<?php
include_once __DIR__ . "/MessageDBDAOUtil.php"; //this file will be automatically generated on this module installation

class MessageUtil {
	const MESSAGE_DELETED_STATUS = 2;

	/* MESSAGE FUNCTIONS */

	public static function insertMessage($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["from_user_id"]) && is_numeric($data["to_user_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			$options = array();
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.insertMessage", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["subject"] = addcslashes($data["subject"], "\\'");
					$data["content"] = addcslashes($data["content"], "\\'");
					$data["from_user_status"] = is_numeric($data["from_user_status"]) ? $data["from_user_status"] : 1;
					$data["to_user_status"] = is_numeric($data["to_user_status"]) ? $data["to_user_status"] : 1;
					
					if ($data["message_id"]) {
						$options = array("hard_coded_ai_pk" => true);
						$status = $broker->callInsert("module/message", "insert_message_with_ai_pk", $data, $options);
						return $status ? $data["message_id"] : $status;
					}
					
					$status = $broker->callInsert("module/message", "insert_message", $data);
					return $status ? $broker->getInsertedId($options) : $status;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["from_user_status"] = is_numeric($data["from_user_status"]) ? $data["from_user_status"] : 1;
					$data["to_user_status"] = is_numeric($data["to_user_status"]) ? $data["to_user_status"] : 1;
					
					if (!$data["message_id"]) {
						unset($data["message_id"]);
					}
					
					$Message = $broker->callObject("module/message", "Message");
					$status = $Message->insert($data, $ids);
					return $status ? $ids["message_id"] : $status;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data["from_user_status"] = is_numeric($data["from_user_status"]) ? $data["from_user_status"] : 1;
					$data["to_user_status"] = is_numeric($data["to_user_status"]) ? $data["to_user_status"] : 1;
					
					$attributes = array(
						"from_user_id" => $data["from_user_id"], 
						"to_user_id" => $data["to_user_id"], 
						"subject" => $data["subject"], 
						"content" => $data["content"], 
						"from_user_status" => $data["from_user_status"], 
						"to_user_status" => $data["to_user_status"], 
						"created_date" => $data["created_date"], 
						"modified_date" => $data["modified_date"]
					);
					
					if ($data["message_id"]) {
						$options["hard_coded_ai_pk"] = true;
						$attributes["message_id"] = $data["message_id"];
					}
					
					$status = $broker->insertObject("mmsg_message", $attributes, $options);
					return $status ? ($data["message_id"] ? $data["message_id"] : $broker->getInsertedId($options)) : $status;
				}
			}
		}
	}

	public static function updateMessageSeenDate($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["message_id"]) && is_numeric($data["from_user_id"]) && is_numeric($data["to_user_id"]) && $data["seen_date"]) {
			$data["modified_date"] = date("Y-m-d H:i:s");
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.updateMessageSeenDate", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["seen_date"] = addcslashes($data["seen_date"], "\\'");
				
					return $broker->callUpdate("module/message", "update_message_seen_date", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mmsg_message", array(
							"seen_date" => $data["seen_date"],
							"modified_date" => $data["modified_date"]
						), array(
							"message_id" => $data["message_id"],
							"from_user_id" => $data["from_user_id"], 
							"to_user_id" => $data["to_user_id"], 
						));
				}
			}
		}
	}

	public static function updateMessagesFromUserStatus($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["from_user_id"]) && is_numeric($data["to_user_id"]) && is_numeric($data["from_user_status"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.updateMessagesFromUserStatus", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/message", "update_messages_from_user_status", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->updateByConditions(array(
						"attributes" => array(
							"from_user_status" => $data["from_user_status"],
							"modified_date" => $data["modified_date"],
						),
						"conditions" => array(
							"from_user_id" => $data["from_user_id"],
							"to_user_id" => $data["to_user_id"],
						),
					));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mmsg_message", array(
							"from_user_status" => $data["from_user_status"],
							"modified_date" => $data["modified_date"]
						), array(
							"from_user_id" => $data["from_user_id"], 
							"to_user_id" => $data["to_user_id"], 
						));
				}
			}
		}
	}

	public static function updateMessagesToUserStatus($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["from_user_id"]) && is_numeric($data["to_user_id"]) && is_numeric($data["to_user_status"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.updateMessagesToUserStatus", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/message", "update_messages_to_user_status", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->updateByConditions(array(
						"attributes" => array(
							"to_user_status" => $data["to_user_status"],
							"modified_date" => $data["modified_date"],
						),
						"conditions" => array(
							"from_user_id" => $data["from_user_id"],
							"to_user_id" => $data["to_user_id"],
						),
					));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mmsg_message", array(
							"to_user_status" => $data["to_user_status"],
							"modified_date" => $data["modified_date"],
						), array(
							"from_user_id" => $data["from_user_id"],
							"to_user_id" => $data["to_user_id"],
						));
				}
			}
		}
	}

	public static function deleteMessage($brokers, $message_id, $from_user_id, $to_user_id) {
		if (is_array($brokers) && is_numeric($message_id) && is_numeric($from_user_id) && is_numeric($to_user_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.deleteMessage", array("message_id" => $message_id, "from_user_id" => $from_user_id, "to_user_id" => $to_user_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/message", "delete_message", array("message_id" => $message_id, "from_user_id" => $from_user_id, "to_user_id" => $to_user_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->delete(array("message_id" => $message_id, "from_user_id" => $from_user_id, "to_user_id" => $to_user_id));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mmsg_message", array("message_id" => $message_id, "from_user_id" => $from_user_id, "to_user_id" => $to_user_id));
				}
			}
		}
	}

	public static function deleteExpiredMessages($brokers) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.deleteExpiredMessages");
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/message", "delete_expired_messages");
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->deleteByConditions(array(
						"conditions" => array(
							"from_user_deleted" => 2,
							"to_user_deleted" => 2,
						),
					));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mmsg_message", array(
							"from_user_deleted" => 2,
							"to_user_deleted" => 2,
						));
				}
			}
		}
	}

	public static function deleteUserMessages($brokers, $user_id) {
		if (is_array($brokers) && is_numeric($user_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.deleteUserMessages", array("user_id" => $user_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/message", "delete_user_messages", array("user_id" => $user_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->deleteByConditions(array(
						"conditions" => array(
							"OR" => array(
								"from_user_id" => $user_id,
								"to_user_id" => $user_id,
							)
						)
					));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mmsg_message", array(
							"OR" => array(
								"from_user_id" => $user_id,
								"to_user_id" => $user_id,
							)
						));
				}
			}
		}
	}

	public static function getAllMessages($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/message", "MessageService.getAllMessages", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/message", "get_all_messages", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mmsg_message", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllMessages($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/message", "MessageService.countAllMessages", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/message", "count_all_messages", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mmsg_message", null, array("no_cache" => $no_cache));
				}
			}
		}
	}

	public static function getMessagesByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.getMessagesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/message", "get_messages_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mmsg_message", null, $conditions, $options);
				}
			}
		}
	}

	public static function countMessagesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.countMessagesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/message", "count_messages_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mmsg_message", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getChatMessages($brokers, $from_user_id, $to_user_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($from_user_id) && is_numeric($to_user_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.getChatMessages", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/message", "get_chat_messages", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->callSelect("get_chat_messages", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MessageDBDAOUtil::get_chat_messages(array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id));
						
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function countChatMessages($brokers, $from_user_id, $to_user_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($from_user_id) && is_numeric($to_user_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.countChatMessages", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/message", "count_chat_messages", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id), $options);
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					$result = $Message->callSelect("count_chat_messages", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id), $options);
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MessageDBDAOUtil::count_chat_messages(array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id));
						
					$result = $broker->getSQL($sql, $options);
					return $result[0]["total"];
				}
			}
		}
	}
	
	public static function getPreviousChatMessagesFromMessage($brokers, $from_user_id, $to_user_id, $message_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($from_user_id) && is_numeric($to_user_id) && is_numeric($message_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.getPreviousChatMessagesFromMessage", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/message", "get_previous_chat_messages_from_message", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->callSelect("get_previous_chat_messages_from_message", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MessageDBDAOUtil::get_previous_chat_messages_from_message(array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id));
						
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function countPreviousChatMessagesFromMessage($brokers, $from_user_id, $to_user_id, $message_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($from_user_id) && is_numeric($to_user_id) && is_numeric($message_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.countPreviousChatMessagesFromMessage", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/message", "count_previous_chat_messages_from_message", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id), $options);
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					$result = $Message->callSelect("count_previous_chat_messages_from_message", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id), $options);
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MessageDBDAOUtil::count_previous_chat_messages_from_message(array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id));
						
					$result = $broker->getSQL($sql, $options);
					return $result[0]["total"];
				}
			}
		}
	}
	
	public static function getNextChatMessagesFromMessage($brokers, $from_user_id, $to_user_id, $message_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($from_user_id) && is_numeric($to_user_id) && is_numeric($message_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.getNextChatMessagesFromMessage", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/message", "get_next_chat_messages_from_message", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->callSelect("get_next_chat_messages_from_message", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MessageDBDAOUtil::get_next_chat_messages_from_message(array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id));
						
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function countNextChatMessagesFromMessage($brokers, $from_user_id, $to_user_id, $message_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($from_user_id) && is_numeric($to_user_id) && is_numeric($message_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.countNextChatMessagesFromMessage", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/message", "count_next_chat_messages_from_message", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id), $options);
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					$result = $Message->callSelect("count_next_chat_messages_from_message", array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id), $options);
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MessageDBDAOUtil::count_next_chat_messages_from_message(array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "message_id" => $message_id));
						
					$result = $broker->getSQL($sql, $options);
					return $result[0]["total"];
				}
			}
		}
	}
	
	public static function getUserChatUsers($brokers, $user_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($user_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.getUserChatUsers", array("user_id" => $user_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/message", "get_user_chat_users", array("user_id" => $user_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->callSelect("get_user_chat_users", array("user_id" => $user_id), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MessageDBDAOUtil::get_user_chat_users(array("user_id" => $user_id));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function getUserLastUniqueChats($brokers, $user_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($user_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/message", "MessageService.getUserLastUniqueChats", array("user_id" => $user_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/message", "get_user_last_unique_chats", array("user_id" => $user_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Message = $broker->callObject("module/message", "Message");
					return $Message->callSelect("get_user_last_unique_chats", array("user_id" => $user_id), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MessageDBDAOUtil::get_user_last_unique_chats(array("user_id" => $user_id));
					
					$result = $broker->getSQL($sql, $options);
					return $result[0]["total"];
				}
			}
		}
	}
}
?>
