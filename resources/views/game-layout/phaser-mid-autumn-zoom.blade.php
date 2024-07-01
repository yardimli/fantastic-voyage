<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>
	let stopTime = 4000;
	let startTime;
	let mid_autumn_bg, lotus_left, lotus_right, lantern, lantern1, lantern2, lantern3, lantern4;

	let answer_button_square_img = '/assets/phaser/buttons/natural/square_5.webp';
	let answer_button_portrait_img = '/assets/phaser/buttons/natural/portrait_5.webp';
	let answer_button_landscape_img = '/assets/phaser/buttons/natural/landscape_5.webp';
	let answer_button_letterbox_img = '/assets/phaser/buttons/natural/letterbox_5.webp';

	//set properties of css id #question-div
	var style = document.createElement('style');
	style.type = 'text/css';
	style.innerHTML = `
	#question-div{
	font-weight: bold;
	}
	#start-quiz, #question-div, .page-controller, .answer-text, #timer, #score {
	color: #ffffff;
	text-shadow: 2px 2px 10px #000;
	}
	.answer-btn-square-padding {
	padding-top: 0px;
	padding-bottom: 0px;
	padding-left: 0px;
	padding-right: 0px;
	}
	#show-article{
	background-color: darkolivegreen;
	border: 3px solid #fff;
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
			this.load.image('mid_autumn_bg', '/assets/phaser/mid-autumn/mid_autumn_bg.png');
			this.load.image('lotus_left', '/assets/phaser/mid-autumn/lotus_left.png');
			this.load.image('lotus_right', '/assets/phaser/mid-autumn/lotus_right.png');
			this.load.image('lantern1', '/assets/phaser/mid-autumn/lantern1.png');
			this.load.image('lantern2', '/assets/phaser/mid-autumn/lantern2.png');
			this.load.image('lantern3', '/assets/phaser/mid-autumn/lantern3.png');
			this.load.image('lantern4', '/assets/phaser/mid-autumn/lantern4.png');
		}

		create() {
			startTime = this.time.now;

			mid_autumn_bg = this.add.image(960, 530, 'mid_autumn_bg').setScale(1).setOrigin(0.5, 0.5);
			lantern1 = this.add.image(1565, 0, 'lantern1').setScale(1).setOrigin(0.5, 0.5);
			lantern2 = this.add.image(540, 100, 'lantern2').setScale(1).setOrigin(0.5, 0.5);
			lantern3 = this.add.image(300, 150, 'lantern3').setScale(1).setOrigin(0.5, 0.5);
			lantern4 = this.add.image(105, 50, 'lantern4').setScale(1).setOrigin(0.5, 0.5);
			lotus_left = this.add.image(960, 540, 'lotus_left').setScale(1).setOrigin(0.5, 0.5);
			lotus_right = this.add.image(960, 540, 'lotus_right').setScale(1).setOrigin(0.5, 0.5);


			this.createTween(lantern1, -2, 2, 4000, this);
			this.createTween(lantern2, -3, 3, 4000, this);
			this.createTween(lantern3, -2, 2, 4000, this);
			this.createTween(lantern4, -3, 3, 4000, this);

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
				mid_autumn_bg.setScale((1 + scaleFactor / 4) * 1).setY(530 + elapsedTime * 0.002);
				lantern1.setScale((1 + scaleFactor / 3) * 1).setY(0 + elapsedTime * 0.006);
				lantern2.setScale((1 + scaleFactor / 5) * 1).setY(100 + elapsedTime * 0.01);
				lantern3.setScale((1 + scaleFactor / 6) * 1).setY(100 + elapsedTime * 0.002);
				lantern4.setScale((1 + scaleFactor / 5) * 1).setY(100 + elapsedTime * 0.006);
				lotus_left.setScale((1 + scaleFactor / 3) * 1).setY(540 + elapsedTime * 0.002);
				lotus_right.setScale((1 + scaleFactor) * 1).setY(540 + elapsedTime * 0.008);
			}
		}

		createTween(target, angleFrom, angleTo, duration, context) {
			context.tweens.add({
				targets: target,
				angle: {from: angleFrom, to: angleTo}, // swing from -20 to 20
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

	let game = new Phaser.Game(config);
</script>
