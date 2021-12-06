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
