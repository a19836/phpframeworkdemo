<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("article/admin/ArticleAdminUtil", $common_project_name);
	include $EVC->getModulePath("common/admin/CommonModuleAdminTableExtraAttributesUtil", $common_project_name);
	
	$ArticleAdminUtil = new ArticleAdminUtil($CommonModuleAdminUtil);
	$CommonModuleAdminTableExtraAttributesUtil = new CommonModuleAdminTableExtraAttributesUtil($EVC, $PEVC, $module_path, $GLOBALS["default_db_driver"], "ma_article", "article");
	
	if ($_POST) {
		$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
		
		$CommonModuleAdminTableExtraAttributesUtil->saveData($_POST);
	}
	
	$head = $CommonModuleAdminTableExtraAttributesUtil->getHead();
	$head .= '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'manage_article_extra_attributes.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ArticleAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminTableExtraAttributesUtil->getContent();
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
