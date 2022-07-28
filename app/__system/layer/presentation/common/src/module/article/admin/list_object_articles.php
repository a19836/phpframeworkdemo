<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("article/admin/ArticleAdminUtil", $common_project_name);
	
	$ArticleAdminUtil = new ArticleAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$ArticleAdminUtil->initObjectArticles($brokers);
	$available_object_types = $ArticleAdminUtil->getAvailableObjectTypes();
	
	$article_id = $_GET["article_id"];
	
	if ($article_id) {
		$total = ArticleUtil::countObjectArticlesByArticleId($brokers, $article_id, true);
		$data = ArticleUtil::getObjectArticlesByArticleId($brokers, $article_id, $options, true);
	}
	else {
		$total = ArticleUtil::countAllObjectArticles($brokers, true);
		$data = ArticleUtil::getAllObjectArticles($brokers, $options, true);
	}
	
	$pks = "article_id=#[\$idx][article_id]#&object_type_id=#[\$idx][object_type_id]#&object_id=#[\$idx][object_id]#";
	
	$list_settings = array(
		"title" => "Object Articles List" . ($article_id ? " for article: '" . $article_id . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_object_article") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_object_article") . $pks,
		"fields" => array(
			"article_id", 
			"object_type_id" => array("available_values" => $available_object_types), 
			"object_id", 
			"group", 
			"order", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_object_articles.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ArticleAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
