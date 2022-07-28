<?php
namespace CMSModule\event\validate_object_event;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("event/EventUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$event_ids = is_array($settings["event_id"]) ? $settings["event_id"] : array($settings["event_id"]);
		
		foreach ($event_ids as $event_id) {
			if (!is_numeric($event_id))
				$status = false;
			else {
				if (is_numeric($settings["group"]))
					$result = \EventUtil::getObjectEventsByConditions($brokers, array(
						"event_id" => $event_id, 
						"object_type_id" => $settings["object_type_id"], 
						"object_id" => $settings["object_id"],
						"group" => $settings["group"],
					), null);
				else
					$result = \EventUtil::getObjectEvent($brokers, $event_id, $settings["object_type_id"], $settings["object_id"]);
		
				$status = !empty($result);
			}
			
			if (!$status)
				break;
		}
		
		return \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
	}
}
?>
