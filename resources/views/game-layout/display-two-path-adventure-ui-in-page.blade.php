@extends('layouts.app')

@section('title', 'Story Voyage')

@section('content')
	<style>
      .hidden-layer {
          display: none !important;
      }
	</style>
	<link rel="preload" as="image" href="{{ asset('assets/phaser/images/correct.png') }}"/>
	<link rel="preload" as="image" href="{{ asset('assets/phaser/images/wrong.png') }}"/>
	<link href="{{ asset('assets/css/quiz-game.css') }}" rel="stylesheet">
	<script src="{{ asset('assets/js/play-two-path-adventure.js') }}"></script>
	<div class="container" style="margin-top: 60px;">
		
		<div id="breadcrumbs" class="pt-2 pb-2">
			<span class="breadcrumb-separator fas fa-chevron-right"></span>
			<a class="clickable-breadcrumb breadcrumb" href="{{route('activities.page', ['type' => 'two-path-adventure'])}}">Adventures</a>
			<span class="breadcrumb-separator fas fa-chevron-right"></span>
			<span class="breadcrumb selected-breadcrumb">{{__('default.Play')}}</span>
		</div>
		
		<audio id="audio-player" style="display: none;"></audio>
		<input type="hidden" id="activity_id" value="{{$activity_id}}">
		<div id="game-ui-in-page" class="prevent-select">
			@include('game-layout.phaser-game-background', ['animation' => $current_theme, 'view_target' => 'game-ui-in-page']) <!-- beach, jungle, mid-autumn, moon, rabbit, space, summer, taipei, winter -->
			<div id="loading-page" class="hidden-layer">
				<div style="margin: auto;">
					<div style="font-size: 44px; font-weight: bold;">Writing Next Chapter...</div>
					<div id="progress-bar-container"
					     style="width: 100%; height: 30px; background-color: #ddd; margin-top: 20px;">
						<div id="progress-bar" style="width: 0%; height: 100%; background-color: #4CAF50;"></div>
					</div>
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
				<div id="question-image-div">
					<img class="question-img" id="current-question-img" src="" style="position:absolute; top:0px; left:0px;">
					<img class="question-img" id="next-question-img" src="" style="display: none; position:absolute; top:0px; left:0px;">
				</div>
				<div id="answers-div"></div>
				<div id="story-footer" style="text-align: center;">
					<div class="page-controller" style="display: inline-block;">
						<i class="fa fa-caret-left" id="prev"></i>
						<span id="page-counter"></span>
						<i class="fa fa-caret-right" id="next"></i>
					</div>
					
					<div id="timer" style="display: none; float:left;">00:00</div>

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
				let total_steps = {{ $total_steps }};
				let chapter_step = {{ $step }};
				let story_title = '{!! $title !!}';
				let type_description = '{!! $type_description !!}';
				let chapter_image = '{!! $image !!}';
				let chapter_choices = {"choices": {!! $choices !!}};
				let active_choice = '{!! $choice !!}';
				let current_theme = '{!! $current_theme !!}';
				window.translations = {
					'default.num_of_num': '{{ __("default.num of num", ["index" => ":index", "total" => ":total"]) }}'
				};
			</script>
	@endpush

