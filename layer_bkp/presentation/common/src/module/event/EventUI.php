<?php
class EventUI {
	
	public static function getEventsFromSettings($EVC, $settings, $brokers, &$options) {
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("event/EventUtil", $common_project_name);
		
		if ($settings["catalog_sort_column"]) {
			if ($settings["catalog_sort_column"] == "most_recent")
				$options["sort"][] = array("column" => "begin_date", "order" => $settings["catalog_sort_order"]);
			else 
				$options["sort"][] = array("column" => $settings["catalog_sort_column"], "order" => $settings["catalog_sort_order"]);
		}
		
		$conditions = CommonModuleUI::getConditionsFromSearchValues($settings);
		
		if ($settings["filter_by_published"])
			$conditions["published"] = 1;
		
		if ($settings["catalog_sort_column"] == "most_recent")
			$conditions["or"] = array(
				"end_date" => array("operator" => ">", "value" => date("Y-m-d H:i:00")),
				"begin_date" => array("operator" => ">=", "value" => date("Y-m-d H:i:00")),//in case the end_date doesn't exists
			);
		
		//Getting events
		switch ($settings["events_type"]) {
			case "all":
				$total = $conditions ? EventUtil::countEventsByConditions($brokers, $conditions, null) : EventUtil::countAllEvents($brokers);
				$events = $conditions ? EventUtil::getEventsByConditions($brokers, $conditions, null, $options) : EventUtil::getAllEvents($brokers, $options);
				break;
			case "tags_and":
				$tags = $settings["tags"];
				if ($tags) {
					$total = EventUtil::countEventsWithAllTags($brokers, $tags, $conditions, null);
					$events = EventUtil::getEventsWithAllTags($brokers, $tags, $conditions, null, $options);
				}
				break;
			case "tags_or":
				$tags = $settings["tags"];
				if ($tags) {
					$total = EventUtil::countEventsByTags($brokers, $tags, $conditions, null);
					$events = EventUtil::getEventsByTags($brokers, $tags, $conditions, null, $options);
				}
				break;
			case "parent":
				$total = EventUtil::countEventsByObject($brokers, $settings["object_type_id"], $settings["object_id"], $conditions, null);
				$events = EventUtil::getEventsByObject($brokers, $settings["object_type_id"], $settings["object_id"], $conditions, null, $options);
				break;
			case "parent_group":
				$total = EventUtil::countEventsByObjectGroup($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $conditions, null);
				$events = EventUtil::getEventsByObjectGroup($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $conditions, null, $options);
				break;
			case "parent_tags_and":
				$tags = $settings["tags"];
				if ($tags) {
					$total = EventUtil::countEventsByObjectWithAllTags($brokers, $settings["object_type_id"], $settings["object_id"], $tags, $conditions, null);
					$events = EventUtil::getEventsByObjectWithAllTags($brokers, $settings["object_type_id"], $settings["object_id"], $tags, $conditions, null, $options);
				}
				break;
			case "parent_tags_or":
				$tags = $settings["tags"];
				if ($tags) {
					$total = EventUtil::countEventsByObjectAndTags($brokers, $settings["object_type_id"], $settings["object_id"], $tags, $conditions, null);
					$events = EventUtil::getEventsByObjectAndTags($brokers, $settings["object_type_id"], $settings["object_id"], $tags, $conditions, null, $options);
				}
				break;
			case "parent_group_tags_and":
				$tags = $settings["tags"];
				if ($tags) {
					$total = EventUtil::countEventsByObjectGroupWithAllTags($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $tags, $conditions, null);
					$events = EventUtil::getEventsByObjectGroupWithAllTags($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $tags, $conditions, null, $options);
				}
				break;
			case "parent_group_tags_or":
				$tags = $settings["tags"];
				if ($tags) {
					$total = EventUtil::countEventsByObjectGroupAndTags($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $tags, $conditions, null);
					$events = EventUtil::getEventsByObjectGroupAndTags($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $tags, $conditions, null, $options);
				}
				break;
			case "selected":
				$event_ids = $settings["event_ids"];
				if ($event_ids) {
					$total = count($event_ids);
					$items = EventUtil::getEventsByIds($brokers, $event_ids, $options);
				
					$events = array();
					if (is_array($items) && !empty($items)) {
						$t = count($event_ids);
						for ($i = 0; $i < $t; $i++) {
							foreach ($items as $item) {
								if ($item["event_id"] == $event_ids[$i] && (!$settings["filter_by_published"] || $item["published"])) {
									$events[] = $item;
									break;
								}
							}
						}
					}
				}
				break;
		}
		
		//get photos
		EventUtil::prepareEventsPhotos($EVC, $events, false, $brokers);
		
		return array($total, $events);
	}
	
	//https://developers.google.com/maps/documentation/embed/guide
	public static function getMapUrl($data, $embed = true) {
		$google_maps_key = EventSettings::getConstantVariable("GOOGLE_MAPS_KEY");
		
		//https://www.google.com/maps/embed/v1/place?key=...&q=loc:38.9419+-78.3020
		if ($data["latitude"] && $data["longitude"] && $data["latitude"] != '0.00000000' && $data["longitude"] != '0.00000000')
			return $embed ? "https://www.google.com/maps/embed/v1/place?key=$google_maps_key&q=loc:" . $data["latitude"] . "," . $data["longitude"] : "https://maps.google.com/maps?daddr=" . $data["latitude"] . "," . $data["longitude"];
	
		//https://www.google.com/maps/embed/v1/place?q=...&key=address
		if ($data["address"]) {
			$address = trim($data["address"]) . ($data["zip_id"] ? ", " . $data["zip_id"] : "") . ($data["locality"] ? " " . $data["locality"] : "") . ($data["country"] ? ", " . $data["country"] : "");
			
			return $embed ? "https://www.google.com/maps/embed/v1/place?key=$google_maps_key&q=" . $address : "https://maps.google.com/maps?daddr=" . $address;
		}
		
		return null;
	}
}
?>
