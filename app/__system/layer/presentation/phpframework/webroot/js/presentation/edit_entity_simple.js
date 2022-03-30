var show_popup_interval_id = null;
var chooseProjectTemplateUrlFromFileManagerTree = null; //used by the create_presentation_uis_diagram.js and module/menu/show_menu/settings.js and others

$(function () {
	$(window).bind('beforeunload', function () {
		if (isEntityCodeObjChanged()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	//init auto save
	addAutoSaveMenu(".top_bar li.dummy_elm_to_add_auto_save_options");
	enableAutoSave(onToggleAutoSave); //only for testing
	initAutoSave(".top_bar li.save a");
	
	//init trees
	createChoosePresentationIncludeFromFileManagerTree();
	
	choosePropertyVariableFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_chils_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForVariables,
	});
	choosePropertyVariableFromFileManagerTree.init("choose_property_variable_from_file_manager .class_prop_var");
	
	chooseBlockFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_chils_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotBlocksFromTree,
	});
	chooseBlockFromFileManagerTree.init("choose_block_from_file_manager");
	
	choosePageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_chils_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePageUrlFromFileManagerTree.init("choose_page_url_from_file_manager");
	
	chooseImageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_chils_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotAPossibleImageFromTree,
	});
	chooseImageUrlFromFileManagerTree.init("choose_image_url_from_file_manager");
	
	chooseProjectTemplateUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_chils_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotTemplatesFromTree,
	});
	chooseProjectTemplateUrlFromFileManagerTree.init("choose_project_template_url_from_file_manager");
	
	//init ui
	var entity_obj = $(".entity_obj");
	
	if (entity_obj[0]) {
		initPageAndTemplateLayout(entity_obj, entity_obj.find(".entity_obj_tabs"), function() {
			update_settings_from_layout_iframe_func = function() {
				updateSettingsFromLayout(entity_obj);
			};
			update_layout_iframe_from_settings_func = function() {
				updateLayoutFromSettings(entity_obj, false);
			};
			
			/*setTimeout(function(){
				$(".invalid").first().remove();
			}, 20000);*/
		});
		
		if (!code_exists)
			onChooseAvailableTemplate( entity_obj.find(".template .search")[0], show_templates_only ); //open template popup automatically if entity is new
		
		//set saved_obj_id
		saved_obj_id = getEntityCodeObjId();
	}
	
	MyFancyPopup.hidePopup();
});

//To be used in the toggleFullScreen function
function onToggleFullScreen(in_full_screen) {
	setTimeout(function() {
		var entity_obj = $(".entity_obj");
		var top = parseInt(entity_obj.find(".regions_blocks_includes_settings").css("top"));
		
		resizeSettingsPanel(entity_obj, top);
	}, 500);
}

function removeAllThatIsNotTemplatesFromTree(ul, data) {
	ul = $(ul);
	
	ul.find("i.file, i.entity_file, i.view_file, i.util_file, i.controller_file, i.config_file, i.undefined_file, i.js_file, i.css_file, i.img_file, i.properties, i.block_file, i.module_file, .entities_folder, .views_folder, .utils_folder, .webroot_folder, .modules_folder, .blocks_folder, .configs_folder").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
	
	ul.find("i.folder").each(function(idx, elm){
		var label = $(elm).parent().children("label").text();
		
		if (label == "pages (entities)" || label == "views" || label == "utils" || label == "webroot" || label == "others" || label == "modules" || label == "blocks" || label == "configs") 
			$(elm).parent().parent().remove();
		//else if (label == "templates") 
		//	$(elm).parent().parent().addClass("jstree-last");
	});
	
	//move templates to project node
	ul.find("i.templates_folder").each(function(idx, elm) {
		var templates_li = $(elm).parent().parent();
		var templates_ul = templates_li.children("ul");
		var project_li = templates_li.parent().parent();
		var project_ul = project_li.children("ul");
		
		project_li.append(templates_ul);
		project_ul.remove();
	});
}

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
	var external_template_params = entity_obj.children(".external_template_params");
	var template_value = null;
	
	if (!is_external_template) {
		entity_obj.removeClass("is_external_template");
		template_value = select.val();
		
		select.show();
		external_template_params.hide();
		
		//update template layout ui
		updateTemplateLayout(entity_obj);
	}
	else {
		entity_obj.addClass("is_external_template");
		select.hide();
		external_template_params.show();
		
		onChangeExternalTemplateType( external_template_params.find(".external_template_type select")[0] );
	}
}

function onChooseAvailableTemplate(elm, show_templates_only) {
	var template_elm = $(elm).parent();
	var entity_obj_elm = template_elm.parent();
	var func = function(selected_template) {
		if (!code_exists) { //only if file is new
			/*var entity_obj_tabs = entity_obj_elm.children(".entity_obj_tabs");
			var active_tab = entity_obj_tabs.tabs('option', 'active');
			
			if (active_tab == 1) {
				entity_obj_tabs.tabs('option', 'active', 0);
				
				updateLayoutFromSettings(entity_obj_elm, true);
			}*/
			updateLayoutFromSettings(entity_obj_elm, true);
		}
	};
	var available_projects_templates_props = {};
	available_projects_templates_props[selected_project_id] = available_templates_props;
	
	chooseAvailableTemplate( template_elm.children("select[name=template]")[0], {
		show_templates_only: show_templates_only,
		available_projects_templates_props: available_projects_templates_props,
		available_projects_props: available_projects_props,
		get_available_templates_props_url: get_available_templates_props_url,
		install_template_url: install_template_url,
		
		on_select: function(selected_template) {
			var template_genre = template_elm.children("select[name=template_genre]");
			
			if (template_genre.val() != "") {
				template_genre.val("");
				template_genre.trigger("change");
			}
			
			func(selected_template);
		},
		on_select_from_other_project: function(selected_template, choose_template_selected_project_id) {
			var template_genre = template_elm.children("select[name=template_genre]");
			template_genre.val("external_template");
			template_genre.trigger("change");
			
			var external_template_params = entity_obj_elm.children(".external_template_params");
			var external_template_type = external_template_params.find(" > .external_template_type select");
			external_template_type.val("project");
			external_template_type.trigger("change");
			
			var external_template_id = external_template_params.find(" > .template_id input");
			external_template_id.val(selected_template);
			
			var external_project_id = external_template_params.find(" > .external_project_id input");
			external_project_id.val(choose_template_selected_project_id);
			
			var keep_original_project_url_prefix = external_template_params.find(" > .keep_original_project_url_prefix input");
			keep_original_project_url_prefix.attr("checked", "checked").prop("checked", true);
			
			external_project_id.trigger("blur");
			
			func(selected_template);
		}
	} );
}

function toggleExternalTemplateParams(elm) {
	elm = $(elm);
	elm.toggleClass("minimize maximize");
	elm.parent().closest(".external_template_params").toggleClass("collapsed");
}

function onChangeExternalTemplateType(elm) {
	elm = $(elm);
	var external_template_type = elm.val();
	var external_template_params = elm.parent().parent();
	
	external_template_params.children(":not(.external_template_type)").hide();
	
	if (external_template_type)
		external_template_params.find(".external_template_params_toggle_btn").show();
	else
		external_template_params.find(".external_template_params_toggle_btn").hide();
	
	if (external_template_type == "project")
		external_template_params.children(".project_param").show();
	else if (external_template_type == "block")
		external_template_params.children(".block_param").show();
	else if (external_template_type == "wordpress_template")
		external_template_params.children(".wordpress_template_param").show();
	else if (external_template_type == "url")
		external_template_params.children(".url_param").show();
	
	//update template layout ui
	updateTemplateLayout( external_template_params.parent() );
}

function onChooseProjectTemplate(elm) {
	var p = $(elm).parent();
	var popup = $("#choose_project_template_url_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		
		targetField: p,
		updateFunction: chooseProjectTemplateFile
	});
	
	MyFancyPopup.showPopup();
}

function chooseProjectTemplateFile(elm) {
	var node = chooseProjectTemplateUrlFromFileManagerTree.getSelectedNodes();
	node = node[0];
	
	if (node) {
		var a = $(node).children("a");
		var file_path = a.attr("file_path");
		var bean_name = a.attr("bean_name");
		var pos = file_path ? file_path.indexOf("/src/template/") : -1;
		var is_template = a.children("i").first().is(".template_file");
		
		if (file_path && pos != -1 && is_template) {
			var project_path = getNodeProjectPath(node);
			project_path = project_path && project_path.substr(project_path.length - 1) == "/" ? project_path.substr(0, project_path.length - 1) : project_path;
			project_path = project_path == selected_project_id ? "" : project_path;
			
			var template_path = file_path.substr(pos + ("/src/template/").length);//14 == /src/template/
			template_path = template_path.substr(template_path.length - 4, 1) == "." ? template_path.substr(0, template_path.lastIndexOf(".")) : template_path;
			
			var p = MyFancyPopup.settings.targetField;
			p.children("input").val(template_path);
			p.parent().find(".external_project_id input").val(project_path);
			
			//update template layout ui
			updateTemplateLayout( p.parent().parent() );
			
			MyFancyPopup.hidePopup();
		}
		else
			alert("invalid selected template file.\nPlease choose a valid template file.");
	}
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
			p.parent().find(".external_project_id input").val(project_path);
			
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
	//save synchronization functions
	var update_settings_from_layout_iframe_func_bkp = update_settings_from_layout_iframe_func;
	var update_layout_iframe_from_settings_func_bkp = update_layout_iframe_from_settings_func;
	
	//disable synchronization functions in case some call recursively by mistake
	update_settings_from_layout_iframe_func = null;
	update_layout_iframe_from_settings_func = null;
	
	//prepare new template load
	var iframe = entity_obj.find(".entity_template_layout iframe");
	var template = getSelectedTemplate(entity_obj);
	var is_template_ok = template ? true : false;
	
	if (template == "parse_php_code") {
		var external_template_params = entity_obj.children(".external_template_params");
		var external_template_type = external_template_params.find(" > .external_template_type select").val();
		
		if (external_template_type == "project" && external_template_params.find(" > .template_id input").val() == "")
			is_template_ok = false;
		else if (external_template_type == "block" && external_template_params.find(" > .block_id input").val() == "")
			is_template_ok = false;
		else if (external_template_type == "url" && external_template_params.find(" > .url input").val() == "")
			is_template_ok = false;
		else if (external_template_type == "")
			is_template_ok = false;
	}
	
	if (is_template_ok) {
		var is_external_template = entity_obj.find(".template select[name=template_genre]").val() ? 1 : 0;
		var external_template_params = getExternalSetTemplateParams(entity_obj);
		
		var regions_blocks_includes_settings = entity_obj.find(".regions_blocks_includes_settings");
		var data = getSettingsTemplateRegionsBlocks(regions_blocks_includes_settings);
		var template_includes = data["includes"];
		
		var url = get_template_regions_and_params_url.replace(/#template#/g, template) + "&is_external_template=" + is_external_template + "&external_template_params=" + encodeURIComponent(JSON.stringify(external_template_params)) + "&template_includes=" + encodeURIComponent(JSON.stringify(template_includes));
		
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
				
				reloadLayoutIframeFromSettings(iframe, {
					"template": template,
					"template_regions" : template_regions,
					"template_params": template_params_values_list,
					"template_includes": template_includes,
					"is_external_template": is_external_template,
					"external_template_params": external_template_params,
				});
				
				//sets back synchronization functions
				update_settings_from_layout_iframe_func = update_settings_from_layout_iframe_func_bkp;
				update_layout_iframe_from_settings_func = update_layout_iframe_from_settings_func_bkp;
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jqXHR.responseText);
					StatusMessageHandler.showError(jqXHR.responseText);
				
				//sets back synchronization functions
				update_settings_from_layout_iframe_func = update_settings_from_layout_iframe_func_bkp;
				update_layout_iframe_from_settings_func = update_layout_iframe_from_settings_func_bkp;
			}
		});
	}
	else {
		//update new template
		updateSelectedTemplateRegionsBlocks(entity_obj, null);
		reloadLayoutIframeFromSettings(iframe, {"template": ""});
			
		//sets back synchronization functions
		update_settings_from_layout_iframe_func = update_settings_from_layout_iframe_func_bkp;
		update_layout_iframe_from_settings_func = update_layout_iframe_from_settings_func_bkp;
	}
}

function updateLayoutFromSettings(entity_obj, reload_iframe) {
	if (entity_obj[0] && !entity_obj.hasClass("inactive")) {
		var orig_template_params_values_list = JSON.stringify(template_params_values_list);
		
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
			var iframe_data = {
				"template": template,
				"template_regions" : data["template_regions"],
				"template_params": data["params"],
				"template_includes": data["includes"],
				"is_external_template": entity_obj.find(" > .template > select[name=template_genre]").val() ? 1 : 0,
				"external_template_params": getExternalSetTemplateParams(entity_obj)
			};
			
			if (reload_iframe)
				reloadLayoutIframeFromSettings(iframe, iframe_data);
			else
				updateLayoutIframeFromSettings(iframe, iframe_data);
			
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

function updateSettingsFromLayout(entity_obj) {
	if (!entity_obj.hasClass("inactive")) {
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

/* SAVING FUNCTIONS */

function getEntityCodeObjId() {
	var obj = getObjToSave();
	
	return $.md5(save_object_url + JSON.stringify(obj));
}

function isEntityCodeObjChanged() {
	var entity_obj = $(".entity_obj");
	
	if (!entity_obj[0])
		return false;
	
	var new_saved_obj_id = getEntityCodeObjId();
	
	return saved_obj_id != new_saved_obj_id;
}

function save(opts) {
	var entity_obj = $(".entity_obj");
	
	prepareAutoSaveVars();
		
	if (entity_obj[0]) {
		var obj = getObjToSave();
		var new_saved_obj_id = $.md5(save_object_url + JSON.stringify(obj)); //Do not use getEntityCodeObjId, so it can be faster...
		
		if (!saved_obj_id || saved_obj_id != new_saved_obj_id) {
			if (!is_from_auto_save) {
				var save_btn = $(".top_bar ul li.save a");
				var save_on_click = save_btn.attr("onClick");
				save_btn.removeAttr("onClick");
				
				var save_preview_btn = $(".top_bar ul li.save_preview a");
				var save_preview_on_click = save_preview_btn.attr("onClick");
				save_preview_btn.removeAttr("onClick");
				
				MyFancyPopup.init({
					parentElement: window,
				});
				MyFancyPopup.showOverlay();
				MyFancyPopup.showLoading();
			}
			
			saveObj(save_object_url, obj, {
				success: function(data, textStatus, jqXHR) {
					if (opts && typeof opts["success"] == "function")
						opts["success"]();
					
					if (!is_from_auto_save) {
						save_btn.attr("onClick", save_on_click);
						save_preview_btn.attr("onClick", save_preview_on_click);
						MyFancyPopup.hidePopup();
					}
					else
						resetAutoSave();
					
					return true;
				},
				error: function(jqXHR, textStatus, errorThrown) {
					if (!is_from_auto_save) {
						save_btn.attr("onClick", save_on_click);
						save_preview_btn.attr("onClick", save_preview_on_click);
						MyFancyPopup.hidePopup();
					}
					else
						resetAutoSave();
					
					return true;
				},
			});
		}
		else if (!is_from_auto_save)
			StatusMessageHandler.showMessage("Nothing to save.");
		else
			resetAutoSave();
	}
	else if (!is_from_auto_save)
		alert("No entity object to save! Please contact the sysadmin...");
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

function confirmSave(opts) {
	if ($(".entity_obj").length == 0){
		alert("There is no entity object! Please contact the sysadmin...");
		return false;
	}
	
	prepareAutoSaveVars();
	
	//if is confirm popup, when from auto_save, it should not do anyting
	if (is_from_auto_save)
		return false;
	
	var obj = getObjToSave();
	var new_saved_obj_id = $.md5(save_object_url + JSON.stringify(obj)); //Do not use getEntityCodeObjId, so it can be faster...
	
	if (!saved_obj_id || saved_obj_id != new_saved_obj_id) {
		if (!is_from_auto_save) {
			MyFancyPopup.init({
				parentElement: window,
			});
			MyFancyPopup.showOverlay();
			MyFancyPopup.showLoading();
		}
		
		opts = opts ? opts : {};
		
		$.ajax({
			type : "post",
			url : create_entity_code_url,
			data : {"object" : obj},
			dataType : "json",
			success : function(data, textStatus, jqXHR) {
				if (!is_from_auto_save) {
					//only show this message if is a manual save, otherwise we don't want to do anything. Otherwise the browser is showing this popup constantly and is annoying for the user.
					var old_code = $(".current_entity_code").text();
					
					showConfirmationCodePopup(old_code, data, {
						save: function() {
							//change save button action to be simply save, otherwise it is always showing the confirmation popup everytime we save the file.
							if (opts && typeof opts["success"] == "function") {
								var prev_func = opts["success"];
								
								opts["success"] = function() {
									prev_func();
									
									$(".top_bar li.save a").click(function() { //cannot use the .attr("onClick", "save()") bc it doesn't work, so we must use click(function() {...});
										save();
									});
								}
							}
							else {
								if (!opts)
									opts = {};
								
								opts["success"] = function() {
									$(".top_bar li.save a").click(function() { //cannot use the .attr("onClick", "save()") bc it doesn't work, so we must use click(function() {...});
										save();
									});
								}
							}
							
							save(opts);
							
							return true;
						},
						cancel: function() {
							if (is_from_auto_save)
								resetAutoSave();
							
							return typeof opts.confirmation_cancel != "function" || opts.confirmation_cancel(data);
						},
					});
					
					MyFancyPopup.hidePopup();
				}
				else
					resetAutoSave();
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_entity_code_url, function() {
						StatusMessageHandler.removeLastShownMessage("error");
						
						confirmSave(opts);
					});
				else if (!is_from_auto_save) {
					var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
					StatusMessageHandler.showError("Error trying to save new changes.\nPlease try again..." + msg);
				}
				
				if (!is_from_auto_save) 
					MyFancyPopup.hidePopup();
				else
					resetAutoSave();
			},
		});
	}
	else if (!is_from_auto_save)
		StatusMessageHandler.showMessage("Nothing to save.");
	else
		resetAutoSave();
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
	/*var active_tab = entity_obj.children(".entity_obj_tabs").tabs('option', 'active');
	if (active_tab == 0) 
		updateSettingsFromLayout(entity_obj);
	*/
	
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
