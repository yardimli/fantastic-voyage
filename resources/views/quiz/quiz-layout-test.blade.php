<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Full Window Div with Children</title>
	<style>
		html, body {
			margin: 0;
			padding: 0;
			height: 100%;
			width: 100%;
			overflow: hidden;
		}

		#main-div {
			position: absolute;
			top: 0;
			left: 0;
			border: 4px solid yellow;
			background-color: lightblue;
			z-index: 10;
		}

		#question-div, #question-image-div, #answers-div {
			position: absolute;
			background-color: #ddd;
		}

		#question-div {
			background-color: green;
			font-size: 34px;
			overflow: hidden;
			padding: 4px;
			box-sizing: border-box;
		}

		.question-img{
			width: 100%;
		}

		#question-image-div {
			background-color: red;
		}

		#answers-div {
			background-color: blue;
		}

		.answer-btn {
			background-color: white;
			color: black;
			border: 1px solid #000;
			position: absolute;
			text-align: center;
			line-height: 2; /* This is arbitrary, adjust as necessary */
			cursor: pointer;
			justify-content: center;
			align-items: center;
			padding: 10px; /* Adjust padding to create space for image text */
			box-sizing: border-box;
		}

		.answer-img {
			height: 50%;
			width: auto; /* You can adjust this value as needed to fit your design */
			max-width: 100%;
			margin-right: 10px; /* Space between the image and the text */
		}

		.answer-text {
			height: 70%;
			width: auto;
		}

	</style>
</head>
<body>
<div id="main-div">
	<div id="question-div"></div>
	<div id="question-image-div"></div>
	<div id="answers-div"></div>
</div>

<script>
	function resizeDivToWindowSize() {
		console.log('resizeDivToWindowSize');

		var windowWidth, windowHeight;

		if (window.orientation === 0 || window.orientation === 180) { // Portrait
			windowWidth = Math.min(window.screen.width, window.innerWidth);
			windowHeight = Math.min(window.screen.height, window.innerHeight);
		} else { // Landscape
			windowWidth = Math.max(window.screen.width, window.innerWidth);
			windowHeight = Math.max(window.screen.height, window.innerHeight);
		}

		var borderOffset = 8;

		var mainDiv = document.getElementById('main-div');
		mainDiv.style.width = (windowWidth - borderOffset) + 'px';
		mainDiv.style.height = (windowHeight - borderOffset) + 'px';

		var questionDiv = document.getElementById('question-div');

		// Reset the height to 'auto' before setting the width
		questionDiv.style.height = 'auto';
		questionDiv.style.width = mainDiv.style.width;

		// Now calculate and set the height based on the new scrollHeight
		var questionDivHeight = questionDiv.scrollHeight;
		questionDiv.style.height = questionDivHeight + 'px';


		var bottomDivHeight;
		var bottomDivsWidth;
		if (windowWidth < 600) {
			bottomDivHeight = (parseInt(mainDiv.style.height) - questionDivHeight) / 2;
			bottomDivsWidth = parseInt(mainDiv.style.width);
		} else {
			console.log(mainDiv.style.width);
			bottomDivHeight = parseInt(mainDiv.style.height) - questionDivHeight;
			bottomDivsWidth = (parseInt(mainDiv.style.width) / 2);
		}

		var questionImageDiv = document.getElementById('question-image-div');
		questionImageDiv.style.width = bottomDivsWidth + 'px';
		questionImageDiv.style.height = bottomDivHeight + 'px';
		questionImageDiv.style.top = questionDivHeight + 'px';
		questionImageDiv.style.left = '0px';

		var answersDiv = document.getElementById('answers-div');
		answersDiv.style.width = bottomDivsWidth + 'px';
		answersDiv.style.height = bottomDivHeight + 'px';
		answersDiv.style.top = (windowWidth < 600) ? (questionDivHeight + bottomDivHeight) + 'px' : questionDivHeight + 'px';
		answersDiv.style.left = (windowWidth < 600) ? '0px' : bottomDivsWidth + 'px';

		if (questionImageDiv.style.display == 'none') {
			// If questionImageDiv is not visible, set answersDiv to 100% width
			answersDiv.style.width = mainDiv.style.width;
			answersDiv.style.top = questionDivHeight + 'px';
			answersDiv.style.left = '0px';
			bottomDivsWidth = parseInt(mainDiv.style.width);
			bottomDivHeight = parseInt(mainDiv.style.height) - questionDivHeight;
			// Adjust height accordingly if a new layout is desired for answersDiv
		}


		// Empty the answersDiv and recreate buttons on each resize to ensure clean state
		answersDiv.innerHTML = '';
		var buttonsArray = [];

		// answers = ['Answer 1', 'Answer 2', 'Answer 3'];
		var answers = ['Answer 1', 'Answer 2', 'Answer 3', 'Answer 4', 'Answer 5', 'Answer 6'];
		// Create and append the buttons
		var columns = answers.length > 4 ? 3 : 2;

		for (var i = 0; i < answers.length; i++) {
			var button = document.createElement('div');
			button.className = 'answer-btn';
			// button.textContent = 'Answer ' + (i + 1) + ' - ' + getRandomParagraph();
			button.style.width = (bottomDivsWidth / columns - 2) + 'px'; // Subtracting 2 for border width
			button.style.height = (answers.length == 2 ? bottomDivHeight : bottomDivHeight / 2 - 2) + 'px'; // Subtracting 2 for border height
			button.style.top = (i < columns ? 0 : bottomDivHeight / 2) + 'px';
			if (columns === 3) {
				if (i % 3 === 0) {
					button.style.left = 0;
				} else {
					button.style.left = (i % 3 === 1 ? bottomDivsWidth / 3 : (bottomDivsWidth / 3)*2) + 'px';
				}
			}else{
				button.style.left = (i % 2 === 0 ? 0 : bottomDivsWidth / 2) + 'px';
			}

			// Create an img element
			var img = document.createElement('img');
			img.src = '/blog_images/covid-19-1-150x150.jpg'; // Replace with the actual path to your image
			img.className = 'answer-img';
			button.appendChild(img);

			// Create a span element for the text to keep it separate from the image
			var answerText = document.createElement('div');
			answerText.className = 'answer-text';
			answerText.textContent = 'Answer ' + (i + 1) + ' - ' + getRandomParagraph();
			button.appendChild(answerText);

			answersDiv.appendChild(button);

			buttonsArray.push(button);
			adjustFontSizeToFit(buttonsArray);
		}

	}

	function insertRandomParagraph() {
		var questionDiv = document.getElementById('question-div');
		// Decide whether the question text is available using a random condition or some logic
		if (Math.random() > 0.5) { // Let's say there's a 50% chance to have question text
			var text = "A random paragraph text that should be long enough to demonstrate the dynamic resizing of the top division based on its content. This text could continue with more sentences to ensure that the height adjustment is noticeable even when the inner content increases.";
			questionDiv.textContent = text;
			questionDiv.style.display = 'block'; // Show the div if there is text
			console.log('has question text');
		} else {
			questionDiv.style.display = 'none'; // Hide the div if there is no text
			console.log('no question text');
		}
	}

	function insertQuestionImage() {
		var questionImageDiv = document.getElementById('question-image-div');
		// Decide whether the question image is available using a random condition or some logic
		if (Math.random() > 0.5) { // Let's say there's a 50% chance to have a question image
			// You can set the image src or you can have an <img> tag already in the HTML and simply show or hide it
			questionImageDiv.style.display = 'block'; // Show the div if there is an image
			console.log('has question image');
		} else {
			questionImageDiv.style.display = 'none'; // Hide the div if there is no image
			console.log('no question image');
		}
	}

	function getRandomParagraph() {
		var paragraphs = [
			"Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
			"Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.",
			"Sed nisi. Nulla quis sem at nibh elementum imperdiet.",
			"Duis sagittis ipsum. Praesent mauris.",
			"Fusce nec tellus sed augue semper porta. Mauris massa."
		];
		// Get a random index from paragraphs array
		var randomIndex = Math.floor(Math.random() * paragraphs.length);
		return paragraphs[randomIndex];
	}

	function adjustFontSizeToFit(elements) {
		let smallestFontSize = Infinity; // Start with a large number to get the minimum

		// First pass: find the smallest font size that fits all buttons
		for (let element of elements) {
			let maxHeight = element.offsetHeight;
			let maxWidth = element.offsetWidth;
			let fontSize = maxHeight;
			let textOverflow = false;

			do {
				element.style.fontSize = `${fontSize}px`;
				textOverflow = element.scrollHeight > maxHeight || element.scrollWidth > maxWidth;
				fontSize -= 1;
			} while (textOverflow && fontSize > 8);

			if (fontSize < smallestFontSize) {
				smallestFontSize = fontSize; // store the smallest font size
			}
		}

		// Second pass: apply the smallest font size to all elements
		for (let element of elements) {
			element.style.fontSize = `${smallestFontSize}px`;
		}
	}

	insertRandomParagraph();
	insertQuestionImage();
	resizeDivToWindowSize();

	let resizeIsGoingOn = false;
	let resizeTimer = null;

	window.addEventListener('resize', function () {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function () {
			if (resizeIsGoingOn) {
				return;
			}
			console.log('start resize');
			resizeIsGoingOn = true;
			resizeDivToWindowSize();
			resizeIsGoingOn = false;
			console.log('end resize');
		}, 50);
	});

</script>

</body>
</html>
