function toggleAllPermissions(elm, class_name) {
	elm = $(elm);
	var table = elm.parent().closest("table");
	var is_checked = elm.is(":checked");
	
	table.find("td." + class_name + " input[type=checkbox]").each(function(idx, sub_elm) {
		sub_elm = $(sub_elm);
		
		if (is_checked) 
			sub_elm.attr("checked", "checked").prop("checked", true);
		else 
			sub_elm.removeAttr("checked").prop("checked", false);
		
		if (sub_elm.is(":disabled")) //used by the view/user/manage_user_type_permissions.php in the second table, this is, in the layers table.
			sub_elm.parent().children(".toggle").click();
	});
}

function saveUserTypePermissions() {
	var select = $(".user_type select")[0];
	var opt = select.options[select.selectedIndex];
	
	if (confirm('Do you wish to save these permissions for the user type: "' + $(opt).text() + '"?')) {
		var inputs = $(".user_type_permissions_list table .user_type_permission input[type='checkbox']");
		
		$.each(inputs, function(idx, input) {
			input = $(input);
			
			if (!input.is(":checked") && input[0].hasAttribute("default_value") && input.attr("default_value").length > 0) {
				input[0].type = "test";
				input.val( input.attr("default_value") );
				input.hide();
				input.parent().removeClass("new").children(".toggle").hide();
			}
		});
		
		return true;
	}
	return false;
}

function updateUserTypePermissions(elm) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var user_type_id = $(elm).val();
	
	if (user_type_id) {
		$.ajax({
			type : "get",
			url : get_user_type_permissions_url.replace("#user_type_id#", user_type_id),
			dataType : "json",
			success : function(items, textStatus, jqXHR) {
				if (items) {
					var user_type_permissions = {};
						
					for (var i = 0; i < items.length; i++) {
						var item = items[i];
						var object_type_id = item["object_type_id"];
						var object_id = item["object_id"];
						var permission_id = item["permission_id"];
						
						if (object_type_id && object_id && permission_id) {
							if (!user_type_permissions.hasOwnProperty(object_type_id)) {
								user_type_permissions[object_type_id] = {}
							}
						
							if (!user_type_permissions[object_type_id].hasOwnProperty(object_id)) {
								user_type_permissions[object_type_id][object_id] = {}
							}
						
							user_type_permissions[object_type_id][object_id][permission_id] = 1;
						}
					}
					
					$(".user_type_permissions_list table").each(function(idx, table) {
						var object_type_id = table.getAttribute("object_type_id");
						
						if ($.isNumeric(object_type_id))
							$(table).find("td.object_id").each(function(idx, elm) {
								elm = $(elm);
								var object_id = elm[0].hasAttribute("object_id") ? elm.attr("object_id") : elm.html();
								var user_type_permission_elms = elm.parent().children(".user_type_permission");
								
								if (user_type_permissions.hasOwnProperty(object_type_id) && user_type_permissions[object_type_id].hasOwnProperty(object_id)) {
									user_type_permission_elms.each(function(idx, sub_elm) {
										sub_elm = $(sub_elm);
										var permission_id = sub_elm.attr("permission_id");
										var input = sub_elm.children("input");
										input.removeAttr("disabled");
										
										if (user_type_permissions[object_type_id][object_id].hasOwnProperty(permission_id))
											input.attr("checked", "checked").prop("checked", true);
										else
											input.removeAttr("checked").prop("checked", false);
										
										sub_elm.removeClass("new");
									});
								}
								else 
									user_type_permission_elms.find("input").each(function(idx, input) {
										input = $(input);
										input.removeAttr("checked").prop("checked", false);
										
										if (input[0].hasAttribute("default_value")) {
											input.attr("disabled", "disabled");
											input.parent().addClass("new");
										}
									});
							});
					});
				}
				else
					$(".user_type_permissions_list table td.user_type_permission input").removeAttr("checked").prop("checked", false).parent().removeClass("new");
				
				MyFancyPopup.hidePopup();
			},
			error : function(jqXHR, textStatus, errorThrown) {
				MyFancyPopup.hidePopup();
				
				if (jqXHR.responseText)
					StatusMessageHandler.showError(jqXHR.responseText);
			},
		});
	}
	else {
		alert("Error: user_type_id undefined!");
		MyFancyPopup.hidePopup();
	}
}

function toggleLayerPermissionVisibility(elm) {
	var input = $(elm).parent().children("input");
	
	if (input[0].hasAttribute("disabled"))
		input.removeAttr("disabled");
	else
		input.attr("disabled", "disabled");
}

function onChangeLocalDBSettings(elm) {
	elm = $(elm);
	var p = elm.parent().parent();
	
	if (elm.val() == 1)
		p.children(".form_field_db").hide().addClass("hidden");
	else
		p.children(".form_field_db").show().removeClass("hidden");
	
	if (p.find(".is_local_db select").val() != 1)
		onChangeDBType( p.find(".db_type select")[0] )
}
