<?php
namespace Module\Message;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/MessageDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class MessageService extends \soa\CommonService {
	private $Message;
	
	private function getMessageHbnObj($b, $options) {
		if (!$this->Message)
			$this->Message = $b->callObject("module/message", "Message", $options);
		
		return $this->Message;
	}
	
	/**
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[subject], type=varchar, default='', length=255)
	 * @param (name=data[content], type=longblob, default='')
	 * @param (name=data[from_user_status], type=tinyint, default=1)
	 * @param (name=data[to_user_status], type=tinyint, default=1)
	 */
	public function insertMessage($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["subject"] = addcslashes($data["subject"], "\\'");
			$data["content"] = addcslashes($data["content"], "\\'");
			
			if ($data["message_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$status = $b->callInsert("module/message", "insert_message_with_ai_pk", $data, $options);
				return $status ? $data["message_id"] : $status;
			}
			
			$status = $b->callInsert("module/message", "insert_message", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			if (!$data["message_id"]) 
				unset($data["message_id"]);
			
			$Message = $this->getMessageHbnObj($b, $options);
			$status = $Message->insert($data, $ids);
			return $status ? $ids["message_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
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
			
			$status = $b->insertObject("mmsg_message", $attributes, $options);
			return $status ? ($data["message_id"] ? $data["message_id"] : $b->getInsertedId($options)) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.insertMessage", $data, $options);
	}
	
	/**
	 * @param (name=data[message_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[seen_date], type=timestamp, not_null=1, min_length=1)
	 */
	public function updateMessageSeenDate($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["seen_date"] = addcslashes($data["seen_date"], "\\'");
			
			return $b->callUpdate("module/message", "update_message_seen_date", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mmsg_message", array(
					"seen_date" => $data["seen_date"],
					"modified_date" => $data["modified_date"]
				), array(
					"message_id" => $data["message_id"],
					"from_user_id" => $data["from_user_id"], 
					"to_user_id" => $data["to_user_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/message", "MessageService.updateMessageSeenDate", $data, $options);
	}
	
	/**
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[from_user_status], type=tinyint, not_null=1)
	 */
	public function updateMessagesFromUserStatus($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/message", "update_messages_from_user_status", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->updateByConditions(array(
				"attributes" => array(
					"from_user_status" => $data["from_user_status"],
					"modified_date" => $data["modified_date"]
				),
				"conditions" => array(
					"from_user_id" => $data["from_user_id"],
					"to_user_id" => $data["to_user_id"]
				),
			));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mmsg_message", array(
					"from_user_status" => $data["from_user_status"],
					"modified_date" => $data["modified_date"]
				), array(
					"from_user_id" => $data["from_user_id"], 
					"to_user_id" => $data["to_user_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.updateMessagesFromUserStatus", $data, $options);
	}
	
	/**
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_status], type=tinyint, not_null=1)
	 */
	public function updateMessagesToUserStatus($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/message", "update_messages_to_user_status", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->updateByConditions(array(
				"attributes" => array(
					"to_user_status" => $data["to_user_status"],
					"modified_date" => $data["modified_date"]
				),
				"conditions" => array(
					"from_user_id" => $data["from_user_id"],
					"to_user_id" => $data["to_user_id"]
				),
			));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mmsg_message", array(
					"to_user_status" => $data["to_user_status"],
					"modified_date" => $data["modified_date"]
				), array(
					"from_user_id" => $data["from_user_id"], 
					"to_user_id" => $data["to_user_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.updateMessagesToUserStatus", $data, $options);
	}
	
	/**
	 * @param (name=data[message_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteMessage($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/message", "delete_message", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->delete($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mmsg_message", array(
					"message_id" => $data["message_id"],
					"from_user_id" => $data["from_user_id"], 
					"to_user_id" => $data["to_user_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/message", "MessageService.deleteMessage", $data, $options);
	}
	
	public function deleteExpiredMessages($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/message", "delete_expired_messages", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->deleteByConditions(array(
				"conditions" => array(
					"from_user_deleted" => 2,
					"to_user_deleted" => 2
				),
			));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mmsg_message", array(
					"from_user_deleted" => 2,
					"to_user_deleted" => 2
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.deleteExpiredMessages", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserMessages($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/message", "delete_user_messages", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->deleteByConditions(array(
				"conditions" => array(
					"OR" => array(
						"from_user_id" => $data["user_id"],
						"to_user_id" => $data["user_id"],
					)
				),
			));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mmsg_message", array(
					"OR" => array(
						"from_user_id" => $data["user_id"],
						"to_user_id" => $data["user_id"],
					)
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.deleteUserMessages", $data, $options);
	}
	
	/**
	 * @param (name=data[message_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 */
	public function getMessage($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/message", "get_message", $data, $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->findById($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mmsg_message", null, array(
					"message_id" => $data["message_id"],
					"from_user_id" => $data["from_user_id"], 
					"to_user_id" => $data["to_user_id"]
				), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.getMessage", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][message_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][from_user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][to_user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][subject], type=varchar|array, length=155)
	 * @param (name=data[conditions][content], type=varchar|array)
	 * @param (name=data[conditions][seen_date], type=timestamp|array)
	 * @param (name=data[conditions][from_user_status], type=tinyint|array)
	 * @param (name=data[conditions][to_user_status], type=tinyint|array)
	 */
	public function getMessagesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/message", "get_messages_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Message = $this->getMessageHbnObj($b, $options);
				return $Message->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mmsg_message", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/message", "MessageService.getMessagesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][message_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][from_user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][to_user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][subject], type=varchar|array, length=155)
	 * @param (name=data[conditions][content], type=varchar|array)
	 * @param (name=data[conditions][seen_date], type=timestamp|array)
	 * @param (name=data[conditions][from_user_status], type=tinyint|array)
	 * @param (name=data[conditions][to_user_status], type=tinyint|array)
	 */
	public function countMessagesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/message", "count_messages_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Message = $this->getMessageHbnObj($b, $options);
				return $Message->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mmsg_message", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/message", "MessageService.countMessagesByConditions", $data, $options);
		}
	}
	
	public function getAllMessages($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/message", "get_all_messages", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mmsg_message", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.getAllMessages", $data, $options);
	}
	
	public function countAllMessages($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/message", "count_all_messages", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mmsg_message", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.countAllMessages", $data, $options);
	}
	
	/**
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 */
	public function getChatMessages($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/message", "get_chat_messages", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->callSelect("get_chat_messages", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MessageDBDAOServiceUtil::get_chat_messages($data);
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.getChatMessages", $data, $options);
	}
	
	/**
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 */
	public function countChatMessages($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/message", "count_chat_messages", $data, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			$result = $Message->callSelect("count_chat_messages", $data, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MessageDBDAOServiceUtil::count_chat_messages($data);
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.countChatMessages", $data, $options);
	}
	
	/**
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[message_id], type=bigint, not_null=1, length=19)
	 */
	public function getPreviousChatMessagesFromMessage($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/message", "get_previous_chat_messages_from_message", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->callSelect("get_previous_chat_messages_from_message", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MessageDBDAOServiceUtil::get_previous_chat_messages_from_message($data);
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.getPreviousChatMessagesFromMessage", $data, $options);
	}
	
	/**
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[message_id], type=bigint, not_null=1, length=19)
	 */
	public function countPreviousChatMessagesFromMessage($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/message", "count_previous_chat_messages_from_message", $data, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			$result = $Message->callSelect("count_previous_chat_messages_from_message", $data, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MessageDBDAOServiceUtil::count_previous_chat_messages_from_message($data);
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.countPreviousChatMessagesFromMessage", $data, $options);
	}
	
	/**
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[message_id], type=bigint, not_null=1, length=19)
	 */
	public function getNextChatMessagesFromMessage($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/message", "get_next_chat_messages_from_message", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->callSelect("get_next_chat_messages_from_message", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MessageDBDAOServiceUtil::get_next_chat_messages_from_message($data);
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.getNextChatMessagesFromMessage", $data, $options);
	}
	
	/**
	 * @param (name=data[from_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[to_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[message_id], type=bigint, not_null=1, length=19)
	 */
	public function countNextChatMessagesFromMessage($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/message", "count_next_chat_messages_from_message", $data, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			$result = $Message->callSelect("count_next_chat_messages_from_message", $data, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MessageDBDAOServiceUtil::count_next_chat_messages_from_message($data);
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.countNextChatMessagesFromMessage", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserChatUsers($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callSelect("module/message", "get_user_chat_users", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->callSelect("get_user_chat_users", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MessageDBDAOServiceUtil::get_user_chat_users($data);
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.getUserChatUsers", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserLastUniqueChats($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/message", "get_user_last_unique_chats", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Message = $this->getMessageHbnObj($b, $options);
			return $Message->callSelect("get_user_last_unique_chats", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MessageDBDAOServiceUtil::get_user_last_unique_chats($data);
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/message", "MessageService.getUserLastUniqueChats", $data, $options);
	}
}
?>
