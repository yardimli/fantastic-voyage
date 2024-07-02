<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>
	let stopTime = 4000;
	let startTime;
	let scene_1, scene_2, scene_3, scene_4, scene_5, landscape1, landscape2, monkey;

	let answer_button_square_img = '/assets/phaser/buttons/western/square_3.webp';
	let answer_button_portrait_img = '/assets/phaser/buttons/western/portrait_3.webp';
	let answer_button_landscape_img = '/assets/phaser/buttons/western/landscape_3.webp';
	let answer_button_letterbox_img = '/assets/phaser/buttons/western/letterbox_3.webp';

	//set properties of css id #question-div
	var style = document.createElement('style');
	style.type = 'text/css';
	style.innerHTML = `
	#question-div, .page-controller, #start-quiz, #timer, #score {
	color: #ffffff;
	text-shadow: 2px 2px 4px #0C561D;
	}
	.answer-text {
	color: #052b0d;
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
			this.load.image('scene_5', '/assets/phaser/jungle/scene_5.png');
			this.load.image('scene_4', '/assets/phaser/jungle/scene_4.png');
			this.load.image('scene_3', '/assets/phaser/jungle/scene_3.png');
			this.load.image('scene_2', '/assets/phaser/jungle/scene_2.png');
			this.load.image('monkey', '/assets/phaser/jungle/monkey.png');
			this.load.image('scene_1', '/assets/phaser/jungle/scene_1.png');
		}

		create() {
			startTime = this.time.now;

			scene_5 = this.add.image(960, 480, 'scene_5').setScale(1).setOrigin(0.5, 0.5);
			scene_4 = this.add.image(960, 450, 'scene_4').setScale(1).setOrigin(0.5, 0.5);
			scene_3 = this.add.image(960, 825, 'scene_3').setScale(1).setOrigin(0.5, 0.5);
			scene_2 = this.add.image(1050, 500, 'scene_2').setScale(1.15).setOrigin(0.5, 0.5);
			monkey = this.add.image(440, 380, 'monkey').setScale(0.4).setOrigin(0.5, 0.5);
			scene_1 = this.add.image(960, 610, 'scene_1').setScale(1.1).setOrigin(0.5, 0.5);
			landscape1 = this.add.image(600, 800, 'landscape1').setScale(0.5).setOrigin(0.5, 0.5);
			landscape2 = this.add.image(1200, 800, 'landscape2').setScale(0.5).setOrigin(0.5, 0.5);


			this.createTween(monkey, -4, 3, 3000, this);

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
				let scaleFactor = 0.5 * Math.sin(0.0003 * elapsedTime) ; // slow and smooth sinusoidal zoom in/out effect
				// console.log(elapsedTime,scaleFactor);

				// scale each layer and displaces it with varying intensity for a 3D effect
				scene_5.setScale((1+ scaleFactor/5 ) * 1).setY(480 + elapsedTime * 0.002);
				scene_4.setScale((1+ scaleFactor/4) * 1).setY(450 + elapsedTime * 0.004);
				scene_3.setScale((1+ scaleFactor/3) * 1).setY(825 + elapsedTime * 0.006);
				scene_2.setScale((1+ scaleFactor/4) * 1.15).setY(500 + elapsedTime * 0.008);
				monkey.setScale((1+ scaleFactor/6) * 0.4).setY(380 + elapsedTime * 0.003);
				scene_1.setScale((1+ scaleFactor/2) * 1.1).setY(610 + elapsedTime * 0.02);

			}
		}

		createTween(target, angleFrom, angleTo, duration, context) {
			context.tweens.add({
				targets: target,
				angle: { from: angleFrom, to: angleTo }, // swing from -20 to 20
				duration: duration, // time it takes to swing back and forth.
				repeat: -1, // Repeat indefinitely
				yoyo: true, // Make the animation play forwards and then backwards
				ease: 'Sine.easeInOut', // Sine.easeInOut will give a smoother swinging motion
			});
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

	let config = {
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

	let game = new Phaser.Game(config);

</script>
