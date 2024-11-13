<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>
	let stopTime = 4000;
	let startTime;
	let ocean_bg, fish1, fish2, fish3, fish4, fish5, turtle, seahorse, stingray, crab;

	let answer_button_square_img = '/assets/phaser/buttons/natural/square_3.webp';
	let answer_button_portrait_img = '/assets/phaser/buttons/natural/portrait_3.webp';
	let answer_button_landscape_img = '/assets/phaser/buttons/natural/landscape_3.webp';
	let answer_button_letterbox_img = '/assets/phaser/buttons/natural/letterbox_3.webp';

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
			this.load.image('ocean_bg', '/assets/phaser/ocean/ocean_bg.png');
			this.load.image('fish1', '/assets/phaser/ocean/fish1.png');
            this.load.image('fish2', '/assets/phaser/ocean/fish2.png');
            this.load.image('fish3', '/assets/phaser/ocean/fish3.png');
            this.load.image('fish4', '/assets/phaser/ocean/fish4.png');
            this.load.image('fish5', '/assets/phaser/ocean/fish5.png');
            this.load.image('turtle', '/assets/phaser/ocean/turtle.png');
            this.load.image('seahorse', '/assets/phaser/ocean/seahorse.png');
            this.load.image('stingray', '/assets/phaser/ocean/stingray.png');
            this.load.image('crab', '/assets/phaser/ocean/crab.png');
		}

		create() {
			startTime = this.time.now;

            ocean_bg = this.add.image(960, 540, 'ocean_bg').setScale(0.6).setOrigin(0.5, 0.5);
            fish1 = this.add.image(1350, 300, 'fish1').setScale(0.5).setOrigin(0.5, 0.5);
            fish2 = this.add.image(1650, 600, 'fish2').setScale(0.5).setOrigin(0.5, 0.5);
            fish3 = this.add.image(550, 500, 'fish3').setScale(0.5).setOrigin(0.5, 0.5);
            fish4 = this.add.image(400, 400, 'fish4').setScale(0.5).setOrigin(0.5, 0.5);
            fish5 = this.add.image(950, 300, 'fish5').setScale(0.5).setOrigin(0.5, 0.5);
            turtle = this.add.image(950, 750, 'turtle').setScale(0.5).setOrigin(0.5, 0.5);
            seahorse = this.add.image(200, 300, 'seahorse').setScale(0.5).setOrigin(0.5, 0.5);
            stingray = this.add.image(1700, 800, 'stingray').setScale(0.5).setOrigin(0.5, 0.5);
            crab = this.add.image(250, 900, 'crab').setScale(0.5).setOrigin(0.5, 0.5);

            this.upDownMotion(turtle, 5, 1000, this);
            this.upDownMotion(fish4, 5, 1000, this);
            this.upDownMotion(seahorse, 25, 3000, this);
            this.leftRightMotion(crab, 20, 2000, this);
            this.swimFish(fish2, 20000, 'right', this);
            this.swimFish(fish5, 20000, 'left', this);


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
                ocean_bg.setScale((1 +  scaleFactor / 10) * 0.8).setY(340 + elapsedTime * 0.001);
                // apartments.setScale(1.6).setY(600 + elapsedTime * 0.003);
                // car.setScale((1 + scaleFactor / 1) * 1).setY(800 + elapsedTime * 0.002);
			}
		}

        upDownMotion(element, distance, duration, context) {
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

        leftRightMotion(element, distance, duration, context) {
            // Add a tween to animate left and right motion
            context.tweens.add({
                targets: element,
                duration: duration,
                x: `+=${distance}`,
                ease: "Sine.easeInOut",
                yoyo: true, // Yoyo creates a back-and-forth effect
                repeat: -1, // Repeat indefinitely for continuous motion
            });
        }

        swimFish(element, duration, direction, context) {
            const screenWidth = context.sys.game.config.width; // Get the game's screen width
            const fishWidth = element.width; // Assuming the element has a width property

            // Set the starting position to just off-screen to the left
            if(direction == 'right'){
                element.x = -fishWidth;
                const tween = context.tweens.add({
                    targets: element,
                    x: screenWidth+fishWidth, // The fish swims to the right edge of the screen
                    ease: 'Linear', // Use 'Linear' for constant speed
                    duration: duration,

                    // onComplete callback will be triggered once the tween (one pass rightward) is complete
                    onComplete: function() {
                        // Once the fish reaches the right edge, it's reset back to start
                        element.directly = -fishWidth;
                        // Restart the tween to swim the fish again
                        tween.restart();
                    }
                });
            }else{
                element.x = screenWidth+fishWidth;
                const tween = context.tweens.add({
                    targets: element,
                    x: -fishWidth, // The fish swims to the right edge of the screen
                    ease: 'Linear', // Use 'Linear' for constant speed
                    duration: duration,

                    // onComplete callback will be triggered once the tween (one pass rightward) is complete
                    onComplete: function() {
                        // Once the fish reaches the right edge, it's reset back to start
                        element.directly = screenWidth+fishWidth;
                        // Restart the tween to swim the fish again
                        tween.restart();
                    }
                });
            }


            // Create a tween that moves the fish horizontally across the screen

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
