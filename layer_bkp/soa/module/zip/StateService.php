<?php
namespace Module\Zip;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/StateDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class StateService extends \soa\CommonService {
	private $State;
	
	private function getStateHbnObj($b, $options) {
		if (!$this->State)
			$this->State = $b->callObject("module/zip", "State", $options);
		
		return $this->State;
	}
	
	/**
	 * @param (name=data[state_id], type=bigint, length=19)
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function insertState($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			if ($data["state_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$status = $b->callInsert("module/zip", "insert_state_with_ai_pk", $data, $options);
				return $status ? $data["state_id"] : $status;
			}
			
			$status = $b->callInsert("module/zip", "insert_state", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			if (!$data["state_id"]) 
				unset($data["state_id"]);
			
			$State = $this->getStateHbnObj($b, $options);
			$status = $State->insert($data, $ids);
			return $status ? $ids["state_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$attributes = array(
				"country_id" => $data["country_id"], 
				"name" => $data["name"],
				"created_date" => $data["created_date"], 
				"modified_date" => $data["modified_date"]
			);
			
			if ($data["state_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$attributes["state_id"] = $data["state_id"];
			}
			
			$status = $b->insertObject("mz_state", $attributes, $options);
			return $status ? ($data["state_id"] ? $data["state_id"] : $b->getInsertedId($options)) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "StateService.insertState", $data, $options);
	}
	
	/**
	 * @param (name=data[state_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function updateState($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			return $b->callUpdate("module/zip", "update_state", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$State = $this->getStateHbnObj($b, $options);
			return $State->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mz_state", array(
					"country_id" => $data["country_id"], 
					"name" => $data["name"],
					"modified_date" => $data["modified_date"]
				), array(
					"state_id" => $data["state_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "StateService.updateState", $data, $options);
	}
	
	/**
	 * @param (name=data[state_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteState($data) {
		$state_id = $data["state_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/zip", "delete_state", array("state_id" => $state_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$State = $this->getStateHbnObj($b, $options);
			return $State->delete($state_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mz_state", array("state_id" => $state_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "StateService.deleteState", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][state_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function deleteStatesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/zip", "delete_states_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$State = $this->getStateHbnObj($b, $options);
				return $State->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("mz_state", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "StateService.deleteStatesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[state_id], type=bigint, not_null=1, length=19)
	 */
	public function getState($data) {
		$state_id = $data["state_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/zip", "get_state", array("state_id" => $state_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$State = $this->getStateHbnObj($b, $options);
			$result = $State->callSelect("get_state", array("state_id" => $state_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mz_state", null, array("state_id" => $state_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "StateService.getState", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][state_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function getStatesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/zip", "get_states_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$State = $this->getStateHbnObj($b, $options);
				return $State->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mz_state", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "StateService.getStatesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][state_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function countStatesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/zip", "count_states_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$State = $this->getStateHbnObj($b, $options);
				return $State->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mz_state", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "StateService.countStatesByConditions", $data, $options);
		}
	}
	
	public function getAllStates($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/zip", "get_all_states", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$State = $this->getStateHbnObj($b, $options);
			return $State->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mz_state", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "StateService.getAllStates", null, $options);
	}
	
	public function countAllStates($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/zip", "count_all_states", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$State = $this->getStateHbnObj($b, $options);
			return $State->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mz_state", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "StateService.countAllStates", null, $options);
	}
	
	public function getFullStates($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/zip", "get_full_states", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$State = $this->getStateHbnObj($b, $options);
			return $State->callSelect("get_full_states", null, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = StateDBDAOServiceUtil::get_full_states();
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "StateService.getFullStates", null, $options);
	}
}
?>
