/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

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
