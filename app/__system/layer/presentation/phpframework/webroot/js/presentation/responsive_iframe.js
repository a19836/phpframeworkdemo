function onChangeTemplateLayoutScreenSize(elm) {
	var p = $(elm).parent();
	p = p.hasClass("dimension") ? p.parent() : p;
	
	var type = p.children(".type").val();
	var dimension = p.children(".dimension");
	var orientation = p.children(".orientation");
	var width = null;
	var height = null;
	var cursor = null;
	
	dimension.hide();
	
	if (type != "auto")
		orientation.show();
	else
		orientation.hide();
	
	switch (type) {
		case "tablet":
			width = "768";
			height = "1024";
			break;
		case "smartphone":
			width = "320";
			height = "568";
			break;
		case "responsive":
			width = dimension.children(".width").val();
			height = dimension.children(".height").val();
			dimension.show();
			break;
		default:
			width = "";
			height = "";
	}
	
	dimension.children(".width").val(width);
	dimension.children(".height").val(height);
	
	var orientation_value = orientation.val();
	if (orientation_value == "horizontal") {
		var aux = width;
		width = height;
		height = aux;
	}
	
	var iframe = p.parent().children("iframe");
	var iframe_body = $(iframe[0].contentWindow.document.body);
	iframe.css({"width": width, "height": height});
	
	if (type == "tablet" || type == "smartphone")
		iframe_body.addClass("mobile_cursor");
	else
		iframe_body.removeClass("mobile_cursor");
}
