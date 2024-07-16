<?php

	namespace App\Helpers;


	use App\Models\ActivityData;
	use App\Models\ApiRequest;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Http;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Support\Str;
	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\RequestException;

	class MyHelper
	{


		//-------------------------------------------------------------------------
		// Send the message to the OpenAI API

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

		//-------------------------------------------------------------------------

		public static function fetchVoices(Request $request)
		{
//			if (!Auth::user()) {
//				return response()->json([
//					'error' => 'You must be logged in to access this resource.',
//				]);
//			}

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

		//-------------------------------------------------------------------------
		//generateContent for the beginning of the story


		public static function addStarterPackage($user_id)
		{
		}

		public static function replicate_upscale_image($image_filename, $scale, $restore_faces = false)
		{
			Log::info('replicate_upscale_image image with file: ' . $image_filename . ' and scale: ' . $scale . ' and restore_faces: ' . $restore_faces);

			$save_path = storage_path('app/public/story_images/' . $image_filename);
			$image_url = 'https://fantastic-voyage.com' . Storage::url("public/story_images/" . $image_filename);
			Log::info('image_url: ' . $image_url);
			Log::info('save_path: ' . $save_path);

			Session::save();

			$url = 'https://api.replicate.com/v1/predictions';
			$ch = curl_init($url);

			$payload = json_encode(array(
				'version' => '42fed1c4974146d4d2414e2be2c5277c7fcf05fcc3a73abf41610695738c1d7b',
				'input' => array(
					'image' => $image_url,
					'scale' => $scale,
					'face_enhance' => $restore_faces
				)
			));

			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Token ' . env('REPLICATE_TOKEN'),
				'Content-Type: application/json'
			));

			# Return response instead of printing.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			# Send request.
			$result = curl_exec($ch);

			if (curl_exec($ch) === false) {
				Log::info('replicate_upscale_image error: ');
				Log::info(curl_error($ch));
			}

			curl_close($ch);
			# Print response.

			$result_json = json_decode($result, true);
			Log::info('replicate_upscale_image result: ');
			Log::info($result_json);

			//wait for url
			$check_url = $result_json['urls']['get'];

			$found_image = false;
			$check_count = 0;

			while (!$found_image && $check_count < 14) {
				sleep(4);
				$check_count++;

				$ch = curl_init($check_url);

				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Token ' . env('REPLICATE_TOKEN'),
					'Content-Type: application/json'
				));

				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				# Return response instead of printing.
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				# Send request.

				$result = curl_exec($ch);

				curl_close($ch);
				# Print response.
				$upscale_result_json = json_decode($result, true);

				Log::info('replicate_upscale_image check result: ');
				Log::info($upscale_result_json);
				//check if output key exists
				if (array_key_exists('output', $upscale_result_json)) {
					if ($upscale_result_json['output'] != null) {
						file_put_contents(str_replace('.png', '_1024.png', $save_path), file_get_contents($upscale_result_json['output']));
						$found_image = true;

						$image = imagecreatefrompng(str_replace('.png', '_1024.png', $save_path));
						$image = imagescale($image, 768);
						imagejpeg($image, str_replace('.png', '_768.jpg', $save_path));
					}
				}
			}

			Session::start();


		}

		public static function replicate_create_image_sdxl_lightning($image_filename, $prompt)
		{
			Log::info('replicate_create_image_sdxl_lightning image with file: ' . $image_filename . ' and prompt: ' . $prompt);

			//make sure folder exists
			$save_folder = Storage::disk('public')->path('quiz_images');
			if (!file_exists($save_folder)) {
				mkdir($save_folder, 0777, true);
			}

			$save_path = Storage::disk('public')->path('quiz_images/' . $image_filename);
			$image_url = Storage::disk('public')->url('quiz_images/' . $image_filename);

			Log::info('image_url: ' . $image_url);
			Log::info('save_path: ' . $save_path);

			Session::save();

			$url = 'https://api.replicate.com/v1/predictions';
			$ch = curl_init($url);

			$payload = json_encode(array(
				'version' => '5f24084160c9089501c1b3545d9be3c27883ae2239b6f412990e82d4a6210f8f',
				'input' => array(
					'prompt' => $prompt,
					'width' => 1024,
					'height' => 1024,
					'scheduler' => 'K_EULER',
					'guidance_scale' => 0,
					'negative_prompt' => 'worst quality, low quality',
					'num_inference_steps' => 4,
					'disable_safety_checker' => true,
				)
			));

			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Token ' . env('REPLICATE_TOKEN'),
				'Content-Type: application/json'
			));

			# Return response instead of printing.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			# Send request.
			$result = curl_exec($ch);

			if (curl_exec($ch) === false) {
				Log::info('replicate_create_image_sdxl_lightning error: ');
				Log::info(curl_error($ch));
			}

			curl_close($ch);
			# Print response.

			$result_json = json_decode($result, true);
			Log::info('replicate_create_image_sdxl_lightning result: ');
			Log::info($result_json);

			//wait for url
			$check_url = $result_json['urls']['get'];

			$found_image = false;
			$check_count = 0;

			while (!$found_image && $check_count < 14) {
				sleep(4);
				$check_count++;

				$ch = curl_init($check_url);

				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Token ' . env('REPLICATE_TOKEN'),
					'Content-Type: application/json'
				));

				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				# Return response instead of printing.
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				# Send request.

				$result = curl_exec($ch);

				curl_close($ch);
				# Print response.
				$upscale_result_json = json_decode($result, true);

				Log::info('replicate_create_image_sdxl_lightning check result: ');
				Log::info($upscale_result_json);
				//check if output key exists
				if (array_key_exists('output', $upscale_result_json)) {
					if ($upscale_result_json['output'] != null) {
						if ($upscale_result_json['output'][0] != null) {
							file_put_contents($save_path, file_get_contents($upscale_result_json['output'][0]));
							$found_image = true;
							//save the image as a jpg
							$image = imagecreatefrompng($save_path);
							imagejpeg($image, str_replace('.png', '.jpg', $save_path));

							$image = imagecreatefrompng($save_path);
							$image = imagescale($image, 512);
							imagejpeg($image, str_replace('.png', '-512.jpg', $save_path));
						}
					}
				}
			}
			Session::start();
		}

		public static function replicate_create_image_proteus($image_filename, $prompt)
		{
			Log::info('replicate_create_image_proteus image with file: ' . $image_filename . ' and prompt: ' . $prompt);

			//make sure folder exists
			$save_folder = Storage::disk('public')->path('quiz_images');
			if (!file_exists($save_folder)) {
				mkdir($save_folder, 0777, true);
			}

			$save_path = Storage::disk('public')->path('quiz_images/' . $image_filename);
			$image_url = Storage::disk('public')->url('quiz_images/' . $image_filename);

			Log::info('image_url: ' . $image_url);
			Log::info('save_path: ' . $save_path);

			Session::save();

			$url = 'https://api.replicate.com/v1/predictions';
			$ch = curl_init($url);

			$payload = json_encode(array(
				'version' => '06775cd262843edbde5abab958abdbb65a0a6b58ca301c9fd78fa55c775fc019',
				'input' => array(
					'prompt' => $prompt,
					'width' => 1024,
					'height' => 1024,
					'scheduler' => 'KarrasDPM',
					'guidance_scale' => 7.5,
					'negative_prompt' => 'worst quality, low quality',
					'num_inference_steps' => 20,
					'disable_safety_checker' => true,
					'apply_watermark' => false,
				)
			));

			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Token ' . env('REPLICATE_TOKEN'),
				'Content-Type: application/json'
			));

			# Return response instead of printing.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			# Send request.
			$result = curl_exec($ch);

			if (curl_exec($ch) === false) {
				Log::info('replicate_create_image_proteus error: ');
				Log::info(curl_error($ch));
			}

			curl_close($ch);
			# Print response.

			$result_json = json_decode($result, true);
			Log::info('replicate_create_image_proteus result: ');
			Log::info($result_json);

			//wait for url
			$check_url = $result_json['urls']['get'];

			$found_image = false;
			$check_count = 0;

			while (!$found_image && $check_count < 14) {
				sleep(4);
				$check_count++;

				$ch = curl_init($check_url);

				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Token ' . env('REPLICATE_TOKEN'),
					'Content-Type: application/json'
				));

				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				# Return response instead of printing.
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				# Send request.

				$result = curl_exec($ch);

				curl_close($ch);
				# Print response.
				$upscale_result_json = json_decode($result, true);

				Log::info('replicate_create_image_proteus check result: ');
				Log::info($upscale_result_json);
				//check if output key exists
				if (array_key_exists('output', $upscale_result_json)) {
					if ($upscale_result_json['output'] != null) {
						if ($upscale_result_json['output'][0] != null) {
							file_put_contents($save_path, file_get_contents($upscale_result_json['output'][0]));
							$found_image = true;
							//save the image as a jpg
							$image = imagecreatefrompng($save_path);
							imagejpeg($image, str_replace('.png', '.jpg', $save_path));

							$image = imagecreatefrompng($save_path);
							$image = imagescale($image, 512);
							imagejpeg($image, str_replace('.png', '-512.jpg', $save_path));
						}
					}
				}
			}
			Session::start();
		}

		public static function replicate_create_image_playground($image_filename, $prompt)
		{
			Log::info('replicate_create_image_playground image with file: ' . $image_filename . ' and prompt: ' . $prompt);

			//make sure folder exists
			$save_folder = Storage::disk('public')->path('quiz_images');
			if (!file_exists($save_folder)) {
				mkdir($save_folder, 0777, true);
			}

			$save_path = Storage::disk('public')->path('quiz_images/' . $image_filename);
			$image_url = Storage::disk('public')->url('quiz_images/' . $image_filename);

			Log::info('image_url: ' . $image_url);
			Log::info('save_path: ' . $save_path);

			Session::save();

			$url = 'https://api.replicate.com/v1/predictions';
			$ch = curl_init($url);

			$payload = json_encode(array(
				'version' => 'a45f82a1382bed5c7aeb861dac7c7d191b0fdf74d8d57c4a0e6ed7d4d0bf7d24',
				'input' => array(
					'prompt' => $prompt,
					'negative_prompt' => 'worst quality, low quality',
					'width' => 1024,
					'height' => 1024,
					'scheduler' => 'DPMSolver++',
					'num_inference_steps' => 25,
					'guidance_scale' => 3,
					'disable_safety_checker' => true,
					'apply_watermark' => false,
				)
			));

			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Token ' . env('REPLICATE_TOKEN'),
				'Content-Type: application/json'
			));

			# Return response instead of printing.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			# Send request.
			$result = curl_exec($ch);

			if (curl_exec($ch) === false) {
				Log::info('replicate_create_image_playground error: ');
				Log::info(curl_error($ch));
			}

			curl_close($ch);
			# Print response.

			$result_json = json_decode($result, true);
			Log::info('replicate_create_image_playground result: ');
			Log::info($result_json);

			//wait for url
			$check_url = $result_json['urls']['get'];

			$found_image = false;
			$check_count = 0;

			while (!$found_image && $check_count < 14) {
				sleep(3);
				$check_count++;

				$ch = curl_init($check_url);

				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Token ' . env('REPLICATE_TOKEN'),
					'Content-Type: application/json'
				));

				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				# Return response instead of printing.
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				# Send request.

				$result = curl_exec($ch);

				curl_close($ch);
				# Print response.
				$upscale_result_json = json_decode($result, true);

				Log::info('replicate_create_image_playground check result: ');
				Log::info($upscale_result_json);
				//check if output key exists
				if (array_key_exists('output', $upscale_result_json)) {
					if ($upscale_result_json['output'] != null) {
						if ($upscale_result_json['output'][0] != null) {
							file_put_contents($save_path, file_get_contents($upscale_result_json['output'][0]));
							$found_image = true;
							//save the image as a jpg
							$image = imagecreatefrompng($save_path);
							imagejpeg($image, str_replace('.png', '.jpg', $save_path));

							$image = imagecreatefrompng($save_path);
							$image = imagescale($image, 512);
							imagejpeg($image, str_replace('.png', '-512.jpg', $save_path));
						}
					}
				}
			}
			Session::start();
		}

		public static function replicate_create_image_sd3($image_filename, $prompt)
		{
			Log::info('replicate_create_image_sd3 image with file: ' . $image_filename . ' and prompt: ' . $prompt);

			//make sure folder exists
			$save_folder = Storage::disk('public')->path('quiz_images');
			if (!file_exists($save_folder)) {
				mkdir($save_folder, 0777, true);
			}

			$save_path = Storage::disk('public')->path('quiz_images/' . $image_filename);
			$image_url = Storage::disk('public')->url('quiz_images/' . $image_filename);

			Log::info('image_url: ' . $image_url);
			Log::info('save_path: ' . $save_path);

			Session::save();

			$url = 'https://api.replicate.com/v1/models/stability-ai/stable-diffusion-3/predictions';
			$ch = curl_init($url);

			$payload = json_encode(array(
				'input' => array(
					'prompt' => $prompt,
					'aspect_ratio' => '1:1',
					'output_format' => 'png',
					'cfg' => 3.5,
					'steps' => 28,
				)
			));

			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Token ' . env('REPLICATE_TOKEN'),
				'Content-Type: application/json'
			));

			# Return response instead of printing.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			# Send request.
			$result = curl_exec($ch);

			if (curl_exec($ch) === false) {
				Log::info('replicate_create_image_sd3 error: ');
				Log::info(curl_error($ch));
			}

			curl_close($ch);
			# Print response.

			$result_json = json_decode($result, true);
			Log::info('replicate_create_image_sd3 result: ');
			Log::info($result_json);

			//wait for url
			$check_url = $result_json['urls']['get'];

			$found_image = false;
			$check_count = 0;

			while (!$found_image && $check_count < 14) {
				sleep(3);
				$check_count++;

				$ch = curl_init($check_url);

				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Token ' . env('REPLICATE_TOKEN'),
					'Content-Type: application/json'
				));

				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				# Return response instead of printing.
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				# Send request.

				$result = curl_exec($ch);

				curl_close($ch);
				# Print response.
				$upscale_result_json = json_decode($result, true);

				Log::info('replicate_create_image_sd3 check result: ');
				Log::info($upscale_result_json);
				//check if output key exists
				if (array_key_exists('output', $upscale_result_json)) {
					if ($upscale_result_json['output'] != null) {
						if ($upscale_result_json['output'][0] != null) {
							file_put_contents($save_path, file_get_contents($upscale_result_json['output'][0]));
							$found_image = true;
							//save the image as a jpg
							$image = imagecreatefrompng($save_path);
							imagejpeg($image, str_replace('.png', '.jpg', $save_path));

							$image = imagecreatefrompng($save_path);
							$image = imagescale($image, 512);
							imagejpeg($image, str_replace('.png', '-512.jpg', $save_path));
						}
					}
				}
			}
			Session::start();
		}

		public static function eleven_labs_text_to_speech($voice_id, $text)
		{
			$url = 'https://api.elevenlabs.io/v1/text-to-speech/' . $voice_id;

			$postFields = json_encode([
				'text' => $text,
				'model_id' => 'eleven_multilingual_v2',
				'voice_settings' => [
					'stability' => 0.5,
					'similarity_boost' => 0.5
				]
			]);
//			Log::info('eleven_labs_text_to_speech url: ' . $url);
//			Log::info('eleven_labs_text_to_speech postFields: ' . $postFields);
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
				$filename = Str::random(10) . '-' . $voice_id . '.mp3';
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

				return [
					'url' => $url,
					'file_path' => $filePath,
					'file_url' => $fileUrl,
					'audio_path' => '/storage/question_audio/' . $filename,
					'filename' => $filename,
				];
			} else {
				$filename = $response->first()->file_name;
//				$filename = basename($fileSaveTo);
				$filePath = Storage::disk('public')->path('question_audio/' . $filename);
				$fileUrl = Storage::disk('public')->url('question_audio/' . $filename);

				return [
					'url' => $url,
					'file_path' => $filePath,
					'file_url' => $fileUrl,
					'audio_path' => '/storage/question_audio/' . $filename,
					'filename' => $filename,
				];
			}
		}


		public static function stability_ai_create_image($image_filename, $prompt)
		{

			Log::info('stability_ai_create_image image with file: ' . $image_filename . ' and prompt: ' . $prompt);

			$stabilityApiKey = env('STABILITY_API_KEY', null);
			if (empty($stabilityApiKey)) {
				Log::error('STABILITY_API_KEY environment variable is not set');
				return response()->json(['error' => 'STABILITY_API_KEY environment variable is not set'], 500);
			}

			//make sure folder exists
			$save_folder = Storage::disk('public')->path('quiz_images');
			if (!file_exists($save_folder)) {
				mkdir($save_folder, 0777, true);
			}

			$save_path = Storage::disk('public')->path('quiz_images/' . $image_filename);
			$image_url = Storage::disk('public')->url('quiz_images/' . $image_filename);

			Log::info('image_url: ' . $image_url);
			Log::info('save_path: ' . $save_path);

			$url = "https://api.stability.ai/v2beta/stable-image/generate/sd3";

			$request_data = [
				'headers' => [
					'Accept' => 'image/*',
					'Authorization' => 'Bearer ' . $stabilityApiKey
				],
				'multipart' => [
					[
						'name' => 'prompt',
						'contents' => $prompt
					],
					[
						'name' => 'mode',
						'contents' => 'text-to-image'
					],
					[
						'name' => 'model',
						'contents' => 'sd3-large-turbo' //sd3-large, sd3-medium, sd3-large-turbo
					],
//					[
//						'name' => 'negative_prompt',
//						'contents' => 'ugly, tiling, poorly drawn hands, poorly drawn feet, poorly drawn face, out of frame, extra limbs, disfigured, deformed, body out of frame, bad anatomy, watermark, signature, cut off, low contrast, underexposed, overexposed, bad art, beginner, amateur, distorted face'
//					]
				],
				'sink' => $save_path
			];

			$client = new Client();
			try {
				$response = $client->request('POST', $url, $request_data);

				$image = imagecreatefrompng($save_path);
				imagejpeg($image, str_replace('.png', '.jpg', $save_path));

				$image = imagecreatefrompng($save_path);
				$image = imagescale($image, 512);
				imagejpeg($image, str_replace('.png', '-512.jpg', $save_path));

				return response()->json(['success' => 'Image generated successfully', 'output_file' => $save_path], 200);
			} catch (GuzzleException $e) {
				return response()->json(['error' => 'Error generating image', 'message' => $e->getMessage()], 500);
			}
		}
	}
