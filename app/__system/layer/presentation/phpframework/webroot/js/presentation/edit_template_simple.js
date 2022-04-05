var saved_template_obj_id = null;
var MyFancyPopupTemplatePreview = new MyFancyPopupClass();

$(function () {
	$(window).bind('beforeunload', function () {
		if (isTemplateCodeObjChanged()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	//prepare top_bar
	$(".code_editor_body > .code_menu").addClass("top_bar_menu");
	$(".code_editor_body > .layout_ui_editor").addClass("with_top_bar_menu");
	
	//init trees
	choosePropertyVariableFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForVariables,
	});
	choosePropertyVariableFromFileManagerTree.init("choose_property_variable_from_file_manager .class_prop_var");
	
	chooseMethodFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForMethods,
	});
	chooseMethodFromFileManagerTree.init("choose_method_from_file_manager");
	
	chooseFunctionFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForFunctions,
	});
	chooseFunctionFromFileManagerTree.init("choose_function_from_file_manager");
	
	chooseFileFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndFunctionsFromTree,
	});
	chooseFileFromFileManagerTree.init("choose_file_from_file_manager");
	
	choosePresentationFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePresentationFromFileManagerTree.init("choose_presentation_from_file_manager");
	
	choosePageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePageUrlFromFileManagerTree.init("choose_page_url_from_file_manager");
	
	chooseImageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotAPossibleImageFromTree,
	});
	chooseImageUrlFromFileManagerTree.init("choose_image_url_from_file_manager");
	
	createChoosePresentationIncludeFromFileManagerTree();
	
	//init ui
	var template_obj = $(".template_obj");
	
	if (template_obj[0]) {
		template_obj.tabs({active: 1});
		
		var textarea = template_obj.find(".head textarea")[0];
		if (textarea)
			createCodeEditor(textarea, {save_func: saveTemplate, no_height: true});
		
		//init ui layout editor
		initCodeLayoutUIEditor(template_obj, {
			save_func: saveTemplate, 
			ready_func: function() {
				//show view layout panel instead of code
				var view_layout = luie.find(" > .tabs > .view-layout");
				view_layout.addClass("do-not-confirm");
				view_layout.trigger("click");
				view_layout.removeClass("do-not-confirm");
				
				//show php widgets
				luie.find(" > .template-widgets-options .show-php input").attr("checked", "checked").prop("checked", true).trigger("click").attr("checked", "checked").prop("checked", true);
			},
		});
		
		var luie = template_obj.find(".code_editor_body > .layout_ui_editor");
		var PtlLayoutUIEditor = luie.data("LayoutUIEditor");
		
		if (PtlLayoutUIEditor)
			PtlLayoutUIEditor.options.on_panels_resize_func = onResizeCodeLayoutUIEditorWithRightContainer;
		
		//init page template layout
		initPageAndTemplateLayout(template_obj, template_obj, function() {
			update_settings_from_layout_iframe_func = function() {
				updateCodeEditorSettingsFromLayout(template_obj);
			};
			update_layout_iframe_from_settings_func = function() {
				updateCodeEditorLayoutFromSettings(template_obj, false, true);
			};
			
			//waits until the load params and joinpoints gets loaded
			setTimeout(function() {
				//set saved_template_obj_id
				saved_template_obj_id = getTemplateCodeObjId();//only for testing. Then uncomment this line!
				
				//init auto save
				addAutoSaveMenu(".top_bar li.dummy_elm_to_add_auto_save_options");
				enableAutoSave(onToggleAutoSave);//only for testing. Then activate auto_save here by uncommenting this line!
				initAutoSave(".top_bar li.save a");
			}, 2000);
		});
	}
	
	MyFancyPopup.hidePopup();
});

//To be used in the toggleFullScreen function
function onToggleFullScreen(in_full_screen) {
	var template_obj = $(".template_obj");
	onToggleCodeEditorFullScreen(in_full_screen, template_obj);
	
	setTimeout(function() {
		var top = parseInt(template_obj.find(".regions_blocks_includes_settings").css("top"));
		
		resizeSettingsPanel(template_obj, top);
	}, 500);
}

function toggleContentEditor(elm) {
	var template_obj = $(".template_obj");
	var top_bar = $(".top_bar");
	var input = top_bar.find("header ul li.toggle_content_editor > a input");
	
	template_obj.toggleClass("content_editor_shown");
	top_bar.toggleClass("content_editor_shown");
	
	if (template_obj.hasClass("content_editor_shown"))
		input.prop("checked", true).attr("checked", "checked");
	else
		input.prop("checked", false).removeAttr("checked");
}

function preview() {
	//prepare popup
	preparePreviewPopup();
	
	//get popup
	var popup= $(".template_obj > #preview");
	
	//open popup
	MyFancyPopupTemplatePreview.init({
		elementToShow: popup,
		parentElement: document,
	});
	
	MyFancyPopupTemplatePreview.showPopup();
}

function preparePreviewPopup() {
	var code = getTemplateCodeForSaving();
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
			var msg = "Couldn't preview template. Error in preparePreviewPopup() function. Please try again...";
			alert(msg);
			
			if (jqXHR.responseText)
				StatusMessageHandler.showError(msg + "\n" + jqXHR.responseText);
		},
	});
}

function getTemplateHeadEditorCode() {
	var editor = $(".template_obj .code_editor_settings .head").data("editor");
	return editor ? editor.getValue() : $(".template_obj .code_editor_settings .head textarea").first().val();
}

function getTemplateBodyEditorCode() {
	var code_editor_body = $(".template_obj .code_editor_body");
	var PtlLayoutUIEditor = code_editor_body.find(".layout_ui_editor").data("LayoutUIEditor");
	
	if (PtlLayoutUIEditor) {
		var luie = PtlLayoutUIEditor.getUI();
		luie.find(" > .options .option.show-full-source").removeClass("option-active"); //remove active class so the getCodeLayoutUIEditorCode calls the getTemplateSourceEditorValue method, instead of getTemplateFullSourceEditorValue
	}
	
	var code = getCodeLayoutUIEditorCode(code_editor_body);
	
	return code;
}

function updateCodeEditorLayoutFromMainTab(elm) {
	var template_obj = $(".template_obj");
	var selected_tab = template_obj.children("ul").find("li.ui-tabs-selected, li.ui-tabs-active").first();
	
	if (selected_tab.attr("id") == "code_editor_body") {
		updateCodeEditorLayoutFromSettings(template_obj, true, false);
	}
}

function updateCodeEditorLayoutFromSettings(template_obj, reload_iframe, do_not_update_from_body_editor) {
	if (template_obj[0]) {
		var regions_blocks_includes_settings = template_obj.find(".regions_blocks_includes_settings");
		
		//must happen before the updateRegionsFromBodyEditor
		var orig_template_params_values_list = JSON.stringify(template_params_values_list);
		
		updateRegionsBlocksRBIndexIfNotSet(regions_blocks_includes_settings); //very important, otherwise we will loose the region-block params-values and joinpoints for the new regions added dynamically
		
		//very important, otherwise we will loose the region-block params-values and joinpoints
		updateRegionsBlocksParamsLatestValues(regions_blocks_includes_settings); 
		updateRegionsBlocksJoinPointsSettingsLatestObjs(regions_blocks_includes_settings);
		
		//updates the last regions and params. This is very important. otherwise the template preview won't show the latest regions and params from the html.
		if (!do_not_update_from_body_editor)
			updateRegionsFromBodyEditor(false, true);
		
		if (!template_obj.hasClass("inactive")) {
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
				
				if (reload_iframe)
					reloadLayoutIframeFromSettings(iframe, iframe_data, current_html);
				else
					updateLayoutIframeFromSettings(iframe, iframe_data, current_html);
			}
		}
	}
}

function updateCodeEditorSettingsFromLayout(template_obj) {
	//updates the last regions and params. This is very important. otherwise the template preview won't show the latest regions and params from the html.
	updateRegionsFromBodyEditor(false, true);
	
	if (!template_obj.hasClass("inactive")) {
		var iframe = template_obj.find(".code_editor_layout iframe");
		var regions_blocks_includes_settings = template_obj.find(".regions_blocks_includes_settings");
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

function updateRegionsFromBodyEditor(show_info_message, do_not_update_layout) {
	//save synchronization functions
	var update_settings_from_layout_iframe_func_bkp = update_settings_from_layout_iframe_func;
	var update_layout_iframe_from_settings_func_bkp = update_layout_iframe_from_settings_func;
	
	//disable synchronization functions bc the updateSelectedTemplateRegionsBlocks calls the sync func, when it triggers the on change event from the blok_type in the getRegionBlockHtml
	update_settings_from_layout_iframe_func = null;
	update_layout_iframe_from_settings_func = null;
	
	//update regions blocks
	var regions_blocks_includes_settings = $(".template_obj .regions_blocks_includes_settings");
	
	var settings = getSettingsTemplateRegionsBlocks(regions_blocks_includes_settings);
	regions_blocks_list = settings["regions_blocks"];
	template_params_values_list = settings["params"]; //global var
	
	var regions = getCurrentCodeRegions();
	var params = getCurrentCodeParams();
	
	updateSelectedTemplateRegionsBlocks(regions_blocks_includes_settings, {
		"regions": regions,
		"params": params,
	});
	
	//sets back synchronization functions
	update_settings_from_layout_iframe_func = update_settings_from_layout_iframe_func_bkp;
	update_layout_iframe_from_settings_func = update_layout_iframe_from_settings_func_bkp;
	
	if (!do_not_update_layout)
		updateLayoutIframeFromSettingsField(); //then reload the layout again
	
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
	prepareAutoSaveVars();
	
	var template_obj = $(".template_obj");
	
	//save synchronization function
	var update_settings_from_layout_iframe_func_bkp = update_settings_from_layout_iframe_func;
	var update_layout_iframe_from_settings_func_bkp = update_layout_iframe_from_settings_func;
	
	//disable synchronization function
	update_settings_from_layout_iframe_func = null;
	update_layout_iframe_from_settings_func = null;
	
	//detect selected tab is layout
	/*var active_tab = template_obj.tabs('option', 'active');
	if (active_tab == 0) {
		updateCodeEditorSettingsFromLayout(template_obj);
	}*/
	
	//preparing new code
	var status = true;
	var code = "";
	
	if (!is_from_auto_save) {
		MyFancyPopup.init({
			parentElement: window,
		});
		MyFancyPopup.showOverlay();
		MyFancyPopup.showLoading();
	}
	
	if (!without_regions_blocks_and_includes) {
		//update settings from body
		updateRegionsFromBodyEditor(false, true);
		
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
				
				if (jqXHR.responseText && !is_from_auto_save) {
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
	
	//sets back synchronization function
	update_settings_from_layout_iframe_func = update_settings_from_layout_iframe_func_bkp;
	update_layout_iframe_from_settings_func = update_layout_iframe_from_settings_func_bkp;
	
	return status ? code : null;
}

function getTemplateCodeObjId() {
	var obj_1 = getTemplateCodeForSaving(true);
	var obj_2 = getRegionsBlocksAndIncludesObjToSave();
	
	MyFancyPopup.hidePopup();
	
	return $.md5(save_object_url + JSON.stringify(obj_1) + JSON.stringify(obj_2));
}

function isTemplateCodeObjChanged() {
	var template_obj = $(".template_obj");
	
	if (!template_obj[0])
		return false;
	
	var new_saved_template_obj_id = getTemplateCodeObjId();
	
	return saved_template_obj_id != new_saved_template_obj_id;
}

function saveTemplate() {
	var template_obj = $(".template_obj");
	
	prepareAutoSaveVars();
	
	if (template_obj[0]) {
		var new_saved_template_obj_id = getTemplateCodeObjId();
		
		if (!saved_template_obj_id || saved_template_obj_id != new_saved_template_obj_id) {
			if (!is_from_auto_save) {
				var save_btn = $(".top_bar ul li.save a");
				var on_click = save_btn.attr("onClick");
				save_btn.addClass("loading").removeAttr("onClick");
				
				var scroll_top = $(document).scrollTop();
			}
			
			var code = getTemplateCodeForSaving();
			
			//detect selected tab is layout
			/* This is already executed in the getTemplateCodeForSaving method
			var template_obj = $(".template_obj");
			var active_tab = template_obj.tabs('option', 'active');
			if (active_tab == 0) {
				updateCodeEditorLayoutFromSettings(template_obj, false, true);
			}*/
			
			if (code != null) {
				var obj = {"code": code};
				
				saveObjCode(save_object_url, obj, {
					success: function(data, textStatus, jqXHR) {
						if (!is_from_auto_save) {
							save_btn.removeClass("loading").attr("onClick", on_click);
							$(document).scrollTop(scroll_top);
							MyFancyPopup.hidePopup();
						
							if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
								showAjaxLoginPopup(jquery_native_xhr_object.responseURL, save_object_url, function() {
									jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
									StatusMessageHandler.removeLastShownMessage("error");
									
									saveTemplate();
								});
							else
								StatusMessageHandler.removeLastShownMessage("error");
						}
						else
							resetAutoSave();
						
						saved_template_obj_id = getTemplateCodeObjId();
						
						return true;
					},
					error: function(jqXHR, textStatus, errorThrown) {
						if (!is_from_auto_save) {
							save_btn.removeClass("loading").attr("onClick", on_click);
							$(document).scrollTop(scroll_top);
							MyFancyPopup.hidePopup();
						}
						else
							resetAutoSave();
						
						return true;
					},
				});
			}
			else if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL)) {
				if (!is_from_auto_save) {
					MyFancyPopup.hidePopup();
					
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, [create_entity_code_url, jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url], function() {
						jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
						StatusMessageHandler.removeLastShownMessage("error");
						
						saveTemplate();
					});
				}
				else
					resetAutoSave();
			}
			else if (!is_from_auto_save) {
				StatusMessageHandler.showError("Error trying to save this file. Please try again...");
				MyFancyPopup.hidePopup();
				save_btn.removeClass("loading").attr("onClick", on_click);
			}
			else
				resetAutoSave();
		}
		else if (!is_from_auto_save)
			StatusMessageHandler.showMessage("Nothing to save.");
		else
			resetAutoSave();
	}
	else if (!is_from_auto_save)
		alert("No template object to save! Please contact the sysadmin...");
}
