var PageIdCounter = 0;
var xhr; // Variable to hold the XMLHttpRequest object

$(document).ready(function () {
	$('#add-content-modal').modal({
		backdrop: 'static',
		keyboard: false
	});
	const container = document.querySelector('.page-list');
	let handle = smoothDragOrder(container, 0.2); //0.2 seconds animation duration
	var csrfToken = $('meta[name="csrf-token"]').attr('content');
	//-------------------------------------------------------------------------------

	function disableDeleteItemButton() {
		$('.page-list').each(function () {
			let listCount = $(this).find('.single-page').length;
			if (listCount <= 1) {
				$(this).find('.single-page .delete-item-button').css('pointer-events', 'none');
			} else {
				$(this).find('.single-page .delete-item-button').css('pointer-events', 'auto');
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
		var biggest_page_num = 1;
		var PageNum = 1;
		$('.single-page').each(function () {
			var page_num = $(this).find('.page-number').data('page_num');
			console.log(page_num+'----');
			if (page_num >= biggest_page_num) {
				biggest_page_num = page_num;
				PageNum = biggest_page_num+1;
			}
		});

		clonedItem.find('.page-number').html(PageNum + ".");
		clonedItem.find('.page-number').data('page_num',PageNum);
		var PRandomId = "P" + randomId;

		// loop over all descendants with an ID
		clonedItem.find('[id]').each(function () {
			// console.log(this.id);
			// change the ID
			var newId = this.id;
			newId = newId.replace(currentItemId, PRandomId);
			this.id = newId;
			// console.log(this.id);
		});

		clonedItem.attr('data-id', PRandomId);
		clonedItem.find('[data-id]').each(function () {

			// console.log("DATA: data-id:", $(this).attr('data-id'));

			var newId = $(this).attr('data-id');
			if (typeof newId !== 'undefined') {
				newId = newId.replace(currentItemId, PRandomId);
				$(this).attr('data-id', newId);
			}
		});

		console.log('ADD CLONE PAGE');
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
		var sentence = deleteButton.parents('.single-page').find('.page-text').html();
		if(sentence === ''){
			return true;
		}else{
			return false;
		}
	}

	$(document).on('click', '.delete-item-button', function () {
		event.preventDefault();
		var isBlank = checkContentIsBlank($(this))
		var id = $(this).closest('.single-page').data('id');
		if(isBlank){
			$('.single-page[data-id="' + id + '"]').remove();
			disableDeleteItemButton();
		}else{
			//show confirmation modal
			$('#delete-item-modal').find('.modal-body').html(translations.deletePage);
			$('#delete-item-modal').modal('show');
			$('#delete-item-modal').data('delete_type', 'item');
			//set the data-id of the delete button to the id of the item to be deleted
			$('#delete-item-modal').data('id', id);
		}

	});

	$("#delete-question-confirm").on('click', function () {
		var id = $('#delete-item-modal').data('id');
			//delete .single-label with data-id=id
			$('.single-page[data-id="' + id + '"]').remove();

		$('#delete-item-modal').modal('hide');
		disableDeleteItemButton();
	});

	//-------------------------------------------------------------------------------
	$(document).on('click', '.js-editor-add-item', function () {
		event.preventDefault();

		PageIdCounter++;
		//validate that no other div has the same data-id
		let found_unique_id = false;
		while (!found_unique_id) {
			found_unique_id = true;
			$('.single-page').each(function () {
				if ($(this).data('id') === "P" + PageIdCounter) {
					PageIdCounter++;
					found_unique_id = false;
				}
			});
		}

		var cloneFrom = $('.empty-page-container').find('.single-page').first();
		// console.log(cloneFrom.html());

		//find the last question in the list
		var insertAfter = $(".page-list").find(".single-page").last();
		// console.log(insertAfter.html());
		clone_item(cloneFrom, insertAfter, PageIdCounter);

	});


	// Listen for a click event on any elements with a class of 'clone-item-button'
	$(document).on('click', '.clone-item-button', function (event) {
		event.preventDefault();
		PageIdCounter++;
		//validate that no other div has the same data-id
		let found_unique_id = false;
		while (!found_unique_id) {
			found_unique_id = true;
			$('.single-page').each(function () {
				if ($(this).data('id') === "P" + PageIdCounter) {
					PageIdCounter++;
					found_unique_id = false;
				}
			});
		}

		clone_item($(this).closest('.single-page'), $(this).closest('.single-page'), PageIdCounter);
	});

	//-------------------------------------------------------------------------------
	$(document).on('click', '.add-new-word', function () {
		event.preventDefault();
		$pid = $(this).data('id');
		$('.word-input').val('');
		$('#add-new-word-modal').modal('show');
		$('#update-new-word').data('id', $pid);
	});

	$('#add-new-word-modal').on('shown.bs.modal', function () {
		$('.word-input').focus();
	});

	$("#update-new-word").on('click', function () {
		let id = $(this).data('id');
		let word = $('.word-input').val();
		var newWord = '<div class="word-box"><span class="fa fa-remove"></span>'+word+'<span class="remove-word"></span></div>';
		$(newWord).insertBefore('#incorrect-words-'+id+' .add-new-word');
		$('#add-new-word-modal').modal('hide');
	});

	function updateSelection(id) {
		var selectedText = window.getSelection().toString();
		var pageDiv = $('.single-page[data-id="' + id + '"]');
		if (selectedText){
			pageDiv.find('.add-selected').css('display', 'inline-block');
			pageDiv.find('.add-selected-text').text(translations.add+' "'+ selectedText + '"');
			pageDiv.find('.selected-msg').hide();
		} else {
			pageDiv.find('.add-selected').css('display', 'none');
		}
	}

	$(document).on('mouseup', '.page-text', function(){
		var id = $(this).parents('.missing-word-div').data('id');
		updateSelection(id);
	});

	$(document).on('click', '.add-selected', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var pageDiv = $('.single-page[data-id="' + id + '"]');
		var selectedText = window.getSelection().toString();
		if (selectedText === '') {
			pageDiv.find('.add-selected').css('display', 'none');
			return;
		}

		var selection = window.getSelection();
		if(selection.anchorNode.parentNode.tagName === 'B' || selection.focusNode.parentNode.tagName === 'B'){
			pageDiv.find('.add-selected').css('display', 'none');
			return;
		}

		pageDiv.find('.selected-words').append('<div class="word-box"><span class="fa fa-check"></span>'+selectedText+'<span class="remove-word"></span></div>');
		pageDiv.find('.add-selected').css('display', 'none');

		var selection = window.getSelection().getRangeAt(0);
		var selectedText = selection.extractContents();

		var span = document.createElement("b");
		span.appendChild(selectedText);
		selection.insertNode(span);
	});

	// $(document).on('click', '.add-selected', function(e){
	// 	e.preventDefault();
	// 	var id = $(this).data('id');
	// 	var pageDiv = $('.single-page[data-id="' + id + '"]');
	// 	var selectedText = window.getSelection().toString();
	// 	pageDiv.find('.selected-words').append('<div class="word-box">'+selectedText+'<span class="remove-word"></span></div>');
	// 	pageDiv.find('.add-selected').css('display', 'none');
	// 	//need a check if already selected............
	// 	var sentence =  pageDiv.find('.page-text').html();
	// 	var textWithBTag =  wrapCharInWord(sentence, selectedText, selectedText);
	// 	//var textWithBTag = sentence.replace(selectedText, '<b>' + selectedText + '</b>');
	// 	pageDiv.find('.page-text').html(textWithBTag);
	//
	//
	// });

	//-------------------------------------------------------------------------------
	$(document).on('click', '.remove-word', function () {
		event.preventDefault();
		var element = $(this).closest('.word-box');
		var selectedText = element.text();
		if($(this).parents('.selected-words').length ){
			var sentence =  $(this).parents('.page-container').find('.page-text').html();
			var removeBTag = sentence.replace('<b>' + selectedText + '</b>', selectedText);
			$(this).parents('.page-container').find('.page-text').html(removeBTag);
		}
		$thisParent = $(this).parents('.words-container');
		element.remove();

		var countMissingWords = $(this).parents('.selected-words').find('.word-box').length;
		if(countMissingWords < 1){
			console.log($thisParent);
			$thisParent.find('.selected-msg').show();
		}
	});
	//-------------------------------------------------------------------------------

	$(".editor-done").on('click', function () {
		var title = $('#activity-title-input').val();
		var category = "Science";
		var grade = "3";
		var language = "English";
		var activity_id = $('#activity_id').val();

		var data_json = [];

			$('.page-list ').find('.single-page').each(function () {
				var pageId = $(this).data('id');
				var pageText = $(this).find('#page-text-' + pageId).html() ?? "";
				var missingWords = [];
				$(this).find('.selected-words .word-box').each(function(){
					missingWords.push($(this).text());
				});
				var incorrectWords = [];
				$(this).find('.incorrect-words .word-box').each(function(){
					incorrectWords.push($(this).text());
				});

				var pageJson = {
					"id": pageId,
					"text": pageText,
					"missingWords": missingWords,
					"incorrectWords": incorrectWords
				};
				data_json.push(pageJson);
			});


// console.log(data_json);
		$.ajax({
			type: "POST",
			url: "/quiz-missingWord-build-json",
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
		$('#generate_example1').html(translations.forExample+' : Favorite food');
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
		$('.single-page').each(function () {
			var pNum = $(this).find('.page-number').data('page_num');
			var pId = $(this).data('id').replace(/[a-z]/gi, "");
			if (pNum > biggest_num) {
				biggest_num = pNum;
			}
			if (pId > biggest_id) {
				biggest_id = pId;
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
					$('.page-list').append(data);
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
		console.log('cancel create content');
		if (xhr) {
			console.log('stop ajax request');
			xhr.abort(); // Cancel the AJAX request
		}
	});
	//-------------------------------------------------------------------------------

});
