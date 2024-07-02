<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>

	let stopTime = 4000;
	let startTime;
	let taipei_bg, sky1, sky2, sky3, sky4, sky5, fireworks, lantern, scene2, scene1;

	let answer_button_square_img = '/assets/phaser/buttons/natural/square_8.webp';
	let answer_button_portrait_img = '/assets/phaser/buttons/natural/portrait_8.webp';
	let answer_button_landscape_img = '/assets/phaser/buttons/natural/landscape_8.webp';
	let answer_button_letterbox_img = '/assets/phaser/buttons/natural/letterbox_8.webp';

	//set properties of css id #question-div
	var style = document.createElement('style');
	style.type = 'text/css';
	style.innerHTML = `
	#question-div, #timer, #score{
	font-weight: bold;
	color: beige;
	text-shadow: 3px 3px 5px #000000;
	}
	#start-quiz, .page-controller {
	color: black;
	text-shadow: 2px 2px 2px #fff;
	}
	.answer-text {
	font-weight: bold;
	color: beige;
	text-shadow: 3px 3px 5px #000000;
	}
	.answer-btn-square-padding {
	padding-top: 0px;
	padding-bottom: 0px;
	padding-left: 0px;
	padding-right: 0px;
	}
	`;
	document.head.appendChild(style);

	class BackgroundScene extends Phaser.Scene {
		gameScene;
		layer;

		constructor() {
			super('BackgroundScene');
		}

		reset_scene() {
			startTime = this.time.now;
			console.log("! reset scene");
		}

		preload() {
			this.load.image('taipei_bg', '/assets/phaser/taipei/taipei_bg.png');
			this.load.image('scene1', '/assets/phaser/taipei/scene1.png');
			this.load.image('scene2', '/assets/phaser/taipei/scene2.png');
			this.load.image('sky1', '/assets/phaser/taipei/sky1.png');
			this.load.image('sky2', '/assets/phaser/taipei/sky2.png');
			this.load.image('sky3', '/assets/phaser/taipei/sky3.png');
			this.load.image('sky4', '/assets/phaser/taipei/sky4.png');
			this.load.image('sky5', '/assets/phaser/taipei/sky5.png');
			this.load.image('lantern', '/assets/phaser/taipei/lantern.png');
			this.load.image('fireworks', '/assets/phaser/taipei/fireworks.png');

		}

		create() {
			startTime = this.time.now;

			taipei_bg = this.add.image(960, 540, 'taipei_bg').setScale(1).setOrigin(0.5, 0.5);
			sky1 = this.add.image(1600, 370, 'sky1').setScale(1).setOrigin(0.5, 0.5);
			sky2 = this.add.image(1100, 300, 'sky2').setScale(1.1).setOrigin(0.5, 0.5);
			sky3 = this.add.image(480, 250, 'sky3').setScale(1.1).setOrigin(0.5, 0.5);
			sky4 = this.add.image(50, 450, 'sky4').setScale(1).setOrigin(0.5, 0.5);
			sky5 = this.add.image(-80, 150, 'sky5').setScale(1).setOrigin(0.5, 0.5);
			fireworks = this.add.image(1150, 660, 'fireworks').setScale(0.6).setOrigin(0.5, 0.5);
			lantern = this.add.image(1620, 570, 'lantern').setScale(0.3).setOrigin(0.5, 0.5);
			scene2 = this.add.image(950, 550, 'scene2').setScale(1).setOrigin(0.5, 0.5);
			scene1 = this.add.image(990, 875, 'scene1').setScale(1).setOrigin(0.5, 0.5);

			// Center camera on the background image
			var camera = this.cameras.main;
			camera.centerOn(1920 / 2, 1080 / 2);

			var scaleX = camera.width / 1920;
			var scaleY = camera.height / 1080;

			// Set the zoom to fill the screen with the background
			var scale = Math.max(scaleX, scaleY);
			camera.setZoom(scale);

			// Register handler for viewport resize
			this.scale.on('resize', function (gameSize, parentSize) {
				// Rescale and recenter the camera
				var scaleX = gameSize.width / 1920;
				var scaleY = gameSize.height / 1080;
				var scale = Math.max(scaleX, scaleY);
				camera.setZoom(scale);
				camera.centerOn(1920 / 2, 1080 / 2);
			});

			this.scene.launch('GameScene');

			this.gameScene = this.scene.get('GameScene');

		}

		update() {
			let currentTime = this.time.now;

			if (this.time.now - startTime < stopTime) {
				let currentTime = this.time.now;
				let elapsedTime = currentTime - startTime;
				let scaleFactor = 0.5 * Math.sin(0.0003 * elapsedTime); // slow and smooth sinusoidal zoom in/out effect
				// console.log(elapsedTime,scaleFactor);

				// scale each layer and displaces it with varying intensity for a 3D effect
				taipei_bg.setScale((1 + scaleFactor / 2) * 1).setY(540 + elapsedTime * 0.002);
				sky1.setScale((1 + scaleFactor / 3) * 1).setY(370 + elapsedTime * 0.006);
				sky2.setScale((1 + scaleFactor / 3) * 1).setY(300 + elapsedTime * 0.006);
				sky3.setScale((1 + scaleFactor / 3) * 1).setY(250 + elapsedTime * 0.006);
				sky4.setScale((1 + scaleFactor / 3) * 1).setY(450 + elapsedTime * 0.006);
				sky5.setScale((1 + scaleFactor / 3) * 1).setY(150 + elapsedTime * 0.006);
				fireworks.setScale((0.6 + scaleFactor) * 1).setY(660 + elapsedTime * -0.05);
				lantern.setScale((0.3 + scaleFactor) * 1.3).setY(570 + elapsedTime * -0.05);
				scene2.setScale((1 + scaleFactor / 3) * 1).setY(600 + elapsedTime * 0.006);
				scene1.setScale((1 + scaleFactor / 2) * 1).setY(875 + elapsedTime * 0.01);
			}

			this.slideRight(sky1, 0.2);
			this.slideRight(sky2, 0.2);
			this.slideRight(sky3, 0.2);
			this.slideRight(sky4, 0.2);
			this.slideRight(sky5, 0.2);
		}

		slideRight(element, speed) {
			// Move your sprite to the right by increasing its x value in update function
			element.x += speed;
			let scaledElementWidth = element.width * element.scaleX;
			// If the sprite's x position is greater than the game's width (meaning it has disappeared on the right)
			// Reset its x position to the left side (outside the screen)
			if (element.x > game_width + scaledElementWidth / 2) {
				element.x = -scaledElementWidth / 2;
			}
		}
	}

	class GameScene extends Phaser.Scene {
		backgroundScene;
		gameOver = false;

		constructor() {
			super('GameScene');
		}

		preload() {
		}

		create() {


			//  -----------------------------------
			//  Normal game stuff from here on down
			//  -----------------------------------


			var game_this = this;

			// Register handler for viewport resize
			this.scale.on('resize', function (gameSize, parentSize) {

				// Rescale and recenter the camera
				// var scaleX = gameSize.width / 1920;
				// var scaleY = gameSize.height / 1080;
				// var scale = Math.max(scaleX, scaleY);

				// camera.setZoom(scale);
				// camera.centerOn(1920 / 2, 1080 / 2);

			});


		}


		update() {
			if (this.gameOver) {
				return;
			}

		}

	}

	const config = {
		type: Phaser.AUTO,
		backgroundColor: '#000000',
		scale: {
			mode: Phaser.Scale.RESIZE, type: Phaser.CANVAS,
			parent: 'game-ui-in-page',
			width: 1920,
			height: 1920,
			min: {
				width: 240,
				height: 240
			},
			max: {
				width: 2560,
				height: 2560
			}
		},
		scene: [BackgroundScene, GameScene],
	};

	const game = new Phaser.Game(config);

	var game_width = game.config.width;



</script>
