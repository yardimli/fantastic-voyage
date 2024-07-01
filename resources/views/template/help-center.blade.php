<!DOCTYPE html>
<html lang="en_US">
<head>
	<title>Eduport - LMS, Education and Course Theme</title>

	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Webestica.com">
	<meta name="description" content="Eduport- LMS, Education and Course Theme">

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

<!-- Header START -->
<header class="navbar-light navbar-sticky">
	<!-- Logo Nav START -->
	<nav class="navbar navbar-expand-xl">
		<div class="container">
			<!-- Logo START -->
			<a class="navbar-brand me-0" href="{{ route('index') }}">
				<img class="light-mode-item navbar-brand-item" src="/assets/images/logo-new-en.png" alt="logo">
			</a>
			<!-- Logo END -->

			<!-- Responsive navbar toggler -->
			<button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-animation">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>

			<!-- Main navbar START -->
			<div class="navbar-collapse collapse" id="navbarCollapse">

				<!-- Nav Search END -->
				<ul class="navbar-nav navbar-nav-scroll mx-auto">

					<!-- Nav item 1 Demos -->
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="{{ route('template.show.home') }}" id="demoMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Demos</a>
						<ul class="dropdown-menu" aria-labelledby="demoMenu">
							<li> <a class="dropdown-item" href="{{ route('template.show.home') }}">Home Default</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'index-2') }}">Home Education</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'index-3') }}">Home Academy</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'index-4') }}">Home Course</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'index-5') }}">Home University</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'index-6') }}">Home Kindergarten</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'index-7') }}">Home Landing</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'index-8') }}">Home Tutor</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'index-9') }}">Home School</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'index-10') }}">Home Abroad</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'index-11') }}">Home Workshop</a></li>
						</ul>
					</li>

					<!-- Nav item 2 Pages -->
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="{{ route('template.show.home') }}" id="pagesMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
						<ul class="dropdown-menu" aria-labelledby="pagesMenu">
							<!-- Dropdown submenu -->
							<li class="dropdown-submenu dropend">
								<a class="dropdown-item dropdown-toggle" href="{{ route('template.show.home') }}">Course</a>
								<ul class="dropdown-menu dropdown-menu-start" data-bs-popper="none">
									<li> <a class="dropdown-item" href="{{ route('template.show', 'course-categories') }}">Course Categories</a></li>
									<li> <hr class="dropdown-divider"></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'course-grid') }}">Course Grid Classic</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'course-grid-2') }}">Course Grid Minimal</a></li>
									<li> <hr class="dropdown-divider"></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'course-list') }}">Course List Classic</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'course-list-2') }}">Course List Minimal</a></li>
									<li> <hr class="dropdown-divider"></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'course-detail') }}">Course Detail Classic</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'course-detail-min') }}">Course Detail Minimal</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'course-detail-adv') }}">Course Detail Advance</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'course-detail-module') }}">Course Detail Module</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'course-video-player') }}">Course Full Screen Video</a></li>
								</ul>
							</li>

							<!-- Dropdown submenu -->
							<li class="dropdown-submenu dropend">
								<a class="dropdown-item dropdown-toggle" href="{{ route('template.show.home') }}">About</a>
								<ul class="dropdown-menu dropdown-menu-start" data-bs-popper="none">

									<li> <a class="dropdown-item" href="{{ route('template.show', 'blog-grid') }}">Blog Grid</a></li>

									<li> <a class="dropdown-item" href="{{ route('template.show', 'blog-detail') }}">Blog Detail</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'pricing') }}">Pricing</a></li>
								</ul>
							</li>

							<!-- Dropdown submenu -->
							<li class="dropdown-submenu dropend">
								<a class="dropdown-item dropdown-toggle" href="{{ route('template.show.home') }}">Hero Banner</a>
								<ul class="dropdown-menu dropdown-menu-start" data-bs-popper="none">
									<li> <a class="dropdown-item" href="{{ route('template.show', 'docs/snippet-hero-12') }}">Hero Form</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'docs/snippet-hero-13') }}">Hero Vector</a></li>
									<li> <p class="dropdown-item mb-0">Coming soon....</p></li>
								</ul>
							</li>

							<li> <a class="dropdown-item" href="{{ route('template.show', 'instructor-list') }}">Instructor List</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'instructor-single') }}">Instructor Single</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'become-instructor') }}">Become an Instructor</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'abroad-single') }}">Abroad Single</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'workshop-detail') }}">Workshop Detail</a></li>
							<li> <a class="dropdown-item" href="{{ route('template.show', 'event-detail') }}">Event Detail <span class="badge bg-success ms-2 smaller">New</span></a></li>

							<!-- Dropdown submenu -->
							<li class="dropdown-submenu dropend">
								<a class="dropdown-item dropdown-toggle" href="{{ route('template.show.home') }}">Shop</a>
								<ul class="dropdown-menu dropdown-menu-start" data-bs-popper="none">
									<li> <a class="dropdown-item" href="{{ route('template.show', 'shop') }}">Shop grid</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'shop-product-detail') }}">Product detail</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'cart') }}">Cart</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'checkout') }}">Checkout</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'empty-cart') }}">Empty Cart</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'wishlist') }}">Wishlist</a></li>
								</ul>
							</li>

							<!-- Dropdown submenu -->
							<li class="dropdown-submenu dropend">
								<a class="dropdown-item dropdown-toggle" href="{{ route('template.show.home') }}">Help</a>
								<ul class="dropdown-menu dropdown-menu-start" data-bs-popper="none">
									<li> <a class="dropdown-item" href="{{ route('template.show', 'help-center') }}">Help Center</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'help-center-detail') }}">Help Center Single</a></li>
									<li> <a class="dropdown-item" href="{{ route('help.page') }}">FAQs</a></li>
								</ul>
							</li>

							<!-- Dropdown submenu -->
							<li class="dropdown-submenu dropend">
								<a class="dropdown-item dropdown-toggle" href="{{ route('template.show.home') }}">Authentication</a>
								<ul class="dropdown-menu dropdown-menu-start" data-bs-popper="none">
									<li> <a class="dropdown-item" href="{{ route('template.show', 'sign-in') }}">Sign In</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'sign-up') }}">Sign Up</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'forgot-password') }}">Forgot Password</a></li>
								</ul>
							</li>

							<!-- Dropdown submenu -->
							<li class="dropdown-submenu dropend">
								<a class="dropdown-item dropdown-toggle" href="{{ route('template.show.home') }}">Form</a>
								<ul class="dropdown-menu dropdown-menu-start" data-bs-popper="none">
									<li> <a class="dropdown-item" href="{{ route('template.show', 'request-demo') }}">Request a demo</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'book-class') }}">Book a Class</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'request-access') }}">Free Access</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'university-admission-form') }}">Admission Form</a></li>
								</ul>
							</li>

							<!-- Dropdown submenu -->
							<li class="dropdown-submenu dropend">
								<a class="dropdown-item dropdown-toggle" href="{{ route('template.show.home') }}">Specialty</a>
								<ul class="dropdown-menu dropdown-menu-start" data-bs-popper="none">
									<li> <a class="dropdown-item" href="{{ route('template.show', 'error-404') }}">Error 404</a></li>
									<li> <a class="dropdown-item" href="{{ route('template.show', 'coming-soon') }}">Coming Soon</a></li>
								</ul>
							</li>
						</ul>
					</li>

					<!-- Nav item 3 Pages -->


					<!-- Nav item 4 link-->

				</ul>
			</div>
			<!-- Main navbar END -->
			<div class="navbar-nav">
				<button class="btn btn-sm btn-dark mb-0"><i class="bi bi-power me-2"></i>Sign In</button>
			</div>

		</div>
	</nav>
	<!-- Logo Nav END -->
</header>
<!-- Header END -->

<!-- **************** MAIN CONTENT START **************** -->
<main>
<!-- ======================= Page Banner START -->
<section class="bg-primary bg-opacity-10">
	<div class="container">
		<div class="row">
			<!-- Content START -->
			<div class="col-lg-8 mx-auto text-center">
				<!-- Title -->
				<h1 class="display-6">Search Solution. Get Support</h1>
				<p class="mb-0">Search here to get answers to your questions.</p>
				<!-- Search bar -->
				<form class="bg-body rounded p-2 mt-4">
					<div class="input-group">
						<input class="form-control border-0 me-1" type="text" placeholder="Search question...">
						<button type="button" class="btn btn-dark mb-0 rounded">Search</button>
					</div>
				</form>

				<!-- Popular questions START -->
				<div class="row mt-4 align-items-center">
					<div class="col-12">
						<h5 class="mb-3">Popular questions</h5>
						<!-- Questions List START -->
						<div class="list-group list-group-horizontal gap-2 justify-content-center flex-wrap mb-0 border-0">
							<a class="btn btn-white btn-sm fw-light" href="{{ route('template.show', 'help-center-detail') }}"> How can we help?</a>
							<a class="btn btn-white btn-sm fw-light" href="{{ route('template.show', 'help-center-detail') }}"> How to upload data to the system? </a>
							<a class="btn btn-white btn-sm fw-light" href="{{ route('template.show', 'help-center-detail') }}"> Installation Guide? </a>
							<a class="btn btn-white btn-sm fw-light" href="{{ route('template.show', 'help-center-detail') }}"> How to view expired course? </a>
							<a class="btn btn-white btn-sm fw-light" href="{{ route('template.show', 'help-center-detail') }}"> What's are the difference between a social?</a>
							<a class="btn btn-primary-soft btn-sm fw-light" href="#!">View all question</a>
						</div>
						<!-- Questions list END -->
					</div>
				</div>
				<!-- Popular questions END -->
			</div>
      <!-- Content END -->

			<!-- Image -->
			<div class="col-12 mt-6">
				<img src="/assets/images/element/help-center.svg" class="w-100" alt="">
			</div>
		</div>
	</div>
</section>
<!-- =======================
Page Banner END -->

<!-- =======================
Recommended topics START -->
<section>
	<div class="container">
			<!-- Titles -->
			<div class="row">
				<div class="col-12 text-center">
					<h2 class="text-center mb-4">Recommended Topics</h2>
				</div>
			</div>

			<!-- Row START -->
			<div class="row g-4">
				<div class="col-md-6 col-xl-3">
					<!-- Card START -->
					<div class="card bg-light h-100">
						<!-- Title -->
						<div class="card-header bg-light pb-0 border-0">
							<i class="bi bi-emoji-smile fs-1 text-success"></i>
							<h5 class="card-title mb-0 mt-2">Get started </h5>
						</div>
						<!-- List -->
						<div class="card-body">
							<ul class="nav flex-column">
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Gulp and Customization</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Color Scheme and Logo Settings</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Dark mode, RTL Version and Lazy Load</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Sources, Credits and Changelog</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Updates and Support</a></li>
							</ul>
						</div>
					</div>
					<!-- Card END -->
				</div>

				<div class="col-md-6 col-xl-3">
					<!-- Card START -->
					<div class="card bg-light h-100">
						<!-- Title -->
						<div class="card-header bg-light pb-0 border-0">
							<i class="bi bi-layers fs-1 text-warning"></i>
							<h5 class="card-title mb-0 mt-2">Account Setup</h5>
						</div>
						<!-- List -->
						<div class="card-body">
							<ul class="nav flex-column">
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Connecting to your Account</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Edit your profile information</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Connecting to other Social Media Accounts</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Adding your profile picture</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Describing your store</a></li>
							</ul>
						</div>
					</div>
					<!-- Card END -->
				</div>

				<div class="col-md-6 col-xl-3">
					<!-- Card START -->
					<div class="card bg-light h-100">
						<!-- Title -->
						<div class="card-header bg-light pb-0 border-0">
							<i class="bi bi-info-circle fs-1 text-orange"></i>
							<h5 class="card-title mb-0 mt-2">Other Topics </h5>
						</div>
						<!-- List -->
						<div class="card-body">
							<ul class="nav flex-column">
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Security &amp; Privacy</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Author, Publisher &amp; Admin Guides</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Pricing plans</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Sales Tax &amp; Regulatory Fees</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 me-2"></i>Promotions &amp; Deals</a></li>
							</ul>
						</div>
					</div>
					<!-- Card END -->
				</div>

				<div class="col-md-6 col-xl-3">
					<!-- Card START -->
					<div class="card bg-light h-100">
						<!-- Title START -->
						<div class="card-header bg-light pb-0 border-0">
							<i class="bi bi-house fs-1 text-purple"></i>
							<h5 class="card-title mb-0 mt-2">Advanced Usage </h5>
						</div>
						<!-- List -->
						<div class="card-body">
							<ul class="nav flex-column">
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 fa-fw me-2"></i>Admin &amp; Billing</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 fa-fw me-2"></i>Become a Pro</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 fa-fw me-2"></i>Mobile application</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 fa-fw me-2"></i>Guide</a></li>
								<li class="nav-item"><a class="nav-link d-flex" href="{{ route('template.show', 'help-center-detail') }}"><i class="fas fa-angle-right text-primary pt-1 fa-fw me-2"></i>Promotions &amp; Deals</a></li>
							</ul>
						</div>
					</div>
					<!-- Card END -->
				</div>
			</div>
			<!-- Row END -->
	</div>
</section>
<!-- =======================
Recommended topics END -->

<!-- =======================
Popular articles START -->
<section>
	<div class="container">
		<!-- Titles -->
		<div class="row">
			<div class="col-12 text-center">
				<h2 class="text-center mb-4">Popular Articles</h2>
			</div>
		</div>

		<div class="row g-4">

			<div class="col-xl-6">
				<!-- Card item START -->
				<div class="card card-body border p-4 h-100">
					<!-- Title -->
					<h4 class="card-title mb-4"><a href="{{ route('template.show.home') }}" class="stretched-link">The installation part</a></h4>

					<!-- Avatar group and content -->
					<div class="d-sm-flex align-items-center">
						<!-- Avatar group -->
						<ul class="avatar-group mb-2 mb-sm-0">
							<li class="avatar avatar-md">
								<img class="avatar-img rounded-circle border-white" src="/assets/images/avatar/01.jpg" alt="avatar">
							</li>
							<li class="avatar avatar-md">
								<img class="avatar-img rounded-circle border-white" src="/assets/images/avatar/02.jpg" alt="avatar">
							</li>
							<li class="avatar avatar-md">
								<img class="avatar-img rounded-circle border-white" src="/assets/images/avatar/03.jpg" alt="avatar">
							</li>
						</ul>
						<!-- Content -->
						<div class="ms-sm-2">
							<h6 class="mb-1">10 articles in this collection</h6>
							<p class="mb-0">Written by <b>Carolyn Ortiz</b> ,<b>Billy Vasquez</b> and <b>Larry Lawson</b></p>
						</div>
					</div>
				</div>
				<!-- Card item END -->
			</div>

			<div class="col-xl-6">
				<!-- Card item START -->
				<div class="card card-body border p-4 h-100">
					<!-- Title -->
					<h4 class="card-title mb-4"><a href="{{ route('template.show.home') }}" class="stretched-link">Supporting Customer With Inbox</a></h4>

					<!-- Avatar group and content -->
					<div class="d-sm-flex align-items-center">
						<!-- Avatar group -->
						<ul class="avatar-group mb-2 mb-sm-0">
							<li class="avatar avatar-md">
								<img class="avatar-img rounded-circle border-white" src="/assets/images/avatar/08.jpg" alt="avatar">
							</li>
							<li class="avatar avatar-md">
								<img class="avatar-img rounded-circle border-white" src="/assets/images/avatar/04.jpg" alt="avatar">
							</li>
						</ul>
						<!-- Content -->
						<div class="ms-sm-2">
							<h6 class="mb-1">5 articles in this collection</h6>
							<p class="mb-0">Written by <b>Dennis Barrett</b> and <b>Louis Ferguson</b></p>
						</div>
					</div>
				</div>
				<!-- Card item END -->
			</div>

			<div class="col-xl-6">
				<!-- Card item START -->
				<div class="card card-body border p-4 h-100">
					<!-- Title -->
					<h4 class="card-title mb-4"><a href="{{ route('template.show.home') }}" class="stretched-link">Sending Effective Emails </a></h4>

					<!-- Avatar group and content -->
					<div class="d-sm-flex align-items-center">
						<!-- Avatar group -->
						<ul class="avatar-group mb-2 mb-sm-0">
							<li class="avatar avatar-md">
								<img class="avatar-img rounded-circle border-white" src="/assets/images/avatar/05.jpg" alt="avatar">
							</li>
						</ul>
						<!-- Content -->
						<div class="ms-sm-2">
							<h6 class="mb-1">4 articles in this collection</h6>
							<p class="mb-0">Written by <b>Jacqueline Miller</b></p>
						</div>
					</div>
				</div>
				<!-- Card item END -->
			</div>

			<div class="col-xl-6">
				<!-- Card item START -->
				<div class="card card-body border p-4 h-100">
					<!-- Title -->
					<h4 class="card-title mb-4"><a href="{{ route('template.show.home') }}" class="stretched-link">Connect With Customers</a></h4>

					<!-- Avatar group and content -->
					<div class="d-sm-flex align-items-center">
						<!-- Avatar group -->
						<ul class="avatar-group mb-2 mb-sm-0">
							<li class="avatar avatar-md">
								<img class="avatar-img rounded-circle border-white" src="/assets/images/avatar/09.jpg" alt="avatar">
							</li>
							<li class="avatar avatar-md">
								<img class="avatar-img rounded-circle border-white" src="/assets/images/avatar/10.jpg" alt="avatar">
							</li>
						</ul>
						<!-- Content -->
						<div class="ms-sm-2">
							<h6 class="mb-1">11 articles in this collection</h6>
							<p class="mb-0">Written by <b>Lori Stevens</b> and <b>Samuel Bishop</b></p>
						</div>
					</div>
				</div>
				<!-- Card item END -->
			</div>

		</div> <!-- Row END -->
	</div>
</section>
<!-- =======================
Popular articles END -->

<!-- =======================
Action boxes START -->
<section>
	<div class="container">
		<div class="row g-4">

			<!-- Action box item -->
			<div class="col-lg-4">
				<div class="bg-info bg-opacity-10 rounded-3 p-5">
					<!-- Icon -->
					<h2 class="display-5 text-info"><i class="bi bi-headset"></i></h2>
					<!-- Title -->
					<h3>Contact Support?</h3>
					<p>Delay death ask to style Me mean able conviction For every delay death ask to style</p>
					<!-- Button -->
					<a href="{{ route('template.show.home') }}" class="btn btn-dark mb-0">Contact Us</a>
				</div>
			</div>

			<!-- Action box item -->
			<div class="col-lg-4">
				<div class="bg-purple bg-opacity-10 rounded-3 p-5">
					<!-- Icon -->
					<h2 class="display-5 text-purple"><i class="fas fa-ticket-alt"></i></h2>
					<!-- Title -->
					<h3>Submit a Ticket</h3>
					<p>Prosperous impression had conviction For every delay death ask to style Me mean able</p>
					<!-- Button -->
					<a href="{{ route('template.show.home') }}" class="btn btn-dark mb-0">Submit Ticket</a>
				</div>
			</div>

			<!-- Action box item -->
			<div class="col-lg-4">
				<div class="bg-warning bg-opacity-15 rounded-3 p-5">
					<!-- Icon -->
					<h2 class="display-5 text-warning"><i class="bi bi-envelope-fill"></i></h2>
					<!-- Title -->
					<h3>Request a feature</h3>
					<p>Prosperous impression had conviction For every delay death ask to style Me mean able</p>
					<!-- Button -->
					<a href="{{ route('template.show.home') }}" class="btn btn-dark mb-0">Request</a>
				</div>
			</div>

		</div> <!-- Row END -->
	</div>
</section>
<!-- =======================
Action boxes ENd -->

</main>
<!-- **************** MAIN CONTENT END **************** -->

<!-- =======================
Footer START -->
<footer class="pt-5">
	<div class="container">
		<!-- Row START -->
		<div class="row g-4">

			<!-- Widget 1 START -->
			<div class="col-lg-3">
				<!-- logo -->
				<a class="me-0" href="{{ route('template.show.home') }}">
					<img class="light-mode-item h-40px" src="/assets/images/logo-new-en.png" alt="logo">
				</a>
				<p class="my-3">Eduport education theme, built specifically for the education centers which is dedicated to teaching and involve learners. </p>
				<!-- Social media icon -->
				<ul class="list-inline mb-0 mt-3">
					<li class="list-inline-item"> <a class="btn btn-white btn-sm shadow px-2 text-facebook" href="{{ route('template.show.home') }}"><i class="fab fa-fw fa-facebook-f"></i></a> </li>
					<li class="list-inline-item"> <a class="btn btn-white btn-sm shadow px-2 text-instagram" href="{{ route('template.show.home') }}"><i class="fab fa-fw fa-instagram"></i></a> </li>
					<li class="list-inline-item"> <a class="btn btn-white btn-sm shadow px-2 text-twitter" href="{{ route('template.show.home') }}"><i class="fab fa-fw fa-twitter"></i></a> </li>
					<li class="list-inline-item"> <a class="btn btn-white btn-sm shadow px-2 text-linkedin" href="{{ route('template.show.home') }}"><i class="fab fa-fw fa-linkedin-in"></i></a> </li>
				</ul>
			</div>
			<!-- Widget 1 END -->

			<!-- Widget 2 START -->
			<div class="col-lg-6">
				<div class="row g-4">
					<!-- Link block -->
					<div class="col-6 col-md-4">
						<h5 class="mb-2 mb-md-4">Company</h5>
						<ul class="nav flex-column">
							<li class="nav-item"><a class="nav-link" href="{{ route('template.show.home') }}">Contact us</a></li>
							<li class="nav-item"><a class="nav-link" href="{{ route('template.show.home') }}">News and Blogs</a></li>
							<li class="nav-item"><a class="nav-link" href="{{ route('template.show.home') }}">Library</a></li>
							<li class="nav-item"><a class="nav-link" href="{{ route('template.show.home') }}">Career</a></li>
						</ul>
					</div>

					<!-- Link block -->
					<div class="col-6 col-md-4">
						<h5 class="mb-2 mb-md-4">Community</h5>
						<ul class="nav flex-column">
							<li class="nav-item"><a class="nav-link" href="{{ route('template.show.home') }}">Documentation</a></li>
							<li class="nav-item"><a class="nav-link" href="{{ route('template.show.home') }}">Faq</a></li>
							<li class="nav-item"><a class="nav-link" href="{{ route('template.show.home') }}">Forum</a></li>
							<li class="nav-item"><a class="nav-link" href="{{ route('template.show.home') }}">Sitemap</a></li>
						</ul>
					</div>

					<!-- Link block -->
					<div class="col-6 col-md-4">
						<h5 class="mb-2 mb-md-4">Teaching</h5>
						<ul class="nav flex-column">
							<li class="nav-item"><a class="nav-link" href="{{ route('template.show.home') }}">Become a teacher</a></li>
							<li class="nav-item"><a class="nav-link" href="{{ route('template.show.home') }}">How to guide</a></li>
							<li class="nav-item"><a class="nav-link" href="{{ route('template.show.home') }}">Terms &amp; Conditions</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- Widget 2 END -->

			<!-- Widget 3 START -->
			<div class="col-lg-3">
				<h5 class="mb-2 mb-md-4">Contact</h5>
				<!-- Time -->
				<p class="mb-2">
					Toll free:<span class="h6 fw-light ms-2">+1234 568 963</span>
					<span class="d-block small">(9:AM to 8:PM IST)</span>
				</p>

				<p class="mb-0">Email:<span class="h6 fw-light ms-2">example@gmail.com</span></p>

				<div class="row g-2 mt-2">
					<!-- Google play store button -->
					<div class="col-6 col-sm-4 col-md-3 col-lg-6">
						<a href="{{ route('template.show.home') }}"> <img src="/assets/images/client/google-play.svg" alt=""> </a>
					</div>
					<!-- App store button -->
					<div class="col-6 col-sm-4 col-md-3 col-lg-6">
						<a href="{{ route('template.show.home') }}"> <img src="/assets/images/client/app-store.svg" alt="app-store"> </a>
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
					<div class="text-primary-hover"> Copyrights <a href="{{ route('template.show.home') }}" class="text-body">©2023 Eduport</a>. All rights reserved. </div>
					<!-- copyright links-->
					<div class="justify-content-center mt-3 mt-lg-0">
						<ul class="nav list-inline justify-content-center mb-0">
							<li class="list-inline-item">
								<!-- Language selector -->
								<div class="dropup mt-0 text-center text-sm-end">
									<a class="dropdown-toggle nav-link" href="{{ route('template.show.home') }}" role="button" id="languageSwitcher" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fas fa-globe me-2"></i>Language
									</a>
									<ul class="dropdown-menu min-w-auto" aria-labelledby="languageSwitcher">
										<li><a class="dropdown-item me-4" href="{{ route('template.show.home') }}"><img class="fa-fw me-2" src="/assets/images/flags/uk.svg" alt="">English</a></li>
										<li><a class="dropdown-item me-4" href="{{ route('template.show.home') }}"><img class="fa-fw me-2" src="/assets/images/flags/gr.svg" alt="">German </a></li>
										<li><a class="dropdown-item me-4" href="{{ route('template.show.home') }}"><img class="fa-fw me-2" src="/assets/images/flags/sp.svg" alt="">French</a></li>
									</ul>
								</div>
							</li>
							<li class="list-inline-item"><a class="nav-link" href="{{ route('template.show.home') }}">Terms of use</a></li>
							<li class="list-inline-item"><a class="nav-link pe-0" href="{{ route('template.show.home') }}">Privacy policy</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- =======================
Footer END -->

<!-- Back to top -->
<div class="back-top"><i class="bi bi-arrow-up-short position-absolute top-50 start-50 translate-middle"></i></div>

<!-- Bootstrap JS -->
<script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Template Functions -->
<script src="/assets/js/functions.js"></script>

</body>
</html>
