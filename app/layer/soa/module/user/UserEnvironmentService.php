<?php
namespace Module\User;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class UserEnvironmentService extends \soa\CommonService {
	private $UserEnvironment;
	
	private function getUserEnvironmentHbnObj($b, $options) {
		if (!$this->UserEnvironment)
			$this->UserEnvironment = $b->callObject("module/user", "UserEnvironment", $options);
		
		return $this->UserEnvironment;
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[environment_id], type=bigint, not_null=1, length=19)
	 */
	public function insertUserEnvironment($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callInsert("module/user", "insert_user_environment", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserEnvironment = $this->getUserEnvironmentHbnObj($b, $options);
			return $UserEnvironment->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("mu_user_environment", array(
					"user_id" => $data["user_id"], 
					"environment_id" => $data["environment_id"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserEnvironmentService.insertUserEnvironment", $data, $options);
	}
	
	/**
	 * @param (name=data[new_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_environment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_environment_id], type=bigint, not_null=1, length=19)
	 */
	public function updateUserEnvironment($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/user", "update_user_environment", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserEnvironment = $this->getUserEnvironmentHbnObj($b, $options);
			return $UserEnvironment->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mu_user_environment", array(
					"user_id" => $data["new_user_id"], 
					"environment_id" => $data["new_environment_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"user_id" => $data["old_user_id"], 
					"environment_id" => $data["old_environment_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserEnvironmentService.updateUserEnvironment", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[environment_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserEnvironment($data) {
		$user_id = $data["user_id"];
		$environment_id = $data["environment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/user", "delete_user_environment", array("user_id" => $user_id, "environment_id" => $environment_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserEnvironment = $this->getUserEnvironmentHbnObj($b, $options);
			return $UserEnvironment->delete(array("user_id" => $user_id, "environment_id" => $environment_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_environment", array("user_id" => $user_id, "environment_id" => $environment_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserEnvironmentService.deleteUserEnvironment", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][environment_id], type=bigint|array, length=19)
	 */
	public function deleteUserEnvironmentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/user", "delete_user_environments_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserEnvironment = $this->getUserEnvironmentHbnObj($b, $options);
				return $UserEnvironment->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]));
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("mu_user_environment", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserEnvironmentService.deleteUserEnvironmentsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[environment_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserEnvironment($data) {
		$user_id = $data["user_id"];
		$environment_id = $data["environment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_user_environment", array("user_id" => $user_id, "environment_id" => $environment_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserEnvironment = $this->getUserEnvironmentHbnObj($b, $options);
			return $UserEnvironment->findById(array("user_id" => $user_id, "environment_id" => $environment_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_user_environment", null, array("user_id" => $user_id, "environment_id" => $environment_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserEnvironmentService.getUserEnvironment", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][environment_id], type=bigint|array, length=19)
	 */
	public function getUserEnvironmentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/user", "get_user_environments_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserEnvironment = $this->getUserEnvironmentHbnObj($b, $options);
				return $UserEnvironment->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mu_user_environment", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserEnvironmentService.getUserEnvironmentsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][environment_id], type=bigint|array, length=19)
	 */
	public function countUserEnvironmentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "count_user_environments_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserEnvironment = $this->getUserEnvironmentHbnObj($b, $options);
				return $UserEnvironment->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mu_user_environment", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserEnvironmentService.countUserEnvironmentsByConditions", $data, $options);
		}
	}
	
	public function getAllUserEnvironments($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/user", "get_all_user_environments", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserEnvironment = $this->getUserEnvironmentHbnObj($b, $options);
			return $UserEnvironment->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_environment", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserEnvironmentService.getAllUserEnvironments", null, $options);
	}
	
	public function countAllUserEnvironments($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "count_all_user_environments", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserEnvironment = $this->getUserEnvironmentHbnObj($b, $options);
			return $UserEnvironment->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mu_user_environment", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserEnvironmentService.countAllUserEnvironments", null, $options);
	}
}
?>
