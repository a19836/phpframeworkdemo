<?php
class EventUI {
	
	public static function prepareEvents($EVC, $settings, &$events) {
		if ($events) {
			$t = count($events);
			for ($i = 0; $i < $t; $i++)
				self::prepareEvent($EVC, $settings, $events[$i]);
		}
	}
	
	public static function prepareEvent($EVC, $settings, &$event) {
		if ($event) {
			$translations = array(
				"from" => translateProjectText($EVC, "From"),
				"to" => translateProjectText($EVC, "to"),
			);
			
			//Preparing dates and location
			$parsed_begin_date = $event["begin_date"] ? $event["begin_date"] : "";
			$parsed_begin_date = $parsed_begin_date && substr_count($parsed_begin_date, ':') >= 2 ? substr($parsed_begin_date, 0, strrpos($parsed_begin_date, ":")) : $parsed_begin_date;
			$parsed_begin_date = $parsed_begin_date == '0000-00-00 00:00' ? '' : $parsed_begin_date;
			
			$parsed_end_date = $event["end_date"] ? $event["end_date"] : "";
			$parsed_end_date = $parsed_end_date && substr_count($parsed_end_date, ':') >= 2 ? substr($parsed_end_date, 0, strrpos($parsed_end_date, ":")) : $parsed_end_date;
			$parsed_end_date = $parsed_end_date == '0000-00-00 00:00' ? '' : $parsed_end_date;
			
			$begin_date = explode(" ", $parsed_begin_date);
			$end_date = explode(" ", $parsed_end_date);
			
			$bd_time = strtotime($parsed_begin_date);
			$bdi = translateProjectText($EVC, date("l", $bd_time)) . date(", d ", $bd_time) . translateProjectText($EVC, date("F", $bd_time)) . (date("Y", $bd_time) != date("Y") ? date(" Y,", $bd_time) : "") . date(" H:i", $bd_time);
			$event["date_interval"] = $bdi;
			
			$event["date"] = '
				<label class="from">' . $translations["from"] . '</label>
				<label class="from_date">' . $begin_date[0] . '</label>';
			
			$event["time"] = '
				<label class="from">' . $translations["from"] . '</label>
				<label class="from_time">' . $begin_date[1] . '</label>';
			
			$date_parts = explode("-", $begin_date[0]);
			$time_parts = explode(":", $begin_date[1]);
			$event["begin_date_time"] = '
			<div class="date">
				<label class="month_text">' . translateProjectText($EVC, date("M", $bd_time)) . '</label>
				<label class="month">' . $date_parts[1] . '</label>
				<label class="day">' . $date_parts[2] . '</label>
				<label class="year">' . $date_parts[0] . '</label>
			</div>
			<div class="time">
				<label class="hour">' . $time_parts[0] . '</label>
				<label class="minute">' . $time_parts[1] . '</label>
			</div>';
			$event["begin_time"] = $time_parts[0] . ":" . $time_parts[1];
			$event["begin_year"] = $date_parts[0];
			$event["begin_month"] = $date_parts[1];
			$event["begin_month_short_text"] = date("M", $bd_time);
			$event["begin_month_long_text"] = date("F", $bd_time);
			$event["begin_day"] = $date_parts[2];
			$event["begin_hour"] = $time_parts[0];
			$event["begin_minute"] = $time_parts[1];
			
			if ($parsed_end_date) {
				$ed_time = strtotime($parsed_end_date);
				$edi = $begin_date[0] == $end_date[0] ? "" : translateProjectText($EVC, date("l", $ed_time)) . date(", d ", $ed_time) . translateProjectText($EVC, date("F", $ed_time)) . (date("Y", $ed_time) != date("Y") ? date(" Y,", $ed_time) : "") . " ";
				$edi .= $begin_date[0] == $end_date[0] && $begin_date[1] == $end_date[1] ? "" : date("H:i", $ed_time);
				$event["date_interval"] .= $edi ? " - $edi" : "";
				
				$event["date"] .= '
				<label class="to">' . $translations["to"] . '</label>
				<label class="to_date">' . $end_date[0] . '</label>';
				
				$event["time"] .= '
				<label class="to">' . $translations["to"] . '</label>
				<label class="to_time">' . $end_date[1] . '</label>';
				
				$date_parts = explode("-", $end_date[0]);
				$time_parts = explode(":", $end_date[1]);
				$event["end_date_time"] = '
				<div class="date">
					<label class="month_text">' . translateProjectText($EVC, date("M", $ed_time)) . '</label>
					<label class="month">' . $date_parts[1] . '</label>
					<label class="day">' . $date_parts[2] . '</label>
					<label class="year">' . $date_parts[0] . '</label>
				</div>
				<div class="time">
					<label class="hour">' . $time_parts[0] . '</label>
					<label class="minute">' . $time_parts[1] . '</label>
				</div>';
				$event["end_time"] = $time_parts[0] . ":" . $time_parts[1];
				$event["end_year"] = $date_parts[0];
				$event["end_month"] = $date_parts[1];
				$event["end_month_short_text"] = date("M", $ed_time);
				$event["end_month_long_text"] = date("F", $ed_time);
				$event["end_day"] = $date_parts[2];
				$event["end_hour"] = $time_parts[0];
				$event["end_minute"] = $time_parts[1];
			}
			
			$event["map_url"] = self::getMapUrl($event, false);
			$event["embed_map_url"] = self::getMapUrl($event);
			$event["map"] = $event["embed_map_url"] ? '<span class="map" onClick="openMap(this, \'' . $event["embed_map_url"] . '\'); return false;"></span>' : '';
			$event["full_address"] = $event["address"] ? '<span class="address">' . $event["address"] . ($event["zip_id"] ? ', ' . $event["zip_id"] : '') . ($event["locality"] ? ' ' . $event["locality"] : '') . '</span>' : '';
			$event["location"] = '<span class="location">' . $event["full_address"] . $event["map"] . '</span>';
		}
	}
	
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
		
		//prepare events data
		self::prepareEvents($EVC, $settings, $events);
		
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
