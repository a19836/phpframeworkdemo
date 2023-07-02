<?php
namespace CMSModule\action\list_user_actions;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("action/ActionUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["current_page"] = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		$settings["total"] = $conditions ? \ActionUtil::countUserActionsByConditions($brokers, $conditions, null) : \ActionUtil::countAllUserActions($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \ActionUtil::getUserActionsByConditions($brokers, $conditions, null, $options) : \ActionUtil::getAllUserActions($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/action/list_user_actions.css';
		$settings["class"] = "module_list_user_actions";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "user_id=#[idx][user_id]#&action_id=#[idx][action_id]#&object_type_id=#[idx][object_type_id]#&object_id=#[idx][object_id]#&time=#[idx][time]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/action/list_user_actions/delete_user_action?user_id=#[idx][user_id]#&action_id=#[idx][action_id]#&object_type_id=#[idx][object_type_id]#&object_id=#[idx][object_id]#&time=#[idx][time]#";
		
		if ($settings["show_action_id"]) {
			$type = $settings["fields"]["action_id"]["field"]["input"]["type"];
			$allow_options = $type == "select" || $type == "radio" || $type == "checkbox";
			
			$actions = \ActionUtil::getAllActions($brokers);
			$action_options = array();
			$available_actions = array();
			
			if ($actions) {
				$t = count($actions);
				for ($i = 0; $i < $t; $i++) {
					if ($allow_options)
						$action_options[] = array("value" => $actions[$i]["action_id"], "label" => $actions[$i]["name"]);
					else 
						$available_actions[ $actions[$i]["action_id"] ] = $action[$i]["name"];
				}
			}
			
			$settings["fields"]["action_id"]["field"]["input"]["options"] = $action_options;
			$settings["fields"]["action_id"]["field"]["input"]["available_values"] = $available_actions;
		}
		
		if ($settings["show_user_id"]) 
			\CommonModuleUtil::prepareUserIdListSettingsField($EVC, $settings);
		
		if ($settings["show_object_type_id"])
			\CommonModuleUtil::prepareObjectTypeIdListSettingsField($EVC, $settings);
			
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "action/list_user_actions", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
