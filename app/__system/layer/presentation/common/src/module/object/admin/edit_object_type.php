<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("object/admin/ObjectAdminUtil", $common_project_name);
	
	$ObjectAdminUtil = new ObjectAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$object_type_id = isset($_GET["object_type_id"]) ? $_GET["object_type_id"] : null;
	$reserved_object_type_ids = ObjectUtil::getReservedObjectTypeIds();
	$is_native = in_array($object_type_id, $reserved_object_type_ids);
	
	if (!empty($_POST)) {
		if ($is_native) {
			$error_message = "This object type is native and cannot be edit!";
		}
		else {
			if (!empty($_POST["add"]) || !empty($_POST["save"])) {
				$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
				$action = "save";
			
				$data = array(
					"object_type_id" => !empty($_POST["add"]) ? (isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null) : $object_type_id,
					"name" => isset($_POST["name"]) ? $_POST["name"] : null,
				);
				$status = !empty($_POST["add"]) ? ObjectUtil::insertObjectType($brokers, $data) : ObjectUtil::updateObjectType($brokers, $data);
			}
			else if (!empty($_POST["delete"])) {
				$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
				$action = "delete";
				$status = ObjectUtil::deleteObjectType($brokers, $object_type_id);
			}
		
			if (!empty($action)) {
				if (!empty($status)) {
					$status_message = "Object Type ${action}d successfully!";
				
					if (!empty($_POST["add"])) {
						$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_object_type") . "object_type_id=$status";
						echo "<script>alert('$status_message');document.location='$url';</script>";
						die();
					}
				}
				else {
					$error_message = "There was an error trying to $action this object type. Please try again...";
				}
			}
		}
	}
	
	$data = ObjectUtil::getObjectTypesByConditions($brokers, array("object_type_id" => $object_type_id), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Object Type '$object_type_id'" : "Add Object Type",
		"class" => $is_native ? "native" : "",
		"fields" => array(
			"object_type_id" => $is_native || $data ? "label" : "text",
			"name" => $is_native ? "label" : "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_object_type.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ObjectAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
