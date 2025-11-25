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

namespace CMSModule\event\catalog;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		include_once $EVC->getModulePath("event/EventSettings", $common_project_name);
		include_once $EVC->getModulePath("event/EventUI", $common_project_name);
		include_once $EVC->getModulePath("zip/ZipUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, isset($GLOBALS["default_db_driver"]) ? $GLOBALS["default_db_driver"] : null, $settings, "event");
		
		$html = '
		<!-- Fancy LighBox -->
		<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymyfancylightbox/css/style.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymyfancylightbox/js/jquery.myfancybox.js"></script>
		
		<!-- Local -->
		<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/event/map.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="' . $project_common_url_prefix . 'module/event/map.js"></script>';
		
		if (empty($settings["style_type"]))
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/event/catalog.css" type="text/css" charset="utf-8" />';
		
		$html .= '<script src="' . $project_common_url_prefix . 'module/event/catalog.js"></script>
		' . (!empty($settings["css"]) ? '<style>' . $settings["css"] . '</style>' : '') . '
		' . (!empty($settings["js"]) ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '') . '
		
		<div class="module_events_catalog ' . (isset($settings["block_class"]) ? $settings["block_class"] : null) . '">';
		
		$catalog_title = isset($settings["catalog_title"]) ? $settings["catalog_title"] : null;
		if ($catalog_title)
			$html .= '<h1 class="catalog_title">' . translateProjectText($EVC, $catalog_title) . '</h1>';
		
		//Preparing options
		$rows_per_page = isset($settings["rows_per_page"]) && $settings["rows_per_page"] > 0 ? $settings["rows_per_page"] : null;
		$options = array("limit" => $rows_per_page, "sort" => array());
		
		//Preparing pagination
		if (!empty($settings["top_pagination_type"]) || !empty($settings["bottom_pagination_type"])) {
			include_once get_lib("org.phpframework.util.web.html.pagination.PaginationLayout");
			
			$current_page = isset($_GET["current_page"]) && is_numeric($_GET["current_page"]) ? $_GET["current_page"] : 0;
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
			$countries = \ZipUtil::getAllCountries($brokers);
			
			//Preparing countries data
			if ($countries) {
				$countries_by_id = array();
				foreach ($countries as $country) {
					$country_id = isset($country["country_id"]) ? $country["country_id"] : null;
					$country_name = isset($country["name"]) ? $country["name"] : null;
					
					$countries_by_id[$country_id] = $country_name;
				}
				
				foreach ($events as $idx => $event) {
					$country_id = isset($event["country_id"]) ? $event["country_id"] : null;
					
					$events[$idx]["country"] = isset($countries_by_id[$country_id]) ? $countries_by_id[$country_id] : null;
				}
			}
			
			//Preparing users data
			if (!empty($settings["show_user"])) {
				$event_idx_by_ids = array();
				foreach ($events as $idx => $event) {
					$event_id = isset($event["event_id"]) ? $event["event_id"] : null;
					
					$event_idx_by_ids[$event_id] = $idx;
				}
				
				$event_ids = array_keys($event_idx_by_ids);
				$object_events = $event_ids ? \EventUtil::getObjectEventsByConditions($brokers, array("event_id" => array("operator" => "in", "value" => $event_ids), "object_type_id" => \ObjectUtil::USER_OBJECT_TYPE_ID), null) : null;
				
				if ($object_events) {
					$user_event_ids = array();
					foreach ($object_events as $object_event) {
						$object_event_id = isset($object_event["object_id"]) ? $object_event["object_id"] : null;
						
						$user_event_ids[$object_event_id][] = isset($object_event["event_id"]) ? $object_event["event_id"] : null;
					}
					
					if ($user_event_ids) {
						include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
						
						$user_ids = array_keys($user_event_ids);
						$users = \UserUtil::getUsersByConditions($brokers, array("user_id" => array("operator" => "in", "value" => $user_ids)), null);
						
						if ($users) {
							foreach ($users as $user) 
								foreach ($user_event_ids[ $user["user_id"] ] as $event_id)
									$events[ $event_idx_by_ids[$event_id] ]["user"] = $user;
						}
					}
				}
			}
			
			//Add Join Point to edit events data
			$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("Edit events data", array(
				"EVC" => &$EVC,
				"data" => &$events,
			));
		}
		
		$current_url = isset($settings["event_properties_url"]) ? $settings["event_properties_url"] : null;
		
		//Preparing pagination
		if (!empty($settings["top_pagination_type"]) || !empty($settings["bottom_pagination_type"])) {
			$PaginationLayout = new \PaginationLayout($total, $rows_per_page, array("current_page" => $current_page), "current_page");
			$PaginationLayout->show_x_pages_at_once = 10;
			$pagination_data = $PaginationLayout->data;
		}
		
		$catalog_type = isset($settings["catalog_type"]) ? $settings["catalog_type"] : null;
		
		//prepare settings with selected template html if apply
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "event/catalog", $settings);
		
		//execute user list with ptl
		if ($catalog_type == "user_list" && !empty($settings["ptl"])) {
			$form_settings = array("ptl" => $settings["ptl"]);
			$events_item_input_data_var_name = isset($form_settings["ptl"]["external_vars"]["events_item_input_data_var_name"]) ? $form_settings["ptl"]["external_vars"]["events_item_input_data_var_name"] : null; //this should contain "event" by default, but is not mandatory. This value should be the same than the following foreach-item-value-name: <ptl:foreach $input i event>, but only if the user doesn't change this value. If the user changes the foreach to <ptl:foreach $input i item>, he must change the external var "events_item_input_data_var_name" to "item" too.
			if ($events_item_input_data_var_name)
				$form_settings["ptl"]["input_data_var_name"] = $events_item_input_data_var_name;
			$HtmlFormHandler = new \HtmlFormHandler($form_settings);
			
			$settings["ptl"]["code"] = isset($settings["ptl"]["code"]) ? $settings["ptl"]["code"] : null;
			
			foreach ($settings["fields"] as $field_id => $field) 
				if (!empty($settings["show_" . $field_id]))
					\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $field_id, $field, $events);
			
			if (!empty($settings["top_pagination_type"])) {
				$pagination_data["style"] = isset($settings["top_pagination_type"]) ? $settings["top_pagination_type"] : null;
				$settings["ptl"]["code"] = preg_replace('/<ptl:block:top-pagination\s*\/?>/i', $PaginationLayout->designWithStyle(1, $pagination_data), $settings["ptl"]["code"]);
			}
			
			if (!empty($settings["bottom_pagination_type"])) {
				$pagination_data["style"] = isset($settings["bottom_pagination_type"]) ? $settings["bottom_pagination_type"] : null;
				$settings["ptl"]["code"] = preg_replace('/<ptl:block:bottom-pagination\s*\/?>/i', $PaginationLayout->designWithStyle(1, $pagination_data), $settings["ptl"]["code"]);
			}
			
			\CommonModuleUI::cleanBlockPTLCode($settings["ptl"]["code"]);
			
			$form_settings = array(
				"ptl" => $settings["ptl"],
				"CacheHandler" => $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler")
			);
		
			$html .= \HtmlFormHandler::createHtmlForm($form_settings, $events);
		}
		else { //execute blog and normal list or user list with no ptl
			//showing top pagination
			if (!empty($settings["top_pagination_type"])) {
				$pagination_data["style"] = isset($settings["top_pagination_type"]) ? $settings["top_pagination_type"] : null;
				
				$html .= '<div class="top_pagination pagination_alignment_' . (isset($settings["top_pagination_alignment"]) ? $settings["top_pagination_alignment"] : null) . '">' . $PaginationLayout->designWithStyle(1, $pagination_data) . '</div>';
			}
			
			//showing catalog
			$html .= '<ul class="catalog catalog_' . $catalog_type . '">';
			
			if ($catalog_type == "blog_list") {
				$blog_introduction_events_num = isset($settings["blog_introduction_events_num"]) ? $settings["blog_introduction_events_num"] : null;
				$blog_featured_events_num = isset($settings["blog_featured_events_num"]) ? $settings["blog_featured_events_num"] : null;
				$blog_featured_events_cols = isset($settings["blog_featured_events_cols"]) ? $settings["blog_featured_events_cols"] : null;
				$blog_listed_events_num = isset($settings["blog_listed_events_num"]) ? $settings["blog_listed_events_num"] : null;
				
				$html .= self::getCatalogListHtml($EVC, $settings, $common_project_name, $current_url, $events, $blog_introduction_events_num, $blog_featured_events_num, $blog_featured_events_cols, $blog_listed_events_num);
			}
			else //execute normal list and user list with no ptl
				$html .= self::getCatalogListHtml($EVC, $settings, $common_project_name, $current_url, $events);
			
			$html .= '</ul>';
			
			if (!empty($settings["bottom_pagination_type"])) {
				$pagination_data["style"] = isset($settings["bottom_pagination_type"]) ? $settings["bottom_pagination_type"] : null;
				
				$html .= '<div class="bottom_pagination pagination_alignment_' . (isset($settings["bottom_pagination_alignment"]) ? $settings["bottom_pagination_alignment"] : null) . '">' . $PaginationLayout->designWithStyle(1, $pagination_data) . '</div>';
			}
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	private static function getCatalogListHtml($EVC, $settings, $common_project_name, $current_url, $events, $introduction_events_num = false, $featured_events_num = false, $featured_events_cols = false, $listed_events_num = false) {
		$html = '';
		
		if ($events) {
			$is_blog = $introduction_events_num || $featured_events_num || $listed_events_num;
			$featured_aux = 1;
			$featured_50 = false;
			$featured_flag = false;
			$exists_more_events = $has_listed = false;
			$featured_events_cols = $featured_events_cols ? $featured_events_cols : 3;
			
			$t = count($events);
			for ($i = 0; $i < $t; $i++) {
				$class = "";
				$clear = false;
				$rest = $t - $i;
				
				if ($introduction_events_num > 0) {
					$class = "introduction_event";
					--$introduction_events_num;
				}
				else if ($featured_events_num > 0) {
					if ($featured_events_cols == 3) {
						if ($featured_events_num >= 3 || $featured_aux > 1) {
							$class = "featured_event_" . ($featured_aux == 1 ? "70" : "30") . " " . ($featured_flag ? "featured_event_right" : "featured_event_left");
							
							if ($featured_aux == 3) {
								$featured_aux = 1;
								$featured_flag = !$featured_flag;
								$clear = true;
							}
							else {
								$featured_aux++;
							}
						}
						else if (($featured_events_num == 2 && $rest >= 2) || $featured_50) {
							$class = "featured_event_50 " . ($featured_events_num == 2 ? "featured_event_50_left" : "featured_event_50_right");
							$clear = $featured_50;
							$featured_50 = true;
						}
						else {
							$class = "featured_event";
						}
					}
					else if ($featured_events_cols == 2 && ($rest >= 2 || $featured_50)) {
						$class = "featured_event_50 " . (!$featured_50 ? "featured_event_50_left" : "featured_event_50_right");
						$clear = $featured_50;
						$featured_50 = !$featured_50;
					}
					else {
						$class = "featured_event";
					}
					
					--$featured_events_num;
				}
				else if ($listed_events_num > 0) {
					if (!$has_listed) {
						$html .= '<li class="listed_events"><ol>';
						$has_listed = true;
					}
					
					$class = "listed_event";
					--$listed_events_num;
				}
				else if ($is_blog) {
					if (!$has_listed) {
						$html .= '<li class="listed_events"><ol>';
						$has_listed = true;
					}
					
					$class = "listed_event event_hidden";
					$exists_more_events = true;
				}
				else {
					$class = "event";
				}
				
				$html .= '<li class="' . $class . '">' . self::getCatalogEventHtml($EVC, $settings, $common_project_name, $current_url, $events[$i]) . '</li>';
				
				if ($clear) {
					$html .= '<li class="catalog_event_clear"></li>';
				}
			}
			
			if ($has_listed) {
				$html .= '</ol></li>';
			}
			
			if ($exists_more_events) {
				$html .= '<li class="more_events" onClick="seeMoreEvents(this)">' . translateProjectText($EVC, "Click here to see more events...") . '</li>';
			}
		}
		else {
			$html .= '<li><h3 class="no_events">' . translateProjectText($EVC, "There are no available events...") . '</h3></li>';
		}
		
		return $html;
	}
	
	private static function getCatalogEventHtml($EVC, $settings, $common_project_name, $current_url, $event) {
		//Preparing fields
		$form_settings = array(
			"with_form" => 0,
			"form_containers" => array(
				0 => array(
					"container" => array(
						"elements" => array(),
						"next_html" => '<div class="catalog_event_clear"></div>',
					)
				),
			)
		);
		
		if ($current_url) {
			$form_settings["form_containers"][0]["container"]["href"] = $current_url . (isset($event["event_id"]) ? $event["event_id"] : null);
			$form_settings["form_containers"][0]["container"]["title"] = isset($event["title"]) ? $event["title"] : null;
		}
		
		$HtmlFormHandler = null;
		if (!empty($settings["ptl"])) {
			$settings["ptl"]["code"] = isset($settings["ptl"]["code"]) ? $settings["ptl"]["code"] : null;
			$HtmlFormHandler = new \HtmlFormHandler(array("ptl" => $settings["ptl"]));
		}
		
		foreach ($settings["fields"] as $field_id => $field)
			if (!empty($settings["show_" . $field_id]) && ($field_id == "photo" || !empty($event[$field_id]))) {
				//Preparing ptl
				if (!empty($settings["ptl"]))
					\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $field_id, $field, $event);
				else
					$form_settings["form_containers"][0]["container"]["elements"][] = $field;
			}
		
		//add ptl to form_settings
		if (!empty($settings["ptl"])) {
			\CommonModuleUI::cleanBlockPTLCode($settings["ptl"]["code"]);
			$form_settings["form_containers"][0]["container"]["elements"][] = array("ptl" => $settings["ptl"]);
		}
		
		translateProjectFormSettings($EVC, $form_settings);
	
		$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
		
		return \HtmlFormHandler::createHtmlForm($form_settings, $event);
	}
		
	/*private static function getCatalogEventHtml($EVC, $settings, $common_project_name, $current_url, $event) {
		$begin_date = explode(" ", isset($event["begin_date"]) ? $event["begin_date"] : null);
		$end_date = explode(" ", isset($event["end_date"]) ? $event["end_date"] : null);
		
		$begin_date[1] = isset($begin_date[1]) ? $begin_date[1] : null;
		$end_date[1] = isset($end_date[1]) ? $end_date[1] : null;
		
		$date = '
		<div class="catalog_event_date">
			<label class="from">From</label>
			<label class="from_date">' . $begin_date[0] . '</label>
			<label class="to">to</label>
			<label class="to_date">' . $end_date[0] . '</label>
		</div>';
		$time = '
		<div class="catalog_event_time">
			<label class="from">From</label>
			<label class="from_time">' . substr($begin_date[1], 0, strrpos($begin_date[1], ":")) . '</label>
			<label class="to">to</label>
			<label class="to_time">' . substr($end_date[1], 0, strrpos($end_date[1], ":")) . '</label>
		</div>';
		
		$photo = '<div class="catalog_event_photo">';
		if (!empty($event["photo_id"]) && isset($event["photo_path"]) && file_exists($event["photo_path"])) {
			$photo .= '<img src="' . (isset($event["photo_url"]) ? $event["photo_url"] : null) . '" />';
		}
		$photo .= '</div>';
		
		if (!empty($event["title"])) {
			$title = '<h1 class="catalog_event_title">';
			if ($current_url)
				$title .= '<a href="' . $current_url . (isset($event["event_id"]) ? $event["event_id"] : null) . '">' . $event["title"] . '</a>';
			else
				$title .= $event["title"];
			$title .= '</h1>';
		}
		
		$sub_title = !empty($event["sub_title"]) ? '<h2 class="catalog_event_sub_title">' . $event["sub_title"] . '</h2>' : '';
		
		$map_url = \EventUI::getMapUrl($event);
		$map = $map_url ? '<span class="map" onClick="openMap(this, \'' . $map_url . '\')"></span>' : '';
		$address = !empty($event["address"]) ? '<span class="address">' . $event["address"] . (!empty($event["zip_id"]) ? ', ' . $event["zip_id"] : '') . '</span>' : '';
		$location = '<h3 class="catalog_event_location">' . $address . $map . '</h3>';
		
		return $date . $time . $photo . '
			<div class="catalog_event_data">' . $title . $sub_title . $location . '</div>
			<div class="catalog_event_clear"></div>';
	}*/
}
?>
