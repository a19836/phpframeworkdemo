<?php
namespace CMSModule\event\validate_object_event;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("event/EventUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$settings["event_id"] = isset($settings["event_id"]) ? $settings["event_id"] : null;
		$event_ids = is_array($settings["event_id"]) ? $settings["event_id"] : array($settings["event_id"]);
		
		foreach ($event_ids as $event_id) {
			if (!is_numeric($event_id))
				$status = false;
			else {
				$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
				$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
				$group = isset($settings["group"]) ? $settings["group"] : null;
				
				if (isset($settings["group"]) && is_numeric($settings["group"]))
					$result = \EventUtil::getObjectEventsByConditions($brokers, array(
						"event_id" => $event_id, 
						"object_type_id" => $object_type_id, 
						"object_id" => $object_id,
						"group" => $group,
					), null);
				else
					$result = \EventUtil::getObjectEvent($brokers, $event_id, $object_type_id, $object_id);
		
				$status = !empty($result);
			}
			
			if (!$status)
				break;
		}
		
		return \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
	}
}
?>
