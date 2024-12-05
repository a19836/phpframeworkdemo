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
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("action/ActionUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Preparing Data
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		$action_id = isset($settings["action_id"]) ? $settings["action_id"] : null;
		$session_id = isset($settings["session_id"]) ? $settings["session_id"] : null;
		$user_id = isset($settings["user_id"]) ? $settings["user_id"] : null;
		
		if (!$user_id && $session_id) {
			include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
	
			$session_data = $session_id ? \UserUtil::getUserSessionsByConditions($brokers, array("session_id" => $session_id), null) : null;
			
			if (isset($session_data[0]["user_id"])) {
				$user_data = \UserUtil::getUsersByConditions($brokers, array("user_id" => $session_data[0]["user_id"]), null);
				$user_id = isset($user_data[0]["user_id"]) ? $user_data[0]["user_id"] : null;
			}
		}
		
		//Preparing Event
		$status = false;
		
		if (!empty($_POST) && $user_id && $action_id && $object_type_id && $object_id) {
			$event = isset($_POST["event"]) ? $_POST["event"] : null;
			$time = isset($_POST["time"]) ? $_POST["time"] : null;
			$value = isset($_POST["value"]) ? $_POST["value"] : null;
			
			switch ($event) {
				case "delete":
				case "update":
				case "save":
					$data = \ActionUtil::getUserActionsByConditions($brokers, array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), null);
					$data = isset($data[0]) ? $data[0] : null;
					break;
			}
			
			switch ($event) {
				case "delete":
					if (!empty($settings["allow_deletion"]) && !empty($data)) {
						if (\ActionUtil::deleteUserAction($brokers, $user_id, $action_id, $object_type_id, $object_id, $time)) {
							$status = true;
						}
					}
					break;
				case "update":
					if (!empty($settings["allow_update"]) && !empty($data)) {
						$data["value"] = $value;
						$status = \ActionUtil::updateUserAction($brokers, $data);
					}
					break;
				case "insert":
					if (!empty($settings["allow_insertion"])) {
						$time = $this->insertAction($brokers, $user_id, $action_id, $object_type_id, $object_id, $value);
						$status = $time ? true : false;
					}
					break;
				case "save":
					if (!empty($data)) {
						if (!empty($settings["allow_update"])) {
							$data["value"] = $value;
							$status = \ActionUtil::updateUserAction($brokers, $data);
						}
					}
					else if (!empty($settings["allow_insertion"])) {
						$time = $this->insertAction($brokers, $user_id, $action_id, $object_type_id, $object_id, $value);
						$status = $time ? true : false;
					}
					break;
			}
		}
		
		//Preparing response
		if ($status)
			return isset($settings["ok_response"]) && strlen($settings["ok_response"]) ? translateProjectText($EVC, $settings["ok_response"]) : (isset($time) ? $time : null);
		else 
			return isset($settings["error_response"]) ? translateProjectText($EVC, $settings["error_response"]) : null;
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
			return isset($data["time"]) ? $data["time"] : null;
		}
	}
}
?>
