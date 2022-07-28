<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$user_type_id = $_GET["user_type_id"];
	$reserved_user_type_ids = UserUtil::getReservedUserTypeIds();
	$is_native = in_array($user_type_id, $reserved_user_type_ids);
	
	if ($_POST) {
		if ($is_native) {
			$error_message = "This user type is native and cannot be edit!";
		}
		else {
			if ($_POST["add"] || $_POST["save"]) {
				$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
				$action = "save";
				
				$data = array(
					"user_type_id" => $_POST["add"] ? $_POST["user_type_id"] : $user_type_id,
					"name" => $_POST["name"],
				);
				$status = $_POST["add"] ? UserUtil::insertUserType($brokers, $data) : UserUtil::updateUserType($brokers, $data);
			}
			else if ($_POST["delete"]) {
				$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
				$action = "delete";
				$status = UserUtil::deleteUserType($brokers, $user_type_id);
			}
	
			if ($action) {
				if ($status) {
					$status_message = "User type ${action}d successfully!";
			
					if ($_POST["add"]) {
						$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_type") . "user_type_id=$status";
						die("<script>alert('$status_message');document.location='$url';</script>");
					}
				}
				else {
					$error_message = "There was an error trying to $action this user type. Please try again...";
				}
			}
		}
	}
	
	$data = UserUtil::getUserTypesByConditions($brokers, array("user_type_id" => $user_type_id), null, true);
	$data = $data[0];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit User Type '$user_type_id'" : "Add User Type",
		"class" => $is_native ? "native" : "",
		"fields" => array(
			"user_type_id" => $is_native || $data ? "label" : "text",
			"name" => $is_native ? "label" : "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_type.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
