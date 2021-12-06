
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
