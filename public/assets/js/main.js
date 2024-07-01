

function getCurrentTime() {
	const now = new Date();
	let hours = now.getHours();
	let minutes = now.getMinutes();
	const ampm = hours >= 12 ? 'pm' : 'am';
	hours = hours % 12;
	hours = hours ? hours : 12; // the hour '0' should be '12'
	minutes = minutes < 10 ? '0' + minutes : minutes;
	return hours + ':' + minutes + ampm;
}

function disableSaveButton() {
	document.getElementById('generate_save_button').disabled = true;
}

function enableSaveButton() {
	document.getElementById('generate_save_button').disabled = false;
}

$(document).ready(function () {
	
	// Scroll button click event
	$('#scrollBtn').on('click', function () {
		
		
		var scrollHeight = $("#chat").height();
		//console.log(scrollHeight);
		// Check if the chat is already scrolled to the end
		if ($(this).html() === '<i class="bi bi-arrow-bar-up"></i>') {
			// Scroll to the beginning
			$(".chat-conversation-content").animate({scrollTop: 0}, 300);
			$('#scrollBtn').html('<i class="bi bi-arrow-bar-down"></i>');
		} else {
			// Scroll to the end
			$(".chat-conversation-content").animate({scrollTop: scrollHeight}, 300);
			$('#scrollBtn').html('<i class="bi bi-arrow-bar-up"></i>');
		}
	});
	
	
});

