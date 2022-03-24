$(function() {
	//init auto save
	/* The edit_relationship page already calls the edit_hbn_obj.js, whch already init the auto save.
	addAutoSaveMenu(".top_bar li.dummy_elm_to_add_auto_save_options");
	enableAutoSave(onToggleAutoSave);
	initAutoSave(".top_bar li.save a");*/
	
	//init ui
	$(".relationship").css("display", "block");
	$(".relationship .result_map_id .search").css("display", "none");
	
	$(".hbn_obj_relationships .advanced_query_settings").click();
	//$(".hbn_obj_relationships .advanced_query_settings").html("Show More Settings");
});

