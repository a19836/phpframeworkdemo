<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("article/admin/ArticleAdminUtil", $common_project_name);
	
	$ArticleAdminUtil = new ArticleAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "article_id=#[\$idx][article_id]#";
	
	$list_settings = array(
		"title" => "Articles List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_article") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_article") . $pks,
		"fields" => array("article_id", "title", "published", "created_date", "modified_date"),
		"total" => ArticleUtil::countAllArticles($brokers, true),
		"data" => ArticleUtil::getAllArticles($brokers, $options, true),
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_articles.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ArticleAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
