$(function () {
	var els = $(".els");
	
	if (els[0] && !els.hasClass("ui-tabs"))
		els.tabs({active: 1});
	
	$(".settings_prop, .button_settings").each(function(idx, elm) {
		var selected_task_properties = $(elm).children(".selected_task_properties");
		var fields = selected_task_properties.children(".form_containers").children(".fields").children(".field");
		var icon = fields.children(".maximize")[0];
		
		FormFieldsUtilObj.minimizeField(icon);
		
		fields.children(".input_settings").children(".input_name").remove();
	});
	
	choosePropertyVariableFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForVariables,
	});
	choosePropertyVariableFromFileManagerTree.init("choose_property_variable_from_file_manager .class_prop_var");
});

function initObjectBlockSettings(class_name, save_func, save_func_name) {
	var block_settings = $("." + class_name).first();
	
	if (save_func_name) {
		$(".top_bar .save a").attr("onclick", save_func_name + "(this);");
	}
	
	createObjectItemCodeEditor( block_settings.find(".block_css textarea.css")[0], "css", save_func);
	createObjectItemCodeEditor( block_settings.find(".block_js textarea.js")[0], "javascript", save_func);
	
	var ptl = block_settings.find(".ptl");
	
	//in case exists others css and js blocks
	ptl.find("textarea.editor").each(function(idx, textarea) {
		createObjectItemCodeEditor(textarea, "php", save_func);
	});
	
	//Preparing UI - MyHtmlBeautify	
	MyHtmlBeautify.single_ptl_tags.push("block:field:");
	MyHtmlBeautify.single_ptl_tags.push("block:button:");
	
	//Preparing UI - LayoutUIEditor
	var layout_ui_editor_elm = ptl.children(".layout-ui-editor");
	
	if (layout_ui_editor_elm[0] && !layout_ui_editor_elm.data("LayoutUIEditor") && typeof LayoutUIEditor == "function") {
		var els_ui_creator_var_name = "ElsLayoutUIEditor_" + Math.abs(("" + class_name).hashCode()); //Be sure that the hasCode is positive with Math.abs
		var ElsLayoutUIEditor = new LayoutUIEditor();
		ElsLayoutUIEditor.options.ui_element = layout_ui_editor_elm;
		ElsLayoutUIEditor.options.on_choose_page_url_func = typeof onIncludePageUrlTaskChooseFile == "function" ? onIncludePageUrlTaskChooseFile : null;
		ElsLayoutUIEditor.options.on_choose_image_url_func = typeof onIncludeImageUrlTaskChooseFile == "function" ? onIncludeImageUrlTaskChooseFile : null;
		ElsLayoutUIEditor.options.template_source_editor_save_func = function() {
			var button = $(".top_bar .save a")[0];
			save_func(button);
		};
		ElsLayoutUIEditor.options.on_ready_func = function() {
			if (typeof LayoutUIEditorFormFieldUtil == "function") {
				var LayoutUIEditorFormFieldUtilObj = new LayoutUIEditorFormFieldUtil(ElsLayoutUIEditor);
				LayoutUIEditorFormFieldUtilObj.initFormFieldsSettings();
			}
		};
		window[els_ui_creator_var_name] = ElsLayoutUIEditor;
		ElsLayoutUIEditor.init(els_ui_creator_var_name);
		
		var editor = layout_ui_editor_elm.children(".template-source").data("editor");
		ptl.data("editor", editor);
	}
}

function loadEditSettingsBlockSettings(settings_elm, settings_values) {
	if (typeof loadObjectToObjectsBlockSettings == "function")
		loadObjectToObjectsBlockSettings(settings_elm.children(".edit_settings"), settings_values, "object_to_objects");
	
	loadObjectBlockSettings(settings_elm, settings_values, "edit_settings");
	loadTaskFormFieldsBlockSettings(settings_elm, settings_values, "edit_settings");
	loadTaskPTLFieldsBlockSettings(settings_elm, settings_values, "edit_settings");
	
	onElsTabChange( settings_elm.find(" > .edit_settings > .els > .els_tabs > li")[0] );
}

function loadListSettingsBlockSettings(settings_elm, settings_values) {
	loadObjectListBlockSettings(settings_elm, settings_values, "list_settings");
	loadTaskFormFieldsBlockSettings(settings_elm, settings_values, "list_settings");
	loadTaskPTLFieldsBlockSettings(settings_elm, settings_values, "list_settings");
	
	if (!settings_values || !settings_values.hasOwnProperty("fields")) {
		//Fixing error: replace [$idx] by [\\$idx], but only if there is no previous settings:
		settings_elm.find(".settings_prop").children(".selected_task_properties").children(".form_containers").children(".fields").children(".field").children(".input_settings").children(".input_name, .input_value").children("input").each(function(idx, elm) {
			elm = $(elm);
	
			if (elm.val().indexOf("[$idx]") != -1) {
				var v = elm.val().replace("[$idx]", "[\\$idx]");
				elm.val(v);
			}
		});
	}
	
	onElsTabChange( settings_elm.find(" > .list_settings > .els > .els_tabs > li")[0] );
}

function loadObjectListBlockSettings(settings_elm, settings_values, class_name) {
	loadObjectBlockSettings(settings_elm, settings_values, class_name);
	
	togglePanelFromCheckbox(settings_elm.children("." + class_name).children(".show_edit_button").children("input")[0], "edit_page_url");
}

function loadTaskFormFieldsBlockSettings(settings_elm, settings_values, class_name) {
	var block_settings = settings_elm.children("." + class_name);
	
	var fields = prepareBlockSettingsItemValue(settings_values["fields"]);
	if (fields) {
		var settings_props = block_settings.children(".settings_props");
		var previous_prop_elm = null;
		
		$.each(fields, function(field_id, field_data) {
			var prop_elm = settings_props.children(".prop_" + field_id);
			
			if (prop_elm[0]) {
				var field_elm = prop_elm.children(".selected_task_properties").children(".form_containers").children(".fields").children(".field");
				
				FormFieldsUtilObj.loadInputFieldData(field_elm, field_data["field"], {"remove" : 1, "sort" : 1});
			}
			else { //add field with default html to show that this field exists but is deprecated! This happens when we add extra attributes to some modules, like in the 'article', 'event' and 'user' modules, and then delete some extra attribute after we save a block.
				var field_name = field_data["field"] && field_data["field"].hasOwnProperty("label") && field_data["field"]["label"] && field_data["field"]["label"].hasOwnProperty("value") && field_data["field"]["label"]["value"] ? field_data["field"]["label"]["value"] : field_id;
				
				prop_elm = $('<div class="settings_prop prop_' + field_id + ' deprecated">'
					+ '<label class="settings_prop_label">Field "' + field_name  + '" - DEPRECATED - WILL BE DELETED ON NEXT SAVE!</label>'
					+ '<span class="settings_prop_icon icon delete" title="Delete" onclick="$(this).parent().remove()">Remove</span>'
					+ '<div class="clear"></div>'
				+ '</div>');
			}
			
			//Move prop_elm to the correspondent position;
			if (previous_prop_elm)
				settings_props[0].insertBefore(prop_elm[0], previous_prop_elm[0].nextSibling);//if nextSibling doesn't exist, the prop_elm will be inserted at the end, which is correct!
			else //insert to first position
				settings_props[0].insertBefore(prop_elm[0], settings_props[0].firstChild);
			
			previous_prop_elm = prop_elm;
		});
	}
	
	var buttons = prepareBlockSettingsItemValue(settings_values["buttons"]);
	if (buttons) {
		$.each(buttons, function(button_id, button_data) {
			var button_elm = block_settings.children(".button_settings_" + button_id).children(".selected_task_properties").children(".form_containers").children(".fields").children(".field");
			
			FormFieldsUtilObj.loadInputFieldData(button_elm, button_data["field"], {"remove" : 1, "sort" : 1});
		});
	}
}

function loadTaskPTLFieldsBlockSettings(settings_elm, settings_values, class_name) {
	var sv = prepareBlockSettingsItemValue(settings_values);
	//console.log(sv);
	
	var block_settings = settings_elm.children("." + class_name);
	
	//load main ptl settings
	if (sv && sv["ptl"]) {
		var els = block_settings.children(".els");
		els.tabs({active: 0});
		
		if (sv["ptl"]["code"]) {
			var code = prepareFieldValueIfValueTypeIsString(sv["ptl"]["code"]);//if string with hard-coded quotes, removes quotes
			 setPtlElementTemplateSourceEditorValue(els.children(".ptl"), code);
		}
	
		var external_vars = sv["ptl"]["external_vars"] ? sv["ptl"]["external_vars"] : null;
		loadPTLExternalVars(block_settings.find(".ptl > .ptl_external_vars"), external_vars);
	}
	
	//init other ptl external vars if not yet inited
	block_settings.find(".ptl > .ptl_external_vars").each(function (idx, elm) {
		elm = $(elm);
		
		if (elm.find(".items").length == 0)
			loadPTLExternalVars(elm, null);
	});
}

function loadObjectBlockSettings(settings_elm, settings_values, class_name) {
	//console.log(settings_values);
	var block_settings = settings_elm.children("." + class_name);
	
	loadBlockSettings(block_settings, settings_values);
	
	block_settings.find(".status_action .on_ok_action select").each(function(idx, elm) {
		toggleOKRedirect(elm);
	});
	
	block_settings.find(".status_action .on_error_action select").each(function(idx, elm) {
		toggleErrorRedirect(elm);
	});
	
	loadObjectBlockCssSettings(block_settings, settings_values);
	loadObjectBlockJsSettings(block_settings, settings_values);
}
	
function loadObjectBlockCssSettings(block_settings, settings_values) {
	if (settings_values.hasOwnProperty("css")) {
		var css = prepareBlockSettingsItemValue(settings_values["css"]);
		
		css = prepareFieldValueIfValueTypeIsString(css);//if string with hard-coded quotes, removes quotes
		
		var editor_parent = block_settings.find(".block_css");
		var editor = editor_parent.data("editor");
		if (editor)
			editor.setValue(css);
		else
			editor_parent.children("textarea.css").first().val(css);
	}
}
	
function loadObjectBlockJsSettings(block_settings, settings_values) {
	if (settings_values.hasOwnProperty("js")) {
		var js = prepareBlockSettingsItemValue(settings_values["js"]);
		
		js = prepareFieldValueIfValueTypeIsString(js);//if string with hard-coded quotes, removes quotes
		
		var editor_parent = block_settings.find(".block_js");
		var editor = editor_parent.data("editor");
		if (editor)
			editor.setValue(js);
		else
			editor_parent.children("textarea.js").first().val(js);
	}
}

function onElsTabChange(elm) {
	elm = $(elm);
	
	setTimeout(function() {
		var els = $(elm).parent().closest(".els");
		var is_list = els.attr("isList") == "1";
		var tab_selection = els.tabs("option", "active");
		
		switch (tab_selection) {
			case 0: //els_ptl
				if (is_list) {
					els.find(".table_class, .rows_class").hide();
					els.parent().find(" > .settings_props > .settings_prop > .selected_task_properties .field > .class").hide();
					//els.parent().children(".show_edit_button, .show_delete_button").children("input").attr("disabled", "disabled");
					els.parent().find(" > .pagination > .top_pagination > select[name='top_pagination_alignment']").hide();
					els.parent().find(" > .pagination > .bottom_pagination > select[name='bottom_pagination_alignment']").hide();
				}
				break;
			case 1: //els_elements
				if (is_list) {
					els.find(".table_class, .rows_class").show();
					els.parent().find(" > .settings_props > .settings_prop > .selected_task_properties .field > .class").show();
					//els.parent().children(".show_edit_button, .show_delete_button").children("input").removeAttr("disabled");
					els.parent().find(" > .pagination > .top_pagination > select[name='top_pagination_alignment']").show();
					els.parent().find(" > .pagination > .bottom_pagination > select[name='bottom_pagination_alignment']").show();
				}
				break;
		}
	}, 100);
}

function updatePTLFromFieldsSettings(elm) {
	var previous_selection = $(elm).parent().closest(".els").tabs("option", "active");
	var els = $(elm).parent().closest(".els");
	
	if (previous_selection == 1 && confirm("Do you wish to convert automatically the form settings into ptl code?")) {
		var block_settings = els.parent().closest(".settings");
	
		//Create ptl code
		prepareTaskFormFieldsForSaving(block_settings);//Prepare task form fields
		var settings = getBlockSettingsObjForSaving(block_settings);
		//console.log(settings);
		
		var external_vars = {};
		var code = getFieldSettingsPTLCode(settings, els, external_vars);
		//console.log(settings);
		
		if (typeof onUpdatePTLFromFieldsSettings == "function")
			code = onUpdatePTLFromFieldsSettings(elm, settings, code, external_vars);
		
		//Prepare block css and js inside of els - if exist - by default it doesn't exist!
		var block_js = els.find(".block_js");
		editor = block_js.data("editor");
		var js = editor ? editor.getValue() : block_js.children("textarea.js").first().val();
		if (js) {
			//checks if js contains some php code like: "function foo() {return \"" . translateProjectText($EVC, "bla ble") . "\";}"
			var is_php_code = js.charAt(0) == '"' && js.charAt(js.length - 1) == '"';
			if (is_php_code)
				js = "<script type=\\\"text/javascript\\\">\n" + js.substr(1, js.length - 2) + "\n</script>\n";
			else			
				js = "<script type=\"text/javascript\">\n" + js + "\n</script>\n";
			
			if (code.charAt(0) == '"' && code.charAt(js.length - 1) == '"')
				code = '"' + js + code.substr(1, code.length - 2) + "\n\"";
			else if (is_php_code)
				code = '"' + js + code.replace(/"/g, '\\"') + "\n\"";
			else
				code = js + code;
		}
		
		var block_css = els.find(".block_css");
		var editor = block_css.data("editor");
		var css = editor ? editor.getValue() : block_css.children("textarea.css").first().val();
		if (css) {
			//checks if css contains some php code like: ".class_xxx {content:\"" . translateProjectText($EVC, "bla ble") . "\";}"
			var is_php_code = css.charAt(0) == '"' && css.charAt(js.length - 1) == '"';
			if (is_php_code)
				css = "<style type=\\\"text/css\\\">\n" + css.substr(1, css.length - 2) + "\n</style>\n";
			else			
				css = "<style type=\"text/css\">\n" + css + "\n</style>\n";
			
			if (code.charAt(0) == '"' && code.charAt(js.length - 1) == '"')
				code = '"' + css + code.substr(1, code.length - 2) + "\n\"";
			else if (is_php_code)
				code = '"' + css + code.replace(/"/g, '\\"') + "\n\"";
			else
				code = css + code;
		}
		
		//Note: Do not add the codeBeautify to the css and js, because they may contain some php code and it will break the code.
		
		var ptl = els.children(".ptl");
		
		//show code
		setPtlElementTemplateSourceEditorValue(ptl, code, true);
		
		//Add External vars
		loadPTLExternalVars(ptl.children(".ptl_external_vars"), external_vars);
	}
}

function getFieldSettingsPTLCode(settings, els, external_vars) {
	var is_list = els.attr("isList") == "1";
	var code = "";
	var code_table_header = "";
	var code_table_body = "";
	
	if (settings["fields"])
		for (var k in settings["fields"])
			if (settings["show_" + k] == "1") {
				var field = settings["fields"][k];
				
				if (!is_list) { //prepare form field ptl code
					code += code ? "\n" : "";
				
					if (field.hasOwnProperty("ptl") && field["ptl"].hasOwnProperty("code") && field["code"]) {
						code += field["ptl"]["code"];
	
						if (field["ptl"]["external_vars"])
							for (var n in field["ptl"]["external_vars"])
								external_vars[n] = field["ptl"]["external_vars"][n];
					}
					else
						code += '<ptl:block:field:' + k + '/>';
				}
				else { //prepare table ptl code
					if (field["field"]) {
						var field_class = field["field"]["class"] ? PTLFieldsUtilObj.parseSettingsAttributeValue(field["field"]["class"]) : "";
						
						code_table_header += "\n\t\t\t<th class=\"list_column " + field_class + "\">";
						
						if (field["field"].hasOwnProperty("label") && field["field"]["label"].hasOwnProperty("ptl") && field["field"]["label"]["ptl"].hasOwnProperty("code") && field["field"]["label"]["code"]) {
							code_table_header += field["field"]["label"]["ptl"]["code"];
	
							if (field["field"]["label"]["ptl"]["external_vars"])
								for (var n in field["field"]["label"]["ptl"]["external_vars"])
									external_vars[n] = field["field"]["label"]["ptl"]["external_vars"][n];
						}
						else
							code_table_header += '<ptl:block:field:label:' + k + '/>';
					
						code_table_header += "</th>";
						code_table_body += "\n\t\t\t\t\t<td class=\"list_column " + field_class + "\">";
					
						if (field["field"].hasOwnProperty("input") && field["field"]["input"].hasOwnProperty("ptl") && field["field"]["input"]["ptl"].hasOwnProperty("code") && field["field"]["input"]["code"]) {
							code_table_body += field["field"]["input"]["ptl"]["code"];
	
							if (field["field"]["input"]["ptl"]["external_vars"])
								for (var n in field["field"]["input"]["ptl"]["external_vars"])
									external_vars[n] = field["field"]["input"]["ptl"]["external_vars"][n];
						}
						else
							code_table_body += '<ptl:block:field:input:' + k + '/>';
					
						code_table_body += "</td>";
					}
				}
			}
	
	if (!is_list && settings["buttons"]) { //preparing code for buttons
		//console.log(settings);
		
		code += "\n" + '<div class="buttons">';
		
		for (var k in settings["buttons"]) {
			var kion = k == "insert" ? "insertion" : (k == "delete" ? k.substr(0, k.length - 1) + "ion" : k);
			
			if (settings["allow_" + kion] == "1") { //prepare form field ptl code
				var field = settings["buttons"][k];
				
				if (field.hasOwnProperty("ptl") && field["ptl"].hasOwnProperty("code") && field["code"]) {
					code += "\n\t" + field["ptl"]["code"];

					if (field["ptl"]["external_vars"])
						for (var n in field["ptl"]["external_vars"])
							external_vars[n] = field["ptl"]["external_vars"][n];
				}
				else
					code += "\n\t" + '<ptl:block:button:' + k + '/>';
			}
		}
		
		code += "\n" + '</div>';
	}
	else if (is_list && code_table_header != "") { //code_table_body || code_table_header
		var table_class = PTLFieldsUtilObj.parseSettingsAttributeValue( els.find(".table_class .module_settings_property").val() );
		var rows_class = PTLFieldsUtilObj.parseSettingsAttributeValue( els.find(".rows_class .module_settings_property").val() );
		rows_class = rows_class ? ' class="' + rows_class + '"' : "";
		
		//is edit button visible
		if ( els.parent().find(" > .show_edit_button > input").is(":checked") ) {
			code_table_header += "\n\t\t\t" + '<th class="list_column edit_action"></th>';
			code_table_body += "\n\t\t\t\t\t" + '<td class="list_column edit_action"><ptl:block:button:edit/></td>';
		}
		
		//is delete button visible
		if ( els.parent().find(" > .show_delete_button > input").is(":checked") ) {
			code_table_header += "\n\t\t\t" + '<th class="list_column delete_action"></th>';
			code_table_body += "\n\t\t\t\t\t" + '<td class="list_column delete_action"><ptl:block:button:delete/></td>';
		}
		
		var top_pagination_alignment = els.parent().find(" > .pagination > .top_pagination > select[name='top_pagination_alignment']").val();
		var bottom_pagination_alignment = els.parent().find(" > .pagination > .bottom_pagination > select[name='bottom_pagination_alignment']").val();
		
		code = '' +
		"\n" + '<div class="top_pagination' + (top_pagination_alignment ? ' pagination_alignment_' + top_pagination_alignment : '') + '">' +
		"\n" + '	<ptl:block:top-pagination/>' +
		"\n" + '</div>' +
		"\n" + '<div class="list_container">' +
			"\n" + '<table class="list_table ' + table_class + '">' +
			"\n" + '	<thead>' +
			"\n" + '		<tr' + rows_class + '>' + 
			"" + '				' + code_table_header + 
			"\n" + '		</tr>' +
			"\n" + '	</thead>' +
			"\n" + '	<tbody>' +
			"\n" + '		<ptl:if is_array(\\$input_data)>' +
			"\n" + '			<ptl:foreach \\$input_data i item>' +
			"\n" + '				<tr' + rows_class + '>' + 
			"" + '						' + code_table_body + 
			"\n" + '				</tr>' +
			"\n" + '			</ptl:foreach>' +
			"\n" + '		</ptl:if>' +
			"\n" + '	</tbody>' +
			"\n" + '</table>' +
		"\n" + '</div>' +
		"\n" + '<div class="bottom_pagination' + (bottom_pagination_alignment ? ' pagination_alignment_' + bottom_pagination_alignment : '') + '">' +
		"\n" + '	<ptl:block:bottom-pagination/>' +
		"\n" + '</div>';
	}
	
	return code;
}

function loadPTLExternalVars(external_vars_elm, external_vars) {
	var evs = [];
	if (external_vars && $.isPlainObject(external_vars))
		for (var k in external_vars)
			evs.push({
				"key" : k,
				"key_type" : "string",
				"value" : external_vars[k],
				"value_type" : "variable",
			});
	
	if (!evs || $.isEmptyObject(evs))
		evs = {0: {key_type: "string", value_type: "string"}};
	
	ArrayTaskUtilObj.onLoadArrayItems(external_vars_elm, evs, "Add External Vars");
	
	$(external_vars_elm).find(".item_add").attr("onClick", "onAddNewPTLExternalVar(this)");
	
	$(external_vars_elm).find("ul li.item").each(function(idx, item) {
		item = $(item);
		item.find(".key").attr("placeHolder", "Var Name");
		item.find(".value").attr("placeHolder", "Var with $");
	});
}

function onAddNewPTLExternalVar(elm) {
	var item = ArrayTaskUtilObj.addItem(elm);
	item.find(".key").attr("placeHolder", "Var Name");
	item.find(".value").attr("placeHolder", "Var with $");
}

function createObjectItemCodeEditor(textarea, type, save_func) {
	if (textarea) {
		var parent = $(textarea).parent();
	
		ace.require("ace/ext/language_tools");
		var editor = ace.edit(textarea);
		editor.setTheme("ace/theme/chrome");
		editor.session.setMode("ace/mode/" + type);
		//editor.setAutoScrollEditorIntoView(false);
		editor.setOption("minLines", 10);
		editor.setOptions({
			enableBasicAutocompletion: true,
			enableSnippets: true,
			enableLiveAutocompletion: false,
		});
		editor.setOption("wrap", true);
	
		if (typeof save_func == "function") {
			editor.commands.addCommand({
				name: 'saveFile',
				bindKey: {
					win: 'Ctrl-S',
					mac: 'Command-S',
					sender: 'editor|cli'
				},
				exec: function(env, args, request) {
					var button = $(".top_bar .save a")[0];
					save_func(button);
				},
			});
		}
	
		parent.data("editor", editor);
		
		parent.find("textarea.ace_text-input").removeClass("ace_text-input"); //fixing problem with scroll up, where when focused or pressed key inside editor the page scrolls to top.
	
		return editor;
	}
	
	return null;
}

function setPtlElementTemplateSourceEditorValue(ptl, value, force) {
	var PtlLayoutUIEditor = ptl.children(".layout-ui-editor").data("LayoutUIEditor");
	
	if (PtlLayoutUIEditor) {
		if (force) {
			//converts visual into code if visual tab is selected
			var is_template_layout_tab_show = PtlLayoutUIEditor.isTemplateLayoutShown();
			
			if (is_template_layout_tab_show)
				PtlLayoutUIEditor.convertTemplateLayoutToSource();
			
			//PtlLayoutUIEditor.forceTemplateSourceConversionAutomatically(); //Be sure that the template source is selected
		}
		
		PtlLayoutUIEditor.setTemplateSourceEditorValue(value);
	}
	else {
		var editor = ptl.data("editor");
		
		if (editor) {
			editor.setValue(value, -1);
			editor.focus();
		}
		else 
			ptl.find(" > .layout-ui-editor > .template-source > textarea").val(value);
	}
}

function getPtlElementTemplateSourceEditorValue(ptl, force) {
	var PtlLayoutUIEditor = ptl.children(".layout-ui-editor").data("LayoutUIEditor");
	
	if (PtlLayoutUIEditor) {
		if (force) {
			//converts visual into code if visual tab is selected
			var is_template_layout_tab_show = PtlLayoutUIEditor.isTemplateLayoutShown();
			
			if (is_template_layout_tab_show)
				PtlLayoutUIEditor.convertTemplateLayoutToSource();
			
			//PtlLayoutUIEditor.forceTemplateSourceConversionAutomatically(); //Be sure that the template source is selected
		}
		
		return PtlLayoutUIEditor.getTemplateSourceEditorValue();
	}
	
	var editor = ptl.data("editor");
	return editor ? editor.getValue() : ptl.find(" > .layout-ui-editor > .template-source > textarea").val();
}

function importTemplatePTLCode(elm, module) {
	var popup = $(".module_settings .settings .import_template_ptl_code_popup");
	
	if (!popup[0]) {
		var html = '<div class="myfancypopup import_template_ptl_code_popup">'
			+ '	<div class="template">'
			+ '		<label>Installed Templates with PTL:</label>'
			+ '		<select>'
			+ '			<option value="">Loading templates...</option>'
			+ '		</select>'
			+ '	</div>'
			+ '	<div class="button">'
			+ '		<input type="button" value="IMPORT" onClick="MyFancyPopup.settings.updateFunction(this)" />'
			+ '		<span class="info">Loading ptl code...</span>'
			+ '	</div>'
			+ '</div>';
		
		popup = $(html);
		$(".module_settings .settings").append(popup);
	}
	
	if (popup.attr("inited") != 1) 
		$.ajax({
			url: call_module_file_prefix_url.replace("#module_file_path#", "get_templates_action") + "&action=available_templates&module=" + module,
			dataType: "json",
			success: function(data) {
				if (data) {
					popup.attr("inited", 1);
					
					var options = '';
					$.each(data, function(idx, template) {
						options += '<option>' + template + '</option>';
					});
					
					popup.find(".template select").html(options);
				}
				else
					StatusMessageHandler.showError("No templates installed.");
			},
			error: function() {
				StatusMessageHandler.showError("Error trying to load available templates.\nPlease try again...");
			},
		});
	
	var ptl = $(elm).parent().closest(".els").children(".ptl");
	popup.find(".button input").show();
	popup.find(".button .info").hide();
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		
		updateFunction: function(button_elm) {
			button_elm = $(button_elm);
			button_elm.hide();
			button_elm.parent().children(".info").show();
			
			var template = popup.find(".template select").val();
			
			if (!template) 
				StatusMessageHandler.showError("Please choose a valid template");
			else
				$.ajax({
					url: call_module_file_prefix_url.replace("#module_file_path#", "get_templates_action") + "&action=template_ptl&module=" + module + "&template=" + template,
					dataType: "text",
					success: function(code) {
						if (code) {
							MyFancyPopup.hidePopup();
							console.log(code);
							
							setPtlElementTemplateSourceEditorValue(ptl, code);
						}
						else
							StatusMessageHandler.showError("No ptl code for this template");
					},
					error: function() {
						StatusMessageHandler.showError("Error trying to load available templates.\nPlease try again...");
					},
				});
		},
	});
	
	MyFancyPopup.showPopup();
}

function togglePanelFromCheckbox(checkbox, panel_class_name) {
	if ($(checkbox).is(":checked")) 
		$(checkbox).parent().parent().children("." + panel_class_name).show();
	else 
		$(checkbox).parent().parent().children("." + panel_class_name).hide();
}

function toggleField(elm) {
	elm = $(elm);
	var main_div = elm.parent();
	
	if (elm.hasClass("minimize")) {
		main_div.children(".selected_task_properties").hide();
		elm.removeClass("minimize").addClass("maximize");
	}
	else {
		main_div.children(".selected_task_properties").show();
		elm.removeClass("maximize").addClass("minimize");
	}
}

function moveUpField(elm) {
	var field = $(elm).parent();
	
	if (field.prev()[0])
		field.parent()[0].insertBefore(field[0], field.prev()[0]);
}

function moveDownField(elm) {
	var field = $(elm).parent();
	
	if (field.next()[0])
		field.parent()[0].insertBefore(field.next()[0], field[0]);
}

function toggleStatusAction(elm, action) {
	elm = $(elm);
	var main_div = elm.parent().parent();
	
	if (elm.hasClass("minimize")) {
		main_div.children(".status_action_" + action + ", .button_settings_" + action).hide();
		elm.removeClass("minimize").addClass("maximize");
	}
	else {
		main_div.children(".status_action_" + action + ", .button_settings_" + action).show();
		elm.removeClass("maximize").addClass("minimize");
	}
}

function toggleOKRedirect(elm) {
	toggleRedirect(elm, 'on_ok_redirect_url', 'on_ok_redirect_ttl');
}

function toggleErrorRedirect(elm) {
	toggleRedirect(elm, 'on_error_redirect_url', 'on_error_redirect_ttl');
}

function toggleRedirect(elm, redirect_url_class, redirect_url_ttl) {
	elm = $(elm);
	var value = elm.val();
	var main_div = elm.parent().parent();
	
	if (value == "show_message_and_redirect" || value == "alert_message_and_redirect") {
		main_div.children("." + redirect_url_class).show();
		
		if (value == "show_message_and_redirect")
			main_div.children("." + redirect_url_ttl).show();
		else
			main_div.children("." + redirect_url_ttl).hide();
	}
	else
		main_div.children("." + redirect_url_class + ", ." + redirect_url_ttl).hide();
}

function saveEditSettings(button) {
	saveObjectBlock(button, "edit_settings");
}

function saveListSettings(button) {
	saveObjectListBlock(button, "list_settings");
}

function saveObjectListBlock(button, class_name) {
	var block_settings = $(".block_obj > .module_settings > .settings > ." + class_name);
	
	if (block_settings.children(".show_edit_button").children("input").is(":checked")) {
		var edit_page_url = block_settings.children(".edit_page_url").children("input").val();
		
		if (edit_page_url && edit_page_url.replace(/ /g, "") == "") {
			StatusMessageHandler.showError("Edit Page Url cannot be undefined.\nPlease try again...");
			return false;
		}
	}
	
	saveObjectBlock(button, class_name);
}

function prepareTaskFormFieldsForSaving(block_settings) {
	//PREPARE TASK FORM FIELDS
	var selected_tasks_properties = block_settings.find(".selected_task_properties");
	
	for (var i = 0; i < selected_tasks_properties.length; i++) {
		var selected_task_properties = $(selected_tasks_properties[i]);
		var field = selected_task_properties.find(".field");
		
		var field_attrs_to_discard = ["label_extra_attributes", "input_extra_attributes", "input_available_values", "input_previous_group_addons", "input_next_group_addons", "input_options", "help_extra_attributes"];
		
		//prepare extra_attributes/available_values/input_options according with the type: variable or tables
		for (var j = 0; j < field_attrs_to_discard.length; j++) {
			var field_attr = field.children(".label_settings, .input_settings").children("." + field_attrs_to_discard[j]);
			var input = field_attr.children("input.task_property_field");
			var table = field_attr.children("table");
			var input_type = field_attr.children("select").val();
			
			if (input_type == "variable" || input_type == "string") {
				table.find(".task_property_field").attr("dont_save", 1).removeClass("module_settings_property");
				input.removeAttr("dont_save");
				
				if (input_type == "variable") {
					var v = input.val();
					if (v && v.substr(0, 1) != '$')
						input.val('$' + v);
				}
			}
			else {
				input.attr("dont_save", 1).removeClass("module_settings_property");
				table.find(".task_property_field").removeAttr("dont_save");
			}
		}
		
		//add module_settings_property class to all elms with task_property_field but that don't have the dont_save attr active.
		var inputs = field.find(".task_property_field");
		
		for (var j = 0; j < inputs.length; j++) {
			var input = $(inputs[j]);
			
			if (!input.hasClass("module_settings_property") && input.attr("dont_save") != 1)
				input.addClass("module_settings_property");
		}
	}
}

function preparePTLSettingsForSaving(elm) {	
	var ptl = elm.find(".ptl");
	
	var code = getPtlElementTemplateSourceEditorValue(ptl, true);
	var ptl_code = ptl.children("textarea.ptl_code");
	ptl_code.val(code);
	
	if (!ptl_code.hasClass("module_settings_property"))
		ptl_code.addClass("module_settings_property");
	
	//Prepare ptl external vars
	var ptl_external_vars = ptl.children(".ptl_external_vars");
	var ev_prefix = ptl_external_vars.attr("array_parent_name");
	$.each(ptl_external_vars.find(".item"), function (idx, item) {
		item = $(item);
		var key = item.children(".key").val();
		var input = item.children(".value");

		if (key && input.val()) {
			input.attr("name", ev_prefix + "[" + key + "]");
		
			if (!input.hasClass("module_settings_property"))
				input.addClass("module_settings_property");
		}
	});
}

function saveObjectBlock(button, class_name) {
	var block_settings = $(".block_obj > .module_settings > .settings > ." + class_name);
	//console.log(block_settings);
	
	//DELETE DEPRECATED TASK FORM FIELDS
	block_settings.find(" > .settings_props > .settings_prop.deprecated").remove();
	
	//PREPARE TASK FORM FIELDS
	prepareTaskFormFieldsForSaving(block_settings);
	
	//PREPARE ELS
	var els = block_settings.children(".els");
	if (els[0]) {
		var selected_tab = els.tabs("option", "active");
		
		switch (selected_tab) {
			case 0:
				preparePTLSettingsForSaving(els);
				
				//removes class .module_settings_property from fields inside of .els_elements
				els.find(" > .els_elements").find(".block_css, .block_js").find(".module_settings_property").removeClass("module_settings_property").addClass("removed_module_settings_property");
				break;
			case 1:
				els.find(".ptl .module_settings_property").removeClass("module_settings_property");
				els.find(" > .els_elements .removed_module_settings_property").addClass("module_settings_property").removeClass("removed_module_settings_property");
				break;
		}
	}
	
	//Prepare block_css and block_js fields that are inside of .els and outside, in case doesn't exist the .els container. All the block_css and block_js that have an textarea.module_settings_property
	$.each( block_settings.find(".block_css, .block_js"), function(idx, elm) {
		elm = $(elm);
		var editor = elm.data("editor");
		var str = editor ? editor.getValue() : elm.children("textarea.css, textarea.js").first().val();
		elm.children("textarea.module_settings_property").first().val(str);
	});
	
	if (typeof onSaveObjectBlock == "function")
		onSaveObjectBlock(block_settings, button, class_name);
	
	//SAVE BLOCK
	return saveBlock();
}
