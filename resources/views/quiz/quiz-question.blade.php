<div class="input-container">

	<div class="image-container question-image mini-side-button ms-1" data-block_type="question"
	     data-id="{{ $question['id'] }}">
		@if ($question['image'] !== null && $question['image'] !== '')
			@if(File::exists(public_path($question['image'])))
			<img src="{{ $question['image'] }}" class="img-fluid question-image-src" style="padding: 2px;"
			     id="image-src-{{ $question['id'] }}" data-id="{{ $question['id'] }}" data-block_type="question">
			<i class="far fa-image fa-lg d-none" id="image-src-placeholder-{{ $question['id'] }}"
			   data-id="{{ $question['id'] }}"
			   data-block_type="question" style="color:#ccc;"></i>
			@else
				<img src="" data-image_suggestion="{{ $question['image'] }}" class="img-fluid question-image-src" style="padding: 2px;"
				     id="image-src-{{ $question['id'] }}" data-id="{{ $question['id'] }}" data-block_type="question">
				<i class="far fa-image fa-lg" id="image-src-placeholder-{{ $question['id'] }}"
				   data-id="{{ $question['id'] }}"
				   data-block_type="question" style="color:#ccc;"></i>
			@endif
		@else
			<img src="" class="img-fluid d-none question-image-src" style="padding: 2px;" data-id="{{ $question['id'] }}"
			     data-block_type="question" id="image-src-{{ $question['id'] }}">
			<i class="far fa-image fa-lg" id="image-src-placeholder-{{ $question['id'] }}" data-id="{{ $question['id'] }}"
			   data-block_type="question" style="color:#ccc;"></i>
		@endif
	</div>
	<div class="vertical-ruler"></div>
	@if ($question['audio'] !== '' && $question['audio'] !== null)
		<div class="audio-container question-audio mini-side-button" data-id="{{ $question['id'] }}"
		     data-block_type="question">
			<i class="fas fa-volume-up" id="audio-src-{{ $question['id'] }}" data-id="{{ $question['id'] }}" data-text="{{$question['audio_tts']}}"
			   data-src="{{ $question['audio'] }}" data-voice="{{ $question['voice_id'] }}"
			   data-block_type="question" style="color:green;"></i>
		</div>
	@else
		<div class="audio-container question-audio mini-side-button" data-id="{{ $question['id'] }}"
		     data-block_type="question">
			<i class="fas fa-volume-up" id="audio-src-{{ $question['id'] }}" data-id="{{ $question['id'] }}" data-text=""
			   data-src="" data-voice=""
			   data-block_type="question" style="color:#ccc;"></i>
		</div>
	@endif
	<div class="vertical-ruler"></div>
	<div class="item-input-wrapper">
		<div contenteditable="true" draggable="false" spellcheck="false" id="question-text-{{ $question['id'] }}"
		     class="item-input question-text">{{$question['text']}}</div>
	</div>
</div>
