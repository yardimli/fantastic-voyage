@extends('layouts.app')

@section('title', 'Welcome')

@section('content')



	<div class="container">
		<div class="row">
			<div class="col-12 my-auto">
				<h1 class="position-relative fs-2 text-center mt-2 mb-5">{{__('default.Terms & Conditions')}}</h1>
				<p>
					bla bla blb
				</p>
			</div>
		</div>
	</div>








@endsection



@push('scripts')<script>
	var current_page = 'payment_end';
	$(document).ready(function () {
		$('#payment_end').addClass('active');
	});</script>
@endpush
