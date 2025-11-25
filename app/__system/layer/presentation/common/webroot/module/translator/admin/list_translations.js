/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

$(function () {
	$(".module_list table thead th.other_action").first().append('<span class="icon add" onClick="addTranslation(this)" title="Add Language"></span>');
	$(".module_list table tbody td.other_action a.icon").attr("onClick", "return deleteTranslation(this)");
});

function addTranslation(elm) {
	$(".module_list table tbody").append(''
	+ '<tr>' 
		+ '<td class="list_column text">' 
			+ '<div class="form-group list_column text ">' 
				+ '<input class="form-control" value="" name="texts[]" type="text">' 
			+ '</div>' 
		+ '</td>' 
		+ '<td class="list_column translation">' 
			+ '<div class="form-group list_column translation">' 
				+ '<input class="form-control" value="" name="translations[]" type="text">' 
			+ '</div>' 
		+ '</td>' 
		+ '<td class="list_column other_action">' 
			+ '<div class="form-group list_column other_action">' 
				+ '<a class="form-control icon" href="#" onClick="return deleteTranslation(this)" title=""></a>' 
			+ '</div>' 
		+ '</td>' 
	+ '</tr>');
}

function deleteTranslation(elm) {
	if (confirm("Are you sure you wish to delete this translation?")) 
		$(elm).parent().closest("tr").remove();
	
	return false;
}
