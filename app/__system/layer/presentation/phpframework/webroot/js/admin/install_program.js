var MyFancyPopupInstallStoreProgram = new MyFancyPopupClass();

$(function () {
	$(window).resize(function() {
		MyFancyPopup.updatePopup();
		MyFancyPopupInstallStoreProgram.updatePopup();
	});
});

function submitForm(elm, on_submit_func) {
	elm = $(elm);
	var oForm = elm.parent().closest(".top_bar").parent().children("form");
	var status = typeof on_submit_func == "function" ? on_submit_func( oForm[0] ) : true;
	
	if (status) {
		elm.hide();
		oForm.submit();
	}
	
	return status;
}

function openUsersManagementAdminPanelPopup(elm) {
	elm = $(elm);
	var popup_elm = elm.parent().closest(".users_permissions").children(".users_management_admin_panel_popup");
	var iframe = popup_elm.children("iframe");
	var url = !iframe.attr("src") ? modules_admin_panel_url : null;
	
	MyFancyPopup.init({
		elementToShow: popup_elm,
		type: "iframe",
		url: url,
	});
	MyFancyPopup.showPopup();
}

function installStoreProgramPopup() {
	if (get_store_programs_url) {
		var popup = $(".install_store_program_popup");
		
		if (!popup[0]) {
			popup = $('<div class="myfancypopup install_store_program_popup"><div class="title">Choose a program to install</div><ul><li class="loading">Loading programs...</li></ul></div>');
			$(document.body).append(popup);
			
			$.ajax({
				type : "get",
				url : get_store_programs_url,
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					//console.log(data);
					var html = '';
					
					$.each(data, function(label, item) {
						html += '<li class="program">'
								+ (item["logo"] ? '<img src="' + item["logo"] + '" />' : '')
								+ '<label>' + label + '</label>'
								+ (item["description"] ? '<div>' + item["description"] + '</div>' : '')
								+ (item["zip"] ? '<a class="choose_program" href="javascript:void(0)" onClick="chooseStoreProgram(\'' + item["zip"] + '\')">Choose</a>' : '')
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
		
		MyFancyPopupInstallStoreProgram.init({
			elementToShow: popup,
			parentElement: document,
		});
		MyFancyPopupInstallStoreProgram.showPopup();
	}
}

function chooseStoreProgram(url) {
	if (url) {
		var upload_url = $('<div class="upload_url"><label>Url:</label><input type="text" name="program_url" value="' + url + '"><span class="icon delete" onClick="removeStoreProgramUrl(this);"></span></div>');
		
		var upload_file = $(".install_program .step_0 .upload_file");
		upload_file.parent().find(".upload_url").remove();
		upload_file.after(upload_url);
		upload_file.remove();
		
		MyFancyPopupInstallStoreProgram.hidePopup();
	}
	else
		alert("Error: You cannot choose this program. Please contact the sysadmin.");
}

function removeStoreProgramUrl(elm) {
	var upload_url = $(elm).parent();
	upload_url.after('<input class="upload_file" type="file" name="program_file" />');
	upload_url.remove();
}
