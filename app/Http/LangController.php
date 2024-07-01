<?php

	namespace App\Http;

	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\App;

	class LangController extends Controller
	{
		public function index()
		{
			return view('lang');
		}

		public function change(Request $request)
		{
			App::setLocale($request->lang);
			session()->put('locale', $request->lang);
			//go to home page
			return redirect()->route('index');
		}

	}
