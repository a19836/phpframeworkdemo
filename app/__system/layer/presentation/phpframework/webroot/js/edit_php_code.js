var choosePropertyVariableFromFileManagerTree = null;
var chooseMethodFromFileManagerTree = null;
var chooseFunctionFromFileManagerTree = null;
var chooseFileFromFileManagerTree = null;
var chooseBusinessLogicFromFileManagerTree = null;
var chooseQueryFromFileManagerTree = null;
var chooseHibernateObjectFromFileManagerTree = null;
var chooseHibernateObjectMethodFromFileManagerTree = null;
var choosePresentationFromFileManagerTree = null;
var chooseBlockFromFileManagerTree = null;
var choosePageUrlFromFileManagerTree = null; //used by the create_presentation_uis_diagram.js and module/menu/show_menu/settings.js and others
var chooseImageUrlFromFileManagerTree = null; //used by the create_presentation_uis_diagram.js and module/menu/show_menu/settings.js and others

var brokers_db_drivers = {};
var auto_scroll_active = true;
var word_wrap_active = false;
var show_low_code_first = true;

$(function () {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
});

/* AUTO SAVE & CONVERT FUNCTIONS */

function onTogglePHPCodeAutoSave() {
	var lis = $("#code .code_menu ul li.auto_save_activation, #ui .taskflowchart .workflow_menu ul.dropdown li.auto_save_activation, #code .layout-ui-editor > .options li.auto_save_activation");
	var inputs = lis.find("input");
	var spans = lis.find("span");
	
	if (auto_save) {
		jsPlumbWorkFlow.jsPlumbTaskFile.auto_save = false; //should be false bc the saveObj calls the getCodeForSaving method which already saves the workflow by default, and we don't need 2 saves at the same time.
		jsPlumbWorkFlow.jsPlumbProperty.auto_save = true;
		$(".taskflowchart").removeClass("auto_save_disabled");
		
		lis.addClass("active");
		inputs.attr("checked", "checked").prop("checked", true);
		spans.html("Disable Auto Save");
	}
	else {
		jsPlumbWorkFlow.jsPlumbTaskFile.auto_save = false;
		jsPlumbWorkFlow.jsPlumbProperty.auto_save = false;
		$(".taskflowchart").addClass("auto_save_disabled");
		
		lis.removeClass("active");
		inputs.removeAttr("checked", "checked").prop("checked", false);
		spans.html("Enable Auto Save");
	}
}

function onTogglePHPCodeAutoConvert() {
	var lis = $("#code .code_menu ul li.auto_convert_activation, #ui .taskflowchart .workflow_menu ul.dropdown li.auto_convert_activation, #code .layout-ui-editor > .options li.auto_convert_activation");
	var inputs = lis.find("input");
	var spans = lis.find("span");
	
	if (auto_convert) {
		lis.addClass("active");
		inputs.attr("checked", "checked").prop("checked", true);
		spans.html("Disable Auto Convert");
	}
	else {
		lis.removeClass("active");
		inputs.removeAttr("checked").prop("checked", false);
		spans.html("Enable Auto Convert");
	}
	
	var PtlLayoutUIEditor = $(".layout-ui-editor").data("LayoutUIEditor");
	
	if (PtlLayoutUIEditor) 
		PtlLayoutUIEditor.options.auto_convert = auto_convert;
}

/* TREE FUNCTIONS */

function removeObjectPropertiesAndMethodsFromTreeForVariables(ul, data) {
	$(ul).find(".function, i.undefined_file, i.css_file, i.js_file, i.img_file, .webroot_folder").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
	
	$(ul).find("i.folder").each(function(idx, elm){
		var label = $(elm).parent().children("label").text();
		
		if (label == "webroot") {
			$(elm).parent().parent().remove();
		}
	});
	
	$(ul).find("i.file, i.service, i.class, i.test_unit_obj, i.objtype, i.hibernatemodel, i.config_file, i.controller_file, i.entity_file, i.view_file, i.template_file, i.util_file, i.block_file, i.module_file").each(function(idx, elm){
		elm = $(elm);
		var a = elm.parent();
		var li = a.parent();
		var class_name = a.children("label").text();
		var file_path = a.attr("file_path");
		var bean_name = a.attr("bean_name");
		var get_file_properties_url = a.attr("get_file_properties_url");
		
		if (elm.is(".file, i.util_file, i.module_file")) {
			if (!file_path || !("" + file_path).match(/\.php([0-9]*)$/i)) { //is not a php file
				li.remove();
				class_name = null;
			}
			else {
				var children = li.find(" > ul > li > a").children("i.service, i.class, i.test_unit_obj");
				
				if (children.length == 1) {
					class_name = $(children[0]).parent().children("label").text();
					var regex = new RegExp("\/" + class_name + "\.php([0-9]*)$", "i");
					
					if (("" + file_path).match(regex))
						li.children("ul").remove();
				}
			}
		}
		else
			li.children("ul").remove();
		
		if (class_name)
			a.click(function() {
				var items = getClassProperties(get_file_properties_url, file_path, class_name);
				var elm = $(MyFancyPopup.settings.elementToShow).find(".property select");
				elm.attr("class_name", class_name);
				elm.attr("file_path", file_path);
				elm.attr("bean_name", bean_name);
				elm.attr("get_file_properties_url", get_file_properties_url);
				
				updateSelectElementWithFileItems(elm, items);
			});
	});
}

function removeObjectPropertiesAndMethodsFromTreeForMethods(ul, data) {
	$(ul).find(".function, i.undefined_file, i.css_file, i.js_file, i.img_file, .webroot_folder").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
	
	$(ul).find("i.folder").each(function(idx, elm){
		var label = $(elm).parent().children("label").text();
		
		if (label == "webroot") {
			$(elm).parent().parent().remove();
		}
	});
	
	$(ul).find("i.file, i.service, i.class, i.test_unit_obj, i.objtype, i.hibernatemodel, i.config_file, i.controller_file, i.entity_file, i.view_file, i.template_file, i.util_file, i.block_file, i.module_file").each(function(idx, elm) {
		elm = $(elm);
		var a = elm.parent();
		var li = a.parent();
		var class_name = a.children("label").text();
		var file_path = a.attr("file_path");
		var bean_name = a.attr("bean_name");
		var get_file_properties_url = a.attr("get_file_properties_url");
		
		if (elm.is(".file, i.util_file, i.module_file")) {
			if (!file_path || !("" + file_path).match(/\.php([0-9]*)$/i)) { //is not a php file
				li.remove();
				class_name = null;
			}
			else {
				var children = li.find(" > ul > li > a").children("i.service, i.class, i.test_unit_obj");
				
				if (children.length == 1) {
					class_name = $(children[0]).parent().children("label").text();
					var regex = new RegExp("\/" + class_name + "\.php([0-9]*)$", "i");
					
					if (("" + file_path).match(regex))
						li.children("ul").remove();
				}
			}
		}
		else
			li.children("ul").remove();
		
		if (class_name)
			a.click(function() {
				var items = getClassMethods(get_file_properties_url, file_path, class_name);
				var elm = $(MyFancyPopup.settings.elementToShow).find(".method select");
				elm.attr("class_name", class_name);
				elm.attr("file_path", file_path);
				elm.attr("bean_name", bean_name);
				elm.attr("get_file_properties_url", get_file_properties_url);
			
				updateSelectElementWithFileItems(elm, items);
			});
	});
}

function removeObjectPropertiesAndMethodsFromTreeForFunctions(ul, data) {
	$(ul).find("i.service, i.class, i.test_unit_obj, i.undefined_file, i.css_file, i.js_file, i.img_file, .webroot_folder").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
	
	$(ul).find("i.folder").each(function(idx, elm){
		var label = $(elm).parent().children("label").text();
		
		if (label == "webroot") {
			$(elm).parent().parent().remove();
		}
	});
	
	$(ul).find("i.file, i.objtype, i.hibernatemodel, i.config_file, i.controller_file, i.entity_file, i.view_file, i.template_file, i.util_file, i.block_file, i.module_file").each(function(idx, elm){
		elm = $(elm);
		var a = elm.parent();
		var li = a.parent();
		var file_path = a.attr("file_path");
		var bean_name = a.attr("bean_name");
		var get_file_properties_url = a.attr("get_file_properties_url");
		
		if (elm.is(".file, i.util_file, i.module_file")) {
			if (!file_path || !("" + file_path).match(/\.php([0-9]*)$/i)) //is not a php file
				li.remove();
			else
				li.children("ul").remove(); //remove functions from file
		}
		else
			li.children("ul").remove();
		
		a.click(function() {
			var items = getFileFunctions(get_file_properties_url, file_path);
			var elm = $(MyFancyPopup.settings.elementToShow).find(".function select");
			elm.attr("file_path", file_path);
			elm.attr("bean_name", bean_name);
			elm.attr("get_file_properties_url", get_file_properties_url);
			
			updateSelectElementWithFileItems(elm, items);
		});
	});
}

function removeObjectPropertiesAndFunctionsFromTree(ul, data) {
	$(ul).find("i.function").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
	
	$(ul).find("i.file, i.objtype, i.hibernatemodel").each(function(idx, elm){
		elm = $(elm);
		var a = elm.parent();
		var li = a.parent();
		var file_path = a.attr("file_path");
		var bean_name = a.attr("bean_name");
		var get_file_properties_url = a.attr("get_file_properties_url");
		
		if (!elm.hasClass("file"))
			li.children("ul").remove();
		
		a.click(function() {
			var items = getFileFunctions(get_file_properties_url, file_path);
			var elm = $(MyFancyPopup.settings.elementToShow).find(".businesslogic select");
			elm.attr("class_name", "");
			elm.attr("file_path", file_path);
			elm.attr("bean_name", bean_name);
			elm.attr("get_file_properties_url", get_file_properties_url);
			
			updateSelectElementWithFileItems(elm, items);
		});
	});
	
	$(ul).find("i.service, i.class, i.test_unit_obj").each(function(idx, elm){
		var a = $(elm).parent();
		
		a.parent().children("ul").remove();
		
		var class_name = a.children("label").text();
		var file_path = a.attr("file_path");
		var bean_name = a.attr("bean_name");
		var get_file_properties_url = a.attr("get_file_properties_url");
		
		a.click(function() {
			var items = getClassMethods(get_file_properties_url, file_path, class_name);
			var elm = $(MyFancyPopup.settings.elementToShow).find(".businesslogic select");
			elm.attr("class_name", class_name);
			elm.attr("file_path", file_path);
			elm.attr("bean_name", bean_name);
			elm.attr("get_file_properties_url", get_file_properties_url);
			
			updateSelectElementWithFileItems(elm, items);
		});
	});
}

function removeParametersAndResultMapsFromTree(ul, data) {
	$(ul).find("i.map").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
}

function removeQueriesAndMapsAndOtherHbnNodesFromTree(ul, data) {
	$(ul).find("i.query, i.map, i.relationship, i.hbn_native").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
}

function removeParametersAndResultMapsFromTree(ul, data) {
	$(ul).find("i.map").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
}

function removeAllThatIsNotPagesFromTree(ul, data) {
	ul = $(ul);
	
	ul.find("i.file, i.view_file, i.template_file, i.util_file, i.controller_file, i.config_file, i.undefined_file, i.js_file, i.css_file, i.img_file, i.properties, i.block_file, i.module_file, .views_folder, .templates_folder, .template_folder, .utils_folder, .webroot_folder, .modules_folder, .blocks_folder, .configs_folder").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
	
	ul.find("i.folder").each(function(idx, elm){
		var label = $(elm).parent().children("label").text();
		
		if (label == "views" || label == "templates" || label == "utils" || label == "webroot" || label == "others" || label == "modules" || label == "blocks" || label == "configs") 
			$(elm).parent().parent().remove();
		//else if (label == "pages (entities)") 
		//	$(elm).parent().parent().addClass("jstree-last");
	});
	
	//move pages to project node
	ul.find("i.entities_folder").each(function(idx, elm) {
		var entities_li = $(elm).parent().parent();
		var entities_ul = entities_li.children("ul");
		var project_li = entities_li.parent().parent();
		var project_ul = project_li.children("ul");
		
		project_li.append(entities_ul);
		project_ul.remove();
	});
}

function removeAllThatIsNotAPossibleImageFromTree(ul, data) {
	ul = $(ul);
	
	ul.find("i.file, i.view_file, i.template_file, i.util_file, i.controller_file, i.config_file, i.js_file, i.css_file, i.properties, i.block_file, i.module_file, .views_folder, .templates_folder, .template_folder, .utils_folder, .modules_folder, .blocks_folder, .configs_folder").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
	
	ul.find("i.folder").each(function(idx, elm){
		var label = $(elm).parent().children("label").text();
		
		if (label == "views" || label == "templates" || label == "utils" || label == "others" || label == "modules" || label == "blocks" || label == "configs") 
			$(elm).parent().parent().remove();
		//else if (label == "webroot") 
		//	$(elm).parent().parent().addClass("jstree-last");
	});
	
	ul.find("i.webroot_folder").each(function(idx, elm) {
		$(elm).parent().parent().addClass("jstree-last");
	});
}

function removeAllThatIsNotBlocksFromTree(ul, data) {
	ul = $(ul);
	
	ul.find("i.file, i.view_file, i.entity_file, i.template_file, i.util_file, i.controller_file, i.config_file, i.undefined_file, i.js_file, i.css_file, i.img_file, i.properties, i.module_file, .entities_folder, .views_folder, .templates_folder, .template_folder, .utils_folder, .webroot_folder, .modules_folder, .configs_folder").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
	
	ul.find("i.folder").each(function(idx, elm){
		var label = $(elm).parent().children("label").text();
		
		if (label == "pages (entities)" || label == "views" || label == "templates" || label == "utils" || label == "webroot" || label == "others" || label == "modules" || label == "configs")
			$(elm).parent().parent().remove();
		//else if (label == "blocks")
		//	$(elm).parent().parent().addClass("jstree-last");
	});
	
	//move pages to project node
	ul.find("i.blocks_folder").each(function(idx, elm) {
		var blocks_li = $(elm).parent().parent();
		var blocks_ul = blocks_li.children("ul");
		var project_li = blocks_li.parent().parent();
		var project_ul = project_li.children("ul");
		
		project_li.append(blocks_ul);
		project_ul.remove();
	});
}

/* UTIL FUNCTIONS */

function updateSelectElementWithFileItems(elm, items) {
	var options_html = "";
	
	if (items && items.length > 0) {
		var class_name = elm.attr("class_name");
		
		for (var i = 0; i < items.length; i++) {
			var item = items[i];
			var name = item["name"] ? item["name"] : "";
			var is_static = item["static"] == "1" ? 1 : 0;
			var is_hidden = item["hidden"] == "1" ? 1 : 0;
			
			if (!is_hidden) {
				options_html += '<option is_static="' + is_static + '" class_name="' + (class_name ? class_name : "") + '">' + name + '</option>';
			}
		}
	}
	
	elm.html(options_html);
}

function getClassProperties(url, file_path, class_name) {
	url = url.replace("#path#", file_path).replace("#class_name#", class_name).replace("#type#", "properties");
	
	return getFileProperties(url);
}

function getClassMethods(url, file_path, class_name) {
	url = url.replace("#path#", file_path).replace("#class_name#", class_name).replace("#type#", "methods");
	
	return getFileProperties(url);
}

function getFileFunctions(url, file_path) {
	url = url.replace("#path#", file_path).replace("#class_name#", "").replace("#type#", "functions");
	
	return getFileProperties(url);
}

function getMethodArguments(url, file_path, class_name, method_name) {
	url = url.replace("#path#", file_path).replace("#class_name#", class_name).replace("#type#", "arguments") + "&method=" + method_name;
	
	return getFileProperties(url);
}

function getFunctionArguments(url, file_path, func_name) {
	url = url.replace("#path#", file_path).replace("#class_name#", "").replace("#type#", "arguments") + "&function=" + func_name;
	
	return getFileProperties(url);
}

function getQueryParameters(bean_file_name, bean_name, db_driver, db_type, file_path, query_type, query_id, hbn_obj_id, relationship_type) {
	var url = get_query_properties_url.replace("#bean_file_name#", bean_file_name).replace("#bean_name#", bean_name).replace("#db_driver#", db_driver).replace("#db_type#", db_type).replace("#path#", file_path).replace("#query_type#", query_type).replace("#query#", query_id).replace("#obj#", hbn_obj_id).replace("#relationship_type#", relationship_type);
	
	return getFileProperties(url);
}

function getBusinessLogicParameters(bean_file_name, bean_name, file_path, service_id) {
	var url = get_business_logic_properties_url.replace("#bean_file_name#", bean_file_name).replace("#bean_name#", bean_name).replace("#path#", file_path).replace("#service#", service_id);
	
	return getFileProperties(url);
}

function getBrokerDBDrivers(broker, bean_file_name, bean_name, item_type) {
	var url = get_broker_db_drivers_url.replace("#bean_file_name#", bean_file_name).replace("#bean_name#", bean_name).replace("#broker#", broker).replace("#item_type#", item_type);
	
	return getFileProperties(url);
}

function getFileProperties(url) {
	var props = null;
	
	$.ajax({
		type : "get",
		url : url,
		dataType : "json",
		success : function(data, textStatus, jqXHR) {
			if (data) {
				props = data;
			}
		},
		async : false,
	});
	
	return props;
}

/* POPUP FUNCTIONS */

function getTargetFieldForProgrammingTaskChooseFromFileManager(elm) {
	var target_field = null;
	var p = elm.parent();
	var input_selector = elm.attr("input_selector");
	
	if (input_selector && p.find(input_selector).length > 0)
		target_field = p.find(input_selector).first();
	else if (elm.prev("input").is("input"))
		target_field = elm.prev();
	else if (elm.next("input").is("input"))
		target_field = elm.next();
	else
		target_field = p.find("input").first();
	
	if (!target_field[0]) {
		if (elm.prev("textarea").is("textarea"))
			target_field = elm.prev();
		else if (elm.next("textarea").is("textarea"))
			target_field = elm.next();
		else
			target_field = p.find("textarea").first();
	}
	
	return target_field;
}

function onChangePropertyVariableType(elm) {
	elm = $(elm);
	var type = elm.val();
	
	var main_elm = elm.parent().parent();
	
	main_elm.children(".variable_type").hide();
	main_elm.children("." + type).show();
	
	MyFancyPopup.updatePopup();
}

function onProgrammingTaskChooseCreatedVariable(elm) {
	elm = $(elm);
	var popup = $("#choose_property_variable_from_file_manager");
	var target_field = getTargetFieldForProgrammingTaskChooseFromFileManager(elm);
	
	if (target_field[0]) {
		popup.children(".type").show();
		
		var type_select = popup.find(".type select");
		onChangePropertyVariableType(type_select[0]);
		
		MyFancyPopup.init({
			elementToShow: popup,
			parentElement: document,
			onOpen: function() {
				var select = popup.find(".existent_var .variable select");
				var total = populateVariablesOfTheWorkflowInSelectField(select);
				
				if (total == 0) {
					type_select.find("option[value=existent_var]").hide();
					
					if (type_select.val() == "existent_var") {
						type_select.val("");
						onChangePropertyVariableType(type_select[0]);
					}
				}
				else
					popup.find(".type select option[value=existent_var]").show();
			},
			
			targetField: target_field[0],
			updateFunction: chooseCreatedVariable
		});
		
		MyFancyPopup.showPopup();
	}
	else
		StatusMessageHandler.showMessage("No targeted field found!");
}

function populateVariablesOfTheWorkflowInSelectField(select) {
	var total = 0;
	
	if (typeof ProgrammingTaskUtil == "object" && ProgrammingTaskUtil.variables_in_workflow) {
		var default_option = select.val();
		
		var options_html = "<option></option>";
		for (var var_name in ProgrammingTaskUtil.variables_in_workflow) {
			options_html += "<option>" + var_name + "</option>";
			
			total++;
		}

		select.html(options_html);
		select.val(default_option);
	}
	
	return total;
}

function chooseCreatedVariable(elm) {
	var popup = $("#choose_property_variable_from_file_manager");
	var type = popup.find(".type select").val();
	var type_elm = popup.find("." + type);
	var value = null;
	
	if (type == "existent_var")
		value = type_elm.find("select").val();
	else if (type == "class_prop_var") {
		var select = type_elm.find(".property select");
		var option = select.find(":selected").first();
		var class_name = option.attr("class_name");
		value = select.val();
		
		if (class_name && value) {
			if (option.attr("is_static") == "1")
				value = option.attr("class_name") + "::" + value;
			else
				value = '$' + option.attr("class_name") + "->" + value;
		}
	}
	else if (type) {
		value = type_elm.find("input").val();
		value = ("" + value).replace(/^\s+/g, "").replace(/\s+$/g, "");
		
		if (value) {
			value = value[0] == '$' ? value.substr(1, value.length) : value; //remove $ if exists
			
			if (type == "new_var")
				value = getNewVarWithSubGroupsInProgrammingTaskChooseCreatedVariablePopup(type_elm, value, true);
			
			value = '$' + value; //adds '$'
		}
	}
	
	var input = $(MyFancyPopup.settings.targetField);
	input.val(value ? value : "");
	
	//update var_type if exists
	var var_type = input.parent().parent().find(".var_type select");
	var_type.val("");
	var_type.trigger("change");
	
	//set value_type if exists and if only input name is simple without "]" and "[" chars:
	var input_name = input.attr("name");
	
	if (input_name && !input_name.match(/[\[\]]/)) {
		var input_type = input.parent().children("select[name=" + input_name + "_type]");
		
		if (input_type[0])
			input_type.val("");
	}
	else if (input.is(".value") && input.parent().is(".item")) //in case of array items
		input.parent().children(".value_type").val("");
	else if (input.is(".var") && input.parent().is(".item")) { //in case of conditions items
		//fins the next sibling with class .var_type
		var node = input;
		
		do {
			var next = node.next();
			
			if (next.hasClass("var_type")) {
				next.val("");
				break;
			}
			
			node = next;
		} 
		while(next[0]);
	}
	
	//put cursor in targetField
	input.focus();
	
	MyFancyPopup.hidePopup();
}
function getNewVarWithSubGroupsInProgrammingTaskChooseCreatedVariablePopup(type_elm, value, with_quotes) {
	var group = type_elm.find("select").val();
	var quotes = with_quotes ? "'" : ""; //with or without single quotes
	
	if (group)
		value = group + "[" + quotes + value + quotes + "]";
	
	var lis = type_elm.find(".sub_group > li");
	
	for (var i = 0; i < lis.length; i++) {
		var li = $(lis[i]);
		var sub_group_value = li.children("input").val();
		
		value += "[" + quotes + sub_group_value + quotes + "]";
	};
	
	return value;
}
function addNewVarSubGroupToProgrammingTaskChooseCreatedVariablePopup(elm) {
	var sub_groups = $(elm).parent().find(".sub_group").last();
	var sub_group = $('<li><input /><span class="icon delete" onClick="removeNewVarSubGroupToProgrammingTaskChooseCreatedVariablePopup(this)" title="Remove">Remove</span><ul class="sub_group"></ul></li>');
	
	sub_groups.append(sub_group);
}
function removeNewVarSubGroupToProgrammingTaskChooseCreatedVariablePopup(elm) {
	$(elm).parent().closest("li").remove();
}

function onProgrammingTaskChooseObjectProperty(elm) {
	var popup = $("#choose_property_variable_from_file_manager");
	
	var select = popup.find(".type select");
	select.val("class_prop_var");
	onChangePropertyVariableType(select[0]);
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			popup.children(".type").hide();
			select.find("option[value=new_var], option[value=existent_var]").hide();
		},
		onClose: function() {
			popup.children(".type").show();
			select.find("option[value=new_var], option[value=existent_var]").show();
		},
		
		targetField: $(elm).parent().find("input")[0],
		updateFunction: chooseObjectProperty
	});
	
	MyFancyPopup.showPopup();
}

function chooseObjectProperty(elm) {
	var popup = $("#choose_property_variable_from_file_manager");
	var select = popup.find(".class_prop_var .property select");
	
	var value = select.val();
	
	var dest = $(MyFancyPopup.settings.targetField);
	dest.val(value ? value : "");
	
	if (value) {
		var obj_field;
		var static_field;
		
		if (dest.parent().hasClass("prop_name")) {
			obj_field = dest.parent().parent().find(".obj_name input");
			static_field = dest.parent().parent().find(".static input");
		}
		else {
			obj_field = dest.parent().parent().find(".result_obj_name input");
			static_field = dest.parent().parent().find(".result_static input");
		}
		
		updateObjNameAccordingWithObjectPropertySelection(select, obj_field, static_field);
	}
	
	MyFancyPopup.hidePopup();
}

function updateObjNameAccordingWithObjectPropertySelection(select, obj_field, static_field) {	
	if (obj_field) {
		var selected_option = select.find(":selected").first();
		
		var class_name = selected_option.attr("class_name");
		var is_static = selected_option.attr("is_static");
		
		if (is_static == "1") {
			obj_field.val(class_name ? class_name : "");
			
			if (static_field) {
				static_field.attr("checked", "checked").prop("checked", true);
			}
		}
		else {
			if (obj_field.val() == "") {
				obj_field.val(class_name ? class_name : "");
			}
			
			if (static_field) {
				static_field.removeAttr("checked").prop("checked", false);
			}
		}
	}
}

function onProgrammingTaskChooseObjectMethod(elm) {
	var popup = $("#choose_method_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			popup.find(".method").show();
		},
		
		targetField: $(elm).parent().find("input")[0],
		updateFunction: chooseObjectMethod
	});
	
	MyFancyPopup.showPopup();
}

function chooseObjectMethod(elm) {
	var popup = $("#choose_method_from_file_manager");
	var select = popup.find(".method select");
	
	var value = select.val();
	
	var dest = $(MyFancyPopup.settings.targetField);
	dest.val(value ? value : "");
	
	if (value) {
		if (dest.parent().hasClass("method_name")) {
			var p = dest.parent().parent();
			var obj_field = p.find(".method_obj_name input");
			var static_field = p.find(".method_static input");
			
			updateObjNameAccordingWithObjectPropertySelection(select, obj_field, static_field);
			
			if (auto_convert || confirm("Do you wish to update automatically this method arguments?")) {
				var args = getMethodArguments( select.attr("get_file_properties_url"), select.attr("file_path"), select.attr("class_name"), value);
				ProgrammingTaskUtil.setArgs(args, dest.parent().parent().find(".method_args .args"));
			}
		}
	}
	
	MyFancyPopup.hidePopup();
}

function onProgrammingTaskChooseFunction(elm) {
	MyFancyPopup.init({
		elementToShow: $("#choose_function_from_file_manager"),
		parentElement: document,
		
		targetField: $(elm).parent().find("input")[0],
		updateFunction: chooseFunction
	});
	
	MyFancyPopup.showPopup();
}

function chooseFunction(elm) {
	var popup = $("#choose_function_from_file_manager");
	var select = popup.find(".function select");
	var value = select.val();
	
	$(MyFancyPopup.settings.targetField).val(value ? value : "");
	
	if (value && (auto_convert || confirm("Do you wish to update automatically this function arguments?"))) {
		var args = getFunctionArguments( select.attr("get_file_properties_url"), select.attr("file_path"), value);
		ProgrammingTaskUtil.setArgs(args, $(MyFancyPopup.settings.targetField).parent().parent().find(".func_args .args"));
	}
		
	MyFancyPopup.hidePopup();
}

function onProgrammingTaskChooseClassName(elm) {
	var popup = $("#choose_method_from_file_manager");
	
	var do_not_update_args = $(elm).attr("do_not_update_args");
	do_not_update_args = do_not_update_args == 1 || do_not_update_args == "true" ? 1 : 0;
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			popup.find(".method").hide();
		},
		
		targetField: $(elm).parent().find("input")[0],
		updateFunction: function(element) {
			chooseClassName(element, do_not_update_args);
		},
	});
	
	MyFancyPopup.showPopup();
}

function chooseClassName(elm, do_not_update_args) {
	var node = chooseMethodFromFileManagerTree.getSelectedNodes();
	node = node[0];
	
	if (node) {
		var class_name = $(node).children("a").children("label").first().text();
		
		$(MyFancyPopup.settings.targetField).val(class_name ? class_name : "");
		
		if (!do_not_update_args && (auto_convert || confirm("Do you wish to update automatically this class arguments?"))) {
			var select = $("#choose_method_from_file_manager .method select");
			var file_path = select.attr("file_path");
			var get_file_properties_url = select.attr("get_file_properties_url");
			
			var args = getMethodArguments(get_file_properties_url, file_path, class_name, "__construct");
			ProgrammingTaskUtil.setArgs(args, $(MyFancyPopup.settings.targetField).parent().parent().find(".class_args .args"));
		}
	}
	
	MyFancyPopup.hidePopup();
}

function onIncludeFileTaskChooseFile(elm) {
	var popup = $("#choose_file_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		
		targetField: $(elm).parent(),
		updateFunction: chooseIncludeFile
	});
	
	MyFancyPopup.showPopup();
}

function chooseIncludeFile(elm) {
	var node = chooseFileFromFileManagerTree.getSelectedNodes();
	node = node[0];
	
	if (node) {
		var a = $(node).children("a");
		var file_path = a.attr("file_path");
		var bean_name = a.attr("bean_name");
		var include_path = file_path ? getNodeIncludePath(node, file_path, bean_name) : null;
		
		if (include_path) {
			MyFancyPopup.settings.targetField.children("input").val(include_path);
			MyFancyPopup.settings.targetField.parent().find(".type select").val("");
			
			//This is for the presentation task: includes and includes_once items.
			MyFancyPopup.settings.targetField.children(".value_type").val("");
			MyFancyPopup.settings.targetField.children(".includes_type").val("");
			MyFancyPopup.settings.targetField.children(".includes_once_type").val("");
		
			MyFancyPopup.hidePopup();
		}
		else {
			alert("invalid selected file.\nPlease choose a valid file.");
		}
	}
}

function getNodeIncludePath(node, file_path, bean_name) {
	var include_path = "";
		
	if (file_path) {
		if (bean_name == "dao")
			include_path = "DAO_PATH . \"" + file_path + "\"";
		else if (bean_name == "lib")
			include_path = "LIB_PATH . \"" + file_path + "\"";
		else if (bean_name == "vendor")
			include_path = "VENDOR_PATH . \"" + file_path + "\"";
		else if (bean_name == "test_unit")
			include_path = "TEST_UNIT_PATH . \"" + file_path + "\"";
		else if (layer_type == "bl")
			include_path = "$vars['business_logic_path'] . \"" + file_path + "\"";
		else if (layer_type == "pres") {
			var project_path = node ? getNodeProjectPath(node) : "";
			project_path = project_path && project_path.substr(project_path.length - 1) == "/" ? project_path.substr(0, project_path.length - 1) : project_path;
			
			if (!project_path)
				include_path = "$EVC->getPresentationLayer()->getLayerPathSetting() . \"" + file_path + "\"";
			else {
				var project_arg = project_path == selected_project_id ? "" : ", \"" + project_path + "\"";
				var pos;
				
				if ( (pos = file_path.indexOf("/src/entity/")) != -1) {
					file_path = file_path.substr(pos + 12);
					file_path = file_path.substr(file_path.length - 4, 1) == "." ? file_path.substr(0, file_path.lastIndexOf(".")) : file_path;
					include_path = "$EVC->getEntityPath(\"" + file_path + "\"" + project_arg + ")";
				}
				else if ( (pos = file_path.indexOf("/src/view/")) != -1) {
					file_path = file_path.substr(pos + 10);
					file_path = file_path.substr(file_path.length - 4, 1) == "." ? file_path.substr(0, file_path.lastIndexOf(".")) : file_path;
					include_path = "$EVC->getViewPath(\"" + file_path + "\"" + project_arg + ")";
				}
				else if ( (pos = file_path.indexOf("/src/template/")) != -1) {
					file_path = file_path.substr(pos + 14);
					file_path = file_path.substr(file_path.length - 4, 1) == "." ? file_path.substr(0, file_path.lastIndexOf(".")) : file_path;
					include_path = "$EVC->getTemplatePath(\"" + file_path + "\"" + project_arg + ")";
				}
				else if ( (pos = file_path.indexOf("/src/controller/")) != -1) {
					file_path = file_path.substr(pos + 16);
					file_path = file_path.substr(file_path.length - 4, 1) == "." ? file_path.substr(0, file_path.lastIndexOf(".")) : file_path;
					include_path = "$EVC->getControllerPath(\"" + file_path + "\"" + project_arg + ")";
				}
				else if ( (pos = file_path.indexOf("/src/util/")) != -1) {
					file_path = file_path.substr(pos + 10);
					file_path = file_path.substr(file_path.length - 4, 1) == "." ? file_path.substr(0, file_path.lastIndexOf(".")) : file_path;
					include_path = "$EVC->getUtilPath(\"" + file_path + "\"" + project_arg + ")";
				}
				else if ( (pos = file_path.indexOf("/src/config/")) != -1) {
					file_path = file_path.substr(pos + 12);
					file_path = file_path.substr(file_path.length - 4, 1) == "." ? file_path.substr(0, file_path.lastIndexOf(".")) : file_path;
					include_path = "$EVC->getConfigPath(\"" + file_path + "\"" + project_arg + ")";
				}
				else if ( (pos = file_path.indexOf("/webroot/")) != -1) {
					file_path = file_path.substr(pos + 9);
					project_arg = project_arg.substr(0, 2) == ", " ? project_arg.substr(2) : project_arg;
					include_path = "$EVC->getWebrootPath(" + project_arg + ") . \"" + file_path + "\"";
				}
				else if ( (pos = file_path.indexOf("/src/block/")) != -1) {
					file_path = file_path.substr(pos + 11);
					file_path = file_path.substr(file_path.length - 4, 1) == "." ? file_path.substr(0, file_path.lastIndexOf(".")) : file_path;
					include_path = "$EVC->getBLockPath(\"" + file_path + "\"" + project_arg + ")";
				}
				else if ( (pos = file_path.indexOf("/src/module/")) != -1) {
					file_path = file_path.substr(pos + 12);
					file_path = file_path.substr(file_path.length - 4, 1) == "." ? file_path.substr(0, file_path.lastIndexOf(".")) : file_path;
					include_path = "$EVC->getModulePath(\"" + file_path + "\"" + project_arg + ")";
				}
				else
					include_path = "$EVC->getPresentationLayer()->getLayerPathSetting() . \"" + file_path + "\"";
			}
		}
		else if (getBeanItemType(bean_name)) { //to be used by the testunit pages
			var broker_name = getBeanBrokerName(bean_name);
			
			if (layer_brokers_settings["business_logic_brokers_obj"][broker_name])
				include_path = layer_brokers_settings["business_logic_brokers_obj"][broker_name] + "->getLayerPathSetting() . \"" + file_path + "\"";
			else if (layer_brokers_settings["presentation_brokers_obj"][broker_name])
				include_path = layer_brokers_settings["presentation_brokers_obj"][broker_name] + "->getLayerPathSetting() . \"" + file_path + "\"";
		}
	}
	
	return include_path;
}

function getNodeProjectPath(node) {
	var parent_li = $(node);
	
	do {
		parent_li = parent_li.parent().parent();
		
		if (parent_li) {
			var a = parent_li.children("a");
			
			if (a.children("i.project")[0] || a.children("i.project_common")[0])
				return parent_li.children("a").attr("project_path");
		}
	}
	while (parent_li && parent_li[0]);
	
	return "";
}

function onIncludeBlockTaskChooseFile(elm) {
	var popup = $("#choose_block_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		
		targetField: $(elm).parent().parent(),
		updateFunction: chooseIncludeBlock
	});
	
	MyFancyPopup.showPopup();
}

function chooseIncludeBlock(elm) {
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
			
			var project = MyFancyPopup.settings.targetField.children(".project");
			project.children("input").hide();
			project.children("select.project").show();
			project.children("select.project").val(project_path);
			project.children("select.type").val("options");
			
			var block = MyFancyPopup.settings.targetField.children(".block");
			block.children("input").val(block_path);
			block.children("select").val("string");
			
			MyFancyPopup.hidePopup();
		}
		else {
			alert("invalid selected file.\nPlease choose a valid file.");
		}
	}
}

//target_field is used by the workflow task: GetUrlContentsTaskPropertyObj
function onIncludePageUrlTaskChooseFile(elm) {
	var popup = $("#choose_page_url_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			var html = popup.find(".mytree ul").html();
			if (!html) {
				updateLayerUrlFileManager( popup.find(".broker select")[0] );
			}
		},
		
		targetField: $(elm).parent().children("input"),
		updateFunction: function(elm) {
			chooseIncludePageUrl(elm);
		}
	});
	
	MyFancyPopup.showPopup();
}

function chooseIncludePageUrl(elm) {
	var node = choosePageUrlFromFileManagerTree.getSelectedNodes();
	node = node[0];
	
	if (node) {
		var a = $(node).children("a");
		var i = a.children("i").first();
		var is_folder = i.hasClass("folder");
		var file_path = is_folder ? a.attr("folder_path") : a.attr("file_path");
		
		if (file_path) {
			var bean_name = a.attr("bean_name");	
			var pos = file_path.indexOf("/src/entity/");
			var is_page = is_folder || i.hasClass("entity_file") || pos != -1;
			
			if (!is_page || !file_path) 
				alert("Selected item must be a valid page!\nPlease try again...");
			else {
				var project_path = getNodeProjectPath(node);
				project_path = project_path && project_path.substr(project_path.length - 1) == "/" ? project_path.substr(0, project_path.length - 1) : project_path;
				project_path = project_path == selected_project_id ? "" : project_path + "/";
				
				var entity_path = file_path.substr(pos + ("/src/entity/").length);//12 == /src/entity/
				
				if (!is_folder) {
					entity_path = entity_path.substr(entity_path.length - 4, 1) == "." ? entity_path.substr(0, entity_path.lastIndexOf(".")) : entity_path;
					
					var pos = entity_path.lastIndexOf("/");
					pos = pos == -1 ? 0 : pos + 1;
					if (entity_path.substr(pos).toLowerCase() == "index")
						entity_path = entity_path.substr(0, pos);
				}
				
				var url_str = project_path == "common/" ? "project_common_url_prefix" : "project_url_prefix";
				var url = MyFancyPopup.settings.is_code_html_base ? "<?= $" + url_str + " ?>" : "{$" + url_str + "}";
				url += project_path == "common/" ? "" : project_path;
				var selected_project_name = project_path ? project_path.replace(/\/+$/g, "") : selected_project_id;
				
				//used in the testunit/edit_test.php
				if (typeof layers_projects_urls != "undefined" && $.isPlainObject(layers_projects_urls) && layers_projects_urls.hasOwnProperty(bean_name) && $.isPlainObject(layers_projects_urls[bean_name]) && layers_projects_urls[bean_name].hasOwnProperty(selected_project_name) && layers_projects_urls[bean_name][selected_project_name])
					url = layers_projects_urls[bean_name][selected_project_name];
				
				var previous_url = MyFancyPopup.settings.targetField.val();
				var m = previous_url ? previous_url.match(/[^<]\?[^>]/) : null;
				var query_string = m ? previous_url.substr(m.index + 2) : ""; //m.index is the char before the "?"
				url += entity_path;
				
				if (query_string != "") 
					url += "?" + query_string;
				else {
					var url_suffix = MyFancyPopup.settings.targetField.attr("url_suffix");
					url += url_suffix ? url_suffix : "";
				}
				
				MyFancyPopup.settings.targetField.val(url);
				MyFancyPopup.settings.targetField.focus(); //if MyFancyPopup.settings.targetField is an input from the LayoutUIEditor, then we must set the cursor inside of that input, bc the value will onnly be updated in the html, with the onBlur event of that input. So we must first to focus it or trigger the onBlur event for that input element.
				
				//update var_Type if exists
				var var_type = MyFancyPopup.settings.targetField.parent().parent().find(".var_type select");
				var_type.val("string");
				var_type.trigger("change");
				
				MyFancyPopup.hidePopup();
			}
		}
		else
			alert("invalid selected file.\nPlease choose a valid file.");
	}
}

//target_field is used by the workflow task: GetUrlContentsTaskPropertyObj
function onIncludeImageUrlTaskChooseFile(elm) {
	var popup = $("#choose_image_url_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			var html = popup.find(".mytree ul").html();
			if (!html) {
				updateLayerUrlFileManager( popup.find(".broker select")[0] );
			}
		},
		
		targetField: $(elm).parent().children("input"),
		updateFunction: function(elm) {
			chooseIncludeImageUrl(elm);
		}
	});
	
	MyFancyPopup.showPopup();
}

function chooseIncludeImageUrl(elm) {
	var node = chooseImageUrlFromFileManagerTree.getSelectedNodes();
	node = node[0];
	
	if (node) {
		var a = $(node).children("a");
		var i = a.children("i").first();
		var is_folder = i.hasClass("folder");
		var file_path = is_folder ? a.attr("folder_path") : a.attr("file_path");
		
		if (file_path) {
			var bean_name = a.attr("bean_name");	
			var page_pos = file_path.indexOf("/src/entity/");
			var webroot_pos = file_path.indexOf("/webroot/");
			var is_possible_img = is_folder || i.hasClass("entity_file") || page_pos != -1 || webroot_pos != -1;
			
			if (!is_possible_img || !file_path)
				alert("Selected item must be a valid file!\nPlease try again...");
			else {
				var project_path = getNodeProjectPath(node);
				project_path = project_path && project_path.substr(project_path.length - 1) == "/" ? project_path.substr(0, project_path.length - 1) : project_path;
				project_path = project_path == selected_project_id ? "" : project_path + "/";
				
				var img_path = file_path;
				
				if (page_pos != -1) {
					img_path = file_path.substr(page_pos + ("/src/entity/").length);
					
					if (!is_folder) {
						img_path = img_path.substr(img_path.length - 4, 1) == "." ? img_path.substr(0, img_path.lastIndexOf(".")) : img_path;
						
						var pos = img_path.lastIndexOf("/");
						pos = pos == -1 ? 0 : pos + 1;
						if (img_path.substr(pos).toLowerCase() == "index")
							img_path = img_path.substr(0, pos);
					}
				}
				else if (webroot_pos != -1)
					img_path = file_path.substr(webroot_pos + ("/webroot/").length);
				else if (project_path == "common/")
					img_path = file_path.substr(project_path.length);
				
				var url_str = project_path == "common/" ? "project_common_url_prefix" : "project_url_prefix";
				var url = MyFancyPopup.settings.is_code_html_base ? "<?= $" + url_str + " ?>" : "{$" + url_str + "}";
				url += project_path == "common/" ? "" : project_path;
				var selected_project_name = project_path ? project_path.replace(/\/+$/g, "") : selected_project_id;
				
				//used in the testunit/edit_test.php
				if (typeof layers_projects_urls != "undefined" && $.isPlainObject(layers_projects_urls) && layers_projects_urls.hasOwnProperty(bean_name) && $.isPlainObject(layers_projects_urls[bean_name]) && layers_projects_urls[bean_name].hasOwnProperty(selected_project_name) && layers_projects_urls[bean_name][selected_project_name])
					url = layers_projects_urls[bean_name][selected_project_name];
				
				var previous_url = MyFancyPopup.settings.targetField.val();
				var m = previous_url ? previous_url.match(/[^<]\?[^>]/) : null;
				var query_string = m ? previous_url.substr(m.index + 2) : ""; //m.index is the char before the "?"
				url += img_path;
				
				if (query_string != "") 
					url += "?" + query_string;
				else {
					var url_suffix = MyFancyPopup.settings.targetField.attr("url_suffix");
					url += url_suffix ? url_suffix : "";
				}
				
				MyFancyPopup.settings.targetField.val(url);
				MyFancyPopup.settings.targetField.focus(); //if MyFancyPopup.settings.targetField is an input from the LayoutUIEditor, then we must set the cursor inside of that input, bc the value will onnly be updated in the html, with the onBlur event of that input. So we must first to focus it or trigger the onBlur event for that input element.
				
				//update var_Type if exists
				var var_type = MyFancyPopup.settings.targetField.parent().parent().find(".var_type select");
				var_type.val("string");
				var_type.trigger("change");
				
				MyFancyPopup.hidePopup();
			}
		}
		else
			alert("invalid selected file.\nPlease choose a valid file.");
	}
}

function onBusinessLogicTaskChooseBusinessLogic(elm) {
	var popup = $("#choose_business_logic_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			var html = popup.find(".mytree ul").html();
			if (!html) {
				updateLayerUrlFileManager( popup.find(".broker select")[0] );
			}
		},
		
		targetField: $(elm).parent().parent(),
		updateFunction: chooseBusinessLogic
	});
	
	MyFancyPopup.showPopup();
}

function chooseBusinessLogic(elm) {
	var popup = $("#choose_business_logic_from_file_manager");
	var select = popup.find(".businesslogic select");
	
	var class_name = select.attr("class_name");
	var service_id = (class_name ? class_name + "." : "") + select.val();
	
	var file_path = select.attr("file_path");
	var module_id = file_path.lastIndexOf("/") != -1 ? file_path.substr(0, file_path.lastIndexOf("/")) : file_path;
	module_id = module_id.replace(/\//g, ".");
	
	var main_div = $(MyFancyPopup.settings.targetField);
	
	//PREPARING MODULE ID
	var module = $(MyFancyPopup.settings.targetField).find(".module_id");
	module.find("input").val(module_id);
	module.find("select").val("string");
	
	//PREPARING SERVICE ID
	var service = $(MyFancyPopup.settings.targetField).find(".service_id");
	service.find("input").val(service_id);
	service.find("select").val("string");
	
	//PREPARING PARAMETERS
	var select = popup.find(".broker select")[0];
	var option = select.options[ select.selectedIndex ];
	var bean_file_name = option.getAttribute("bean_file_name");
	var bean_name = option.getAttribute("bean_name");
	var selected_option_text = $(option).text();
	
	updateBusinessLogicParams(main_div, bean_file_name, bean_name, file_path, service_id);
	
	//update the selected broker
	var select = main_div.find(".broker_method_obj select");
	var exists = false;
	for (var i = 0; i < select[0].options.length; i++) {
		var option = select[0].options[i];
		
		if (option.value.indexOf('("' + selected_option_text + '")') != -1) {
			select.val( option.value );
			BrokerOptionsUtilObj.onBrokerChange(select[0]);
			exists = true;
			break;
		}
	}
	
	//in case of being already in the business logic layer
	if (!exists) {
		select.val("this->getBusinessLogicLayer()");
		BrokerOptionsUtilObj.onBrokerChange(select[0]);
	}
	
	MyFancyPopup.hidePopup();
}

function updateBusinessLogicParams(main_div, bean_file_name, bean_name, file_path, service_id) {
	var attrs = getBusinessLogicParameters(bean_file_name, bean_name, file_path, service_id);
	attrs = attrs ? attrs : {};
	
	var params_elm = main_div.children(".params");

	var parameters = params_elm.children(".parameters");
	parameters.html("");

	var parameters_type = params_elm.children(".parameters_type");
	parameters_type.val("array");
	CallBusinessLogicTaskPropertyObj.onChangeParametersType(parameters_type[0]);

	var add_item = parameters.children(".items").first().children(".item_add");
	var ul = parameters.children("ul");

	for (var pname in attrs) {
		var ptype = attrs[pname];
	
		var item = ul.children(".item").last();
	
		item.children(".key").val(pname);
		item.children(".key_type").val("string");
		item.children(".value").val("");
		item.children(".value_type").val(ptype);
	
		add_item.click();
	}

	ul.children(".item").last().remove();
}

function onChooseIbatisQuery(elm) {
	var popup = $("#choose_query_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			updateDBDriverOnBrokerNameChange( popup.find(".broker select") );
			
			var html = popup.find(".mytree ul").html();
			if (!html) 
				updateLayerUrlFileManager( popup.find(".broker select")[0] );
		},
		
		targetField: $(elm).parent().parent(),
		updateFunction: chooseQuery
	});
	
	MyFancyPopup.showPopup();
}

function chooseQuery(elm) {
	var popup = $("#choose_query_from_file_manager");
	
	var node = chooseQueryFromFileManagerTree.getSelectedNodes();
	node = node[0];
	
	if (node) {
		var a = $(node).children("a");
		var is_query = a.children("i").first().hasClass("query");
		
		if (!is_query) {
			alert("Selected item must be a query!\nPlease try again...");
		}
		else {
			var query_id = a.children("label").first().text();
			var file_path = a.attr("file_path");
			var query_type = a.attr("query_type");
			var relationship_type = a.attr("relationship_type");
			var hbn_obj_id = a.attr("hbn_obj_id");
		
			var main_div = $(MyFancyPopup.settings.targetField);
		
			//PREPARING MODULE ID
			var module = file_path.lastIndexOf("/") != -1 ? file_path.substr(0, file_path.lastIndexOf("/")) : file_path;
			module = module.replace(/\//g, ".");
		
			var module_id = main_div.children(".module_id");
			module_id.children("input").val(module);
			module_id.children("select").val("string");
		
			//PREPARING SERVICE TYPE
			var service_type = main_div.children(".service_type");
			var service_type_type = service_type.children(".service_type_type");
			service_type_type.val("string");
			CallIbatisQueryTaskPropertyObj.onChangeServiceType(service_type_type[0]);
			service_type.children(".service_type_string").val(query_type.toLowerCase());
		
			//PREPARING SERVICE ID
			var service_id = main_div.children(".service_id");
			service_id.children("input").val(query_id);
			service_id.children("select").val("string");
		
			//PREPARING PARAMETERS
			var select = popup.find(".broker select")[0];
			var option = select.options[ select.selectedIndex ];
			var bean_file_name = option.getAttribute("bean_file_name");
			var bean_name = option.getAttribute("bean_name");
			var selected_option_text = $(option).text();
			var db_driver = popup.find(".db_driver select").val();
			var db_type = popup.find(".type select").val();
			
			updateQueryParams(main_div, bean_file_name, bean_name, db_driver, db_type, file_path, query_type, query_id, hbn_obj_id, relationship_type);
			
			//update the selected broker
			var select = main_div.find(".broker_method_obj select");
			for (var i = 0; i < select[0].options.length; i++) {
				var option = select[0].options[i];
				
				if (option.value.indexOf('("' + selected_option_text + '")') != -1) {
					select.val( option.value );
					BrokerOptionsUtilObj.onBrokerChange(select[0]);
					break;
				}
			}
			
			MyFancyPopup.hidePopup();
		}
	}
}

function updateQueryParams(main_div, bean_file_name, bean_name, db_driver, db_type, file_path, query_type, query_id, hbn_obj_id, relationship_type) {
	var attrs = getQueryParameters(bean_file_name, bean_name, db_driver, db_type, file_path, query_type, query_id, hbn_obj_id, relationship_type);
	attrs = attrs ? attrs : {};

	var params_elm = main_div.children(".params");

	var parameters = params_elm.children(".parameters");
	parameters.html("");

	var parameters_type = params_elm.children(".parameters_type");
	parameters_type.val("array");
	CallIbatisQueryTaskPropertyObj.onChangeParametersType(parameters_type[0]);

	var add_item = parameters.children(".items").first().children(".item_add");
	var ul = parameters.children("ul");

	for (var pname in attrs) {
		var ptype = attrs[pname];
	
		var item = ul.children(".item").last();
	
		item.children(".key").val(pname);
		item.children(".key_type").val("string");
		item.children(".value").val("");
		item.children(".value_type").val(ptype);
	
		add_item.click();
	}

	ul.children(".item").last().remove();
}

function updateLayerUrlFileManager(elm) {
	var option = elm.options[ elm.selectedIndex ];
	var bean_file_name = option.getAttribute("bean_file_name");
	var bean_name = option.getAttribute("bean_name");
	
	var mytree = $(elm).parent().parent().find(".mytree");
	var root_elm = mytree.children("li").first();
	var ul = root_elm.children("ul").first();
	
	root_elm.removeClass("jstree-open").addClass("jstree-closed");
	ul.html("");
	
	var url = ul.attr("layer_url");
	url = url.replace("#bean_file_name#", bean_file_name).replace("#bean_name#", bean_name);
	ul.attr("url", url);
}

function onChooseHibernateObject(elm) {
	var popup = $("#choose_hibernate_object_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			updateDBDriverOnBrokerNameChange( popup.find(".broker select") );
			
			var html = popup.find(".mytree ul").html();
			if (!html) {
				updateLayerUrlFileManager( popup.find(".broker select")[0] );
			}
		},
		
		targetField: $(elm).parent().parent(),
		updateFunction: chooseHibernateObject,
	});
	
	MyFancyPopup.showPopup();
}

function chooseHibernateObject(elm) {
	var popup = $("#choose_hibernate_object_from_file_manager");
	
	var node = chooseHibernateObjectFromFileManagerTree.getSelectedNodes();
	node = node[0];

	if (node) {
		var a = $(node).children("a");
		var file_path = a.attr("file_path");
		
		if (a.children("i").hasClass("obj") && file_path) {
			var main_div = $(MyFancyPopup.settings.targetField);
			
			//PREPARING MODULE ID
			var module_id = file_path.lastIndexOf("/") != -1 ? file_path.substr(0, file_path.lastIndexOf("/")) : file_path;
			module_id = module_id.replace(/\//g, ".");
			
			main_div.find(".module_id input").val(module_id);
			main_div.find(".module_id select").val("string");
			
			//PREPARING SERVICE ID
			var service_id = a.children("label").first().text();
			
			main_div.find(".service_id input").val(service_id);
			main_div.find(".service_id select").val("string");
		
			//update the selected broker
			var select = popup.find(".broker select")[0];
			var option = select.options[ select.selectedIndex ];
			var selected_option_text = $(option).text();
			
			var select = main_div.find(".broker_method_obj select");
			for (var i = 0; i < select[0].options.length; i++) {
				var option = select[0].options[i];
				
				if (option.value.indexOf('("' + selected_option_text + '")') != -1) {
					select.val( option.value );
					BrokerOptionsUtilObj.onBrokerChange(select[0]);
					break;
				}
			}
			
			MyFancyPopup.hidePopup();
		}
		else {
			alert("You must select a valid Hibernate Object.\nPlease try again...");
		}
	}
}

function onChooseHibernateObjectMethod(elm) {
	var popup = $("#choose_hibernate_object_method_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			updateDBDriverOnBrokerNameChange( popup.find(".broker select") );
			
			var html = popup.find(".mytree ul").html();
			if (!html) {
				updateLayerUrlFileManager( popup.find(".broker select")[0] );
			}
		},
		
		targetField: $(elm).parent().parent(),
		updateFunction: chooseHibernateObjectMethod,
	});
	
	MyFancyPopup.showPopup();
}

function chooseHibernateObjectMethod(elm) {
	var popup = $("#choose_hibernate_object_method_from_file_manager");
	
	var node = chooseHibernateObjectMethodFromFileManagerTree.getSelectedNodes();
	node = node[0];

	if (node) {
		var a = $(node).children("a");
		var i = a.children("i").first();
		var is_query = i.hasClass("query");
		var is_relationship = i.hasClass("relationship");
		var is_hbn_native = i.hasClass("hbn_native");
		
		if (!is_query && !is_relationship && !is_hbn_native) {
			alert("Selected item must be a query or a relationship!\nPlease try again...");
		}
		else {
			var query_id = a.children("label").first().text();
			var file_path = a.attr("file_path");
			var query_type = a.attr("query_type");
			var relationship_type = a.attr("relationship_type");
			var hbn_obj_id = a.attr("hbn_obj_id");
		
			var main_div = $(MyFancyPopup.settings.targetField);
			
			//PREPARING MODULE ID
			var module_id = file_path.lastIndexOf("/") != -1 ? file_path.substr(0, file_path.lastIndexOf("/")) : file_path;
			module_id = module_id.replace(/\//g, ".");
			
			main_div.find(".module_id input").val(module_id);
			main_div.find(".module_id select").val("string");
			
			//PREPARING HBN OBJ ID
			main_div.find(".service_id input").val(hbn_obj_id);
			main_div.find(".service_id select").val("string");
		
			//PREPARING QUERY ID / REL NAME
			var method = null;
			if (relationship_type == "queries") {
				main_div.find(".sma_query_id input").val(query_id);
				main_div.find(".sma_query_id select").val("string");
				
				method = "call" + query_type.charAt(0).toUpperCase() + query_type.slice(1).toLowerCase();
			}
			else if (relationship_type == "relationships") {
				main_div.find(".sma_rel_name input").val(query_id);
				main_div.find(".sma_rel_name select").val("string");
				
				method = "findRelationship";
			}
			else if (relationship_type == "native") {
				method = query_id;
			}
			
			//PREPARING METHOD NAME
			main_div.find(".service_method .service_method_string").val(method);
			main_div.find(".service_method .service_method_type").val("string");
			
			CallHibernateMethodTaskPropertyObj.onChangeServiceMethodType( main_div.find(".service_method .service_method_type")[0] );
			CallHibernateMethodTaskPropertyObj.onChangeServiceMethod( main_div.find(".service_method .service_method_string")[0] );
			
			//PREPARING PARAMETERS
			var select = popup.find(".broker select")[0];
			var option = select.options[ select.selectedIndex ];
			var bean_file_name = option.getAttribute("bean_file_name");
			var bean_name = option.getAttribute("bean_name");
			var selected_option_text = $(option).text();
			var db_driver = popup.find(".db_driver select").val();
			var db_type = popup.find(".type select").val();
			
			updateHibernateObjectMethodParams(main_div, bean_file_name, bean_name, db_driver, db_type, file_path, query_type, query_id, hbn_obj_id, relationship_type);
			
			//update the selected broker
			var select = main_div.find(".broker_method_obj select");
			for (var i = 0; i < select[0].options.length; i++) {
				var option = select[0].options[i];
				
				if (option.value.indexOf('("' + selected_option_text + '")') != -1) {
					select.val( option.value );
					BrokerOptionsUtilObj.onBrokerChange(select[0]);
					break;
				}
			}
			
			MyFancyPopup.hidePopup();
		}
	}
}

function updateHibernateObjectMethodParams(main_div, bean_file_name, bean_name, db_driver, db_type, file_path, query_type, query_id, hbn_obj_id, relationship_type) {
	if (relationship_type == "native") {
		switch(query_id) {
			case "findRelationships":
			case "findRelationship":
			case "countRelationships":
			case "countRelationship":
				var sma_rel_name = main_div.find(".sma_rel_name input").val();
				if (sma_rel_name && main_div.find(".sma_rel_name select").val() == "string") {
					query_id = sma_rel_name;
					relationship_type = "relationships";
				}
				break;
		}
	}
	
	var attrs = getQueryParameters(bean_file_name, bean_name, db_driver, db_type, file_path, query_type, query_id, hbn_obj_id, relationship_type);
	attrs = attrs ? attrs : {};
	
	var method = main_div.children(".service_method").children(".service_method_string").val();
	
	var params_class_name = "sma_data";
	if (method == "findRelationships" || method == "findRelationship" || method == "countRelationships" || method == "countRelationship") {
		params_class_name = "sma_parent_ids";
	}
	
	var params_elm = main_div.children(".service_method_args").children("." + params_class_name);

	var parameters = params_elm.children("." + params_class_name);
	parameters.html("");

	var parameters_type = params_elm.children("select");
	parameters_type.val("array");
	CallHibernateMethodTaskPropertyObj.onChangeSMAType(parameters_type[0]);

	var add_item = parameters.children(".items").first().children(".item_add");
	var ul = parameters.children("ul");

	for (var pname in attrs) {
		var ptype = attrs[pname];

		var item = ul.children(".item").last();

		item.children(".key").val(pname);
		item.children(".key_type").val("string");
		item.children(".value").val("");
		item.children(".value_type").val(ptype);

		add_item.click();
	}

	ul.children(".item").last().remove();
}

function onChooseDBDriver(elm) {
	var popup = $("#choose_db_driver");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			updateDBDriverOnBrokerNameChange( popup.find(".broker select") );
		},
		
		updateFunction: function(element) {
			var db_driver = popup.find(".db_driver select").val();
			$(elm).parent().children("input.value").val(db_driver);
			
			MyFancyPopup.hidePopup();
		}
	});
	
	MyFancyPopup.showPopup();
}

function onChooseDBTableAndAttributes(elm, callback) { //This is used in the workflow task: DBDAOActionTaskPropertyObj.on_choose_table_callback.
	if (typeof callback != "function")
		StatusMessageHandler.showError("callback argument in onGetDBTableAndAttributes function must be a function reference!");
	
	var popup = $("#choose_db_driver_table");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			updateDBDriverOnBrokerNameChange( popup.find(".broker select") );
			updateDBTablesOnBrokerDBDriverChange( popup.find(".db_driver select") );
		},
		
		updateFunction: function(element) {
			var table_and_attributes = {
				table: popup.find(".db_table select").val(),
				attributes: []
			};
			
			if (table_and_attributes["table"] && get_broker_db_data_url) {
				var option = popup.find(".broker select option:selected");
				var broker_bean_name = option.attr("bean_name");
				var broker_bean_file_name = option.attr("bean_file_name");
				var broker = option.val();
				var db_driver = popup.find(".db_driver select").val();
				var type = popup.find(".type select").val();
				
				var url = get_broker_db_data_url.replace("#bean_name#", broker_bean_name).replace("#bean_file_name#", broker_bean_file_name); //to be used by the testunit pages
				
				$.ajax({
					type : "post",
					url : url,
					data : {"db_broker" : broker, "db_driver" : db_driver, "type" : type, "db_table" : table_and_attributes["table"], "detailed_info" : 1},
					dataType : "json",
					success : function(data, textStatus, jqXHR) {
						table_and_attributes["attributes"] = data;
						
						callback(table_and_attributes);
					},
					error : function(jqXHR, textStatus, errorThrown) { 
						if (jqXHR.responseText)
							StatusMessageHandler.showError(jqXHR.responseText);
					},
				});
			}
			else
				callback(table_and_attributes);
		
			MyFancyPopup.hidePopup();
		}
	});
	
	MyFancyPopup.showPopup();
}

function updateDBDriverOnBrokerNameChange(elm) {
	elm = $(elm);
	var broker = elm.val();
	var select = elm.parent().parent().find(".db_driver select");
	
	if (!brokers_db_drivers.hasOwnProperty(broker)) {
		var selected_option = elm.find("option:selected");
		var bean_file_name = selected_option.attr("bean_file_name");
		var bean_name = selected_option.attr("bean_name");
		var item_type = getBeanItemType(bean_name); //to be used by the testunit pages
		
		brokers_db_drivers[broker] = getBrokerDBDrivers(broker, bean_file_name, bean_name, item_type);
	}
	
	var db_drivers = brokers_db_drivers[broker];
	var html = "";
	
	if (db_drivers)
		for (var db_driver_name in db_drivers) {
			var db_driver_props = db_drivers[db_driver_name];
			
			html += '<option value="' + db_driver_name + '">' + db_driver_name + (db_driver_props && db_driver_props.length > 0 ? '' : ' (Rest)') + '</option>'; 
		}
	
	select.html(html);
}

function updateDBTablesOnBrokerDBDriverChange(elm) {
	if (get_broker_db_data_url) {
		var p = $(elm).parent().parent();
		var option = p.find(".broker select option:selected");
		var broker_bean_name = option.attr("bean_name");
		var broker_bean_file_name = option.attr("bean_file_name");
		var broker = option.val();
		var db_driver = p.find(".db_driver select").val();
		var type = p.find(".type select").val();
		
		var url = get_broker_db_data_url.replace("#bean_name#", broker_bean_name).replace("#bean_file_name#", broker_bean_file_name); //to be used by the testunit pages
		
		$.ajax({
			type : "post",
			url : url,
			data : {"db_broker" : broker, "db_driver" : db_driver, "type" : type},
			dataType : "json",
			success : function(data, textStatus, jqXHR) {
				if(data) {
					var html = '<option></option>';
					for (var i = 0; i < data.length; i++)
						html += '<option>' + data[i] + '</option>';
					
					var select = p.find(".db_table select");
					var selected_table = select.val();
					select.html(html);
					select.val(selected_table);
				}
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jqXHR.responseText)
					StatusMessageHandler.showError(jqXHR.responseText);
			},
		});
	}
	else
		StatusMessageHandler.showError("Error: get_broker_db_data_url is not defined!");
}

//to be used by the testunit pages, bc the layer_brokers_settings is only set in the view/testunit/edit_test.php
function getBeanItemType(bean_name) { 
	if (typeof layer_brokers_settings != "undefined" && $.isPlainObject(layer_brokers_settings) && bean_name) {
		for (var settings_type in layer_brokers_settings) {
			//settings_type: db_brokers, db_brokers_obj, ibatis_brokers, ibatis_brokers_obj, business_logic_brokers, business_logic_brokers_obj, presentation_brokers, presentation_brokers_obj, presentation_evc_brokers, presentation_evc_template_brokers, available_projects, etc...
			
			//if settings_type is: db_brokers, ibatis_brokers, business_logic_brokers, presentation_brokers, presentation_evc_brokers, presentation_evc_template_brokers...
			if (settings_type.match(/^([a-z_]+)_brokers$/i)) {
				var brokers_settings = layer_brokers_settings[settings_type];
				
				if ($.isArray(brokers_settings))
					for (var i = 0, l = brokers_settings.length; i < l; i++) {
						var broker_settings = brokers_settings[i];
						var broker_bean_name = broker_settings[2];
						
						if (broker_bean_name == bean_name)
							switch (settings_type) {
								case "db_brokers": return "db";
								case "ibatis_brokers": return "ibatis";
								case "hibernate_brokers": return "hibernate";
								case "business_logic_brokers": return "businesslogic";
								case "presentation_brokers": return "presentation";
							}
					}
			}
		}
	}
	
	return null;
}

//to be used by the testunit pages, bc the layer_brokers_settings is only set in the view/testunit/edit_test.php
function getBeanBrokerName(bean_name) { 
	if (typeof layer_brokers_settings != "undefined" && $.isPlainObject(layer_brokers_settings) && bean_name) {
		for (var settings_type in layer_brokers_settings) {
			//settings_type: db_brokers, db_brokers_obj, ibatis_brokers, ibatis_brokers_obj, business_logic_brokers, business_logic_brokers_obj, presentation_brokers, presentation_brokers_obj, presentation_evc_brokers, presentation_evc_template_brokers, available_projects, etc...
			
			//if settings_type is: db_brokers, ibatis_brokers, business_logic_brokers, presentation_brokers, presentation_evc_brokers, presentation_evc_template_brokers...
			if (settings_type.match(/^([a-z_]+)_brokers$/i)) {
				var brokers_settings = layer_brokers_settings[settings_type];
				
				if ($.isArray(brokers_settings))
					for (var i = 0, l = brokers_settings.length; i < l; i++) {
						var broker_settings = brokers_settings[i];
						var broker_bean_name = broker_settings[2];
						
						if (broker_bean_name == bean_name) 
							return broker_settings[0];
					}
			}
		}
	}
	
	return null;
}

function onPresentationTaskChoosePage(elm) {
	var popup = $("#choose_presentation_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			var html = popup.find(".mytree ul").html();
			if (!html) {
				updateLayerUrlFileManager( popup.find(".broker select")[0] );
			}
		},
		
		targetField: $(elm).parent().parent(),
		updateFunction: choosePresentation
	});
	
	MyFancyPopup.showPopup();
}

function choosePresentation(elm) {
	var node = choosePresentationFromFileManagerTree.getSelectedNodes();
	node = node[0];
	
	if (node) {
		var a = $(node).children("a");
		var file_path = a.attr("file_path");
		var pos = file_path.indexOf("/src/entity/");
		var is_page = a.children("i").first().hasClass("entity_file");
		
		if (!is_page || !file_path || pos == -1) {
			alert("Selected item must be a valid page!\nPlease try again...");
		}
		else {
			//var project_path = file_path.substr(0, file_path.indexOf("/"));
			var project_path = getNodeProjectPath(node);
			project_path = project_path && project_path.substr(project_path.length - 1) == "/" ? project_path.substr(0, project_path.length - 1) : project_path;
			project_path = project_path == selected_project_id ? "" : project_path;
			
			var pos = file_path.indexOf("/src/entity/");
			var page_path = file_path.substr(pos + ("/src/entity/").length);//12 == /src/entity/
			page_path = page_path.substr(page_path.length - 4, 1) == "." ? page_path.substr(0, page_path.lastIndexOf(".")) : page_path;
			
			var project = $(MyFancyPopup.settings.targetField).children(".project");
			project.find("input").val(project_path);
			project.find("select").val("string");
			
			var page = $(MyFancyPopup.settings.targetField).children(".page");
			page.find("input").val(page_path);
			page.find("select").val("string");
	
			MyFancyPopup.hidePopup();
		}
	}
}

/* UI, TABS, WORKFLOW & CODE EDITOR FUNCTIONS */

function onLoadTaskFlowChartAndCodeEditor() {
	if ($(".taskflowchart")[0]) {
		resizeTaskFlowChart()
		resizeCodeEditor();
		
		$(window).resize(function() {
			resizeTaskFlowChart();
			resizeCodeEditor();
			
			MyFancyPopup.updatePopup();
		});
		
		jsPlumbWorkFlow.onReady(function() {
			$(".taskflowchart .workflow_menu").show();
			$(".big_white_panel").hide();
		});
		
		//init the code_id in #ui and #code so the system doesn't re-generate the code and workflow when clicked in the code and taskflow tabs and bc of the isCodeAndWorkflowObjChanged method, when the #tasks_flow_tab is not inited yet.
		if ($("#ui").length && $("#code").length) {
			var code = getEditorCodeRawValue();
			var code_id = $.md5(code);
			
			$("#code").attr("generated_code_id", code_id);
			$("#ui").attr("code_id", code_id);
		}
	}
	
	MyFancyPopup.hidePopup();
}

function createCodeEditor(textarea, options) {
	var parent = $(textarea).parent();
	var mode = options && options["mode"] ? options["mode"] : null;
	mode = mode ? mode : "php";
	
	ace.require("ace/ext/language_tools");
	var editor = ace.edit(textarea);
	editor.setTheme("ace/theme/chrome");
	editor.session.setMode("ace/mode/" + mode);
	editor.setAutoScrollEditorIntoView(true);
	editor.setOption("minLines", 5);
	editor.setOptions({
		enableBasicAutocompletion: true,
		enableSnippets: true,
		enableLiveAutocompletion: false,
	});
	editor.setOption("wrap", true);
	
	if (options && typeof options.save_func == "function") {
		editor.commands.addCommand({
			name: 'saveFile',
			bindKey: {
				win: 'Ctrl-S',
				mac: 'Command-S',
				sender: 'editor|cli'
			},
			exec: function(env, args, request) {
				options.save_func();
			},
		});
	}
	
	parent.find("textarea.ace_text-input").removeClass("ace_text-input"); //fixing problem with scroll up, where when focused or pressed key inside editor the page scrolls to top.
	
	parent.data("editor", editor);
	
	if (!options.hasOwnProperty("no_height") || !options["no_height"]) {
		var h = getCodeEditorHeight(parent);
		parent.children(".ace_editor").css("height", h + "px");
	}
	
	return editor;
}

function resizeTaskFlowChart() {
	$(".taskflowchart").height(getTaskFlowChartHeight() + "px");
	
	jsPlumbWorkFlow.resizePanels();
}

function onResizeTaskFlowChartPanels(WF, height) {
	if ($("#" + WF.jsPlumbContextMenu.main_tasks_menu_obj_id).parent().hasClass("with_top_bar_menu"))
		$("#" + WF.jsPlumbContextMenu.main_tasks_menu_obj_id + ", #" + WF.jsPlumbContextMenu.main_tasks_menu_hide_obj_id + ", #" + WF.jsPlumbTaskFlow.main_tasks_flow_obj_id).css("top", "");
}

function resizeCodeEditor(code_elm) {
	code_elm = code_elm ? code_elm : $("#code");
	
	var height = getCodeEditorHeight(code_elm);
	var editor_elm = code_elm.find(".ace_editor");
	
	if (editor_elm[0])
		editor_elm.css("height", height + "px");
	else
		code_elm.find(" > textarea, > .layout-ui-editor > textarea").css("height", getCodeEditorHeight(code_elm) + "px");
	
	var editor = code_elm.data("editor");
	if (editor)
		editor.resize();
}

function getTaskFlowChartHeight() {
	var taskflowchart = $(".taskflowchart");
	var offset = taskflowchart.offset();
	var top = parseInt(offset.top) + 2;
	
	return parseInt( $(window).height() ) - top;
}

function getCodeEditorHeight(code_editor_parent) {
	if (code_editor_parent[0]) {
		var offset = code_editor_parent.offset();
		var top = parseInt(offset.top) + 2;
		
		var code_menu = code_editor_parent.children(".code_menu:not(.top_bar_menu)");
		top += (code_menu[0] ? code_menu.height() : 0) + 10;
		
		return parseInt( $(window).height() ) - top;
	}
	return null;
}

function prettyPrintCode() {
	var editor = $("#code").data("editor");
	
	var code = editor ? editor.getValue() : $("#code textarea").first().val();
	code = MyHtmlBeautify.beautify(code);
	code = code.replace(/^\s+/g, "").replace(/\s+$/g, "");
	
	if (editor)
		editor.setValue(code);
	else
		$("#code textarea").first().val(code);
}

function setWordWrap(elm) {
	var editor = $("#code").data("editor");
	
	if (editor) {
		var wrap = $(elm).attr("wrap") != 1 ? false : true;
		$(elm).attr("wrap", wrap ? 0 : 1);
		
		editor.getSession().setUseWrapMode(wrap);
		StatusMessageHandler.showMessage("Wrap is now " + (wrap ? "enable" : "disable"));
	}
}

function openEditorSettings() {
	var editor = $("#code").data("editor");
	
	if (editor) {
		editor.execCommand("showSettingsMenu");
	}
	else {
		StatusMessageHandler.showError("Error trying to open the editor settings...");
	}
}

function toggleTaskFlowFullScreen(elm) {
	toggleEditorFullScreen(elm);
	
	resizeTaskFlowChart();
}

function toggleCodeEditorFullScreen(elm) {
	toggleEditorFullScreen(elm);
	
	resizeCodeEditor( $(elm).parent().closest(".code_menu").parent() );
}

function toggleEditorFullScreen(elm) {
	elm = $(elm);
	
	var in_full_screen = isInFullScreen();
	var ui = $("#ui");
	var code = $("#code");
	
	toggleFullScreen(elm);
	
	if (in_full_screen) {
		code.removeClass("editor_full_screen");
		ui.removeClass("tasks_flow_full_screen");
		
		code.find(" > .code_menu > ul > .editor_full_screen a").removeClass("active");
		ui.find(" > .taskflowchart > .workflow_menu > .dropdown > .tasks_flow_full_screen a").removeClass("active");
		
		//bc of the LayoutUIEditor
		code.children(".layout-ui-editor, .layout_ui_editor_right_container").removeClass("full-screen");
	   	code.find(".layout-ui-editor .options .full-screen").removeClass("zmdi-fullscreen-exit").addClass("zmdi-fullscreen");
	}
	else {
		code.addClass("editor_full_screen");
		ui.addClass("tasks_flow_full_screen");
		
		code.find(" > .code_menu > ul > .editor_full_screen a").addClass("active");
		ui.find(" > .taskflowchart > .workflow_menu > .dropdown > .tasks_flow_full_screen a").addClass("active");
		
		//bc of the LayoutUIEditor
		code.children(".layout-ui-editor, .layout_ui_editor_right_container").addClass("full-screen");
	   	code.find(".layout-ui-editor .options .full-screen").addClass("zmdi-fullscreen-exit").removeClass("zmdi-fullscreen");
	}
}

function setEditorCodeRawValue(code) {
	var editor = $("#code").data("editor");
	
	if (editor) 
		editor.setValue(code, 1);
	else
		$("#code textarea").val(code);
}

function getEditorCodeRawValue() {
	var code = "";
	var editor = $("#code").data("editor");
	
	if (editor) 
		code = editor.getValue();
	else
		code = $("#code textarea").val();
	
	return code;
}

function getEditorCodeValue() {
	var code = getEditorCodeRawValue();
	
	if (code) {
		code = code ? code.trim() : "";
	
		if (code != "") {
			if (code.substr(0, 2) == "<?") {
				code = code.substr(0, 5) == "<?php" ? code.substr(5) : (code.substr(0, 2) == "<?" ? code.substr(2) : code);
			}
			else {
				code = "?>\n" + code;
			}
		
			if (code.substr(code.length - 2) == "?>") {
				code = code.substr(0, code.length - 2);
			}
		
			else if (code.lastIndexOf("<?") < code.lastIndexOf("?>")) {//this means that exists html elements at the end of the file
				code += "\n<?php";
			}
			
			while(code.indexOf("<?php\n?>") != -1) {
				code = code.replace("<?php\n?>", "");
			}
			
			code = code.trim();
		}
	}
	
	return code;
}

function sortWorkflowTask(sort_type) {
	jsPlumbWorkFlow.getMyFancyPopupObj().init({
		parentElement: $("#" + jsPlumbWorkFlow.jsPlumbTaskFlow.main_tasks_flow_obj_id),
	});
	jsPlumbWorkFlow.getMyFancyPopupObj().showOverlay();
	jsPlumbWorkFlow.getMyFancyPopupObj().showLoading();
	
	if (!sort_type) {
		sort_type = prompt("Please choose the sort type that you wish? You can choose 1, 2, 3 or 4.");
	}
	
	if (sort_type) {
		jsPlumbWorkFlow.jsPlumbTaskSort.sortTasks(sort_type);
		jsPlumbWorkFlow.jsPlumbStatusMessage.showMessage("Done sorting tasks based in the sort type: " + sort_type + ".");
	}
	
	jsPlumbWorkFlow.getMyFancyPopupObj().hidePopup();
}

function toggleHeader(elm) {
	elm = $(elm);
	var p = elm.parent();
	var items = p.children(".title, .sub_title");
	
	if (elm.hasClass("maximize")) {
		items.show();
		elm.removeClass("maximize").addClass("minimize");
		p.removeClass("minimized");
	}
	else {
		items.hide();
		elm.removeClass("minimize").addClass("maximize");
		p.addClass("minimized");
	}
	
	resizeTaskFlowChart();
	resizeCodeEditor();
}

function onClickCodeEditorTab(elm) {
	jsPlumbWorkFlow.jsPlumbTaskFile.stopAutoSave();
	
	if (auto_convert) {
		StatusMessageHandler.showMessage("Generating code based in workflow... Loading...");
		
		//close properties popup in case the auto_save be active on close task properties popup, but only if is not auto_save, otherwise the task properties can become messy, like it happens with the task inlinehtml.
		if (auto_save && jsPlumbWorkFlow.jsPlumbProperty.auto_save && !is_from_auto_save) {
			if (jsPlumbWorkFlow.jsPlumbProperty.isSelectedTaskPropertiesOpen())
				jsPlumbWorkFlow.jsPlumbProperty.saveTaskProperties();
			else if (jsPlumbWorkFlow.jsPlumbProperty.isSelectedConnectionPropertiesOpen())
				jsPlumbWorkFlow.jsPlumbProperty.saveConnectionProperties();
		}
		
		generateCodeFromTasksFlow(true, {
			do_not_change_to_code_tab: true, 
			
			success : function() {
				StatusMessageHandler.removeMessages("info");
			},
			error : function() {
				StatusMessageHandler.removeMessages("info");
			},
		});
	}
	
	setTimeout(function() {
		resizeCodeEditor();
		
		var editor = $("#code").data("editor");
		if (editor && $("#code").is(":visible"))
			editor.focus();
	}, 10);
}

function onClickTaskWorkflowTab(elm, options) {
	elm = $(elm);
	
	if (elm.attr("is_init") != 1) {
		elm.attr("is_init", 1);
		
		MyFancyPopup.init({
			parentElement: window,
		});
		MyFancyPopup.showOverlay();
		MyFancyPopup.showLoading();
		
		var selector = elm.attr("href");
		var p = elm.parent().closest("ul").parent();
		var ui_elm = p.children(selector);
		
		if (!ui_elm[0])
			ui_elm = p.find(selector);
		
		var workflow_menu = ui_elm.find(".workflow_menu");
		workflow_menu.hide();
		
		var auto_save_bkp = auto_save;
		auto_save = false;
		
		jsPlumbWorkFlow.jsPlumbTaskFile.read(get_workflow_file_url, {
			"success": function(data, textStatus, jqXHR) {
				resizeTaskFlowChart();
				
				MyFancyPopup.hidePopup();
				workflow_menu.show();
				jsPlumbWorkFlow.resizePanels();
				
				auto_save = auto_save_bkp;
				
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, get_workflow_file_url, function() {
						elm.removeAttr("is_init");
						jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
						onClickTaskWorkflowTab(elm[0], options);
					});
				else {
					//set workflow_id to #ui so it doesn't re-generate the code when clicked in the code tab
					var workflow_id = getCurrentWorkFlowId();
					var code = getEditorCodeRawValue();
					var new_code_id = $.md5(code);
					
					//Note that the code_id and the generated_code_id were already set in the onLoadTaskFlowChartAndCodeEditor, when the page loads
					$("#ui").attr("workflow_id", workflow_id);
					
					//If auto_convert is active and:
					//- if diagram is empty and code exists
					//- or if taskflow is not yet inited and we meanwhile changed the code
					//so we must generate a new diagram
					if (auto_convert) {
						var is_empty_diagram = code && (!data || (!$.isArray(data["tasks"]) && !$.isPlainObject(data["tasks"])) || $.isEmptyObject(data["tasks"]));
						var old_code_id = $("#ui").attr("code_id");
						
						if (is_empty_diagram || (old_code_id != new_code_id)) {
							generateTasksFlowFromCode(true, {
								force : true,
								success : function(data, textStatus, jqXHR) {
									StatusMessageHandler.removeMessages("info");
									
									if (typeof options == "object" && typeof options["on_success"] == "function")
										options["on_success"]();
									
									//save new workflow for the first time
									jsPlumbWorkFlow.jsPlumbTaskFile.save(null, {
										overwrite: true, 
										silent: true, 
										success: jsPlumbWorkFlow.jsPlumbTaskFile.save_options["success"], 
									});
								},
								error : function(jqXHR, textStatus, errorThrown) {
									StatusMessageHandler.removeMessages("info");
									
									if (typeof options == "object" && typeof options["on_error"] == "function")
										options["on_error"]();
								},
							});
						}
						else if (typeof options == "object" && typeof options["on_success"] == "function")
							options["on_success"]();
					}
					else if (typeof options == "object" && typeof options["on_success"] == "function")
						options["on_success"]();
				}
			},
			"error": function(jqXHR, textStatus, errorThrown) {
				resizeTaskFlowChart();
				
				MyFancyPopup.hidePopup();
				workflow_menu.show();
				jsPlumbWorkFlow.resizePanels();
				
				auto_save = auto_save_bkp;
				
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, get_workflow_file_url, function() {
						elm.removeAttr("is_init");
						jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
						onClickTaskWorkflowTab(elm[0], options);
					});
				else if (typeof options == "object" && typeof options["on_error"] == "function")
					options["on_error"]();
			},
		});
	}
	else {
		//when editing code, if auto_save is active, the system will try to convert the code to a workflow, but ig the php code contains errors (bc the user didn't finish yet his code, the generateTasksFlowFromCode will show errors, so we must remove this errors first, before generateTasksFlowFromCode.
		if (auto_save) {
			StatusMessageHandler.removeMessages("error");
			jsPlumbWorkFlow.jsPlumbStatusMessage.removeMessages("error");
		}
		
		setTimeout(function() {
			updateTasksFlow();
			resizeTaskFlowChart();
			
			jsPlumbWorkFlow.jsPlumbTaskFile.startAutoSave();
			
			if (auto_convert) {
				StatusMessageHandler.showMessage("Generating workflow based in code... Loading...");
				
				generateTasksFlowFromCode(true, {
					success : function(data, textStatus, jqXHR) {
						StatusMessageHandler.removeMessages("info");
						
						if (typeof options == "object" && typeof options["on_success"] == "function")
							options["on_success"]();
					},
					error : function(jqXHR, textStatus, errorThrown) {
						StatusMessageHandler.removeMessages("info");
						
						if (typeof options == "object" && typeof options["on_error"] == "function")
							options["on_error"]();
					},
				});
			}
		}, 10);
	}
}

function updateTasksFlow() {
	jsPlumbWorkFlow.getMyFancyPopupObj().updatePopup();
		
	var tasks = jsPlumbWorkFlow.jsPlumbTaskFlow.getAllTasks();
	
	for (var i = 0; i < tasks.length; i++) {
		var task = tasks[i];
		
		onEditLabel(task.id);
		jsPlumbWorkFlow.jsPlumbTaskFlow.repaintTask( $(task) );
	}
}

function getCurrentWorkFlowId() {
	var data = jsPlumbWorkFlow.jsPlumbTaskFile.getWorkFlowData();
	data = Object.assign({}, data);
	
	//regularize the sizes and offsets from tasks, so we can get the real workflow id and check if there were changes or not in the workflow...
	if (data && data["tasks"])
		for (var task_id in data["tasks"]) {
			data["tasks"][task_id]["width"] = 100;
			data["tasks"][task_id]["height"] = 100;
			data["tasks"][task_id]["offset_top"] = 0;
			data["tasks"][task_id]["offset_left"] = 0;
		}
	
	var workflow_id = $.md5(JSON.stringify(data));
	
	return workflow_id;
}

function generateCodeFromTasksFlow(do_not_confirm, options) {
	var status = true;
	var options = typeof options == "object" ? options : {};
	
	var old_workflow_id = $("#ui").attr("workflow_id");
	var new_workflow_id = getCurrentWorkFlowId();
	
	var generated_code_id = $("#code").attr("generated_code_id");
	var code = getEditorCodeRawValue();
	var new_code_id = $.md5(code);
	
	var is_tasks_flow_tab_inited = $("#code").parent().find(" > ul > #tasks_flow_tab > a").attr("is_init") == 1;
	
	if (is_tasks_flow_tab_inited && (
		old_workflow_id != new_workflow_id || (generated_code_id && generated_code_id != new_code_id) || options["force"]
	)) {
		if (do_not_confirm || auto_convert || confirm("Do you wish to update this code accordingly with the workflow tasks?")) {
			status = false;
			
			if (!is_from_auto_save) {
				MyFancyPopup.init({
					parentElement: window,
				});
				MyFancyPopup.showOverlay();
				MyFancyPopup.showLoading();
				$(".workflow_menu").hide();
			}
			
			var save_options = {
				overwrite: true,
				silent: true,
				do_not_silent_errors: true,
				success: function(data, textStatus, jqXHR) {
					if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
						showAjaxLoginPopup(jquery_native_xhr_object.responseURL, set_tmp_workflow_file_url, function() {
							jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
							StatusMessageHandler.removeLastShownMessage("error");
							generateCodeFromTasksFlow(true, options);
						});
				},
			};
			
			if (jsPlumbWorkFlow.jsPlumbTaskFile.save(set_tmp_workflow_file_url, save_options)) {
				//if not default start task, the system will try to figure out one by default, but is always good to show a message to the user alerting him of this situation...
				if (!is_from_auto_save) {
					var exists_start_tasks = $("#" + jsPlumbWorkFlow.jsPlumbTaskFlow.main_tasks_flow_obj_id + " ." + jsPlumbWorkFlow.jsPlumbTaskFlow.task_class_name + "." + jsPlumbWorkFlow.jsPlumbTaskFlow.start_task_class_name).length > 0;
					
					if (!exists_start_tasks)
						StatusMessageHandler.showMessage("There is no startup task selected. The system tried to select a default one, but is more reliable if you define one manually...");
				}
				
				$.ajax({
					type : "get",
					url : create_code_from_workflow_file_url,
					dataType : "json",
					success : function(data, textStatus, jqXHR) {
						if (data && data.hasOwnProperty("code")) {
							var code = "<?php\n" + data.code.trim() + "\n?>";
							setEditorCodeRawValue(code);
							var new_code_id = $.md5(code);
							
							$("#ui").attr("workflow_id", new_workflow_id);
							$("#ui").attr("code_id", new_code_id);
							$("#code").attr("generated_code_id", new_code_id);
							
							if (data["error"] && data["error"]["infinit_loop"] && data["error"]["infinit_loop"][0]) {
								var loops = data["error"]["infinit_loop"];
								
								var msg = "";
								for (var i = 0; i < loops.length; i++) {
									var loop = loops[i];
									var slabel = jsPlumbWorkFlow.jsPlumbTaskFlow.getTaskLabelByTaskId(loop["source_task_id"]);
									var tlabel = jsPlumbWorkFlow.jsPlumbTaskFlow.getTaskLabelByTaskId(loop["target_task_id"]);
									
									msg += (i > 0 ? "\n" : "") + "- '" + slabel + "' => '" + tlabel + "'";
								}
								
								msg = "The system detected the following invalid loops and discarded them from the code:\n" + msg + "\n\nYou should remove them from the workflow and apply the correct 'loop task' for doing loops.";
								jsPlumbWorkFlow.jsPlumbStatusMessage.showError(msg);
								
								if (typeof options["error"] == "function")
									options["error"]();
								
								alert(msg);
							}
							else {
								if (!options["do_not_change_to_code_tab"]) {
									var edit_tab = $("#raw_editor_tab a").first(); //bc of edit_template_simple
									edit_tab = edit_tab[0] ? edit_tab : $("#code_editor_tab a").first(); //bc of all others
									edit_tab.click();
								}
								
								status = true;
								
								if (typeof options["success"] == "function")
									options["success"]();
							}
						}
						else {
							StatusMessageHandler.showError("There was an error trying to update this code. Please try again.");
							
							if (typeof options["error"] == "function")
								options["error"]();
						}
						
						MyFancyPopup.hidePopup();
						$(".workflow_menu").show();
					},
					error : function(jqXHR, textStatus, errorThrown) { 
						var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
						
						if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
							showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_code_from_workflow_file_url, function() {
								generateCodeFromTasksFlow(true, options);
							});
						else {
							StatusMessageHandler.showError("There was an error trying to update this code. Please try again." + msg);
				
							MyFancyPopup.hidePopup();
							$(".workflow_menu").show();
							
							if (typeof options["error"] == "function")
								options["error"]();
						}
					},
					async : options.hasOwnProperty("async") ? options["async"] : true,
				});
			}
			else {
				StatusMessageHandler.showError("There was an error trying to update this code. Please try again.");
				MyFancyPopup.hidePopup();
				$(".workflow_menu").show();
				
				if (typeof options["error"] == "function")
					options["error"]();
			}
		}
		else if (typeof options["success"] == "function")
			options["success"]();
	}
	else {
		if (!is_from_auto_save) {
			if (!is_tasks_flow_tab_inited)
				StatusMessageHandler.showMessage("Tasks flow diagram was not loaded yet. Please open the tasks flow diagram first, before any conversion...");
			else
				StatusMessageHandler.showMessage("The tasks flow diagram has no changes. No need to update the code.");
		}
		
		if (typeof options["success"] == "function")
			options["success"]();
	}
	
	return status;
}

function generateTasksFlowFromCode(do_not_confirm, options) {
	var status = true;
	var options = typeof options == "object" ? options : {};
	
	var old_code_id = $("#ui").attr("code_id");
	var code = getEditorCodeRawValue();
	var new_code_id = $.md5(code);
	
	if (old_code_id != new_code_id || options["force"]) {
		if (do_not_confirm || auto_convert || confirm("Do you wish to update this workflow accordingly with the code in the editor?")) {
			status = false;
			
			if (!is_from_auto_save) {
				jsPlumbWorkFlow.getMyFancyPopupObj().hidePopup();
				MyFancyPopup.init({
					parentElement: window,
				});
				MyFancyPopup.showOverlay();
				MyFancyPopup.showLoading();
				$(".workflow_menu").hide();
			}
			
			var auto_save_bkp = auto_save;
			auto_save = false;
			
			$.ajax({
				type : "post",
				url : create_workflow_file_from_code_url,
				data : code,
				dataType : "text",
				success : function(data, textStatus, jqXHR) {
					if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL)) {
						auto_save = auto_save_bkp;
						
						showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_workflow_file_from_code_url, function() {
							generateTasksFlowFromCode(true, options);
						});
					}
					else if (data == 1) {
						var previous_callback = jsPlumbWorkFlow.jsPlumbTaskFile.on_success_read;
						var previous_tasks_flow_saved_data_obj = jsPlumbWorkFlow.jsPlumbTaskFile.saved_data_obj; //save the previous jsPlumbTaskFile.saved_data_obj, bc when we run the jsPlumbTaskFile.reload method, this var will be with the new workflow data obj and then the auto_save won't run bc the jsPlumbTaskFile.isWorkFlowChangedFromLastSaving will return false. So we must save this var before and then re-put it again with the previous value.
						
						jsPlumbWorkFlow.jsPlumbTaskFile.on_success_read = function(data, text_status, jqXHR) {
							if (!data) {
								jsPlumbWorkFlow.jsPlumbStatusMessage.showError("There was an error trying to load the workflow's tasks.");
								
								if (typeof options["error"] == "function")
									options["error"]();
							}
							else {
								jsPlumbWorkFlow.jsPlumbTaskSort.sortTasks();
								
								setTimeout(function() { //must be in timeout otherwise the connections will appear weird
									jsPlumbWorkFlow.jsPlumbTaskFlow.repaintAllTasks();
								}, 5);
								
								$("#code").attr("generated_code_id", new_code_id);
								$("#ui").attr("code_id", new_code_id);
								$("#ui").attr("workflow_id", getCurrentWorkFlowId());
								
								status = true;
								
								if (typeof options["success"] == "function")
									options["success"]();
							}
							
							auto_save = auto_save_bkp;
							
							//The jsPlumbTaskFile will call after this function the jsPlumbTaskFile.startAutoSave method which updates the jsPlumbTaskFile.saved_data_obj var with the new workflow data obj. So we must execute a setTimeout so we can then update the old value to the jsPlumbTaskFile.saved_data_obj var.
							setTimeout(function() {
								jsPlumbWorkFlow.jsPlumbTaskFile.saved_data_obj = previous_tasks_flow_saved_data_obj;
							}, 100);
							
							jsPlumbWorkFlow.jsPlumbTaskFile.on_success_read = previous_callback;
						}
						
						jsPlumbWorkFlow.jsPlumbTaskFile.reload(get_tmp_workflow_file_url, {
							"async": true,
							error: function() {
								auto_save = auto_save_bkp;
							}
						});
					}
					else {
						jsPlumbWorkFlow.jsPlumbStatusMessage.showError("There was an error trying to update this workflow. Please try again." + (data ? "\n" + data : ""));
						auto_save = auto_save_bkp;
					
						if (typeof options["error"] == "function")
							options["error"]();
					}
					
					if (!is_from_auto_save) {
						MyFancyPopup.hidePopup();
						$(".workflow_menu").show();
					}
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
					jsPlumbWorkFlow.jsPlumbStatusMessage.showError("There was an error trying to update this workflow. Please try again." + msg);
					auto_save = auto_save_bkp;
					
					if (typeof options["error"] == "function")
						options["error"]();
					
					if (!is_from_auto_save) {
						MyFancyPopup.hidePopup();
						$(".workflow_menu").show();
					}
				},
				async : options.hasOwnProperty("async") ? options["async"] : true,
			});
		}
		else if (typeof options["success"] == "function")
			options["success"]();
	}
	else {
		if (!is_from_auto_save)
			StatusMessageHandler.showMessage("The code has no changes. No need to update the tasks flow diagram.");
		
		if (typeof options["success"] == "function")
			options["success"]();
	}
	
	return status;
}

/* SAVING FUNCTIONS */

function isCodeAndWorkflowObjChanged(main_obj_with_tabs) {
	//checks if code is different
	var code = getEditorCodeRawValue();
	var new_code_id = $.md5(code);
	var old_code_id = $("#ui").attr("code_id");
	
	var is_changed = old_code_id != new_code_id;
	
	//if code is the same and task flow tab was already opened by user, checks if diagram is different but ignoring the tasks positioning, this is, only comparing the tasks' data.
	if (!is_changed) {
		var is_tasks_flow_tab_inited = main_obj_with_tabs.find(" > ul > #tasks_flow_tab > a").attr("is_init");
		
		if (is_tasks_flow_tab_inited) {
			var old_workflow_id = $("#ui").attr("workflow_id");
			var new_workflow_id = getCurrentWorkFlowId();
			
			is_changed = old_workflow_id != new_workflow_id;
			
			//if code and diagram are the same, checks if the the tasks positioning are different
			if (!is_changed) {
				var selected_tab = main_obj_with_tabs.children("ul").find("li.ui-tabs-selected, li.ui-tabs-active").first();
				
				is_changed = selected_tab.attr("id") == "tasks_flow_tab" && jsPlumbWorkFlow.jsPlumbTaskFile.isWorkFlowChangedFromLastSaving(); //compares if tasks' sizes and offsets are different, but only if workflow tab is selected.
			}
		}
	}
	
	/*console.log("is_changed:"+is_changed);
	console.log("old_workflow_id:"+old_workflow_id);
	console.log("new_workflow_id:"+new_workflow_id);
	console.log("old_code_id:"+old_code_id);
	console.log("new_code_id:"+new_code_id);*/
	
	return is_changed;
}

function getCodeForSaving(parent_elm, options) {
	prepareAutoSaveVars();
	
	if (!is_from_auto_save) { //only show loading bar if a manual save action
		MyFancyPopup.init({
			parentElement: window,
		});
		MyFancyPopup.showOverlay();
		MyFancyPopup.showLoading();
		
		$(".workflow_menu").hide();
	}
	
	options = typeof options == "object" ? options : {};
	
	var raw_code = getEditorCodeRawValue();
	var code = options.strip_php_tags ? getEditorCodeValue() : raw_code;
	var new_code_id = $.md5(raw_code);
	var old_code_id = $("#ui").attr("code_id");
	
	var is_tasks_flow_tab_inited = parent_elm.find("#tasks_flow_tab a").attr("is_init");
	var status = true;
	
	if (is_tasks_flow_tab_inited) {
		var old_workflow_id = $("#ui").attr("workflow_id");
		var new_workflow_id = getCurrentWorkFlowId();
		var selected_tab = parent_elm.children("ul").find("li.ui-tabs-selected, li.ui-tabs-active").first();
		
		//if in tasks_flow_tab, saves workflow if manual save action, then gets the new code to be saved, if auto_convert is active or if user accepts confirmation message.
		if (selected_tab.attr("id") == "tasks_flow_tab") {
			updateTasksFlow();
			
			//close properties popup in case the auto_save be active on close task properties popup, but only if is not auto_save, otherwise the task properties can become messy, like it happens with the task inlinehtml.
			if (auto_save && jsPlumbWorkFlow.jsPlumbProperty.auto_save && !is_from_auto_save) {
				if (jsPlumbWorkFlow.jsPlumbProperty.isSelectedTaskPropertiesOpen())
					jsPlumbWorkFlow.jsPlumbProperty.saveTaskProperties();
				else if (jsPlumbWorkFlow.jsPlumbProperty.isSelectedConnectionPropertiesOpen())
					jsPlumbWorkFlow.jsPlumbProperty.saveConnectionProperties();
				
				new_workflow_id = getCurrentWorkFlowId();
			}
			
			//save workflow
			if (jsPlumbWorkFlow.jsPlumbTaskFile.isWorkFlowChangedFromLastSaving() || !is_from_auto_save) //if it is a manual save action, saves workflow
				status = jsPlumbWorkFlow.jsPlumbTaskFile.save(null, {
					overwrite: true, 
					silent: true, 
					do_not_silent_errors: !is_from_auto_save, //only show errors if not from auto_save
					success: jsPlumbWorkFlow.jsPlumbTaskFile.save_options["success"], 
				});
			
			//generate code if some changes in workflow
			if (status && ((old_workflow_id != new_workflow_id) || (old_code_id != new_code_id))) {
				var convert_code = auto_convert || (!is_from_auto_save && confirm("Do you wish to generate new code based in the Workflow UI tab, before you save?\nIf you click the cancel button, the system will discard the changes in the UI tab and give preference to the Code tab.")); //if auto_convert is active or is a manual user save action with a confirmation msg.
				
				if (convert_code) {
					status = generateCodeFromTasksFlow(true, {do_not_change_to_code_tab: auto_convert, async: false});
					
					if (status) 
						code = options.strip_php_tags ? getEditorCodeValue() : getEditorCodeRawValue();
				}
			}
		}
		else if (selected_tab.attr("id") == "code_editor_tab" || selected_tab.attr("id") == "visual_editor_tab") { //if in code_editor_tab or in visual_editor_tab and auto_convert is active, saves the code as it is and then converts asynchronous the workflow and then save it.
			if (auto_convert)
				generateTasksFlowFromCode(true, {
					success: function() {
						//only saves workflow if it was really generated
						if (old_code_id != new_code_id) { 
							//disable auto_convert so it doesn't call the generateTasksFlowFromCode method in onClickTaskWorkflowTab
							auto_convert = false;
							
							//click in workflow tab
							parent_elm.find("ul #tasks_flow_tab a").first().click();
							updateTasksFlow();
							
							//save workflow
							jsPlumbWorkFlow.jsPlumbTaskFile.save(null, {
								overwrite: true, 
								silent: true, 
								do_not_silent_errors: true,
								success: jsPlumbWorkFlow.jsPlumbTaskFile.save_options["success"],
							});
							
							//click in code tab
							selected_tab.children("a").click();
							
							//enable auto_convert
							auto_convert = true;
						}
						else //remove messages saying there are no changes to save
							StatusMessageHandler.removeMessages("info");
					},
				});
		}
		else { //if in other tab (like any other tab that is not the code and task_flow tabs), saves workflow and if workflow is changed, generate the new code
			//backup auto_convert value
			var auto_convert_bkp = auto_convert;
			
			auto_convert = false; //set auto_convert to false so it doesn't call the generateTasksFlowFromCode method in onClickTaskWorkflowTab
		
			//click in workflow tab
			parent_elm.find("ul #tasks_flow_tab a").first().click();
			updateTasksFlow();
			
			//close properties popup in case the auto_save be active on close task properties popup, but only if is not auto_save, otherwise the task properties can become messy, like it happens with the task inlinehtml.
			if (auto_save && jsPlumbWorkFlow.jsPlumbProperty.auto_save && !is_from_auto_save) {
				if (jsPlumbWorkFlow.jsPlumbProperty.isSelectedTaskPropertiesOpen())
					jsPlumbWorkFlow.jsPlumbProperty.saveTaskProperties();
				else if (jsPlumbWorkFlow.jsPlumbProperty.isSelectedConnectionPropertiesOpen())
					jsPlumbWorkFlow.jsPlumbProperty.saveConnectionProperties();
				
				new_workflow_id = getCurrentWorkFlowId();
			}
			
			//save workflow
			if (jsPlumbWorkFlow.jsPlumbTaskFile.isWorkFlowChangedFromLastSaving() || !is_from_auto_save) //if it is a manual save action, saves workflow
				status = jsPlumbWorkFlow.jsPlumbTaskFile.save(null, {
					overwrite: true, 
					silent: true, 
					do_not_silent_errors: !is_from_auto_save, //only show errors if not from auto_save
					success: jsPlumbWorkFlow.jsPlumbTaskFile.save_options["success"], 
				});
			
			//generate code if some changes in workflow
			if (status && ((old_workflow_id != new_workflow_id) || (old_code_id != new_code_id))) {
				var convert_code = auto_convert_bkp || (!is_from_auto_save && confirm("Do you wish to generate new code based in the Workflow UI tab, before you save?\nIf you click the cancel button, the system will discard the changes in the UI tab and give preference to the Code tab.")); //if auto_convert is active or is a manual user save action with a confirmation msg.
				
				if (convert_code) {
					status = generateCodeFromTasksFlow(true, {do_not_change_to_code_tab: auto_convert_bkp, async: false});
					
					if (status) 
						code = options.strip_php_tags ? getEditorCodeValue() : getEditorCodeRawValue();
				}
			}
			
			//click in code tab
			selected_tab.children("a").click();
			
			//reset the auto_convert with previous value
			auto_convert = auto_convert_bkp;
		}
	}
	
	return status ? code : null;
}

function saveObjCode(save_object_url, obj, opts) {
	if (obj && obj.code != null)
		saveObj(save_object_url, obj, opts);
	else {
		opts = opts ? opts : {};
		
		if (typeof opts.error != "function" || opts.error()) {
			if (!is_from_auto_save)
				StatusMessageHandler.showError("Error trying to generate new code and saving the tasks' flow. Please try again...");
		}
		
		if (!is_from_auto_save) {
			MyFancyPopup.hidePopup();
			$(".workflow_menu").show();
		}
		else
			resetAutoSave();
	}
}

function saveObj(save_object_url, obj, opts) {
	opts = opts ? opts : {};
	
	var url = save_object_url + (typeof file_modified_time != "undefined" && file_modified_time ? (save_object_url.indexOf("?") != -1 ? "&" : "?") + "file_modified_time=" + file_modified_time : "");
	var url_aux = save_object_url;
	
	if (typeof opts.parse_url == "function") {
		url = opts.parse_url(url);
		url_aux = opts.parse_url(url_aux);
	}
	
	var new_saved_obj_id = $.md5(url_aux + JSON.stringify(obj));
	
	//only saves if object is different
	if (!saved_obj_id || saved_obj_id != new_saved_obj_id) {
		var ajax_options = {
			type : "post",
			url : url,
			data : {"object" : obj},
			dataType : "text",
			success : function(data, textStatus, jqXHR) {
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, url, function() {
						saveObj(save_object_url, obj, opts);
					});
				else {
					var json_data = data && ("" + data).substr(0, 1) == "{" ? JSON.parse(data) : null;
					var status = parseInt(data) == 1 || ($.isPlainObject(json_data) && json_data["status"] == 1);
					var file_was_changed = !status && $.isPlainObject(json_data) && json_data["status"] == "CHANGED";
					
					if(status) {
						if ($.isPlainObject(json_data) && json_data["modified_time"])
							file_modified_time = json_data["modified_time"];
						
						if (typeof opts.success != "function" || opts.success(data, textStatus, jqXHR)) {
							if (!is_from_auto_save) //only show message if a manual save action
								StatusMessageHandler.showMessage("Saved successfully.");
						}
						
						//sets new saved_obj_id
						saved_obj_id = new_saved_obj_id;
					}
					else if (file_was_changed) {
						if (!is_from_auto_save) { 
							//only show this message if is a manual save, otherwise we don't want to do anything. Otherwise the browser is showing this popup constantly and is annoying for the user.
							showConfirmationCodePopup(json_data["old_code"], json_data["new_code"], {
								save: function() {
									//remove file_modified_time so it doesn't check if the file was changed. 
									var pu = opts.parse_url;
									
									opts.parse_url = function(url) {
										url = typeof pu == "function" ? pu(url) : url;
										return url.replace(/(&|\?)file_modified_time=([0-9]*)/g, "");
									};
									
									if (typeof opts.confirmation_save != "function" || opts.confirmation_save(data)) {
										saveObj(save_object_url, obj, opts);
										
										return true;
									}
									else if (is_from_auto_save)
										resetAutoSave();
								},
								cancel: function() {
									if (is_from_auto_save)
										resetAutoSave();
									
									return typeof opts.confirmation_cancel != "function" || opts.confirmation_cancel(data);
								},
							});
						}
						else
							resetAutoSave();
					}
					else if (typeof opts.error != "function" || opts.error(jqXHR, textStatus, data)) {
						if (!is_from_auto_save) //only show error if manual save, bc if is auto_save the user shouldn't be bother with errors while is changing the code.
							StatusMessageHandler.showError("Error trying to save new changes. Please try again..." + (data ? "\n" + data : ""));
					}
					
					if (!is_from_auto_save) {
						MyFancyPopup.hidePopup();
						$(".workflow_menu").show();
					}
					else
						resetAutoSave();
				}
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, url, function() {
						saveObj(save_object_url, obj, opts);
					});
				else {
					if (typeof opts.error != "function" || opts.error(jqXHR, textStatus, errorThrown)) 
						StatusMessageHandler.showError((errorThrown ? errorThrown + " error.\n" : "") + "Error trying to save new changes. Please try again..." + (jqXHR.responseText ? "\n" + jqXHR.responseText : ""));
					
					if (!is_from_auto_save) {
						MyFancyPopup.hidePopup();
						$(".workflow_menu").show();
					}
					else
						resetAutoSave();
				}
			},
		};
		
		if (is_from_auto_save && auto_save_connection_ttl)
			ajax_options["timeout"] = auto_save_connection_ttl; //add timeout to auto save connection.
		
		if (opts.hasOwnProperty("async")) 
			ajax_options["async"] = opts["async"];
		
		$.ajax(ajax_options);
	}
	else if (!is_from_auto_save) {
		StatusMessageHandler.showMessage("Nothing to save.");
		
		MyFancyPopup.hidePopup();
		$(".workflow_menu").show();
	}
	else
		resetAutoSave();
}

function showConfirmationCodePopup(old_code, new_code, opts) {
	old_code = typeof old_code == "undefined" || old_code == null ? "" : old_code;
	new_code = typeof new_code == "undefined" || new_code == null ? "" : new_code;
	opts = opts ? opts : {};
	
	var confirm_save_elm = $(".confirm_save");
	
	//prepare popup
	if (!confirm_save_elm[0]) {
		confirm_save_elm = $('<div class="confirm_save hidden">'
			+ '	<div class="title">Please confirm if the code is correct and if it is, click on the save button...</div>'
			+ '	'
			+ '	<div class="code_comparison">'
			+ '		<div class="old_code">'
			+ '			<label>Old code:</label>'
			+ '			<pre><code class="php"></code></pre>'
			+ '		</div>'
			+ '		<div class="new_code">'
			+ '			<label>New code to be saved:</label>'
			+ '			<pre><code class="php"></code></pre>'
			+ '		</div>'
			+ '	</div>'
			+ '	'
			+ '	<div class="buttons"></div>'
			+ '	'
			+ '	<div class="disable_auto_scroll">Click here to disable auto scroll.</div>'
			+ '	<div class="disable_word_wrap">Click here to enable word wrap.</div>'
			+ '</div>');
		
		$("body").append(confirm_save_elm);
		
		//prepare code ui
		var old_code_area = confirm_save_elm.find(".code_comparison .old_code pre code");
		var new_code_area = confirm_save_elm.find(".code_comparison .new_code pre code");
		var old_code_parent = old_code_area.parent();
		var new_code_parent = new_code_area.parent();
		
		old_code_parent.scroll(function() {
			if (auto_scroll_active) {
				auto_scroll_active = false;
				
				new_code_parent.scrollTop( $(this).scrollTop() );
				new_code_parent.scrollLeft( $(this).scrollLeft() );
				
				setTimeout(function() {
					auto_scroll_active = true;
				}, 50);
			}
		});
		
		new_code_parent.scroll(function() {
			if (auto_scroll_active) {
				auto_scroll_active = false;
				
				old_code_parent.scrollTop( $(this).scrollTop() );
				old_code_parent.scrollLeft( $(this).scrollLeft() );
				
				setTimeout(function() {
					auto_scroll_active = true;
				}, 50);
			}
		});

		confirm_save_elm.children(".disable_auto_scroll").on("click", function (ev) {
			auto_scroll_active = !auto_scroll_active;
			$(this).html(auto_scroll_active ? "Click here to disable auto scroll." : "Click here to enable auto scroll.");
		});

		confirm_save_elm.children(".disable_word_wrap").on("click", function (ev) {
			word_wrap_active = !word_wrap_active;
			$(this).html(word_wrap_active ? "Click here to disable word wrap." : "Click here to enable word wrap.");
			
			if (word_wrap_active) {
				old_code_area.css({"display": "block", "white-space": "pre-line"});
				new_code_area.css({"display": "block", "white-space": "pre-line"});
			}
			else {
				old_code_area.css({"display": "", "white-space": ""});
				new_code_area.css({"display": "", "white-space": ""});
			}
		});
	}
	
	//prepare buttons
	var buttons = confirm_save_elm.find(".buttons");
	buttons.html('<input class="cancel" type="button" name="cancel" value="Cancel" /><input class="save" type="button" name="save" value="Save" />'); //be sure that everytime the popup is open has new buttons with new handlers
	
	buttons.children(".save").on("click", function(ev) {
		if (typeof opts.save != "function" || opts.save())
			confirm_save_elm.hide();
	});
	
	buttons.children(".cancel").on("click", function(ev) {
		if (typeof opts.cancel != "function" || opts.cancel())
			confirm_save_elm.hide();
	});
	
	//prepare codes
	var old_code_area = confirm_save_elm.find(".code_comparison .old_code pre code");
	var old_code_parsed = old_code ? old_code.replace(/>/g, "&gt;").replace(/</g, "&lt;") : "";
	old_code_area.html(old_code_parsed);
	
	var new_code_area = confirm_save_elm.find(".code_comparison .new_code pre code");
	var new_code_parsed = new_code ? new_code.replace(/>/g, "&gt;").replace(/</g, "&lt;") : "";
	new_code_area.html(new_code_parsed);
	
	confirm_save_elm.show();
	
	if (typeof hljs == "object") {
		hljs.highlightBlock(old_code_area[0]);
		hljs.highlightBlock(new_code_area[0]);
	}
	
	if (old_code.trim() == "" || old_code.trim().hashCode() == new_code.trim().hashCode())
		buttons.children(".save").trigger("click");
}
