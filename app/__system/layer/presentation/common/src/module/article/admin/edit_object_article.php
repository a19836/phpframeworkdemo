<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("article/admin/ArticleAdminUtil", $common_project_name);
	
	$ArticleAdminUtil = new ArticleAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$article_id = $_GET["article_id"];
	$object_type_id = $_GET["object_type_id"];
	$object_id = $_GET["object_id"];
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"article_id" => $_POST["article_id"],
				"object_type_id" => $_POST["object_type_id"],
				"object_id" => $_POST["object_id"],
				"group" => $_POST["group"],
			);
			$status = ArticleUtil::insertObjectArticle($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = ArticleUtil::deleteObjectArticle($brokers, $article_id, $object_type_id, $object_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Object Article ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_object_article") . "article_id=${data['article_id']}&object_type_id=${data['object_type_id']}&object_id=${data['object_id']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this object article. Please try again...";
			}
		}
	}
	
	$data = ArticleUtil::getObjectArticlesByConditions($brokers, array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true);
	$data = $data[0];
	
	$ArticleAdminUtil->initObjectArticles($brokers);
	$available_object_types = $ArticleAdminUtil->getAvailableObjectTypes();
	$object_type_options = $ArticleAdminUtil->getObjectTypeOptions();
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Object Article" : "Add Object Article",
		"fields" => array(
			"article_id" => $data ? "label" : "text",
			"object_type_id" => $data ? array("type" => "label", "available_values" => $available_object_types) : array("type" => "select", "options" => $object_type_options),
			"object_id" => $data ? "label" : "text",
			"group" => $data ? "label" : "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_object_article.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ArticleAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
