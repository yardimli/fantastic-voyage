<!DOCTYPE html>
<html>
<head>
	<script src="/assets/phaser/dist/phaser.min.js"></script>
</head>
<body style="padding: 0px; margin:0px;">
<script>
	
	
	//  This Scene has no aspect ratio lock, it will scale to fit the browser window, but will zoom to match the Game
	let moon_bg, cloud_bg, moving_clouds, moon, cloud1, cloud2, cloud3, cloud4, cloud5, cloud6;
	
	class BackgroundScene extends Phaser.Scene {
		gameScene;
		layer;
		
		constructor() {
			super('BackgroundScene');
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
		
	}
	
	
	let stopTime = 4000;
	let startTime;
	let buttons = [];
	
	//  This Scene is aspect ratio locked at 640 x 960 (and scaled and centered accordingly)
	class GameScene extends Phaser.Scene {
		backgroundScene;
		gameOver = false;
		
		constructor() {
			super('GameScene');
		}
		
		preload() {
			this.load.image('button_portrait', 'assets/phaser/images/portrait.webp');
			this.load.image('button_landscape', 'assets/phaser/images/landscape.webp');
			this.load.image('button_square', 'assets/phaser/images/square.webp');
			this.load.image('button_letterbox', 'assets/phaser/images/letterbox.webp');
			
			
			this.load.image('buttonImg', 'assets/phaser/images/nice.png');
			this.load.image('frame_mid', 'assets/phaser/images/frame_mid.png');
			this.load.image('small_blue_bottle', 'assets/phaser/images/small_blue_bottle.png');
			this.load.image('boy_with_kite', 'assets/phaser/images/cartoon-happy-boy-playing-kite_29190-5260.jpg');
			
			this.load.plugin('rexbbcodetextplugin', 'assets/phaser/dist/rexbbcodetextplugin.js', true);
		}
		
		add_buttons(width, height, scene) {
			//delete all buttons
			buttons.forEach(button => button.destroy());
			buttons.length = 0;
			
			if (width > height) {
				
				let temp_scale = width / 1920;
				
				let button_width = 440 * temp_scale;
				let button_height = 210 * temp_scale;
				
				buttons.push(new Button(scene, width - (button_width * 2) + (button_width / 2) - 10, height - ((button_height * 3) + 20), button_width, button_height, '讓您的讓您的讓您的讓您的讓您的讓您的讓您的讓您的', '#000000', 'buttonImg', false, 10, 10));
				buttons.push(new Button(scene, width - (button_width * 1) + (button_width / 2), height - ((button_height * 3) + 20), button_width, button_height, '讓您的讓您的', '#000000', 'frame_mid', false, 10, 10));
				
				buttons.push(new Button(scene, width - (button_width * 2) + (button_width / 2) - 10, height - ((button_height * 2) + 10), button_width, button_height, '讓您的讓您的讓您的讓您的讓您的', '#000000', 'small_blue_bottle', false, 10, 0));
				buttons.push(new Button(scene, width - (button_width * 1) + (button_width / 2), height - ((button_height * 2) + 10), button_width, button_height, '讓您的讓您的讓您的讓您的讓您的讓您的讓您的讓您的', '#000000', '', false, 10, 0));
				
				buttons.push(new Button(scene, width - (button_width * 2) + (button_width / 2) - 10, height - ((button_height * 1) + 0), button_width, button_height, '', '#000000', 'buttonImg', false, 10, 0));
				buttons.push(new Button(scene, width - (button_width * 1) + (button_width / 2), height - ((button_height * 1) + 0), button_width, button_height, '讓您的讓您的讓您的讓您的', '#000000', '', false, 10, 0));
				
			} else {
				
				let temp_scale = height / 1080;
				
				let button_width = 160 * temp_scale;
				let button_height = 170 * temp_scale;
				
				buttons.push(new Button(scene, width - (button_width * 3) + (button_width / 2) - 20, height - ((button_height * 2) + 10), button_width, button_height, '讓您的讓您的讓您的讓您的讓您的讓您的讓您的讓您的', '#000000', 'buttonImg', false, 10, 10));
				buttons.push(new Button(scene, width - (button_width * 2) + (button_width / 2) - 10, height - ((button_height * 2) + 10), button_width, button_height, '讓您的讓您的', '#000000', 'frame_mid', false, 10, 10));
				buttons.push(new Button(scene, width - (button_width * 1) + (button_width / 2) - 0, height - ((button_height * 2) + 10), button_width, button_height, '讓您的讓您的讓您的讓您的讓您的', '#000000', 'small_blue_bottle', false, 10, 0));
				
				buttons.push(new Button(scene, width - (button_width * 3) + (button_width / 2) - 20, height - ((button_height * 1) + 0), button_width, button_height, '讓您的讓您的讓您的讓您的讓您的讓您的讓您的讓您的', '#000000', '', false, 10, 0));
				buttons.push(new Button(scene, width - (button_width * 2) + (button_width / 2) - 10, height - ((button_height * 1) + 0), button_width, button_height, '', '#000000', 'buttonImg', false, 10, 0));
				buttons.push(new Button(scene, width - (button_width * 1) + (button_width / 2) - 0, height - ((button_height * 1) + 0), button_width, button_height, '讓您的讓您的讓您的讓您的', '#000000', '', false, 10, 0));
			}
			
			// Get smallest font size from all buttons
			let smallestFontSize = Math.min(...buttons.map(button => button.getFontSize()));
			
			// Set text to smallest size for all buttons
			buttons.forEach(button => button.setFontSize(smallestFontSize));
			
		}
		
		create() {
			
			
			//  -----------------------------------
			//  -----------------------------------
			//  -----------------------------------
			//  Normal game stuff from here on down
			//  -----------------------------------
			//  -----------------------------------
			//  -----------------------------------
			
			
			this.add_buttons(this.cameras.main.width, this.cameras.main.height, this);
			
			var game_this = this;
			
			// Register handler for viewport resize
			this.scale.on('resize', function (gameSize, parentSize) {
				
				// Rescale and recenter the camera
				// var scaleX = gameSize.width / 1920;
				// var scaleY = gameSize.height / 1080;
				// var scale = Math.max(scaleX, scaleY);
				
				// camera.setZoom(scale);
				// camera.centerOn(1920 / 2, 1080 / 2);
				
				game_this.add_buttons(gameSize.width, gameSize.height, game_this);
			});
			
			
		}
		
		
		//  ------------------------
		//  ------------------------
		//  ------------------------
		//  Resize related functions
		//  ------------------------
		//  ------------------------
		//  ------------------------
		
		
		//  ------------------------
		//  ------------------------
		//  ------------------------
		//  Game related functions
		//  ------------------------
		//  ------------------------
		//  ------------------------
		
		update() {
			if (this.gameOver) {
				return;
			}
			
		}
		
	}
	
	
	class Button extends Phaser.GameObjects.Container {
		
		getFontSize() {
			if (!this.bbText) {
				return 999;
			}
			
			let fontSize = parseInt(this.bbText.style.fontSize, 10);
			
			// Check if fontSize is not a number (for instance, if fontSize was "px" or any non-numeric strings)
			if (isNaN(fontSize)) {
				fontSize = 999;
			}
			
			return fontSize;
		}
		
		setFontSize(size) {
			if (!this.bbText || this.isTextBlank) {
				return;
			}
			this.bbText.setFontSize(size + 'px');
		}
		
		constructor(scene, x, y, width, height, text, text_color, asset, debug_image_frame, padding, spacing) {
			super(scene, x, y);
			
			this.padding = padding || 0;
			this.spacing = spacing || 0;
			this.debug_image_frame = debug_image_frame || false;
			
			// Check if text or image is blank
			this.isImageBlank = (asset === '');
			this.isTextBlank = (text === '');
			
			let buttonBackground;
			if (width > height * 2) {
				buttonBackground = 'button_letterbox';
			} else if (width > height) {
				buttonBackground = 'button_landscape';
			} else if (height > width) {
				buttonBackground = 'button_portrait';
			} else {
				buttonBackground = 'button_square';
			}
			
			// Adding background image
			const bgImage = scene.add.image(0, 0, buttonBackground);
			bgImage.setScale(width / bgImage.width, height / bgImage.height);
			
			let image;
			if (!this.isImageBlank) {
				image = scene.add.image(0, 0, asset);
			}
			
			// Determine whether the button is portrait or landscape.
			const isPortrait = height > width;
			
			let imageBorderWidth = 1;
			let imageBorderColor = 0xFF0000; // Red color border
			let imageBorder;
			
			
			let paddedWidth = width - (this.padding * 2);
			let paddedHeight = height - (this.padding * 2);
			
			if (isPortrait) {
				paddedHeight = paddedHeight - this.spacing;
			} else {
				paddedWidth = paddedWidth - this.spacing;
			}
			
			let text_width;
			let text_align;
			let image_ratio = 1;
			
			
			if (!this.isTextBlank) {
				
				if (isPortrait) {
					text_width = this.isTextBlank ? paddedWidth : paddedWidth;
					text_align = 'center';
				} else {
					if (!this.isImageBlank) {
						text_width = this.isTextBlank ? paddedWidth : paddedWidth / 2;
						text_align = 'left';
					} else {
						text_width = this.isTextBlank ? paddedWidth : paddedWidth;
						text_align = 'center';
					}
				}
				
				
				let font_style = {
					fontFamily: 'Arial',
					fontSize: 32,
					color: text_color,
					halign: text_align,
					wrap: {
						width: text_width,
						mode: 'character'
					}
				};
				this.bbText = scene.add.rexBBCodeText(0, 0, text.replace(/\[br\]/gi, "\n"), font_style);
			}
			
			if (!this.isImageBlank && !this.isTextBlank) {
				if (isPortrait) {
					image_ratio = Math.min((paddedWidth) / image.width, (paddedHeight / 2) / (image.height));
					image.setScale(image_ratio, image_ratio);
				} else {
					image_ratio = Math.min((paddedWidth / 2) / image.width, paddedHeight / image.height);
					image.setScale(image_ratio, image_ratio);
				}
			} else if (!this.isImageBlank && this.isTextBlank) {
				image_ratio = Math.min(paddedWidth / image.width, paddedHeight / image.height);
				image.setScale(image_ratio, image_ratio);
				
			}
			
			if (!this.isImageBlank && this.debug_image_frame) {
				imageBorder = scene.add.graphics({lineStyle: {width: imageBorderWidth, color: imageBorderColor}});
				imageBorder.strokeRect(0, 0, image.displayWidth, image.displayHeight);
				imageBorder.x = image.x - image.displayWidth / 2;
				imageBorder.y = image.y - image.displayHeight / 2;
			}
			
			if (!isPortrait && !this.isTextBlank) { // If button is landscape or square
				if (!this.isImageBlank) {
					this.bbText.setOrigin(0, 0.5); // Set text origin
				} else {
					this.bbText.setOrigin(0.5); // Set text origin
				}
				
				// Try to decrease the font size until it fits in maximum width
				let fontSize = 50;
				
				// Set initial font size
				this.bbText.setFontSize(fontSize + 'px');
				
				// Decrease the font size until text fits in maximum width
				while (this.bbText.width > paddedWidth / (this.isImageBlank ? 1 : 2) || this.bbText.height > paddedHeight) {
					fontSize--;
					this.bbText.setFontSize(fontSize + 'px');
				}
			} else if (isPortrait && !this.isTextBlank) {
				this.bbText.setOrigin(0.5); // Set text origin
				
				// Try to decrease the font size until it fits in maximum width
				let fontSize = Math.min(1, paddedWidth) * 32;
				
				// Set initial font size
				this.bbText.setFontSize(fontSize + 'px');
				
				// Decrease the font size until text fits in maximum width
				while (this.bbText.width > paddedWidth || this.bbText.height > paddedHeight / 2) {
					fontSize--;
					this.bbText.setFontSize(fontSize + 'px');
				}
				
			}
			
			this.add(bgImage);
			if (!this.isImageBlank) {
				this.add(image);
				if (this.debug_image_frame) {
					this.add(imageBorder);
				}
			}
			
			if (!this.isTextBlank) {
				this.add(this.bbText);
			}
			
			this.calculatePlacement(width, height);
			
			this.setSize(width, height);
			this.setInteractive({
					hitArea: new Phaser.Geom.Rectangle(0, 0, width, height),
					hitAreaCallback: Phaser.Geom.Rectangle.Contains
				})
				.on('pointerover', () => this.enterButtonHoverState())
				.on('pointerout', () => this.enterButtonRestState())
				.on('pointerdown', () => this.enterButtonActiveState())
				.on('pointerup', () => this.enterButtonHoverState());
			
			scene.add.existing(this);
		}
		
		calculatePlacement(width, height) {
			if (!this.isImageBlank && !this.isTextBlank) {
				
				let img = this.list[1];
				let txt;
				let img_border;
				if (this.debug_image_frame) {
					img_border = this.list[2];
					txt = this.list[3];
				} else {
					txt = this.list[2];
				}
				
				if (width >= height) {
					img.x = img.x - (((width - (this.padding * 2)) / 4) + (this.spacing / 2));
					if (this.debug_image_frame) {
						img_border.x = img_border.x - (((width - (this.padding * 2)) / 4) + (this.spacing / 2));
					}
					txt.x = txt.x + (this.spacing / 2);
				} else {
					// Here we have to place the image and the text one above the other
					img.y = img.y - (((height - (this.padding * 2)) / 4) - (this.spacing / 2));
					if (this.debug_image_frame) {
						img_border.y = img_border.y - (((height - (this.padding * 2)) / 4) - (this.spacing / 2));
					}
					txt.y = txt.y + (((height - (this.padding * 2)) / 4)) + (this.spacing / 2);
				}
			}
		}
		
		enterButtonHoverState() {
			// this.list[3].setStyle({ fill: '#ff0'});
			this.scene.tweens.add({
				targets: [this.list[0]],
				alpha: {from: 1, to: 0.7},
				duration: 1000,
				loop: 5,
				yoyo: true,
			});
		}
		
		enterButtonRestState() {
			this.scene.tweens.killTweensOf(this.list[0]);
			// this.list[3].setStyle({ fill: '#ffffff'});
			this.scene.tweens.add({
				targets: [this.list[0]],
				alpha: {from: 0.7, to: 1},
				duration: 500,
			});
		}
		
		enterButtonActiveState() {
			this.scene.tweens.killTweensOf(this.list[0]);
			// this.list[3].setStyle({ fill: '#0ff'});
			this.scene.tweens.add({
				targets: [this.list[0]],
				alpha: {from: 1, to: 0.5},
				duration: 500,
			});
		}
	}
	
	
	const config = {
		type: Phaser.AUTO,
		backgroundColor: '#000000',
		scale: {
			mode: Phaser.Scale.RESIZE, type: Phaser.CANVAS,
			parent: 'phaser-example',
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
	
	
	// 	let config = {
	// 		type: Phaser.AUTO,
	// 		width: window.innerWidth,
	// 		height: window.innerHeight,
	// 		// width: 1920,
	// 		// height: 1080,
	// 		scene: {
	// 			preload: preload,
	// 			create: create,
	// 			update: update
	// 		},
	// 		scale: {
	// 			mode: Phaser.Scale.RESIZE, type: Phaser.CANVAS, //keeps the aspect ratio and scale the game up to fit the screen
	// 			autoCenter: Phaser.Scale.CENTER_BOTH // Centers the game canvas
	// 		},
	// 	};
	//
	// 	let game = new Phaser.Game(config);
	// 	var game_width = game.config.width;
	//
	// 	function preload() {
	// 		this.load.image('button_portrait', 'assets/phaser/images/portrait.webp');
	// 		this.load.image('button_landscape', 'assets/phaser/images/landscape.webp');
	// 		this.load.image('button_square', 'assets/phaser/images/square.webp');
	// 		this.load.image('button_letterbox', 'assets/phaser/images/letterbox.webp');
	//
	//
	// 		this.load.image('buttonImg', 'assets/phaser/images/nice.png');
	// 		this.load.image('frame_mid', 'assets/phaser/images/frame_mid.png');
	// 		this.load.image('small_blue_bottle', 'assets/phaser/images/small_blue_bottle.png');
	// 		this.load.image('boy_with_kite', 'assets/phaser/images/cartoon-happy-boy-playing-kite_29190-5260.jpg');
	//
	// 		this.load.plugin('rexbbcodetextplugin', 'assets/phaser/dist/rexbbcodetextplugin.js', true);
	//
	// 	}
	//
	//
	// 	function create() {
	// 		startTime = this.time.now;
	//
	//
	// 		//question text
	// 		let question_text = 'What is the best option?';
	// 		let question_color = '#ffffff';
	// 		let font_style = {
	// 			fontFamily: 'Arial',
	// 			fontSize: 50,
	// 			color: question_color,
	// 			halign: 'left',
	// 			wrap: {
	// 				width: this.width,
	// 				mode: 'character'
	// 			}
	// 		};
	//
	//
	// 		this.bbQuestionText = this.add.rexBBCodeText(config.width / 2, 30, question_text.replace(/\[br\]/gi, "\n"), font_style);
	// 		this.bbQuestionText.setOrigin(0.5, 0);
	//
	// 		let question_image = 'boy_with_kite';
	// 		const questionImage = this.add.image(500, 500, question_image);
	// 		questionImage.setOrigin(0.5);
	//
	// 		let sceneWidth = this.cameras.main.width; // get the width of the scene
	// 		let sceneHeight = this.cameras.main.height; // get the height of the scene
	//
	// 		let targetWidth = sceneWidth * 0.4; // 40% of scene's width
	// 		let targetHeight = sceneHeight * 0.75; // 75% of scene's height
	//
	// 		let scaleW = targetWidth / questionImage.width; // calculate width scale
	// 		let scaleH = targetHeight / questionImage.height; // calculate height scale
	//
	// 		let scale = (questionImage.height * scaleW) > targetHeight ? scaleH : scaleW; // choose scale
	//
	// // Apply the scale
	// 		questionImage.setScale(scale);
	//
	// // Position the image
	// // horizontal: the middle of the left 50% of the scene, that is, 25% of the scene's width
	// // vertical: the middle of the scene height plus a top margin of 120px
	// 		questionImage.x = sceneWidth * 0.25;
	// 		questionImage.y = sceneHeight * 0.4 + 120;
	//
	// 		const questionImageBorder = this.add.graphics({lineStyle: {width: 2, color: 0xFFFF00}});
	// 		questionImageBorder.strokeRect(0, 0, questionImage.displayWidth, questionImage.displayHeight);
	// 		questionImageBorder.x = questionImage.x - questionImage.displayWidth / 2;
	// 		questionImageBorder.y = questionImage.y - questionImage.displayHeight / 2;
	//
	//
	// 		//buttons
	//
	// 		buttons.push(new Button(this, 1100, 400, 340, 200, '讓您的讓您的讓您的讓您的讓您的讓您的讓您的讓您的', '#000000', 'buttonImg', false, 10, 10));
	// 		buttons.push(new Button(this, 1130, 230, 400, 100, '讓您的讓您的', '#000000', 'frame_mid', false, 10, 10));
	// 		buttons.push(new Button(this, 1400, 400, 200, 200, '讓您的讓您的讓您的讓您的讓您的', '#000000', 'small_blue_bottle', false, 10, 0));
	//
	// 		buttons.push(new Button(this, 1030, 620, 200, 200, '讓您的讓您的讓您的讓您的讓您的讓您的讓您的讓您的', '#000000', '', false, 10, 0));
	// 		buttons.push(new Button(this, 1260, 645, 200, 250, '', '#000000', 'buttonImg', false, 10, 0));
	//
	// 		// Get smallest font size from all buttons
	// 		let smallestFontSize = Math.min(...buttons.map(button => button.getFontSize()));
	//
	// 		// Set text to smallest size for all buttons
	// 		buttons.forEach(button => button.setFontSize(smallestFontSize));
	//
	// 	}
	//


</script>
</body>
</html>
