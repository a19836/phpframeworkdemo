<?php
namespace Module\User;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class UserActivityObjectService extends \soa\CommonService {
	private $UserActivityObject;
	
	private function getUserActivityObjectHbnObj($b, $options) {
		if (!$this->UserActivityObject)
			$this->UserActivityObject = $b->callObject("module/user", "UserActivityObject", $options);
		
		return $this->UserActivityObject;
	}
	
	/**
	 * @param (name=data[thread_id], type=varchar, not_null=1, min_length=1, max_length=19)
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[time], type=bigint, not_null=1, length=19)
	 */
	public function insertUserActivityObject($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["thread_id"] = addcslashes($data["thread_id"], "\\'");
			$data["extra"] = addcslashes($data["extra"], "\\'");
			
			return $b->callInsert("module/user", "insert_user_activity_object", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			return $UserActivityObject->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("mu_user_activity_object", array(
					"thread_id" => $data["thread_id"], 
					"user_id" => $data["user_id"], 
					"activity_id" => $data["activity_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"time" => $data["time"], 
					"extra" => $data["extra"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.insertUserActivityObject", $data, $options);
	}
	
	/**
	 * @param (name=data[thread_id], type=varchar, not_null=1, min_length=1, max_length=19)
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[time], type=bigint, not_null=1, length=19)
	 */
	public function updateUserActivityObject($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["thread_id"] = addcslashes($data["thread_id"], "\\'");
			$data["extra"] = addcslashes($data["extra"], "\\'");
			
			return $b->callUpdate("module/user", "update_user_activity_object", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			return $UserActivityObject->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mu_user_activity_object", array(
					"extra" => $data["extra"], 
					"modified_date" => $data["modified_date"]
				), array(
					"thread_id" => $data["thread_id"], 
					"user_id" => $data["user_id"], 
					"activity_id" => $data["activity_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"time" => $data["time"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.updateUserActivityObject", $data, $options);
	}
	
	/**
	 * @param (name=data[thread_id], type=varchar, not_null=1, min_length=1, max_length=19)
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[time], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserActivityObject($data) {
		$thread_id = $data["thread_id"];
		$user_id = $data["user_id"];
		$activity_id = $data["activity_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$time = $data["time"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$thread_id = addcslashes($thread_id, "\\'");
			
			return $b->callDelete("module/user", "delete_user_activity_object", array("thread_id" => $thread_id, "user_id" => $user_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			return $UserActivityObject->delete(array("thread_id" => $thread_id, "user_id" => $user_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_activity_object", array("thread_id" => $thread_id, "user_id" => $user_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.deleteUserActivityObject", $data, $options);
	}
	
	/**
	 * @param (name=data[thread_id], type=varchar, not_null=1, min_length=1, max_length=19)
	 */
	public function deleteUserActivityObjectsByThreadId($data) {
		$thread_id = $data["thread_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$thread_id = addcslashes($thread_id, "\\'");
			
			return $b->callDelete("module/user", "delete_user_activity_objects_by_thread_id", array("thread_id" => $thread_id), $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			$conditions = array("thread_id" => $thread_id);
			return $UserActivityObject->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_activity_object", array("thread_id" => $thread_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.deleteUserActivityObjectsByThreadId", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserActivityObjectsByUserId($data) {
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/user", "delete_user_activity_objects_by_user_id", array("user_id" => $user_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			$conditions = array("user_id" => $user_id);
			return $UserActivityObject->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_activity_object", array("user_id" => $user_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.deleteUserActivityObjectsByUserId", $data, $options);
	}
	
	/**
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserActivityObjectsByActivityId($data) {
		$activity_id = $data["activity_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/user", "delete_user_activity_objects_by_activity_id", array("activity_id" => $activity_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			$conditions = array("activity_id" => $activity_id);
			return $UserActivityObject->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_activity_object", array("activity_id" => $activity_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.deleteUserActivityObjectsByActivityId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserActivityObjectsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/user", "delete_user_activity_objects_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $UserActivityObject->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_activity_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.deleteUserActivityObjectsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[thread_id], type=varchar, not_null=1, min_length=1, max_length=19)
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[time], type=bigint, not_null=1, length=19)
	 */
	public function getUserActivityObject($data) {
		$thread_id = $data["thread_id"];
		$user_id = $data["user_id"];
		$activity_id = $data["activity_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$time = $data["time"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$thread_id = addcslashes($thread_id, "\\'");
			
			$result = $b->callSelect("module/user", "get_user_activity_object", array("thread_id" => $thread_id, "user_id" => $user_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			return $UserActivityObject->findById(array("thread_id" => $thread_id, "user_id" => $user_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_user_activity_object", null, array("thread_id" => $thread_id, "user_id" => $user_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.getUserActivityObject", $data, $options);
	}
	
	/**
	 * @param (name=data[thread_id], type=varchar, not_null=1, min_length=1, max_length=19)
	 */
	public function getUserActivityObjectsByThreadId($data) {
		$thread_id = $data["thread_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$thread_id = addcslashes($thread_id, "\\'");
			
			return $b->callSelect("module/user", "get_user_activity_objects_by_thread_id", array("thread_id" => $thread_id), $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			$conditions = array("thread_id" => $thread_id);
			return $UserActivityObject->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_activity_object", null, array("thread_id" => $thread_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.getUserActivityObjectsByThreadId", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserActivityObjectsByUserId($data) {
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/user", "get_user_activity_objects_by_user_id", array("user_id" => $user_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			$conditions = array("user_id" => $user_id);
			return $UserActivityObject->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_activity_object", null, array("user_id" => $user_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.getUserActivityObjectsByUserId", $data, $options);
	}
	
	/**
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserActivityObjectsByActivityId($data) {
		$activity_id = $data["activity_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/user", "get_user_activity_objects_by_activity_id", array("activity_id" => $activity_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			$conditions = array("activity_id" => $activity_id);
			return $UserActivityObject->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_activity_object", null, array("activity_id" => $activity_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.getUserActivityObjectsByActivityId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserActivityObjectsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/user", "get_user_activity_objects_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $UserActivityObject->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_activity_object", null, array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.getUserActivityObjectsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][thread_id], type=varchar|array, length=19)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][activity_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][time], type=bigint|array, length=19)
	 */
	public function getUserActivityObjectsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/user", "get_user_activity_objects_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
				return $UserActivityObject->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mu_user_activity_object", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserActivityObjectService.getUserActivityObjectsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][thread_id], type=varchar|array, length=19)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][activity_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][time], type=bigint|array, length=19)
	 */
	public function countUserActivityObjectsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "count_user_activity_objects_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
				return $UserActivityObject->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mu_user_activity_object", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserActivityObjectService.countUserActivityObjectsByConditions", $data, $options);
		}
	}
	
	public function getAllUserActivityObjects($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/user", "get_all_user_activity_objects", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			return $UserActivityObject->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_user_activity_object", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.getAllUserActivityObjects", $data, $options);
	}
	
	public function countAllUserActivityObjects($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "count_all_user_activity_objects", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserActivityObject = $this->getUserActivityObjectHbnObj($b, $options);
			return $UserActivityObject->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mu_user_activity_object", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserActivityObjectService.countAllUserActivityObjects", $data, $options);
	}
}
?>
