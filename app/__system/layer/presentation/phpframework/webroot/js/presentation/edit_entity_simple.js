var show_popup_interval_id = null;

$(function () {
	/*$(window).bind('beforeunload', function () {
		if (window.parent && window.parent.iframe_overlay)
			window.parent.iframe_overlay.hide();
		
		return "Changes you made may not be saved. Click cancel to save them first, otherwise to continue...";
	});*/
	
	createChoosePresentationIncludeFromFileManagerTree();
	
	choosePropertyVariableFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForVariables,
	});
	choosePropertyVariableFromFileManagerTree.init("choose_property_variable_from_file_manager .class_prop_var");
	
	chooseBlockFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotBlocksFromTree,
	});
	chooseBlockFromFileManagerTree.init("choose_block_from_file_manager");
	
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
	
	/*setTimeout(function(){
		$(".invalid").first().remove();
	}, 20000);*/
	
	var entity_obj = $(".entity_obj");
	initPageAndTemplateLayout(entity_obj, entity_obj.find(".entity_obj_tabs"));
	
	if (!code_exists)
		onChooseAvailableTemplate( entity_obj.find(".template .search")[0], show_templates_only ); //open template popup automatically if entity is new
	
	MyFancyPopup.hidePopup();
});

function onChangeTemplate(elm) {
	elm = $(elm);
	elm.attr("title", elm.val());
	
	//update template layout ui
	updateTemplateLayout(elm.parent().parent());
}

function onChangeTemplateGenre(elm) {
	elm = $(elm);
	var is_external_template = elm.val() ? true : false;
	var p = elm.parent();
	var entity_obj = p.parent();
	var select = p.children("select[name=template]");
	var template_search_icon = p.children(".search");
	var external_template_params = entity_obj.children(".external_template_params");
	var template_value = null;
	
	if (!is_external_template) {
		template_value = select.val();
		
		select.show();
		template_search_icon.show();
		external_template_params.hide();
		
		//update template layout ui
		updateTemplateLayout(entity_obj);
	}
	else {
		select.hide();
		template_search_icon.hide();
		external_template_params.show();
		
		onChangeExternalTemplateType( external_template_params.find(".external_template_type select")[0] );
	}
}

function onChooseAvailableTemplate(elm, show_templates_only) {
	var template_elm = $(elm).parent();
	
	chooseAvailableTemplate( template_elm.children("select[name=template]")[0], {
		show_templates_only: show_templates_only,
		on_select: function(selected_template) {
			if (!code_exists) { //only if file is new
				var entity_obj_elm = template_elm.parent();
				var entity_obj_tabs = entity_obj_elm.children(".entity_obj_tabs");
				var active_tab = entity_obj_tabs.tabs('option', 'active');
				
				if (active_tab == 1) {
					entity_obj_tabs.tabs('option', 'active', 0);
					
					var tabs = entity_obj_tabs.children(".tabs");
					updateLayoutFromSettings( tabs.children().first().children("a")[0] );
				}
			}
		}
	} );
}

function onChangeExternalTemplateType(elm) {
	elm = $(elm);
	var external_template_type = elm.val();
	var external_template_params = elm.parent().parent();
	
	external_template_params.children(":not(.external_template_type)").hide();
	
	if (external_template_type == "block")
		external_template_params.children(".block_param").show();
	else if (external_template_type == "wordpress_template")
		external_template_params.children(".wordpress_template_param").show();
	else if (external_template_type == "url")
		external_template_params.children(".url_param").show();
	
	//update template layout ui
	updateTemplateLayout( external_template_params.parent() );
}

function onChooseBlockTemplate(elm) {
	elm = $(elm);
	var p = elm.parent();
	var popup = $("#choose_block_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		
		targetField: p,
		updateFunction: chooseBlockTemplate
	});

	MyFancyPopup.showPopup();
}

function chooseBlockTemplate(elm) {
	var node = chooseBlockFromFileManagerTree.getSelectedNodes();
	node = node[0];
	
	if (node) {
		var a = $(node).children("a");
		var file_path = a.attr("file_path");
		var bean_name = a.attr("bean_name");
		var pos = file_path ? file_path.indexOf("/src/block/") : -1;
		
		if (file_path && pos != -1) {
			var project_path = getNodeProjectPath(node);
			project_path = project_path && project_path.substr(project_path.length - 1) == "/" ? project_path.substr(0, project_path.length - 1) : project_path;
			project_path = project_path == selected_project_id ? "" : project_path;
			
			var block_path = file_path.substr(pos + 11);//11 == /src/block/
			block_path = block_path.substr(block_path.length - 4, 1) == "." ? block_path.substr(0, block_path.lastIndexOf(".")) : block_path;
			
			var p = MyFancyPopup.settings.targetField;
			p.children("input").val(block_path);
			p.parent().find(".block_project_id input").val(project_path);
			
			//update template layout ui
			updateTemplateLayout( p.parent().parent() );
			
			MyFancyPopup.hidePopup();
		}
		else
			alert("invalid selected file.\nPlease choose a valid file.");
	}
}

function onBlurExternalTemplate(elm) {
	//update template layout ui
	updateTemplateLayout( $(elm).parent().closest(".entity_obj") );
}

function updateTemplateLayout(entity_obj) {
	var iframe = entity_obj.find(".entity_template_layout iframe");
	var template = getSelectedTemplate(entity_obj);
	var is_template_ok = template ? true : false;
	
	if (template == "parse_php_code") {
		var external_template_params = entity_obj.children(".external_template_params");
		var external_template_type = external_template_params.find(" > .external_template_type select").val();
		
		if (external_template_type == "block" && external_template_params.find(" > .block_id input").val() == "")
			is_template_ok = false;
		else if (external_template_type == "url" && external_template_params.find(" > .url input").val() == "")
			is_template_ok = false;
		else if (external_template_type == "")
			is_template_ok = false;
		
	}
	
	if (is_template_ok) {
		var is_external_template = entity_obj.find(".template select[name=template_genre]").val() ? 1 : 0;
		var external_template_params = getExternalSetTemplateParams(entity_obj);
		
		var url = get_template_regions_and_params_url.replace(/#template#/g, template) + "&is_external_template=" + is_external_template + "&external_template_params=" + encodeURIComponent(JSON.stringify(external_template_params));
		
		$.ajax({
			type : "get",
			url : url,
			dataType : "json",
			success : function(data, textStatus, jqXHR) {
				//console.log(data);
				
				//update regions_blocks_list and template_params_values_list
				var settings = getSettingsTemplateRegionsBlocks( entity_obj.find(".entity_template_settings") );
				regions_blocks_list = settings["regions_blocks"]; //global var
				template_params_values_list = settings["params"]; //global var
				var template_includes = settings["includes"];
				
				//update settings with data
				updateSelectedTemplateRegionsBlocks(entity_obj, data);
				
				//show iframe with new regions and params
				var regions = data["regions"];
				var template_regions = {};
				
				if (regions)
					for (var i in regions) {
						var region = regions[i];
						template_regions[region] = "";
						
						for (var j in regions_blocks_list) {
							var rbl = regions_blocks_list[j];
							
							if (rbl[0] == region) {
								if (!$.isArray(template_regions[region]))
									template_regions[region] = [];
								
								template_regions[region].push(rbl);
							}
						}
					}
				
				updateLayoutIframeFromSettings(iframe, {
					"template": template,
					"template_regions" : template_regions,
					"template_params": template_params_values_list,
					"template_includes": template_includes,
					"is_external_template": is_external_template,
					"external_template_params": external_template_params,
				});
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jqXHR.responseText);
					StatusMessageHandler.showError(jqXHR.responseText);
			}
		});
	}
	else {
		updateSelectedTemplateRegionsBlocks(entity_obj, null);
		updateLayoutIframeFromSettings(iframe, {"template": ""});
	}
}

function updateLayoutFromSettings(elm) {
	elm = $(elm);
	
	if (!elm.hasClass("inactive")) {
		var orig_template_params_values_list = JSON.stringify(template_params_values_list);
		
		var entity_obj = $(".entity_obj");
		var iframe = entity_obj.find(".entity_template_layout iframe");
		var regions_blocks_includes_settings = entity_obj.find(".regions_blocks_includes_settings");
		
		updateRegionsBlocksRBIndexIfNotSet(regions_blocks_includes_settings); //very important, otherwise we will loose the region-block params-values and joinpoints for the new regions added dynamically
		
		//very important, otherwise we will loose the region-block params-values and joinpoints
		updateRegionsBlocksParamsLatestValues(regions_blocks_includes_settings); 
		updateRegionsBlocksJoinPointsSettingsLatestObjs(regions_blocks_includes_settings);
		
		var are_different = areLayoutAndSettingsDifferent(iframe, regions_blocks_includes_settings);
		var data = getSettingsTemplateRegionsBlocks(regions_blocks_includes_settings);
		
		if (!are_different)
			are_different = orig_template_params_values_list != JSON.stringify(data["params"]);
		
		if (are_different /*&& confirm("Do you wish to convert the template settings to the layout panel?")*/) {
			var template = getSelectedTemplate(entity_obj);
			var iframe = entity_obj.find(".entity_template_layout iframe");
			
			updateLayoutIframeFromSettings(iframe, {
				"template": template,
				"template_regions" : data["template_regions"],
				"template_params": data["params"],
				"template_includes": data["includes"],
				"is_external_template": entity_obj.find(" > .template > select[name=template_genre]").val() ? 1 : 0,
				"external_template_params": getExternalSetTemplateParams(entity_obj)
			});
			
			//update regions_blocks_list
			regions_blocks_list = data["regions_blocks"];
			
			//update template_params_values_list
			template_params_values_list = data["params"];
		}
	}
}

function getSelectedTemplate(entity_obj) {
	var template = "";
	var is_external_template = entity_obj.find(" > .template > select[name=template_genre]").val() ? true : false;
	
	if (is_external_template) 
		template = "parse_php_code";
	else {
		template = entity_obj.find(" > .template select[name=template]").val();
		template = template ? template : layer_default_template;
	}
	
	return template;
}

function updateSettingsFromLayout(elm) {
	elm = $(elm);
	
	if (!elm.hasClass("inactive")) {
		var entity_obj = $(".entity_obj");
		var iframe = entity_obj.find(".entity_template_layout iframe");
		var regions_blocks_includes_settings = entity_obj.find(".regions_blocks_includes_settings");
		var are_different = areLayoutAndSettingsDifferent(iframe, regions_blocks_includes_settings);
		
		if (are_different /*&& confirm("Do you wish to convert the template regions to the settings panel?")*/) {
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

function confirmSave(opts) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var obj = getObjToSave();
	
	$.ajax({
		type : "post",
		url : create_entity_code_url,
		data : {"object" : obj},
		dataType : "json",
		success : function(data, textStatus, jqXHR) {
			var old_code = $(".current_entity_code").text();
			
			showConfirmationCodePopup(old_code, data, {
				save: function() {
					save(opts);
					
					return true;
				},
			});
			
			MyFancyPopup.hidePopup();
		},
		error : function(jqXHR, textStatus, errorThrown) { 
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_entity_code_url, function() {
					StatusMessageHandler.removeLastShownMessage("error");
					confirmSave(opts);
				});
			else {
				var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
				StatusMessageHandler.showError("Error trying to save new changes.\nPlease try again..." + msg);
			}
			
			MyFancyPopup.hidePopup();
		},
	});
}

function save(opts) {
	var save_btns = $(".entity_obj > .buttons input");
	save_btns.attr("disabled", "disabled").hide();
	
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var obj = getObjToSave();
	
	saveObj(save_object_url, obj, {
		success: function(data, textStatus, jqXHR) {
			if (opts && typeof opts["success"] == "function")
				opts["success"]();
			
			save_btns.removeAttr("disabled").show();
			MyFancyPopup.hidePopup();
			
			return true;
		},
		error: function(jqXHR, textStatus, errorThrown) {
			save_btns.removeAttr("disabled").show();
			MyFancyPopup.hidePopup();
			
			return true;
		},
	});
}

function saveAndPreview(confirm_save) {
	var opts = {
		success: function() {
			//open popup with preview
			//setTimeout very important bc if confirmSave, after this success function be executed the MyFancyPopup will be hided.
			setTimeout(function() {
				preview();
			}, 300);
		}
	};
	
	if (confirm_save) 
		confirmSave(opts);
	else
		save(opts);
}

function preview() {
	if (page_preview_url) {
		//get popup
		var popup= $(".page_preview_popup");
		
		if (!popup[0]) {
			popup = $('<div class="myfancypopup page_preview_popup"></div>');
			$(document.body).append(popup);
		}
		
		popup.html('<iframe></iframe>'); //cleans the iframe so we don't see the previous html
		popup.children("iframe").attr("src", page_preview_url);
		
		//open popup
		MyFancyPopup.init({
			elementToShow: popup,
			parentElement: document,
		});
		
		MyFancyPopup.showPopup();
	}
}

function getExternalSetTemplateParams(entity_obj) {
	var is_external_template = entity_obj.find(" > .template select[name=template_genre]").val() ? true : false;
	
	//prepare template params
	var external_template_params = entity_obj.children(".external_template_params");
	var external_template_type = external_template_params.find(".external_template_type select").val();
	var template_args = [];
	
	if (is_external_template && external_template_type) {
		template_args.push({
			"key": "project_id",
			"key_type": "string",
			"value": "$EVC->getCommonProjectName()",
			"value_type": "method",
		});
		
		$.each( external_template_params.find(".external_template_type, ." + external_template_type + "_param").find("input, select, textarea"), function(idx, input) {
			input = $(input);
			var input_type = input.attr("type");
			var input_name = input.attr("name");
			
			if (input_name) {
				var input_value = null;
				
				if (input_type == "checkbox" || input_type == "radio")
					input_value = input.is(":checked") ? 1 : 0;
				else
					input_value = input.val();
				
				template_args.push({
					"key": input_name,
					"key_type": "string",
					"value": input_value,
					"value_type": $.isNumeric(input_value) ? "" : "string",
				});
			}		
		});
	}
	
	return template_args;
}

function getExternalTemplateParams(entity_obj) {
	var is_external_template = entity_obj.find(" > .template select[name=template_genre]").val() ? true : false;
	
	//prepare template params
	var external_template_params = entity_obj.children(".external_template_params");
	var external_template_type = external_template_params.find(".external_template_type select").val();
	var template_args = {};
	
	if (is_external_template && external_template_type) {
		template_args["project_id"] = '$EVC->getCommonProjectName()';
		
		$.each( external_template_params.find(".external_template_type, ." + external_template_type + "_param").find("input, select, textarea"), function(idx, input) {
			input = $(input);
			var input_type = input.attr("type");
			var input_name = input.attr("name");
			
			if (input_name) {
				if (input_type == "checkbox" || input_type == "radio")
					template_args[input_name] = input.is(":checked") ? 1 : 0;
				else
					template_args[input_name] = input.val();
			}		
		});
	}
	
	return template_args;
}

function getObjToSave() {
	var entity_obj = $(".entity_obj");
	
	//detect selected tab is layout
	var tabs = entity_obj.find(".entity_obj_tabs");
	var active_tab = tabs.tabs('option', 'active');
	if (active_tab == 0) {
		var tab = $( tabs.find(" > .tabs > li")[1] );
		updateSettingsFromLayout( tab.children("a") );
	}
	
	var obj = getRegionsBlocksAndIncludesObjToSave();
	//console.log(obj);
	
	//prepare template
	var template_elm = entity_obj.find(".template");
	var is_external_template = template_elm.find("select[name=template_genre]").val() ? true : false;
	var template = getSelectedTemplate(entity_obj);
	template = !is_external_template && template == layer_default_template ? "" : template;
	
	//prepare template params
	var template_args = getExternalTemplateParams(entity_obj);
	
	if (template) {
		obj["templates"] = [
			{
				"template": template,
				"template_type": "string",
				"template_args": template_args,
			},
		];
	}
	
	return obj;
}
