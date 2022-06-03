function toggleSubTutorials(elm) {
	$(elm).parent().closest("li").toggleClass("open");
}

function openVideoPopup(elm) {
	elm = $(elm);
	var popup = $(".popup_video");
	var video_url = elm.attr("video_url");
	var image_url = elm.attr("image_url");
	var p = elm.parent().closest("li, .card");
	var title = "";
	var description = "";
	
	if (p.is(".card")) {
		var p_body = p.children(".card-body");
		title = p_body.find(".card-title").text();
		description = p_body.find(".card-text").text();
	}
	else {
		title = p.find(".tutorial_title").text();
		description = p.find(".tutorial_description").text();
	}
	
	if (description)
		popup.find(".popup_description").show();
	else
		popup.find(".popup_description").hide();
	
	popup.find(".popup_title").html(title);
	popup.find(".popup_description").html(description);
	popup.find(".popup_img").show().attr("src", image_url);
	popup.find("iframe").attr("src", video_url).attr("title", title).css("background-image", "url(" + image_url + ")");
	popup.show();
}

function closeVideoPopup(elm) {
	elm = $(elm);
	var popup = elm.parent().closest(".popup_video");
	
	popup.find("iframe").attr("src", "");
	
	popup.hide();
}

