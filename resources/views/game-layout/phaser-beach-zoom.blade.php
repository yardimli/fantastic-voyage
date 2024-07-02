<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>
	let stopTime = 4000;
	let startTime;
	let beach_bg, beach_top, beach_middle, beach_bottom;

	let answer_button_square_img = '/assets/phaser/buttons/natural/square_3.webp';
	let answer_button_portrait_img = '/assets/phaser/buttons/natural/portrait_3.webp';
	let answer_button_landscape_img = '/assets/phaser/buttons/natural/landscape_3.webp';
	let answer_button_letterbox_img = '/assets/phaser/buttons/natural/letterbox_3.webp';

	//set properties of css id #question-div
	var style = document.createElement('style');
	style.type = 'text/css';
	style.innerHTML = `
	#question-div, .answer-text, #timer, #score {
	color: white;
	text-shadow: 1px 1px 4px #000000;
	}
	.answer-btn-square-padding {
	padding-top: 0px;
	padding-bottom: 0px;
	padding-left: 0px;
	padding-right: 0px;
	}
	.page-controller{color: black;}
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
			this.load.image('beach_bg', '/assets/phaser/beach/beach_bg.png');
			this.load.image('beach_top', '/assets/phaser/beach/top.png');
			this.load.image('beach_middle', '/assets/phaser/beach/middle.png');
			this.load.image('beach_bottom', '/assets/phaser/beach/bottom.png');
		}

		create() {
			startTime = this.time.now;

			beach_bg = this.add.image(960, 540, 'beach_bg').setScale(1).setOrigin(0.5, 0.5);
			beach_top = this.add.image(180, 190, 'beach_top').setScale(1).setOrigin(0.5, 0.5);
			beach_middle = this.add.image(960, 600, 'beach_middle').setScale(1).setOrigin(0.5, 0.5);
			beach_bottom = this.add.image(920, 895, 'beach_bottom').setScale(1).setOrigin(0.5, 0.5);


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
				// console.log(elapsedTime, scaleFactor);

				// scale each layer and displaces it with varying intensity for a 3D effect
				beach_bg.setScale((1 + scaleFactor / 2) * 1).setY(540 + elapsedTime * 0.002);
				beach_top.setScale((1 + scaleFactor / 3) * 1).setY(190 + elapsedTime * 0.006);
				beach_middle.setScale((1 + scaleFactor / 3) * 1).setY(600 + elapsedTime * 0.006);
				beach_bottom.setScale((1 + scaleFactor) * 1).setY(895 + elapsedTime * 0.06);
			}
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

	//  This Scene is aspect ratio locked at 640 x 960 (and scaled and centered accordingly)
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

</script>
