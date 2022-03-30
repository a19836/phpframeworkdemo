function onChangeTemplateLayoutScreenToDesktop(elm) {
	elm = $(elm);
	var p = elm.parent();
	
	elm.addClass("active");
	p.addClass("desktop");
	p.children(".mobile").removeClass("active");
	
	onChangeTemplateLayoutScreenSize(elm[0]);
}

function onChangeTemplateLayoutScreenToMobile(elm) {
	elm = $(elm);
	var p = elm.parent();
	
	elm.addClass("active");
	p.removeClass("desktop");
	p.children(".desktop").removeClass("active");
	
	onChangeTemplateLayoutScreenSize(elm[0]);
}

function onChangeTemplateLayoutScreenSize(elm) {
	var p = $(elm).parent().closest(".iframe_toolbar");
	var is_desktop = p.hasClass("desktop");
	var width = "";
	var height = "";
	var cursor = null;
	
	if (!is_desktop) {
		width = p.children(".width").val();
		height = p.children(".height").val();
	}
	
	var iframe = p.parent().children("iframe");
	var iframe_body = $(iframe[0].contentWindow.document.body);
	iframe.css({"width": width, "height": height});
	
	if (!is_desktop)
		iframe_body.addClass("mobile_cursor");
	else
		iframe_body.removeClass("mobile_cursor");
}
