@extends('layouts.app')

@section('title', 'Story Voyage')

@section('content')
	<link rel="preload" as="image" href="{{ asset('assets/phaser/images/correct.png') }}"/>
	<link rel="preload" as="image" href="{{ asset('assets/phaser/images/wrong.png') }}"/>
	<link href="{{ asset('assets/css/quiz-game.css') }}" rel="stylesheet">
	<script src="{{ asset('assets/js/play-investigation.js') }}"></script>
	<div class="container" style="margin-top: 60px;">
		
		<div id="breadcrumbs" class="pt-2 pb-2">
			<span class="breadcrumb-separator fas fa-chevron-right"></span>
			<a class="clickable-breadcrumb breadcrumb" href="{{route('activities.page', ['type' => 'investigation'])}}">Investigations</a>
			<span class="breadcrumb-separator fas fa-chevron-right"></span>
			<span class="breadcrumb selected-breadcrumb">{{__('default.Play')}}</span>
		</div>
		
		<audio id="audio-player" style="display: none;"></audio>
		<input type="hidden" id="activity_id" value="{{$activity_id}}">
		<div id="game-ui-in-page" class="prevent-select">
			@include('game-layout.phaser-game-background', ['animation' => $current_theme, 'view_target' => 'game-ui-in-page']) <!-- beach, jungle, mid-autumn, moon, rabbit, space, summer, taipei, winter -->
			<div id="loading-page">
				<div style="margin: auto;">
					<div style="font-size: 64px; font-weight: bold;">{{ __('default.Loading...') }}</div>
				</div>
			</div>
			<div id="preload-page">
				<div style="margin: auto;">
					<div class="story-title"></div>
					<div class="start-btn">
						<img src="{{ asset('assets/phaser/images/start.png') }}" style="width: 80px; margin: 5px;">
						<div class="mb-2 start-btn-text" style="font-weight: bold; font-size: 20px;">START</div>
					</div>
					<div id="type-description" style="font-size: 28px;"></div>
				</div>
			</div>
			<div id="main-div" style="overflow: hidden;" class="hide-main-div">
				<div id="question-div"></div>
				<div id="question-image-div"></div>
				<div id="answers-div"></div>
				<div id="story-footer" style="text-align: center;">
					<div id="timer" style="display: none;">00:00</div>
					<div class="page-controller" style="display: none;">
						<span id="page-counter"></span>
					</div>
					<i class="fa fa-caret-right" id="start-story"></i>
				
				</div>
			</div>
			<div id="endgame-page">
				<div id="finish-info">
					<div class="title">{{ __('default.Game Finished') }}</div>
					<div style="color: #3faa6f">{{ __('default.Time Spent') }}</div>
					<div class="time-spent" style="font-size: 32px;"></div>
					{{--					<div id="restart">ReStart</div>--}}
				</div>
			</div>
			<img id="full-screen" src="{{ asset('assets/phaser/images/full_screen.png') }}">
			<img id="mute-audio" src="{{ asset('assets/phaser/images/sound.png') }}">
		</div>
		
		<div id="themes_div">
			@foreach ($themes as $theme)
				<img src="{{ asset('assets/phaser/images/' . $theme . '.png') }}"
				     class="{{ $current_theme == $theme ? 'selected-theme' : 'themes_img' }}"
				     data-theme="{{ $theme }}">
			@endforeach
		</div>
		
		
		@endsection
		
		@push('scripts')
			<script>
				let chapter_step = {{ $step }};
				let story_title = '{!! $title !!}';
				let type_description = '{!! $type_description !!}';
				let chapter_image = '{!! $image !!}';
				let chapter_voice = '{!! $chapter_voice !!}';
				let chapter_text = '{!! $chapter_text !!}';
				let chapter_choices = {"choices": {!! $choices !!}};
				let current_theme = '{!! $current_theme !!}';
				window.translations = {
					'default.num_of_num': '{{ __("default.num of num", ["index" => ":index", "total" => ":total"]) }}'
				};
			</script>
	@endpush
