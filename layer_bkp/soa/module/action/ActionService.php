<?php
namespace Module\Action;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class ActionService extends \soa\CommonService {
	private $Action;
	
	private function getActionHbnObj($b, $options) {
		if (!$this->Action)
			$this->Action = $b->callObject("module/action", "Action", $options);
		
		return $this->Action;
	}
	
	/**
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=100)
	 */
	public function insertAction($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			$status = $b->callInsert("module/action", "insert_action", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Action = $this->getActionHbnObj($b, $options);
			$status = $Action->insert($data, $ids);
			return $status ? $ids["action_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("mact_action", array(
					"name" => $data["name"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "ActionService.insertAction", $data, $options);
	}
	
	/**
	 * @param (name=data[action_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=100)
	 */
	public function updateAction($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			return $b->callUpdate("module/action", "update_action", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Action = $this->getActionHbnObj($b, $options);
			return $Action->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mact_action", array(
					"name" => $data["name"], 
					"modified_date" => $data["modified_date"]
				), array(
					"action_id" => $data["action_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "ActionService.updateAction", $data, $options);
	}
	
	/**
	 * @param (name=data[action_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteAction($data) {
		$action_id = $data["action_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/action", "delete_action", array("action_id" => $action_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Action = $this->getActionHbnObj($b, $options);
			return $Action->delete($action_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mact_action", array("action_id" => $action_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "ActionService.deleteAction", $data, $options);
	}
	
	/**
	 * @param (name=data[action_id], type=bigint, not_null=1, length=19)  
	 */
	public function getAction($data) {
		$action_id = $data["action_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/action", "get_action", array("action_id" => $action_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Action = $this->getActionHbnObj($b, $options);
			return $Action->findById($action_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mact_action", null, array("action_id" => $action_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "ActionService.getAction", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][action_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=100)
	 */
	public function getActionsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/action", "get_actions_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Action = $this->getActionHbnObj($b, $options);
				return $Action->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mact_action", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/action", "ActionService.getActionsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][action_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=100)
	 */
	public function countActionsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/action", "count_actions_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Action = $this->getActionHbnObj($b, $options);
				return $Action->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mact_action", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/action", "ActionService.countActionsByConditions", $data, $options);
		}
	}
	
	public function getAllActions($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/action", "get_all_actions", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Action = $this->getActionHbnObj($b, $options);
			return $Action->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mact_action", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "ActionService.getAllActions", null, $options);
	}
	
	public function countAllActions($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/action", "count_all_actions", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Action = $this->getActionHbnObj($b, $options);
			return $Action->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mact_action", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/action", "ActionService.countAllActions", null, $options);
	}
	
	/**
	 * @param (name=data[action_ids], type=mixed, not_null=1)  
	 */
	public function getActionsByIds($data) {
		$action_ids = $data["action_ids"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($action_ids) {
			$action_ids_str = "";//just in case the user tries to hack the sql query. By default all action_id should be numeric.
			$action_ids = is_array($action_ids) ? $action_ids : array($action_ids);
			foreach ($action_ids as $action_id) 
				$action_ids_str .= ($action_ids_str ? ", " : "") . "'" . addcslashes($action_id, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) 
				return $b->callSelect("module/action", "get_actions_by_ids", array("action_ids" => $action_ids_str), $options);
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Action = $this->getActionHbnObj($b, $options);
				$conditions = array("action_id" => array("operator" => "in", "value" => $action_ids));
				return $Action->find(array("conditions" => $conditions), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				return $b->findObjects("mact_action", null, array("action_id" => array("operator" => "in", "value" => $action_ids)), $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/action", "ActionService.getActionsByIds", $data, $options);
		}
	}
}
?>
