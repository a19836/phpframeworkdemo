function togglePanel(elm) {
	elm = $(elm);
	var table = elm.parent().parent().children("table");
	
	if (elm.hasClass("maximize")) {
		table.show();
		elm.removeClass("maximize").addClass("minimize");
	}
	else {
		table.hide();
		elm.removeClass("minimize").addClass("maximize");
	}
}

function changeUserTypeId(elm) {
	goToUserTypeId( $(elm).val() );
}

function addObjectActivity(url) {
	document.location = url;
}

function toggleCheckboxes(elm, class_name) {
	var checked = $(elm).is(":checked");
	
	var tr = $(elm).parent().parent();
	var table = tr.parent();
	var group = tr.hasClass("group_name");
	
	do {
		var next = tr.next("tr");
		var next_group = next.hasClass("group_name");
		
		if (group && next_group)
			break;
		
		//if (!next_group) {
			var input = next.find("td." + class_name + " input");
			if (checked)
				input.attr("checked", "checked").prop("checked", true);
			else
				input.removeAttr("checked").prop("checked", false);
		//}
		
		tr = next;
	} while (next[0]);
}

function goToUserTypeId(user_type_id) {
	document.location = updateQueryStringParameter(document.location, "user_type_id", user_type_id);
}

function updateQueryStringParameter(uri, key, value) {
	uri = uri ? "" + uri : "";
	
	var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
	var separator = uri.indexOf('?') !== -1 ? "&" : "?";
	if (uri.match(re)) {
		return uri.replace(re, '$1' + key + "=" + value + '$2');
	}
	else {
		return uri + separator + key + "=" + value;
	}
}
