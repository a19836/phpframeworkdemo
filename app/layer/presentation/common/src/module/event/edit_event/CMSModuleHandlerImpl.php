<?php
namespace CMSModule\event\edit_event;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("event/EventSettings", $common_project_name);
		include_once $EVC->getModulePath("event/EventUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, $GLOBALS["default_db_driver"], $settings, "event");
		
		//Getting Event Details
		$event_id = $_GET["event_id"];
		$data = \EventUtil::getEventProperties($EVC, $event_id, true);
		$photo_url = $data["photo_url"];
		
		//Getting Event Extra Details
		if ($data) {
			$data_extra = $CommonModuleTableExtraAttributesUtil->getTableExtra(array("event_id" => $event_id), true);
			$data = $data_extra ? array_merge($data, $data_extra) : $data;
		}
		
		//Preparing Action
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \EventUtil::deleteEvent($EVC, $data["event_id"]);
				
				if ($status && $data["event_id"])
					$status = $CommonModuleTableExtraAttributesUtil->deleteTableExtra(array("event_id" => $data["event_id"]));
				
				if ($status) {
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull event deleting action", array(
						"EVC" => &$EVC,
						"event_id" => $data["event_id"],
						"event_data" => &$data,
						"error_message" => &$error_message,
					));
				}
			}
			else if ($_POST["save"]) {
				$title = $_POST["title"];
				$sub_title = $_POST["sub_title"];
				$published = $_POST["published"];
				$tags = $_POST["tags"];
				$photo_id = $_POST["photo_id"];
				$description = $_POST["description"];
				$address = $_POST["address"];
				$zip_id = $_POST["zip_id"];
				$locality = $_POST["locality"];
				$country_id = $_POST["country_id"];
				$latitude = $_POST["latitude"];
				$longitude = $_POST["longitude"];
				$begin_date = $_POST["begin_date"];
				$end_date = $_POST["end_date"];
				$allow_comments = $_POST["allow_comments"];
				
				$photo_id = $photo_id ? $photo_id : 0;
				$begin_date = $begin_date == '0000-00-00 00:00:00' ? '' : $begin_date;
				$end_date = $end_date == '0000-00-00 00:00:00' ? '' : $end_date;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("title" => $title, "sub_title" => $sub_title, "published" => $published, "tags" => $tags, "photo_id" => $photo_id, "description" => $description, "address" => $address, "zip_id" => $zip_id, "locality" => $locality, "country_id" => $country_id, "latitude" => $latitude, "longitude" => $longitude, "begin_date" => $begin_date, "end_date" => $end_date, "allow_comments" => $allow_comments));
				
				if (!$empty_field_name)
					$empty_field_name = $CommonModuleTableExtraAttributesUtil->checkIfEmptyFields($settings, $_POST);
				
				if ($empty_field_name) 
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				else {
					$new_data = $data;
					$new_data["title"] = $settings["show_title"] ? $title : $new_data["title"];
					$new_data["sub_title"] = $settings["show_sub_title"] ? $sub_title : $new_data["sub_title"];
					$new_data["published"] = $settings["show_published"] ? $published : $new_data["published"];
					$new_data["tags"] = $settings["show_tags"] ? $tags : $new_data["tags"];
					$new_data["photo_id"] = $settings["show_photo_id"] ? $photo_id : $new_data["photo_id"];
					$new_data["description"] = $settings["show_description"] ? $description : $new_data["description"];
					$new_data["address"] = $settings["show_address"] ? $address : $new_data["address"];
					$new_data["zip_id"] = $settings["show_zip_id"] ? $zip_id : $new_data["zip_id"];
					$new_data["locality"] = $settings["show_locality"] ? $locality : $new_data["locality"];
					$new_data["country_id"] = $settings["show_country_id"] ? $country_id : $new_data["country_id"];
					$new_data["latitude"] = $settings["show_latitude"] ? $latitude : $new_data["latitude"];
					$new_data["longitude"] = $settings["show_longitude"] ? $longitude : $new_data["longitude"];
					$new_data["begin_date"] = $settings["show_begin_date"] ? $begin_date : $new_data["begin_date"];
					$new_data["end_date"] = $settings["show_end_date"] ? $end_date : $new_data["end_date"];
					$new_data["allow_comments"] = $settings["show_allow_comments"] ? $allow_comments : $new_data["allow_comments"];
					
					$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $new_data, $data, $_POST);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if ($new_data["begin_date"] && $new_data["end_date"]) {
						$begin_time = strtotime($new_data["begin_date"]);
						$end_time = strtotime($new_data["end_date"]);
						
						if ($end_time <= $begin_time)
							$error_message = "End date must be bigger than begin date!";
					}
					
					//check if $_FILES["photo"] is an image
					if ($_FILES["photo"] && $_FILES["photo"]["tmp_name"]) {
						$mime_type = $_FILES["photo"]["type"] ? $_FILES["photo"]["type"] : MimeTypeHandler::getFileMimeType($_FILES["photo"]["tmp_name"]);
						
						if (!\MimeTypeHandler::isImageMimeType($mime_type))
							$error_message = "Upload photo must be an image!";
					} 
					
					if (!$error_message && \CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message) && $CommonModuleTableExtraAttributesUtil->areFileFieldsValid($EVC, $settings, $error_message)) {
						$new_data["object_events"] = $settings["object_to_objects"];
						
						if ($settings["allow_insertion"] && empty($data["event_id"])) {
							$status = \EventUtil::setEventProperties($EVC, null, $new_data, $_FILES["photo"]);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "event_id=$status";
							}
						}
						else if ($settings["allow_update"] && $data["event_id"]) {
							$status = \EventUtil::setEventProperties($EVC, $data["event_id"], $new_data, $_FILES["photo"]);
						}
					
						if ($status) {
							$event_id = $status;
						
							if ($_FILES["photo"]) {
								//Load again data because of the photo_url, but without changing the $data variable
								$db_data = \EventUtil::getEventProperties($EVC, $event_id, true);
								$new_data["photo_id"] = $db_data["photo_id"];
								$new_data["photo_url"] = $db_data["photo_url"];
								$photo_id = $db_data["photo_id"];
								$photo_url = $db_data["photo_url"];
							}
							else
								$photo_url = $photo_id ? $photo_url : false;
							
							$status = \AttachmentUtil::saveObjectAttachments($EVC, \ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id, \EventUtil::EVENT_ATTACHMENTS_GROUP_ID, $error_message);
						
							if ($status) {
								//save event extra
								$new_extra_data = $new_data;
								$new_extra_data["event_id"] = $event_id;
								$status = $CommonModuleTableExtraAttributesUtil->insertOrUpdateTableExtra($new_extra_data);
								$CommonModuleTableExtraAttributesUtil->reloadSavedTableExtra($settings, array("event_id" => $event_id), $data, $new_data, $_POST);
								
								if ($status) {
									//Prepare inline html images
									if ($new_data["description"] != $data["description"]) {
										$this->prepareEventHtmlAttributes($EVC, $settings, $event_id, $new_data, $status);
										$aux = $new_data;
										$aux["event_id"] = $event_id;
										if (!\EventUtil::insertOrUpdateEvent($brokers, $aux))
											$status = false;
										
										$description = $settings["show_description"] ? $new_data["description"] : $description;
									}
								
									if ($status) {
										//Add Join Point creating a new action of some kind
										$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull event saving action", array(
											"EVC" => &$EVC,
											"object_type_id" => \ObjectUtil::EVENT_OBJECT_TYPE_ID,
											"object_id" => &$event_id,
											"group_id" => \EventUtil::EVENT_ATTACHMENTS_GROUP_ID,
											"event_data" => &$new_data,
											"error_message" => &$error_message,
										));
									}
								}
							}
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"event_id" => $settings["show_event_id"] ? $event_id : $data["event_id"],
				"title" => $settings["show_title"] ? $title : $data["title"],
				"sub_title" => $settings["show_sub_title"] ? $sub_title : $data["sub_title"],
				"published" => $settings["show_published"] ? $published : $data["published"],
				"tags" => $settings["show_tags"] ? $tags : $data["tags"],
				"photo_id" => $settings["show_photo_id"] ? $photo_id : $data["photo_id"],
				"photo_url" => $photo_url,
				"description" => $settings["show_description"] ? $description : $data["description"],
				"address" => $settings["show_address"] ? $address : $data["address"],
				"zip_id" => $settings["show_zip_id"] ? $zip_id : $data["zip_id"],
				"locality" => $settings["show_locality"] ? $locality : $data["locality"],
				"country_id" => $settings["show_country_id"] ? $country_id : $data["country_id"],
				"latitude" => $settings["show_latitude"] ? $latitude : $data["latitude"],
				"longitude" => $settings["show_longitude"] ? $longitude : $data["longitude"],
				"begin_date" => $settings["show_begin_date"] ? $begin_date : $data["begin_date"],
				"end_date" => $settings["show_end_date"] ? $end_date : $data["end_date"],
				"allow_comments" => $settings["show_allow_comments"] ? $allow_comments : $data["allow_comments"],
			);
			
			$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $form_data, $data, $_POST);
			
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = $settings["allow_view"] && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/event/edit_event.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/event/edit_event.js';
		$settings["class"] = "module_edit_event";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		$settings["form_on_submit"] = "saveEvent()";
		
		$is_insertion = $settings["allow_insertion"] && !$data;
		
		$CommonModuleTableExtraAttributesUtil->prepareFileFieldsSettings($EVC, $settings);
		
		if ($settings["show_event_id"]) {
			$settings["fields"]["event_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		}
		
		if ($settings["show_published"]) {
			$settings["fields"]["published"]["field"]["input"]["type"] = "checkbox";
			$settings["fields"]["published"]["field"]["input"]["options"] = array(
				array("value" => 1)
			);
		}
		
		if ($settings["show_allow_comments"]) {
			$settings["fields"]["allow_comments"]["field"]["input"]["type"] = "checkbox";
			$settings["fields"]["allow_comments"]["field"]["input"]["options"] = array(
				array("value" => 1)
			);
		}
		
		if ($settings["show_photo_id"]) {
			$settings["fields"]["photo_id"]["field"]["input"]["type"] = "hidden";
			
			$label = $settings["fields"]["photo_id"]["field"]["label"]["value"];
			
			$previous_html = $settings["fields"]["photo_id"]["field"]["input"]["previous_html"];
			$next_html = $settings["fields"]["photo_id"]["field"]["input"]["next_html"];
			
			$settings["fields"]["photo_id"]["field"]["input"]["previous_html"] = "";
			
			$settings["fields"]["photo_id"]["field"]["input"]["next_html"] = '
			</div>
			<div class="form-group form_field photo_file">
				' . ($label ? '<label class="form-label control-label ' . $label = $settings["fields"]["photo_id"]["field"]["label"]["class"] . '">' . translateProjectText($EVC, $label) . '</label>' : '') . '
				<input type="file" class="form-control" name="photo" data-allow-null="1" data-validation-label="' . translateProjectText($EVC, \CommonModuleUI::getFieldLabel($settings, "photo_id")) . '" />';
			
			if ($photo_url) {
				$photo_url .= (strpos($photo_url, '?') !== false ? '&' : '?') . "t=" . time();
				
				$settings["fields"]["photo_id"]["field"]["input"]["next_html"] .= '
				</div>
				<div class="form_field photo_url">
					<a href="' . $photo_url . '" target="photo">
						<div class="form-group">
							<img class="form-control" src="' . $photo_url . '" onError="deletePhoto($(this).parent().closest(\'.photo_url\').find(\'.photo_remove\')[0])" alt="' . translateProjectText($EVC, "No Photo") . '" />
						</div>
					</a>
					<a class="photo_remove" onClick="deletePhoto(this)">' . translateProjectText($EVC, "Remove this photo") . '</a>';
			}
			
			$settings["fields"]["photo_id"]["field"]["input"]["next_html"] .= $next_html;
		}
		
		if ($settings["show_country_id"]) {
			include_once $EVC->getModulePath("zip/ZipUtil", $common_project_name);
			
			$countries = \ZipUtil::getAllCountries($brokers);
			$country_options = array();
			if ($countries)
				foreach ($countries as $country)
					$country_options[] = array("value" => $country["country_id"], "label" => $country["name"]);
			
			$settings["fields"]["country_id"]["field"]["input"]["type"] = "select";
			$settings["fields"]["country_id"]["field"]["input"]["options"] = $country_options;
		}
		
		if ($settings["show_begin_date"]) {
			$settings["fields"]["begin_date"]["field"]["input"]["type"] = "datetime"; //Do not add datetime-local bc in chrome the date is not shown bc does not contain 'T' in date, this is: 'yyy-mm-ddThh:ii'. The date is 'yyy-mm-dd hh:ii'.
		}
		
		if ($settings["show_end_date"]) {
			$settings["fields"]["end_date"]["field"]["input"]["type"] = "datetime"; //Do not add datetime-local bc in chrome the date is not shown bc does not contain 'T' in date, this is: 'yyy-mm-ddThh:ii'. The date is 'yyy-mm-dd hh:ii'.
		}
		
		if ($settings["show_map"]) {
			$map_settings = array(
				"style_type" => $settings["style_type"],
				"class" => $settings["fields"]["map"]["field"]["class"],
				"title" => $settings["fields"]["map"]["field"]["label"]["value"],
			);
			
			unset($settings["fields"]["map"]["field"]);
			
			$settings["fields"]["map"]["container"] = array(
				"class" => "module_edit_event_map",
				"previous_html" => $this->getMapHtml($map_settings, $project_common_url_prefix),
			);
		}
		
		if ($settings["show_event_attachments"]) {
			include_once $EVC->getModulePath("attachment/AttachmentUI", $common_project_name);
			
			$attachments_settings = array(
				"style_type" => $settings["style_type"],
				"class" => $settings["fields"]["event_attachments"]["field"]["class"],
				"title" => $settings["fields"]["event_attachments"]["field"]["label"]["value"],
			);
			
			unset($settings["fields"]["event_attachments"]["field"]);
			
			$settings["fields"]["event_attachments"]["container"] = array(
				"class" => "module_edit_event_attachments",
				"previous_html" => \AttachmentUI::getEditObjectAttachmentsHtml($EVC, $attachments_settings, \ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id, \EventUtil::EVENT_ATTACHMENTS_GROUP_ID),
			);
		}
		
		//Add join point creating new fields in the event form.
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("New Event bottom fields", array(
			"EVC" => &$EVC,
			"settings" => &$settings,
			"object_type_id" => \ObjectUtil::EVENT_OBJECT_TYPE_ID,
			"object_id" => &$event_id,
			"group_id" => \EventUtil::EVENT_ATTACHMENTS_GROUP_ID,
		));
		
		$html .= '<script type="text/javascript">
			var style_type = "' . $settings["style_type"] . '";
			
			var description_ckeditor_active_prev = description_ckeditor_active;
			var description_ckeditor_active = description_ckeditor_active ? description_ckeditor_active : false;
			var description_ckeditor_configs = description_ckeditor_configs ? description_ckeditor_configs : null;
			var description_upload_url = "' . str_replace("#event_id#", $event_id ? $event_id : 0, str_replace("#group#", \EventUtil::EVENT_DESCRIPTION_HTML_IMAGE_GROUP_ID, $settings["upload_url"])) . '";
		</script>';
		
		if (empty($settings["style_type"]))
			$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/ckeditor/ckeditor.js"></script>
			<script>
			description_ckeditor_active = typeof description_ckeditor_active_prev != "undefined" ? description_ckeditor_active_prev : true;
			</script>';
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "event/edit_event", $settings);
		$html .= \CommonModuleUI::getFormHtml($EVC, $settings);
		return $html;
	}
	
	private function prepareEventHtmlAttributes($EVC, $settings, $event_id, &$event_data, &$status = false) {
		$upload_url = str_replace("#event_id#", $event_id, str_replace("#group#", \EventUtil::EVENT_DESCRIPTION_HTML_IMAGE_GROUP_ID, $settings["upload_url"]));
		\CommonModuleUtil::prepareObjectHtmlContent($EVC, $event_data["description"], \ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id, \EventUtil::EVENT_DESCRIPTION_HTML_IMAGE_GROUP_ID, $settings["attachment_id_regex"], $upload_url, $status);
		
		return $status;
	}
	
	private function getMapHtml($map_settings, $project_common_url_prefix) {
		$label = $map_settings["label"] ? $map_settings["label"] : "Address Search";
		
		return '
		<div class="' . ($map_settings["class"] ? $map_settings["class"] : "") . '">
			<label>' . translateProjectText($EVC, $label) . ':</label>
            		<input class="map_search" type="text" placeholder="' . translateProjectText($EVC, "Write here your address...") . '">
                    	<div class="map_canvas"></div>
                	
                	<script>
                		var address_search_map_main_element = $(".module_edit_event");
                		var address_search_map_class = "' . ($map_settings["class"] ? str_replace(" ", ".", $map_settings["class"]) : "map") . '";
                	</script>
                	<script type="text/javascript" src="' . $project_common_url_prefix . 'module/event/map.js"></script>
                	<script async defer src="https://maps.googleapis.com/maps/api/js?key=' . \EventSettings::GOOGLE_MAPS_KEY . '&libraries=places&callback=initializeMapAddressSearch"></script>
                </div>';
	}
}
?>
