<?php

	namespace App\Http\Controllers;

	use App\Helpers\MyHelper;
	use App\Models\ApiRequest;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Http;

	use App\Models\ImageSearchCache;
	use App\Models\Activity;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Str;

	use App\Models\ActivityData;

	class QuizBuilderController extends Controller
	{

		public function createQuestionImage(Request $request)
		{
			$user_id = Auth::user()->id ?? 0;
			$question_id = $request->question_id ?? 0;
			$activity_id = $request->activity_id ?? 0;
			$update_field = $request->update_field ?? '';
			$image_filename = '';

			Log::info('createQuestionImage: ' . $question_id . ' -- ' . $activity_id . ' -- ' . $update_field);

			$activity = Activity::where('id', $activity_id)
				->first();

			if ($activity) {
				$quizImageKeywords = $activity['keywords'] ?? '';

				$activity_update = ActivityData::where('activity_id', $activity_id)
					->where('user_id', $user_id)
					->orderBy('id', 'desc')
					->first();

				if ($activity_update) {
					$json_data = json_decode($activity_update->json_data, true);
					$timestamp = time();
					$image_filename = '';

					if ($json_data && isset($json_data['questions'])) {
						foreach ($json_data['questions'] as &$question) {
							if ($question['id'] === $question_id) {
								if ($update_field === 'question') {

									if ($question['image'] !== '' && $question['image'] !== null) {
										$image_filename = $question['image'];
									} else {
										$image_filename = 'quiz_image_' . $timestamp . '_Q' . $question_id . '.png';
										MyHelper::replicate_create_image_sd3($image_filename, $quizImageKeywords . ', ' . $question['image_prompt']);

										$question['image'] = '/storage/quiz_images/' . $image_filename;
									}
								}
								break;
							}
						}

						$activity_update->json_data = json_encode($json_data);
						$activity_update->save();
					}
					return response()->json(array('image_path' => '/storage/quiz_images/' . $image_filename));
				} else {
					return response()->json([
						'error' => 'Activity data not found.',
					]);
				}
			} else {
				return response()->json([
					'error' => 'Activity not found.',
				]);
			}
		}

		public function convertTextToSpeech(Request $request)
		{
//			if (!Auth::user()) {
//				return response()->json([
//					'error' => 'You must be logged in to access this resource.',
//				]);
//			}

			$user_id = Auth::user()->id ?? 0;

			$voice_id = $request->voice_id;
			$text = $request->text;
			$question_id = $request->question_id ?? 0;
			$answer_id = $request->answer_id ?? 0;
			$activity_id = $request->activity_id ?? 0;
			$update_field = $request->update_field ?? '';

			Log::info('convertTextToSpeech: ' . $voice_id . ' -- ' . $text . ' -- ' . $question_id . ' -- ' . $answer_id . ' -- ' . $activity_id . ' -- ' . $update_field);

			if (!empty($voice_id)) {
				$tts_results = MyHelper::eleven_labs_text_to_speech($voice_id, $text);
			} else {
				$tts_results = ['audio_path' => '', 'filename' => ''];
			}

			if ($activity_id !== 0) {
				$activity_update = ActivityData::where('activity_id', $activity_id)
					->where('user_id', $user_id)
					->orderBy('id', 'desc')
					->first();

				if ($activity_update) {
					$json_data = json_decode($activity_update->json_data, true);

					if ($json_data && isset($json_data['questions'])) {
						foreach ($json_data['questions'] as &$question) {
							if ($question['id'] === $question_id) {
								if ($update_field === 'question') {
									$question['audio'] = '/storage/question_audio/' . $tts_results['filename'];
									$question['audio_tts'] = $text;
								} elseif ($update_field === 'answer' && $answer_id) {
									foreach ($question['answers'] as &$answer) {
										if ($answer['id'] === $answer_id) {
											$answer['audio'] = '/storage/question_audio/' . $tts_results['filename'];
											$answer['audio_tts'] = $text;
											break;
										}
									}
								}
								break;
							}
						}

						$activity_update->json_data = json_encode($json_data);
						$activity_update->save();
					}
				}
			}

			return response()->json($tts_results);

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
			$user_id = $user->id ?? 0;

			if ($activity_id == null) {
				$activity = new Activity();
				$activity->title = 'New Activity';
				$activity->user_id = $user_id;
				// Save the new activity
				$activity->save();

				// Redirect to the quiz-builder route,
				// passing through the new activity's id
				return redirect()->route('quiz-builder', ['activity_id' => $activity->id]);
			} else {
				$activity = Activity::find($activity_id);
				$title = $activity->title ?? "";
				$prompt = $activity->prompt ?? "";

				$find_json_data = ActivityData::where('user_id', $user_id)
					->where('activity_id', $activity_id)
					->orderBy('id', 'desc')
					->first();
				$json_data = '[]';
				$language = '';
				if ($find_json_data) {
					$json_data = $find_json_data->json_data;
					$language = $find_json_data->language;
				}

				$quiz_json = '{
			"title": "' . $title . '",
			"prompt": "' . $prompt . '",
			"user_id": "' . $user_id . '",
		  "language": "' . $language . '",
		  "data_json": ' . $json_data . '
				}';

				return view('quiz.quiz-builder', compact('user_id', 'quiz_json', 'activity_id'));
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
			$user_id = $user->id ?? 0;
			$title = $request->input('title') ?? "";
			$activity_id = $request->input('activity_id') ?? "";
			$language = $request->input('language') ?? "";
			$questions = $request->input('questions') ?? "";

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
			$user_id = $user->id ?? 0;
			$title = $request->input('title') ?? "";
			$activity_id = $request->input('activity_id') ?? "";
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

		public function quizActivities(Request $request, $type = 'quiz')
		{
//			if (!Auth::user()) {
//				return redirect()->route('login');
//			}

			$user = $request->user();
			$user_id = $user->id ?? 0;
			$activities = Activity::where('user_id', $user_id)
				->where('is_deleted', 0)
				->where('type', $type)
				->get();

			//loop all $activity['cover_image'] and check if they exist
			foreach ($activities as $activity) {
				if (!empty($activity['cover_image'])) {
					$cover_image = base_path(str_replace('/storage/quiz_images/', 'storage/app/public/quiz_images/', $activity['cover_image']));

					$cover_image_jpg = str_replace('.png', '.jpg', $cover_image);
					if (!file_exists($cover_image_jpg) && file_exists($cover_image)) {

						//save the image as a jpg
						$image = imagecreatefrompng($cover_image);
						imagejpeg($image, str_replace('.png', '.jpg', $cover_image));

						$image = imagecreatefrompng($cover_image);
						$image = imagescale($image, 512);
						imagejpeg($image, str_replace('.png', '-512.jpg', $cover_image));
					}
				}
			}

			if ($type === 'quiz') {
				$type_string = 'Quizzes';
			} else if ($type === 'investigation') {
				$type_string = 'Investigations';
			} elseif ($type === 'two-path-adventure') {
				$type_string = 'Two Path Adventures';
			} elseif ($type === 'story') {
				$type_string = 'Stories';
			} elseif ($type === 'game') {
				$type_string = 'Games';
			} elseif ($type === 'activity') {
				$type_string = 'Activities';
			} else {
				$type_string = 'Activities';
			}

			return view('quiz.quiz-activities', compact('user_id', 'activities', 'type', 'type_string'));
		}

		public function quizActivitiesAction(Request $request, $action, $id)
		{
			if (!Auth::user()) {
				return redirect()->route('login');
			}

			$user = $request->user();
			$user_id = $user->id ?? 0;
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

			return view('quiz.quiz-activities', compact('quizzes'));
		}

		public function setTheme(Request $request)
		{
//			if (!Auth::user()) {
//				return response()->json([
//					                        'error' => 'You must be logged in to access this resource.',
//				                        ]);
//			}

			$user = $request->user();
			$user_id = $user->id ?? 0;
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
			return response()->json(array('rst' => $rst));
		}
	}
