<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("zip/admin/ZipAdminUtil", $common_project_name);
	
	$ZipAdminUtil = new ZipAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$city_id = $_GET["city_id"];
	
	if ($city_id) {
		$total = ZipUtil::countZonesByConditions($brokers, array("city_id" => $city_id), null, true);
		$data = ZipUtil::getZonesByConditions($brokers, array("city_id" => $city_id), null, $options, true);
	}
	else {
		$total = ZipUtil::countAllZones($brokers, true);
		$data = ZipUtil::getAllZones($brokers, $options, true);
	}
	
	$pks = "zone_id=#[\$idx][zone_id]#";
	
	$list_settings = array(
		"title" => "Zones List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_zone") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_zone") . $pks,
		"other_urls" => array($CommonModuleAdminUtil->getAdminFileUrl("list_zips") . $pks),
		"fields" => array(
			"zone_id", 
			"city_id", 
			"name", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_zones.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ZipAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
