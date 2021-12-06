<?php
namespace CMSModule\message\manage_chat;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("message/MessageUtil", $common_project_name);
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		if (!$settings["logged_user_id"] && $settings["session_id"]) {
			$session_data = \UserUtil::getUserSessionsByConditions($brokers, array("session_id" => $session_id), null);
			
			if ($session_data[0]) {
				$user_data = \UserUtil::getUsersByConditions($brokers, array("user_id" => $session_data[0]["user_id"]), null);
				$settings["from_user_id"] = $user_data[0]["user_id"];
			}
		}
		else 
			$settings["from_user_id"] = $settings["logged_user_id"];
		
		if (is_numeric($settings["from_user_id"])) {
			switch ($settings["action"]) {
				case "delete_chat":
					if (is_numeric($settings["to_user_id"]) && $settings["to_user_id"] > 0)
						$status_1 = \MessageUtil::updateMessagesFromUserStatus($brokers, array(
							"from_user_status" => \MessageUtil::MESSAGE_DELETED_STATUS,
							"from_user_id" => $settings["from_user_id"],
							"to_user_id" => $settings["to_user_id"],
						));
						
						$status_2 = \MessageUtil::updateMessagesToUserStatus($brokers, array(
							"to_user_status" => \MessageUtil::MESSAGE_DELETED_STATUS,
							"from_user_id" => $settings["to_user_id"],
							"to_user_id" => $settings["from_user_id"],
						));
						
						return $status_1 && $status_2;
					break;
				
				case "get_existent_chat_users":
					$users = \MessageUtil::getUserChatUsers($brokers, $settings["from_user_id"]);
					
					//Add join point updating the data
					$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Changing Users data", array(
						"EVC" => &$EVC,
						"settings" => &$settings,
						"users" => &$users,
					), "This join point only applies if the Action is 'Get existent chat users'");
					
					return json_encode($users ? $users : array());
					//break;
				
				case "new_message":
					if ($_POST && is_numeric($settings["to_user_id"]) && ($_POST["subject"] || $_POST["content"])) {
						$message_id = \MessageUtil::insertMessage($brokers, array(
							"from_user_id" => $settings["from_user_id"],
							"to_user_id" => $settings["to_user_id"],
							"subject" => $_POST["subject"],
							"content" => $_POST["content"],
						));
						return $message_id;
					}
					break;
				
				case "load_messages":
					if ($_POST && is_numeric($settings["to_user_id"])) {
						$message_id = $_POST["message_id"];
						$direction = $_POST["direction"];
					
						if ($message_id) {
							if ($direction < 0) { 
								$messages = \MessageUtil::getPreviousChatMessagesFromMessage($brokers, $settings["from_user_id"], $settings["to_user_id"], $message_id, array("limit" => $settings["maximum_number_of_loaded_messages"], "sort" => array(
									array("column" => "created_date", "order" => "desc")
								)));
								$messages = array_reverse($messages);//To be used to update the seen_date. the correct order is updated bellow.
							}
							else {
								$messages = \MessageUtil::getNextChatMessagesFromMessage($brokers, $settings["from_user_id"], $settings["to_user_id"], $message_id, array("sort" => array(
									array("column" => "created_date", "order" => "asc")
								)));
							}
						}
						else {
							$settings["maximum_number_of_loaded_messages"] = $settings["maximum_number_of_loaded_messages"] ? $settings["maximum_number_of_loaded_messages"] : null;
						
							$messages = \MessageUtil::getChatMessages($brokers, $settings["from_user_id"], $settings["to_user_id"], array("limit" => $settings["maximum_number_of_loaded_messages"], "sort" => array(
								array("column" => "created_date", "order" => "asc")
							)));
						}
					
						//Updating messages seen date
						$current_date = date("Y-m-d H:i:s");
						$t = $messages ? count($messages) : 0;
						for ($i = $t - 1; $i >= 0; $i--) {
							$message = &$messages[$i];
							if ($message["to_user_id"] == $settings["from_user_id"]) {
								if (empty($message["seen_date"])) {
									$message["seen_date"] = $current_date;
									\MessageUtil::updateMessageSeenDate($brokers, $message);
								}
								else 
									break;
							}
						}
						
						//Put back the correct order but only if $direction is desc
						if ($message_id && $direction < 0) 
							$messages = array_reverse($messages);
						
						//Add join point updating the data
						$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Changing Loaded Messages data", array(
							"EVC" => &$EVC,
							"settings" => &$settings,
							"messages" => &$messages,
						), "This join point only applies if the Action is 'Load new Messages'");
						
						return json_encode($messages ? $messages : array());
					}
					break;
				
				case "get_last_chats":
					$settings["maximum_number_of_loaded_messages"] = $settings["maximum_number_of_loaded_messages"] > 0 ? $settings["maximum_number_of_loaded_messages"] : 10;
					
					$messages = \MessageUtil::getUserLastUniqueChats($brokers, $settings["from_user_id"], null, array("limit" => $settings["maximum_number_of_loaded_messages"]));
					
					//Add join point updating the data
					$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Changing Get Last Chats data", array(
						"EVC" => &$EVC,
						"settings" => &$settings,
						"messages" => &$messages,
					), "This join point only applies if the Action is 'Get Last Chats'");
					
					return json_encode($messages ? $messages : array());
					//break;
			}
		}
	}
}
?>
