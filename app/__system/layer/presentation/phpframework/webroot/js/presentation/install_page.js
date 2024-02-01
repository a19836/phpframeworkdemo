var loaded_pages = {};
var MyFancyPopupInstallStorePage = new MyFancyPopupClass();
var MyFancyPopupViewPage = new MyFancyPopupClass();

$(function () {
	var install_page = $(".install_page");
	install_page.tabs();
	
	$(window).resize(function() {
		MyFancyPopupInstallStorePage.updatePopup();
		MyFancyPopupViewPage.updatePopup();
	});
	
	initInstallStorePage();
});

function initInstallStorePage() {
	if (get_store_pages_url)
		$.ajax({
			type : "get",
			url : get_store_pages_url,
			dataType : "json",
			crossDomain: true,
			success : function(data, textStatus, jqXHR) {
				//console.log(data);
				loaded_pages = data;
				
				var html = '';
				
				if (!data)
					html += '<li class="empty">Unfortunately there are no pre-built pages available at this time...</li>';
				else
					$.each(data, function(label, item) {
						html += '<li class="page" title="' + label + '" url="' + item["zip"] + '">'
								+ (item["file"] ? '<a class="img_label" href="javascript:void(0)" onClick="viewStorePage(\'' + item["file"] + '\', \'' + item["zip"] + '\')">' : '')
									+ '<div class="photo">' + (item["logo"] ? '<img src="' + item["logo"] + '" />' : '<span class="icon image"></span>') + '</div>'
									+ '<label>' + label + '</label>'
									+ (item["description"] ? '<div>' + item["description"] + '</div>' : '')
								+ (item["file"] ? '</a>' : '')
								+ (item["file"] ? '<button class="view_page" href="javascript:void(0)" onClick="viewStorePage(\'' + item["file"] + '\', \'' + item["zip"] + '\')"><i class="icon view"></i> Preview</button>' : '')
								+ (item["zip"] ? '<button class="choose_page" href="javascript:void(0)" onClick="chooseStorePage(\'' + item["zip"] + '\')"><i class="icon save"></i> Install</button>' : '')
							+ '</li>';
					});
				
				$(".install_store_page > ul").html(html);
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jqXHR.responseText)
					StatusMessageHandler.showError(jqXHR.responseText);
			},
		});
}

function viewStorePage(preview_url, zip_url) {
	if (preview_url) {
		var popup = $(".view_store_page_popup");
		
		if (!popup[0]) {
			popup = $('<div class="myfancypopup with_title view_store_page_popup' + (is_popup ? " in_popup" : "") + '"></div>');
			$(document.body).append(popup);
		}
		
		var html = '<div class="title">Pre-built page Preview <button class="install_page" onClick="chooseStorePage(\'' + zip_url + '\')">Install this pre-built page</button></div>'
				+ '<iframe src="' + preview_url + '"></iframe>';
		popup.html(html);
		
		MyFancyPopupViewPage.init({
			elementToShow: popup,
			parentElement: document,
		});
		MyFancyPopupViewPage.showPopup();
	}
	else
		alert("Error: You cannot view this pre-built page. Please contact the sysadmin.");
}

function chooseStorePage(url) {
	if (url) {
		MyFancyPopupViewPage.hidePopup(); //in case it comes from the preview popup
		
		StatusMessageHandler.showMessage("Download and installing pre-built page... Please be patient...", "", "", 60000); //1 minute. This message will disappear on submit...
		
		var upload_url = $('<div class="upload_url"><label>Url:</label><input type="text" name="zip_url" value="' + url + '"><span class="icon delete" onClick="removeStorePageUrl(this);"></span></div>');
		
		var f = $(".install_page > .file_upload > form");
		f.find(".upload_url").remove();
		f.append(upload_url);
		f.find(".upload_file").remove();
		
		MyFancyPopupInstallStorePage.hidePopup();
		
		//install page
		$(".top_bar li.continue > a").trigger("click");
	}
	else
		alert("Error: You cannot choose this pre-built page. Please contact the sysadmin.");
}

function removeStorePageUrl(elm) {
	var upload_url = $(elm).parent();
	var url = upload_url.children("input").val();
	
	$(".install_page > .file_upload > form .upload_url input").each(function(idx, input) {
		input = $(input);
		
		if (input.val() == url) {
			var p = input.parent();
			
			if (p.parent().children(".upload_file").length == 0)
				p.after('<input class="upload_file" type="file" name="zip_file">');
			
			p.remove();
		}
	});
}

function searchPages(elm) {
	if ($.isPlainObject(loaded_pages) || $.isArray(loaded_pages)) {
		elm = $(elm);
		var to_search = elm.val().toLowerCase().replace(/^\s*/, "").replace(/\s*$/, "");
		var ul = elm.parent().parent().children("ul");
		var lis = ul.children("li");
		
		if (to_search == "")
			lis.removeClass("hidden");
		else {
			lis.addClass("hidden");
			
			$.each(loaded_pages, function(idx, item) {
				var matched = ("" + item["label"]).toLowerCase().indexOf(to_search) != -1;
				
				if (matched) {
					$.each(lis, function(label, li) {
						li = $(li);
						
						if (li.attr("url") == item["zip"]) {
							li.removeClass("hidden");
							return false;
						}
					});
				}
			});
		}
	}
}

function resetSearchPages(elm) {
	var input = $(elm).parent().children("input");
	input.val("");
	searchPages(input[0]);
}

function installPage(elm) {
	var install_page = $(".install_page");
	var oForm = install_page.find(" > .file_upload form");
	
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
		else {
			if (install_page.tabs("option", "active") == 0)
				StatusMessageHandler.showError("Please click in one of the available pre-built pages to install!");
			else
				StatusMessageHandler.showError("You must upload a pre-built page first!");
		}
	}
	else
		StatusMessageHandler.showError("form object undefined! Please contact the sysadmin...");
}
