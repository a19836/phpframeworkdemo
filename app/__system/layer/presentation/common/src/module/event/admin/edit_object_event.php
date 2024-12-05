<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("event/admin/EventAdminUtil", $common_project_name);
	
	$EventAdminUtil = new EventAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$event_id = isset($_GET["event_id"]) ? $_GET["event_id"] : null;
	$object_type_id = isset($_GET["object_type_id"]) ? $_GET["object_type_id"] : null;
	$object_id = isset($_GET["object_id"]) ? $_GET["object_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"event_id" => isset($_POST["event_id"]) ? $_POST["event_id"] : null,
				"object_type_id" => isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null,
				"object_id" => isset($_POST["object_id"]) ? $_POST["object_id"] : null,
				"group" => isset($_POST["group"]) ? $_POST["group"] : null,
			);
			$status = EventUtil::insertObjectEvent($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = EventUtil::deleteObjectEvent($brokers, $event_id, $object_type_id, $object_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Object Event ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_object_event") . "event_id=${data['event_id']}&object_type_id=${data['object_type_id']}&object_id=${data['object_id']}";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this object event. Please try again...";
			}
		}
	}
	
	$data = EventUtil::getObjectEventsByConditions($brokers, array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	$EventAdminUtil->initObjectEvents($brokers);
	$available_object_types = $EventAdminUtil->getAvailableObjectTypes();
	$object_type_options = $EventAdminUtil->getObjectTypeOptions();
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Object Event" : "Add Object Event",
		"fields" => array(
			"event_id" => $data ? "label" : "text",
			"object_type_id" => $data ? array("type" => "label", "available_values" => $available_object_types) : array("type" => "select", "options" => $object_type_options),
			"object_id" => $data ? "label" : "text",
			"group" => $data ? "label" : "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_object_event.css" type="text/css" charset="utf-8" />';
	$menu_settings = $EventAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
