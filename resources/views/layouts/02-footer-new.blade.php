<!-- =======================
Footer START -->
<footer class="bg-light pt-5">
	<div class="container">
		<!-- Row START -->
		<div class="row g-4">
			
			<!-- Widget 1 START -->
			<div class="col-lg-3">
				<!-- logo -->
				<a class="me-0" href="{{ route('index') }}">
					<img class="light-mode-item h-40px" src="/assets/logos/epa-logo-temp.png" alt="logo">
					<img class="dark-mode-item h-40px" src="/assets/logos/epa-logo-temp.png" alt="logo">
				</a>
				<p
					class="my-3">{{__('default.footer side text')}}</p>
				<!-- Social media icon -->
				<ul class="list-inline mb-0 mt-3">
					<li class="list-inline-item"><a class="btn btn-white btn-sm shadow px-2 text-facebook"
					                                href="{{ route('index') }}"><i
								class="fab fa-fw fa-facebook-f"></i></a></li>
					<li class="list-inline-item"><a class="btn btn-white btn-sm shadow px-2 text-instagram"
					                                href="{{ route('index') }}"><i
								class="fab fa-fw fa-instagram"></i></a></li>
					<li class="list-inline-item"><a class="btn btn-white btn-sm shadow px-2 text-twitter"
					                                href="{{ route('index') }}"><i
								class="fab fa-fw fa-twitter"></i></a></li>
					<li class="list-inline-item"><a class="btn btn-white btn-sm shadow px-2 text-linkedin"
					                                href="{{ route('index') }}"><i
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
							                        href="{{ route('index') }}">{{__('default.Home')}}</a></li>
							<li class="nav-item"><a class="nav-link"
							                        href="{{ route('template.show.home') }}">{{__('default.Blog')}}</a>
							</li>
						</ul>
					</div>
					
					<!-- Link block -->
					<div class="col-6 col-md-4">
						<h5 class="mb-2 mb-md-4">{{__('default.Community')}}</h5>
						<ul class="nav flex-column">
							<li class="nav-item"><a class="nav-link"
							                        href="{{ route('template.show.home') }}">{{__('default.Help')}}</a></li>
							<li class="nav-item"><a class="nav-link"
							                        href="{{ route('template.show.home') }}">{{__('default.Forum')}}</a></li>
							<li class="nav-item"><a class="nav-link"
							                        href="{{ route('template.show.home') }}">{{__('default.sitemap')}}</a></li>
						</ul>
					</div>
					
					<!-- Link block -->
					<div class="col-6 col-md-4">
						<h5 class="mb-2 mb-md-4">{{__('default.Help')}}</h5>
						<ul class="nav flex-column">
							<li class="nav-item"><a class="nav-link"
							                        href="{{ route('template.show.home') }}">{{__('default.How to guide')}}</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- Widget 2 END -->
			
			<!-- Widget 3 START -->
			<div class="col-lg-3">
				<!-- Time -->
				<p class="mb-2">
					Toll free:<span class="h6 fw-light ms-2">0975 812 1911</span>
					<span class="d-block small">(09:00 - 17:00)</span>
				</p>
				
				<p class="mb-0">{{__('default.Email')}}:<span class="h6 fw-light ms-2">{{__('default.contact_email')}}</span>
				</p>
				
				<div class="row g-2 mt-2">
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
