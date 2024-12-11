<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>
	let stopTime = 4000;
	let startTime;
	let halloween_bg, bat1, bat2, bat3, bat4;

	let answer_button_square_img = '/assets/phaser/buttons/ghosts/square1.png';
	let answer_button_portrait_img = '/assets/phaser/buttons/ghosts/portrait1.webp';
	let answer_button_landscape_img = '/assets/phaser/buttons/ghosts/landscape1.png';
	let answer_button_letterbox_img = '/assets/phaser/buttons/ghosts/letterbox_3.webp';

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
			this.load.image('halloween_bg', '/assets/phaser/halloween/halloween_bg.png');
			this.load.image('bat1', '/assets/phaser/halloween/bat1.png');
			this.load.image('bat2', '/assets/phaser/halloween/bat2.png');
			this.load.image('bat3', '/assets/phaser/halloween/bat3.png');
            this.load.image('bat4', '/assets/phaser/halloween/bat4.png');
		}

		create() {
			startTime = this.time.now;

            halloween_bg = this.add.image(960, 540, 'halloween_bg').setScale(1).setOrigin(0.5, 0.5);
            bat1 = this.add.image(255, 240, 'bat1').setScale(0.8).setOrigin(0.5, 0.5);
            bat2 = this.add.image(1260, 600, 'bat2').setScale(1).setOrigin(0.5, 0.5);
            bat3 = this.add.image(1380, 295, 'bat3').setScale(1).setOrigin(0.5, 0.5);
            bat4 = this.add.image(820, 395, 'bat4').setScale(1).setOrigin(0.5, 0.5);

            this.seesawMotion(bat1, 10, 0.9, this);
            this.seesawMotion(bat2, 10, 0.7, this);
            this.seesawMotion(bat3, 10, 0.9, this);
            this.seesawMotion(bat4, 10, 0.8, this);


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
                halloween_bg.setScale((1 + scaleFactor / 2) * 1).setY(640 + elapsedTime * 0.002);
			}
		}

        seesawMotion(element, distance, scaleIncrease, context) {
            let duration = 4000; // Duration of a single back-and-forth animation in milliseconds
            // Add a tween to animate left and right motion
            context.tweens.add({
                targets: element,
                duration: duration,
                scale: scaleIncrease,
                x: `+=${distance}`, // Move right
                y: `+=${distance}`,
                ease: "Sine.easeInOut",
                yoyo: true, // Yoyo creates a back-and-forth effect
                repeat: -1, // Repeat indefinitely for continuous motion
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
