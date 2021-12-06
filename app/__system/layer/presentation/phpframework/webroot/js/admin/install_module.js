function addNewFile(elm) {
	var html = '<div class="upload_file"><input type="file" name="zip_file[]" multiple> <span class="icon delete" onClick="$(this).parent().remove()"></span></div>';
	var upload_files = $(elm).parent().children(".upload_file");
	
	if (upload_files.length < 20)
		upload_files.last().after(html);
	else
		alert("Maximum number of allowable file uploads has been reached!");
}

function onSubmitButtonClick(elm) {
	if (checkUploadedFiles()) {
		$('<p class=installing>Installing...</p>').insertBefore(elm);
		$(elm).hide();
		
		return true;
	}
	
	return false;
}

function checkUploadedFiles() {
	var inputs = $(".file_upload input[type=file]");
	var count = 0;
	
	$.each(inputs, function (idx, input) {
		if (input.files)
			count += input.files.length;
	});
	
	if (count > 20) {
		alert("You can only upload 20 zip files maximum each time!\nPlease remove some files before you proceed...");
		return false;
	}
	else if (!count) {
		alert("You must select at least 1 zip file to proceed!");
		return false;
	}
	
	$.each(inputs, function (idx, input) {
		if (idx > 0 && (!input.files || !input.files.length))
			$(input).parent().closest(".upload_file").remove();
	});
	
	return true;
}
