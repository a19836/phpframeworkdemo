//$(function () {
window.addEventListener("load", function() {
	//var main_elm = $(".module_edit_article");
	var main_elms = document.querySelectorAll(".module_edit_article");
	
	if (main_elms)
		for (var i = 0; i < main_elms.length; i++) {
			var main_elm = main_elms[i];
			
			//show photo_url if image src
			//var src = main_elm.find(".photo_url img").attr("src");
			var photo_url = main_elm.querySelector(".photo_url");
			
			if (photo_url) {
				var img = photo_url.querySelector("img");
				var src = img ? img.getAttribute("src") : null;
				
				if (typeof src !== typeof undefined && src !== false && src != "" && src != 0 && src) {
					//main_elm.find(".photo_url").show();
					photo_url.style.display = "block";
				}
			}
			
			if (typeof initAttachments == "function") { //on delete this function is not loaded
				//var tbody = main_elm.find(".module_edit_object_attachments .attachments table tbody");
				var tbody = main_elm.querySelector(".module_edit_object_attachments .attachments table tbody");
				
				if (tbody)
					initAttachments(tbody);
			}
			
			if (summary_ckeditor_active) {
				//var article_summary = main_elm.find(".summary").first();
				var article_summary = main_elm.querySelector(".summary");
				
				if (article_summary) {
					//var summary_editor = article_summary.data("editor");
					var summary_editor = article_summary.editor;
					
					//var summary_textarea = article_summary.find("textarea")[0];
					var summary_textarea = article_summary.querySelector("textarea");
					
					if (!style_type && !summary_editor && summary_textarea) {
						var summary_editor = createBodyCodeEditor(summary_textarea, 150, summary_ckeditor_configs, summary_upload_url);
						
						//article_summary.data("editor", summary_editor);
						article_summary.editor = summary_editor;
					}
				}
			}
			
			if (content_ckeditor_active) {
				//var article_content = main_elm.find(".content").first();
				var article_content = main_elm.querySelector(".content");
				
				if (article_content) {
					//var content_editor = article_content.data("editor");
					var content_editor = article_content.editor;
					
					//var content_textarea = article_content.find("textarea")[0];
					var content_textarea = article_content.querySelector("textarea");
					
					if (!style_type && !content_editor && content_textarea) {
						var content_editor = createBodyCodeEditor(content_textarea, 300, content_ckeditor_configs, content_upload_url);
						
						//article_content.data("editor", content_editor);
						article_content.editor = content_editor;
					}
				}
			}
			
			//Save button needs to have this action, otherwise (in the insert article screen - when the text-fields are empty) the summary and content may give an alert message because are empty and may not be allowed empty values... Leave this code here please!!!
			/*main_elm.find(".buttons .button_save input").click(function() {
				return saveArticle();
			});*/
			var inputs = main_elm.querySelectorAll(".buttons .button_save input");
			
			for (var j = 0; j < inputs.length; j++) {
				var input = inputs[j];
				
				input.addEventListener("click", function() {
					return saveArticle();
				});
			}
		}
});

function createBodyCodeEditor(textarea, height, editor_configs, upload_url) {
	var editor = null;
	
	if (typeof CKEDITOR != "undefined") {
		if (!editor_configs)
			editor_configs = {
				toolbarGroups: [
					{ name: "my_bar", groups: [ "mode", "undo", "basicstyles", "cleanup", "list", "indent", "blocks", "align", "bidi", "links", "forms", "insert" ] },
					{ name: "insert", groups: [ "styles", "colors", "tools", "others" ] },
				],
				codeSnippet_theme: "monokai_sublime",
				//fullPage: true,
				//allowedContent: true,
				//filebrowserImageBrowseUrl: '...',//disabled
				//filebrowserImageUploadUrl:  '...',//disabled
				height: height,
			};
		
		if (upload_url)
			editor_configs.filebrowserImageUploadUrl = upload_url;
		
		editor = CKEDITOR.replace(textarea, editor_configs);
		
		if (!upload_url)
			CKEDITOR.config.removeDialogTabs = 'link:upload;image:Upload';
	}
	
	return editor;
}

function saveArticle() {
	//var main_elm = $(".module_edit_article");
	var main_elms = document.querySelectorAll(".module_edit_article");
	
	if (main_elms)
		for (var i = 0; i < main_elms.length; i++) {
			var main_elm = main_elms[i];
			
			//Ckeditor already does it for us, but the only does it, after our form check runs, which means our formcheck will see the summary and content fields empty. This means that we must leave this here!
			if (summary_ckeditor_active) {
				//var summary_editor = main_elm.find(".summary").data("editor");
				var summary = main_elm.querySelector(".summary");
				var summary_editor = summary ? summary.editor : null;
				
				if (summary_editor) {
					summary_editor.updateElement();
					//updateElement does the samething then: 
					//main_elm.find(".summary").find("textarea").first().val( summary_editor.getData() );
				}
			}
			
			if (content_ckeditor_active) {
				//var content_editor = main_elm.find(".content").data("editor");
				var content = main_elm.querySelector(".content");
				var content_editor = content ? content.editor : null;
				
				if (content_editor) {
					content_editor.updateElement();
					//updateElement does the samething then: 
					//main_elm.find(".content").find("textarea").first().val( content_editor.getData() );
				}
			}
		}
		
	return true;
}

function deletePhoto(a) {
	//var article_current_photo = $(a).parent();
	var article_current_photo = a.parentNode;
	
	//article_current_photo.find("img").attr("src", "");
	var img = article_current_photo.querySelector("img");
	
	if (img)
		img.setAttribute("src", "");
	
	//article_current_photo.hide();
	article_current_photo.style.display = "none";
	
	//article_current_photo.parent().find(" > .photo_id input").val("");
	var input = article_current_photo.parentNode.querySelector(".photo_id input");
	
	if (input)
		input.value = "";
}
