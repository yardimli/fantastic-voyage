<script src="/assets/phaser/dist/phaser.min.js"></script>
<script>

	let stopTime = 4000;
	let startTime;
	let sky, mountains, lake, grass, tree, table, sunflower;
	
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

	class BackgroundScene extends Phaser.Scene{
		gameScene;
		layer;

		constructor() {
			super('BackgroundScene');
		}

		preload() {
			this.load.image('sky', '/assets/phaser/summer/summer_sky.webp');
			this.load.image('mountains', '/assets/phaser/summer/summer_mountains.webp');
			this.load.image('lake', '/assets/phaser/summer/summer_lake.webp');
			this.load.image('grass', '/assets/phaser/summer/summer_grass.webp');
			this.load.image('tree', '/assets/phaser/summer/summer_tree.webp');
			this.load.image('table', '/assets/phaser/summer/summer_table.webp');
			this.load.image('sunflower', '/assets/phaser/summer/summer_sunflower.webp');
		}

		create() {
			startTime = this.time.now;

			sky = this.add.image(512, 300, 'sky').setScale(1.5).setOrigin(0.5, 0.5);
			sky.originalYPosition = sky.y;
			mountains = this.add.image(512, 300, 'mountains').setScale(0.8).setOrigin(0.5, 0.5);
			lake = this.add.image(512, 500, 'lake').setScale(0.8).setOrigin(0.5, 0.5);
			grass = this.add.image(512, 400, 'grass').setScale(1.2).setOrigin(0.5, 0.5);
			tree = this.add.image(850, 150, 'tree').setScale(1.2).setOrigin(0.5, 0.5);
			table = this.add.image(140, 440, 'table').setScale(1).setOrigin(0.5, 0.5);
			sunflower = this.add.image(600, 550, 'sunflower').setScale(1).setOrigin(0.5, 0.5);
		}

		update() {
			let currentTime = this.time.now;
			if (this.time.now - startTime < stopTime) {
				let currentTime = this.time.now;
				let elapsedTime = currentTime - startTime;
				let scaleFactor = 0.5 * Math.sin(0.0003 * elapsedTime) ; // slow and smooth sinusoidal zoom in/out effect
				// console.log(elapsedTime,scaleFactor);

				// scale each layer and displaces it with varying intensity for a 3D effect
				sky.setScale((1+ scaleFactor/6 ) * 1.5).setY(sky.originalYPosition + elapsedTime * 0.002);
				mountains.setScale((1+ scaleFactor/5) * 0.8).setY(300 + elapsedTime * 0.004);
				lake.setScale((1+ scaleFactor/3) * 0.8).setY(500 + elapsedTime * 0.006);
				grass.setScale((1+ scaleFactor) * 1.2).setY(400 + elapsedTime * 0.008);
				tree.setScale((1+ scaleFactor) * 1.2).setY(150 + elapsedTime * 0.010);
				table.setScale((1+ scaleFactor) * 1).setY(440 + elapsedTime * 0.012);
				sunflower.setScale((1+ scaleFactor*10) * 1).setY(550 + elapsedTime * 0.09);
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


</script>
