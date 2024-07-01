<!DOCTYPE html>
<html lang="en_US">
<head>
	<title>{{__('default.Aspirant') }} - {{$sub_title ?? 'Register'}}</title>
	
	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="{{__('site_domain')}}">
	<meta name="description" content="{{__('default.Aspirant') }} - {{$sub_title ?? 'Home'}}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="app_url" content="{{env('APP_URL')}}">
	
	<script src="/assets/js/core/jquery.min.js"></script>

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
			if(el != 'undefined' && el != null) {
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
		})

		$(document).ready(function () {
			$('.characters img').click(function () {
				$('.characters img').removeClass('active');
				$(this).addClass('active');
				$('#profile_pic').val($(this).attr('src'));
			});
		});

	</script>

	<!-- Favicon -->
	<link rel="shortcut icon" href="/assets/images/favicon.ico">

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap">

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/all.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">

	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css">

</head>

<body>

<!-- **************** MAIN CONTENT START **************** -->
<main>
	<section class="p-0 d-flex align-items-center position-relative overflow-hidden">

		<div class="container-fluid">
			<div class="row">
				<!-- left -->
				<div class="col-12 col-lg-6 d-md-flex align-items-center justify-content-center bg-primary bg-opacity-10 vh-lg-100">
					<div class="p-3 p-lg-5">
						<!-- Title -->
						<div class="text-center">
							<h2 class="fw-bold">{{__('default.Welcome to Coolxue!')}}</h2>
							<p class="mb-0 h6 fw-light">{{__('default.Let\'s learn something new today!')}}</p>
						</div>
						<!-- SVG Image -->
						<img src="/assets/images/characters/register.png" class="mt-5" alt="">
						<!-- Info -->
						<div class="d-sm-flex mt-5 align-items-center justify-content-center">
							<!-- Content -->
							<p class="mb-0 h6 fw-light ms-0 ms-sm-3">{{__('default.Its your turn to join.')}}</p>
						</div>
						<ul class="mt-4 text-center">
							<a class="fw-bold ps-0 pe-2" href="">{{__('default.TERMS OF SERVICE')}}</a>
							<a class="fw-bold px-2" href="">{{__('default.PRIVACY POLICY')}}</a>

						</ul>
					</div>
				</div>

				<!-- Right -->
				<div class="col-12 col-lg-6 m-auto">
					<div class="row my-5">
						<div class="col-sm-10 col-xl-8 m-auto">
							<!-- Title -->
							<span class="mb-0 fs-1">👋🏼</span>
							<h2>{{__('default.Sign Up')}}</h2>
							<!-- Form START -->
							<form class="mt-4" method="POST" action="{{ route('register') }}" role="form">
								@csrf



								<!-- Email -->
								<div class="mb-4 {{ $errors->has('email') ? ' has-danger' : '' }}">
									<label for="exampleInputEmail1" class="form-label">{{__('default.Your email address')}} *</label>
									<div class="input-group input-group-lg">
										<span class="input-group-text bg-light rounded-start border-0 text-secondary px-3"><i class="bi bi-envelope-fill"></i></span>
										<input type="email" class="form-control border-0 bg-light rounded-end ps-1" name="email" placeholder="{{ __('default.Please enter your email address') }}" value="{{ old('email') }}" required autocomplete="email" id="exampleInputEmail1">
									</div>
									@if ($errors->has('email'))
										<div id="email-error" class="error text-danger pl-3" for="name" style="display: block;">
											<strong class="errors-field-email">{{ $errors->first('email') }}</strong>
										</div>
									@endif
								</div>

								<!-- Password -->
								<div class="mb-4">
									<label for="inputPassword5" class="form-label">{{__('default.Password')}} *</label>
									<div class="input-group input-group-lg">
										<span class="input-group-text bg-light rounded-start border-0 text-secondary px-3"><i class="fas fa-lock"></i></span>
										<input type="password" name="password"  class="form-control border-0 bg-light rounded-end ps-1" placeholder="*********" required id="inputPassword5">
									</div>
									@if ($errors->has('password'))
										<div id="password-error" class="error text-danger pl-3" for="password"
										     style="display: block;">
											<strong class="errors-field-pass">{{ $errors->first('password') }}</strong>
										</div>
									@endif
								</div>

								<!-- Confirm Password -->
								<div class="mb-4">
									<label for="password_confirmation" class="form-label">{{__('default.Confirm Password')}} *</label>
									<div class="input-group input-group-lg">
										<span class="input-group-text bg-light rounded-start border-0 text-secondary px-3"><i class="fas fa-lock"></i></span>
										<input type="password" class="form-control border-0 bg-light rounded-end ps-1" placeholder="*********" name="password_confirmation" id="password_confirmation" required>
									</div>
								</div>

								<!-- choose default characters -->
								<div class="mb-4">
									<label for="password_confirmation" class="form-label">{{__('default.Choose Character')}} *</label>
									<input type="hidden" name="profile_pic" id="profile_pic" value="/assets/images/avatar/head1.png" required>
									<div class="characters input-group-lg">
										<img src="/assets/images/avatar/head1.png" class="active" alt="">
										<img src="/assets/images/avatar/head2.png" alt="">
										<img src="/assets/images/avatar/head3.png" alt="">
										<img src="/assets/images/avatar/head4.png" alt="">
										<img src="/assets/images/avatar/head5.png" alt="">
										<img src="/assets/images/avatar/head6.png" alt="">
										<img src="/assets/images/avatar/head7.png" alt="">
										<img src="/assets/images/avatar/head8.png" alt="">
									</div>
								</div>

								<!-- Check box -->


								<div class="form-check form-check-info text-left mb-3">
									<input class="form-check-input" type="checkbox" name="policy" id="policy"
									       value="1" {{ old('policy', 0) ? 'checked' : '' }}>
									<label class="form-check-label" for="policy">
										terms url
									</label>
								</div>
								@if ($errors->has('policy'))
									<div id="policy-error" class="error text-danger pl-3" for="policy"
									     style="display: block;">
										<strong class="errors-field-pass">{{ $errors->first('policy') }}</strong>
									</div>
								@endif

								<!-- Button -->
								<div class="align-items-center mt-0">
									<div class="d-grid">
										<button class="btn btn-primary mb-0" type="submit">{{__('default.Sign Up')}}</button>
									</div>
								</div>

							<!-- Form END -->

							<!-- Social buttons -->
							<div class="row">
								<!-- Divider with text -->
								<div class="position-relative my-4">
									<hr>
									<p class="small position-absolute top-50 start-50 translate-middle bg-body px-5">{{__('default.Or')}}</p>
								</div>
								<!-- Social btn -->
								<div class="col-xxl-6 d-grid">
									<a href="{{ url('login/google')}}" class="btn btn-neutral btn-icon">
									<span class="btn-inner--icon">
											<img src="{{asset('assets/images/btn_google_signin_dark_normal_web.png')}}"></span>
									</a>
								</div>
								<!-- Social btn -->
								<div class="col-xxl-6 d-grid">
								</div>
							</div>

							<!-- Sign up link -->
							<div class="mt-4 text-center">
								<span>{!! __('default.Already Have Account Sign In',['url' => route('login')]) !!}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
<!-- **************** MAIN CONTENT END **************** -->

<!-- Back to top -->
<div class="back-top"><i class="bi bi-arrow-up-short position-absolute top-50 start-50 translate-middle"></i></div>

<!-- Bootstrap JS -->
<script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Template Functions -->
<script src="/assets/js/functions.js"></script>

</body>
</html>


