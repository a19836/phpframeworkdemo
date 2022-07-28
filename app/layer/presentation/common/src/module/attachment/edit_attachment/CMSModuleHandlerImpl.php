<?php
namespace CMSModule\attachment\edit_attachment;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("attachment/AttachmentUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Attachment Details
		$attachment_id = $_GET["attachment_id"];
		$data = $attachment_id ? \AttachmentUtil::getAttachmentsByConditions($brokers, array("attachment_id" => $attachment_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Action
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \AttachmentUtil::deleteFile($EVC, $data["attachment_id"]);
			}
			else if ($_POST["save"]) {
				$name = $_POST["name"];
				$type = $_POST["type"];
				$size = $_POST["size"];
				$path = $_POST["path"];
				
				$size = is_numeric($size) ? $size : 0;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("name" => $name, "type" => $type, "size" => $size, "path" => $path));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["name"] = $settings["show_name"] ? $name : $new_data["name"];
					$new_data["type"] = $settings["show_type"] ? $type : $new_data["type"];
					$new_data["size"] = $settings["show_size"] ? $size : $new_data["size"];
					$new_data["path"] = $settings["show_path"] ? $path : $new_data["path"];
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if ($settings["allow_insertion"] && empty($data["attachment_id"])) {
							$status = \AttachmentUtil::insertAttachment($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "attachment_id=$status";
							}
						}
						else if ($settings["allow_update"] && $data["attachment_id"]) {
							$status = \AttachmentUtil::updateFile($EVC, $new_data, $brokers, $data, $settings["security"]);
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"attachment_id" => $settings["show_attachment_id"] ? $attachment_id : $data["attachment_id"],
				"name" => $settings["show_name"] ? $name : $data["name"],
				"type" => $settings["show_type"] ? $type : $data["type"],
				"size" => $settings["show_size"] ? $size : $data["size"],
				"path" => $settings["show_path"] ? $path : $data["path"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/attachment/edit_attachment.css';
		$settings["class"] = "module_edit_attachment";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data;
		
		if ($settings["show_attachment_id"])
			$settings["fields"]["attachment_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "attachment/edit_attachment", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
