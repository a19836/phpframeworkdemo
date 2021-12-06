<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("object/admin/ObjectAdminUtil", $common_project_name);
	
	$ObjectAdminUtil = new ObjectAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$object_type_id = $_GET["object_type_id"];
	$reserved_object_type_ids = ObjectUtil::getReservedObjectTypeIds();
	$is_native = in_array($object_type_id, $reserved_object_type_ids);
	
	if ($_POST) {
		if ($is_native) {
			$error_message = "This object type is native and cannot be edit!";
		}
		else {
			if ($_POST["add"] || $_POST["save"]) {
				$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
				$action = "save";
			
				$data = array(
					"object_type_id" => $_POST["add"] ? $_POST["object_type_id"] : $object_type_id,
					"name" => $_POST["name"],
				);
				$status = $_POST["add"] ? ObjectUtil::insertObjectType($brokers, $data) : ObjectUtil::updateObjectType($brokers, $data);
			}
			else if ($_POST["delete"]) {
				$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
				$action = "delete";
				$status = ObjectUtil::deleteObjectType($brokers, $object_type_id);
			}
		
			if ($action) {
				if ($status) {
					$status_message = "Object Type ${action}d successfully!";
				
					if ($_POST["add"]) {
						$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_object_type") . "object_type_id=$status";
						die("<script>alert('$status_message');document.location='$url';</script>");
					}
				}
				else {
					$error_message = "There was an error trying to $action this object type. Please try again...";
				}
			}
		}
	}
	
	$data = ObjectUtil::getObjectTypesByConditions($brokers, array("object_type_id" => $object_type_id), null, null, true);
	$data = $data[0];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Object Type '$object_type_id'" : "Add Object Type",
		"class" => $is_native ? "native" : "",
		"fields" => array(
			"object_type_id" => $is_native || $data ? "label" : "text",
			"name" => $is_native ? "label" : "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_object_type.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ObjectAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
