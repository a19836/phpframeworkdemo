<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("attachment/admin/AttachmentAdminUtil", $common_project_name);
	
	$AttachmentAdminUtil = new AttachmentAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$attachment_id = $_GET["attachment_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"attachment_id" => $attachment_id,
				"name" => $_POST["name"],
				"type" => $_POST["type"],
				"size" => $_POST["size"],
				"path" => $_POST["path"],
			);
			
			$status = $_POST["add"] ? AttachmentUtil::insertAttachment($brokers, $data) : AttachmentUtil::updateFile($PEVC, $data, $brokers, null, false);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = AttachmentUtil::deleteFile($PEVC, $attachment_id, $brokers);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Attachment ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_attachment") . "attachment_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this attachment. Please try again...";
			}
		}
	}
	
	$data = AttachmentUtil::getAttachmentsByConditions($brokers, array("attachment_id" => $attachment_id), null, null, true);
	$data = $data[0];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Attachment '$attachment_id'" : "Add Attachment",
		"fields" => array(
			"name" => "text",
			"type" => "text",
			"size" => "text",
			"path" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_attachment.css" type="text/css" charset="utf-8" />';
	$menu_settings = $AttachmentAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
