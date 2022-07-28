<?php
namespace CMSModule\menu\validate_menu_object_group;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("menu/MenuUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		if (is_numeric($settings["group"]))
			$result = \MenuUtil::getMenuObjectGroupsByConditions($brokers, array(
				"group_id" => $settings["group_id"], 
				"object_type_id" => $settings["object_type_id"], 
				"object_id" => $settings["object_id"],
				"group" => $settings["group"],
			), null);
		else
			$result = \MenuUtil::getMenuObjectGroup($brokers, $settings["group_id"], $settings["object_type_id"], $settings["object_id"]);
		
		$status = !empty($result);
		
		return \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
	}
}
?>
