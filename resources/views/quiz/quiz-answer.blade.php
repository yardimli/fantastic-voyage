<div class="input-container">
	<div class="answer-letter">{{ $answer['letter'] ?? '!' }}</div>
	<div class="checkbox-container"><?php
	                                $answer_text = $answer['answer_text'] ?? $answer['text'];
	                                if ($answer_text == '' && $answer['image'] == '' && $answer['audio'] == '') {?>
		<i class="fas fa-times fa-lg m-1 checkbox-disabled"></i>
			<?php } else { if ($answer['isCorrect'] === true || $answer['isCorrect'] === "true") { ?>
		<i class="fas fa-check fa-lg m-1 checkbox-enabled" style="color:green;"></i><?php } else { ?>
		<i class="fas fa-times fa-lg m-1 checkbox-enabled" style="color: red;"></i><?php }
		} ?>
	</div>
	<div class="image-container answer-image mini-side-button ms-1" data-id="{{ $answer['id'] }}">
		@if ($answer['image'] !== null && $answer['image'] !== '')
			@if(File::exists(public_path($answer['image'])))
				<img src="{{ $answer['image'] }}" class="img-fluid answer-image-src" style="padding: 2px;"
				     id="image-src-{{ $answer['id'] }}">
				<i class="far fa-image fa-lg d-none" id="image-src-placeholder-{{ $answer['id'] }}" style="color:#ccc;"></i>
			@else
				<img src="" data-image_suggestion="{{ $answer['image'] }}" class="img-fluid answer-image-src"
				     style="padding: 2px;"
				     id="image-src-{{ $answer['id'] }}">
				<i class="far fa-image fa-lg" id="image-src-placeholder-{{ $answer['id'] }}" style="color:#ccc;"></i>
			@endif

		@else
			<img src="" class="img-fluid d-none answer-image-src" style="padding: 2px;" id="image-src-{{ $answer['id'] }}">
			<i class="far fa-image fa-lg" id="image-src-placeholder-{{ $answer['id'] }}" style="color:#ccc;"></i>
		@endif
	</div>
	<div class="vertical-ruler"></div>
	@if ($answer['audio'] !== '' && $answer['audio'] !== null)
		<div class="audio-container answer-audio mini-side-button" data-id="{{ $answer['id'] }}"
		     data-block_type="answer">
			<i class="fas fa-volume-up" id="audio-src-{{ $answer['id'] }}" data-id="{{ $answer['id'] }}"
			   data-text="{{$answer['audio_tts']}}"
			   data-src="{{ $answer['audio'] }}" data-voice="{{ $answer['voice_id'] ?? '' }}"
			   data-block_type="answer" style="color:green;"></i>
		</div>
	@else
		<div class="audio-container answer-audio mini-side-button" data-id="{{ $answer['id'] }}"
		     data-block_type="answer">
			<i class="fas fa-volume-up" id="audio-src-{{ $answer['id'] }}" data-id="{{ $answer['id'] }}" data-text=""
			   data-src="" data-voice=""
			   data-block_type="answer" style="color:#ccc;"></i>
		</div>
	@endif
	<div class="vertical-ruler"></div>
	<div class="item-input-wrapper">
		<div contenteditable="true" draggable="false" spellcheck="false" class="item-input"
		     id="answer-text-{{ $answer['id'] }}">{{$answer_text}}</div>
	</div>
</div>
