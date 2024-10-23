@extends('layouts.app')

@section('title', 'Maze Chase')

@section('content')
	<link rel="preload" as="image" href="{{ asset('assets/phaser/images/correct.png') }}"/>
	<link rel="preload" as="image" href="{{ asset('assets/phaser/images/wrong.png') }}"/>
	<link href="{{ asset('assets/css/quiz-game.css') }}" rel="stylesheet">
	<style>
      .hidden-layer {
          display: none !important;
      }
	</style>
	<script src="{{ asset('assets/js/play-quiz.js') }}"></script>
	<div class="container" style="margin-top: 60px; margin-bottom: 60px;">
		
		<div id="breadcrumbs" class="pt-2 pb-2">
			<span class="breadcrumb-separator fas fa-chevron-right"></span>
			<a class="clickable-breadcrumb breadcrumb" href="{{route('quiz-activities')}}">Quizzes</a>
			@if ($user_id !== 0)
				<span class="breadcrumb-separator fas fa-chevron-right"></span>
				<a href="{{ route('quiz-builder',[ $activity_id]) }}"><span
						class="breadcrumb ">{{__('default.Enter Content')}}</span></a>
			@endif
			<span class="breadcrumb-separator fas fa-chevron-right"></span>
			<span class="breadcrumb selected-breadcrumb">{{__('default.Play')}}</span>
		</div>

		<?php
			$data = json_decode($json_data, true);
//			print_r($questions);
		?>
		<audio id="audio-player" style="display: none;"></audio>
		<input type="hidden" id="activity_id" value="{{$activity_id}}">
		<div id="game-ui-in-page" class="prevent-select">
			@include('game-layout.phaser-game-background', ['animation' => $current_theme, 'view_target' => 'game-ui-in-page']) <!-- beach, jungle, mid-autumn, moon, rabbit, space, summer, taipei, winter -->
			<div id="loading-page" class="hidden-layer">
				<div style="margin: auto;">
					<div style="font-size: 44px; font-weight: bold;">Getting Next Question...</div>
					<div id="progress-bar-container"
					     style="width: 90%; height: 30px; background-color: #ddd; margin-top: 20px; margin-left:auto; margin-right:auto;">
						<div id="progress-bar" style="width: 0%; height: 100%; background-color: #4CAF50;"></div>
					</div>
				</div>
			</div>
			<div id="preload-page">
				<div style="margin: auto;">
					<div class="quiz-title"></div>
					<div class="start-btn">
						<img src="{{ asset('assets/phaser/images/start.png') }}" style="width: 80px; margin: 5px;">
						<div class="mb-2 start-btn-text" style="font-weight: bold; font-size: 20px;">START</div>
					</div>
					<div id="quiz-type-description" style="font-size: 28px;"></div>
				</div>
			</div>
			<div id="main-div" style="overflow: hidden;" class="hide-main-div">
				<div id="quiz-header" style="display:flex; justify-content: space-between;">
					<div id="timer" style="display: none;">00:00</div>
					<div id="score" style="display: none; margin-right: 20px;">0</div>
				</div>
				<div id="question-div"></div>
				<div id="question-image-div"></div>
				<div id="answers-div"></div>
				<div id="quiz-footer" style="text-align: center;">
					<div class="page-controller" style="display: none;">
						<i class="fa fa-caret-left" id="prev"></i>
						<span id="page-counter"></span>
						<i class="fa fa-caret-right" id="next"></i>
					</div>
					<i class="fa fa-caret-right" id="start-quiz"></i>
				
				</div>
			</div>
			<div id="endgame-page">
				<div id="finish-info">
					<div class="title">{{ __('default.Game Finished') }}</div>
					<div style="color: #3faa6f">{{ __('default.Time Spent') }}</div>
					<div class="time-spent" style="font-size: 32px;"></div>
					<div style="color: #3faa6f">{{ __('default.Score') }}</div>
					<div class="total-score" style="font-size: 32px;"></div>
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
	</div>
	
	</main>
	
	<!-- **************** MAIN CONTENT END **************** -->
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<script>
		let json_data = {!! $json_data; !!};
		let game_title = '{!! str_replace("'","\'", $title); !!}';
		let type_description = '{!! str_replace("'","\'", $type_description); !!}';
		let current_theme = '{!! str_replace("'","\'", $current_theme); !!}';
		var previewQid = null;
		window.translations = {
			'default.num_of_num': '{{ __("default.num of num", ["index" => ":index", "total" => ":total"]) }}'
		};
	</script>
@endpush

