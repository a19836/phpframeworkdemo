<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("objectsgroup/admin/ObjectsGroupAdminUtil", $common_project_name);
	
	$ObjectsGroupAdminUtil = new ObjectsGroupAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$objects_group_id = $_GET["objects_group_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
		
			$data = array(
				"objects_group_id" => $objects_group_id,
				"object" => $_POST["object"] ? json_decode($_POST["object"], true) : $_POST["object"],
				"tags" => $_POST["tags"],
			);
			
			if ($objects_group_id)
				$data["object_objects_groups"] = ObjectsGroupUtil::getObjectObjectsGroupsByConditions($brokers, array("objects_group_id" => $objects_group_id), null, false, true);
			
			$status = $_POST["add"] ? ObjectsGroupUtil::insertObjectsGroup($PEVC, $data, $_FILES, $brokers) : ObjectsGroupUtil::updateObjectsGroup($PEVC, $data, $_FILES, $brokers);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = ObjectsGroupUtil::deleteObjectsGroup($PEVC, $objects_group_id, $brokers);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Objects Group ${action}d successfully!";
			
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_objects_group") . "objects_group_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this objects group. Please try again...";
			}
		}
	}
	
	$data = ObjectsGroupUtil::getObjectsGroupProperties($PEVC, $objects_group_id, $brokers, true);
	$data["object"] = $data["object"] ? json_encode($data["object"], true) : $data["object"];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Objects Group '$objects_group_id'" : "Add Objects Group",
		"fields" => array(
			"object" => "textarea",
			"tags" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_objects_group.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ObjectsGroupAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
