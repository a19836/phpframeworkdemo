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
