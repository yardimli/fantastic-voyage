<!-- footer START -->
<footer class="bg-mode py-3">
	<div class="container">
		@if (1===2)
		<div class="row">
			<div class="col-md-6">
				<!-- Footer nav START -->
				<ul class="nav justify-content-center justify-content-md-start lh-1">
					<li class="nav-item">
						<a class="nav-link" href="{{route('about.page')}}">About</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('onboarding.page')}}">Onboarding</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('help.page')}}">Help </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="">Terms</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="">Privacy</a>
					</li>
				</ul>
				<!-- Footer nav START -->
			</div>
			<div class="col-md-6">
				<!-- Copyright START -->
				<p class="text-center text-md-end mb-0">©2023 <a class="text-body" href="https://www.fantastic-voyage.com"> CoolXue </a>All rights
					reserved.</p>
				<!-- Copyright END -->
			</div>
		</div>
		@endif
	</div>
</footer>
<!-- footer END -->
