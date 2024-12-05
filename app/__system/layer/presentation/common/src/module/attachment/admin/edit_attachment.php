<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("attachment/admin/AttachmentAdminUtil", $common_project_name);
	
	$AttachmentAdminUtil = new AttachmentAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$attachment_id = isset($_GET["attachment_id"]) ? $_GET["attachment_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"]) || !empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"attachment_id" => $attachment_id,
				"name" => isset($_POST["name"]) ? $_POST["name"] : null,
				"type" => isset($_POST["type"]) ? $_POST["type"] : null,
				"size" => isset($_POST["size"]) ? $_POST["size"] : null,
				"path" => isset($_POST["path"]) ? $_POST["path"] : null,
			);
			
			$status = !empty($_POST["add"]) ? AttachmentUtil::insertAttachment($brokers, $data) : AttachmentUtil::updateFile($PEVC, $data, $brokers, null, false);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = AttachmentUtil::deleteFile($PEVC, $attachment_id, $brokers);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Attachment ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_attachment") . "attachment_id=$status";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this attachment. Please try again...";
			}
		}
	}
	
	$data = AttachmentUtil::getAttachmentsByConditions($brokers, array("attachment_id" => $attachment_id), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Attachment '$attachment_id'" : "Add Attachment",
		"fields" => array(
			"name" => "text",
			"type" => "text",
			"size" => "text",
			"path" => "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_attachment.css" type="text/css" charset="utf-8" />';
	$menu_settings = $AttachmentAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
