var loaded_programs = {};

$(function () {
	$(window).resize(function() {
		MyFancyPopup.updatePopup();
	});
});

function submitForm(elm, on_submit_func) {
	elm = $(elm);
	var oForm = elm.parent().closest(".top_bar").parent().children("form");
	var status = typeof on_submit_func == "function" ? on_submit_func( oForm[0] ) : true;
	
	if (status) {
		var on_click = elm.attr("onClick");
		elm.addClass("loading").removeAttr("onClick");
		
		oForm.submit();
		
		/*setTimeout(function() {
			elm.removeClass("loading").attr("onClick", on_click);
		}, 2000);*/
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

function initInstallStoreProgram() {
	if (get_store_programs_url)
		$.ajax({
			type : "get",
			url : get_store_programs_url,
			dataType : "json",
			crossDomain: true,
			success : function(data, textStatus, jqXHR) {
				//console.log(data);
				loaded_programs = data;
				
				var html = '';
				
				$.each(data, function(label, item) {
					html += '<li class="program">'
							+ (item["logo"] ? '<img src="' + item["logo"] + '" />' : '')
							+ '<label>' + label + '</label>'
							+ (item["description"] ? '<div>' + item["description"] + '</div>' : '')
							+ (item["zip"] ? '<a class="choose_program" href="javascript:void(0)" onClick="chooseStoreProgram(\'' + item["zip"] + '\')">Choose</a>' : '')
						+ '</li>';
				});
				
				$(".install_store_program > ul").html(html);
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jqXHR.responseText)
					StatusMessageHandler.showError(jqXHR.responseText);
			},
		});
}

function chooseStoreProgram(url) {
	if (url) {
		StatusMessageHandler.showMessage("Download and installing program... Please be patient...");
		
		var upload_url = $('<div class="upload_url"><label>Url:</label><input type="text" name="program_url" value="' + url + '"><span class="icon delete" onClick="removeStoreProgramUrl(this);"></span></div>');
		
		var upload_file = $(".install_program .step_0 .upload_file");
		upload_file.parent().find(".upload_url").remove();
		upload_file.after(upload_url);
		upload_file.remove();
		
		//install template
		$(".top_bar li.continue > a").trigger("click");
	}
	else
		alert("Error: You cannot choose this program. Please contact the sysadmin.");
}

function removeStoreProgramUrl(elm) {
	var upload_url = $(elm).parent();
	upload_url.after('<input class="upload_file" type="file" name="program_file" />');
	upload_url.remove();
}
