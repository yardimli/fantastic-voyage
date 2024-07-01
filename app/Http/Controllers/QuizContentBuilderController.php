<?php

	namespace App\Http\Controllers;

	use App\Models\ApiRequest;
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

		public function index(Request $request)
		{
			$user         = $request->user();
			$user_id      = $user->id ?? -1;
			$user_content = $request->input('user_content') ?? "";
			$language     = $request->input('language') ?? "en_US";
			$quantity     = $request->input('quantity') ?? 1;
			$new_num      = $request->input('next_num') ?? 1;
			$new_id       = $request->input('next_id') ?? 1;
			$groupAndItem = $request->input('groupAndItem') ?? [];
//			$skipQuestion = $request->input('skipQuestion') ?? '';

			$html = '';
			$rst = $this->buildQuizContent($user_content, $language, $new_num, $new_id,  $quantity);

			$html .= $rst['html'];
			return $html;
		}

		//		-------------------------------------------------------------------------------------------
		public function buildQuizContent($user_content, $language, $next_num, $next_id, $quantity)
		{
			$prompt = "Create " . $quantity . " quiz question with answers set from this article : " . $user_content . " , only one answer is correct. Written in " . $language . ".";

			$schema = array(
				"type" => "object",
				"properties" => array(
					"quizSet" => array(
						"type" => "array",
						"items" => array(
							"type" => "object",
							"properties" => array(
								"question" => array(
									"type" => "string",
									"description" => "The question of the quiz set."
								),
								"answers" => array(
									"type" => "array",
									"items" => array(
										"type" => "object",
										"properties" => array(
											"text" => array("type" => "string"),
											"isCorrect" => array("type" => "boolean")
										),
									),
								),
							),
						),
					),
				),
			);

			$functions = [
				[
					"name" => "get_content",
					"parameters" => $schema
				]
			];

			$chat_messages   = [];
			$chat_messages[] = [
				'role' => 'system',
				'content' => 'You are an expert teacher.'
			];
			$chat_messages[] = [
				'role' => 'user',
				'content' => $prompt
			];

			$complete_json = $this->openAI_question($chat_messages, $functions, 1, 3680, 'gpt-3.5-turbo');
			$complete_rst  = json_decode($complete_json['complete'], true);
			Log::info('---------------Log complete_rst--------------------------');
			Log::info($complete_rst);
			$content      = $complete_rst['choices'][0]['message']['function_call']['arguments'];
			$validateJson = $this->validateJson($content);
			if ($validateJson == "Valid JSON") {
				$content_array = json_decode($content, true);
			} else {
				// remove the necessary characters for json
				$startPos         = strpos($content, '[');
				$endPos           = strrpos($content, ']');
				$removeTextResult = substr($content, $startPos, $endPos - $startPos + 1);
				$content_array    = json_decode($removeTextResult, true) ?? [];
			}
			Log::info('---------------Log content_array--------------------------');
			Log::info($content_array);
			$rst        = array('html' => 'failed', 'returnText' => '');
			$returnHtml = '';
			if ($content_array !== null) {
				foreach ($content_array['quizSet'] as $index => &$quiz) {
					$num      = $next_num + $index;
					$id       = $next_id + $index;
					$question = $quiz['question'];
					$alphabet = range('A', 'F');
					foreach ($quiz['answers'] as $key => &$answers) {
						$answers['id']     = 'Q' . $id . 'A' . ($key + 1);
						$answers['letter'] = $alphabet[$key % 6];
						$answers['audio']  = $answers['audio_tts'] = $answers['audio_voice'] = null;
						$answers['image']  = '';
					}

					$returnHtml .= view('template.quiz-question-set')->with([
						                                                        'question_number' => $num,
						                                                        'question' => ['id' => 'Q' . $id, 'image' => '', 'audio' => '', 'text' => $question],
						                                                        'answers' => $quiz['answers']
					                                                        ])->render();
				}
//					array_push($skipQuestion, $question['text']);
				$rst = array('html' => $returnHtml, 'returnText' => $question);

			}

			return $rst;
		}

		//		-------------------------------------------------------------------------------------------
		public function quizArticleBuilder(Request $request)
		{

			$user     = $request->user();
			$user_id  = $user->id ?? -1;
			$subject  = $request->input('subject') ?? "";
			$language = $request->input('language') ?? "en_US";


			$prompt = "create an article about : " . $subject . ". Written in " . $language . ".";
			// Define the JSON Schema by creating a schema array
			$schema = array(
				"type" => "object",
				"properties" => array(
					"article" => array(
						"type" => "string",
						"description" => "The article has at least 3 paragraphs. Each paragraph has 5 sentences."
					)
				)
			);

			$functions = [
				[
					"name" => "get_content",
					"parameters" => $schema
				]
			];

			$chat_messages   = [];
			$chat_messages[] = [
				'role' => 'system',
				'content' => 'You are an expert storyteller.'
			];
			$chat_messages[] = [
				'role' => 'user',
				'content' => $prompt
			];


			$complete_json = $this->openAI_question($chat_messages, $functions, 1, 3680, 'gpt-3.5-turbo');
			$complete_rst  = json_decode($complete_json['complete'], true);
			$content       = $complete_rst['choices'][0]['message']['function_call']['arguments'];

			$rst = array('html' => 'Failed', 'returnText' => 'Nothing is created.');
			if ($content !== null) {
				$toArray = json_decode($content, true);
				Log::info('---------------Log Article--------------------------');
				Log::info($toArray['article']);
				$rst = $toArray['article'];
			}

			return $rst;
		}

		//		-------------------------------------------------------------------------------------------

		public static function openAI_question($messages, $functions, $temperature, $max_tokens, $gpt_engine)
		{
			set_time_limit(300);

			$data = array(
				'model' => $gpt_engine, // 'gpt-3.5-turbo-1106', 'gpt-4',
				'messages' => $messages,
				'functions' => $functions,
				'function_call' => ['name' => 'get_content'],
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
			$txt               = '';
			$completion_tokens = 0;

//			Log::info('openAI_question: ');
//			Log::info($data);

			$gpt_base_url = env('OPEN_AI_API_BASE');
			$gpt_api_key  = env('OPEN_AI_API_KEY');


			//dont stream
			$post_json = json_encode($data);
			$ch        = curl_init();
			curl_setopt($ch, CURLOPT_URL, $gpt_base_url . '/chat/completions');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);

			$headers   = array();
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

		//		-------------------------------------------------------------------------------------------

		public static function validateJson($str)
		{

			$error = json_last_error();
			json_decode($str);
			$error = json_last_error();

			switch ($error) {
				case JSON_ERROR_NONE:
					return "Valid JSON";
				case JSON_ERROR_DEPTH:
					return "Maximum stack depth exceeded";
				case JSON_ERROR_STATE_MISMATCH:
					return "Underflow or the modes mismatch";
				case JSON_ERROR_CTRL_CHAR:
					return "Unexpected control character found";
				case JSON_ERROR_SYNTAX:
					return "Syntax error, malformed JSON";
				case JSON_ERROR_UTF8:
					return "Malformed UTF-8 characters, possibly incorrectly encoded";
				default:
					return "Unknown error";
			}
		}
	}
