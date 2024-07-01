<?php

	namespace App\Traits;

	use App\Models\Badges;
	use App\Models\UserLoginLogs;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Transliterator;

	trait SharedFunction
	{

		public function urlify($string, $lang)
		{

			if ($lang === 'chinese') {

				$url_friendly_string = $string;

				if ($url_friendly_string !== null) {
					// Remove any double hyphens
					$url_friendly_string = preg_replace('/\-+/', '-', $url_friendly_string);
					//bad characters
					$bad_characters = ['？', '：', '?', ' ', '（', '）', '、', '(', ')', '[', ']', ':', ';', '.', ',', '!', '/'];
					$url_friendly_string = str_replace($bad_characters, '', $url_friendly_string);

					// Remove any hyphens at the beginning or end of the string
					$url_friendly_string = trim($url_friendly_string, '-');
				}
				return $url_friendly_string;
			} else {

				$transliterator = Transliterator::create("Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove");

				$ascii_string = $transliterator->transliterate($string);

				// Convert to ASCII using iconv
//			$ascii_string = iconv( 'UTF-8', 'ASCII//TRANSLIT', $string );

				// Replace non-URL friendly characters with hyphens
				$url_friendly_string = preg_replace('/[^a-zA-Z0-9\-]/', '-', $ascii_string);

				// Remove any double hyphens
				$url_friendly_string = preg_replace('/\-+/', '-', $url_friendly_string);

				// Remove any hyphens at the beginning or end of the string
				$url_friendly_string = trim($url_friendly_string, '-');

				return strtolower($url_friendly_string);
			}
		}


		public function get_language()
		{
			$language_arr = ['zh_TW' => 'chinese', 'fr_FR' => 'french', 'tr' => 'turkish', 'en_US' => 'english'];

			$lang_short = 'en_US';
			$lang = $language_arr[$lang_short];
			return $lang;
		}



		public function array_diff_multidimensional($array1, $array2)
		{
			// Custom comparison function for use in array_udiff_assoc()
			$compareFunction = function ($value1, $value2) {
				if (is_array($value1) && is_array($value2)) {
					return json_encode($value1) === json_encode($value2) ? 0 : 1;
				}
				return strcmp((string)$value1, (string)$value2);
			};

			return array_udiff_assoc($array1, $array2, $compareFunction);
		}
	}
