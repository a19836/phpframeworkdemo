var editors_inited = false;
var step = false;

$(function() {
	var edit_table = $(".edit_table");
	
	edit_table.accordion({
     	//collapsible: true,
 		disabled: true,
 		active: step,
 		heightStyle: "fill",
	});
	
	if (step == 1) {
		editors_inited = true;
		edit_table.find(".table_sql_statements .sql_statement > textarea.editor").each(function(idx, textarea) {
			createSqlEditor(textarea);
		});
	}
});

function createSqlEditor(textarea) {
	if (textarea) {
		var p = $(textarea).parent();
		
		ace.require("ace/ext/language_tools");
		var editor = ace.edit(textarea);
		editor.setTheme("ace/theme/chrome");
		editor.session.setMode("ace/mode/sql");
	    	editor.setAutoScrollEditorIntoView(true);
		editor.setOption("maxLines", "Infinity");
		editor.setOption("minLines", 2);
		editor.setOptions({
			enableBasicAutocompletion: true,
			enableSnippets: true,
			enableLiveAutocompletion: false,
		});
		editor.setOption("wrap", true);
		editor.$blockScrolling = "Infinity";
		
		editor.getSession().on("change", function () {
			var t = p.children("textarea:not(.editor)");
			t.val(editor.getSession().getValue());
		});
		
		p.find("textarea.ace_text-input").removeClass("ace_text-input"); //fixing problem with scroll up, where when focused or pressed key inside editor the page scrolls to top.
		
		p.data("editor", editor);
	}
}

function addTableAttribute(elm) {
	elm = $(elm);
	tbody = elm.parent().closest(".edit_table").find("table").children("tbody");
	var idx = getListNewIndex(tbody);
	var html = $( attribute_html.replace(/#idx#/g, idx) );
	
	tbody.append(html);
	
	tbody.children(".no_attributes").hide();
}

function removeTableAttribute(elm) {
	elm = $(elm);
	
	if (elm.attr("confirm") != 1 || confirm("You are about to remove this attribute in this table. All correspondent data will be lost!\nDo you wish to continue?")) {
		var tr = elm.parent().closest("tr");
		var tbody = tr.parent();
		tr.remove();
		
		if (tbody.children().not(".no_attributes").length == 0)
			tbody.children(".no_attributes").show();
	}
}

function onClickCheckBox(elm) {
	elm = $(elm);
	var parent = elm.parent();
	
	if (parent.hasClass("table_attr_has_default")) {
		var default_field = parent.parent().find('.table_attr_default input');
	
		if(elm.is(":checked")) 
			default_field.removeAttr('disabled');
		else
			default_field.attr('disabled', 'disabled').val('');
	}
	else if (elm.is(":checked") && parent.hasClass("table_attr_primary_key")) {
		var grand_parent = parent.parent();
		var type = grand_parent.find('.table_attr_type select').val();
		var pks_count = grand_parent.parent().find(".table_attr_primary_key input:checked").length;
		
		grand_parent.find('.table_attr_null input').removeAttr("checked").prop("checked", false);
		grand_parent.find('.table_attr_unique input').attr("checked", "checked").prop("checked", true);
		
		//if there is only 1 primary key and type is numeric or blank, then add auto_increment text and check unsigned. Note that postgres will recognize the "auto_increment" text and remove it directly in the db-driver, so don't worry.
		if (pks_count == 1 && (!type || ($.isArray(column_numeric_types) && $.inArray(type, column_numeric_types) != -1))) { 
			//prepare unsigned
			var unsigned_input = grand_parent.find('.table_attr_unsigned input');
			
			if (!unsigned_input.is(":disabled"))
				unsigned_input.attr("checked", "checked").prop("checked", true);
			
			//prepare extra
			var extra = grand_parent.find('.table_attr_extra input');
			var text = extra.val();
			
			if (!text || ("" + text).toLowerCase().indexOf("auto_increment") == -1) {
				grand_parent.find('.table_attr_auto_increment input').attr("checked", "checked").prop("checked", true);
				
				extra.val(text + (text ? " " : "") + "auto_increment"); //TODO: Maybe in the future remove this bc it shouldn't be needed, since we already have the .table_attr_auto_increment field.
			}
		}
	}
}

function onBlurTableInputBox(elm) {
	elm = $(elm);
	var name = elm.val();
	
	if (name) {
		if (!isTableNameValid(name, true)) {
			name = normalizeTaskTableName(name);
			isTableNameValid(name);
		}
	}
}

function onBlurTableAttributeInputBox(elm) {
	elm = $(elm);
	var name = elm.val();
	
	if (name) {
		//normalizeTaskTableName
		name = normalizeTableName(name);
		
		//don't allow . for attribute name
		if (name.indexOf(".") != -1)
			name = name.replace(/\.+/, "");
		
		elm.val(name);
		
		//isTaskTableNameAdvisable
		isNameAdvisable(name);
	}
}

function isTableNameValid(name, do_not_alert) {
	//isTaskTableLabelValid
	var valid = false;
	
	if (name && name.length > 0) {
		var text = name;
		text = text.replace(/\n/g, ""); //if text has \n then the regex won't work. So we need to use .replace(/\n/g, "")
		
		//var m = text.match(/^[\p{L}\w\.]+$/giu); //\p{L} and /../u is to get parameters with accents and ç. Already includes the a-z. Cannot use this bc it does not work in IE.
		var m = text.match(/^[\w\.\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u024F\u1EBD\u1EBC]+$/gi); //'\w' means all words with '_' and 'u' means with accents and ç too.
		var valid = m && m[0];
		
		if (valid) {
			m = text.match(/[a-z]+/i); //checks if label has at least one letter
			valid = m && m[0];
		}
		
		if (valid)
			isNameAdvisable(text);
	}
	
	if (!valid && !do_not_alert)
		alert("Invalid label. Please choose a different label.\nOnly this letters are allowed: a-z, A-Z, 0-9, '_', '.' and you must have at least 1 character. Note that by adding the '.' char you are adding a schema to your table.");
	
	return valid;
}

function normalizeTableName(name) {
	//normalizeTaskTableName
	//name = name ? ("" + name).replace(/\n/g, "").replace(/[ \-]+/g, "_").match(/[\p{L}\w]+/giu).join("") : name; //\p{L} and /../u is to get parameters with accents and ç. Already includes the a-z. Cannot use this bc it does not work in IE.
	name = name ? ("" + name).replace(/\n/g, "").replace(/[ \-]+/g, "_").match(/[\w\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u024F\u1EBD\u1EBC]+/gi).join("") : name; //'\w' means all words with '_' and 'u' means with accents and ç too.

	return name;
}

function isNameAdvisable(name) {
	//isTaskTableNameAdvisable
	var normalized = ("" + name);
	
	if (typeof normalized.normalize == "function") //This doesn't work in IE11
		normalized = normalized.normalize("NFD");
		
	normalized = normalized.replace(/[\u0300-\u036f]/g, ""); //replaces all characters with accents with non accented characters including 'ç' to 'c'
	
	if (name != normalized)
		alert("Is NOT advisable to add names with accents and with non-standard characters. Please try to only use A-Z 0-9 and '_'.");
}

function onChangeSelectBox(elm) {
	elm = $(elm);
	var parent = elm.parent();
	
	if (parent.hasClass("table_attr_type")) {
		var value = elm.val();
		var tr = parent.parent();
		var length_field = tr.find('.table_attr_length input');
		var unsigned_field = tr.find(".table_attr_unsigned input");
		
		prepareAttributeSerialType(tr, value);
		value = elm.val(); //update the current value
		
		column_types_ignored_props = $.isPlainObject(column_types_ignored_props) ? column_types_ignored_props : {};
		var column_type_ignored_props = value && column_types_ignored_props.hasOwnProperty(value) && $.isArray(column_types_ignored_props[value]) ? column_types_ignored_props[value] : [];
		
		tr.find("input, select").removeAttr('disabled');
		
		if (!value) {
			column_type_ignored_props.push("length");
			column_type_ignored_props.push("unsigned");
			column_type_ignored_props.push("auto_increment");
		}
		
		for (var i = 0; i < column_type_ignored_props.length; i++) {
			var field_name = column_type_ignored_props[i];
			var td = tr.find(".table_attr_" + field_name);
			td.find("input, select").attr('disabled', 'disabled'); 
			td.find("input[type=text]").val('');
			td.find("input[type=checkbox]").removeAttr("checked").prop("checked", false); 
		}
		
		//disable default field if has_default is not selected
		tr.find(".table_attr_has_default input").each(function(idx, input) {
			input = $(input);
			
			if (!input.is(":checked"))
				input.parent().parent().find('.table_attr_default input').attr('disabled', 'disabled').val(''); 
		});
	}
}

function prepareAttributeSerialType(tr, type) {
	/*Do not uncomment this bc we want to be able to choose the serial types in the diagrams. Postgres uses the serial types.
	if (column_serial_types && column_serial_types.hasOwnProperty(type) && $.isPlainObject(column_serial_types[type])) {
		var props = column_serial_types[type];
		
		for (var k in props) {
			var v = props[k];
			var input = tr.find('.table_attr_' + k).find('input, select');
			
			if (input[0]) {
				var input_type = input.attr("type");
				
				if (input_type == "checkbox") {
					if (v)
						input.attr("checked", "checked").prop("checked", true); 
					else
						input.removeAttr("checked").prop("checked", false); 
				}
				else if (input.is("select"))
					input.val(v);
				else if (v) {
					var text = input.val();
					text = typeof text != "undefined" ? "" + text : "";
					var parts = ("" + v).split(" ");
					
					for (var i = 0; i < parts.length; i++)
						if (text.toLowerCase().indexOf(parts[i].toLowerCase()) == -1) 
							text += (text ? " " : "") + parts[i];
					
					input.val(text);
				}
			}
		}
	}*/
}

function onDeleteButton(elm) {
	if (confirm("This table will be deleted and all it's data will be lost forever!\nDo you wish to continue?") && confirm("Do you really wish to continue?\nThere is no rollback for this action..."))
		return true;
	return false;
}

function onExecuteButton(elm) {
	if (confirm("You are about to execute this sql on the DB.\nDo you really wish to continue?"))
		return true;
	return false;
}
function onBackButton(elm, step) {
	var edit_table = $(elm).parent().closest(".edit_table");
	edit_table.accordion("option", "active", step);
	
	if (step == 1 && !editors_inited) {
		edit_table.find(".table_sql_statements .sql_statement > textarea.editor").each(function(idx, textarea) {
			createSqlEditor(textarea);
		});
	}
	
	return false;
}
