var timeout_id = null;
var saved_str_id = null;

$(function () {
	$(window).unbind('beforeunload').bind('beforeunload', function () {
		var str = getActiveEditorValue();
		var new_str_id = $.md5(str);
	
		if (saved_str_id != new_str_id) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	//prepare auto_save
	$(".top_bar .save a").attr("onclick", "saveEchoStrSettings(this);");
	
	//prepare ui
	var echostr_settings = $(".echostr_settings");
	
	createLayoutUIEditor( echostr_settings.find(" > #layoutui_content > textarea")[0] );
	createTinyMCEEditor( echostr_settings.find(" > #tinymce_content > textarea")[0] );
	createCKEditor( echostr_settings.find(" > #ckeditor_content > textarea")[0] );
	
	echostr_settings.tabs();
	
	//select layout view. Needs to be inside of settimeout otherwise the layout ui editor will not be inited correctly
	setTimeout(function() {
		var luie = echostr_settings.find("#layoutui_content > .layout-ui-editor");
		
		//show view layout panel instead of code
		var view_layout = luie.find(" > .tabs > .view-layout");
		view_layout.addClass("do-not-confirm");
		view_layout.trigger("click");
		view_layout.removeClass("do-not-confirm");
		
		//show php widgets, borders and background
		var PtlLayoutUIEditor = luie.data("LayoutUIEditor");
		
		if (PtlLayoutUIEditor) {
			PtlLayoutUIEditor.showTemplateWidgetsDroppableBackground();
			PtlLayoutUIEditor.showTemplateWidgetsBorders();
			PtlLayoutUIEditor.showTemplatePHPWidgets();
		}
	}, 500);
	
	$(window).resize(function() {
		clearTimeout(timeout_id);
		
		timeout_id = setTimeout(function() {
			resizeTinyMCEEditor();
			resizeCKEditor();
		}, 500);
	});
});

function onToggleFullScreen(in_full_screen) {
	setTimeout(function() {
		var main_obj = $(".echostr_settings > #layoutui_content");
		var PtlLayoutUIEditor = main_obj.find(".layout-ui-editor").data("LayoutUIEditor");
		var menu_settings = PtlLayoutUIEditor.getMenuSettings();
		
		if (menu_settings.is(":visible"))
			PtlLayoutUIEditor.showFixedMenuSettings();
		
		PtlLayoutUIEditor.MyTextSelection.refreshMenu();
	}, 500);
}

function loadEchoStrBlockSettings(settings_elm, settings_values) {
	//console.log(settings_values);
	var echostr_settings = settings_elm.children(".echostr_settings");
	
	var tasks_values = convertBlockSettingsValuesIntoBasicArray(settings_values);
	var value = prepareFieldValueIfValueTypeIsString(tasks_values["str"]);
	
	echostr_settings.children("textarea.module_settings_property").val(value);
	echostr_settings.find(" > .editor > textarea").val(value);
	
	//set save str id
	saved_str_id = $.md5(value);
}

function createLayoutUIEditor(textarea) {
	if (textarea) {
		var parent = $(textarea).parent();
		
		if (typeof LayoutUIEditor == "function") {
			var ui = parent.children(".layout-ui-editor");
			
			if (!ui[0]) {
				ui = $('<div class="layout-ui-editor reverse fixed-side-properties hide-template-widgets-options"><ul class="menu-widgets"></ul><div class="template-source"></div></div>');
				parent.append(ui);
			}
			else if (ui.data("LayoutUIEditor")) 
				return ui.data("LayoutUIEditor");
			
			var mwb = parent.children(".ui-menu-widgets-backup");
			ui.children(".menu-widgets").append( mwb.contents() );
			mwb.remove();
			
			ui.children(".template-source").append(textarea);
			
			var ptl_ui_creator_var_name = "PTLLayoutUIEditor_" + Math.floor(Math.random() * 1000);
			var PtlLayoutUIEditor = new LayoutUIEditor();
			PtlLayoutUIEditor.options.ui_element = ui;
			PtlLayoutUIEditor.options.template_source_editor_save_func = saveEchoStrSettings;
			PtlLayoutUIEditor.options.on_choose_page_url_func = typeof onIncludePageUrlTaskChooseFile == "function" ? onIncludePageUrlTaskChooseFile : null;
			PtlLayoutUIEditor.options.on_choose_image_url_func = typeof onIncludeImageUrlTaskChooseFile == "function" ? onIncludeImageUrlTaskChooseFile : null;
			PtlLayoutUIEditor.options.on_ready_func = function() {
				if (typeof LayoutUIEditorFormFieldUtil == "function") {
					var LayoutUIEditorFormFieldUtilObj = new LayoutUIEditorFormFieldUtil(PtlLayoutUIEditor);
					LayoutUIEditorFormFieldUtilObj.initFormFieldsSettings();
				}
				
				var luie = PtlLayoutUIEditor.getUI();
        			var options = luie.children(".options");
        			
        			//prepare full-screen option
				options.find(".full-screen").click(function() {
        				if (luie.hasClass("full-screen"))
        					openFullscreen(ui[0]);
        				else
        					closeFullscreen();
        			});
			};
			window[ptl_ui_creator_var_name] = PtlLayoutUIEditor;
			PtlLayoutUIEditor.init(ptl_ui_creator_var_name);
			
			var editor = ui.children(".template-source").data("editor");
			parent.data("editor", editor);
		}
		else {
			ace.require("ace/ext/language_tools");
			var editor = ace.edit(textarea);
			editor.setTheme("ace/theme/chrome");
			editor.session.setMode("ace/mode/php");
			editor.setAutoScrollEditorIntoView(true);
			editor.setOption("minLines", 5);
			editor.setOptions({
				enableBasicAutocompletion: true,
				enableSnippets: true,
				enableLiveAutocompletion: false,
			});
			editor.setOption("wrap", true);
			
			parent.find("textarea.ace_text-input").removeClass("ace_text-input"); //fixing problem with scroll up, where when focused or pressed key inside editor the page scrolls to top.

			
			parent.data("editor", editor);
		}
	}
}

function createTinyMCEEditor(textarea) {
	if (textarea) {
		textarea = $(textarea);
		var parent = textarea.parent();
		
		var toolbar = 'bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent | fontselect fontsizeselect | formatselect | forecolor backcolor | bullist numlist | link unlink nonbreaking emoticons';
		
		var tinymce_opts = {
			//theme: 'modern',
			plugins: [
				'advlist anchor autolink autoresize link image lists charmap preview hr pagebreak',
				'searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking pagebreak',
				'table contextmenu directionality emoticons paste textcolor colorpicker textpattern',
				'fullscreen imagetools noneditable template',
				//disabled plugins: 'template spellchecker example example_dependency print save autosave bbcode codesample legacyoutput importcss layer tabfocus toc fullpage',
			], 
			toolbar: toolbar, 
			menubar: true,
			toolbar_items_size: 'small',
			image_title: false, // disable title field in the Image dialog
			image_description: false,// disable title field in the Image dialog
			convert_urls: false,// disable convert urls for images and other urls, this is, the url that the user inserts is the url that will be in the HTML.
			inline: false, //makes tinymce inline. It doesn't work if the main element is a textarea.
		}
		
		textarea.tinymce(tinymce_opts);
	}
}

function createCKEditor(textarea) {
	if (textarea) {
		textarea = $(textarea);
		
		var toolbar = [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat','-','NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl','-','Link','Unlink'/*,'Anchor'*/,'-','Image','Flash','Embed','Table','HorizontalRule'/*,'Smiley','SpecialChar','PageBreak','Iframe'*/,'-',/*'Styles',*/'Format','Font','FontSize','TextColor','BGColor','-','Blockquote','CreateDiv','-'/*,'About'*/,'-','Templates','ShowBlocks','Maximize','Source' ];
		
		var ckeditor_opts = {
			toolbar: 'Full',
			toolbar_Full: [
				//{ name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
				//{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
				//{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
				//{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
				//'/',
				//{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
				//{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent'/*,'-','Blockquote','CreateDiv'*/,
				//'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
				//{ name: 'links', items : [ 'Link','Unlink',/*'Anchor'*/ ] },
				//{ name: 'insert', items : [ 'Image','Flash','EmbedSemantic','Table','HorizontalRule',/*'Smiley','SpecialChar','PageBreak','Iframe'*/ ] },
				//'/',
				//{ name: 'styles', items : [ /*'Styles',*/'Format','Font','FontSize' ] },
				//{ name: 'colors', items : [ 'TextColor','BGColor' ] },
				//{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] },
				//{ name: 'source', items : [ 'Templates','ShowBlocks','Maximize','Source' ] },
				{ name: 'mybar', items : toolbar },
			],
			image_previewText: ' ',
			removePlugins: 'image2',
			//extraPlugins: 'image2,embed',
			//codeSnippet_theme: 'monokai_sublime',
		};
		
		var editor = CKEDITOR.replace(textarea[0], ckeditor_opts);
		textarea.parent().data("editor", editor);
		
		CKEDITOR.config.removeDialogTabs = 'link:upload;image:Upload';
	}
}

function resizeTinyMCEEditor() {
	setTimeout(function() {
		var parent = $(".echostr_settings > #tinymce_content");
		var editor_elm = parent.children(".mce-tinymce");
		
		if (!editor_elm.hasClass("mce-fullscreen")) {
			var editor_body_elm = editor_elm.children(".mce-container-body");
			var toolbar_height = editor_body_elm.height() - editor_body_elm.children(".mce-edit-area").height();
			var height = parent.height() - toolbar_height;
			
			tinymce.activeEditor.theme.resizeTo(null, height);
		}
	}, 500);
}

function resizeCKEditor() {
	setTimeout(function() {
		var parent = $(".echostr_settings > #ckeditor_content");
		var editor_body_elm = parent.find(" > .cke > .cke_inner");
		var toolbar_height = editor_body_elm.height() - editor_body_elm.children(".cke_contents").height();
		var height = parent.height() - toolbar_height;
		
		CKEDITOR.instances.editor1.resize(CKEDITOR.instances.editor1.width, height, true);
	}, 500);
}

function getActiveEditorValue() {
	var echostr_settings = $(".echostr_settings");
	var active_tab = echostr_settings.tabs('option', 'active');
	var str = "";
	
	if (active_tab == 0) {
		var editor_elm = echostr_settings.children("#layoutui_content");
		var PtlLayoutUIEditor = editor_elm.find(".layout-ui-editor").data("LayoutUIEditor");
		
		if (PtlLayoutUIEditor) {
			//converts visual into code if visual tab is selected
			var is_template_layout_tab_show = PtlLayoutUIEditor.isTemplateLayoutShown();
			
			if (is_template_layout_tab_show)
				PtlLayoutUIEditor.convertTemplateLayoutToSource();
			
			//PtlLayoutUIEditor.forceTemplateSourceConversionAutomatically(); //Be sure that the template source is selected
			str = PtlLayoutUIEditor.getTemplateSourceEditorValue();
		}
		else {
			var editor = editor_elm.data("editor");
			str = editor ? editor.getValue() : editor_elm.children("textarea").val();
		}
	}
	else if (active_tab == 1) {
		var editor_elm = echostr_settings.children("#tinymce_content");
		var id = editor_elm.children(".mce-tinymce").attr("id");
		str = tinymce.get(id) ? tinymce.get(id).getContent() : editor_elm.children("textarea").val();
	}
	else if (active_tab == 2) {
		var editor_elm = echostr_settings.children("#ckeditor_content");
		var editor = editor_elm.data("editor");
		str = editor ? editor.getData() : editor_elm.children("textarea").val();
	}
	
	return str;
}

function saveEchoStrSettings() {
	prepareAutoSaveVars();
	
	var active_tab = $(".echostr_settings").tabs('option', 'active');
	var str = getActiveEditorValue();
	var new_str_id = $.md5(str);
	
	if (!saved_str_id || saved_str_id != new_str_id) {
		if (active_tab == 0) { //is layout ui editor
			if (!is_from_auto_save) {
				MyFancyPopup.showOverlay();
				MyFancyPopup.showLoading();
			}
			
			$.ajax({
				type : "post",
				url : create_echostr_settings_code_url,
				data : {str: str},
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					if (data && data["code"]) {
						if (saveBlockRawCode(data["code"]))
							saved_str_id = new_str_id; //set new saved_str_id
					}
					else if (!is_from_auto_save)
						StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
					
					if (!is_from_auto_save)
						MyFancyPopup.hidePopup();
					else
						resetAutoSave();
				},
				error : function() { 
					if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
						showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_echostr_settings_code_url, function() {
							saveEchoStrSettings();
						});
					else if (!is_from_auto_save)
						StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
					
					if (!is_from_auto_save)
						MyFancyPopup.hidePopup();
					else
						resetAutoSave();
				},
			});
		}
		else {
			$(".echostr_settings > textarea.module_settings_property").val(str);
			
			if (saveBlock())
				saved_str_id = new_str_id; //set new saved_str_id
			
			if (is_from_auto_save)
				resetAutoSave();
		}
	}
	else if (!is_from_auto_save)
		StatusMessageHandler.showMessage("Nothing to save.");
	else
		resetAutoSave();
}
