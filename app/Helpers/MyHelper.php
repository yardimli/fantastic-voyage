<?php

	namespace App\Helpers;


	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Http;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\Validator;

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
				sleep(1);
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
	}
