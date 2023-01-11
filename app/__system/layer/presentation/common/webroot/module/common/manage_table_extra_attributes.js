$(function() {
	var properties_html_elm = $(".manage_table_exta_attributes > .table_settings > .selected_task_properties");
	var task_html_elm = properties_html_elm.children(".db_table_task_html");
	
	//load allow_javascript and file_type
	loadExtraTableTaskProperty(task_html_elm, task_property_values);
});

function loadExtraTableTaskProperty(task_html_elm, task_property_values) {
	if (task_property_values && task_property_values.table_attr_names) {
		var selector = task_html_elm.hasClass("attributes_list_shown") ? ".list_attributes .list_attrs" : ".table_attrs";
		var table_inputs = task_html_elm.find(selector + " .table_attr_name input");
		var simple_inputs = task_html_elm.find(".simple_attributes > ul li .simple_attr_name");
		
		$.each(task_property_values.table_attr_names, function(i, table_attr_name) {
			//get values
			var file_type = task_property_values.hasOwnProperty("table_attr_file_types") ? task_property_values.table_attr_file_types[i] : null;
			var allow_javascript = task_property_values.hasOwnProperty("table_attr_allow_javascripts") ? checkIfValueIsTrue(task_property_values.table_attr_allow_javascripts[i]) : false;
			
			//prepare html with extra attributes
			var table_item = $(table_inputs[i]).parent().closest("tr, li");
			var simple_item = $(simple_inputs[i]).parent().closest("li");
			
			addExtraTableAttributes(table_item, allow_javascript, file_type);
			addExtraSimpleAttributes(simple_item, allow_javascript, file_type);
		});
	}
}

function addExtraTableAttributes(table_item, allow_javascript, file_type) {
	var table_type_select = table_item.find(".table_attr_type select");
	var type = table_type_select.val();
	
	//add new type
	var is_primary_key = table_item.find(".table_attr_primary_key input").is(":checked");
	type = type == "bigint" && file_type && !is_primary_key ? "attachment" : type;
	table_type_select.val(type);
	
	//add new properties: allow_javascript and file_type
	var column_types_ignored_props = $.isPlainObject(DBTableTaskPropertyObj.column_types_ignored_props) ? DBTableTaskPropertyObj.column_types_ignored_props : {};
	var column_type_ignored_props = type && column_types_ignored_props.hasOwnProperty(type) && $.isArray(column_types_ignored_props[type]) ? column_types_ignored_props[type] : [];
	var is_allow_javascript_disabled = !type || $.inArray("allow_javascript", column_type_ignored_props) != -1;
	var is_file_type_disabled = $.inArray("file_type", column_type_ignored_props) != -1;
	
	var file_types_options_html = '';
	
	for (var type_id in available_file_types) {
		var type_name = available_file_types[type_id];
		file_types_options_html += '<option value="' + type_id + '">' + type_name + '</option>';
	}
	
	var is_table = table_item.is("tr");
	var thead = null;
	var table_allow_javascript_html = "", table_file_type_html = "";
	
	if (is_table) {
		thead = table_item.parent().closest("table").find("thead tr");
		
		table_allow_javascript_html = '<td class="table_attr_allow_javascript"><input type="checkbox" value="1" class="task_property_field" name="table_attr_allow_javascripts[]" ' + (is_allow_javascript_disabled ? 'disabled="disabled"' : '') + ' /></td>';
		table_file_type_html = '<td class="table_attr_file_type"><select class="task_property_field" name="table_attr_file_types[]" title="In order to be activated, please change the attribute type to Bigint or Attachment" ' + (is_file_type_disabled ? 'disabled="disabled"' : '') + '>' + file_types_options_html + '</select></td>';
	}
	else {
		thead = table_item.parent().closest(".list_attributes").parent().find(".table_attrs").parent().closest("table").find("thead tr");
		
		table_allow_javascript_html = '<div class="table_attr_allow_javascript"><label>Allow Javascript:</label><input type="checkbox" value="1" class="task_property_field" name="table_attr_allow_javascripts[]" ' + (is_allow_javascript_disabled ? 'disabled="disabled"' : '') + ' /></div>';
		table_file_type_html = '<div class="table_attr_file_type"><label>File Type:</label><select class="task_property_field" name="table_attr_file_types[]" title="In order to be activated, please change the attribute type to Bigint or Attachment" ' + (is_file_type_disabled ? 'disabled="disabled"' : '') + '>' + file_types_options_html + '</select></div>';
	}
	
	table_item.find(".table_attr_auto_increment").after(table_allow_javascript_html);
	table_item.find(".table_attr_comment, .table_attr_icons").first().before(table_file_type_html);
	
	if (thead.find(".table_attr_allow_javascript, .table_attr_file_type").length == 0) {
		var table_allow_javascript_head_html = '<th class="table_attr_allow_javascript table_header">Allow Javascript</th>';
		var table_file_type_head_html = '<th class="table_attr_file_type table_header">File Type</th>';
		
		thead.find(".table_attr_auto_increment").after(table_allow_javascript_head_html);
		thead.find(".table_attr_comment, .table_attr_icons").first().before(table_file_type_head_html);
	}
	
	//set allow_javascript and file_type
	var table_file_type_select = table_item.find(".table_attr_file_type select");
	
	table_file_type_select.val(file_type);
	
	if (file_type && table_file_type_select.val() != file_type)
		table_file_type_select.append('<option value="' + file_type + '" selected>' + file_type + ' - NON DEFAULT</option>');
	
	if (allow_javascript)
		table_item.find(".table_attr_allow_javascript input").prop("checked", true).attr("checked", "checked");
	else
		table_item.find(".table_attr_allow_javascript input").prop("checked", false).removeAttr("checked");
}

function addExtraSimpleAttributes(simple_item, allow_javascript, file_type) {
	var simple_type_select = simple_item.find(".simple_attr_type");
	var type = simple_type_select.val();
	
	//prepare type in case is a simple type
	var column_simple_types = $.isPlainObject(DBTableTaskPropertyObj.column_simple_types) ? DBTableTaskPropertyObj.column_simple_types : {};
	var column_simple_custom_types = $.isPlainObject(DBTableTaskPropertyObj.column_simple_custom_types) ? DBTableTaskPropertyObj.column_simple_custom_types : {};
	
	var simple_props = type && column_simple_types.hasOwnProperty(type) ? column_simple_types[type] : (
		type && column_simple_custom_types.hasOwnProperty(type) ? column_simple_custom_types[type] : null
	);
	
	if (simple_props) {
		//change the value with the real DB value, so we can use the value to prepare the ignored fields below.
		if ($.isArray(simple_props["type"]))
			type = simple_props["type"][0];
		else
			type = simple_props["type"];
	}
	
	//add new type
	var is_primary_key = simple_item.find(".simple_attr_primary_key").is(":checked");
	type = type == "bigint" && file_type && !is_primary_key ? "attachment" : type;
	simple_type_select.val(type);
	
	//fake disable in some of the simple types:
	simple_type_select.find("option").each(function(idx, option) {
		option = $(option);
		
		if (option.val().indexOf("primary_key") != -1) {
			option.append(" (Not Apply)");
			option.addClass("not_apply");
		}
	});
	
	//add new properties: allow_javascript and file_type
	var column_types_ignored_props = $.isPlainObject(DBTableTaskPropertyObj.column_types_ignored_props) ? DBTableTaskPropertyObj.column_types_ignored_props : {};
	var column_type_ignored_props = type && column_types_ignored_props.hasOwnProperty(type) && $.isArray(column_types_ignored_props[type]) ? column_types_ignored_props[type] : [];
	var is_allow_javascript_disabled = !type || $.inArray("allow_javascript", column_type_ignored_props) != -1;
	var is_file_type_disabled = $.inArray("file_type", column_type_ignored_props) != -1;
	
	var file_types_options_html = '';
	
	for (var type_id in available_file_types) {
		var type_name = available_file_types[type_id];
		file_types_options_html += '<option value="' + type_id + '">' + type_name + '</option>';
	}
	
	var simple_allow_javascript_html = '<li><label>Allow Javascript:</label><input type="checkbox" value="1" class="simple_attr_allow_javascript" ' + (is_allow_javascript_disabled ? 'disabled="disabled"' : '') + ' /></li>';
	var simple_file_type_html = '<li><label>File Type:</label><select class="simple_attr_file_type" title="In order to be activated, please change the attribute type to Bigint or Attachment" ' + (is_file_type_disabled ? 'disabled="disabled"' : '') + '>' + file_types_options_html + '</select></li>';
	
	simple_item.children("ul").append(simple_allow_javascript_html + simple_file_type_html);
	
	//set allow_javascript and file_type
	var simple_file_type_select = simple_item.find(".simple_attr_file_type");
	
	simple_file_type_select.val(file_type);
	
	if (file_type && simple_file_type_select.val() != file_type)
		simple_file_type_select.append('<option value="' + file_type + '" selected>' + file_type + ' - NON DEFAULT</option>');
	
	if (allow_javascript)
		simple_item.find(".simple_attr_allow_javascript").prop("checked", true).attr("checked", "checked");
	else
		simple_item.find(".simple_attr_allow_javascript").prop("checked", false).removeAttr("checked");
}

function onUpdateExtraSimpleAttributesHtmlWithTableAttributes(elm) {
	onUpdateSimpleAttributesHtmlWithTableAttributes(elm);
	
	var task_html_elm = $(elm).closest(".db_table_task_html");
	
	//get table inputs for attribute name
	var selector = task_html_elm.hasClass("attributes_list_shown") ? ".list_attributes .list_attrs" : ".table_attrs";
	var table_inputs = task_html_elm.find(selector + " .table_attr_name input");
	var simple_inputs = task_html_elm.find(".simple_attributes > ul li .simple_attr_name");
	
	//set old_names from table inputs to simple inputs
	$.each(table_inputs, function(idx, table_input) {
		var table_item = $(table_input).parent().closest("tr, li");
		var simple_input = simple_inputs[idx];
		var simple_item = $(simple_input).parent().closest("li");
		
		//get values
		var allow_javascript = table_item.find(".table_attr_allow_javascript input").is(":checked");
		var file_type = table_item.find(".table_attr_file_type select").val();
		
		//prepare html with extra attributes
		addExtraSimpleAttributes(simple_item, allow_javascript, file_type);
	});
}

function onUpdateExtraTableAttributesHtmlWithSimpleAttributes(elm) {
	onUpdateTableAttributesHtmlWithSimpleAttributes(elm);
	
	var task_html_elm = $(elm).closest(".db_table_task_html");
	var table_inputs = task_html_elm.find(".table_attrs .table_attr_name input"); //no need to check the list_attributes .list_attrs, bc this function runs always with the .table_attrs
	var simple_inputs = task_html_elm.find(".simple_attributes > ul li .simple_attr_name");
	
	//set old_names from table inputs to simple inputs
	$.each(simple_inputs, function(idx, simple_input) {
		var simple_item = $(simple_input).parent().closest("li");
		var table_input = table_inputs[idx];
		var table_item = $(table_input).parent().closest("tr");
		
		//get values
		var allow_javascript = simple_item.find(".simple_attr_allow_javascript").is(":checked");
		var file_type = simple_item.find(".simple_attr_file_type").val();
		
		//prepare html with extra attributes
		addExtraTableAttributes(table_item, allow_javascript, file_type);
	});
}

function onAddExtraSimpleAttribute(elm) {
	//prepare html with extra attributes
	addExtraSimpleAttributes(elm);
}

function onAddExtraTableAttribute(elm) {
	//prepare html with extra attributes
	addExtraTableAttributes(elm);
}
