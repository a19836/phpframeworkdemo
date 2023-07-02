<?php
namespace Module\User;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class ActivityService extends \soa\CommonService {
	private $Activity;
	
	private function getActivityHbnObj($b, $options) {
		if (!$this->Activity)
			$this->Activity = $b->callObject("module/user", "Activity", $options);
		
		return $this->Activity;
	}
	
	/**
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function insertActivity($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			if ($data["activity_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$status = $b->callInsert("module/user", "insert_activity_with_ai_pk", $data, $options);
				return $status ? $data["activity_id"] : $status;
			}
			
			$status = $b->callInsert("module/user", "insert_activity", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			if (!$data["activity_id"])
				unset($data["activity_id"]);
			
			$Activity = $this->getActivityHbnObj($b, $options);
			$status = $Activity->insert($data, $ids);
			return $status ? $ids["activity_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$attributes = array(
				"name" => $data["name"], 
				"created_date" => $data["created_date"], 
				"modified_date" => $data["modified_date"]
			);
			
			if ($data["activity_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$attributes["activity_id"] = $data["activity_id"];
			}
			
			$status = $b->insertObject("mu_activity", $attributes, $options);
			return $status ? ($data["activity_id"] ? $data["activity_id"] : $b->getInsertedId($options)) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "ActivityService.insertActivity", $data, $options);
	}
	
	/**
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function updateActivity($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			return $b->callUpdate("module/user", "update_activity", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Activity = $this->getActivityHbnObj($b, $options);
			return $Activity->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mu_activity", array(
					"name" => $data["name"],
					"modified_date" => $data["modified_date"]
				), array(
					"activity_id" => $data["activity_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "ActivityService.updateActivity", $data, $options);
	}
	
	/**
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteActivity($data) {
		$activity_id = $data["activity_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/user", "delete_activity", array("activity_id" => $activity_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Activity = $this->getActivityHbnObj($b, $options);
			return $Activity->delete($activity_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_activity", array("activity_id" => $activity_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "ActivityService.deleteActivity", $data, $options);
	}
	
	/**
	 * @param (name=data[activity_id], type=bigint, not_null=1, length=19)  
	 */
	public function getActivity($data) {
		$activity_id = $data["activity_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_activity", array("activity_id" => $activity_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Activity = $this->getActivityHbnObj($b, $options);
			return $Activity->findById($activity_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_activity", null, array("activity_id" => $activity_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "ActivityService.getActivity", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][activity_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function getActivitiesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/user", "get_activities_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Activity = $this->getActivityHbnObj($b, $options);
				return $Activity->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mu_activity", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/user", "ActivityService.getActivitiesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][activity_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function countActivitiesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "count_activities_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Activity = $this->getActivityHbnObj($b, $options);
				return $Activity->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mu_activity", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "ActivityService.countActivitiesByConditions", $data, $options);
		}
	}
	
	public function getAllActivities($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/user", "get_all_activities", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Activity = $this->getActivityHbnObj($b, $options);
			return $Activity->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mu_activity", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "ActivityService.getAllActivities", $data, $options);
	}
	
	public function countAllActivities($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "count_all_activities", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Activity = $this->getActivityHbnObj($b, $options);
			return $Activity->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mu_activity", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "ActivityService.countAllActivities", $data, $options);
	}
}
?>
