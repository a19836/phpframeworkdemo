function hideOrShowIsBusinessLogicService(elm) {
	var is_business_logic_service = $(elm).val();
	
	var settings = $(elm).parent().parent();
	
	if (is_business_logic_service == 1) {
		settings.children(".type, .abstract, .static, .arguments").hide();
		settings.find(".arguments .fields").html("");
		addNewArgument( settings.find(".arguments thead .add")[0] );
	}
	else {
		if (settings.hasClass("function_settings")) {
			settings.children(".arguments").show();
			settings.children(".type, .abstract, .static").hide();
		}
		else {
			settings.children(".type, .abstract, .static, .arguments").show();
		}
	}
}

function saveMethod() {
	var obj = getFileClassMethodObj();
	obj["is_business_logic_service"] = $(".file_class_method_obj > #settings > .is_business_logic_service").children("select").val();
	
	var save_url = save_object_url.replace("#method_id#", original_method_id);
	
	saveObjCode(save_url, obj, {
		success: function(data, textStatus, jqXHR) {
			if (original_method_id != obj["name"]) {
				original_method_id = obj["name"];
				window.parent.refreshLastNodeParentChilds();
			}
			
			return true;
		}
	});
}
