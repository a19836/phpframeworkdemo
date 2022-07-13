var saved_settings_id = null;

$(function () {
	//unbind beforeunload that was inited by the edit_query.js and edit_simple_block.js
	$(window).unbind('beforeunload').bind('beforeunload', function () {
		if (isModuleFormSettingsChanged()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	//prepare top_bar
	$("#ui > .taskflowchart").addClass("with_top_bar_menu fixed_properties").children(".workflow_menu").addClass("top_bar_menu").find("li.save, li.auto_save_activation, li.auto_convert_activation, li.tasks_flow_full_screen").remove();
	
	//change the toggle Auto save handler bc the edit_query task
	if (typeof onToggleQueryAutoSave == "function")
		$(".top_bar li.auto_save_activation input").attr("onChange", "").unbind("change").bind("change", function() {
			toggleAutoSaveCheckbox(this, onToggleQueryAutoSave);
		});
	
	//init trees
	//only add popups if not exist yet - must be before the code that inits the trees
	if (choose_from_file_manager_popup_html) {
		var items = $(choose_from_file_manager_popup_html);
		
		$.each(items, function (idx, item) {
			if (item.nodeType == Node.ELEMENT_NODE) {
				var id = item.getAttribute("id");
				
				if (!id || $("#" + id).length == 0)
					$(document.body).append(item);
			}
			else
				$(document.body).append(item);
		});
	}
	
	/* This is already executed in the common/settings.js, so we cannot executed again.
	choosePropertyVariableFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForVariables,
	});
	choosePropertyVariableFromFileManagerTree.init("choose_property_variable_from_file_manager .class_prop_var");*/
	
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
	
	chooseHibernateObjectMethodFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeParametersAndResultMapsFromTree,
	});
	chooseHibernateObjectMethodFromFileManagerTree.init("choose_hibernate_object_method_from_file_manager");
	
	chooseBlockFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotBlocksFromTree,
	});
	chooseBlockFromFileManagerTree.init("choose_block_from_file_manager");
	
	//init ui
	var module_form_settings = $(".module_form_settings");
	var block_obj = module_form_settings.parent().closest(".block_obj");
	$(".top_bar .save a").attr("onclick", "saveModuleFormSettings(this);");
	
	if (workflow_module_installed_and_enabled)
		block_obj.find(" > .module_data > .module_description").append('<a class="convert_to_module_workflow" onclick="convertModuleFormSettingsToModuleWorkflowSettings(this)">Convert this block into code workflow...</a>');
	
	createObjectItemCodeEditor( module_form_settings.find(".block_css textarea.css")[0], "css", saveModuleFormSettings);
	createObjectItemCodeEditor( module_form_settings.find(".block_js textarea.js")[0], "javascript", saveModuleFormSettings);
	
	var module_form_contents = module_form_settings.children(".module_form_contents");
	
	//remove database options bc there are no detected db_drivers
	if (typeof db_brokers_drivers_tables_attributes != "undefined" && $.isEmptyObject(db_brokers_drivers_tables_attributes)) 
		initSLAGroupItemsActionType( module_form_contents.find(".sla_groups_flow > .sla_groups") );
	
	module_form_contents.tabs();
	
	//change some handlers
	ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onSLAProgrammingTaskChooseCreatedVariable;
	
	//load task flow and code editor
	onLoadTaskFlowChartAndCodeEditor();
	
	//set saved settings id
	saved_settings_id = getModuleFormSettingsId();
	
	MyFancyPopup.hidePopup();
});

/* LOAD FUNCTIONS */

function loadFormBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var module_form_settings = settings_elm.children(".module_form_settings");
	var add_group_icon = module_form_settings.find(".sla_groups_flow > nav > .add_form_group")[0];
	
	var tasks_values = convertFormSettingsToTasksValues(settings_values);
	//console.log(settings_values);
	//console.log(tasks_values);
	
	//setting the save_func in the CreateFormTaskPropertyObj
	if (CreateFormTaskPropertyObj) 
		CreateFormTaskPropertyObj.editor_save_func = function () {
			$(".top_bar .save a").first().trigger("click");
		};
	
	if (tasks_values) {
		//load old form settings - Do not remove this code until all the old forms have the new settings
		if (tasks_values.hasOwnProperty("form_settings_data_type"))
			loadFormBlockOldSettings(module_form_settings, add_group_icon, tasks_values);
		else //load new form settings
			loadFormBlockNewSettings(module_form_settings, add_group_icon, tasks_values);
	}
	else { 
		//set default group
		/*var new_group = addNewSLAGroup(add_group_icon);
		var select = new_group.find(" > .sla_group_header .action_type");
		onChangeSLAInputType( select[0] );*/
		
		openFormWizard();
	}
	
	MyFancyPopup.hidePopup();
}

function convertFormSettingsToTasksValues(settings_values) {
	var tasks_values = {};
	
	if (!$.isEmptyObject(settings_values)) {
		//Preparing new settings
		tasks_values = convertSettingsToTasksValues(settings_values);
		
		if ($.isEmptyObject(tasks_values) && (settings_values["css"] || settings_values["js"]))
			tasks_values = convertBlockSettingsValuesIntoBasicArray(settings_values);
		
		if ($.type(tasks_values["css"]) == "string")
			tasks_values["css"] = prepareFieldValueIfValueTypeIsString(tasks_values["css"]); //remove extra quotes that were added by the convertBlockSettingsValuesIntoBasicArray function
		
		if ($.type(tasks_values["js"]) == "string")
			tasks_values["js"] = prepareFieldValueIfValueTypeIsString(tasks_values["js"]); //remove extra quotes that were added by the convertBlockSettingsValuesIntoBasicArray function
		
		//Preparing old settings
		if (settings_values[0] || settings_values[1]) {
			if (settings_values[0]) {
				if (settings_values[0]["value"]) {
					tasks_values["form_settings_data_type"] = settings_values[0]["value_type"];
					tasks_values["form_settings_data"] = settings_values[0]["value"];
				}
				else {
					tasks_values["form_settings_data_type"] = "array";
					tasks_values["form_settings_data"] = convertObjectIntoArray(settings_values[0]);
				}
			}
			
			if (settings_values[1]) {
				if (settings_values[1]["value"]) {
					tasks_values["form_input_data_type"] = settings_values[1]["value_type"];
					tasks_values["form_input_data"] = settings_values[1]["value"];
					
					if (tasks_values["form_input_data_type"] == "method" || tasks_values["form_input_data_type"] == "function") {
						$.ajax({
							type : "post",
							url : get_input_data_method_settings_url,
							data : {"method" : tasks_values["form_input_data"]},
							dataType : "json",
							success : function(data, textStatus, jqXHR) {
								if (data && data["brokers_layer_type"] && data["brokers"] && $.isPlainObject(data["brokers"])) {
									tasks_values["broker"] = data["brokers"];
									tasks_values["brokers_layer_type"] = data["brokers_layer_type"];
									tasks_values["form_input_data_type"] = "brokers";
									tasks_values["form_input_data"] = null;
								}
								else
									StatusMessageHandler.showError("Error trying to load new settings.\nPlease try again...");
							},
							error : function() { 
								StatusMessageHandler.showError("Error trying to load new settings.\nPlease try again...");
							},
							async: false,
						});
					}
				}
				else {
					tasks_values["form_input_data_type"] = "array";
					tasks_values["form_input_data"] = convertObjectIntoArray(settings_values[1]);
				}
			}
			
			tasks_values = convertBlockSettingsValuesKeysToLowerCase(tasks_values);
		}
	}
	
	return tasks_values;
}

function loadFormBlockNewSettings(module_form_settings, add_group_icon, tasks_values) {
	if (tasks_values.hasOwnProperty("actions"))
		loadSLASettingsActions(add_group_icon, tasks_values["actions"], false);
	
	if (tasks_values.hasOwnProperty("css")) {
		var block_css = module_form_settings.find(".block_css");
		var editor = block_css.data("editor"); //In case this function be called for the second time, through the form wizard...
		
		if (editor)
			editor.setValue(tasks_values["css"]);
		else
			block_css.children("textarea.css").first().val(tasks_values["css"]);
	}
	
	if (tasks_values.hasOwnProperty("js")) {
		var block_js = module_form_settings.find(".block_js");
		var editor = block_js.data("editor"); //In case this function be called for the second time, through the form wizard...
		
		if (editor)
			editor.setValue(tasks_values["js"]);
		else
			block_js.children("textarea.js").first().val(tasks_values["js"]);
	}
}

function loadFormBlockOldSettings(module_form_settings, add_group_icon, tasks_values) {
	var has_form_input = tasks_values.hasOwnProperty("form_input_data_type");
		
	var group_items = module_form_settings.find(".sla_groups_flow > .sla_groups > .sla_group_item:not(.sla_group_default)");
	
	//creating group_item input data
	if (has_form_input) {
		var group_item_input_data = $(group_items[1]);
			
		if (!group_item_input_data || !group_item_input_data[0])
			group_item_input_data = addNewSLAGroup(add_group_icon);
		
		group_item_input_data.find(" > .sla_group_header > .result_var_name").val("result_items").removeClass("result_var_name_output");
	}
	
	//creating group_item html
	var group_item_html = group_items.first();
	
	if (!group_item_html || !group_item_html[0])
		group_item_html = addNewSLAGroup(add_group_icon);
	
	if (js_load_functions) {
		//preparing form html
		//prepare input_data in form_settings_data
		if (tasks_values["form_settings_data"])
			tasks_values["form_settings_data"] = convertFormSettingsDataWithNewInputData(tasks_values["form_settings_data"], "result_items");
		
		var html_values = tasks_values ? {"createform" : {"form_settings_data_type": tasks_values["form_settings_data_type"], "form_settings_data": tasks_values["form_settings_data"]}} : {};
		
		initSLAGroupItemTasks(group_item_html, html_values);
		
		//preparing form input
		if (has_form_input) {
			//preparing broker input
			var broker_values = {};
			if (tasks_values && tasks_values["form_input_data_type"] == "brokers")
				broker_values[ tasks_values["brokers_layer_type"] ] = tasks_values["broker"];
			
			initSLAGroupItemTasks(group_item_input_data, broker_values);
			
			//preparing other inputs (variable, code, string and array)
			if (tasks_values["form_input_data_type"] != "brokers") {
				var form_input_data = tasks_values["form_input_data"];
				
				if (tasks_values["form_input_data_type"] == "array") //is array
					ArrayTaskUtilObj.onLoadArrayItems( group_item_input_data.find(' > .sla_group_body > .array_action_body'), form_input_data, "");
				else {
					form_input_data = form_input_data ? "" + form_input_data : "";
					
					if (tasks_values["form_input_data_type"] == "variable" && form_input_data.trim().substr(0, 1) == '$') //is variable
						group_item_input_data.find(' > .sla_group_body > .variable_action_body > input').val( form_input_data.trim().substr(1) );
					else if (tasks_values["form_input_data_type"] == "") //is code
						group_item_input_data.find(' > .sla_group_body > .code_action_body > textarea').val(form_input_data);
					else //is string
						group_item_input_data.find(' > .sla_group_body > .string_action_body > input').val(form_input_data);
				}
			}
		}
	}
	
	var select = group_item_html.find(" > .sla_group_header .action_type");
	select.val("html");
	onChangeSLAInputType( select[0] );
	
	if (has_form_input) {
		var default_broker_action_type = tasks_values["form_input_data_type"] == "brokers" ? tasks_values["brokers_layer_type"] : (tasks_values["form_input_data_type"] ? tasks_values["form_input_data_type"] : "code");
		var select = group_item_input_data.find(" > .sla_group_header .action_type");
		
		select.val(default_broker_action_type);
		onChangeSLAInputType( select[0] );
	}
}

function convertFormSettingsDataWithNewInputData(data, input_data_var_name) {
	if (data)
		for (var k in data) {
			var v = data[k];
			
			if ($.isPlainObject(v) && v.hasOwnProperty("key") && (v["key"] == "table" || v["key"] == "tree")) {
				if (v["items"])
					for (var i in v["items"]) 
						if (v["items"][i]["key"] == "default_input_data") {
							if (v["items"][i]["value_type"] == "string") {
								var value = v["items"][i]["value"];
								
								if (!value)
									data[k]["items"][i]["value"] = "#" + input_data_var_name + "#";
								else
									alert("Couldn't transform old form settings to new settings. You should edit this block with the advanced view.");
							}
							else
								alert("Couldn't transform old form settings to new settings. You should edit this block with the advanced view.");
							
							break;
						}
			}
			else if ($.isPlainObject(v) && v.hasOwnProperty("value") && $.type(v["value"]) == "string" && v["value"].indexOf("#") != -1) {
				var value = v["value"];
				var new_value = value;
				var offset = 0;
				var length = value.length;
				var reg = /#([a-zA-Z0-9_"' \-\[\]\.\\\$]+)#/g //must be outisde of the do-while, otherwise it will give an infinitive loop
				
				do {
					var matches = reg.exec(value);
					
					if (matches && matches.length > 0 && matches[1]) {
						var m = matches[1];
						var replacement = "";
						
						if (m.indexOf("[") != -1) { //if value == #[0]name# or #[$idx - 1][name]#, returns $input_data[0]["name"] or $input_data[$idx - 1]["name"]
							var pos = m.indexOf("[");
							if (pos > 0)
								replacement = input_data_var_name + "[" + m.substr(0, pos) + "]" + m.substr(pos);
							else
								replacement = input_data_var_name + m;
						}
						else if (m != "$input" && m != "idx" && m != "$idx" && m != "\\$idx") //#idx#, returns $idx
							replacement = input_data_var_name + '[' + m + ']';
						
						new_value = value.replace(matches[0], "#" + replacement + "#");
						
						//console.log(matches[0]);
						//console.log("#" + replacement + "#");
						offset = matches.index + matches[0].length;
					}
				}
				while (matches && matches.length > 0 && offset < length);
				
				data[k]["value"] = new_value;
			}
			else if ($.isPlainObject(v) && v.hasOwnProperty("items"))
				data[k]["items"] = convertFormSettingsDataWithNewInputData(v["items"], input_data_var_name);
			else if ($.isArray(v))
				data[k] = convertFormSettingsDataWithNewInputData(v, input_data_var_name);
		}
	
	return data;
}

/* FORM WIZARD FUNCTIONS */

function openFormWizard() {
	var form_wizard = $(".module_form_settings .form_wizard");
	
	MyFancyPopup.init({
		elementToShow: form_wizard,
		parentElement: document,
		onOpen: function() {
			if (!form_wizard[0].hasAttribute("data_is_inited")) {
				form_wizard.attr("data_is_inited", 1);
				
				//preparing db brokers, drivers and tables
				var section = form_wizard.find(".steps .table_selection");
				
				var options = '';
				for (var broker in db_brokers_drivers_tables_attributes)
					options += "<option" + (default_dal_broker == broker ? " selected" : "") + ">" + broker + "</option>";
				
				var select = section.children(".dal_broker");
				select.html(options);
				onChangeDALBrokerFormWizard(select[0]);
				
				openFormWizardPanel(0);
				
				toggleFormWizardPanelType( form_wizard.find(".steps > .panel_type_selection > select")[0] );
			}
		},
	});
	
	MyFancyPopup.showPopup();
}

function openFormWizardPanel(idx) {
	if ($.isNumeric(idx) && idx >= 0) {
		var form_wizard = $(".module_form_settings .form_wizard");
		var steps = form_wizard.find(" > .steps > .step");
		steps.hide();
		var step = $( steps.get(idx) );
		step.show();
		
		if (step.hasClass("table_selection")) {
			var panel_type = step.parent().find(" > .panel_type_selection > .panel_type").val();
			
			if (panel_type == "single_form")
				step.find(" > .table_options > .conditions").hide();
			else
				step.find(" > .table_options > .conditions").show();
		}
		
		if (idx >= steps.length - 1)
			form_wizard.find(" > .buttons > .next").hide();
		else
			form_wizard.find(" > .buttons > .next").show();
		
		if (idx > 0)
			form_wizard.find(" > .buttons > .previous").show();
		else
			form_wizard.find(" > .buttons > .previous").hide();
	}
}

function previousFormWizard(elm) {
	var steps = $(".module_form_settings .form_wizard > .steps > .step");
	var idx = 0;
	
	$.each(steps, function(i, step) {
		if ($(step).css("display") != "none") {
			idx = i;
			return false;
		}
	});
	
	idx--;
	openFormWizardPanel(idx);
}

function nextFormWizard(elm) {
	var steps = $(".module_form_settings .form_wizard > .steps > .step");
	var idx = 0;
	
	$.each(steps, function(i, step) {
		if ($(step).css("display") != "none") {
			idx = i;
			return false;
		}
	});
	
	idx++;
	openFormWizardPanel(idx);
}

function createFormWizard(elm, replace_prev_html) {
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var settings = getFormWizardSettings();
	//console.log(settings);
	
	if (!settings["table"])
		alert("Error: You must select a table!");
	else if (!settings["attributes"] || settings["attributes"].length == 0)
		alert("Error: You must select at least 1 attribute of the selected table!");
	else {
		var buttons = $(elm).parent().children("input");
		buttons.attr("disabled", "disabled");
		
		var url = get_form_wizard_settings_url + "&dal_broker=" + settings["dal_broker"] + "&db_driver=" + settings["db_driver"] + "&type=" + settings["type"];
		
		var url_settings = {};
		url_settings[ settings["table"] ] = {
			"panel_type": settings["panel_type"],
			"form_type": settings["form_type"],
			"attributes": settings["attributes"],
			"conditions": settings["conditions"],
			"actions": settings["actions"],
		};
		
		$.ajax({
			type : "post",
			url : url,
			data : {"settings": url_settings},
			dataType : "json",
			success : function(data, textStatus, jqXHR) {
				if (data) {
					//console.log(data);
					var module_form_settings = $(".module_form_settings");
					var add_group_icon = module_form_settings.find(".sla_groups_flow > nav > .add_form_group")[0];
					
					if (replace_prev_html)
						module_form_settings.find(".sla_groups_flow > .sla_groups > .sla_group_item").not(".sla_group_default").remove();
					
					//indent ptl code in actions
					if (MyHtmlBeautify && typeof MyHtmlBeautify.beautify == "function" && data["actions"] && $.isArray(data["actions"]))
						for (var i = 0; i < data["actions"].length; i++)
							if ($.isPlainObject(data["actions"][i]) 
							  && data["actions"][i]["action_type"] == "html" 
							  && $.isPlainObject(data["actions"][i]["action_value"]) 
							  && data["actions"][i]["action_value"]["form_settings_data_type"] == "array" 
							  && $.isArray(data["actions"][i]["action_value"]["form_settings_data"]) 
							  && $.isPlainObject(data["actions"][i]["action_value"]["form_settings_data"][0]) 
							  && data["actions"][i]["action_value"]["form_settings_data"][0]["key"] == "ptl"
							  && $.isArray(data["actions"][i]["action_value"]["form_settings_data"][0]["items"])
							) {
								for (var j = 0; j < data["actions"][i]["action_value"]["form_settings_data"][0]["items"].length; j++)
									if ($.isPlainObject(data["actions"][i]["action_value"]["form_settings_data"][0]["items"][j]) 
									  && data["actions"][i]["action_value"]["form_settings_data"][0]["items"][j]["key"] == "code" 
									  && data["actions"][i]["action_value"]["form_settings_data"][0]["items"][j]["value_type"] == "string"
									) {
										data["actions"][i]["action_value"]["form_settings_data"][0]["items"][j]["value"] = MyHtmlBeautify.beautify(data["actions"][i]["action_value"]["form_settings_data"][0]["items"][j]["value"]);
										break;
									}
							}
					
					if (!replace_prev_html) {
						//preparing css code with current css
						var block_css = module_form_settings.find(".block_css");
						var editor = block_css.data("editor");
						var css = editor ? editor.getValue() : block_css.children("textarea.css").first().val();
						
						data["css"] = css + (data["css"] ? (css ? "\n\n" : "") + data["css"] : "");
						
						//preparing js code with current js
						var block_js = module_form_settings.find(".block_js");
						var editor = block_js.data("editor");
						var js = editor ? editor.getValue() : block_js.children("textarea.js").first().val();
						
						data["js"] = js + (data["js"] ? (js ? "\n\n" : "") + data["js"] : "");
					}
					
					loadFormBlockNewSettings(module_form_settings, add_group_icon, data);
					
					buttons.removeAttr("disabled");
					MyFancyPopup.hidePopup();
				}
				else {
					StatusMessageHandler.showError("Error trying to load form wizard settings.\nPlease try again...");
					buttons.removeAttr("disabled");
				}
			},
			error : function() { 
				StatusMessageHandler.showError("Error trying to load form wizard settings.\nPlease try again...");
				
				buttons.removeAttr("disabled");
			},
		});
	}
}

function getFormWizardSettings() {
	var form_wizard = $(".module_form_settings .form_wizard");
	var steps = form_wizard.find(" > .steps > .step");
	var settings = {};
	
	$.each(steps, function (idx, step) {
		var step = $(step);
		
		if (step.hasClass("panel_type_selection")) {
			settings["panel_type"] = step.children("select.panel_type").val();
			settings["form_type"] = step.children("select.form_type").val();
		}
		else if (step.hasClass("table_selection")) {
			settings["dal_broker"] = step.children(".dal_broker").val();
			settings["db_driver"] = step.children(".db_driver").val();
			settings["type"] = step.children(".db_type").val();
			settings["table"] = step.children(".db_table").val();
			
			var table_options = step.children(".table_options");
			var attributes = [];
			var conditions = [];
			
			$.each( table_options.find(" > .attributes > ul > li"), function (idj, li) {
				li = $(li);
				
				if (li.children(".attr_active").is(":checked"))
					attributes.push(li.attr("data_attr_name"));
			});
			
			$.each( table_options.find(" > .conditions > ul > li"), function (idj, li) {
				li = $(li);
				
				if (li.children(".attr_active").is(":checked"))
					conditions.push({
						"column": li.attr("data_attr_name"),
						"value": li.children(".attr_value").val(),
						"name": li.children(".attr_name").val()
					});
			});
			
			settings["attributes"] = attributes;
			settings["conditions"] = conditions;
		}
		else if (step.hasClass("actions_selection")) {
			var items = step.find(".action > input:checked");
			settings["actions"] = {};
			
			$.each(items, function (idj, item) {
				var action = $(item).parent();
				var action_options = action.find(" > .action_options > .action_option");
				
				var m = action.attr("class").match(/action_([a-z\_]+)/);
				m = m && m[1] ? m[1] : "";
				
				if (m == "links") {
					var action_links = action_options.filter(".action_links");
					
					var links = [];
					$.each(action_links.children(".action_link"), function (idw, link) {
						link = $(link);
						var url = link.children(".action_link_url").val();
						
						if (url != "")
							links.push({
								"url": url,
								"title": link.children(".action_link_title").val(),
								"class": link.children(".action_link_class").val(),
							});
					});
					
					settings["actions"][m] = links;
				}
				else if (m) {
					var succ_msg = action_options.filter(".successful_msg_options");
					var unsucc_msg = action_options.filter(".unsuccessful_msg_options");
					m = m.replace(/-/g, "_");
					
					settings["actions"][m] = {
						"action": m,
						"action_type": action_options.filter(".action_type").children("select").val(),
						"ajax_url": action_options.filter(".ajax_url").children("input").val(),
						"ok_msg_type": succ_msg.find(".msg_type > select").val(),
						"ok_msg_message": succ_msg.find(".msg_message > input").val(),
						"ok_msg_redirect_url": succ_msg.find(".msg_redirect_url > input").val(),
						"error_msg_type": unsucc_msg.find(".msg_type > select").val(),
						"error_msg_message": unsucc_msg.find(".msg_message > input").val(),
						"error_msg_redirect_url": unsucc_msg.find(".msg_redirect_url > input").val(),
						"links": links,
					};
				}
			});
		}
	});
	
	return settings;
}

function toggleFormWizardPanelType(elm) {
	elm = $(elm);
	var panel_type = elm.val();
	var is_panel_list = panel_type == "list_table" || panel_type == "list_form";
	var actions_elm = elm.parent().closest(".steps").children(".actions_selection");
	var selects = actions_elm.find(".action_single_insert, .action_single_update, .action_single_delete").find(" > .action_options > .action_type > select");
	var multiple_elms = actions_elm.children(".multiple_actions");
	
	if (is_panel_list)
		multiple_elms.show();
	else
		multiple_elms.hide();
	
	selects.each(function(idx, select) {
		select = $(select);
		
		if (is_panel_list) {
			if (select.val() == "")
				select.val("ajax_on_click");
			
			select.children("option").first().hide(); //hide default action
		}
		else
			select.children("option").show();
	});
}

function toggleFormWizardTableOptions(elm) {
	$(elm).parent().children(".table_options").toggle();
}

function toggleFormWizardActionOptions(elm) {
	elm = $(elm);
	elm.toggleClass("expand_content collapse_content");
	var ao = elm.parent().children(".action_options");
	var is_visible = ao.css("display") == "none";
	ao.toggle();
	
	if (is_visible)
		toggleFormWizardActionTypeOptions( ao.find(" > .action_type > select")[0] );
}

function addFormWizardActionLinkOptionUrl(elm) {
	var p = $(elm).parent();
	var link = p.children(".action_link").first().clone();
	link.children("input").each(function(idx, input) {
		input.value = "";
	});
	
	p.append(link);
}

function toggleFormWizardActionTypeOptions(elm) {
	elm = $(elm);
	var value = elm.val();
	var p = elm.parent().parent();
	
	if (value == "ajax_on_click" || value == "ajax_on_blur") {
		p.children(".ajax_url").show();
		p.find(".msg_type > select").val("alert").children("option").first().hide(); //hide "show" option
	}
	else {
		p.children(".ajax_url").hide();
		p.find(".msg_type > select").children("option").first().show(); //show "show" option
	}
}

function activateFormWizardAction(elm) {
	elm = $(elm);
	var p = elm.parent();
	
	if (elm.is(":checked")) {
		p.addClass("action_activated");
		
		//prepare multiple_insert_update
		if (p.hasClass("action_multiple_insert") || p.hasClass("action_multiple_update")) {
			var p2 = p.hasClass("action_multiple_insert") ? p.parent().children(".action_multiple_update.action_activated") : p.parent().children(".action_multiple_insert.action_activated");
			
			if (p2[0]) {
				var miu = p.parent().children(".action_multiple_insert_update");
				miu.addClass("action_activated").show();
				miu.children("input").attr("checked", "checked").prop("checked", true);
				
				var mu = p2.hasClass("action_multiple_update") ? p2 : p;
				copyActionToAnotherAction(mu, miu);
				
				//prepare multiple_insert and multiple_update
				p2.hide().removeClass("action_activated").children("input").removeAttr("checked").prop("checked", false);
				p.hide().removeClass("action_activated").children("input").removeAttr("checked").prop("checked", false);
				
				if (p.children(".action_options").css("display") != "none")
					toggleFormWizardActionOptions( p.children(".toggle")[0] );	
				
				if (p2.children(".action_options").css("display") != "none")
					toggleFormWizardActionOptions( p2.children(".toggle")[0] );	
			}
		}
	}
	else {
		p.removeClass("action_activated");
		
		if (p.hasClass("action_multiple_insert_update")) {
			p.hide();
			p.parent().children(".action_multiple_insert, .action_multiple_update").show();
			copyActionToAnotherAction(p, p.parent().children(".action_multiple_update"));
		}
		
		if (p.children(".action_options").css("display") != "none")
			toggleFormWizardActionOptions( p.children(".toggle")[0] );	
	}
}

function copyActionToAnotherAction(action1, action2) {
	if (action1[0] && action2[0]) {
		var ao1 = action1.find(".action_options");
		var ao2 = action2.find(".action_options");
		
		ao2.find(".action_type select").val( ao1.find(".action_type select").val() );
		ao2.find(".ajax_url input").val( ao1.find(".ajax_url input").val() );
		ao2.find(".successful_msg_options .msg_type select").val( ao1.find(".successful_msg_options .msg_type select").val() );
		ao2.find(".successful_msg_options .msg_message input").val( ao1.find(".successful_msg_options .msg_message input").val() );
		ao2.find(".successful_msg_options .msg_redirect_url input").val( ao1.find(".successful_msg_options .msg_redirect_url input").val() );
		ao2.find(".unsuccessful_msg_options .msg_type select").val( ao1.find(".unsuccessful_msg_options .msg_type select").val() );
		ao2.find(".unsuccessful_msg_options .msg_message input").val( ao1.find(".unsuccessful_msg_options .msg_message input").val() );
		ao2.find(".unsuccessful_msg_options .msg_redirect_url input").val( ao1.find(".unsuccessful_msg_options .msg_redirect_url input").val() );
		
		toggleFormWizardActionTypeOptions( ao2.find(".action_type select")[0] );
	}
}

function onChangeDALBrokerFormWizard(elm) {
	elm = $(elm);
	
	var selected_broker = elm.val();
	var select = elm.parent().children(".db_driver");
	var selected_driver = select.val();
	selected_driver = selected_driver ? selected_driver : default_db_driver;
	
	var db_drivers = db_brokers_drivers_tables_attributes ? db_brokers_drivers_tables_attributes[selected_broker] : null;
	var options = '<option value="">-- Default --</option>';
	
	if (db_drivers)
		for (var db_driver in db_drivers) 
			options += "<option" + (selected_driver == db_driver ? " selected" : "") + ">" + db_driver + "</option>";
	
	select.html(options);
	
	if (selected_driver) 
		onChangeDBDriverOrTypeFormWizard(select[0]);
}

function onChangeDBDriverOrTypeFormWizard(elm) {
	elm = $(elm);
	
	var p = elm.parent();
	var selected_broker = p.children(".dal_broker").val();
	var selected_driver = p.children(".db_driver").val(); //bc elm it could be .db_driver or .db_type
	selected_driver = selected_driver ? selected_driver : default_db_driver;
	var selected_type = p.children(".db_type").val();
	var select = p.children(".db_table");
	var selected_table = select.val();
	
	getDBTables(selected_broker, selected_driver, selected_type);
				
	var tables = db_brokers_drivers_tables_attributes && db_brokers_drivers_tables_attributes[selected_broker] && db_brokers_drivers_tables_attributes[selected_broker][selected_driver] ? db_brokers_drivers_tables_attributes[selected_broker][selected_driver][selected_type] : null;

	var options = '<option></option>';
	if (tables)
		for (var table in tables) 
			options += "<option" + (selected_table == table ? " selected" : "") + ">" + table + "</option>";
	
	select.html(options);
	
	if (selected_table)
		onChangeDBTableFormWizard(select[0]);
	else
		p.children(".table_options").hide();
}

function onChangeDBTableFormWizard(elm) {
	elm = $(elm);
	
	var p = elm.parent();
	var selected_broker = p.children(".dal_broker").val();
	var selected_driver = p.children(".db_driver").val(); //bc elm it could be .db_driver or .db_type
	selected_driver = selected_driver ? selected_driver : default_db_driver;
	var selected_type = p.children(".db_type").val();
	var selected_table = p.children(".db_table").val();
	
	getDBAttributes(selected_broker, selected_driver, selected_type, selected_table);
	
	var attributes = db_brokers_drivers_tables_attributes && db_brokers_drivers_tables_attributes[selected_broker] && db_brokers_drivers_tables_attributes[selected_broker][selected_driver] && db_brokers_drivers_tables_attributes[selected_broker][selected_driver][selected_type] ? db_brokers_drivers_tables_attributes[selected_broker][selected_driver][selected_type][selected_table] : null;
	
	var html = '';
	if (attributes)
		for (var idx in attributes) 
			html += '<li data_attr_name="' + attributes[idx] + '">'
				+ '<input class="attr_active" type="checkbox" onClick="activateDBActionTableAttribute(this)" />'
				+ '<label>' + attributes[idx] + '</label>'
				+ '<input class="attr_value" type="text" title="Write the value here" />'
				+ '<input class="attr_name" type="text" title="Write the alias/label here" />'
			+ '</li>';
	
	var table_options = p.children(".table_options");
	table_options.find(" > .attributes > ul").html(html).find("input.attr_active").attr("checked", "checked").prop("checked", true).parent().addClass("attr_activated");
	table_options.find(" > .conditions > ul").html(html);
	
	if (!selected_table)
		table_options.hide();
}

/* SAVE FUNCTIONS */

function getModuleFormContentsSettings(module_form_contents) {
	var items = module_form_contents.find(".sla_groups_flow > .sla_groups > .sla_group_item:not(.sla_group_default)");
	var actions_settings = getSLASettingsFromItemsToSave(items);
	
	if (!$.isArray(actions_settings))
		return null;
	
	//Preparing block_css and block_js
	var block_css = module_form_contents.find(".block_css");
	var editor = block_css.data("editor");
	var css = editor ? editor.getValue() : block_css.children("textarea.css").first().val();
	
	var block_js = module_form_contents.find(".block_js");
	var editor = block_js.data("editor");
	var js = editor ? editor.getValue() : block_js.children("textarea.js").first().val();
	
	var settings = {
		"actions": actions_settings,
		"css": css,
		"js": js
	};
	
	return settings;
}

function getModuleFormSettings(module_form_settings) {
	var status = true;
	var module_form_contents = module_form_settings.children(".module_form_contents");
	var task_flow_tab_openned_by_user = module_form_contents.find("#tasks_flow_tab a").attr("is_init");
	
	if (task_flow_tab_openned_by_user && !is_from_auto_save) { //only if not auto save action, otherwise ignore it and only save the properties.
		var selected_tab = module_form_contents.children("ul").find("li.ui-tabs-selected, li.ui-tabs-active").first();
		
		if (selected_tab.attr("id") != "tasks_flow_tab") {
			module_form_contents.find("ul #tasks_flow_tab a").first().click();
			updateTasksFlow();
		}
		
		status = jsPlumbWorkFlow.jsPlumbTaskFile.save(null, {overwrite: true, silent: true});
	
		if (status && confirm("Do you wish to generate new Groups based in the Workflow tab, before you save?\nIf you click the cancel button, the system will discard the changes in the Workflow tab and give preference to the Groups tab."))
			status = generateGroupsFromTasksFlow(true);
		
		if (selected_tab.attr("id") != "tasks_flow_tab")
			selected_tab.children("a").click();
	}
	
	return status ? getModuleFormContentsSettings(module_form_contents) : null;
}

function onClickFormModuleSLAGroupsFlowTab(elm) {
	update_sla_programming_task_variables_from_sla_groups = true;
	update_sla_programming_task_variables_from_workflow = false;
}

function onClickFormModuleTaskWorkflowTab(elm) {
	update_sla_programming_task_variables_from_sla_groups = false;
	update_sla_programming_task_variables_from_workflow = true;
	
	onClickTaskWorkflowTab(elm);
}

function generateGroupsFromTasksFlow(do_not_confirm) {
	var status = true;
	
	if (do_not_confirm || confirm("Do you wish to update Groups accordingly with the workflow tasks?")) {
		status = false;
		
		MyFancyPopup.init({
			parentElement: window,
		});
		MyFancyPopup.showOverlay();
		MyFancyPopup.showLoading();
		var workflow_menu = $(".workflow_menu");
		workflow_menu.hide();
		
		var save_options = {
			overwrite: true,
			success: function(data, textStatus, jqXHR) {
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, set_tmp_workflow_file_url, function() {
						jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
						StatusMessageHandler.removeLastShownMessage("error");
						generateGroupsFromTasksFlow(true);
					});
			},
		};
		
		if (jsPlumbWorkFlow.jsPlumbTaskFile.save(set_tmp_workflow_file_url, save_options)) {
			$.ajax({
				type : "get",
				url : create_settings_from_workflow_file_url,
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					if (data && data.hasOwnProperty("settings")) {
						//console.log(data);
						var settings = data["settings"];
						
						//create group settings
						if (settings) {
							var module_form_settings = $(".module_form_settings");
							var add_group_icon = module_form_settings.find(".sla_groups_flow > nav > .add_form_group")[0];
							
							//remove old groups
							module_form_settings.find(".sla_groups_flow > .sla_groups > .sla_group_item").not(".sla_group_default").remove();
							
							//load new groups
							loadFormBlockNewSettings(module_form_settings, add_group_icon, settings);
						}
						
						if (data["error"] && data["error"]["infinit_loop"] && data["error"]["infinit_loop"][0]) {
							var loops = data["error"]["infinit_loop"];
							
							var msg = "";
							for (var i = 0; i < loops.length; i++) {
								var loop = loops[i];
								var slabel = jsPlumbWorkFlow.jsPlumbTaskFlow.getTaskLabelByTaskId(loop["source_task_id"]);
								var tlabel = jsPlumbWorkFlow.jsPlumbTaskFlow.getTaskLabelByTaskId(loop["target_task_id"]);
								
								msg += (i > 0 ? "\n" : "") + "- '" + slabel + "' => '" + tlabel + "'";
							}
							
							msg = "The system detected the following invalid loops and discarded them from the Groups settings:\n" + msg + "\n\nYou should remove them from the workflow and apply the correct 'loop task' for doing loops.";
							jsPlumbWorkFlow.jsPlumbStatusMessage.showError(msg);
							alert(msg);
						}
						else {
							$("#sla_groups_flow_tab a").first().click();
							status = true;
						}
					}
					else 
						jsPlumbWorkFlow.jsPlumbStatusMessage.showError("There was an error trying to update Groups. Please try again.");
					
					MyFancyPopup.hidePopup();
					workflow_menu.show();
				},
				error : function() { 
					if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
						showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_settings_from_workflow_file_url, function() {
							generateGroupsFromTasksFlow(true);
						});
					else {
						jsPlumbWorkFlow.jsPlumbStatusMessage.showError("There was an error trying to update Groups. Please try again.");
				
						MyFancyPopup.hidePopup();
						workflow_menu.show();
					}
				},
				async : false,
			});
		}
		else 
			jsPlumbWorkFlow.jsPlumbStatusMessage.showError("There was an error trying to update Groups. Please try again.");
	}
	
	return status;
}

function generateTasksFlowFromGroups(do_not_confirm) {
	var status = true;
	
	if (do_not_confirm || confirm("Do you wish to update this workflow accordingly with the settings in the Groups Tab?")) {
		status = false;
		
		jsPlumbWorkFlow.getMyFancyPopupObj().hidePopup();
		MyFancyPopup.init({
			parentElement: window,
		});
		MyFancyPopup.showOverlay();
		MyFancyPopup.showLoading();
		$(".workflow_menu").hide();
		
		var module_form_contents = $(".module_form_settings .module_form_contents");
		var settings = getModuleFormContentsSettings(module_form_contents);
		
		$.ajax({
			type : "post",
			url : create_workflow_file_from_settings_url,
			data : {"settings": settings},
			dataType : "text",
			success : function(data, textStatus, jqXHR) {
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_workflow_file_from_settings_url, function() {
						generateTasksFlowFromGroups(true);
					});
				else if (data == 1) {
					var previous_callback = jsPlumbWorkFlow.jsPlumbTaskFile.on_success_read;
					
					jsPlumbWorkFlow.jsPlumbTaskFile.on_success_read = function(data, text_status, jqXHR) {
						if (!data)
							jsPlumbWorkFlow.jsPlumbStatusMessage.showError("There was an error trying to load the workflow's tasks.");
						else {
							jsPlumbWorkFlow.jsPlumbTaskSort.sortTasks();
							status = true;
						}
						
						jsPlumbWorkFlow.jsPlumbTaskFile.on_success_read = previous_callback;
					}
				
					jsPlumbWorkFlow.jsPlumbTaskFile.reload(get_tmp_workflow_file_url, {"async": true});
				}
				else
					jsPlumbWorkFlow.jsPlumbStatusMessage.showError("There was an error trying to update this workflow. Please try again.");
				
				MyFancyPopup.hidePopup();
				$(".workflow_menu").show();
			},
			error : function() { 
				jsPlumbWorkFlow.jsPlumbStatusMessage.showError("There was an error trying to update this workflow. Please try again.");
			
				MyFancyPopup.hidePopup();
				$(".workflow_menu").show();
			},
			async : false,
		});
	}
	
	return status;
}

function isModuleFormSettingsChanged() {
	var new_settings_id = getModuleFormSettingsId();
	
	return saved_settings_id != new_settings_id;
}

function getModuleFormSettingsId() {
	var module_form_settings = $(".block_obj > .module_settings > .settings > .module_form_settings");
	var settings = getModuleFormSettings(module_form_settings);
	
	return $.isPlainObject(settings) ? $.md5(JSON.stringify(settings)) : null;
}

function saveModuleFormSettings(button) {
	prepareAutoSaveVars();
	
	var is_from_auto_save_bkp = is_from_auto_save; //backup the is_from_auto_save, bc if there is a concurrent process running at the same time, this other process may change the is_from_auto_save value.
	
	if (!window.is_save_block_func_running) {
		window.is_save_block_func_running = true;
		
		var module_form_settings = $(".block_obj > .module_settings > .settings > .module_form_settings");
		var settings = getModuleFormSettings(module_form_settings);
		var new_settings_id = $.isPlainObject(settings) ? $.md5(JSON.stringify(settings)) : null;
		
		if (!saved_settings_id || saved_settings_id != new_settings_id) {
			//console.log(settings);
			
			//check if settings obj is valid
			if (!$.isPlainObject(settings) || !$.isArray(settings["actions"])) {
				if (!is_from_auto_save_bkp) {
					//check if user is logged in
					//if there was a previous function that tried to execute an ajax request, like the getCodeForSaving method, we detect here if the user needs to login, and if yes, recall the save function again. 
					//Do not re-call only the ajax request below, otherwise there will be some other files that will not be saved, this is, the getCodeForSaving saves the workflow and if we only call the ajax request below, the workflow won't be saved. To avoid this situation, we call the all save function.
					if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL)) {
						showAjaxLoginPopup(jquery_native_xhr_object.responseURL, jquery_native_xhr_object.responseURL, function() {
							jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
							StatusMessageHandler.removeLastShownMessage("error");
							
							window.is_save_block_func_running = false;
							saveModuleFormSettings(button);
						});
					}
					else
						StatusMessageHandler.showError("Error trying to get form settings actions.\nPlease try again...");
				}
				else
					resetAutoSave();
				
				window.is_save_block_func_running = false;
				
				return;
			}
			
			//show loading icon
			if (!is_from_auto_save_bkp) {
				MyFancyPopup.showOverlay();
				MyFancyPopup.showLoading();
			}
			
			//execute ajax request
			var ajax_opts = {
				type : "post",
				url : create_form_settings_code_url,
				data : {"settings" : settings},
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					if (data && data["code"]) {
						var status = saveBlockRawCode(data["code"], {
							complete : function() {
								if (!is_from_auto_save_bkp)
									MyFancyPopup.hidePopup(); //we still need this here bc the saveBlockObj doesn't hide the popup if the .block_obj doesn't exists.
								window.is_save_block_func_running = false;
							},
						});
						
						if (status)
							saved_settings_id = new_settings_id; //set new saved_settings_id
					}
					else {
						if (!is_from_auto_save_bkp) {
							MyFancyPopup.hidePopup();
							StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
						}
						else
							resetAutoSave();
						
						window.is_save_block_func_running = false;
					}
				},
				error : function() { 
					if (!is_from_auto_save_bkp) {
						//hide popup in case be over of login popup
						MyFancyPopup.hidePopup();
						
						if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
							showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_form_settings_code_url, function() {
								jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
								StatusMessageHandler.removeLastShownMessage("error");
								
								//show loading icon again
								MyFancyPopup.showOverlay();
								MyFancyPopup.showLoading();
								
								//re-call ajax request
								$.ajax(ajax_opts);
							});
						else {
							StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
							window.is_save_block_func_running = false;
						}
					}
					else {
						resetAutoSave();
						window.is_save_block_func_running = false;
					}
				},
			};
			
			$.ajax(ajax_opts);
		}
		else {
			if (!is_from_auto_save_bkp)
				StatusMessageHandler.showMessage("Nothing to save.");
			else
				resetAutoSave();
			
			window.is_save_block_func_running = false;
		}
	}
	else if (!is_from_auto_save_bkp)
		StatusMessageHandler.showMessage("There is already a saving process running. Please wait a few seconds and try again...");
}

function convertModuleFormSettingsToModuleWorkflowSettings(elm) {
	if (confirm("You are about to convert this block to a workflow.\nThis action is irreversible.\n\nAre you sure you wish to continue?")) {
		MyFancyPopup.showOverlay();
		MyFancyPopup.showLoading();
		
		var module_form_settings = $(elm).parent().closest(".block_obj").find(".module_form_settings");
		var settings = getModuleFormSettings(module_form_settings);
		//console.log(settings);
		
		if (!$.isArray(settings["actions"])) {
			StatusMessageHandler.showError("Error trying to get form settings actions.\nPlease try again...");
			return;
		}
		
		$.ajax({
			type : "post",
			url : convert_form_settings_to_workflow_code_url,
			data : {"settings" : settings},
			dataType : "json",
			success : function(data, textStatus, jqXHR) {
				if (data && data["code"])
					saveBlockObj({
						"module_id": "workflow",
						"code": data["code"],
					}, {
						"success": function() {
							//refresh page
							var url = document.location;
							document.location = url;
						}
					});
				else
					StatusMessageHandler.showError("Error trying to convert settings into workflow.\nPlease try again...");
				
				MyFancyPopup.hidePopup();
			},
			error : function() { 
				StatusMessageHandler.showError("Error trying to convert settings into workflow.\nPlease try again...");
				MyFancyPopup.hidePopup();
			},
		});
	}
}
