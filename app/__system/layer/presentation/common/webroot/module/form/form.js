var cached_data_for_variables_in_workflow = {};

$(function () {
	//only add popups if not exist yet
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
	
	var module_form_settings = $(".module_form_settings");
	var block_obj = module_form_settings.parent().closest(".block_obj");
	block_obj.children(".buttons").children("input").attr("onclick", "saveModuleFormSettings(this);");
	
	if (workflow_module_installed_and_enabled)
		block_obj.find(" > .module_data > .module_description").append('<a class="convert_to_module_workflow" onclick="convertModuleFormSettingsToModuleWorkflowSettings(this)">Convert this block into code workflow...</a>');
	
	createObjectItemCodeEditor( module_form_settings.find(".block_css textarea.css")[0], "css", saveModuleFormSettings);
	createObjectItemCodeEditor( module_form_settings.find(".block_js textarea.js")[0], "javascript", saveModuleFormSettings);
	
	/* This is already executed in the common/settings.js, so we cannot executed again.
	choosePropertyVariableFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForVariables,
	});
	choosePropertyVariableFromFileManagerTree.init("choose_property_variable_from_file_manager .class_prop_var");*/
	
	chooseBusinessLogicFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndFunctionsFromTree,
	});
	chooseBusinessLogicFromFileManagerTree.init("choose_business_logic_from_file_manager");
	
	chooseQueryFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeParametersAndResultMapsFromTree,
	});
	chooseQueryFromFileManagerTree.init("choose_query_from_file_manager");
	
	chooseHibernateObjectMethodFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeParametersAndResultMapsFromTree,
	});
	chooseHibernateObjectMethodFromFileManagerTree.init("choose_hibernate_object_method_from_file_manager");
	
	chooseBlockFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotBlocksFromTree,
	});
	chooseBlockFromFileManagerTree.init("choose_block_from_file_manager");
	
	var module_form_contents = module_form_settings.children(".module_form_contents");
	
	//remove database options bc there are no detected db_drivers
	if (typeof db_brokers_drivers_tables_attributes != "undefined" && $.isEmptyObject(db_brokers_drivers_tables_attributes)) 
		module_form_contents.find("#groups_flow > .form-groups > .form-group-item > .form-group-header > .action-type").each(function(idx, item) {
			 $(item).children("optgroup").first().remove(); 
		});
	
	module_form_contents.tabs();
	
	//change some handlers
	ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onFormModuleProgrammingTaskChooseCreatedVariable;
	
	//load task flow and code editor
	onLoadTaskFlowChartAndCodeEditor();
	
	MyFancyPopup.hidePopup();
});

/* LOAD FUNCTIONS */

function loadFormBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var module_form_settings = settings_elm.children(".module_form_settings");
	var add_group_icon = module_form_settings.find("#groups_flow > nav > .add_form_group")[0];
	
	var tasks_values = convertSettingsToTasksValues(settings_values);
	//console.log(settings_values);
	//console.log(tasks_values);
	
	//setting the save_func in the CreateFormTaskPropertyObj
	if (CreateFormTaskPropertyObj) 
		CreateFormTaskPropertyObj.editor_save_func = function () {
			var button = module_form_settings.parent().closest(".block_obj").children(".buttons").children("input")[0];
			saveModuleFormSettings(button);
		}
	
	if (tasks_values) {
		//load old form settings - Do not remove this code until all the old forms have the new settings
		if (tasks_values.hasOwnProperty("form_settings_data_type"))
			loadFormBlockOldSettings(module_form_settings, add_group_icon, tasks_values);
		else //load new form settings
			loadFormBlockNewSettings(module_form_settings, add_group_icon, tasks_values);
	}
	else { 
		//set default group
		/*var new_group = addNewFormGroup(add_group_icon);
		var select = new_group.find(" > .form-group-header .action-type");
		onChangeFormInputType( select[0] );*/
		
		openFormWizard();
	}
	
	MyFancyPopup.hidePopup();
}

function loadFormBlockNewSettings(module_form_settings, add_group_icon, tasks_values) {
	if (tasks_values.hasOwnProperty("actions"))
		loadFormBlockNewSettingsActions(add_group_icon, tasks_values["actions"], false);
	
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

function loadFormBlockNewSettingsActions(add_group_icon, actions, is_sub_group) {
	if (actions) {
		$.each(actions, function (i, action) {
			var group_item = is_sub_group ? addNewFormSubGroup(add_group_icon) : addNewFormGroup(add_group_icon);
			loadFormBlockNewSettingsAction(action, group_item);
		});
		
		var module_form_settings = $(add_group_icon).parent().closest(".module_form_settings");
		var exists_deprecated_actions = module_form_settings.find("#groups_flow .form-groups .form-group-item-undefined").length > 0;
		
		if (module_form_settings.children(".deprecated_actions_message").length == 0 && exists_deprecated_actions)
			module_form_settings.prepend('<div class="deprecated_actions_message">Attention: There are actions which are now DEPRECATED! Apparently this presentation layer is not connected anymore with all layers that some actions need use!</div>');
	}
}
		
function loadFormBlockNewSettingsAction(action, group_item) {
	if ($.isPlainObject(action) && group_item[0]) {
		var result_var_name = action["result_var_name"];
		var action_type = ("" + action["action_type"]).toLowerCase();
		var action_value = action["action_value"];
		var condition_type = action["condition_type"];
		var condition_value = action["condition_value"];
		var action_description = action["action_description"];
		var task_default_values = {};
		
		switch (action_type) {
			case "html":
				task_default_values["createform"] = {
					"form_settings_data_type": action_value["form_settings_data_type"], 
					"form_settings_data": action_value["form_settings_data"]
				};
				break;
			
			case "callbusinesslogic":
			case "callibatisquery":
			case "callhibernatemethod":
			case "getquerydata":
			case "setquerydata":
			case "callfunction":
			case "callobjectmethod":
			case "restconnector":
			case "soapconnector":
				task_default_values[ action_type ] = action_value;
				break;
			
			case "show_ok_msg":
			case "show_ok_msg_and_stop":
			case "show_ok_msg_and_die":
			case "show_ok_msg_and_redirect":
			case "show_error_msg":
			case "show_error_msg_and_die":
			case "show_error_msg_and_stop":
			case "show_error_msg_and_redirect":
			case "alert_msg":
			case "alert_msg_and_stop":
			case "alert_msg_and_redirect":
				var message_elm = group_item.find(' > .form-group-body > .message-action-body');
				message_elm.find(' > .message > input').val(action_value["message"]);
				message_elm.find(' > .redirect-url > input').val(action_value["redirect_url"]);
				break;
			
			case "redirect":
				group_item.find(' > .form-group-body > .redirect-action-body > input').val(action_value);
				break;
				
			case "return_previous_record":
			case "return_next_record":
			case "return_specific_record":
				var records_elm = group_item.find(' > .form-group-body > .records-action-body');
				records_elm.find(' > .records-variable-name > input').val(action_value["records_variable_name"]);
				records_elm.find(' > .index-variable-name > input').val(action_value["index_variable_name"]);
				break;
			
			case "check_logged_user_permissions":
				var clupab = group_item.find(" > .form-group-body > .check-logged-user-permissions-action-body");
				
				if (action_value["all_permissions_checked"] == 1)
					clupab.find(" > .all-permissions-checked > input").attr("checked", "checked").prop("checked", true);
				
				if (action_value["logged_user_id"])
					clupab.find(" > .logged-user-id > input").val(action_value["logged_user_id"]);
				
				if (action_value["entity_path_var_name"])
					clupab.find(" > .entity-path-var-name").val(action_value["entity_path_var_name"]);
				
				if (action_value["users_perms"] && ($.isArray(action_value["users_perms"]) || $.isPlainObject(action_value["users_perms"]))) {
					var add_elm = clupab.find(" > .users-perms > table > thead .add");
					
					if (action_value["users_perms"].hasOwnProperty("user_type_id") || action_value["users_perms"].hasOwnProperty("activity_id")) //This is very important bc the users_perms come from the workflow xml too, and it can be the item it-self. In this case we must convert it to an array.
						action_value["users_perms"] = [ action_value["users_perms"] ];
					
					$.each(action_value["users_perms"], function(idx, user_perm) {
						if ($.isPlainObject(user_perm)) {
							var row = addUserPermission(add_elm[0]);
							var user_type_id_elm = row.find(".user-type-id select");
							var activity_id_elm = row.find(".activity-id select");
							
							user_type_id_elm.val(user_perm["user_type_id"]);
							activity_id_elm.val(user_perm["activity_id"]);
							
							//in case the values don't exist, add them...
							if (user_type_id_elm.val() != user_perm["user_type_id"]) 
								user_type_id_elm.append('<option selected>NOT IN DB: ' + user_perm["user_type_id"] + '</option>');
							
							//in case the values don't exist, add them...
							if (activity_id_elm.val() != user_perm["activity_id"]) 
								activity_id_elm.append('<option selected>NOT IN DB: ' + user_perm["activity_id"] + '</option>');
						}
					});
				}
				break;
				
			case "code":
				group_item.find(' > .form-group-body > .code-action-body > textarea').val(action_value);
				break;
				
			case "array":
				ArrayTaskUtilObj.onLoadArrayItems( group_item.find(' > .form-group-body > .array-action-body'), action_value, "");
				break;
				
			case "string":
				group_item.find(' > .form-group-body > .string-action-body > input').val(action_value);
				break;
				
			case "variable":
				action_value = "" + action_value;
				action_value = action_value.trim().substr(0, 1) == '$' ? action_value.trim().substr(1) : action_value;
				group_item.find(' > .form-group-body > .variable-action-body > input').val(action_value);
				
				break;
				
			case "sanitize_variable":
				action_value = "" + action_value;
				action_value = action_value.trim().substr(0, 1) == '$' ? action_value.trim().substr(1) : action_value;
				group_item.find(' > .form-group-body > .sanitize-variable-action-body > input').val(action_value);
				
				break;
				
			case "list_report":
				var list_report_elm = group_item.find(' > .form-group-body > .list-report-action-body');
				list_report_elm.find(' > .type > select').val( action_value["type"] );
				list_report_elm.find(' > .doc_name > input').val( action_value["doc_name"] );
				list_report_elm.find(' > .continue > select').val( action_value["continue"] );
				
				var variable = "" + action_value["variable"];
				variable = variable.trim().substr(0, 1) == '$' ? variable.trim().substr(1) : variable;
				list_report_elm.find(' > .variable > input').val(variable);
				
				break;
				
			case "call_block":
				var call_block_elm = group_item.find(' > .form-group-body > .call-block-action-body');
				call_block_elm.find(' > .block > input').val( action_value["block"] );
				
				if (action_value["project"]) {
					var select = call_block_elm.find(' > .project > select');
					select.val( action_value["project"] );
					
					if (select.val() != action_value["project"])
						select.append('<option value="' + action_value["project"] + '" selected>' + action_value["project"] + ' - DOES NOT EXIST ANYMORE</option>');
				}
				break;
			
			case "include_file":
				var include_file_elm = group_item.find(' > .form-group-body > .include-file-action-body');
				include_file_elm.children('input.path').val( action_value["path"] );
				
				if (action_value["once"] == 1)
					include_file_elm.children('input.once').attr("checked", "checked").prop("checked", true);
				
				break;
			
			case "draw_graph":
				var draw_graph_elm = group_item.find(' > .form-group-body > .draw-graph-action-body');
				
				if ($.isPlainObject(action_value)) {
					var draw_graph_settings_elm = draw_graph_elm.children(".draw-graph-settings");
					addDrawGraphSettingsDataSet( draw_graph_settings_elm.find(".graph-data-sets > label > .add")[0] );
					
					if (action_value.hasOwnProperty("code")) {
						var draw_graph_js_elm = draw_graph_elm.children(".draw-graph-js-code");
						draw_graph_js_elm.children("textarea").val(action_value["code"]);
					}
					else
						loadDrawGraphSettings(draw_graph_settings_elm, action_value);
				}
				break;
				
			case "loop":
				var loop_elm = group_item.find(' > .form-group-body > .loop-action-body');
				var sub_add_group_icon = loop_elm.find(' > header > a');
				
				loop_elm.find(' > header > .records-variable-name > input').val(action_value["records_variable_name"]);
				loop_elm.find(' > header > .records-start-index > input').val(action_value["records_start_index"]);
				loop_elm.find(' > header > .records-end-index > input').val(action_value["records_end_index"]);
				loop_elm.find(' > header > .array-item-key-variable-name > input').val(action_value["array_item_key_variable_name"]);
				loop_elm.find(' > header > .array-item-value-variable-name > input').val(action_value["array_item_value_variable_name"]);
				
				loadFormBlockNewSettingsActions(sub_add_group_icon[0], action_value["actions"], true);
				
				break;
			
			case "group":
				var group_elm = group_item.find(' > .form-group-body > .group-action-body');
				var sub_add_group_icon = group_elm.find(' > header > a');
				
				group_elm.find(' > header > .group-name > input').val(action_value["group_name"]);
				
				loadFormBlockNewSettingsActions(sub_add_group_icon[0], action_value["actions"], true);
				
				break;
		}
		
		initFormGroupItemTasks(group_item, task_default_values);
		
		var group_header = group_item.children(".form-group-header");
		
		if (result_var_name != "")
			group_header.children(".result-var-name").val(result_var_name).removeClass("result-var-name-output");
		
		var select = group_header.find(" > .form-group-sub-header > .condition-type");
		select.val(condition_type);
		onGroupConditionTypeChange( select[0] );
		
		group_header.find(" > .form-group-sub-header > .condition-value").val(condition_value);
		
		group_header.find(" > .form-group-sub-header > .action-description > textarea").val(action_description);
		
		select = group_header.children(".action-type");
		select.val(action_type);
		onChangeFormInputType( select[0] );
		
		switch (action_type) {
			case "insert":
			case "update":
			case "delete":
			case "select":
			case "procedure":
			case "getinsertedid":
				$(function () { //must be after everything loads otherwise the UI was not created yet
					var db_elm = group_item.find(' > .form-group-body > .database-action-body');
					
					if (typeof DBQueryTaskPropertyObj != "undefined" && db_elm[0]) { //db_drivers can be null and so DBQueryTaskPropertyObj won't exists
						//load header fields
						var select = db_elm.find('.dal-broker > select');
						select.val(action_value["dal_broker"]);
						updateDALActionBroker(select[0]);
						
						select = db_elm.find('.db-type > select');
						var selected_type = select.val();
						
						//note that the updateDBActionType doesn't need to run bc the updateDBActionDriver runs in the updateDALActionBroker and does almost the same thing.
						if (selected_type != action_value["db_type"]) {
							select.val(action_value["db_type"]);
							
							updateDBActionType(select[0]);
						}
						
						select = db_elm.find('.db-driver > select');
						var selected_driver = select.val();
						
						//note that the updateDBActionDriver already runs in the updateDALActionBroker
						if (selected_driver != action_value["db_driver"]) {
							select.val(action_value["db_driver"]);
							
							updateDBActionDriver(select[0]);
						}
						
						if (action_type != "getinsertedid") {
							//load table fields
							if (action_value["table"]) {
								var db_action_table = db_elm.find(".database-action-table");
								select = db_action_table.find(" > .table > select");
								select.val(action_value["table"]);
								updateDBActionTableAttributes(select[0]);
								
								if (action_value["attributes"]) {
									var ul = db_action_table.find(" > .attributes > ul");
									ul.children(".attr-activated").removeClass("attr-activated").children(".attr-active").removeAttr("checked").prop("checked", false);
									
									$.each(action_value["attributes"], function (idx, attribute) {
										var attr_name = attribute["column"];
										var attr_value = attribute["value"];
										var attr_alias = attribute["name"];
										
										var li = ul.children("li[data-attr-name='" + attr_name + "']");
										li.addClass("attr-activated");
										li.children(".attr-active").attr("checked", "checked").prop("checked", true);
										li.children(".attr-value").val(attr_value);
										li.children(".attr-name").val(attr_alias);
									});
								}
								
								if (action_value["conditions"]) {
									var ul = db_action_table.find(" > .conditions > ul");
									ul.children(".attr-activated").removeClass("attr-activated").children(".attr-active").removeAttr("checked").prop("checked", false);
									
									$.each(action_value["conditions"], function (idx, condition) {
										var attr_name = condition["column"];
										var attr_value = condition["value"];
										
										var li = ul.children("li[data-attr-name='" + attr_name + "']");
										li.addClass("attr-activated");
										li.children(".attr-active").attr("checked", "checked").prop("checked", true);
										li.children(".attr-value").val(attr_value);
									});
								}
								
								//load sql and settings tabs
								var sql = convertActionValueToSQL(action_type, action_value);
								
								if (sql) {
									var rel_query_elm = db_elm.find(".relationship .query").first();
									var query_tabs = rel_query_elm.children(".query_tabs");
									var query_sql_elm = rel_query_elm.find(" > .sql_text_area");
									var editor = getQuerySqlEditor(query_sql_elm);
									
									if (editor)
										editor.setValue(sql);
									else
										query_sql_elm.children("textarea").val(sql);
									
									//prepare sql ui
									query_tabs.find(" > .query_design_tab a").attr("do_not_confirm", 1).trigger("click").removeAttr("do_not_confirm");
									
									//show tables ui
									query_tabs.find(" > .query_table_tab a").trigger("click");
									
									//close settings ui popup automatically
									setTimeout(function() {
										eval('var WF = jsPlumbWorkFlow_' + rel_query_elm.attr("rand_number") + ';');
										WF.getMyFancyPopupObj().hidePopup();
										
										//just in case
										rel_query_elm.find(".popup_overlay, .choose_table_or_attribute").hide();
									}, 10000);
								}
							}
							else {
								var sql_elm = db_elm.find(".sql_text_area");
								var editor = sql_elm.data("editor");
								var sql = prepareFieldValueIfValueTypeIsString(action_value["sql"]); //remove enclosed quotes if exists
								
								if (editor)
									editor.setValue(sql);
								else
									sql_elm.children("textarea").val(sql);
								
								db_elm.find(".query > .query_tabs > .query_sql_tab > a").attr("not_create_sql_from_ui", 1).click().removeAttr("not_create_sql_from_ui");
							}
						}
						
						//load footer fields
						db_elm.find(" > footer .opts .options_type").val( action_value["options_type"] );
						
						LayerOptionsUtilObj.onLoadTaskProperties(db_elm.children("footer"), action_value); //init options
					}
					else 
						initGroupItemUndefinedTask(group_item, action_type, action_value);
				});	
				break;
			
			case "draw_graph":
				var draw_graph_elm = group_item.find(' > .form-group-body > .draw-graph-action-body');
				
				if ($.isPlainObject(action_value) && action_value.hasOwnProperty("code")) {
					draw_graph_elm.tabs("option", "active", 1);
					initDrawGraphCode( draw_graph_elm.children(".draw-graph-js-code") );
				}
		}
	}
}

function loadFormBlockOldSettings(module_form_settings, add_group_icon, tasks_values) {
	var has_form_input = tasks_values.hasOwnProperty("form_input_data_type");
		
	var group_items = module_form_settings.find("#groups_flow > .form-groups > .form-group-item:not(.form-group-default)");
	
	//creating group-item input data
	if (has_form_input) {
		var group_item_input_data = $(group_items[1]);
			
		if (!group_item_input_data || !group_item_input_data[0])
			group_item_input_data = addNewFormGroup(add_group_icon);
		
		group_item_input_data.find(" > .form-group-header > .result-var-name").val("result_items").removeClass("result-var-name-output");
	}
	
	//creating group-item html
	var group_item_html = group_items.first();
	
	if (!group_item_html || !group_item_html[0])
		group_item_html = addNewFormGroup(add_group_icon);
	
	if (js_load_functions) {
		//preparing form html
		//prepare input_data in form_settings_data
		if (tasks_values["form_settings_data"])
			tasks_values["form_settings_data"] = convertFormSettingsDataWithNewInputData(tasks_values["form_settings_data"], "result_items");
		
		var html_values = tasks_values ? {"createform" : {"form_settings_data_type": tasks_values["form_settings_data_type"], "form_settings_data": tasks_values["form_settings_data"]}} : {};
		
		initFormGroupItemTasks(group_item_html, html_values);
		
		//preparing form input
		if (has_form_input) {
			//preparing broker input
			var broker_values = {};
			if (tasks_values && tasks_values["form_input_data_type"] == "brokers")
				broker_values[ tasks_values["brokers_layer_type"] ] = tasks_values["broker"];
			
			initFormGroupItemTasks(group_item_input_data, broker_values);
			
			//preparing other inputs (variable, code, string and array)
			if (tasks_values["form_input_data_type"] != "brokers") {
				var form_input_data = tasks_values["form_input_data"];
				
				if (tasks_values["form_input_data_type"] == "array") //is array
					ArrayTaskUtilObj.onLoadArrayItems( group_item_input_data.find(' > .form-group-body > .array-action-body'), form_input_data, "");
				else {
					form_input_data = form_input_data ? "" + form_input_data : "";
					
					if (tasks_values["form_input_data_type"] == "variable" && form_input_data.trim().substr(0, 1) == '$') //is variable
						group_item_input_data.find(' > .form-group-body > .variable-action-body > input').val( form_input_data.trim().substr(1) );
					else if (tasks_values["form_input_data_type"] == "") //is code
						group_item_input_data.find(' > .form-group-body > .code-action-body > textarea').val(form_input_data);
					else //is string
						group_item_input_data.find(' > .form-group-body > .string-action-body > input').val(form_input_data);
				}
			}
		}
	}
	
	var select = group_item_html.find(" > .form-group-header .action-type");
	select.val("html");
	onChangeFormInputType( select[0] );
	
	if (has_form_input) {
		var default_broker_action_type = tasks_values["form_input_data_type"] == "brokers" ? tasks_values["brokers_layer_type"] : (tasks_values["form_input_data_type"] ? tasks_values["form_input_data_type"] : "code");
		var select = group_item_input_data.find(" > .form-group-header .action-type");
		
		select.val(default_broker_action_type);
		onChangeFormInputType( select[0] );
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

function convertSettingsToTasksValues(settings_values) {
	var tasks_values = {};
	
	if (!$.isEmptyObject(settings_values)) {
		//Preparing old settings
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
		
		//Preparing old settings
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
		
		//Preparing new settings
		if (settings_values["actions"] || settings_values["css"] || settings_values["js"]) {
			tasks_values = convertBlockSettingsValuesIntoBasicArray(settings_values);
			
			if ($.type(tasks_values["css"]) == "string")
				tasks_values["css"] = prepareFieldValueIfValueTypeIsString(tasks_values["css"]); //remove extra quotes that were added by the convertBlockSettingsValuesIntoBasicArray function
			
			if ($.type(tasks_values["js"]) == "string")
				tasks_values["js"] = prepareFieldValueIfValueTypeIsString(tasks_values["js"]); //remove extra quotes that were added by the convertBlockSettingsValuesIntoBasicArray function
			
			var actions = tasks_values["actions"];
			tasks_values["actions"] = [];
			
			if (actions) {
				if (actions.hasOwnProperty("key") || actions.hasOwnProperty("value") || actions.hasOwnProperty("items"))
					actions = [actions];
				
				$.each(actions, function (i, action) {
					var action_type = action["action_type"];
					var item_settings = settings_values["actions"]["items"][i];
					item_settings = item_settings && item_settings.hasOwnProperty("items") ? item_settings["items"] : item_settings;
					var action_value = item_settings["action_value"];
					var condition_value = item_settings["condition_value"];
					
					if (action["condition_type"] == "execute_if_code" || action["condition_type"] == "execute_if_not_code")
						action["condition_value"] = condition_value["value_type"] == "string" ? '"' + condition_value["value"].replace(/"/g, '\\"') + '"' : condition_value["value"];
					//else if (condition_value["value_type"] == "string") 
					else if ($.type(action["condition_value"]) == "string")
						action["condition_value"] = prepareFieldValueIfValueTypeIsString(action["condition_value"]);
					
					if (!action_value)
						action["action_value"] = "";
					else if (action_value["value"] && (action_value["value_type"] == "method" || action_value["value_type"] == "function")) {
						$.ajax({
							type : "post",
							url : get_input_data_method_settings_url,
							data : {"method" : action_value["value"]},
							dataType : "json",
							success : function(data, textStatus, jqXHR) {
								if (data && data["brokers_layer_type"] && data["brokers"] && $.isPlainObject(data["brokers"])) {
									action["action_value"] = data["brokers"];
									action["action_type"] = data["brokers_layer_type"];
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
					else if (action_type == "html") {
						var av = {};
						
						if (action_value["value"]) {
							av["form_settings_data_type"] = action_value["value_type"];
							av["form_settings_data"] = action_value["value"];
						}
						else {
							av["form_settings_data_type"] = "array";
							av["form_settings_data"] = convertObjectIntoArray(action_value["items"]);
						}
						
						action["action_value"] = av;
					}
					else if (action_value["items"] && (action_type == "array" || action_type == "code" || action_type == "variable" || action_type == "string")) { //fix some wrong hard coded values
						action["action_value"] = convertObjectIntoArray(action_value["items"]);
						action["action_type"] = "array";
					}
					else if (action_type == "callbusinesslogic" || action_type == "callibatisquery" || action_type == "callhibernatemethod" || action_type == "getquerydata" || action_type == "setquerydata" || action_type == "callfunction" || action_type == "callobjectmethod" || action_type == "restconnector") {
						//Preparing new broker settings
						if (action_value["items"] && $.isPlainObject(action_value["items"]))
							for (var k in action_value["items"]) {
								var v = action_value["items"][k];
								
								if (v && $.isPlainObject(v)) {
									if (v.hasOwnProperty("items")) {
										var v_items = convertObjectIntoArray(v["items"]);
										
										//pass the value_type inside of the array to type, but only if is callfunction or callobjectmethod
										if ((action_type == "callfunction" && k == "func_args") || (action_type == "callobjectmethod" && k == "method_args"))
											$.each(v_items, function(idx, v_item) {
												v_item["type"] = v_item["value_type"];
												delete v_item["value_type"];
												v_items[idx] = v_item;
											});
										
										action["action_value"][k] = v_items;
										action["action_value"][k + "_type"] = "array";
									}
									else if (v.hasOwnProperty("value")) {
										action["action_value"][k + "_type"] = v["value_type"]; //adding the _type attribute for all the other values
										
										if (v["value_type"] == "" && v["value"] == "null")
											action["action_value"][k] = "";
									}
								}
							}
							
						//console.log(action_value);
						//console.log(action["action_value"]);
					}
					else if (action_type == "insert" || action_type == "update" || action_type == "delete" || action_type == "select" || action_type == "procedure" || action_type == "getinsertedid") {
						if (action_value["items"] && $.isPlainObject(action_value["items"]) && action_value["items"]["options"]) {
							var v = action_value["items"]["options"];
							
							if (v && $.isPlainObject(v)) {
								if (v.hasOwnProperty("items")) {
									action["action_value"]["options"] = convertObjectIntoArray(v["items"]);
									action["action_value"]["options_type"] = "array";
								}
								else {
									action["action_value"]["options"] = v["value"];
									action["action_value"]["options_type"] = v["value_type"];
									
									if (v["value_type"] == "string")
										action["action_value"]["options"] = prepareFieldValueIfValueTypeIsString(action["action_value"]["options"]); //remove extra quotes that were added by the convertBlockSettingsValuesIntoBasicArray function
								}
							}
							else
								action["action_value"]["options_type"] = "";
						}
					}
					else if (action_type == "soapconnector") {
						if (action_value["items"] && $.isPlainObject(action_value["items"]))
							for (var k in action_value["items"]) { //check first level for: data and result_type
								var v = action_value["items"][k];
								
								if (v && $.isPlainObject(v)) {
									if (v.hasOwnProperty("items")) { //for data
										var new_v = {};
										
										if (v["items"] && $.isPlainObject(v["items"]))
											for (var sub_k in v["items"]) { //check second level for: data[headers], data[options], data[type], data[wsdl_url], etc...
												var sub_v = v["items"][sub_k];
												
												if (sub_v && $.isPlainObject(sub_v)) {
													if (sub_v.hasOwnProperty("items")) { //for data[headers], data[options], etc...
														switch(sub_k) {
															case "headers": //for data[headers]
																var headers = [];
																
																if (sub_v["items"] && ($.isPlainObject(sub_v["items"]) || $.isArray(sub_v["items"])))
																	for (var header_idx in sub_v["items"]) {
																		var header = sub_v["items"][header_idx];
																		var new_header = {};
																		
																		if (header && $.isPlainObject(header)) //for data[headers][idx][namespace], data[headers][idx][name], data[headers][idx][actor], data[headers][idx][parameters], etc...
																			for (var header_k in header) {
																				var header_v = header[header_k];
																				
																				if (header_v && $.isPlainObject(header_v)) {
																					if (header_v.hasOwnProperty("items")) { //for data[headers][idx][parameters]
																						new_header[header_k] = convertObjectIntoArray(header_v["items"]);
																						new_header[header_k + "_type"] = "array";
																					}
																					else if (header_v.hasOwnProperty("value")) { //for data[headers][idx][namespace], data[headers][idx][name], data[headers][idx][actor], etc...
																						new_header[header_k] = header_v["value"];
																						new_header[header_k + "_type"] = header_v["value_type"];
																						
																						if (header_v["value_type"] == "" && header_v["value"] == "null")
																							new_header[header_k] = "";
																					}
																				}
																			}
																			
																		headers.push(new_header);
																	}
																
																new_v[sub_k] = headers;
																new_v[sub_k + "_type"] = "options";
																break;
															
															case "options": //for data[options]
																var options = [];
																
																if (sub_v["items"] && ($.isPlainObject(sub_v["items"]) || $.isArray(sub_v["items"])))
																	for (var opt_idx in sub_v["items"]) {
																		var opt = sub_v["items"][opt_idx];
																		
																		options.push({
																			name: opt["key"],
																			value: opt["items"] ? opt["items"] : opt["value"],
																			var_type: opt["items"] ? "array" : opt["value_type"],
																		});
																	}
																
																new_v[sub_k] = options;
																new_v[sub_k + "_type"] = "options";
																break;
															
															case "remote_function_args": //for data[remote_function_args]
																new_v[sub_k] = convertObjectIntoArray(sub_v["items"]);
																new_v[sub_k + "_type"] = "array";
																break;
														}
													}
													else if (sub_v.hasOwnProperty("value")) { //for data[type], data[wsdl_url], etc...
														new_v[sub_k] = sub_v["value"];
														new_v[sub_k + "_type"] = sub_v["value_type"]; //adding the _type attribute for all the other values
														
														if (sub_v["value_type"] == "" && sub_v["value"] == "null")
															new_v[sub_k] = "";
													}
												}
											}
											
										action["action_value"][k] = new_v;
										action["action_value"][k + "_type"] = "options";
									}
									else if (v.hasOwnProperty("value")) { //for result_type
										action["action_value"][k + "_type"] = v["value_type"]; //adding the _type attribute for all the other values
										
										if (v["value_type"] == "" && v["value"] == "null")
											action["action_value"][k] = "";
									}
								}
							}
						//console.log(action_value);
					}
					//Is already done bellow
					//else if (action_type == "code" || action_type == "variable" || action_type == "string")
					//	action["action_value"] = $.type(action_value["value"]) == "string" ? prepareFieldValueIfValueTypeIsString(action_value["value"]) : action_value["value"];
					else if ((action_type == "loop" || action_type == "group") && action_value && action_value.hasOwnProperty("items") && action_value["items"]) {
						var sub_task_values = convertSettingsToTasksValues(action_value["items"]);
						action["action_value"]["actions"] = sub_task_values["actions"];
					}
					else if ($.type(action["action_value"]) == "string") //remove extra quotes that were added by the convertBlockSettingsValuesIntoBasicArray function. This will include the parse for the values with "code", "variable" and "string"
						action["action_value"] = prepareFieldValueIfValueTypeIsString(action["action_value"]); 
					else if ($.isPlainObject(action["action_value"])) { //for the messages and database/sql cases
					
						for (var k in action["action_value"]) {
							var v = action["action_value"][k];
							
							if ($.type(v) == "string")
								action["action_value"][k] = prepareFieldValueIfValueTypeIsString(v); //remove extra quotes that were added by the convertBlockSettingsValuesIntoBasicArray function
						}
					}
					
					if (action_type == "")
						action["action_type"] = "code";
					
					tasks_values["actions"].push(action);
				});
			}
		}
		
		tasks_values = convertBlockSettingsValuesKeysToLowerCase(tasks_values);
	}
	
	//console.log(tasks_values);
	return tasks_values;
}

function initFormGroupItemTasks(group_item, values) {
	if (!group_item[0].hasAttribute("inited")) {
		group_item[0].setAttribute("inited", 1);
		var exists = false;
		
		if (js_load_functions) {
			for (var tag in js_load_functions) {
				var func = js_load_functions[tag];
				
				var s = values && values.hasOwnProperty(tag) && values[tag] ? values[tag] : {};
				var m = null;
				
				switch(tag) {
					case "callbusinesslogic": 
						m = group_item.find(" > .form-group-body > .broker-action-body > .call_business_logic_task_html");
						break;
					case "callibatisquery": 
						m = group_item.find(" > .form-group-body > .broker-action-body > .call_ibatis_query_task_html");
						break;
					case "callhibernatemethod": 
						m = group_item.find(" > .form-group-body > .broker-action-body > .call_hibernate_method_task_html");
						break;
					case "getquerydata": 
						m = group_item.find(" > .form-group-body > .broker-action-body > .get_query_data_task_html");
						break;
					case "setquerydata": 
						m = group_item.find(" > .form-group-body > .broker-action-body > .set_query_data_task_html");
						break;
					case "callfunction": 
						m = group_item.find(" > .form-group-body > .broker-action-body > .call_function_task_html");
						break;
					case "callobjectmethod": 
						m = group_item.find(" > .form-group-body > .broker-action-body > .call_object_method_task_html");
						break;
					case "restconnector": 
						m = group_item.find(" > .form-group-body > .broker-action-body > .get_url_contents_task_html");
						break;
					case "soapconnector": 
						m = group_item.find(" > .form-group-body > .broker-action-body > .soap_connector_task_html");
						break;
					case "createform": 
						m = group_item.find(" > .form-group-body > .html-action-body > .create_form_task_html");
						break;
				}
				
				if (m && m[0]) {
					exists = $.isEmptyObject(values) || values.hasOwnProperty(tag);
					
					//prepare properties
					jsPlumbWorkFlow.jsPlumbProperty.setPropertiesFromHtmlElm(m, "task_property_field", s);
					
					//console.log(func);
					//console.log(m);
					//console.log(s);
					eval (func + '(m.parent(), null, s);');
				}
			}
		}
		
		//if a broker does not exists anymore (bc the layer stop be related with another layer), then we must continue saving the value, in case the user relates again the layer with that layer.
		if (!exists && values) {
			//get first key which is the tag
			for (var tag in values)
				break;
			
			var s = values.hasOwnProperty(tag) && values[tag] ? values[tag] : {};
			initGroupItemUndefinedTask(group_item, tag, s);
		}
	}
}

function initGroupItemUndefinedTask(group_item, tag_name, tag_values) {
	group_item.find(" > .form-group-body > .undefined-action-value").val( JSON.stringify(tag_values) );
	
	var select = group_item.find(" > .form-group-header > select.action-type");
	var option = select.find("option[value=" + tag_name + "]");
	
	if (option.length == 0) {
		select.append('<option value="' + tag_name + '" undefined="1">Undefined - ' + tag_name + '</option>');
		select.val(tag_name);
		onChangeFormInputType( select[0] );
	}
	
	group_item.addClass("form-group-item-undefined");
}

/* UI FUNCTIONS */

function addNewFormGroup(elm) {
	var groups = $(elm).parent().closest(".module_form_settings").find("#groups_flow .form-groups");
	var new_group = groups.children(".form-group-item.form-group-default").clone().removeClass("form-group-default");
	groups.append(new_group);
	new_group.show();
	
	return new_group;
}

function addNewFormSubGroup(elm) {
	var groups = $(elm).parent().closest(".module_form_settings").find("#groups_flow .form-groups");
	var new_group = groups.children(".form-group-item.form-group-default").clone().removeClass("form-group-default");
	
	var sub_groups = $(elm).parent().closest("section").children(".form-sub-groups");
	sub_groups.append(new_group);
	new_group.show();
	
	return new_group;
}

function collapseFormGroups(elm) {
	$(elm).parent().closest(".module_form_settings").find("#groups_flow .form-group-item:not(.form-group-default) > .form-group-header > .toggle").each(function(idx, item) {
		item = $(item);

		if (item.parent().closest(".form-group-item").children(".form-group-body").css("display") != "none")
			item.trigger("click");
	});
}

function expandFormGroups(elm) {
	$(elm).parent().closest(".module_form_settings").find("#groups_flow .form-group-item:not(.form-group-default) > .form-group-header > .toggle").each(function(idx, item) {
		item = $(item);

		if (item.parent().closest(".form-group-item").children(".form-group-body").css("display") == "none")
			item.trigger("click");
	});
}

function addAndInitNewFormGroup(elm) {
	var new_group = addNewFormGroup(elm);
	onChangeFormInputType( new_group.find(" > .form-group-header .action-type")[0] );
	return new_group;
}

function addAndInitNewFormSubGroup(elm) {
	var new_group = addNewFormSubGroup(elm);
	onChangeFormInputType( new_group.find(" > .form-group-header .action-type")[0] );
	return new_group;
}

function onChangeFormInputType(elm) {
	elm = $(elm);
	var group_item = elm.parent().closest(".form-group-item");
	var group_body = group_item.children(".form-group-body");
	var selection = elm.val();
	var is_undefined = elm.find("option:selected").attr("undefined");
	
	if (is_undefined)
		group_item.addClass("form-group-item-undefined");
	else
		group_item.removeClass("form-group-item-undefined");
	
	var sections = group_body.children();
	sections.hide();
	
	if (group_body.css("display") == "none")
		toggleGroupBody( group_item.find(" > .form-group-header > .toggle")[0] );
	
	switch (selection) {
		case "html":
			var section = sections.filter(".html-action-body").show();
			initFormGroupItemTasks(group_item, {});
			break;
			
		case "insert":
		case "update":
		case "delete":
		case "select":
		case "procedure":
		case "getinsertedid":
			var section = sections.filter(".database-action-body");
			section.attr("class", "database-action-body database-action-body-" + selection).show();
			
			var db_action_body = section.children("article");
			var rel_type_select = db_action_body.find(".rel_type > select");
			
			//even if selection is getinsertedid, execute code below, so it can initalize the dal and db broker fields.
			if (!rel_type_select[0]) {
				//preparing taskworkflow
				var rand = Math.floor(Math.random() * 1000);
				
				var html = $( query_task_html.replace(/#rand#/g, rand) );
				var query = html.find(".query");
				db_action_body.append(html);
				
				var table_html = '<div id="query_obj_tabs_#rand#-3" class="database-action-table">'
					+ '<div class="table"><label>Table: </label><select class="task_property_field" name="form[0][table]" onChange="updateDBActionObjTableAttributes(this)"></select></div>'
					+ '<div class="attributes"><label>Attributes: </label><ul></ul></div>'
					+ '<div class="conditions"><label>Conditions: </label><ul></ul></div>'
				+ '</div>';
				table_html = $( table_html.replace(/#rand#/g, rand) );
				query.append(table_html);
				
				var query_tabs = query.children(".query_tabs");
				query_tabs.prepend( '<li class="query_table_tab"><a href="#query_obj_tabs_#rand#-3">Table</a></li>'.replace(/#rand#/g, rand) );
				
				var query_design_tab = query_tabs.find(" > .query_design_tab > a");
				var on_click = query_design_tab.attr("onClick").replace("initQueryDesign", "initDatabaseActionBodyQueryDesign");
				query_design_tab.attr("onClick", on_click);
				
				var query_sql_tab = query_tabs.find(" > .query_sql_tab > a");
				var on_click = query_sql_tab.attr("onClick").replace("initQuerySql", "initDatabaseActionBodyQuerySql");
				query_sql_tab.attr("onClick", on_click);
				
				//settings tabs
				query.tabs();
				html.find(".query_settings").tabs();
				html.find(".query_insert_update_delete").tabs();
				
				$(function () { //must be after everything loads, otherwise the JsPlumb gives error. This error happens when we have saved settings and we are loading them before the page loads. In this case the JsPlumb gives error. So we can only run the the code bellow after the page loads.
					addTaskFlowChart(rand, true);
					updateQueryUITableFromQuerySettings(rand);
					
					html.children(".header_buttons, .rel_name, .parameter_class_id, .parameter_map_id, .result_class_id, .result_map_id").hide();
					
					var rel_type = html.children(".rel_type");
					rel_type.hide();
					rel_type_select = rel_type.children("select");
					rel_type_select.val(selection);
					updateRelationshipType(rel_type_select[0], rand);
					
					html.find(".myfancypopup.choose_table_or_attribute > .contents > .db_broker > select").change(function () {
						onChangePopupDBBrokers(this);
					});
					
					html.find(".myfancypopup.choose_table_or_attribute > .contents > .db_driver > select").change(function () {
						onChangePopupDBDrivers(this);
					});
					
					html.find(".myfancypopup.choose_table_or_attribute > .contents > .type > select").change(function () {
						onChangePopupDBTypes(this);
					});
					
					//preparing db brokers, drivers and tables
					var options = '<option></option>';
					for (var broker in db_brokers_drivers_tables_attributes)
						options += "<option" + (default_dal_broker == broker ? " selected" : "") + ">" + broker + "</option>";
					
					var select = section.find(" > header > .dal-broker > select");
					select.html(options);
					updateDALActionBroker(select[0]);
				});
			}
			else {
				var rand = rel_type_select.parent().closest(".relationship").children(".query").attr("rand_number");
				rel_type_select.val(selection);
				updateRelationshipType(rel_type_select[0], rand);
			}
			
			//if procedure table tabshould be hidden, so we need to change the active tab automatically. The rest is done through css.
			if (selection == "procedure" && db_action_body.find(".query").tabs("option", "active") == 0)
				db_action_body.find(".query").tabs("option", "active", 2);
			
			//add add_variable icon to inputs
			var rel_query_elm = db_action_body.find(" > .relationship > .query").first();
			
			if (rel_query_elm.attr("icon_add_variable_added") != 1) {
				rel_query_elm.attr("icon_add_variable_added", 1);
				
				var query_obj_tabs_id = rel_query_elm.find(" > .query_tabs > .query_design_tab > a").attr("href");
				var query_obj_tabs = rel_query_elm.children(query_obj_tabs_id);
				addModuleFormVariableIconToInputs( query_obj_tabs.find(" > .query_insert_update_delete > .query_table") );
				addModuleFormVariableIconToInputs( query_obj_tabs.find(".limit_start") );
				
				query_obj_tabs.find(".attributes, .keys, .conditions, .groups_by, .sorts").find(" > table > thead .icon.add").each(function(idx, add_icon) {
					add_icon = $(add_icon);
					add_icon.attr("onClick", "addModuleFormVariableIconToInputs(" + add_icon.attr("onClick") + ")");
				});
			}
			break;
			
		case "callbusinesslogic":
		case "callibatisquery":
		case "callhibernatemethod":
		case "getquerydata":
		case "setquerydata":
		case "callfunction":
		case "callobjectmethod":
		case "restconnector":
		case "soapconnector":
			var section = sections.filter(".broker-action-body");
			section.show();
			
			initFormGroupItemTasks(group_item, {});
			onChangeBrokersLayerType(selection, section);
			
			break;
			
		case "show_ok_msg":
		case "show_ok_msg_and_stop":
		case "show_ok_msg_and_die":
		case "show_ok_msg_and_redirect":
		case "show_error_msg":
		case "show_error_msg_and_stop":
		case "show_error_msg_and_die":
		case "show_error_msg_and_redirect":
		case "alert_msg":
		case "alert_msg_and_stop":
		case "alert_msg_and_redirect":
			onMessageChange(elm[0]);
			sections.filter(".message-action-body").show();
			break;
			
		case "redirect":
			sections.filter(".redirect-action-body").show();
			break;
		
		case "refresh":
			group_body.hide();
			break;
		
		case "return_previous_record":
		case "return_next_record":
		case "return_specific_record":
			sections.filter(".records-action-body").show();
			break;
			
		case "check_logged_user_permissions":
			sections.filter(".check-logged-user-permissions-action-body").show();
			break;
			
		case "code":
			var section = sections.filter(".code-action-body");
			section.show();
			
			var editor = section.data("editor");
			
			if (!editor)
				createObjectItemCodeEditor( section.children("textarea")[0], "php", saveModuleFormSettings);
			break;
			
		case "array":
			var section = sections.filter(".array-action-body");
			section.show();
			
			if (!section.find(".items")[0]) {
				var items = {0: {key_type: "null", value_type: "string"}};
				ArrayTaskUtilObj.onLoadArrayItems(section, items, "");
			}
			break;
			
		case "string":
			sections.filter(".string-action-body").show();
			break;
			
		case "variable":
			sections.filter(".variable-action-body").show();
			break;
			
		case "sanitize_variable":
			sections.filter(".sanitize-variable-action-body").show();
			break;
			
		case "list_report":
			sections.filter(".list-report-action-body").show();
			break;
			
		case "call_block":
			sections.filter(".call-block-action-body").show();
			break;
			
		case "include_file":
			sections.filter(".include-file-action-body").show();
			break;
			
		case "draw_graph":
			var section = sections.filter(".draw-graph-action-body").show();
			section.tabs();
			break;
		
		case "loop":
			sections.filter(".loop-action-body").show();
			break;
			
		case "group":
			sections.filter(".group-action-body").show();
			break;
	}
}

function initDatabaseActionBodyQueryDesign(elm, rand_number) {
	elm = $(elm);
	var query_settings_elm = $( elm.attr("href") ).find(".query_settings");
	var prev_html = query_settings_elm.html();
	
	initQueryDesign(elm, rand_number);
	
	//update action type based on sql
	var count = 5;
	var interval = setInterval(function() {
		var next_html = query_settings_elm.html();
		count--;
		
		if (next_html != prev_html) {
			clearInterval(interval);
			
			var rel_type = elm.parent().closest(".relationship").find(" > .rel_type > select").val();
			var action_type_select = elm.parent().closest(".form-group-item").find(" > .form-group-header select.action-type");
			action_type_select.val(rel_type);
			onChangeFormInputType( action_type_select[0] );
		}
		else if (count <= 0)
			clearInterval(interval);
	}, 700);
}

function initDatabaseActionBodyQuerySql(elm, rand_number) {
	initQuerySql(elm, rand_number);
	
	if (!elm.hasAttribute("data-editor-with-save-func")) {
		elm = $(elm);
		elm.attr("data-editor-with-save-func", 1);
		var selector = elm.attr("href");
		var editor = $(selector).data("editor");
		
		if (editor)
			editor.commands.addCommand({
				name: 'saveFile',
				bindKey: {
					win: 'Ctrl-S',
					mac: 'Command-S',
					sender: 'editor|cli'
				},
				exec: function(env, args, request) {
					var button = elm.parent().closest(".block_obj").children(".buttons").children("input")[0];
					saveModuleFormSettings(button);
				},
			});
	}
}

function addModuleFormVariableIconToInputs(added_elm) {
	added_elm.find("input[type=text]").each(function(idx, input) {
		$(input).after('<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>');
	});
}

function convertActionValueToSQL(action_type, action_value) {
	var sql = "";
	
	var conditions = "";
	if (action_value["conditions"])
		$.each(action_value["conditions"], function (idx, condition) {
			if (condition["column"])
				conditions += (conditions ? " and " : "") + "`" + condition["column"] + "`='" + condition["value"].replace(/'/g, "") + "'";
		});
	conditions = conditions ? " where " + conditions : "";
	
	switch (action_type) {
		case "insert":
			var attributes_name = "";
			var attributes_value = "";
			
			if (action_value["attributes"])
				$.each(action_value["attributes"], function (idx, attribute) {
					if (attribute["column"]) {
						attributes_name += (attributes_name ? ", " : "") + "`" + attribute["column"] + "`";
						attributes_value += (attributes_value ? ", " : "") + "'" + attribute["value"].replace(/'/g, "") + "'";
					}
				});
			
			if (attributes_name)
				sql += "insert into " + action_value["table"] + "(" + attributes_name + ") values (" + attributes_value + ");";
			break;
		case "update":
			var attributes = "";
			
			if (action_value["attributes"])
				$.each(action_value["attributes"], function (idx, attribute) {
					if (attribute["column"])
						attributes += (attributes ? ", " : "") + "`" + attribute["column"] + "`='" + attribute["value"].replace(/'/g, "") + "'";
				});
			
			if (attributes)
				sql += "update " + action_value["table"] + " set " + attributes + conditions;
			break;
		case "delete":
			sql += "delete from " + action_value["table"] + conditions;
			break;
		case "select":
			var attributes = "";
			
			if (action_value["attributes"])
				$.each(action_value["attributes"], function (idx, attribute) {
					if (attribute["column"])
						attributes += (attributes ? ", " : "") + "`" + attribute["column"] + "`";
				});
			
			if (attributes)
				sql += "select " + attributes + " from " + action_value["table"] + conditions;
			break;
	}
	
	return sql;
}

function onMessageChange(elm) {
	elm = $(elm);
	var selection = elm.val();
	
	var redirect_url = elm.parent().closest(".form-group-item").find(" > .form-group-body > .message-action-body > .redirect-url");
	redirect_url.hide();
	
	switch (selection) {
		case "show_ok_msg_and_redirect":
		case "show_error_msg_and_redirect":
		case "alert_msg_and_redirect":
			redirect_url.show();
			break;
	}
}

/* CHECK LOGGED USER PERMISSIONS ACTION POPUPS FUNCTIONS */
function addUserPermission(elm) {
	var tr = '<tr>'
		+ '		<td class="user-type-id">'
		+ '			<select>';
		
	if (available_user_types)
		$.each(available_user_types, function(user_type_id, user_type_name) {
			tr += '		<option value="' + user_type_id + '">' + user_type_name + '</option>';
		});
		
	tr += '			</select>'
		+ '		</td>'
		+ '		<td class="activity-id">'
		+ '			<select>';
		
	if (available_activities)
		$.each(available_activities, function(activity_id, activity_name) {
			tr += '		<option value="' + activity_id + '">' + activity_name + '</option>';
		});
		
	tr += '			</select>'
		+ '		</td>'
		+ '		<td class="actions">'
		+ '			<i class="icon remove" onClick="removeUserPermission(this)"></i>'
		+ '		</td>'
		+ '	</tr>';
	
	tr = $(tr);
	
	var tbody = $(elm).parent().closest("table").children("tbody");
	tbody.children(".no_users").hide();
	tbody.append(tr);
	
	return tr;
}

function removeUserPermission(elm) {
	var tr = $(elm).parent().closest('tr');
	var tbody = tr.parent().closest("tbody");
	tr.remove();
	
	if (tbody.children().length == 1)
		tbody.children(".no_users").show();
}

/* DATABASE ACTION POPUPS FUNCTIONS */

function onChangePopupDBBrokers(elm) {
	elm = $(elm);
	var p = elm.parent().closest(".choose_table_or_attribute");
	
	if (p.is("#choose_db_table_or_attribute"))
		p = $(MyFancyPopup.settings.targetField).parent().closest(".database-action-body");
	else
		p = elm.parent().closest(".database-action-body");
	
	var db_driver_select = p.find(" > header > .db-driver > select");
	var old_db_driver = db_driver_select.val();
	
	//update db broker
	var select = p.find(" > header > .dal-broker > select");
	select.val( elm.val() );
	updateDALActionBroker(select[0], true);
	
	//sync db driver too
	var new_db_driver = elm.parent().parent().find(".db_driver > select").val();
	
	if (new_db_driver != old_db_driver || new_db_driver != db_driver_select.val()) {
		db_driver_select.val(new_db_driver);
		updateDBActionDriver(db_driver_select[0], true);
	}
}
	
function onChangePopupDBDrivers(elm) {
	elm = $(elm);
	
	var p = elm.parent().closest(".choose_table_or_attribute");
	
	if (p.is("#choose_db_table_or_attribute"))
		p = $(MyFancyPopup.settings.targetField).parent().closest(".database-action-body");
	else
		p = elm.parent().closest(".database-action-body");
	
	//update db driver
	var select = p.find(" > header > .db-driver > select");
	select.val( elm.val() );
	updateDBActionDriver(select[0], true);
}
	
function onChangePopupDBTypes(elm) {
	elm = $(elm);
	
	var p = elm.parent().closest(".choose_table_or_attribute");
	
	if (p.is("#choose_db_table_or_attribute"))
		p = $(MyFancyPopup.settings.targetField).parent().closest(".database-action-body");
	else
		p = elm.parent().closest(".database-action-body");
	
	//update db type
	var select = p.find(" > header > .db-type > select");
	select.val( elm.val() );
	updateDBActionType(select[0], true);
}

function updateDALActionBroker(elm, already_synced) {
	elm = $(elm);
	var selected_broker = elm.val();
	var db_body = elm.parent().closest(".database-action-body");
	
	if (selected_broker == "")
		db_body.children("article").hide();
	else 
		db_body.children("article").show();
	
	if (!already_synced) { //already_synced means that the popups are already synced and the updateDBDrivers was already called.
		//update all the other brokers in the popups and trigger onchange event on each of them
		var selects = db_body.find(".myfancypopup > .contents > .db_broker > select");
		selects.push( $("#choose_db_table_or_attribute > .contents > .db_broker > select")[0] );
		$.each(selects, function (idx, select) { //must be synchronous bc the code bellow needs to have the
			$(select).val(selected_broker);
			updateDBDrivers(select, false);
		});
	}
	
	//update db_drivers
	var db_drivers = db_brokers_drivers_tables_attributes ? db_brokers_drivers_tables_attributes[selected_broker] : null;
	var options = '<option value="">-- Default --</option>';
	
	if (db_drivers)
		for (var db_driver in db_drivers) 
			options += "<option>" + db_driver + "</option>";
	
	var select = db_body.find(" > header > .db-driver > select");
	var db_driver = select.val();
	select.html(options);
	select.val(db_driver);
	
	if (db_driver != select.val())
		updateDBActionDriver(select[0], already_synced);
}

function updateDBActionDriver(elm, already_synced) {
	//update tables
	elm = $(elm);
	var selected_driver = elm.val();
	selected_driver = selected_driver ? selected_driver : default_db_driver;
	var db_body = elm.parent().closest(".database-action-body");
	
	if (!already_synced) { //already_synced means that the popups are already synced and the updateDBTables was already called.
		//update all the other brokers in the popups and trigger onchange event on each of them
		var rand = db_body.find(".relationship > .query").attr("rand_number");
		var selects = db_body.find(".myfancypopup > .contents > .db_driver > select");
		selects.push( $("#choose_db_table_or_attribute > .contents > .db_driver > select")[0] );
		$.each(selects, function (idx, select) { //must be synchronous bc the code bellow needs to have the db_brokers_drivers_tables_attributes[selected_broker][selected_driver] inited!
			$(select).val(selected_driver);
			updateDBTables(select, rand, false);
		});
	}
	
	//update tables
	var selected_broker = db_body.find(" > header > .dal-broker > select").val();
	var selected_type = db_body.find(" > header > .db-type > select").val();
	var tables = db_brokers_drivers_tables_attributes && db_brokers_drivers_tables_attributes[selected_broker] && db_brokers_drivers_tables_attributes[selected_broker][selected_driver] ? db_brokers_drivers_tables_attributes[selected_broker][selected_driver][selected_type] : null;
	
	var options = '<option></option>';
	if (tables)
		for (var table in tables) 
			options += "<option>" + table + "</option>";
	
	var select = db_body.find(".database-action-table > .table > select");
	var prev_table = select.val();
	select.html(options).val(prev_table);
	updateDBActionTableAttributes(select[0], already_synced);
}

function updateDBActionType(elm, already_synced) {
	//update tables
	elm = $(elm);
	var selected_type = elm.val();
	var db_body = elm.parent().closest(".database-action-body");
	
	if (!already_synced) { //already_synced means that the popups are already synced and the updateDBTables was already called.
		//update all the other brokers in the popups and trigger onchange event on each of them
		var rand = db_body.find(".relationship > .query").attr("rand_number");
		var selects = db_body.find(".myfancypopup > .contents > .type > select");
		selects.push( $("#choose_db_table_or_attribute > .contents > .type > select")[0] );
		$.each(selects, function (idx, select) { //must be synchronous bc the code bellow needs to have the db_brokers_drivers_tables_attributes[selected_broker][selected_driver][selected_type] inited!
			$(select).val(selected_type);
			updateDBTables(select, rand, false);
		});
	}
	
	//update tables
	var selected_broker = db_body.find(" > header > .dal-broker > select").val();
	var selected_driver = db_body.find(" > header > .db-driver > select").val();
	selected_driver = selected_driver ? selected_driver : default_db_driver;
	var tables = db_brokers_drivers_tables_attributes && db_brokers_drivers_tables_attributes[selected_broker] && db_brokers_drivers_tables_attributes[selected_broker][selected_driver] ? db_brokers_drivers_tables_attributes[selected_broker][selected_driver][selected_type] : null;
	
	var options = '<option></option>';
	if (tables)
		for (var table in tables) 
			options += "<option>" + table + "</option>";
	
	var select = db_body.find(".database-action-table > .table > select");
	var prev_table = select.val();
	select.html(options).val(prev_table);
	updateDBActionTableAttributes(select[0], already_synced);
}

function updateDBActionTableAttributes(elm, already_synced) {
	//update tables
	elm = $(elm);
	var selected_table = elm.val();
	var db_body = elm.parent().closest(".database-action-body");
	
	if (!already_synced) { //already_synced means that the popups are already synced and the updateDBAttributes was already called.
		//update all the other brokers in the popups and trigger onchange event on each of them
		var rand = db_body.find(".relationship > .query").attr("rand_number");
		var selects = db_body.find(".myfancypopup > .contents > .db_table > select");
		selects.push( $("#choose_db_table_or_attribute > .contents > .db_table > select")[0] );
		$.each(selects, function (idx, select) { //must be synchronous bc the code bellow needs to have the db_brokers_drivers_tables_attributes[selected_broker][selected_driver] inited!
			$(select).val(selected_table);
			updateDBAttributes(select, rand, false);
		});
	}
	
	//update attributes
	var selected_broker = db_body.find(" > header > .dal-broker > select").val();
	var selected_driver = db_body.find(" > header > .db-driver > select").val();
	selected_driver = selected_driver ? selected_driver : default_db_driver;
	var selected_type = db_body.find(" > header > .db-type > select").val();
	var attributes = db_brokers_drivers_tables_attributes && db_brokers_drivers_tables_attributes[selected_broker] && db_brokers_drivers_tables_attributes[selected_broker][selected_driver] && db_brokers_drivers_tables_attributes[selected_broker][selected_driver][selected_type] ? db_brokers_drivers_tables_attributes[selected_broker][selected_driver][selected_type][selected_table] : null;
	
	var html = '';
	if (attributes)
		for (var idx in attributes) 
			html += '<li data-attr-name="' + attributes[idx] + '">'
					+ '<input class="attr-active" type="checkbox" onClick="activateDBActionTableAttribute(this)" />'
					+ '<label>' + attributes[idx] + '</label>'
					+ '<input class="attr-value" type="text" title="Write the value here" />'
					+ '<input class="attr-name" type="text" title="Write the alias/label here" />'
					+ '<span class="icon add_variable" onClick="onFormModuleDataBaseActionTableProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>'
				+ '</li>';
	
	var db_action_table = db_body.find(".database-action-table");
	var previous_data = {};
	
	//get previous attributes settings
	if (db_action_table.children(".attributes li, .conditions li").length > 0) {
		var lis = db_action_table.children(".attributes, .conditions").find(" > ul > li");
		previous_data[attributes] = {};
		previous_data[conditions] = {};
		
		$.each(lis, function(idx, li) {
			li = $(li);
			var attr_group = li.parent().parent().hasClass("attributes") ? "attributes" : "conditions";
			var attr_name = li.attr("data-attr-name");
			
			previous_data[attr_group][attr_name] = {
				checked: li.children("input.attr-active").is(":checked"),
				value: li.children("input.attr-value").val(),
				name: li.children("input.attr-name").val(),
			};
		});
	}
	
	//load attributes new html
	db_action_table.find(" > .attributes > ul").html(html);
	db_action_table.find(" > .conditions > ul").html(html);
	
	//set new attributes settings if none previously
	if ($.isEmptyObject(previous_data)) //set new attributes settings
		db_action_table.find(" > .attributes > ul").find("input.attr-active").attr("checked", "checked").prop("checked", true).parent().addClass("attr-activated");
	else { //load previous attributes settings
		for (var attr_group in previous_data) {
			var attr_group_items = previous_data[attr_group];
			var ul = db_action_table.find(" > .attributes > ul");
			
			for (var attr_name in attr_group_items) {
				var attr_values = attr_group_items[attr_name];
				var li = ul.children("li[data-attr-name='" + attr_name + "']");
				
				if (attr_values["checked"])
					li.children("input.attr-active").attr("checked", "checked").prop("checked", true).parent().addClass("attr-activated");
				
				li.children("input.attr-value").val(attr_values["value"]);
				li.children("input.attr-name").val(attr_values["name"]);
			}
		}
	}
	
	//show attributes ui
	if (selected_table)
		db_action_table.children(".attributes, .conditions").show();
	else
		db_action_table.children(".attributes, .conditions").hide();
}

function updateDBActionObjTableAttributes(elm, already_synced) {
	updateDBActionTableAttributes(elm, already_synced);
	
	elm = $(elm);
	var selected_table = elm.val();
	var db_body = elm.parent().closest(".database-action-body");
	
	db_body.find(".query .query_insert_update_delete .query_table input").val(selected_table);
}

function activateDBActionTableAttribute(elm) {
	elm = $(elm);
	
	if (elm.is(":checked"))
		elm.parent().addClass("attr-activated");
	else 
		elm.parent().removeClass("attr-activated");
}

/* DRAW GRAPH FUNCTIONS */

function initDrawGraphCode(elm) {
	var section = $(elm).parent().closest(".draw-graph-action-body");
	var js_code = section.children(".draw-graph-js-code");
	var editor = js_code.data("editor");
	
	if (!editor)
		createObjectItemCodeEditor( js_code.children("textarea")[0], "php", saveModuleFormSettings);
}

function loadDrawGraphSettings(draw_graph_settings_elm, action_value) {
	draw_graph_settings_elm.find('.include-graph-library select').val( action_value["include_graph_library"] );
	draw_graph_settings_elm.find('.graph-width input').val( action_value["width"] );
	draw_graph_settings_elm.find('.graph-height input').val( action_value["height"] );
	draw_graph_settings_elm.find('.labels-variable input').val( action_value["labels_variable"] );
	
	var select = draw_graph_settings_elm.find('.labels-and-values-type select');
	select.val( action_value["labels_and_values_type"] );
	onDrawGraphSettingsLabelsAndValuesTypeChange( select[0] );
	
	if (action_value.hasOwnProperty("data_sets") && action_value["data_sets"]) {
		if ($.isPlainObject(action_value["data_sets"]) && action_value["data_sets"].hasOwnProperty("values_variable"))
			action_value["data_sets"] = [ action_value["data_sets"] ];
		
		var graph_data_sets = draw_graph_settings_elm.find(".graph-data-sets");
		var ul = graph_data_sets.children("ul");
		var li = ul.children("li:not(.no-data-sets):last-child");
		var static_options = ["type", "item_label", "values_variable", "background_colors", "border_colors", "border_width"];
		var count = 1;
		
		$.each(action_value["data_sets"], function (idx, data_set) {
			if (ul.children("li:not(.no-data-sets)").length < count)
				li = addDrawGraphSettingsDataSet( graph_data_sets.find(" > label > .add")[0] );
			
			$.each(data_set, function(key, value) {
				if ($.inArray(key, static_options) != -1) {
					var key_class = ("" + key).replace(/\_/g, "-");
					li.find('.' + key_class).children('input, select, textarea').val(value);
				}
				else {
					var sub_li = addDrawGraphSettingsDataSetOtherOption( li.find(".other-options > label > .add")[0] );
					sub_li.find(".option-name").val(key);
					sub_li.find(".option-value").val(value);
				}
			});
			
			count++;
		});
	}
}

function addDrawGraphSettingsDataSet(elm) {
	elm = $(elm);
	var html = $( getDrawGraphSettingsDataSetHtml() );
	
	var ul = elm.parent().closest(".graph-data-sets").children("ul");
	
	ul.append(html);
	
	ul.children("li.no-data-sets").hide();
	
	return html;
}

function removeDrawGraphSettingsDataSet(elm) {
	if (confirm("Do you wish to remove this data-set?")) {
		var li = $(elm).parent().closest("li");
		var ul = li.parent();
		
		li.remove();
		
		if (ul.children("li:not(.no-data-sets)").length == 0)
			ul.children("li.no-data-sets").show();
	}
}

function getDrawGraphSettingsDataSetHtml() {
	return '<li>'
		 + '	<span class="icon delete" onClick="removeDrawGraphSettingsDataSet(this)" title="Click here to remove this data-set">Remove</span>'
		 + '	<div class="type">'
		 + '		<label>Graph Type: </label>'
		 + '		<select class="task_property_field">'
		 + '			<option value="bar">Bar</option>'
		 + '			<option value="line">Line</option>'
		 + '			<option value="radar">Radar</option>'
		 + '			<option value="pie">Pie</option>'
		 + '			<option value="doughnut">Doughnut</option>'
		 + '			<option value="polarArea">Polar Area</option>'
		 + '			<option value="bubble">Bubble</option>'
		 + '			<option value="scatter">Scatter</option>'
		 + '			<option value="area">Area</option>'
		 + '		</select>'
		 + '	</div>'
		 + '	<div class="item-label">'
		 + '		<label>Item Info Label: </label>'
		 + '		<input class="task_property_field" />'
		+ '		<span class="icon add_variable" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>'
		 + '	</div>'
		 + '	<div class="values-variable">'
		 + '		<label>Values Variable (Name): </label>'
		 + '		<input class="task_property_field" />'
		+ '		<span class="icon add_variable" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>'
		 + '	</div>'
		 + '	<div class="background-colors">'
		 + '		<label>Background Colors: </label>'
		 + '		<input class="task_property_field" />'
		+ '		<span class="icon add_variable" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>'
		 + '	</div>'
		 + '	<div class="border-colors">'
		 + '		<label>Border Colors: </label>'
		 + '		<input class="task_property_field" />'
		+ '		<span class="icon add_variable" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>'
		 + '	</div>'
		 + '	<div class="border-width">'
		 + '		<label>Border width: </label>'
		 + '		<input class="task_property_field" />'
		+ '		<span class="icon add_variable" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>'
		 + '	</div>'
		 + '	<div class="other-options">'
		 + '		<label>Other Options: <span class="icon add" onClick="addDrawGraphSettingsDataSetOtherOption(this)">Add</span></label>'
		 + '		<ul>'
		 + '			<li class="no-other-options">No options defined...</li>'
		 + '		</ul>'
		 + '	</div>'
		 + '</li>';
}

function addDrawGraphSettingsDataSetOtherOption(elm) {
	elm = $(elm);
	var html = $( getDrawGraphSettingsDataSetOtherOptionHtml() );
	
	var ul = elm.parent().closest(".other-options").children("ul");
	ul.append(html);;
	
	ul.children("li.no-other-options").hide();
	
	return html;
}

function getDrawGraphSettingsDataSetOtherOptionHtml() {
	return '<li>'
		 + '	<input class="task_property_field option-name" placeHolder="option name" />'
		 + '	<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>'
		 + '	<input class="task_property_field option-value" placeHolder="option value" />'
		 + '	<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>'
		 + '	<span class="icon delete" onClick="removeDrawGraphSettingsDataSetOtherOption(this)" title="Click here to remove this data-set option">Remove</span>'
		 + '</li>';
}

function removeDrawGraphSettingsDataSetOtherOption(elm) {
	if (confirm("Do you wish to remove this data-set option?")) {
		var li = $(elm).parent().closest("li");
		var ul = li.parent();
		
		li.remove();
		
		if (ul.children("li:not(.no-other-options)").length == 0)
			ul.children("li.no-other-options").show();
	}
}

function onDrawGraphJSCodeTabClick(elm) {
	elm = $(elm);
	
	if (elm.attr("is_init") != 1) {
		elm.attr("is_init", 1);
		initDrawGraphCode(elm);
	}
	
	if (confirm("Do you wish to convert the setings into javascript code?")) {
		var draw_graph_elm = elm.parent().closest(".draw-graph-action-body");
		var draw_graph_settings_elm = draw_graph_elm.children(".draw-graph-settings");
		var draw_graph_js_elm = draw_graph_elm.children(".draw-graph-js-code");
		
		var include_graph_library = draw_graph_settings_elm.find('.include-graph-library select').val();
		var width = draw_graph_settings_elm.find('.graph-width input').val();
		var height = draw_graph_settings_elm.find('.graph-height input').val();
		var labels_and_values_type = draw_graph_settings_elm.find('.labels-and-values-type select').val();
		var labels_variable = draw_graph_settings_elm.find('.labels-variable input').val();
		var lis = draw_graph_settings_elm.find('.graph-data-sets > ul > li:not(.no-data-sets)');
		
		width = getDrawGraphSettingValueToJSCode(width, false, false, true);
		height = getDrawGraphSettingValueToJSCode(height, false, false, true);
		labels_variable = getDrawGraphSettingValueToJSCode(labels_variable, true, true, true);
		
		var code = '';
		var data_sets_code = '';
		var default_type = null;
		var count = 1;
		
		$.each(lis, function(idx, li) {
			li = $(li);
			
			var type = li.find('.type select').val();
			var item_label = li.find('.item-label input').val();
			var values_variable = li.find('.values-variable input').val();
			var background_colors = li.find('.background-colors input').val();
			var border_colors = li.find('.border-colors input').val();
			var border_width = li.find('.border-width input').val();
			var other_options = li.find(".other-options > ul > li");
			var data_set_options_code = '';
			var order_exists = false;
			
			if (!default_type)
				default_type = type;
			
			if (labels_and_values_type == "associative") {
				var aux = getDrawGraphSettingValueToJSCode(values_variable, true, false, false);
				labels_variable = '<?php echo json_encode(is_array(' + aux + ') ? array_keys(' + aux + ') : null); ?>';
				labels_and_values_type = null;
			}
			
			item_label = getDrawGraphSettingValueToJSCode(item_label, false, false, true);
			values_variable = getDrawGraphSettingValueToJSCode(values_variable, true, true, true);
			background_colors = getDrawGraphSettingValueToJSCode(background_colors, false, true, true);
			border_colors = getDrawGraphSettingValueToJSCode(border_colors, false, true, true);
			border_width = getDrawGraphSettingValueToJSCode(border_width, false, false, true);
			
			$.each(other_options, function(idy, other_option) {
				other_option = $(other_option);
				var option_name = other_option.find(".option-name").val();
				
				if (option_name) {
					if (option_name == "order")
						order_exists = true;
					
					var option_value = other_option.find(".option-value").val()
					option_value = getDrawGraphSettingValueToJSCode(option_value, false, true, true);
					
					data_set_options_code += "\t\t\t\t" + option_name + ': ' + option_value+ ',' + "\n";
				}
			});
			
			data_sets_code += ''
				 + "\t\t\t" + '{' + "\n"
				 + (type && type != default_type ? "\t\t\t\t" + 'type: "' + type + '",' + "\n" : '')
				 + (item_label ? "\t\t\t\t" + 'label: "' + item_label + '",' + "\n" : '')
				 + "\t\t\t\t" + 'data: ' + (values_variable ? values_variable : "null") + ',' + "\n"
				 + (background_colors ? "\t\t\t\t" + 'backgroundColor: ' + background_colors + ',' + "\n" : '')
				 + (border_colors ? "\t\t\t\t" + 'borderColor: ' + border_colors + ',' + "\n" : '')
				 + (border_width || $.isNumeric(border_width) ? "\t\t\t\t" + 'borderWidth: ' + border_width + ',' + "\n" : '')
				 + (!order_exists ? "\t\t\t\t" + 'order: ' + count + (data_set_options_code ? ',' : '') + "\n" : '')
				 + data_set_options_code
				 + "\t\t\t" + '},' + "\n";
			
			count++;
		});
		
		if (include_graph_library == "cdn_even_if_exists")
			code += '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>' + "\n\n";
		else if (include_graph_library == "cdn_if_not_exists")
			code += '<script>' + "\n"
				+ 'if (typeof Chart != "function")' + "\n"
				+ '	document.write(\'<scr\' + \'ipt src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></scr\' + \'ipt>\');' + "\n"
				+ '</script>' + "\n\n";
		
		var rand = parseInt(Math.random() * 1000);
		code += '<canvas id="my_chart_' + rand + '"' + (width || $.isNumeric(width) ? ' width="' + width + '"' : '') + (height || $.isNumeric(height) ? ' height="' + height + '"' : '') + '></canvas>' + "\n"
			 + "\n"
			 + '<script>' + "\n"
			 + 'var ctx = document.getElementById("my_chart_' + rand + '").getContext("2d");' + "\n"
			 + 'var myChart = new Chart(ctx, {' + "\n"
			 + "\t" + 'type: "' + default_type + '",' + "\n"
			 + "\t" + 'data: {' + "\n"
			 + (labels_variable ? "\t\t" + 'labels: ' + labels_variable + ',' + "\n" : '')
			 + "\t\t" + 'datasets: [' + "\n"
			 + data_sets_code
			 + "\t\t" + ']' + "\n"
			 + "\t" + '},' + "\n"
			 + "\t" + 'options: {' + "\n"
			 + "\t\t" + 'scales: {' + "\n"
			 + "\t\t\t" + 'yAxes: [{' + "\n"
			 + "\t\t\t\t" + 'ticks: {' + "\n"
			 + "\t\t\t\t\t" + 'beginAtZero: true' + "\n"
			 + "\t\t\t\t" + '}' + "\n"
			 + "\t\t\t" + '}]' + "\n"
			 + "\t\t" + '}' + "\n"
			 + "\t" + '}' + "\n"
			 + '});' + "\n"
			 + '</script>';
		
		var editor = draw_graph_js_elm.data("editor");
		
		if (editor)
			editor.setValue(code);
		else
			draw_graph_js_elm.children("textarea").val(code);
	}
}

function getDrawGraphSettingValueToJSCode(value, is_variable_name, is_json_encode, is_code) {
	if (value) {
		var fc = value.charAt(0);
		var lc = value.charAt(value.length - 1);
		
		if ($.isNumeric(value) || value.toLowerCase() == "true" || value.toLowerCase() == "false")
			return value;
		else if (fc == '$')
			value = '\\' + value;
		else if (fc == '#' && lc == '#')
			value = '\\$' + value.substr(1, value.length - 2);
		else {
			var aux = FormFieldsUtilObj.getFormSettingsAttributeValueDesconfigured(value);
			var type = aux[1];
			
			if (type == "string" && is_variable_name)
				value = '\\$' + value;
			else
				value = getArgumentCode(value, type);
		}
		
		return is_json_encode ? '<?php echo json_encode(' + value + '); ?>' : (is_code ? '<?php echo ' + value + '; ?>' : value);
	}
	
	return "";
}

function onDrawGraphSettingsLabelsAndValuesTypeChange(elm) {
	elm = $(elm);
	var labels_and_values_type = elm.val();
	var draw_graph_settings_elm = elm.parent().closest(".draw-graph-settings");
	
	if (labels_and_values_type == "associative") 
		draw_graph_settings_elm.find(".labels-variable").hide();
	else
		draw_graph_settings_elm.find(".labels-variable").show();
}

/* GROUP ITEMS FUNCTIONS */

function toggleGroupBody(elm) {
	elm = $(elm);
	elm.toggleClass("zmdi-plus-circle-o zmdi-minus-circle-outline");
	
	var group_header = elm.parent().closest(".form-group-header");
	var group = group_header.parent().closest(".form-group-item");
	var group_body = group.children(".form-group-body");
	var is_hidden = group_body.css("display") != "none";
	group_body.toggle("fast");
	
	if (is_hidden) {
		group_header.children(".form-group-sub-header").hide("fast");
		group.addClass("collapsed");
	}
	else {
		group_header.children(".form-group-sub-header").show("fast");
		group.removeClass("collapsed");
	}
}

function removeItem(elm) {
	if (confirm("Do you wish to remove this item?"))
		$(elm).parent().closest(".form-group-item").remove();
}

function moveUpItem(elm) {
	var item = $(elm).parent().closest(".form-group-item");
	
	if (item.prev()[0] && !item.prev().hasClass("form-group-default"))
		item.parent()[0].insertBefore(item[0], item.prev()[0]);
}

function moveDownItem(elm) {
	var item = $(elm).parent().closest(".form-group-item");
	
	if (item.next()[0])
		item.parent()[0].insertBefore(item.next()[0], item[0]);
}

/* FORM WIZARD FUNCTIONS */

function openFormWizard() {
	var form_wizard = $(".module_form_settings .form-wizard");
	
	MyFancyPopup.init({
		elementToShow: form_wizard,
		parentElement: document,
		onOpen: function() {
			if (!form_wizard[0].hasAttribute("data-is-inited")) {
				form_wizard.attr("data-is-inited", 1);
				
				//preparing db brokers, drivers and tables
				var section = form_wizard.find(".steps .table-selection");
				
				var options = '';
				for (var broker in db_brokers_drivers_tables_attributes)
					options += "<option" + (default_dal_broker == broker ? " selected" : "") + ">" + broker + "</option>";
				
				var select = section.children(".dal-broker");
				select.html(options);
				onChangeDALBrokerFormWizard(select[0]);
				
				openFormWizardPanel(0);
				
				toggleFormWizardPanelType( form_wizard.find(".steps > .panel-type-selection > select")[0] );
			}
		},
	});
	
	MyFancyPopup.showPopup();
}

function openFormWizardPanel(idx) {
	if ($.isNumeric(idx) && idx >= 0) {
		var form_wizard = $(".module_form_settings .form-wizard");
		var steps = form_wizard.find(" > .steps > .step");
		steps.hide();
		var step = $( steps.get(idx) );
		step.show();
		
		if (step.hasClass("table-selection")) {
			var panel_type = step.parent().find(" > .panel-type-selection > .panel-type").val();
			
			if (panel_type == "single_form")
				step.find(" > .table-options > .conditions").hide();
			else
				step.find(" > .table-options > .conditions").show();
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
	var steps = $(".module_form_settings .form-wizard > .steps > .step");
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
	var steps = $(".module_form_settings .form-wizard > .steps > .step");
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
					var add_group_icon = module_form_settings.find("#groups_flow > nav > .add_form_group")[0];
					
					if (replace_prev_html)
						module_form_settings.find("#groups_flow > .form-groups > .form-group-item").not(".form-group-default").remove();
					
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
	var form_wizard = $(".module_form_settings .form-wizard");
	var steps = form_wizard.find(" > .steps > .step");
	var settings = {};
	
	$.each(steps, function (idx, step) {
		var step = $(step);
		
		if (step.hasClass("panel-type-selection")) {
			settings["panel_type"] = step.children("select.panel-type").val();
			settings["form_type"] = step.children("select.form-type").val();
		}
		else if (step.hasClass("table-selection")) {
			settings["dal_broker"] = step.children(".dal-broker").val();
			settings["db_driver"] = step.children(".db-driver").val();
			settings["type"] = step.children(".db-type").val();
			settings["table"] = step.children(".db-table").val();
			
			var table_options = step.children(".table-options");
			var attributes = [];
			var conditions = [];
			
			$.each( table_options.find(" > .attributes > ul > li"), function (idj, li) {
				li = $(li);
				
				if (li.children(".attr-active").is(":checked"))
					attributes.push(li.attr("data-attr-name"));
			});
			
			$.each( table_options.find(" > .conditions > ul > li"), function (idj, li) {
				li = $(li);
				
				if (li.children(".attr-active").is(":checked"))
					conditions.push({
						"column": li.attr("data-attr-name"),
						"value": li.children(".attr-value").val(),
						"name": li.children(".attr-name").val()
					});
			});
			
			settings["attributes"] = attributes;
			settings["conditions"] = conditions;
		}
		else if (step.hasClass("actions-selection")) {
			var items = step.find(".action > input:checked");
			settings["actions"] = {};
			
			$.each(items, function (idj, item) {
				var action = $(item).parent();
				var action_options = action.find(" > .action-options > .action-option");
				
				var m = action.attr("class").match(/action-([a-z\-]+)/);
				m = m && m[1] ? m[1] : "";
				
				if (m == "links") {
					var action_links = action_options.filter(".action-links");
					
					var links = [];
					$.each(action_links.children(".action-link"), function (idw, link) {
						link = $(link);
						var url = link.children(".action-link-url").val();
						
						if (url != "")
							links.push({
								"url": url,
								"title": link.children(".action-link-title").val(),
								"class": link.children(".action-link-class").val(),
							});
					});
					
					settings["actions"][m] = links;
				}
				else if (m) {
					var succ_msg = action_options.filter(".successful-msg-options");
					var unsucc_msg = action_options.filter(".unsuccessful-msg-options");
					m = m.replace(/-/g, "_");
					
					settings["actions"][m] = {
						"action": m,
						"action_type": action_options.filter(".action-type").children("select").val(),
						"ajax_url": action_options.filter(".ajax-url").children("input").val(),
						"ok_msg_type": succ_msg.find(".msg-type > select").val(),
						"ok_msg_message": succ_msg.find(".msg-message > input").val(),
						"ok_msg_redirect_url": succ_msg.find(".msg-redirect-url > input").val(),
						"error_msg_type": unsucc_msg.find(".msg-type > select").val(),
						"error_msg_message": unsucc_msg.find(".msg-message > input").val(),
						"error_msg_redirect_url": unsucc_msg.find(".msg-redirect-url > input").val(),
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
	var actions_elm = elm.parent().closest(".steps").children(".actions-selection");
	var selects = actions_elm.find(".action-single-insert, .action-single-update, .action-single-delete").find(" > .action-options > .action-type > select");
	var multiple_elms = actions_elm.children(".multiple-actions");
	
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
	$(elm).parent().children(".table-options").toggle();
}

function toggleFormWizardActionOptions(elm) {
	elm = $(elm);
	elm.toggleClass("zmdi-plus-circle-o zmdi-minus-circle-outline");
	var ao = elm.parent().children(".action-options");
	var is_visible = ao.css("display") == "none";
	ao.toggle();
	
	if (is_visible)
		toggleFormWizardActionTypeOptions( ao.find(" > .action-type > select")[0] );
}

function addFormWizardActionLinkOptionUrl(elm) {
	var p = $(elm).parent();
	var link = p.children(".action-link").first().clone();
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
		p.children(".ajax-url").show();
		p.find(".msg-type > select").val("alert").children("option").first().hide(); //hide "show" option
	}
	else {
		p.children(".ajax-url").hide();
		p.find(".msg-type > select").children("option").first().show(); //show "show" option
	}
}

function activateFormWizardAction(elm) {
	elm = $(elm);
	var p = elm.parent();
	
	if (elm.is(":checked")) {
		p.addClass("action-activated");
		
		//prepare multiple-insert-update
		if (p.hasClass("action-multiple-insert") || p.hasClass("action-multiple-update")) {
			var p2 = p.hasClass("action-multiple-insert") ? p.parent().children(".action-multiple-update.action-activated") : p.parent().children(".action-multiple-insert.action-activated");
			
			if (p2[0]) {
				var miu = p.parent().children(".action-multiple-insert-update");
				miu.addClass("action-activated").show();
				miu.children("input").attr("checked", "checked").prop("checked", true);
				
				var mu = p2.hasClass("action-multiple-update") ? p2 : p;
				copyActionToAnotherAction(mu, miu);
				
				//prepare multiple-insert and multiple-update
				p2.hide().removeClass("action-activated").children("input").removeAttr("checked").prop("checked", false);
				p.hide().removeClass("action-activated").children("input").removeAttr("checked").prop("checked", false);
				
				if (p.children(".action-options").css("display") != "none")
					toggleFormWizardActionOptions( p.children(".toggle")[0] );	
				
				if (p2.children(".action-options").css("display") != "none")
					toggleFormWizardActionOptions( p2.children(".toggle")[0] );	
			}
		}
	}
	else {
		p.removeClass("action-activated");
		
		if (p.hasClass("action-multiple-insert-update")) {
			p.hide();
			p.parent().children(".action-multiple-insert, .action-multiple-update").show();
			copyActionToAnotherAction(p, p.parent().children(".action-multiple-update"));
		}
		
		if (p.children(".action-options").css("display") != "none")
			toggleFormWizardActionOptions( p.children(".toggle")[0] );	
	}
}

function copyActionToAnotherAction(action1, action2) {
	if (action1[0] && action2[0]) {
		var ao1 = action1.find(".action-options");
		var ao2 = action2.find(".action-options");
		
		ao2.find(".action-type select").val( ao1.find(".action-type select").val() );
		ao2.find(".ajax-url input").val( ao1.find(".ajax-url input").val() );
		ao2.find(".successful-msg-options .msg-type select").val( ao1.find(".successful-msg-options .msg-type select").val() );
		ao2.find(".successful-msg-options .msg-message input").val( ao1.find(".successful-msg-options .msg-message input").val() );
		ao2.find(".successful-msg-options .msg-redirect-url input").val( ao1.find(".successful-msg-options .msg-redirect-url input").val() );
		ao2.find(".unsuccessful-msg-options .msg-type select").val( ao1.find(".unsuccessful-msg-options .msg-type select").val() );
		ao2.find(".unsuccessful-msg-options .msg-message input").val( ao1.find(".unsuccessful-msg-options .msg-message input").val() );
		ao2.find(".unsuccessful-msg-options .msg-redirect-url input").val( ao1.find(".unsuccessful-msg-options .msg-redirect-url input").val() );
		
		toggleFormWizardActionTypeOptions( ao2.find(".action-type select")[0] );
	}
}

function onChangeDALBrokerFormWizard(elm) {
	elm = $(elm);
	
	var selected_broker = elm.val();
	var select = elm.parent().children(".db-driver");
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
	var selected_broker = p.children(".dal-broker").val();
	var selected_driver = p.children(".db-driver").val(); //bc elm it could be .db-driver or .db-type
	selected_driver = selected_driver ? selected_driver : default_db_driver;
	var selected_type = p.children(".db-type").val();
	var select = p.children(".db-table");
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
		p.children(".table-options").hide();
}

function onChangeDBTableFormWizard(elm) {
	elm = $(elm);
	
	var p = elm.parent();
	var selected_broker = p.children(".dal-broker").val();
	var selected_driver = p.children(".db-driver").val(); //bc elm it could be .db-driver or .db-type
	selected_driver = selected_driver ? selected_driver : default_db_driver;
	var selected_type = p.children(".db-type").val();
	var selected_table = p.children(".db-table").val();
	
	getDBAttributes(selected_broker, selected_driver, selected_type, selected_table);
	
	var attributes = db_brokers_drivers_tables_attributes && db_brokers_drivers_tables_attributes[selected_broker] && db_brokers_drivers_tables_attributes[selected_broker][selected_driver] && db_brokers_drivers_tables_attributes[selected_broker][selected_driver][selected_type] ? db_brokers_drivers_tables_attributes[selected_broker][selected_driver][selected_type][selected_table] : null;
	
	var html = '';
	if (attributes)
		for (var idx in attributes) 
			html += '<li data-attr-name="' + attributes[idx] + '">'
				+ '<input class="attr-active" type="checkbox" onClick="activateDBActionTableAttribute(this)" />'
				+ '<label>' + attributes[idx] + '</label>'
				+ '<input class="attr-value" type="text" title="Write the value here" />'
				+ '<input class="attr-name" type="text" title="Write the alias/label here" />'
			+ '</li>';
	
	var table_options = p.children(".table-options");
	table_options.find(" > .attributes > ul").html(html).find("input.attr-active").attr("checked", "checked").prop("checked", true).parent().addClass("attr-activated");
	table_options.find(" > .conditions > ul").html(html);
	
	if (!selected_table)
		table_options.hide();
}

function onGroupConditionTypeChange(elm) {
	elm = $(elm);
	var selection = elm.val();
	
	var condition_value = elm.parent().children(".condition-value");
	condition_value.hide();
	
	switch (selection) {
		case "execute_if_var":
		case "execute_if_not_var":
			condition_value.show().attr("placeHolder", "Variable name");
			break;
			
		case "execute_if_post_button":
		case "execute_if_not_post_button":
		case "execute_if_get_button":
		case "execute_if_not_get_button":
			condition_value.show().attr("placeHolder", "Button name");
			break;
			
		case "execute_if_condition":
		case "execute_if_not_condition":
			condition_value.show().attr("placeHolder", "Condition code");
			break;
			
		case "execute_if_code":
		case "execute_if_not_code":
			condition_value.show().attr("placeHolder", "Condition Code");
			break;
	}
}

/* ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback FUNCTIONS */

function onFormModuleDataBaseActionTableProgrammingTaskChooseCreatedVariable(elm) {
	elm = $(elm);
	var p = elm.parent();
	
	if (p.children("input.attr-name").is(":visible"))
		elm.attr("input_selector", "input.attr-name");
	else
		elm.attr("input_selector", "input.attr-value");
	
	onFormModuleProgrammingTaskChooseCreatedVariable(elm);
}
function onFormModuleProgrammingTaskChooseCreatedVariable(elm) {
	elm = $(elm);
	var target_field = getTargetFieldForProgrammingTaskChooseFromFileManager(elm);
	
	if (target_field[0]) {
		var popup = $("#choose_property_variable_from_file_manager");
		
		//hide option 2 bc it doesn't matter
		popup.find(" > .type > select > option[value=class_prop_var]").hide();
		
		popup.children(".type").show();
		onChangePropertyVariableType( popup.find(".type select")[0] );
		
		MyFancyPopup.init({
			elementToShow: popup,
			parentElement: document,
			onOpen: function() {
				//bc this can be a little bit slow if the forms has several groups, we add a timeout to happen assynchronously
				setTimeout(function() {
					//update ProgrammingTaskUtil.variables_in_workflow
					updateFormModuleProgrammingTaskVariablesInWorkflow(popup);
				}, 100);
			},
			
			targetField: target_field[0],
			updateFunction: function(other_elm) {
				var type = popup.find(".type select").val();
				var type_elm = popup.find("." + type);
				var value = null;
				
				if (type == "existent_var")
					value = type_elm.find(".variable select").val();
				else if (type) {
					value = type_elm.find("input").val();
					value = ("" + value).replace(/^\s+/g, "").replace(/\s+$/g, "");
					
					if (value) {
						value = value[0] == '$' ? value.substr(1, value.length) : value; //remove $ if exists
						
						if (type == "new_var")
							value = getNewVarWithSubGroupsInProgrammingTaskChooseCreatedVariablePopup(type_elm, value, false);
					}
				}
				
				if (value) {
					target_field.val(value ? "#" + value + "#" : "");
					target_field.parent().parent().find(".var_type select").val("string");
					
					//set value_type if exists and if only input name is simple without "]" and "[" chars:
					var target_field_name = target_field.attr("name");
					
					if (target_field_name && !target_field_name.match(/[\[\]]/)) {
						var target_field_type = target_field.parent().children("select[name=" + target_field_name + "_type]");
						
						if (target_field_type[0])
							target_field_type.val("string");
					}
					else if (target_field.is(".value") && target_field.parent().is(".item")) //in case of array items
						target_field.parent().children(".value_type").val("");
					
					MyFancyPopup.hidePopup();
				}
				else
					alert("No variable selected!");
			},
		});
		
		MyFancyPopup.showPopup();
	}
	else
		StatusMessageHandler.showMessage("No targeted field found!");
}

function updateFormModuleProgrammingTaskVariablesInWorkflow(popup) {
	//update ProgrammingTaskUtil.variables_in_workflow
	var module_form_contents = $(".module_form_settings .module_form_contents");
	var active_tab = module_form_contents.tabs('option', 'active');
	var select = popup.find(".existent_var .variable select");
	
	//show loading bar
	showLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
	
	ProgrammingTaskUtil.variables_in_workflow = {};
	
	if (active_tab == 0) {
		var inputs = module_form_contents.find(" > #groups_flow .form-groups .form-group-item:not(form-group-default) > .form-group-header > .result-var-name");
		
		$.each(inputs, function(idx, input) {
			input = $(input);
			var var_name = input.val();
			var_name = var_name ? var_name.replace(/^\s+/g, "").replace(/\s+$/g, "") : "";
			
			if (var_name != "") {
				ProgrammingTaskUtil.variables_in_workflow[var_name] = {};
				
				//update sub vars if var is a composed var
				var form_group_header = input.parent();
				var form_group_item = form_group_header.parent();
				var action_type = form_group_header.children(".action-type").val();
				
				if (action_type == "select" || action_type == "callbusinesslogic" || action_type == "callibatisquery" || action_type == "callhibernatemethod" || action_type == "getquerydata" || action_type == "setquerydata" || action_type == "restconnector" || action_type == "soapconnector" || action_type == "array") {
					var item_settings = getModuleFormSettingsFromItemsToSave(form_group_item, {ignore_errors: true});
					updateFormModuleProgrammingTaskVariablesInWorkflowBasedInItemSettings(select, var_name, item_settings ? item_settings[0] : null);
				}
			}
		});
	}
	else {
		var tasks_properties = jsPlumbWorkFlow.jsPlumbTaskFlow.tasks_properties;
		
		if (tasks_properties)
			$.each(tasks_properties, function(idx, task_properties) {
				var var_name = task_properties && task_properties["properties"] ? task_properties["properties"]["result_var_name"] : "";
				var_name = var_name ? var_name.replace(/^\s+/g, "").replace(/\s+$/g, "") : "";
				
				if (var_name != "") {
					ProgrammingTaskUtil.variables_in_workflow[var_name] = {};
					
					//update sub vars if var is a composed var
					updateFormModuleProgrammingTaskVariablesInWorkflowBasedInItemSettings(select, var_name, task_properties["properties"]);
				}
			});
	}
	
	//hide loading bar
	hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
	
	//update select field from ProgrammingTaskUtil.variables_in_workflow
	populateVariablesOfTheWorkflowInSelectField(select);
}

function showLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select) {
	window.variables_in_workflow_loading_processes = 0;
	var p = select.parent();
	var loading = p.children(".loading");
	
	if (!loading[0]) {
		loading = $('<span class="icon loading"></span>');
		p.append(loading);
	}
	else
		loading.show();
}

function hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select) {
	if (window.variables_in_workflow_loading_processes == 0)
		select.parent().children(".loading").remove();
}

function updateFormModuleProgrammingTaskVariablesInWorkflowBasedInItemSettings(select, var_name, item_settings) {
	if (item_settings && $.isPlainObject(item_settings)) {
		var action_type = item_settings["action_type"];
		var action_value = item_settings["action_value"];
		
		if ($.isPlainObject(action_value) || $.isArray(action_value))
			switch (action_type) {
				case "select":
					if (action_value["attributes"]) {
						$.each(action_value["attributes"], function(idx, attr) {
							var column = attr["name"] ? attr["name"] : attr["column"];
							
							if (column && column != "*")
								ProgrammingTaskUtil.variables_in_workflow[var_name + "[" + column + "]"] = {};
						});
					}
					else if (action_value["sql"])
						updateFormModuleProgrammingTaskVariablesInWorkflowBasedInSQL(select, var_name, action_value["sql"]);
					
					break;
				case "callbusinesslogic":
					updateFormModuleProgrammingTaskVariablesInWorkflowBasedInBusinessLogicService(select, var_name, action_value);
					break;
				case "callibatisquery":
					updateFormModuleProgrammingTaskVariablesInWorkflowBasedInIbatisQuery(select, var_name, action_value);
					break;
				case "callhibernatemethod":
					updateFormModuleProgrammingTaskVariablesInWorkflowBasedInHibernateMethod(select, var_name, action_value);
					break;
				case "getquerydata":
					updateFormModuleProgrammingTaskVariablesInWorkflowBasedInSQL(select, var_name, action_value["sql"]);
					break;
				case "setquerydata":
					updateFormModuleProgrammingTaskVariablesInWorkflowBasedInSQL(select, var_name, action_value["sql"]);
					break;
				case "restconnector":
					updateFormModuleProgrammingTaskVariablesInWorkflowBasedInRestConnector(select, var_name, action_value);
					break;
				case "soapconnector":
					updateFormModuleProgrammingTaskVariablesInWorkflowBasedInSoapConnector(select, var_name, action_value);
					break;
				case "array":
					var count = 0;
					
					$.each(action_value, function(idx, item) {
						if ($.isPlainObject(item)) {
							if (item["key"] && ($.isNumeric(item["key"]) || item["key_type"] == "string" || item["key_type"] == "null")) {
								if (item["key_type"] == "null")
									ProgrammingTaskUtil.variables_in_workflow[var_name + "[" + count + "]"] = {};
								else
									ProgrammingTaskUtil.variables_in_workflow[var_name + "[" + item["key"] + "]"] = {};
							}
							
							if (item["key_type"] == "null")
								count++;
						}
					});
					break;
			}
	}
}

function updateFormModuleProgrammingTaskVariablesInWorkflowBasedInBusinessLogicService(select, var_name, bl_settings) {
	if (bl_settings && bl_settings["service_id"]) {
		var broker_name = brokers_name_by_obj_code[ bl_settings["method_obj"] ];
		
		if (broker_name) {
			var broker_props = brokers_settings[broker_name];
			
			if (broker_props) {
				if (("" + bl_settings["service_id"]).indexOf(".get")) { //if a get method: like get, getAll, gets, getItem, getItems, getAllItems
					//http://jplpinto.localhost/__system/phpframework/businesslogic/get_business_logic_attributes?bean_name=SoaBLLayer&bean_file_name=soa_bll.xml&module_id=sample.test&service=ItemService.get
					var url = get_business_logic_result_properties_url.replace("#bean_file_name#", broker_props[1]).replace("#bean_name#", broker_props[2]).replace("#module_id#", bl_settings["module_id"]).replace("#service#", bl_settings["service_id"]);
					var url_id = $.md5(url);
					
					if (cached_data_for_variables_in_workflow.hasOwnProperty(url_id)) {
						var data = cached_data_for_variables_in_workflow[url_id];
						
						if(data && $.isPlainObject(data) && data["attributes"])
							$.each(data["attributes"], function(idx, attr) {
								var column = attr["column"];
								
								if (column)
									ProgrammingTaskUtil.variables_in_workflow[var_name + (data["is_multiple"] ? "[idx]" : "") + "[" + attr["column"] + "]"] = {};
							});
					}
					else {
						window.variables_in_workflow_loading_processes++;
						
						$.ajax({
							type : "get",
							url : url,
							dataType : "json",
							success : function(data, textStatus, jqXHR) {
								cached_data_for_variables_in_workflow[url_id] = data;
								
								if(data && $.isPlainObject(data) && data["attributes"]) {
									$.each(data["attributes"], function(idx, attr) {
										var column = attr["column"];
										
										if (column) 
											ProgrammingTaskUtil.variables_in_workflow[var_name + (data["is_multiple"] ? "[idx]" : "") + "[" + attr["column"] + "]"] = {};
									});
									
									populateVariablesOfTheWorkflowInSelectField(select);
								}
								
								window.variables_in_workflow_loading_processes--;
								hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
							},
							error : function(jqXHR, textStatus, errorThrown) { 
								window.variables_in_workflow_loading_processes--;
								hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
							},
						});
					}
				}
			}
		}
	}
}

function updateFormModuleProgrammingTaskVariablesInWorkflowBasedInIbatisQuery(select, var_name, ibatis_settings) {
	if (ibatis_settings && ibatis_settings["service_type"] == "select") {
		var broker_name = brokers_name_by_obj_code[ ibatis_settings["method_obj"] ];
		
		if (broker_name) {
			var broker_props = brokers_settings[broker_name];
			
			if (broker_props) {
				//http://jplpinto.localhost/__system/phpframework/dataaccess/get_query_attributes?bean_name=IormIDALayer&bean_file_name=iorm_dal.xml&module_id=sample.test&query_type=select&query=get_item
				var url = get_query_result_properties_url.replace("#bean_file_name#", broker_props[1]).replace("#bean_name#", broker_props[2]).replace("#module_id#", ibatis_settings["module_id"]).replace("#query_type#", ibatis_settings["service_type"]).replace("#query#", ibatis_settings["service_id"]);
				updateFormModuleProgrammingTaskVariablesInWorkflowBasedInDataAccess(select, var_name, url);
			}
		}
	}
}

function updateFormModuleProgrammingTaskVariablesInWorkflowBasedInHibernateMethod(select, var_name, hibernate_settings) {
	if (hibernate_settings) {
		var broker_name = brokers_name_by_obj_code[ hibernate_settings["method_obj"] ];
		
		if (broker_name) {
			var broker_props = brokers_settings[broker_name];
			
			if (broker_props) {
				var url = null;
				
				if (hibernate_settings["service_method"] == "callSelect") {
					//http://jplpinto.localhost/__system/phpframework/dataaccess/get_query_attributes?bean_name=HormHDALayer&bean_file_name=horm_dal.xml&db_driver=test&module_id=sample.test&query_type=select&query=get_items&obj=Item&relationship_type=queries
					url = get_query_result_properties_url.replace("#bean_file_name#", broker_props[1]).replace("#bean_name#", broker_props[2]).replace("#module_id#", hibernate_settings["module_id"]).replace("#query_type#", "select").replace("#query#", hibernate_settings["sma_query_id"]).replace("#obj#", hibernate_settings["service_id"]).replace("#relationship_type#", "queries");
				}
				else if (hibernate_settings["service_method"] == "callQuery" && hibernate_settings["sma_query_type"] == "select") {
					//http://jplpinto.localhost/__system/phpframework/dataaccess/get_query_attributes?bean_name=HormHDALayer&bean_file_name=horm_dal.xml&db_driver=test&module_id=sample.test&query_type=select&query=get_items&obj=Item&relationship_type=queries
					url = get_query_result_properties_url.replace("#bean_file_name#", broker_props[1]).replace("#bean_name#", broker_props[2]).replace("#module_id#", hibernate_settings["module_id"]).replace("#query_type#", hibernate_settings["sma_query_type"]).replace("#query#", hibernate_settings["sma_query_id"]).replace("#obj#", hibernate_settings["service_id"]).replace("#relationship_type#", "queries");
				}
				else if (hibernate_settings["service_method"] == "find" || hibernate_settings["service_method"] == "findById") {
					//http://jplpinto.localhost/__system/phpframework/dataaccess/get_query_attributes?bean_name=HormHDALayer&bean_file_name=horm_dal.xml&db_driver=test&module_id=sample.test&query=findById&obj=Item&relationship_type=native
					url = get_query_result_properties_url.replace("#bean_file_name#", broker_props[1]).replace("#bean_name#", broker_props[2]).replace("#module_id#", hibernate_settings["module_id"]).replace("#query#", hibernate_settings["service_method"]).replace("#obj#", hibernate_settings["service_id"]).replace("#relationship_type#", "native");
				}
				else if (hibernate_settings["service_method"] == "findRelationship") {
					//http://jplpinto.localhost/__system/phpframework/dataaccess/get_query_attributes?bean_name=HormHDALayer&bean_file_name=horm_dal.xml&db_driver=test&module_id=sample.test&query=findrelationship&rel_name=item_sub_item_childs&obj=Item&relationship_type=native
					url = get_query_result_properties_url.replace("#bean_file_name#", broker_props[1]).replace("#bean_name#", broker_props[2]).replace("#module_id#", hibernate_settings["module_id"]).replace("#query#", hibernate_settings["service_method"]).replace("#rel_name#", hibernate_settings["sma_rel_name"]).replace("#obj#", hibernate_settings["service_id"]).replace("#relationship_type#", "native");
				}
				
				updateFormModuleProgrammingTaskVariablesInWorkflowBasedInDataAccess(select, var_name, url);
			}
		}
	}
}

function updateFormModuleProgrammingTaskVariablesInWorkflowBasedInDataAccess(select, var_name, url) {
	if (url) {
		url = url.replace(/#[a-z0-9_\-]+#/gi, ""); //remove all #xxx# that were not replaced previously, otherwise the url will be shrinked because everything after # will be considered a hashtag.
		var url_id = $.md5(url);
		
		if (cached_data_for_variables_in_workflow.hasOwnProperty(url_id)) {
			var data = cached_data_for_variables_in_workflow[url_id];
			
			if(data && $.isPlainObject(data) && data["attributes"])
				$.each(data["attributes"], function(idx, attr) {
					var column = attr["name"] ? attr["name"] : attr["column"];
					
					if (column != "*") {
						if (data["is_single"] || !data["is_multiple"])
							ProgrammingTaskUtil.variables_in_workflow[var_name + "[" + attr["column"] + "]"] = {};
						
						if (data["is_multiple"])
							ProgrammingTaskUtil.variables_in_workflow[var_name + "[idx][" + attr["column"] + "]"] = {};
					}
				});
		}
		else {
			window.variables_in_workflow_loading_processes++;
			
			$.ajax({
				type : "get",
				url : url,
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					cached_data_for_variables_in_workflow[url_id] = data;
					
					if(data && $.isPlainObject(data) && data["attributes"]) {
						$.each(data["attributes"], function(idx, attr) {
							var column = attr["name"] ? attr["name"] : attr["column"];
							
							if (column != "*") {
								if (data["is_single"] || !data["is_multiple"])
									ProgrammingTaskUtil.variables_in_workflow[var_name + "[" + attr["column"] + "]"] = {};
								
								if (data["is_multiple"])
									ProgrammingTaskUtil.variables_in_workflow[var_name + "[idx][" + attr["column"] + "]"] = {};
							}
						});
						
						populateVariablesOfTheWorkflowInSelectField(select);
					}
					
					window.variables_in_workflow_loading_processes--;
					hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					window.variables_in_workflow_loading_processes--;
					hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
				},
			});
		}
	}
}

function updateFormModuleProgrammingTaskVariablesInWorkflowBasedInSQL(select, var_name, sql) {
	if (sql) {
		var sql_id = $.md5(sql);
		
		if (cached_data_for_variables_in_workflow.hasOwnProperty(sql_id)) {
			var data = cached_data_for_variables_in_workflow[sql_id];
			
			if(data && $.isPlainObject(data) && data["attributes"] && data["type"] == "select")
				$.each(data["attributes"], function(idx, attr) {
					var column = attr["name"] ? attr["name"] : attr["column"];
					
					if (column && column != "*") {
						ProgrammingTaskUtil.variables_in_workflow[var_name + "[" + column + "]"] = {};
						ProgrammingTaskUtil.variables_in_workflow[var_name + "[idx][" + column + "]"] = {};
					}
				});
		}
		else {
			window.variables_in_workflow_loading_processes++;
			
			//get selected db broker and db driver and add them to get_query_obj_from_sql
			var url = get_query_obj_from_sql.replace("#db_broker#", selected_db_broker ? selected_db_broker : "").replace("#db_driver#", selected_db_driver ? selected_db_driver : "");
			
			$.ajax({
				type : "post",
				url : url,
				data : {"sql" : sql},
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					cached_data_for_variables_in_workflow[sql_id] = data;
					
					if(data && $.isPlainObject(data) && data["attributes"] && data["type"] == "select") {
						$.each(data["attributes"], function(idx, attr) {
							var column = attr["name"] ? attr["name"] : attr["column"];
							
							if (column && column != "*") {
								ProgrammingTaskUtil.variables_in_workflow[var_name + "[" + column + "]"] = {};
								ProgrammingTaskUtil.variables_in_workflow[var_name + "[idx][" + column + "]"] = {};
							}
						});
						
						populateVariablesOfTheWorkflowInSelectField(select);
					}
					
					window.variables_in_workflow_loading_processes--;
					hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					window.variables_in_workflow_loading_processes--;
					hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
				},
			});
		}
	}
}

function updateFormModuleProgrammingTaskVariablesInWorkflowBasedInRestConnector(select, var_name, rest_settings) {
	var allowed_result_types = ["content", "content_json", "content_xml", "content_serialized"];
	
	if (rest_settings && $.inArray(rest_settings["result_type"], allowed_result_types) != -1) {
		var post_data = {"action_type" : "restconnector", "action_value": rest_settings};
		var post_data_id = $.md5(JSON.stringify(post_data));
		
		if (cached_data_for_variables_in_workflow.hasOwnProperty(post_data_id)) {
			var data = cached_data_for_variables_in_workflow[post_data_id];
			
			if (data && $.isPlainObject(data) && data["attributes"])
				$.each(data["attributes"], function(idx, attr) {
					var column = attr["column"];
					
					if (column) 
						ProgrammingTaskUtil.variables_in_workflow[var_name + (data["is_multiple"] ? "[idx]" : "") + "[" + attr["column"] + "]"] = {};
				});
		}
		else {
			window.variables_in_workflow_loading_processes++;
			//samples: 
			//- https://postman-echo.com/get?test=123
			//- http://localhost/samples/books.xml
			//- http://localhost/samples/book.xml
			//- http://localhost/samples/books.json
			//- http://localhost/samples/book.json
			
			$.ajax({
				type : "post",
				url : get_form_action_result_properties_url,
				data : post_data,
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					cached_data_for_variables_in_workflow[post_data_id] = data;
					
					if (data && $.isPlainObject(data) && data["attributes"]) {
						$.each(data["attributes"], function(idx, attr) {
							var column = attr["column"];
							
							if (column) 
								ProgrammingTaskUtil.variables_in_workflow[var_name + (data["is_multiple"] ? "[idx]" : "") + "[" + attr["column"] + "]"] = {};
						});
						
						populateVariablesOfTheWorkflowInSelectField(select);
					}
					
					window.variables_in_workflow_loading_processes--;
					hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					window.variables_in_workflow_loading_processes--;
					hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
				},
			});
		}
	}
}

function updateFormModuleProgrammingTaskVariablesInWorkflowBasedInSoapConnector(select, var_name, soap_settings) {
	//console.log(soap_settings);
	var allowed_types = ["callSoapClient", "callSoapFunction"];
	
	if (soap_settings && $.isPlainObject(soap_settings["data"]) && $.inArray(soap_settings["data"]["type"], allowed_types) != -1) {
		var allowed_result_types = ["content", "content_json", "content_xml", "content_serialized"];
		
		if (soap_settings["data"]["type"] != "callSoapFunction" || $.inArray(soap_settings["result_type"], allowed_result_types) != -1) {
			var post_data = {"action_type" : "soapconnector", "action_value": soap_settings};
			var post_data_id = $.md5(JSON.stringify(post_data));
			
			if (cached_data_for_variables_in_workflow.hasOwnProperty(post_data_id)) {
				var data = cached_data_for_variables_in_workflow[post_data_id];
				
				if (data && $.isPlainObject(data)) {
					if (soap_settings["data"]["type"] == "callSoapClient") {
						if (data["functions"] && $.isArray(data["functions"]))
							$.each(data["functions"], function(idx, func) {
								if (func["name"])
									ProgrammingTaskUtil.variables_in_workflow[var_name + "[" + func["name"] + "]"] = {};
							});
					}
					else if (data["attributes"]) {
						$.each(data["attributes"], function(idx, attr) {
							var column = attr["column"];
							
							if (column) 
								ProgrammingTaskUtil.variables_in_workflow[var_name + (data["is_multiple"] ? "[idx]" : "") + "[" + attr["column"] + "]"] = {};
						});
					}
				}
			}
			else {
				window.variables_in_workflow_loading_processes++;
				//samples: 
				//- http://localhost/samples/soap.wsdl
				
				$.ajax({
					type : "post",
					url : get_form_action_result_properties_url,
					data : post_data,
					dataType : "json",
					success : function(data, textStatus, jqXHR) {
						//console.log(data);
						cached_data_for_variables_in_workflow[post_data_id] = data;
						
						if (data && $.isPlainObject(data)) {
							if (soap_settings["data"]["type"] == "callSoapClient") {
								if (data["functions"] && $.isArray(data["functions"]))
									$.each(data["functions"], function(idx, func) {
										if (func["name"])
											ProgrammingTaskUtil.variables_in_workflow[var_name + "[" + func["name"] + "]"] = {};
									});
							}
							else if (data["attributes"]) {
								$.each(data["attributes"], function(idx, attr) {
									var column = attr["column"];
									
									if (column) 
										ProgrammingTaskUtil.variables_in_workflow[var_name + (data["is_multiple"] ? "[idx]" : "") + "[" + attr["column"] + "]"] = {};
								});
							}
							
							populateVariablesOfTheWorkflowInSelectField(select);
						}
						
						window.variables_in_workflow_loading_processes--;
						hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
					},
					error : function(jqXHR, textStatus, errorThrown) { 
						window.variables_in_workflow_loading_processes--;
						hideLoadingBarInFormModuleProgrammingTaskVariablesInWorkflow(select);
					},
				});
			}
		}
	}
}

/* SAVE FUNCTIONS */

function getModuleFormSettingsFromItemsToSave(items, options) {
	var actions_settings = [];
	var ignore_errors = options && $.isPlainObject(options) && options["ignore_errors"];
	
	$.each(items, function(idx, item) {
		item = $(item);
		var header = item.children(".form-group-header");
		var result_var_name = header.children(".result-var-name");
		var action_type = header.children(".action-type");
		var sub_header = header.children(".form-group-sub-header");
		var condition_type = sub_header.children(".condition-type");
		var condition_value = sub_header.children(".condition-value");
		var action_description = sub_header.find(" > .action-description > textarea");
		var group_body = item.children(".form-group-body");
		
		var selection = action_type.val();
		var is_selection_undefined = action_type.find("option:selected").attr("undefined");
		
		var item_settings = {
			"result_var_name": result_var_name.val(), 
			"action_type": selection, 
			"condition_type": condition_type.val(), 
			"condition_value": condition_value.val(),
			"action_description": action_description.val(),
			"action_value": {},
		};
		
		if (is_selection_undefined) {
			var v = group_body.children(".undefined-action-value").val();
			item_settings["action_value"] = v ? JSON.parse(v) : {};
		}
		else {
			switch (selection) {
				case "html": //getting design form html settings
					var section = group_body.children(".html-action-body");
					var create_form_task_html = section.children(".create_form_task_html");
					
					item_settings["action_value"]["form_settings_data_type"] = create_form_task_html.find(".form_settings select").val();
					
					if (item_settings["action_value"]["form_settings_data_type"] == "array") {
						var form_settings_data = parseArray( create_form_task_html.children(".form_settings_data") );
						item_settings["action_value"]["form_settings_data"] = form_settings_data["form_settings_data"];
					}
					else if (item_settings["action_value"]["form_settings_data_type"] == "settings") {
						CreateFormTaskPropertyObj.prepareCssAndJsFieldsToSave(create_form_task_html);
						
						var form_settings_data = FormFieldsUtilObj.convertFormSettingsDataSettingsToArray( create_form_task_html.children(".inline_settings") );
						ArrayTaskUtilObj.onLoadArrayItems( create_form_task_html.children(".form_settings_data"), form_settings_data, "");
						
						var form_settings_data = parseArray( create_form_task_html.children(".form_settings_data") );
						item_settings["action_value"]["form_settings_data"] = form_settings_data["form_settings_data"];
						item_settings["action_value"]["form_settings_data_type"] = "array";
					}
					else if (item_settings["action_value"]["form_settings_data_type"] == "ptl") {
						var ptl_settings = create_form_task_html.find(".ptl_settings");
						var code = getPtlElementTemplateSourceEditorValue(ptl_settings, true);
						var external_vars = {};
						
						$.each( ptl_settings.find(" > .ptl_external_vars .item"), function (idx, item) {
							item = $(item);
							var k = item.children(".key").val();
							var v = item.children(".value").val();
							
							if (k && v)
								external_vars[ k.charAt(0) == "$" ? k.substr(1) : k ] = v.charAt(0) == "$" ? v : "$" + v;
						});
						
						item_settings["action_value"]["form_settings_data"] = {"ptl" : {
							"code" : code,
							"input_data_var_name" : ptl_settings.find(" > .input_data_var_name > input").val(),
							"idx_var_name" : ptl_settings.find(" > .idx_var_name > input").val(),
							"external_vars" : external_vars,
						}};
						item_settings["action_value"]["form_settings_data_type"] = "ptl";
					}
					else
						item_settings["action_value"]["form_settings_data"] = create_form_task_html.find(".form_settings input").val();
					
					break;

				case "callbusinesslogic":
				case "callibatisquery":
				case "callhibernatemethod":
				case "getquerydata":
				case "setquerydata":
				case "callfunction":
				case "callobjectmethod":
				case "restconnector":
				case "soapconnector":
					//getting brokers settings
					var section = group_body.children(".broker-action-body");
					
					item_settings["action_value"] = getBrokerSettings(section, selection);
					break;
					
				case "insert":
				case "update":
				case "delete":
				case "select":
				case "procedure":
				case "getinsertedid":
					var section = group_body.children(".database-action-body");
					
					//get header fields
					item_settings["action_value"]["dal_broker"] = section.find(".dal-broker select").val();
					item_settings["action_value"]["db_driver"] = section.find(".db-driver select").val();
					item_settings["action_value"]["db_type"] = section.find(".db-type select").val();
					
					if (selection != "getinsertedid") {
						//get table or sql fields
						var select_tab_index = section.find(".query").tabs("option", "active");
						
						if (select_tab_index == 0) {
							var table = section.find(".database-action-table > .table > select").val();
							
							item_settings["action_value"]["table"] = table;
							
							if (table != "") {
								var attributes = [];
								var conditions = [];
								
								$.each( section.find(".database-action-table > .attributes > ul > li"), function (idx, li) {
									li = $(li);
									
									if (li.children(".attr-active").is(":checked"))
										attributes.push({
											"column": li.attr("data-attr-name"),
											"value": li.children(".attr-value").val(),
											"name": li.children(".attr-name").val()
										});
								});
								
								$.each( section.find(".database-action-table > .conditions > ul > li"), function (idx, li) {
									li = $(li);
									
									if (li.children(".attr-active").is(":checked"))
										conditions.push({
											"column": li.attr("data-attr-name"),
											"value": li.children(".attr-value").val()
										});
								});
								
								item_settings["action_value"]["attributes"] = attributes;
								item_settings["action_value"]["conditions"] = conditions;
							}
							else if (!ignore_errors) {
								var label = action_type.find("option:selected").text();
								StatusMessageHandler.showError("'" + label + "' group cannot be empty!");
								MyFancyPopup.hidePopup();
								return;
							}
							
						}
						else {
							var rel = getUserRelationshipObj( section.find(".relationship") );
							var sql_type = rel[0] ? rel[0].toLowerCase() : "";
							var sql = rel[1] && rel[1]["value"] ? rel[1]["value"] : "";
							
							if (sql != "" && sql_type && sql_type != selection)
								item_settings["action_type"] = sql_type;
							
							item_settings["action_value"]["sql"] = sql;
							
							if (sql == "" && !ignore_errors) {
								var label = action_type.find("option:selected").text();
								StatusMessageHandler.showError("'" + label + "' group cannot be empty!");
								MyFancyPopup.hidePopup();
								return;
							}
						}
					}
					
					//get footer fields
					var opts = section.find(" > footer > .opts");
					item_settings["action_value"]["options_type"] = opts.children(".options_type").val();
					
					if (item_settings["action_value"]["options_type"] == "array") {
						var aux = parseArray( opts.children(".options") );
						item_settings["action_value"]["options"] = aux["options"];
					}
					else
						item_settings["action_value"]["options"] = opts.children(".options_code").val();
					
					break;
					
				case "show_ok_msg":
				case "show_ok_msg_and_stop":
				case "show_ok_msg_and_die":
				case "show_ok_msg_and_redirect":
				case "show_error_msg":
				case "show_error_msg_and_stop":
				case "show_error_msg_and_die":
				case "show_error_msg_and_redirect":
				case "alert_msg":
				case "alert_msg_and_stop":
				case "alert_msg_and_redirect":
					var section = group_body.children(".message-action-body");
					
					item_settings["action_value"]["message"] = section.find(" > .message > input").val();
					item_settings["action_value"]["redirect_url"] = section.find(" > .redirect-url > input").val();
					break;
					
				case "redirect": //getting redirect settings
					var section = group_body.children(".redirect-action-body");
					
					item_settings["action_value"] = section.children("input").val();
					break;
				
				case "return_previous_record":
				case "return_next_record":
				case "return_specific_record":
					var section = group_body.children(".records-action-body");
					
					item_settings["action_value"]["records_variable_name"] = section.find(" > .records-variable-name > input").val();
					item_settings["action_value"]["index_variable_name"] = section.find(" > .index-variable-name > input").val();
					break;
				
				case "check_logged_user_permissions":
					var section = group_body.children(".check-logged-user-permissions-action-body");
					var user_types = section.find(" > .users-perms > table > tbody .user-type-id select");
					
					item_settings["action_value"] = {
						"all_permissions_checked": section.find(" > .all-permissions-checked > input").is(":checked") ? 1 : 0,
						"entity_path_var_name": section.find(" > .entity-path-var-name").val(),
						"logged_user_id": section.find(" > .logged-user-id > input").val(),
						"users_perms": []
					};
					
					$.each(user_types, function(idx, user_type) {
						user_type = $(user_type);
						
						item_settings["action_value"]["users_perms"].push({
							"user_type_id": user_type.val(),
							"activity_id": user_type.parent().closest("tr").find(".activity-id select").val()
						});
					});
					
					break;
								
				case "code": //getting code settings
					var section = group_body.children(".code-action-body");
					var editor = section.data("editor");
					
					item_settings["action_value"] = editor ? editor.getValue() : section.children("textarea").val();
					break;
					
				case "array": //getting array settings
					var section = group_body.children(".array-action-body");
					var aux = parseArray(section);
					
					item_settings["action_value"] = aux["array-action-body"];
					break;
					
				case "string": //getting string settings
					var section = group_body.children(".string-action-body");
					
					item_settings["action_value"] = section.children("input").val();
					break;
					
				case "variable": //getting variable settings
					var section = group_body.children(".variable-action-body");
					
					item_settings["action_value"] = section.children("input").val();
					break;
					
				case "sanitize_variable": //getting variable settings
					var section = group_body.children(".sanitize-variable-action-body");
					
					item_settings["action_value"] = section.children("input").val();
					break;
					
				case "list_report": //getting variable settings
					var section = group_body.children(".list-report-action-body");
					
					item_settings["action_value"] = {
						"type": section.find(".type > select").val(),
						"doc_name": section.find(".doc_name > input").val(),
						"variable": section.find(".variable > input").val(),
						"continue": section.find(".continue > select").val(),
					};
					break;
					
				case "call_block": //getting variable settings
					var section = group_body.children(".call-block-action-body");
					
					item_settings["action_value"] = {
						"block": section.find(".block > input").val(),
						"project": section.find(".project > select").val(),
					};
					break;
					
				case "include_file": //getting include_file settings
					var section = group_body.children(".include-file-action-body");
					
					item_settings["action_value"] = {
						"path": section.children("input.path").val(),
						"once": section.children("input.once").is(":checked") ? 1 : 0,
					};
					break;
				
				case "draw_graph": //getting draw_graph settings
					var section = group_body.children(".draw-graph-action-body");
					var is_code = section.tabs("option", "active") == 1;
					
					if (is_code) {
						var editor = section.children(".draw-graph-js-code").data("editor");
						
						item_settings["action_value"] = {
							"code": editor ? editor.getValue() : sub_section.children("textarea").val(),
						};
					}
					else {
						var sub_section = section.children(".draw-graph-settings");
						var lis = sub_section.find(".graph-data-sets > ul > li:not(.no-data-sets)");
						var data_sets = [];
						
						$.each(lis, function(idx, li) {
							li = $(li);
							
							var data_set_options = {
								"type": li.find(".type select").val(),
								"item_label": li.find(".item-label input").val(),
								"values_variable": li.find(".values-variable input").val(),
								"background_colors": li.find(".background-colors input").val(),
								"border_colors": li.find(".border-colors input").val(),
								"border_width": li.find(".border-width input").val()
							};
							
							var other_options = li.find(".other-options > ul > li");
							$.each(other_options, function(idy, other_option) {
								other_option = $(other_option);
								var option_name = other_option.find(".option-name").val();
								
								if (option_name)
									data_set_options[option_name] = other_option.find(".option-value").val();
							});
							
							data_sets.push(data_set_options);
						});
						
						item_settings["action_value"] = {
							"include_graph_library": sub_section.find(".include-graph-library select").val(),
							"width": sub_section.find(".graph-width input").val(),
							"height": sub_section.find(".graph-height input").val(),
							"labels_and_values_type": sub_section.find(".labels-and-values-type select").val(),
							"labels_variable": sub_section.find(".labels-variable input").val(),
							
							"data_sets": data_sets
						};
					}
					break;
				
				case "loop": //getting loop settings
					var section = group_body.children(".loop-action-body");
					var header = section.children("header");
					var loop_items = section.find(" > .form-sub-groups > .form-group-item");
					
					item_settings["action_value"] = {
						"records_variable_name": header.find(".records-variable-name input").val(),
						"records_start_index": header.find(".records-start-index input").val(),
						"records_end_index": header.find(".records-end-index input").val(),
						"array_item_key_variable_name": header.find(".array-item-key-variable-name input").val(),
						"array_item_value_variable_name": header.find(".array-item-value-variable-name input").val(),
						"actions": getModuleFormSettingsFromItemsToSave(loop_items),
					};
					break;
					
				case "group": //getting group settings
					var section = group_body.children(".group-action-body");
					var header = section.children("header");
					var group_items = section.find(" > .form-sub-groups > .form-group-item");
					
					item_settings["action_value"] = {
						"group_name": header.find(".group-name input").val(),
						"actions": getModuleFormSettingsFromItemsToSave(group_items),
					};
					break;
			}
		}
		
		actions_settings.push(item_settings);
	});
	
	return actions_settings;
}

function getModuleFormContentsSettings(module_form_contents) {
	var items = module_form_contents.find("#groups_flow > .form-groups > .form-group-item:not(.form-group-default)");
	var actions_settings = getModuleFormSettingsFromItemsToSave(items);
	
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
	
	if (task_flow_tab_openned_by_user) {
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
							var add_group_icon = module_form_settings.find("#groups_flow > nav > .add_form_group")[0];
							
							//remove old groups
							module_form_settings.find("#groups_flow > .form-groups > .form-group-item").not(".form-group-default").remove();
							
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
							$("#groups_flow_tab a").first().click();
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

function saveModuleFormSettings(button) {
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var module_form_settings = $(".module_form_settings");
	var settings = getModuleFormSettings(module_form_settings);
	//console.log(settings);
	
	if (!$.isPlainObject(settings) || !$.isArray(settings["actions"])) {
		if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
			showAjaxLoginPopup(jquery_native_xhr_object.responseURL, [ create_workflow_file_from_settings_url, jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url ], function() {
				jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
				StatusMessageHandler.removeLastShownMessage("error");
				saveModuleFormSettings(button);
			});
		else
			StatusMessageHandler.showError("Error trying to get form settings actions.\nPlease try again...");
		
		return;
	}
	
	$.ajax({
		type : "post",
		url : create_form_settings_code_url,
		data : {"settings" : settings},
		dataType : "json",
		success : function(data, textStatus, jqXHR) {
			if (data && data["code"])
				saveBlockRawCode(data["code"]);
			else
				StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
			
			MyFancyPopup.hidePopup();
		},
		error : function() { 
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_form_settings_code_url, function() {
					jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
					StatusMessageHandler.removeLastShownMessage("error");
					saveModuleFormSettings(button);
				});
			else
				StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
			
			MyFancyPopup.hidePopup();
		},
	});
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
