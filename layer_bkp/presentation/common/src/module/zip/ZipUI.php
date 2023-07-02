<?php
class ZipUI {
	
	public static function prepareFieldSettingsWithAvailableCountries($brokers, &$settings) {
		if ($settings["show_country_id"]) {
			$is_edit = $settings["allow_insertion"] || $settings["allow_update"];
			
			if ($is_edit && $settings["fields"]["country_id"]["field"]["input"]["type"] == "select" && !$settings["fields"]["country_id"]["field"]["input"]["options"]) {
				$items = \ZipUtil::getAllCountries($brokers);
				
				if ($items) {
					$settings["fields"]["country_id"]["field"]["input"]["options"] = array();
					foreach ($items as $item)
						$settings["fields"]["country_id"]["field"]["input"]["options"][] = array("value" => $item["country_id"], "label" => $item["name"]);
				}
			}
			else if ($settings["fields"]["country_id"]["field"]["input"]["type"] == "label" && !$settings["fields"]["country_id"]["field"]["input"]["available_values"]) {
				$items = \ZipUtil::getAllCountries($brokers);
				
				if ($items) {
					$settings["fields"]["country_id"]["field"]["input"]["available_values"] = array();
					foreach ($items as $item)
						$settings["fields"]["country_id"]["field"]["input"]["available_values"][ $item["country_id"] ] = $item["name"];
				}
			}
		}
	}
	
	public static function prepareFieldSettingsWithAvailableStates($brokers, &$settings) {
		if ($settings["show_state_id"]) {
			$is_edit = $settings["allow_insertion"] || $settings["allow_update"];
			
			if ($is_edit && $settings["fields"]["state_id"]["field"]["input"]["type"] == "select" && !$settings["fields"]["state_id"]["field"]["input"]["options"]) {
				$items = \ZipUtil::getAllStates($brokers);
				
				if ($items) {
					$settings["fields"]["state_id"]["field"]["input"]["options"] = array();
					foreach ($items as $item)
						$settings["fields"]["state_id"]["field"]["input"]["options"][] = array("value" => $item["state_id"], "label" => $item["name"]);
				}
			}
			else if ($settings["fields"]["state_id"]["field"]["input"]["type"] == "label" && !$settings["fields"]["state_id"]["field"]["input"]["available_values"]) {
				$items = \ZipUtil::getAllStates($brokers);
				
				if ($items) {
					$settings["fields"]["state_id"]["field"]["input"]["available_values"] = array();
					foreach ($items as $item)
						$settings["fields"]["state_id"]["field"]["input"]["available_values"][ $item["state_id"] ] = $item["name"];
				}
			}
		}
	}
	
	public static function prepareFieldSettingsWithAvailableCities($brokers, &$settings) {
		if ($settings["show_city_id"]) {
			$is_edit = $settings["allow_insertion"] || $settings["allow_update"];
			
			if ($is_edit && $settings["fields"]["city_id"]["field"]["input"]["type"] == "select" && !$settings["fields"]["city_id"]["field"]["input"]["options"]) {
				$items = \ZipUtil::getAllCities($brokers);
				
				if ($items) {
					$settings["fields"]["city_id"]["field"]["input"]["options"] = array();
					foreach ($items as $item)
						$settings["fields"]["city_id"]["field"]["input"]["options"][] = array("value" => $item["city_id"], "label" => $item["name"]);
				}
			}
			else if ($settings["fields"]["city_id"]["field"]["input"]["type"] == "label" && !$settings["fields"]["city_id"]["field"]["input"]["available_values"]) {
				$items = \ZipUtil::getAllCities($brokers);
				
				if ($items) {
					$settings["fields"]["city_id"]["field"]["input"]["available_values"] = array();
					foreach ($items as $item)
						$settings["fields"]["city_id"]["field"]["input"]["available_values"][ $item["city_id"] ] = $item["name"];
				}
			}
		}
	}
	
	public static function prepareFieldSettingsWithAvailableZones($brokers, &$settings) {
		if ($settings["show_zone_id"]) {
			$is_edit = $settings["allow_insertion"] || $settings["allow_update"];
			
			if ($is_edit && $settings["fields"]["zone_id"]["field"]["input"]["type"] == "select" && !$settings["fields"]["zone_id"]["field"]["input"]["options"]) {
				$items = \ZipUtil::getAllZones($brokers);
				
				if ($items) {
					$settings["fields"]["zone_id"]["field"]["input"]["options"] = array();
					foreach ($items as $item)
						$settings["fields"]["zone_id"]["field"]["input"]["options"][] = array("value" => $item["zone_id"], "label" => $item["name"]);
				}
			}
			else if ($settings["fields"]["zone_id"]["field"]["input"]["type"] == "label" && !$settings["fields"]["zone_id"]["field"]["input"]["available_values"]) {
				$items = \ZipUtil::getAllZones($brokers);
				
				if ($items) {
					$settings["fields"]["zone_id"]["field"]["input"]["available_values"] = array();
					foreach ($items as $item)
						$settings["fields"]["zone_id"]["field"]["input"]["available_values"][ $item["zone_id"] ] = $item["name"];
				}
			}
		}
	}
}
?>
