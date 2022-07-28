function onChangeSelectBoxExtra(elm) {
	onChangeSelectBox(elm);
	
	elm = $(elm);
	var parent = elm.parent();
	
	if (parent.hasClass("table_attr_type")) {
		var value = elm.val();
		var tr = parent.parent();
		var allow_javascript_field = tr.find(".table_attr_allow_javascript input");
		var file_type_field = tr.find(".table_attr_file_type select");
		
		//prepare table_attr_allow_javascript
		if ($.inArray(value, valid_allow_javascript_types) != -1) //if text value
			allow_javascript_field.removeAttr('disabled');
		else
			allow_javascript_field.attr('disabled', 'disabled').removeAttr("checked").prop("checked", false); 
		
		//prepare table_attr_file_type
		if (value == "bigint") //if bigint value which is the PK of the mat_attachment table
			file_type_field.removeAttr('disabled');
		else
			file_type_field.attr('disabled', 'disabled').val("");
	}
}

