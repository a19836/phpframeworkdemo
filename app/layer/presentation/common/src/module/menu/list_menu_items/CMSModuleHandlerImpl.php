<?php
namespace CMSModule\menu\list_menu_items;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("menu/MenuUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting menu items
		$settings["current_page"] = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		$settings["total"] = $conditions ? \MenuUtil::countMenuItemsByConditions($brokers, $conditions, null) : \MenuUtil::countAllMenuItems($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \MenuUtil::getMenuItemsByConditions($brokers, $conditions, null, $options) : \MenuUtil::getAllMenuItems($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/menu/list_menu_items.css';
		$settings["class"] = "module_list_menu_items";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "item_id=#[idx][item_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/menu/list_menu_items/delete_menu_item?item_id=#[idx][item_id]#";
		
		if ($settings["show_group_id"]) {
			$type = $settings["fields"]["group_id"]["field"]["input"]["type"];
			$allow_options = $type == "select" || $type == "radio" || $type == "checkbox";
			
			$groups = \MenuUtil::getAllMenuGroups($brokers);
			$group_options = array();
			$available_groups = array();
			
			if ($groups) {
				$t = count($groups);
				for ($i = 0; $i < $t; $i++) {
					if ($allow_options)
						$group_options[] = array("value" => $groups[$i]["group_id"], "label" => $groups[$i]["name"]);
					else 
						$available_groups[ $groups[$i]["group_id"] ] = $groups[$i]["name"];
				}
			}
			
			$settings["fields"]["group_id"]["field"]["input"]["options"] = $group_options;
			$settings["fields"]["group_id"]["field"]["input"]["available_values"] = $available_groups;
		}
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "menu/list_menu_items", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
