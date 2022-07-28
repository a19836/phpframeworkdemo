$(function () {
	//Fixing time input_value issue
	$(".prop_time").children(".selected_task_properties").children(".form_containers").children(".fields").children(".field").children(".input_settings").children(".input_name, .input_value").children("input").each(function(idx, elm) {
		elm = $(elm);
		
		var v = elm.val().replace("[time]", "[time_date]");
		elm.val(v);
	});
	
	//Fixing username input_value issue
	$(".prop_username").children(".selected_task_properties").children(".form_containers").children(".fields").children(".field").children(".input_settings").children(".input_name, .input_value").children("input").each(function(idx, elm) {
		elm = $(elm);
		
		var v = elm.val().replace("[username]", "[user_id]");
		elm.val(v);
	});
});
