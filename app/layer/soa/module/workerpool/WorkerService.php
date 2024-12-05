<?php
namespace Module\WorkerPool;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/WorkerDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class WorkerService extends \soa\CommonService {
	private $Worker;
	
	private function getWorkerHbnObj($b, $options) {
		if (!$this->Worker) 
			$this->Worker = $b->callObject("module/workerpool", "Worker", $options);
		
		return $this->Worker;
	}
	
	/**
	 * @param (name=data[class], type=varchar, not_null=1, min_length=1, max_length=2048)
	 * @param (name=data[status], type=tinyint, default=0)
	 * @param (name=data[thread_id], type=varchar, length=100)
	 * @param (name=data[begin_time], type=bigint, default=0, length=19)
	 * @param (name=data[end_time], type=bigint, default=0, length=19)
	 * @param (name=data[failed_attempts], type=tinyint, default=0)
	 */
	public function insertWorker($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["class"] = addcslashes($data["class"], "\\'");
			$data["args"] = isset($data["args"]) ? addcslashes($data["args"], "\\'") : "";
			$data["thread_id"] = isset($data["thread_id"]) ? addcslashes($data["thread_id"], "\\'") : "";
			$data["description"] = isset($data["description"]) ? addcslashes($data["description"], "\\'") : "";
			
			$status = $b->callInsert("module/workerpool", "insert_worker", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			$ids = null;
			$status = $Worker->insert($data, $ids);
			return $status ? (isset($ids["worker_id"]) ? $ids["worker_id"] : null) : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("mwp_worker", array(
					"class" => $data["class"], 
					"args" => isset($data["args"]) ? $data["args"] : null, 
					"status" => isset($data["status"]) ? $data["status"] : null, 
					"thread_id" => isset($data["thread_id"]) ? $data["thread_id"] : null, 
					"begin_time" => isset($data["begin_time"]) ? $data["begin_time"] : null, 
					"end_time" => isset($data["end_time"]) ? $data["end_time"] : null, 
					"failed_attempts" => isset($data["failed_attempts"]) ? $data["failed_attempts"] : null, 
					"description" => isset($data["description"]) ? $data["description"] : null, 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.insertWorker", $data, $options);
	}
	
	/**
	 * @param (name=data[worker_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[class], type=varchar, not_null=1, min_length=1, max_length=2048)
	 * @param (name=data[status], type=tinyint, default=0)
	 * @param (name=data[thread_id], type=varchar, length=100)
	 * @param (name=data[begin_time], type=bigint, default=0, length=19)
	 * @param (name=data[end_time], type=bigint, default=0, length=19)
	 * @param (name=data[failed_attempts], type=tinyint, default=0)
	 */
	public function updateWorker($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["class"] = addcslashes($data["class"], "\\'");
			$data["args"] = isset($data["args"]) ? addcslashes($data["args"], "\\'") : "";
			$data["thread_id"] = isset($data["thread_id"]) ? addcslashes($data["thread_id"], "\\'") : "";
			$data["description"] = isset($data["description"]) ? addcslashes($data["description"], "\\'") : "";
			
			return $b->callUpdate("module/workerpool", "update_worker", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mwp_worker", array(
					"class" => $data["class"], 
					"args" => isset($data["args"]) ? $data["args"] : null, 
					"status" => isset($data["status"]) ? $data["status"] : null, 
					"thread_id" => isset($data["thread_id"]) ? $data["thread_id"] : null, 
					"begin_time" => isset($data["begin_time"]) ? $data["begin_time"] : null, 
					"end_time" => isset($data["end_time"]) ? $data["end_time"] : null, 
					"failed_attempts" => isset($data["failed_attempts"]) ? $data["failed_attempts"] : null, 
					"description" => isset($data["description"]) ? $data["description"] : null, 
					"modified_date" => $data["modified_date"]
				), array(
					"worker_id" => $data["worker_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.updateWorker", $data, $options);
	}
	
	/**  
	 * @param (name=data[maximum_failed_attempts], type=tinyint, not_null=1)
	 */
	public function updateFailedAndToParseWorkers($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/workerpool", "update_failed_and_to_parse_workers", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->callUpdate("update_failed_and_to_parse_workers", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = WorkerDBDAOServiceUtil::update_failed_and_to_parse_workers($data);
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.updateFailedAndToParseWorkers", $data, $options);
	}
	
	/**
	 * @param (name=data[maximum_failed_attempts], type=tinyint, not_null=1)
	 * @param (name=data[expiration_time], type=bigint, not_null=1, length=19)
	 */
	public function updateFailedAndExpiredWorkers($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/workerpool", "update_failed_and_expired_workers", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->callUpdate("update_failed_and_expired_workers", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = WorkerDBDAOServiceUtil::update_failed_and_expired_workers($data);
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.updateFailedAndExpiredWorkers", $data, $options);
	}
	
	/**
	 * @param (name=data[expiration_time], type=bigint, not_null=1, length=19)
	 */
	public function resetExpiredWorkers($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callUpdate("module/workerpool", "reset_expired_workers", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->callUpdate("reset_expired_workers", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = WorkerDBDAOServiceUtil::reset_expired_workers($data);
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.resetExpiredWorkers", $data, $options);
	}
	
	/**
	 * @param (name=data[worker_id], type=bigint, not_null=1, length=19) 
	 * @param (name=data[thread_id], type=varchar, not_null=1, length=100)
	 * @param (name=data[begin_time], type=bigint, not_null=1, length=19)
	 */
	public function updateThreadWorker($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callUpdate("module/workerpool", "update_thread_worker", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->callUpdate("update_thread_worker", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = WorkerDBDAOServiceUtil::update_thread_worker($data);
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.updateThreadWorker", $data, $options);
	}
	
	/**
	 * @param (name=data[worker_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[end_time], type=bigint, not_null=1, length=19)
	 */
	public function updateClosedWorker($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/workerpool", "update_closed_worker", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->callUpdate("update_closed_worker", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = WorkerDBDAOServiceUtil::update_closed_worker($data);
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.updateClosedWorker", $data, $options);
	}
	
	/**
	 * @param (name=data[worker_id], type=bigint, not_null=1, length=19)
	 */
	public function updateFailedWorker($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/workerpool", "update_failed_worker", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->callUpdate("update_failed_worker", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = WorkerDBDAOServiceUtil::update_failed_worker($data);
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.updateFailedWorker", $data, $options);
	}
	
	/**
	 * @param (name=data[worker_id], type=bigint, not_null=1, length=19)
	 */
	public function resetFailedWorker($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/workerpool", "reset_failed_worker", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->callUpdate("reset_failed_worker", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = WorkerDBDAOServiceUtil::reset_failed_worker($data);
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.resetFailedWorker", $data, $options);
	}
	
	/**
	 * @param (name=data[worker_id], type=bigint, not_null=1, length=19) 
	 */
	public function deleteWorker($data) {
		$worker_id = $data["worker_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/workerpool", "delete_worker", array("worker_id" => $worker_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->delete($worker_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mwp_worker", array("worker_id" => $worker_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/workerpool", "WorkerService.deleteWorker", $data, $options);
	}
	
	/**
	 * @param (name=data[worker_id], type=bigint, not_null=1, length=19)  
	 */
	public function getWorker($data) {
		$worker_id = $data["worker_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/workerpool", "get_worker", array("worker_id" => $worker_id), $options);
			return isset($result[0]) ? $result[0] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->findById($worker_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mwp_worker", null, array("worker_id" => $worker_id), $options);
			return isset($result[0]) ? $result[0] : null;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.getWorker", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][worker_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][class], type=varchar|array), length=2048)
	 * @param (name=data[conditions][status], type=tinyint|array)
	 * @param (name=data[conditions][thread_id], type=varchar|array, length=100)
	 * @param (name=data[conditions][begin_time], type=bigint|array, length=19)
	 * @param (name=data[conditions][end_time], type=bigint|array, length=19)
	 * @param (name=data[conditions][failed_attempts], type=tinyint|array)
	 */
	public function getWorkersByConditions($data) {
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/workerpool", "get_workers_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Worker = $this->getWorkerHbnObj($b, $options);
				return $Worker->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				return $b->findObjects("mwp_worker", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/workerpool", "WorkerService.getWorkersByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][worker_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][class], type=varchar|array), length=2048)
	 * @param (name=data[conditions][status], type=tinyint|array)
	 * @param (name=data[conditions][thread_id], type=varchar|array, length=100)
	 * @param (name=data[conditions][begin_time], type=bigint|array, length=19)
	 * @param (name=data[conditions][end_time], type=bigint|array, length=19)
	 * @param (name=data[conditions][failed_attempts], type=tinyint|array)
	 */
	public function countWorkersByConditions($data) {
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/workerpool", "count_workers_by_conditions", array("conditions" => $cond), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Worker = $this->getWorkerHbnObj($b, $options);
				return $Worker->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				return $b->countObjects("mwp_worker", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/workerpool", "WorkerService.countWorkersByConditions", $data, $options);
		}
	}
	
	public function getAllWorkers($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callSelect("module/workerpool", "get_all_workers", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mwp_worker", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.getAllWorkers", null, $options);
	}
	
	public function countAllWorkers($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/workerpool", "count_all_workers", null, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Worker = $this->getWorkerHbnObj($b, $options);
			return $Worker->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mwp_worker", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/workerpool", "WorkerService.countAllWorkers", null, $options);
	}
	
	/**
	 * @param (name=data[worker_ids], type=mixed, not_null=1)  
	 */
	public function getWorkersByIds($data) {
		$worker_ids = $data["worker_ids"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($worker_ids) {
			$worker_ids_str = "";//just in case the user tries to hack the sql query. By default all worker_id should be numeric.
			$worker_ids = is_array($worker_ids) ? $worker_ids : array($worker_ids);
			foreach ($worker_ids as $worker_id) 
				$worker_ids_str .= ($worker_ids_str ? ", " : "") . "'" . addcslashes($worker_id, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) 
				return $b->callSelect("module/workerpool", "get_workers_by_ids", array("worker_ids" => $worker_ids_str), $options);
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Worker = $this->getWorkerHbnObj($b, $options);
				$conditions = array("worker_id" => array("operator" => "in", "value" => $worker_ids));
				return $Worker->find(array("conditions" => $conditions), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				return $b->findObjects("mwp_worker", null, array("worker_id" => array("operator" => "in", "value" => $worker_ids)), $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/workerpool", "WorkerService.getWorkersIds", $data, $options);
		}
	}
}
?>
