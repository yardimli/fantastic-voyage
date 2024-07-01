<?php

	namespace App\Http\Controllers;

	use App\Traits\SharedFunction;
	use Illuminate\Auth\Access\AuthorizationException;
	use Illuminate\Http\Request;


	use App\Models\User;

	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;
	use Illuminate\Support\Facades\Validator;
	use App\Helpers\MyHelper;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Validation\Rule;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Validation\ValidationException;


	class UserSettingsController extends Controller
	{
		use SharedFunction;

		//-------------------------------------------------------------------------
		// Index
		public function index(Request $request)
		{

		}


//-------------------------------------------------------------------------
		// Update user password
		public function updatePassword(Request $request)
		{
			// Get the authenticated user
			$user = $request->user();

			// Validate input
			$validator = Validator::make($request->all(), [
				'current_password' => ['nullable', 'string'],
				'new_password' => ['required', 'string', 'min:6', 'confirmed'],
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$current_password = $request->input('current_password') ?? '123456dummy_password';
//dd($user->password, $current_password, Hash::check($current_password, $user->password), Hash::make($current_password));
			// Check if the current password is correct
			if (!Hash::check($current_password, $user->password)) {
				throw ValidationException::withMessages([
					                                        'current_password' => ['Current password is incorrect.'],
				                                        ]);
			}

			// Update user password
			$user->password = Hash::make($request->input('new_password'));
			$user->save();

			// Redirect back with success message
			Session::flash('success', 'Your password has been updated successfully.');
			return redirect()->back();
		}


		//-------------------------------------------------------------------------
		public function userProfile(Request $request)
		{
			// Get the authenticated user
			$user       = $request->user();
			$sub_title  = __('default.dashboard');

			return view('user.profile', compact('user',  'sub_title'));
		}


		//-------------------------------------------------------------------------
		public function wishlist(Request $request)
		{
			// Get the authenticated user
			$user = $request->user();

			return view('user.wishlist', compact('user'));
		}


		//-------------------------------------------------------------------------
		public function switchToParent(Request $request)
		{
			\Session::put('active_account', 'parent');
			\Session::put('login_role', 'parent');
			return redirect()->back();
		}


		public function userScore(Request $request, $score_url, $grade)
		{

			// Get the authenticated user
			$user      = $request->user();
			$sub_title = __('default.score URL Link');
			$language  = $this->get_language();
			$grade_arr = ['K' => 'Kindergarten URL Link', '1' => 'First Grade URL Link', '2' => 'Second Grade URL Link', '3' => 'Third Grade URL Link', '4' => 'Fourth Grade URL Link'];

			$category_lists = [];
			foreach ($grade_arr as $key => $value) {
				if ($grade == __('default.' . $value)) {
					$category_lists['short_grade'] = $key;
					$categories                    = $this->get_categories($key, $language);
					$chart_info                    = $this->get_score_chart_data($key, $language);
					$show_all_chart                = implode(',', $chart_info['show_all_chart']);
					$show_started_chart            = implode(',', $chart_info['show_started_chart']);
					$started_lesson_count          = count($chart_info['show_started_chart']);
					foreach ($categories as &$category) {

						$category_code           = $category['sort_category_code'];
						$lessons_by_category     = $this->get_lessons_by_category_code($category_code, $language, $key);
						$category['lessons']     = $lessons_by_category;
						$category['collapse_id'] = $key . '_' . $category['sort_category_code'];
					}
					$category_lists['categories'] = $categories;
				}
			}

			return view('user.score', compact('user', 'grade', 'category_lists', 'show_all_chart', 'show_started_chart', 'sub_title', 'started_lesson_count', 'score_url'));
		}



		//-------------------------------------------------------------------------
		public function userCourses(Request $request, $courses_url, $grade)
		{
			// Get the authenticated user
			$user       = $request->user();
			$student_id = session('student_id') ?? -1;
			$sub_title  = __('default.My Courses List');

			$language  = $this->get_language();
			$lists     = [];
			$grade_arr = ['K' => 'Kindergarten URL Link', '1' => 'First Grade URL Link', '2' => 'Second Grade URL Link', '3' => 'Third Grade URL Link', '4' => 'Fourth Grade URL Link'];
			foreach ($grade_arr as $key => $value) {
				if ($grade == __('default.' . $value)) {
					$lists['short_grade']     = $key;
					$lists['started_lessons'] = $this->get_started_lessons($student_id, $language, $key, 1, null);
				}
			}

			// Custom sort function


			// Sorting the array based on scores
			usort($lists['started_lessons'], function ($a, $b) {
				return $a->display_progress - $b->display_progress;
			});

			return view('user.courses', compact('user', 'lists', 'sub_title', 'grade', 'courses_url'));
		}


		//-------------------------------------------------------------------------
		// settings

		public function userProgress(Request $request)
		{
			// Get the authenticated user
			$user      = $request->user();
			$sub_title = __('default.Progress');
			$lang      = $this->get_language();
			$grade_arr = ['K' => 'Kindergarten', '1' => 'First Grade', '2' => 'Second Grade', '3' => 'Third Grade', '4' => 'Fourth Grade'];

			$lists = [];
			foreach ($grade_arr as $key => $value) {
				$each_list                = [];
				$each_list['grade']       = __('default.' . $value);
				$each_list['short_grade'] = $key;
				$categories               = $this->get_categories($key, $lang);
				foreach ($categories as &$category) {
					$category_code           = $category['sort_category_code'];
					$lessons_by_category     = $this->get_lessons_by_category_code($category_code, $lang, $key);
					$category['lessons']     = $lessons_by_category;
					$category['collapse_id'] = $key . '_' . $category['sort_category_code'];
				}
				$each_list['categories'] = $categories;

				array_push($lists, $each_list);
			}

			return view('user.progress', compact('user', 'lists', 'sub_title'));
		}

		public function userSettings(Request $request)
		{
			// Get the authenticated user
			$user       = $request->user();
			$sub_title  = __('default.dashboard');

			return view('user.settings', compact('user',  'sub_title'));
		}


		public function userRecommendations(Request $request)
		{
			// Get the authenticated user
			$user      = $request->user();
			$user_id   = session('student_id') ?? -1;
			$lang      = $this->get_language();
			$sub_title = __('default.Recommendations');

			$lessons_over_50          = $this->get_started_lessons($user_id, $lang, null, 50, null);
			$recommended_lesson_count = 0;
			$recommended              = array();
			if (count($lessons_over_50) > 0) {
				foreach ($lessons_over_50 as $each_lesson) {
					$lesson_ext_link          = $each_lesson->ext_link;
					$recommended_lessons      = $this->get_harder_recommend_lesson($lesson_ext_link);
					$recommended_lesson_count += count($recommended_lessons);
					foreach ($recommended_lessons as $recommended_lesson) {
						if (!in_array($recommended_lesson['lesson_id'], $recommended)) {
							array_push($recommended, $recommended_lesson);
						}
					}
				}
			}

			$message = 'success';
			if ($recommended_lesson_count == 0) {
				$message = __('default.Please try to do some lessons to get recommended lessons.');
			}

			$gradeOrder = ['K', '1', '2', '3', '4'];

			usort($recommended, function ($a, $b) use ($gradeOrder) {
				$gradeA = array_search($a['grade'], $gradeOrder);
				$gradeB = array_search($b['grade'], $gradeOrder);
				return $gradeA - $gradeB;
			});

			return view('user.recommendations', compact('user', 'user_id', 'recommended', 'message', 'sub_title'));
		}

		public function settings(Request $request)
		{
			// Get the authenticated user
			$user = $request->user();

			return view('user.settings', compact('user'));
		}

		public function dashboard(Request $request)
		{
			// Get the authenticated user
			$user      = $request->user();
			$sub_title = __('default.dashboard');

			return view('user.dashboard', compact('user', 'sub_title'));
		}

// Update user settings
		public function updateProfile(Request $request)
		{
			// Get the authenticated user
			$user = $request->user();

			// Validate input
			$validator = Validator::make($request->all(), [
				'name' => ['required', 'string', 'max:255'],
				'username' => [
					'required', 'string', 'max:255', 'alpha_dash',
					Rule::unique('users')->ignore($user->id),
				],
				'email' => [
					'required', 'string', 'email', 'max:255',
					Rule::unique('users')->ignore($user->id),
				],
				'about_me_input' => ['nullable', 'string', 'max:500'],
				'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
				'background_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			if ($request->hasFile('avatar')) {
				$avatar         = $request->file('avatar');
				$avatarContents = file_get_contents($avatar->getRealPath());
				$avatarName     = $user->id . '.jpg';
				$avatarPath     = 'public/user_avatars/' . $avatarName;
				Storage::put($avatarPath, $avatarContents);
				$user->avatar = $avatarPath;
			}

// Handle background upload
			if ($request->hasFile('background_image')) {
				$background         = $request->file('background_image');
				$backgroundContents = file_get_contents($background->getRealPath());
				$backgroundName     = $user->id . '_bg.jpg';
				$backgroundPath     = 'public/user_avatars/' . $backgroundName;
				Storage::put($backgroundPath, $backgroundContents);
				$user->background_image = $backgroundPath;
			}


			// Update user
			$user->name     = $request->input('name');
			$user->username = $request->input('username');
			$user->email    = $request->input('email');
			$user->about_me = $request->input('about_me_input');
			$user->save();

			// Redirect back with success message
			Session::flash('success', 'Your settings have been updated successfully.');
			return redirect()->back();
		}

	}
