<div class="col-12 col-md-6 col-lg-6 col-xl-4 d-flex">
	<a href="{{route('quiz-builder')}}" class="quiz-template-link"><div class="card flex-fill mb-3 template-card">
		<div class="row g-0">
			<div class="col-3 col-md-3">
				<img src="{{$image}}" class="img-fluid rounded-start" alt="...">
			</div>
			<div class="col-9 col-md-9">
				<div class="card-body" style="padding-bottom: 4px;">
					<h5 class="card-title">{{ __('default.'.$name) }}</h5>
					<p class="card-text" style="font-size:14px;">{{ __('default.'.$description) }}</p>
				</div>
			</div>
		</div>
	</div></a>
</div>
