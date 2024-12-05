<?php
namespace CMSModule\menu\validate_menu_object_group;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("menu/MenuUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$group_id = isset($settings["group_id"]) ? $settings["group_id"] : null;
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		
		if (isset($settings["group"]) && is_numeric($settings["group"]))
			$result = \MenuUtil::getMenuObjectGroupsByConditions($brokers, array(
				"group_id" => $group_id, 
				"object_type_id" => $object_type_id, 
				"object_id" => $object_id,
				"group" => $settings["group"],
			), null);
		else
			$result = \MenuUtil::getMenuObjectGroup($brokers, $group_id, $object_type_id, $object_id);
		
		$status = !empty($result);
		
		return \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
	}
}
?>
