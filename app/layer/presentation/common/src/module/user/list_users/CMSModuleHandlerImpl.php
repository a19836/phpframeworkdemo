<?php
namespace CMSModule\user\list_users;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, $GLOBALS["default_db_driver"], $settings, "user");
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["current_page"] = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		
		switch ($settings["query_type"]) {
			case "user_by_user_type": 
				if ($settings["user_type_id"]) {
					$settings["total"] = \UserUtil::countUsersByUserTypesAndConditions($brokers, array($settings["user_type_id"]), $conditions, null);
					$settings["data"] = \UserUtil::getUsersByUserTypesAndConditions($brokers, array($settings["user_type_id"]), $conditions, null, $options);
				}
				break;
			case "parent": 
				$settings["total"] = \UserUtil::countUsersByObjectAndConditions($brokers, $settings["object_type_id"], $settings["object_id"], $conditions, null);
				$settings["data"] = \UserUtil::getUsersByObjectAndConditions($brokers, $settings["object_type_id"], $settings["object_id"], $conditions, null, $options);
				break;
			case "parent_group": 
				$settings["total"] = \UserUtil::countUsersByObjectGroupAndConditions($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $conditions, null);
				$settings["data"] = \UserUtil::getUsersByObjectGroupAndConditions($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $conditions, null, $options);
				break;
			case "parent_and_user_type": 
				if ($settings["user_type_id"]) {
					$settings["total"] = \UserUtil::countUsersByObjectAndUserTypesAndConditions($brokers, $settings["object_type_id"], $settings["object_id"], array($settings["user_type_id"]), $conditions, null);
					$settings["data"] = \UserUtil::getUsersByObjectAndUserTypesAndConditions($brokers, $settings["object_type_id"], $settings["object_id"], array($settings["user_type_id"]), $conditions, null, $options);
				}
				break;
			case "parent_group_and_user_type": 
				if ($settings["user_type_id"]) {
					$settings["total"] = \UserUtil::countUsersByObjectGroupAndUserTypesAndConditions($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], array($settings["user_type_id"]), $conditions, null);
					$settings["data"] = \UserUtil::getUsersByObjectGroupAndUserTypesAndConditions($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], array($settings["user_type_id"]), $conditions, null, $options);
				}
				break;
			default:
				$settings["total"] = $conditions ? \UserUtil::countUsersByConditions($brokers, $conditions, null) : \UserUtil::countAllUsers($brokers);
				$settings["data"] = $conditions ? \UserUtil::getUsersByConditions($brokers, $conditions, null, $options) : \UserUtil::getAllUsers($brokers, $options);
		}
		
		//Getting Users Extra Details
		$CommonModuleTableExtraAttributesUtil->prepareItemsWithTableExtra($settings["data"], "user_id");
		
		$settings["css_file"] = $project_common_url_prefix . 'module/user/list_users.css';
		$settings["class"] = "module_list_users";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "user_id=#[idx][user_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/user/list_users/delete_user?user_id=#[idx][user_id]#";
				
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/list_users", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
