var imageEditor = null;
var currentImageInEditorSourceID = null;
var currentAudioInEditorSourceID = null;

var MatchIdCounter = 0;
var quizType = 'match';
var xhr; // Variable to hold the XMLHttpRequest object

function attachListeners() {

}

$(document).ready(function () {
	$('#add-content-modal').modal({
		backdrop: 'static',
		keyboard: false
	});
	const container = document.querySelector('.match-list');
	let handle = smoothDragOrder(container, 0.2); //0.2 seconds animation duration
	var csrfToken = $('meta[name="csrf-token"]').attr('content');
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
		// var currentMatchId = currentImageInEditor;
		// var dataUrl = imageEditor.toDataURL();
		// console.log(e.detail.dataURL);
		$('#image-src-' + currentImageInEditorSourceID).attr('src', e.detail.dataURL);
		$("#tui-image-editor-container").hide();
		$('body').removeClass('no-scroll-bars');

	});

	$(document).on('click', '.pair-image', function () {
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
			var textContent = $(this).parent().find('.item-input').text();
			var putContent = textContent.length < 15 ? textContent : '';
			var currentId = $(this).data('id');
			$('#image-search-query').val(putContent);
			$('#image-picker').data('current', currentId);
			$('#image-picker').modal('show');
		}
	});

	$('#close-tui-editor').on('click', function () {
		$("#tui-image-editor-container").hide();
		$('body').removeClass('no-scroll-bars');
	});

	$("#clear-tui-editor").on('click', function () {
		$('#image-src-' + currentImageInEditorSourceID).attr('src', '');
		$('#image-src-' + currentImageInEditorSourceID).addClass('d-none');
		$('#image-src-placeholder-' + currentImageInEditorSourceID).removeClass('d-none');
		$("#tui-image-editor-container").hide();
		$('body').removeClass('no-scroll-bars');

		var text = $('#pair-text-' + currentImageInEditorSourceID).text();
		if( text == ''){
			$('#pair-text-' + currentImageInEditorSourceID).parent().parent().find('.checkbox-container').find('i').addClass('checkbox-disabled');
			$('#pair-text-' + currentImageInEditorSourceID).parent().parent().find('.checkbox-container').find('i').removeClass('fa-check');
			$('#pair-text-' + currentImageInEditorSourceID).parent().parent().find('.checkbox-container').find('i').addClass('fa-times');
		}
	});


	$('#image-picker').on('shown.bs.modal', function () {
		$('.input-container').removeClass('active-border');
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
		var csrfToken = $('meta[name="csrf-token"]').attr('content');

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
					var currentId = $('#image-picker').data('current');
					console.log(currentId);

					$('#image-src-' + currentId).attr('src', $(this).attr('src'));
					$('#image-src-' + currentId).removeClass('d-none');
					$('#image-src-placeholder-' + currentId).addClass('d-none');
					$('#image-picker').modal('hide');
					$('#image-src-' + currentId).parent().parent().find('.checkbox-container').find('i').removeClass('checkbox-disabled');
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
		console.log("SHOW");
		if ($("#audio-src-" + currentAudioInEditorSourceID).data('block_type') === 'question') {
			console.log($("#question-text-" + currentAudioInEditorSourceID));
		} else {
			console.log($("#pair-text-" + currentAudioInEditorSourceID));
		}

		if ($("#audio-src-" + currentAudioInEditorSourceID).data('src') !== '') {
			$('#play-audio-button').removeClass('d-none');
			$('#audio-tts-input').val($("#audio-src-" + currentAudioInEditorSourceID).data('text'));  // clear the search-input
			$('#audio-preview').attr('src', $("#audio-src-" + currentAudioInEditorSourceID).data('src'));
			var voiceId = $('#audio-src-' + currentAudioInEditorSourceID).data('voice');
			//set the dropdown #voice-input to the voiceId
			$('#voice-input').val(voiceId);
		} else {
			if ($("#audio-src-" + currentAudioInEditorSourceID).data('block_type') === 'question') {
				$('#audio-tts-input').val($("#question-text-" + currentAudioInEditorSourceID).html());  // clear the search-input
			} else {
				$('#audio-tts-input').val($("#pair-text-" + currentAudioInEditorSourceID).html());  // clear the search-input
			}
			$("#audio-tts-input").focus(); // focus on the search-input

			$('#play-audio-button').addClass('d-none');
			$('#audio-preview').attr('src', '');
		}

	});

	$(document).on('click', '.pair-audio', function () {
		console.log('audio clicked');

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
				console.log('File path:', response.file_path);
				console.log('File URL:', response.file_url);
				$('#play-audio-button').removeClass('d-none');
				$('#audio-preview').attr('src', response.file_url);
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
		$('#play-audio-button').html('<i class="fas fa-pause"></i> '+ translations.pause);
	});

	$('#audio-preview')[0].addEventListener('pause', function () {
		// Set the text and icon back to 'Play' when the audio is paused
		$('#play-audio-button').html('<i class="fas fa-play"></i> '+ translations.play);
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

		var currentId = $('#audio-picker').data('current');
		console.log(currentId);

		$('#audio-src-' + currentId).data('src', $('#audio-preview').attr('src'));
		$('#audio-src-' + currentId).data('text', $('#audio-tts-input').val());
		$('#audio-src-' + currentId).data('voice', $('#voice-input').val());
		$('#audio-src-' + currentId).css('color', 'green');

		$('#audio-picker').modal('hide');

	});

	$("#remove-audio-btn").on('click', function () {

		var currentId = $('#audio-picker').data('current');
		console.log(currentId);

		$('#audio-src-' + currentId).data('src', '');
		$('#audio-src-' + currentId).data('text', '');
		$('#audio-src-' + currentId).css('color', '#ccc');

		$('#audio-picker').modal('hide');

	});

//------------------------------------------------------

	$(document).on('click', '.checkbox-container', function () {

		var icon = $(this).find('i');
		if(icon.hasClass('checkbox-disabled')) {
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
		console.log($(this).text());
		//set parent input_container to active
		$('.input-container').removeClass('active-border');
		$(this).parent().parent().addClass('active-border');
	});

	$(document).on('input', '.item-input', function () {
		console.log($(this).text());
		if ($(this).text() !== '') {
			//enable checkbox
			$(this).parent().parent().find('.checkbox-container').find('i').removeClass('checkbox-disabled');
		} else {
			//check if has iamge
			var imageSrc = $(this).parent().parent().find('.pair-image img').attr('src');
			if(imageSrc == ''){
				//disable checkbox
				$(this).parent().parent().find('.checkbox-container').find('i').addClass('checkbox-disabled');
				$(this).parent().parent().find('.checkbox-container').find('i').removeClass('fa-check');
				$(this).parent().parent().find('.checkbox-container').find('i').addClass('fa-times');
			}
		}
	});

	function disableDeleteItemButton() {
		let listCount = $('.match-list').find('.single-match').length;
		var minItems = quizType === 'match' ? 3 : 2;
		if (listCount <= minItems) {
			$('.match-list').find('.single-match .delete-item-button').css('pointer-events', 'none');
		} else {
			$('.match-list').find('.single-match .delete-item-button').css('pointer-events', 'auto');
		}
	}
	disableDeleteItemButton();


	//-------------------------------------------------------------------------------

	function clone_match(matchToClone, insertAfter, randomId) {
		// Use the clone method to clone the match question, and insert it after the current one

		var clonedMatch = matchToClone.clone();
		var currentMatchId = matchToClone.data('id');

		// find the biggest label number
		var biggest_num = 0;
		var MatchingNum = 1;
		$('.single-match').each(function () {
			var match_num = $(this).find('.match-number').data('match_num');
			if (match_num > biggest_num) {
				biggest_num = match_num;
				MatchingNum = biggest_num + 1;
			}
		});

		clonedMatch.find('.match-number').html(MatchingNum + ".");
		clonedMatch.find('.match-number').attr('data-match_num', MatchingNum);
		var randomId = "M" + randomId;

		// loop over all descendants with an ID
		clonedMatch.find('[id]').each(function () {
			// console.log(this.id);
			// change the ID
			var newId = this.id;
			newId = newId.replace(currentMatchId, randomId);
			this.id = newId;
			// console.log(this.id);
		});

		clonedMatch.attr('data-id', randomId);
		clonedMatch.find('[data-id]').each(function () {

			// console.log("DATA: data-id:", $(this).attr('data-id'));

			var newId = $(this).attr('data-id');
			if (typeof newId !== 'undefined') {
				newId = newId.replace(currentMatchId, randomId);
				$(this).attr('data-id', newId);
			}
		});

		console.log('ADD CLONE MATCH');
		clonedMatch.insertAfter(insertAfter);
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
		var isImageBlank = deleteButton.parents('.single-match').find('.pair-image-src').filter(function() {
			return $(this).attr('src') !== '';
		}).length === 0;

		var isAudioBlank = deleteButton.parents('.single-match').find('.fa-volume-up').filter(function() {
			return $(this).css('color') !== 'rgb(204, 204, 204)';
		}).length === 0;

		var isTextBlank = deleteButton.parents('.single-match').find('.item-input').filter(function() {
			return $(this).text() !== '';
		}).length === 0;

		return isImageBlank && isAudioBlank && isTextBlank;
	}

	$(document).on('click', '.delete-item-button', function () {
		event.preventDefault();
		var isBlank = checkContentIsBlank($(this));
		var id = $(this).closest('.single-match').data('id')
		if(isBlank){
			$('.single-match[data-id="' + id + '"]').remove();
			disableDeleteItemButton();
		}else{
			//show confirmation modal
			$('#delete-item-modal').find('.modal-body').html(translations.deleteMatch);
			$('#delete-item-modal').modal('show');
			//set the data-id of the delete button to the id of the item to be deleted
			$('#delete-item-modal').data('id', id);
		}

	});

	$("#delete-question-confirm").on('click', function () {
		var id = $('#delete-item-modal').data('id');
		console.log(id);
		//delete .single-match with data-id=id
		$('.single-match[data-id="' + id + '"]').remove();


		$('#delete-item-modal').modal('hide');
		disableDeleteItemButton();
	});


	$(document).on('click', '.js-editor-add-item', function () {
		event.preventDefault();

		MatchIdCounter++;
		//validate that no other div has the same data-id
		let found_unique_id = false;
		while (!found_unique_id) {
			found_unique_id = true;
			$('.single-match').each(function () {
				if ($(this).data('id') === "M" + MatchIdCounter) {
					MatchIdCounter++;
					found_unique_id = false;
				}
			});
		}

		//find the div with the data-id="QBlank"
		var cloneFrom = $('.empty-match-container').find('.single-match').first();
		// console.log(cloneFrom.html());

		//find the last question in the list
		var insertAfter = $('.match-list').find('.single-match').last();
		// console.log(insertAfter.html());
		clone_match(cloneFrom, insertAfter, MatchIdCounter);

	});


	// Listen for a click event on any elements with a class of 'clone-item-button'
	$(document).on('click', '.clone-item-button', function (event) {
		event.preventDefault();

		MatchIdCounter++;
		//validate that no other div has the same data-id
		let found_unique_id = false;
		while (!found_unique_id) {
			found_unique_id = true;
			$('.single-match').each(function () {
				if ($(this).data('id') === "M" + MatchIdCounter) {
					MatchIdCounter++;
					found_unique_id = false;
				}
			});
		}

		clone_match($(this).closest('.single-match'), $(this).closest('.single-match'), MatchIdCounter);
	});


	$(".editor-done").on('click', function () {
		var title = $('#activity-title-input').val();
		var category = "Science";
		var grade = "3";
		var language = "English";
		var activity_id = $('#activity_id').val();
		var csrfToken = $('meta[name="csrf-token"]').attr('content');

		var data_json = [];
		$('.match-list').find('.single-match').each(function () {
			var matchId = $(this).data('id');
			var pair = {};
			$(this).find('.single-pair .input-container').each(function () {
					var side = $(this).data('side');
					var pairSideId = $(this).find('.pair-image').data('id');
					var pairAudio = $(this).find('.pair-audio i').data('src') ?? "";
					var pairAudioText = $(this).find('.pair-audio i').data('text') ?? "";
					var pairAudioVoice = $(this).find('.pair-audio i').data('voice') ?? "";
					var pairText = $(this).find('#pair-text-' + pairSideId).text() ?? "";
					var pairImg = $(this).find('.pair-image img').attr('src') ?? "";

					var sideJson = {
						"id": pairSideId,
						"audio": pairAudio,
						"audio_tts": pairAudioText,
						"audio_voice": pairAudioVoice,
						"image": pairImg,
						"side": side,
						"text": pairText
					}


					pair[side] = sideJson;
			});

			var matchJson = {
				"id": matchId,
				"pair": pair
			};
			data_json.push(matchJson);
		});

// console.log(data_json);
		$.ajax({
			type: "POST",
			url: "/quiz-match-build-json",
			data: {
				title: title,
				category: category,
				grade: grade,
				language: language,
				activity_id: activity_id,
				data_json: data_json
			},
			headers: {
				'X-CSRF-TOKEN': csrfToken
			},
			success: function (data) {
				showMessage(data);
			}
		});

	});

	$(document).on('click', '.upload_audio_btn', function () {
		$('#upload_audio_file').click();
		$('#upload_audio_file').val('');
	});

	$('#upload_audio_file').change(function () {
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
			success: function(response) {
				var message = response['message'];
				showMessage(message);

				console.log('File path:', response['path']);
				console.log('File URL:', response['file_url']);
				$('#play-audio-button').removeClass('d-none');
				$('#audio-preview').attr('src', response['file_url']);
				$('#audio-preview')[0].play();
			},
			error: function(error) {
				alert('File upload error');
			}
		});

	});

	$(document).on('click', '.upload_image_btn', function () {
		$('#upload_image_file').click();
	});

	$('#upload_image_file').change(function () {
		var csrfToken = $('meta[name="csrf-token"]').attr('content');
		var formData = new FormData();
		var currentMatchId = $('#image-picker').data('current');
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
			success: function(response) {
				var message = response['message'];
				showMessage(message);
				$('#image-src-' + currentMatchId).attr('src', response['path']);
				$('#image-src-' + currentMatchId).removeClass('d-none');
				$('#image-src-placeholder-' + currentMatchId).addClass('d-none');
				$('#image-picker').modal('hide');

				$('#image-src-' + currentMatchId).parent().parent().find('.checkbox-container').find('i').removeClass('checkbox-disabled');
			},
			error: function(error) {
				alert('File upload error');
			}
		});
	})

	//-------------------------------------------------------------------------------

	$(document).on('click', '.create-content', function () {
		console.log('create content with AI.');
		var quiz_type = $(this).data('type');

		$('#generate-content').data('type', quiz_type);
		$('.user-content').val('');
		$('#generate_example1').html(translations.forExample+' : Colors');
		$('#add-content-modal').modal('show');
	});

	$('#add-content-modal').on('shown.bs.modal', function () {
		$('.user-content').focus();
	});

	$('#generate-content').on('click', function () {
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
		$('.single-match').each(function () {
			var qNum = $(this).find('.match-number').data('match_num');
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
					$('.match-list').append(data);
				}

				setTimeout(function(){
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

	$('#cancel-create-content').click(function() {
		if (xhr) {
			xhr.abort(); // Cancel the AJAX request
		}
	});
//-------------------------------------------------------------------------------

});
