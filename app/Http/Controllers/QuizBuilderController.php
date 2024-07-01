<?php

	namespace App\Http\Controllers;

	use App\Models\ApiRequest;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Http;

	use App\Models\ImageSearchCache;
	use App\Models\Activity;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Str;

	use App\Models\ActivityData;

	class QuizBuilderController extends Controller
	{

		public function fetchVoices(Request $request)
		{
			if (!Auth::user()) {
				return response()->json([
					'error' => 'You must be logged in to access this resource.',
				]);
			}

			$url = 'https://api.elevenlabs.io/v1/voices';

			$response = ApiRequest::where('url', $url)->get();

			if ($response->count() === 0) {
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'accept: application/json',
					'xi-api-key: ' . env('ELEVENLABS_API_KEY'),
				));

				$response = curl_exec($ch);

				if (curl_errno($ch)) {
					echo 'Fetch error: ' . curl_error($ch);
				}

				curl_close($ch);

				// Save the response to the database
				$insert = ApiRequest::create([
					'url' => $url,
					'results' => $response,
				]);

				return $response;


			} else {
				return $response->first()->results;
			}
		}

		public function convertTextToSpeech(Request $request)
		{
			if (!Auth::user()) {
				return response()->json([
					'error' => 'You must be logged in to access this resource.',
				]);
			}

			$voiceId = $request->voice_id;
			$text = $request->text;

			$url = 'https://api.elevenlabs.io/v1/text-to-speech/' . $voiceId;

			$postFields = json_encode([
				'text' => $text,
				'model_id' => 'eleven_multilingual_v2',
				'voice_settings' => [
					'stability' => 0.5,
					'similarity_boost' => 0.5
				]
			]);
			$response = ApiRequest::where('url', $url)->where('post_data', $postFields)->get();

			if ($response->count() === 0) {


				$headers = [
					'Content-Type: application/json',
					'accept: audio/mpeg',
					'xi-api-key: ' . env('ELEVENLABS_API_KEY'), // Make sure you replace this with your actual key
				];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$result = curl_exec($ch);

				if (curl_errno($ch)) {
					echo 'Error:' . curl_error($ch);
				}
				curl_close($ch);

				// Save the file into local storage. Replace 'file.mp3' with your desired file name
				$filename = Str::random(10) . '-' . $voiceId . '.mp3';
				$audioPath = 'public/question_audio/' . $filename;
				Storage::put($audioPath, $result);

				// Get the file path and url
				$filePath = Storage::disk('public')->path('question_audio/' . $filename);
				$fileUrl = Storage::disk('public')->url('question_audio/' . $filename);

				// Save the response to the database
				$insert = ApiRequest::create([
					'url' => $url,
					'post_data' => $postFields,
					'results' => 'File saved to ' . $filePath,
					'file_name' => $filename,
				]);

				return response()->json([
					'url' => $url,
					'file_path' => $filePath,
					'file_url' => $fileUrl,
					'audio_path' => '/storage/question_audio/' . $filename,
				]);

			} else {
				$filename = $response->first()->file_name;
//				$filename = basename($fileSaveTo);
				$filePath = Storage::disk('public')->path('question_audio/' . $filename);
				$fileUrl = Storage::disk('public')->url('question_audio/' . $filename);

				return response()->json([
					'url' => $url,
					'file_path' => $filePath,
					'file_url' => $fileUrl,
					'audio_path' => '/storage/question_audio/' . $filename,
				]);
			}
		}


		public function quizImageSearch(Request $request)
		{
			if (!Auth::user()) {
				return response()->json([
					'error' => 'You must be logged in to access this resource.',
				]);
			}

			$search_query = $request->input('query');
			$page = $request->input('page') ?? 1;
			$perPage = 18;

			$apiPage = (int)floor((($page - 1) * $perPage) / 80) + 1; // This will be the API page we start fetching from
			$nextApiPage = (int)floor((($page * $perPage) - 1) / 80) + 1; // This will be the next API page we might need to fetch from if images spill over to the next API page

			$apiStart = ($page - 1) * $perPage % 80;
			$apiEnd = $apiStart + $perPage;

			$response = ImageSearchCache::where('query', $search_query)
				->whereBetween('page', [$apiPage, $nextApiPage])
				->orderBy('page')
				->get();

			if ($response->count() === 0 || $response->last()->page < $nextApiPage) {
				// Get images data from API
				$getResponse = Http::withHeaders([
					'Authorization' => env('PEXELS_KEY')
				])->get('https://api.pexels.com/v1/search', [
					'query' => $search_query,
					'size' => 'small',
					'page' => $nextApiPage,
					'per_page' => 80,
				]);

				if ($getResponse->successful()) {
					$newEntry = ImageSearchCache::create([
						'query' => $search_query,
						'page' => $nextApiPage,
						'per_page' => 80,
						'result' => json_encode($getResponse->json()),
					]);
					$response->push($newEntry);
				}
			}

			// Combine the results
			$combinedResult = [];
			foreach ($response as $cache) {
				$combinedResult = array_merge($combinedResult, json_decode($cache->result, true)['photos']);
			}

			// Only take the images required for the page
			$images = array_slice($combinedResult, $apiStart, $perPage);
			return response()->json($images);
		}

		public function show_quizBuilder(Request $request, $activity_id = null)
		{
			if (!Auth::user()) {
				return redirect()->route('login');
			}

			$user = $request->user();
			$user_id = $user->id ?? -1;

			if ($activity_id == null) {
				$activity = new Activity();
				$activity->title = 'New Activity';
				$activity->user_id = $user_id;
				// Save the new activity
				$activity->save();

				// Redirect to the quiz-builder route,
				// passing through the new activity's id
				return redirect()->route('quiz-builder', [ 'activity_id' => $activity->id]);
			} else {
				$activity = Activity::find($activity_id);
				$title = $activity->title ?? "";

				$find_json_data = ActivityData::where('user_id', $user_id)
					->where('activity_id', $activity_id)
					->orderBy('id', 'desc')
					->first();
				$json_data = '[]';
				$category = '';
				$grade = '';
				$language = '';
				if ($find_json_data) {
					$json_data = $find_json_data->json_data;
					$category = $find_json_data->category;
					$grade = $find_json_data->grade;
					$language = $find_json_data->language;
				}

				$quiz_json = '{
			"title": "' . $title . '",
			"user_id": "' . $user_id . '",
		  "category": "' . $category . '",
		  "grade": "' . $grade . '",
		  "language": "' . $language . '",
		  "data_json": ' . $json_data . '
				}';

				return view('template.quiz-builder', compact('user_id', 'quiz_json', 'activity_id'));
			}
		}

		public function quizBuildJson(Request $request)
		{
			if (!Auth::user()) {
				return response()->json([
					'error' => 'You must be logged in to access this resource.',
				]);
			}

			$user = $request->user();
			$user_id = $user->id ?? -1;
			$title = $request->input('title') ?? "";
			$activity_id = $request->input('activity_id') ?? "";
			$category = $request->input('category') ?? "";
			$grade = $request->input('grade') ?? "";
			$language = $request->input('language') ?? "";
			$questions = $request->input('questions') ?? "";
			$article = $request->input('article') ?? "";
			$articleImg = $request->input('articleImg') ?? "";
			$articleAudio = $request->input('articleAudio') ?? "";
			$articleAudioText = $request->input('articleAudioText') ?? "";
			$articleAudioVoice = $request->input('articleAudioVoice') ?? "";
			$json_data['article'] = $article;
			$json_data['articleAudio'] = $articleAudio;
			$json_data['articleAudioText'] = $articleAudioText;
			$json_data['articleAudioVoice'] = $articleAudioVoice;

			$articleFilename = $this->createFileName() . '.png';
			if (strpos($articleImg, 'https') !== false) {
				$this->downloadImageFromUrl($articleImg, $articleFilename);
				$json_data['articleImg'] = '/storage/quiz_images/' . $articleFilename;
			} else if (strpos($articleImg, 'base64') !== false) {
				// Remove "data:image/png;base64,"
				$data = str_replace('data:image/png;base64,', '', $articleImg);
				Storage::disk('public')->put('quiz_images/' . $articleFilename, base64_decode($data));
				$json_data['articleImg'] = '/storage/quiz_images/' . $articleFilename;
			} else {
				$json_data['articleImg'] = $articleImg;
			}

			foreach ($questions as &$question) {
				$qImg = $question['image'];
				$qFilename = $this->createFileName() . '.png';
				if (strpos($qImg, 'https') !== false) {
					$this->downloadImageFromUrl($qImg, $qFilename);
					$question['image'] = '/storage/quiz_images/' . $qFilename;
				} else if (strpos($qImg, 'base64') !== false) {
					// Remove "data:image/png;base64,"
					$data = str_replace('data:image/png;base64,', '', $qImg);
					Storage::disk('public')->put('quiz_images/' . $qFilename, base64_decode($data));
					$question['image'] = '/storage/quiz_images/' . $qFilename;
				}

				foreach ($question['answers'] as &$answer) {
					$aImg = $answer['image'];
					$aFilename = $this->createFileName() . '.png';
					if (strpos($aImg, 'https') !== false) {
						$this->downloadImageFromUrl($aImg, $aFilename);
						$answer['image'] = '/storage/quiz_images/' . $aFilename;
					} else if (strpos($aImg, 'base64') !== false) {
						// Remove "data:image/png;base64,"
						$data = str_replace('data:image/png;base64,', '', $aImg);
						Storage::disk('public')->put('quiz_images/' . $aFilename, base64_decode($data));
						$answer['image'] = '/storage/quiz_images/' . $aFilename;
					}
				}
			}

			$json_data['questions'] = $questions;

			if ($user_id !== "" && $activity_id !== "") {
				$activity_update = Activity::find($activity_id);
				$activity_update->title = $title;
				$activity_update->save();

				$quiz = ActivityData::create([
					'user_id' => $user_id,
					'activity_id' => (int)$activity_id,
					'category' => $category,
					'grade' => $grade,
					'language' => $language,
					'json_data' => json_encode($json_data),
				]);
				$message = __('default.Quiz saved successfully');
			} else {
				$message = __('default.Error');
			}
			return $message;
		}

		function createFileName()
		{
			$part1 = Str::random(4);
			$part2 = Str::random(4);
			$part3 = Str::random(4);

			$filename = $part1 . '-' . $part2 . '-' . $part3;

			return $filename;
		}

		public function downloadImageFromUrl($url, $fileName)
		{
			if (!Auth::user()) {
				return response()->json([
					'error' => 'You must be logged in to access this resource.',
				]);
			}

			$contents = file_get_contents($url);

			// Define the storage path
			$storagePath = 'public/quiz_images/';

			// Check if the directory exists and is writable
			if (!File::isDirectory($storagePath)) {
				// Use Storage facade to create the directory
				Storage::makeDirectory($storagePath, 755, true, true);
			}

			// Use Storage facade to save the image
			Storage::put($storagePath . $fileName, $contents);
		}


		public function quizItemBuildJson(Request $request)
		{
			if (!Auth::user()) {
				return response()->json([
					'error' => 'You must be logged in to access this resource.',
				]);
			}

			$user = $request->user();
			$user_id = $user->id ?? -1;
			$title = $request->input('title') ?? "";
			$activity_id = $request->input('activity_id') ?? "";
			$category = $request->input('category') ?? "";
			$grade = $request->input('grade') ?? "";
			$language = $request->input('language') ?? "";
			$data_json = $request->input('data_json') ?? "";

			foreach ($data_json as &$item) {
				if (isset($item['image'])) {
					$itemImg = $item['image'];
					$itemFilename = $this->createFileName() . '.png';
					if (strpos($itemImg, 'https') !== false) {
						$this->downloadImageFromUrl($itemImg, $itemFilename);
						$item['image'] = '/storage/quiz_images/' . $itemFilename;
					} else if (strpos($itemImg, 'base64') !== false) {
						// Remove "data:image/png;base64,"
						$data = str_replace('data:image/png;base64,', '', $itemImg);
						Storage::disk('public')->put('quiz_images/' . $itemFilename, base64_decode($data));
						$item['image'] = '/storage/quiz_images/' . $itemFilename;
					}
				}
			}

			if ($user_id !== "" && $activity_id !== "") {
				$activity_update = Activity::find($activity_id);
				$activity_update->title = $title;
				$activity_update->save();

				$quiz = ActivityData::create([
					'user_id' => $user_id,
					'activity_id' => (int)$activity_id,
					'category' => $category,
					'grade' => $grade,
					'language' => $language,
					'json_data' => json_encode($data_json),
				]);
				$message = __('default.Quiz saved successfully');
			} else {
				$message = __('default.Error');
			}
			return $message;
		}

		public function quizUpload(Request $request, $file_type)
		{
			if (!Auth::user()) {
				return response()->json([
					'error' => 'You must be logged in to access this resource.'
				]);
			}

			if ($file_type == 'image') {
				if ($request->hasFile('file')) {
					$file = $request->file('file');
					$filename = $file->getClientOriginalName();
					$path = public_path('storage/quiz_images/');
					$file->move($path, $filename);

					return response()->json(['message' => 'File uploaded', 'path' => '/storage/quiz_images/' . $filename]);
				}

				return response()->json(['message' => 'No image was found.']);
			} else if ($file_type == 'audio') {
				if ($request->hasFile('file')) {
					$file = $request->file('file');
					$filename = $file->getClientOriginalName();
					$path = public_path('storage/question_audio/');
					$file->move($path, $filename);
					$fileUrl = Storage::disk('public')->url('question_audio/' . $filename);

					return response()->json(['message' => 'File uploaded', 'path' => '/storage/question_audio/' . $filename, 'file_url' => $fileUrl]);
				}

				return response()->json(['message' => 'No audio was found.']);

			}

		}

		public function quizActivities(Request $request)
		{
			if (!Auth::user()) {
				return redirect()->route('login');
			}

			$user = $request->user();
			$user_id = $user->id ?? -1;
			$activities = Activity::where('user_id', $user_id)
				->where('is_deleted', 0)
				->get();
			return view('template.quiz-activities', compact('user_id', 'activities'));
		}

		public function quizActivitiesAction(Request $request, $action, $id)
		{
			if (!Auth::user()) {
				return redirect()->route('login');
			}

			$user = $request->user();
			$user_id = $user->id ?? -1;
			if ($action == 'clone') {
				$activity = Activity::find($id);
				$newActivity = $activity->replicate();
				$newActivity->title = $activity->title . '_Clone';
				$newActivity->save();

				$quiz = ActivityData::where('user_id', $user_id)
					->where('activity_id', $id)
					->orderBy('id', 'desc')
					->first();
				if ($quiz) {
					$newQuiz = $quiz->replicate();
					$newQuiz->activity_id = $newActivity->id;
					$newQuiz->save();
				}


			} else if ($action == 'delete') {
				$activity = Activity::find($id);
				$activity->is_deleted = 1;
				$activity->save();
			}
			return redirect()->route('quiz-activities');
		}

		public function quiz_templates(Request $request)
		{
			if (!Auth::user()) {
				return redirect()->route('login');
			}

			$quizzes = [
				[
					'name' => 'Quiz',
					'description' => 'A series of multiple choice questions. Tap the correct answer to proceed.',
					'image' => '/assets/images/quiz-icons/quiz.png',
				],
			];

			return view('template.quiz-activities', compact('quizzes'));
		}

		public function setTheme(Request $request)
		{
			if (!Auth::user()) {
				return response()->json([
					                        'error' => 'You must be logged in to access this resource.',
				                        ]);
			}

			$user = $request->user();
			$user_id = $user->id ?? -1;
			$activity_id = $request->input('activity_id') ?? "";
			$theme = $request->input('theme') ?? "beach";


			if ($user_id !== "" && $activity_id !== "") {
				$activity_update = Activity::find($activity_id);
				$activity_update->theme = $theme;
				$activity_update->save();

				$rst = 'success';
			} else {
				$rst = 'error';
			}
			return $rst;
		}
	}
