<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>
	let stopTime = 4000;
	let startTime;
	let city_bg, apartments, car;

	let answer_button_square_img = '/assets/phaser/buttons/plain/square3.webp';
	let answer_button_portrait_img = '/assets/phaser/buttons/plain/portrait3.webp';
	let answer_button_landscape_img = '/assets/phaser/buttons/plain/landscape3.webp';
	let answer_button_letterbox_img = '/assets/phaser/buttons/plain/letterbox3.webp';

	//set properties of css id #question-div
	var style = document.createElement('style');
	style.type = 'text/css';
	style.innerHTML = `
	#question-div {
	color: black;
	text-shadow: 1px 1px 2px #fff;
	background-color: rgba(255, 255, 255, 0.8);
	border-radius: 5px;
	}
	.answer-text, #timer, #score {
	color: black;
	text-shadow: 1px 1px 2px #fff;
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
			this.load.image('city_bg', '/assets/phaser/city/city_bg.png');
			this.load.image('apartments', '/assets/phaser/city/apartments.png');
			this.load.image('car', '/assets/phaser/city/car.png');
		}

		create() {
			startTime = this.time.now;

            city_bg = this.add.image(960, 540, 'city_bg').setScale(0.6).setOrigin(0.5, 0.5);
            apartments = this.add.image(950, 600, 'apartments').setScale(1.5).setOrigin(0.5, 0.5);
            car = this.add.image(450, 800, 'car').setScale(1).setOrigin(0.5, 0.5);

            this.upDownMotion(car, 5, this);


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
				let scaleFactor = 0.2 * Math.sin(0.0003 * elapsedTime); // slow and smooth sinusoidal zoom in/out effect
				// console.log(elapsedTime, scaleFactor);

				// scale each layer and displaces it with varying intensity for a 3D effect
                city_bg.setScale((1 +  scaleFactor / 5) * 1).setY(340 + elapsedTime * 0.001);
                apartments.setScale(1.6).setY(600 + elapsedTime * 0.003);
                car.setScale((1 + scaleFactor / 1) * 1).setY(800 + elapsedTime * 0.002);
			}
		}

        upDownMotion(element, distance, context) {
            let duration = 500; // Duration of a single back-and-forth animation in milliseconds
            // Add a tween to animate left and right motion
            context.tweens.add({
                targets: element,
                duration: duration,
                y: `+=${distance}`,
                ease: "Sine.easeInOut",
                yoyo: true, // Yoyo creates a back-and-forth effect
                repeat: -1, // Repeat indefinitely for continuous motion
            });
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
