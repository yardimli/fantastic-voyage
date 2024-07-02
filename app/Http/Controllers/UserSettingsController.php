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




		//-------------------------------------------------------------------------
		// settings

		public function userSettings(Request $request)
		{
			// Get the authenticated user
			$user       = $request->user();
			$sub_title  = __('default.dashboard');

			return view('user.settings', compact('user',  'sub_title'));
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
