<?php
namespace CMSModule\user\list_users;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, isset($GLOBALS["default_db_driver"]) ? $GLOBALS["default_db_driver"] : null, $settings, "user");
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["current_page"] = isset($_GET["current_page"]) && is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		
		$query_type = isset($settings["query_type"]) ? $settings["query_type"] : null;
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		$group = isset($settings["group"]) ? $settings["group"] : null;
		
		switch ($query_type) {
			case "user_by_user_type": 
				if (!empty($settings["user_type_id"])) {
					$settings["total"] = \UserUtil::countUsersByUserTypesAndConditions($brokers, array($settings["user_type_id"]), $conditions, null);
					$settings["data"] = \UserUtil::getUsersByUserTypesAndConditions($brokers, array($settings["user_type_id"]), $conditions, null, $options);
				}
				break;
			case "parent": 
				$settings["total"] = \UserUtil::countUsersByObjectAndConditions($brokers, $object_type_id, $object_id, $conditions, null);
				$settings["data"] = \UserUtil::getUsersByObjectAndConditions($brokers, $object_type_id, $object_id, $conditions, null, $options);
				break;
			case "parent_group": 
				$settings["total"] = \UserUtil::countUsersByObjectGroupAndConditions($brokers, $object_type_id, $object_id, $group, $conditions, null);
				$settings["data"] = \UserUtil::getUsersByObjectGroupAndConditions($brokers, $object_type_id, $object_id, $group, $conditions, null, $options);
				break;
			case "parent_and_user_type": 
				if (!empty($settings["user_type_id"])) {
					$settings["total"] = \UserUtil::countUsersByObjectAndUserTypesAndConditions($brokers, $object_type_id, $object_id, array($settings["user_type_id"]), $conditions, null);
					$settings["data"] = \UserUtil::getUsersByObjectAndUserTypesAndConditions($brokers, $object_type_id, $object_id, array($settings["user_type_id"]), $conditions, null, $options);
				}
				break;
			case "parent_group_and_user_type": 
				if (!empty($settings["user_type_id"])) {
					$settings["total"] = \UserUtil::countUsersByObjectGroupAndUserTypesAndConditions($brokers, $object_type_id, $object_id, $group, array($settings["user_type_id"]), $conditions, null);
					$settings["data"] = \UserUtil::getUsersByObjectGroupAndUserTypesAndConditions($brokers, $object_type_id, $object_id, $group, array($settings["user_type_id"]), $conditions, null, $options);
				}
				break;
			default:
				$settings["total"] = $conditions ? \UserUtil::countUsersByConditions($brokers, $conditions, null) : \UserUtil::countAllUsers($brokers);
				$settings["data"] = $conditions ? \UserUtil::getUsersByConditions($brokers, $conditions, null, $options) : \UserUtil::getAllUsers($brokers, $options);
		}
		
		$settings["data"] = isset($settings["data"]) ? $settings["data"] : null;
		
		//Getting Users Extra Details
		$CommonModuleTableExtraAttributesUtil->prepareItemsWithTableExtra($settings["data"], "user_id");
		
		$settings["css_file"] = $project_common_url_prefix . 'module/user/list_users.css';
		$settings["class"] = "module_list_users";
		$settings["edit_page_url"] .= (isset($settings["edit_page_url"]) && strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "user_id=#[idx][user_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/user/list_users/delete_user?user_id=#[idx][user_id]#";
				
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/list_users", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
