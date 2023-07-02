<?php
namespace CMSModule\user\validate_user_activity;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		
		include $EVC->getModulePath("user/include_user_session_activities_handler", $EVC->getCommonProjectName());
		
		return $GLOBALS["UserSessionActivitiesHandler"]->validateUserActivity($settings["activity_id"], $settings["object_type_id"], $settings["object_id"], $settings);
	}
}
?>
