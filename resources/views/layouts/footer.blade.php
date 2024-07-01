<!-- footer START -->
<footer class="pt-5 bg-mode">
	<div class="container pt-4">
		<div class="row g-4">
			<div class="col-sm-6 col-lg-3">
				<!-- Footer Widget -->
				<img src="assets/images/logo/logo.png" alt="">
				<p class="mt-3">Fantastic Voyages Is About To Start </p>
			</div>
			<div class="col-sm-6 col-lg-3">
				<!-- Footer Widget -->
				<h5 class="mb-4">Download</h5>
				<ul class="nav flex-column">
					<li class="nav-item"><a class="nav-link pt-0" href="#"> <i class="bi bi-globe fa-fw pe-2"></i>Web Browser</a></li>
					<li class="nav-item"><a class="nav-link" href="#"> <i class="bi bi-windows fa-fw pe-2"></i>Windows</a></li>
					<li class="nav-item"><a class="nav-link" href="#"> <i class="bi bi-apple fa-fw pe-2"></i>macOS</a></li>
					<li class="nav-item"><a class="nav-link" href="#"> <i class="bi bi-phone fa-fw pe-2"></i>iOS &amp; Android</a></li>
				</ul>
			</div>
			<div class="col-sm-6 col-lg-3">
				<!-- Footer Widget -->
				<h5 class="mb-4">About</h5>
				<ul class="nav flex-column">
					<li class="nav-item"><a class="nav-link pt-0" href="{{route('about.page')}}"> About</a></li>
					<li class="nav-item"><a class="nav-link" href="{{route('onboarding.page')}}"> Onboarding</a></li>
				</ul>
			</div>
			<div class="col-sm-6 col-lg-3">
				<!-- Footer Widget -->
				<h5 class="mb-4">Resources</h5>
				<ul class="nav flex-column">
					<li class="nav-item"><a class="nav-link pt-0" href="#"> Join</a></li>
					<li class="nav-item"><a class="nav-link" href="{{route('help.page')}}"> Help Center</a></li>
					<li class="nav-item"><a class="nav-link" href="#"> Status</a></li>
					<li class="nav-item"><a class="nav-link" href="#"> Communities </a></li>
				</ul>
			</div>
		</div>
	</div>
	<hr class="mb-0 mt-5">
	<div class="bg- light py-3">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<!-- Footer nav START -->
					<ul class="nav justify-content-center justify-content-lg-start lh-1">
						<li class="nav-item">
							<a class="nav-link ps-0" href="#">Support </a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="{{route('terms.page')}}">Terms of Use</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="{{route('privacy.page')}}">Privacy &amp; terms</a>
						</li>
					</ul>
					<!-- Footer nav START -->
				</div>
				<div class="col-lg-6">
					<!-- Copyright START -->
					<p class="text-center text-md-end mb-0">©2024 <a class="text-body" href="https://www.fantastic-voyage.com"> Fantastic Voyage </a>All rights
						reserved.</p>
					<!-- Copyright END -->
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- footer END -->

{{--@include('layouts.modals')--}}

<?php
