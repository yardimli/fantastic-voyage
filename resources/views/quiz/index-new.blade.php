@extends('layouts.app')

@section('title', 'Write a Story')

@section('content')
	
	<!-- **************** MAIN CONTENT START **************** -->
	<main>
		
		<!-- Hero event START -->
		<section class="pt-5 pb-0 position-relative"
		         style="background-image: url(assets/images/bg/07.jpg); background-repeat: no-repeat; background-size: cover; background-position: top center;">
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
								<div class="row g-3 justify-content-center">
									<div class="col-md-9">
										<!-- What -->
										<div class="input-group">
											<textarea class="form-control me-1 pe-5" id="whats_it_about" type="text"
											          placeholder="What is it about"></textarea>
										</div>
									</div>
									<div class="col-md-3 d-grid">
										<!-- Search -->
										<button style="cursor: pointer;" class="btn btn-primary" id="build_voyage">
											Start
											<div id="build_voyage_spinner" class="typing align-items-center ms-2"
											     style="min-height: 20px; display: none;">
												<div class="dot"></div>
												<div class="dot"></div>
												<div class="dot"></div>
											</div>
										</button>
									</div>
									<div class="col-md-3">
										<div class="input-group">
											<select class="form-select me-1 pe-5" id="content_type"
											        aria-label="Default select example">
												<option value="" selected>Type</option>
												<option value="quiz">Quiz</option>
												<option value="story">Story</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="input-group">
											<select class="form-select me-1 pe-5" aria-label="Default select example"
											        id="content_language">
												<option value="" selected>Languages</option>
												<option value="English">English</option>
												<option value="Turkish">Turkish</option>
												<option value="Traditional Chinese">Chinese</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="input-group">
											@foreach($voices['voices'] as $voice)
													<?php
//													var_dump($voice);
													?>
											@endforeach
											<select class="form-select me-1 pe-5" aria-label="Default select example"
											        id="voice_id">
												<option selected for="voice_id">Silent</option>
												@foreach($voices['voices'] as $voice)
														<?php
//														var_dump($voice);
														?>
													@if (isset($voice['labels']['gender']))
														<option value="{{ $voice['voice_id'] }}"
														        data-voice-name="{{$voice['name']}}">{{ $voice['name'] }}
															({{$voice['labels']['gender'] ??''}} {{$voice['labels']['age'] ??''}} {{$voice['labels']['accent'] ??''}} {{$voice['labels']['description'] ??''}}
															)
														</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="input-group">
											<select class="form-select me-1 pe-5" aria-label="Default select example"
											        id="content_length">
												<option value="" selected>Length</option>
												@for ($i = 1; $i <= 10; $i++)
													<option value="{{ $i }}">{{ $i }}</option>
												@endfor
											</select>
										</div>
									</div>
								</div>
								<!-- Form END -->
								<div class="progress mt-4" style="height: 5px; display: none;">
									<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" id="progress-bar"></div>
								</div>
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
		
		<audio id="audio-preview" controls style="max-width: 200px; max-height: 34px; display: none;"></audio>
	
	</main>
	<!-- **************** MAIN CONTENT END **************** -->
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
		var current_page = 'index';
		var progress_interval = null;
		
		function updateProgressBar(duration) {
			var $progressBar = $('#progress-bar');
			var $progressContainer = $progressBar.closest('.progress');
			$progressContainer.show(); // Show the progress bar container
			
			var start = 0;
			var intervalDuration = 100; // Update interval in ms
			var increment = (intervalDuration / duration) * 100; // percentage to increase at each interval
			
			clearInterval(progress_interval);
			progress_interval = setInterval(function() {
				start += increment;
				$progressBar.css('width', start + '%');
				
				if (start >= 100) {
					clearInterval(progress_interval);
					$progressContainer.hide(); // Optionally hide the progress bar when complete
				}
			}, intervalDuration);
		}
		
		$(document).ready(function () {
			
			$("#voice_id").change(function () {
				var voice_id = $(this).val();
				var voice_name = $(this).find(':selected').data('voice-name');
				
				$.ajax({
					url: '/convert-text-to-speech',
					type: 'POST',
					data: {
						voice_id: voice_id,
						text: 'hi there, I\'m ' + voice_name + ' how are you? Do you like my voice?'
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function (response) {
						$('#audio-preview').attr('src', response.audio_path);
						$('#audio-preview')[0].play();
					},
					error: function (err) {
						console.error(err);
					}
				});
			});
			
			$('#build_voyage').click(function () {
				//disable the button to prevent multiple clicks
				$("#build_voyage").prop('disabled', true);
				$("#build_voyage_spinner").css('display', 'inline-flex');
				
				
				var content_type = $('#content_type').val() || 'quiz';
				var next_num = 0;
				var next_id = 0;
				var activity_id = 0;
				var language = $('#content_language').val() || 'English';
				var content_length = $('#content_length').val() || 1;
				var user_content = $('#whats_it_about').val() || 'Kittens';
				var voice_id = $('#voice_id').val();
				
				var totalDuration = 20 * 1000 + (content_length - 1) * 10 * 1000;
				
				xhr = $.ajax({
					type: "POST",
					url: "/quiz-content-builder-json",
					data: {
						content_type: content_type,
						activity_id: activity_id,
						user_content: user_content,
						language: language,
						voice_id: voice_id,
						quantity: content_length,
						next_num: next_num,
						next_id: next_id,
						return_json: true
					},
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}'
					},
					beforeSend: function () {
						updateProgressBar(totalDuration); // start the progress bar
						// $('#spinIcon').addClass('fa-spin');
						// $('#spinIcon').css('display', 'inline-block');
					},
					success: function (data) {
						$("#build_voyage").prop('disabled', false);
						$("#build_voyage_spinner").css('display', 'none');
						console.log(data);
						if (data == '') {
							showMessage('Something went wrong with the AI. Please try again.');
						} else {
							if (content_type==='quiz') {
								window.location.href = '/load-game-in-page/' + data.activity_id;
							} else
							{
								window.location.href = '/load-story-in-page/' + data.activity_id;
							}
						}
					},
					complete: function () {
						// $('#spinIcon').removeClass('fa-spin');
						// $('#spinIcon').css('display', 'none');
					},
					error: function (err) {
						console.error(err);
					}
				});
			});
		});
	</script>

@endpush
