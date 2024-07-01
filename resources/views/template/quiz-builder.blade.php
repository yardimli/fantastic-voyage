@extends('layouts.app')

@section('title', 'Quiz')

@section('content')
	
	<div class="container mt-5 mb-5 pb-5 pt-5">
		
		<div class="card mt-2">
			
			<div class="card-header pb-0 border-0">
				
				<div id="breadcrumbs" class="">
					<span class="breadcrumb-separator fas fa-chevron-right"></span>
					<a class="clickable-breadcrumb breadcrumb" href="/quiz-activities">{{__('default.My Activities')}}</a>
					<span class="breadcrumb-separator fas fa-chevron-right"></span>
					<span class="breadcrumb selected-breadcrumb">{{__('default.Enter Content')}}</span>
					<span class="breadcrumb-separator fas fa-chevron-right"></span>
					<span class="breadcrumb">{{__('default.Play')}}</span>
				</div>
			</div>

			<?php
				//load json from public/quiz-json/science-1.json
//		$json = file_get_contents(public_path('quiz-json/science-1.json'));
				if ($quiz_json !== null) {
					//convert json object to php associative array
					$quiz = json_decode($quiz_json, true);
					$question_number = 0;
					$quiz_title = $quiz['title'] ?? '';
					$article = $quiz['data_json']['article'] ?? '';
					$articleImg = $quiz['data_json']['articleImg'] ?? '';
					$articleAudio = $quiz['data_json']['articleAudio'] ?? '';
					$articleAudioTts = $quiz['data_json']['articleAudioText'] ?? '';
					$articleAudioVoice = $quiz['data_json']['articleAudioVoice'] ?? '';
					$questions = $quiz['data_json']['questions'] ?? [];
				}
			?>
			
			
			<div class="card-body">
				<div class="activity-title mt-4 col-10 ms-4 mb-4">
					<div class="activity-title-text mb-2">{{__('default.Activity Title')}}</div>
					<input type="hidden" id="activity_id" value="{{ $activity_id }}">
					<div class="activity-title-input">
						<input type="text" class="form-control" id="activity-title-input" placeholder="Enter Title"
						       value="{{ $quiz_title }}"
						       style="font-size: 20px; font-weight: bold;">
					</div>
				</div>
				
				<div class="js-createContent-button createContent-button ms-4">
					<a href="#" class="create-article" data-type="quiz" data-activity-id="{{ $activity_id }}"><span
							class="fa fa-plus"></span>{{__('default.Create Article')}}</a>
				</div>
				<div class="mt-2 ms-4 mb-4" style="display: flex">
					<div class="ms-1 col-1" style="margin: auto 5px; text-align: center; font-size: 28px;">
						<div class="article-image article-image-container mini-side-button" data-id="article"
						     style="display:inline-block;">
							@if ($articleImg !== null && $articleImg !== '')
								@if(File::exists(public_path($articleImg)))
									<img src="{{ $articleImg }}" class="img-fluid article-image-src" style="padding: 2px;"
									     data-id="article"
									     id="image-src-article" data-block_type="article">
									<i class="far fa-image fa-lg d-none" id="image-src-placeholder-article" data-id="article"
									   data-block_type="article" style="color:#ccc;"></i>
								@else
									<img src="" data-image_suggestion="{{ $articleImg }}" class="img-fluid article-image-src"
									     data-id="article" style="padding: 2px;"
									     id="image-src-article" data-block_type="article">
									<i class="far fa-image fa-lg" id="image-src-placeholder-article" data-block_type="article"
									   data-id="article" style="color:#ccc;"></i>
								@endif
							@else
								<img src="" class="img-fluid d-none article-image-src" data-id="article" style="padding: 2px;"
								     data-block_type="article" id="image-src-article">
								<i class="far fa-image fa-lg" id="image-src-placeholder-article" data-block_type="article"
								   data-id="article"
								   style="color:#ccc;"></i>
							@endif
						</div>
						<div class="vertical-ruler"></div>
						<div style="display:inline-block;">
							@if ($articleAudio !== '' && $articleAudio !== null)
								<div class="audio-container article-audio mini-side-button" data-id="article-audio"
								     data-block_type="article">
									<i class="fas fa-volume-up" id="audio-src-article-audio" data-id="{{ $articleAudio }}"
									   data-text="{{ $articleAudioTts }}"
									   data-src="{{ $articleAudio }}" data-voice="{{ $articleAudioVoice }}"
									   data-block_type="article" style="color:green;"></i>
								</div>
							@else
								<div class="audio-container article-audio mini-side-button" data-id="article-audio"
								     data-block_type="article">
									<i class="fas fa-volume-up" id="audio-src-article-audio" data-id="article-audio" data-text=""
									   data-src="" data-voice=""
									   data-block_type="article" style="color:#ccc;"></i>
								</div>
							@endif
						</div>
					</div>
					{{--				<i class="far fa-image fa-lg" id="image-src-placeholder-article" data-id="article" style="color:#ccc;"></i></div>--}}
					<div class="col-10">
						<textarea class="quiz-article p-2" style="width: 100%; min-height: 100px;">{{ $article }}</textarea>
					</div>
				</div>
				
				
				<div class="js-createContent-button createContent-button ms-4">
					<a href="#" class="create-content" data-type="quiz" data-activity-id="{{ $activity_id }}"><span
							class="fa fa-plus"></span>{{__('default.Create Quiz')}}</a>
					<span class="enableCreateText" style="color: indianred; font-size: 10px;">[ {{__('default.Please write some article to enable this button.')}} ] </span>
				</div>
				
				
				<div class="questions-list mt-4">
					@if(!empty($questions))
						@foreach ($questions as $question)
								<?php
								$question_number++;
								?>
							@include('template.quiz-question-set', ['question_number' => $question_number, 'question' => $question,  'answers' => $question['answers'] ])
						@endforeach
					@else
							<?php
							$question_number++;
							?>
						@include('template.quiz-question-set', ['question_number' => $question_number, 'question' => ['id' => 'Q1', 'image' => '', 'audio'=>'', 'text' => ''],  'answers' => [
					['letter' => 'A', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'Q1A1'],
					['letter' => 'B', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'Q1A2'],
					['letter' => 'C', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'Q1A3'],
					['letter' => 'D', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'Q1A4'],
					['letter' => 'E', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'Q1A5'],
					['letter' => 'F', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'Q1A6'] ]
					])
					@endif
				</div>
				
				<div style="display: none;" class="empty-question-container">
					@include('template.quiz-question-set', ['question_number' => '0', 'question' => ['id' => 'QBlank', 'image' => '', 'audio'=>'', 'text' => ''],  'answers' => [
					['letter' => 'A', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'QBlankA1'],
					['letter' => 'B', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'QBlankA2'],
					['letter' => 'C', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'QBlankA3'],
					['letter' => 'D', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'QBlankA4'],
					['letter' => 'E', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'QBlankA5'],
					['letter' => 'F', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => 'QBlankA6'] ]
					])
				</div>
				
				<div class="editor-add-item js-editor-add-item no-select " style="display: inline-block;">
					<span class="fas fa-plus"></span>{{__('default.Add a question')}}
					<span class="add-range">{{ __('default.itemCount', ['min' => '1','max' => '100']) }}</span>
				</div>
			</div>
			<div class="card-footer">
				<div class="text-end me-5">
					<button type="button" class="preview-quiz btn btn-secondary btn-lg"
					        data-preview="in-page">{{__('default.Preview')}}</button>
					<button type="button" class="preview-quiz btn btn-info btn-lg"
					        data-preview="full-screen">{{__('default.Full Screen Preview')}}</button>
					<button type="button" class="editor-done btn btn-primary btn-lg">{{__('default.Done')}}</button>
					<div class="quiz_builder_message alert alert-success d-none" style="margin-top: 10px;"></div>
				</div>
			</div>
		
		</div>
	
	</div>
	
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	
	@include('template.quiz-modals')
	@include('layouts.footer')

@endsection

@push('scripts')
	<script src="/assets/js/quiz-builder.js"></script>
	<script>
		QuestionIdCounter = {{$question_number}};
		var translations = {
			pause: '<?php
				        echo __("default.Pause"); ?>',
			play: '<?php
				       echo __("default.Play Voice"); ?>',
			forExample: '<?php
				             echo __("default.For example"); ?>',
			deleteQuestion: '<?php
				                 echo __("default.Are you sure you want to delete this question?"); ?>',
		};
	</script>
@endpush
