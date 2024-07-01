<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;

	class TemplateController extends Controller
	{
		public function show(Request $request, $viewName)
		{
			$templatePath = 'template.' . $viewName;


			if (view()->exists($templatePath)) {
				return view($templatePath);
			}

			abort(404); // Return 404 error if the view does not exist.
		}


		public function home_page(Request $request)
		{
			$templatePath = 'template.index';


			if (view()->exists($templatePath)) {
				return view($templatePath);
			}

			abort(404); // Return 404 error if the view does not exist.
		}

	}
