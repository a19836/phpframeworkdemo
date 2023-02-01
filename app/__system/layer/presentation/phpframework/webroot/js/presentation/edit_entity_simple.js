var available_projects_props = null;
var available_templates_props = null;
var show_popup_interval_id = null;
var chooseProjectTemplateUrlFromFileManagerTree = null; //used by the create_presentation_uis_diagram.js and module/menu/show_menu/settings.js and others
var MyFancyPopupEditTemplateFile = new MyFancyPopupClass();
var MyFancyPopupEditWebrootFile = new MyFancyPopupClass();

//var start = (new Date()).getTime();

$(function () {
	var init_finished = false;
	
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	$(window).bind('beforeunload', function () {
		if (init_finished && isEntityCodeObjChanged()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	//prepare top_bar
	$(".code_layout_ui_editor > .code_menu").addClass("top_bar_menu");
	$(".code_layout_ui_editor > .layout-ui-editor").addClass("with_top_bar_menu");
	
	//init trees
	chooseProjectTemplateUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotTemplatesFromTree,
	});
	chooseProjectTemplateUrlFromFileManagerTree.init("choose_project_template_url_from_file_manager");
	
	//init ui
	var entity_obj = $(".entity_obj");
	
	if (entity_obj[0]) {
		//prepare main settings tab
		var regions_blocks_includes_settings = entity_obj.find(".regions_blocks_includes_settings");
		regions_blocks_includes_settings.tabs();
		
		setTimeout(function() {
			//init template list
			initTemplatesList(entity_obj);
			
			//prepare advanced entity settings
			initPageAdvancedSettings( regions_blocks_includes_settings.find(".advanced_settings") );
			
			//init sla
			initPageAndTemplateLayoutSLA(regions_blocks_includes_settings);
			
			//load sla settings
			loadPageAndTemplateLayoutSLASettings(regions_blocks_includes_settings, false);
			
			init_finished = true;
		}, 10);
		
		//init page template layout
		initPageAndTemplateLayout(entity_obj, {
			save_func: saveEntity, 
			ready_func: function() {
				//console.log("initPageAndTemplateLayout ready_func");
				
				//prepare some PtlLayoutUIEditor options
				var luie = entity_obj.find(".code_layout_ui_editor > .layout-ui-editor");
				var PtlLayoutUIEditor = luie.data("LayoutUIEditor");
				
				//set on_template_widgets_iframe_reload_func so everytime the template layout is changed or reload we update the css and js files.
				PtlLayoutUIEditor.options.on_template_widgets_iframe_reload_func = function() {
					//reload js and css files
					loadCodeEditorLayoutJSAndCSSFilesToSettings();
				};
				
				//DEPRECATED - waits until the load params and joinpoints gets loaded. No need this anymorebc the initPageAndTemplateLayout method already covers this case.
				//setTimeout(function() {
					//load js and css files
					loadCodeEditorLayoutJSAndCSSFilesToSettings();
					
					var func = function() {
						if (init_finished) {
							//set saved_obj_id
							saved_obj_id = getEntityCodeObjId();
							
							//init auto save
							addAutoSaveMenu(".top_bar li.sub_menu li.save");
							//enableAutoSave(onToggleSLAAutoSave); //Do not enable auto save bc it gets a litte bit slow editing the template.
							initAutoSave(".top_bar li.sub_menu li.save a");
							StatusMessageHandler.showMessage("Auto save is disabled for a better user-experience...");
							
							//change the toggle Auto save handler bc the edit_query task
							initSLAAutoSaveActivationMenu();
							
							//set update handlers
							var iframe = getContentTemplateLayoutIframe(entity_obj);
							
							update_settings_from_layout_iframe_func = function() {
								//console.log("updateSettingsFromLayout");
								updateSettingsFromLayout(entity_obj);
							};
							update_layout_iframe_from_settings_func = function() {
								//console.log("updateLayoutFromSettings");
								updateLayoutFromSettings(entity_obj, false);
							};
							update_layout_iframe_field_html_value_from_settings_func = function(elm, html) { //set handler to update directly the the html in the template layout without refreshing the entire layout.
								//console.log("updateLayoutIframeRegionBlockHtmlFromSettingsHtmlField");
								updateLayoutIframeRegionBlockHtmlFromSettingsHtmlField(elm, html, iframe);
							};
							
							//hide loading icon
							MyFancyPopup.hidePopup();
							
							//var end = (new Date()).getTime();
							//console.log("loading time: "+((end-start)/1000)+" secs");
						}
						else
							setTimeout(function() {
								func();
							}, 700);
					};
					func();
					
					entity_or_template_obj_inited = true;
				//}, 2000);
			}
		});
		
		if (!code_exists)
			onChooseAvailableTemplate( entity_obj.find(".template .search")[0], show_templates_only ); //open template popup automatically if entity is new
	}
});

//To be used in the toggleFullScreen function
function onToggleFullScreen(in_full_screen) {
	var entity_obj = $(".entity_obj");
	onToggleCodeEditorFullScreen(in_full_screen, entity_obj);
	
	setTimeout(function() {
		var top = parseInt(entity_obj.find(".regions_blocks_includes_settings").css("top"));
		
		resizeSettingsPanel(entity_obj, top);
	}, 500);
}

/* CHOOSE TEMPLATE FUNCTIONS */

function initTemplatesList(entity_obj) {
	$.ajax({
		type : "get",
		url : get_available_templates_list_url,
		dataType : "json",
		success : function(data, textStatus, jqXHR) {
			//console.log(data);
			var templates_select = entity_obj.find(" > .template select[name=template]");
			var current_template = templates_select.val();
			var html = '<option value="">-- DEFAULT --</option>';
			
			$.each(data, function(idx, template_id) {
				html += '<option value="' + template_id + '">' + template_id + '</option>';
			});
			
			templates_select.html(html);
			templates_select.val(current_template);
		},
		error : function(jqXHR, textStatus, errorThrown) { 
			if (jqXHR.responseText);
				StatusMessageHandler.showError(jqXHR.responseText);
		},
	});
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
	var top_bar = $(".top_bar");
	var select = p.children("select[name=template]");
	var external_template_params = entity_obj.children(".external_template_params");
	var external_template_params_toggle_btn = p.children(".external_template_params_toggle_btn");
	var template_value = null;
	
	if (!is_external_template) {
		entity_obj.removeClass("is_external_template");
		top_bar.removeClass("is_external_template");
		template_value = select.val();
		
		select.show();
		external_template_params.hide();
		external_template_params_toggle_btn.hide();
		
		//update template layout ui
		updateTemplateLayout(entity_obj);
	}
	else {
		entity_obj.addClass("is_external_template");
		top_bar.addClass("is_external_template");
		select.hide();
		external_template_params.show();
		external_template_params_toggle_btn.show();
		
		onChangeExternalTemplateType( external_template_params.find(".external_template_type select")[0] );
	}
}

function onChooseAvailableTemplate(elm, show_templates_only) {
	var template_elm = $(elm).parent();
	var entity_obj_elm = template_elm.parent();
	var func = function(selected_template) {
		if (!code_exists) { //only if file is new
			updateLayoutFromSettings(entity_obj_elm, true);
		}
	};
	var available_projects_templates_props = {};
	available_projects_templates_props[selected_project_id] = available_templates_props;
	
	var cat_select = template_elm.children("select[name=template]");
	var cat_props = {
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
	};
	
	//init available_projects_props
	if (!available_templates_props && get_available_templates_props_url) {
		MyFancyPopup.showLoading();
		
		$.ajax({
			type : "get",
			url : get_available_templates_props_url.replace("#path#", entity_path),
			dataType : "json",
			success : function(data, textStatus, jqXHR) {
				//console.log(data);
				available_templates_props = data;
				cat_props["available_projects_templates_props"][selected_project_id] = data;
				
				MyFancyPopup.hideLoading();
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jqXHR.responseText);
					StatusMessageHandler.showError(jqXHR.responseText);
				
				MyFancyPopup.hideLoading();
			},
			async: false
		});
	}
	
	//init available_projects_props
	if (!available_projects_props && get_available_projects_props_url) {
		MyFancyPopup.showLoading();
		
		$.ajax({
			type : "get",
			url : get_available_projects_props_url,
			dataType : "json",
			success : function(data, textStatus, jqXHR) {
				//console.log(data);
				available_projects_props = data;
				cat_props["available_projects_props"] = data;
				
				MyFancyPopup.hideLoading();
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jqXHR.responseText);
					StatusMessageHandler.showError(jqXHR.responseText);
				
				MyFancyPopup.hideLoading();
			},
			async: false
		});
	}
	
	chooseAvailableTemplate(cat_select[0], cat_props);
}

function toggleExternalTemplateParams(elm) {
	elm = $(elm);
	elm.toggleClass("dropdown_arrow dropup_arrow");
	elm.parent().closest(".entity_obj").children(".external_template_params").toggleClass("collapsed");
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
	var update_layout_iframe_field_html_value_from_settings_func_bkp = update_layout_iframe_field_html_value_from_settings_func;
	
	//disable synchronization functions in case some call recursively by mistake
	update_settings_from_layout_iframe_func = null;
	update_layout_iframe_from_settings_func = null;
	update_layout_iframe_field_html_value_from_settings_func = null;
	
	//prepare new template load
	var iframe = getContentTemplateLayoutIframe(entity_obj);
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
		var is_external_template = isExternalTemplate(entity_obj) ? 1 : 0;
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
				update_layout_iframe_field_html_value_from_settings_func = update_layout_iframe_field_html_value_from_settings_func_bkp;
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jqXHR.responseText);
					StatusMessageHandler.showError(jqXHR.responseText);
				
				//sets back synchronization functions
				update_settings_from_layout_iframe_func = update_settings_from_layout_iframe_func_bkp;
				update_layout_iframe_from_settings_func = update_layout_iframe_from_settings_func_bkp;
				update_layout_iframe_field_html_value_from_settings_func = update_layout_iframe_field_html_value_from_settings_func_bkp;
			}
		});
	}
	else {
		//update new template
		updateSelectedTemplateRegionsBlocks(entity_obj, null);
		reloadLayoutIframeFromSettings(iframe, {template: ""});
		
		//sets back synchronization functions
		update_settings_from_layout_iframe_func = update_settings_from_layout_iframe_func_bkp;
		update_layout_iframe_from_settings_func = update_layout_iframe_from_settings_func_bkp;
		update_layout_iframe_field_html_value_from_settings_func = update_layout_iframe_field_html_value_from_settings_func_bkp;
	}
}

function getSelectedTemplate(entity_obj) {
	var template = "";
	var is_external_template = isExternalTemplate(entity_obj);
	
	if (is_external_template) 
		template = "parse_php_code";
	else {
		template = entity_obj.find(" > .template select[name=template]").val();
		template = template ? template : layer_default_template;
	}
	
	return template;
}

function isExternalTemplate(entity_obj) {
	return entity_obj.find(" > .template > select[name=template_genre]").val() ? true : false;
}

function editCurrentTemplateFile(elm) {
	var entity_obj = $(".entity_obj");
	var template = getSelectedTemplate(entity_obj);
	var url = null;
	
	if (template == "parse_php_code") {
		var template_args = getExternalTemplateParams(entity_obj);
		//console.log(template_args);
		
		if (template_args["type"] == "project" && template_args["template_id"]) {
			var path = (template_args["external_project_id"] ? template_args["external_project_id"] : selected_project_id) + "/src/template/" + template_args["template_id"] + ".php";
			url = edit_template_file_url.replace("#path#", path);
		}
		else if (template_args["type"] == "block" && template_args["block_id"]) {
			var path = (template_args["external_project_id"] ? template_args["external_project_id"] : selected_project_id) + "/src/block/" + template_args["block_id"] + ".php";
			url = edit_block_url.replace("#path#", path);
		}
	}
	else {
		var path = selected_project_id + "/src/template/" + (template ? template : layer_default_template) + ".php";
		url = edit_template_file_url.replace("#path#", path);
	}
	
	if (!url)
		alert("Cannot edit this template through here. Please go directly to the file path through the navigator panel.");
	else {
		url += (url.indexOf("?") != -1 ? "&" : "?") + "popup=1";
		
		//prepare popup
		var entity_obj = $(".entity_obj");
		var popup = entity_obj.children("#edit_template_file");
		
		if (!popup[0]) {
			popup = $('<div id="edit_template_file" class="myfancypopup with_iframe_title"><iframe></iframe></div>');
			entity_obj.append(popup);
		}
		else 
			popup.html('<iframe></iframe>');
		
		var iframe = popup.children("iframe");
		
		iframe.attr("src", url);
		//console.log(url);
		
		//open popup
		MyFancyPopupEditTemplateFile.init({
			elementToShow: popup,
			parentElement: document,
			
			onClose: function() {
				updateTemplateLayout(entity_obj);
			},
		});
		
		MyFancyPopupEditTemplateFile.showPopup();
	}
}

function editWebrootFile(path) {
	//prepare popup
	var entity_obj = $(".entity_obj");
	var popup = entity_obj.children("#edit_webroot_file");
	
	if (!popup[0]) {
		popup = $('<div id="edit_webroot_file" class="myfancypopup with_iframe_title"><iframe></iframe></div>');
		entity_obj.append(popup);
	}
	else 
		popup.html('<iframe></iframe>');
	
	var iframe = popup.children("iframe");
	var url = edit_webroot_file_url.replace("#path#", path);
	
	iframe.attr("src", url);
	
	//open popup
	MyFancyPopupEditWebrootFile.init({
		elementToShow: popup,
		parentElement: document,
		
		onClose: function() {
			updateTemplateLayout(entity_obj);
		},
	});
	
	MyFancyPopupEditWebrootFile.showPopup();
}

function getTemplateHeadEditorCode() {
	var code_layout_ui_editor = $(".entity_obj .code_layout_ui_editor");
	var PtlLayoutUIEditor = code_layout_ui_editor.find(".layout-ui-editor").data("LayoutUIEditor");
	
	if (PtlLayoutUIEditor) {
		//disable beauty in PtlLayoutUIEditor so it can get the code faster
		var beautify = PtlLayoutUIEditor.options.beautify;
		PtlLayoutUIEditor.options.beautify = false; //This will make the system 3 secs faster everytime this function gets called.
		
		//remove active class so the getCodeLayoutUIEditorCode calls the getTemplateSourceEditorValue method, instead of getTemplateFullSourceEditorValue
		var luie = PtlLayoutUIEditor.getUI();
		luie.find(" > .options .option.show-full-source").addClass("option-active"); 
	
		var code = getCodeLayoutUIEditorCode(code_layout_ui_editor);
		
		//set original beauty
		PtlLayoutUIEditor.options.beautify = beautify;
		
		//filter head 
		if (PtlLayoutUIEditor.existsTagFromSource(code, "head"))
			return PtlLayoutUIEditor.getTagContentFromSource(code, "head"); 
	}
	
	return "";
}

function getTemplateBodyEditorCode() {
	var code_layout_ui_editor = $(".entity_obj .code_layout_ui_editor");
	var PtlLayoutUIEditor = code_layout_ui_editor.find(".layout-ui-editor").data("LayoutUIEditor");
	
	if (PtlLayoutUIEditor) {
		//disable beauty in PtlLayoutUIEditor so it can get the code faster
		var beautify = PtlLayoutUIEditor.options.beautify;
		PtlLayoutUIEditor.options.beautify = false; //This will make the system 3 secs faster everytime this function gets called.
		
		//remove active class so the getCodeLayoutUIEditorCode calls the getTemplateSourceEditorValue method, instead of getTemplateFullSourceEditorValue
		var luie = PtlLayoutUIEditor.getUI();
		luie.find(" > .options .option.show-full-source").removeClass("option-active"); 
	}
	
	var code = getCodeLayoutUIEditorCode(code_layout_ui_editor);
	
	//set original beauty
	if (PtlLayoutUIEditor)
		PtlLayoutUIEditor.options.beautify = beautify;
	
	return code;
}

/* LAYOUT-SETTINGS-LAYOUT UPDATE FUNCTIONS */

function updateLayoutFromSettings(entity_obj, reload_iframe) {
	if (entity_obj[0] && !entity_obj.hasClass("inactive")) {
		var orig_template_params_values_list = JSON.stringify(template_params_values_list);
		var orig_includes_list = JSON.stringify(includes_list);
		
		var iframe = getContentTemplateLayoutIframe(entity_obj);
		var regions_blocks_includes_settings = entity_obj.find(".regions_blocks_includes_settings");
		
		updateRegionsBlocksRBIndexIfNotSet(regions_blocks_includes_settings); //very important, otherwise we will loose the region-block params-values and joinpoints for the new regions added dynamically
		
		//very important, otherwise we will loose the region-block params-values and joinpoints
		updateRegionsBlocksParamsLatestValues(regions_blocks_includes_settings); 
		updateRegionsBlocksJoinPointsSettingsLatestObjs(regions_blocks_includes_settings);
		
		var are_different = areLayoutAndSettingsDifferent(iframe, regions_blocks_includes_settings, true);
		var data = getSettingsTemplateRegionsBlocks(regions_blocks_includes_settings);
		//console.log(data);
		
		if (!are_different)
			are_different = orig_template_params_values_list != JSON.stringify(data["params"]);
		
		if (!are_different)
			are_different = orig_includes_list != JSON.stringify(data["includes"]);
		
		if (are_different /*&& confirm("Do you wish to convert the template settings to the layout panel?")*/) {
			var template = getSelectedTemplate(entity_obj);
			var iframe_data = {
				"template": template,
				"template_regions" : data["template_regions"],
				"template_params": data["params"],
				"template_includes": data["includes"],
				"is_external_template": isExternalTemplate(entity_obj) ? 1 : 0,
				"external_template_params": getExternalSetTemplateParams(entity_obj)
			};
			
			if (reload_iframe)
				reloadLayoutIframeFromSettings(iframe, iframe_data);
			else
				updateLayoutIframeFromSettings(iframe, iframe_data, data);
			
			//update regions_blocks_list
			regions_blocks_list = data["regions_blocks"];
			
			//update template_params_values_list
			template_params_values_list = data["params"];
			
			//update includes_list
			includes_list = data["includes"];
		}
	}
}

function updateSettingsFromLayout(entity_obj) {
	if (!entity_obj.hasClass("inactive")) {
		var iframe = getContentTemplateLayoutIframe(entity_obj);
		var regions_blocks_includes_settings = entity_obj.find(".regions_blocks_includes_settings");
		var are_different = areLayoutAndSettingsDifferent(iframe, regions_blocks_includes_settings, true);
		
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

/* ADVACNED SETTINGS FUNCTIONS */

function initPageAdvancedSettings(elm) {
	onChangeParseHtml( elm.find(".parser .parse_html select")[0] );
	
	elm.find(".cache input[type=checkbox]").each(function(idx, elm) {
		onChangeCacheOption(elm);
	});
}

function onChangeParseHtml(elm) {
	elm = $(elm);
	var type = elm.val();
	var parser_elm = elm.parent().closest(".parser");
	var divs = parser_elm.children("div:not(.parse_html)");
	var inputs = divs.find("input, select");
	
	if (type == 0) {
		divs.hide();
		inputs.attr("disabled", "disabled");
	}
	else {
		divs.show();
		inputs.removeAttr("disabled", "disabled");
	}
}

function onChangeCacheOption(elm) {
	elm = $(elm);
	var input = elm.parent().children("input:not([type=checkbox])");
	
	if (elm.is(":checked"))
		input.removeAttr("disabled").show();
	else
		input.attr("disabled", "disabled").hide();
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

function saveEntity(opts) {
	var entity_obj = $(".entity_obj");
	
	prepareAutoSaveVars();
	
	if (entity_obj[0]) {
		var func = function() {
			if (confirm_save)
				confirmSave(opts);
			else
				save(opts);
		};
		
		if (!auto_convert_settings_from_layout) {
			if (!is_from_auto_save) {
				MyFancyPopup.init({
					parentElement: window,
				});
				MyFancyPopup.showOverlay();
				MyFancyPopup.showLoading();
			}
			
			enableAutoConvertSettingsFromLayout(function() {
				if (!is_from_auto_save)				
					MyFancyPopup.hidePopup();
				
				func();
			});
			disableAutoConvertSettingsFromLayout();
		}
		else
			func();
	}
	else if (!is_from_auto_save)
		alert("No entity object to save! Please contact the sysadmin...");
}

function save(opts) {
	var entity_obj = $(".entity_obj");
	
	prepareAutoSaveVars();
	
	var is_from_auto_save_bkp = is_from_auto_save; //backup the is_from_auto_save, bc if there is a concurrent process running at the same time, this other process may change the is_from_auto_save value.
		
	if (entity_obj[0]) {
		if (!window.is_save_func_running) {
			window.is_save_func_running = true;
			
			//prepare save
			var obj = getObjToSave();
			var new_saved_obj_id = $.md5(save_object_url + JSON.stringify(obj)); //Do not use getEntityCodeObjId, so it can be faster...
			
			if (!saved_obj_id || saved_obj_id != new_saved_obj_id) {
				var save_btn = $(".top_bar ul li.save a");
				
				if (!is_from_auto_save_bkp) {
					save_btn.first().addClass("loading"); //only for the short-action icon
					
					MyFancyPopup.init({
						parentElement: window,
					});
					MyFancyPopup.showOverlay();
					MyFancyPopup.showLoading();
				}
				
				opts = opts ? opts : {};
				opts.complete = function() {
					if (!is_from_auto_save_bkp) {
						save_btn.removeClass("loading");
						//MyFancyPopup.hidePopup(); //the saveObj function already hides the popup
					}
					//else
					//	resetAutoSave(); //the saveObj function already resetAutoSave
					
					window.is_save_func_running = false;
				};
				
				saveObj(save_object_url, obj, opts);
			}
			else {
				if (!is_from_auto_save_bkp)
					StatusMessageHandler.showMessage("Nothing to save.");
				else
					resetAutoSave();
				
				window.is_save_func_running = false;
			}
		}
		else if (!is_from_auto_save_bkp)
			StatusMessageHandler.showMessage("There is already a saving process running. Please wait a few seconds and try again...");
	}
	else if (!is_from_auto_save_bkp)
		alert("No entity object to save! Please contact the sysadmin...");
}

function saveAndPreview() {
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
	
	//disable auto_save if manual action
	var auto_save_bkp = auto_save;
	
	if (/*!is_from_auto_save && */auto_save_bkp && isAutoSaveMenuEnabled())
		auto_save = false;
	
	//prepare save
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
					
					MyFancyPopup.hidePopup();
					
					showConfirmationCodePopup(old_code, data, {
						save: function() {
							if (/*!is_from_auto_save && */auto_save_bkp && isAutoSaveMenuEnabled())
								auto_save = auto_save_bkp;
							
							//change save button action to be simply save, otherwise it is always showing the confirmation popup everytime we save the file.
							if (opts && typeof opts["success"] == "function") {
								var prev_func = opts["success"];
								
								opts["success"] = function() {
									prev_func();
									
									$(".top_bar li.save a").click(function() { //cannot use the .attr("onClick", "save()") bc it doesn't work, so we must use click(function() {...});
										save();
									});
									
									return true;
								}
							}
							else {
								if (!opts)
									opts = {};
								
								opts["success"] = function() {
									$(".top_bar li.save a").click(function() { //cannot use the .attr("onClick", "save()") bc it doesn't work, so we must use click(function() {...});
										save();
									});
									
									return true;
								}
							}
							
							save(opts);
							
							return true;
						},
						cancel: function() {
							if (/*!is_from_auto_save && */auto_save_bkp && isAutoSaveMenuEnabled())
								auto_save = auto_save_bkp;
							
							return typeof opts.confirmation_cancel != "function" || opts.confirmation_cancel(data);
						},
					});
				}
				else {
					if (/*!is_from_auto_save && */auto_save_bkp && isAutoSaveMenuEnabled())
						auto_save = auto_save_bkp;
					
					resetAutoSave();
				}
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (/*!is_from_auto_save && */auto_save_bkp && isAutoSaveMenuEnabled())
					auto_save = auto_save_bkp;
				
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_entity_code_url, function() {
						StatusMessageHandler.removeLastShownMessage("error");
						
						confirmSave(opts);
					});
				else if (!is_from_auto_save) {
					var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
					StatusMessageHandler.showError("Error trying to save new changes.\nPlease try again..." + msg);
					
					MyFancyPopup.hidePopup();
				}
				else
					resetAutoSave();
			},
		});
	}
	else {
		if (/*!is_from_auto_save && */auto_save_bkp)
			auto_save = auto_save_bkp;
		
		if (!is_from_auto_save)
			StatusMessageHandler.showMessage("Nothing to save.");
		else
			resetAutoSave();
	}
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
	var is_external_template = isExternalTemplate(entity_obj);
	
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
	var is_external_template = isExternalTemplate(entity_obj);
	
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
	//get regions blocks settings
	var obj = getRegionsBlocksAndIncludesObjToSave();
	//console.log(obj);
	
	//get sla settings
	var sla = $(".sla");
	obj["sla_settings"] = getSLASettings(sla);
	
	//get advanced properties
	var advanced_settings = $(".advanced_settings");
	obj["advanced_settings"] = getAdvancedSettings(advanced_settings);
	
	//prepare template
	var entity_obj = $(".entity_obj");
	var template_elm = entity_obj.find(".template");
	var is_external_template = isExternalTemplate(entity_obj);
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

function getAdvancedSettings(advanced_settings_elm) {
	var inputs = advanced_settings_elm.find("input, select, textarea");
	var setttings = {};
	
	for (var i = 0; i < inputs.length; i++) {
		var input = inputs[i];
		
		if (!input.hasAttribute("disabled")) {
			var name = input.name;
			
			if (name) {
				if ((input.type == "checkbox" || input.type == "radio")) {
					if (input.checked)
						setttings[name] = input.value;
				}
				else
					setttings[name] = input.value;	
			}
		}
	}
	
	return setttings;
}
