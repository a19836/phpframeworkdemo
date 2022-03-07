var MyFancyPopupCreateDiagramSQL = new MyFancyPopupClass();

$(function () {
	$(window).bind('beforeunload', function () {
		if (jsPlumbWorkFlow.jsPlumbTaskFile.isWorkFlowChangedFromLastSaving()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	//prepare top_bar
	$(".taskflowchart").addClass("with_top_bar_menu fixed_properties").children(".workflow_menu").addClass("top_bar_menu");
	
	//init auto save
	addAutoSaveMenu(".taskflowchart.with_top_bar_menu .workflow_menu.top_bar_menu li.save", "onToggleWorkflowAutoSave");
	addAutoConvertMenu(".taskflowchart.with_top_bar_menu .workflow_menu.top_bar_menu li.save", "onToggleWorkflowAutoConvert");
	enableAutoSave(onToggleWorkflowAutoSave);
	enableAutoConvert(onToggleWorkflowAutoConvert);
	initAutoSave(".taskflowchart.with_top_bar_menu .workflow_menu.top_bar_menu li.save a");
	
	//init workflow
	jsPlumbWorkFlow.jsPlumbTaskFlow.default_connection_connector = "Flowchart";
	jsPlumbWorkFlow.jsPlumbTaskFlow.default_connection_overlay = "One To One";
	//jsPlumbWorkFlow.jsPlumbTaskFlow.available_connection_connectors_type = ["Flowchart"];
	jsPlumbWorkFlow.jsPlumbTaskFlow.available_connection_overlays_type = ["One To One", "Many To One", "One To Many"]; //Do not add "Many To Many" bc it doesn't make sense for the db relational diagram.

	jsPlumbWorkFlow.jsPlumbTaskFile.on_success_read = updateTasksAfterFileRead;
	jsPlumbWorkFlow.jsPlumbTaskFile.on_success_update = updateTasksAfterFileRead;
});

function prepareTaskContextMenu() {
	;(function() {
		jsPlumbWorkFlow.onReady(function() {
			$("#" + jsPlumbWorkFlow.jsPlumbContextMenu.task_context_menu_id + " .set_label a").html("Edit Table Name");
			
			var start_task = $("#" + jsPlumbWorkFlow.jsPlumbContextMenu.task_context_menu_id + " .start_task a");
			start_task.html("Get DB Table\'s Attributes");
			start_task.attr("onClick", "");
			start_task.click(function() {
				jsPlumbWorkFlow.jsPlumbContextMenu.hideContextMenus();
				
				var task_id = jsPlumbWorkFlow.jsPlumbContextMenu.getContextMenuTaskId();
				
				updateTaskTableAttributes(task_id);
			});
		});
	})();
}

function updateTaskTableAttributes(task_id, do_not_confirm) {
	if (task_id) {
		var table_name = jsPlumbWorkFlow.jsPlumbTaskFlow.getTaskLabelByTaskId(task_id);
		table_name = table_name ? table_name.trim() : "";

		if (table_name && (do_not_confirm || confirm("The system will now get the DB\'s attributes for the table '" + table_name + "'.\nDo you wish to proceed?"))) {
			var url = get_db_data_url.replace("#table#", table_name);
			
			$.ajax({
				type : "get",
				url : url,
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					if (data) {
						//PREPARING ATTRIBUTES
						DBTableTaskPropertyObj.updateTaskPropertiesFromTableAttributes(task_id, data);
					}
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
						showAjaxLoginPopup(jquery_native_xhr_object.responseURL, url, function() {
							jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
							updateTaskTableAttributes(task_id, true);
						});
					else if (jqXHR.responseText)
						jsPlumbWorkFlow.jsPlumbStatusMessage.showError(jqXHR.responseText);
				},
			});
		}
	}
}

function saveDBDiagram() {
	prepareAutoSaveVars();
	
	if (jsPlumbWorkFlow.jsPlumbTaskFile.isWorkFlowChangedFromLastSaving()) {
		jsPlumbWorkFlow.jsPlumbTaskFile.save(null, {
			success: function(data, textStatus, jqXHR) {
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url, function() {
						jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
						
						saveDBDiagram();
					});
				else if (is_from_auto_save) {
					jsPlumbWorkFlow.jsPlumbStatusMessage.removeMessages("status");
					resetAutoSave();
				}
			},
			timeout: is_from_auto_save && auto_save_connection_ttl ? auto_save_connection_ttl : 0,
		});
	}
	else if (!is_from_auto_save)
		jsPlumbWorkFlow.jsPlumbStatusMessage.showMessage("Nothing to save.");
	else
		resetAutoSave();

	return false;
}

function updateDBDiagram() {
	jsPlumbWorkFlow.jsPlumbTaskFile.update(get_updated_db_diagram_url, {
		success: function(data, textStatus, jqXHR) {
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url, function() {
					jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
					updateDBDiagram();
				});
		},
		error: function(jqXHR, textStatus, errorThrown) {
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, [ jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url, get_updated_db_diagram_url ], function() {
					jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
					updateDBDiagram();
				});
		},
	});

	return false;
}

function updateTasksAfterFileRead() {
	$(jsPlumbWorkFlow.jsPlumbTaskFlow.target_selector).each(function(idx, elm) {
		var task_id = $(elm).attr("id");
		
		DBTableTaskPropertyObj.prepareTableForeignKeys(task_id);
	});
	
	prepareTasksTableConnections(); //This function is in tasks/dbdiagram/global.js
	
	$(".loading_panel").hide();
}

function addNewTable() {
	var task_id = jsPlumbWorkFlow.jsPlumbContextMenu.addTaskByType(task_type_id);
	jsPlumbWorkFlow.jsPlumbTaskFlow.setTaskLabelByTaskId(task_id, {label: null}); //set {label: null}, so the jsPlumbTaskFlow.setTaskLabel method ignores the prompt and adds the default label or an auto generated label.
	
	//add id, created_date and modified_date attributes by default
	var task_label = jsPlumbWorkFlow.jsPlumbTaskFlow.getTaskLabelByTaskId(task_id);
	var id_attribute_name = "id_" + task_label.toLowerCase().replace(/ /g, "_").replace(/_/g, "_");
	id_attribute_name = normalizeTaskTableName(id_attribute_name);
	
	var task_property_values = jsPlumbWorkFlow.jsPlumbTaskFlow.tasks_properties[task_id];
	task_property_values = task_property_values ? task_property_values : {};
	
	task_property_values.table_attr_names = [id_attribute_name, "created_date", "modified_date"];
	task_property_values.table_attr_primary_keys = ["1", null, null];
	task_property_values.table_attr_types = ["bigint", "timestamp", "timestamp"];
	task_property_values.table_attr_lengths = ["20", null, null];
	task_property_values.table_attr_nulls = [null, "1", "1"];
	task_property_values.table_attr_unsigneds = ["1", null, null];
	task_property_values.table_attr_uniques = ["1", null, null];
	task_property_values.table_attr_auto_increments = ["1", null, null];
	task_property_values.table_attr_has_defaults = [null, null, null];
	task_property_values.table_attr_defaults = [null, null, null];
	task_property_values.table_attr_extras = [null, null, null];
	task_property_values.table_attr_charsets = [null, null, null];
	task_property_values.table_attr_collations = [null, null, null];
	task_property_values.table_attr_comments = [null, null, null];
	jsPlumbWorkFlow.jsPlumbTaskFlow.tasks_properties[task_id] = task_property_values;
	
	DBTableTaskPropertyObj.prepareTableAttributes(task_id, task_property_values);
	
	//open properties
	jsPlumbWorkFlow.jsPlumbProperty.showTaskProperties(task_id);
}

function createDiagamSQL() {
	var popup = $('.create_diagram_sql_popup');
	
	if (!popup[0]) {
		popup = $('<div class="create_diagram_sql_popup myfancypopup"><iframe></iframe></div>');
		$("body").append(popup);
	}
	else {
		//remove and readd iframe so we don't see the previous loaded html
		popup.children("iframe").remove(); 
		popup.append('<iframe></iframe>');
	}
	
	MyFancyPopupCreateDiagramSQL.init({
		elementToShow: popup,
		parentElement: document,
		type: "iframe",
		url: create_diagram_sql_url,
	});
	
	MyFancyPopupCreateDiagramSQL.showPopup();
}
