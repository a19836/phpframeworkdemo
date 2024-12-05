<?php
namespace CMSModule\zip\edit_zone;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("zip/ZipUtil", $common_project_name);
		include_once $EVC->getModulePath("zip/ZipUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Zone Details
		$zone_id = isset($_GET["zone_id"]) ? $_GET["zone_id"] : null;
		$data = $zone_id ? \ZipUtil::getZonesByConditions($brokers, array("zone_id" => $zone_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Zone
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_zone_id = isset($data["zone_id"]) ? $data["zone_id"] : null;
				$status = !$data || (/*\ZipUtil::deleteZipsByZoneId($brokers, $data["zone_id"]) && */\ZipUtil::deleteZone($brokers, $data_zone_id));
			}
			else if (!empty($_POST["save"])) {
				$city_id = isset($_POST["city_id"]) ? $_POST["city_id"] : null;
				$name = isset($_POST["name"]) ? $_POST["name"] : null;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("city_id" => $city_id, "name" => $name));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["city_id"] = !empty($settings["show_city_id"]) ? $city_id : (isset($new_data["city_id"]) ? $new_data["city_id"] : null);
					$new_data["name"] = !empty($settings["show_name"]) ? $name : (isset($new_data["name"]) ? $new_data["name"] : null);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if (!empty($settings["allow_insertion"]) && empty($data["zone_id"])) {
							$status = \ZipUtil::insertZone($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "zone_id=$status";
							}
						}
						else if (!empty($settings["allow_update"]) && !empty($data["zone_id"])) {
							$status = \ZipUtil::updateZone($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"zone_id" => !empty($settings["show_zone_id"]) ? $city_id : (isset($data["zone_id"]) ? $data["zone_id"] : null),
				"city_id" => !empty($settings["show_city_id"]) ? $city_id : (isset($data["city_id"]) ? $data["city_id"] : null),
				"name" => !empty($settings["show_name"]) ? $name : (isset($data["name"]) ? $data["name"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/zip/edit_zone.css';
		$settings["class"] = "module_edit_zone";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data;
		
		if (!empty($settings["show_zone_id"]))
			$settings["fields"]["zone_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\ZipUI::prepareFieldSettingsWithAvailableCities($brokers, $settings);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "zip/edit_zone", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
