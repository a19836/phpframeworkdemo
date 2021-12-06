<?php
namespace CMSModule\zip\edit_state;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("zip/ZipUtil", $common_project_name);
		include_once $EVC->getModulePath("zip/ZipUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting State Details
		$state_id = $_GET["state_id"];
		$data = $state_id ? \ZipUtil::getStatesByConditions($brokers, array("state_id" => $state_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing State
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || (/*\ZipUtil::deleteZipsByStateId($brokers, $data["state_id"]) && \ZipUtil::deleteZonesByStateId($brokers, $data["state_id"]) && \ZipUtil::deleteCitiesByStateId($brokers, $data["state_id"]) && */\ZipUtil::deleteState($brokers, $data["state_id"]));
			}
			else if ($_POST["save"]) {
				$country_id = $_POST["country_id"];
				$name = $_POST["name"];
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("country_id" => $country_id, "name" => $name));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["country_id"] = $settings["show_country_id"] ? $country_id : $new_data["country_id"];
					$new_data["name"] = $settings["show_name"] ? $name : $new_data["name"];
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if ($settings["allow_insertion"] && empty($data["state_id"])) {
							$status = \ZipUtil::insertState($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "state_id=$status";
							}
						}
						else if ($settings["allow_update"] && $data["state_id"]) {
							$status = \ZipUtil::updateState($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"state_id" => $settings["show_state_id"] ? $state_id : $data["state_id"],
				"country_id" => $settings["show_country_id"] ? $country_id : $data["country_id"],
				"name" => $settings["show_name"] ? $name : $data["name"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/zip/edit_state.css';
		$settings["class"] = "module_edit_state";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data;
		
		if ($settings["show_state_id"])
			$settings["fields"]["state_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\ZipUI::prepareFieldSettingsWithAvailableCountries($brokers, $settings);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "zip/edit_state", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
