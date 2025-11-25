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

function seeMoreArticles(elm) {
	if (elm) {
		//show .listed_article.article_hidden 
		//$(elm).parent().find(".listed_article.article_hidden").show();
		var items = elm.parentNode.querySelectorAll(".listed_article.article_hidden");
		
		if (items)
			for (var i = 0; i < items.length; i++)
				items[i].style.display = "list-item";
		
		//hide elm
		//$(elm).hide();
		elm.style.display = "none";
	}
}
