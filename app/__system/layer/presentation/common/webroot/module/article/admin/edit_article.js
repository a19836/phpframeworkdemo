$(function () {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var main_elm = $(".module_edit");
	
	main_elm.find(".submit_button_add, .submit_button_save").children("input").attr("onclick", "return saveArticle();");
	
	var src = main_elm.find(".photo_url img").attr("src");
	if (typeof src !== typeof undefined && src !== false && src != "") {
		main_elm.find(".photo_url").show();
	}
	
	var article_summary = main_elm.find(".summary").first();
	var summary_editor = article_summary.data("editor");
	var summary_textarea = article_summary.find("textarea")[0];
	if (!summary_editor && summary_textarea) {
		var summary_editor = createBodyCodeEditor(summary_textarea, 150);
		article_summary.data("editor", summary_editor);
	}
	
	var article_content = main_elm.find(".content").first();
	var content_editor = article_content.data("editor");
	var content_textarea = article_content.find("textarea")[0];
	if (!content_editor && content_textarea) {
		var content_editor = createBodyCodeEditor(content_textarea, 300);
		article_content.data("editor", content_editor);
	}
	
	MyFancyPopup.hidePopup();
});

function createBodyCodeEditor(textarea, height) {
	var editor = CKEDITOR.replace(textarea, {
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
	
	return editor;
}

function saveArticle() {
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var main_elm = $(".module_edit");
	
	var summary_editor = main_elm.find(".summary").data("editor");
	var content_editor = main_elm.find(".content").data("editor");
	
	var summary = summary_editor ? summary_editor.getData() : "";
	var content = content_editor ? content_editor.getData() : "";
	
	main_elm.find(".summary").find("textarea").first().val(summary);
	main_elm.find(".content").find("textarea").first().val(content);
	
	return true;
}

function deletePhoto(a) {
	var article_current_photo = $(a).parent();
	article_current_photo.find("img").attr("src", "");
	article_current_photo.hide();
	
	article_current_photo.parent().children(".photo_id").find("input").val("");
}
