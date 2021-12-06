<?php
/*
 * Sample test commands:
 * 	curl -v --data "event=insert" --cookie "session_id=<session_id>" "<url>/manage_user_action?object_id=1"
 * 	curl -v --data "time=1445341533&event=update&value=2" --cookie "session_id=<session_id>" "<url>/manage_user_action?object_id=1"
 * 	curl -v --data "time=1445341466&event=delete" --cookie "session_id=<session_id>" "<url>/manage_user_action?object_id=1"
 * 	curl -v --data "time=1445341533&event=save&value=5" --cookie "session_id=<session_id>" "<url>/manage_user_action?object_id=1"
 */
namespace CMSModule\action\manage_user_action;

include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("action/ActionUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Preparing Data
		$object_type_id = $settings["object_type_id"];
		$object_id = $settings["object_id"];
		$action_id = $settings["action_id"];
		$session_id = $settings["session_id"];
		$user_id = $settings["user_id"];
		
		if (!$user_id && $session_id) {
			include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
	
			$session_data = $session_id ? \UserUtil::getUserSessionsByConditions($brokers, array("session_id" => $session_id), null) : null;
			
			if ($session_data[0]) {
				$user_data = \UserUtil::getUsersByConditions($brokers, array("user_id" => $session_data[0]["user_id"]), null);
				$user_id = $user_data[0]["user_id"];
			}
		}
		
		//Preparing Event
		$status = false;
		
		if ($_POST && $user_id && $action_id && $object_type_id && $object_id) {
			$event = $_POST["event"];
			$time = $_POST["time"];
			$value = $_POST["value"];
			
			switch ($event) {
				case "delete":
				case "update":
				case "save":
					$data = \ActionUtil::getUserActionsByConditions($brokers, array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), null);
					$data = $data[0];
					break;
			}
			
			switch ($event) {
				case "delete":
					if ($settings["allow_deletion"] && $data) {
						if (\ActionUtil::deleteUserAction($brokers, $user_id, $action_id, $object_type_id, $object_id, $time)) {
							$status = true;
						}
					}
					break;
				case "update":
					if ($settings["allow_update"] && $data) {
						$data["value"] = $value;
						$status = \ActionUtil::updateUserAction($brokers, $data);
					}
					break;
				case "insert":
					if ($settings["allow_insertion"]) {
						$time = $this->insertAction($brokers, $user_id, $action_id, $object_type_id, $object_id, $value);
						$status = $time ? true : false;
					}
					break;
				case "save":
					if ($data) {
						if ($settings["allow_update"]) {
							$data["value"] = $value;
							$status = \ActionUtil::updateUserAction($brokers, $data);
						}
					}
					else if ($settings["allow_insertion"]) {
						$time = $this->insertAction($brokers, $user_id, $action_id, $object_type_id, $object_id, $value);
						$status = $time ? true : false;
					}
					break;
			}
		}
		
		//Preparing response
		if ($status)
			return strlen($settings["ok_response"]) ? translateProjectText($EVC, $settings["ok_response"]) : $time;
		else 
			return translateProjectText($EVC, $settings["error_response"]);
	}
	
	private function insertAction($brokers, $user_id, $action_id, $object_type_id, $object_id, $value) {
		$data = array(
			"user_id" => $user_id,
			"action_id" => $action_id,
			"object_type_id" => $object_type_id,
			"object_id" => $object_id,
			"time" => time(),
			"value" => $value,
		);
		
		if (\ActionUtil::insertUserAction($brokers, $data)) {
			return $data["time"];
		}
	}
}
?>
