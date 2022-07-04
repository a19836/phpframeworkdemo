$(function() {
	var edit_single_query = $(".edit_single_query");
	
	//init auto save
	addAutoSaveMenu(".top_bar li.sub_menu li.save", "onToggleQueryAutoSave");
	addAutoConvertMenu(".top_bar li.sub_menu li.save", "onToggleQueryAutoConvert");
	enableAutoSave(onToggleQueryAutoSave);
	
	if (is_covertable_sql)
		enableAutoConvert(onToggleQueryAutoConvert);
	else
		disableAutoConvert(onToggleQueryAutoConvert);
	
	initAutoSave(".top_bar li.sub_menu li.save a");
	
	//init ui
	var relationship = edit_single_query.find(".data_access_obj .relationships .relationship");
	var query = relationship.find(".query");
	var rand_number = query.attr("rand_number");
	var select = relationship.find(".rel_type select");
	select.attr("onChange", "updateSingleQueryRelationshipType(this, " + rand_number + ")");
	
	updateSingleQueryRelationshipType(select[0], rand_number);
	
	relationship.css("display", "block");
	
	//init main settings panel
	initMainSettingsPanel();
	
	//load sql
	var a = $(".query_tabs .query_sql_tab a").first();
	a.attr("not_create_sql_from_ui", 1);
	a.click();
	a.removeAttr("not_create_sql_from_ui");
	
	//update design with query
	var a = $(".query_tabs .query_design_tab a").first();
	a.attr("do_not_confirm", 1);
	a.click();
	a.removeAttr("do_not_confirm");
	
	//hide advanced settings
	var advanced_query_settings = query.find(".query_settings .advanced_query_settings");
	showOrHideExtraQuerySettings(advanced_query_settings[0], rand_number);
	
	//show query properties
	showOrHideSingleQuerySettings($(".top_bar .toggle_settings a")[0], rand_number);
	
	//set sync_ui_settings_with_sql to 1 so it updates automatically the sql query on every change on UI.
	eval('var WF = jsPlumbWorkFlow_' + rand_number + ';');
	var main_tasks_flow_obj = $("#" + WF.jsPlumbTaskFlow.main_tasks_flow_obj_id);
	main_tasks_flow_obj.attr("sync_ui_settings_with_sql", 1);
	main_tasks_flow_obj.attr("sync_sql_with_ui_settings", 1);
	
	$(window).resize(function() {
		WF.getMyFancyPopupObj().updatePopup();
	});
});

function onToggleQueryAutoConvert() {
	onToggleAutoConvert();
	
	var rand_number = $(".edit_single_query .data_access_obj .relationships .relationship .query").attr("rand_number");
	eval('var WF = jsPlumbWorkFlow_' + rand_number + ';');
	var main_tasks_flow_obj = $("#" + WF.jsPlumbTaskFlow.main_tasks_flow_obj_id);
	
	if (auto_convert) {
		main_tasks_flow_obj.attr("sync_ui_settings_with_sql", 1);
		main_tasks_flow_obj.attr("sync_sql_with_ui_settings", 1);
		
		onBlurQuerySqlEditor(rand_number);
	}
	else {
		main_tasks_flow_obj.attr("sync_ui_settings_with_sql", 0);
		main_tasks_flow_obj.attr("sync_sql_with_ui_settings", 0);
	}
}

function updateSingleQueryRelationshipType(elm, rand_number) {
	updateRelationshipType(elm, rand_number);
	
	var rel_type = $(elm).val();
	var menus = $(".edit_single_query > .top_bar ul .select_query");
	
	if (rel_type == "select")
		menus.show();
	else
		menus.hide();
}

function showOrHideSingleQuerySettings(elm, rand_number) {
	elm = $(elm);
	var input = elm.children("input");
	var span = elm.children("span");
	var query_select = $(".edit_single_query .data_access_obj .relationships .relationship .query .query_select");
	var settings = query_select.find(".query_settings");
	
	if (settings[0]) {
		eval('var WF = jsPlumbWorkFlow_' + rand_number + ';');
		
		if(settings.css("display") == "none") {//show
			input.attr("checked", "checked").prop("checked", true);
			span.html("Hide Query Settings");
			
			settings.slideDown("slow", function() {
				elm.addClass("active");
				query_select.removeClass("hide_query_settings");
				
				MyFancyPopup.updatePopup();
				WF.getMyFancyPopupObj().updatePopup();
			});
		}
		else {//hide
			input.removeAttr("checked").prop("checked", false);
			span.html("Show Query Settings");
			
			settings.slideUp("slow", function() {
				elm.removeClass("active");
				query_select.addClass("hide_query_settings");
				
				MyFancyPopup.updatePopup();
				WF.getMyFancyPopupObj().updatePopup();
			});
		}
	}
}

function showOrHideSingleQueryUI(elm, rand_number) {
	elm = $(elm);
	var input = elm.children("input");
	var span = elm.children("span");
	var query_select = $(".edit_single_query .data_access_obj .relationships .relationship .query .query_select");
	var a = query_select.find(".query_ui .taskflowchart .workflow_menu .toggle_ui a")[0];
	var is_shown = elm.hasClass("active");
	
	eval('var WF = jsPlumbWorkFlow_' + rand_number + ';');
	
	if (is_shown) {
		elm.removeClass("active");
		input.removeAttr("checked").prop("checked", false);
		span.html("Show Query Diagram");
	}
	else {
		elm.addClass("active");
		input.attr("checked", "checked").prop("checked", true);
		span.html("Hide Query Diagram");
		query_select.removeClass("hide_taskflowchart");
		
		MyFancyPopup.updatePopup();
		WF.getMyFancyPopupObj().updatePopup();
	}
	
	showOrHideQueryUI(a, rand_number, {
		callback: function() {
			if (is_shown) {
				query_select.addClass("hide_taskflowchart");
				
				MyFancyPopup.updatePopup();
				WF.getMyFancyPopupObj().updatePopup();
			}
		}
	});
}

function onToggleFullScreen(in_full_screen) {
	var query = $(".edit_single_query .data_access_obj .relationships .relationship .query");
	var rand_number = query.attr("rand_number");
	eval('var WF = jsPlumbWorkFlow_' + rand_number + ';');
	
	setTimeout(function() {
		MyFancyPopup.updatePopup();
		WF.getMyFancyPopupObj().updatePopup();
	}, 500);
}

function onChangeIsConvertableSQL(elm) {
	elm = $(elm);
	var edit_single_query = $(".edit_single_query");
	var input = elm.find("input");
	var span = elm.find("span");
	
	edit_single_query.toggleClass("covertable_sql");
	
	var is_covertable_sql = edit_single_query.is(".covertable_sql");
	
	if (is_covertable_sql) {
		input.attr("checked", "checked").prop("checked", true);
		span.html("Disable SQL convertable");
		
		if (elm.attr("previous_auto_convert") == 1) {
			enableAutoConvert(onToggleQueryAutoConvert);
			
			var query = edit_single_query.find(".data_access_obj .relationships .relationship .query");
			query.find(".rel_type select").trigger("change");
			
			var rand_number = query.attr("rand_number");
			onBlurQuerySqlEditor(rand_number);
		}
	}
	else {
		input.removeAttr("checked", "checked").prop("checked", false);
		span.html("Enable SQL convertable");
		elm.attr("previous_auto_convert", auto_convert ? 1 : 0);
		disableAutoConvert(onToggleQueryAutoConvert);
	}
}
