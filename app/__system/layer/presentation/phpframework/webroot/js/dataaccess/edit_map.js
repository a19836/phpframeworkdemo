$(function() {
	//init auto save
	addAutoSaveMenu(".top_bar li.save");
	enableAutoSave(onToggleAutoSave);
	initAutoSave(".top_bar li.save a");
});

function createSingleMapParameterOrResultMapAutomatically(type) {
	$(".edit_map .data_access_obj > .relationships").children(".parameters_maps, .results_maps").children(".parameters, .results").children(".map").children(".update_automatically").trigger("click");
}

function toggleMapClass(elm, prefix_class) {
	elm = $(elm);
	var selector = (prefix_class ? prefix_class : "") + " .data_access_obj";
	var data_access_obj = $(selector);
	var is_shown = elm.hasClass("active");
	
	data_access_obj.find(".relationships .map").find(".map_class").each(function(idx, child) {
		child = $(child);
		
		if (is_shown)
			child.slideUp("fast", function() {
				child.hide();
			});
		else
			child.slideDown("fast", function() {
				child.show();
			});
	}).promise().done(function () { 
		if (is_shown) {
			elm.removeClass("active");
			data_access_obj.removeClass("with_map_fields");
		}
		else {
			elm.addClass("active");
			data_access_obj.addClass("with_map_fields");
		}
	});
}
