<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use App\Traits\SharedFunction;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;

	class IndexController extends Controller
	{
		use SharedFunction;

		public function index(Request $request)
		{

			return view('template.index-new');
		}

	}
