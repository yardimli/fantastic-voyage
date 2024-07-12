@extends('layouts.app')

@section('title', 'Quiz Activities')

@section('content')
	<!-- **************** MAIN CONTENT START **************** -->
	<main>
		<section class="pb-5 pt-0 pt-lg-5">
			<div class="container">
				<div class="card mt-2" style="min-height: 600px;">
					<div class="card-header pb-0 border-0">
						<div id="breadcrumbs" class="mt-2">
							<span class="breadcrumb-separator fas fa-chevron-right"></span>
							<span
								class="clickable-breadcrumb breadcrumb selected-breadcrumb">{{$type === 'quiz' ? "Quizzes" : "Stories"}}</span>
						</div>
					</div>
					<div class="card-body">
						<div class="ms-2 mt-2 pb-2 ps-5">
							<div class="row">
								@foreach($activities as $activity)
									<div class="col-6 col-xl-3 mb-3">
										@php
											$dataLink = '';
											if ($activity['type'] === 'quiz') {
													$dataLink = route('load-game-in-page', [$activity['id']]);
											} elseif ($activity['type'] === 'story') {
													$dataLink = route('load-story-in-page', [$activity['id']]);
											} elseif ($activity['type'] === 'cliffhanger') {
													$dataLink = route('load-cliffhanger-in-page', [$activity['id']]);
											}
										@endphp
										<div class="activity-card"
										     data-link="{{ $dataLink }}"
										     style="cursor:pointer; height: 200px; width: 100%; border: 1px solid #ccc; border-radius: 5px; background: {{ $activity['cover_image'] ? 'url('.str_replace('.png', '-512.jpg', $activity['cover_image']).') center/cover no-repeat' : '' }}; text-align: center; color:white;">
											<div class="d-flex flex-column justify-content-between align-items-center"
											     style="height: 180px;">
												<div class="w-100 d-flex justify-content-center">
													<h5
														style="background-color:rgba(0,0,0,0.7); margin-top:10px; width: 90%; padding:5px; border-radius: 5px; color: #fff; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);">{{ $activity['title'] }}</h5>
												</div>
												<div class="w-100 d-flex align-items-end">
													<div class="w-100 d-flex justify-content-center align-items-end">
														@if ($activity['type'] === 'quiz')
															<a href="{{ route('quiz-builder',[ $activity['id']]) }}"
															   class="no_decoration btn btn-info me-2"><i class="fas fa-edit"></i></a>
															<a href="{{ route('quiz-activities-action',['clone',$activity['id']]) }}"
															   class="no_decoration btn btn-info me-2"><i class="fas fa-copy"></i></a>
														@endif
														<span class="no_decoration btn btn-info delete_activity" data-id="{{ $activity['id'] }}"
														      style="cursor: pointer;"><i class="fas fa-trash"></i></span>
													</div>
												</div>
											</div>
										</div>
										</a>
									</div>
								@endforeach
							</div>
							<div class="mt-4">
								<a href="{{ route('quiz-builder') }}" class="btn btn-info">{{__('default.Create New Quiz')}}</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
	@include('layouts.footer')
	<div class="modal fade" id="delete-item-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					{{ __('default.Are you sure you want to delete this activity?') }}
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger">
						<a href="{{ route('quiz-activities-action',['delete','']) }}" id="delete-activity-confirm"
						   class="no_decoration">{{ __('default.Delete') }}</a>
					</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('default.Cancel') }}</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		$(document).ready(function () {
			
			$('.delete_activity').on('click', function (event) {
				event.preventDefault();
				event.stopPropagation();
				
				$('#delete-item-modal').modal('show');
				// set the data-id of the delete button to the id of the item to be deleted
				var activity_id = $(this).data('id');
				$('#delete-activity-confirm').attr('href', function () {
					return "{{ route('quiz-activities-action', ['delete', '']) }}/" + activity_id;
				});
			});
			
			$(".activity-card").on('click', function () {
				window.location.href = $(this).data('link');
			});
			
		});
	</script>
@endpush
