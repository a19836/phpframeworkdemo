<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\zip\edit_country;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("zip/ZipUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Country Details
		$country_id = isset($_GET["country_id"]) ? $_GET["country_id"] : null;
		$data = $country_id ? \ZipUtil::getCountriesByConditions($brokers, array("country_id" => $country_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Country
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_country_id = isset($data["country_id"]) ? $data["country_id"] : null;
				$status = !$data || (/*\ZipUtil::deleteZipsByCountryId($brokers, $data_country_id) && \ZipUtil::deleteZonesByCountryId($brokers, $data_country_id) && \ZipUtil::deleteCitiesByCountryId($brokers, $data_country_id) && \ZipUtil::deleteStatesByCountryId($brokers, $data_country_id) && */\ZipUtil::deleteCountry($brokers, $data_country_id));
			}
			else if (!empty($_POST["save"])) {
				$name = isset($_POST["name"]) ? $_POST["name"] : null;
				
				if (\CommonModuleUI::checkIfEmptyField($settings, "name", $name))
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, "name");
				else {
					$new_data = $data;
					$new_data["name"] = !empty($settings["show_name"]) ? $name : (isset($new_data["name"]) ? $new_data["name"] : null);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if (!empty($settings["allow_insertion"]) && empty($data["country_id"])) {
							$status = \ZipUtil::insertCountry($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false)
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "country_id=$status";
						}
						else if (!empty($settings["allow_update"]) && !empty($data["country_id"]))
							$status = \ZipUtil::updateCountry($brokers, $new_data);
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"country_id" => !empty($settings["show_country_id"]) ? $country_id : (isset($data["country_id"]) ? $data["country_id"] : null),
				"name" => !empty($settings["show_name"]) ? $name : (isset($data["name"]) ? $data["name"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/zip/edit_country.css';
		$settings["class"] = "module_edit_country";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data;
		
		if (!empty($settings["show_country_id"]))
			$settings["fields"]["country_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "zip/edit_country", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
