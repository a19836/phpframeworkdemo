<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("event/admin/EventAdminUtil", $common_project_name);
	include_once $EVC->getModulePath("attachment/AttachmentUI", $common_project_name);
	
	$EventAdminUtil = new EventAdminUtil($CommonModuleAdminUtil);
	$EventAdminUtil->initObjectEvents($brokers);
	
	//Preparing Data
	$event_id = isset($_GET["event_id"]) ? $_GET["event_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["save"]) || !empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			$data = isset($_POST) ? $_POST : null;
			
			if ($event_id)
				$data["object_events"] = EventUtil::getObjectEventsByConditions($brokers, array("event_id" => $event_id), null, false, true);
			
			$status = EventUtil::setEventProperties($PEVC, $event_id, $data, isset($_FILES["photo"]) ? $_FILES["photo"] : null);
			
			if ($status) {
				$event_id = $status;
				
				$status = \AttachmentUtil::saveObjectAttachments($PEVC, ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id, EventUtil::EVENT_ATTACHMENTS_GROUP_ID, $error_message);
			}
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = EventUtil::deleteEvent($PEVC, $event_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Event ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_event") . "event_id=$event_id";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else if (empty($error_message)) {
				$error_message = "There was an error trying to $action this event. Please try again...";
			}
		}
	}
	
	$data = EventUtil::getEventProperties($PEVC, $event_id, true);
	$photo = !empty($data["photo_url"]) ? "#photo_url#" . (strpos($data["photo_url"], "?") !== false ? "&" : "?") . "t=" . time() : "";
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Event '$event_id'" : "Add Event",
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
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
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
