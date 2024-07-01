<?php

	namespace App\Http\Controllers;


	use GuzzleHttp\Client;
	use Illuminate\Http\Request;


	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;
	use Illuminate\Support\Facades\Validator;
	use App\Helpers\MyHelper;
	use Illuminate\Support\Facades\Session;


	class DreamStudioController extends Controller
	{
		public function generateImage(Request $request)
		{

			$stabilityApiKey = env('STABILITY_API_KEY', null);
			if (empty($stabilityApiKey)) {
				return response()->json(['error' => 'STABILITY_API_KEY environment variable is not set'], 500);
			}

			$filename   = $request->input('filename', (string)Str::uuid() . '.png');
			$outputFile = storage_path("app/public/story_images/" . $filename);

			$baseUrl = 'https://api.stability.ai';
			$url     = "$baseUrl/v1/generation/stable-diffusion-xl-beta-v2-2-2/text-to-image";

			// Use request input or set default values
			$text                 = $request->input('text', 'A lighthouse on a cliff');
			$cfg_scale            = $request->input('cfg_scale', 7);
			$clip_guidance_preset = $request->input('clip_guidance_preset', 'FAST_BLUE');
			$height               = $request->input('height', 512);
			$width                = $request->input('width', 512);
			$samples              = $request->input('samples', 1);
			$steps                = $request->input('steps', 50);

			$request_data = [
				'headers' => [
					'Content-Type'  => 'application/json',
					'Accept'        => 'image/png',
					'Authorization' => "Bearer $stabilityApiKey"
				],
				'json'    => [
					'text_prompts'         => [
						[
							'text'   => $text,
							"weight" => 1
						],
						[
							'text'   => 'ugly, tiling, poorly drawn hands, poorly drawn feet, poorly drawn face, out of frame, extra limbs, disfigured, deformed, body out of frame, bad anatomy, watermark, signature, cut off, low contrast, underexposed, overexposed, bad art, beginner, amateur, distorted face',
							"weight" => -1
						],
					],
					'cfg_scale'            => $cfg_scale,
					'clip_guidance_preset' => $clip_guidance_preset,
					'height'               => (int)$height,
					'width'                => (int)$width,
					'samples'              => (int)$samples,
					'steps'                => (int)$steps,
					'style_preset'         => $request->input('story_image_type', 'fantasy-art') //3d-model analog-film anime cinematic comic-book digital-art enhance fantasy-art isometric line-art low-poly modeling-compound neon-punk origami photographic pixel-art tile-texture
				],
				'sink'    => $outputFile
			];
			Log::info('request_data', $request_data);

			$client   = new Client();
			$response = $client->request('POST', $url, $request_data);

			if ($response->getStatusCode() == 200) {

				//add into TokenUsage table with user_id
				$token_usage                    = new TokenUsage();
				$token_usage->user_id           = Auth::user()->id;
				$token_usage->completion_tokens = 1000;
				$token_usage->usage_type        = 'Image Generation';
				$token_usage->product_name      = 'DREAMSTUDIO';
				$token_usage->save();

				//resize image and save to storage using gd
				$image = imagecreatefrompng($outputFile);
				$image = imagescale($image, 128);
				imagejpeg($image, str_replace('.png', '_128.jpg', $outputFile));

				//resize image and save to storage using gd
				$image = imagecreatefrompng($outputFile);
				$image = imagescale($image, 380);
				imagejpeg($image, str_replace('.png', '_380.jpg', $outputFile));

				return response()->json(['success' => 'Image generated successfully', 'output_file' => $filename]);
			} else {
				return response()->json(['error' => 'Error generating image'], $response->getStatusCode());
			}
		}
	}
