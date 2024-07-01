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
	<script src="/assets/js/core/jquery-ui.min.js"></script>
	<script src="/assets/js/moment.min.js"></script>
	<script src="/assets/js/daterangepicker.js"></script>
	
	<script src="/assets/js/smooth-drag.js"></script>
	
	<!-- Quiz Builder -->
	<link rel="stylesheet" type="text/css" href="/assets/css/quiz-builder-new.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/icons.css">
	
	<!-- image editor -->
	<link rel="stylesheet" type="text/css" href="/assets/vendor/tui-image-editor/tui-image-editor.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/tui-image-editor/tui-color-picker.css">
	
	
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
	
	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/new-index.css">
	@if (!isset($homePage))
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css">
	@endif
	
	<!-- Daterangepicker CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/daterangepicker.css">
	
	<!-- Quiz Builder -->
	<link rel="stylesheet" type="text/css" href="/assets/css/quiz-builder-new.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/icons.css">
	
	<!-- image editor -->
	<link rel="stylesheet" type="text/css" href="/assets/vendor/tui-image-editor/tui-image-editor.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/tui-image-editor/tui-color-picker.css">
	
	<script src="/assets/vendor/tui-image-editor/tui-color-picker.min.js"></script>
	<script src="/assets/vendor/tui-image-editor/white-theme.js"></script>
	<script src="/assets/vendor/tui-image-editor/black-theme.js"></script>
	<script src="/assets/vendor/tui-image-editor/tui-image-editor.js"></script>
	
</head>

<body>
@if (!isset($homePage))
	<!-- Header START -->
	<header class="navbar-light navbar-sticky">
		<!-- Nav START -->
		<nav class="navbar navbar-expand-xl z-index-9">
			<div class="container">
				<!-- Logo START -->
				<a class="navbar-brand" href="{{ route('index') }}">
					<img class="light-mode-item navbar-brand-item" src="/assets/images/logo-new-en.png"
					     alt="logo">
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
				
				<!-- Nav Main menu START -->
				<a href="{{route('quiz-activities')}}" class="button-secondary-2 margin-left w-button">{{__('default.Play')}}</a>
				
				<a href="{{route('help.page')}}" class="button-secondary-2 margin-left w-button">{{__('default.Help')}}</a>

				<!-- Nav Main menu END -->
				<!-- Main navbar END -->
				
				<!-- Profile and notification START -->
				<ul class="nav flex-row align-items-center list-unstyled ms-xl-auto">
					
					
					
					<!-- Profile dropdown START -->
					<li class="nav-item ms-3 dropdown">
						<!-- Avatar -->
						<a class="avatar-sm p-0 avatar-img rounded-circle avatar-img" href="{{ route('template.show.home') }}"
						   id="profileDropdown"
						   role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown"
						   aria-expanded="false">
							@if (Auth::user())
									<?php
									$addClass = '';
									if (!empty(Auth::user()->avatar)) {
										$addClass = 'avatar-sm';
									}
									?>
								<img
									src="{{ !empty(Auth::user()->avatar) ? Storage::url(Auth::user()->avatar) : '/assets/images/avatar/parent.png' }}"
									alt="avatar" class="rounded-circle {{$addClass}}">
							
							@else
								<img src="/assets/images/avatar/placeholder.jpg" alt="">
							@endif
						</a>
						
						<!-- Profile dropdown START -->
						<ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3"
						    aria-labelledby="profileDropdown">
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
								</li>
								
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									@csrf
								</form>

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
@endif


<!--------------------------- NEW INDEX APP --------------------------->
<!--------------------------- NEW INDEX APP --------------------------->
<!--------------------------- NEW INDEX APP --------------------------->
<!--------------------------- NEW INDEX APP --------------------------->
<div class="w-embed w-iframe w-script">
	<div class="page-wrapper-3">
		
		<!-- **************** MAIN CONTENT START **************** -->
		<main class="main-wrapper">
			
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
		
		
		<footer class="footer_component">
			<div class="page-padding">
				<div class="container-large footer">
					<div class="grid-footerlinks margin-vertical">
						<div class="grid_1_1_1 container-large footer-links">
							<div class="heading-small text-color-blue margin-bottom">General</div>
							<a href="{{ route('template.show.home') }}" aria-current="page"
							   class="footer-link-item w--current">{{__('default.Home')}}</a>
						
						</div>
						<div class="grid_1_1_1 container-large footer-links">
							<div class="heading-small text-color-blue margin-bottom">Resources</div>
							<a href="/teachers-page" class="footer-link-item">Teacher page</a><a href="/parents-page"
							                                                                     class="footer-link-item">parent
								page</a><a href="/teaching-resources" class="footer-link-item">teaching resources</a></div>
						<div class="grid_1_1_1 container-large footer-links">
							<div class="heading-small text-color-blue margin-bottom">Support</div>
							<a href="#" class="footer-link-item">support center</a><a
								href="#FAQS" class="footer-link-item">FAQs</a><a href="/support"
							                                                   class="footer-link-item">getting
								set up</a><a href="/contact"
							               class="footer-link-item">Contact</a>
						</div>
						<div class="grid_1_1_1 container-large footer-links">
							<div class="heading-small text-color-blue margin-bottom">Legal</div>
							<a class="footer-link-item" href="">{{__('default.TERMS OF SERVICE')}}</a>
							
							<a  class="footer-link-item"
							    href="">{{__('default.PRIVACY POLICY')}}</a>
							
							<p class="mb-0">{{__('default.Email')}}:<span
									class="h6 fw-light ms-2">{{__('default.contact_email')}}</span>
							</p>
						
						</div>
					</div>
					<div class="line-2"></div>
					<div class="copyright margin-top">
						{{__('default.© 2023 aspirant.tw. All rights reserved.')}}</div>
				</div>
			</div>
		</footer>
		
		<!-- =======================
		Footer END -->
		
		<!-- Back to top -->
		<div class="back-top"><i class="bi bi-arrow-up-short position-absolute top-50 start-50 translate-middle"></i></div>
		
		<!-- Bootstrap JS -->
		<script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
		
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link
			href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,500;0,700;1,100;1,500;1,700&display=swap"
			rel="stylesheet">
		
		<!-- Template Functions -->
		<script>
			$(document).ready(function () {
				var logo_character = $('#logo_character');
				var logo_land = $('#logo_land');
				var logo_numbers1 = $('#logo_numbers1');
				var logo_numbers2 = $('#logo_numbers2');
				var logo_operator = $('#logo_operator');
				
				let orig_character = logo_character.css('transform'); // Getting original position
				let orig_land = logo_land.css('transform');
				let orig_numbers1 = logo_numbers1.css('transform');
				let orig_numbers2 = logo_numbers2.css('transform');
				let orig_operator = logo_operator.css('transform');
				
				var logo_div = $('.float-test');
				
				logo_div.on('mousemove', function (e) {
					var cvalueX = (e.pageX * 1 / 80);
					var cvalueY = (e.pageY * -1 / 30);
					var lvalueX = (e.pageX * -1 / 60);
					var lvalueY = (e.pageY * -1 / 30);
					var n1valueX = (e.pageX * -1 / 80);
					var n2valueX = (e.pageX * 1 / 130);
					var nvalueY = (e.pageY * -1 / 50);
					var ovalueX = (e.pageX * -1 / 60);
					var ovalueY = (e.pageY * 1 / 60);
					logo_character.css('transform', 'translate3d(' + cvalueX + 'px,' + cvalueY + 'px, 0)');
					logo_land.css('transform', 'translate3d(' + lvalueX + 'px,' + lvalueY + 'px, 0)');
					logo_numbers1.css('transform', 'translate3d(' + n1valueX + 'px,' + nvalueY + 'px, 0)');
					logo_numbers2.css('transform', 'translate3d(' + n2valueX + 'px,' + nvalueY + 'px, 0)');
					logo_operator.css('transform', 'translate3d(' + ovalueX + 'px,' + ovalueY + 'px, 0)');
				});
				
				logo_div.on('mouseleave', function (e) {
					logo_character.css('transform', orig_character); // Moving back to original position
					logo_land.css('transform', orig_land);
					logo_numbers1.css('transform', orig_numbers1);
					logo_numbers2.css('transform', orig_numbers2);
					logo_operator.css('transform', orig_operator);
				});
				
				
				var hold_left = $('.floater-left');
				var hold_right = $('.floater-right');
				
				let orig_hold_left = hold_left.css('transform'); // Getting original position
				let orig_hold_right = hold_right.css('transform');
				
				var video_div = $('.video');
				video_div.on('mousemove', function (e) {
					var lvalueX = (e.pageX * -1 / 100);
					var rvalueX = (e.pageX * 1 / 100);
					hold_left.css('transform', 'translate3d(' + lvalueX + 'px, 0, 0)');
					hold_right.css('transform', 'translate3d(' + rvalueX + 'px, 0, 0)');
				});
				
			});
		
		</script>

@stack('scripts')

</body>
</html>
