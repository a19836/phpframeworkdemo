<?php
namespace CMSModule\event\edit_event;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("event/EventSettings", $common_project_name);
		include_once $EVC->getModulePath("event/EventUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, isset($GLOBALS["default_db_driver"]) ? $GLOBALS["default_db_driver"] : null, $settings, "event");
		
		//Getting Event Details
		$event_id = isset($_GET["event_id"]) ? $_GET["event_id"] : null;
		$data = \EventUtil::getEventProperties($EVC, $event_id, true);
		$photo_url = isset($data["photo_url"]) ? $data["photo_url"] : null;
		
		//Getting Event Extra Details
		if ($data) {
			$data_extra = $CommonModuleTableExtraAttributesUtil->getTableExtra(array("event_id" => $event_id), true);
			$data = $data_extra ? array_merge($data, $data_extra) : $data;
		}
		
		//Preparing Action
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_event_id = isset($data["event_id"]) ? $data["event_id"] : null;
				
				$status = !$data || \EventUtil::deleteEvent($EVC, $data_event_id);
				
				if ($status && $data_event_id)
					$status = $CommonModuleTableExtraAttributesUtil->deleteTableExtra(array("event_id" => $data_event_id));
				
				if ($status) {
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull event deleting action", array(
						"EVC" => &$EVC,
						"event_id" => $data_event_id,
						"event_data" => &$data,
						"error_message" => &$error_message,
					));
				}
			}
			else if (!empty($_POST["save"])) {
				$title = isset($_POST["title"]) ? $_POST["title"] : null;
				$sub_title = isset($_POST["sub_title"]) ? $_POST["sub_title"] : null;
				$published = isset($_POST["published"]) ? $_POST["published"] : null;
				$tags = isset($_POST["tags"]) ? $_POST["tags"] : null;
				$photo_id = isset($_POST["photo_id"]) ? $_POST["photo_id"] : null;
				$description = isset($_POST["description"]) ? $_POST["description"] : null;
				$address = isset($_POST["address"]) ? $_POST["address"] : null;
				$zip_id = isset($_POST["zip_id"]) ? $_POST["zip_id"] : null;
				$locality = isset($_POST["locality"]) ? $_POST["locality"] : null;
				$country_id = isset($_POST["country_id"]) ? $_POST["country_id"] : null;
				$latitude = isset($_POST["latitude"]) ? $_POST["latitude"] : null;
				$longitude = isset($_POST["longitude"]) ? $_POST["longitude"] : null;
				$begin_date = isset($_POST["begin_date"]) ? $_POST["begin_date"] : null;
				$end_date = isset($_POST["end_date"]) ? $_POST["end_date"] : null;
				$allow_comments = isset($_POST["allow_comments"]) ? $_POST["allow_comments"] : null;
				
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
					$new_data["title"] = !empty($settings["show_title"]) ? $title : (isset($new_data["title"]) ? $new_data["title"] :null);
					$new_data["sub_title"] = !empty($settings["show_sub_title"]) ? $sub_title : (isset($new_data["sub_title"]) ? $new_data["sub_title"] :null);
					$new_data["published"] = !empty($settings["show_published"]) ? $published : (isset($new_data["published"]) ? $new_data["published"] :null);
					$new_data["tags"] = !empty($settings["show_tags"]) ? $tags : (isset($new_data["tags"]) ? $new_data["tags"] :null);
					$new_data["photo_id"] = !empty($settings["show_photo_id"]) ? $photo_id : (isset($new_data["photo_id"]) ? $new_data["photo_id"] :null);
					$new_data["description"] = !empty($settings["show_description"]) ? $description : (isset($new_data["description"]) ? $new_data["description"] :null);
					$new_data["address"] = !empty($settings["show_address"]) ? $address : (isset($new_data["address"]) ? $new_data["address"] :null);
					$new_data["zip_id"] = !empty($settings["show_zip_id"]) ? $zip_id : (isset($new_data["zip_id"]) ? $new_data["zip_id"] :null);
					$new_data["locality"] = !empty($settings["show_locality"]) ? $locality : (isset($new_data["locality"]) ? $new_data["locality"] :null);
					$new_data["country_id"] = !empty($settings["show_country_id"]) ? $country_id : (isset($new_data["country_id"]) ? $new_data["country_id"] :null);
					$new_data["latitude"] = !empty($settings["show_latitude"]) ? $latitude : (isset($new_data["latitude"]) ? $new_data["latitude"] :null);
					$new_data["longitude"] = !empty($settings["show_longitude"]) ? $longitude : (isset($new_data["longitude"]) ? $new_data["longitude"] :null);
					$new_data["begin_date"] = !empty($settings["show_begin_date"]) ? $begin_date : (isset($new_data["begin_date"]) ? $new_data["begin_date"] :null);
					$new_data["end_date"] = !empty($settings["show_end_date"]) ? $end_date : (isset($new_data["end_date"]) ? $new_data["end_date"] :null);
					$new_data["allow_comments"] = !empty($settings["show_allow_comments"]) ? $allow_comments : (isset($new_data["allow_comments"]) ? $new_data["allow_comments"] :null);
					
					$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $new_data, $data, $_POST);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (!empty($new_data["begin_date"]) && !empty($new_data["end_date"])) {
						$begin_time = strtotime($new_data["begin_date"]);
						$end_time = strtotime($new_data["end_date"]);
						
						if ($end_time <= $begin_time)
							$error_message = "End date must be bigger than begin date!";
					}
					
					//check if $_FILES["photo"] is an image
					if (!empty($_FILES["photo"]) && !empty($_FILES["photo"]["tmp_name"])) {
						$mime_type = !empty($_FILES["photo"]["type"]) ? $_FILES["photo"]["type"] : \MimeTypeHandler::getFileMimeType($_FILES["photo"]["tmp_name"]);
						
						if (!\MimeTypeHandler::isImageMimeType($mime_type))
							$error_message = "Upload photo must be an image!";
					} 
					
					if (!$error_message && \CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message) && $CommonModuleTableExtraAttributesUtil->areFileFieldsValid($EVC, $settings, $error_message)) {
						$new_data["object_events"] = isset($settings["object_to_objects"]) ? $settings["object_to_objects"] : null;
						
						if (!empty($settings["allow_insertion"]) && empty($data["event_id"])) {
							$status = \EventUtil::setEventProperties($EVC, null, $new_data, isset($_FILES["photo"]) ? $_FILES["photo"] : null);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "event_id=$status";
							}
						}
						else if (!empty($settings["allow_update"]) && !empty($data["event_id"])) {
							$status = \EventUtil::setEventProperties($EVC, $data["event_id"], $new_data, isset($_FILES["photo"]) ? $_FILES["photo"] : null);
						}
					
						if (!empty($status)) {
							$event_id = $status;
						
							if (!empty($_FILES["photo"])) {
								//Load again data because of the photo_url, but without changing the $data variable
								$db_data = \EventUtil::getEventProperties($EVC, $event_id, true);
								$photo_id = $new_data["photo_id"] = isset($db_data["photo_id"]) ? $db_data["photo_id"] : null;
								$photo_url = $new_data["photo_url"] = isset($db_data["photo_url"]) ? $db_data["photo_url"] : null;
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
									$data_description = isset($data["description"]) ? $data["description"] : null;
									
									if ($new_data["description"] != $data_description) {
										$this->prepareEventHtmlAttributes($EVC, $settings, $event_id, $new_data, $status);
										$aux = $new_data;
										$aux["event_id"] = $event_id;
										if (!\EventUtil::insertOrUpdateEvent($brokers, $aux))
											$status = false;
										
										$description = !empty($settings["show_description"]) ? $new_data["description"] : $description;
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
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"event_id" => !empty($settings["show_event_id"]) ? $event_id : (isset($data["event_id"]) ? $data["event_id"] : null),
				"title" => !empty($settings["show_title"]) ? $title : (isset($data["title"]) ? $data["title"] : null),
				"sub_title" => !empty($settings["show_sub_title"]) ? $sub_title : (isset($data["sub_title"]) ? $data["sub_title"] : null),
				"published" => !empty($settings["show_published"]) ? $published : (isset($data["published"]) ? $data["published"] : null),
				"tags" => !empty($settings["show_tags"]) ? $tags : (isset($data["tags"]) ? $data["tags"] : null),
				"photo_id" => !empty($settings["show_photo_id"]) ? $photo_id : (isset($data["photo_id"]) ? $data["photo_id"] : null),
				"photo_url" => $photo_url,
				"description" => !empty($settings["show_description"]) ? $description : (isset($data["description"]) ? $data["description"] : null),
				"address" => !empty($settings["show_address"]) ? $address : (isset($data["address"]) ? $data["address"] : null),
				"zip_id" => !empty($settings["show_zip_id"]) ? $zip_id : (isset($data["zip_id"]) ? $data["zip_id"] : null),
				"locality" => !empty($settings["show_locality"]) ? $locality : (isset($data["locality"]) ? $data["locality"] : null),
				"country_id" => !empty($settings["show_country_id"]) ? $country_id : (isset($data["country_id"]) ? $data["country_id"] : null),
				"latitude" => !empty($settings["show_latitude"]) ? $latitude : (isset($data["latitude"]) ? $data["latitude"] : null),
				"longitude" => !empty($settings["show_longitude"]) ? $longitude : (isset($data["longitude"]) ? $data["longitude"] : null),
				"begin_date" => !empty($settings["show_begin_date"]) ? $begin_date : (isset($data["begin_date"]) ? $data["begin_date"] : null),
				"end_date" => !empty($settings["show_end_date"]) ? $end_date : (isset($data["end_date"]) ? $data["end_date"] : null),
				"allow_comments" => !empty($settings["show_allow_comments"]) ? $allow_comments : (isset($data["allow_comments"]) ? $data["allow_comments"] : null),
			);
			
			$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $form_data, $data, $_POST);
			
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/event/edit_event.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/event/edit_event.js';
		$settings["class"] = "module_edit_event";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		$settings["form_on_submit"] = "saveEvent()";
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data;
		
		$CommonModuleTableExtraAttributesUtil->prepareFileFieldsSettings($EVC, $settings);
		
		if (!empty($settings["show_event_id"])) {
			$settings["fields"]["event_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		}
		
		if (!empty($settings["show_published"])) {
			$settings["fields"]["published"]["field"]["input"]["type"] = "checkbox";
			$settings["fields"]["published"]["field"]["input"]["options"] = array(
				array("value" => 1)
			);
		}
		
		if (!empty($settings["show_allow_comments"])) {
			$settings["fields"]["allow_comments"]["field"]["input"]["type"] = "checkbox";
			$settings["fields"]["allow_comments"]["field"]["input"]["options"] = array(
				array("value" => 1)
			);
		}
		
		if (!empty($settings["show_photo_id"])) {
			$settings["fields"]["photo_id"]["field"]["input"]["type"] = "hidden";
			
			$label = isset($settings["fields"]["photo_id"]["field"]["label"]["value"]) ? $settings["fields"]["photo_id"]["field"]["label"]["value"] : null;
			$class = isset($settings["fields"]["photo_id"]["field"]["label"]["class"]) ? $settings["fields"]["photo_id"]["field"]["label"]["class"] : null;
			$previous_html = isset($settings["fields"]["photo_id"]["field"]["input"]["previous_html"]) ? $settings["fields"]["photo_id"]["field"]["input"]["previous_html"] : null;
			$next_html = isset($settings["fields"]["photo_id"]["field"]["input"]["next_html"]) ? $settings["fields"]["photo_id"]["field"]["input"]["next_html"] : null;
			
			$settings["fields"]["photo_id"]["field"]["input"]["previous_html"] = "";
			
			$settings["fields"]["photo_id"]["field"]["input"]["next_html"] = '
			</div>
			<div class="form-group form_field photo_file">
				' . ($label ? '<label class="form-label control-label ' . $class . '">' . translateProjectText($EVC, $label) . '</label>' : '') . '
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
		
		if (!empty($settings["show_country_id"])) {
			include_once $EVC->getModulePath("zip/ZipUtil", $common_project_name);
			
			$countries = \ZipUtil::getAllCountries($brokers);
			$country_options = array();
			if ($countries)
				foreach ($countries as $country) {
					$av_country_id = isset($country["country_id"]) ? $country["country_id"] : null;
					$av_country_name = isset($country["name"]) ? $country["name"] : null;
					
					$country_options[] = array("value" => $av_country_id, "label" => $av_country_name);
				}
			
			$settings["fields"]["country_id"]["field"]["input"]["type"] = "select";
			$settings["fields"]["country_id"]["field"]["input"]["options"] = $country_options;
		}
		
		if (!empty($settings["show_begin_date"])) {
			$settings["fields"]["begin_date"]["field"]["input"]["type"] = "datetime"; //Do not add datetime-local bc in chrome the date is not shown bc does not contain 'T' in date, this is: 'yyy-mm-ddThh:ii'. The date is 'yyy-mm-dd hh:ii'.
		}
		
		if (!empty($settings["show_end_date"])) {
			$settings["fields"]["end_date"]["field"]["input"]["type"] = "datetime"; //Do not add datetime-local bc in chrome the date is not shown bc does not contain 'T' in date, this is: 'yyy-mm-ddThh:ii'. The date is 'yyy-mm-dd hh:ii'.
		}
		
		if (!empty($settings["show_map"])) {
			$map_settings = array(
				"style_type" => isset($settings["style_type"]) ? $settings["style_type"] : null,
				"class" => isset($settings["fields"]["map"]["field"]["class"]) ? $settings["fields"]["map"]["field"]["class"] : null,
				"title" => isset($settings["fields"]["map"]["field"]["label"]["value"]) ? $settings["fields"]["map"]["field"]["label"]["value"] : null,
			);
			
			unset($settings["fields"]["map"]["field"]);
			
			$settings["fields"]["map"]["container"] = array(
				"class" => "module_edit_event_map",
				"previous_html" => $this->getMapHtml($EVC, $map_settings, $project_common_url_prefix),
			);
		}
		
		if (!empty($settings["show_event_attachments"])) {
			include_once $EVC->getModulePath("attachment/AttachmentUI", $common_project_name);
			
			$attachments_settings = array(
				"style_type" => isset($settings["style_type"]) ? $settings["style_type"] : null,
				"class" => isset($settings["fields"]["event_attachments"]["field"]["class"]) ? $settings["fields"]["event_attachments"]["field"]["class"] : null,
				"title" => isset($settings["fields"]["event_attachments"]["field"]["label"]["value"]) ? $settings["fields"]["event_attachments"]["field"]["label"]["value"] : null,
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
		
		$style_type = isset($settings["style_type"]) ? $settings["style_type"] : null;
		$upload_url = isset($upload_url) ? str_replace("#event_id#", $event_id ? $event_id : 0, str_replace("#group#", \EventUtil::EVENT_DESCRIPTION_HTML_IMAGE_GROUP_ID, $settings["upload_url"])) : null;
		
		$html = '<script type="text/javascript">
			var style_type = "' . $style_type . '";
			
			var description_ckeditor_active_prev = description_ckeditor_active;
			var description_ckeditor_active = description_ckeditor_active ? description_ckeditor_active : false;
			var description_ckeditor_configs = description_ckeditor_configs ? description_ckeditor_configs : null;
			var description_upload_url = "' . $upload_url . '";
		</script>';
		
		$exists_ckeditor = file_exists($EVC->getWebrootPath($common_project_name) . "vendor/ckeditor/ckeditor.js");
		
		if (!$style_type && $exists_ckeditor)
			$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/ckeditor/ckeditor.js"></script>
			<script>
			description_ckeditor_active = typeof description_ckeditor_active_prev != "undefined" ? description_ckeditor_active_prev : true;
			</script>';
		
		if (!$exists_ckeditor) //be sure that ckeditor is inactive
			$html .= '<script>
			description_ckeditor_active = false;
			</script>';
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "event/edit_event", $settings);
		$html .= \CommonModuleUI::getFormHtml($EVC, $settings);
		return $html;
	}
	
	private function prepareEventHtmlAttributes($EVC, $settings, $event_id, &$event_data, &$status = false) {
		$upload_url = isset($settings["upload_url"]) ? str_replace("#event_id#", $event_id, str_replace("#group#", \EventUtil::EVENT_DESCRIPTION_HTML_IMAGE_GROUP_ID, $settings["upload_url"])) : null;
		$description = isset($event_data["description"]) ? $event_data["description"] : null;
		$regex = isset($settings["attachment_id_regex"]) ? $settings["attachment_id_regex"] : null;
		
		\CommonModuleUtil::prepareObjectHtmlContent($EVC, $description, \ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id, \EventUtil::EVENT_DESCRIPTION_HTML_IMAGE_GROUP_ID, $regex, $upload_url, $status);
		
		return $status;
	}
	
	private function getMapHtml($EVC, $map_settings, $project_common_url_prefix) {
		$label = !empty($map_settings["label"]) ? $map_settings["label"] : "Address Search";
		
		return '
		<div class="' . (!empty($map_settings["class"]) ? $map_settings["class"] : "") . '">
			<label>' . translateProjectText($EVC, $label) . ':</label>
            		<input class="map_search" type="text" placeholder="' . translateProjectText($EVC, "Write here your address...") . '">
                    	<div class="map_canvas"></div>
                	
                	<script>
                		var address_search_map_main_element = $(".module_edit_event");
                		var address_search_map_class = "' . (!empty($map_settings["class"]) ? str_replace(" ", ".", $map_settings["class"]) : "map") . '";
                	</script>
                	<script type="text/javascript" src="' . $project_common_url_prefix . 'module/event/map.js"></script>
                	<script async defer src="https://maps.googleapis.com/maps/api/js?key=' . \EventSettings::GOOGLE_MAPS_KEY . '&libraries=places&callback=initializeMapAddressSearch"></script>
                </div>';
	}
}
?>
