<?php
namespace CMSModule\objectsgroup\list_object_objects_groups;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("objectsgroup/ObjectsGroupUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["current_page"] = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		$settings["total"] = $conditions ? \ObjectsGroupUtil::countObjectObjectsGroupsByConditions($brokers, $conditions, null) : \ObjectsGroupUtil::countAllObjectObjectsGroups($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \ObjectsGroupUtil::getObjectObjectsGroupsByConditions($brokers, $conditions, null, $options) : \ObjectsGroupUtil::getAllObjectObjectsGroups($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/objectsgroup/list_object_objects_groups.css';
		$settings["class"] = "module_list_object_objects_groups";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "objects_group_id=#[idx][objects_group_id]#&object_type_id=#[idx][object_type_id]#&object_id=#[idx][object_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/objectsgroup/list_object_objects_groups/delete_object_objects_group?objects_group_id=#[idx][objects_group_id]#&object_type_id=#[idx][object_type_id]#&object_id=#[idx][object_id]#";
		
		if ($settings["show_object_type_id"]) {
			include_once $EVC->getModulePath("object/ObjectUtil", $common_project_name);
		
			$type = $settings["fields"]["object_type_id"]["field"]["input"]["type"];
			$allow_options = $type == "select" || $type == "radio" || $type == "checkbox";
			
			$object_types = \ObjectUtil::getAllObjectTypes($brokers);
			$object_type_options = array();
			$available_object_types = array();
			
			if ($object_types) {
				$t = count($object_types);
				for ($i = 0; $i < $t; $i++) {
					if ($allow_options)
						$object_type_options[] = array("value" => $object_types[$i]["object_type_id"], "label" => $object_types[$i]["name"]);
					else 
						$available_object_types[ $object_types[$i]["object_type_id"] ] = $object_types[$i]["name"];
				}
			}
			
			$settings["fields"]["object_type_id"]["field"]["input"]["options"] = $object_type_options;
			$settings["fields"]["object_type_id"]["field"]["input"]["available_values"] = $available_object_types;
		}
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "objectsgroup/list_object_objects_groups", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
