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
	use App\Mail\WelcomeMail;

	use App\Models\User;
	use Illuminate\Foundation\Auth\RegistersUsers;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Facades\Mail;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Support\Str;
	use Illuminate\Http\Request;
	use Symfony\Component\VarDumper\Cloner\VarCloner;
	use App\Helpers\MyHelper;

	class RegisterController extends Controller
	{
		/*
		|--------------------------------------------------------------------------
		| Register Controller
		|--------------------------------------------------------------------------
		|
		| This controller handles the registration of new users as well as their
		| validation and creation. By default this controller uses a trait to
		| provide this functionality without requiring any additional code.
		|
		*/

		use RegistersUsers;

		/**
		 * Where to redirect users after registration.
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
			$this->middleware('guest');
		}

		/**
		 * Get a validator for an incoming registration request.
		 *
		 * @param array $data
		 *
		 * @return \Illuminate\Contracts\Validation\Validator
		 */
		protected function validator(array $data)
		{
			return Validator::make($data, [
//				'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
				'password' => ['required', 'string', 'min:6', 'confirmed'],
				'profile_pic' => ['required', 'string', 'max:255'],
				'policy' => ['required', 'accepted'],
			]);
		}

		/**
		 * Create a new user instance after a valid registration.
		 *
		 * @param array $data
		 *
		 * @return \App\Models\User
		 */
		protected function create(array $data)
		{
			$new_user = User::create([
				                         'name' => $data['email'], //$data['username'],
				                         'email' => $data['email'],
				                         'password' => Hash::make($data['password']),
				                         'avatar' => '',
				                         'picture' => '/assets/images/avatar/parent.png',
				                         'username' => $data['email'], // $data['username'],
				                         'about_me' => 'I am a new student!',
				                         'member_status' => 1,
				                         'member_type' => 2,
																 'last_login' => now(),
				                         'last_ip' => request()->ip(),
				                         'background_image' => '',
			                         ]);

			if ($new_user) {
//            Mail::mailer( 'mailgun' )->to( $data['email'] )->send( new \App\Mail\WelcomeMember( $data['organization_name'] ?? 'NoName Corp',$data['name'], $data['email'], $temp_password ) );
				MyHelper::addStarterPackage($new_user->id);
			}

			// Set session here
			\Session::put('active_account', 'parent');

			///-------------- ADD NEW USER TOKENS


			return $new_user;
		}

		protected function registered(Request $request, $user)
		{
			Mail::to($request->input('email'))->send(new WelcomeMail($user->name, $user->email));

			return redirect()->intended('/quiz-activities');

//			return redirect()->route('template.show', 'about');
		}
	}
