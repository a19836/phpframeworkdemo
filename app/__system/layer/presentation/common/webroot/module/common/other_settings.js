var object_types = null;

function loadObjectToObjectsBlockSettings(parent_elm, settings_values, class_name) {
	class_name = class_name ? class_name : "object_to_objects";
	
	var object_to_objects_elm = parent_elm.children("." + class_name);
	
	if (object_to_objects_elm[0]) {
		var object_to_objects_items = settings_values ? prepareBlockSettingsItemValue(settings_values[class_name]) : null;
		
		if (!object_types) {
			$.ajax({
				url: call_module_file_prefix_url.replace("#module_file_path#", "get_object_types"),
				success: function(data) {
					if (data) {
						var options = '<option value=""></option>';
						$.each(data, function(index, object_type) {
							options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
						});
						object_to_objects_elm.find(".object_type_id").children("select").html(options);
					
						object_types = data;
					}
				},
				error: function() {
					StatusMessageHandler.showError("Error trying to load data.\nPlease try again...");
				},
				dataType: "json",
				async: false,
			});
		}
		else {
			var options = '<option value=""></option>';
			$.each(object_types, function(index, object_type) {
				options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
			});
			object_to_objects_elm.find(".object_type_id").children("select").html(options);
		}
		
		if (!jQuery.isEmptyObject(object_to_objects_items)) {
			var add_icon = object_to_objects_elm.children(".add");
			var object_to_object_elms = object_to_objects_elm.children(".object_to_object");
			eval ('var object_to_object_html = ' + class_name + '_html;');
			var elms_idx = 0;
			
			$.each(object_to_objects_items, function(idx, value) {
				var item = object_to_objects_items[idx];
				var o2o_elm = object_to_object_elms[elms_idx];
				
				if (!o2o_elm) 
					o2o_elm = addObjectToObjectItem(add_icon[0], object_to_object_html);
				else
					o2o_elm = $(o2o_elm);
				
				o2o_elm.children(".object_type_id").children("select").val(item["object_type_id"]);
				o2o_elm.children(".object_id").children("input").val(item["object_id"]);
				o2o_elm.children(".object_group").children("input").val(item["group"]);
				
				elms_idx++;
			});
		}
	}
}

function addObjectToObjectItem(elm, object_to_object_html) {
	var p = $(elm).parent();
	var idx = parseInt( p.attr("count") );
	idx = idx >= 1 ? idx + 1 : 1;
	p.attr("count", idx);
	
	var o2o_elm = $(object_to_object_html.replace(/#idx#/g, idx));
	p.append(o2o_elm);
	
	if (object_types) {
		var options = '<option value=""></option>';
		$.each(object_types, function(index, object_type) {
			options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
		});
		o2o_elm.children(".object_type_id").children("select").html(options);
	}
	
	return o2o_elm;
}
