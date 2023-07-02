<?php
namespace CMSModule\objectsgroup\show_objects_group;

include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("objectsgroup/ObjectsGroupUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$form_settings = $settings["form_settings"];
		$action_settings = $settings["action_settings"];
		$action_buttons = $action_settings["action_buttons"];
		
		if ($form_settings) {
			$form_settings["with_form"] = true;
			$form_settings["form_method"] = "post";
		}
		
		//Preparing Actions
		if ($_POST && is_array($action_buttons["action_type"])) {
			foreach ($action_buttons["action_type"] as $idx => $action_type) {
				$action_variable = $action_buttons["action_variable"][$idx];
				
				$object_objects_groups = $settings["action_settings"]["object_to_objects"];
				
				switch($action_type) {
					case "insert_objects_group"://$action_variable must be an array with the properties of an objects_group object
						if (\ObjectsGroupUtil::checkIfSingleFileFieldsAreValid($_FILES)) {
							$files = \ObjectsGroupUtil::getSingleFiles($_FILES);
							$action_variable["object_objects_groups"] = $object_objects_groups;
							$status = \ObjectsGroupUtil::insertObjectsGroup($EVC, $action_variable, $files, $brokers);
					
							if ($status) {
								$status_message = "Objects Group inserted successfully.";
							
								//Add Join Point creating a new action of some kind
								$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull objects group inserting action", array(
									"EVC" => &$EVC,
									"object_type_id" => \ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID,
									"objects_group_id" => $status,
									"action_variable" => &$action_variable,
									"object_objects_groups" => &$object_objects_groups,
									"files" => &$files,
									"error_message" => &$error_message,
								));
							}
							else
								$error_message = "There was an error trying to insert this objects group. Please try again...";
						}
						else {
							$error_message = "\$_FILES contains an illegal variable name. Please check the Admin Settings.";
						}
						break;
					case "insert_objects_groups"://$action_variable must be an array where each item contains the properties for each objects_group object
						if (\ObjectsGroupUtil::checkIfMultipleFileFieldsAreValid($_FILES)) {
							$files = \ObjectsGroupUtil::getMultipleFiles($_FILES);
							$objects_groups_id = array();
							
							if ($action_variable) {
								$status = true;
								$t = count($action_variable);
								for ($i = 0; $i < $t; $i++) {
									$av = $action_variable[$i];
									$av["object_objects_groups"] = $object_objects_groups;
									
									$objects_group_id = \ObjectsGroupUtil::insertObjectsGroup($EVC, $av, $files[$i], $brokers);
									
									if ($objects_group_id)
										$objects_groups_id[] = $objects_group_id;
									else
										$status = false;
								}
							}
					
							if ($status) {
								$status_message = "Objects Groups were inserted successfully.";
							
								//Add Join Point creating a new action of some kind
								$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull objects groups inserting action", array(
									"EVC" => &$EVC,
									"object_type_id" => \ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID,
									"objects_groups_id" => $objects_groups_id,
									"action_variable" => &$action_variable,
									"object_objects_groups" => &$object_objects_groups,
									"files" => &$files,
									"error_message" => &$error_message,
								));
							}
							else
								$error_message = "There was an error trying to insert these objects groups. Please try again...";
						}
						else {
							$error_message = "\$_FILES contains an illegal variable name. Please check the Admin Settings.";
						}
						break;
					case "update_objects_group"://$action_variable must be an array with the properties of an objects_group object
						if (\ObjectsGroupUtil::checkIfSingleFileFieldsAreValid($_FILES)) {
							$files = \ObjectsGroupUtil::getSingleFiles($_FILES);
							$action_variable["object_objects_groups"] = $object_objects_groups;
							$status = \ObjectsGroupUtil::updateObjectsGroup($EVC, $action_variable, $files, $brokers);
							
							if ($status) {
								$status_message = "Objects Group updated successfully.";
							
								//Add Join Point creating a new action of some kind
								$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull objects group updating action", array(
									"EVC" => &$EVC,
									"object_type_id" => \ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID,
									"objects_group_id" => $action_variable["objects_group_id"],
									"action_variable" => &$action_variable,
									"object_objects_groups" => &$object_objects_groups,
									"files" => &$files,
									"error_message" => &$error_message,
								));
							}
							else
								$error_message = "There was an error trying to update this objects group. Please try again...";
						}
						else {
							$error_message = "\$_FILES contains an illegal variable name. Please check the Admin Settings.";
						}
						break;
					case "update_objects_groups"://$action_variable must be an array where each item contains the properties for each objects_group object
						if (\ObjectsGroupUtil::checkIfMultipleFileFieldsAreValid($_FILES)) {
							$files = \ObjectsGroupUtil::getMultipleFiles($_FILES);
							$objects_groups_id = array();
							
							if ($action_variable) {
								$status = true;
								$t = count($action_variable);
								for ($i = 0; $i < $t; $i++) {
									$av = $action_variable[$i];
									$av["object_objects_groups"] = $object_objects_groups;
									$objects_groups_id[] = $av["objects_group_id"];
									
									if (!\ObjectsGroupUtil::updateObjectsGroup($EVC, $av, $files[$i], $brokers))
										$status = false;
								}
							}
					
							if ($status) {
								$status_message = "Objects Groups were updated successfully.";
							
								//Add Join Point creating a new action of some kind
								$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull objects groups updating action", array(
									"EVC" => &$EVC,
									"object_type_id" => \ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID,
									"objects_groups_id" => $objects_groups_id,
									"action_variable" => &$action_variable,
									"object_objects_groups" => &$object_objects_groups,
									"files" => &$files,
									"error_message" => &$error_message,
								));
							}
							else
								$error_message = "There was an error trying to update these objects groups. Please try again...";
						}
						else {
							$error_message = "\$_FILES contains an illegal variable name. Please check the Admin Settings.";
						}
						break;
					case "save_objects_group"://$action_variable must be an array with the properties of an objects_group object
						if (\ObjectsGroupUtil::checkIfSingleFileFieldsAreValid($_FILES)) {
							$files = \ObjectsGroupUtil::getSingleFiles($_FILES);
							$files = \ObjectsGroupUtil::checkIfSingleFileFieldsAreValid($_FILES) ? \ObjectsGroupUtil::getSingleFiles($_FILES) : null;
							$action_variable["object_objects_groups"] = $object_objects_groups;
							
							if ($action_variable["objects_group_id"]) {
								$status = \ObjectsGroupUtil::updateObjectsGroup($EVC, $action_variable, $files, $brokers);
								$status = $status ? $action_variable["objects_group_id"] : false;
							}
							else {
								$status = \ObjectsGroupUtil::insertObjectsGroup($EVC, $action_variable, $files, $brokers);
							}
					
							if ($status) {
								$status_message = "Objects Group saved successfully.";
							
								//Add Join Point creating a new action of some kind
								$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull objects group saving action", array(
									"EVC" => &$EVC,
									"object_type_id" => \ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID,
									"objects_group_id" => $status,
									"action_variable" => &$action_variable,
									"object_objects_groups" => &$object_objects_groups,
									"files" => &$files,
									"error_message" => &$error_message,
								));
							}
							else
								$error_message = "There was an error trying to save this objects group. Please try again...";
						}
						else {
							$error_message = "\$_FILES contains an illegal variable name. Please check the Admin Settings.";
						}
						break;
					case "save_objects_groups"://$action_variable must be an array where each item contains the properties for each objects_group object
						if (\ObjectsGroupUtil::checkIfMultipleFileFieldsAreValid($_FILES)) {
							$files = \ObjectsGroupUtil::getMultipleFiles($_FILES);
							$objects_groups_id = array();
							
							if ($action_variable) {
								$status = true;
								$t = count($action_variable);
								for ($i = 0; $i < $t; $i++) {
									$av = $action_variable[$i];
									$av["object_objects_groups"] = $object_objects_groups;
									
									if ($av["objects_group_id"]) {
										$objects_groups_id[] = $av["objects_group_id"];
										
										if (!\ObjectsGroupUtil::updateObjectsGroup($EVC, $av, $files[$i], $brokers))
											$status = false;
									}
									else {
										$objects_group_id = \ObjectsGroupUtil::insertObjectsGroup($EVC, $av, $files[$i], $brokers);
										
										if ($objects_group_id)
											$objects_groups_id[] = $objects_group_id;
										else
											$status = false;
									}
								}
							}
							
							if ($status) {
								$status_message = "Objects Groups were saved successfully.";
							
								//Add Join Point creating a new action of some kind
								$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull objects groups saving action", array(
									"EVC" => &$EVC,
									"object_type_id" => \ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID,
									"objects_groups_id" => $objects_groups_id,
									"action_variable" => &$action_variable,
									"object_objects_groups" => &$object_objects_groups,
									"files" => &$files,
									"error_message" => &$error_message,
								));
							}
							else
								$error_message = "There was an error trying to save these objects groups. Please try again...";
						}
						else {
							$error_message = "\$_FILES contains an illegal variable name. Please check the Admin Settings.";
						}
						break;
					case "delete_objects_group"://$action_variable must be a numeric value
						$status = \ObjectsGroupUtil::deleteObjectsGroup($EVC, $action_variable, $brokers);
					
						if ($status) {
							$status_message = "Objects Group deleted successfully.";
							
							//Add Join Point creating a new action of some kind
							$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull objects group deleting action", array(
								"EVC" => &$EVC,
								"object_type_id" => \ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID,
								"objects_group_id" => $action_variable,
								"error_message" => &$error_message,
							));
						}
						else
							$error_message = "There was an error trying to delete this objects group. Please try again...";
						break;
					case "delete_objects_groups"://$action_variable must be an aray of numeric values
						if ($action_variable) {
							$status = true;
							$t = count($action_variable);
							for ($i = 0; $i < $t; $i++)
								if (!\ObjectsGroupUtil::deleteObjectsGroup($EVC, $action_variable[$i], $brokers))
									$status = false;
						}
					
						if ($status) {
							$status_message = "Objects Groups were deleted successfully.";
							
							//Add Join Point creating a new action of some kind
							$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull objects groups deleting action", array(
								"EVC" => &$EVC,
								"object_type_id" => \ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID,
								"objects_groups_id" => $action_variable,
								"error_message" => &$error_message,
							));
						}
						else
							$error_message = "There was an error trying to delete these objects groups. Please try again...";
						break;
				}
			}
		}
		
		//Preparing options
		$rows_per_page = $action_settings["rows_per_page"] > 0 ? $action_settings["rows_per_page"] : null;
		$options = array("limit" => $rows_per_page, "sort" => array());
		
		//Preparing pagination
		if ($action_settings["top_pagination_type"] || $action_settings["bottom_pagination_type"]) {
			$current_page = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : 0;
			$rows_per_page = $rows_per_page > 0 ? $rows_per_page : 50;
			$options["start"] = \PaginationHandler::getStartValue($current_page, $rows_per_page);
		}
		
		//Getting objects_groups
		switch ($action_settings["objects_groups_type"]) {
			case "all":
				$total = \ObjectsGroupUtil::countAllObjectsGroups($EVC, $brokers);
				$objects_groups = \ObjectsGroupUtil::getAllObjectsGroups($EVC, $brokers, $options);
				break;
			case "tags_and":
				$tags = $action_settings["tags"];
				if ($tags) {
					$total = \ObjectsGroupUtil::countObjectsGroupsWithAllTags($EVC, $tags, $brokers);
					$objects_groups = \ObjectsGroupUtil::getObjectsGroupsWithAllTags($EVC, $tags, $brokers, $options);
				}
				break;
			case "tags_or":
				$tags = $action_settings["tags"];
				if ($tags) {
					$total = \ObjectsGroupUtil::countObjectsGroupsByTags($EVC, $tags, $brokers);
					$objects_groups = \ObjectsGroupUtil::getObjectsGroupsByTags($EVC, $tags, $brokers, $options);
				}
				break;
			case "parent":
				$total = \ObjectsGroupUtil::countObjectsGroupsByObject($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $brokers);
				$objects_groups = \ObjectsGroupUtil::getObjectsGroupsByObject($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $brokers, $options);
				break;
			case "parent_group":
				$total = \ObjectsGroupUtil::countObjectsGroupsByObjectGroup($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $action_settings["group"], $brokers);
				$objects_groups = \ObjectsGroupUtil::getObjectsGroupsByObjectGroup($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $action_settings["group"], $brokers, $options);
				break;
			case "parent_tags_and":
				$tags = $action_settings["tags"];
				if ($tags) {
					$total = \ObjectsGroupUtil::countObjectsGroupsByObjectWithAllTags($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $tags, $brokers);
					$objects_groups = \ObjectsGroupUtil::getObjectsGroupsByObjectWithAllTags($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $tags, $brokers, $options);
				}
				break;
			case "parent_tags_or":
				$tags = $action_settings["tags"];
				if ($tags) {
					$total = \ObjectsGroupUtil::countObjectsGroupsByObjectAndTags($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $tags, $brokers);
					$objects_groups = \ObjectsGroupUtil::getObjectsGroupsByObjectAndTags($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $tags, $brokers, $options);
				}
				break;
			case "parent_group_tags_and":
				$tags = $action_settings["tags"];
				if ($tags) {
					$total = \ObjectsGroupUtil::countObjectsGroupsByObjectGroupWithAllTags($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $action_settings["group"], $tags, $brokers);
					$objects_groups = \ObjectsGroupUtil::getObjectsGroupsByObjectGroupWithAllTags($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $action_settings["group"], $tags, $brokers, $options);
				}
				break;
			case "parent_group_tags_or":
				$tags = $action_settings["tags"];
				if ($tags) {
					$total = \ObjectsGroupUtil::countObjectsGroupsByObjectGroupAndTags($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $action_settings["group"], $tags, $brokers);
					$objects_groups = \ObjectsGroupUtil::getObjectsGroupsByObjectGroupAndTags($EVC, $action_settings["object_type_id"], $action_settings["object_id"], $action_settings["group"], $tags, $brokers, $options);
				}
				break;
			case "selected":
				$objects_group_ids = $action_settings["objects_group_ids"];
				if ($objects_group_ids) {
					$total = count($objects_group_ids);
					$items = \ObjectsGroupUtil::getObjectsGroupsByIds($EVC, $objects_group_ids, $brokers, $options);
					
					//preparing ids order:
					$objects_groups = array();
					if (is_array($items) && !empty($items)) {
						$t = count($objects_group_ids);
						for ($i = 0; $i < $t; $i++) {
							foreach ($items as $item) {
								if ($item["objects_group_id"] == $objects_group_ids[$i]) {
									$objects_groups[] = $item;
									break;
								}
							}
						}
					}
				}
				break;
			case "specific":
				$objects_group_id = $action_settings["objects_group_id"];
				if ($objects_group_id) {
					$total = 1;
					$objects_groups = \ObjectsGroupUtil::getObjectsGroupProperties($EVC, $objects_group_id, $brokers);
				}
				break;
		}
		
		//Preparing Buttons
		if ($form_settings && $action_buttons["button_label"]) {
			$buttons = array();
			
			$t = count($action_buttons["button_label"]);
			for ($i = 0; $i < $t; $i++) {
				$button_label = $action_buttons["button_label"][$i];
				
				$buttons[] = array(
					"field" => array(
						"class" => "submit_button submit_button_" . str_replace(array(" ", "-"), "_", strtolower($button_label)),
						"input" => array(
							"type" => "submit",
							"name" => $button_label,
							"value" => translateProjectText($EVC, $button_label),
						)
					)
				);	
			}
			
			$form_settings["form_containers"][]["container"] = array(
				"class" => "buttons",
				"elements" => $buttons
			);
		}
		
		$objects_groups = $objects_groups ? $objects_groups : array();
		
		$HtmlFormHandler = new \HtmlFormHandler($form_settings);
		$class = $HtmlFormHandler->getParsedValueFromData($form_settings["form_containers"][0]["container"]["class"], $objects_groups);
		$form_settings["form_containers"][0]["container"]["class"] = "";
		
		//Getting Html
		$html = '<div class="module_show_objects_group ' . $class . ' ' . ($settings["block_class"]) . '">';
		
		$html .= \CommonModuleUI::getModuleMessagesHtml($EVC, $status_message, $error_message);
		
		$is_delete_ok = $action_buttons["action_type"]["delete_objects_group"] && $status && !$error_message;
		
		//if delete specific element was executed successfully, only show message and don't show form.
		if (!$is_delete_ok) {
			//Preparing pagination
			if ($action_settings["top_pagination_type"] || $action_settings["bottom_pagination_type"]) {
				$PaginationLayout = new \PaginationLayout($total, $rows_per_page, array("current_page" => $current_page), "current_page");
				$PaginationLayout->show_x_pages_at_once = 10;
				$pagination_data = $PaginationLayout->data;
			}
		
			//showing top pagination
			if ($action_settings["top_pagination_type"]) {
				$pagination_data["style"] = $action_settings["top_pagination_type"];
			
				$html .= '<div class="top_pagination pagination_alignment_' . $action_settings["top_pagination_alignment"] . '">' . $PaginationLayout->designWithStyle(1, $pagination_data) . '</div>';
			}
			
			translateProjectFormSettings($EVC, $form_settings);
			
			$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
		
			$html .= \HtmlFormHandler::createHtmlForm($form_settings, $objects_groups);
		
			if ($action_settings["bottom_pagination_type"]) {
				$pagination_data["style"] = $action_settings["bottom_pagination_type"];
			
				$html .= '<div class="bottom_pagination pagination_alignment_' . $action_settings["bottom_pagination_alignment"] . '">' . $PaginationLayout->designWithStyle(1, $pagination_data) . '</div>';
			}
		}
		
		$html .= '</div>';
		
		return $html;
	}
}
?>
