var loaded_function_or_method = {};
	
$(function () {
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var module_edit = $(".module_edit");
	module_edit.find(" > form > .buttons").children(".submit_button_save, .submit_button_add").children("input").attr("onclick", "return saveAdminModuleEditWorkerData(this);");
	
	module_edit.children(".main_title").after('<div class="toggle_advanced_fields">(<a href="javascript:void(0)" onClick="toggleWorkerAdvancedFields(this)">Toggle advanced options</a>)</div>');
	
	//prepare path_to_filter
	path_to_filter = path_to_filter.replace(/\/+/g, "/").replace(/^\//g, "").replace(/\/$/g, ""); //remove duplicated slashes and at the begin and at the end. Then converts path_to_filter to an array.
	
	//prepare trees
	chooseMethodFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : function(ul, data) {
			prepareLayerFileNodes1(ul, data, chooseMethodFromFileManagerTree);
		},
		ajax_callback_after : function(ul, data) {
			prepareLayerFileNodes2(ul, data, chooseMethodFromFileManagerTree, removeObjectPropertiesAndMethodsFromTreeForMethods);
		},
		path_to_filter: path_to_filter,
	});
	chooseMethodFromFileManagerTree.init("choose_method_from_file_manager");
	
	chooseFunctionFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : function(ul, data) {
			prepareLayerFileNodes1(ul, data, chooseFunctionFromFileManagerTree);
		},
		ajax_callback_after : function(ul, data) {
			prepareLayerFileNodes2(ul, data, chooseFunctionFromFileManagerTree, removeObjectPropertiesAndMethodsFromTreeForFunctions);
		},
		path_to_filter: path_to_filter,
	});
	chooseFunctionFromFileManagerTree.init("choose_function_from_file_manager");
	
	chooseFileFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : function(ul, data) {
			prepareLayerFileNodes1(ul, data, chooseFileFromFileManagerTree);
		},
		ajax_callback_after : function(ul, data) {
			prepareLayerFileNodes2(ul, data, chooseFileFromFileManagerTree, removeObjectPropertiesAndFunctionsFromTree);
		},
		path_to_filter: path_to_filter,
	});
	chooseFileFromFileManagerTree.init("choose_file_from_file_manager");
	
	if (js_load_function) {
		var args_elm = module_edit.find(".form_field.args");
		var selected_task_properties_elm = args_elm.children(".selected_task_properties");
		var set_array_task_elm = selected_task_properties_elm.children(".set_array_task_html");
		var args_type_select = selected_task_properties_elm.children(".args_type");
		
		set_array_task_elm.children(".result, .task_property_exit").remove();
		
		var args_encoded = args_elm.find(" > div > textarea").val();
		var args_type = "";
		
		try {
			args_type = args_encoded && args_encoded.charAt(0) == "{" && args_encoded.charAt(args_encoded.length - 1) == "}" && JSON.parse(args_encoded) ? "array" : "";
		}
		catch(e) {};
		
		selected_task_properties_elm.children(".args_code").val(args_encoded);
		
		if (args_type == "array")
			convertArgsCodeToArray(selected_task_properties_elm, args_encoded);
		else {
			js_load_function(selected_task_properties_elm, null, {});
			prepareWorkerArgsArrayItem(selected_task_properties_elm.find(" > .set_array_task_html > .array_items"));
		}
		
		args_type_select.val(args_type);
		onEditWorkerArgsTypeChange(args_type_select[0], true);
	}
	
	var select = module_edit.find(".form_field.type select");
	select.val( module_edit.find(".form_field.class input").val() );
	onChangeWorkerType(select[0]);
	
	MyFancyPopup.hidePopup();
});

function prepareLayerFileNodes1(ul, data, my_tree_obj) {
	var filter_path = false;
	var path_to_filter = my_tree_obj.options.path_to_filter;
	
	if (path_to_filter && data && data.properties) {
		var data_bean_name = data.properties.bean_name ? data.properties.bean_name : "";
		var data_bean_file_name = data.properties.bean_file_name ? data.properties.bean_file_name : "";
		
		if (data_bean_name == bean_name && data_bean_file_name == bean_file_name)
			filter_path = true;
	}
	
	if (filter_path) {
		//filter data by path
		var path_to_filter_parts = path_to_filter.split("/");
		var path_to_filter_parts_idx = getPathToFilterPartsIndex(ul, ".mytree");
		data = prepareDataAccordingWithPathToFilterIndex(data, path_to_filter, path_to_filter_parts, path_to_filter_parts_idx);
	}
	
	//create nodes based in data
	prepareLayerNodes1(ul, data);
}

function prepareLayerFileNodes2(ul, data, my_tree_obj, callback) {
	var filter_path = false;
	var path_to_filter = my_tree_obj.options.path_to_filter;
	
	if (path_to_filter && data && data.properties) {
		var data_bean_name = data.properties.bean_name ? data.properties.bean_name : "";
		var data_bean_file_name = data.properties.bean_file_name ? data.properties.bean_file_name : "";
		
		if (data_bean_name == bean_name && data_bean_file_name == bean_file_name)
			filter_path = true;
	}
	
	if (!filter_path) { 
		if (typeof callback == "function")
			callback(ul, data);
	}
	else {
		ul = $(ul);
		
		var path_to_filter_parts = null;
		var path_to_filter_parts_idx = null;
		
		//filter data by path
		if (path_to_filter != "" && data) {
			path_to_filter_parts = path_to_filter.split("/");
			path_to_filter_parts_idx = getPathToFilterPartsIndex(ul, ".mytree");
			data = prepareDataAccordingWithPathToFilterIndex(data, path_to_filter, path_to_filter_parts, path_to_filter_parts_idx);
		}
		
		if (typeof callback == "function")
			callback(ul, data);
		
		//open sub nodes according with path_to_filter
		if (path_to_filter != "" && path_to_filter_parts_idx >= 0 && path_to_filter_parts.length > path_to_filter_parts_idx) {
			var parents = ul.parentsUntil(".mytree");
			var main_layer_li = $(parents[ parents.length - 1 ]);
			
			//check if exists list from data
			var data_contains_filtered_items = false;
			for (var k in data)
				if (k != "properties" && k != "aliases") {
					data_contains_filtered_items = true;
					break;
				}
			
			if (data_contains_filtered_items) {
				//refresh created lis and show their sub-nodes
				var li = ul.children("li"); //There is only 1
				var sub_ul = li.children("ul");
				var sub_lis = sub_ul.children("li"); 
				
				li.removeClass("jstree-closed").addClass("jstree-open"); //add in case it doesn't have, so we can then show the inner folder that was created.
				
				if (sub_ul.children("li").length > 0) {
					var key = path_to_filter_parts[path_to_filter_parts_idx];
					var sub_data = data.hasOwnProperty(key) && $.isPlainObject(data[key]) ? data[key] : {};
					
					if (!sub_data.hasOwnProperty("properties"))
						sub_data.properties = {};
						
					sub_data.properties["bean_name"] = data.properties.bean_name;
					sub_data.properties["bean_file_name"] = data.properties.bean_file_name;
					
					prepareLayerFileNodes2(sub_ul[0], sub_data, my_tree_obj); //without the callback bc it was already called before
				}
				else {
					sub_ul.show();
					my_tree_obj.refreshNodeChilds(li);
				}
				
				//make last node as primary node
				if (path_to_filter_parts_idx == path_to_filter_parts.length - 1) {
					var main_ul = main_layer_li.children("ul");
					main_layer_li.append(sub_ul);
					main_ul.remove(); //I can only remove the old ul after add the new ul, otherwise I will loose all events from the new ul...
					
					//disable path_to_filter
					my_tree_obj.options.path_to_filter = "";
				}
				//else do nothing bc it exists childs and path_to_filter didn't end yet...
			}
			else { //if there are no filtered childs
				main_layer_li.children("ul").children("li").remove(); //delete all children
			}
		}
	}
}

function onChangeWorkerType(elm) {
	elm = $(elm);
	var type = elm.val();
	var p = elm.parent().closest(".form_fields");
	var class_elm = p.children(".class");
	var function_fields = p.children(".function");
	var class_method_fields = p.children(".class_method");
	var send_email_fields = p.children(".send_email");
	var args_elm = p.children(".args");
	var args_type_elm = args_elm.find(" > .selected_task_properties > .args_type");
	
	if (type) {
		if (type == "app.layer." + layer_folder_name + ".common.src.module.workerpool.work.CallExternalFunctionWorkerPoolWork") {
			class_elm.hide();
			function_fields.show();
			class_method_fields.hide();
			send_email_fields.hide();
			args_elm.show();
		}
		else if (type == "app.layer." + layer_folder_name + ".common.src.module.workerpool.work.CallExternalClassMethodWorkerPoolWork") {
			class_elm.hide();
			function_fields.hide();
			class_method_fields.show();
			send_email_fields.hide();
			args_elm.show();
		}
		else {
			class_elm.hide();
			function_fields.hide();
			class_method_fields.hide();
			send_email_fields.show();
			args_elm.hide();
		}
		
		//if json code is empty, show array ui.
		if (args_type_elm.val() == "" && args_type_elm.parent().children(".args_code").val() == "") {
			args_type_elm.val("array");
			onEditWorkerArgsTypeChange(args_type_elm[0], true);
		}
	}
	else {
		class_elm.show();
		function_fields.hide();
		class_method_fields.hide();
		send_email_fields.hide();
		args_elm.show();
	}
}

function onEditWorkerArgsTypeChange(elm, do_not_convert) {
	elm = $(elm);
	var args_type = elm.val();
	var p = elm.parent();
	var args_code_elm = p.children(".args_code");
	var set_array_task_elm = p.children(".set_array_task_html");
	
	if (args_type == "array") {
		var args_code = args_code_elm.val();
		
		if (!do_not_convert && confirm("Do you wish to convert this json code into an array?")) {
			try {
				convertArgsCodeToArray(p, args_code ? args_code : "{}");
			}
			catch (e) {
				alert("Error trying to convert json into array.\n" + (e.message ? e.message : e));
			}
		}
		
		args_code_elm.hide();
		set_array_task_elm.show();
	}
	else {
		if (!do_not_convert && confirm("Do you wish to convert this array into json code?"))
			args_code_elm.val( getJsonCodeFromArgsArray(set_array_task_elm) );
		
		args_code_elm.show();
		set_array_task_elm.hide();
	}
}

function convertArgsCodeToArray(selected_task_properties_elm, args_encoded) {
	var args_values = args_encoded && args_encoded.charAt(0) == "{" && args_encoded.charAt(args_encoded.length - 1) == "}" ? JSON.parse(args_encoded) : null;
	
	if (args_values) {
		var array_items = FormFieldsUtilObj.convertFormSettingsObjectToArray(args_values);
		
		if (array_items)
			array_items = {items: array_items};
		
		js_load_function(selected_task_properties_elm, null, array_items);
		prepareWorkerArgsArrayItem(selected_task_properties_elm.find(" > .set_array_task_html > .array_items"));
	}
}

function addArrayGroupToWorkerArgs(elm) {
	var res = ArrayTaskUtilObj.addGroup(elm);
	
	var item = $(elm).parent().parent().children("ul").first().children("li").last();
	prepareWorkerArgsArrayItem(item);
	
	return item;
}
function addArrayItemToWorkerArgs(elm) {
	var item = ArrayTaskUtilObj.addItem(elm);
	
	prepareWorkerArgsArrayItem(item);
	
	return item;
}
function prepareWorkerArgsArrayItem(elm) {
	$(elm).find(".value_type").each(function(idx, select) {
		select = $(select);
		var opt = select.children("option").first();
		
		if (!opt.val())
			opt.remove(); //remove first option that is the option to write php code. PHP CODE is not allowed here!
	});
	
	$(elm).find(".items").each(function(idx, item) {
		item = $(item);
		item.children(".group_add").attr("onclick", "addArrayGroupToWorkerArgs(this)");
		item.children(".item_add").attr("onclick", "addArrayItemToWorkerArgs(this)");
	});
}

function getJsonCodeFromArgsArray(set_array_task_elm) {
	var WF = myWFObj.getJsPlumbWorkFlow();
	var query_string = WF.jsPlumbProperty.getPropertiesQueryStringFromHtmlElm(set_array_task_elm, "task_property_field");
	var args_data = {};
	parse_str(query_string, args_data);
	
	if (args_data)
		args_data = args_data["items"];
	
	var args_settings = FormFieldsUtilObj.convertFormSettingsDataArrayToSettings(args_data);
	json_code = JSON.stringify(args_settings);
	
	return json_code;
}

function onIncludeWorkerFileTaskChooseFile(elm) {
	var popup = $("#choose_file_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		
		targetField: $(elm).parent(),
		updateFunction: chooseIncludeWorkerFile
	});
	
	MyFancyPopup.showPopup();
}

function chooseIncludeWorkerFile(elm) {
	var node = chooseFileFromFileManagerTree.getSelectedNodes();
	var include_path = getFileManagerTreeNodePHPFilePath(node[0]);
	
	if (include_path) {
		MyFancyPopup.settings.targetField.find("input").val(include_path);
		MyFancyPopup.hidePopup();
	}
	else
		alert("Invalid selected file.\nPlease choose a valid PHP file.");
}

function getFileManagerTreeNodePHPFilePath(node) {
	if (node) {
		var a = $(node).children("a");
		var file_path = a.attr("file_path");
		var bean_name = a.attr("bean_name");
		
		if (file_path && bean_name && layer_types_prefix_paths[bean_name]) {
			var is_php = file_path.substr(file_path.length - 4).toLowerCase() == ".php";
			
			if (is_php) {
				var include_path = layer_types_prefix_paths[bean_name] + file_path.substr(0, file_path.length - 4);
				include_path = include_path.replace(/\/+/g, "/").replace(/\//g, ".");
				
				return include_path;
			}
		}
	}
	
	return null;
}

function onIncludeWorkerFileTaskChooseFunction(elm) {
	var popup = $("#choose_function_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		
		targetField: $(elm).parent().find("input")[0],
		updateFunction: chooseIncludeWorkerFunction
	});
	
	MyFancyPopup.showPopup();
}

function chooseIncludeWorkerFunction(elm) {
	var popup = $("#choose_function_from_file_manager");
	var select = popup.find(".function select");
	var value = select.val();
	
	if (value) {
		var function_file_input = $(MyFancyPopup.settings.targetField);
		var p = function_file_input.parent().closest(".form_fields");
		var function_name_input = p.find(" > .function_name input");
		
		var node = chooseFunctionFromFileManagerTree.getSelectedNodes();
		var file_path = getFileManagerTreeNodePHPFilePath(node[0]);
		function_file_input.val(file_path);
		
		function_name_input.val(value ? value : "");
		
		var args = getFunctionArguments( select.attr("get_file_properties_url"), select.attr("file_path"), value);
		showArgsInfo(function_name_input.parent().closest(".function_name"), args);
		
		if (value && confirm("Do you wish to update automatically this function arguments?")) 
			prepareArgsArray( function_name_input.parent().closest(".form_fields").find(".args > .selected_task_properties"), args);
		
		MyFancyPopup.hidePopup();
	}
	else
		alert("Invalid selected function.\nPlease choose a valid function.");
}

function onIncludeWorkerFileTaskChooseClassMethod(elm) {
	var popup = $("#choose_method_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		
		targetField: $(elm).parent().find("input")[0],
		updateFunction: chooseIncludeWorkerClassMethod
	});
	
	MyFancyPopup.showPopup();
}

function chooseIncludeWorkerClassMethod(elm) {
	var popup = $("#choose_method_from_file_manager");
	var select = popup.find(".method select");
	
	var value = select.val();
	
	if (value) {
		var selected_option = select.find(":selected").first();
		var is_static = selected_option.attr("is_static");
		
		if (is_static == "1") {
			var class_method_file_input = $(MyFancyPopup.settings.targetField);
			var p = class_method_file_input.parent().closest(".form_fields");
			var class_name_input = p.find(" > .class_name input");
			var method_name_input = p.find(" > .method_name input");
			
			var node = chooseMethodFromFileManagerTree.getSelectedNodes();
			var file_path = getFileManagerTreeNodePHPFilePath(node[0]);
			class_method_file_input.val(file_path);
			
			var class_name = select.attr("class_name");
			class_name_input.val(class_name ? class_name : "");
			method_name_input.val(value);
			
			var args = getMethodArguments( select.attr("get_file_properties_url"), select.attr("file_path"), class_name, value);
			showArgsInfo(class_name_input.parent().closest(".class_name"), args);
			
			if (confirm("Do you wish to update automatically this method arguments?"))
				prepareArgsArray(p.find(".args > .selected_task_properties"), args);
			
			MyFancyPopup.hidePopup();
		}
		else
			alert("Invalid selected method. The choosen method must be a Static method.");
	}
	else
		alert("Invalid selected method.\nPlease choose a valid static method.");
}

function showArgsInfo(elm, args) {
	var html = '';
	
	if (args && ($.isArray(args) || $.isPlainObject(args)))
		$.each(args, function(idx, arg) {
			var name = arg["name"];
			var value_exists = arg.hasOwnProperty("value");
			var value = arg["value"];
			var type = arg["type"];
			
			html += '<li>$' + name + (value_exists ? ' = ' + (type == "string" ? '"' + value + '"' : value) : '') + '</li>';
		});
	else
		html = '<li>No arguments needed!</li>';
	
	var args_info = elm.children(".args_info");
	
	if (!args_info[0]) {
		args_info = $('<div class="args_info"><label>This function receives the following arguments:</label><ul></ul></div>');
		elm.append(args_info);
	}
	
	args_info.children("ul").html(html);
	args_info.show();
}

function prepareArgsArray(selected_task_properties_elm, args) {
	var args_values = {};
	
	var select = selected_task_properties_elm.children(".args_type");
	select.val("array");
	onEditWorkerArgsTypeChange(select[0], true);
	
	if (args && ($.isArray(args) || $.isPlainObject(args)))
		$.each(args, function(idx, arg) {
			var name = arg["name"];
			var value_exists = arg.hasOwnProperty("value");
			var value = arg["value"];
			var type = arg["type"];
			
			if (type == "array") {
				try {
					value = JSON.parse(value);
				}
				catch(e) {};
			}
			
			args_values[name] = value_exists ? value : null;
		});
	
	var array_items = FormFieldsUtilObj.convertFormSettingsObjectToArray(args_values);
	
	if (array_items)
		array_items = {items: array_items};
	
	js_load_function(selected_task_properties_elm, null, array_items);
	prepareWorkerArgsArrayItem(selected_task_properties_elm.find(" > .set_array_task_html > .array_items"));
}

function toggleWorkerAdvancedFields(elm) {
	$(elm).parent().closest(".module_edit").find(".worker_advanced_field").toggle();
}

function saveAdminModuleEditWorkerData(elm) {
	var args_elm = $(elm).parent().closest(".module_edit").find(".form_field.args");
	var selected_task_properties_elm = args_elm.children(".selected_task_properties");
	var set_array_task_elm = selected_task_properties_elm.children(".set_array_task_html");
	var args_type_select = selected_task_properties_elm.children(".args_type");
	var args_type = args_type_select.val();
	var json_code= "";
	
	if (args_type == "array")
		json_code = getJsonCodeFromArgsArray(set_array_task_elm);
	else
		json_code = selected_task_properties_elm.children(".args_code").val();
	
	args_elm.find(" > div > textarea").val(json_code);
	
	return true;
}
