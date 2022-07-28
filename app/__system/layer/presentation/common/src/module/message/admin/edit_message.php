<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("message/admin/MessageAdminUtil", $common_project_name);
	
	$MessageAdminUtil = new MessageAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$message_id = $_GET["message_id"];
	$from_user_id = $_GET["from_user_id"];
	$to_user_id = $_GET["to_user_id"];
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
		
			$data = array(
				"from_user_id" => $_POST["from_user_id"],
				"to_user_id" => $_POST["to_user_id"],
				"subject" => $_POST["subject"],
				"content" => $_POST["content"],
			);
			$status = MessageUtil::insertMessage($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = MessageUtil::deleteMessage($brokers, $message_id, $from_user_id, $to_user_id);
		}
	
		if ($action) {
			if ($status) {
				$status_message = "Message ${action}d successfully!";
			
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_message") . "message_id=$status&from_user_id={$_POST['from_user_id']}&to_user_id={$_POST['to_user_id']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this message. Please try again...";
			}
		}
	}
	
	$data = $message_id && $from_user_id && $to_user_id ? MessageUtil::getMessagesByConditions($brokers, array("message_id" => $message_id, "from_user_id" => $from_user_id, "to_user_id" => $to_user_id), null, null, true) : null;
	$data = $data[0];
	
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	$available_users = $users_limit_exceeded ? null : $CommonModuleAdminUtil->getAvailableUsers($brokers);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Message from user '$from_user_id' to user '$to_user_id'" : "Add Message",
		"fields" => array(
			"message_id" => $data ? "label" : "hidden",
			"from_user_id" => $data ? array("type" => "label", "available_values" => $available_users) : ($users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options)),
			"to_user_id" => $data ? array("type" => "label", "available_values" => $available_users) : ($users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options)),
			"subject" => $data ? "label" : "text",
			"content" => $data ? "label" : "textarea",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_message.css" type="text/css" charset="utf-8" />';
	$menu_settings = $MessageAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
