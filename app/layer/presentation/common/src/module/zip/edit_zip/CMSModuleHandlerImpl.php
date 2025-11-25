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

namespace CMSModule\zip\edit_zip;

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
		
		//Getting Zip Details
		$zip_id = isset($_GET["zip_id"]) ? $_GET["zip_id"] : null;
		$country_id = isset($_GET["country_id"]) ? $_GET["country_id"] : null;
		$data = $zip_id && $country_id ? \ZipUtil::getZipsByConditions($brokers, array("zip_id" => $zip_id, "country_id" => $country_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Zip
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_zip_id = isset($data["zip_id"]) ? $data["zip_id"] : null;
				$data_country_id = isset($data["country_id"]) ? $data["country_id"] : null;
				
				$status = !$data || \ZipUtil::deleteZip($brokers, $data_zip_id, $data_country_id);
			}
			else if (!empty($_POST["save"])) {
				$new_zip_id = $data ? $zip_id : (isset($_POST["zip_id"]) ? $_POST["zip_id"] : null);
				$new_country_id = $data ? $country_id : (isset($_POST["country_id"]) ? $_POST["country_id"] : null);
				$new_zone_id = isset($_POST["zone_id"]) ? $_POST["zone_id"] : null;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("zip_id" => $new_zip_id, "country_id" => $new_country_id, "zone_id" => $new_zone_id));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["zip_id"] = !empty($settings["show_zip_id"]) ? $new_zip_id : (isset($new_data["zip_id"]) ? $new_data["zip_id"] : null);
					$new_data["country_id"] = !empty($settings["show_country_id"]) ? $new_country_id : (isset($new_data["country_id"]) ? $new_data["country_id"] : null);
					$new_data["zone_id"] = !empty($settings["show_zone_id"]) ? $new_zone_id : (isset($new_data["zone_id"]) ? $new_data["zone_id"] : null);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if (!empty($settings["allow_insertion"]) && empty($data)) {
							$status = \ZipUtil::insertZip($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "zip_id={$new_data['zip_id']}&country_id={$new_data['country_id']}";
							}
						}
						else if (!empty($settings["allow_update"]) && !empty($data["zip_id"]) && !empty($data["country_id"]) && !empty($data["zone_id"])) {
							$status = \ZipUtil::updateZip($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"zip_id" => !empty($settings["show_zip_id"]) ? $zip_id : (isset($data["zip_id"]) ? $data["zip_id"] : null),
				"country_id" => !empty($settings["show_country_id"]) ? $country_id : (isset($data["country_id"]) ? $data["country_id"] : null),
				"zone_id" => !empty($settings["show_zone_id"]) ? $zone_id : (isset($data["zone_id"]) ? $data["zone_id"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/zip/edit_zip.css';
		$settings["class"] = "module_edit_zip";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data;
		
		if (!empty($settings["show_zip_id"]) && !$is_insertion)
			$settings["fields"]["zip_id"]["field"]["input"]["type"] = "label";
		
		if (!empty($settings["show_country_id"]) && !$is_insertion)
			$settings["fields"]["country_id"]["field"]["input"]["type"] = "label";
		
		\ZipUI::prepareFieldSettingsWithAvailableCountries($brokers, $settings);
		\ZipUI::prepareFieldSettingsWithAvailableZones($brokers, $settings);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "zip/edit_zip", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
