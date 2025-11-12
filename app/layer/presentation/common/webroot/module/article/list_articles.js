/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

//$(function () {
window.addEventListener("load", function() {
	/* Delete images with empty sources
	var src = $(".module_list_articles td.photo img").each(function (idx, elm) {
		elm = $(elm);
		
		if (!elm.attr("src")) {
			elm.remove();
		}
	});*/
	var items = document.querySelectorAll(".module_list_articles td.photo img");
	
	if (items)
		for (var i = 0; i < items.length; i++) {
			var item = items[i];
			var src = item.getAttribute("src");
			
			if (!src)
				item.parentNode.removeChild(item);
		}
});
