var ModuleAdminPanelFancyPopup = new MyFancyPopupClass();

$(function () {
	/*$(window).bind('beforeunload', function () {
		if (window.parent && window.parent.iframe_overlay)
			window.parent.iframe_overlay.hide();
		
		return "Changes you made may not be saved. Click cancel to save them first, otherwise to continue...";
	});*/
	
	choosePageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePageUrlFromFileManagerTree.init("choose_page_url_from_file_manager");
	
	chooseImageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotAPossibleImageFromTree,
	});
	chooseImageUrlFromFileManagerTree.init("choose_image_url_from_file_manager");
	
	if (load_module_settings_function && typeof load_module_settings_function == "function")
		load_module_settings_function($(".block_obj .module_settings .settings"), block_settings_obj);
	
	var join_points_elms = $(".module_join_points > .join_points > .join_point");
	onLoadBlockJoinPoints(join_points_elms, block_join_points_settings_objs, available_block_local_join_point);
	
	MyFancyPopup.hidePopup();
});

function openModuleAdminPanelPopup() {
	var popup = $(".module_admin_panel_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup module_admin_panel_popup"><iframe src="' + module_admin_panel_url + '"></iframe></div>');
		$(document.body).append(popup);
	}
	
	ModuleAdminPanelFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
	});
	ModuleAdminPanelFancyPopup.showPopup();
}

/* SAVING FUNCTIONS */

function saveBlock(opts) {
	var block_obj = $(".block_obj");
	
	var module_id = block_obj.children(".module_data").children("input").val();
	var settings_elm = block_obj.find(".module_settings .settings");
	var join_points_elm = block_obj.find(".module_join_points .join_points");
	var joint_points = getBlockJoinPointsObjForSaving(join_points_elm, "module_join_points_property");
	
	var obj = {
		"module_id": module_id,
		"settings": getBlockSettingsObjForSaving(settings_elm),
		"join_points": joint_points,
	};
	
	return saveBlockObj(obj, opts);
}

function saveBlockRawCode(code, opts) {
	var block_obj = $(".block_obj");
	
	var module_id = block_obj.children(".module_data").children("input").val();
	var join_points_elm = block_obj.find(".module_join_points .join_points");
	var joint_points = getBlockJoinPointsObjForSaving(join_points_elm, "module_join_points_property");
	
	var obj = {
		"module_id": module_id,
		"code": code,
		"join_points": joint_points,
	};
	
	return saveBlockObj(obj, opts);
}

function saveBlockObj(obj, opts) {
	var status = false;
	
	saveObj(save_object_url, obj, {
		success: function(data, textStatus, jqXHR) {
			var json_data = data && ("" + data).substr(0, 1) == "{" ? JSON.parse(data) : null;
			status = parseInt(data) == 1 || ($.isPlainObject(json_data) && json_data["status"] == 1);
			
			if (opts && typeof opts["success"] == "function")
				opts["success"]();
			
			return true;
		},
		async: false,
	});
	
	return status;
}
