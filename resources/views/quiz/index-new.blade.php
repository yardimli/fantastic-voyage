@extends('layouts.app')

@section('title', 'Write a Story')

@section('content')
	
	<!-- **************** MAIN CONTENT START **************** -->
	<main>
		
		<!-- Hero event START -->
		<section class="pt-5 pb-0 position-relative" style="background-image: url(assets/images/bg/07.jpg); background-repeat: no-repeat; background-size: cover; background-position: top center;">
			<div class="bg-overlay bg-dark opacity-8"></div>
			<!-- Container START -->
			<div class="container">
				<div class="pt-5">
					<div class="row position-relative">
						<div class="col-xl-8 col-lg-10 mx-auto pt-sm-5 text-center">
							<!-- Title -->
							<h1 class="text-white">Design Your Journey</h1>
							<p class="text-white">Fill in a few details to start your Journey</p>
							<div class="mx-auto bg-mode shadow rounded p-4 mt-5">
								<!-- Form START -->
								<form class="row g-3 justify-content-center">
									<div class="col-md-12">
										<!-- What -->
										<div class="input-group">
											<textarea class="form-control form-control-lg me-1 pe-5" id="whats_it_about" type="text" placeholder="What is it about"></textarea>
										</div>
									</div>
									<div class="col-md-3">
										<div class="input-group">
											<select class="form-select form-select-lg me-1 pe-5" id="content_mode" aria-label="Default select example">
												<option selected>Mode</option>
												<option value="quiz">Quiz</option>
												<option value="story">Story</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="input-group">
											<select class="form-select form-select-lg me-1 pe-5" aria-label="Default select example" id="content_language">
												<option selected>Languages</option>
												<option value="en">English</option>
												<option value="tr">Turkish</option>
												<option value="zh_TW">Chinese</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="input-group">
											<select class="form-select form-select-lg me-1 pe-5" aria-label="Default select example" id="content_length">
												<option selected>Length</option>
												@for ($i = 1; $i <= 10; $i++)
													<option value="{{ $i }}">{{ $i }}</option>
												@endfor
											</select>
										</div>
									</div>
									<div class="col-md-2 d-grid">
										<!-- Search -->
										<div style="cursor: pointer;" class="btn btn-lg btn-primary" href="#">Start</div>
									</div>
								</form>
								<!-- Form END -->
							</div>
						</div>
						<div class="mb-n5 mt-3 mt-lg-5" style="height: 50px;">
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Hero event END -->
		
		<!-- Top Destinations START -->
		<section class="bg-mode pb-5 pt-0 pt-lg-5">
			<div class="container">
				<div class="row">
					<div class="col-12 mb-3">
						<!-- Title -->
						<h4>Top Destinations </h4>
					</div>
				</div>
				<div class="row g-4">
					<div class="col-sm-6 col-lg-3">
						<!-- Card START -->
						<div class="card card-overlay-bottom card-img-scale">
							<!-- Card Image -->
							<img class="card-img" src="assets/images/albums/02.jpg" alt="">
							<!-- Card Image overlay -->
							<div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
								<div class="w-100 mt-auto">
									<!-- Card title -->
									<h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">California</a></h5>
									<!-- Card info -->
									<span class="text-white small">Business & Conferences</span>
								</div>
							</div>
						</div>
						<!-- Card END -->
					</div>
					<div class="col-sm-6 col-lg-3">
						<!-- Card START -->
						<div class="card card-overlay-bottom card-img-scale">
							<!-- Card Image -->
							<img class="card-img" src="assets/images/albums/04.jpg" alt="">
							<!-- Card Image overlay -->
							<div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
								<div class="w-100 mt-auto">
									<!-- Card title -->
									<h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">Los Angeles</a></h5>
									<!-- Card info -->
									<span class="text-white small">Events & Parties</span>
								</div>
							</div>
						</div>
						<!-- Card END -->
					</div>
					<div class="col-sm-6 col-lg-3">
						<!-- Card START -->
						<div class="card card-overlay-bottom card-img-scale">
							<!-- Card Image -->
							<img class="card-img" src="assets/images/albums/05.jpg" alt="">
							<!-- Card Image overlay -->
							<div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
								<div class="w-100 mt-auto">
									<!-- Card title -->
									<h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">London</a></h5>
									<!-- Card info -->
									<span class="text-white small">Arts & Entertainment</span>
								</div>
							</div>
						</div>
						<!-- Card END -->
					</div>
					<div class="col-sm-6 col-lg-3">
						<!-- Card START -->
						<div class="card card-overlay-bottom card-img-scale">
							<!-- Card Image -->
							<img class="card-img" src="assets/images/albums/01.jpg" alt="">
							<!-- Card Image overlay -->
							<div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
								<div class="w-100 mt-auto">
									<!-- Card title -->
									<h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">London</a></h5>
									<!-- Card info -->
									<span class="text-white small">Arts & Entertainment</span>
								</div>
							</div>
						</div>
						<!-- Card END -->
					</div>
				</div>
			</div>
		</section>
		<!-- Top Destinations END -->
		
		<!-- Explore Groups START -->
		<section class="pt-5 pb-5">
			<div class="container">
				<div class="row">
					<div class="col-12 mb-3">
						<!-- Title -->
						<h4>Top Destinations </h4>
					</div>
				</div>
				<div class="row g-4">
					<div class="col-sm-6 col-lg-3">
						<!-- Card START -->
						<div class="card card-overlay-bottom card-img-scale">
							<!-- Card Image -->
							<img class="card-img" src="assets/images/albums/02.jpg" alt="">
							<!-- Card Image overlay -->
							<div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
								<div class="w-100 mt-auto">
									<!-- Card title -->
									<h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">California</a></h5>
									<!-- Card info -->
									<span class="text-white small">Business & Conferences</span>
								</div>
							</div>
						</div>
						<!-- Card END -->
					</div>
					<div class="col-sm-6 col-lg-3">
						<!-- Card START -->
						<div class="card card-overlay-bottom card-img-scale">
							<!-- Card Image -->
							<img class="card-img" src="assets/images/albums/04.jpg" alt="">
							<!-- Card Image overlay -->
							<div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
								<div class="w-100 mt-auto">
									<!-- Card title -->
									<h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">Los Angeles</a></h5>
									<!-- Card info -->
									<span class="text-white small">Events & Parties</span>
								</div>
							</div>
						</div>
						<!-- Card END -->
					</div>
					<div class="col-sm-6 col-lg-3">
						<!-- Card START -->
						<div class="card card-overlay-bottom card-img-scale">
							<!-- Card Image -->
							<img class="card-img" src="assets/images/albums/05.jpg" alt="">
							<!-- Card Image overlay -->
							<div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
								<div class="w-100 mt-auto">
									<!-- Card title -->
									<h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">London</a></h5>
									<!-- Card info -->
									<span class="text-white small">Arts & Entertainment</span>
								</div>
							</div>
						</div>
						<!-- Card END -->
					</div>
					<div class="col-sm-6 col-lg-3">
						<!-- Card START -->
						<div class="card card-overlay-bottom card-img-scale">
							<!-- Card Image -->
							<img class="card-img" src="assets/images/albums/01.jpg" alt="">
							<!-- Card Image overlay -->
							<div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
								<div class="w-100 mt-auto">
									<!-- Card title -->
									<h5 class="text-white"><a href="#" class="btn-link text-reset stretched-link">London</a></h5>
									<!-- Card info -->
									<span class="text-white small">Arts & Entertainment</span>
								</div>
							</div>
						</div>
						<!-- Card END -->
					</div>
				</div>
			</div>
		</section>
		<!-- Explore Groups END -->
	
	</main>
	<!-- **************** MAIN CONTENT END **************** -->
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
		var current_page = 'index';
		$(document).ready(function () {
			
			function BuildQuiz() {
				var quiz_type = 'quiz';
				var next_num = 0;
				var next_id = 0;
				var jobId = Date.now(); // create unique job ID based on timestamp
				var activity_id = 0;
				
				xhr = $.ajax({
					type: "POST",
					url: "/quiz-content-builder-json",
					data: {
						quiz_type: quiz_type,
						activity_id: activity_id,
						user_content: $('whats_it_about').val(),
						language: $('#content_language').val(),
						quantity: $('#content_length').val(),
						next_num: next_num,
						next_id: next_id,
						jobId: jobId
					},
					headers: {
						'X-CSRF-TOKEN': csrfToken
					},
					beforeSend: function () {
						$('#spinIcon').addClass('fa-spin');
						$('#spinIcon').css('display', 'inline-block');
					},
					success: function (data) {
						if (data == '') {
							showMessage('Something went wrong with the AI. Please try again.');
						} else {
							//go to the activity editor http://localhost:8004/quiz-builder/1 where 1 is the activity id
						}
						
						setTimeout(function () {
							$('#add-content-modal').modal('hide');
							disableDeleteItemButton();
						}, 1000);
						
					},
					complete: function () {
						$('#spinIcon').removeClass('fa-spin');
						$('#spinIcon').css('display', 'none');
					}
				});
			}
			
			
		});
	</script>

@endpush
