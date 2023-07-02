<?php
namespace CMSModule\event\list_events;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		include_once $EVC->getModulePath("event/EventSettings", $common_project_name);
		include_once $EVC->getModulePath("event/EventUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, $GLOBALS["default_db_driver"], $settings, "event");
		
		//Preparing options
		$rows_per_page = $settings["rows_per_page"] > 0 ? $settings["rows_per_page"] : null;
		$options = array("limit" => $rows_per_page, "sort" => array());
		
		//Preparing pagination
		if ($settings["top_pagination_type"] || $settings["bottom_pagination_type"]) {
			include_once get_lib("org.phpframework.util.web.html.pagination.PaginationLayout");
			
			$current_page = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : 0;
			$rows_per_page = $rows_per_page > 0 ? $rows_per_page : 50;
			$options["start"] = \PaginationHandler::getStartValue($current_page, $rows_per_page);
		}
		
		//Getting events
		$events = \EventUI::getEventsFromSettings($EVC, $settings, $brokers, $options);
		$total = $events[0];
		$events = $events[1];
		
		//Getting Event Extra Details
		$CommonModuleTableExtraAttributesUtil->prepareItemsWithTableExtra($events, "event_id");
		
		//Add join point changing the events.
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing events", array(
			"EVC" => &$EVC,
			"settings" => &$settings,
			"total" => &$total,
			"events" => &$events,
		), "This join point's method/function can change the \$settings, \$total or \$events variables.");
		
		if ($events) {
			$from_label = translateProjectText($EVC, "From");
			$to_label = translateProjectText($EVC, "to");
			
			foreach ($events as &$event) {
				$event["begin_date"] = $event["begin_date"] ? substr($event["begin_date"], 0, strrpos($event["begin_date"], ":")) : '';
				$event["begin_date"] = $event["begin_date"] == '0000-00-00 00:00' ? '' : $event["begin_date"];
				
				$event["end_date"] = $event["end_date"] ? substr($event["end_date"], 0, strrpos($event["end_date"], ":")) : '';
				$event["end_date"] = $event["end_date"] == '0000-00-00 00:00' ? '' : $event["end_date"];
				
				$begin_date = explode(" ", $event["begin_date"]);
				$end_date = explode(" ", $event["end_date"]);
				
				$bd_time = strtotime($event["begin_date"]);
				$bdi = translateProjectText($EVC, date("l", $bd_time)) . date(", d ", $bd_time) . translateProjectText($EVC, date("F", $bd_time)) . (date("Y", $bd_time) != date("Y") ? date(" Y,", $bd_time) : "") . date(" H:i", $bd_time);
				$event["date_interval"] = $bdi;
	
				$event["date"] = '
					<label class="from">' . $from_label . '</label>
					<label class="from_date">' . $begin_date[0] . '</label>';
				
				$event["time"] = '
					<label class="from">' . $from_label . '</label>
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
				
				if ($event["end_date"]) {
					$ed_time = strtotime($event["end_date"]);
					$edi = $begin_date[0] == $end_date[0] ? "" : translateProjectText($EVC, date("l", $ed_time)) . date(", d ", $ed_time) . translateProjectText($EVC, date("F", $ed_time)) . (date("Y", $ed_time) != date("Y") ? date(" Y,", $ed_time) : "") . " ";
					$edi .= $begin_date[0] == $end_date[0] && $begin_date[1] == $end_date[1] ? "" : date("H:i", $ed_time);
					$event["date_interval"] .= $edi ? " - $edi" : "";
					
					$event["date"] .= '
					<label class="to">' . $to_label . '</label>
					<label class="to_date">' . $end_date[0] . '</label>';
				
					$event["time"] .= '
					<label class="to">' . $to_label . '</label>
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
				}
				
				$event["map_url"] = \EventUI::getMapUrl($event, false);
				$event["embed_map_url"] = \EventUI::getMapUrl($event);
				$event["map"] = $event["embed_map_url"] ? '<span class="map" onClick="openMap(this, \'' . $event["embed_map_url"] . '\'); return false;"></span>' : '';
				$event["full_address"] = $event["address"] ? '<span class="address">' . $event["address"] . ($event["zip_id"] ? ', ' . $event["zip_id"] : '') . ($event["locality"] ? ' ' . $event["locality"] : '') . '</span>' : '';
				$event["location"] = '<span class="location">' . $event["full_address"] . $event["map"] . '</span>';
			}
		}
		
		$html = '<div class="module_list_events ' . ($settings["block_class"]) . '">';
		$settings["block_class"] = null;
		
		//Getting events
		$settings["total"] = $total;
		$settings["data"] = $events;
		$settings["css_file"] = $project_common_url_prefix . 'module/event/list_events.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/event/list_events.js';
		$settings["class"] = "";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "event_id=#[idx][event_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/event/list_events/delete_event?event_id=#[idx][event_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "event/list_events", $settings);
		$html .= \CommonModuleUI::getListHtml($EVC, $settings);
		
		$html .= '</div>';
		
		return $html;
	}
}
?>
