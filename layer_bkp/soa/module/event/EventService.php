<?php
namespace Module\Event;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/EventDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class EventService extends \soa\CommonService {
	private $Event;
	
	private function getEventHbnObj($b, $options) {
		if (!$this->Event) 
			$this->Event = $b->callObject("module/event", "Event", $options);
		
		return $this->Event;
	}
	
	/**
	 * @param (name=data[title], type=varchar, default="", length=1000)  
	 * @param (name=data[sub_title], type=varchar, default="", length=1000)  
	 * @param (name=data[description], type=longblob, default="")  
	 * @param (name=data[published], type=bool, default="0")  
	 * @param (name=data[photo_id], type=bigint, default=0, length=19)
	 * @param (name=data[allow_comments], type=bool, default="1")
	 * @param (name=data[address], type=varchar, default="", length=100) 
	 * @param (name=data[zip_id], type=varchar, default="", length=15) 
	 * @param (name=data[locality], type=varchar, default="", length=50) 
	 * @param (name=data[country_id], type=bigint, default=0, length=19)
	 * @param (name=data[latitude], type=coordinate, default="0") 
	 * @param (name=data[longitude], type=coordinate, default="0") 
	 * @param (name=data[begin_date], type=timestamp) 
	 * @param (name=data[end_date], type=timestamp) 
	 */
	public function insertEvent($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if (empty($data["published"])) 
			$data["published"] = 0;
		
		if (empty($data["photo_id"])) 
			$data["photo_id"] = 0;
		
		if (empty($data["country_id"])) 
			$data["country_id"] = 0;
		
		$data["allow_comments"] = empty($data["allow_comments"]) ? 0 : 1;
		$data["end_date"] = empty($data["end_date"]) ? '0000-00-00 00:00:00' : $data["end_date"];
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["title"] = addcslashes($data["title"], "\\'");
			$data["sub_title"] = addcslashes($data["sub_title"], "\\'");
			$data["description"] = addcslashes($data["description"], "\\'");
			$data["address"] = addcslashes($data["address"], "\\'");
			$data["zip_id"] = addcslashes($data["zip_id"], "\\'");
			$data["locality"] = addcslashes($data["locality"], "\\'");
			$data["end_date"] = addcslashes($data["end_date"], "\\'");
			$data["begin_date"] = addcslashes($data["begin_date"], "\\'");
			
			$status = $b->callInsert("module/event", "insert_event", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Event = $this->getEventHbnObj($b, $options);
			$status = $Event->insert($data, $ids);
			return $status ? $ids["event_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("me_event", array(
					"title" => $data["title"], 
					"sub_title" => $data["sub_title"], 
					"description" => $data["description"], 
					"published" => $data["published"], 
					"photo_id" => $data["photo_id"], 
					"allow_comments" => $data["allow_comments"], 
					"address" => $data["address"], 
					"zip_id" => $data["zip_id"], 
					"locality" => $data["locality"], 
					"country_id" => $data["country_id"], 
					"latitude" => $data["latitude"], 
					"longitude" => $data["longitude"], 
					"begin_date" => $data["begin_date"], 
					"end_date" => $data["end_date"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/event", "EventService.insertEvent", $data, $options);
	}
	
	/**
	 * @param (name=data[event_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[title], type=varchar, length=1000)  
	 * @param (name=data[sub_title], type=varchar, length=1000)  
	 * @param (name=data[description], type=longblob)  
	 * @param (name=data[published], type=bool)  
	 * @param (name=data[photo_id], type=bigint, length=19)
	 * @param (name=data[allow_comments], type=bool) 
	 * @param (name=data[address], type=varchar, default="", length=100) 
	 * @param (name=data[zip_id], type=varchar, default="", length=15) 
	 * @param (name=data[locality], type=varchar, default="", length=50) 
	 * @param (name=data[country_id], type=bigint, default=0, length=19)
	 * @param (name=data[latitude], type=coordinate, default="0") 
	 * @param (name=data[longitude], type=coordinate, default="0") 
	 * @param (name=data[begin_date], type=timestamp) 
	 * @param (name=data[end_date], type=timestamp) 
	 */
	public function updateEvent($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if (empty($data["published"])) 
			$data["published"] = 0;
		
		if (empty($data["photo_id"])) 
			$data["photo_id"] = 0;
		
		if (empty($data["country_id"])) 
			$data["country_id"] = 0;
		
		$data["allow_comments"] = empty($data["allow_comments"]) ? 0 : 1;
		$data["end_date"] = empty($data["end_date"]) ? '0000-00-00 00:00:00' : $data["end_date"];
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["title"] = addcslashes($data["title"], "\\'");
			$data["sub_title"] = addcslashes($data["sub_title"], "\\'");
			$data["description"] = addcslashes($data["description"], "\\'");
			$data["address"] = addcslashes($data["address"], "\\'");
			$data["zip_id"] = addcslashes($data["zip_id"], "\\'");
			$data["locality"] = addcslashes($data["locality"], "\\'");
			$data["end_date"] = addcslashes($data["end_date"], "\\'");
			$data["begin_date"] = addcslashes($data["begin_date"], "\\'");
		
			return $b->callUpdate("module/event", "update_event", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Event = $this->getEventHbnObj($b, $options);
			return $Event->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("me_event", array(
					"title" => $data["title"], 
					"sub_title" => $data["sub_title"], 
					"description" => $data["description"], 
					"published" => $data["published"], 
					"photo_id" => $data["photo_id"], 
					"allow_comments" => $data["allow_comments"], 
					"address" => $data["address"], 
					"zip_id" => $data["zip_id"], 
					"locality" => $data["locality"], 
					"country_id" => $data["country_id"], 
					"latitude" => $data["latitude"], 
					"longitude" => $data["longitude"], 
					"begin_date" => $data["begin_date"], 
					"end_date" => $data["end_date"], 
					"modified_date" => $data["modified_date"]
				), array(
					"event_id" => $data["event_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "EventService.updateEvent", $data, $options);
	}
	
	/**
	 * @param (name=data[event_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteEvent($data) {
		$event_id = $data["event_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/event", "delete_event", array("event_id" => $event_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Event = $this->getEventHbnObj($b, $options);
			return $Event->delete($event_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("me_event", array("event_id" => $event_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "EventService.deleteEvent", $data, $options);
	}
	
	/**
	 * @param (name=data[event_id], type=bigint, not_null=1, length=19)  
	 */
	public function getEvent($data) {
		$event_id = $data["event_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/event", "get_event", array("event_id" => $event_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Event = $this->getEventHbnObj($b, $options);
			return $Event->findById($event_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("me_event", null, array("event_id" => $event_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "EventService.getEvent", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000) 
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function getEventsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/event", "get_events_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Event = $this->getEventHbnObj($b, $options);
				return $Event->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("me_event", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/event", "EventService.getEventsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000) 
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function countEventsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/event", "count_events_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Event = $this->getEventHbnObj($b, $options);
				return $Event->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("me_event", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/event", "EventService.countEventsByConditions", $data, $options);
		}
	}
	
	public function getAllEvents($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/event", "get_all_events", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Event = $this->getEventHbnObj($b, $options);
			return $Event->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("me_event", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "EventService.getAllEvents", $data, $options);
	}
	
	public function countAllEvents($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/event", "count_all_events", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Event = $this->getEventHbnObj($b, $options);
			return $Event->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("me_event", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/event", "EventService.countAllEvents", $data, $options);
	}
	
	/**
	 * @param (name=data[event_ids], type=mixed, not_null=1)  
	 */
	public function getEventsByIds($data) {
		$event_ids = $data["event_ids"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($event_ids) {
			$event_ids_str = "";//just in case the user tries to hack the sql query. By default all event_id should be numeric.
			$event_ids = is_array($event_ids) ? $event_ids : array($event_ids);
			foreach ($event_ids as $event_id) 
				$event_ids_str .= ($event_ids_str ? ", " : "") . "'" . addcslashes($event_id, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) 
				return $b->callSelect("module/event", "get_events_by_ids", array("event_ids" => $event_ids_str), $options);
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Event = $this->getEventHbnObj($b, $options);
				$conditions = array("event_id" => array("operator" => "in", "value" => $event_ids));
				return $Event->find(array("conditions" => $conditions), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				return $b->findObjects("me_event", null, array("event_id" => array("operator" => "in", "value" => $event_ids)), $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/event", "EventService.getEventsByIds", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function getEventsByTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag)
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				return $b->callSelect("module/event", "get_events_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				$Event = $this->getEventHbnObj($b, $options);
				return $Event->callSelect("get_events_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				$sql = EventDBDAOServiceUtil::get_events_by_tags(array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/event", "EventService.getEventsByTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function countEventsByTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				$result = $b->callSelect("module/event", "count_events_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				$Event = $this->getEventHbnObj($b, $options);
				$result = $Event->callSelect("count_events_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				$sql = EventDBDAOServiceUtil::count_events_by_tags(array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond));
				
				$result = $b->getSQL($sql, $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/event", "EventService.countEventsByTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[event_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function getEventsByObjectAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$event_object_type_id = $data["event_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				return $b->callSelect("module/event", "get_events_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				$Event = $this->getEventHbnObj($b, $options);
				return $Event->callSelect("get_events_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				$sql = EventDBDAOServiceUtil::get_events_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/event", "EventService.getEventsByObjectAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[event_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000) 
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function countEventsByObjectAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$event_object_type_id = $data["event_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				$result = $b->callSelect("module/event", "count_events_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				$Event = $this->getEventHbnObj($b, $options);
				$result = $Event->callSelect("count_events_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				$sql = EventDBDAOServiceUtil::count_events_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond));
				
				$result = $b->getSQL($sql, $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/event", "EventService.countEventsByObjectAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)   
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[event_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function getEventsByObjectGroupAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$tags = $data["tags"];
		$event_object_type_id = $data["event_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				return $b->callSelect("module/event", "get_events_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				$Event = $this->getEventHbnObj($b, $options);
				return $Event->callSelect("get_events_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				$sql = EventDBDAOServiceUtil::get_events_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/event", "EventService.getEventsByObjectGroupAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[event_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function countEventsByObjectGroupAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$tags = $data["tags"];
		$event_object_type_id = $data["event_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				$result = $b->callSelect("module/event", "count_events_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
				$Event = $this->getEventHbnObj($b, $options);
				$result = $Event->callSelect("count_events_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				$sql = EventDBDAOServiceUtil::count_events_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => $event_object_type_id, "conditions" => $cond));
				
				$result = $b->getSQL($sql, $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/event", "EventService.countEventsByObjectGroupAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1) 
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array) 
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function getEventsWithAllTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					return $b->callSelect("module/event", "get_events_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					$Event = $this->getEventHbnObj($b, $options);
					return $Event->callSelect("get_events_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
					$sql = EventDBDAOServiceUtil::get_events_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond));
					
					return $b->getSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient"))
					return $b->callBusinessLogic("module/event", "EventService.getEventsWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1) 
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array) 
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function countEventsWithAllTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					$result = $b->callSelect("module/event", "count_events_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					$Event = $this->getEventHbnObj($b, $options);
					$result = $Event->callSelect("count_events_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
					$sql = EventDBDAOServiceUtil::count_events_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/event", "EventService.countEventsWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[event_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function getEventsByObjectWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$event_object_type_id = $data["event_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					return $b->callSelect("module/event", "get_events_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					$Event = $this->getEventHbnObj($b, $options);
					return $Event->callSelect("get_events_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
					$sql = EventDBDAOServiceUtil::get_events_by_object_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond));
					
					return $b->getSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient"))
					return $b->callBusinessLogic("module/event", "EventService.getEventsByObjectWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[event_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function countEventsByObjectWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$event_object_type_id = $data["event_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					$result = $b->callSelect("module/event", "count_events_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					$Event = $this->getEventHbnObj($b, $options);
					$result = $Event->callSelect("count_events_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
					$sql = EventDBDAOServiceUtil::count_events_by_object_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/event", "EventService.countEventsByObjectWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)    
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[event_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function getEventsByObjectGroupWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$tags = $data["tags"];
		$event_object_type_id = $data["event_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					return $b->callSelect("module/event", "get_events_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					$Event = $this->getEventHbnObj($b, $options);
					return $Event->callSelect("get_events_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
					$sql = EventDBDAOServiceUtil::get_events_by_object_group_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond));
					
					return $b->getSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/event", "EventService.getEventsByObjectGroupWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)   
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[event_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function countEventsByObjectGroupWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$tags = $data["tags"];
		$event_object_type_id = $data["event_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					$result = $b->callSelect("module/event", "count_events_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
					$Event = $this->getEventHbnObj($b, $options);
					$result = $Event->callSelect("count_events_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
					$sql = EventDBDAOServiceUtil::count_events_by_object_group_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => $event_object_type_id, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/event", "EventService.countEventsByObjectGroupWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function getEventsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
			return $b->callSelect("module/event", "get_events_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
			$Event = $this->getEventHbnObj($b, $options);
			return $Event->callSelect("get_events_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
			$sql = EventDBDAOServiceUtil::get_events_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "EventService.getEventsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function countEventsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
			$result = $b->callSelect("module/event", "count_events_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
			$Event = $this->getEventHbnObj($b, $options);
			$result = $Event->callSelect("count_events_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
			$sql = EventDBDAOServiceUtil::count_events_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "EventService.countEventsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function getEventsByObjectGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
			return $b->callSelect("module/event", "get_events_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
			$Event = $this->getEventHbnObj($b, $options);
			return $Event->callSelect("get_events_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
			$sql = EventDBDAOServiceUtil::get_events_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "EventService.getEventsByObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[conditions][event_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 * @param (name=data[conditions][address], type=varchar|array, length=100) 
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15) 
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][latitude], type=coordinate|array) 
	 * @param (name=data[conditions][longitude], type=coordinate|array) 
	 * @param (name=data[conditions][begin_date], type=timestamp|array) 
	 * @param (name=data[conditions][end_date], type=timestamp|array)  
	 */
	public function countEventsByObjectGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
			$result = $b->callSelect("module/event", "count_events_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
				
			$Event = $this->getEventHbnObj($b, $options);
			$result = $Event->callSelect("count_events_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "e");
			$sql = EventDBDAOServiceUtil::count_events_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/event", "EventService.countEventsByObjectGroup", $data, $options);
	}
	
	private static function getSQLConditions($conditions, $conditions_join, $key_prefix) {
		$cond = \DB::getSQLConditions($conditions, $conditions_join, $key_prefix);
		return $cond ? $cond : '1=1';
	}
}
?>
