var imageEditor = null;
var currentImageInEditorSourceID = null;
var currentAudioInEditorSourceID = null;

var GroupIdCounter = 0;
var ItemIdCounter = 0;
var quizType = 'groupSort';
var xhr; // Variable to hold the XMLHttpRequest object

$(document).ready(function () {
	console.log(quizType);
	$('#add-content-modal').modal({
		backdrop: 'static',
		keyboard: false
	});
	if(quizType == 'whackAMole'){
		$('#add-content-modal').find('.modal-body .quantity').hide();
		$('#add-content-modal').find('.modal-body .subject1 span').text('Correct:');
		$('#generate_example1').html(translations.forExample+' : Facts about birds. / Name of the animals.');
		$('#add-content-modal').find('.modal-body .subject2').show();
		$('#add-content-modal').find('.modal-body .subject2 span').text('Incorrect:');
		$('#generate_example2').html(translations.forExample+' : Facts about cats. / Misspelled words or correct group.');
	}else{
		$('#add-content-modal').find('.modal-body .quantity').show();
		$('#generate_example1').html(translations.forExample+' : Happy / Weather / List of ingredients / Name of the animals.');
	}
	function handleItemList(itemList) {
		let handle = smoothDragOrder(itemList, 0.2); //0.2 seconds animation duration
	}
	var csrfToken = $('meta[name="csrf-token"]').attr('content');
	const containers = document.querySelectorAll('.item-list');
	containers.forEach(handleItemList);
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

	$(document).on('click', '.group-image, .item-image', function () {
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
			var currentId = $(this).data('id');
			var textContent = $(this).parent().find('.item-input').text();
			var putContent = textContent.length < 15 ? textContent : '';
			$('#image-picker').data('current', currentId);
			$('#image-search-query').val(putContent);
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
	});


	$('#image-picker').on('shown.bs.modal', function () {
		$('.input-container').removeClass('active-border');
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
					var currentId = $('#image-picker').data('current');
					console.log(currentId);

					$('#image-src-' + currentId).attr('src', $(this).attr('src'));
					$('#image-src-' + currentId).removeClass('d-none');
					$('#image-src-placeholder-' + currentId).addClass('d-none');
					$('#image-picker').modal('hide');
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

	$(document).on('click', '.item-input', function () {
		console.log($(this).text());
		//set parent input_container to active
		$('.input-container').removeClass('active-border');
		$(this).parent().parent().addClass('active-border');
	});


	//-------------------------------------------------------------------------------

	function disableDeleteItemButton() {
		$('.single-group').each(function () {
			let listCount = $(this).find('.single-item').length;
			let minItems = quizType === 'groupSort' ? 1 : 5;
			if (listCount <= minItems) {
				$(this).find('.single-item .delete-item-button').css('pointer-events', 'none');
			} else {
				$(this).find('.single-item .delete-item-button').css('pointer-events', 'auto');
			}
		});
	}
	disableDeleteItemButton();

	function disableDeleteGroupButton() {
			let groupCount = $('.group-list').find('.single-group').length;
			if (groupCount <= 2) {
				$('.group-list').find('.single-group .delete-group-button').css('pointer-events', 'none');
			} else {
				$('.group-list').find('.single-group .delete-group-button').css('pointer-events', 'auto');
			}
	}
	disableDeleteGroupButton();

	//-------------------------------------------------------------------------------
	function add_group(itemToClone, insertAfter, randomId) {
		// Use the clone method to clone the item, and insert it after the current one
// console.log(randomId);
		var clonedItem = itemToClone.clone();
		var currentItemId = itemToClone.data('id');

		//find the total number of questions
		clonedItem.find('.group-text').html("Group " + randomId);

		var randomId = "G" + randomId;
		clonedItem.find('.item-list').attr("data-group_id", randomId);
		clonedItem.find('.editor-add-item').attr("data-group_id", randomId);
		// console.log(clonedItem);

		// loop over all descendants with an ID
		clonedItem.find('[id]').each(function () {
			// console.log(this.id);
			// change the ID
			var newId = this.id;
			newId = newId.replace(currentItemId, randomId);
			newId = newId.replace('GBlank', randomId);
			this.id = newId;
			// console.log(this.id);
		});

		clonedItem.attr('data-id', randomId);
		clonedItem.find('[data-id]').each(function () {

			// console.log("DATA: data-id:", $(this).attr('data-id'));

			var newId = $(this).attr('data-id');
			if (typeof newId !== 'undefined') {
				newId = newId.replace(currentItemId, randomId);
				newId = newId.replace('GBlank', randomId);
				$(this).attr('data-id', newId);
			}
		});

		console.log('ADD CLONE GROUP');
		clonedItem.insertAfter(insertAfter);
		handleItemList(clonedItem.find('.item-list').get(0)); // Reapply functionality to cloned group
		disableDeleteGroupButton();
	}

	function clone_item(itemToClone, insertAfter, groupId, randomId) {
		// Use the clone method to clone the item, and insert it after the current one

		var clonedItem = itemToClone.clone();
		var currentItemId = itemToClone.data('id');
		// console.log(insertAfter);

		// find the biggest label number
		var biggest_num = 0;
		var ItemNum = 1;
		$('[data-group_id="' + groupId + '"] .single-item').each(function () {
			var item_num = $(this).find('.item-number').data('item_num');
			if (item_num > biggest_num) {
				biggest_num = item_num;
				ItemNum = biggest_num + 1;
			}
		});


		clonedItem.find('.item-number').html(ItemNum + ".");
		clonedItem.find('.item-number').attr('data-item_num',ItemNum);
		var randomId = "I" + randomId;

		// loop over all descendants with an ID
		clonedItem.find('[id]').each(function () {
			// console.log(this.id);
			// change the ID
			var newId = this.id;
			newId = newId.replace(currentItemId, groupId+randomId);
			this.id = newId;
			// console.log(this.id);
		});

		clonedItem.attr('data-id', groupId+randomId);
		clonedItem.find('[data-id]').each(function () {

			// console.log("DATA: data-id:", $(this).attr('data-id'));

			var newId = $(this).attr('data-id');
			if (typeof newId !== 'undefined') {
				newId = newId.replace(currentItemId, groupId+randomId);
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
	function generateContent(postData,quiz_type){
		xhr = $.ajax({
			type: "POST",
			url: "/quiz-content-builder-json",
			data: postData,
			headers: {
				'X-CSRF-TOKEN': csrfToken
			},
			beforeSend: function () {
				$('#spinIcon').addClass('fa-spin');
				$('#spinIcon').css('display', 'inline-block');
			},
			success: function (data) {
				if(quiz_type == 'whack_a_mole'){
					if (data == null) {
						showMessage('Something went wrong with the AI. Please try again.');
					} else {
						$.each(data, function(key, value){
							$('.item-list[data-group_id="' + key + '"]').append(value);
						});
					}
				}else{
					if (data == '') {
						showMessage('Something went wrong with the AI. Please try again.');
					} else {
						$('.group-list > .row').append(data);
					}
				}
				setTimeout(function(){
					$('#add-content-modal').modal('hide');
					disableDeleteItemButton();
					disableDeleteGroupButton();
				}, 1000);

			},
			complete: function () {
				$('#spinIcon').removeClass('fa-spin');
				$('#spinIcon').css('display', 'none');
			}
		});
	}

	//-------------------------------------------------------------------------------
	function checkGroupContentIsBlank(deleteButton){
		// single-group
		var isImageBlank = deleteButton.parents('.single-group').find('.item-image-src').filter(function() {
			return $(this).attr('src') !== '';
		}).length === 0;

		var isAudioBlank = deleteButton.parents('.single-group').find('.fa-volume-up').filter(function() {
			return $(this).css('color') !== 'rgb(204, 204, 204)';
		}).length === 0;

		var isTextBlank = deleteButton.parents('.single-group').find('.item-input').filter(function() {
			return $(this).text() !== '';
		}).length === 0;

		return isImageBlank && isAudioBlank && isTextBlank;
	}

	$(document).on('click', '.delete-group-button', function () {
		event.preventDefault();
		var isBlank = checkGroupContentIsBlank($(this));
		var id = $(this).closest('.single-group').data('id');
		if (isBlank) {
			$('.single-group[data-id="' + id + '"]').remove();
			disableDeleteGroupButton();
		} else {
		//show confirmation modal
		$('#delete-item-modal').find('.modal-body').html(translations.deleteGroup);
		$('#delete-item-modal').modal('show');
		$('#delete-item-modal').data('delete_type', 'group');
		//set the data-id of the delete button to the id of the item to be deleted
		$('#delete-item-modal').data('id', id);
		}
	});

	function checkItemContentIsBlank(deleteButton){
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
		var isBlank = checkItemContentIsBlank($(this));
		var id = $(this).closest('.single-item').data('id');
		if(isBlank){
			$('.single-item[data-id="' + id + '"]').remove();
			disableDeleteItemButton();
		}else{
			//show confirmation modal
			$('#delete-item-modal').find('.modal-body').html(translations.deleteItem);
			$('#delete-item-modal').modal('show');
			$('#delete-item-modal').data('delete_type', 'item');
			//set the data-id of the delete button to the id of the item to be deleted
			$('#delete-item-modal').data('id', id);
		}
	});

	$("#delete-question-confirm").on('click', function () {
		var id = $('#delete-item-modal').data('id');
		var type = $('#delete-item-modal').data('delete_type');
		// console.log(type);

		if(type == 'group'){
			//delete .single-group with data-id=id
			$('.single-group[data-id="' + id + '"]').remove();
		}else if(type == 'item'){
			//delete .single-item with data-id=id
			$('.single-item[data-id="' + id + '"]').remove();
		}

		$('#delete-item-modal').modal('hide');
		disableDeleteItemButton();
		disableDeleteGroupButton();
	});

	//-------------------------------------------------------------------------------

	$(document).on('click', '.js-editor-add-group', function () {
		event.preventDefault();

		GroupIdCounter++;
		//validate that no other div has the same data-id
		let found_unique_id = false;
		while (!found_unique_id) {
			found_unique_id = true;
			$('.single-group').each(function () {
				if ($(this).data('id') === "G" + GroupIdCounter) {
					GroupIdCounter++;
					found_unique_id = false;
				}
			});
		}
		// console.log(GroupIdCounter);
		//find the div with the data-id="QBlank"
		var cloneFrom = $('.empty-group-container').find('.single-group').first();
		// console.log(cloneFrom.html());

		//find the last question in the list
		var insertAfter = $('.group-list').find('.single-group').last();
		// console.log(insertAfter.html());
		add_group(cloneFrom, insertAfter, GroupIdCounter);

	});

	//-------------------------------------------------------------------------------
	$(document).on('click', '.js-editor-add-item', function () {
		event.preventDefault();
		var $items = $(this).siblings('.item-list').find('.single-item');
		var ItemIdCounter = 1;
		var groupId = $(this).data('group_id');
		console.log('group: ' + groupId);
		$items.each(function() {
			let found_unique_id = false;
			while (!found_unique_id) {
				found_unique_id = true;
				$('.single-item').each(function () {
					if ($(this).data('id') === groupId+"I" + ItemIdCounter) {
						ItemIdCounter++;
						found_unique_id = false;
					}
				});
			}

		});

		console.log('item id' + ItemIdCounter);

		//find the div with the data-id="QBlank"
		var cloneFrom = $('.empty-item-container').find('.single-item').first();
		// console.log(cloneFrom.html());

		//find the last question in the list
		var insertAfter = $(".item-list").filter(function() {
			return $(this).data("group_id") == groupId;
		}).find(".single-item").last();
		// console.log(insertAfter.html());
		clone_item(cloneFrom, insertAfter, groupId, ItemIdCounter);

	});


	// Listen for a click event on any elements with a class of 'clone-item-button'
	$(document).on('click', '.clone-item-button', function (event) {
		event.preventDefault();
		var groupId = $(this).parents('.item-list').data('group_id');
		var $items = $(this).parents('.item-list').find('.single-item');
		var ItemIdCounter = 1;
		$items.each(function() {
			let found_unique_id = false;
			while (!found_unique_id) {
				found_unique_id = true;
				$('.single-item').each(function () {
					if ($(this).data('id') === groupId+"I" + ItemIdCounter) {
						ItemIdCounter++;
						found_unique_id = false;
					}
				});
			}

		});
		clone_item($(this).closest('.single-item'), $(this).closest('.single-item'), groupId, ItemIdCounter);
	});

	//-------------------------------------------------------------------------------

	$(document).on('click', '.edit-group-text', function() {
		var oldText = $(this).siblings('.group-text').text();
		var groupId = $(this).data('id');
		$('#edit-group-text-modal').find('.group-text-input').val(oldText);
		$('#edit-group-text-modal').find('#update-group-text').data('id', groupId);
		$('#edit-group-text-modal').modal('show');

	});

	$(document).on('click', '#update-group-text', function (event) {
		var newText = $('.group-text-input').val();
		var groupId = $(this).data('id');
		$('#'+groupId).text(newText);
		$('#edit-group-text-modal').modal('hide');

	});

	//-------------------------------------------------------------------------------

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
			},
			error: function(error) {
				alert('File upload error');
			}
		});
	})
//-------------------------------------------------------------------------------

	$(".editor-done").on('click', function () {
		var title = $('#activity-title-input').val();
		var category = "Science";
		var grade = "3";
		var language = "English";
		var activity_id = $('#activity_id').val();

		var data_json = [];
		$('.group-list').find('.single-group').each(function () {
			var groupId = $(this).data('id');
			var groupImg = $(this).find('.group-image img').attr('src') ?? "";
			var groupText = $(this).find('#group-text-' + groupId).text() ?? "";

			var items = [];
			$(this).find('.item-list .input-container').each(function () {
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
				};

				items.push(itemJson);
			});

			var groupJson = {
				"id": groupId,
				"image": groupImg,
				"text": groupText,
				"items": items
			};
			data_json.push(groupJson);
		});

// console.log(data_json);
		$.ajax({
			type: "POST",
			url: "/quiz-group-build-json",
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

	//-------------------------------------------------------------------------------

	$(document).on('click', '.create-content', function () {
		console.log('create content with AI.');
		var quiz_type = $(this).data('type');

		$('#generate-content').data('type', quiz_type);
		$('.user-content').val('');
		$('.warning_msg').html('');
		$('#add-content-modal').modal('show');
	});

	$('#add-content-modal').on('shown.bs.modal', function () {
		$('.user-content').focus();
	});

	$('#generate-content').on('click', function () {
		console.log('Generate content with AI.');
		var quiz_type = $(this).data('type');
		var activity_id = $('#activity_id').val();
		var language = $('#generate_language').val();
		// var skipQuestion = [];

		var isBlank = false;
		if(quiz_type == 'whack_a_mole'){
			var user_content =[];
			$('.user-content').each(function () {
				if ($.trim($(this).val()) === '') {
					isBlank = true;
					return;
				}
				user_content.push($(this).val());
			});

			// find the biggest item number for each group
			let groups = $('.single-group');
			let groupAndItem = {};

			groups.each(function(){
				let groupId = $(this).data('id'); // group id
				if(groupId !== 'GBlank'){
					let items = $(this).find('.single-item');
					let allItemNumbers = items.map(function(){
						return $(this).data('id');
					}).get();
					// Collect all numeric parts of item ids
					let numericPartOfItemIds = allItemNumbers.map(id => parseInt(id.replace(groupId+'I', ''), 10));
					// Find max item id
					let maxItemId = Math.max(...numericPartOfItemIds)+1;

					// Find max item number
					let itemNumbers = items.map(function(){
						return parseInt($(this).find('.item-number').data('item_num'), 10);
					}).get();

					let maxItemNumber = Math.max.apply(null, itemNumbers)+1;

					groupAndItem[groupId] = {
						maxItemId: maxItemId,
						maxItemNumber
					};
				}
			});

			var postData = {
				quiz_type: quiz_type,
				activity_id: activity_id,
				user_content: user_content,
				language: language,
				groupAndItem: groupAndItem
			}

		}else{
			var user_content =$('.user-content').val();
			if ($.trim(user_content) === '') {
				isBlank = true;
				return;
			}

			var quantity = $('#generate_quantity').val();

			// find the biggest question number
			var biggest_id = 1;
			// var skipQuestion = [];
			$('.single-group').each(function () {
				var qId = $(this).data('id').replace(/[a-z]/gi, "");
				if (qId > biggest_id) {
					biggest_id = qId;
				}
			});

			var next_id = Number(biggest_id) + 1;

			var postData = {
				quiz_type: quiz_type,
				activity_id: activity_id,
				quantity: quantity,
				next_id: next_id,
				user_content: user_content,
				language: language
			}

		}

		if(isBlank){
			$('#add-content-modal').find('.modal-body').append('<div class="warning_msg" style="color: red; font-size: 10px;">Correct or Incorrect input cannot be blank!</div>');
		}

		generateContent(postData,quiz_type);

	});

	$('#cancel-create-content').click(function() {
		if (xhr) {
			xhr.abort(); // Cancel the AJAX request
		}
	});

	//-------------------------------------------------------------------------------
});
