$(function () {
	var settings_prop = $(".settings_prop");
	var fields = settings_prop.children(".selected_task_properties").find(".fields .field");
	
	settings_prop.children(".settings_prop_default_value").remove();
	
	fields.children(".input_settings").children(".input_name").hide();
	
	initObjectBlockSettings("edit_settings", saveEditSettings, "saveEditSettings");
	
	$(".catalog_settings > .els > .els_tabs > li").click(function (idx, li) {
		updateEventsCatalogType( $(".catalog_settings > .catalog_type > select")[0] );
	});
});

function onEventCatalogUpdatePTLFromFieldsSettings(elm, settings, code, external_vars) {
	var catalog_settings = $(elm).parent().closest(".catalog_settings");
	var catalog_type = catalog_settings.find(" > .catalog_type > select").val();
	
	if (catalog_type == "user_list") {
		var event_properties_url = catalog_settings.find(" > .event_properties_url > input").val();
		
		code = '' +
			"\n" + '<div class="top_pagination">' +
			"\n" + '	<ptl:block:top-pagination/>' +
			"\n" + '</div>' +
			"\n" + '<ul class="catalog catalog_normal_list">' +
			"\n" + '	<ptl:if is_array(\\$input_data)>' +
			"\n" + '		<ptl:foreach \\$input_data i event>' +
			"\n" + '			<li class="event">' + 
			"\n" + '				<a href="' + (event_properties_url != "" ? event_properties_url : '?event_id=') + '<ptl:echo \\$event[event_id]/>' + '">' +
			"\n" + '					' + code.replace(/\n/g, "\n\t\t\t\t\t") +
			"\n" + '				</a>' +
			"\n" + '			</li>' +
			"\n" + '		</ptl:foreach>' +
			"\n" + '	<ptl:else>' +
			"\n" + '		<li><h3 class="no_events">There are no available events...</h3></li>' + 
			"\n" + '	</ptl:if>' +
			"\n" + '</ul>' +
			"\n" + '<div class="bottom_pagination">' +
			"\n" + '	<ptl:block:bottom-pagination/>' +
			"\n" + '</div>';
		
		external_vars["events_item_input_data_var_name"] = "event";
	}
	
	return code;
}

function updateEventsCatalogType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var catalog_settings = elm.parent().parent();
	
	var event_properties_url = catalog_settings.children(".event_properties_url");
	var alignments = catalog_settings.children(".pagination").find(" > .top_pagination > select[name='top_pagination_alignment'], > .bottom_pagination > select[name='bottom_pagination_alignment']");
	
	event_properties_url.show();
	alignments.show();
	
	if (value == "blog_list")
		catalog_settings.children(".catalog_blog_list").show();
	else {
		catalog_settings.children(".catalog_blog_list").hide();
		
		var ptl_tab_selected = catalog_settings.find("> .els > .els_tabs > .ptl_tab").hasClass("ui-tabs-active");
		
		if (value == "user_list" && ptl_tab_selected) {
			alignments.hide();
			event_properties_url.hide();
		}
	}
}

function updateEventsSelectionType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var catalog_settings = elm.parent().parent();
	
	catalog_settings.children(".catalog_by_parent, .catalog_by_tags, .catalog_by_selected_events").hide();
	catalog_settings.find(".catalog_by_parent > .catalog_parent_group, .catalog_sort_column select .tag_order, .catalog_sort_column select .parent_order").hide();
	
	if (value == "tags_and" || value == "tags_or" || value == "parent_tags_and" || value == "parent_tags_or" || value == "parent_group_tags_and" || value == "parent_group_tags_or") {
		catalog_settings.children(".catalog_by_tags").show();
		catalog_settings.children(".catalog_sort_column").children("select").children(".tag_order").show();
	}
	else if (value == "selected") {
		catalog_settings.children(".catalog_by_selected_events").show();
	}
	
	if (value == "parent" || value == "parent_tags_and" || value == "parent_tags_or") {
		catalog_settings.children(".catalog_by_parent").show();
		catalog_settings.children(".catalog_sort_column").children("select").children(".parent_order").show();
	}
	else if (value == "parent_group" || value == "parent_group_tags_and" || value == "parent_group_tags_or") {
		var cp = catalog_settings.children(".catalog_by_parent");
		cp.show();
		cp.children(".catalog_parent_group").show();
		catalog_settings.children(".catalog_sort_column").children("select").children(".parent_order").show();
	}
}

function loadEventsCatalogBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var catalog_settings = settings_elm.children(".catalog_settings");
	
	if (!settings_values || ($.isArray(settings_values) && settings_values.length == 0)) {
		settings_values = {};
	}
	
	if (!settings_values.hasOwnProperty("blog_introduction_events_num")) {
		settings_values["blog_introduction_events_num"] = {"value": 1};
	}
	if (!settings_values.hasOwnProperty("blog_featured_events_num")) {
		settings_values["blog_featured_events_num"] = {"value": 2};
	}
	if (!settings_values.hasOwnProperty("blog_featured_events_cols")) {
		settings_values["blog_featured_events_cols"] = {"value": 3};
	}
	if (!settings_values.hasOwnProperty("blog_listed_events_num")) {
		settings_values["blog_listed_events_num"] = {"value": 10};
	}
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	
	updateEventsCatalogType( catalog_settings.find(".catalog_type select")[0] );
	updateEventsSelectionType( catalog_settings.find(".events_type select")[0] );
	
	var event_ids = prepareBlockSettingsItemValue(settings_values["event_ids"]);
	
	var url = call_module_file_prefix_url.replace("#module_file_path#", "get_data");
	var events = {};
	
	$.ajax({
		url: url,
		success: function(data) {
			if (data) {
				var object_types = data["object_types"];
				var all_events = data["events"];
				
				var selected_object_type_id = prepareBlockSettingsItemValue(settings_values["object_type_id"]);
				
				var options = '';
				$.each(object_types, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '"' + (selected_object_type_id == object_type["object_type_id"] ? ' selected' : '') + '>' + object_type["name"] + '</option>';
				});
				catalog_settings.find(".catalog_by_parent .catalog_parent_object_type_id select").html(options);
				
				options = '';
				$.each(all_events, function(index, event) {
					if ($.inArray(event["event_id"], event_ids) != -1) {
						events[ event["event_id"] ] = event;
					}
					
					options += '<option value="' + event["event_id"] + '">' + event["title"] + '</option>';
				});
				catalog_settings.find(".catalog_by_selected_events .available_events select").html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load all events.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	if (!jQuery.isEmptyObject(events) && $.isArray(event_ids)) {
		var table = catalog_settings.find(".catalog_by_selected_events .selected_events table").first();
		table.find(".no_events").hide();
		
		var html = '';
		
		for (var i = 0; i < event_ids.length; i++) {
			var event_id = event_ids[i];
			
			if (!jQuery.isEmptyObject(events[event_id])) {
				html += getEventHtml(event_id, events[event_id]["title"]);
			}
		}
		
		table.append(html);
	}
	
	MyFancyPopup.hidePopup();
}

function getEventHtml(event_id, event_title) {
	return '<tr class="event">'
	+ '	<td class="event_id">' + event_id + '</td>'
	+ '	<td class="event_title">' + event_title + '</td>'
	+ '	<td class="buttons">'
	+ '		<input class="module_settings_property" type="hidden" name="event_ids[]" value="' + event_id + '" />'
	+ '		<span class="icon up" onClick="moveSelectedEventUp(this)">Move Up</span>'
	+ '		<span class="icon down" onClick="moveSelectedEventDown(this)">Move Down</span>'
	+ '		<span class="icon delete" onClick="removeSelectedEvent(this)">Remove</span>'
	+ '	</td>'
	+ '</tr>';
}

function addSelectedEvent(elm) {
	var p = $(elm).parent();
	var select = p.children("select");
	var event_id = select.val();
	var event_title = select.find(":selected").text();
	
	var table = p.parent().find(".selected_events table");
	
	var exists = table.find("tr.event .buttons input[value='" + event_id + "']");
	if (exists[0]) {
		StatusMessageHandler.showError("Event already exists!");
	}
	else {
		var html = getEventHtml(event_id, event_title);
		table.append(html);
		table.find(".no_events").hide();
	}
}

function moveSelectedEventUp(elm) {
	moveRegionBlock(elm, "up");
}

function moveSelectedEventDown(elm) {
	moveRegionBlock(elm, "down");
}

function moveRegionBlock(elm, direction) {
	var item = $(elm).parent().parent();
	
	if (direction == "up") {
		var prev = item.prev();
		if (prev.hasClass("event"))
			item.insertBefore(prev);
	}
	else {
		var next = item.next();
		if (next.hasClass("event"))
			item.insertAfter(next);
	}
}

function removeSelectedEvent(elm) {
	var tr = $(elm).parent().parent();
	var table = tr.parent();
	tr.remove();
	
	if (table.find("tr.event").length == 0) {
		table.find(".no_events").show();
	}
}
