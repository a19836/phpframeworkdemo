function exportTable(btn) {
	var query = $(".data_access_obj  > .relationships > .rels > .relationship .query");
	var rand_number = query.attr("rand_number");
	var query_sql_tab = query.find(".query_tabs .query_sql_tab");
	
	if (!query_sql_tab.hasClass("ui-tabs-selected") && !query_sql_tab.hasClass("ui-tabs-active"))
		createSqlFromUI(query.children(".query_tabs").first(), rand_number, true);
	$(query.children(".query_tabs")[1]).click();
	
	var sql_text_area = query.find(".sql_text_area");
	var sql = getQuerySqlEditorValue(sql_text_area);
	
	var form = $(".export_form");
	form.find("input[name=sql]").val(sql);
	
	$(btn).hide();
	form.submit();
}
