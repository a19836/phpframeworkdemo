//$(function () {
window.addEventListener("load", function() {
	//var main_elm = $(".module_edit_event");
	var main_elms = document.querySelectorAll(".module_edit_event");
	
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
			
			if (description_ckeditor_active) {
				//var event_description = main_elm.find(".description").first();
				var event_description = main_elm.querySelector(".description");
				
				if (event_description) {
					//var description_editor = event_description.data("editor");
					var description_editor = event_description.editor;
					
					//var description_textarea = event_description.find("textarea")[0];
					var description_textarea = event_description.querySelector("textarea");
					
					if (!style_type && !description_editor && description_textarea) {
						var description_editor = createBodyCodeEditor(description_textarea, 300, description_ckeditor_configs, description_upload_url);
						
						//event_description.data("editor", description_editor);
						event_description.editor = description_editor;
					}
				}
			}
			
			//Save button needs to have this action, otherwise (in the insert event screen - when the text-fields are empty) the summary and content may give an alert message because are empty and may not be allowed empty values... Leave this code here please - Copied from article.js but Not tested!!!
			/*main_elm.find(".buttons .button_save input").click(function() {
				return saveEvent();
			});*/
			var inputs = main_elm.querySelectorAll(".buttons .button_save input");
			
			for (var j = 0; j < inputs.length; j++) {
				var input = inputs[j];
				
				input.addEventListener("click", function() {
					return saveEvent();
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

function saveEvent() {
	//var main_elm = $(".module_edit_event");
	var main_elms = document.querySelectorAll(".module_edit_article");
	
	if (main_elms)
		for (var i = 0; i < main_elms.length; i++) {
			var main_elm = main_elms[i];
		
			//Ckeditor already does it for us, but the only does it, after our form check runs, which means our formcheck will see the summary and content fields empty. This means that we must leave this here!
			if (description_ckeditor_active) {
				//var description_editor = main_elm.find(".description").data("editor");
				var description = main_elm.querySelector(".description");
				var description_editor = description ? description.editor : null;
				
				if (description_editor) {
					description_editor.updateElement();
					//updateElement does the samething then: 
					//main_elm.find(".description").find("textarea").first().val( description_editor.getData() );
				}
			}
		}
	
	return true;
}

function deletePhoto(a) {
	//var event_current_photo = $(a).parent();
	var event_current_photo = a.parentNode;
	
	//event_current_photo.find("img").attr("src", "");
	var img = event_current_photo.querySelector("img");
	
	if (img)
		img.setAttribute("src", "");
	
	//event_current_photo.hide();
	event_current_photo.style.display = "none";
	
	//event_current_photo.parent().find(" > .photo_id input").val("");
	var input = event_current_photo.parentNode.querySelector(".photo_id input");
	
	if (input)
		input.value = "";
}
