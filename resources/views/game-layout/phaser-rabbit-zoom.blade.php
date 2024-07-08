<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>
	let stopTime = 4000;
	let startTime;
	let moon_bg, cloud_bg, moving_clouds, moon, yellow_bg, mountain, moon_lotus, teapot, rabbit1, rabbit2;
	
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
	text-shadow: 1px 1px 4px #fff;
	background-color: rgba(255, 255, 255, 0.8);
	border-radius: 5px;
	}
	#start-quiz, .page-controller, #timer, #score {
	color: #000000;
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
			this.load.image('yellow_bg', '/assets/phaser/rabbit/yellow_bg.png');
			this.load.image('moon_lotus', '/assets/phaser/rabbit/moon_lotus.png');
			this.load.image('mountain', '/assets/phaser/rabbit/mountain.png');
			this.load.image('teapot', '/assets/phaser/rabbit/teapot.png');
			this.load.image('rabbit1', '/assets/phaser/rabbit/rabbit1.png');
			this.load.image('rabbit2', '/assets/phaser/rabbit/rabbit2.png');
		}
		
		create() {
			startTime = this.time.now;
			
			yellow_bg = this.add.image(960, 540, 'yellow_bg').setScale(1).setOrigin(0.5, 0.5);
			mountain = this.add.image(970, 650, 'mountain').setScale(1).setOrigin(0.5, 0.5);
			moon_lotus = this.add.image(730, 500, 'moon_lotus').setScale(1.1).setOrigin(0.5, 0.5);
			teapot = this.add.image(390, 670, 'teapot').setScale(1).setOrigin(0.5, 0.5);
			rabbit2 = this.add.image(1470, 700, 'rabbit2').setScale(1.1).setOrigin(0.5, 0.5);
			rabbit1 = this.add.image(950, 850, 'rabbit1').setScale(1).setOrigin(0.5, 0.5);
			
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
		
		scaleAndDisappear(element, scaleIncrease, context) {
			let duration = 4000;  // Duration of the operation in milliseconds
			
			// Spin and scale the zebra
			context.tweens.add({
				targets: element,
				duration: duration,
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
		
		
		update() {
			let currentTime = this.time.now;
			
			if (this.time.now - startTime < stopTime) {
				let currentTime = this.time.now;
				let elapsedTime = currentTime - startTime;
				let scaleFactor = 0.5 * Math.sin(0.0003 * elapsedTime); // slow and smooth sinusoidal zoom in/out effect
//				console.log(elapsedTime, scaleFactor);
				
				// scale each layer and displaces it with varying intensity for a 3D effect
				yellow_bg.setScale((1 + scaleFactor / 2) * 1).setY(540 + elapsedTime * 0.002);
				mountain.setScale((1 + scaleFactor / 3) * 1).setY(650 + elapsedTime * 0.006);
				moon_lotus.setScale((1 + scaleFactor / 2) * 1).setY(500 + elapsedTime * 0.002);
				teapot.setScale((1 + scaleFactor / 2) * 1).setY(670 + elapsedTime * 0.006);
				rabbit2.setScale((1 + scaleFactor) * 1).setX(1470 + elapsedTime * 0.03).setY(700 + elapsedTime * 0.01);
				rabbit1.setScale((1 + scaleFactor) * 1).setY(850 + elapsedTime * 0.01);
				
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
