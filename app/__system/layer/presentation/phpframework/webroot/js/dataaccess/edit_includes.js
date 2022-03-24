$(function() {
	//init auto save
	addAutoSaveMenu(".top_bar li.dummy_elm_to_add_auto_save_options");
	enableAutoSave(onToggleAutoSave);
	initAutoSave(".top_bar li.save a");
	
	//init ui
	$(".description").html("Add new Include:");
});

