@extends('layouts.app')

@section('title', 'Welcome')

@section('content')

	@include('layouts.user-banner')

	<!-- =======================	Page content START -->
	<section class="pt-0">
		<div class="container">
			<div class="row">

				@include('layouts.user-sidebar', ['active_menu' => 'profile'])
				<!-- Main content START -->
			<div class="col-xl-9">


				<!-- Account setting tab START -->
				<div class="tab-pane show active fade" id="nav-setting-tab-1">
					<!-- Account settings START -->
					<div class="card mb-4">
						<!-- Show success message if available -->
						@if (session('status'))
							<div class="alert alert-success" role="alert">
								{{ session('status') }}
							</div>
						@endif

						<!-- Title START -->
						<div class="card-header border-0 pb-0">
							<h1 class="h5 card-title">Account Settings</h1>
							<p class="mb-0">"Embark on a literary adventure: weave tales, inspire minds, and
								unleash the power of your pen as an aspiring writer"</p>
						</div>
						<!-- Card header START -->
						<!-- Card body START -->
						<div class="card-body">
							<!-- Form settings START -->

							<!-- Display success or error messages -->
							@if (session('success'))
								<div class="alert alert-success mt-2">
									{{ session('success') }}
								</div>
							@endif

							<form action="{{ route('profile.update') }}" method="post" class="row g-3"
							      enctype="multipart/form-data">
								@csrf
								<!-- First name -->
								<div class="col-sm-6 col-lg-6">
									<label class="form-label">Name</label>
									<input type="text" name="name" class="form-control" placeholder=""
									       value="{{ old('name', $user->name) }}">
								</div>
								<!-- User name -->
								<div class="col-sm-6">
									<label class="form-label">User name</label>
									<input type="text" name="username" class="form-control" placeholder=""
									       value="{{ old('username', $user->username) }}">
								</div>

								<!-- Email address -->
								<div class="col-sm-6">
									<label class="form-label">Email</label>
									<input type="email" name="email" class="form-control" placeholder=""
									       value="{{ old('email', $user->email) }}">
								</div>
								<!-- Page information -->
								<div class="col-12">
									<label class="form-label">About Me</label>
									<textarea name="about_me_input" id="about_me_input"
									          class="form-control input-for-generate"
									          rows="4"
									          placeholder="Description (Required)">{{ old('about_me', $user->about_me) }}</textarea>
									<div style="min-height: 50px;">
										<small id="about_me_char_count">Character limit: 0/364</small>


										<button type="button" id="generate_about_me_button"
										        class="btn btn-sm btn-secondary-soft mt-2 float-end"
										        onclick="generateContent('about_me')">Continue writing about me
											<div id="about_me_spinner" class="typing align-items-center ms-2"
											     style="min-height: 20px; display: none;">
												<div class="dot"></div>
												<div class="dot"></div>
												<div class="dot"></div>
											</div>
										</button>
									</div>
								</div>

								<!-- Avatar upload -->
								<div class="col-sm-6">
									<label class="form-label">Avatar</label>
									<input type="file" name="avatar" class="form-control" accept="image/*">
								</div>

								<!-- Background upload -->
								<div class="col-sm-6">
									<label class="form-label">Background</label>
									<input type="file" name="background_image" class="form-control"
									       accept="image/*">
								</div>

								<!-- Button -->
								<div class="col-12 text-start">
									<button type="submit" class="btn btn-sm btn-primary mb-0">Save changes
									</button>
								</div>

								<!-- Display success or error messages -->
								@if (session('success'))
									<div class="alert alert-success mt-2">
										{{ session('success') }}
									</div>
								@endif

								@if ($errors->any())
									<div class="alert alert-danger mt-2">
										<ul>
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								@endif
							</form>
							<!-- Settings END -->
						</div>
						<!-- Card body END -->

						<!-- Account settings END -->

						<!-- Change your password START -->

						<div class="card">
							<!-- Title START -->
							<div class="card-header border-0 pb-0">
								<h5 class="card-title">Change your password</h5>
								<p class="mb-0">If you signed up with Google, leave the current password blank
									the first time you
									update your password.</p>
							</div>
							<!-- Title START -->
							<div class="card-body">

								<form action="{{ route('profile.password.update') }}" method="post"
								      class="row g-3">
									@csrf
									<!-- Current password -->
									<div class="col-12">
										<label class="form-label">Current password</label>
										<input type="password" name="current_password" class="form-control"
										       placeholder="">
									</div>
									<!-- New password -->
									<div class="col-12">
										<label class="form-label">New password</label>
										<!-- Input group -->
										<div class="input-group">
											<input class="form-control fakepassword psw-input" type="password"
											       name="new_password" id="psw-input"
											       placeholder="Enter new password">
											<span class="input-group-text p-0">
                          <i class="fakepasswordicon fa-solid fa-eye-slash cursor-pointer p-2 w-40px"></i>
                        </span>
										</div>
										<!-- Pswmeter -->
										<div id="pswmeter" class="mt-2"></div>
										<div id="pswmeter-message" class="rounded mt-1"></div>
									</div>

									<!-- Confirm new password -->
									<div class="col-12">
										<label class="form-label">Confirm password</label>
										<input type="password" name="new_password_confirmation"
										       class="form-control" placeholder="">
									</div>
									<!-- Button -->
									<div class="col-12 text-end">
										<button type="submit" class="btn btn-primary mb-0">Update password
										</button>
									</div>

									<!-- Display success or error messages -->
									@if (session('success'))
										<div class="alert alert-success mt-2">
											{{ session('success') }}
										</div>
									@endif

									@if ($errors->any())
										<div class="alert alert-danger mt-2">
											<ul>
												@foreach ($errors->all() as $error)
													<li>{{ $error }}</li>
												@endforeach
											</ul>
										</div>
									@endif
								</form>

								<!-- Settings END -->
							</div>
						</div>
						<!-- Card END -->
					</div>
				</div>
				<!-- Account setting tab END -->

			</div>
			<!-- Main content END -->
		</div><!-- Row END -->
	</div>
</section>
<!-- ======================= Page content END -->

<!-- **************** MAIN CONTENT END **************** -->


@endsection



@push('scripts')<script>
	var current_page = 'payment_end';
	$(document).ready(function () {
	$('#payment_end').addClass('active');
	});</script>
@endpush







