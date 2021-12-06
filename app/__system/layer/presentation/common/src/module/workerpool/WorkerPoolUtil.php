<?php
include __DIR__ . "/WorkerPoolSettings.php";
include_once __DIR__ . "/WorkerDBDAOUtil.php"; //this file will be automatically generated on this module installation

class WorkerPoolUtil extends WorkerPoolSettings {
	
	/* WORKER FUNCTIONS */
	
	public static function insertWorker($brokers, $data) {
		if (is_array($brokers) && $data["class"]) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			
			if ($data["args"])
				$data["args"] = json_encode($data["args"]);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.insertWorker", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["class"] = addcslashes($data["class"], "\\'");
					$data["args"] = addcslashes($data["args"], "\\'");
					$data["thread_id"] = addcslashes($data["thread_id"], "\\'");
					$data["description"] = addcslashes($data["description"], "\\'");
					
					$status = $broker->callInsert("module/workerpool", "insert_worker", $data);
					return $status ? $broker->getInsertedId() : $status;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					$status = $Worker->insert($data, $ids);
					return $status ? $ids["worker_id"] : $status;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$status = $broker->insertObject("mwp_worker", array(
							"class" => $data["class"], 
							"args" => $data["args"], 
							"status" => $data["status"], 
							"thread_id" => $data["thread_id"], 
							"begin_time" => $data["begin_time"], 
							"end_time" => $data["end_time"], 
							"failed_attempts" => $data["failed_attempts"], 
							"description" => $data["description"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
					return $status ? $broker->getInsertedId() : $status;
				}
			}
		}
	}
	
	public static function updateWorker($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["worker_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
			
			if ($data["args"])
				$data["args"] = json_encode($data["args"]);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.updateWorker", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["class"] = addcslashes($data["class"], "\\'");
					$data["args"] = addcslashes($data["args"], "\\'");
					$data["thread_id"] = addcslashes($data["thread_id"], "\\'");
					$data["description"] = addcslashes($data["description"], "\\'");
					
					return $broker->callUpdate("module/workerpool", "update_worker", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					return $Worker->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mwp_worker", array(
							"class" => $data["class"], 
							"args" => $data["args"], 
							"status" => $data["status"], 
							"thread_id" => $data["thread_id"], 
							"begin_time" => $data["begin_time"], 
							"end_time" => $data["end_time"], 
							"failed_attempts" => $data["failed_attempts"], 
							"description" => $data["description"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						), array(
							"worker_id" => $data["worker_id"]
						));
				}
			}
		}
	}
	
	public static function updateFailedAndToParseWorkers($brokers, $maximum_failed_attempts) {
		if (is_array($brokers) && $maximum_failed_attempts > 0) {
			$data = array(
				"maximum_failed_attempts" => $maximum_failed_attempts, 
				"modified_date" => date("Y-m-d H:i:s"),
			);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.updateFailedAndToParseWorkers", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/workerpool", "update_failed_and_to_parse_workers", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					return $Worker->callUpdate("update_failed_and_to_parse_workers", $data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = WorkerDBDAOUtil::update_failed_and_to_parse_workers($data);
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function updateFailedAndExpiredWorkers($brokers, $maximum_failed_attempts, $expiration_time) {
		if (is_array($brokers) && $maximum_failed_attempts > 0 && $expiration_time > 0) {
			$data = array(
				"maximum_failed_attempts" => $maximum_failed_attempts, 
				"expiration_time" => $expiration_time, 
				"modified_date" => date("Y-m-d H:i:s"),
			);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.updateFailedAndExpiredWorkers", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/workerpool", "update_failed_and_expired_workers", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					return $Worker->callUpdate("update_failed_and_expired_workers", $data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = WorkerDBDAOUtil::update_failed_and_expired_workers($data);
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function resetExpiredWorkers($brokers, $expiration_time) {
		if (is_array($brokers) && $expiration_time > 0) {
			$data = array(
				"expiration_time" => $expiration_time, 
				"modified_date" => date("Y-m-d H:i:s"),
			);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.resetExpiredWorkers", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/workerpool", "reset_expired_workers", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					return $Worker->callUpdate("reset_expired_workers", $data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = WorkerDBDAOUtil::reset_expired_workers($data);
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function updateThreadWorker($brokers, $worker_id, $thread_id, $begin_time) {
		if (is_array($brokers) && is_numeric($worker_id) && $thread_id && is_numeric($begin_time)) {
			$data = array(
				"worker_id" => $worker_id, 
				"thread_id" => $thread_id, 
				"begin_time" => $begin_time, 
				"modified_date" => date("Y-m-d H:i:s"),
			);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.updateThreadWorker", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/workerpool", "update_thread_worker", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					return $Worker->callUpdate("update_thread_worker", $data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = WorkerDBDAOUtil::update_thread_worker($data);
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function updateClosedWorker($brokers, $worker_id, $end_time) {
		if (is_array($brokers) && is_numeric($worker_id) && is_numeric($end_time)) {
			$data = array(
				"worker_id" => $worker_id, 
				"end_time" => $end_time, 
				"modified_date" => date("Y-m-d H:i:s"),
			);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.updateClosedWorker", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/workerpool", "update_closed_worker", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					return $Worker->callUpdate("update_closed_worker", $data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = WorkerDBDAOUtil::update_closed_worker($data);
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function updateFailedWorker($brokers, $worker_id) {
		if (is_array($brokers) && is_numeric($worker_id)) {
			$data = array(
				"worker_id" => $worker_id, 
				"modified_date" => date("Y-m-d H:i:s"),
			);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.updateFailedWorker", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/workerpool", "update_failed_worker", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					return $Worker->callUpdate("update_failed_worker", $data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = WorkerDBDAOUtil::update_failed_worker($data);
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function resetFailedWorker($brokers, $worker_id) {
		if (is_array($brokers) && is_numeric($worker_id)) {
			$data = array(
				"worker_id" => $worker_id, 
				"modified_date" => date("Y-m-d H:i:s"),
			);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.resetFailedWorker", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/workerpool", "reset_failed_worker", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					return $Worker->callUpdate("reset_failed_worker", $data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = WorkerDBDAOUtil::reset_failed_worker($data);
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function deleteWorker($brokers, $worker_id) {
		if (is_array($brokers) && is_numeric($worker_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.deleteWorker", array("worker_id" => $worker_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/workerpool", "delete_worker", array("worker_id" => $worker_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					return $Worker->delete($worker_id);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mwp_worker", array("worker_id" => $worker_id));
				}
			}
		}
	}
	
	public static function getWorker($brokers, $worker_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($worker_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$result = $broker->callBusinessLogic("module/workerpool", "WorkerService.getWorker", array("worker_id" => $worker_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/workerpool", "get_worker", array("worker_id" => $worker_id), $options);
					$result = $result[0];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					$result = $Worker->findById($worker_id, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$result = $broker->findObjects("mwp_worker", null, array("worker_id" => $worker_id), $options);
					$result = $result[0];
				}
				
				$result = self::prepareResult(array($result));
				return $result[0];
			}
		}
	}
	
	public static function getWorkersByIds($brokers, $worker_ids, $options = array(), $no_cache = false) {
		if (is_array($brokers) && $worker_ids) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$worker_ids_str = "";//just in case the user tries to hack the sql query. By default all worker_id should be numeric.
			$worker_ids = is_array($worker_ids) ? $worker_ids : array($worker_ids);
			foreach ($worker_ids as $worker_id) {
				$worker_ids_str .= ($worker_ids_str ? ", " : "") . "'" . addcslashes($worker_id, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$result = $broker->callBusinessLogic("module/workerpool", "WorkerService.getWorkersByIds", array("worker_ids" => $worker_ids, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/workerpool", "get_workers_by_ids", array("worker_ids" => $worker_ids_str), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					$conditions = array("worker_id" => array("operator" => "in", "value" => $worker_ids));
					$result = $Worker->find(array("conditions" => $conditions), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$result = $broker->findObjects("mwp_worker", null, array("worker_id" => array("operator" => "in", "value" => $worker_ids)), $options);
				}
				
				return self::prepareResult($result);
			}
		}
	}
	
	public static function getAllWorkers($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					$result = $broker->callBusinessLogic("module/workerpool", "WorkerService.getAllWorkers", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/workerpool", "get_all_workers", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					$result = $Worker->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$result = $broker->findObjects("mwp_worker", null, null, $options);
				}
				
				return self::prepareResult($result);
			}
		}
	}
	
	public static function countAllWorkers($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.countAllWorkers", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/workerpool", "count_all_workers", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					return $Worker->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mwp_worker", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getWorkersByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			if (isset($conditions["worker"]))
				$conditions["worker"] = strtolower($conditions["worker"]);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$result = $broker->callBusinessLogic("module/workerpool", "WorkerService.getWorkersByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/workerpool", "get_workers_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					$result = $Worker->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					$result = $broker->findObjects("mwp_worker", null, $conditions, $options);
				}
				
				return self::prepareResult($result);
			}
		}
	}
	
	public static function countWorkersByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			if (isset($conditions["worker"]))
				$conditions["worker"] = strtolower($conditions["worker"]);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/workerpool", "WorkerService.countWorkersByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/workerpool", "count_workers_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Worker = $broker->callObject("module/workerpool", "Worker");
					return $Worker->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mwp_worker", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	private static function prepareResult($result) {
		if ($result)
			foreach ($result as &$item)
				if ($item["args"])
					$item["args"] = json_decode($item["args"], true);
		
		return $result;
	}
}
?>
