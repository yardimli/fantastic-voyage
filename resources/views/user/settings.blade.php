@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
	
	@include('layouts.user-banner')
	
	<!-- =======================	Page content START -->
	<section class="pt-0">
		<div class="container">
			<div class="row">
				
				@include('layouts.user-sidebar', ['active_menu' => 'settings'])
				
				<!-- Main content START -->
			<div class="col-xl-9">
				<!-- Privacy START -->
				<div class="border rounded-3">
					<div class="row">
						<div class="col-12">
							<!-- Card START -->
							<div class="card bg-transparent">
								<!-- Card header START -->
								<div class="card-header bg-transparent border-bottom">
									<h3 class="card-header-title">Settings</h3>
								</div>
								<!-- Card header END -->

								<!-- Card body START -->
								<div class="card-body">

									<!-- Profile START -->
									<h5 class="mb-4">Profile Settings</h5>
									<div class="form-check form-switch form-check-md">
										<input class="form-check-input" type="checkbox" role="switch" id="profilePublic" checked>
										<label class="form-check-label" for="profilePublic">Your profile's public visibility</label>
									</div>
									<!-- Profile START -->

									<hr><!-- Divider -->

									<!-- Buttons -->
									<div class="d-sm-flex justify-content-end">
										<button type="button" class="btn btn-sm btn-primary me-2 mb-0">Save changes</button>
										<a href="#" class="btn btn-sm btn-outline-secondary mb-0">Cancel</a>
									</div>

								</div>
								<!-- Card body END -->
							</div>
							<!-- Card END -->
						</div>
						<!-- Privacy END -->
					</div>
				</div>
				<!-- Main content END -->
			</div><!-- Row END -->
		</div>
	</div>
</section>
<!-- ======================= Page content END -->

<!-- **************** MAIN CONTENT END **************** -->


@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
		var current_page = 'settings';
		$(document).ready(function () {
		});
	</script>
	<script src="{{ asset('assets/js/main.js') }}"></script>
	
@endpush
