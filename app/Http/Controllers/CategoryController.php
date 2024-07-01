<?php

	namespace App\Http\Controllers;

	use App\Models\LessonNameTranslations;
	use App\Models\Category;
	use App\Models\CategoryTranslations;
	use App\Models\Lessons;
	use App\Traits\SharedFunction;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Support\Collection;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Session;

	class CategoryController extends Controller
	{
		use SharedFunction;
		public function show($category_url, $grade = null, $category_name_url)
		{
			$lang         = $this->get_language();
			$sub_title		= __('default.Category').' - '.$grade;

			$grade_friendly_name = ['K' => 'Kindergarten', '1' =>  'First Grade', '2' => 'Second Grade', '3' => 'Third Grade', '4' => 'Fourth Grade'];

			foreach ($grade_friendly_name as $key => $value) {
				if($grade == __('default.'.$value.' URL Link')){
					$grade_short = $key;
					$grade_str = __('default.'.$value);
				}
			}

			$grade_first_category = array();
			foreach ($grade_friendly_name as $key => $value) {
				$get_grade_first_category = $this->get_categories($key, $lang);
				$grade_first_category[] = $get_grade_first_category[0]['category_translated_name_url'];
			}

			$categories = $this->get_categories($grade_short, $lang);

			foreach ($categories as $category) {
				$translated_name_url = $category['category_name'];
				if($lang !== 'english'){
					$translated_name_url = $category['category_translated_name_url'];
				}
				if($translated_name_url == $category_name_url){
					$category_code = $category['sort_category_code'];
					$category_translated_name = $category['category_translated_name'];
				}
			}

			//get lessons with category code
			$lessons_by_category = $this->get_lessons_by_category_code($category_code, $lang, $grade_short);

			//pagination
			$currentPage = LengthAwarePaginator::resolveCurrentPage();
			$perPage = 9;

			$collection = new Collection($lessons_by_category);
			$totalItems = count($collection);

			$paginatedItems = $collection->slice(($currentPage-1) * $perPage, $perPage)->all();
			$pagination = new LengthAwarePaginator($paginatedItems, $totalItems, $perPage);
			$pagination->withPath(route('category.show', ['category_url' => $category_url, 'grade' => $grade, 'grade_str' => $grade_str, 'category_name_url' => $category_name_url]));

			$bg_array = array('bg-primary', 'bg-warning', 'bg-danger', 'bg-orange', 'bg-purple', 'bg-info', 'bg-blue', 'bg-success', 'bg-dark');
			return view('catalogue.category', compact('pagination', 'totalItems', 'category_name_url', 'category_translated_name', 'categories', 'grade', 'grade_str', 'collection', 'bg_array', 'grade_first_category', 'sub_title'));

		}
	}
