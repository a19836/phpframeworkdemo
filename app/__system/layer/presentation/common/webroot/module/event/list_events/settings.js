$(function () {
	$(".list_settings .prop_photo .settings_prop_search_value").remove();
});

function updateEventsSelectionType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var list_settings = elm.parent().parent();
	
	list_settings.children(".catalog_by_parent, .catalog_by_tags, .catalog_by_selected_events").hide();
	list_settings.find(".catalog_by_parent > .catalog_parent_group, .catalog_sort_column select .tag_order, .catalog_sort_column select .parent_order").hide();
	
	if (value == "tags_and" || value == "tags_or" || value == "parent_tags_and" || value == "parent_tags_or" || value == "parent_group_tags_and" || value == "parent_group_tags_or") {
		list_settings.children(".catalog_by_tags").show();
		list_settings.children(".catalog_sort_column").children("select").children(".tag_order").show();
	}
	else if (value == "selected") {
		list_settings.children(".catalog_by_selected_events").show();
	}
	
	if (value == "parent" || value == "parent_tags_and" || value == "parent_tags_or") {
		list_settings.children(".catalog_by_parent").show();
		list_settings.children(".catalog_sort_column").children("select").children(".parent_order").show();
	}
	else if (value == "parent_group" || value == "parent_group_tags_and" || value == "parent_group_tags_or") {
		var cp = list_settings.children(".catalog_by_parent");
		cp.show();
		cp.children(".catalog_parent_group").show();
		list_settings.children(".catalog_sort_column").children("select").children(".parent_order").show();
	}
}

function loadListEventsBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var list_settings = settings_elm.children(".list_settings");
	
	if (!settings_values || ($.isArray(settings_values) && settings_values.length == 0)) {
		settings_values = {};
	}
	
	loadListSettingsBlockSettings(settings_elm, settings_values);
	
	updateEventsSelectionType( list_settings.find(".events_type select")[0] );
	
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
				list_settings.find(".catalog_by_parent .catalog_parent_object_type_id select").html(options);
				
				options = '';
				$.each(all_events, function(index, event) {
					if ($.inArray(event["event_id"], event_ids) != -1) {
						events[ event["event_id"] ] = event;
					}
					
					options += '<option value="' + event["event_id"] + '">' + event["title"] + '</option>';
				});
				list_settings.find(".catalog_by_selected_events .available_events select").html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load all events.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	if (!jQuery.isEmptyObject(events) && $.isArray(event_ids)) {
		var table = list_settings.find(".catalog_by_selected_events .selected_events table").first();
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
