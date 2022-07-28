<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("zip/admin/ZipAdminUtil", $common_project_name);
	
	$ZipAdminUtil = new ZipAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$ZipAdminUtil->initZips($brokers);
	$available_countries = $ZipAdminUtil->getAvailableCountries();
	
	$country_id = $_GET["country_id"];
	
	if ($country_id) {
		$total = ZipUtil::countStatesByConditions($brokers, array("country_id" => $country_id), null, true);
		$data = ZipUtil::getStatesByConditions($brokers, array("country_id" => $country_id), null, $options, true);
	}
	else {
		$total = ZipUtil::countAllStates($brokers, true);
		$data = ZipUtil::getAllStates($brokers, $options, true);
	}
	
	$pks = "state_id=#[\$idx][state_id]#";
	
	$list_settings = array(
		"title" => "States List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_state") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_state") . $pks,
		"other_urls" => array($CommonModuleAdminUtil->getAdminFileUrl("list_cities") . $pks),
		"fields" => array(
			"state_id", 
			"country_id" => array("available_values" => $available_countries), 
			"name", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_states.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ZipAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
