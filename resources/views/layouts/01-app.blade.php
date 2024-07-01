<!DOCTYPE html>
<html lang="en_US">
<head>
	<title>{{__('default.Aspirant') }} - {{$sub_title ?? 'Home'}}</title>

	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="{{__('site_domain')}}">
	<meta name="description" content="{{__('default.Aspirant') }} - {{$sub_title ?? 'Home'}}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="app_url" content="{{env('APP_URL')}}">

	<script src="/assets/js/core/jquery.min.js"></script>
	<script src="/assets/js/moment.min.js"></script>
	<script src="/assets/js/daterangepicker.js"></script>

	<!-- Dark mode -->
	<script>
		const storedTheme = localStorage.getItem('theme')

		const getPreferredTheme = () => {
			if (storedTheme) {
				return storedTheme
			}
			return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
		}

		const setTheme = function (theme) {
			if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.setAttribute('data-bs-theme', 'dark')
			} else {
				document.documentElement.setAttribute('data-bs-theme', theme)
			}
		}

		setTheme(getPreferredTheme())

		window.addEventListener('DOMContentLoaded', () => {
			var el = document.querySelector('.theme-icon-active');
			if (el != 'undefined' && el != null) {
				const showActiveTheme = theme => {
					const activeThemeIcon = document.querySelector('.theme-icon-active use')
					const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
					const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

					document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
						element.classList.remove('active')
					})

					btnToActive.classList.add('active')
					activeThemeIcon.setAttribute('href', svgOfActiveBtn)
				}

				window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
					if (storedTheme !== 'light' || storedTheme !== 'dark') {
						setTheme(getPreferredTheme())
					}
				})

				showActiveTheme(getPreferredTheme())

				document.querySelectorAll('[data-bs-theme-value]')
					.forEach(toggle => {
						toggle.addEventListener('click', () => {
							const theme = toggle.getAttribute('data-bs-theme-value')
							localStorage.setItem('theme', theme)
							setTheme(theme)
							showActiveTheme(theme)
						})
					})

			}
		});
		
		
		
	</script>
	@if (env('APP_URL')==='http://localhost:8004')
		<script>
			console.log('Google tag (gtag.js) is disabled on localhost');
		</script>
	@endif

	@if (env('APP_URL')==='https://fantastic-voyage.com')
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-3FWEMJ6MZX"></script>
		<script>
			window.dataLayer = window.dataLayer || [];

			function gtag() {
				dataLayer.push(arguments);
			}

			gtag('js', new Date());

			gtag('config', 'G-3FWEMJ6MZX');
		</script>
	@endif

	@if (env('APP_URL')==='https://minikdersler.com')
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-LCYWTQ7LMH"></script>
		<script>
			window.dataLayer = window.dataLayer || [];

			function gtag() {
				dataLayer.push(arguments);
			}

			gtag('js', new Date());

			gtag('config', 'G-LCYWTQ7LMH');
		</script>
	@endif

	<!-- Favicon -->
	<link rel="shortcut icon" href="/assets/images/favicon.ico">

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet"
	      href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap">

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/all.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/tiny-slider/tiny-slider.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/glightbox/css/glightbox.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/choices/css/choices.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/aos/aos.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/apexcharts/css/apexcharts.css">

	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css">

	<!-- Daterangepicker CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/daterangepicker.css">

</head>

<body>
<!-- Header START -->
<header class="navbar-light navbar-sticky">
	<!-- Nav START -->
	<nav class="navbar navbar-expand-xl z-index-9">
		<div class="container">
			<!-- Logo START -->
			<a class="navbar-brand" href="{{ route( 'index') }}">
				<img class="light-mode-item navbar-brand-item" src="/assets/images/logo-new-en.png" alt="logo">
			</a>
			<!-- Logo END -->

			<!-- Responsive navbar toggler -->
			<button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
			        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-animation">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>

			<!-- Main navbar START -->
			<div class="navbar-collapse collapse" id="navbarCollapse">

				<!-- Nav Main menu START -->
				<ul class="navbar-nav navbar-nav-scroll">
					<!-- Nav item 1 Demos -->
					

					<li class="nav-item ">
						<a class="nav-link  {{ (isset($page_route) && $page_route === 'template.show.home') ? 'active' : '' }}"
						   href="{{ route('template.show.home') }}" id="demoMenu"
						   aria-haspopup="true" aria-expanded="false"><i class="bi bi-house me-2"></i>{{__('default.Home')}}</a>
					</li>

					<li class="nav-item dropdown">
						<a class="nav-link  {{ (isset($page_route) && $page_route === 'study') ? 'active' : '' }} dropdown-toggle"
						   id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
						   aria-expanded="false">
							{{__('default.Learning')}}
						</a>
					</li>

					<li class="nav-item"><a
							class="nav-link {{ (isset($page_route) && $page_route === 'quiz-activities') ? 'active' : '' }}"
							href="{{ route('quiz-activities') }}">{{__('default.Builder')}}</a></li>
				</ul>
				<!-- Nav Main menu END -->
			</div>
			<!-- Main navbar END -->

			<!-- Profile and notification START -->
			<ul class="nav flex-row align-items-center list-unstyled ms-xl-auto">

				<!-- Profile dropdown START -->
				<li class="nav-item ms-3 dropdown">
					<!-- Avatar -->
					<a class="avatar-sm p-0 avatar-img rounded-circle avatar-img" href="{{ route('template.show.home') }}" id="profileDropdown"
					   role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown"
					   aria-expanded="false">
						@if (Auth::user())
									<?php
									$addClass = '';
									if (!empty(Auth::user()->avatar)) {
										$addClass = 'avatar-sm';
									}
									?>
								<img src="{{ !empty(Auth::user()->avatar) ? Storage::url(Auth::user()->avatar) : '/assets/images/avatar/parent.png' }}"
								     alt="avatar" class="rounded-circle {{$addClass}}">
						@else
							<img src="/assets/images/avatar/placeholder.jpg" alt="">
						@endif
					</a>

					<!-- Profile dropdown START -->
					<ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
						<!-- Profile info -->
						@if (Auth::user())
							<li class="px-3">
								<div class="d-flex align-items-center position-relative">
									<!-- Avatar -->
										<div class="avatar me-3">
											<img class="avatar-img rounded-circle"
											     src="{{ !empty(Auth::user()->avatar) ? Storage::url(Auth::user()->avatar) : '/assets/images/avatar/01.jpg' }}"
											     alt="avatar">
										</div>
										<div>
											<a class="h6 stretched-link"
											   href="{{route('my.profile')}}">{{ Auth::user()->username }}</a>
											<p class="small m-0">{{ Auth::user()->email }}</p>
										</div>
								</div>
								{{--								<a class="dropdown-item btn btn-primary-soft btn-sm my-2 text-center"--}}
								{{--								   href="{{route('my.profile')}}">View profile</a>--}}
							</li>
						@endif
						<!-- Links -->

						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>

						@if (Auth::user())
							<li><a href="{{route('account.parent')}}" class="dropdown-item active"><i
										class="bi bi-person fa-fw me-2"></i>{{__('default.Parent Account')}}</a></li>
							<li>
								<hr class="dropdown-divider">
							</li>
						@endif

						@if (Auth::user())
							<li><a class="dropdown-item bg-danger-soft-hover" href="#"
							       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
										class="bi bi-power fa-fw me-2"></i>{{__('default.Logout')}}</a></li>
							<li>
						@else
							<li><a class="dropdown-item bg-primary-soft-hover" href="{{ route('login') }}"><i
										class="bi bi-unlock fa-fw me-2"></i>{{__('default.Sign In')}}</a></li>
							<li><a class="dropdown-item bg-primary-soft-hover" href="{{ route('register') }}"><i
										class="bi bi-person-circle fa-fw me-2"></i>{{__('default.Sign Up')}}</a></li>
						@endif

						<!-- Dark mode options START -->
						<li>
							<div class="bg-light dark-mode-switch theme-icon-active d-flex align-items-center p-1 rounded mt-2">
								<button type="button" class="btn btn-sm mb-0" data-bs-theme-value="light">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
									     class="bi bi-sun fa-fw mode-switch" viewBox="0 0 16 16">
										<path
											d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
										<use href="{{ route('template.show.home') }}"></use>
									</svg>
									{{__('default.Light')}}
								</button>
								<button type="button" class="btn btn-sm mb-0" data-bs-theme-value="dark">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
									     class="bi bi-moon-stars fa-fw mode-switch" viewBox="0 0 16 16">
										<path
											d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278zM4.858 1.311A7.269 7.269 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.316 7.316 0 0 0 5.205-2.162c-.337.042-.68.063-1.029.063-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286z"/>
										<path
											d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
										<use href="{{ route('template.show.home') }}"></use>
									</svg>
									{{__('default.Dark')}}
								</button>
								<button type="button" class="btn btn-sm mb-0 active" data-bs-theme-value="auto">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
									     class="bi bi-circle-half fa-fw mode-switch" viewBox="0 0 16 16">
										<path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
										<use href="{{ route('template.show.home') }}"></use>
									</svg>
									{{__('default.Auto')}}
								</button>
							</div>
						</li>
						<!-- Dark mode options END-->
					</ul>
					<!-- Profile dropdown END -->
				</li>
			</ul>
			<!-- Profile and notification END -->
		</div>
	</nav>
	<!-- Nav END -->

</header>
<!-- Header END -->

<!-- **************** MAIN CONTENT START **************** -->
<main>

	@if( Auth::user() && !auth()->user()->hasVerifiedEmail())
		<div class="container mt-3">
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
				{!!__('default.<strong>Warning!</strong> Your email address is not verified. Please verify your email to access all features.') !!}
				<br>
				{{__('default.If you have not received the verification email, please click the button below.')}}
				<form action="{{ route('verification.send') }}" method="get" class="mt-2">
					@csrf
					<button type="submit" class="btn btn-warning btn-sm">{{__('default.Resend Verification Email')}}</button>
				</form>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		</div>
	@endif


	@yield('content')


</main>
<!-- **************** MAIN CONTENT END **************** -->

<!-- =======================
Footer START -->
<footer class="bg-light pt-5">
	<div class="container">
		<!-- Row START -->
		<div class="row g-4">

			<!-- Widget 1 START -->
			<div class="col-lg-3">
				<!-- logo -->
				<a class="me-0" href="{{ route('template.show.home') }}">
					<img class="light-mode-item h-40px" src="/assets/images/logo-new-en.png" alt="logo">
				</a>
				<p
					class="my-3">{{__('default.Coolxue - an interactive platform dedicated to enhancing your child\'s love for math. Taking the next step in their academic growth is now just a click away.')}}</p>
				<!-- Social media icon -->
				<ul class="list-inline mb-0 mt-3">
					<li class="list-inline-item"><a class="btn btn-white btn-sm shadow px-2 text-facebook"
					                                href="{{ route('template.show.home') }}"><i
								class="fab fa-fw fa-facebook-f"></i></a></li>
					<li class="list-inline-item"><a class="btn btn-white btn-sm shadow px-2 text-instagram"
					                                href="{{ route('template.show.home') }}"><i
								class="fab fa-fw fa-instagram"></i></a></li>
					<li class="list-inline-item"><a class="btn btn-white btn-sm shadow px-2 text-twitter"
					                                href="{{ route('template.show.home') }}"><i
								class="fab fa-fw fa-twitter"></i></a></li>
					<li class="list-inline-item"><a class="btn btn-white btn-sm shadow px-2 text-linkedin"
					                                href="{{ route('template.show.home') }}"><i
								class="fab fa-fw fa-linkedin-in"></i></a></li>
				</ul>
			</div>
			<!-- Widget 1 END -->

			<!-- Widget 2 START -->
			<div class="col-lg-6">
				<div class="row g-4">
					<!-- Link block -->
					<div class="col-6 col-md-4">
						<h5 class="mb-2 mb-md-4">{{__('default.Company')}}</h5>
						<ul class="nav flex-column">
							<li class="nav-item"><a class="nav-link"
							                        href="{{ route('template.show.home') }}">{{__('default.Home')}}</a></li>
							<li class="nav-item"><a class="nav-link"
							                        href="">{{__('default.TERMS OF SERVICE')}}</a>
							</li>
							<li class="nav-item"><a class="nav-link"
							                        href="">{{__('default.PRIVACY POLICY')}}</a>
							</li>
						</ul>
					</div>

					<!-- Link block -->
					<div class="col-6 col-md-4">
					</div>

					<!-- Link block -->
					<div class="col-6 col-md-4">
					</div>
				</div>
			</div>
			<!-- Widget 2 END -->

			<!-- Widget 3 START -->
			<div class="col-lg-3">
				<!-- Time -->
{{--				<p class="mb-2">--}}
{{--					Toll free:<span class="h6 fw-light ms-2">0975 812 1911</span>--}}
{{--					<span class="d-block small">(09:00 - 17:00)</span>--}}
{{--				</p>--}}

				<p class="mb-0">{{__('default.Email')}}:<span class="h6 fw-light ms-2">{{__('default.contact_email')}}</span>
				</p>

				<div class="row g-2 mt-2">
					<!-- Google play store button -->
					<div class="col-6 col-sm-4 col-md-3 col-lg-6">
						<a href="{{ route('template.show.home') }}"> <img src="/assets/images/client/google-play.svg" alt="">
						</a>
					</div>
					<!-- App store button -->
					<div class="col-6 col-sm-4 col-md-3 col-lg-6">
						<a href="{{ route('template.show.home') }}"> <img src="/assets/images/client/app-store.svg"
						                                                  alt="app-store"> </a>
					</div>
				</div> <!-- Row END -->
			</div>
			<!-- Widget 3 END -->
		</div><!-- Row END -->

		<!-- Divider -->
		<hr class="mt-4 mb-0">

		<!-- Bottom footer -->
		<div class="py-3">
			<div class="container px-0">
				<div class="d-lg-flex justify-content-between align-items-center py-3 text-center text-md-left">
					<!-- copyright text -->
					<div class="text-primary-hover"> {{__('default.© 2023 aspirant.tw. All rights reserved.')}}
					</div>
					<!-- copyright links-->
					<div class="justify-content-center mt-3 mt-lg-0">
						<ul class="nav list-inline justify-content-center mb-0">
							<li class="list-inline-item"><a class="nav-link"
							                                href="{{ route('template.show.home') }}">{{__('default.TERMS OF SERVICE')}}</a>
							</li>
							<li class="list-inline-item"><a class="nav-link pe-0"
							                                href="{{ route('template.show.home') }}">{{__('default.PRIVACY POLICY')}}</a>
							</li>
						</ul>
					</div>				</div>
			</div>
		</div>
	</div>
</footer>
<!-- =======================
Footer END -->

<!-- Back to top -->
<div class="back-top"><i class="bi bi-arrow-up-short position-absolute top-50 start-50 translate-middle"></i></div>

<!-- Admission alert box START -->
{{--<div--}}
{{--	class="alert alert-light alert-dismissible fade show position-fixed bottom-0 start-50 translate-middle-x z-index-99 d-lg-flex justify-content-between align-items-center shadow p-4 col-9 col-md-7 col-xxl-5"--}}
{{--	role="alert">--}}
{{--	<div>--}}
{{--		<h4 class="text-dark">Admissions open!</h4>--}}
{{--		<p class="m-0 pe-3">We are so eager to be working with kids and making a difference in their careers. Being a mentor--}}
{{--			is what we have always wanted to be.</p>--}}
{{--	</div>--}}
{{--	<div class="d-flex mt-3 mt-lg-0">--}}
{{--		<button type="button" class="btn btn-success btn-sm mb-0 me-2" data-bs-dismiss="alert" aria-label="Close">--}}
{{--			<span aria-hidden="true">Get Admission</span>--}}
{{--		</button>--}}
{{--		<div class="position-absolute end-0 top-0 mt-n3 me-n3">--}}
{{--			<button type="button" class="btn btn-danger btn-round btn-sm mb-0" data-bs-dismiss="alert" aria-label="Close">--}}
{{--				<span aria-hidden="true"><i class="bi bi-x-lg"></i></span>--}}
{{--			</button>--}}
{{--		</div>--}}
{{--	</div>--}}
{{--</div>--}}
<!-- Admission alert box END -->

<!-- Bootstrap JS -->
<script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


<!-- Vendors -->
<script src="/assets/vendor/tiny-slider/tiny-slider.js"></script>
<script src="/assets/vendor/isotope/isotope.pkgd.min.js"></script>
<script src="/assets/vendor/imagesLoaded/imagesloaded.js"></script>
<script src="/assets/vendor/glightbox/js/glightbox.js"></script>
<script src="/assets/vendor/choices/js/choices.min.js"></script>
<script src="/assets/vendor/purecounterjs/dist/purecounter_vanilla.js"></script>
<script src="/assets/vendor/aos/aos.js"></script>
<script src="/assets/vendor/apexcharts/js/apexcharts.min.js"></script>

<!-- Template Functions -->
<script src="/assets/js/functions.js"></script>

@stack('scripts')

</body>
</html>
