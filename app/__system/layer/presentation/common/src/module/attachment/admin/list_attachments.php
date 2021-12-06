<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("attachment/admin/AttachmentAdminUtil", $common_project_name);
	
	$AttachmentAdminUtil = new AttachmentAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "attachment_id=#[\$idx][attachment_id]#";
	
	$list_settings = array(
		"title" => "Attachments List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_attachment") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_attachment") . $pks,
		"other_urls" => array($CommonModuleAdminUtil->getAdminFileUrl("list_object_attachments") . $pks),
		"fields" => array("attachment_id", "name", "type", "size", "path", "created_date", "modified_date"),
		"total" => AttachmentUtil::countAllAttachments($brokers, true),
		"data" => AttachmentUtil::getAllAttachments($brokers, $options, true),
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_attachments.css" type="text/css" charset="utf-8" />';
	$menu_settings = $AttachmentAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
