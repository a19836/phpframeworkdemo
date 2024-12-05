<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("objectsgroup/admin/ObjectsGroupAdminUtil", $common_project_name);
	
	$ObjectsGroupAdminUtil = new ObjectsGroupAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$objects_group_id = isset($_GET["objects_group_id"]) ? $_GET["objects_group_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"]) || !empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
		
			$data = array(
				"objects_group_id" => $objects_group_id,
				"object" => !empty($_POST["object"]) ? json_decode($_POST["object"], true) : null,
				"tags" => isset($_POST["tags"]) ? $_POST["tags"] : null,
			);
			
			if ($objects_group_id)
				$data["object_objects_groups"] = ObjectsGroupUtil::getObjectObjectsGroupsByConditions($brokers, array("objects_group_id" => $objects_group_id), null, false, true);
			
			$status = !empty($_POST["add"]) ? ObjectsGroupUtil::insertObjectsGroup($PEVC, $data, isset($_FILES) ? $_FILES : null, $brokers) : ObjectsGroupUtil::updateObjectsGroup($PEVC, $data, isset($_FILES) ? $_FILES : null, $brokers);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = ObjectsGroupUtil::deleteObjectsGroup($PEVC, $objects_group_id, $brokers);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Objects Group ${action}d successfully!";
			
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_objects_group") . "objects_group_id=$status";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this objects group. Please try again...";
			}
		}
	}
	
	$data = ObjectsGroupUtil::getObjectsGroupProperties($PEVC, $objects_group_id, $brokers, true);
	$data["object"] = !empty($data["object"]) ? json_encode($data["object"], true) : null;
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Objects Group '$objects_group_id'" : "Add Objects Group",
		"fields" => array(
			"object" => "textarea",
			"tags" => "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_objects_group.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ObjectsGroupAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
