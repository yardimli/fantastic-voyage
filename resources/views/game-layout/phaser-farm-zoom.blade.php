<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>
	let stopTime = 4000;
	let startTime;
	let farm_bg, bird1, bird2, chickens, cloud1, cloud2, cloud3, cloud4, cow1, cow2, fan1, fan2;

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
			this.load.image('farm_bg', '/assets/phaser/farm/farm_bg.png');
			this.load.image('bird1', '/assets/phaser/farm/bird1.png');
            this.load.image('bird2', '/assets/phaser/farm/bird2.png');
            this.load.image('chickens', '/assets/phaser/farm/chickens.png');
            this.load.image('cloud1', '/assets/phaser/farm/cloud1.png');
            this.load.image('cloud2', '/assets/phaser/farm/cloud2.png');
            this.load.image('cloud3', '/assets/phaser/farm/cloud3.png');
            this.load.image('cloud4', '/assets/phaser/farm/cloud4.png');
            this.load.image('cow1', '/assets/phaser/farm/cow1.png');
            this.load.image('cow2', '/assets/phaser/farm/cow2.png');
            this.load.image('fan1', '/assets/phaser/farm/fan1.png');
            this.load.image('fan2', '/assets/phaser/farm/fan2.png');
		}

		create() {
			startTime = this.time.now;

            farm_bg = this.add.image(960, 540, 'farm_bg').setScale(1).setOrigin(0.5, 0.5);
            bird1 = this.add.image(600, 820, 'bird1').setScale(0.6).setOrigin(0.5, 0.5);
            chickens = this.add.image(1750, 700, 'chickens').setScale(0.6).setOrigin(0.5, 0.5);
            cloud1 = this.add.image(500, 400, 'cloud1').setScale(0.8).setOrigin(0.5, 0.5);
            cloud2 = this.add.image(1250, 130, 'cloud2').setScale(0.6).setOrigin(0.5, 0.5);
            cloud3 = this.add.image(1850, 250, 'cloud3').setScale(0.5).setOrigin(0.5, 0.5);
            cloud4 = this.add.image(200, 300, 'cloud4').setScale(0.5).setOrigin(0.5, 0.5);
            cow1 = this.add.image(1000, 600, 'cow1').setScale(0.5).setOrigin(0.5, 0.5);
            cow2 = this.add.image(1000, 600, 'cow2').setScale(0.5).setOrigin(0.5, 0.5);
            fan1 = this.add.image(1360, 480, 'fan1').setScale(0.6).setOrigin(0.5, 0.55);
            fan2 = this.add.image(650, 300, 'fan2').setScale(0.7).setOrigin(0.48, 0.68);

            this.upDownMotion(chickens, 3, 1000, this);
            this.leftRightMotion(cloud1, 20, 3000, this);
            this.leftRightMotion(cloud3, 10, 2500, this);
            this.leftRightMotion(cloud4, 25, 2500, this);
            this.spin(fan1, 6000, this);
            this.spin(fan2, 4500, this);
            this.jumpAndFlip(bird1, 10, 1000, this)
            this.toggleImages(cow1, cow2, 1000, this);


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
                farm_bg.setScale((1 +  scaleFactor / 5) * 1).setY(340 + elapsedTime * 0.001);
                fan1.setScale((1 + scaleFactor / 1) * 0.8).setY(480 + elapsedTime * 0.003).setX(1360 + elapsedTime * 0.003);
                fan2.setScale((1 + scaleFactor / 1) * 0.7).setY(255 + elapsedTime * 0.003).setX(630 + elapsedTime * 0.003);
                cow1.setScale((1 + scaleFactor / 1) * 0.6).setY(600 + elapsedTime * 0.003);
                cow2.setScale((1 + scaleFactor / 1) * 0.6).setY(600 + elapsedTime * 0.003);
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

        spin(element, duration, context) {
            // Add a tween to animate left and right motion
            context.tweens.add({
                targets: element,
                duration: duration,
                angle: 360,
                repeat: -1, // Repeat indefinitely for continuous motion
            });
        }

        toggleImages(image1, image2, switchDuration, context) {
            // Set initial visibility states
            image1.setVisible(true);
            image2.setVisible(false);

            context.time.addEvent({
                delay: switchDuration, // Duration to switch images
                callback: () => {
                    // Toggle visibility states
                    image1.setVisible(!image1.visible);
                    image2.setVisible(!image2.visible);
                },
                loop: true, // Repeat indefinitely
            });
        }

        jumpAndFlip(bird, stepDistance, stepDuration, context) {
            // First, setup a tween to jump two steps to the left
            context.tweens.add({
                targets: bird,
                x: `-=${stepDistance}`, // Move left by stepDistance
                y: `-=${stepDistance}`, // Move up by stepDistance
                duration: stepDuration/2,
                ease: "Power1",
                onComplete: () => {
                    // Move further left for the second step
                    context.tweens.add({
                        targets: bird,
                        x: `-=${stepDistance}`, // Move left by stepDistance again
                        y: `+=${stepDistance}`, // Move up by stepDistance
                        duration: stepDuration,
                        ease: "Power1",
                        onComplete: () => {
                            bird.setScale(-0.6, 0.6);
                            // Then jump two steps to the right
                            context.tweens.add({
                                targets: bird,
                                x: `+=${stepDistance}`, // Move right by the combined stepDistance
                                y: `-=${stepDistance}`, // Move up by stepDistance
                                duration: stepDuration/2, // Takes twice as long to return as its two steps combined
                                ease: "Power1",
                                onComplete: () => {
                                    context.tweens.add({
                                        targets: bird,
                                        x: `+=${stepDistance}`, // Move right by the combined stepDistance
                                        y: `+=${stepDistance}`, // Move up by stepDistance
                                        duration: stepDuration, // Takes twice as long to return as its two steps combined
                                        ease: "Power1",
                                        onComplete: () => {
                                            bird.setScale(0.6, 0.6);
                                            // Repeat the jump and flip indefinitely
                                            this.jumpAndFlip(bird, stepDistance, stepDuration, context);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
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

			var game_this = this;

			// Register handler for viewport resize
			this.scale.on('resize', function (gameSize, parentSize) {

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
