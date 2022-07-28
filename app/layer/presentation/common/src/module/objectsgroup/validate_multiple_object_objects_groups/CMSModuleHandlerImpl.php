<?php
namespace CMSModule\objectsgroup\validate_multiple_object_objects_groups;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("objectsgroup/ObjectsGroupUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		if ($settings["objects_group_ids"]) {
			$settings["objects_group_ids"] = array_unique($settings["objects_group_ids"]);
			$status = true;
			
			foreach ($settings["objects_group_ids"] as $objects_group_id) {
				if (is_numeric($settings["group"]))
					$result = \ObjectsGroupUtil::getObjectObjectsGroupsByConditions($brokers, array(
						"objects_group_id" => $objects_group_id, 
						"object_type_id" => $settings["object_type_id"], 
						"object_id" => $settings["object_id"],
						"group" => $settings["group"],
					), null);
				else
					$result = \ObjectsGroupUtil::getObjectObjectsGroup($brokers, $objects_group_id, $settings["object_type_id"], $settings["object_id"]);
			
				if (!$result) {
					$status = false;
					break;
				}
			}
		}
		
		return \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
	}
}
?>
