$(function() {
	//init auto save
	addAutoSaveMenu(".top_bar li.dummy_elm_to_add_auto_save_options");
	enableAutoSave(onToggleAutoSave);
	initAutoSave(".top_bar li.save a");
});

function createSingleMapParameterOrResultMapAutomatically(type) {
	$(".edit_map .data_access_obj > .relationships").children(".parameters_maps, .results_maps").children(".parameters, .results").children(".map").children(".update_automatically").trigger("click");
}
