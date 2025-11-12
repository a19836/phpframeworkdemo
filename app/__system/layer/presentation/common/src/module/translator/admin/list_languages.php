<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("translator/admin/TranslatorAdminUtil", $common_project_name);
	
	$TranslatorAdminUtil = new TranslatorAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$category = isset($_GET["category"]) ? $_GET["category"] : null;
	$languages = TranslatorUtil::getLanguages($PEVC, $category);
	
	if ($languages) {
		$data = array();
		
		foreach ($languages as $language) {
			$file_path = TranslatorUtil::getTextTranslatorRootFolderPath($PEVC) . "$category/$language.php";
			
			$data[] = array(
				"language" => $language,
				"modified_date" => date("Y-m-d H:i", filemtime($file_path)),
				"file" => $file_path,
			);
		}
	}
	
	$pks = "category=$category&language=#[\$idx][language]#";
	
	$list_settings = array(
		"title" => "Languages List" . ($category ? " for category '$category'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_language") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_language") . $pks,
		"other_urls" => array($CommonModuleAdminUtil->getAdminFileUrl("list_translations") . $pks),
		"fields" => array("language", "modified_date"),
		"data" => isset($data) ? $data : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_languages.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_languages.js"></script>
	<script>
		var add_language_url = "' . $CommonModuleAdminUtil->getAdminFileUrl("edit_language") . 'category=' . $category . '";
	</script>';
	$menu_settings = $TranslatorAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
