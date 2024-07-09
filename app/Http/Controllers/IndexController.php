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

		public function indexPage(Request $request)
		{

			$voices = MyHelper::fetchVoices($request);
			$voices = json_decode($voices, true);

			$good_voices = ['Rachel','Drew','Clyde','Domi','Fin','Sarah','Antoni','Elli','Liam','Arnold','Matilda','Jeremy','Freya','Grace','Adam','Nicole'];
			//filter out the good voices from $voice['name']
			$voices = array_filter($voices['voices'], function($voice) use ($good_voices) {
				//check if the voice name is in the good voices array, case insensitive match beginning
				for ($i = 0; $i < count($good_voices); $i++) {
					if (stripos($voice['name']??'', $good_voices[$i]) === 0) {
						return true;
					}
				}
			});
			$voices = array('voices' => array_values($voices));

			return view('quiz.index-new', compact('voices'));
		}

	}
