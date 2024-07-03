<!DOCTYPE html>
<html lang="en_US">
<head>
	<title>{{__('default.Fantastic Voyage') }} - {{$sub_title ?? 'Login'}}</title>
	
	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="{{__('site_domain')}}">
	<meta name="description" content="{{__('default.Fantastic Voyage') }} - {{$sub_title ?? 'Login'}}">
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
							<h2 class="fw-bold">{{__('default.Welcome to Fantastic Voyage!')}}</h2>
							<p class="mb-0 h6 fw-light">{{__('default.Let\'s learn something new today!')}}</p>
						</div>
						<!-- SVG Image -->
						<img src="/assets/images/characters/login.png" class="mt-5" alt="">
						<!-- Info -->
						<div class="d-sm-flex mt-5 align-items-center justify-content-center">
							<!-- Content -->
							<p class="mb-0 h6 fw-light ms-0 ms-sm-3">{{__('default.Its your turn to join.')}}</p>
						</div>
						<ul class="mt-4 text-center">
							<a class="fw-bold ps-0 pe-2"
							   href="{{ route('index') }}">{{__('default.Home')}}</a>
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
							<h1 class="fs-2">{{__('default.Login to your account')}}</h1>
{{--							<p class="lead mb-4">{{__('default.Nice to see you! Please Sign up with your account.')}}</p>--}}

							<!-- Form START -->
							<form roll="form" class="" method="POST" action="{{ route('login') }}">
								@csrf
								<div class="mb-3 position-relative input-group-lg">
									<input type="email" class="form-control"
									       aria-label="Email" id="exampleEmails" name="email" placeholder="{{ __('default.Enter Email...') }}"
									       value="{{ old('email', '') }}" required>
									@include('alerts.feedback', ['field' => 'email'])
									<span class="form-group email-error {{ $errors->has('email') ? ' has-danger' : '' }}"></span>
								</div>
								<!-- New password -->
								<div class="mb-3">
									<!-- Input group -->
									<div class="input-group input-group-lg">
										<input type="password" class="form-control fakepassword psw-input" aria-label="Password"
										       id="psw-input" name="password"
										       placeholder="{{ __('default.Password') }}" value="" required>
										<span class="input-group-text p-0">
                    <i class="fakepasswordicon fas fa-solid fa-eye-slash cursor-pointer p-2 w-40px"></i>
							</span>
										<span class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                  </span>
										@include('alerts.feedback', ['field' => 'password'])
									</div>
								</div>
								<!-- Remember me -->
								<div class="mb-3 d-sm-flex justify-content-between">
									<div>
										<input class="form-check-input" type="checkbox"
										       name="remember" {{ old('remember') ? 'checked' : '' }} id="rememberCheck">
										<label class="form-check-label" for="rememberCheck">{{__('default.Remember me')}}?</label>
									</div>
									<a href="/forgot-password">{{__('default.Forgot password?')}}</a>
								</div>
								<!-- Button -->
								<!-- Button -->
								<style>
                    .styles_divider___jUn1 {
                        display: flex;
                        margin: 24px 0 16px;
                        flex-direction: row;
                        align-items: center;
                        color: #ccc;
                    }

                    @media (max-width: 480px) {
                        .styles_divider___jUn1 {
                            margin: 5px 0;
                        }
                    }

                    .styles_divider___jUn1:before {
                        margin-right: 8px;
                    }

                    .styles_divider___jUn1:after, .styles_divider___jUn1:before {
                        content: "";
                        flex-grow: 1;
                        border-top: 1px solid #ccc;
                    }

                    .styles_divider___jUn1:after {
                        margin-left: 8px;
                    }
								</style>

								<div class="d-grid">
									<button type="submit" class="btn btn-lg btn-primary-soft">{{__('default.Login')}}</button>

									<div class="styles_divider___jUn1">{{(__('default.Or Continue With'))}}</div>

									<a href="{{ url('login/google')}}" class="btn btn-neutral btn-icon">
										<span class="btn-inner--icon">
											<img src="{{asset('assets/images/btn_google_signin_dark_normal_web.png')}}"></span>
										</span>
									</a>
								</div>

								<div class="mt-4 text-center">
									<span>{{__('default.Not a member yet?')}} <a href="{{ route('register') }}">{{__('default.Sign Up')}}</a></span>
								</div>
							</form>
					</div> <!-- Row END -->
				</div>
			</div> <!-- Row END -->
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













<!-- =======================
Footer END -->

<!-- **************** MAIN CONTENT END **************** -->

<!-- =======================
JS libraries, plugins and custom scripts -->

<!-- Bootstrap JS -->
<script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Vendors -->
<script src="/assets/vendor/pswmeter/pswmeter.min.js"></script>

<!-- Theme Functions -->
<script src="/assets/js/functions.js"></script>

</body>
</html>
