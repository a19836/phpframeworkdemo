$(function () {
	var menu_items_elm = $(".module_edit .menu_items").first();
	
	prepareMenuItemsToShow(menu_items_elm[0], menu_items);
});

function prepareMenuItemsToShow(elm, menu_items) {
	if (elm && menu_items) {
		var add_icon = $(elm).children(".add")[0];
		
		for (var i = 0; i < menu_items.length; i++) {
			var menu_item = menu_items[i];
			
			var item_elm = addMenuItem(add_icon);
			
			if (item_elm[0]) {
				item_elm.children(".item_id").find("input").val( menu_item["item_id"] );
				item_elm.children(".item_label").find("input").val( menu_item["label"] );
				item_elm.children(".item_title").find("input").val( menu_item["title"] );
				item_elm.children(".item_class").find("input").val( menu_item["class"] );
				item_elm.children(".item_url").find("input").val( menu_item["url"] );
				item_elm.children(".item_previous_html").find("textarea").val( menu_item["previous_html"] );
				item_elm.children(".item_next_html").find("textarea").val( menu_item["next_html"] );
				
				if (menu_item.hasOwnProperty("items")) {
					prepareMenuItemsToShow(item_elm[0], menu_item["items"]);
				}
			}
		}
	}
}

function addMenuItem(elm) {
	var item = $(menu_item_html);
	
	$(elm).parent().children(".items").append(item);
	
	return item;
}

function removeMenuItem(elm) {
	$(elm).parent().remove();
}

function toggleMenuItem(elm) {
	elm = $(elm);
	
	if (elm.hasClass("maximize")) {
		elm.removeClass("maximize").addClass("minimize");
		elm.parent().children(".item_title, .item_class, .item_url, .item_previous_html, .item_next_html").show();
	}
	else {
		elm.removeClass("minimize").addClass("maximize");
		elm.parent().children(".item_title, .item_class, .item_url, .item_previous_html, .item_next_html").hide();
	}
}

function moveUpMenuItem(elm) {
	var li = $(elm).parent();
	
	if (li.prev()[0])
		li.parent()[0].insertBefore(li[0], li.prev()[0]);
}

function moveDownMenuItem(elm) {
	var li = $(elm).parent();
	
	if (li.next()[0])
		li.parent()[0].insertBefore(li.next()[0], li[0]);
}

function moveOutMenuItem(elm) {
	var li = $(elm).parent();
	var parent_li = li.parent().closest(".menu_item");
	
	if (parent_li[0])
		parent_li.after(li);
}

function moveInMenuItem(elm) {
	var li = $(elm).parent();
	var parent_li = li.prev();
	
	if (parent_li[0])
		parent_li.children(".items").append(li);
}

function saveMenu(elm) {
	var items_elm = $(elm).find(".menu_items").children(".items");
	
	prepareMenuItemsToSave(items_elm[0], "menu_items");
}

function prepareMenuItemsToSave(items_elm, prefix) {
	if (items_elm) {
		var menu_items = $(items_elm).children(".menu_item");
	
		if (menu_items) {
			for (var i = 0; i < menu_items.length; i++) {
				var menu_item = $(menu_items[i]);
			
				menu_item.children(".item_id").find("input").attr("name", prefix + "[" + i + "][item_id]");
				menu_item.children(".item_label").find("input").attr("name", prefix + "[" + i + "][label]");
				menu_item.children(".item_title").find("input").attr("name", prefix + "[" + i + "][title]");
				menu_item.children(".item_class").find("input").attr("name", prefix + "[" + i + "][class]");
				menu_item.children(".item_url").find("input").attr("name", prefix + "[" + i + "][url]");
				menu_item.children(".item_previous_html").find("textarea").attr("name", prefix + "[" + i + "][previous_html]");
				menu_item.children(".item_next_html").find("textarea").attr("name", prefix + "[" + i + "][next_html]");
			
				prepareMenuItemsToSave(menu_item.children(".items")[0], prefix + "[" + i + "][items]");
			}
		}
	}
}
