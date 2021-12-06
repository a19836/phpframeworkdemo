var includesTree = null;

$(function () {
	includesTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeNonPHPFilesFromTree,
	});
	includesTree.init("choose_file_from_file_manager");
	
	$(window).resize(function() {
		MyFancyPopup.updatePopup();
	});
	/*$(window).bind('beforeunload', function () {
		if (window.parent && window.parent.iframe_overlay)
			window.parent.iframe_overlay.hide();
		
		return "Changes you made may not be saved. Click cancel to save them first, otherwise to continue...";
	});*/
	
	MyFancyPopup.hidePopup();
});

function removeNonPHPFilesFromTree(ul, data) {
	ul = $(ul);
	
	ul.find("i.function").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
	
	ul.find("i.file").each(function(idx, elm){
		elm = $(elm);
		var a = elm.parent();
		var li = a.parent();
		var file_path = a.attr("file_path");
		
		if (!file_path || !("" + file_path).match(/\.php([0-9]*)$/i)) //is not a php file
			li.remove();
		else 
			li.children("ul").remove();
	});
	
	ul.find("i.service, i.class, i.test_unit_obj").each(function(idx, elm) {
		$(elm).parent().parent().children("ul").remove();
	});
}

function getIncludePathFromFileManager(elm, selector) {
	MyFancyPopup.init({
		elementToShow: $("#choose_file_from_file_manager"),
		parentElement: document,
		
		targetField: $(elm).parent().find(selector)[0],
		updateFunction: updateIncludeFieldFromFileManager
		
	});
	
	MyFancyPopup.showPopup();
}

function updateIncludeFieldFromFileManager(elm) {
	var node = includesTree.getSelectedNodes();
	node = node[0];
	
	var file_path = null;
	var is_php_file = false;
	var file_path_type = "string";
	
	if (node) {
		var a = $(node).children("a");
		
		if (a) {
			file_path = a.attr("file_path");
			
			if (file_path && file_path.substr(file_path.length - 4) == ".php") {
				var bean_name = a.attr("bean_name");
				file_path = getNodeIncludePath(node, file_path, bean_name);
				file_path_type = "";
				is_php_obj = true;
			}
		}
	}
	
	if (file_path && is_php_obj) {
		var input = $(MyFancyPopup.settings.targetField);
		var p = input.parent();
		
		input.val(file_path);
		p.children(".include_type").val(file_path_type);
		p.children(".include_once").attr("checked", "checked").prop("checked", true);
		
		MyFancyPopup.hidePopup();
	}
	else
		alert("Invalid File selection.\nPlease choose a php file and then click the button.");
}

function addNewUse(elm) {
	var html_obj = $(new_use_html);
	$(elm).parent().find(".fields").append(html_obj);
	
	return html_obj;
}

function addNewInclude(elm) {
	var html_obj = $(new_include_html);
	$(elm).parent().find(".fields").append(html_obj);
	
	return html_obj;
}

function getIncludesObj() {
	var includes_obj = $(".includes_obj");	
	
	var namespaces = [];
	var namespace = includes_obj.children(".namespace").children("input").val();
	if (namespace && namespace.replace(/ /g, "") != "")
		namespaces.push(namespace);
	
	var uses = {};
	var items = includes_obj.children(".uses").find(".fields .use");
	for (var i = 0; i < items.length; i++) {
		var item = $(items[i]);
		
		var use_name = item.children("input.use_name").val();
		var use_alias = item.children("input.use_alias").val();
		
		if (use_name && use_name.replace(/ /g, "") != "")
			uses[use_name] = use_alias;
	}
	
	var includes = [];
	var items = includes_obj.children(".includes").find(".fields .include");
	for (var i = 0; i < items.length; i++) {
		var item = $(items[i]);
		
		var path = item.children("input.include_path").val();
		var var_type = item.children("select.include_type").val();
		var once = item.children("input.include_once").is(":checked") ? 1 : 0;
		
		if (path && path.replace(/ /g, "") != "")
			includes.push({
				"path": path,
				"var_type": var_type,
				"once": once,
			});
	}
	
	var object = {
		"namespaces": namespaces,
		"uses": uses,
		"includes": includes,
	};
	
	return object;
}

function saveIncludes() {
	var object = getIncludesObj();
	
	$.ajax({
		type : "post",
		url : save_object_url,
		data : {"object" : object},
		dataType : "json",
		success : function(data, textStatus, jqXHR) {
			if (data == 1 || ($.isPlainObject(data) && data["status"] == 1))
				StatusMessageHandler.showMessage("Saved successfully.");
			else 
				StatusMessageHandler.showError("Error trying to save new changes.\nPlease try again..." + (data ? "\n" + data : ""));
		},
		error : function(jqXHR, textStatus, errorThrown) { 
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, save_object_url, function() {
					StatusMessageHandler.removeLastShownMessage("error");
					saveIncludes();
				});
			else {
				var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
				StatusMessageHandler.showError("Error trying to save new changes.\nPlease try again..." + msg);
			}
		},
	});
}
