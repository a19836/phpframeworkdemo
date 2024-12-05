<?php
class ZipUI {
	
	public static function prepareFieldSettingsWithAvailableCountries($brokers, &$settings) {
		if (!empty($settings["show_country_id"]) && isset($settings["fields"]["country_id"]["field"]["input"]["type"])) {
			$is_edit = !empty($settings["allow_insertion"]) || !empty($settings["allow_update"]);
			
			if ($is_edit && $settings["fields"]["country_id"]["field"]["input"]["type"] == "select" && empty($settings["fields"]["country_id"]["field"]["input"]["options"])) {
				$items = \ZipUtil::getAllCountries($brokers);
				
				if ($items) {
					$settings["fields"]["country_id"]["field"]["input"]["options"] = array();
					foreach ($items as $item)
						$settings["fields"]["country_id"]["field"]["input"]["options"][] = array(
							"value" => isset($item["country_id"]) ? $item["country_id"] : null, 
							"label" => isset($item["name"]) ? $item["name"] : null
						);
				}
			}
			else if ($settings["fields"]["country_id"]["field"]["input"]["type"] == "label" && empty($settings["fields"]["country_id"]["field"]["input"]["available_values"])) {
				$items = \ZipUtil::getAllCountries($brokers);
				
				if ($items) {
					$settings["fields"]["country_id"]["field"]["input"]["available_values"] = array();
					foreach ($items as $item) {
						$country_id = isset($item["country_id"]) ? $item["country_id"] : null;
						$settings["fields"]["country_id"]["field"]["input"]["available_values"][$country_id] = isset($item["name"]) ? $item["name"] : null;
					}
				}
			}
		}
	}
	
	public static function prepareFieldSettingsWithAvailableStates($brokers, &$settings) {
		if (!empty($settings["show_state_id"]) && isset($settings["fields"]["state_id"]["field"]["input"]["type"])) {
			$is_edit = !empty($settings["allow_insertion"]) || !empty($settings["allow_update"]);
			
			if ($is_edit && $settings["fields"]["state_id"]["field"]["input"]["type"] == "select" && empty($settings["fields"]["state_id"]["field"]["input"]["options"])) {
				$items = \ZipUtil::getAllStates($brokers);
				
				if ($items) {
					$settings["fields"]["state_id"]["field"]["input"]["options"] = array();
					foreach ($items as $item)
						$settings["fields"]["state_id"]["field"]["input"]["options"][] = array(
							"value" => isset($item["state_id"]) ? $item["state_id"] : null, 
							"label" => isset($item["name"]) ? $item["name"] : null
						);
				}
			}
			else if ($settings["fields"]["state_id"]["field"]["input"]["type"] == "label" && empty($settings["fields"]["state_id"]["field"]["input"]["available_values"])) {
				$items = \ZipUtil::getAllStates($brokers);
				
				if ($items) {
					$settings["fields"]["state_id"]["field"]["input"]["available_values"] = array();
					foreach ($items as $item) {
						$state_id = isset($item["state_id"]) ? $item["state_id"] : null;
						
						$settings["fields"]["state_id"]["field"]["input"]["available_values"][$state_id] = isset($item["name"]) ? $item["name"] : null;
					}
				}
			}
		}
	}
	
	public static function prepareFieldSettingsWithAvailableCities($brokers, &$settings) {
		if (!empty($settings["show_city_id"])) {
			$is_edit = !empty($settings["allow_insertion"]) || !empty($settings["allow_update"]);
			
			if ($is_edit && $settings["fields"]["city_id"]["field"]["input"]["type"] == "select" && empty($settings["fields"]["city_id"]["field"]["input"]["options"])) {
				$items = \ZipUtil::getAllCities($brokers);
				
				if ($items) {
					$settings["fields"]["city_id"]["field"]["input"]["options"] = array();
					foreach ($items as $item)
						$settings["fields"]["city_id"]["field"]["input"]["options"][] = array(
							"value" => isset($item["city_id"]) ? $item["city_id"] : null, 
							"label" => isset($item["name"]) ? $item["name"] : null
						);
				}
			}
			else if ($settings["fields"]["city_id"]["field"]["input"]["type"] == "label" && empty($settings["fields"]["city_id"]["field"]["input"]["available_values"])) {
				$items = \ZipUtil::getAllCities($brokers);
				
				if ($items) {
					$settings["fields"]["city_id"]["field"]["input"]["available_values"] = array();
					foreach ($items as $item) {
						$city_id = isset($item["city_id"]) ? $item["city_id"] : null;
						
						$settings["fields"]["city_id"]["field"]["input"]["available_values"][$city_id] = isset($item["name"]) ? $item["name"] : null;
					}
				}
			}
		}
	}
	
	public static function prepareFieldSettingsWithAvailableZones($brokers, &$settings) {
		if (!empty($settings["show_zone_id"])) {
			$is_edit = !empty($settings["allow_insertion"]) || !empty($settings["allow_update"]);
			
			if ($is_edit && $settings["fields"]["zone_id"]["field"]["input"]["type"] == "select" && empty($settings["fields"]["zone_id"]["field"]["input"]["options"])) {
				$items = \ZipUtil::getAllZones($brokers);
				
				if ($items) {
					$settings["fields"]["zone_id"]["field"]["input"]["options"] = array();
					foreach ($items as $item)
						$settings["fields"]["zone_id"]["field"]["input"]["options"][] = array(
							"value" => isset($item["zone_id"]) ? $item["zone_id"] : null, 
							"label" => isset($item["name"]) ? $item["name"] : null
						);
				}
			}
			else if ($settings["fields"]["zone_id"]["field"]["input"]["type"] == "label" && empty($settings["fields"]["zone_id"]["field"]["input"]["available_values"])) {
				$items = \ZipUtil::getAllZones($brokers);
				
				if ($items) {
					$settings["fields"]["zone_id"]["field"]["input"]["available_values"] = array();
					foreach ($items as $item) {
						$zone_id = isset($item["zone_id"]) ? $item["zone_id"] : null;
						
						$settings["fields"]["zone_id"]["field"]["input"]["available_values"][$zone_id] = isset($item["name"]) ? $item["name"] : null;
					}
				}
			}
		}
	}
}
?>
