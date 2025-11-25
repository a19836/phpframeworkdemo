<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

namespace CMSModule\attachment\edit_attachment;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("attachment/AttachmentUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Attachment Details
		$attachment_id = isset($_GET["attachment_id"]) ? $_GET["attachment_id"] : null;
		$data = $attachment_id ? \AttachmentUtil::getAttachmentsByConditions($brokers, array("attachment_id" => $attachment_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Action
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$status = !$data || \AttachmentUtil::deleteFile($EVC, $data["attachment_id"]);
			}
			else if (!empty($_POST["save"])) {
				$name = isset($_POST["name"]) ? $_POST["name"] : null;
				$type = isset($_POST["type"]) ? $_POST["type"] : null;
				$size = isset($_POST["size"]) ? $_POST["size"] : null;
				$path = isset($_POST["path"]) ? $_POST["path"] : null;
				
				$size = is_numeric($size) ? $size : 0;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("name" => $name, "type" => $type, "size" => $size, "path" => $path));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["name"] = !empty($settings["show_name"]) ? $name : (isset($new_data["name"]) ? $new_data["name"] : null);
					$new_data["type"] = !empty($settings["show_type"]) ? $type : (isset($new_data["type"]) ? $new_data["type"] : null);
					$new_data["size"] = !empty($settings["show_size"]) ? $size : (isset($new_data["size"]) ? $new_data["size"] : null);
					$new_data["path"] = !empty($settings["show_path"]) ? $path : (isset($new_data["path"]) ? $new_data["path"] : null);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if (!empty($settings["allow_insertion"]) && empty($data["attachment_id"])) {
							$status = \AttachmentUtil::insertAttachment($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "attachment_id=$status";
							}
						}
						else if (!empty($settings["allow_update"]) && !empty($data["attachment_id"])) {
							$status = \AttachmentUtil::updateFile($EVC, $new_data, $brokers, $data, isset($settings["security"]) ? $settings["security"] : null);
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"attachment_id" => !empty($settings["show_attachment_id"]) ? $attachment_id : (isset($data["attachment_id"]) ? $data["attachment_id"] : null),
				"name" => !empty($settings["show_name"]) ? $name : (isset($data["name"]) ? $data["name"] : null),
				"type" => !empty($settings["show_type"]) ? $type : (isset($data["type"]) ? $data["type"] : null),
				"size" => !empty($settings["show_size"]) ? $size : (isset($data["size"]) ? $data["size"] : null),
				"path" => !empty($settings["show_path"]) ? $path : (isset($data["path"]) ? $data["path"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/attachment/edit_attachment.css';
		$settings["class"] = "module_edit_attachment";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data;
		
		if (!empty($settings["show_attachment_id"]))
			$settings["fields"]["attachment_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "attachment/edit_attachment", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
