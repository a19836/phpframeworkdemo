<?php
/*
 * Sample test commands:
 * 	curl -v --data "event=insert&comment=blable bli" --cookie "session_id=<session_id>" "<url>/manage_object_comment?object_id=1"
 * 	curl -v --data "comment_id=11&event=update&comment=outro..." --cookie "session_id=<session_id>" "<url>/manage_object_comment?object_id=1"
 * 	curl -v --data "comment_id=9&event=delete" --cookie "session_id=<session_id>" "<url>/manage_object_comment?object_id=1"
 * 	curl -v --data "comment_id=14&event=save&comment=outro..." --cookie "session_id=<session_id>" "<url>/manage_object_comment?object_id=1"
 */
namespace CMSModule\comment\manage_object_comment;

include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("comment/CommentUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Preparing Data
		$object_type_id = $settings["object_type_id"];
		$object_id = $settings["object_id"];
		$session_id = $settings["session_id"];
		$user_id = $settings["user_id"];
		$validate_user = $settings["validate_user"];
		
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
		
		if ($_POST && $object_type_id && $object_id) {
			$event = $_POST["event"];
			$comment_id = $_POST["comment_id"];
			$comment = $_POST["comment"];
			$is_validated = false;
			
			switch ($event) {
				case "delete":
				case "update":
				case "save":
					$data = \CommentUtil::getCommentsByConditions($brokers, array("comment_id" => $comment_id), null);
					$data = $data[0];
					
					if (!$validate_user || (is_numeric($user_id) && $data["user_id"] == $user_id)) {
						$is_validated = true;
					}
					break;
			}
			
			switch ($event) {
				case "delete":
					if ($settings["allow_deletion"] && $comment_id && (!$data || $is_validated)) {
						if (\CommentUtil::deleteObjectComment($brokers, $comment_id, $object_type_id, $object_id) && \CommentUtil::deleteComment($brokers, $comment_id)) {
							$status = true;
						}
					}
					break;
				case "update":
					if ($settings["allow_update"] && $comment_id && $data && $is_validated && $comment) {
						$data["comment"] = $comment;
						$status = $this->updateComment($brokers, $settings, $comment_id, $object_type_id, $object_id, $data);
					}
					break;
				case "insert":
					if ($settings["allow_insertion"] && is_numeric($user_id) && $comment) {
						$status = $this->insertComment($brokers, $settings, $user_id, $object_type_id, $object_id, $comment);
					}
					break;
				case "save":
					if ($comment) {
						if ($data) {
							if ($settings["allow_update"] && $comment_id && $is_validated) {
								$data["comment"] = $comment;
								$status = $this->updateComment($brokers, $settings, $comment_id, $object_type_id, $object_id, $data);
							}
						}
						else if ($settings["allow_insertion"] && is_numeric($user_id)) {
							$status = $this->insertComment($brokers, $settings, $user_id, $object_type_id, $object_id, $comment);
						}
					}
					break;
			}
		}
		
		//Preparing response
		if ($status)
			return strlen($settings["ok_response"]) ? translateProjectText($EVC, $settings["ok_response"]) : $comment_id;
		else 
			return translateProjectText($EVC, $settings["error_response"]);
	}
	
	private function insertComment($brokers, $settings, $user_id, $object_type_id, $object_id, $comment) {
		$status = false;
		
		$data = array(
			"user_id" => $user_id,
			"comment" => $comment,
			"object_comments" => $settings["object_to_objects"],
						
		);
		
		$comment_id = \CommentUtil::insertComment($brokers, $data);
		
		if ($comment_id) {
			$data = array("comment_id" => $comment_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
			if (\CommentUtil::insertObjectComment($brokers, $data)) {
				$status = true;
			}
		}
		
		return $status ? $comment_id : false;
	}
	
	private function updateComment($brokers, $settings, $comment_id, $object_type_id, $object_id, $data) {
		$status = false;
		
		if ($data) {
			$data["object_comments"] = $settings["object_to_objects"];
			
			if (\CommentUtil::updateComment($brokers, $data)) {
				$status = true;
			
				$cond = array("comment_id" => $comment_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
				$data = \CommentUtil::getObjectCommentsByConditions($brokers, $cond, null);
				
				if (!$data[0] && !\CommentUtil::insertObjectComment($brokers, $cond))
					$status = false;
			}
		}
		
		return $status ? $comment_id : false;
	}
}
?>
