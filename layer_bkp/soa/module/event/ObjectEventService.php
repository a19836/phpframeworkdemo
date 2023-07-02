<?php
namespace Module\Event;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/ObjectEventDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class ObjectEventService extends \soa\CommonService {
	private $ObjectEvent;
	
	private function getObjectEventHbnObj($b, $options) {
		if (!$this->ObjectEvent)
			$this->ObjectEvent = $b->callObject("module/event", "ObjectEvent", $options);
		
		return $this->ObjectEvent;
	}
	
	/**
	 * @param (name=data[event_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function insertObjectEvent($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callInsert("module/event", "insert_object_event", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			return $ObjectEvent->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("me_object_event", array(
					"event_id" => $data["event_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"group" => $data["group"], 
					"order" => $data["order"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.insertObjectEvent", $data, $options);
	}
	
	/**
	 * @param (name=data[new_event_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_event_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function updateObjectEvent($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/event", "update_object_event", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			return $ObjectEvent->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("me_object_event", array(
					"event_id" => $data["new_event_id"], 
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"group" => $data["group"], 
					"order" => $data["order"], 
					"modified_date" => $data["modified_date"]
				), array(
					"event_id" => $data["old_event_id"], 
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.updateObjectEvent", $data, $options);
	}
	
	/**
	 * @param (name=data[new_event_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_event_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function updateObjectEventIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/event", "update_object_event_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			return $ObjectEvent->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("me_object_event", array(
					"event_id" => $data["new_event_id"], 
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"event_id" => $data["old_event_id"], 
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.updateObjectEventIds", $data, $options);
	}
	
	/**
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function changeObjectEventsObjectIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/event", "change_object_events_object_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			return $ObjectEvent->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("me_object_event", array(
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.changeObjectEventsObjectIds", $data, $options);
	}
	
	/**
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[parent_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[parent_object_id], type=bigint, not_null=1, length=19)
	 */
	public function changeObjectEventsObjectIdsOfParentObject($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/event", "change_object_events_object_ids_of_parent_object", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			return $ObjectEvent->callUpdate("change_object_events_object_ids_of_parent_object", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ObjectEventDBDAOServiceUtil::change_object_events_object_ids_of_parent_object($data);
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.changeObjectEventsObjectIdsOfParentObject", $data, $options);
	}
	
	/**
	 * @param (name=data[event_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function updateObjectEventOrder($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/event", "update_object_event_order", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			return $ObjectEvent->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("me_object_event", array(
					"order" => $data["order"], 
					"modified_date" => $data["modified_date"]
				), array(
					"event_id" => $data["event_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.updateObjectEventOrder", $data, $options);
	}
	
	/**
	 * @param (name=data[event_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectEvent($data) {
		$event_id = $data["event_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/event", "delete_object_event", array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			return $ObjectEvent->delete(array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("me_object_event", array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.deleteObjectEvent", $data, $options);
	}
	
	/**
	 * @param (name=data[event_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectEventsByEventId($data) {
		$event_id = $data["event_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/event", "delete_object_events_by_event_id", array("event_id" => $event_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			$conditions = array("event_id" => $event_id);
			return $ObjectEvent->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("me_object_event", array("event_id" => $event_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.deleteObjectEventsByEventId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectEventsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/event", "delete_object_events_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $ObjectEvent->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("me_object_event", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.deleteObjectEventsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function deleteObjectEventsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/event", "delete_object_events_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
				return $ObjectEvent->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]));
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("me_object_event", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/event", "ObjectEventService.deleteObjectEventsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[event_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectEvent($data) {
		$event_id = $data["event_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/event", "get_object_event", array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			return $ObjectEvent->findById(array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("me_object_event", null, array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.getObjectEvent", $data, $options);
	}
	
	/**
	 * @param (name=data[event_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectEventsByEventId($data) {
		$event_id = $data["event_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/event", "get_object_events_by_event_id", array("event_id" => $event_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			$conditions = array("event_id" => $event_id);
			return $ObjectEvent->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("me_object_event", null, array("event_id" => $event_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.getObjectEventsByEventId", $data, $options);
	}
	
	/**
	 * @param (name=data[event_id], type=bigint, not_null=1, length=19)
	 */
	public function countObjectEventsByEventId($data) {
		$event_id = $data["event_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/event", "count_object_events_by_event_id", array("event_id" => $event_id), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			return $ObjectEvent->count(array("event_id" => $event_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("me_object_event", array("event_id" => $event_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.countObjectEventsByEventId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectEventsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/event", "get_object_events_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $ObjectEvent->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("me_object_event", null, array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.getObjectEventsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function getObjectEventsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/event", "get_object_events_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
				return $ObjectEvent->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("me_object_event", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/event", "ObjectEventService.getObjectEventsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function countObjectEventsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/event", "count_object_events_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
				return $ObjectEvent->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("me_object_event", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/event", "ObjectEventService.countObjectEventsByConditions", $data, $options);
		}
	}
	
	public function getAllObjectEvents($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/event", "get_all_object_events", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			return $ObjectEvent->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("me_object_event", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.getAllObjectEvents", null, $options);
	}
	
	public function countAllObjectEvents($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/event", "count_all_object_events", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectEvent = $this->getObjectEventHbnObj($b, $options);
			return $ObjectEvent->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("me_object_event", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "ObjectEventService.countAllObjectEvents", null, $options);
	}
}
?>
