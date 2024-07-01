<?php

	namespace App\Http\Middleware;

	use Closure;
	use Illuminate\Http\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\View;
	use App\Traits\SharedFunction;
	use App\Models\Badges;
	use Carbon\Carbon;


	class ShareUserData
	{
		/**
		 * Handle an incoming request.
		 *
		 * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
		 */
		use SharedFunction;

		public function handle(Request $request, Closure $next): Response
		{

			$this->dashboardHeaderInfo();

			return $next($request);
		}

		private function dashboardHeaderInfo()
		{
			$default_profile_pic = '/assets/images/avatar/head1.png';

			if (Auth::user()) {
				$user_id = Auth::user()->id;
			}

			View::share(['default_profile_pic' => $default_profile_pic]);
		}
	}
