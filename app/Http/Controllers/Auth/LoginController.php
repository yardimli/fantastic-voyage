<?php
	/*

	=========================================================

	* Product Page: https://fantastic-voyage.com/product
	* Copyright 2018 CoolXue (https://fantastic-voyage.com)

	* Coded by fantastic-voyage.com

	=========================================================

	* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

	*/

	namespace App\Http\Controllers\Auth;

	use App\Http\Controllers\Controller;
	use Illuminate\Foundation\Auth\AuthenticatesUsers;
	use Illuminate\Auth\Events\Logout;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Log;
	use Socialite;
	use \App\Models\UserLoginLogs;

	class LoginController extends Controller
	{
		/*
		|--------------------------------------------------------------------------
		| Login Controller
		|--------------------------------------------------------------------------
		|
		| This controller handles authenticating users for the application and
		| redirecting them to your home screen. The controller uses a trait
		| to conveniently provide its functionality to your applications.
		|
		*/

		use AuthenticatesUsers;

		/**
		 * Where to redirect users after login.
		 *
		 * @var string
		 */
		protected $redirectTo = '/quiz-activities';

		/**
		 * Create a new controller instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
			$this->middleware('guest')->except('logout');
		}

		// Override the login function
		public function login(Request $request)
		{
			$this->validateLogin($request);

			if ($this->attemptLogin($request)) {

				// Set session here
				$request->session()->put('active_account', 'parent');

				// Update last_login in database
				$user             = Auth::user();
				$user->last_login = date('Y-m-d H:i:s');
				$user->save();

				// Also update last_login and continue_login_count in user_login_logs table
				$user_login_logs = UserLoginLogs::where('user_id', $user->id)->first();
				$todays_date     = date('Y-m-d');
				if ($user_login_logs) {
					$last_login  = date('Y-m-d', strtotime($user_login_logs->last_login));
					$prev_date   = date('Y-m-d', strtotime($todays_date . ' -1 day'));
					$login_count = $user_login_logs->continue_login_count;
					if ($last_login == $prev_date) {
						$login_count++;
					} else if ($last_login != $todays_date && $last_login != $prev_date) {
						$login_count = 1;
					}
					$user_login_logs->last_login           = $todays_date;
					$user_login_logs->continue_login_count = $login_count;
					$user_login_logs->save();
				} else {
					$user_login_logs                       = new UserLoginLogs;
					$user_login_logs->user_id              = $user->id;
					$user_login_logs->last_login           = date('Y-m-d H:i:s');
					$user_login_logs->continue_login_count = 1;
					$user_login_logs->save();
				}


				return $this->sendLoginResponse($request);
			}

			return $this->sendFailedLoginResponse($request);
		}

		// Add this method if you want to use your own validation rules.
		protected
		function validateLogin(Request $request)
		{
			$request->validate([
				                   'email' => 'required|email',
				                   'password' => 'required|string',
			                   ]);
		}


		public
		function redirectToProvider()
		{
			return Socialite::driver('google')->stateless()->redirect();
		}

		public
		function handleProviderCallback()
		{
			$user = Socialite::driver('google')->user();
// $user->token; (return as your need)
		}

		public
		function logout(Request $request)
		{
			Log::info('Logout');
			// Get the currently authenticated user before logging out
			$user = Auth::check() ? Auth::user() : null;

			$this->guard()->logout();

			$request->session()->invalidate();

			$request->session()->regenerateToken();

			if ($user) {
				event(new Logout($this->guard(), $user)); // Dispatch the logout event only if there was an authenticated user
			}

			// Perform additional custom actions here, like cleanup or logging
			$_SESSION['guid'] = null;

			return $this->loggedOut($request) ?: redirect('/');
		}
	}
