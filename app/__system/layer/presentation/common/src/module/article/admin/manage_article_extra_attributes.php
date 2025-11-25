<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("article/admin/ArticleAdminUtil", $common_project_name);
	include $EVC->getModulePath("common/admin/CommonModuleAdminTableExtraAttributesUtil", $common_project_name);
	
	$ArticleAdminUtil = new ArticleAdminUtil($CommonModuleAdminUtil);
	$CommonModuleAdminTableExtraAttributesUtil = new CommonModuleAdminTableExtraAttributesUtil($EVC, $PEVC, $UserAuthenticationHandler, $module_path, isset($GLOBALS["default_db_driver"]) ? $GLOBALS["default_db_driver"] : null, "ma_article", "article");
	
	if (!empty($_POST)) {
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
