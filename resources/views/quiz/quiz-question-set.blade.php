<div class="single-question row" data-id="{{$question['id']}}">
	
	<div class="col-1" style="text-align: right; ">
		<span class="question-number" data-question_num="{{ $question_number ?? '' }}"
		      style="font-size: 21px; font-weight: bold; ">
			{{ $question_number ?? '' }}.
		</span>
	
	</div>
	
	<div class="question-container col-12 col-xs-12 col-md-10 col-lg-10 col-xl-10 mb-4">
		<div class="card">
			<div class="card-header">
				<h3 class="question-container-title">{{__('default.Question')}}</h3>
				@include('quiz.quiz-question', ['question' => $question])
			</div>
			
			<div class="card-body">
				<h3 class="question-container-title">{{__('default.Answers')}}</h3>
				<div class="row answers-container">
					<div class="col-12 col-lg-6 col-xl-6 p-0">
						@include('quiz.quiz-answer', ['answer' => $answers[0] ?? ['letter' => 'A', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => $question['id'].'A1']])
						@include('quiz.quiz-answer', ['answer' => $answers[1] ?? ['letter' => 'B', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => $question['id'].'A2']])
						@include('quiz.quiz-answer', ['answer' => $answers[2] ?? ['letter' => 'C', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => $question['id'].'A3']])
					</div>
					<div class="col-12 col-lg-6 col-xl-6 p-0">
						@include('quiz.quiz-answer', ['answer' => $answers[3] ?? ['letter' => 'D', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => $question['id'].'A4']])
						@include('quiz.quiz-answer', ['answer' => $answers[4] ?? ['letter' => 'E', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => $question['id'].'A5']])
						@include('quiz.quiz-answer', ['answer' => $answers[5] ?? ['letter' => 'F', 'text'=>'', 'image' => '', 'audio'=>'', 'id' => $question['id'].'A6']])
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-12 col-xs-12 col-md-1 col-lg-1 col-xl-1 d-flex">
		<div class="d-inline-flex order-item-button mini-side-button" drag-handle>
			<i class="fas fa-sort"></i>
		</div>
		<div class="d-inline-flex clone-item-button mini-side-button">
			<i class="fas fa-copy"></i>
		</div>
		
		<div class="d-inline-flex delete-item-button mini-side-button">
			<i class="fas fa-trash"></i>
		</div>
	
	
	</div>
</div>
