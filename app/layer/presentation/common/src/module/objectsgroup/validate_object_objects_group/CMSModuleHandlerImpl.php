<?php
namespace CMSModule\objectsgroup\validate_object_objects_group;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("objectsgroup/ObjectsGroupUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		if (is_numeric($settings["group"]))
			$result = \ObjectsGroupUtil::getObjectObjectsGroupsByConditions($brokers, array(
				"objects_group_id" => $settings["objects_group_id"], 
				"object_type_id" => $settings["object_type_id"], 
				"object_id" => $settings["object_id"],
				"group" => $settings["group"],
			), null);
		else
			$result = \ObjectsGroupUtil::getObjectObjectsGroup($brokers, $settings["objects_group_id"], $settings["object_type_id"], $settings["object_id"]);
		
		$status = !empty($result);
		
		return \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
	}
}
?>
