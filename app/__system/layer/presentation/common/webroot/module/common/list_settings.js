$(function () {
	var fields = $(".settings_prop").children(".selected_task_properties").children(".form_containers").children(".fields").children(".field");
	fields.children(".disable_field_group").remove();
	
	initObjectBlockSettings("list_settings", saveListSettings, "saveListSettings");
});

//To be called by the modules that call \CommonModuleUI::getListHtml($EVC, $settings); and that don't want to have the pagination code in the PTL code.
//Basically this code removes the pagination ptl from the code variable
function onListUpdatePTLFromFieldsSettingsWithNoPagination(elm, settings, code, external_vars) {
	if (code) {
		var pagination_classes = ["top_pagination", "bottom_pagination", "pagination"];
		
		for (var i in pagination_classes) {
			var c = pagination_classes[i];
			var pos = code.indexOf('class="' + c);
			
			if (pos != -1) {
				var start_pos = code.substr(0, pos).lastIndexOf("<div ");
				
				if (start_pos != -1) {
					var end_pos = code.indexOf("</div>", start_pos);
					end_pos = end_pos != -1 ? end_pos + 6 : code.length; //</div> == 6
					
					code = code.substr(0, start_pos) + code.substr(end_pos);
				}
			}
		}
	}
	
	return code.trim();
}
