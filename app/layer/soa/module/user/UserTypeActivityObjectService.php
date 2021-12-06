<?php
namespace Module\User;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/UserTypeActivityObjectDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class UserTypeActivityObjectService extends \soa\CommonService {
	private $UserTypeActivityObject;
	
	private function getUserTypeActivityObjectHbnObj($b, $options) {
		if (!$this->UserTypeActivityObject)
			$this->UserTypeActivityObject = $b->callObject("module/user", "UserTypeActivityObject", $options);
		
		return $this->UserTypeActivityObject;
	}
	
	/**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function insertUserTypeActivityObject($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callInsert("module/user", "insert_user_type_activity_object", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			return $UserTypeActivityObject->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("mu_user_type_activity_object", array(
					"user_type_id" => $data["user_type_id"], 
					"activity_id" => $data["activity_id"], 
					"object_type_id" => $data["object_type_id"],  
					"object_id" => $data["object_id"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.insertUserTypeActivityObject", $data, $options);
	}
	
	/**
	 * @param (name=data[new_user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_activity_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_activity_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function updateUserTypeActivityObject($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/user", "update_user_type_activity_object", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			return $UserTypeActivityObject->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mu_user_type_activity_object", array(
					"user_type_id" => $data["new_user_type_id"], 
					"activity_id" => $data["new_activity_id"], 
					"object_type_id" => $data["new_object_type_id"],  
					"object_id" => $data["new_object_id"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["_modified_date"]
				), array(
					"user_type_id" => $data["old_user_type_id"], 
					"activity_id" => $data["old_activity_id"], 
					"object_type_id" => $data["old_object_type_id"],  
					"object_id" => $data["old_object_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.updateUserTypeActivityObject", $data, $options);
	}
	
	/**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserTypeActivityObject($data) {
		$user_type_id = $data["user_type_id"];
		$activity_id = $data["activity_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/user", "delete_user_type_activity_object", array("user_type_id" => $user_type_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			return $UserTypeActivityObject->delete(array("user_type_id" => $user_type_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_type_activity_object", array("user_type_id" => $user_type_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.deleteUserTypeActivityObject", $data, $options);
	}
	
	/**
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserTypeActivityObjectsByActivityIdAndObjectId($data) {
		$activity_id = $data["activity_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/user", "delete_user_type_activity_object_by_activity_id_and_object_id", array("activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			return $UserTypeActivityObject->delete(array("activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_type_activity_object", array("activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.deleteUserTypeActivityObject", $data, $options);
	}
	
	/**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserTypeActivityObjectsByUserTypeId($data) {
		$user_type_id = $data["user_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/user", "delete_user_type_activity_objects_by_user_type_id", array("user_type_id" => $user_type_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			$conditions = array("user_type_id" => $user_type_id);
			return $UserTypeActivityObject->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_type_activity_object", array("user_type_id" => $user_type_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.deleteUserTypeActivityObjectsByUserTypeId", $data, $options);
	}
	
	/**
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserTypeActivityObjectsByActivityId($data) {
		$activity_id = $data["activity_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/user", "delete_user_type_activity_objects_by_activity_id", array("activity_id" => $activity_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			$conditions = array("activity_id" => $activity_id);
			return $UserTypeActivityObject->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_type_activity_object", array("activity_id" => $activity_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.deleteUserTypeActivityObjectsByActivityId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserTypeActivityObjectsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/user", "delete_user_type_activity_objects_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $UserTypeActivityObject->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_type_activity_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.deleteUserTypeActivityObjectsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserTypeActivityObject($data) {
		$user_type_id = $data["user_type_id"];
		$activity_id = $data["activity_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_user_type_activity_object", array("user_type_id" => $user_type_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			return $UserTypeActivityObject->findById(array("user_type_id" => $user_type_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_user_type_activity_object", null, array("user_type_id" => $user_type_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.getUserTypeActivityObject", $data, $options);
	}
	
	/**
	 * @param (name=data[user_type_ids], type=mixed, not_null=1)
	 */
	public function getUserTypeActivityObjectsByUserTypeIds($data) {
		$user_type_ids = $data["user_type_ids"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($user_type_ids) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
				$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
				foreach ($user_type_ids as $user_type_id) 
					if (is_numeric($user_type_id))
						$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
				
				return $b->callSelect("module/user", "get_user_type_activity_objects_by_user_type_ids", array("user_type_ids" => $user_type_ids_str), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
				$conditions = array("user_type_id" => array("operator" => "in", "value" => $user_type_ids));
				return $UserTypeActivityObject->find(array("conditions" => $conditions), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				return $b->findObjects("mu_user_type_activity_object", null, array("user_type_id" => array("operator" => "in", "value" => $user_type_ids)), $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.getUserTypeActivityObjectsByUserTypeIds", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserTypeActivityObjectsByUserTypeId($data) {
		$user_type_id = $data["user_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/user", "get_user_type_activity_objects_by_user_type_id", array("user_type_id" => $user_type_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			$conditions = array("user_type_id" => $user_type_id);
			return $UserTypeActivityObject->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_type_activity_object", null, array("user_type_id" => $user_type_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.getUserTypeActivityObjectsByUserTypeId", $data, $options);
	}
	
	/**
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserTypeActivityObjectsByActivityId($data) {
		$activity_id = $data["activity_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callSelect("module/user", "get_user_type_activity_objects_by_activity_id", array("activity_id" => $activity_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			$conditions = array("activity_id" => $activity_id);
			return $UserTypeActivityObject->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_type_activity_object", null, array("activity_id" => $activity_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.getUserTypeActivityObjectsByActivityId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserTypeActivityObjectsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/user", "get_user_type_activity_objects_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $UserTypeActivityObject->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_type_activity_object", null, array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.getUserTypeActivityObjectsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][user_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][activity_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function getUserTypeActivityObjectsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/user", "get_user_type_activity_objects_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
				return $UserTypeActivityObject->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mu_user_type_activity_object", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.getUserTypeActivityObjectsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][user_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][activity_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function countUserTypeActivityObjectsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "count_user_type_activity_objects_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
				return $UserTypeActivityObject->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mu_user_type_activity_object", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.countUserTypeActivityObjectsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[conditions][user_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][activity_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function getUserTypeActivityObjectsByUserIdAndConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "utao");
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/user", "get_user_type_activity_objects_by_user_id_and_conditions", array("user_id" => $data["user_id"], "conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "utao");
				$cond = $cond ? $cond : "1=1";
				return $UserTypeActivityObject->callSelect("get_user_type_activity_objects_by_user_id_and_conditions", array("user_id" => $data["user_id"], "conditions" => $cond), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "utao");
				$cond = $cond ? $cond : "1=1";
				$sql = UserTypeActivityObjectDBDAOServiceUtil::get_user_type_activity_objects_by_user_id_and_conditions(array("user_id" => $data["user_id"], "conditions" => $cond));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.getUserTypeActivityObjectsByUserIdAndConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[conditions][user_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][activity_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function countUserTypeActivityObjectsByUserIdAndConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "utao");
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "count_user_type_activity_objects_by_user_id_and_conditions", array("user_id" => $data["user_id"], "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "utao");
				$cond = $cond ? $cond : "1=1";
				$result = $UserTypeActivityObject->callSelect("count_user_type_activity_objects_by_user_id_and_conditions", array("user_id" => $data["user_id"], "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "utao");
				$cond = $cond ? $cond : "1=1";
				$sql = UserTypeActivityObjectDBDAOServiceUtil::count_user_type_activity_objects_by_user_id_and_conditions(array("user_id" => $data["user_id"], "conditions" => $cond));
				
				$result = $b->getSQL($sql, $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.countUserTypeActivityObjectsByUserIdAndConditions", $data, $options);
		}
	}
	
	public function getAllUserTypeActivityObjects($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callSelect("module/user", "get_all_user_type_activity_objects", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			return $UserTypeActivityObject->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_type_activity_object", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.getAllUserTypeActivityObjects", $data, $options);
	}
	
	public function countAllUserTypeActivityObjects($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "count_all_user_type_activity_objects", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserTypeActivityObject = $this->getUserTypeActivityObjectHbnObj($b, $options);
			return $UserTypeActivityObject->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mu_user_type_activity_object", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserTypeActivityObjectService.countAllUserTypeActivityObjects", $data, $options);
	}
}
?>
