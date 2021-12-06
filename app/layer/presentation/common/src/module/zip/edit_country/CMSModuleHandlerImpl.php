<?php
namespace CMSModule\zip\edit_country;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("zip/ZipUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Country Details
		$country_id = $_GET["country_id"];
		$data = $country_id ? \ZipUtil::getCountriesByConditions($brokers, array("country_id" => $country_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Country
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || (/*\ZipUtil::deleteZipsByCountryId($brokers, $data["country_id"]) && \ZipUtil::deleteZonesByCountryId($brokers, $data["country_id"]) && \ZipUtil::deleteCitiesByCountryId($brokers, $data["country_id"]) && \ZipUtil::deleteStatesByCountryId($brokers, $data["country_id"]) && */\ZipUtil::deleteCountry($brokers, $data["country_id"]));
			}
			else if ($_POST["save"]) {
				$name = $_POST["name"];
				
				if (\CommonModuleUI::checkIfEmptyField($settings, "name", $name))
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, "name");
				else {
					$new_data = $data;
					$new_data["name"] = $settings["show_name"] ? $name : $new_data["name"];
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if ($settings["allow_insertion"] && empty($data["country_id"])) {
							$status = \ZipUtil::insertCountry($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false)
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "country_id=$status";
						}
						else if ($settings["allow_update"] && $data["country_id"])
							$status = \ZipUtil::updateCountry($brokers, $new_data);
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"country_id" => $settings["show_country_id"] ? $country_id : $data["country_id"],
				"name" => $settings["show_name"] ? $name : $data["name"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = $settings["allow_view"] && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/zip/edit_country.css';
		$settings["class"] = "module_edit_country";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data;
		
		if ($settings["show_country_id"])
			$settings["fields"]["country_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "zip/edit_country", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
