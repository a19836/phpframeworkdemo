<?php
namespace CMSModule\object\list_object_types;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("object/ObjectUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["data"] = $conditions ? \ObjectUtil::getObjectTypesByConditions($brokers, $conditions, null) : \ObjectUtil::getAllObjectTypes($brokers);
		$settings["css_file"] = $project_common_url_prefix . 'module/object/list_object_types.css';
		$settings["class"] = "module_list_object_types";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "object_type_id=#[idx][object_type_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/object/list_object_types/delete_object_type?object_type_id=#[idx][object_type_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "object/list_object_types", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
