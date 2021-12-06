<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$user_id = $_GET["user_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
	
			$data = array(
				"user_id" => $user_id,
				"user_type_id" => $_POST["user_type_id"],
				"username" => strtolower($_POST["username"]),
				"password" => $_POST["password"],
				"email" => strtolower($_POST["email"]),
				"name" => $_POST["name"],
				"security_question_1" => $_POST["security_question_1"],
				"security_answer_1" => $_POST["security_answer_1"],
				"security_question_2" => $_POST["security_question_2"],
				"security_answer_2" => $_POST["security_answer_2"],
				"security_question_3" => $_POST["security_question_3"],
				"security_answer_3" => $_POST["security_answer_3"],
				"do_not_encrypt_password" => $_POST["do_not_encrypt_password"],
			);
			
			if ($_POST["add"]) {
				$status = UserUtil::insertUser($PEVC, $data, $brokers);
			}
			else {
				if ($user_id) {
					$data["object_users"] = UserUtil::getObjectUsersByConditions($brokers, array("user_id" => $user_id), null, false, true);
					$data["user_environments"] = UserUtil::getUserEnvironmentsByConditions($brokers, array("user_id" => $user_id), null, false, true);
				}
				
				if (UserUtil::updateUser($PEVC, $data, $brokers))
					$status = strlen($_POST["password"]) ? UserUtil::updateUserPassword($brokers, $data) : true;//only update password if exists any change
			}
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteUser($PEVC, $user_id, $brokers);
		}

		if ($action) {
			if ($status) {
				$status_message = "User ${action}d successfully!";
		
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user") . "user_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this user. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getUsersByConditions($brokers, array("user_id" => $user_id), null, null, true);
	$data = $data[0];
	
	unset($data["password"]);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit User '$user_id'" : "Add User",
		"fields" => array(
			"username" => "text",
			"password" => "password",
			"email" => "text",
			"name" => "text",
			"security_question_1" => "text",
			"security_answer_1" => "text",
			"security_question_2" => "text",
			"security_answer_2" => "text",
			"security_question_3" => "text",
			"security_answer_3" => "text",
			"do_not_encrypt_password" => array("type" => "checkbox", "label" => "Do not encrypt the user password:", "next_html" => '<div class="info">(This means that the passwords will not be encrypted in the DB and the Sysadmin can see it by accessing directly the mu_user table in the DB)</div>'),
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
