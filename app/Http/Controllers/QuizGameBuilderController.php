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

			$rst = $this->buildGameUI($user_id, $activity_id, 'game-layout.display-game-ui', $question);
			return $rst;
		}

		public function inPage(Request $request, $activity_id)
		{

			$user = $request->user();
			$user_id = $user->id ?? 0;
			$activity_id = $activity_id ?? -1;

			$rst = $this->buildGameUI($user_id, $activity_id, 'game-layout.display-game-ui-in-page');
			return $rst;
		}

		public function buildGameUI($user_id, $activity_id, $view = 'game-layout.display-game-ui', $question = null)
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
			$quiz_type = $activity->type;
			$current_theme = $activity->theme ?? 'beach';

			$themes = ['beach', 'jungle', 'mid-autumn', 'moon', 'rabbit', 'space', 'taipei'];

			$type_description = 'A series of multiple choice questions. Tap the correct answer to proceed.';

			return view($view, compact('json_data', 'title', 'type_description', 'current_theme', 'themes', 'activity_id', 'question'));
		}

		//-------------------------------------------------------------------------


		public function storyIndex(Request $request, $activity_id, $step = null)
		{
			$user = $request->user();
			$user_id = $user->id ?? 0;
			$activity_id = $activity_id ?? -1;

			$rst = $this->buildStoryUI($user_id, $activity_id, 'game-layout.display-story-ui', $step ?? 1);
			return $rst;
		}

		public function storyInPage(Request $request, $activity_id)
		{

			$user = $request->user();
			$user_id = $user->id ?? 0;
			$activity_id = $activity_id ?? -1;

			$rst = $this->buildStoryUI($user_id, $activity_id, 'game-layout.display-story-ui-in-page');
			return $rst;
		}


		public function buildStoryUI($user_id, $activity_id, $view = 'game-layout.display-story-ui', $step = 1)
		{

			$story = StoryData::where('user_id', $user_id)
				->where('activity_id', $activity_id)
				->where('step', $step)
				->orderBy('id', 'desc')
				->first();

			$activity = Activity::where('user_id', $user_id)
				->where('id', $activity_id)
				->first();

			$title = $activity->title;
			$title = str_replace("'", "\'", $title);
			$title = str_replace("\n", "\\n", $title);

			$image = $story->image;

			$chapter_text = $story->chapter_text;
			$chapter_text = str_replace("\n", "\\n", $chapter_text);
			$chapter_text = str_replace("'", "\'", $chapter_text);

			$chapter_voice = $story->chapter_voice;

			$choices = $story->choices;
			$choices = str_replace("'", "\'", $choices);
			$choices = str_replace("\n", "<br>", $choices);

			$current_theme = $activity->theme ?? 'beach';

			$themes = ['beach', 'jungle', 'mid-autumn', 'moon', 'rabbit', 'space', 'taipei'];

			$type_description = 'A series of multiple choice stories. Tap the choice to proceed.';

			return view($view, compact('title', 'image', 'chapter_text', 'chapter_voice', 'type_description', 'current_theme', 'themes', 'activity_id', 'choices', 'step'));
		}


	}
