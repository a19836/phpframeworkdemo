function openVideoPopup(elm) {
	elm = $(elm);
	var popup = $("#modal-video-01");
	var video_url = elm.attr("video_url");
	var p = elm.parent().closest("li, .card");
	var title = "";
	var description = "";
	
	if (p.is(".card")) {
		var p_body = p.children(".card-body");
		title = p_body.find(".card-title").text();
		description = p_body.find(".card-text").text();
	}
	else {
		title = p.find("button").text();
		description = p.find(".description").text();
	}
	
	if (description)
		popup.find(".modal-footer").show();
	else
		popup.find(".modal-footer").hide();
	
	popup.find(".modal-header .modal-title").html(title);
	popup.find(".modal-footer .modal-description").html(description);
	popup.find("iframe").attr("src", video_url);
	popup.modal('show');
}

