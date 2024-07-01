@extends('layouts.app')

@section('title', 'On Boarding')

@section('content')
	
	<!-- Container START -->
		<div class="container" style="min-height: calc(88vh);">
			<div class="row g-4">
				<!-- Main content START -->
				<div class="col-lg-8 mx-auto">
					<!-- Card START -->
					<div class="card">
						<div class="card-header py-3 border-0 d-flex align-items-center justify-content-between">
							<h1 class="h5 mb-0">Welcome Aboard</h1>
						</div>
						<div class="card-body p-3">
							Start Your Fantastic Voyage Journey Today!
							<br><br>
							We appreciate your decision to join the Fantastic Voyage family.
							<br><br>
							To fully immerse yourself in our creative world, please verify your email address. Once your account is verified, you can start crafting incredible stories by clicking on the "Compose" link provided above.
							<br><br>
							To manage and view your stories, simply select the "My Stories" link above.
							<br><br>
							As you compose your story, provide essential details such as the Title, Genre, Writing Style, and Author you wish to emulate. This is an opportunity for you to give your work a unique identity and flavor.
							<br><br>
							Generate an eye-catching image using our AI Image Generator by supplying a prompt in the form of a sentence or paragraph. The AI Image Generator will produce an image that aligns with the provided prompt, enhancing your story's visual appeal.
							<br><br>
							All input boxes come with a "Continue writing..." button. By clicking on it, the AI will either create new content or continue from the point you left off, streamlining your storytelling experience.
							<br><br>
							We wish you the best of luck and endless fun in your writing endeavors and are excited to read your captivating stories.
							<br><br>
							Remember, you can always upgrade to a premium package, giving you the ability to make your stories private - exclusively for your reading pleasure!
							<br><br>
							Happy Writing!
							<br><br>
							The Fantastic Voyage Team
							<br><br>
							
							<div style="text-align: center; ">
								<img src="{{ asset('assets/logos/new_logo.png') }}"
								     style="max-width: 300px; width: 300px; height: 300px;" alt="Thank You" class="img-fluid">
							</div>
						</div>
					</div>
					<!-- Card END -->
				</div>
			</div> <!-- Row END -->
		</div>
		<!-- Container END -->
	
	
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
      var current_page = 'my.onboarding';
      $(document).ready(function () {
      });
	</script>
	<script src="{{ asset('assets/js/main.js') }}"></script>
	
@endpush
