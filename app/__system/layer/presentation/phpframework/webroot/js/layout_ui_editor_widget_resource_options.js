/* LAYOUTUIEDITOR FUNCTIONS */
var creating_resources = {};

function initLayoutUIEditorWidgetResourceOptions(PtlLayoutUIEditor) {
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.toggle_choose_db_table_attribute_popup_func = toggleChooseLayoutUIEditorWidgetResourceDBTableAttributePopup;
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.toggle_choose_widget_resource_popup_func = function(elm, widget, handler) {
		toggleChooseLayoutUIEditorWidgetResourceValueAttributePopup(elm, widget, handler, PtlLayoutUIEditor, false);
	};
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.toggle_choose_widget_resource_value_attribute_popup_func = function(elm, widget, handler) {
		toggleChooseLayoutUIEditorWidgetResourceValueAttributePopup(elm, widget, handler, PtlLayoutUIEditor, true);
	};
	
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.get_db_brokers_func = getLayoutUIEditorWidgetResourceDBBrokers;
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.get_db_drivers_func = getLayoutUIEditorWidgetResourceDBDrivers;
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.get_db_tables_func = getLayoutUIEditorWidgetResourceDBTables;
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.get_db_attributes_func = getLayoutUIEditorWidgetResourceDBAttributes;
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.get_resources_references_func = getLayoutUIEditorWidgetResourceResourcesReferences;
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.get_user_types_func = getLayoutUIEditorWidgetResourceUserTypes;
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.get_php_numeric_types_func = getLayoutUIEditorWidgetResourcePHPNumericTypes;
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.get_db_numeric_types_func = getLayoutUIEditorWidgetResourceDBNumericTypes;
	
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.add_sla_resource_func = addLayoutUIEditorWidgetResourceSLAResource;
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.remove_sla_resource_func = removeLayoutUIEditorWidgetResourceSLAResource;
	PtlLayoutUIEditor.LayoutUIEditorWidgetResource.options.create_resource_names_func = createLayoutUIEditorWidgetResourceSLAResourceNames;
}

function toggleChooseLayoutUIEditorWidgetResourceValueAttributePopup(elm, widget, handler, PtlLayoutUIEditor, show_resource_attributes) {
	var popup = $("#choose_widget_resource_value_attribute");
	
	if (!popup[0]) {
		html = '<div id="choose_widget_resource_value_attribute" class="myfancypopup choose_widget_resource_value_attribute">'
				+ '<div class="title"></div>'
				+ '<ul class="tabs tabs_transparent tabs_right">'
					+ '<li class="existent_resource_attribute_tab"><a href="#existent_resource_attribute">Based in Existent Resource</a></li>'
					+ '<li class="new_resource_attribute_tab"><a href="#new_resource_attribute">Based in DB Table Attribute</a></li>'
				+ '</ul>'
				+ '<div id="existent_resource_attribute">'
					+ '<ul>'
						+ '<li class="empty_items">There are no available resources...</li>'
					+ '</ul>' //show all existent resources with correspondent attributes
				+ '</div>'
				+ '<div id="new_resource_attribute">'
					+ $("#choose_db_table_or_attribute > .contents").html()
					+ '<div class="query_type">'
						+ '<label>Query Type:</label>'
						+ '<select>' //options must be the same than the action_type in LayoutUIEditorWidgetResource.js
							+ '<option value="get_all">Get multiple records list</option>'
							+ '<option value="get">Get a specific record</option>'
						+ '</select>'
					+ '</div>'
					+ '<div class="row_index">'
						+ '<label>Row Index:</label>'
						+ '<input placeHolder="Leave it blank for automatic">'
					+ '</div>'
					+ '<div class="query_conditions">'
						+ '<label>Query Conditions:</label>'
						+ '<ul>'
							+ '<li class="empty_items">There are no table attributes...</li>'
						+ '</ul>' //show all the attributes from the selected table so we can create a condition
					+ '</div>'
				+ '</div>'
				+ '<div class="button">'
					+ '<input type="button" value="update" />'
				+ '</div>'
			+ '</div>';
		
		popup = $(html);
		
		popup.tabs();
		popup.find(" > #new_resource_attribute > .db_attribute").show();
		popup.find(" > #new_resource_attribute > .db_table > select").attr("onChange", "updateChooseLayoutUIEditorWidgetResourceValueDBAttributes(this)");
		
		popup.find(" > .tabs > li > a").on("click", function(event) {
			popup.css("height", "");
			
			setTimeout(function() {
				MyFancyPopup.updatePopup();
			}, 300);
		});
		
		popup.find(" > #new_resource_attribute > .query_type > select").on("change", function(event) {
			var lis = popup.find(" > #new_resource_attribute > .query_conditions > ul > li:not(.empty_items):not(.primary_key)");
			
			if (this.value == "get_all") {
				popup.find(" > #new_resource_attribute > .row_index").show();
				lis.show();
			}
			else {
				popup.find(" > #new_resource_attribute > .row_index").hide();
				lis.each(function(idx, li) {
					li = $(li);
					li.hide().removeClass("condition_activated");
					li.find("input[type=checkbox]").prop("checked", false).removeAttr("checked");
					li.find("input[type=text]").val("");
				});
			}
		});
		
		$("body").append(popup);
	}
	
	if (popup[0]) {
		var style = window.getComputedStyle(popup[0]);
		
		if (style.display === "none") {
			//update popup class
			if (show_resource_attributes)
				popup.addClass("show_resource_attributes");
			else
				popup.removeClass("show_resource_attributes");
			
			//update title
			popup.children(".title").html("Choose Widget Resource" + (show_resource_attributes ? " Attribute" : ""));
			
			//set popup update click event
			popup.find(" > .button input").unbind("click").on("click", function(event) {
				var data = getChooseLayoutUIEditorWidgetResourceValueUserData(this, elm, widget, PtlLayoutUIEditor);
				handler(data["resource_name"], data["resource_attribute"], data["resource_index"]);
				
				MyFancyPopup.hidePopup();
			});
			
			//get all available resources
			var available_resources = getLayoutUIEditorWidgetResourceSLAsDescriptionByName();
			
			//show available resources
			var ul = popup.find(" > #existent_resource_attribute > ul");
			var lis = ul.children("li:not(.empty_items)");
			
			if ($.isEmptyObject(available_resources)) {
				lis.remove();
				ul.children("li.empty_items").show();
			}
			else {
				ul.children("li.empty_items").hide();
				
				//remove old slas
				$.each(lis, function(idx, li) {
					li = $(li);
					var resource_name = li.attr("resource_name");
					
					if (!available_resources.hasOwnProperty(resource_name))
						li.remove();
				});
				
				//create new slas
				for (var resource_name in available_resources) {
					var resource_description = available_resources[resource_name];
					
					//Do not show the _group resources bc are dummy resources
					if (resource_name.match(/_group$/) && available_resources.hasOwnProperty(resource_name.substr(0, resource_name.length - 6)))
						continue;
					
					var li = lis.filter("[resource_name='" + resource_name + "']");
					
					//add new resource item
					if (!li[0]) {
						var html = '<li resource_name="' + resource_name + '">'
									+ '<div class="sla_resource_header">'
										+ '<div class="title"><input type="radio" name="selected_resource" /> Resource: "' + resource_name + '":</div>'
										+ '<div class="description"></div>'
									+ '</div>'
									+ '<div class="sla_resource_body">'
										+ '<div class="sla_resource_index">'
											+ '<label>Row Index (Only fill this field if the resource is a list):</label>'
											+ '<input type="text" placeHolder="Leave it blank for automatic" />'
										+ '</div>'
										+ '<div class="sla_resource_attributes">'
											+ '<label>Choose an attribute:</label>'
											+ '<ul>'
												+ '<li class="user_defined_item">'
													+ 'If you wish a different attribute than the below ones, please fill the following text box:<br/>'
													+ '<input type="radio" name="selected_resource_attribute" value="" />'
													+ '<input type="text" />'
												+ '</li>'
												+ '<li class="empty_items">The system couldn\'t detect the attributes for this resource. Please use the text box above.</li>'
											+ '</ul>'
										+ '</div>'
									+ '</div>'
								+ '</li>';
						li = $(html);
						
						li.find(".sla_resource_header input[type=radio]").on("click", function() {
							ul.children("li:not(.empty_items)").removeClass("selected");
							$(this).parent().closest("li").addClass("selected");
						});
						
						//set keypress event to update value of selected_resource_attribute input
						li.find(".sla_resource_attributes li.user_defined_item > input[type=text]").on("keypress", function() {
							var input_text = $(this);
							
							if (input_text.data("set_timeout_id"))
								clearTimeout( input_text.data("set_timeout_id") );
							
							var set_timeout_id = setTimeout(function() {
								input_text.data("set_timeout_id", null);
								
								var input_radio = input_text.parent().children("input[type=radio]");
								
								input_radio.prop("checked", true).attr("checked", "checked");
								input_radio.val( input_text.val() );
							}, 500);
							
							input_text.data("set_timeout_id", set_timeout_id);
						});
						
						ul.append(li);
					}
					
					//get description from group
					if (!resource_description && available_resources[resource_name + "_group"])
						resource_description = available_resources[resource_name + "_group"];
					
					//update description
					li.find(" > .sla_resource_header > .description").html(resource_description);
				}
				
				//prepare attributes for the available resources
				if (show_resource_attributes) {
					var select = $('<select></select>');
					var variables_in_workflow = Object.assign({}, ProgrammingTaskUtil.variables_in_workflow);
					var callback = function() {
						if (window.variables_in_workflow_loading_processes == 0) {
							//because the updateSLAProgrammingTaskVariablesInWorkflowSelect method resets the ProgrammingTaskUtil.variables_in_workflow var, we need to update it with the variables_in_workflow var, which contains the vars created from the addLayoutUIEditorWidgetResourceSLAResource method
							
							if ($.isPlainObject(variables_in_workflow) && !$.isEmptyObject(variables_in_workflow)) {
								for (var k in variables_in_workflow)
									if (!ProgrammingTaskUtil.variables_in_workflow.hasOwnProperty(k))
										ProgrammingTaskUtil.variables_in_workflow[k] = variables_in_workflow[k];
								
								var html = select.html(); //backup select html bc the populateVariablesOfTheWorkflowInSelectField will remove all html
								populateVariablesOfTheWorkflowInSelectField(select);
								select.append(html); //append previous html
							}
							
							//get all resources' attributes by resource name
							var options = select.find("option");
							var available_resources_attributes = {};
							var resources_multiple = {};
							
							$.each(options, function(idx, option) {
								var var_name = $(option).val();
								
								for (var resource_name in available_resources) {
									var prefix = resource_name + "[";
									
									if (var_name.indexOf(prefix) == 0) {
										if (!$.isArray(available_resources_attributes[resource_name]))
											available_resources_attributes[resource_name] = [];
										
										var pos = var_name.lastIndexOf("[") + 1; //get last position for "[" bc the var_name may be: "xxx[idx][attr_name]"
										var parsed_var_name = var_name.substr(pos, var_name.length - (pos + 1)); //+1 bc of the last char: "]"
										
										if (available_resources_attributes[resource_name].indexOf(parsed_var_name) == -1)
											available_resources_attributes[resource_name].push(parsed_var_name);
										
										if (var_name.indexOf("[idx]") != -1)
											resources_multiple[resource_name] = true;
										
										break;
									}
								}
							});
							//console.log(available_resources_attributes);
							
							//show attributes for all the displayed resources
							for (var resource_name in available_resources_attributes) {
								var resource_attributes = available_resources_attributes[resource_name];
								var resource_li = ul.find(" > li[resource_name='" + resource_name + "']");
								var resource_ul = resource_li.find(".sla_resource_attributes > ul");
								var attribute_lis = resource_ul.children(":not(.empty_items)");
								
								if (resource_li && resources_multiple[resource_name])
									resource_li.addClass("is_multiple");
								
								if (resource_attributes.length > 0) {
									resource_ul.children(".empty_items").hide();
									
									//remove old attributes
									for (var i = 0, t = attribute_lis.length; i < t; i++) {
										var attribute_li = $(attribute_lis[i]);
										var attr_name = attribute_li.children("input[type=radio]").attr("value");
										
										if (!attr_name || resource_attributes.indexOf(attr_name) == -1)
											attribute_li.remove();
									}
									
									//add new attributes
									for (var i = 0, t = resource_attributes.length; i < t; i++) {
										var attr_name = resource_attributes[i];
										var attr_input = attribute_lis.children("input[type=radio][value='" + attr_name + "']");
										
										if (!attr_input[0]) {
											var html = '<li>'
														+ '<input type="radio" name="selected_resource_attribute" value="' + attr_name + '" />'
														+ '<label>' + attr_name + '</label>'
													+ '</li>';
											var li = $(html);
											
											resource_ul.append(li);
										}
									}
								}
								else {
									resource_ul.children(".empty_items").show();
									attribute_lis.remove();
								}
							}
						}
						else
							setTimeout(callback, 500);
					};
					
					updateSLAProgrammingTaskVariablesInWorkflowSelect(select);
					setTimeout(callback, 1000);
				}
			}
			
			//show popup
			MyFancyPopup.init({
				elementToShow: popup,
				parentElement: document,
			});
			MyFancyPopup.showPopup();
		}
		else {
			MyFancyPopup.hidePopup();
		}
	}
}

function updateChooseLayoutUIEditorWidgetResourceValueDBAttributes(elm) {
	updateDBAttributes(elm);
	
	var p = $(elm).parent().parent();
	var db_broker = p.find(" > .db_broker select").val();
	var db_driver = p.find(" > .db_driver select").val();
	var db_type = p.find(" > .type select").val();
	var db_table = p.find(" > .db_table select").val();
	
	var ul = p.find(" > .query_conditions > ul");
	
	ul.children("li:not(.empty_items)").remove();
	ul.children("li.empty_items").show();
	
	if (db_table) {
		var db_attributes = getLayoutUIEditorWidgetResourceDBAttributes(db_broker, db_driver, db_type, db_table);
		
		if (!$.isEmptyObject(db_attributes)) {
			ul.children("li.empty_items").hide();
			
			var html = '';
			
			for (var attr_name in db_attributes) {
				var attr = db_attributes[attr_name];
				var is_pk = attr["primary_key"];
				
				html += '<li' + (is_pk ? ' class="primary_key"' : '') + '>'
						+ '<input type="checkbox" onClick="toggleChooseLayoutUIEditorWidgetResourceValueQueryCondition(this)">'
						+ '<label>' + attr_name + ':</label>'
						+ '<input type="text" name="conditions[' + attr_name + ']" />'
					+ '</li>';
			}
			
			ul.append(html);
		}
	}
	
	MyFancyPopup.updatePopup();
}

function toggleChooseLayoutUIEditorWidgetResourceValueQueryCondition(elm) {
	$(elm).parent().toggleClass("condition_activated");
}

function getChooseLayoutUIEditorWidgetResourceValueUserData(elm, menu_settings_elm, widget, PtlLayoutUIEditor) {
	var popup = $(elm).parent().closest(".choose_widget_resource_value_attribute");
	var active_tab = popup.find(" > ul > .ui-state-active");
	var resource_name = null;
	var resource_attribute = null;
	var resource_index = null;
	
	//check what is the active panel and get the correspondent attribute and resource reference. 
	if (active_tab.is(".existent_resource_attribute_tab")) {
		var li = popup.find(" > #existent_resource_attribute li:not(empty_items) > .sla_resource_header input[type=radio]:checked").parent().closest("li[resource_name]");
		resource_name = li.attr("resource_name");
		var input = li.find(".sla_resource_attributes input[name=selected_resource_attribute]:checked").first();
		resource_attribute = input.val();
		resource_index = li.find(".sla_resource_index input").val();
	}
	else if (active_tab.is(".new_resource_attribute_tab")) {
		//prepare resource data
		var p = popup.children("#new_resource_attribute");
		var db_broker = p.find(" > .db_broker select").val();
		var db_driver = p.find(" > .db_driver select").val();
		var db_type = p.find(" > .type select").val();
		var db_table = p.find(" > .db_table select").val();
		var db_attribute = p.find(" > .db_attribute select").val();
		var query_type = p.find(" > .query_type select").val();
		var query_conditions_ul = p.find(" > .query_conditions > ul");
		var query_conditions_inputs = query_conditions_ul.find(" > li.condition_activated input:not([type=checkbox])");
		
		resource_index = p.find(" > .row_index input").val();
		
		query_conditions_inputs.removeClass("task_property_field");
		query_conditions_inputs.addClass("task_property_field");
		
		var conditions = parseArray(query_conditions_ul);
		conditions = $.isPlainObject(conditions) ? conditions["conditions"] : null;
		
		query_conditions_inputs.removeClass("task_property_field");
		
		var resource_data = {
			conditions: conditions,
		};
		var resource_conditions_hash = $.isPlainObject(conditions) && !$.isEmptyObject(conditions) ? ("" + JSON.stringify(conditions).hashCode()).replace(/-/g, "_") : null;
		//console.log("resource_data:");
		//console.log(resource_data);
		
		//prepare resource possible names
		var resources_name = createLayoutUIEditorWidgetResourceSLAResourceNames(query_type, db_driver, db_table, null, resource_data);
		resource_attribute = db_attribute;
		
		var resource_possible_names = [resources_name];
		var resource_possible_name_permissions = [null];
		var widget_group = widget.closest("[data-widget-group-list], [data-widget-group-form], [data-widget-list], [data-widget-form]");
		var widget_group_view_permissions = widget_group[0] ? PtlLayoutUIEditor.LayoutUIEditorWidgetResource.getWidgetPermissions(widget_group) : null;
		var widget_view_permissions = PtlLayoutUIEditor.LayoutUIEditorWidgetResource.getWidgetPermissions(widget);
		
		if (widget_group_view_permissions) {
			resource_possible_names.push( createLayoutUIEditorWidgetResourceSLAResourceNames(query_type, db_driver, db_table, widget_group_view_permissions, resource_data) );
			resource_possible_name_permissions.push(widget_group_view_permissions);
		}
		
		if (widget_view_permissions) {
			resource_possible_names.push( createLayoutUIEditorWidgetResourceSLAResourceNames(query_type, db_driver, db_table, widget_view_permissions, resource_data) );
			resource_possible_name_permissions.push(widget_view_permissions);
		}
		
		//If the resource name doesn't exist yet, create it
		var available_resources = getLayoutUIEditorWidgetResourceSLAsDescriptionByName();
		
		for (var i = resource_possible_names.length - 1; i >= 0; i--) {
			var resources_name = resource_possible_names[i];
			
			for (var j = 0, t = resources_name.length; j < t; j++) {
				var rn = resources_name[j];
				
				//if conditions exists add the conditions hash code to the resource name, otherwise the system wil find an incorrect resource
				if (resource_conditions_hash)
					rn += "_" + resource_conditions_hash;
				
				if (available_resources.hasOwnProperty(rn)) {
					resource_name = rn;
					break;
				}
			}
		}
		
		if (!resource_name) {
			var idx = resource_possible_names.length - 1;
			resource_name = resource_possible_names[idx][0]; //get the latest names from resource_possible_names and then set resource name with widget's permissions, and if they don't exist, set to widget_group's permissions, and if they don't exist, set the resource name without permissions.
			var permissions = resource_possible_name_permissions[idx];
			
			//if conditions exists add the conditions hash code to the resource name, otherwise the system wil find an incorrect resource
			if (resource_conditions_hash)
				resource_name += "_" + resource_conditions_hash;
			
			//console.log("resource_name:"+resource_name);
			addLayoutUIEditorWidgetResourceSLAResource(db_broker, db_driver, db_type, db_table, query_type, resource_name, permissions, resource_data);
		}
	}
	//console.log("resource_name:"+resource_name);
	//console.log("resource_attribute:"+resource_attribute);
	
	return {
		resource_name: resource_name,
		resource_attribute: resource_attribute,
		resource_index: resource_index
	};
}

function toggleChooseLayoutUIEditorWidgetResourceDBTableAttributePopup(elm, event, handler) {
	var popup = $("#choose_db_table_or_attribute");
	
	if (popup[0]) {
		var style = window.getComputedStyle(popup[0]);
		
		if (style.display === "none") {
			popup.hide(); //This popup is shared with other actions so we must hide it first otherwise the user experience will be weird bc we will see the popup changing with the new changes.
			
			var default_value = $(elm).parent().children("input").val();
			var db_attribute_elm = popup.find(".db_attribute");
			db_attribute_elm.show();
			
			if (default_value)
				db_attribute_elm.find("select").val(default_value);
			
			MyFancyPopup.init({
				elementToShow: popup,
				parentElement: document,
				updateFunction: function(btn) { //prepare update handler
					var p = $(btn).parent().parent();
					var db_attribute = p.find(".db_attribute select").val();
					
					handler(db_attribute);
					
					MyFancyPopup.hidePopup();
				},
			});
			MyFancyPopup.showPopup();
		}
		else {
			MyFancyPopup.hidePopup();
		}
	}
}

function getLayoutUIEditorWidgetResourceDBBrokers() {
	if (typeof db_brokers_drivers_tables_attributes != "undefined") {
		var db_brokers = [];
		
		if (db_brokers_drivers_tables_attributes)
			for (var db_broker in db_brokers_drivers_tables_attributes)
				db_brokers.push(db_broker);
		
		return db_brokers;
	}
	
	return null;
}

function getLayoutUIEditorWidgetResourceDBDrivers(db_broker) {
	if (typeof db_brokers_drivers_tables_attributes != "undefined") {
		//set defaults
		db_broker = db_broker ? db_broker : (typeof default_dal_broker != "undefined" ? default_dal_broker : null);
		
		if (db_broker) {
			var db_drivers = [];
			
			if (db_brokers_drivers_tables_attributes && db_brokers_drivers_tables_attributes[db_broker])
				for (var db_driver in db_brokers_drivers_tables_attributes[db_broker])
					db_drivers.push(db_driver);
			
			return db_drivers;
		}
	}
	
	return null;
}

function getLayoutUIEditorWidgetResourceDBTables(db_broker, db_driver, db_type) {
	//set defaults
	db_broker = db_broker ? db_broker : (typeof default_dal_broker != "undefined" ? default_dal_broker : null);
	db_driver = db_driver ? db_driver : (typeof default_db_driver != "undefined" ? default_db_driver : null);
	db_type = db_type ? db_type : (typeof default_db_type != "undefined" ? default_db_type : null);
	
	if (db_broker && db_driver && db_type) {
		var db_tables = getDBTables(db_broker, db_driver, db_type);
		var names = [];
		
		if ($.isPlainObject(db_tables))
			for (var table_name in db_tables)
				names.push(table_name);
		
		return names;
	}
	
	return null;
}

function getLayoutUIEditorWidgetResourceDBAttributes(db_broker, db_driver, db_type, db_table) {
	//set defaults
	db_broker = db_broker ? db_broker : (typeof default_dal_broker != "undefined" ? default_dal_broker : null);
	db_driver = db_driver ? db_driver : (typeof default_db_driver != "undefined" ? default_db_driver : null);
	db_type = db_type ? db_type : (typeof default_db_type != "undefined" ? default_db_type : null);
	
	if (db_broker && db_driver && db_type && db_table)
		return getDBTableAttributesDetailedInfo(db_broker, db_driver, db_type, db_table);
	
	return null;
}

//based in the updateSLAProgrammingTaskVariablesInWorkflowSelect method inside of sla.js
function getLayoutUIEditorWidgetResourceResourcesReferences() {
	var references = [];
	var resources = getLayoutUIEditorWidgetResourceSLAsDescriptionByName();
	
	for (var resource_name in resources)
		references.push(resource_name);
	
	return references;
}

function getLayoutUIEditorWidgetResourceSLAsDescriptionByName() {
	var resources = {};
	
	var inputs = $(".sla_groups_flow .sla_groups .sla_group_item:not(sla_group_default) > .sla_group_header > .result_var_name");
	
	$.each(inputs, function(idx, input) {
		input = $(input);
		var var_name = input.val();
		var_name = var_name ? var_name.replace(/^\s+/g, "").replace(/\s+$/g, "") : "";
		
		if (var_name != "")
			resources[var_name] = input.parent().closest(".sla_group_header").find(" > .sla_group_sub_header > .action_description > textarea").val();
	});
	
	if (jsPlumbWorkFlow) {
		var tasks_properties = jsPlumbWorkFlow.jsPlumbTaskFlow.tasks_properties;
		
		if (tasks_properties)
			$.each(tasks_properties, function(idx, task_properties) {
				var var_name = task_properties && task_properties["properties"] ? task_properties["properties"]["result_var_name"] : "";
				var_name = var_name ? var_name.replace(/^\s+/g, "").replace(/\s+$/g, "") : "";
				
				if (var_name != "")
					resources[var_name] = task_properties["properties"]["action_description"];
			});
	}
	
	return resources;
}

function getLayoutUIEditorWidgetResourceUserTypes() {
	return typeof available_user_types != "undefined" ? available_user_types : null;
}

function getLayoutUIEditorWidgetResourcePHPNumericTypes() {
	return typeof php_numeric_types != "undefined" ? php_numeric_types : null;
}

function getLayoutUIEditorWidgetResourceDBNumericTypes() {
	return typeof db_numeric_types != "undefined" ? db_numeric_types : null;
}

function addLayoutUIEditorWidgetResourceSLAResource(db_broker, db_driver, db_type, db_table, action_type, resource_name, permissions, data) {
	if (create_sla_resource_url && !creating_resources[resource_name]) {
		creating_resources[resource_name] = true;
		
		//console.log("ADD RESOURCE: "+resource_name);
		var status_message_elm = StatusMessageHandler.showMessage("Creating new resource with name: '" + resource_name + "'");
		
		var post_data = {
			db_broker: db_broker,
			db_driver: db_driver,
			db_type: db_type,
			db_table: db_table,
			action_type: action_type,
			resource_name: resource_name,
			permissions: permissions,
			resource_data: data,
		};
		//console.log(post_data);
		
		MyFancyPopup.showLoading();
		
		$.ajax({
			url: create_sla_resource_url,
			type: 'post',
			data: post_data,
			dataType: 'json',
			success: function(data, textStatus, jqXHR) {
				//console.log(data);
				creating_resources[resource_name] = null;
				delete creating_resources[resource_name];
				status_message_elm.remove();
				
				if (data && data["status"]) {
					//create resource in sla panel
					if (data["actions"]) {
						var resource_settings = $(".regions_blocks_includes_settings .resource_settings");
						
						//check if resource_name doesn't exist already, bc meanwhile it may was created before. Note that it is possible to happen multiple concurrent calls of this function with the same resource_name. So just in case we check if exists again...
						var inputs = resource_settings.find(".sla_groups_flow .sla_groups .sla_group_item:not(sla_group_default) > .sla_group_header > .result_var_name");
						var exists = false;
						
						$.each(inputs, function(idx, input) {
							if ($(input).val() == resource_name) {
								exists = true;
								return false
							}
						});
						
						if (!exists) {
							var add_group_icon = resource_settings.find(".sla_groups_flow > nav > .add_sla_group")[0];
							
							loadSLASettingsActions(add_group_icon, data["actions"], false);
							
							//add vars with resource_name to ProgrammingTaskUtil.variables_in_workflow
							addSLAProgrammingTaskVariablesBasedInResourceDBTable(db_broker, db_driver, db_type, db_table, resource_name);
							
							//prepare messages
							StatusMessageHandler.showMessage("Resource '" + resource_name + "' created successfully!");
							
							StatusMessageHandler.shown_messages_timeout && clearTimeout(StatusMessageHandler.shown_messages_timeout);
							
							StatusMessageHandler.shown_messages_timeout = setTimeout(function() {
								StatusMessageHandler.shown_messages_timeout = null;
								
								StatusMessageHandler.removeMessages("info");
							}, 5000); //hide messages after 5 secs
						}
					}
				}
				else if (data && data["error_message"])
					StatusMessageHandler.showError(data["error_message"]);
				else
					StatusMessageHandler.showError("Error trying to create resource '" + resource_name + "'! Please create it manually...");
				
				MyFancyPopup.hideLoading();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				creating_resources[resource_name] = null;
				delete creating_resources[resource_name];
				
				MyFancyPopup.hideLoading();
				
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_sla_resource_url, function() {
						StatusMessageHandler.removeLastShownMessage("error");
						
						addLayoutUIEditorWidgetResourceSLAResource(db_broker, db_driver, db_type, db_table, action_type, resource_name, permissions, data);
					});
				else {
					var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
					StatusMessageHandler.showError("Error trying to create new resource '" + resource_name + "'.\nPlease try again..." + msg);
				}
			},
		});
	}
}

//remove all slas with result_var_name equal to resource_name or if result_var_name is child of result_var_name
function removeLayoutUIEditorWidgetResourceSLAResource(resources_name, do_not_confirm) {
	if (!resources_name)
		resources_name = [];
	else if (!$.isArray(resources_name))
		resources_name = [resources_name];
	
	if (resources_name.length > 0) {
		var found_inputs_by_resource_name = {};
		var task_ids_by_resource_name = {};
		var inputs = $(".sla_groups_flow .sla_groups .sla_group_item:not(sla_group_default) > .sla_group_header > .result_var_name");
		
		$.each(inputs, function(idx, input) {
			input = $(input);
			var var_name = input.val();
			var_name = var_name ? var_name.replace(/^\s+/g, "").replace(/\s+$/g, "") : "";
			
			for (var i = 0, t = resources_name.length; i < t; i++) {
				var resource_name = resources_name[i];
				
				if (var_name == resource_name || var_name.indexOf(resource_name + "[") === 0) { //if var_name is equal to resource_name or if is a child
					if (!found_inputs_by_resource_name.hasOwnProperty(resource_name))
						found_inputs_by_resource_name[resource_name] = [];
					
					//get correspondent group for resource if apply
					var sla_group_item = input.parent().closest(".sla_group_item");
					var parent_sla_group_item = sla_group_item.parent().closest(".sla_group_item");
					var parent_input = parent_sla_group_item.find(" > .sla_group_header > .result_var_name");
					var parent_var_name = parent_input.val();
					
					if (parent_var_name == resource_name + "_group" || var_name.indexOf(resource_name + "_group" + "[") === 0) //if var_name is equal to resource_name or if is a child
						found_inputs_by_resource_name[resource_name].push(parent_input[0]);
					else
						found_inputs_by_resource_name[resource_name].push(input[0]);
				}
			}
		});
		
		if (jsPlumbWorkFlow) {
			var tasks_properties = jsPlumbWorkFlow.jsPlumbTaskFlow.tasks_properties;
			
			if (tasks_properties)
				for (var task_id in tasks_properties) {
					var task_properties = tasks_properties[task_id];
					var var_name = task_properties && task_properties["properties"] ? task_properties["properties"]["result_var_name"] : "";
					var_name = var_name ? var_name.replace(/^\s+/g, "").replace(/\s+$/g, "") : "";
					
					for (var i = 0, t = resources_name.length; i < t; i++) {
						var resource_name = resources_name[i];
						
						if (var_name == resource_name || var_name.indexOf(resource_name + "[") === 0) { //if var_name is equal to resource_name or if is a child
							if (!task_ids_by_resource_name.hasOwnProperty(resource_name))
								task_ids_by_resource_name[resource_name] = [];
							
							//get correspondent group for resource if apply
							var found_parent_task_id = null;
							
							for (var parent_task_id in tasks_properties) {
								var parent_task_properties = tasks_properties[parent_task_id];
								var parent_var_name = parent_task_properties && parent_task_properties["properties"] ? parent_task_properties["properties"]["result_var_name"] : "";
								parent_var_name = parent_var_name ? parent_var_name.replace(/^\s+/g, "").replace(/\s+$/g, "") : "";
								
								if (parent_var_name == resource_name + "_group" || parent_var_name.indexOf(resource_name + "_group" + "[") === 0) { //if var_name is equal to resource_name or if is a child
									found_parent_task_id = parent_task_id;
									break;
								}
							}
							
							task_ids_by_resource_name[resource_name].push(found_parent_task_id ? found_parent_task_id : task_id);
						}
					}
				}
		}
		
		if (!$.isEmptyObject(found_inputs_by_resource_name) || !$.isEmptyObject(task_ids_by_resource_name)) {
			var msg = resources_name.length > 1 ? "The following resources: '" + resources_name.join("', '") + "' are not currently being used anymore. Do you wish to remove them from the Resources Panel in the Main-Settings?" : "The '" + resources_name[0] + "' resource is not currently being used anymore. Do you wish to remove it from the Resources Panel in the Main-Settings?";
			
			if (do_not_confirm || confirm(msg)) {
				var repeated_inputs = [];
				
				for (var resource_name in found_inputs_by_resource_name) {
					$.each(found_inputs_by_resource_name[resource_name], function(idx, input) {
						if (repeated_inputs.indexOf(input) == -1) {
							removeGroupItem(input, true);
							repeated_inputs.push(input);
						}
					});
					
					//remove vars with resource_name in ProgrammingTaskUtil.variables_in_workflow
					removeSLAProgrammingTaskVariablesBasedInResource(resource_name);
				}
				
				var repeated_task_ids = [];
				
				for (var resource_name in task_ids_by_resource_name) {
					$.each(task_ids_by_resource_name[resource_name], function(idx, task_id) {
						if (repeated_task_ids.indexOf(task_id) == -1) {
							jsPlumbWorkFlow.jsPlumbTaskFlow.deleteTask(task_id, {confirm: false});
							repeated_inputs.push(task_id);
						}
					});
					
					//remove vars with resource_name in ProgrammingTaskUtil.variables_in_workflow
					removeSLAProgrammingTaskVariablesBasedInResource(resource_name);
				}
				
			}
		}
	}
}

function addSLAProgrammingTaskVariablesBasedInResourceDBTable(db_broker, db_driver, db_type, db_table, resource_name) {
	if (db_table && resource_name) {
		var db_attributes = getLayoutUIEditorWidgetResourceDBAttributes(db_broker, db_driver, db_type, db_table);
		
		if (!$.isEmptyObject(db_attributes))
			for (var attr_name in db_attributes) {
				var key = resource_name + "[" + attr_name + "]";
				
				if (!ProgrammingTaskUtil.variables_in_workflow.hasOwnProperty(key))
					ProgrammingTaskUtil.variables_in_workflow[key] = {};
			}
	}
}

function removeSLAProgrammingTaskVariablesBasedInResource(resource_name) {
	if (resource_name) {
		var variables_in_workflow = {};
		
		for (var key in ProgrammingTaskUtil.variables_in_workflow) {
			var regex = new RegExp("^" + resource_name + "($|\\[)");
			var exists = key.match(regex);
			
			if (!exists)
				variables_in_workflow[key] = ProgrammingTaskUtil.variables_in_workflow[key];
		}
		
		ProgrammingTaskUtil.variables_in_workflow = variables_in_workflow;
	}
}

function createLayoutUIEditorWidgetResourceSLAResourceNames(action_type, db_driver, db_table, permissions, data) {
	var resource_names = [];
	var permissions_hash_code = permissions ? ("_" + JSON.stringify(permissions).hashCode()).replace(/-/g, "_") : "";
	var is_default_db_driver = !db_driver || (typeof default_db_driver != "undefined" && default_db_driver == db_driver);
	var db_table_plural = db_table + (db_table.substr(db_table.length - 1) == "y" ? "ies" : "s");
	var db_driver_table = (db_driver ? db_driver + "_" : "") + db_table + permissions_hash_code;
	var db_driver_table_plural = (db_driver ? db_driver + "_" : "") + db_table_plural + permissions_hash_code;
	
	switch (action_type) {
		case "insert": 
			if (is_default_db_driver)
				resource_names.push("insert_" + db_table);
			
			resource_names.push("insert_" + db_driver_table);
			break;
		case "update": 
			if (is_default_db_driver)
				resource_names.push("update_" + db_table);
			
			resource_names.push("update_" + db_driver_table);
			break;
		case "update_attribute": 
			if (is_default_db_driver)
				resource_names.push("update_" + db_table + "_attribute");
			
			resource_names.push("update_" + db_driver_table + "_attribute");
			break;
		case "multiple_save": 
			if (is_default_db_driver) {
				resource_names.push("update_all_" + db_table_plural);
				resource_names.push("update_all_" + db_table_plural + "_items");
				resource_names.push("update_all_" + db_table);
				resource_names.push("update_all_" + db_table + "_items");
			}
			
			resource_names.push("update_all_" + db_driver_table);
			resource_names.push("update_all_" + db_driver_table + "_items");
			resource_names.push("update_all_" + db_driver_table_plural);
			resource_names.push("update_all_" + db_driver_table_plural + "_items");
			break;
		case "delete": 
			if (is_default_db_driver)
				resource_names.push("delete_" + db_table);
			
			resource_names.push("delete_" + db_driver_table);
			break;
		case "multiple_delete": 
			if (is_default_db_driver) {
				resource_names.push("delete_all_" + db_table_plural);
				resource_names.push("delete_all_" + db_table_plural + "_items");
				resource_names.push("delete_all_" + db_table);
				resource_names.push("delete_all_" + db_table + "_items");
			}
			
			resource_names.push("delete_all_" + db_driver_table);
			resource_names.push("delete_all_" + db_driver_table + "_items");
			resource_names.push("delete_all_" + db_driver_table_plural);
			resource_names.push("delete_all_" + db_driver_table_plural + "_items");
			break;
		case "get": 
			if (is_default_db_driver) {
				resource_names.push(db_table);
				resource_names.push("get_" + db_table);
				resource_names.push("get_" + db_table + "_item");
			}
			
			resource_names.push("get_" + db_driver_table);
			resource_names.push("get_" + db_driver_table + "_item");
			resource_names.push(db_driver_table);
			break;
		case "get_all": 
			if (is_default_db_driver) {
				resource_names.push(db_table_plural);
				resource_names.push("get_" + db_table_plural);
				resource_names.push("get_" + db_table_plural + "_items");
				resource_names.push("get_" + db_table + "_items");
			}
			
			resource_names.push("get_" + db_driver_table + "_items");
			resource_names.push("get_" + db_driver_table_plural);
			resource_names.push("get_" + db_driver_table_plural + "_items");
			resource_names.push(db_driver_table_plural);
			break;
		case "count": 
			if (is_default_db_driver) {
				resource_names.push("count_" + db_table_plural);
				resource_names.push("count_" + db_table_plural + "_items");
				resource_names.push("count_" + db_table);
				resource_names.push("count_" + db_table + "_items");
			}
			
			resource_names.push("count_" + db_driver_table);
			resource_names.push("count_" + db_driver_table + "_items");
			resource_names.push("count_" + db_driver_table_plural);
			resource_names.push("count_" + db_driver_table_plural + "_items");
			break;
		case "get_options":
			if ($.isPlainObject(data))
				data = [data];
			
			if ($.isArray(data)) {
				var suffix = data[0]["table"] + (data[0]["attribute"] ? "_" + data[0]["attribute"] : "") + permissions_hash_code + "_options";
				
				if (is_default_db_driver) {
					resource_names.push("get_" + suffix);
					resource_names.push(suffix);
				}
				
				resource_names.push("get_" + (db_driver ? db_driver + "_" : "") + suffix);
				resource_names.push((db_driver ? db_driver + "_" : "") + suffix);
			}
			break;
	}
	
	return resource_names;
}

if (typeof getDBTables != "function")
	function getDBTables(db_broker, db_driver, type) {
		var db_tables = db_brokers_drivers_tables_attributes[db_broker][db_driver][type];
		
		if (jQuery.isEmptyObject(db_tables)) {
			$.ajax({
				type : "post",
				url : get_broker_db_data_url,
				data : {"db_broker" : db_broker, "db_driver" : db_driver, "type" : type},
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					if(data) {
						db_tables = {};
						for (var i = 0; i < data.length; i++) {
							db_tables[ data[i] ] = {};
						}
						
						db_brokers_drivers_tables_attributes[db_broker][db_driver][type] = db_tables;
					}
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					if (jqXHR.responseText)
						StatusMessageHandler.showError(jqXHR.responseText);
				},
				async: false,
			});
		}
		
		return db_tables;
	}

if (typeof getDBTableAttributesDetailedInfo != "function")
	function getDBTableAttributesDetailedInfo(db_broker, db_driver, type, db_table) {
		var parts = db_table.split(" ");
		db_table = parts[0];
		
		var detailed_info;
		
		$.ajax({
			type : "post",
			url : get_broker_db_data_url,
			data : {"db_broker" : db_broker, "db_driver" : db_driver, "type" : type, "db_table" : db_table, "detailed_info" : 1},
			dataType : "json",
			success : function(data, textStatus, jqXHR) {
				detailed_info = data ? data : {};
				
				if ($.isPlainObject(detailed_info) && !$.isEmptyObject(detailed_info) && !db_brokers_drivers_tables_attributes[db_broker][db_driver][type].hasOwnProperty(db_table)) {
					db_brokers_drivers_tables_attributes[db_broker][db_driver][type][db_table] = [];
					
					for (var attr_name in detailed_info)
						db_brokers_drivers_tables_attributes[db_broker][db_driver][type][db_table].push(attr_name);
				}
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jqXHR.responseText)
					StatusMessageHandler.showError(jqXHR.responseText);
			},
			async: false,
		});
		
		return detailed_info;
	}
