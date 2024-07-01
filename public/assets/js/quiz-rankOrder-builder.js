var imageEditor = null;
var currentImageInEditorSourceID = null;
var currentAudioInEditorSourceID = null;

var ItemIdCounter = 0;
var xhr; // Variable to hold the XMLHttpRequest object

$(document).ready(function () {
	$('#add-content-modal').modal({
		backdrop: 'static',
		keyboard: false
	});
	$('#add-content-modal').find('.modal-body .quantity').hide();
	$('#generate_example1').html(translations.forExample+' : Brush teeth, Make a cake, Wash hands, etc.');

	const container = document.querySelector('.item-list');
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
		// var currentItemId = currentImageInEditor;
		// var dataUrl = imageEditor.toDataURL();
		// console.log(e.detail.dataURL);
		$('#image-src-' + currentImageInEditorSourceID).attr('src', e.detail.dataURL);
		$("#tui-image-editor-container").hide();
		$('body').removeClass('no-scroll-bars');

	});

	$(document).on('click', '.item-image', function () {
		$('.input-container').removeClass('active-border');

		console.log('clicked');
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

		var text = $('#item-text-' + currentImageInEditorSourceID).text();
		if( text == ''){
			$('#item-text-' + currentImageInEditorSourceID).parent().parent().find('.checkbox-container').find('i').addClass('checkbox-disabled');
			$('#item-text-' + currentImageInEditorSourceID).parent().parent().find('.checkbox-container').find('i').removeClass('fa-check');
			$('#item-text-' + currentImageInEditorSourceID).parent().parent().find('.checkbox-container').find('i').addClass('fa-times');
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
			console.log($("#item-text-" + currentAudioInEditorSourceID));
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
				$('#audio-tts-input').val($("#item-text-" + currentAudioInEditorSourceID).html());  // clear the search-input
			}
			$("#audio-tts-input").focus(); // focus on the search-input

			$('#play-audio-button').addClass('d-none');
			$('#audio-preview').attr('src', '');
		}

	});

	$(document).on('click', '.item-audio', function () {
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
			var imageSrc = $(this).parent().parent().find('.item-image img').attr('src');
			if(imageSrc == ''){
				//disable checkbox
				$(this).parent().parent().find('.checkbox-container').find('i').addClass('checkbox-disabled');
				$(this).parent().parent().find('.checkbox-container').find('i').removeClass('fa-check');
				$(this).parent().parent().find('.checkbox-container').find('i').addClass('fa-times');
			}
		}
	});

	function disableDeleteItemButton() {
			let listCount = $('.item-list').find('.single-item').length;
			if (listCount <= 3) {
				$('.item-list').find('.single-item .delete-item-button').css('pointer-events', 'none');
			} else {
				$('.item-list').find('.single-item .delete-item-button').css('pointer-events', 'auto');
			}
	}
	disableDeleteItemButton();


	//-------------------------------------------------------------------------------

	function clone_item(itemToClone, insertAfter, randomId) {
		// Use the clone method to clone the item, and insert it after the current one

		var clonedItem = itemToClone.clone();
		var currentItemId = itemToClone.data('id');

		// find the biggest label number
		var biggest_num = 0;
		var ItemNum = 1;
		$('.single-item').each(function () {
			var item_num = $(this).find('.item-number').data('item_num');
			if (item_num > biggest_num) {
				biggest_num = item_num;
				ItemNum = biggest_num + 1;
			}
		});

		clonedItem.find('.item-number').html( ItemNum + ".");
		clonedItem.find('.item-number').attr('data-item_num', ItemNum);

		var randomId = "I" + randomId;

		// loop over all descendants with an ID
		clonedItem.find('[id]').each(function () {
			// console.log(this.id);
			// change the ID
			var newId = this.id;
			newId = newId.replace(currentItemId, randomId);
			this.id = newId;
			// console.log(this.id);
		});

		clonedItem.attr('data-id', randomId);
		clonedItem.find('[data-id]').each(function () {

			// console.log("DATA: data-id:", $(this).attr('data-id'));

			var newId = $(this).attr('data-id');
			if (typeof newId !== 'undefined') {
				newId = newId.replace(currentItemId, randomId);
				$(this).attr('data-id', newId);
			}
		});

		console.log('ADD CLONE ITEM');
		clonedItem.insertAfter(insertAfter);
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
		var image = deleteButton.parents('.single-item').find('.item-image-src').attr('src');
		var audio = deleteButton.parents('.single-item').find('.fa-volume-up').css('color');
		var text = deleteButton.parents('.single-item').find('.item-input').text();
		if(image === '' && audio === 'rgb(204, 204, 204)' && text === ''){
			return true;
		}else{
			return false;
		}
	}

	$(document).on('click', '.delete-item-button', function () {
		event.preventDefault();
		var isBlank = checkContentIsBlank($(this));
		var id = $(this).closest('.single-item').data('id');
		if (isBlank) {
			$('.single-item[data-id="' + id + '"]').remove();
			disableDeleteItemButton();
		} else {
		//show confirmation modal
		$('#delete-item-modal').find('.modal-body').html(translations.deleteOrder);
		$('#delete-item-modal').modal('show');
		//set the data-id of the delete button to the id of the item to be deleted
		$('#delete-item-modal').data('id', id);
	}
	});

	$("#delete-question-confirm").on('click', function () {
		var id = $('#delete-item-modal').data('id');
		//delete .single-item with data-id=id
		$('.single-item[data-id="' + id + '"]').remove();


		$('#delete-item-modal').modal('hide');
		disableDeleteItemButton();
	});


	$(document).on('click', '.js-editor-add-item', function () {
		event.preventDefault();

		ItemIdCounter++;
		//validate that no other div has the same data-id
		let found_unique_id = false;
		while (!found_unique_id) {
			found_unique_id = true;
			$('.single-item').each(function () {
				console.log($(this).data('id'));
				if ($(this).data('id') === "I" + ItemIdCounter) {
					ItemIdCounter++;
					found_unique_id = false;
				}
			});
		}
		//find the div with the data-id="QBlank"
		var cloneFrom = $('.empty-item-container').find('.single-item').first();
		// console.log(cloneFrom.html());

		//find the last question in the list
		var insertAfter = $('.item-list').find('.single-item').last();
		// console.log(insertAfter.html());
		clone_item(cloneFrom, insertAfter, ItemIdCounter);

	});


	// Listen for a click event on any elements with a class of 'clone-item-button'
	$(document).on('click', '.clone-item-button', function (event) {
		event.preventDefault();

		ItemIdCounter++;
		//validate that no other div has the same data-id
		let found_unique_id = false;
		while (!found_unique_id) {
			found_unique_id = true;
			$('.single-item').each(function () {
				if ($(this).data('id') === "I" + ItemIdCounter) {
					ItemIdCounter++;
					found_unique_id = false;
				}
			});
		}

		clone_item($(this).closest('.single-item'), $(this).closest('.single-item'), ItemIdCounter);
	});


	$(".editor-done").on('click', function () {
		var title = $('#activity-title-input').val();
		var category = "Science";
		var grade = "3";
		var language = "English";
		var activity_id = $('#activity_id').val();

		var data_json = [];
		$('.item-list').find('.single-item .input-container').each(function () {
					var itemId = $(this).find('.item-image').data('id');
					var itemAudio = $(this).find('.item-audio i').data('src') ?? "";
					var itemAudioText = $(this).find('.item-audio i').data('text') ?? "";
					var itemAudioVoice = $(this).find('.item-audio i').data('voice') ?? "";
					var itemText = $(this).find('#item-text-' + itemId).text() ?? "";
					var itemImg = $(this).find('.item-image img').attr('src') ?? "";
					var itemJson = {
						"id": itemId,
						"audio": itemAudio,
						"audio_tts": itemAudioText,
						"audio_voice": itemAudioVoice,
						"image": itemImg,
						"text": itemText
					}

			data_json.push(itemJson);
		});

// console.log(data_json);
		$.ajax({
			type: "POST",
			url: "/quiz-item-build-json",
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
		var formData = new FormData();
		var currentItemId = $('#image-picker').data('current');
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
				$('#image-src-' + currentItemId).attr('src', response['path']);
				$('#image-src-' + currentItemId).removeClass('d-none');
				$('#image-src-placeholder-' + currentItemId).addClass('d-none');
				$('#image-picker').modal('hide');

				$('#image-src-' + currentItemId).parent().parent().find('.checkbox-container').find('i').removeClass('checkbox-disabled');
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
		$('.single-item').each(function () {
			var iNum = $(this).find('.item-number').data('item_num');
			var iId = $(this).data('id').replace(/[a-z]/gi, "");
			if (iNum > biggest_num) {
				biggest_num = iNum;
			}
			if (iId > biggest_id) {
				biggest_id = iId;
			}
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
					$('.item-list').append(data);
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
