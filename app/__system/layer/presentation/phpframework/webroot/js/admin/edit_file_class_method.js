var saved_class_method_settings_id = null;

$(function () {
	$(window).bind('beforeunload', function () {
		if (isClassMethodObjChanged()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	//prepare top_bar
	$("#ui > .taskflowchart").addClass("with_top_bar_menu fixed_side_properties").children(".workflow_menu").addClass("top_bar_menu");
	
	$("#code > .code_menu > ul, #ui > .taskflowchart > .workflow_menu > ul").prepend('<li class="toggle_main_settings" title="Toggle Main Settings"><a onClick="toggleSettingsPanel(this)"><i class="icon toggle_main_settings"></i> <span>Show Main Settings</span> <input type="checkbox"/></a></li><li class="separator"></li>');
	
	//init auto save
	enableAutoSave(onTogglePHPCodeAutoSave);
	enableAutoConvert(onTogglePHPCodeAutoConvert);
	initAutoSave("#code > .code_menu li.save a");
	
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
	
	if (layer_type == "pres" || layer_type == "bl") {
		chooseBusinessLogicFromFileManagerTree = new MyTree({
			multiple_selection : false,
			toggle_children_on_click : true,
			ajax_callback_before : prepareLayerNodes1,
			ajax_callback_after : removeObjectPropertiesAndFunctionsFromTree,
		});
		chooseBusinessLogicFromFileManagerTree.init("choose_business_logic_from_file_manager");
		
		chooseQueryFromFileManagerTree = new MyTree({
			multiple_selection : false,
			toggle_children_on_click : true,
			ajax_callback_before : prepareLayerNodes1,
			ajax_callback_after : removeParametersAndResultMapsFromTree,
		});
		chooseQueryFromFileManagerTree.init("choose_query_from_file_manager");
		
		chooseHibernateObjectFromFileManagerTree = new MyTree({
			multiple_selection : false,
			toggle_children_on_click : true,
			ajax_callback_before : prepareLayerNodes1,
			ajax_callback_after : removeQueriesAndMapsAndOtherHbnNodesFromTree,
		});
		chooseHibernateObjectFromFileManagerTree.init("choose_hibernate_object_from_file_manager");
		
		chooseHibernateObjectMethodFromFileManagerTree = new MyTree({
			multiple_selection : false,
			toggle_children_on_click : true,
			ajax_callback_before : prepareLayerNodes1,
			ajax_callback_after : removeParametersAndResultMapsFromTree,
		});
		chooseHibernateObjectMethodFromFileManagerTree.init("choose_hibernate_object_method_from_file_manager");
		
		if (layer_type == "pres") {
			choosePresentationFromFileManagerTree = new MyTree({
				multiple_selection : false,
				toggle_children_on_click : true,
				ajax_callback_before : prepareLayerNodes1,
				ajax_callback_after : removeAllThatIsNotPagesFromTree,
			});
			choosePresentationFromFileManagerTree.init("choose_presentation_from_file_manager");
			
			chooseBlockFromFileManagerTree = new MyTree({
				multiple_selection : false,
				toggle_children_on_click : true,
				ajax_callback_before : prepareLayerNodes1,
				ajax_callback_after : removeAllThatIsNotBlocksFromTree,
			});
			chooseBlockFromFileManagerTree.init("choose_block_from_file_manager");
			
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
		}
	}
		
	//init file_class_method
	var file_class_method_obj = $(".file_class_method_obj");
	
	if (file_class_method_obj[0]) {
		file_class_method_obj.tabs({active: show_low_code_first ? 1 : 0});
		
		var textarea = $("#code textarea")[0];
		if (textarea) {
			eval("var save_func = " + js_save_func_name + ";");
			
			var editor = createCodeEditor(textarea, {save_func: save_func});
			
			if (editor)
				editor.focus();
		}
		
		//load workflow
		onLoadTaskFlowChartAndCodeEditor({do_not_hide_popup : true});
		
		//init tasks flow tab
		onClickTaskWorkflowTab( file_class_method_obj.find(" > .tabs > #tasks_flow_tab > a")[0], {
			on_success: function() {
				//set saved_class_method_settings_id and saved_obj_id
				saved_class_method_settings_id = getFileClassMethodSettingsId();
				//saved_obj_id = getFileClassMethodObjId(); //Do not uncomment this code, otherwise the it will save the workflow on page load.
				
				MyFancyPopup.hidePopup();
			},
			on_error: function() {
				file_class_method_obj.tabs("option", "active", 0); //show code tab
				
				//set saved_class_method_settings_id and saved_obj_id
				saved_class_method_settings_id = getFileClassMethodSettingsId();
				//saved_obj_id = getFileClassMethodObjId(); //Do not uncomment this code, otherwise the it will save the workflow on page load.
				
				MyFancyPopup.hidePopup();
			}
		});
		
		//init settings
		var settings = file_class_method_obj.find("#settings");
		
		settings.draggable({
			axis: "y",
			appendTo: 'body',
			cursor: 'move',
               tolerance: 'pointer',
               handle: ' > .settings_header',
         		cancel: '.icon', //this means that is inside of .settings_header
			start: function(event, ui) {
				settings.addClass("resizing").removeClass("collapsed");
				settings.find(" > .settings_header .icon").addClass("minimize").removeClass("maximize");
				
				return true;
			},
			drag: function(event, ui) {
				var h = $(window).height() - (ui.offset.top - $(window).scrollTop());
				
				settings.css({
					height: h + "px",
					top: "", 
					left: "", 
					bottom: ""
				});
			},
			stop: function(event, ui) {
				var top = parseInt(ui.helper.css("top"));//Do not use ui.offset.top bc if the window has scrollbar, it will get the wrong top for the calculations inside of resizeSettingsPanel
				resizeSettingsPanel(settings, top);
			},
		});
		
		//prepare arguments and annotations and ProgrammingTaskUtil.variables_in_workflow
		var arguments_tbody = settings.find(".arguments .fields");
		var arguments_rows = arguments_tbody.children(".argument");
		
		if (arguments_rows.length == 0)
			addNewArgument( arguments_tbody.parent().find("thead .add")[0] );
		else //add argument to ProgrammingTaskUtil.variables_in_workflow
			for (var i = 0; i < arguments_rows.length; i++)
				onBlurArgumentName( $(arguments_rows[i]).find(".name input")[0] );
		
		var annotations_tbody = settings.find(".annotations .fields");
		var annotations_rows = annotations_tbody.children(".annotation");
		for (var i = 0; i < annotations_rows.length; i++)
			onBlurAnnotationName( $(annotations_rows[i]).find(".name input")[0] );
	}
	else	//hide loading icon
		MyFancyPopup.hidePopup();
});

//To be used in the toggleFullScreen function
function onToggleFullScreen(in_full_screen) {
	setTimeout(function() {
		var settings = $(".file_class_method_obj #settings");
		var top = parseInt(settings.css("top"));
		
		resizeSettingsPanel(settings, top);
	}, 500);
}

function resizeSettingsPanel(settings, top) {
	var icon = settings.find(" > .settings_header .icon");
	var wh = $(window).height();
	var height = 0;
	
	settings.removeClass("resizing");
	settings.css({top: "", left: "", bottom: ""}); //remove top, left and bottom from style attribute in #settings_header
	
	if (top < 40) { //40 is the size of #top_bar (40px)
		height = wh - 40;
		
		settings.css("height", height + "px");
	}
	else if (top > wh - 25) { //25 is the size of #settings .settings_header when collapsed
		icon.addClass("maximize").removeClass("minimize");
		settings.addClass("collapsed");
		
		settings.css("height", ""); //remove height from style attribute in #settings
	}
	else {
		settings.css("height", (wh - top) + "px");
	}
}

function toggleSettingsPanel(elm) {
	var settings = $("#settings");
	var lis = $("#code > .code_menu > ul li.toggle_main_settings, #ui > .taskflowchart > .workflow_menu > ul li.toggle_main_settings");
	var inputs = lis.find("input");
	var spans = lis.find("span");
	var icon = settings.find(" > .settings_header > .icon").filter(".maximize, .minimize");
	
	icon.toggleClass("maximize").toggleClass("minimize");
	settings.toggleClass("collapsed");
	
	if (settings.hasClass("collapsed")) {
		inputs.removeAttr("checked").prop("checked", false);
		spans.html("Show Main Settings");
	}
	else {
		inputs.attr("checked", "checked").prop("checked", true);
		spans.html("Hide Main Settings");
	}
}

function addNewArgument(elm) {
	var html_obj = $(new_argument_html);
	$(elm).closest(".arguments").find("table .fields").append(html_obj);
	
	return html_obj;
}
function removeArgument(elm) {
	var p = $(elm).parent().parent();
	var name = p.find(".name input").val();
	name = ("" + name).replace(/^[&$]/g, "");
	
	if (name) {
		name = "$" + name;
		
		if ($.isPlainObject(ProgrammingTaskUtil.variables_in_workflow) && ProgrammingTaskUtil.variables_in_workflow.hasOwnProperty(name))
			delete ProgrammingTaskUtil.variables_in_workflow[name];
	}
	
	p.remove();
}
function onBlurArgumentName(elm) {
	var name = $(elm).val();
	name = ("" + name).replace(/^[&$]/g, "");
	
	if (name) {
		name = "$" + name;
		ProgrammingTaskUtil.variables_in_workflow[name] = {};
	}
}

function addNewAnnotation(elm) {
	var html_obj = $(new_annotation_html);
	$(elm).closest(".annotations").find("table .fields").append(html_obj);
	
	return html_obj;
}
function removeAnnotation(elm) {
	var p = $(elm).parent().parent();
	var name = p.find(".name input").val();
	name = ("" + name).replace(/^[&$]/g, "");
	
	if (name) {
		var is_business_logic_service = $(".top_bar .is_business_logic_service").val();
		
		if (is_business_logic_service == 1)
			name = "$data[\"" + name + "\"]";
		else
			name = "$" + name;
		
		if ($.isPlainObject(ProgrammingTaskUtil.variables_in_workflow) && ProgrammingTaskUtil.variables_in_workflow.hasOwnProperty(name))
			delete ProgrammingTaskUtil.variables_in_workflow[name];
	}
	
	p.remove();
}
function onBlurAnnotationName(elm) {
	var name = $(elm).val();
	name = ("" + name).replace(/^[&$]/g, "");
	
	if (name) {
		var is_business_logic_service = $(".top_bar .is_business_logic_service").val();
		
		if (is_business_logic_service == 1)
			name = "$data[\"" + name + "\"]";
		else
			name = "$" + name;
		
		ProgrammingTaskUtil.variables_in_workflow[name] = {};
	}
}

function swapTypeTextField(elm) {
	var p = $(elm).parent();
	var select = p.children("select");
	var input = p.children("input");
	
	if (select.css("display") == "none") {
		input.css("display", "none");
		select.css("display", "inline");
		select.val( input.val() );
	}
	else {
		select.css("display", "none");
		input.css("display", "inline");
		input.val( select.val() );
	}
}

function getFileClassMethodSettingsId() {
	var obj_settings = getFileClassMethodSettingsObj();
	
	return $.md5(JSON.stringify(obj_settings));
}

function isClassMethodObjChanged() {
	var file_class_method_obj = $(".file_class_method_obj");
	
	if (!file_class_method_obj[0])
		return false;
	
	if (isCodeAndWorkflowObjChanged(file_class_method_obj))
		return true;
	
	var new_class_method_settings_id = getFileClassMethodSettingsId();
	
	return saved_class_method_settings_id != new_class_method_settings_id;
}

function getFileClassMethodObjId() {
	var obj = getFileClassMethodObj();
	
	//remove error messages bc when we call the getCodeForSaving method, it will save try to save the workflow but it will give an error bc we are calling the isTestObjChanged on window before load, which will kill the ongoing ajax requests...
	StatusMessageHandler.removeMessages("error");
	jsPlumbWorkFlow.jsPlumbStatusMessage.removeMessages("error");
	
	$(".workflow_menu").show();
	MyFancyPopup.hidePopup();
	
	return $.md5(save_object_url + JSON.stringify(obj));
}

function getFileClassMethodObj() {
	var file_class_method_obj = $(".file_class_method_obj");
	
	if (!file_class_method_obj[0])
		return null;
	
	var obj = getFileClassMethodSettingsObj();
	obj["code"] = getCodeForSaving(file_class_method_obj, {strip_php_tags: true}); //if tasks flow tab is selected ask user to convert workfow into code
	
	return obj;
}

function getFileClassMethodSettingsObj() {
	var file_class_method_obj = $(".file_class_method_obj");
	
	if (!file_class_method_obj[0])
		return {};
	
	var settings_elm = file_class_method_obj.children("#settings");
	
	var arguments = [];
	var items = settings_elm.children(".arguments").find(".fields .argument");
	for (var i = 0; i < items.length; i++) {
		var item = $(items[i]);
	
		var name = item.find(".name input").val();
		var value = item.find(".value input").val();
		var var_type = item.find(".var_type select").val();
	
		arguments.push({
			"name": name,
			"value": value,
			"var_type": var_type,
		});
	}
	
	var annotations = {};
	var items = settings_elm.children(".annotations").find(".fields .annotation");
	for (var i = 0; i < items.length; i++) {
		var item = $(items[i]);
		
		var annotation_type = item.find(".annotation_type select").val();
		
		if (!annotations.hasOwnProperty(annotation_type)) {
			annotations[annotation_type] = [];
		}
		
		annotations[annotation_type].push({
			"name": item.find(".name input").val(), 
			"type": item.find(".type select").css("display") == "none" ? item.find(".type input").val() : item.find(".type select").val(), 
			"not_null": item.find(".not_null input").is(":checked") ? 1 : 0, 
			"default": item.find(".default input").val(), 
			"others": item.find(".others input").val(), 
			"desc": item.find(".description input").val(),
		});
	}
	
	var name = $(".top_bar .title > input.name").val();
	
	var obj = {
		"name": name,
		"type": settings_elm.children(".type").children("select").val(),
		"abstract": settings_elm.children(".abstract").children("input").prop("checked") ? 1 : 0,
		"static": settings_elm.children(".static").children("input").prop("checked") ? 1 : 0,
		"arguments": arguments,
		"annotations": annotations,
		"comments": settings_elm.children(".comments").children("textarea").val(),
	};
	
	if (typeof prepareFileClassMethodSettingsObj == "function") //inited in js/businesslogic/edit_method.js
		prepareFileClassMethodSettingsObj(obj);
	
	return obj;
}

function saveFileClassMethod() {
	var file_class_method_obj = $(".file_class_method_obj");
	
	prepareAutoSaveVars();
	
	var is_from_auto_save_bkp = is_from_auto_save; //backup the is_from_auto_save, bc if there is a concurrent process running at the same time, this other process may change the is_from_auto_save value.
	
	if (file_class_method_obj[0]) {
		if (!window.is_save_func_running) {
			window.is_save_func_running = true;
		
			if (is_from_auto_save_bkp && !isClassMethodObjChanged()) {
				resetAutoSave();
				window.is_save_func_running = false;
				return;
			}
			
			var obj = getFileClassMethodObj();
			
			//check if user is logged in
			//if there was a previous function that tried to execute an ajax request, like the getCodeForSaving method, we detect here if the user needs to login, and if yes, recall the save function again. 
			//Do not re-call only the ajax request below, otherwise there will be some other files that will not be saved, this is, the getCodeForSaving saves the workflow and if we only call the ajax request below, the workflow won't be saved. To avoid this situation, we call the all save function.
			if (!is_from_auto_save_bkp && jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL)) {
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, jquery_native_xhr_object.responseURL, function() {
					jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
					StatusMessageHandler.removeLastShownMessage("error");
					
					window.is_save_func_running = false;
					saveFileClassMethod();
				});
				
				return;
			}
			
			//call saveFileClassMethodObj
			saveFileClassMethodObj(obj, {
				complete: function() {
					window.is_save_func_running = false;
				},
			});
		}
		else if (!is_from_auto_save_bkp)
			StatusMessageHandler.showMessage("There is already a saving process running. Please wait a few seconds and try again...");
	}
	else if (!is_from_auto_save_bkp)
		alert("No object to save! Please contact the sysadmin...");
}

function saveFileClassMethodObj(obj, props) {
	var save_url = save_object_url.replace("#method_id#", original_method_id);
	
	props = props ? props : {};
	var func = props.success;
	
	props.success = function(data, textStatus, jqXHR) {
		var status = true;
		
		if (typeof func == "function")
			status = func(data, textStatus, jqXHR);
		
		if (status) {
			//update class_method_settings_id
			saved_class_method_settings_id = getFileClassMethodSettingsId();
			
			//checks if name changed
			if (original_method_id != obj["name"]) {
				original_method_id = obj["name"];
				
				if (window.parent && typeof window.parent.refreshLastNodeParentChilds == "function")
					window.parent.refreshLastNodeParentChilds();
				
				//replace new method name in url and refresh page
				var url = "" + document.location;
				var service_exists = url.match(/(&|\\?)service=[^&]+/);
				
				url = url.replace(/(&|\\?)(method|function)=[^&]*/g, "$1");
				url += "&" + (service_exists ? "method" : "function") + "=" + original_method_id;
				document.location = url;
			}
		}
		
		return status;
	};
	
	saveObjCode(save_url, obj, props);
}
