<?php
namespace Module\User;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class UserTypeService extends \soa\CommonService {
	private $UserType;
	
	private function getUserTypeHbnObj($b, $options) {
		if (!$this->UserType)
			$this->UserType = $b->callObject("module/user", "UserType", $options);
		
		return $this->UserType;
	}
	
	/**
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function insertUserType($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			if ($data["user_type_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$status = $b->callInsert("module/user", "insert_user_type_with_ai_pk", $data, $options);
				return $status ? $data["user_type_id"] : $status;
			}
			
			$status = $b->callInsert("module/user", "insert_user_type", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			if (!$data["user_type_id"])
				unset($data["user_type_id"]);
			
			$UserType = $this->getUserTypeHbnObj($b, $options);
			$status = $UserType->insert($data, $ids);
			return $status ? $ids["user_type_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$attributes = array(
				"name" => $data["name"], 
				"created_date" => $data["created_date"], 
				"modified_date" => $data["modified_date"]
			);
			
			if ($data["user_type_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$attributes["user_type_id"] = $data["user_type_id"];
			}
			
			$status = $b->insertObject("mu_user_type", $attributes, $options);
			return $status ? ($data["user_type_id"] ? $data["user_type_id"] : $b->getInsertedId($options)) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserTypeService.insertUserType", $data, $options);
	}
	
	/**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function updateUserType($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			return $b->callUpdate("module/user", "update_user_type", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserType = $this->getUserTypeHbnObj($b, $options);
			return $UserType->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mu_user_type", array(
					"name" => $data["name"],
					"modified_date" => $data["modified_date"]
				), array(
					"user_type_id" => $data["user_type_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserTypeService.updateUserType", $data, $options);
	}
	
	/**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteUserType($data) {
		$user_type_id = $data["user_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/user", "delete_user_type", array("user_type_id" => $user_type_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserType = $this->getUserTypeHbnObj($b, $options);
			return $UserType->delete($user_type_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_type", array("user_type_id" => $user_type_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserTypeService.deleteUserType", $data, $options);
	}
	
	/**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)  
	 */
	public function getUserType($data) {
		$user_type_id = $data["user_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_user_type", array("user_type_id" => $user_type_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserType = $this->getUserTypeHbnObj($b, $options);
			return $UserType->findById($user_type_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_user_type", null, array("user_type_id" => $user_type_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserTypeService.getUserType", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][user_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function getUserTypesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/user", "get_user_types_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserType = $this->getUserTypeHbnObj($b, $options);
				return $UserType->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mu_user_type", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/user", "UserTypeService.getUserTypesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][user_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function countUserTypesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "count_user_types_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserType = $this->getUserTypeHbnObj($b, $options);
				return $UserType->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mu_user_type", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/user", "UserTypeService.countUserTypesByConditions", $data, $options);
		}
	}
	
	public function getAllUserTypes($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callSelect("module/user", "get_all_user_types", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserType = $this->getUserTypeHbnObj($b, $options);
			return $UserType->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_type", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserTypeService.getAllUserTypes", $data, $options);
	}
	
	public function countAllUserTypes($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "count_all_user_types", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserType = $this->getUserTypeHbnObj($b, $options);
			return $UserType->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mu_user_type", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserTypeService.countAllUserTypes", $data, $options);
	}
}
?>
