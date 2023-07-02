function seeMoreEvents(elm) {
	if (elm) {
		//show .listed_article.article_hidden 
		//$(elm).parent().find(".listed_event.event_hidden").show();
		var items = elm.parentNode.querySelectorAll(".listed_event.event_hidden");
		
		if (items)
			for (var i = 0; i < items.length; i++)
				items[i].style.display = "list-item";
		
		//hide elm
		//$(elm).hide();
		elm.style.display = "none";
	}
}
