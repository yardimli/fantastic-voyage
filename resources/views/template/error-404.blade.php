@extends('layouts.app')

@section('title', 'Welcome')

@section('content')



	<section class="pt-5">
	<div class="container">
		<div class="row">
			<div class="col-12 text-center">
				<!-- Image -->
				<img src="/assets/images/element/error404-01.svg" class="h-200px h-md-400px mb-4" alt="">
				<!-- Title -->
				<h1 class="display-1 text-danger mb-0">404</h1>
				<!-- Subtitle -->
				<h2>{{ $message ??  'Oh no, something went wrong!'}}</h2>
				<!-- info -->
				<p class="mb-4">Either something went wrong or this page doesn't exist anymore.</p>
				<!-- Button -->
				<a href="{{ route('index') }}" class="btn btn-primary mb-0">Take me to Homepage</a>
			</div>
		</div>
	</div>
</section>




@endsection



@push('scripts')<script>
	var current_page = 'payment_end';
	$(document).ready(function () {
	$('#payment_end').addClass('active');
	});</script>
@endpush
