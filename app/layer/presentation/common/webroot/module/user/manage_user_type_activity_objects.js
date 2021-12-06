function togglePanel(elm) {
	//elm = $(elm);
	//var table = elm.parent().parent().children("table");
	var table = null;
	var p = elm.parentNode.parentNode;
	
	if (p)
		for (var i = 0; i < p.children.length; i++)
			if (p.children[i].nodeName.toUpperCase() == "TABLE") {
				table = p.children[i];
				break;
			}
	
	//if (elm.hasClass("maximize")) {
	if (elm.classList.contains("maximize")) {
		//table.show();
		if (table)
			table.style.display = "table";
		
		//elm.removeClass("maximize").addClass("minimize");
		elm.classList.remove("maximize");
		elm.classList.add("minimize");
	}
	else {
		//table.hide();
		if (table)
			table.style.display = "none";
		
		//elm.removeClass("minimize").addClass("maximize");
		elm.classList.remove("minimize");
		elm.classList.add("maximize");
	}
}

function changeUserTypeId(elm) {
	//goToUserTypeId( $(elm).val() );
	goToUserTypeId( elm.value );
}

function addObjectActivity(url) {
	document.location = url;
}

function toggleCheckboxes(elm, class_name) {
	//var checked = $(elm).is(":checked");
	var checked = elm.checked ? true : false;
	
	//var tr = $(elm).parent().parent();
	var tr = elm.parentNode.parentNode;
	
	//var table = tr.parent();
	var table = tr.parentNode;
	
	//var group = tr.hasClass("group_name");
	var group = tr.classList.contains("group_name");
	
	do {
		//var next = tr.next("tr");
		var next = tr.nextElementSibling;
		
		if (next && next.nodeName.toUpperCase() == "TR") {
			//var next_group = next.hasClass("group_name");
			var next_group = next.classList.contains("group_name");
			
			if (group && next_group)
				break;
			
			//if (!next_group) {
				//var input = next.find("td." + class_name + " input");
				var input = next.querySelector("td." + class_name + " input");
				
				if (input) {
					if (checked) {
						//input.attr("checked", "checked").prop("checked", true);
						input.setAttribute("checked", "checked");
						input.checked = true;
					}
					else {
						//input.removeAttr("checked").prop("checked", false);
						input.removeAttribute("checked", "checked");
						input.checked = false;
					}
				}
			//}
		}
		
		tr = next;
	//} while (next[0]);
	} while (next);
}

function goToUserTypeId(user_type_id) {
	document.location = updateQueryStringParameter(document.location, "user_type_id", user_type_id);
}

function updateQueryStringParameter(uri, key, value) {
	uri = uri ? "" + uri : "";
	
	var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
	var separator = uri.indexOf('?') !== -1 ? "&" : "?";
	
	if (uri.match(re))
		return uri.replace(re, '$1' + key + "=" + value + '$2');
	else
		return uri + separator + key + "=" + value;
}
