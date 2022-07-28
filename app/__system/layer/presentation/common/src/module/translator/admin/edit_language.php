<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("translator/admin/TranslatorAdminUtil", $common_project_name);
	
	$TranslatorAdminUtil = new TranslatorAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$category = trim($_GET["category"]);
	$language = trim($_GET["language"]);
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"category" => $category,
				"language" => trim($_POST["language"]),
			);
			$status = TranslatorUtil::insertLanguage($PEVC, $data);
		}
		else if ($_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"category" => $category,
				"old_language" => $language,
				"new_language" => trim($_POST["language"]),
			);
			$status = TranslatorUtil::updateLanguage($PEVC, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = TranslatorUtil::deleteLanguage($PEVC, $language, $category);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Language ${action}d successfully!";
				
				if ($_POST["add"] || $_POST["save"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_language") . "category=$category&language=" . trim($_POST["language"]);
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this language. Please try again...";
			}
		}
	}
	
	if ($language && TranslatorUtil::languageExists($PEVC, $language, $category))
		$data = array(
			"language" => $language,
		);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Language '$language'" . ($category ? " in '$category'" : "") : "Add Language" . ($category ? " to '$category'" : ""),
		"fields" => array(
			"language" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_language.css" type="text/css" charset="utf-8" />';
	$menu_settings = $TranslatorAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings) . '
		<a class="view_category_languages" href="' . $CommonModuleAdminUtil->getAdminFileUrl("list_languages") . 'category=' . $category . '">View Category Languages</a>
	';
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
