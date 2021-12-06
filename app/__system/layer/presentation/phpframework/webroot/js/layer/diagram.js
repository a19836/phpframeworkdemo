;(function() {
	/*$(window).bind('beforeunload', function () {
		if (window.parent && window.parent.iframe_overlay)
			window.parent.iframe_overlay.hide();
		
		return "Changes you made may not be saved. Click cancel to save them first, otherwise to continue...";
	});*/
	
	jsPlumbWorkFlow.jsPlumbTaskFlow.default_connection_connector = "Straight";
	jsPlumbWorkFlow.jsPlumbTaskFlow.default_connection_overlay = "Forward Arrow";
	jsPlumbWorkFlow.jsPlumbTaskFlow.available_connection_overlays_type = ["Forward Arrow"];
	
	jsPlumbWorkFlow.jsPlumbTaskFile.on_success_read = updateTasksAfterFileRead;
	jsPlumbWorkFlow.jsPlumbTaskFile.on_success_update = updateTasksAfterFileRead;
})();

function updateTasksAfterFileRead() {
	//load tasks properties
	var tasks = jsPlumbWorkFlow.jsPlumbTaskFlow.getAllTasks();
	
	if (tasks)
		for (var i = 0, l = tasks.length; i < l; i++) {
			var task = $(tasks[i]);
			var task_id = task.attr("id");
			var task_properties = jsPlumbWorkFlow.jsPlumbTaskFlow.tasks_properties[task_id];
			var is_active = task_properties && parseInt(task_properties["active"]) == 1 || ("" + task_properties["active"]).toLowerCase() == "true";
			
			if (is_active)
				task.addClass("active");
			else
				task.removeClass("active");
			
			prepareLayerTaskActiveStatus(task);
		}
}

function saveDBDiagram() {
	jsPlumbWorkFlow.jsPlumbTaskFile.save(null, {
		success: function(data, textStatus, jqXHR) {
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url, function() {
					jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
					saveDBDiagram();
				});
		},
	});

	return false;
}

function onOpenGlobalSettingsAndVars() {
	var close_popup_func = function(e) {
		e.preventDefault();
		
		if (confirm("You are about to close this popup and loose the unsaved changes. Do you wish to proceed?"))
			jsPlumbWorkFlow.getMyFancyPopupObj().hidePopup();
	};
	
	var close_btn = jsPlumbWorkFlow.getMyFancyPopupObj().getPopupCloseButton();
	close_btn.off("click");
	close_btn.click(close_popup_func);
	
	var overlay = jsPlumbWorkFlow.getMyFancyPopupObj().getOverlay();
	overlay.off("click");
	overlay.click(close_popup_func);
}
