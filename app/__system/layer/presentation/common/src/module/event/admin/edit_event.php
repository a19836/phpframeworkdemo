<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("event/admin/EventAdminUtil", $common_project_name);
	include_once $EVC->getModulePath("attachment/AttachmentUI", $common_project_name);
	
	$EventAdminUtil = new EventAdminUtil($CommonModuleAdminUtil);
	$EventAdminUtil->initObjectEvents($brokers);
	
	//Preparing Data
	$event_id = $_GET["event_id"];
	
	if ($_POST) {
		if ($_POST["save"] || $_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			$data = $_POST;
			
			if ($event_id)
				$data["object_events"] = EventUtil::getObjectEventsByConditions($brokers, array("event_id" => $event_id), null, false, true);
			
			$status = EventUtil::setEventProperties($PEVC, $event_id, $data, $_FILES["photo"]);
			
			if ($status) {
				$event_id = $status;
				
				$status = \AttachmentUtil::saveObjectAttachments($PEVC, ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id, EventUtil::EVENT_ATTACHMENTS_GROUP_ID, $error_message);
			}
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = EventUtil::deleteEvent($PEVC, $event_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Event ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_event") . "event_id=$event_id";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else if (!$error_message) {
				$error_message = "There was an error trying to $action this event. Please try again...";
			}
		}
	}
	
	$data = EventUtil::getEventProperties($PEVC, $event_id, true);
	$photo = $data["photo_url"] ? "#photo_url#" . (strpos($data["photo_url"], "?") !== false ? "&" : "?") . "t=" . time() : "";
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Event '$event_id'" : "Add Event",
		"fields" => array(
			"title" => "text",
			"sub_title" => "text",
			"published" => array(
				"type" => "checkbox",
				"options" => array(array("value" => 1))
			),
			"tags" => "text",
			"photo" => "file",
			"photo_id" => "hidden",
			"photo_url" => array(
				"type" => "image", 
				"href" => $photo, 
				"src" => $photo,
				"next_html" => '<a class="photo_remove" onClick="deletePhoto(this)">Remove this photo</a>', 
				"extra_attributes" => array(
					array("name" => "onError", "value" => "$(this).parent().closest('.photo_url').remove()"),
				)
			),
			"description" => "textarea",
			"allow_comments" => array(
				"type" => "checkbox",
				"options" => array(array("value" => 1))
			),
			"address" => "text",
			"zip_id" => "text",
			"locality" => "text",
			"country_id" => array(
				"type" => "select",
				"options" => $EventAdminUtil->getCountryOptions(),
			),
			"latitude" => array(
				"type" => "text",
				"validation_type" => "decimal",
				"validation_message" => "Incorrect latitude value",
			),
			"longitude" => array(
				"type" => "text",
				"validation_type" => "decimal",
				"validation_message" => "Incorrect longitude value",
			),
			"begin_date" => "datetime-local",
			"end_date" => "datetime-local",
			"event_attachments" => array(
				"next_html" => AttachmentUI::getEditObjectAttachmentsHtml($PEVC, null, ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id, EventUtil::EVENT_ATTACHMENTS_GROUP_ID),
			),
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_event.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_event.js"></script>';
	
	if ($CommonModuleAdminUtil->existsCKEditor())
		$head .= '<script type="text/javascript" src="' . $CommonModuleAdminUtil->getProjectCommonUrlPrefix() . 'vendor/ckeditor/ckeditor.js"></script>';
	
	$menu_settings = $EventAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
