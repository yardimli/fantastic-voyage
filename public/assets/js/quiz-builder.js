var imageEditor = null;
var currentImageInEditorSourceID = null;
var currentAudioInEditorSourceID = null;

var QuestionIdCounter = 0;
var xhr; // Variable to hold the XMLHttpRequest object
var quiz_type = 'quiz';


$(document).ready(function () {
	var isEdit = false;
	document.addEventListener('keyup', function (e) {
		isEdit = true;
	});

	$('#add-content-modal').modal({
		backdrop: 'static',
		keyboard: false
	});
	$('#audio-picker').modal({
		backdrop: 'static',
		keyboard: false
	});
	
	var csrfToken = $('meta[name="csrf-token"]').attr('content');
	const container = document.querySelector('.questions-list');
	let handle = smoothDragOrder(container, 0.2); //0.2 seconds animation duration

	// container.addEventListener('change', e => {
	// 	console.log(
	// 		Array.from(e.currentTarget.children)
	// 			.map(el => el.dataset.my)
	// 	);
	// });


	//-------------------------------------------
	window.onresize = function () {
		imageEditor.ui.resizeEditor();
	}

	imageEditor = new tui.ImageEditor('#tui-image-editor-container', {
		includeUI: {
			loadImage: {
				path: 'http://nhn.github.io/tui.image-editor/latest/examples/img/sampleImage2.png',
				name: 'background'
			},
			theme: blackTheme, // or whiteTheme
			menuBarPosition: 'bottom',
			uiSize: {
				width: '100%',
				height: '100%'
			},
		},
		cssMaxWidth: 1000,
		cssMaxHeight: 800,
		usageStatistics: false,
	});

	document.addEventListener('tui-save-image-changes', function (e) {
		// var currentQuestionId = currentImageInEditor;
		// var dataUrl = imageEditor.toDataURL();
		isEdit = true;
		console.log('isEdit save image');
		console.log(e.detail.dataURL);
		$('#image-src-' + currentImageInEditorSourceID).attr('src', e.detail.dataURL);
		$("#tui-image-editor-container").hide();
		$('body').removeClass('no-scroll-bars');

	});

	$(document).on('click', '.question-image, .answer-image', function () {
		$('.input-container').removeClass('active-border');
		currentImageInEditorSourceID = $(this).data('id');

		if ($('#image-src-' + currentImageInEditorSourceID).attr('src') !== '') {

			console.log(currentImageInEditorSourceID);

			imageEditor
				.loadImageFromURL($('#image-src-' + currentImageInEditorSourceID).attr('src'), "background")
				.then((result) => {
					console.log(result);
					imageEditor.ui.activeMenuEvent();
					imageEditor.clearUndoStack();
				});
			$('body').addClass('no-scroll-bars');
			$("#tui-image-editor-container").show();

		} else {
			var questionId = $(this).data('id');
			var textContent = $(this).parent().find('.item-input').text();
			var putContent = textContent.length < 15 ? textContent : '';
			$('#image-picker').data('current', questionId);
			$('#image-search-query').val(putContent);
			$('#image-picker').modal('show');
		}
	});

	$('#close-tui-editor').on('click', function () {
		$("#tui-image-editor-container").hide();
		$('body').removeClass('no-scroll-bars');
	});

	$("#clear-tui-editor").on('click', function () {
		isEdit = true;
		console.log('isEdit clear image');
		$('#image-src-' + currentImageInEditorSourceID).attr('src', '');
		$('#image-src-' + currentImageInEditorSourceID).addClass('d-none');
		$('#image-src-placeholder-' + currentImageInEditorSourceID).removeClass('d-none');
		$("#tui-image-editor-container").hide();
		$('body').removeClass('no-scroll-bars');

		var text = $('#answer-text-' + currentImageInEditorSourceID).text();
		if (text == '') {
			$('#answer-text-' + currentImageInEditorSourceID).parent().parent().find('.checkbox-container').find('i').addClass('checkbox-disabled');
			$('#answer-text-' + currentImageInEditorSourceID).parent().parent().find('.checkbox-container').find('i').removeClass('fa-check');
			$('#answer-text-' + currentImageInEditorSourceID).parent().parent().find('.checkbox-container').find('i').addClass('fa-times');
		}
	});


	$('#image-picker').on('shown.bs.modal', function () {
		$('.input-container').removeClass('active-border');

		// console.log("SHOW");
		// $('#image-search-query').val('');  // clear the search-input
		$('#image-search-results').html('<div class="col-12 text-center">Image Powered by Pexels</div>'); // clear the search-results
		$('#currentPage').text('1'); // reset the current page
		$("#image-search-query").focus(); // focus on the search-input
		if($("#image-search-query").val() !== ''){
			$('#quiz-image-search-button').click(); //trigger click event of the button
		}
	});

	$("#image-search-query").on('keypress', function (e) {
		if (e.which === 13) {  //13 is the ASCII value for Enter key
			$('#quiz-image-search-button').click(); //trigger click event of the button
		}
	});


	var imageSearchCurrentPage = 1;
	$('#quiz-image-search-button, #prevPage, #nextPage').on('click', function () {
		if (this.id === 'prevPage') {
			imageSearchCurrentPage -= 1;
		} else if (this.id === 'nextPage') {
			imageSearchCurrentPage += 1;
		} else {
			imageSearchCurrentPage = 1;
		}

		$('#currentPage').text(imageSearchCurrentPage.toString());


		$.ajax({
			type: "POST",
			url: "/quiz-image-search",
			'dataType': 'json',
			data: {
				query: $('#image-search-query').val(),
				page: imageSearchCurrentPage
			},
			headers: {
				'X-CSRF-TOKEN': csrfToken
			},
			success: function (data) {
				$("#image-search-results").empty();
				for (var i = 0; i < data.length; i++) {
					// console.log(data[i]);
					$("#image-search-results").append(`
                    <div class="col-2">
                        <img src="${data[i].src.medium}" alt="Image ${i + 1}" class="img-fluid selectable-image">
                    </div>
                `);
				}

				$('.selectable-image').on('click', function () {
					isEdit = true;
					console.log('isEdit selectable image');
					var currentQuestionId = $('#image-picker').data('current');
					// console.log(currentQuestionId);

					$('#image-src-' + currentQuestionId).attr('src', $(this).attr('src'));
					$('#image-src-' + currentQuestionId).removeClass('d-none');
					$('#image-src-placeholder-' + currentQuestionId).addClass('d-none');
					$('#image-picker').modal('hide');
					$('#image-src-' + currentQuestionId).parent().parent().find('.checkbox-container').find('i').removeClass('checkbox-disabled');
				});
			}
		});
	});

	//------------------------------------------------------
	$.ajax({
		url: '/fetch-voices',
		type: 'GET',
		dataType: 'json',
		success: function (data) {
			var voices = data.voices;
			var options = '';
			for (var i = 0; i < voices.length; i++) {
				var selected = '';
				if (voices[i].voice_id === 'EXAVITQu4vr4xnSDxMaL') {
					selected = 'selected';
				}
				options += `<option value="${voices[i].voice_id}" ${selected}>${voices[i].name} (${voices[i].labels.gender}, ${voices[i].labels.age}, ${voices[i].labels.accent}, ${voices[i].labels.description})</option>`;
			}
			$('#voice-input').html(options);
		},
		error: function (err) {
			console.error(err);
		}
	});

	$('#audio-picker').on('shown.bs.modal', function () {
		// console.log("SHOW");
		if ($("#audio-src-" + currentAudioInEditorSourceID).data('src') !== '') {
			$("#remove-audio-btn").removeClass('disabled');
			$("#update-audio-btn").removeClass('disabled');
			$('#play-audio-button').removeClass('d-none');
			$('#audio-tts-input').val($("#audio-src-" + currentAudioInEditorSourceID).data('text'));  // clear the search-input
			$('#audio-preview').attr('src', $("#audio-src-" + currentAudioInEditorSourceID).data('src'));
			var voiceId = $('#audio-src-' + currentAudioInEditorSourceID).data('voice');
			//set the dropdown #voice-input to the voiceId
			$('#voice-input').val(voiceId);
		} else {
			if ($("#audio-src-" + currentAudioInEditorSourceID).data('block_type') === 'question') {
				$('#audio-tts-input').val($("#question-text-" + currentAudioInEditorSourceID).text());  // clear the search-input
			} else if($("#audio-src-" + currentAudioInEditorSourceID).data('block_type') === 'answer'){
				$('#audio-tts-input').val($("#answer-text-" + currentAudioInEditorSourceID).text());  // clear the search-input
			}
			$("#audio-tts-input").focus(); // focus on the search-input
			$("#remove-audio-btn").addClass('disabled');
			$("#update-audio-btn").addClass('disabled');
			$('#play-audio-button').addClass('d-none');
			$('#audio-preview').attr('src', '');
		}

	});

	$(document).on('click', '.question-audio, .answer-audio', function () {
		// console.log('audio clicked');
		currentAudioInEditorSourceID = $(this).data('id');

		$('#audio-picker').data('current', currentAudioInEditorSourceID);
		$('#audio-picker').modal('show');
	});

	$('#generate-sound-button').on('click', function (e) {
		e.preventDefault();

		var voiceId = $('#voice-input').val();
		var text = $('#audio-tts-input').val();

		$('#spinner').show();

		$.ajax({
			url: '/convert-text-to-speech',
			type: 'POST',
			data: {
				voice_id: voiceId,
				text: text
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function (response) {
				$('#spinner').hide();
				// console.log('File path:', response.file_path);
				// console.log('File URL:', response.file_url);
				$("#remove-audio-btn").removeClass('disabled');
				$("#update-audio-btn").removeClass('disabled');
				$('#play-audio-button').removeClass('d-none');
				$('#audio-preview').attr('src', response.audio_path);
				$('#audio-preview')[0].play();
			},
			error: function (err) {
				$('#spinner').hide();
				console.error(err);
			}
		});
	});

	$('#audio-preview')[0].addEventListener('playing', function () {
		// Set the text and icon to 'Pause' when the audio starts playing
		$('#play-audio-button').html('<i class="fas fa-pause"></i> '+translations.pause);
	});

	$('#audio-preview')[0].addEventListener('pause', function () {
		// Set the text and icon back to 'Play' when the audio is paused
		$('#play-audio-button').html('<i class="fas fa-play"></i> '+translations.play);
	});

	$('#play-audio-button').on('click', function (e) {
		e.preventDefault();

		// The clicked button will either play or pause the audio, depending on its current state
		var audio = $('#audio-preview')[0];

		if (audio.paused) {
			audio.play();
		} else {
			audio.pause();
		}
	});

	$("#update-audio-btn").on('click', function () {
		isEdit = true;
		console.log('isEdit update audio');
		var currentQuestionId = $('#audio-picker').data('current');
		// console.log(currentQuestionId);

		$('#audio-src-' + currentQuestionId).data('src', $('#audio-preview').attr('src'));
		$('#audio-src-' + currentQuestionId).data('text', $('#audio-tts-input').val());
		$('#audio-src-' + currentQuestionId).data('voice', $('#voice-input').val());
		$('#audio-src-' + currentQuestionId).css('color', 'green');

		$('#audio-picker').modal('hide');

	});

	$("#remove-audio-btn").on('click', function () {
		isEdit = true;
		console.log('isEdit remove audio');
		var currentQuestionId = $('#audio-picker').data('current');
		// console.log(currentQuestionId);

		$('#audio-src-' + currentQuestionId).data('src', '');
		$('#audio-src-' + currentQuestionId).data('text', '');
		$('#audio-src-' + currentQuestionId).css('color', '#ccc');

		$('#audio-picker').modal('hide');

	});

	$('#audio-picker').on('hidden.bs.modal', function () {
		$('#audio-preview')[0].pause();
	});

//------------------------------------------------------

	$(document).on('click', '.checkbox-container', function () {
		isEdit = true;
		console.log('isEdit answer checkbox');
		var icon = $(this).find('i');
		if (icon.hasClass('checkbox-disabled')) {
			e.preventDefault();  // Prevent the click from doing anything
			return false;  // Stop further event propagation
		}

		icon.toggleClass('fa-check fa-times');

		if (icon.hasClass('fa-check')) {
			icon.css('color', 'green');
		} else {
			icon.css('color', 'red');
		}
	});

	$(document).on('click', '.item-input', function () {
		// console.log($(this).text());
		//set parent input_container to active
		$('.input-container').removeClass('active-border');
		$(this).parent().parent().addClass('active-border');
	});

	$(document).on('input', '.item-input', function () {
		// console.log($(this).text());
		if ($(this).text() !== '') {
			//enable checkbox
			$(this).parent().parent().find('.checkbox-container').find('i').removeClass('checkbox-disabled');
		} else {
			//check if has iamge
			var imageSrc = $(this).parent().parent().find('.answer-image img').attr('src');
			if (imageSrc == '') {
				//disable checkbox
				$(this).parent().parent().find('.checkbox-container').find('i').addClass('checkbox-disabled');
				$(this).parent().parent().find('.checkbox-container').find('i').removeClass('fa-check');
				$(this).parent().parent().find('.checkbox-container').find('i').addClass('fa-times');
			}
		}
	});

	function disableDeleteItemButton() {
		let listCount = $('.questions-list').find('.single-question').length;
		if (listCount <= 1) {
			$('.questions-list').find('.single-question .delete-item-button').first().css('pointer-events', 'none');
		} else {
			$('.questions-list').find('.single-question .delete-item-button').css('pointer-events', 'auto');
		}
	}

	disableDeleteItemButton();


	//-------------------------------------------------------------------------------

	function clone_question(questionToClone, insertAfter, randomId) {
		// Use the clone method to clone the question, and insert it after the current one

		var clonedQuestion = questionToClone.clone();
		var currentQuestionId = questionToClone.data('id');

		// find the biggest label number
		var biggest_num = 0;
		var QuestionNum = 1;
		$('.single-question').each(function () {
			var question_num = $(this).find('.question-number').data('question_num');
			if (question_num > biggest_num) {
				biggest_num = question_num;
				QuestionNum = biggest_num + 1;
			}
		});

		clonedQuestion.find('.question-number').html(QuestionNum + ".");
		clonedQuestion.find('.question-number').attr('data-question_num', QuestionNum);
		var randomId = "Q" + randomId;

		// loop over all descendants with an ID
		clonedQuestion.find('[id]').each(function () {
			// console.log(this.id);
			// change the ID
			var newId = this.id;
			newId = newId.replace(currentQuestionId, randomId);
			this.id = newId;
			// console.log(this.id);
		});

		clonedQuestion.attr('data-id', randomId);
		clonedQuestion.find('[data-id]').each(function () {

			// console.log("DATA: data-id:", $(this).attr('data-id'));

			var newId = $(this).attr('data-id');
			if (typeof newId !== 'undefined') {
				newId = newId.replace(currentQuestionId, randomId);
				$(this).attr('data-id', newId);
			}
		});

		// console.log('ADD CLONE QUESTION');
		clonedQuestion.insertAfter(insertAfter);
		disableDeleteItemButton();
	}

	//-------------------------------------------------------------------------------

	function showMessage(message) {
		$('.quiz_builder_message').removeClass('d-none');
		$('.quiz_builder_message').html(message);
		setTimeout(function () {
			$('.quiz_builder_message').addClass('d-none');
			$('.quiz_builder_message').html('');
		}, 1500);
	}

	//-------------------------------------------------------------------------------
	function checkContentIsBlank(deleteButton){
		var isQImgBlank = deleteButton.parents('.single-question').find('.question-image-src').attr('src') === '' ? true : false;
		var isImageBlank = deleteButton.parents('.single-question').find('.answer-image-src').filter(function() {
			return $(this).attr('src') !== '';
		}).length === 0;

		var isAudioBlank = deleteButton.parents('.single-question').find('.fa-volume-up').filter(function() {
			return $(this).css('color') !== 'rgb(204, 204, 204)';
		}).length === 0;

		var isTextBlank = deleteButton.parents('.single-question').find('.item-input').filter(function() {
			return $(this).text() !== '';
		}).length === 0;

		return isQImgBlank && isImageBlank && isAudioBlank && isTextBlank;
	}

	$(document).on('click', '.delete-item-button', function () {
		event.preventDefault();
		var isBlank = checkContentIsBlank($(this));
		var id = $(this).closest('.single-question').data('id');
		// console.log(isBlank);
		if(isBlank){
			$('.single-question[data-id="' + id + '"]').remove();
			disableDeleteItemButton();
		}else{
			//show confirmation modal
			$('#delete-item-modal').modal('show');
			//set the data-id of the delete button to the id of the item to be deleted
			$('#delete-item-modal').data('id', id);
		}
	});

	$("#delete-question-confirm").on('click', function () {
		isEdit = true;
		console.log('isEdit delete question');
		var id = $('#delete-item-modal').data('id');
		// console.log(id);
		//delete .single-question with data-id=id
		$('.single-question[data-id="' + id + '"]').remove();


		$('#delete-item-modal').modal('hide');
		disableDeleteItemButton();
	});


	$(document).on('click', '.js-editor-add-item', function () {
		event.preventDefault();

		QuestionIdCounter++;
		//validate that no other div has the same data-id
		let found_unique_id = false;
		while (!found_unique_id) {
			found_unique_id = true;
			$('.single-question').each(function () {
				if ($(this).data('id') === "Q" + QuestionIdCounter) {
					QuestionIdCounter++;
					found_unique_id = false;
				}
			});
		}


		// console.log('QuestionIdCounter: ' + QuestionIdCounter);
		//find the div with the data-id="QBlank"
		var cloneFrom = $('.empty-question-container').find('.single-question').first();
		// console.log(cloneFrom.html());

		//find the last question in the list
		var insertAfter = $('.questions-list').find('.single-question').last();
		// console.log(insertAfter.html());
		clone_question(cloneFrom, insertAfter, QuestionIdCounter);

		// $("#answer-text-Q"+QuestionIdCounter+"A2").html("test");
		// //simulate input into the answer
		// $("#answer-text-Q"+QuestionIdCounter+"A2").trigger('input');
		// $("#answer-text-Q"+QuestionIdCounter+"A2").parent().parent().find('.checkbox-container').trigger('click');

	});


	// Listen for a click event on any elements with a class of 'clone-item-button'
	$(document).on('click', '.clone-item-button', function (event) {
		event.preventDefault();

		QuestionIdCounter++;
		//validate that no other div has the same data-id
		let found_unique_id = false;
		while (!found_unique_id) {
			found_unique_id = true;
			$('.single-question').each(function () {
				if ($(this).data('id') === "Q" + QuestionIdCounter) {
					QuestionIdCounter++;
					found_unique_id = false;
				}
			});
		}

		console.log('QuestionIdCounter: ' + QuestionIdCounter);
		isEdit = true;
		console.log('isEdit clone question item');

		clone_question($(this).closest('.single-question'), $(this).closest('.single-question'), QuestionIdCounter);
	});

//-------------------------------------------------------------------------------

	$(".editor-done").on('click', function () {
		var title = $('#activity-title-input').val();
		var language = "English";
		var activity_id = $('#activity_id').val();
		var csrfToken = $('meta[name="csrf-token"]').attr('content');
		
		var questions = [];
		$('.questions-list').find('.question-container').each(function () {
			var questionId = $(this).find('.question-image').data('id');
			var qImg = $(this).find('.question-image img').attr('src') ?? "";
			var qAudio = $(this).find('.question-audio i').data('src') ?? "";
			var qAudioText = $(this).find('.question-audio i').data('text') ?? "";
			var qAudioVoice = $(this).find('.question-audio i').data('voice') ?? "";
			var qText = $(this).find('#question-text-' + questionId).text() ?? "";

			var answers = [];
			$(this).find('.answers-container .input-container').each(function () {
				var ansLetter = $(this).find('.answer-letter').html();
				var ansId = $(this).find('.answer-image').data('id');
				var ansAudio = $(this).find('.answer-audio i').data('src') ?? "";
				var ansAudioText = $(this).find('.answer-audio i').data('text') ?? "";
				var ansAudioVoice = $(this).find('.answer-audio i').data('voice') ?? "";
				var ansText = $(this).find('#answer-text-' + ansId).text() ?? "";
				var ansImg = $(this).find('.answer-image img').attr('src') ?? "";
				var ansCorrect = $(this).find('.checkbox-container i').hasClass('fa-check');

				var answerJson = {
					"id": ansId,
					"letter": ansLetter.replace(/\s/g, ''),
					"audio": ansAudio,
					"audio_tts": ansAudioText,
					"voice_id": ansAudioVoice,
					"image": ansImg,
					"text": ansText,
					"isCorrect": ansCorrect
				};

				answers.push(answerJson);
			});

			var questionJson = {
				"id": questionId,
				"audio": qAudio,
				"audio_tts": qAudioText,
				"voice_id": qAudioVoice,
				"image": qImg,
				"text": qText,
				"answers": answers
			};
			questions.push(questionJson);
		});

		$.ajax({
			type: "POST",
			url: "/quiz-build-json",
			data: {
				title: title,
				language: language,
				activity_id: activity_id,
				questions: questions,
			},
			headers: {
				'X-CSRF-TOKEN': csrfToken
			},
			success: function (data) {
				showMessage(data);
				isEdit = false;
			}
		});

	});

	//-------------------------------------------------------------------------------
	$(".preview-quiz, .preview-breadcrumb").on('click', function () {
		var activity_id = $('#activity_id').val();
		var preview = $(this).data('preview');
		$('#save-preview').data('preview', preview);
		if(isEdit){
			$('#preview-confirm').modal('show');
		}else{
			isEdit = false;
			var url = preview == 'in-page' ? '/load-quiz-in-page/' + activity_id : '/load-game/' + activity_id;
			window.open(url, '_blank');
		}

	});
	
	$("#save-preview").on('click', function () {
		var preview = $(this).data('preview');
		var title = $('#activity-title-input').val();
		var language = "English";
		var activity_id = $('#activity_id').val();
		var csrfToken = $('meta[name="csrf-token"]').attr('content');
		var questions = [];
		$('.questions-list').find('.question-container').each(function () {
			var questionId = $(this).find('.question-image').data('id');
			var qImg = $(this).find('.question-image img').attr('src') ?? "";
			var qAudio = $(this).find('.question-audio i').data('src') ?? "";
			var qAudioText = $(this).find('.question-audio i').data('text') ?? "";
			var qAudioVoice = $(this).find('.question-audio i').data('voice') ?? "";
			var qText = $(this).find('#question-text-' + questionId).text() ?? "";

			var answers = [];
			$(this).find('.answers-container .input-container').each(function () {
				var ansLetter = $(this).find('.answer-letter').html();
				var ansId = $(this).find('.answer-image').data('id');
				var ansAudio = $(this).find('.answer-audio i').data('src') ?? "";
				var ansAudioText = $(this).find('.answer-audio i').data('text') ?? "";
				var ansAudioVoice = $(this).find('.answer-audio i').data('voice') ?? "";
				var ansText = $(this).find('#answer-text-' + ansId).text() ?? "";
				var ansImg = $(this).find('.answer-image img').attr('src') ?? "";
				var ansCorrect = $(this).find('.checkbox-container i').hasClass('fa-check');

				var answerJson = {
					"id": ansId,
					"letter": ansLetter.replace(/\s/g, ''),
					"audio": ansAudio,
					"audio_tts": ansAudioText,
					"voice_id": ansAudioVoice,
					"image": ansImg,
					"text": ansText,
					"isCorrect": ansCorrect
				};

				answers.push(answerJson);
			});

			var questionJson = {
				"id": questionId,
				"audio": qAudio,
				"audio_tts": qAudioText,
				"voice_id": qAudioVoice,
				"image": qImg,
				"text": qText,
				"answers": answers
			};
			questions.push(questionJson);
		});

		$.ajax({
			type: "POST",
			url: "/quiz-build-json",
			data: {
				title: title,
				language: language,
				activity_id: activity_id,
				questions: questions,
			},
			headers: {
				'X-CSRF-TOKEN': csrfToken
			},
			success: function (data) {
				isEdit = false;
				$('#preview-confirm').modal('hide');
				var url = preview == 'in-page' ? '/load-quiz-in-page/' + activity_id : '/load-game/' + activity_id;
				window.open(url, '_blank');
			}
		});

	});
//-------------------------------------------------------------------------------

	$(document).on('click', '.upload_audio_btn', function () {
		$('#upload_audio_file').click();
		$('#upload_audio_file').val('');
	});

	$('#upload_audio_file').change(function () {
		var csrfToken = $('meta[name="csrf-token"]').attr('content');
		var formData = new FormData();
		formData.append('file', $('#upload_audio_file')[0].files[0]);

		$.ajax({
			url: "/quiz-upload/audio",
			type: 'POST',
			data: formData,
			contentType: false, // NEEDED, DON'T OMIT THIS (for formData)
			processData: false, // NEEDED, DON'T OMIT THIS
			headers: {
				'X-CSRF-TOKEN': csrfToken
			},
			success: function (response) {
				var message = response['message'];
				showMessage(message);
				// console.log('File path:', response['path']);
				// console.log('File URL:', response['file_url']);
				$('#play-audio-button').removeClass('d-none');
				$("#update-audio-btn").removeClass('disabled');
				$('#audio-preview').attr('src', response['path']);
				$('#audio-preview')[0].play();
			},
			error: function (error) {
				alert('File upload error');
			}
		});

	});

//-------------------------------------------------------------------------------

	$(document).on('click', '.upload_image_btn', function () {
		$('#upload_image_file').click();
	});

	$('#upload_image_file').change(function () {
		var csrfToken = $('meta[name="csrf-token"]').attr('content');
		var formData = new FormData();
		var currentQuestionId = $('#image-picker').data('current');
		formData.append('file', $('#upload_image_file')[0].files[0]);

		$.ajax({
			url: "/quiz-upload/image",
			type: 'POST',
			data: formData,
			contentType: false, // NEEDED, DON'T OMIT THIS (for formData)
			processData: false, // NEEDED, DON'T OMIT THIS
			headers: {
				'X-CSRF-TOKEN': csrfToken
			},
			success: function (response) {
				isEdit = true;
				var message = response['message'];
				showMessage(message);
				$('#image-src-' + currentQuestionId).attr('src', response['path']);
				$('#image-src-' + currentQuestionId).removeClass('d-none');
				$('#image-src-placeholder-' + currentQuestionId).addClass('d-none');
				$('#image-picker').modal('hide');

				$('#image-src-' + currentQuestionId).parent().parent().find('.checkbox-container').find('i').removeClass('checkbox-disabled');
			},
			error: function (error) {
				alert('File upload error');
			}
		});
	})

//-------------------------------------------------------------------------------

	$(document).on('click', '.create-content', function () {
		console.log('create content with AI.');
		var quiz_type = $(this).data('type');
		$('#generate-content').data('type', quiz_type);

		$('.user-content').val(quiz_prompt);
		$('#generate_example1').html(translations.forExample+' : Birds');

		$('#add-content-modal').modal('show');
	});

	$('#add-content-modal').on('shown.bs.modal', function () {
		$('.user-content').focus();
	});

	$('#generate-content').on('click', function () {
		isEdit = true;
		console.log('isEdit generate content');
		console.log('Generate content with AI.');
		var quiz_type = $(this).data('type');
		var activity_id = $('#activity_id').val();
		var user_content = $('.user-content').val();
		var language = $('#generate_language').val();
		var quantity = $('#generate_quantity').val();
		// find the biggest question number
		var biggest_num = 1;
		var biggest_id = 1;
		// var skipQuestion = [];
		$('.single-question').each(function () {
			var qNum = $(this).find('.question-number').data('question_num');
			var qId = $(this).data('id').replace(/[a-z]/gi, "");
			if (qNum > biggest_num) {
				biggest_num = qNum;
			}
			if (qId > biggest_id) {
				biggest_id = qId;
			}

			// var questionText = $(this).find('.question-text').text();
			// if(questionText !== ''){
			// 	skipQuestion.push(questionText);
			// }
		});

		var next_num = biggest_num + 1;
		var next_id = Number(biggest_id) + 1;
		var jobId = Date.now(); // create unique job ID based on timestamp

		xhr = $.ajax({
			type: "POST",
			url: "/quiz-content-builder-json",
			data: {
				quiz_type: quiz_type,
				activity_id: activity_id,
				user_content: user_content,
				language: language,
				quantity: quantity,
				next_num: next_num,
				next_id: next_id,
				jobId: jobId
			},
			headers: {
				'X-CSRF-TOKEN': csrfToken
			},
			beforeSend: function () {
				$('#spinIcon').addClass('fa-spin');
				$('#spinIcon').css('display', 'inline-block');
			},
			success: function (data) {
				if (data == '') {
					showMessage('Something went wrong with the AI. Please try again.');
				} else {
					$('.questions-list').append(data);
				}

				setTimeout(function () {
					$('#add-content-modal').modal('hide');
					disableDeleteItemButton();
				}, 1000);

			},
			complete: function () {
				$('#spinIcon').removeClass('fa-spin');
				$('#spinIcon').css('display', 'none');
			}
		});

	});

	$('#cancel-create-content').click(function () {
		if (xhr) {
			xhr.abort(); // Cancel the AJAX request
		}
	});
//-------------------------------------------------------------------------------

});
