<?php

	namespace App\Http\Controllers;

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


	class StaticPagesController extends Controller
	{
		//-------------------------------------------------------------------------
		// Index
		public function index(Request $request)
		{

		}

		public function quizLayoutTest(Request $request)
		{
			return view('template.quiz-layout-test');
		}


		//-------------------------------------------------------------------------
		// user_Profile
		public function userProfile(Request $request, $username)
		{
			$user = User::where('username', $username)->first();

			return view('user.my-profile', compact('user'));
		}


		//-------------------------------------------------------------------------
		// myProfile
		public function myProfile(Request $request)
		{
			$user = $request->user();

			return view('user.my-profile', compact('user'));
		}



		//-------------------------------------------------------------------------
		// privacy
		public function privacy(Request $request)
		{
			return view('user.privacy');
		}

		public function onboarding(Request $request)
		{
			return view('user.onboarding');
		}

		public function about(Request $request)
		{
			return view('user.about');
		}

		//-------------------------------------------------------------------------
		// terms
		public function terms(Request $request)
		{
			// Fetch last 10 stories
			return view('user.terms');
		}

		//-------------------------------------------------------------------------
		// landing
		public function landing(Request $request)
		{
			return view('landing.landing');
		}


		//-------------------------------------------------------------------------
		// help
		public function help(Request $request)
		{
				return view('help.help');
		}

		//-------------------------------------------------------------------------
		// help-details
		public function helpDetails(Request $request, $topic)
		{

				return view('help.help-details', ['topic' => $topic]);

		}


	}
