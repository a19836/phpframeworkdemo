<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("event/admin/EventAdminUtil", $common_project_name);
	
	$EventAdminUtil = new EventAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "event_id=#[\$idx][event_id]#";
	
	$list_settings = array(
		"title" => "Events List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_event") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_event") . $pks,
		"fields" => array("event_id", "title", "published", "address", "zip_id", "country_id", "begin_date", "end_date"),
		"total" => EventUtil::countAllEvents($brokers, true),
		"data" => EventUtil::getAllEvents($brokers, $options, true),
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_events.css" type="text/css" charset="utf-8" />';
	$menu_settings = $EventAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
