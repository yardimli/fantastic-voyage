<?php
	if (View::exists('game-layout.phaser-'.$animation.'-zoom')) {
		echo view('game-layout.phaser-' . $animation . '-zoom')->render();
	}
