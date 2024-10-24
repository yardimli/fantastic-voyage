<?php

	namespace App\Http\Controllers;

	use App\Models\ApiRequest;
	use App\Models\StoryData;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Http;

	use App\Models\ImageSearchCache;
	use App\Models\Activity;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Str;

	use App\Models\ActivityData;

	class QuizGameBuilderController extends Controller
	{
		public function index(Request $request, $activity_id, $question = null)
		{

			$user = $request->user();
			$user_id = $user->id ?? 0;
			$activity_id = $activity_id ?? -1;

			$quiz = ActivityData::where('user_id', $user_id)
				->where('activity_id', $activity_id)
				->orderBy('id', 'desc')
				->first();

			if (!$quiz) {
				$user_id = 0;
			}

			$rst = $this->buildGameUI($user_id, $activity_id, 'game-layout.display-quiz-ui', $question);
			return $rst;
		}

		public function inPage(Request $request, $activity_id)
		{

			$user = $request->user();
			$user_id = $user->id ?? 0;
			$activity_id = $activity_id ?? -1;

			$quiz = ActivityData::where('user_id', $user_id)
				->where('activity_id', $activity_id)
				->orderBy('id', 'desc')
				->first();

			if (!$quiz) {
				$user_id = 0;
				}

			$rst = $this->buildGameUI($user_id, $activity_id, 'game-layout.display-quiz-ui-in-page');
			return $rst;
		}

		public function buildGameUI($user_id, $activity_id, $view = 'game-layout.display-quiz-ui', $question = null)
		{
			if ($user_id === -1) {
				$quiz = ActivityData::where('activity_id', $activity_id)
					->orderBy('id', 'desc')
					->first();
				$activity = Activity::where('id', $activity_id)
					->first();
			} else {
				$quiz = ActivityData::where('user_id', $user_id)
					->where('activity_id', $activity_id)
					->orderBy('id', 'desc')
					->first();

				$activity = Activity::where('user_id', $user_id)
					->where('id', $activity_id)
					->first();
			}

			$json_data = $quiz->json_data;
			$title = $activity->title;
			$current_theme = $activity->theme ?? 'beach';

			$themes = ['beach', 'jungle', 'mid-autumn', 'moon', 'rabbit', 'space', 'taipei'];

			$type_description = 'A series of multiple choice questions. Tap the correct answer to proceed.';

			return view($view, compact('json_data', 'title', 'type_description', 'current_theme', 'themes', 'activity_id', 'question', 'user_id'));
		}
	}
