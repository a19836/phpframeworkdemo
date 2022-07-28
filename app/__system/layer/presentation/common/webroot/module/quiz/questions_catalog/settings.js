$(function () {
	var settings_prop = $(".settings_prop");
	var fields = settings_prop.children(".selected_task_properties").find(".fields .field");
	
	settings_prop.children(".settings_prop_default_value").remove();
	//$(".edit_settings .ptl_default_values").remove(); //20190608 Not sure for what this is for. I believe this was for something old, that got forgothen here and now is not needed anymore.
	
	fields.children(".input_settings").children(".input_name").hide();
	
	initObjectBlockSettings("edit_settings", saveEditSettings, "saveEditSettings");
	
	$(".catalog_settings > .els > .els_tabs > li").click(function (idx, li) {
		updateQuestionsCatalogType( $(".catalog_settings > .catalog_type > select")[0] );
	});
});

function onQuestionCatalogUpdatePTLFromFieldsSettings(elm, settings, code, external_vars) {
	var catalog_settings = $(elm).parent().closest(".catalog_settings");
	var catalog_type = catalog_settings.find(" > .catalog_type > select").val();
	
	if (catalog_type == "user_list") {
		var question_properties_url = catalog_settings.find(" > .question_properties_url > input").val();
		
		code = '' +
			"\n" + '<div class="top_pagination">' +
			"\n" + '	<ptl:block:top-pagination/>' +
			"\n" + '</div>' +
			"\n" + '<ul class="catalog catalog_normal_list">' +
			"\n" + '	<ptl:if is_array(\\$input)>' +
			"\n" + '		<ptl:foreach \\$input i question>' +
			"\n" + '			<li class="question">' + 
			"\n" + '				<a href="' + (question_properties_url != "" ? question_properties_url : '?question_id=') + '<ptl:echo \\$question[question_id]/>' + '">' +
			"\n" + '					' + code.replace(/\n/g, "\n\t\t\t\t\t") +
			"\n" + '				</a>' +
			"\n" + '			</li>' +
			"\n" + '		</ptl:foreach>' +
			"\n" + '	<ptl:else>' +
			"\n" + '		<li><h3 class="no_questions">There are no available questions...</h3></li>' + 
			"\n" + '	</ptl:if>' +
			"\n" + '</ul>' +
			"\n" + '<div class="bottom_pagination">' +
			"\n" + '	<ptl:block:bottom-pagination/>' +
			"\n" + '</div>';
		
		external_vars["questions_item_input_data_var_name"] = "question";
	}
	
	return code;
}

function updateQuestionsCatalogType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var catalog_settings = elm.parent().parent();
	
	var question_properties_url = catalog_settings.children(".question_properties_url");
	var alignments = catalog_settings.children(".pagination").find(" > .top_pagination > select[name='top_pagination_alignment'], > .bottom_pagination > select[name='bottom_pagination_alignment']");
	
	question_properties_url.show();
	alignments.show();
	
	var ptl_tab_selected = catalog_settings.find("> .els > .els_tabs > .ptl_tab").hasClass("ui-tabs-active");
	
	if (value == "user_list" && ptl_tab_selected) {
		alignments.hide();
		question_properties_url.hide();
	}
}

function loadQuestionsCatalogBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var catalog_settings = settings_elm.children(".catalog_settings");
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_object_types"),
		success: function(data) {
			if (data) {
				var selected_object_type_id = prepareBlockSettingsItemValue(settings_values["object_type_id"]);
				
				var options = '';
				$.each(data, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '"' + (selected_object_type_id == object_type["object_type_id"] ? ' selected' : '') + '">' + object_type["name"] + '</option>';
				});
				catalog_settings.children(".catalog_by_parent").children(".catalog_parent_object_type_id").children("select").html('<option></option>' + options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load object types.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	
	updateQuestionsCatalogType( catalog_settings.find(".catalog_type select")[0] );
	onChangeQuestionsType( catalog_settings.find(".questions_type select")[0] );
	
	MyFancyPopup.hidePopup();
}

function onChangeQuestionsType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var catalog_settings = elm.parent().parent();
	
	catalog_settings.children(".catalog_by_parent").hide();
	catalog_settings.find(".catalog_by_parent > .catalog_parent_group").hide();
	
	if (value == "parent") {
		catalog_settings.children(".catalog_by_parent").show();
	}
	else if (value == "parent_group") {
		var cp = catalog_settings.children(".catalog_by_parent");
		cp.show();
		cp.children(".catalog_parent_group").show();
	}
}
