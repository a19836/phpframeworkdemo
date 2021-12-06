<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("zip/admin/ZipAdminUtil", $common_project_name);
	
	$ZipAdminUtil = new ZipAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$state_id = $_GET["state_id"];
	
	if ($state_id) {
		$total = ZipUtil::countCitiesByConditions($brokers, array("state_id" => $state_id), null, true);
		$data = ZipUtil::getCitiesByConditions($brokers, array("state_id" => $state_id), null, $options, true);
	}
	else {
		$total = ZipUtil::countAllCities($brokers, true);
		$data = ZipUtil::getAllCities($brokers, $options, true);
	}
	
	$pks = "city_id=#[\$idx][city_id]#";
	
	$list_settings = array(
		"title" => "Cities List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_city") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_city") . $pks,
		"other_urls" => array($CommonModuleAdminUtil->getAdminFileUrl("list_zones") . $pks),
		"fields" => array(
			"city_id", 
			"state_id", 
			"name", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_cities.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ZipAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
