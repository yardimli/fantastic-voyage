let isMute = false;
var fadeIn = false;
var currentAudio = null;
var continueAudioPlayback = true;
let isFullscreen = false;
var isOnMobile = false;
var displayInPage = false;
var audioTimer1 = null;
let timerDisplay = null;
let runningTimer;
let seconds = 0, minutes = 0;
//look at the user agent to determine if the user is on a mobile device
if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
	isOnMobile = true;
}
$(document).ready(function () {
	timerDisplay = document.getElementById('timer');
	currentAudio = document.getElementById('audio-player');
	
	$('#loading-page').hide();
	
	$('#preload-page .story-title').text(story_title);
	$('#type-description').text(type_description);
	const elementForFullscreen = document.getElementById('game-ui-in-page');
	if (elementForFullscreen) {
		displayInPage = true;
		var fullScreenButton = document.getElementById('full-screen');
	}
	
	isMute = getCookie('isMute') === 'true' ? true : false;
	if (isMute) {
		$('#mute-audio').attr('src', '/assets/phaser/images/mute.png');
		$('.audio-image').attr('src', '/assets/phaser/images/sound.png');
		$('.audio-image').addClass('audio-image-disabled');
	}
	
	//$('#preload-page').hide();
	// showStory(true);
	// $('.page-controller').css('display', 'block');
	// $('#start-story').css('display', 'none');
	
	if (displayInPage) {
		if (!isOnMobile) {
			$('#full-screen').show();
			
			fullScreenButton.addEventListener('click', function () {
				// Toggle fullscreen on click
				if (!isFullscreen) {
					console.log('open fullscreen');
					openFullscreen(elementForFullscreen);
					fullScreenButton.src = '/assets/phaser/images/shrink_screen.png';
					isFullscreen = true;
				} else {
					console.log('close fullscreen');
					fullScreenButton.src = '/assets/phaser/images/full_screen.png';
					closeFullscreen();
					isFullscreen = false;
				}
			});
			
		} else {
			$('#game-ui-in-page').css('height', '200px');
			$('.start-btn-text').text('Preview');
			$('#type-description').hide();
			$('.story-title').hide();
		}
		
		document.addEventListener('fullscreenchange', function () {
			isFullscreen = !!document.fullscreenElement;
		});
	}
	
	$('.start-btn').click(function () {
		
		if (isOnMobile && displayInPage) {
			var activity_id = $('#activity_id').val();
			var gameUrl = '/load-story/' + activity_id;
			window.open(gameUrl, '_blank');
		} else {
			// Hide the #preload-page
			$('#preload-page').hide();
			for (let i = 0; i < game.scene.scenes.length; ++i) {
				let scene = game.scene.scenes[i];
				
				if (scene && typeof scene.reset_scene === 'function') {
					scene.reset_scene();
				}
			}
			
			showStory(true);
			// $('.page-controller').css('display', 'block');
			$('#start-story').css('display', 'none');
		}
	});
	
	
	$('#start-story').on('click', function () {
		if (!runningTimer) {
			$('#timer').css('display', 'inline-block');
			runningTimer = setInterval(updateTimerDisplay, 1000); // Update every second
		}
		// $('.page-controller').css('display', 'block');
		$('#start-story').css('display', 'none');
		showStory(true);
	});
	
	$(document).on('click', '.story-answer-btn', function () {
		var width = $(this).outerWidth();
		var height = $(this).outerHeight();
		if (currentAudio) {
			continueAudioPlayback = false;
			currentAudio.pause();
			currentAudio.currentTime = 0;
		}
		$('.story-answer-btn').removeClass('animate-zoom');
		$(this).addClass('animate-zoom');
		
		$('.story-answer-btn').prop('disabled', true);
		
		const correctImage = document.createElement('img');
		correctImage.src = '/assets/phaser/images/correct.png'; // Set the source of your correct image
		correctImage.classList.add('correct-image', 'fade-in');
		setImageSize(correctImage, width, height);
		
		this.appendChild(correctImage);
		
		setTimeout(() => {
			correctImage.classList.add('visible');
			correctImage.classList.add('zoom-in-rotate');
		}, 10);
		
		setTimeout(function () {
			goToNextQuestion();
		}, 1500);
		
	});
	
	$(document).on('click', '.audio-image', function (e) {
		e.stopPropagation();
		if (isMute) {
			return;
		}
		var newAudioSrc = $(this).data('audio');
		var audio = [];
		// Check if there's a currently playing audio.
		if (currentAudio && currentAudio.src !== '') {
			if (currentAudio.src === newAudioSrc) {
				// If the audio that is currently played is the same as the clicked one, toggle it.
				if (currentAudio.paused) {
					audio.push(newAudioSrc);
					$(this).attr('src', '/assets/phaser/images/playing.png');
					playAudio(audio);
				} else {
					$(this).attr('src', '/assets/phaser/images/sound.png');
					currentAudio.pause();
				}
			} else {
				// If a different audio is currently played, stop it and play the new one.
				currentAudio.pause();
				currentAudio.currentTime = 0;
				const audioURL = new URL(currentAudio.src);
				const audioPath = audioURL.pathname;
				var associatedImage = document.querySelector(`img[data-audio="${audioPath}"]`);
				if (associatedImage) {
					// Update the image to show it has been paused.
					associatedImage.src = '/assets/phaser/images/sound.png';
				}
				$(this).attr('src', '/assets/phaser/images/playing.png');
				audio.push(newAudioSrc);
				playAudio(audio);
			}
			
		} else {
			// If there's no audio playing, just play the new audio.
			audio.push(newAudioSrc);
			playAudio(audio);
		}
	});
	
	$(document).on('click', '#mute-audio', function () {
		var muteBtn = $(this); // Get the jQuery object for the mute button
		var imageSrc = isMute ? '/assets/phaser/images/sound.png' : '/assets/phaser/images/mute.png';
		isMute = !isMute;
		document.cookie = 'isMute=' + isMute + '; path=/';
		if (isMute) {
			if (currentAudio) {
				continueAudioPlayback = false;
				currentAudio.pause();
				currentAudio.currentTime = 0;
			}
			$('.audio-image').attr('src', '/assets/phaser/images/sound.png');
			$('.audio-image').addClass('audio-image-disabled');
		} else {
			$('.audio-image').removeClass('audio-image-disabled');
		}
		muteBtn.attr('src', imageSrc);
	});
	
	$('.themes_img').on('click', function () {
		var current_theme = $(this).data('theme');
		var activity_id = $('#activity_id').val();
		var csrfToken = $('meta[name="csrf-token"]').attr('content');
		console.log('theme=>' + current_theme);
		console.log('activity_id=>' + activity_id);
		$.ajax({
			type: "POST",
			url: "/set-theme",
			data: {
				theme: current_theme,
				activity_id: activity_id
			},
			headers: {
				'X-CSRF-TOKEN': csrfToken
			},
			success: function (data) {
				// console.log(data);
				location.reload();
			}
		});
	});
});

function goToNextQuestion() {
	if (currentAudio) {
		continueAudioPlayback = false;
		currentAudio.pause();
		currentAudio.currentTime = 0;
	}
	//load the next question call LLM AJAX
	alert('Next Question');
}

let resizeIsGoingOn = false;
let resizeTimer = null;

window.addEventListener('resize', function () {
	clearTimeout(resizeTimer);
	resizeTimer = setTimeout(function () {
		if (resizeIsGoingOn) {
			return;
		}
		// console.log('start resize');
		resizeIsGoingOn = true;
		showStory(false);
		resizeIsGoingOn = false;
		// console.log('end resize');
	}, 50);
});

function showStory(autoPlayAudio) {
	// console.log(question);
	
	let ajaxPromises = [];
	
	$('#loading-page').show();
	
	$('#loading-page').hide();
	var questionDiv = document.getElementById('question-div');
	var chapterImageDiv = document.getElementById('question-image-div');
	var answersDiv = document.getElementById('answers-div');
	questionDiv.innerHTML = "";
	chapterImageDiv.innerHTML = "";
	answersDiv.innerHTML = "";
	var audioArr = [];
	resizeDivToWindowSize();
	
	if (autoPlayAudio && !isMute) {
		if (chapter_voice !== null && chapter_voice !== '') {
			audioArr.push(chapter_voice);
		}
		//for each answer, if there is audio, add it to the audio array
		chapter_choices.choices.forEach(function (answer) {
			if (answer['audio'] !== null && answer['audio'] !== '') {
				audioArr.push(answer['audio']);
			}
		})
		playAudio(audioArr, 2000);
	}
	
	if (!fadeIn) {
		setTimeout(function () {
			$('#main-div').addClass('fade-in');
			if (!runningTimer) {
				$('#timer').css('display', 'inline-block');
				runningTimer = setInterval(updateTimerDisplay, 1000); // Update every second
			}
		}, 2000);
		fadeIn = true;
	}
	
}


function __(key, replacements) {
	let translation = window.translations[key] || '';
	
	Object.keys(replacements).forEach(function (placeholder) {
		translation = translation.replace(':' + placeholder, replacements[placeholder]);
	});
	
	return translation;
}

function resizeDivToWindowSize() {
	var windowWidth, windowHeight;
	var promises = []; // this array will hold all the promises for the images
	
	if (isOnMobile) {
		if (window.orientation === 0 || window.orientation === 180) { // Portrait
			windowWidth = Math.min(window.screen.width, window.innerWidth);
			windowHeight = Math.min(window.screen.height, window.innerHeight);
		} else { // Landscape
			windowWidth = Math.max(window.screen.width, window.innerWidth);
			windowHeight = Math.max(window.screen.height, window.innerHeight);
		}
	} else {
		windowWidth = window.innerWidth;
		windowHeight = window.innerHeight;
	}
	
	var gameUiInPage = document.getElementById('game-ui-in-page');
	if (gameUiInPage !== null) {
		windowWidth = $("#game-ui-in-page").width();
		windowHeight = $("#game-ui-in-page").height();
	}
	
	var borderOffset = 8;
	
	var mainDiv = document.getElementById('main-div');
	mainDiv.style.width = (windowWidth - borderOffset - 10) + 'px';
	mainDiv.style.height = (windowHeight - borderOffset) + 'px';
	
	
	var footerDiv = document.getElementById('story-footer');
	footerDivHeight = 50;
	footerDiv.style.width = (windowWidth - borderOffset) + 'px';
	footerDiv.style.height = '50px';
	
	var questionDiv = document.getElementById('question-div');
	
	
	questionDiv.innerHTML = chapter_text.replace(/\\n/g, '<br>');
	questionDiv.innerHTML = chapter_text.replace(/\n/g, '<br>');
	questionDiv.style.fontSize = '40px';
	questionDiv.style.display = 'block'; // Show the div if there is text
	
	if (chapter_voice !== null && chapter_voice !== '') {
		var audioImage = document.createElement('img');
		audioImage.src = '/assets/phaser/images/sound.png';
		audioImage.className = 'audio-image';
		audioImage.dataset.audio = chapter_voice;
		
		var qAudioImgPromise = new Promise(function (resolve) {
			audioImage.onload = function () {
				resolve(true);
			};
		});
		promises.push(qAudioImgPromise);
		
		questionDiv.prepend(audioImage);
	}
	
	// Reset the height to 'auto' before setting the width
	questionDiv.style.maxHeight = parseInt(mainDiv.style.height) / 5 + 'px';
	questionDiv.style.width = mainDiv.style.width;
	// Now calculate and set the height based on the new scrollHeight
	// questionDiv.style.height = questionDivHeight + 'px';
	Promise.all(promises).then(function () {
		adjustFontSizeToFit([questionDiv], 40, 20);
	});
	
	var questionDivHeight = questionDiv.clientHeight;
	// console.log('questionDivHeight=>'+questionDivHeight);
	
	var bottomDivHeight;
	var bottomDivsWidth;
	var chapterImageDivHeight;
	var buttonWidthMultiplier = 1.2;
	var imageWidthMultiplier = 0.8;
	if (windowWidth < 600) {
		buttonWidthMultiplier = 1;
		imageWidthMultiplier = 1;
		chapterImageDivHeight = (parseInt(mainDiv.style.height) - questionDivHeight - footerDivHeight) / 3;
		bottomDivHeight = (parseInt(mainDiv.style.height) - questionDivHeight - footerDivHeight) / 3 * 2;
		bottomDivsWidth = parseInt(mainDiv.style.width);
	} else {
		chapterImageDivHeight = parseInt(mainDiv.style.height) - questionDivHeight - footerDivHeight;
		bottomDivHeight = parseInt(mainDiv.style.height) - questionDivHeight - footerDivHeight;
		bottomDivsWidth = (parseInt(mainDiv.style.width) / 2);
	}
	
	var chapterImageDivPadding = 10;
	var chapterImageDiv = document.getElementById('question-image-div');
	insertChapterImage();
	chapterImageDiv.style.width = ((bottomDivsWidth * imageWidthMultiplier) - chapterImageDivPadding) + 'px';
	chapterImageDiv.style.height = (chapterImageDivHeight - chapterImageDivPadding) + 'px';
	chapterImageDiv.style.top = (questionDivHeight + 10) + 'px';
	chapterImageDiv.style.left = '0px';
	
	// Adjust the image size to fit the div
	$('.question-img').css('width', '100%');
	// Displayed size
	var qImg = document.querySelector('.question-img');
	qImg.onload = function () {
		var displayedHeight = this.height;
		if (displayedHeight > chapterImageDivHeight) {
			$('.question-img').css('height', '100%');
			$('.question-img').css('width', 'auto');
		}
	}
	
	var answersDiv = document.getElementById('answers-div');
	answersDiv.style.width = (bottomDivsWidth * buttonWidthMultiplier) + 'px';
	answersDiv.style.height = bottomDivHeight + 'px';
	answersDiv.style.top = (windowWidth < 600) ? (questionDivHeight + 5 + chapterImageDivHeight) + 'px' : (questionDivHeight + 10) + 'px';
	answersDiv.style.left = (windowWidth < 600) ? '0px' : (bottomDivsWidth * imageWidthMultiplier) + 'px';
	
	
	// Empty the answersDiv and recreate buttons on each resize to ensure clean state
	answersDiv.innerHTML = '';
	var chapterTextFontSize = questionDiv.style.fontSize == '0px' || questionDiv.style.fontSize == '' ? '40' : questionDiv.style.fontSize.replace('px', '');
	var buttonsArray = [];
	
	// Create and append the buttons
	
	for (var i = 0; i < chapter_choices.choices.length; i++) {
		var button = document.createElement('div');
		button.style.width = ((bottomDivsWidth * buttonWidthMultiplier)  - 10) + 'px'; // Subtracting 10 for padding
		buttonWidth = ((bottomDivsWidth * buttonWidthMultiplier) - 10);
		button.style.height = (chapter_choices.choices.length == 2 ? bottomDivHeight : bottomDivHeight / chapter_choices.choices.length - 10) + 'px'; // Subtracting 10 for padding
		buttonHeight = (chapter_choices.choices.length == 2 ? bottomDivHeight : bottomDivHeight / chapter_choices.choices.length - 10);
		button.style.top = ( i * (bottomDivHeight / chapter_choices.choices.length) ) + 'px';
		button.style.left = 0;
		
		button.className = 'story-answer-btn';
		button.dataset.index = i;
		var bg_img = document.createElement('img');
		bg_img.className = 'answer-bg-img';
		bg_img.src = answer_button_letterbox_img;
		button.appendChild(bg_img);
		
		var answerText = document.createElement('div');
		answerText.className = 'answer-text';
		answerText.innerHTML = chapter_choices.choices[i]['text'];
		button.appendChild(answerText);
		
		if (chapter_choices.choices[i]['audio'] !== null && chapter_choices.choices[i]['audio'] !== '') {
			var ansAudioImage = document.createElement('img');
			ansAudioImage.src = '/assets/phaser/images/sound.png';
			ansAudioImage.className = 'audio-image';
			ansAudioImage.dataset.audio = chapter_choices.choices[i]['audio'];
			
			var audioImgPromise = new Promise(function (resolve) {
				ansAudioImage.onload = function () {
					resolve(true);
				};
			});
			promises.push(audioImgPromise);
			answerText.prepend(ansAudioImage);
		}
		
		
		answersDiv.appendChild(button);
		buttonsArray.push(button);
	}
	
	
	Promise.all(promises).then(function () {
		adjustFontSizeToFit(buttonsArray, Math.round(chapterTextFontSize * 1.25), Math.round(chapterTextFontSize * 0.25));
	});
	if (isMute) {
		//make all the audio images disabled
		$('.audio-image').addClass('audio-image-disabled');
	} else {
		//make all the audio images enabled
		$('.audio-image').removeClass('audio-image-disabled');
	}
}


function insertChapterImage() {
	var chapterImageDiv = document.getElementById('question-image-div');
	
	var qImg = document.createElement('img');
	qImg.src = chapter_image; // Replace with the actual path to your image
	qImg.className = 'question-img';
	chapterImageDiv.appendChild(qImg);
	chapterImageDiv.style.display = 'block'; // Show the div if there is an image
}

function adjustFontSizeToFit(elements, maxFontSize, minFontSize) {
	
	let smallestFontSize = Infinity; // Start with a large number to get the minimum
	var heightMargin = 10;
	// First pass: find the smallest font size that fits all buttons
	for (let element of elements) {
		// console.log(element.innerHTML);
		let maxHeight = element.offsetHeight;
		let maxWidth = element.offsetWidth;
		let fontSize = maxFontSize;
		let textOverflow = true;
		while (textOverflow && fontSize > minFontSize) {
			element.style.fontSize = `${fontSize}px`;
			let image_height = element.querySelector('.answer-img') !== null ? element.querySelector('.answer-img').height : 0;
			textOverflow = (element.scrollHeight) > maxHeight || element.scrollWidth > maxWidth;
			// console.log(textOverflow, fontSize, minFontSize, image_height, '--', element.scrollHeight, maxHeight, heightMargin, '--', element.scrollWidth, maxWidth);
			fontSize -= 1;
		}
		if (fontSize < smallestFontSize) {
			smallestFontSize = fontSize; // store the smallest font size
		}
	}
	
	if (smallestFontSize >= maxFontSize) {
		smallestFontSize = maxFontSize;
	}
	// console.log(smallestFontSize);
	
	// Second pass: apply the smallest font size to all elements
	for (let element of elements) {
		element.style.fontSize = `${smallestFontSize}px`;
	}
}

function playAudio(audioSrcArray, waitTime = 0) {
	if (!Array.isArray(audioSrcArray) || audioSrcArray.length === 0) {
		console.log("playAudio: No audio sources provided or audioSrc is not an array.");
		return;
	}
	continueAudioPlayback = true;
	// Start the sequence with the first audio source.
	playSequence(audioSrcArray, 0, waitTime);
}

function playSequence(audioSrcArray, index, waitTime = 0) {
	if (!continueAudioPlayback) {
		return;
	}
	// Check if we have reached the end of the array.
	if (index >= audioSrcArray.length) {
		return;
	}
	
	// If there is a current audio playing, stop it before proceeding.
	if (currentAudio) {
		currentAudio.pause();
		currentAudio.currentTime = 0; // Reset the audio if you want to play from the beginning next time.
	}
	
	// Create a new audio object with the next source and play it.
	currentAudio.src = audioSrcArray[index];
	clearTimeout(audioTimer1); // Clear the timeout if it hasn't executed yet
	
	
	// Set a timeout to resume playback
	audioTimer1 = setTimeout(() => {
		currentAudio.play().catch(error => {
			console.error("Error playing audio:", error);
		});
	}, waitTime); // Delay of 2 seconds
	
	
	currentAudio.onplay = function () {
		changeImageToPlayingState(audioSrcArray[index]);
	};
	
	// Set up event listeners.
	currentAudio.onended = function () {
		changeImageToDefaultState(audioSrcArray[index]);
		playSequence(audioSrcArray, index + 1, 1000);
	};
}

function changeImageToDefaultState(audioSrc) {
	var associatedImage = document.querySelector(`img[data-audio="${audioSrc}"]`);
	if (associatedImage) {
		// Reset the image to its default state.
		associatedImage.src = '/assets/phaser/images/sound.png';
	}
}

function changeImageToPlayingState(audioSrc) {
	var associatedImage = document.querySelector(`img[data-audio="${audioSrc}"]`);
	if (associatedImage) {
		// Change the image to show that it's currently playing.
		associatedImage.src = '/assets/phaser/images/playing.png';
	}
}

function setImageSize(image, width, height) {
	if (width > height) {
		image.style.height = '80%';
		image.style.width = 'auto';
	} else {
		image.style.width = '80%';
		image.style.height = 'auto';
	}
}

function openFullscreen(elem) {
	if (elem.requestFullscreen) {
		elem.requestFullscreen();
	} else if (elem.webkitRequestFullscreen) { /* Safari */
		elem.webkitRequestFullscreen();
	} else if (elem.msRequestFullscreen) { /* IE11 */
		elem.msRequestFullscreen();
	}
}

function closeFullscreen() {
	if (document.exitFullscreen) {
		document.exitFullscreen();
	} else if (document.webkitExitFullscreen) { /* Safari */
		document.webkitExitFullscreen();
	} else if (document.msExitFullscreen) { /* IE11 */
		document.msExitFullscreen();
	}
}

function getCookie(name) {
	var cookieArr = document.cookie.split(';');
	
	for (var i = 0; i < cookieArr.length; i++) {
		var cookiePair = cookieArr[i].split('=');
		
		/* Removing whitespace at the beginning of the cookie name
		and compare it with the given string */
		if (name == cookiePair[0].trim()) {
			// Decode the cookie value and return
			return decodeURIComponent(cookiePair[1]);
		}
	}
	// Return null if not found
	return null;
}

function updateTimerDisplay() {
	seconds++;
	if (seconds >= 60) {
		seconds = 0;
		minutes++;
		if (minutes >= 60) {
			minutes = 0;
		}
	}
	
	let minutesStr = minutes.toString().padStart(2, '0');
	let secondsStr = seconds.toString().padStart(2, '0');
	
	timerDisplay.textContent = `${minutesStr}:${secondsStr}`;
}

function endGameUi() {
	$('#endgame-page').css('display', 'flex');
	$('.time-spent').text(timerDisplay.textContent);
	$('#full-screen').hide();
	$('#mute-audio').hide();
	$('#main-div').hide();
	for (let i = 0; i < game.scene.scenes.length; ++i) {
		let scene = game.scene.scenes[i];
		
		if (scene && typeof scene.reset_scene === 'function') {
			scene.reset_scene();
		}
	}
}
