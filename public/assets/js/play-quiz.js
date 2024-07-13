var currentQuestion = null;
var title = '';
let isMute = false;
var userAnswered = [];
var currentIndex = 0;
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
let scoreDisplay = null;
let score = 0;
//look at the user agent to determine if the user is on a mobile device
if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
	isOnMobile = true;
}
$(document).ready(function () {
	timerDisplay = document.getElementById('timer');
	scoreDisplay = document.getElementById('score');
	currentAudio = document.getElementById('audio-player');
	var data = json_data;
	title = game_title;
	var questions = data['questions'];
	currentQuestion = questions[currentIndex];
	$('#loading-page').hide();
	
	$('#preload-page .quiz-title').text(title);
	$('#quiz-type-description').text(type_description);
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
	
	if (previewQid !== null) {
		previewQid = parseInt(previewQid - 1) < questions.length ? previewQid - 1 : 0;
		$('#preload-page').hide();
		currentQuestion = questions[previewQid];
		currentIndex = parseInt(previewQid);
		showQuestion(currentQuestion, currentIndex, true);
		updateButtonStates(currentIndex, questions);
		$('.page-controller').css('display', 'block');
		$('#start-quiz').css('display', 'none');
	}
	
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
			$('#quiz-type-description').hide();
			$('.quiz-title').hide();
		}
		
		document.addEventListener('fullscreenchange', function () {
			isFullscreen = !!document.fullscreenElement;
		});
	}
	
	$('.start-btn').click(function () {
		
		if (isOnMobile && displayInPage) {
			var activity_id = $('#activity_id').val();
			var gameUrl = '/load-game/' + activity_id;
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
			
			
			showQuestion(currentQuestion, currentIndex, true);
			updateButtonStates(currentIndex, questions);
			$('.page-controller').css('display', 'block');
			$('#start-quiz').css('display', 'none');
		}
	});
	
	$('#prev').on('click', function () {
		continueAudioPlayback = false;
		if (currentAudio) {
			currentAudio.pause();
			currentAudio.currentTime = 0;
		}
		if (currentIndex > 0) {
			currentIndex--;
			updateButtonStates(currentIndex, questions);
			currentQuestion = questions[currentIndex];
			if (userAnswered[currentIndex] !== undefined) {
				showQuestion(currentQuestion, currentIndex, false);
			} else {
				showQuestion(currentQuestion, currentIndex, true);
			}
			checkAnswered(userAnswered, currentIndex);
		}
	});
	
	$('#next').on('click', function () {
		if (currentAudio) {
			continueAudioPlayback = false;
			currentAudio.pause();
			currentAudio.currentTime = 0;
		}
		if (currentIndex < questions.length - 1) {
			currentIndex++;
			updateButtonStates(currentIndex, questions);
			currentQuestion = questions[currentIndex];
			if (userAnswered[currentIndex] !== undefined) {
				showQuestion(currentQuestion, currentIndex, false);
			} else {
				showQuestion(currentQuestion, currentIndex, true);
			}
			checkAnswered(userAnswered, currentIndex);
		}
	});
	
	$('#start-quiz').on('click', function () {
		if (!runningTimer) {
			$('#timer').css('display', 'inline-block');
			$('#score').css('display', 'inline-block');
			runningTimer = setInterval(updateTimerDisplay, 1000); // Update every second
		}
		$('.page-controller').css('display', 'block');
		$('#start-quiz').css('display', 'none');
		if (userAnswered[currentIndex] !== undefined) {
			showQuestion(currentQuestion, currentIndex, false);
		} else {
			showQuestion(currentQuestion, currentIndex, true);
		}
		updateButtonStates(currentIndex, questions);
		checkAnswered(userAnswered, currentIndex);
	});
	
	$(document).on('click', '.answer-btn', function () {
		var width = $(this).outerWidth();
		var height = $(this).outerHeight();
		if (currentAudio) {
			continueAudioPlayback = false;
			currentAudio.pause();
			currentAudio.currentTime = 0;
		}
		$('.answer-btn').removeClass('animate-zoom');
		$(this).addClass('animate-zoom');
		
		if (userAnswered[currentIndex] !== undefined) return;
		var isCorrect = $(this).data('iscorrect');
		userAnswered[currentIndex] = {
			isCorrect: isCorrect,
			clickOn: $(this).data('index'),
		};
		
		$('.answer-btn').prop('disabled', true);
		var answeredCount = userAnswered.filter(item => item !== null).length;
		var totalQuestions = questions.length;
		if (isCorrect) {
			// Create the correct image element
			const correctImage = document.createElement('img');
			correctImage.src = '/assets/phaser/images/correct.png'; // Set the source of your correct image
			correctImage.classList.add('correct-image', 'fade-in');
			setImageSize(correctImage, width, height);
			
			this.appendChild(correctImage);
			
			setTimeout(() => {
				scoreDisplay.textContent = ++score;
				correctImage.classList.add('visible');
				correctImage.classList.add('zoom-in-rotate');
			}, 10);
			
			setTimeout(function () {
				if (currentIndex < questions.length - 1) {
					goToNextQuestion(questions);
					checkAnswered(userAnswered, currentIndex);
				}
				
				if (questions.length === answeredCount) {
					clearInterval(runningTimer);
					endGameUi(totalQuestions);
				}
			}, 1500);
		} else {
			// Create the correct image element
			const wrongImage = document.createElement('img');
			wrongImage.src = '/assets/phaser/images/wrong.png'; // Set the source of your correct image
			wrongImage.classList.add('wrong-image', 'fade-in');
			setImageSize(wrongImage, width, height);
			
			
			this.appendChild(wrongImage);
			setTimeout(() => {
				wrongImage.classList.add('visible');
				wrongImage.classList.add('zoom-in-rotate');
			}, 10);
			
			setTimeout(function () {
				$('.answer-btn').each(function () {
					if ($(this).data('iscorrect')) {
						const correctImage = document.createElement('img');
						correctImage.src = '/assets/phaser/images/correct.png';
						correctImage.classList.add('correct-image', 'fade-in');
						setImageSize(correctImage, width, height)
						this.appendChild(correctImage);
						setTimeout(() => {
							correctImage.classList.add('visible');
							correctImage.classList.add('zoom-in-rotate');
						}, 20);
					}
				});
			}, 1000);
			
			// Wait for 2 seconds, then go to next question
			setTimeout(function () {
				if (currentIndex < questions.length - 1) {
					goToNextQuestion(questions);
					checkAnswered(userAnswered, currentIndex);
				}
				
				if (questions.length === answeredCount) {
					clearInterval(runningTimer);
					endGameUi(totalQuestions);
				}
			}, 2500);
		}
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

function goToNextQuestion(questions) {
	if (currentAudio) {
		continueAudioPlayback = false;
		currentAudio.pause();
		currentAudio.currentTime = 0;
	}
	if (currentIndex < questions.length - 1 && currentIndex !== questions.length - 1) {
		currentIndex++;
		updateButtonStates(currentIndex, questions);
		currentQuestion = questions[currentIndex];
		if (userAnswered[currentIndex] !== undefined) {
			showQuestion(currentQuestion, currentIndex, false);
		} else {
			showQuestion(currentQuestion, currentIndex, true);
		}
	}
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
		showQuestion(currentQuestion, currentIndex, false);
		
		resizeIsGoingOn = false;
		checkAnswered(userAnswered, currentIndex);
		// console.log('end resize');
	}, 50);
});

function showQuestion(question, currentIndex, autoPlayAudio) {
	// console.log(question);
	var questionText = question['text'];
	var questionImage = question['image'];
	var questionAudio = question['audio'];
	var answers = question['answers'];
	
	let ajaxPromises = [];
	
	$('#loading-page').show();
	
	
	if (question['voice_id'] !== null && question['audio'] === null) {
		ajaxPromises.push(
			$.ajax({
				url: '/convert-text-to-speech',
				type: 'POST',
				data: {
					voice_id: question['voice_id'],
					text: question['text'],
					question_id: question['id'],
					answer_id: null,
					activity_id: $('#activity_id').val(),
					update_field: 'question'
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			}).then(function (response) {
				question['audio'] = response.audio_path;
				questionAudio = response.audio_path;
			})
		);
	}
	
	answers.forEach((answer, index) => {
		if (question['voice_id'] !== null && answer['audio'] === null) {
			ajaxPromises.push(
				$.ajax({
					url: '/convert-text-to-speech',
					type: 'POST',
					data: {
						voice_id: question['voice_id'],
						text: answer['text'],
						question_id: question['id'],
						answer_id: answer['id'],
						activity_id: $('#activity_id').val(),
						update_field: 'answer',
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				}).then(function (response) {
					answer['audio'] = response.audio_path;
				})
			);
		}
	});
	
	Promise.all(ajaxPromises).then(() => {
		$('#loading-page').hide();
		var questionDiv = document.getElementById('question-div');
		var questionImageDiv = document.getElementById('question-image-div');
		var answersDiv = document.getElementById('answers-div');
		questionDiv.innerHTML = "";
		questionImageDiv.innerHTML = "";
		answersDiv.innerHTML = "";
		var audioArr = [];
		resizeDivToWindowSize(questionText, questionImage, questionAudio, answers, 'question');
		if (currentIndex === 0 && !fadeIn && previewQid === null) {
			if (questionAudio !== null && questionAudio !== '') {
				audioArr.push(questionAudio);
			}
			//for each answer, if there is audio, add it to the audio array
			answers.forEach(function (answer) {
				if (answer['audio'] !== null && answer['audio'] !== '') {
					audioArr.push(answer['audio']);
				}
			})
			if (autoPlayAudio && !isMute) {
				playAudio(audioArr, 2000);
			}
			setTimeout(function () {
				$('#main-div').addClass('fade-in');
				if (!runningTimer) {
					$('#timer').css('display', 'inline-block');
					$('#score').css('display', 'inline-block');
					runningTimer = setInterval(updateTimerDisplay, 1000); // Update every second
				}
			}, 2000);
			fadeIn = true;
		} else {
			if (autoPlayAudio && !isMute) {
				if (questionAudio !== null && questionAudio !== '') {
					audioArr.push(questionAudio);
				}
				//for each answer, if there is audio, add it to the audio array
				answers.forEach(function (answer) {
					if (answer['audio'] !== null && answer['audio'] !== '') {
						audioArr.push(answer['audio']);
					}
				})
				playAudio(audioArr, 1000);
			}
		}
	}).catch(error => {
		console.error('Error in AJAX calls:', error);
	});
}

function checkAnswered(userAnswered, currentIndex) {
	if (userAnswered[currentIndex] !== undefined) {
		// Disable the answer buttons
		$('.answer-btn').prop('disabled', true);
		var answerIndex = userAnswered[currentIndex].clickOn;
		// Show feedback for the previously selected answer
		$('.answer-btn').each(function () {
			var isCorrect = $(this).data('iscorrect');
			var imageSrc = isCorrect ? '/assets/phaser/images/correct.png' : '/assets/phaser/images/wrong.png';
			if (isCorrect) {
				const checkImage = document.createElement('img');
				checkImage.src = imageSrc;
				checkImage.classList.add('answered-image');
				this.appendChild(checkImage);
				setTimeout(() => {
					checkImage.classList.add('visible');
				}, 10);
			}
			if (!isCorrect && $(this).data('index') == answerIndex) {
				const checkImage = document.createElement('img');
				checkImage.src = imageSrc;
				checkImage.classList.add('answered-image');
				this.appendChild(checkImage);
				setTimeout(() => {
					checkImage.classList.add('visible');
				}, 10);
			}
		});
	} else {
		$('.answer-btn').prop('disabled', false);
	}
}

function __(key, replacements) {
	let translation = window.translations[key] || '';
	
	Object.keys(replacements).forEach(function (placeholder) {
		translation = translation.replace(':' + placeholder, replacements[placeholder]);
	});
	
	return translation;
}

function updateButtonStates(currentIndex, questions) {
	var pageCounterDiv = document.getElementById('page-counter');
	let pageInfo = __('default.num_of_num', {index: currentIndex + 1, total: questions.length});
	pageCounterDiv.textContent = pageInfo;
	// Disable or enable buttons depending on currentIndex
	$('#prev').prop('disabled', currentIndex === 0);
	$('#next').prop('disabled', currentIndex === questions.length - 1);
	
	// Optionally, add a class for styling disabled buttons
	$('#prev').toggleClass('disabled', currentIndex === 0);
	$('#next').toggleClass('disabled', currentIndex === questions.length - 1);
}

function resizeDivToWindowSize(questionText, questionImage, questionAudio, answers) {
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
	
	var headerDiv = document.getElementById('quiz-header');
	headerDivHeight = 50;
	headerDiv.style.width = (windowWidth - borderOffset) + 'px';
	headerDiv.style.height = '50px';
	
	var footerDiv = document.getElementById('quiz-footer');
	footerDivHeight = 50;
	footerDiv.style.width = (windowWidth - borderOffset) + 'px';
	footerDiv.style.height = '50px';
	
	var questionDiv = document.getElementById('question-div');
	
	if ((questionText !== '' && questionText !== null) || (questionAudio !== null && questionAudio !== '')) { // Let's say there's a 50% chance to have question text
		questionDiv.textContent = questionText;
		questionDiv.style.fontSize = '40px';
		questionDiv.style.display = 'block'; // Show the div if there is text
	} else {
		questionDiv.style.display = 'none'; // Hide the div if there is no text
	}
	
	if (questionAudio !== null && questionAudio !== '') {
		var audioImage = document.createElement('img');
		audioImage.src = '/assets/phaser/images/sound.png';
		audioImage.className = 'audio-image';
		audioImage.dataset.audio = questionAudio;
		
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
	questionDiv.style.top = '42px';
	Promise.all(promises).then(function () {
		adjustFontSizeToFit([questionDiv], 40, 20);
	});
	
	var questionDivHeight = questionDiv.clientHeight;
	// console.log('questionDivHeight=>'+questionDivHeight);
	
	var bottomDivHeight;
	var bottomDivsWidth;
	var questionImageDivHeight;
	var buttonWidthMultiplier = 1.2;
	var imageWidthMultiplier = 0.8;
	if (windowWidth < 600) {
		buttonWidthMultiplier = 1;
		imageWidthMultiplier = 1;
		questionImageDivHeight = (parseInt(mainDiv.style.height) - questionDivHeight - headerDivHeight - footerDivHeight) / 3;
		bottomDivHeight = (parseInt(mainDiv.style.height) - questionDivHeight - headerDivHeight - footerDivHeight) / 3 * 2;
		bottomDivsWidth = parseInt(mainDiv.style.width);
	} else {
		questionImageDivHeight = parseInt(mainDiv.style.height) - questionDivHeight - headerDivHeight - footerDivHeight;
		bottomDivHeight = parseInt(mainDiv.style.height) - questionDivHeight - headerDivHeight - footerDivHeight;
		bottomDivsWidth = (parseInt(mainDiv.style.width) / 2);
	}
	
	var questionImageDivPadding = 10;
	var questionImageDiv = document.getElementById('question-image-div');
	insertQuestionImage(questionImage);
	questionImageDiv.style.width = ((bottomDivsWidth - questionImageDivPadding)*imageWidthMultiplier) + 'px';
	questionImageDiv.style.height = (questionImageDivHeight - questionImageDivPadding) + 'px';
	questionImageDiv.style.top = questionDivHeight + headerDivHeight + 'px';
	questionImageDiv.style.left = '0px';
	
	// Adjust the image size to fit the div
	$('.question-img').css('width', '100%');
	// Displayed size
	if (questionImage !== null && questionImage !== '') {
		var qImg = document.querySelector('.question-img');
		qImg.onload = function () {
			var displayedHeight = this.height;
			if (displayedHeight > questionImageDivHeight) {
				$('.question-img').css('height', '100%');
				$('.question-img').css('width', 'auto');
			}
		}
	}
	
	var answersDiv = document.getElementById('answers-div');
	answersDiv.style.width = (bottomDivsWidth*buttonWidthMultiplier) + 'px';
	answersDiv.style.height = bottomDivHeight + 'px';
	answersDiv.style.top = (windowWidth < 600) ? (headerDivHeight + questionDivHeight + questionImageDivHeight) + 'px' : headerDivHeight + questionDivHeight + 'px';
	answersDiv.style.left = (windowWidth < 600) ? '0px' : (bottomDivsWidth*imageWidthMultiplier) + 'px';
	
	if (questionImageDiv.style.display == 'none') {
		buttonWidthMultiplier = 1;
		imageWidthMultiplier = 1;
		// If questionImageDiv is not visible, set answersDiv to 100% width
		answersDiv.style.width = mainDiv.style.width;
		answersDiv.style.top = headerDivHeight + questionDivHeight + 'px';
		answersDiv.style.left = '0px';
		bottomDivsWidth = parseInt(mainDiv.style.width);
		bottomDivHeight = parseInt(mainDiv.style.height) - questionDivHeight - headerDivHeight - footerDivHeight;
		// Adjust height accordingly if a new layout is desired for answersDiv
	}
	
	// Empty the answersDiv and recreate buttons on each resize to ensure clean state
	answersDiv.innerHTML = '';
	var questionTextFontSize = questionDiv.style.fontSize == '0px' || questionDiv.style.fontSize == '' ? '40' : questionDiv.style.fontSize.replace('px', '');
	var buttonsArray = [];
	
	if (userAnswered[currentIndex] === undefined) {
		shuffleArray(answers);
	}
	answers = answers.filter(answer => !(answer.text === null && answer.image === null));
	// Create and append the buttons
	var columns = answers.length > 4 ? 3 : 2;
	for (var i = 0; i < answers.length; i++) {
		var button = document.createElement('div');
		button.style.width = ((bottomDivsWidth*buttonWidthMultiplier) / columns - 10) + 'px'; // Subtracting 10 for padding
		buttonWidth = ((bottomDivsWidth*buttonWidthMultiplier) / columns - 10);
		button.style.height = (answers.length == 2 ? bottomDivHeight : bottomDivHeight / 2 - 10) + 'px'; // Subtracting 10 for padding
		buttonHeight = (answers.length == 2 ? bottomDivHeight : bottomDivHeight / 2 - 10);
		button.style.top = (i < columns ? 0 : bottomDivHeight / 2) + 'px';
		if (columns === 3) {
			if (i % 3 === 0) {
				button.style.left = 0;
			} else {
				button.style.left = (i % 3 === 1 ? (bottomDivsWidth*buttonWidthMultiplier) / 3 + 5 : ((bottomDivsWidth*buttonWidthMultiplier) / 3 + 5) * 2) + 'px';
			}
		} else {
			button.style.left = (i % 2 === 0 ? 5 : (bottomDivsWidth*buttonWidthMultiplier) / 2 + 5) + 'px';
		}
		
		button.className = 'answer-btn';
		button.dataset.index = i;
		var bg_img = document.createElement('img');
		bg_img.className = 'answer-bg-img';
		
		if (buttonWidth === buttonHeight) {
			bg_img.src = answer_button_square_img;
		} else if (buttonWidth < buttonHeight) {
			bg_img.src = answer_button_portrait_img;
		} else if (buttonWidth > (buttonHeight * 2)) {
			bg_img.src = answer_button_letterbox_img;
		} else {
			bg_img.src = answer_button_landscape_img;
		}
		button.appendChild(bg_img);
		
		if (answers[i]['audio'] !== null && answers[i]['audio'] !== '') {
			var ansAudioImage = document.createElement('img');
			ansAudioImage.src = '/assets/phaser/images/sound.png';
			ansAudioImage.className = 'audio-image';
			ansAudioImage.dataset.audio = answers[i]['audio'];
			
			var audioImgPromise = new Promise(function (resolve) {
				ansAudioImage.onload = function () {
					resolve(true);
				};
			});
			promises.push(audioImgPromise);
			button.appendChild(ansAudioImage);
		}
		
		if (answers[i]['image'] !== null) {
			// Create an img element
			var img = document.createElement('img');
			img.src = answers[i]['image']; // Replace with the actual path to your image
			img.className = 'answer-img';
			img.style.height = Math.round(buttonHeight / 2) + 'px';
			img.style.maxWidth = '100%';
			
			var imgPromise = new Promise(function (resolve) {
				img.onload = function () {
					resolve(true);
				};
			});
			promises.push(imgPromise);
			
			
			button.setAttribute('data-isCorrect', answers[i]['isCorrect']);
			button.appendChild(img);
		}
		// Create a span element for the text to keep it separate from the image
		if (answers[i]['text'] !== null) {
			var answerText = document.createElement('div');
			answerText.className = 'answer-text';
			answerText.textContent = answers[i]['text'];
			button.setAttribute('data-isCorrect', answers[i]['isCorrect']);
			button.appendChild(answerText);
		}
		
		answersDiv.appendChild(button);
		buttonsArray.push(button);
	}
	
	
	Promise.all(promises).then(function () {
		adjustFontSizeToFit(buttonsArray, Math.round(questionTextFontSize * 1.25), Math.round(questionTextFontSize * 0.25));
	});
	if (isMute) {
		//make all the audio images disabled
		$('.audio-image').addClass('audio-image-disabled');
	} else {
		//make all the audio images enabled
		$('.audio-image').removeClass('audio-image-disabled');
	}
}


function insertQuestionImage(questionImage) {
	var questionImageDiv = document.getElementById('question-image-div');
	// Decide whether the question image is available using a random condition or some logic
	if (questionImage !== null && questionImage !== '') {
		var qImg = document.createElement('img');
		qImg.src = questionImage; // Replace with the actual path to your image
		qImg.className = 'question-img';
		questionImageDiv.appendChild(qImg);
		questionImageDiv.style.display = 'block'; // Show the div if there is an image
	} else {
		questionImageDiv.style.display = 'none'; // Hide the div if there is no image
	}
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

function shuffleArray(array) {
	for (var i = array.length - 1; i > 0; i--) {
		// Generate a random index
		var j = Math.floor(Math.random() * (i + 1));
		
		// Swap elements at indices i and j
		var temp = array[i];
		array[i] = array[j];
		array[j] = temp;
	}
	return array;
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

function endGameUi(totalQuestions) {
	$('#endgame-page').css('display', 'flex');
	$('.time-spent').text(timerDisplay.textContent);
	$('.total-score').text(scoreDisplay.textContent + ' / ' + totalQuestions);
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
