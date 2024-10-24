<?php

	use App\Helpers\MyHelper;
	use App\Http\Controllers\Auth\ForgotPasswordController;
	use App\Http\Controllers\Auth\ResetPasswordController;
	use App\Http\Controllers\IndexController;
	use App\Http\Controllers\QuizBuilderController;
	use App\Http\Controllers\QuizContentBuilderController;
	use App\Http\Controllers\QuizGameBuilderController;
	use App\Http\Controllers\DreamStudioController;
	use App\Http\Controllers\LoginWithGoogleController;
	use App\Http\Controllers\StaticPagesController;
	use App\Http\Controllers\UserSettingsController;
	use App\Http\Controllers\VerifyThankYouController;
	use App\Mail\ThankYouForYourOrder;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Mail;
	use Illuminate\Support\Facades\Route;


	/*
	|--------------------------------------------------------------------------
	| Web Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register web routes for your application. These
	| routes are loaded by the RouteServiceProvider and all of them will
	| be assigned to the "web" middleware group. Make something great!
	|
	*/
	Route::get('/', [IndexController::class, 'indexPage'])->name('index');

	Route::get('quiz-builder/{activity_id?}', [QuizBuilderController::class, 'show_quizBuilder'])->name('quiz-builder');
	Route::get('quiz-activities', [QuizBuilderController::class, 'quizActivities'])->name('quiz-activities');
	Route::get('quiz-activities-action/{action}/{id}', [QuizBuilderController::class, 'quizActivitiesAction'])->name('quiz-activities-action');

	Route::get('example-quiz', [QuizBuilderController::class, 'exampleQuizzes'])->name('example-quiz');




	Route::get('phaser-summer-zoom', function () {
		return view('game-layout.phaser-summer-zoom');
	})->name('phaser-summer-zoom');

	Route::get('phaser-winter-zoom', function () {
		return view('game-layout.phaser-winter-zoom');
	})->name('phaser-winter-zoom');

	Route::get('phaser-winter-zoom-with-buttons', function () {
		return view('game-layout.phaser-winter-zoom-with-buttons');
	})->name('phaser-winter-zoom-with-buttons');

	Route::get('phaser-mid-autumn-zoom', function () {
		return view('game-layout.phaser-mid-autumn-zoom');
	})->name('phaser-mid-autumn-zoom');

	Route::get('phaser-moon-zoom', function () {
		return view('game-layout.phaser-moon-zoom');
	})->name('phaser-moon-zoom');

	Route::get('phaser-taipei-zoom', function () {
		return view('game-layout.phaser-taipei-zoom');
	})->name('phaser-taipei-zoom');

	Route::get('phaser-beach-zoom', function () {
		return view('game-layout.phaser-beach-zoom');
	})->name('phaser-beach-zoom');

	Route::get('phaser-space-zoom', function () {
		return view('game-layout.phaser-space-zoom');
	})->name('phaser-space-zoom');

	Route::get('phaser-jungle-zoom', function () {
		return view('game-layout.phaser-jungle-zoom');
	})->name('phaser-jungle-zoom');


	Route::get('phaser-responsive', function () {
		return view('game-layout.phaser-responsive');
	})->name('phaser-responsive');

	Route::get('phaser-rabbit-zoom', function () {
		return view('game-layout.phaser-rabbit-zoom');
	})->name('phaser-rabbit-zoom');


	Route::post('/quiz-image-search', [QuizBuilderController::class, 'quizImageSearch'])->name('quiz-image-search');

	Route::get('/fetch-voices', [MyHelper::class, 'fetchVoices'])->name('fetch-voices');
	Route::post('/convert-text-to-speech', [QuizBuilderController::class, 'convertTextToSpeech'])->name('convert-text-to-speech');
	Route::post('/quiz-build-json', [QuizBuilderController::class, 'quizBuildJson'])->name('quiz-build-json');
	Route::post('/quiz-item-build-json', [QuizBuilderController::class, 'quizItemBuildJson'])->name('quiz-item-build-json');
	Route::post('/quiz-upload/{file_type}', [QuizBuilderController::class, 'quizUpload'])->name('quiz-upload');
	Route::post('/set-theme', [QuizBuilderController::class, 'setTheme'])->name('set-theme');

	Route::post('/quiz-content-builder-json', [QuizContentBuilderController::class, 'addNewVoyage'])->name('quiz-content-builder-json');

	Route::post('/create-question-image', [QuizBuilderController::class, 'createQuestionImage'])->name('create-question-image');

	Route::get('/load-quiz/{activity_id}/{question?}', [QuizGameBuilderController::class, 'index'])->name('load-quiz');
	Route::get('/load-quiz-in-page/{activity_id}/{question?}', [QuizGameBuilderController::class, 'inPage'])->name('load-quiz-in-page');

	Route::get('/landing', [StaticPagesController::class, 'landing'])->name('landing.page');

	Route::get('login/google', [LoginWithGoogleController::class, 'redirectToGoogle']);
	Route::get('login/google/callback', [LoginWithGoogleController::class, 'handleGoogleCallback']);

//	Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
//	Route::post('forgot-password', [ForgotPasswordController::class, 'sendPasswordResetEmail'])->name('password.email');
//	Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
//	Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

	Route::get('/privacy', [StaticPagesController::class, 'privacy'])->name('privacy.page');

	Route::get('/terms', [StaticPagesController::class, 'terms'])->name('terms.page');

	Route::get('/help', [StaticPagesController::class, 'help'])->name('help.page');
	Route::get('/help/{topic}', [StaticPagesController::class, 'helpDetails'])->name('help-details.page');
	Route::get('/about', [StaticPagesController::class, 'about'])->name('about.page');
	Route::get('/contact', [StaticPagesController::class, 'contact'])->name('contact.page');

	Route::get('/quiz-layout-test', [StaticPagesController::class, 'quizLayoutTest'])->name('quiz.layout.test');

	Route::get('/test-email', function () {
		Mail::to('kunfukedisi@gmail.com')->send(new ThankYouForYourOrder());

		return 'Email has been sent';
	});


	Route::get('/writer-profile/{username}', [StaticPagesController::class, 'userProfile'])->name('user-profile');

	Route::get('/email/verification-notification', function (Request $request) {
		$request->user()->sendEmailVerificationNotification();
		return back()->with('resent', true);
	})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

	Route::get('/verify-thank-you', [VerifyThankYouController::class, 'index'])->name('verify-thank-you')->middleware('verified');

	Route::get('/onboarding', [StaticPagesController::class, 'onboarding'])->name('onboarding.page');

	Route::middleware(['auth'])->group(function () {

		Route::get('/account/parent', [UserSettingsController::class, 'switchToParent'])->name('account.parent');

		Route::get('/account/student/{id}', [UserSettingsController::class, 'switchToChildren'])->name('account.student');


		Route::get('/dashboard', [UserSettingsController::class, 'dashboard'])->name('my.dashboard');

		Route::get('/profile', [UserSettingsController::class, 'userProfile'])->name('my.profile');
		Route::post('/profile', [UserSettingsController::class, 'updateProfile'])->name('profile.update');
		Route::get('/settings', [UserSettingsController::class, 'userSettings'])->name('my.settings');

		Route::post('/profile/password', [UserSettingsController::class, 'updatePassword'])->name('profile.password.update');

		Route::post('/generate-image', [DreamStudioController::class, 'generateImage'])->name('generate-image');

	});



	Auth::routes();
	Auth::routes(['verify' => true]);
