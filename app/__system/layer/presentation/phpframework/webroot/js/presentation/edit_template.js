$(function () {
	/*$(window).bind('beforeunload', function () {
		if (window.parent && window.parent.iframe_overlay)
			window.parent.iframe_overlay.hide();
		
		return "Changes you made may not be saved. Click cancel to save them first, otherwise to continue...";
	});*/
	
	choosePropertyVariableFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForVariables,
	});
	choosePropertyVariableFromFileManagerTree.init("choose_property_variable_from_file_manager .class_prop_var");
	
	chooseMethodFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForMethods,
	});
	chooseMethodFromFileManagerTree.init("choose_method_from_file_manager");
	
	chooseFunctionFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForFunctions,
	});
	chooseFunctionFromFileManagerTree.init("choose_function_from_file_manager");
	
	chooseFileFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndFunctionsFromTree,
	});
	chooseFileFromFileManagerTree.init("choose_file_from_file_manager");
	
	choosePresentationFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePresentationFromFileManagerTree.init("choose_presentation_from_file_manager");
	
	choosePageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePageUrlFromFileManagerTree.init("choose_page_url_from_file_manager");
	
	chooseImageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotAPossibleImageFromTree,
	});
	chooseImageUrlFromFileManagerTree.init("choose_image_url_from_file_manager");
	
	createChoosePresentationIncludeFromFileManagerTree();
	
	var template_obj = $(".template_obj");
	var code_editor = template_obj.find("#code_editor");
	
	template_obj.tabs();
	code_editor.tabs();
	
	var textarea = code_editor.find(".head textarea")[0];
	if (textarea)
		createCodeEditor(textarea, {save_func: saveTemplate, no_height: true});
	
	initCodeLayoutUIEditor(template_obj, saveTemplate);
	
	onLoadTaskFlowChartAndCodeEditor();
	
	initPageAndTemplateLayout(template_obj, code_editor.find(".code_editor_contents").first());
	
	//if simple code, shows the raw code editor
	if (!is_body_code_valid) {
		template_obj.tabs('option', 'active', 1);
		
		onClickCodeRawEditorTab( template_obj.find("#raw_editor_tab")[0] );
	}
});

//I think this is not used anymore.
function createBodyCodeEditor(textarea, options) {
	if (textarea) {
		var parent = $(textarea).parent();
		
		if (typeof LayoutUIEditor == "function") {
			var ui = parent.children(".layout_ui_editor");
			
			if (!ui[0]) {
				ui = $('<div class="layout_ui_editor"><ul class="menu-widgets"></ul><div class="template-source"></div></div>');
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
			PtlLayoutUIEditor.options.template_preview_html_url = template_preview_html_url;
			PtlLayoutUIEditor.options.template_source_editor_save_func = options && options.save_func ? options.save_func : null;
			
			if (typeof onIncludePageUrlTaskChooseFile == "function")
				PtlLayoutUIEditor.options.on_choose_page_url_func = function(elm) {
					onIncludePageUrlTaskChooseFile(elm);
					MyFancyPopup.settings.is_code_html_base = true;
				}
			
			if (typeof onIncludeImageUrlTaskChooseFile == "function")
				PtlLayoutUIEditor.options.on_choose_image_url_func = function(elm) {
					onIncludeImageUrlTaskChooseFile(elm);
					MyFancyPopup.settings.is_code_html_base = true;
				}
			
			PtlLayoutUIEditor.options.on_ready_func = function() {
				if (typeof LayoutUIEditorFormFieldUtil == "function") {
					var LayoutUIEditorFormFieldUtilObj = new LayoutUIEditorFormFieldUtil(PtlLayoutUIEditor);
					LayoutUIEditorFormFieldUtilObj.initFormFieldsSettings();
				}
			};
			window[ptl_ui_creator_var_name] = PtlLayoutUIEditor;
			PtlLayoutUIEditor.init(ptl_ui_creator_var_name);
			
			var editor = ui.children(".template-source").data("editor");
			parent.data("editor", editor);
		}
	}
}

/* DEPRECATED bc the CKEDITOR DOESN'T WORK WELL WITH PHP AND PTL CODE 
function createBodyCodeEditor(textarea) {
	var editor = CKEDITOR.replace(textarea, {
		toolbarGroups: [
			{ name: "document", groups: [ "mode", "document", "doctools" ] },
			{ name: "clipboard", groups: [ "clipboard", "undo" ] },
			{ name: "editing", groups: [ "find", "selection" ] },
			{ name: "forms" },
			"/",
			{ name: "basicstyles", groups: [ "basicstyles", "cleanup" ] },
			{ name: "paragraph", groups: [ "list", "indent", "blocks", "align", "bidi" ] },
			{ name: "links" },
			{ name: "insert" },
			"/",
			{ name: "styles" },
			{ name: "colors" },
			{ name: "tools" },
			{ name: "others" },
		],
		codeSnippet_theme: "monokai_sublime",
		//fullPage: true,
		//allowedContent: true,
		//filebrowserImageBrowseUrl: '...',//disabled
		//filebrowserImageUploadUrl: '...',//disabled
		height: 400,
	});
	
	editor.on('blur', function() {
		updateRegionsFromBodyEditor();
	});
	
	CKEDITOR.config.removeDialogTabs = 'link:upload;image:Upload';
	
	CKEDITOR.plugins.registered['save'] = {
		init: function(editor) {
			var command = editor.addCommand(
				'save', 
				{
					modes: {wysiwyg: 1, source: 1},
					exec: function (editor) {
						saveTemplate();
					}
				}
			);
			
			editor.ui.addButton('Save', {
				label: 'Save', 
				command: 'save',
				toolbar: 'document,1',
    			});
		}
	}
	
	return editor;
}*/

function onClickCodeRawEditorTab(elm) {
	elm = $(elm);
	
	$("#code textarea, #code .ace_editor").css("height", getCodeEditorHeight($("#code")) + "px");
	
	if (elm.attr("is_init") != "1") {
		elm.attr("is_init", "1");
		
		createCodeEditor( $("#code textarea")[0], {save_func: saveTemplate});
	}
	
	onClickCodeEditorTab(elm[0]);
	
	if (confirm("Do you wish to create a new code based in the settings of the Simple-Editor tab?")) {
		var code = getTemplateCodeForSaving();
		MyFancyPopup.hidePopup();
		
		setEditorCodeRawValue(code);
	}
}

function onClickPreviewTab(elm) {
	elm = $(elm);
	
	var code = getTemplateCodeForSaving();
	//console.log(code);
	MyFancyPopup.hidePopup();
	
	var iframe = $(".template_obj > #preview > iframe");
	var url = iframe.attr("orig_src");
	var iframe_head = iframe.contents().find("head");
	var iframe_body = iframe.contents().find("body");
	
	iframe_head.html("");
	iframe_body.html("");
	
	$.ajax({
		url: url,
		type: 'post',
		processData: false,
		contentType: 'text/html',
		data: code,
		success: function(parsed_html, textStatus, jqXHR) {
			//iframe.contents().find("html").html(parsed_html);
			var head_html = "";
			var body_html = "";
			var body_attributes = [];
			//console.log(parsed_html);
			
			if (parsed_html) {
				if (parsed_html.indexOf("<head") == -1 && parsed_html.indexOf("<body") == -1)
					body_html = parsed_html;
				else {
					head_html = getTemplateHtmlTagContent(parsed_html, "head");
					body_html = getTemplateHtmlTagContent(parsed_html, "body");
					
					body_attributes = getTemplateHtmlTagAttributes(parsed_html, "body");
				}
			}
			
			//console.log(head_html);
			iframe_head[0].innerHTML = head_html; //Do not use .html(head_html), bc in some cases it breaks
			iframe_body[0].innerHTML = body_html; //Do not use .html(body_html), bc in some cases it breaks
			
			//set body attributes
			if (body_attributes)
				$.each(body_attributes, function(idx, attr) {
					iframe_body.attr(attr["name"], attr["value"]);
				});
		},
		error: function (jqXHR, textStatus, errorThrown) {
			var msg = "Couldn't preview template. Error in onClickPreviewTab() function. Please try again...";
			alert(msg);
			
			if (jqXHR.responseText)
				StatusMessageHandler.showError(msg + "\n" + jqXHR.responseText);
		},
	});
}

function getTemplateHeadEditorCode() {
	var editor = $(".template_obj #code_editor .head").data("editor");
	return editor ? editor.getValue() : $(".template_obj #code_editor .head textarea").first().val();
}

function getTemplateBodyEditorCode() {
	var tb = $(".template_obj #code_editor .body");
	var ptl_layout_ui_editor = tb.find(".layout_ui_editor").data("LayoutUIEditor");
	
	if (ptl_layout_ui_editor) {
		var luie = ptl_layout_ui_editor.getUI();
		var tabs = luie.children(".tabs");
        	var is_view_layout = tabs.children(".tab.tab-active").is(".view-layout");
        	
		ptl_layout_ui_editor.forceTemplateSourceConversionAutomatically(); //Be sure that the template source is selected
		var code = ptl_layout_ui_editor.getTemplateSourceEditorValue();
		
		if (is_view_layout)
			ptl_layout_ui_editor.clickViewLayoutTabWithoutSourceConversion();
        	
        	return code;
	}
	
	var editor = tb.data("editor");
	return editor ? editor.getValue() : tb.find(" > textarea, > .layout_ui_editor > .template-source > textarea").first().val();
}

function updateCodeEditorLayoutFromMainTab(elm) {
	var tabs = $(".template_obj #code_editor .code_editor_contents");
	active_tab = tabs.tabs('option', 'active');
	
	if (active_tab == 0) {
		var code_editor_layout_tab = tabs.find(" > .tabs > li > a").first();
		updateCodeEditorLayoutFromSettings(code_editor_layout_tab);
	}
	else
		updateRegionsFromBodyEditor(false); //updates the last regions and params.
}

function updateCodeEditorLayoutFromSettings(elm) {
	elm = $(elm);
	var template_obj = $(".template_obj");
	var regions_blocks_includes_settings = template_obj.find("#code_editor .regions_blocks_includes_settings");
	
	//must happen before the updateRegionsFromBodyEditor
	var orig_template_params_values_list = JSON.stringify(template_params_values_list);
	
	updateRegionsBlocksRBIndexIfNotSet(regions_blocks_includes_settings); //very important, otherwise we will loose the region-block params-values and joinpoints for the new regions added dynamically
	
	//very important, otherwise we will loose the region-block params-values and joinpoints
	updateRegionsBlocksParamsLatestValues(regions_blocks_includes_settings); 
	updateRegionsBlocksJoinPointsSettingsLatestObjs(regions_blocks_includes_settings);
	
	//updates the last regions and params. This is very important. otherwise the template preview won't show the latest regions and params from the html.
	updateRegionsFromBodyEditor(false);
	
	if (!elm.hasClass("inactive")) {
		var iframe = template_obj.find(".code_editor_layout iframe");
		var are_different = areLayoutAndSettingsDifferent(iframe, regions_blocks_includes_settings);
		
		//get regions and params from settings
		var data = getSettingsTemplateRegionsBlocks(regions_blocks_includes_settings);
		//console.log(data);
		
		if (!are_different)
			are_different = orig_template_params_values_list != JSON.stringify(data["params"]);
		
		if (are_different /*&& confirm("Do you wish to convert the template settings to the layout panel?\n\nNote: You must save the html code first, in order to see the new changes that you made (if you made any).")*/) {
			//update regions_blocks_list
			regions_blocks_list = data["regions_blocks"];
			
			//update template_params_values_list
			template_params_values_list = data["params"];
			
			//prepare iframe with new data
			var iframe = template_obj.find(".code_editor_layout iframe");
			var iframe_data = {
				"template_regions" : data["template_regions"],
				"template_params": data["params"],
				"template_includes": data["includes"]
			};
			
			//show template preview based in the html source
			var current_html = getTemplateCodeForSaving(true);
			MyFancyPopup.hidePopup();
			
			updateLayoutIframeFromSettings(iframe, iframe_data, current_html);
		}
	}
}

function updateCodeEditorSettingsFromLayout(elm) {
	elm = $(elm);
	
	//updates the last regions and params. This is very important. otherwise the template preview won't show the latest regions and params from the html.
	updateRegionsFromBodyEditor(false);
	
	if (!elm.hasClass("inactive")) {
		var template_obj = $(".template_obj");
		var iframe = template_obj.find(".code_editor_layout iframe");
		var regions_blocks_includes_settings = template_obj.find("#code_editor .regions_blocks_includes_settings");
		var are_different = areLayoutAndSettingsDifferent(iframe, regions_blocks_includes_settings);
		
		if (are_different /*&& confirm("Do you wish to convert the template regions to the settings panel?\n\nNote: You must save the html code first, in order to see the new changes that you made (if you made any).")*/) {
			var data = getIframeTemplateRegionsBlocksForSettings(iframe, regions_blocks_includes_settings);
			
			regions_blocks_list = data["regions_blocks_list"]; //global var
			template_params_values_list = data["template_params_values_list"]; //global var
			
			updateSelectedTemplateRegionsBlocks(regions_blocks_includes_settings, {
				regions: data["regions"], 
				params: data["params"], 
			});
		}
	}
}

function updateRegionsFromBodyEditor(show_info_message) {
	var regions_blocks_includes_settings = $(".template_obj #code_editor .regions_blocks_includes_settings");
	
	var settings = getSettingsTemplateRegionsBlocks(regions_blocks_includes_settings);
	regions_blocks_list = settings["regions_blocks"];
	template_params_values_list = settings["params"]; //global var
	
	var regions = getCurrentCodeRegions();
	var params = getCurrentCodeParams();
	
	updateSelectedTemplateRegionsBlocks(regions_blocks_includes_settings, {
		"regions": regions,
		"params": params,
	});
	
	if (show_info_message && regions.length == 0)
		StatusMessageHandler.showMessage("No regions to be updated...");
}

function getCurrentCodeRegions() {
	var head_code = getTemplateHeadEditorCode();
	var body_code = getTemplateBodyEditorCode();
	
	var code = "<html><head>" + head_code + "</head><body>" + body_code + "</body></html>";
	code = code.replace(/&gt;/gi, ">").replace(/&lt;/gi, "<");
	
	var matches = code.match(/\$([^\$]+)([ ]*)->([ ]*)renderRegion([ ]*)\(([ ]*)([^\)]*)([ ]*)\)/gi);
	var regions = [];
	
	if (matches) {
		for (var i = 0; i < matches.length; i++) {
			var m = matches[i];
			m = m.substring(m.lastIndexOf("(") + 1, m.lastIndexOf(")")).trim().replace(/'/g, '"');
		
			if (regions.indexOf(m) == -1)
				regions.push(m);
		}
	}
	
	return regions;
}

function getCurrentCodeParams() {
	var head_code = getTemplateHeadEditorCode();
	var body_code = getTemplateBodyEditorCode();
	
	var code = "<html><head>" + head_code + "</head><body>" + body_code + "</body></html>";
	code = code.replace(/&gt;/gi, ">").replace(/&lt;/gi, "<");
	
	var matches = code.match(/\$([^\$]+)([ ]*)->([ ]*)getParam([ ]*)\(([ ]*)([^\)]*)([ ]*)\)/gi);
	var params = [];
	if (matches) {
		for (var i = 0; i < matches.length; i++) {
			var m = matches[i];
			m = m.substring(m.lastIndexOf("(") + 1, m.lastIndexOf(")")).trim().replace(/'/g, '"');
			
			if (params.indexOf(m) == -1)
				params.push(m);
		}
	}
	
	return params;
}

function getTemplateCodeForSaving(without_regions_blocks_and_includes) {
	var template_obj = $(".template_obj");
	
	//detect selected tab is layout
	var active_tab = template_obj.tabs('option', 'active');
	if (active_tab == 0) {
		var tabs = template_obj.find(" > #code_editor .code_editor_contents");
		active_tab = tabs.tabs('option', 'active');
		
		if (active_tab == 0) { //it means the layout tab is selected and it must be the settings tab
			var tab = $( tabs.find(" > .tabs > li")[1] );
			updateCodeEditorSettingsFromLayout( tab.children("a") );
		}
	}
	
	//update settings from body
	updateRegionsFromBodyEditor(false);
	
	var status = true;
	var code = "";
	var code_editor_tab = template_obj.find("#code_editor_tab");
	
	if (code_editor_tab.hasClass("ui-tabs-selected") || code_editor_tab.hasClass("ui-tabs-active")) {
		MyFancyPopup.init({
			parentElement: window,
		});
		MyFancyPopup.showOverlay();
		MyFancyPopup.showLoading();
		
		if (!without_regions_blocks_and_includes) {
			var obj = getRegionsBlocksAndIncludesObjToSave();
			$.ajax({
				type : "post",
				url : create_entity_code_url,
				data : {"object" : obj},
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					code = data ? data + "\n" : "";
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					status = false;
					
					if (jqXHR.responseText) {
						if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
							StatusMessageHandler.showError("Please Login first!");
						else 
							StatusMessageHandler.showError(jqXHR.responseText);
					}
				},
				async: false,
			});
		}
			
		var head_code = getTemplateHeadEditorCode();
		var body_code = getTemplateBodyEditorCode();
		
		code += doc_type_tag_attributes_str ? '<!DOCTYPE ' + doc_type_tag_attributes_str + '>' + "\n" : '';
		code += (html_tag_attributes_str ? '<html ' + html_tag_attributes_str + '>' : '<html>') + "\n";
		code += (head_tag_attributes_str ? '<head ' + head_tag_attributes_str + '>' : "<head>") + "\n";
		code += head_code;
		code += "\n</head>\n";
		code += (body_tag_attributes_str ? '<body ' + body_tag_attributes_str + '>' : "<body>") + "\n";
		code += body_code;
		code += "\n</body>\n";
		code += '</html>';
	}
	else
		code = getCodeForSaving(template_obj);
	
	return status ? code : null;
}

function saveTemplate() {
	var template_obj = $(".template_obj");
	var save_btn = template_obj.find(" > #code_editor > .buttons input");
	save_btn.addClass("loading").attr("disabled", "disabled");
	
	var scroll_top = $(document).scrollTop();
	var code = getTemplateCodeForSaving();
	
	//detect selected tab is layout
	/* This is already executed in the getTemplateCodeForSaving method
	var active_tab = template_obj.tabs('option', 'active');
	if (active_tab == 0) {
		var tabs = template_obj.find(" > #code_editor .code_editor_contents");
		active_tab = tabs.tabs('option', 'active');
		
		if (active_tab == 0) { //it means the layout tab is selected and it must be udated with the new regions
			var tab = $( tabs.find(" > .tabs > li")[0] );
			updateCodeEditorLayoutFromSettings( tab.children("a") );
		}
	}*/
	
	if (code != null) {
		var obj = {"code": code};
		
		saveObjCode(save_object_url, obj, {
			success: function(data, textStatus, jqXHR) {
				save_btn.removeAttr("disabled").removeClass("loading");
				$(document).scrollTop(scroll_top);
				
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, save_object_url, function() {
						jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
						StatusMessageHandler.removeLastShownMessage("error");
						saveTemplate();
					});
				else
					StatusMessageHandler.removeLastShownMessage("error");
				
				return true;
			},
			error: function(jqXHR, textStatus, errorThrown) {
				save_btn.removeAttr("disabled").removeClass("loading");
				$(document).scrollTop(scroll_top);
				
				return true;
			},
		});
	}
	else if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
		showAjaxLoginPopup(jquery_native_xhr_object.responseURL, [create_entity_code_url, jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url], function() {
			jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
			StatusMessageHandler.removeLastShownMessage("error");
			
			saveTemplate();
		});
	else {
		StatusMessageHandler.showError("Error trying to save this file. Please try again...");
		MyFancyPopup.hidePopup();
		$(".workflow_menu").show();
		save_btn.removeAttr("disabled").removeClass("loading");
	}
}
