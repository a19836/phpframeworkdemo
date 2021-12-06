<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("event/admin/EventAdminUtil", $common_project_name);
	
	$EventAdminUtil = new EventAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$EventAdminUtil->initObjectEvents($brokers);
	$available_object_types = $EventAdminUtil->getAvailableObjectTypes();
	
	$event_id = $_GET["event_id"];
	
	if ($event_id) {
		$total = EventUtil::countObjectEventsByEventId($brokers, $event_id, true);
		$data = EventUtil::getObjectEventsByEventId($brokers, $event_id, $options, true);
	}
	else {
		$total = EventUtil::countAllObjectEvents($brokers, true);
		$data = EventUtil::getAllObjectEvents($brokers, $options, true);
	}
	
	$pks = "event_id=#[\$idx][event_id]#&object_type_id=#[\$idx][object_type_id]#&object_id=#[\$idx][object_id]#";
	
	$list_settings = array(
		"title" => "Object Events List" . ($event_id ? " for event: '" . $event_id . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_object_event") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_object_event") . $pks,
		"fields" => array(
			"event_id", 
			"object_type_id" => array("available_values" => $available_object_types), 
			"object_id", 
			"group", 
			"order", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_object_events.css" type="text/css" charset="utf-8" />';
	$menu_settings = $EventAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
