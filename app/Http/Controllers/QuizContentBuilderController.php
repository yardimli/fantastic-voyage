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


		public function addNewVoyage(Request $request)
		{
			$user = $request->user();
			$user_id = $user->id ?? 0;

			$user_content = $request->input('user_content') ?? "";
			$language = $request->input('language') ?? "English";
			$voice_id = $request->input('voice_id') ?? "";
			$quantity = $request->input('quantity') ?? 1;
			$new_num = $request->input('next_num') ?? 1;
			$new_id = $request->input('next_id') ?? 1;
			$return_json = $request->input('return_json') ?? false;

			Log::info('---------------Log addNewVoyage--------------------------');
			Log::info('user_content: ' . $user_content . ' language: ' . $language . ' voice_id: ' . $voice_id . ' quantity: ' . $quantity . ' new_num: ' . $new_num . ' new_id: ' . $new_id . ' return_json: ' . $return_json);

			$html = '';
			$rst = $this->buildQuizContent($user_content, $language, $voice_id, $new_num, $new_id, $quantity);

			$html .= $rst['html'];
			if ($return_json) {
				//create activity
				$activity = new Activity();
				$activity->user_id = $user_id;
				$activity->title = $rst['title'];
				$activity->cover_image = $rst['coverImage'];
				$activity->keywords = $rst['keywords'];
				$activity->prompt = $user_content;
				$activity->language = $language;
				$activity->voice_id = $voice_id;
				$activity->theme = 'space';
				$activity->type = 'quiz';
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

		//-------------------------------------------------------------------------------------------
		public function buildQuizContent($user_content, $language, $voice_id, $next_num, $next_id, $quantity)
		{
			$prompt = "Create a quiz with questions and answers set about the following topic: " . $user_content . " , only one answer is correct. Written in " . $language . ". Number of Questions with answers to return in the JSON:" . $quantity . "";

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
