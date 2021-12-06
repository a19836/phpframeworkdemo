<?php
namespace CMSModule\zip\edit_zip;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("zip/ZipUtil", $common_project_name);
		include_once $EVC->getModulePath("zip/ZipUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Zip Details
		$zip_id = $_GET["zip_id"];
		$country_id = $_GET["country_id"];
		$data = $zip_id && $country_id ? \ZipUtil::getZipsByConditions($brokers, array("zip_id" => $zip_id, "country_id" => $country_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Zip
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \ZipUtil::deleteZip($brokers, $data["zip_id"], $data["country_id"]);
			}
			else if ($_POST["save"]) {
				$new_zip_id = $data ? $zip_id : $_POST["zip_id"];
				$new_country_id = $data ? $country_id : $_POST["country_id"];
				$new_zone_id = $_POST["zone_id"];
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("zip_id" => $new_zip_id, "country_id" => $new_country_id, "zone_id" => $new_zone_id));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["zip_id"] = $settings["show_zip_id"] ? $new_zip_id : $new_data["zip_id"];
					$new_data["country_id"] = $settings["show_country_id"] ? $new_country_id : $new_data["country_id"];
					$new_data["zone_id"] = $settings["show_zone_id"] ? $new_zone_id : $new_data["zone_id"];
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if ($settings["allow_insertion"] && empty($data)) {
							$status = \ZipUtil::insertZip($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "zip_id={$new_data['zip_id']}&country_id={$new_data['country_id']}";
							}
						}
						else if ($settings["allow_update"] && $data["zip_id"] && $data["country_id"] && $data["zone_id"]) {
							$status = \ZipUtil::updateZip($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"zip_id" => $settings["show_zip_id"] ? $zip_id : $data["zip_id"],
				"country_id" => $settings["show_country_id"] ? $country_id : $data["country_id"],
				"zone_id" => $settings["show_zone_id"] ? $zone_id : $data["zone_id"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/zip/edit_zip.css';
		$settings["class"] = "module_edit_zip";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data;
		
		if ($settings["show_zip_id"] && !$is_insertion)
			$settings["fields"]["zip_id"]["field"]["input"]["type"] = "label";
		
		if ($settings["show_country_id"] && !$is_insertion)
			$settings["fields"]["country_id"]["field"]["input"]["type"] = "label";
		
		\ZipUI::prepareFieldSettingsWithAvailableCountries($brokers, $settings);
		\ZipUI::prepareFieldSettingsWithAvailableZones($brokers, $settings);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "zip/edit_zip", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
