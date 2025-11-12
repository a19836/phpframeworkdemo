<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

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
			$parsed_begin_date = !empty($event["begin_date"]) ? $event["begin_date"] : "";
			$parsed_begin_date = $parsed_begin_date && substr_count($parsed_begin_date, ':') >= 2 ? substr($parsed_begin_date, 0, strrpos($parsed_begin_date, ":")) : $parsed_begin_date;
			$parsed_begin_date = $parsed_begin_date == '0000-00-00 00:00' ? '' : $parsed_begin_date;
			
			$parsed_end_date = !empty($event["end_date"]) ? $event["end_date"] : "";
			$parsed_end_date = $parsed_end_date && substr_count($parsed_end_date, ':') >= 2 ? substr($parsed_end_date, 0, strrpos($parsed_end_date, ":")) : $parsed_end_date;
			$parsed_end_date = $parsed_end_date == '0000-00-00 00:00' ? '' : $parsed_end_date;
			
			$begin_date = explode(" ", $parsed_begin_date);
			$end_date = explode(" ", $parsed_end_date);
			
			$begin_date[1] = isset($begin_date[1]) ? $begin_date[1] : null;
			$end_date[1] = isset($end_date[1]) ? $end_date[1] : null;
			
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
			$date_parts[1] = isset($date_parts[1]) ? $date_parts[1] : null;
			$date_parts[2] = isset($date_parts[2]) ? $date_parts[2] : null;
			
			$time_parts = explode(":", $begin_date[1]);
			$time_parts[1] = isset($time_parts[1]) ? $time_parts[1] : null;
			
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
			$event["map"] = !empty($event["embed_map_url"]) ? '<span class="map" onClick="openMap(this, \'' . $event["embed_map_url"] . '\'); return false;"></span>' : '';
			$event["full_address"] = !empty($event["address"]) ? '<span class="address">' . $event["address"] . (!empty($event["zip_id"]) ? ', ' . $event["zip_id"] : '') . (!empty($event["locality"]) ? ' ' . $event["locality"] : '') . '</span>' : '';
			$event["location"] = '<span class="location">' . (isset($event["full_address"]) ? $event["full_address"] : null) . (isset($event["map"]) ? $event["map"] : null) . '</span>';
		}
	}
	
	public static function getEventsFromSettings($EVC, $settings, $brokers, &$options) {
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("event/EventUtil", $common_project_name);
		
		if (!empty($settings["catalog_sort_column"])) {
			$catalog_sort_order = isset($settings["catalog_sort_order"]) ? $settings["catalog_sort_order"] : null;
			
			if ($settings["catalog_sort_column"] == "most_recent")
				$options["sort"][] = array("column" => "begin_date", "order" => $catalog_sort_order);
			else 
				$options["sort"][] = array("column" => $settings["catalog_sort_column"], "order" => $catalog_sort_order);
		}
		
		$conditions = CommonModuleUI::getConditionsFromSearchValues($settings);
		
		if (!empty($settings["filter_by_published"]))
			$conditions["published"] = 1;
		
		if (!empty($settings["catalog_sort_column"]) && $settings["catalog_sort_column"] == "most_recent")
			$conditions["or"] = array(
				"end_date" => array("operator" => ">", "value" => date("Y-m-d H:i:00")),
				"begin_date" => array("operator" => ">=", "value" => date("Y-m-d H:i:00")),//in case the end_date doesn't exists
			);
		
		//Getting events
		$events_type = isset($settings["events_type"]) ? $settings["events_type"] : null;
		$tags = isset($settings["tags"]) ? $settings["tags"] : null;
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		$group = isset($settings["group"]) ? $settings["group"] : null;
		$total = $events = null;
		
		switch ($events_type) {
			case "all":
				$total = $conditions ? EventUtil::countEventsByConditions($brokers, $conditions, null) : EventUtil::countAllEvents($brokers);
				$events = $conditions ? EventUtil::getEventsByConditions($brokers, $conditions, null, $options) : EventUtil::getAllEvents($brokers, $options);
				break;
			case "tags_and":
				if ($tags) {
					$total = EventUtil::countEventsWithAllTags($brokers, $tags, $conditions, null);
					$events = EventUtil::getEventsWithAllTags($brokers, $tags, $conditions, null, $options);
				}
				break;
			case "tags_or":
				if ($tags) {
					$total = EventUtil::countEventsByTags($brokers, $tags, $conditions, null);
					$events = EventUtil::getEventsByTags($brokers, $tags, $conditions, null, $options);
				}
				break;
			case "parent":
				$total = EventUtil::countEventsByObject($brokers, $object_type_id, $object_id, $conditions, null);
				$events = EventUtil::getEventsByObject($brokers, $object_type_id, $object_id, $conditions, null, $options);
				break;
			case "parent_group":
				$total = EventUtil::countEventsByObjectGroup($brokers, $object_type_id, $object_id, $group, $conditions, null);
				$events = EventUtil::getEventsByObjectGroup($brokers, $object_type_id, $object_id, $group, $conditions, null, $options);
				break;
			case "parent_tags_and":
				if ($tags) {
					$total = EventUtil::countEventsByObjectWithAllTags($brokers, $object_type_id, $object_id, $tags, $conditions, null);
					$events = EventUtil::getEventsByObjectWithAllTags($brokers, $object_type_id, $object_id, $tags, $conditions, null, $options);
				}
				break;
			case "parent_tags_or":
				if ($tags) {
					$total = EventUtil::countEventsByObjectAndTags($brokers, $object_type_id, $object_id, $tags, $conditions, null);
					$events = EventUtil::getEventsByObjectAndTags($brokers, $object_type_id, $object_id, $tags, $conditions, null, $options);
				}
				break;
			case "parent_group_tags_and":
				if ($tags) {
					$total = EventUtil::countEventsByObjectGroupWithAllTags($brokers, $object_type_id, $object_id, $group, $tags, $conditions, null);
					$events = EventUtil::getEventsByObjectGroupWithAllTags($brokers, $object_type_id, $object_id, $group, $tags, $conditions, null, $options);
				}
				break;
			case "parent_group_tags_or":
				if ($tags) {
					$total = EventUtil::countEventsByObjectGroupAndTags($brokers, $object_type_id, $object_id, $group, $tags, $conditions, null);
					$events = EventUtil::getEventsByObjectGroupAndTags($brokers, $object_type_id, $object_id, $group, $tags, $conditions, null, $options);
				}
				break;
			case "selected":
				$event_ids = isset($settings["event_ids"]) ? $settings["event_ids"] : null;
				if ($event_ids) {
					$total = count($event_ids);
					$items = EventUtil::getEventsByIds($brokers, $event_ids, $options);
				
					$events = array();
					if (is_array($items) && !empty($items)) {
						$t = count($event_ids);
						for ($i = 0; $i < $t; $i++) {
							foreach ($items as $item) {
								$item_event_id = isset($item["event_id"]) ? $item["event_id"] : null;
								
								if ($item_event_id == $event_ids[$i] && (empty($settings["filter_by_published"]) || !empty($item["published"]))) {
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
		if (!empty($data["latitude"]) && !empty($data["longitude"]) && $data["latitude"] != '0.00000000' && $data["longitude"] != '0.00000000')
			return $embed ? "https://www.google.com/maps/embed/v1/place?key=$google_maps_key&q=loc:" . $data["latitude"] . "," . $data["longitude"] : "https://maps.google.com/maps?daddr=" . $data["latitude"] . "," . $data["longitude"];
	
		//https://www.google.com/maps/embed/v1/place?q=...&key=address
		if (!empty($data["address"])) {
			$address = trim($data["address"]) . (!empty($data["zip_id"]) ? ", " . $data["zip_id"] : "") . (!empty($data["locality"]) ? " " . $data["locality"] : "") . (!empty($data["country"]) ? ", " . $data["country"] : "");
			
			return $embed ? "https://www.google.com/maps/embed/v1/place?key=$google_maps_key&q=" . $address : "https://maps.google.com/maps?daddr=" . $address;
		}
		
		return null;
	}
}
?>
