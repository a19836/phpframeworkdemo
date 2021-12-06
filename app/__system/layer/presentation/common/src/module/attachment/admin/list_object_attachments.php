<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("attachment/admin/AttachmentAdminUtil", $common_project_name);
	
	$AttachmentAdminUtil = new AttachmentAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$AttachmentAdminUtil->initObjectAttachments($brokers);
	$available_object_types = $AttachmentAdminUtil->getAvailableObjectTypes();
	
	$attachment_id = $_GET["attachment_id"];
	
	if ($attachment_id) {
		$total = AttachmentUtil::countObjectAttachmentsByAttachmentId($brokers, $attachment_id, true);
		$data = AttachmentUtil::getObjectAttachmentsByAttachmentId($brokers, $attachment_id, $options, true);
	}
	else {
		$total = AttachmentUtil::countAllObjectAttachments($brokers, true);
		$data = AttachmentUtil::getAllObjectAttachments($brokers, $options, true);
	}
	
	$pks = "attachment_id=#[\$idx][attachment_id]#&object_type_id=#[\$idx][object_type_id]#&object_id=#[\$idx][object_id]#";
	
	$list_settings = array(
		"title" => "Object Attachments List" . ($attachment_id ? " for attachment: '" . $attachment_id . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_object_attachment") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_object_attachment") . $pks,
		"fields" => array(
			"attachment_id", 
			"object_type_id" => array("available_values" => $available_object_types), 
			"object_id", 
			"group", 
			"order", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_object_attachments.css" type="text/css" charset="utf-8" />';
	$menu_settings = $AttachmentAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
