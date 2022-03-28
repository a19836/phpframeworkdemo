var MyFancyPopupInstallStoreModule = new MyFancyPopupClass();
var MyFancyPopupViewModule = new MyFancyPopupClass();
var loaded_modules = {};

$(function () {
	$(window).resize(function() {
		MyFancyPopupInstallStoreModule.updatePopup();
		MyFancyPopupViewModule.updatePopup();
	});
});

function addNewFile(elm) {
	var html = '<div class="upload_file"><input type="file" name="zip_file[]" multiple> <span class="icon delete" onClick="$(this).parent().remove()"></span></div>';
	var upload_files = $(elm).parent().children(".upload_file, .upload_url");
	
	if (upload_files.filter(".upload_file").length < 20)
		upload_files.last().after(html);
	else
		alert("Maximum number of allowable file uploads has been reached!");
}

function onSubmitButtonClick(elm) {
	elm = $(elm);
	
	if (checkUploadedFiles()) {
		var on_click = elm.attr("onClick");
		elm.addClass("loading").removeAttr("onClick");
		
		var oForm = elm.parent().closest(".top_bar").parent().find(".file_upload form");
		oForm.submit();
		
		/*setTimeout(function() {
			elm.removeClass("loading").attr("onClick", on_click);
		}, 2000);*/
	}
}

function checkUploadedFiles() {
	var inputs = $(".file_upload").find(".upload_file input[type=file], .upload_url input");
	var count = 0;
	var count_files = 0;
	
	$.each(inputs, function (idx, input) {
		if (input.type == "file" && input.files) {
			count += input.files.length;
			count_files += input.files.length;
		}
		else if (input.value != "")
			count++;
	});
	
	if (count_files > 20) {
		alert("You can only upload 20 zip files maximum each time!\nPlease remove some files before you proceed...");
		return false;
	}
	else if (!count) {
		alert("You must select at least 1 zip file to proceed!");
		return false;
	}
	
	//remove input files with empty values
	$.each(inputs, function (idx, input) {
		if (idx > 0) {
			if (input.type == "file" && (!input.files || !input.files.length))
				$(input).parent().closest(".upload_file").remove();
			else if (input.value == "")
				$(input).parent().closest(".upload_url").remove();
		}
	});
	
	return true;
}

function installStoreModulePopup() {
	if (get_store_modules_url) {
		var popup = $(".install_store_module_popup");
		
		if (!popup[0]) {
			popup = $('<div class="myfancypopup install_store_module_popup"><div class="title">Choose a module to install</div><ul><li class="loading">Loading modules...</li></ul></div>');
			$(document.body).append(popup);
			
			$.ajax({
				type : "get",
				url : get_store_modules_url,
				dataType : "json",
				crossDomain: true,
				success : function(data, textStatus, jqXHR) {
					//console.log(data);
					loaded_modules = data;
					
					var html = '';
					
					$.each(data, function(label, item) {
						html += '<li class="module">'
								+ (item["logo"] ? '<img src="' + item["logo"] + '" />' : '')
								+ '<label>' + label + '</label>'
								+ (item["description"] ? '<div>' + item["description"] + '</div>' : '')
								+ (item["zip"] ? '<a class="choose_module' + (item["modules"] ? ' with_view_module' : '') + '" href="javascript:void(0)" onClick="chooseStoreModule(\'' + item["zip"] + '\')">Choose</a>' : '')
								+ (item["modules"] ? '<a class="view_module" href="javascript:void(0)" onClick="viewStoreModule(\'' + label + '\')">View</a>' : '')
							+ '</li>';
					});
					
					popup.children("ul").html(html);
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					if (jqXHR.responseText)
						StatusMessageHandler.showError(jqXHR.responseText);
				},
			});
		}
		
		MyFancyPopupInstallStoreModule.init({
			elementToShow: popup,
			parentElement: document,
		});
		MyFancyPopupInstallStoreModule.showPopup();
	}
}

function viewStoreModule(module_label) {
	if (module_label) {
		var popup = $(".view_store_module_popup");
		
		if (!popup[0]) {
			popup = $('<div class="myfancypopup view_store_module_popup"></div>');
			$(document.body).append(popup);
		}
		
		var sub_modules = $.isPlainObject(loaded_modules) && $.isPlainObject(loaded_modules[module_label]) ? loaded_modules[module_label]["modules"] : null;
		var html = '<div class="title">Sub modules of "' + module_label + '"</div><ul>';
		
		if (sub_modules && $.isPlainObject(sub_modules) && !$.isEmptyObject(sub_modules)) {
			$.each(sub_modules, function(label, item) {
				html += '<li class="sub_module">'
						+ (item["logo"] ? '<img src="' + item["logo"] + '" />' : '')
						+ '<label>' + label + '</label>'
						+ (item["description"] ? '<div>' + item["description"] + '</div>' : '')
					+ '</li>';
			});
		}
		else
			html += '<li class="no_sub_modules">No sub modules available</li>';
		
		html += '</ul>';
		popup.html(html);
		
		MyFancyPopupViewModule.init({
			elementToShow: popup,
			parentElement: document,
		});
		MyFancyPopupViewModule.showPopup();
	}
	else
		alert("Error: You cannot view this module. Please contact the sysadmin.");
}

function chooseStoreModule(url) {
	if (url) {
		var upload_url = $('<div class="upload_url"><label>Url:</label><input type="text" name="zip_url[]" value="' + url + '"><span class="icon delete" onClick="removeStoreModuleUrl(this);"></span></div>');
		
		var upload_file = $(".file_upload .upload_file").last();
		upload_file.after(upload_url);
		
		MyFancyPopupInstallStoreModule.hidePopup();
	}
	else
		alert("Error: You cannot choose this module. Please contact the sysadmin.");
}

function removeStoreModuleUrl(elm) {
	var upload_url = $(elm).parent();
	upload_url.remove();
}
