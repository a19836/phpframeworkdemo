var MyFancyPopupInstallStoreTemplate = new MyFancyPopupClass();
var MyFancyPopupViewTemplate = new MyFancyPopupClass();

$(function () {
	onChangeLayer( $(".file_upload .layer select")[0] );
	
	$(window).resize(function() {
		MyFancyPopupInstallStoreTemplate.updatePopup();
		MyFancyPopupViewTemplate.updatePopup();
	});
	
	initInstallStoreTemplate();
});

function toggleLayerAndProject() {
	var file_upload = $(".file_upload");
	var layer = file_upload.children(".layer");
	var project = file_upload.find(" > form > .project");
	
	if (!layer.hasClass("unique_layer"))
		layer.toggleClass("hidden");
	
	project.toggleClass("hidden");
}

function onChangeLayer(elm) {
	elm = $(elm);
	var bn = elm.val();
	
	$("form").hide();
	$("#form_" + bn).show();
}

function initInstallStoreTemplate() {
	if (get_store_templates_url)
		$.ajax({
			type : "get",
			url : get_store_templates_url,
			dataType : "json",
			crossDomain: true,
			success : function(data, textStatus, jqXHR) {
				//console.log(data);
				var html = '';
				
				$.each(data, function(idx, item) {
					html += '<li class="template" title="' + item["label"] + '">'
							+ (item["file"] ? '<a class="img_label" href="javascript:void(0)" onClick="viewStoreTemplate(\'' + item["file"] + '\', \'' + item["zip"] + '\')">' : '')
								+ (item["logo"] ? '<img src="' + item["logo"] + '" />' : '')
								+ '<label>' + item["label"] + '</label>'
							+ (item["file"] ? '</a>' : '')
							+ (item["zip"] ? '<a class="choose_template" href="javascript:void(0)" onClick="chooseStoreTemplate(\'' + item["zip"] + '\')"><i class="icon save"></i> Install</a>' : '')
							+ (item["file"] ? '<a class="view_template" href="javascript:void(0)" onClick="viewStoreTemplate(\'' + item["file"] + '\', \'' + item["zip"] + '\')"><i class="icon view"></i> Preview</a>' : '')
						+ '</li>';
				});
				
				$(".install_store_template > ul").html(html);
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jqXHR.responseText)
					StatusMessageHandler.showError(jqXHR.responseText);
			},
		});
}

function viewStoreTemplate(preview_url, zip_url) {
	if (preview_url) {
		var popup = $(".view_store_template_popup");
		
		if (!popup[0]) {
			popup = $('<div class="myfancypopup with_title view_store_template_popup' + (is_popup ? " in_popup" : "") + '"></div>');
			$(document.body).append(popup);
		}
		
		var html = '<div class="title">Template Preview <button class="install_template" onClick="chooseStoreTemplate(\'' + zip_url + '\')">Install this template</button></div>'
				+ '<iframe src="' + preview_url + '"></iframe>';
		popup.html(html);
		
		MyFancyPopupViewTemplate.init({
			elementToShow: popup,
			parentElement: document,
		});
		MyFancyPopupViewTemplate.showPopup();
	}
	else
		alert("Error: You cannot view this template. Please contact the sysadmin.");
}

function chooseStoreTemplate(url) {
	if (url) {
		MyFancyPopupViewTemplate.hidePopup(); //in case it comes from the preview popup
		
		StatusMessageHandler.showMessage("Download and installing template... Please be patient...", "", "", 60000); //1 minute. This message will disappear on submit...
		
		var upload_url = $('<div class="upload_url"><label>Url:</label><input type="text" name="zip_url" value="' + url + '"><span class="icon delete" onClick="removeStoreTemplateUrl(this);"></span></div>');
		
		var upload_file = $(".file_upload form .upload_file");
		upload_file.parent().find(".upload_url").remove();
		upload_file.after(upload_url);
		upload_file.remove();
		
		MyFancyPopupInstallStoreTemplate.hidePopup();
		
		//install template
		$(".top_bar li.continue > a").trigger("click");
	}
	else
		alert("Error: You cannot choose this template. Please contact the sysadmin.");
}

function removeStoreTemplateUrl(elm) {
	var upload_url = $(elm).parent();
	upload_url.after('<input class="upload_file" type="file" name="zip_file">');
	upload_url.remove();
}

function installTemplate(elm) {
	var layer_bn = $(".file_upload > .layer select").val();
	var oForm = $(".file_upload > #form_" + layer_bn);
	
	if (oForm[0]) {
		var zip_url = oForm.find(".upload_url input");
		var zip_file = oForm.find("input.upload_file");
		var status = (zip_url[0] && zip_url.val() != "") || (zip_file[0] && zip_file[0].files.length > 0);
		
		if (status) {
			elm = $(elm);
			var on_click = elm.attr("onClick");
			elm.addClass("loading").removeAttr("onClick");
			
			oForm.submit();
			
			/*setTimeout(function() {
				elm.removeClass("loading").attr("onClick", on_click);
			}, 2000);*/
		}
		else
			StatusMessageHandler.showError("You must upload a template first!");
	}
	else
		StatusMessageHandler.showError("form object undefined! Please contact the sysadmin...");
}
