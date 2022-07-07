var chooseProjectFolderUrlFromFileManagerTree = null;

$(function () {
	//init ui
	MyFancyPopup.init({
		parentElement: window,
	});
	
	chooseProjectFolderUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotProjectFolderFromTree,
	});
	chooseProjectFolderUrlFromFileManagerTree.init("choose_project_folder_url_from_file_manager");
	
	MyFancyPopup.hidePopup();
});

function removeAllThatIsNotProjectFolderFromTree(ul, data) {
	ul = $(ul);
	
	ul.find("i.project, i.project_common").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
}

function onChooseProjectFolder(elm) {
	var p = $(elm).parent();
	var popup = $("#choose_project_folder_url_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			var html = popup.find(".mytree ul").html();
			
			if (!html) 
				updateLayerUrlFileManager( popup.find(".broker select")[0] );
		},
		
		targetField: p,
		updateFunction: chooseProjectFolder
	});
	
	MyFancyPopup.showPopup();
}

function chooseProjectFolder(elm) {
	var node = chooseProjectFolderUrlFromFileManagerTree.getSelectedNodes();
	node = node[0];
	
	if (node) {
		var a = $(node).children("a");
		var folder_path = a.attr("folder_path");
		var is_project_folder = a.children("i").first().is(".project_folder");
		
		if (folder_path && is_project_folder) {
			var p = MyFancyPopup.settings.targetField;
			p.children("input[name=project_folder]").val(folder_path);
			
			MyFancyPopup.hidePopup();
		}
		else
			alert("invalid selected project folder.\nPlease choose a valid project folder.");
	}
}

function updateLayerFileManagers(elm) {
	var selected_opt = $(elm).find("option:selected");
	var bean_name = selected_opt.attr("bean_name");
	var bean_file_name = selected_opt.attr("bean_file_name");
	
	var select = $(".choose_project_folder_url_from_file_manager > .broker > select");
	var option = select.find("option[bean_name='" + bean_name + "'][bean_file_name='" + bean_file_name + "']");
	var option_value = option.val();
	
	select.val(option_value);
	select.trigger("change");
}

function submitForm(elm) {
	elm = $(elm);
	var oForm = elm.parent().closest(".top_bar").parent().find(".edit_project_details form");
	oForm.submit();
}

function addProject(oForm) {
	oForm = $(oForm);
	var btn = oForm.find(".buttons input");
	var icon = $(".top_bar header li.save > a");
	
	if (btn.hasClass("loading")) {
		StatusMessageHandler.showMessage("Another saving action is already running. Please wait until it finishes...");
		return false;
	}
	
	btn.addClass("loading");
	icon.addClass("loading");
	
	var is_project_created = oForm.attr("project_created") == "1";
	var project_name = oForm.find(".name input[name=name]").val();
	project_name = project_name ? ("" + project_name).replace(/^\s+/g, "").replace(/\s+$/g, "") : "";
	
	if (project_name) {
		project_name = normalizeFileName(project_name, false);
		oForm.find(".name input[name=name]").val(project_name);
		
		MyFancyPopup.showOverlay();
		MyFancyPopup.showLoading();
		
		var project_folder = oForm.find(".project_folder input").val();
		var option = oForm.find(".layer select option:selected").first();
		var bean_name = option.attr("bean_name");
		var bean_file_name = option.attr("bean_file_name");
		var old_project_name = oForm.find(".name input[name=old_name]").val();
		var rename_project = is_project_created && old_project_name != project_name;
		var action = rename_project ? "rename" : "create_folder";
		var path = "";
		var extra = (project_folder ? project_folder + "/" : "") + project_name;
		
		if (rename_project) {
			path = (project_folder ? project_folder + "/" : "") + old_project_name;
			extra = project_name;
		}
		
		var url = manage_project_url.replace("#action#", action).replace("#path#", path).replace("#bean_name#", bean_name).replace("#bean_file_name#", bean_file_name).replace("#extra#", extra);
		
		if (is_project_created && !rename_project) {
			setTimeout(function() {
				btn.removeClass("loading");
				icon.removeClass("loading");
			}, 1000);
			
			return true;
		}
		
		$.ajax({
			type : "get",
			url : url,
			dataType : "text",
			success : function(data, textStatus, jqXHR) {
				if (data == "1") {
					oForm.attr("onSubmit", "");
					oForm.submit();
				}
				else
					StatusMessageHandler.showError("Error: Project not " + (action == "create_folder" ? "created" : "renamed") + "! Please try again." + (data ? "\n" + data : ""));
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jqXHR.responseText);
					StatusMessageHandler.showError(jqXHR.responseText);
			}
		}).always(function() {
			MyFancyPopup.hidePopup();
			
			setTimeout(function() {
				btn.removeClass("loading");
				icon.removeClass("loading");
			}, 1000);
		});
	}
	else {
		StatusMessageHandler.showError("Project name cannot be empty");
		btn.removeClass("loading");
		icon.removeClass("loading");
	}
	
	return false;
}
