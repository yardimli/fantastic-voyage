var imageEditor = null;
var currentImageInEditorSourceID = null;
var currentAudioInEditorSourceID = null;

var LabelIdCounter = 0;

$(document).ready(function () {

	const container = document.querySelector('.label-list');
	let handle = smoothDragOrder(container, 0.2); //0.2 seconds animation duration

	$(".image-label").draggable({
		containment: "parent" // confine dragging to within the bounds of the parent
	});

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

	$(document).on('click', '.diagram-image, .item-image', function () {
		$('.input-container').removeClass('active-border');

		console.log('Add image');
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

		$(document).find('.image-label').hide();
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
					$('.image-label').show();
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

	$(document).on('click', '.item-input', function () {
		console.log($(this).text());
		//set parent input_container to active
		$('.input-container').removeClass('active-border');
		$(this).parent().parent().addClass('active-border');
	});


	//-------------------------------------------------------------------------------

	function disableDeleteItemButton() {
		$('.label-list').each(function () {
			let listCount = $(this).find('.single-label').length;
			if (listCount <= 3) {
				$(this).find('.single-label .delete-item-button').css('pointer-events', 'none');
			} else {
				$(this).find('.single-label .delete-item-button').css('pointer-events', 'auto');
			}
		});
	}
	disableDeleteItemButton();

	//-------------------------------------------------------------------------------

	function clone_item(itemToClone, insertAfter, randomId) {
		// Use the clone method to clone the item, and insert it after the current one

		var clonedItem = itemToClone.clone();
		var currentItemId = itemToClone.data('id');
		// console.log(insertAfter);

		// find the biggest label number
		var biggest_label_num = 0;
		var LabelNum = 1;
		$('.single-label').each(function () {
			var label_num = $(this).find('.label-number').data('label_num');
			if (label_num > biggest_label_num) {
				biggest_label_num = label_num;
				LabelNum = biggest_label_num+1;
			}
		});

		clonedItem.find('.label-number').html(LabelNum + ".");
		clonedItem.find('.label-number').data('label_num',LabelNum);
		var LRandomId = "L" + randomId;

		// loop over all descendants with an ID
		clonedItem.find('[id]').each(function () {
			// console.log(this.id);
			// change the ID
			var newId = this.id;
			newId = newId.replace(currentItemId, LRandomId);
			this.id = newId;
			// console.log(this.id);
		});

		clonedItem.attr('data-id', LRandomId);
		clonedItem.find('[data-id]').each(function () {

			// console.log("DATA: data-id:", $(this).attr('data-id'));

			var newId = $(this).attr('data-id');
			if (typeof newId !== 'undefined') {
				newId = newId.replace(currentItemId, LRandomId);
				$(this).attr('data-id', newId);
			}
		});

		console.log('ADD CLONE ITEM');
		clonedItem.insertAfter(insertAfter);
		disableDeleteItemButton();


		//create a new label icon on image
		$top_pos = Math.floor((randomId-1)/10)*20 + 15;
		$left_pos = ((randomId-1)%10)*20 + 10;
		$display = $('#image-src-D1').attr('src') !== '' ? 'block' : 'none';
		var newLabel = $('<div class="image-label" data-id="'+LRandomId+'" style="top: '+$top_pos+'px; left: '+$left_pos+'px; display: '+$display+'">' +LabelNum + '</div>');
		$('#diagram-image-container').append(newLabel);

		// Make label draggable
		$(".image-label").draggable({
			containment: "parent" // confine dragging to within the bounds of the parent
		});
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
		var image = deleteButton.parents('.single-label').find('.item-image-src').attr('src');
		var audio = deleteButton.parents('.single-label').find('.fa-volume-up').css('color');
		var text = deleteButton.parents('.single-label').find('.item-input').text();
		if(image === '' && audio === 'rgb(204, 204, 204)' && text === ''){
			return true;
		}else{
			return false;
		}
	}

	$(document).on('click', '.delete-item-button', function () {
		event.preventDefault();
		var isBlank = checkContentIsBlank($(this));
		var id = $(this).closest('.single-label').data('id');
		if(isBlank){
			$('.single-label[data-id="' + id + '"]').remove();
			$('.image-label[data-id="' + id + '"]').remove();
			disableDeleteItemButton();
		}else{
			//show confirmation modal
			$('#delete-item-modal').find('.modal-body').html(translations.deleteLabel);
			$('#delete-item-modal').modal('show');
			$('#delete-item-modal').data('delete_type', 'item');
			//set the data-id of the delete button to the id of the item to be deleted
			$('#delete-item-modal').data('id', id);
		}

	});

	$("#delete-question-confirm").on('click', function () {
		var id = $('#delete-item-modal').data('id');
			//delete .single-label with data-id=id
			$('.single-label[data-id="' + id + '"]').remove();
			$('.image-label[data-id="' + id + '"]').remove();

		$('#delete-item-modal').modal('hide');
		disableDeleteItemButton();
	});

	//-------------------------------------------------------------------------------
	$(document).on('click', '.js-editor-add-item', function () {
		event.preventDefault();

		LabelIdCounter++;
		//validate that no other div has the same data-id
		let found_unique_id = false;
		while (!found_unique_id) {
			found_unique_id = true;
			$('.single-label').each(function () {
				if ($(this).data('id') === "L" + LabelIdCounter) {
					LabelIdCounter++;
					found_unique_id = false;
				}
			});
		}

		var cloneFrom = $('.empty-label-container').find('.single-label').first();
		// console.log(cloneFrom.html());

		//find the last question in the list
		var insertAfter = $(".label-list").find(".single-label").last();
		// console.log(insertAfter.html());
		clone_item(cloneFrom, insertAfter, LabelIdCounter);

	});


	// Listen for a click event on any elements with a class of 'clone-item-button'
	$(document).on('click', '.clone-item-button', function (event) {
		event.preventDefault();
		LabelIdCounter++;
		//validate that no other div has the same data-id
		let found_unique_id = false;
		while (!found_unique_id) {
			found_unique_id = true;
			$('.single-label').each(function () {
				if ($(this).data('id') === "L" + LabelIdCounter) {
					LabelIdCounter++;
					found_unique_id = false;
				}
			});
		}

		clone_item($(this).closest('.single-label'), $(this).closest('.single-label'), LabelIdCounter);
	});

	//-------------------------------------------------------------------------------

	$(".editor-done").on('click', function () {
		var title = $('#activity-title-input').val();
		var category = "Science";
		var grade = "3";
		var language = "English";
		var activity_id = $('#activity_id').val();
		var csrfToken = $('meta[name="csrf-token"]').attr('content');

		var data_json = [];

			var diagramId = $('.diagram-image').data('id');
			var diagramImg = $('.diagram-image').find('img').attr('src') ?? "";

		var positions = $(".image-label").map(function() {
			var position = $(this).position();
			return {
				id: $(this).data("id"),
				top: position.top,
				left: position.left
			};
		}).get(); // convert to plain JS array

			var labels = [];
			$('.label-list ').find('.input-container').each(function () {
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

				labels.push(itemJson);
			});

			var diagramJson = {
				"id": diagramId,
				"image": diagramImg,
				"labels": labels,
				"labels_positions": positions
			};
			data_json.push(diagramJson);

// console.log(data_json);
		$.ajax({
			type: "POST",
			url: "/quiz-diagram-build-json",
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
				$('.image-label').show();
				$('#image-picker').modal('hide');
			},
			error: function(error) {
				alert('File upload error');
			}
		});
	})

//check if label image has changed - clear, update, or crop
	let imgEl = document.querySelector("#image-src-D1");

	let observer = new MutationObserver((mutations) => {
		mutations.forEach((mutation) => {
			if (mutation.type == "attributes" && mutation.attributeName === "src") {
				resetLabelPositions();
				console.log("Img changed: ");
			}
		});
	});

	let config = { attributes: true };
	observer.observe(imgEl, config);

	function resetLabelPositions(){
		$(".image-label").each(function() {
			var index = parseInt($(this).html());
			var origPos = calculatePosition(index);
			$(this).css({
				top: origPos.top + 'px',
				left: origPos.left + 'px'
			});
		});
	}

	function calculatePosition(index) {
		var top_pos = Math.floor((index-1)/10)*20 + 15;
		var left_pos = ((index-1)%10)*20 + 10;
		return { top: top_pos, left: left_pos };
	}

});
