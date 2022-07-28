$(function () {
	loadElementMimeTypes($(".allowed_mime_types"), allowed_mime_types, allowed_mime_type_html);
	loadElementMimeTypes($(".denied_mime_types"), denied_mime_types, denied_mime_type_html);
	
	loadElementExtensions($(".allowed_extensions"), allowed_extensions, allowed_extension_html);
	loadElementExtensions($(".denied_extensions"), denied_extensions, denied_extension_html);
	
	$(".allowed_extensions > .form-group .form-control").val("");
	$(".denied_extensions > .form-group .form-control").val("");
});

function deleteMimeType(elm) {
	$(elm).parent().parent().remove();
}

function deleteExtension(elm) {
	$(elm).parent().parent().remove();
}

function addMimeType(elm) {
	elm = $(elm);
	var p = elm.parent();
	var select = p.children("div").first().find("select");
	var option = $(select[0].options[select[0].selectedIndex]);
	var table = p.children("table").children("tbody");
	
	var html = p.hasClass("allowed_mime_types") ? allowed_mime_type_html : denied_mime_type_html;
	html = html.replace("#extension#", option.attr("extension")).replace(/#mime_type#/g, option.attr("mime_type"));
	table.append(html);
	
	table.find("tr .empty_table").hide();
}

function addExtension(elm) {
	elm = $(elm);
	var p = elm.parent();
	var input = p.children("div").first().find("input");
	var extension = input.val();
	
	if (extension) {
		var table = p.children("table").children("tbody");
		var html = p.hasClass("allowed_extensions") ? allowed_extension_html : denied_extension_html;
		html = html.replace(/#extension#/g, extension);
		table.append(html);
	
		table.find("tr .empty_table").hide();
		input.val("");
	}
}

function loadElementMimeTypes(elm, selected_mime_types, mime_type_html) {
	if (selected_mime_types) {
		var mime_types = selected_mime_types.split(";");
		
		var html = '';
		for (var i = 0; i < mime_types.length; i++) {
			var mime_type = mime_types[i];
			
			if (mime_type) {
				var extension = available_types_by_mime_types.hasOwnProperty(mime_type) ? available_types_by_mime_types[mime_type]["extension"] : "";
			
				html += mime_type_html.replace("#extension#", extension).replace(/#mime_type#/g, mime_type);
			}
		}
		
		var table = elm.children("table").children("tbody");
		table.append(html);
		table.find("tr .empty_table").hide();
	}
}

function loadElementExtensions(elm, selected_extensions, extension_html) {
	if (selected_extensions) {
		var extensions = selected_extensions.split(";");
		
		var html = '';
		for (var i = 0; i < extensions.length; i++) {
			var extension = extensions[i];
			
			if (extension)
				html += extension_html.replace(/#extension#/g, extension);
		}
		
		var table = elm.children("table").children("tbody");
		table.append(html);
		table.find("tr .empty_table").hide();
	}
}
