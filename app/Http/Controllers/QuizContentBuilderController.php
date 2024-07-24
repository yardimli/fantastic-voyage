<?php

	namespace App\Http\Controllers;

	use App\Helpers\MyHelper;
	use App\Models\ApiRequest;
	use App\Models\StoryData;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Http;

	use App\Models\ImageSearchCache;
	use App\Models\Activity;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Str;
	use Illuminate\Support\Facades\Log;
	use App\Models\ActivityData;

	class QuizContentBuilderController extends Controller
	{

		public function createNextInvestigation(Request $request)
		{
			$user = $request->user();
			$user_id = $user->id ?? 0;
			$activity_id = $request->input('activity_id') ?? 0;
			$answer_index = $request->input('answer_index') ?? 0;
			$chapter_text = $request->input('chapter_text') ?? '';
			$choice = $request->input('choice') ?? '';
			$step = $request->input('step') ?? 1;

			//check if $activity_id exists
			$activity = Activity::where('id', $activity_id)
				->first();
			if ($activity === null) {
				return response()->json(['error' => 'Activity not found']);
			}
			//check if step story_data exists
			$story_data = StoryData::where('activity_id', $activity_id)
				->where('step', $step)
				->first();
			if ($story_data === null) {
				return response()->json(['error' => 'Story Data not found']);
			}
			//update step choice
			$story_data->choice = $choice;
			$story_data->save();

			$story_history = '';
			$story_steps = StoryData::where('activity_id', $activity_id)
				->orderBy('step', 'asc')
				->get();
			foreach ($story_steps as $story_step) {
				$story_history .= $story_step->chapter_text . "\n\n";
				$story_history .= 'Choice: ' . $story_step->choice . "\n\n";
			}
			$next_step = $step + 1;

			//check if $next_step story_data exists
			$story_data = StoryData::where('activity_id', $activity_id)
				->where('step', $next_step)
				->first();

			if ($story_data !== null) {
				return response()->json(array(
					'title' => $activity->title,
					'image' => $story_data->image,
					'chapter_text' => $story_data->chapter_text,
					'chapter_voice' => $story_data->chapter_voice,
					'choices' => json_decode($story_data->choices),
					'step' => $next_step,
					'success' => true
				));
			}

			$language = $activity->language;
			$voice_id = $activity->voice_id;

			$rst = $this->buildInvestigationContent($activity->prompt, $activity->title, $story_history, $next_step, $language);

			if ($rst['success'] === false) {
				return response()->json(['success' => false, 'error' => 'Failed to create story', 'rst' => $rst]);
			}

			if (!empty($voice_id)) {
				$tts_results_text = MyHelper::eleven_labs_text_to_speech($voice_id, $rst['chapterText']);
			} else {
				$tts_results_text = ['audio_path' => '', 'filename' => ''];
			}

			foreach ($rst['choices'] as $key => $choice) {
				if (!empty($voice_id)) {
					$tts_results = MyHelper::eleven_labs_text_to_speech($voice_id, $choice['text']);
				} else {
					$tts_results = ['audio_path' => '', 'filename' => ''];
				}
				$rst['choices'][$key]['audio'] = $tts_results['audio_path'];
			}

			$story = new StoryData();
			$story->user_id = $user_id;
			$story->activity_id = $activity_id;
			$story->step = $next_step;
			$story->title = $activity->title;
			$story->image = $rst['coverImage'];
			$story->chapter_text = $rst['chapterText'];
			$story->chapter_voice = $tts_results_text['audio_path'];
			$story->choices = json_encode($rst['choices']);
			$story->choice = '';
			$story->language = $language;
			$story->json_data = json_encode($rst['returnJSON']);
			$story->save();

			return response()->json(array(
				'title' => $rst['title'],
				'image' => $rst['coverImage'],
				'chapter_text' => $rst['chapterText'],
				'chapter_voice' => $tts_results_text['audio_path'],
				'choices' => $rst['choices'],
				'step' => $next_step,
				'success' => true
			));


		}

		public function createNextCliffhanger(Request $request)
		{
			$user = $request->user();
			$user_id = $user->id ?? 0;
			$activity_id = $request->input('activity_id') ?? 0;
			$answer_index = $request->input('answer_index') ?? 0;
			$choice = $request->input('choice') ?? '';
			$step = $request->input('step') ?? 1;

			//check if $activity_id exists
			$activity = Activity::where('id', $activity_id)
				->first();
			if ($activity === null) {
				return response()->json(['error' => 'Activity not found']);
			}

			$next_step = $step + 1;
			//check if $next_step story_data exists
			$story_data = StoryData::where('activity_id', $activity_id)
				->where('step', $next_step)
				->first();

			$total_steps = StoryData::where('activity_id', $activity_id)
				->count();

			if ($story_data !== null) {
				return response()->json(array(
					'title' => $activity->title,
					'image' => $story_data->image,
					'choices' => json_decode($story_data->choices),
					'choice' => $story_data->choice,
					'step' => $next_step,
					'total_steps' => $total_steps,
					'success' => true
				));
			}

			//check if step story_data exists
			$story_data = StoryData::where('activity_id', $activity_id)
				->where('step', $step)
				->first();
			if ($story_data === null) {
				return response()->json(['error' => 'Story Data not found']);
			}

			//update step choice
			$story_data->choice = $choice;
			$story_data->save();

			$story_history = '';
			$story_steps = StoryData::where('activity_id', $activity_id)
				->orderBy('step', 'asc')
				->get();
			foreach ($story_steps as $story_step) {
				$story_history .= $story_step->choice . "\n\n";
			}

			$language = $activity->language;
			$voice_id = $activity->voice_id;

			$rst = $this->buildCliffhangerContent($activity->prompt, $activity->title, $story_history, $next_step, $language);

			if ($rst['success'] === false) {
				return response()->json(['success' => false, 'error' => 'Failed to create story', 'rst' => $rst]);
			}

			foreach ($rst['choices'] as $key => $choice) {
				if (!empty($voice_id)) {
					$tts_results = MyHelper::eleven_labs_text_to_speech($voice_id, $choice['text']);
				} else {
					$tts_results = ['audio_path' => '', 'filename' => ''];
				}
				$rst['choices'][$key]['audio'] = $tts_results['audio_path'];
			}

			$story = new StoryData();
			$story->user_id = $user_id;
			$story->activity_id = $activity_id;
			$story->step = $next_step;
			$story->title = $activity->title;
			$story->image = $rst['coverImage'];
			$story->choices = json_encode($rst['choices']);
			$story->choice = '';
			$story->language = $language;
			$story->json_data = json_encode($rst['returnJSON']);
			$story->save();


			$total_steps = StoryData::where('activity_id', $activity_id)
				->count();

			return response()->json(array(
				'title' => $rst['title'],
				'image' => $rst['coverImage'],
				'choices' => $rst['choices'],
				'choice' => '',
				'step' => $next_step,
				'total_steps' => $total_steps,
				'success' => true
			));


		}

		public function addNewVoyage(Request $request)
		{
			$user = $request->user();
			$user_id = $user->id ?? 0;

			$content_type = $request->input('content_type') ?? "quiz";
			$user_content = $request->input('user_content') ?? "";
			$language = $request->input('language') ?? "English";
			$voice_id = $request->input('voice_id') ?? "";
			$quantity = $request->input('quantity') ?? 1;
			$new_num = $request->input('next_num') ?? 1;
			$new_id = $request->input('next_id') ?? 1;
			$return_json = $request->input('return_json') ?? false;

			Log::info('---------------Log addNewVoyage--------------------------');
			Log::info('content-type: ' . $content_type . ' user_content: ' . $user_content . ' language: ' . $language . ' voice_id: ' . $voice_id . ' quantity: ' . $quantity . ' new_num: ' . $new_num . ' new_id: ' . $new_id . ' return_json: ' . $return_json);

			if ($content_type === 'cliffhanger') {
				$rst = $this->buildCliffhangerContent($user_content, '', '', 1, $language);

				if ($rst['success'] === false) {
					return response()->json(['success' => false, 'error' => 'Failed to create story', 'rst' => $rst]);
				}

				$activity = new Activity();
				$activity->user_id = $user_id;
				$activity->title = $rst['title'];
				$activity->cover_image = $rst['coverImage'];
				$activity->keywords = '';
				$activity->question_count = $quantity;
				$activity->voice_id = $voice_id;
				$activity->language = $language;
				$activity->prompt = $user_content;
				$activity->type = 'cliffhanger';
				$activity->theme = 'moon';
				$activity->is_deleted = 0;
				$activity->save();

				$insertId = $activity->id;

				foreach ($rst['choices'] as $key => $choice) {
					if (!empty($voice_id)) {
						$tts_results = MyHelper::eleven_labs_text_to_speech($voice_id, $choice['text']);
					} else {
						$tts_results = ['audio_path' => '', 'filename' => ''];
					}
					$rst['choices'][$key]['audio'] = $tts_results['audio_path'];
				}


				$story = new StoryData();
				$story->user_id = $user_id;
				$story->activity_id = $insertId;
				$story->step = 1;
				$story->title = $rst['title'];
				$story->image = $rst['coverImage'];
				$story->choices = json_encode($rst['choices']);
				$story->choice = '';
				$story->language = $language;
				$story->json_data = json_encode($rst['returnJSON']);
				$story->save();

				$rst['activity_id'] = $insertId;
				$rst['success'] = true;

				return response()->json($rst);
			} else if ($content_type === 'investigation') {
				$rst = $this->buildInvestigationContent($user_content, '', '', 1, $language);

				if ($rst['success'] === false) {
					return response()->json(['success' => false, 'error' => 'Failed to create story', 'rst' => $rst]);
				}

				$activity = new Activity();
				$activity->user_id = $user_id;
				$activity->title = $rst['title'];
				$activity->cover_image = $rst['coverImage'];
				$activity->keywords = '';
				$activity->question_count = $quantity;
				$activity->voice_id = $voice_id;
				$activity->language = $language;
				$activity->prompt = $user_content;
				$activity->type = 'story';
				$activity->theme = 'space';
				$activity->is_deleted = 0;
				$activity->save();

				$insertId = $activity->id;

				if (!empty($voice_id)) {
					$tts_results_text = MyHelper::eleven_labs_text_to_speech($voice_id, $rst['chapterText']);
				} else {
					$tts_results_text = ['audio_path' => '', 'filename' => ''];
				}

				foreach ($rst['choices'] as $key => $choice) {
					if (!empty($voice_id)) {
						$tts_results = MyHelper::eleven_labs_text_to_speech($voice_id, $choice['text']);
					} else {
						$tts_results = ['audio_path' => '', 'filename' => ''];
					}
					$rst['choices'][$key]['audio'] = $tts_results['audio_path'];
				}


				$story = new StoryData();
				$story->user_id = $user_id;
				$story->activity_id = $insertId;
				$story->step = 1;
				$story->title = $rst['title'];
				$story->image = $rst['coverImage'];
				$story->chapter_text = $rst['chapterText'];
				$story->chapter_voice = $tts_results_text['audio_path'];
				$story->choices = json_encode($rst['choices']);
				$story->choice = '';
				$story->language = $language;
				$story->json_data = json_encode($rst['returnJSON']);
				$story->save();

				$rst['activity_id'] = $insertId;
				$rst['success'] = true;

				return response()->json($rst);
			} else
				if ($content_type === 'quiz') {

					$html = '';
					$rst = $this->buildQuizContent($user_content, $language, $voice_id, $new_num, $new_id, $quantity);

					$html .= $rst['html'];
					if ($return_json) {
						//create activity
						$activity = new Activity();
						$activity->user_id = $user_id;
						$activity->type = 'quiz';
						$activity->title = $rst['title'];
						$activity->cover_image = $rst['coverImage'];
						$activity->keywords = $rst['keywords'];
						$activity->prompt = $user_content;
						$activity->language = $language;
						$activity->voice_id = $voice_id;
						$activity->theme = 'space';
						$activity->is_deleted = 0;
						$activity->save();

						$insertId = $activity->id;

						$activityData = new ActivityData();
						$activityData->user_id = $user_id;
						$activityData->activity_id = $insertId;
						$activityData->language = $language;
						$activityData->json_data = json_encode(array('questions' => $rst['returnJSON']));
						$activityData->save();

						$rst['activity_id'] = $insertId;

						return response()->json($rst);
					} else {
						return $html;
					}
				}
		}

		//-------------------------------------------------------------------------------------------
		public function buildQuizContent($user_content, $language, $voice_id, $next_num, $next_id, $quantity)
		{
			$prompt = "Create a quiz with questions and answers set about the following topic: " . $user_content . " , only one answer is correct. Written in " . $language . ". Number of Questions:" . $quantity . "";

			$schema_str = file_get_contents(public_path('texts/quiz.json'));

			$chat_messages = [];
			$chat_messages[] = [
				'role' => 'system',
				'content' => 'You are an expert teacher.'
			];
			$chat_messages[] = [
				'role' => 'user',
				'content' => $prompt
			];

			$schema = json_decode($schema_str, true);

			$complete_json = $this->openAI_question($chat_messages, $schema, 1, 3680, 'gpt-4o'); // 'gpt-4o' 'gpt-3.5-turbo');
			$complete_rst = json_decode($complete_json['complete'], true);
//			Log::info('---------------Log complete_rst--------------------------');
//			Log::info($chat_messages);
//			Log::info($schema);
//			Log::info($complete_rst);
//			$content = $complete_rst['choices'][0]['message']['function_call']['arguments'];
			$content = $complete_rst['choices'][0]['message']['tool_calls'][0]['function']['arguments'];
			Log::info($content);
			$validateJson = MyHelper::validateJson($content);
			if ($validateJson == "Valid JSON") {
				$content_array = json_decode($content, true);
			} else {
				$content_array = null;
			}
			Log::info('---------------Log content_array--------------------------');
			Log::info($content_array);
			$rst = array('html' => 'failed', 'returnText' => '');
			$returnHtml = '';
			$timestamp = time();
			$returnJSON = [];
			$quizTitle = $content_array['quizTitle'];
			$quizImageKeywords = $content_array['quizImageKeywords'];
			$quizImagePrompt = $content_array['quizImagePrompt'];

			$quizCoverImageFilename = 'quiz_image_cover_' . $timestamp . '.png';
			MyHelper::replicate_create_image_sd3($quizCoverImageFilename, $quizImageKeywords . ', ' . $quizImagePrompt);

			if ($content_array !== null) {
				foreach ($content_array['quizSet'] as $index => &$quiz) {
					$num = $next_num + $index;
					$id = $next_id + $index;

//					$image_filename = 'quiz_image_' . $timestamp . '_Q' . $id . '.png';
//					MyHelper::stability_ai_create_image($image_filename, $quizImageKeywords . ', ' . $quiz['image_prompt']);

					$question_array = [];
					$question_array['id'] = 'Q' . $id;
					$question_array['text'] = $quiz['question'];
					$question_array['image'] = null; //'/storage/quiz_images/' . $image_filename;
					$question_array['image_prompt'] = $quiz['image_prompt'];
					$question_array['audio'] = null;
					$question_array['audio_tts'] = null;
					$question_array['voice_id'] = $voice_id;
					$question_array['answers'] = [];


					$question = $quiz['question'];
					$alphabet = range('A', 'F');
					foreach ($quiz['answers'] as $key => &$answers) {
						$answers['id'] = 'Q' . $id . 'A' . ($key + 1);
						$answers['letter'] = $alphabet[$key % 6];
						$answers['audio'] = null;
						$answers['audio_tts'] = null;
						$answers['voice_id'] = $voice_id;
						$answers['image'] = null;

						$question_array['answers'][] = [
							'id' => $answers['id'],
							'letter' => $answers['letter'],
							'text' => $answers['answer_text'],
							'isCorrect' => $answers['isCorrect'],
							'audio' => $answers['audio'],
							'audio_tts' => $answers['audio_tts'],
							'image' => $answers['image']
						];
					}

					$returnJSON[] = $question_array;

					$returnHtml .= view('quiz.quiz-question-set')->with([
						'question_number' => $num,
						'question' => ['id' => 'Q' . $id, 'image' => '', 'audio' => '', 'text' => $question],
						'answers' => $quiz['answers']
					])->render();
				}
//					array_push($skipQuestion, $question['text']);
				$rst = array('html' => $returnHtml, 'returnText' => $question, 'title' => $quizTitle, 'keywords' => $quizImageKeywords, 'coverImage' => '/storage/quiz_images/' . $quizCoverImageFilename, 'returnJSON' => $returnJSON);

			}

			return $rst;
		}


		//-------------------------------------------------------------------------------------------
		public function buildInvestigationContent($user_content, $story_title, $prev_chapter, $step, $language)
		{
			$prompt = "Create an interactive story with the story text and four, 2-3 sentence choices of how the story might continue. Use the following topic: " . $user_content . ". The story can be fact or fiction.";

			$schema_str = file_get_contents(public_path('texts/investigation-first-chapter.json'));
			if ($prev_chapter !== '') {

				$prompt = "Create an interactive story with the story text and four, 2-3 sentence choices of how the story might continue. Use the following topic: " . $user_content . ". This is the " . $step . ". step of the story. The previous texts and choices are:\n\n" . $prev_chapter . ".";

				$schema_str = file_get_contents(public_path('texts/investigation-second-chapter.json'));
			}

			$prompt .= "\n\nThe story should be written in " . $language;


			$chat_messages = [];
			$chat_messages[] = [
				'role' => 'system',
				'content' => 'You are an expert story teller.'
			];
			$chat_messages[] = [
				'role' => 'user',
				'content' => $prompt
			];

			$schema = json_decode($schema_str, true);

			$complete_json = $this->openAI_question($chat_messages, $schema, 1, 3680, 'gpt-4o'); // 'gpt-4o' 'gpt-3.5-turbo');
			$complete_rst = json_decode($complete_json['complete'], true);
//			Log::info('---------------Log complete_rst--------------------------');
//			Log::info($chat_messages);
//			Log::info($schema);
//			Log::info($complete_rst);
			$content = $complete_rst['choices'][0]['message']['tool_calls'][0]['function']['arguments'];
			$content_array = json_decode($content, true);
			Log::info($content_array);

			if ($prev_chapter === '') {
				$storyTitle = $content_array['storyTitle'];
			} else {
				$storyTitle = $story_title;
			}

			$chapterText = $content_array['chapterText'];
			$chapterImagePrompt = $content_array['chapterImagePrompt'];

			if (empty($content_array['chapterChoices'])) {
				return array('title' => $storyTitle, 'chapterText' => $chapterText, 'coverImage' => $chapterImagePrompt, 'choices' => [], 'returnJSON' => $complete_json, 'success' => false);
			}

			$chapterChoices = [];
			for ($i = 0; $i < 4; $i++) {
				$chapterChoices[] = array('text' => $content_array['chapterChoices'][$i]['choiceText'], 'audio' => '');
			}

			$timestamp = time();

			$chapterCoverImageFilename = 'chapter_image_cover_' . $timestamp . '.png';
			MyHelper::replicate_create_image_sd3($chapterCoverImageFilename, $chapterImagePrompt);

			return array('title' => $storyTitle, 'chapterText' => $chapterText, 'coverImage' => '/storage/quiz_images/' . $chapterCoverImageFilename, 'choices' => $chapterChoices, 'returnJSON' => $complete_json, 'success' => true);
		}


		//-------------------------------------------------------------------------------------------
		public function buildCliffhangerContent($user_content, $story_title, $prev_chapter, $step, $language)
		{
			$prompt = "Story prompt:" . $user_content . ".\nStory language: " . $language ;

			$schema_str = file_get_contents(public_path('texts/cliffhanger-first-chapter.json'));
			if ($prev_chapter !== '') {

				$prompt = "Story Title: " . $story_title . ".\nStory Prompt: " . $user_content . ".\nThe Story so far:\n" . $prev_chapter . ".\nStory language:" . $language;

				$schema_str = file_get_contents(public_path('texts/cliffhanger-second-chapter.json'));
			}

			$prompt .= "\n\nThe story should be written in " . $language;


			$chat_messages = [];
			$chat_messages[] = [
				'role' => 'system',
				'content' => 'You are an expert story teller.'
			];
			$chat_messages[] = [
				'role' => 'user',
				'content' => $prompt
			];

			$schema = json_decode($schema_str, true);

			$complete_json = $this->openAI_question($chat_messages, $schema, 1, 3680, 'gpt-4o'); // 'gpt-4o' 'gpt-3.5-turbo');
			$complete_rst = json_decode($complete_json['complete'], true);
//			Log::info('---------------Log complete_rst--------------------------');
//			Log::info($chat_messages);
//			Log::info($schema);
//			Log::info($complete_rst);
			$content = $complete_rst['choices'][0]['message']['tool_calls'][0]['function']['arguments'];
			$content_array = json_decode($content, true);
			Log::info($content_array);

			if ($prev_chapter === '') {
				$storyTitle = $content_array['storyTitle'];
				$chapterImagePrompt = $content_array['storyImagePrompt'];
			} else {
				$storyTitle = $story_title;
				$chapterImagePrompt = '';
			}


			if (empty($content_array['chapterChoices'])) {
				return array('title' => $storyTitle, 'coverImage' => $chapterImagePrompt, 'choices' => [], 'returnJSON' => $complete_json, 'success' => false);
			}
			$timestamp = time();

			$chapterChoices = [];
			for ($i = 0; $i < count($content_array['chapterChoices']); $i++) {
				$chapterImageFilename = 'chapter_image_' . $timestamp . '_C' . $step . 'A' . ($i + 1) . '.png';
				MyHelper::replicate_create_image_sd3($chapterImageFilename, $content_array['chapterChoices'][$i]['choiceImagePrompt']);

				$chapterChoices[] = array('text' => $content_array['chapterChoices'][$i]['choiceText'], 'audio' => '', 'image' => '/storage/quiz_images/' . $chapterImageFilename);
			}

			if ($prev_chapter === '') {
				$chapterCoverImageFilename = 'chapter_image_cover_' . $timestamp . '.png';
				MyHelper::replicate_create_image_sd3($chapterCoverImageFilename, $chapterImagePrompt);
				$chapterCoverImageFilepath = '/storage/quiz_images/' . $chapterCoverImageFilename;
			} else
			{
				$chapterCoverImageFilename = '';
				$chapterCoverImageFilepath = '';
			}

			return array('title' => $storyTitle, 'coverImage' => $chapterCoverImageFilepath, 'choices' => $chapterChoices, 'returnJSON' => $complete_json, 'success' => true);
		}

		//-------------------------------------------------------------------------------------------
		public static function openAI_question($messages, $functions, $temperature, $max_tokens, $gpt_engine)
		{
			set_time_limit(300);

			$tool_name = 'auto';
//			if ($use_gpt === 'anthropic') {
//				$tool_name = $schema['function']['name'];
//			}

			$data = array(
				'model' => $gpt_engine, // 'gpt-3.5-turbo-1106', 'gpt-4',
				'messages' => $messages,
				'tools' => [$functions],
				'tool_choice' => $tool_name,
				'temperature' => $temperature,
				'max_tokens' => $max_tokens,
				'top_p' => 1,
				'frequency_penalty' => 0,
				'presence_penalty' => 0,
				'n' => 1,
				'stream' => false,
				'stop' => "" //"\n"
			);

//			Log::info('==================openAI_question=====================');
//			Log::info($data);

			session_write_close();
			$txt = '';
			$completion_tokens = 0;

//			Log::info('openAI_question: ');
//			Log::info($data);

			$gpt_base_url = env('OPEN_AI_API_BASE');
			$gpt_api_key = env('OPEN_AI_API_KEY');


			//dont stream
			$post_json = json_encode($data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $gpt_base_url . '/chat/completions');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);

			$headers = array();
			$headers[] = 'Content-Type: application/json';
			$headers[] = "Authorization: Bearer " . $gpt_api_key;
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$complete = curl_exec($ch);
			Log::info('==================Log complete=====================');
			Log::info($complete);
			if (curl_errno($ch)) {
				Log::info('Error:' . curl_error($ch));
			}
			curl_close($ch);
			session_start();

//			$completeArray = json_decode($complete, true);
//			Log::info('completeArray: ');
//			Log::info($completeArray);
//			$content = $completeArray['choices'][0]['message']['content'];
//			$completion_tokens = $completeArray['usage']['completion_tokens'];
//			$prompt_tokens = $completeArray['usage']['prompt_tokens'];
			return array('complete' => $complete);
		}

	}
