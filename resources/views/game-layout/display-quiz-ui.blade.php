<!DOCTYPE html>
<html lang="en_US">
<head>
    <title>{{__('default.Fantastic Voyage') }} - {{$sub_title ?? 'Home'}}</title>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="{{__('site_domain')}}">
    <meta name="description" content="{{__('default.Fantastic Voyage') }} - {{$sub_title ?? 'Home'}}">
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
    <script src="{{ asset('assets/js/play-quiz.js') }}"></script>

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
       .hidden-layer {
           display: none !important;
       }
    </style>
</head>
<?php
	$data = json_decode($json_data, true);
	$div_class_name = '';
	if ($question === null) {
		$div_class_name = 'hide-main-div';
    }
//			print_r($questions);
?>

<body>
<audio id="audio-player" style="display: none;"></audio>
@include('game-layout.phaser-game-background', ['animation' => $current_theme]) <!-- beach, jungle, mid-autumn, moon, rabbit, space, summer, taipei, winter -->
@if($question === null)
    <div id="loading-page" class="hidden-layer">
        <div style="margin: auto;">
            <div style="font-size: 44px; font-weight: bold;">Getting Next Question...</div>
            <div id="progress-bar-container" style="width: 90%; height: 30px; background-color: #ddd; margin-top: 20px; margin-left:auto; margin-right:auto;">
                <div id="progress-bar" style="width: 0%; height: 100%; background-color: #4CAF50;"></div>
            </div>
        </div>
    </div>
    <div id="preload-page">
        <div style="margin: auto;">
            <div class="quiz-title"></div>
            <div class="start-btn">
                <img src="{{ asset('assets/phaser/images/start.png') }}" style="width: 80px; margin: 5px;">
                <div class="mb-2" style="font-weight: bold; font-size: 20px;">START</div>
            </div>
            <div id="quiz-type-description" style="font-size: 28px;"></div>
        </div>
    </div>
@endif
<div id="main-div" style="overflow: hidden;" class="prevent-select {{ $div_class_name }}">
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
        {{--        <div id="restart">ReStart</div>--}}
    </div>
</div>
<img id="mute-audio" src="{{ asset('assets/phaser/images/sound.png') }}">
</body>

<script>
	let json_data = {!! $json_data; !!};
	let game_title = '{!! str_replace("'","\'", $title); !!}';
	let type_description = '{!! str_replace("'","\'", $type_description); !!}';
	let current_theme = '{!! str_replace("'","\'", $current_theme); !!}';
	let previewQid = @json($question);
	window.translations = {
		'default.num_of_num': '{{ __("default.num of num", ["index" => ":index", "total" => ":total"]) }}'
	};
</script>



