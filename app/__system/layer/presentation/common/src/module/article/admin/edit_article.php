<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("article/admin/ArticleAdminUtil", $common_project_name);
	include_once $EVC->getModulePath("attachment/AttachmentUI", $common_project_name);
	
	$ArticleAdminUtil = new ArticleAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$article_id = $_GET["article_id"];
	
	if ($_POST) {
		if ($_POST["save"] || $_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			$data = $_POST;
			
			if ($article_id)
				$data["object_articles"] = ArticleUtil::getObjectArticlesByConditions($brokers, array("article_id" => $article_id), null, false, true);
			
			$status = ArticleUtil::setArticleProperties($PEVC, $article_id, $data, $_FILES["photo"]);
			
			if ($status) {
				$article_id = $status;
				
				$status = \AttachmentUtil::saveObjectAttachments($PEVC, ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, ArticleUtil::ARTICLE_ATTACHMENTS_GROUP_ID, $error_message);
			}
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = ArticleUtil::deleteArticle($PEVC, $article_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Article ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_article") . "article_id=$article_id";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else if (!$error_message) {
				$error_message = "There was an error trying to $action this article. Please try again...";
			}
		}
	}
	
	$data = ArticleUtil::getArticleProperties($PEVC, $article_id, true);
	$photo = $data["photo_url"] ? "#photo_url#" . (strpos($data["photo_url"], "?") !== false ? "&" : "?") . "t=" . time() : "";
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Article '$article_id'" : "Add Article",
		"fields" => array(
			"title" => "text",
			"sub_title" => "text",
			"published" => array(
				"type" => "checkbox",
				"options" => array(array("value" => 1))
			),
			"tags" => "text",
			"photo" => "file",
			"photo_id" => "hidden",
			"photo_url" => array(
				"type" => "image", 
				"href" => $photo, 
				"src" => $photo,
				"next_html" => '<a class="photo_remove" onClick="deletePhoto(this)">Remove this photo</a>', 
				"extra_attributes" => array(
					array("name" => "onError", "value" => "$(this).parent().closest('.photo_url').remove()"),
				)
			),
			"summary" => "textarea",
			"content" => "textarea",
			"allow_comments" => array(
				"type" => "checkbox",
				"options" => array(array("value" => 1))
			),
			"article_attachments" => array(
				"next_html" => AttachmentUI::getEditObjectAttachmentsHtml($PEVC, null, ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, ArticleUtil::ARTICLE_ATTACHMENTS_GROUP_ID),
			),
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_article.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_article.js"></script>
	<script type="text/javascript" src="' . $CommonModuleAdminUtil->getProjectCommonUrlPrefix() . 'vendor/ckeditor/ckeditor.js"></script>';
	$menu_settings = $ArticleAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
