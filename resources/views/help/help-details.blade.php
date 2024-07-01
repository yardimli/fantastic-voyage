@extends('layouts.app', ['page_route' => 'help.page'])

@section('title', 'Help')

@section('content')
		<!-- Container START -->
		<div class="container">
			<!-- Main content START -->
			
			<!-- Help search START -->
			<div class="row align-items-center pt-5 pb-5 pb-lg-3">
				<div class="col-md-3">
					@include('layouts.svg3-image')
				</div>
				<!-- Card START -->
				<div class="col-md-6 text-center">
					<!-- Title -->
					<h1>Hi Cer, we're here to help.</h1>
					<p class="mb-4">Search here to get answers to your questions.</p>
				</div>
				<div class="col-md-3">
					@include('layouts.svg4-image')
				</div>
			</div>
			<!-- Help search START -->
			
			<?php
				function parseFAQ($text)
				{
					$lines = preg_split('/\r\n|\r|\n/', $text);
					
					$categories      = [];
					$currentCategory = '';
					
					foreach ($lines as $line) {
						if (strpos($line, '===') === 0) {
							$currentCategory              = trim(substr($line, 3));
							$categories[$currentCategory] = [];
						} elseif (preg_match('/^Q[0-9]+:/', $line)) {
							$question = trim(preg_replace('/^Q[0-9]+:/', '', $line));
						} elseif (preg_match('/^A[0-9]+:/', $line)) {
							$answer                         = trim(preg_replace('/^A[0-9]+:/', '', $line));
							$categories[$currentCategory][] = ['question' => $question, 'answer' => $answer];
						}
					}
					
					return $categories;
				}
				
				$help_array = parseFAQ(file_get_contents(public_path('texts/faq.txt')));
			
			?>
				
				
				<!-- Article Single  -->
			<div class="row">
				<div class="col-12 vstack gap-4">
					
					<div class="card border p-sm-4">
						<!-- Article title -->
						<div class="card-header border-0 py-0">
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb breadcrumb-dots mb-2">
									<li class="breadcrumb-item"><a href="/"><i class="bi bi-house me-1"></i> Home</a></li>
									<li class="breadcrumb-item"><a href="/help"><i class="bi bi-info-circle me-1"></i> Help</a></li>
									<li class="breadcrumb-item active">{{$topic}}</li>
								</ol>
							</nav>
							<h2>{{$topic}}</h2>
							<!-- Update and author -->
						</div>
						<!-- Article Info -->
						<div class="card-body">
							@foreach($help_array[$topic] as $faq)
								<h5 class="mt-4">{{$faq['question']}}</h5>
								<p>{{$faq['answer']}}</p>
							@endforeach
						</div>
					
					</div>
				</div>
			</div>
			<!-- Article Single  -->
		</div> <!-- Row END -->
		<!-- Container END -->
	
	<!-- **************** MAIN CONTENT END **************** -->
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
      var current_page = 'help.details';
      $(document).ready(function () {
      });
	</script>
	<script src="{{ asset('assets/js/main.js') }}"></script>
	
@endpush
