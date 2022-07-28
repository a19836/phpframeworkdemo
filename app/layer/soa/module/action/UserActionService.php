<?php
namespace Module\Action;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class UserActionService extends \soa\CommonService {
	private $UserAction;
	
	private function getUserActionHbnObj($b, $options) {
		if (!$this->UserAction) 
			$this->UserAction = $b->callObject("module/action", "UserAction", $options);
		
		return $this->UserAction;
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[action_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[time], type=bigint, not_null=1, length=19)
	 * @param (name=data[value], type=varchar, length=100)
	 */
	public function insertUserAction($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["value"] = addcslashes($data["value"], "\\'");
			
			return $b->callInsert("module/action", "insert_user_action", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			return $UserAction->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("mact_user_action", array(
					"user_id" => $data["user_id"], 
					"action_id" => $data["action_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"time" => $data["time"], 
					"value" => $data["value"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.insertUserAction", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[action_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[time], type=bigint, not_null=1, length=19)
	 * @param (name=data[value], type=varchar, length=100)
	 */
	public function updateUserAction($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["value"] = addcslashes($data["value"], "\\'");
			
			return $b->callUpdate("module/action", "update_user_action", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			return $UserAction->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mact_user_action", array(
					"value" => $data["value"], 
					"modified_date" => $data["modified_date"]
				), array(
					"user_id" => $data["user_id"], 
					"action_id" => $data["action_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"time" => $data["time"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.updateUserAction", $data, $options);
	}
	
	/**
	 * @param (name=data[new_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_action_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_action_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_time], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_time], type=bigint, not_null=1, length=19)
	 */
	public function updateUserActionPks($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/action", "update_user_action_pks", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			return $UserAction->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mact_user_action", array(
					"user_id" => $data["new_user_id"], 
					"action_id" => $data["new_action_id"], 
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"time" => $data["new_time"],
					"modified_date" => $data["modified_date"]
				), array(
					"user_id" => $data["old_user_id"], 
					"action_id" => $data["old_action_id"], 
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
					"time" => $data["old_time"] 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.updateUserActionPks", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[action_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[time], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserAction($data) {
		$user_id = $data["user_id"];
		$action_id = $data["action_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$time = $data["time"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/action", "delete_user_action", array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			return $UserAction->delete(array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mact_user_action", array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.deleteUserAction", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserActionsByUserId($data) {
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/action", "delete_user_actions_by_user_id", array("user_id" => $user_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			$conditions = array("user_id" => $user_id);
			return $UserAction->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mact_user_action", array("user_id" => $user_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.deleteUserActionsByUserId", $data, $options);
	}
	
	/**
	 * @param (name=data[action_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserActionsByActionId($data) {
		$action_id = $data["action_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/action", "delete_user_actions_by_action_id", array("action_id" => $action_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			$conditions = array("action_id" => $action_id);
			return $UserAction->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mact_user_action", array("action_id" => $action_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.deleteUserActionsByActionId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserActionsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/action", "delete_user_actions_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $UserAction->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mact_user_action", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.deleteUserActionsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[action_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[time], type=bigint, not_null=1, length=19)
	 */
	public function getUserAction($data) {
		$user_id = $data["user_id"];
		$action_id = $data["action_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$time = $data["time"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/action", "get_user_action", array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			return $UserAction->findById(array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mact_user_action", null, array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.getUserAction", $data, $options);
	}
	
	/**
	 * @param (name=data[action_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserActionsByActionId($data) {
		$action_id = $data["action_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/action", "get_user_actions_by_action_id", array("action_id" => $action_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			$conditions = array("action_id" => $action_id);
			return $UserAction->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mact_user_action", null, array("action_id" => $action_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.getUserActionsByActionId", $data, $options);
	}
	
	/**
	 * @param (name=data[action_id], type=bigint, not_null=1, length=19)
	 */
	public function countUserActionsByActionId($data) {
		$action_id = $data["action_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/action", "count_user_actions_by_action_id", array("action_id" => $action_id), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			return $UserAction->count(array("conditions" => array("action_id" => $action_id)));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mact_user_action", array("action_id" => $action_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.countUserActionsByActionId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserActionsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/action", "get_user_actions_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $UserAction->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mact_user_action", null, array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.getUserActionsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][action_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][time], type=bigint|array, length=19)
	 */
	public function getUserActionsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/action", "get_user_actions_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserAction = $this->getUserActionHbnObj($b, $options);
				return $UserAction->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mact_user_action", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/action", "UserActionService.getUserActionsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][action_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][time], type=bigint|array, length=19)
	 */
	public function countUserActionsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/action", "count_user_actions_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserAction = $this->getUserActionHbnObj($b, $options);
				return $UserAction->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mact_user_action", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/action", "UserActionService.countUserActionsByConditions", $data, $options);
		}
	}
	
	public function getAllUserActions($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/action", "get_all_user_actions", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			return $UserAction->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mact_user_action", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.getAllUserActions", null, $options);
	}
	
	public function countAllUserActions($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/action", "count_all_user_actions", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAction = $this->getUserActionHbnObj($b, $options);
			return $UserAction->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mact_user_action", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "UserActionService.countAllUserActions", null, $options);
	}
}
?>
