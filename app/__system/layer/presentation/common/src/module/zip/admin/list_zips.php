<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("zip/admin/ZipAdminUtil", $common_project_name);
	
	$ZipAdminUtil = new ZipAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$zone_id = $_GET["zone_id"];
	
	if ($zone_id) {
		$total = ZipUtil::countZipsByConditions($brokers, array("zone_id" => $zone_id), null, true);
		$data = ZipUtil::getZipsByConditions($brokers, array("zone_id" => $zone_id), null, $options, true);
	}
	else {
		$total = ZipUtil::countAllZips($brokers, true);
		$data = ZipUtil::getAllZips($brokers, $options, true);
	}
	
	$pks = "zip_id=#[\$idx][zip_id]#&country_id=#[\$idx][country_id]#";
	
	$list_settings = array(
		"title" => "Zips List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_zip") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_zip") . $pks,
		"fields" => array(
			"zip_id", 
			"country_id",
			"zone_id",
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_zips.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ZipAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
