<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("comment/admin/CommentAdminUtil", $common_project_name);
	
	$CommentAdminUtil = new CommentAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$comment_id = isset($_GET["comment_id"]) ? $_GET["comment_id"] : null;
	$object_type_id = isset($_GET["object_type_id"]) ? $_GET["object_type_id"] : null;
	$object_id = isset($_GET["object_id"]) ? $_GET["object_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"comment_id" => isset($_POST["comment_id"]) ? $_POST["comment_id"] : null,
				"object_type_id" => isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null,
				"object_id" => isset($_POST["object_id"]) ? $_POST["object_id"] : null,
				"group" => isset($_POST["group"]) ? $_POST["group"] : null,
			);
			$status = CommentUtil::insertObjectComment($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = CommentUtil::deleteObjectComment($brokers, $comment_id, $object_type_id, $object_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Object Comment ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_object_comment") . "comment_id=${data['comment_id']}&object_type_id=${data['object_type_id']}&object_id=${data['object_id']}";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this user comment. Please try again...";
			}
		}
	}
	
	$data = CommentUtil::getObjectCommentsByConditions($brokers, array("comment_id" => $comment_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	$object_type_options = $CommonModuleAdminUtil->getObjectTypeOptions($brokers, $data);
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Object Comment" : "Add Object Comment",
		"fields" => array(
			"comment_id" => $data ? "label" : "text",
			"object_type_id" => $data ? array("type" => "label", "available_values" => $available_object_types) : array("type" => "select", "options" => $object_type_options),
			"object_id" => $data ? "label" : "text",
			"group" => $data ? "label" : "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_object_comment.css" type="text/css" charset="utf-8" />';
	$menu_settings = $CommentAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
