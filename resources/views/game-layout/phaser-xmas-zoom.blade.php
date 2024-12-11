<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>
	let stopTime = 4000;
	let startTime;
	let xmas_bg, candy_bar1, candy_bar2, hand1, hand2, hand3;

	let answer_button_square_img = '/assets/phaser/buttons/paper/square1.webp';
	let answer_button_portrait_img = '/assets/phaser/buttons/paper/portrait1.webp';
	let answer_button_landscape_img = '/assets/phaser/buttons/paper/landscape1.webp';
	let answer_button_letterbox_img = '/assets/phaser/buttons/paper/letterbox1.webp';

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
			this.load.image('xmas_bg', '/assets/phaser/xmas/xmas_bg.png');
			this.load.image('candy_bar1', '/assets/phaser/xmas/candy_bar1.png');
			this.load.image('candy_bar2', '/assets/phaser/xmas/candy_bar2.png');
			this.load.image('hand1', '/assets/phaser/xmas/hand1.png');
            this.load.image('hand2', '/assets/phaser/xmas/hand2.png');
            this.load.image('hand3', '/assets/phaser/xmas/hand3.png');
		}

		create() {
			startTime = this.time.now;

            xmas_bg = this.add.image(960, 540, 'xmas_bg').setScale(1).setOrigin(0.5, 0.5);
            candy_bar1 = this.add.image(450, 900, 'candy_bar1').setScale(0.3).setOrigin(0.5, 0.5);
            candy_bar2 = this.add.image(250, 800, 'candy_bar2').setScale(0.3).setOrigin(0.5, 0.5);
            hand1 = this.add.image(1580, 850, 'hand1').setScale(0.3).setOrigin(0.5, 0.5);
            hand2 = this.add.image(1330, 200, 'hand2').setScale(0.3).setOrigin(0.5, 0.5);
            hand3 = this.add.image(180, 395, 'hand3').setScale(0.3).setOrigin(0.5, 0.5);

            this.createTween(candy_bar1, -2, 2, 4000, this);
            this.createTween(candy_bar2, -1, 2, 3000, this);
            this.seesawMotion(hand2, 5, 0.31, this);
            this.seesawMotion(hand3, 10, 0.3, this);


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
                xmas_bg.setScale((1 +  scaleFactor / 10) * 1).setY(540 + elapsedTime * 0.001);
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
