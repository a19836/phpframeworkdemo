var ModuleAdminPanelFancyPopup = new MyFancyPopupClass();

$(function () {
	$(window).bind('beforeunload', function () {
		if (isBlockCodeObjChanged()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	//init auto save
	addAutoSaveMenu(".top_bar li.sub_menu li.save");
	enableAutoSave(onToggleAutoSave);
	initAutoSave(".top_bar li.save a");
	
	//init trees
	choosePageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePageUrlFromFileManagerTree.init("choose_page_url_from_file_manager");
	
	chooseImageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotAPossibleImageFromTree,
	});
	chooseImageUrlFromFileManagerTree.init("choose_image_url_from_file_manager");
	
	//init ui
	var block_obj = $(".block_obj");
	
	if (block_obj[0]) {
		if (load_module_settings_function && typeof load_module_settings_function == "function")
			load_module_settings_function(block_obj.find(".module_settings .settings"), block_settings_obj);
		
		if (typeof block_join_points_settings_objs != "undefined") {
			var join_points_elms = $(".module_join_points > .join_points > .join_point");
			onLoadBlockJoinPoints(join_points_elms, block_join_points_settings_objs, available_block_local_join_point);
		}
		
		//set saved_obj_id
		saved_obj_id = getBlockCodeObjId();
	}
	
	MyFancyPopup.hidePopup();
});

function showOrHideModuleData(elm) {
	elm = $(elm);
	var module_data = $(".block_obj > .module_data");
	var p = elm.parent();
	var input = p.find("input");
	var span = p.find("span");
	
	if (module_data[0]) {
		if (module_data.css("display") == "none") {//show
			module_data.slideDown("slow");
			elm.addClass("active");
			input.attr("checked", "checked").prop("checked", true);
			span.html("Hide Module Info");
		}
		else {//hide
			module_data.slideUp("slow");
			elm.removeClass("active");
			input.removeAttr("checked").prop("checked", false);
			span.html("Show Module Info");
		}
	}
}


function openModuleAdminPanelPopup() {
	var popup = $(".module_admin_panel_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup' + (is_popup ? " in_popup" : "") + ' module_admin_panel_popup"><iframe src="' + module_admin_panel_url + '"></iframe></div>');
		$(document.body).append(popup);
	}
	
	ModuleAdminPanelFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
	});
	ModuleAdminPanelFancyPopup.showPopup();
}

/* SAVING FUNCTIONS */

function getBlockCodeObjId() {
	var obj = getBlockCodeObj();
	
	return $.md5(save_object_url + JSON.stringify(obj));
}

function isBlockCodeObjChanged() {
	var block_obj = $(".block_obj");
	
	if (!block_obj[0])
		return false;
	
	var new_saved_obj_id = getBlockCodeObjId();
	
	return saved_obj_id != new_saved_obj_id;
}

function getBlockCodeObj() {
	var block_obj = $(".block_obj");
	var module_id = block_obj.children(".module_data").children("input").val();
	var join_points_elm = block_obj.find(".module_join_points .join_points");
	var joint_points = getBlockJoinPointsObjForSaving(join_points_elm, "module_join_points_property");
	
	var obj = {
		"module_id": module_id,
		"join_points": joint_points,
	};
	
	var settings_elm = block_obj.find(".module_settings .settings");
	
	if (settings_elm[0])
		obj["settings"] = getBlockSettingsObjForSaving(settings_elm);
	
	return obj;
}

function saveBlock(opts) {
	var obj = getBlockCodeObj(); //obj includes settings
	
	return saveBlockObj(obj, opts);
}

function saveBlockRawCode(code, opts) {
	var obj = getBlockCodeObj();
	delete obj["settings"]; //obj does NOT include settings
	obj["code"] = code;
	
	return saveBlockObj(obj, opts);
}

function saveBlockObj(obj, opts) {
	var block_obj = $(".block_obj");
	var status = false;
	
	prepareAutoSaveVars();
	
	if (block_obj[0]) {
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
	}
	else if (!is_from_auto_save)
		alert("No block object to save! Please contact the sysadmin...");
	
	return status;
}
