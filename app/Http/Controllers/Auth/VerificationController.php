<?php
	/*

	=========================================================

	* Product Page: https://fantastic-voyage.com/product
	* Copyright 2024 Fantastic Voyage (https://fantastic-voyage.com)

	* Coded by fantastic-voyage.com

	=========================================================

	* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

	*/

	namespace App\Http\Controllers\Auth;

	use App\Http\Controllers\Controller;
	use Illuminate\Foundation\Auth\VerifiesEmails;
	use Illuminate\Http\Request;
	use Illuminate\Auth\Events\Verified;
	use Illuminate\Auth\Access\AuthorizationException;

	class VerificationController extends Controller
	{
		/*
		|--------------------------------------------------------------------------
		| Email Verification Controller
		|--------------------------------------------------------------------------
		|
		| This controller is responsible for handling email verification for any
		| user that recently registered with the application. Emails may also
		| be re-sent if the user didn't receive the original email message.
		|
		*/

		use VerifiesEmails;

		/**
		 * Where to redirect users after verification.
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
			$this->middleware('auth');
			$this->middleware('signed')->only('verify');
			$this->middleware('throttle:6,1')->only('verify', 'resend');
		}

		public function verify(Request $request)
		{
			auth()->loginUsingId($request->route('id'));

			if ($request->user()->hasVerifiedEmail()) {
					throw new AuthorizationException('You have already verified your email.');
			}

			if ($request->user()->markEmailAsVerified()) {
				event(new Verified($request->user()));
			}
				return redirect()->route('verify-thank-you');
		}
	}
