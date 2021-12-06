<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("translator/admin/TranslatorAdminUtil", $common_project_name);
	
	$TranslatorAdminUtil = new TranslatorAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$parent = trim($_GET["parent"]);
	$parent .= $parent && substr($parent, -1) != "/" ? "/" : "";
	$category = trim($_GET["category"]);
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"category" => $parent . trim($_POST["category"]),
			);
			$status = TranslatorUtil::insertCategory($PEVC, $data);
		}
		else if ($_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"old_category" => $parent . $category,
				"new_category" => $parent . trim($_POST["category"]),
			);
			$status = TranslatorUtil::updateCategory($PEVC, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = TranslatorUtil::deleteCategory($PEVC, $parent . $category);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Category ${action}d successfully!";
				
				if ($_POST["add"] || $_POST["save"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_category") . "parent=$parent&category=" . trim($_POST["category"]);
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this category. Please try again...";
			}
		}
	}
	
	if ($category && TranslatorUtil::categoryExists($PEVC, $parent . $category))
		$data = array(
			"category" => $category,
		);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Category '$parent$category'" : "Add Category" . ($parent ? " to '$parent'" : ""),
		"fields" => array(
			"category" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_category.css" type="text/css" charset="utf-8" />';
	$menu_settings = $TranslatorAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
