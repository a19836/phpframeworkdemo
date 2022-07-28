<?php
namespace CMSModule\user\validate_object_user;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		if (is_numeric($settings["group"]))
			$result = \UserUtil::getObjectUsersByConditions($brokers, array(
				"user_id" => $settings["user_id"], 
				"object_type_id" => $settings["object_type_id"], 
				"object_id" => $settings["object_id"],
				"group" => $settings["group"],
			), null);
		else
			$result = \UserUtil::getObjectUser($brokers, $settings["user_id"], $settings["object_type_id"], $settings["object_id"]);
		
		$status = !empty($result);
		
		return \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
	}
}
?>
