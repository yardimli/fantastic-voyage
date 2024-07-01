<!-- ======================= Page Banner START -->
<section class="pt-0">
	<div class="container-fluid px-0">
		<div class="card bg-light h-200px h-md-200px rounded-0"
		     style="background:url({{ !empty(Auth::user()->background_image) ? Storage::url(Auth::user()->background_image) : '/assets/images/header/banner_web.png' }}) no-repeat; background-size:cover; position: relative;">
			<img class="banner_character banner_character" src="/assets/images/header/banner_character.png" style="position: absolute;">
		</div>
	</div>


	<div class="container mt-n4">
		<div class="row">
			<div class="col-12">
				<div class="card bg-transparent card-body pb-0 px-0 mt-2 mt-sm-0">
					<div class="row d-sm-flex justify-sm-content-between mt-2 mt-md-0">
						<!-- Avatar -->
						<div class="col-auto">
							<div
								class="avatar-xxl position-relative mt-n3 avatar-img rounded-circle border border-white border-3 shadow">
										<?php
										$addClass = '';
										if (!empty(Auth::user()->avatar)) {
											$addClass = 'avatar-xxl';
										}
										?>
									<img
										src="{{ !empty(Auth::user()->avatar) ? Storage::url(Auth::user()->avatar) : '/assets/images/avatar/parent.png' }}"
										alt="" class="rounded-circle {{ $addClass }}">
							</div>
						</div>
						<!-- Profile info -->
						<div class="col d-sm-flex justify-content-between align-items-center">
							<div>
									<h1 class="my-1 fs-4">{{Auth::user()->name}}</h1>
							</div>
							<!-- Button -->
							<div class="mt-2 mt-sm-0">
								@if (isset($active_menu) && $active_menu==='students')
									<div class="btn btn-sm btn-primary mb-0" id="newStudentButton">{{__('default.Add New Student')}}</div>
									{{--								<a href="#" class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#studentModal">{{__('default.Add New Student')}}</a>--}}
									{{--								<a href="{{ route('template.show', 'student-course-list') }}" class="btn btn-outline-primary mb-0">View--}}
									{{--									my courses</a>--}}
								@endif
							</div>
						</div>
					</div>
				</div>

				<!-- Advanced filter responsive toggler START -->
				<!-- Divider -->
				<hr class="d-xl-none">
				<div class="col-12 col-xl-3 d-flex justify-content-between align-items-center">
					<a class="h6 mb-0 fw-bold d-xl-none" href="{{ route('index') }}">Menu</a>
					<button class="btn btn-primary d-xl-none" type="button" data-bs-toggle="offcanvas"
					        data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
						<i class="fas fa-sliders-h"></i>
					</button>
				</div>
				<!-- Advanced filter responsive toggler END -->
			</div>
		</div>
	</div>
</section>
<!-- ======================= Page Banner END -->
