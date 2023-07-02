<?php
namespace Module\User;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class UserUserTypeService extends \soa\CommonService {
	private $UserUserType;
	
	private function getUserUserTypeHbnObj($b, $options) {
		if (!$this->UserUserType)
			$this->UserUserType = $b->callObject("module/user", "UserUserType", $options);
		
		return $this->UserUserType;
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 */
	public function insertUserUserType($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callInsert("module/user", "insert_user_user_type", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserUserType = $this->getUserUserTypeHbnObj($b, $options);
			return $UserUserType->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("mu_user_user_type", array(
				"user_id" => $data["user_id"], 
				"user_type_id" => $data["user_type_id"], 
				"created_date" => $data["created_date"], 
				"modified_date" => $data["modified_date"]
			), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserUserTypeService.insertUserUserType", $data, $options);
	}
	
	/**
	 * @param (name=data[old_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_user_type_id], type=bigint, not_null=1, length=19)
	 */
	public function updateUserUserType($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/user", "update_user_user_type", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserUserType = $this->getUserUserTypeHbnObj($b, $options);
			return $UserUserType->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mu_user_user_type", array(
					"user_id" => $data["new_user_id"], 
					"user_type_id" => $data["new_user_type_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"user_id" => $data["old_user_id"], 
					"user_type_id" => $data["old_user_type_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserUserTypeService.updateUserUserType", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteUserUserType($data) {
		$user_id = $data["user_id"];
		$user_type_id = $data["user_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/user", "delete_user_user_type", array("user_id" => $user_id, "user_type_id" => $user_type_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserUserType = $this->getUserUserTypeHbnObj($b, $options);
			$conditions = array("user_id" => $user_id, "user_type_id" => $user_type_id);
			return $UserUserType->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_user_type", array("user_id" => $user_id, "user_type_id" => $user_type_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserUserTypeService.deleteUserUserType", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][user_type_id], type=bigint|array, length=19)
	 */
	public function deleteUserUserTypesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/user", "delete_user_user_types_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserUserType = $this->getUserUserTypeHbnObj($b, $options);
				return $UserUserType->deleteByConditions($data);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("mu_user_user_type", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/user", "UserUserTypeService.deleteUserUserTypesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)  
	 */
	public function getUserUserType($data) {
		$user_id = $data["user_id"];
		$user_type_id = $data["user_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_user_user_type", array("user_id" => $user_id, "user_type_id" => $user_type_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserUserType = $this->getUserUserTypeHbnObj($b, $options);
			return $UserUserType->findById(array("user_id" => $user_id, "user_type_id" => $user_type_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_user_user_type", null, array("user_id" => $user_id, "user_type_id" => $user_type_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserUserTypeService.getUserUserType", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][user_type_id], type=bigint|array, length=19)
	 */
	public function getUserUserTypesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/user", "get_user_user_types_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserUserType = $this->getUserUserTypeHbnObj($b, $options);
				return $UserUserType->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mu_user_user_type", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserUserTypeService.getUserUserTypesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][user_type_id], type=bigint|array, length=19)
	 */
	public function countUserUserTypesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "count_user_user_types_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserUserType = $this->getUserUserTypeHbnObj($b, $options);
				return $UserUserType->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mu_user_user_type", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserUserTypeService.countUserUserTypesByConditions", $data, $options);
		}
	}
	
	public function getAllUserUserTypes($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/user", "get_all_user_user_types", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserUserType = $this->getUserUserTypeHbnObj($b, $options);
			return $UserUserType->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_user_type", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserUserTypeService.getAllUserUserTypes", $data, $options);
	}
	
	public function countAllUserUserTypes($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "count_all_user_user_types", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserUserType = $this->getUserUserTypeHbnObj($b, $options);
			return $UserUserType->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mu_user_user_type", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserUserTypeService.countAllUserUserTypes", $data, $options);
	}
}
?>
