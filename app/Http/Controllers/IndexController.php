<?php

	namespace App\Http\Controllers;

	use App\Helpers\MyHelper;
	use Illuminate\Http\Request;
	use App\Traits\SharedFunction;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;

	class IndexController extends Controller
	{
		use SharedFunction;

		public function index(Request $request)
		{

			$voices = MyHelper::fetchVoices($request);
			$voices = json_decode($voices, true);

			return view('quiz.index-new', compact('voices'));
		}

	}
