$(function () {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var main_elm = $(".module_edit");
	
	main_elm.find(".submit_button_add, .submit_button_save").children("input").attr("onclick", "return saveEvent();");
	
	var src = main_elm.find(".photo_url img").attr("src");
	if (typeof src !== typeof undefined && src !== false && src != "") {
		main_elm.find(".photo_url").show();
	}
	
	var event_description = main_elm.find(".description").first();
	var description_editor = event_description.data("editor");
	var description_textarea = event_description.find("textarea")[0];
	if (!description_editor && description_textarea) {
		var description_editor = createBodyCodeEditor(description_textarea, 300);
		event_description.data("editor", description_editor);
	}
	
	MyFancyPopup.hidePopup();
});

function createBodyCodeEditor(textarea, height) {
	var editor = null;
	
	if (typeof CKEDITOR != "undefined") {
		editor = CKEDITOR.replace(textarea, {
			toolbarGroups: [
				{ name: "my_bar", groups: [ "mode", "undo", "basicstyles", "cleanup", "list", "indent", "blocks", "align", "bidi", "links", "forms", "insert" ] },
				{ name: "insert", groups: [ "styles", "colors", "tools", "others" ] },
			],
			codeSnippet_theme: "monokai_sublime",
			//fullPage: true,
			//allowedContent: true,
			//filebrowserImageBrowseUrl: '...',//disabled
			//filebrowserImageUploadUrl: '...',//disabled
			height: height,
		});
		
		CKEDITOR.config.removeDialogTabs = 'link:upload;image:Upload';
	}
	
	return editor;
}

function saveEvent() {
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var main_elm = $(".module_edit");
	
	var description_editor = main_elm.find(".description").data("editor");
	var description = description_editor ? description_editor.getData() : "";
	main_elm.find(".description").find("textarea").first().val(description);
	
	return true;
}

function deletePhoto(a) {
	var event_current_photo = $(a).parent();
	event_current_photo.find("img").attr("src", "");
	event_current_photo.hide();
	
	event_current_photo.parent().children(".photo_id").find("input").val("");
}
