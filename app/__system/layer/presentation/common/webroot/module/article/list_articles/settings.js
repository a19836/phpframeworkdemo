$(function () {
	$(".list_settings .prop_photo .settings_prop_search_value").remove();
});

function updateArticlesSelectionType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var list_settings = elm.parent().parent();
	
	list_settings.children(".catalog_by_parent, .catalog_by_tags, .catalog_by_selected_articles").hide();
	list_settings.find(".catalog_by_parent > .catalog_parent_group, .catalog_sort_column select .tag_order, .catalog_sort_column select .parent_order").hide();
	
	if (value == "tags_and" || value == "tags_or" || value == "parent_tags_and" || value == "parent_tags_or" || value == "parent_group_tags_and" || value == "parent_group_tags_or") {
		list_settings.children(".catalog_by_tags").show();
		list_settings.children(".catalog_sort_column").children("select").children(".tag_order").show();
	}
	else if (value == "selected") {
		list_settings.children(".catalog_by_selected_articles").show();
	}
	
	if (value == "parent" || value == "parent_tags_and" || value == "parent_tags_or") {
		list_settings.children(".catalog_by_parent").show();
		list_settings.children(".catalog_sort_column").children("select").children(".parent_order").show();
	}
	else if (value == "parent_group" || value == "parent_group_tags_and" || value == "parent_group_tags_or") {
		var cp = list_settings.children(".catalog_by_parent");
		cp.show();
		cp.children(".catalog_parent_group").show();
		list_settings.children(".catalog_sort_column").children("select").children(".parent_order").show();
	}
}

function loadListArticlesBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var list_settings = settings_elm.children(".list_settings");
	
	if (!settings_values || ($.isArray(settings_values) && settings_values.length == 0)) {
		settings_values = {};
	}
	
	loadListSettingsBlockSettings(settings_elm, settings_values);
	
	updateArticlesSelectionType( list_settings.find(".articles_type select")[0] );
	
	var article_ids = prepareBlockSettingsItemValue(settings_values["article_ids"]);
	
	var url = call_module_file_prefix_url.replace("#module_file_path#", "get_data");
	var articles = {};
	
	$.ajax({
		url: url,
		success: function(data) {
			if (data) {
				var object_types = data["object_types"];
				var all_articles = data["articles"];
				
				var selected_object_type_id = prepareBlockSettingsItemValue(settings_values["object_type_id"]);
				
				var options = '';
				$.each(object_types, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '"' + (selected_object_type_id == object_type["object_type_id"] ? ' selected' : '') + '>' + object_type["name"] + '</option>';
				});
				list_settings.find(".catalog_by_parent .catalog_parent_object_type_id select").html(options);
				
				options = '';
				$.each(all_articles, function(index, article) {
					if ($.inArray(article["article_id"], article_ids) != -1) {
						articles[ article["article_id"] ] = article;
					}
					
					options += '<option value="' + article["article_id"] + '">' + article["title"] + '</option>';
				});
				list_settings.find(".catalog_by_selected_articles .available_articles select").html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load all articles.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	if (!jQuery.isEmptyObject(articles) && $.isArray(article_ids)) {
		var table = list_settings.find(".catalog_by_selected_articles .selected_articles table").first();
		table.find(".no_articles").hide();
		
		var html = '';
		
		for (var i = 0; i < article_ids.length; i++) {
			var article_id = article_ids[i];
			
			if (!jQuery.isEmptyObject(articles[article_id])) {
				html += getArticleHtml(article_id, articles[article_id]["title"]);
			}
		}
		
		table.append(html);
	}
	
	MyFancyPopup.hidePopup();
}

function getArticleHtml(article_id, article_title) {
	return '<tr class="article">'
	+ '	<td class="article_id">' + article_id + '</td>'
	+ '	<td class="article_title">' + article_title + '</td>'
	+ '	<td class="buttons">'
	+ '		<input class="module_settings_property" type="hidden" name="article_ids[]" value="' + article_id + '" />'
	+ '		<span class="icon up" onClick="moveSelectedArticleUp(this)">Move Up</span>'
	+ '		<span class="icon down" onClick="moveSelectedArticleDown(this)">Move Down</span>'
	+ '		<span class="icon delete" onClick="removeSelectedArticle(this)">Remove</span>'
	+ '	</td>'
	+ '</tr>';
}

function addSelectedArticle(elm) {
	var p = $(elm).parent();
	var select = p.children("select");
	var article_id = select.val();
	var article_title = select.find(":selected").text();
	
	var table = p.parent().find(".selected_articles table");
	
	var exists = table.find("tr.article .buttons input[value='" + article_id + "']");
	if (exists[0]) {
		StatusMessageHandler.showError("Article already exists!");
	}
	else {
		var html = getArticleHtml(article_id, article_title);
		table.append(html);
		table.find(".no_articles").hide();
	}
}

function moveSelectedArticleUp(elm) {
	moveRegionBlock(elm, "up");
}

function moveSelectedArticleDown(elm) {
	moveRegionBlock(elm, "down");
}

function moveRegionBlock(elm, direction) {
	var item = $(elm).parent().parent();
	
	if (direction == "up") {
		var prev = item.prev();
		if (prev.hasClass("article"))
			item.insertBefore(prev);
	}
	else {
		var next = item.next();
		if (next.hasClass("article"))
			item.insertAfter(next);
	}
}

function removeSelectedArticle(elm) {
	var tr = $(elm).parent().parent();
	var table = tr.parent();
	tr.remove();
	
	if (table.find("tr.article").length == 0) {
		table.find(".no_articles").show();
	}
}
