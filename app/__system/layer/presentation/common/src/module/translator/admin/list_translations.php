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
	include $EVC->getModulePath("translator/admin/TranslatorAdminUtil", $common_project_name);
	
	$TranslatorAdminUtil = new TranslatorAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$category = isset($_GET["category"]) ? $_GET["category"] : null;
	$language = isset($_GET["language"]) ? $_GET["language"] : null;
	
	if (!empty($_POST["save"])) {
		$texts = isset($_POST["texts"]) ? $_POST["texts"] : null;
		$translations = isset($_POST["translations"]) ? $_POST["translations"] : null;
		$texts_translations = array();
		
		if ($texts)
			foreach ($texts as $idx => $text)
				$texts_translations[$text] = $translations[$idx];
		
		if (TranslatorUtil::setTranslations($PEVC, $texts_translations, $category, $language))
			$status = '<script>alert("Translations saved successfully!");</script>';
		else
			$status = '<script>alert("Error: Translations not saved! Please try again...");</script>';
	}
	else
		$texts_translations = TranslatorUtil::getTranslations($PEVC, $category, $language);
	
	if ($texts_translations) {
		$data = array();
			
		foreach ($texts_translations as $text => $translation) {
			$data[] = array(
				"text" => $text,
				"translation" => $translation,
			);
		}
	}
	
	$list_settings = array(
		"title" => "Translations List for language '$language' " . ($category ? " in category '$category'" : ""),
		"other_urls" => array("#"),
		"fields" => array(
			"text" => array("type" => "text", "label" => "Text/Key", "name" => "texts[]"), 
			"translation" => array("type" => "text", "label" => "Translation", "name" => "translations[]"),
		),
		"data" => isset($data) ? $data : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_translations.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_translations.js"></script>';
	$menu_settings = $TranslatorAdminUtil->getMenuSettings();
	$main_content = (isset($status) ? $status : "") . '
	<form class="module_edit" method="post">
		' . $CommonModuleAdminUtil->getListContent($list_settings) . '
		<div class="buttons">
			<div class="form-group submit_button submit_button_save">
				<input class="btn btn-default" value="Save" name="save" type="submit">
			</div>
		</div>
	</form>
	
	<a class="view_category_languages" href="' . $CommonModuleAdminUtil->getAdminFileUrl("list_languages") . 'category=' . $category . '">View Category Languages</a>';
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
