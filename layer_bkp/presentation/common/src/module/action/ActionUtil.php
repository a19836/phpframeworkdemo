<?php
include_once get_lib("org.phpframework.encryption.CryptoKeyHandler");

class ActionUtil {
	
	/* ACTION FUNCTIONS */
	
	public static function insertAction($brokers, $data) {
		if (is_array($brokers)) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "ActionService.insertAction", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
					
					$status = $broker->callInsert("module/action", "insert_action", $data);
					return $status ? $broker->getInsertedId() : $status;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Action = $broker->callObject("module/action", "Action");
					$status = $Action->insert($data, $ids);
					return $status ? $ids["action_id"] : $status;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$status = $broker->insertObject("mact_action", array(
							"name" => $data["name"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
					return $status ? $broker->getInsertedId() : false;
				}
			}
		}
	}
	
	public static function updateAction($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["action_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "ActionService.updateAction", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
					
					return $broker->callUpdate("module/action", "update_action", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Action = $broker->callObject("module/action", "Action");
					return $Action->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mact_action", array(
							"name" => $data["name"], 
							"modified_date" => $data["modified_date"]
						), array(
							"action_id" => $data["action_id"]
						));
				}
			}
		}
	}
	
	public static function deleteAction($brokers, $action_id) {
		if (is_array($brokers) && is_numeric($action_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "ActionService.deleteAction", array("action_id" => $action_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/action", "delete_action", array("action_id" => $action_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Action = $broker->callObject("module/action", "Action");
					return $Action->delete($action_id);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mact_action", array("action_id" => $action_id));
				}
			}
		}
	}
	
	public static function getAllActions($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/action", "ActionService.getAllActions", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/action", "get_all_actions", null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Action = $broker->callObject("module/action", "Action");
					return $Action->find(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mact_action", null, null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function countAllActions($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/action", "ActionService.countAllActions", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/action", "count_all_actions", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Action = $broker->callObject("module/action", "Action");
					return $Action->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mact_action", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getActionsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "ActionService.getActionsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/action", "get_actions_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Action = $broker->callObject("module/action", "Action");
					return $Action->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mact_action", null, $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function countActionsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "ActionService.countActionsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/action", "count_actions_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Action = $broker->callObject("module/action", "Action");
					return $Action->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mact_action", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getActionsByIds($brokers, $action_ids, $no_cache = false) {
		if (is_array($brokers) && $action_ids) {
			$action_ids_str = "";//just in case the user tries to hack the sql query. By default all action_id should be numeric.
			$action_ids = is_array($action_ids) ? $action_ids : array($action_ids);
			foreach ($action_ids as $action_id)
				$action_ids_str .= ($action_ids_str ? ", " : "") . "'" . addcslashes($action_id, "\\'") . "'";
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "ActionService.getActionsByIds", array("action_ids" => $action_ids, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/action", "get_actions_by_ids", array("action_ids" => $action_ids_str), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Action = $broker->callObject("module/action", "Action");
					$conditions = array("action_id" => array("operator" => "in", "value" => $action_ids));
					return $Action->find(array("conditions" => $conditions), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mact_action", null, array("action_id" => array("operator" => "in", "value" => $action_ids)), array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	/* USER ACTION FUNCTIONS */
	
	public static function insertUserAction($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["user_id"]) && is_numeric($data["action_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"]) && is_numeric($data["time"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "UserActionService.insertUserAction", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["value"] = addcslashes($data["value"], "\\'");
					
					return $broker->callInsert("module/action", "insert_user_action", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->insert($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->insertObject("mact_user_action", array(
							"user_id" => $data["user_id"], 
							"action_id" => $data["action_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"], 
							"time" => $data["time"], 
							"value" => $data["value"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
				}
			}
		}
	}
	
	public static function updateUserAction($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["user_id"]) && is_numeric($data["action_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"]) && is_numeric($data["time"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "UserActionService.updateUserAction", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["value"] = addcslashes($data["value"], "\\'");
					
					return $broker->callUpdate("module/action", "update_user_action", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mact_user_action", array(
							"value" => $data["value"], 
							"modified_date" => $data["modified_date"]
						), array(
							"user_id" => $data["user_id"], 
							"action_id" => $data["action_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"], 
							"time" => $data["time"]
						));
				}
			}
		}
	}
	
	public static function deleteUserAction($brokers, $user_id, $action_id, $object_type_id, $object_id, $time) {
		if (is_array($brokers) && is_numeric($user_id) && is_numeric($action_id) && is_numeric($object_type_id) && is_numeric($object_id) && is_numeric($time)) {
			$data = array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "UserActionService.deleteUserAction", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/action", "delete_user_action", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mact_user_action", $data);
				}
			}
		}
	}
	
	public static function deleteUserActionsByUserId($brokers, $user_id) {
		if (is_array($brokers) && is_numeric($user_id)) {
			$data = array("user_id" => $user_id);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "UserActionService.deleteUserActionsByUserId", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/action", "delete_user_actions_by_user_id", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mact_user_action", $data);
				}
			}
		}
	}
	
	public static function deleteUserActionsByActionId($brokers, $action_id) {
		if (is_array($brokers) && is_numeric($action_id)) {
			$data = array("action_id" => $action_id);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "UserActionService.deleteUserActionsByActionId", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/action", "delete_user_actions_by_action_id", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mact_user_action", $data);
				}
			}
		}
	}
	
	public static function deleteUserActionsByObject($brokers, $object_type_id, $object_id) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$data = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "UserActionService.deleteUserActionsByObject", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/action", "delete_user_actions_by_object", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mact_user_action", $data);
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function getUserActionsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "UserActionService.getUserActionsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/action", "get_user_action_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mact_user_action", null, $conditions, $options);
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function countUserActionsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/action", "UserActionService.countUserActionsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/action", "count_user_action_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mact_user_action", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getUserActionsByActionId($brokers, $action_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($action_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("action_id" => $action_id, "options" => $options);
					return $broker->callBusinessLogic("module/action", "UserActionService.getUserActionsByActionId", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/action", "get_user_actions_by_action_id", array("action_id" => $action_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->find(array("conditions" => array("action_id" => $action_id)), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mact_user_action", null, array("action_id" => $action_id), $options);
				}
			}
		}
	}
	
	public static function countUserActionsByActionId($brokers, $action_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($action_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("action_id" => $action_id, "options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/action", "UserActionService.countUserActionsByActionId", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/action", "count_user_actions_by_action_id", array("action_id" => $action_id), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->count(array("conditions" => array("action_id" => $action_id)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mact_user_action", array("action_id" => $action_id), array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getAllUserActions($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/action", "UserActionService.getAllUserActions", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/action", "get_all_user_actions", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mact_user_action", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllUserActions($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/action", "UserActionService.countAllUserActions", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/action", "count_all_user_actions", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAction = $broker->callObject("module/action", "UserAction");
					return $UserAction->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mact_user_action", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
}
?>
