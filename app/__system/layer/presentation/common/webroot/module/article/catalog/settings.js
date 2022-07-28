$(function () {
	var settings_prop = $(".settings_prop");
	var fields = settings_prop.children(".selected_task_properties").find(".fields .field");
	
	settings_prop.children(".settings_prop_default_value").remove();
	
	fields.children(".input_settings").children(".input_name").hide();
	
	initObjectBlockSettings("edit_settings", saveEditSettings, "saveEditSettings");
	
	$(".catalog_settings > .els > .els_tabs > li").click(function (idx, li) {
		updateArticlesCatalogType( $(".catalog_settings > .catalog_type > select")[0] );
	});
});

function onArticleCatalogUpdatePTLFromFieldsSettings(elm, settings, code, external_vars) {
	var catalog_settings = $(elm).parent().closest(".catalog_settings");
	var catalog_type = catalog_settings.find(" > .catalog_type > select").val();
	
	if (catalog_type == "user_list") {
		var article_properties_url = catalog_settings.find(" > .article_properties_url > input").val();
		
		code = '' +
			"\n" + '<div class="top_pagination">' +
			"\n" + '	<ptl:block:top-pagination/>' +
			"\n" + '</div>' +
			"\n" + '<ul class="catalog catalog_normal_list">' +
			"\n" + '	<ptl:if is_array(\\$input)>' +
			"\n" + '		<ptl:foreach \\$input i article>' +
			"\n" + '			<li class="article">' + 
			"\n" + '				<a href="' + (article_properties_url != "" ? article_properties_url : '?article_id=') + '<ptl:echo \\$article[article_id]/>' + '">' +
			"\n" + '					' + code.replace(/\n/g, "\n\t\t\t\t\t") +
			"\n" + '				</a>' +
			"\n" + '			</li>' +
			"\n" + '		</ptl:foreach>' +
			"\n" + '	<ptl:else>' +
			"\n" + '		<li><h3 class="no_articles">There are no available articles...</h3></li>' + 
			"\n" + '	</ptl:if>' +
			"\n" + '</ul>' +
			"\n" + '<div class="bottom_pagination">' +
			"\n" + '	<ptl:block:bottom-pagination/>' +
			"\n" + '</div>';
		
		external_vars["articles_item_input_data_var_name"] = "article";
	}
	
	return code;
}

function updateArticlesCatalogType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var catalog_settings = elm.parent().parent();
	
	var article_properties_url = catalog_settings.children(".article_properties_url");
	var alignments = catalog_settings.children(".pagination").find(" > .top_pagination > select[name='top_pagination_alignment'], > .bottom_pagination > select[name='bottom_pagination_alignment']");
	
	article_properties_url.show();
	alignments.show();
	
	if (value == "blog_list")
		catalog_settings.children(".catalog_blog_list").show();
	else {
		catalog_settings.children(".catalog_blog_list").hide();
		
		var ptl_tab_selected = catalog_settings.find("> .els > .els_tabs > .ptl_tab").hasClass("ui-tabs-active");
		
		if (value == "user_list" && ptl_tab_selected) {
			alignments.hide();
			article_properties_url.hide();
		}
	}
}

function updateArticlesSelectionType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var catalog_settings = elm.parent().parent();
	
	catalog_settings.children(".catalog_by_parent, .catalog_by_tags, .catalog_by_selected_articles").hide();
	catalog_settings.find(".catalog_by_parent > .catalog_parent_group, .catalog_sort_column select .tag_order, .catalog_sort_column select .parent_order").hide();
	
	if (value == "tags_and" || value == "tags_or" || value == "parent_tags_and" || value == "parent_tags_or" || value == "parent_group_tags_and" || value == "parent_group_tags_or") {
		catalog_settings.children(".catalog_by_tags").show();
		catalog_settings.children(".catalog_sort_column").children("select").children(".tag_order").show();
	}
	else if (value == "selected") {
		catalog_settings.children(".catalog_by_selected_articles").show();
	}
	
	if (value == "parent" || value == "parent_tags_and" || value == "parent_tags_or") {
		catalog_settings.children(".catalog_by_parent").show();
		catalog_settings.children(".catalog_sort_column").children("select").children(".parent_order").show();
	}
	else if (value == "parent_group" || value == "parent_group_tags_and" || value == "parent_group_tags_or") {
		var cp = catalog_settings.children(".catalog_by_parent");
		cp.show();
		cp.children(".catalog_parent_group").show();
		catalog_settings.children(".catalog_sort_column").children("select").children(".parent_order").show();
	}
}

function loadArticlesCatalogBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var catalog_settings = settings_elm.children(".catalog_settings");
	
	if (!settings_values || ($.isArray(settings_values) && settings_values.length == 0)) {
		settings_values = {};
	}
	
	if (!settings_values.hasOwnProperty("blog_introduction_articles_num")) {
		settings_values["blog_introduction_articles_num"] = {"value": 1};
	}
	if (!settings_values.hasOwnProperty("blog_featured_articles_num")) {
		settings_values["blog_featured_articles_num"] = {"value": 2};
	}
	if (!settings_values.hasOwnProperty("blog_featured_articles_cols")) {
		settings_values["blog_featured_articles_cols"] = {"value": 3};
	}
	if (!settings_values.hasOwnProperty("blog_listed_articles_num")) {
		settings_values["blog_listed_articles_num"] = {"value": 10};
	}
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	
	updateArticlesCatalogType( catalog_settings.find(".catalog_type select")[0] );
	updateArticlesSelectionType( catalog_settings.find(".articles_type select")[0] );
	
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
				catalog_settings.find(".catalog_by_parent .catalog_parent_object_type_id select").html(options);
				
				options = '';
				$.each(all_articles, function(index, article) {
					if ($.inArray(article["article_id"], article_ids) != -1) {
						articles[ article["article_id"] ] = article;
					}
					
					options += '<option value="' + article["article_id"] + '">' + article["title"] + '</option>';
				});
				catalog_settings.find(".catalog_by_selected_articles .available_articles select").html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load all articles.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	if (!jQuery.isEmptyObject(articles) && $.isArray(article_ids)) {
		var table = catalog_settings.find(".catalog_by_selected_articles .selected_articles table").first();
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
