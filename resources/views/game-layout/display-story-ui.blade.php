<!DOCTYPE html>
<html lang="en_US">
<head>
    <title>{{__('default.Fantastic Voyage') }} - {{$sub_title ?? 'Voyage'}}</title>
	
	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="{{__('site_domain')}}">
	<meta name="description" content="{{__('default.Fantastic Voyage') }} - {{$sub_title ?? 'Voyage'}}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="app_url" content="{{env('APP_URL')}}">
	
	<script src="/assets/js/core/jquery.min.js"></script>
	<!-- Favicon -->
	<link rel="shortcut icon" href="/assets/images/favicon.ico">
	
	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet"
	      href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap">
	
	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/all.css">
	
	<link href="{{ asset('assets/css/quiz-game.css') }}" rel="stylesheet">
	<script src="{{ asset('assets/js/play-story.js') }}"></script>
	
	<!-- Preload the answer image -->
	<link rel="preload" as="image" href="{{ asset('assets/phaser/images/correct.png') }}"/>
	<link rel="preload" as="image" href="{{ asset('assets/phaser/images/wrong.png') }}"/>
	
	<style>
      html, body {
          margin: 0;
          padding: 0;
          height: 100%;
          width: 100%;
          overflow: hidden;
          font-family: 'Heebo', sans-serif;
          line-height: 1.25;
      }
	</style>

</head>
<body>
<audio id="audio-player" style="display: none;"></audio>
@include('game-layout.phaser-game-background', ['animation' => $current_theme]) <!-- beach, jungle, mid-autumn, moon, rabbit, space, summer, taipei, winter -->
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
			<div class="mb-2" style="font-weight: bold; font-size: 20px;">START</div>
		</div>
		<div id="type-description" style="font-size: 28px;"></div>
	</div>
</div>

<div id="main-div" style="overflow: hidden;" class="prevent-select">
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
		{{--        <div id="restart">ReStart</div>--}}
	</div>
</div>
<img id="mute-audio" src="{{ asset('assets/phaser/images/sound.png') }}">
</body>

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
