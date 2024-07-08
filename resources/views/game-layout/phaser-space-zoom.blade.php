<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>

	let stopTime = 4000;
	let startTime;
	let space_bg, planets, spaceship, cat, monkey, fox, penguin, zebra;

	let answer_button_square_img = '/assets/phaser/buttons/plain/square1.webp';
	let answer_button_portrait_img = '/assets/phaser/buttons/plain/portrait1.webp';
	let answer_button_landscape_img = '/assets/phaser/buttons/plain/landscape1.webp';
	let answer_button_letterbox_img = '/assets/phaser/buttons/plain/letterbox1-wider.png';

	//set properties of css id #question-div
	var style = document.createElement('style');
	style.type = 'text/css';
	style.innerHTML = `
	#question-div {
	color: white;
	text-shadow: 1px 1px 4px #000;
	background-color: rgba(0, 0, 0, 0.8);
	overflow: auto;
	border-radius: 5px;
	}
	#start-quiz, .page-controller, .answer-text, #timer, #score {
	color: #ffffff;
	text-shadow: 1px 1px 4px #000;
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
			this.load.image('space_bg', '/assets/phaser/space/space_bg.png');
			this.load.image('planets', '/assets/phaser/space/planets.png');
			this.load.image('spaceship', '/assets/phaser/space/spaceship.png');
			this.load.image('cat', '/assets/phaser/space/cat.png');
			this.load.image('monkey', '/assets/phaser/space/monkey.png');
			this.load.image('fox', '/assets/phaser/space/fox.png');
			this.load.image('penguin', '/assets/phaser/space/penguin.png');
			this.load.image('zebra', '/assets/phaser/space/zebra.png');

			//buttons
			this.load.image('square1', '/assets/phaser/space/square1.png');
			this.load.image('square2', '/assets/phaser/space/square2.png');
			this.load.image('portrait1', '/assets/phaser/space/portrait1.png');
			this.load.image('portrait2', '/assets/phaser/space/portrait2.png');
			this.load.image('letterbox1', '/assets/phaser/space/letterbox1.png');
			this.load.image('letterbox2', '/assets/phaser/space/letterbox2.png');
			this.load.image('landscape1', '/assets/phaser/space/landscape1.png');
			this.load.image('landscape2', '/assets/phaser/space/landscape2.png');
		}

		create() {
			startTime = this.time.now;

			space_bg = this.add.image(960, 540, 'space_bg').setScale(1).setOrigin(0.5, 0.5);
			planets = this.add.image(960, 540, 'planets').setScale(0.25).setOrigin(0.5, 0.5);
			spaceship = this.add.image(1750, 900, 'spaceship').setScale(0.3).setOrigin(0.5, 0.5);
			cat = this.add.image(1650, 300, 'cat').setScale(0.2).setOrigin(0.5, 0.5);
			monkey = this.add.image(600, 665, 'monkey').setScale(0.2).setOrigin(0.5, 0.5);
			fox = this.add.image(300, 225, 'fox').setScale(0.2).setOrigin(0.5, 0.5);
			penguin = this.add.image(940, 150, 'penguin').setScale(0.2).setOrigin(0.5, 0.5);
			zebra = this.add.image(1240, 800, 'zebra').setScale(0.2).setOrigin(0.5, 0.5);

			this.spinScaleAndDisappear(cat, 0.2, this);
			this.spinScaleAndDisappear(monkey, 0.4, this);
			this.spinScaleAndDisappear(fox, 0.4, this);
			this.spinScaleAndDisappear(penguin, 0.2, this);
			this.spinScaleAndDisappear(zebra, 0.6, this);

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
				space_bg.setScale((1 + scaleFactor / 2) * 1).setY(540 + elapsedTime * 0.002);
				planets.setScale((1 + scaleFactor / 3) * 0.25).setY(540 + elapsedTime * 0.006);
				spaceship.setScale((1 + scaleFactor / 2) * 0.3).setY(900 + elapsedTime * 0.02);
				// cat.setScale((1+ scaleFactor/4) * 0.2).setY(300 + elapsedTime * 0.008);
				// monkey.setScale((1+ scaleFactor/2) * 0.2).setY(665 + elapsedTime * 0.008);
				// fox.setScale((1+ scaleFactor/2) * 0.2).setY(225 + elapsedTime * 0.02);
				// penguin.setScale((1+ scaleFactor/4) * 0.2).setY(150 + elapsedTime * 0.008);
				// zebra.setScale((1+ scaleFactor) * 0.2).setY(800 + elapsedTime * 0.06);
			}
		}

		spinScaleAndDisappear(element, scaleIncrease, context) {

			let duration = 4000;  // Duration of the operation in milliseconds
			let rotationSpeed = 360; // Speed at which the image will spin

			// Spin and scale the zebra
			context.tweens.add({
				targets: element,
				duration: duration,
				angle: rotationSpeed,
				scale: scaleIncrease, // How much the image scale will increase
				alpha: 0,
				ease: "Power2"
			});
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
