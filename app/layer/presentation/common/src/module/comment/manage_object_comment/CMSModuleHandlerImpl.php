<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

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
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("comment/CommentUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Preparing Data
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		$session_id = isset($settings["session_id"]) ? $settings["session_id"] : null;
		$user_id = isset($settings["user_id"]) ? $settings["user_id"] : null;
		$validate_user = isset($settings["validate_user"]) ? $settings["validate_user"] : null;
		
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
		
		if (!empty($_POST) && $object_type_id && $object_id) {
			$event = isset($_POST["event"]) ? $_POST["event"] : null;
			$comment_id = isset($_POST["comment_id"]) ? $_POST["comment_id"] : null;
			$comment = isset($_POST["comment"]) ? $_POST["comment"] : null;
			$is_validated = false;
			$data = null;
			
			switch ($event) {
				case "delete":
				case "update":
				case "save":
					$data = \CommentUtil::getCommentsByConditions($brokers, array("comment_id" => $comment_id), null);
					$data = isset($data[0]) ? $data[0] : null;
					
					if (!$validate_user || (is_numeric($user_id) && isset($data["user_id"]) && $data["user_id"] == $user_id)) {
						$is_validated = true;
					}
					break;
			}
			
			switch ($event) {
				case "delete":
					if (!empty($settings["allow_deletion"]) && $comment_id && (!$data || $is_validated)) {
						if (\CommentUtil::deleteObjectComment($brokers, $comment_id, $object_type_id, $object_id) && \CommentUtil::deleteComment($brokers, $comment_id)) {
							$status = true;
						}
					}
					break;
				case "update":
					if (!empty($settings["allow_update"]) && $comment_id && $data && $is_validated && $comment) {
						$data["comment"] = $comment;
						$status = $this->updateComment($brokers, $settings, $comment_id, $object_type_id, $object_id, $data);
					}
					break;
				case "insert":
					if (!empty($settings["allow_insertion"]) && is_numeric($user_id) && $comment) {
						$status = $this->insertComment($brokers, $settings, $user_id, $object_type_id, $object_id, $comment);
					}
					break;
				case "save":
					if ($comment) {
						if ($data) {
							if (!empty($settings["allow_update"]) && $comment_id && $is_validated) {
								$data["comment"] = $comment;
								$status = $this->updateComment($brokers, $settings, $comment_id, $object_type_id, $object_id, $data);
							}
						}
						else if (!empty($settings["allow_insertion"]) && is_numeric($user_id)) {
							$status = $this->insertComment($brokers, $settings, $user_id, $object_type_id, $object_id, $comment);
						}
					}
					break;
			}
		}
		
		//Preparing response
		if ($status)
			return isset($settings["ok_response"]) && strlen($settings["ok_response"]) ? translateProjectText($EVC, $settings["ok_response"]) : (isset($comment_id) ? $comment_id : null);
		else 
			return isset($settings["error_response"]) ? translateProjectText($EVC, $settings["error_response"]) : null;
	}
	
	private function insertComment($brokers, $settings, $user_id, $object_type_id, $object_id, $comment) {
		$status = false;
		
		$data = array(
			"user_id" => $user_id,
			"comment" => $comment,
			"object_comments" => isset($settings["object_to_objects"]) ? $settings["object_to_objects"] : null,
						
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
			$data["object_comments"] = isset($settings["object_to_objects"]) ? $settings["object_to_objects"] : null;
			
			if (\CommentUtil::updateComment($brokers, $data)) {
				$status = true;
			
				$cond = array("comment_id" => $comment_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
				$data = \CommentUtil::getObjectCommentsByConditions($brokers, $cond, null);
				
				if (empty($data[0]) && !\CommentUtil::insertObjectComment($brokers, $cond))
					$status = false;
			}
		}
		
		return $status ? $comment_id : false;
	}
}
?>
