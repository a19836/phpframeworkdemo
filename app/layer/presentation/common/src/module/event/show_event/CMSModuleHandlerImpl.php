<?php
namespace CMSModule\event\show_event;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		$event_id = is_numeric($settings["event_id"]) ? $settings["event_id"] : null;
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		include_once $EVC->getModulePath("event/EventSettings", $common_project_name);
		include_once $EVC->getModulePath("event/EventUI", $common_project_name);
		include_once $EVC->getModulePath("event/EventUtil", $common_project_name);
		include_once $EVC->getModulePath("zip/ZipUtil", $common_project_name);
		include_once $EVC->getModulePath("attachment/AttachmentUI", $common_project_name);
		include_once $EVC->getModulePath("comment/CommentUI", $common_project_name);
		include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, $GLOBALS["default_db_driver"], $settings, "event");
		
		$html = '
		<!-- Fancy LighBox -->
		<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymyfancylightbox/css/style.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymyfancylightbox/js/jquery.myfancybox.js"></script>
		
		<!-- Local -->
		<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/event/map.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="' . $project_common_url_prefix . 'module/event/map.js"></script>';
		
		if (empty($settings["style_type"])) {
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/event/show_event.css" type="text/css" charset="utf-8" />';
		}
		
		$html .= ($settings["css"] ? '<style>' . $settings["css"] . '</style>' : '') . '
		' . ($settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '') . '
		
		<script>
			var jquery_lib_url = jquery_lib_url ? jquery_lib_url : \'' . $project_common_url_prefix . 'vendor/jquery/js/jquery-1.8.1.min.js\';
		</script>
		
		<div class="module_event ' . ($settings["block_class"]) . '">';
		
		if ($event_id) {
			$data = \EventUtil::getEventProperties($EVC, $event_id, true);
			
			if ($data) {
				//Getting Event Extra Details
				$data_extra = $CommonModuleTableExtraAttributesUtil->getTableExtra(array("event_id" => $event_id), true);
				$data = $data_extra ? array_merge($data, $data_extra) : $data;
				
				//Preparing event data
				\EventUI::prepareEvent($EVC, $settings, $data);
			}
			
			//Add join point changing the event properties.
			$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing event properties", array(
				"EVC" => &$EVC,
				"settings" => &$settings,
				"data" => &$data,
				"event_id" => &$event_id,
			), "This join point's method/function can change the \$settings or \$data variables. \$data contains the event properties.");
			
			if ($data) {
				if (!$data["published"] && !$settings["allow_not_published"]) {
					$html .= '<h3 class="event_error">' . translateProjectText($EVC, "Event not Published!") . '</h3>';
				}
				else if ($settings["fields"]) {
					if (!$data["end_date"] && $settings["show_end_date"])
						$settings["show_end_date"] = false;
					
					if ($data["country_id"]) {
						$countries = \ZipUtil::getAllCountries($brokers);
						
						if ($countries)
							foreach ($countries as $country) 
								if ($country["country_id"] == $data["country_id"]) {
									$data["country"] = $country["name"];
									break;
								}
					}
					
					//Preparing user data
					if ($settings["show_user"]) {
						$object_events = \EventUtil::getObjectEventsByConditions($brokers, array("event_id" => $data["event_id"], "object_type_id" => \ObjectUtil::USER_OBJECT_TYPE_ID), null);
						
						if ($object_events[0]) {
							include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
						
							$user_data = \UserUtil::getUsersByConditions($brokers, array("user_id" => $object_events[0]["object_id"]), null);
							$data["user"] = $user_data[0];
						}
					}
					
					//Add Join Point to edit event data
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("Edit event data", array(
						"EVC" => &$EVC,
						"data" => &$data,
					));
					
					//Preparing fields
					$form_settings = array(
						"with_form" => 0,
						"form_containers" => array(
							0 => array(
								"container" => array(
									"elements" => array()
								)
							),
						)
					);
					
					$attachments_html = '';
					if ($settings["show_attachments"]) {
						$attachments_settings = array(
							"style_type" => $settings["style_type"],
							"class" => $settings["fields"]["attachments"]["field"]["class"],
							"title" => $settings["fields"]["attachments"]["field"]["label"]["value"],
						);
						$attachments_html = \AttachmentUI::getObjectAttachmentsHtml($EVC, $attachments_settings, \ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id, \EventUtil::EVENT_ATTACHMENTS_GROUP_ID) . '<div class="clear"></div>';
					}
					
					$comments_html = '';
					if ($settings["show_comments"]) {
						$comments_settings = array(
							"style_type" => $settings["style_type"],
							"class" => $settings["fields"]["comments"]["field"]["class"],
							"title" => $settings["fields"]["comments"]["field"]["label"]["value"],
							"add_comment_url" => $data["allow_comments"] ? $settings["fields"]["comments"]["field"]["add_comment_url"] : null,
						);
						
						//Add join point initting the $settings[comments_users] with the correspondent users' data array for the event comments.
						$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing event comments settings", array(
							"EVC" => &$EVC,
							"settings" => &$comments_settings,
							"object_type_id" => \ObjectUtil::EVENT_OBJECT_TYPE_ID,
							"object_id" => &$event_id,
						), "This join point's method/function can set the \$settings[comments_users] and \$settings[current_user] items with the correspondent users' data for the event's comments and correspondent logged user data. Additionally can set the following items too: \$settings[add_comment_label], \$settings[add_comment_textarea_place_holder], \$settings[add_comment_button_label], \$settings[add_comment_error_message], \$settings[empty_comments_label], \$settings[style_type], \$settings[class], \$settings[title] and \$settings[add_comment_url]. These items are optional.");
						
						$comments_html = \CommentUI::getObjectCommentsHtml($EVC, $comments_settings, \ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id);
					}
					
					//prepare settings with selected template html if apply
					\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "event/show_event", $settings);
					
					$HtmlFormHandler = null;
					if ($settings["ptl"])
						$HtmlFormHandler = new \HtmlFormHandler(array("ptl" => $settings["ptl"]));
					
					$container_idx = 0;
					foreach ($settings["fields"] as $field_id => $field) {
						if ($settings["show_" . $field_id]) {
							//Preparing ptl
							if ($settings["ptl"]) {
								if ($field_id == "attachments")
									$settings["ptl"]["code"] = preg_replace('/<ptl:block:field:attachments\s*\/?>/', $attachments_html, $settings["ptl"]["code"]);
								else if ($field_id == "comments")
									$settings["ptl"]["code"] = preg_replace('/<ptl:block:field:comments\s*\/?>/', $comments_html, $settings["ptl"]["code"]);
								else if ($field_id == "photo" || $data[$field_id])
									\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $field_id, $field, $data);
							}
							else {
								if ($field_id == "attachments") {
									$form_settings["form_containers"][$container_idx]["container"]["next_html"] = '<div class="clear"></div>' . $attachments_html;
									$container_idx++;
								}
								else if ($field_id == "comments") {
									$form_settings["form_containers"][$container_idx]["container"]["next_html"] = '<div class="clear"></div>' . $comments_html;
									$container_idx++;
								}
								else if ($field_id == "photo" || $data[$field_id])
									$form_settings["form_containers"][$container_idx]["container"]["elements"][] = $field;
							}
						}
					}
					
					//add ptl to form_settings
					if ($settings["ptl"]) {
						\CommonModuleUI::cleanBlockPTLCode($settings["ptl"]["code"]);
						$form_settings["form_containers"][$container_idx]["container"]["elements"][] = array("ptl" => $settings["ptl"]);
					}
					
					$form_settings["form_containers"][$container_idx]["container"]["next_html"] = '<div class="clear"></div>';
					translateProjectFormSettings($EVC, $form_settings);
					
					$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
					
					$html .= \HtmlFormHandler::createHtmlForm($form_settings, $data);
				}
			}
		}
		else
			$html .= '<h3 class="event_error">' . translateProjectText($EVC, "Invalid Event") . '</h3>';
		
		$html .= '</div>';
				
		return $html;
	}
}
?>
