//$(function () {
window.addEventListener("load", function() {
	//var menu_items_elm = $(".module_edit_menu .menu_items").first();
	var menu_items_elm = document.querySelector(".module_edit_menu .menu_items");
	
	//prepareMenuItemsToShow(menu_items_elm[0], menu_items);
	if (menu_items_elm)
		prepareMenuItemsToShow(menu_items_elm, menu_items);
});

function prepareMenuItemsToShow(elm, menu_items) {
	if (elm && menu_items) {
		//var add_icon = $(elm).children(".add")[0];
		var add_icon = null;
		
		for (var i = 0; i < elm.children.length; i++) 
			if (elm.children[i].classList.contains("add")) {
				add_icon = elm.children[i];
				break;
			}
		
		if (add_icon)
			for (var i = 0; i < menu_items.length; i++) {
				var menu_item = menu_items[i];
				
				var item_elm = addMenuItem(add_icon);
				
				//if (item_elm[0]) {
				if (item_elm) {
					/*item_elm.children(".item_id").find("input, textarea").first().val( menu_item["item_id"] );
					item_elm.children(".item_label").find("input, textarea").first().val( menu_item["label"] );
					item_elm.children(".item_title").find("input, textarea").first().val( menu_item["title"] );
					item_elm.children(".item_class").find("input, textarea").first().val( menu_item["class"] );
					item_elm.children(".item_url").find("input, textarea").first().val( menu_item["url"] );
					item_elm.children(".item_previous_html").find("input, textarea").first().val( menu_item["previous_html"] );
					item_elm.children(".item_next_html").find("input, textarea").first().val( menu_item["next_html"] );*/
					var classes_to_search = {item_id: "item_id", item_label: "label", item_title: "title", item_class: "class", item_url: "url", item_previous_html: "previous_html", item_next_html: "next_html"};
					
					for (var j = 0; j < item_elm.children.length; j++) {
						var child = item_elm.children[j];
						
						for (var k in classes_to_search)
							if (child.classList.contains(k)) {
								var input = child.querySelector("input, textarea");
								
								if (input)
									input.value = menu_item[ classes_to_search[k] ];
								
								break;
							}
					}
					
					if (menu_item.hasOwnProperty("items")) {
						//prepareMenuItemsToShow(item_elm[0], menu_item["items"]);
						prepareMenuItemsToShow(item_elm, menu_item["items"]);
					}
				}
			}
	}
}

function addMenuItem(elm) {
	//var item = $(menu_item_html);
	var item = null;
	
	//$(elm).parent().children(".items").append(item);
	var items_elm = null;
	var children = elm.parentNode.children;
	for (var i = 0; i < children.length; i++) 
		if (children[i].classList.contains("items")) {
			items_elm = children[i];
			break;
		}
	
	if (items_elm) {
		var aux = document.createElement('ul');
		aux.innerHTML = menu_item_html;
		
		while (aux.firstChild)
			item = items_elm.appendChild(aux.firstChild);
	}
	
	return item;
}

function removeMenuItem(elm) {
	//$(elm).parent().remove();
	var p = elm.parentNode;
	p.parentNode.removeChild(p);
}

function toggleMenuItem(elm) {
	//elm = $(elm);
	var children = elm.parentNode.children;
	var classes = ["item_title", "item_class", "item_url", "item_previous_html", "item_next_html"];
	
	//if (elm.hasClass("maximize")) {
	if (elm.classList.contains("maximize")) {
		//elm.removeClass("maximize").addClass("minimize");
		elm.classList.remove("maximize");
		elm.classList.add("minimize");
		
		//elm.parent().children(".item_title, .item_class, .item_url, .item_previous_html, .item_next_html").show();
		for (var i = 0; i < children.length; i++)
			for (var j = 0; j < classes.length; j++)
				if (children[i].classList.contains( classes[j] )) {
					children[i].style.display = "block";
					break;
				}
	}
	else {
		//elm.removeClass("minimize").addClass("maximize");
		elm.classList.remove("minimize");
		elm.classList.add("maximize");
		
		//elm.parent().children(".item_title, .item_class, .item_url, .item_previous_html, .item_next_html").hide();
		for (var i = 0; i < children.length; i++)
			for (var j = 0; j < classes.length; j++)
				if (children[i].classList.contains( classes[j] )) {
					children[i].style.display = "none";
					break;
				}
	}
}

function moveUpMenuItem(elm) {
	//var li = $(elm).parent();
	var li = elm.parentNode;
	
	//li.prev().before(li);
	var prev = li.previousElementSibling;
	
	if (prev)
		li.parentNode.insertBefore(li, prev);
}

function moveDownMenuItem(elm) {
	//var li = $(elm).parent();
	var li = elm.parentNode;
	
	//li.next().after(li);
	var next = li.nextElementSibling;
	
	if (next)
		li.parentNode.insertBefore(next, li);
}

function moveOutMenuItem(elm) {
	//var li = $(elm).parent();
	var li = elm.parentNode;
	
	//var parent_li = li.parent().closest(".menu_item");
	var parent_li = li.parentNode.closest(".menu_item");
	
	//parent_li.after(li);
	if (parent_li) {
		var next = parent_li.nextElementSibling;
		
		if (next)
			parent_li.parentNode.insertBefore(li, next);
		else
			parent_li.parentNode.appendChild(li);
	}
}

function moveInMenuItem(elm) {
	//var li = $(elm).parent();
	var li = elm.parentNode;
	
	//var parent_li = li.prev();
	var parent_li = li.previousElementSibling;
	
	//parent_li.children(".items").append(li);
	if (parent_li) {
		var items = null;
		
		for (var i = 0; i < parent_li.children.length; i++)
			if (parent_li.children[i].classList.contains("items")) {
				items = parent_li.children[i];
				break;
			}
		
		if (items)
			items.appendChild(li);
	}
}

function saveMenu(elm) {
	//var items_elm = $(elm).find(".menu_items").children(".items");
	var items_elm = null;
	var menu_items = elm.querySelector(".menu_items");
	
	if (menu_items)
		for (var i = 0; i < menu_items.children.length; i++)
			if (menu_items.children[i].classList.contains("items")) {
				items_elm = menu_items.children[i];
				break;
			}
	
	if (items_elm)
		//prepareMenuItemsToSave(items_elm[0], "menu_items");
		prepareMenuItemsToSave(items_elm, "menu_items");
	
	return true;
}

function prepareMenuItemsToSave(items_elm, prefix) {
	if (items_elm) {
		//var menu_items = $(items_elm).children(".menu_item");
		var menu_items = [];
		for (var i = 0; i < items_elm.children.length; i++)
			if (items_elm.children[i].classList.contains("menu_item"))
				menu_items.push( items_elm.children[i] );
		
		if (menu_items) {
			var classes_to_search = {item_id: "item_id", item_label: "label", item_title: "title", item_class: "class", item_url: "url", item_previous_html: "previous_html", item_next_html: "next_html"};
			
			for (var i = 0; i < menu_items.length; i++) {
				//var menu_item = $(menu_items[i]);
				var menu_item = menu_items[i];
				
				/*menu_item.children(".item_id").find("input, textarea").first().attr("name", prefix + "[" + i + "][item_id]");
				menu_item.children(".item_label").find("input, textarea").first().attr("name", prefix + "[" + i + "][label]");
				menu_item.children(".item_title").find("input, textarea").first().attr("name", prefix + "[" + i + "][title]");
				menu_item.children(".item_class").find("input, textarea").first().attr("name", prefix + "[" + i + "][class]");
				menu_item.children(".item_url").find("input, textarea").first().attr("name", prefix + "[" + i + "][url]");
				menu_item.children(".item_previous_html").find("input, textarea").first().attr("name", prefix + "[" + i + "][previous_html]");
				menu_item.children(".item_next_html").find("input, textarea").first().attr("name", prefix + "[" + i + "][next_html]");*/
				
				var ol = null;
				
				for (var j = 0; j < menu_item.children.length; j++) {
					var child = menu_item.children[j];
					
					if (child.classList.contains("items"))
						ol = child;
					else {
						for (var k in classes_to_search)
							if (child.classList.contains(k)) {
								var input = child.querySelector("input, textarea");
								
								if (input)
									input.setAttribute("name", prefix + "[" + i + "][" + classes_to_search[k] + "]");
								
								break;
							}
					}
				}
				
				//prepareMenuItemsToSave(menu_item.children(".items")[0], prefix + "[" + i + "][items]");
				if (ol)
					prepareMenuItemsToSave(ol, prefix + "[" + i + "][items]");
			}
		}
	}
}
