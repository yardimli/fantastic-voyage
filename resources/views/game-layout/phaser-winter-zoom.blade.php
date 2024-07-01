<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>
	//  This Scene has no aspect ratio lock, it will scale to fit the browser window, but will zoom to match the Game
	let stopTime = 4000;
	let startTime;
	let moon_bg, cloud_bg, moving_clouds, moon, cloud1, cloud2, cloud3, cloud4, cloud5, cloud6;
	
	let answer_button_square_img = '/assets/phaser/images/square.webp';
	let answer_button_portrait_img = '/assets/phaser/images/portrait.webp';
	let answer_button_landscape_img = '/assets/phaser/images/landscape.webp';
	let answer_button_letterbox_img = '/assets/phaser/images/letterbox.webp';
	
	//set properties of css id #question-div
	var style = document.createElement('style');
	style.type = 'text/css';
	style.innerHTML =  `
	#question-div { color: black; }
	.answer-text { color: green; }
	
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
			this.load.image('moon_bg', '/assets/phaser/moon/moon_bg.webp');
			this.load.image('cloud_bg', '/assets/phaser/moon/cloud_bg.webp');
			this.load.image('cloud1', '/assets/phaser/moon/cloud1.png');
			this.load.image('cloud2', '/assets/phaser/moon/cloud2.png');
			this.load.image('cloud3', '/assets/phaser/moon/cloud3.png');
			this.load.image('cloud4', '/assets/phaser/moon/cloud4.png');
			this.load.image('cloud5', '/assets/phaser/moon/cloud5.png');
			this.load.image('cloud6', '/assets/phaser/moon/cloud6.png');
			this.load.image('moon', '/assets/phaser/moon/moon.webp');
		}

		create() {
			startTime = this.time.now;

			moon_bg = this.add.image(950, 540, 'moon_bg').setScale(1).setOrigin(0.5, 0.5);
			cloud_bg = this.add.image(950, 540, 'cloud_bg').setScale(1).setOrigin(0.5, 0.5);
			cloud6 = this.add.image(1650, 340, 'cloud6').setScale(1).setOrigin(0.5, 0.5);
			cloud3 = this.add.image(750, 240, 'cloud3').setScale(1).setOrigin(0.5, 0.5);
			cloud4 = this.add.image(750, 640, 'cloud4').setScale(1).setOrigin(0.5, 0.5);
			moon = this.add.image(1150, 440, 'moon').setScale(1).setOrigin(0.5, 0.5);
			cloud1 = this.add.image(1700, 540, 'cloud1').setScale(1).setOrigin(0.5, 0.5);
			cloud2 = this.add.image(50, 840, 'cloud2').setScale(1).setOrigin(0.5, 0.5);
			cloud5 = this.add.image(1450, 880, 'cloud5').setScale(1).setOrigin(0.5, 0.5);


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
				moon_bg.setScale((1 + scaleFactor / 4) * 1).setY(540 + elapsedTime * 0.002);
				cloud_bg.setScale((1 + scaleFactor / 5) * 1).setY(540 + elapsedTime * 0.006);
				cloud1.setScale((1 + scaleFactor) * 1).setY(540 + elapsedTime * 0.02);
				cloud2.setScale((1 + scaleFactor) * 1).setY(840 + elapsedTime * 0.006);
				cloud3.setScale((1 + scaleFactor / 4) * 1).setY(240 + elapsedTime * 0.006);
				cloud4.setScale((1 + scaleFactor) * 1).setY(640 + elapsedTime * 0.008);
				cloud5.setScale((1 + scaleFactor) * 1).setY(880 + elapsedTime * 0.004);
				cloud6.setScale((1 + scaleFactor) * 2).setY(340 + elapsedTime * 0.004);
				moon.setScale((1 + scaleFactor / 3) * 1).setY(540 + elapsedTime * 0.01);
			}
			this.slideRight(cloud1, 0.2);
			this.slideRight(cloud2, 0.2);
			this.slideRight(cloud3, 0.2);
			this.slideRight(cloud4, 2);
			this.slideRight(cloud5, 0.1);
			this.slideRight(cloud6, 0.1);
		}

		slideRight(element, speed) {
			const width = this.scale.gameSize.width;
			// Move your sprite to the right by increasing its x value in update function
			element.x += speed;
			let scaledElementWidth = element.width * element.scaleX;
			// If the sprite's x position is greater than the game's width (meaning it has disappeared on the right)
			// Reset its x position to the left side (outside the screen)
			if (element.x > width + scaledElementWidth / 2) {
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
