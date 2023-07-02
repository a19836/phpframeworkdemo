<?php
include_once $EVC->getModulePath("tag/TagUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("comment/CommentUtil", $EVC->getCommonProjectName());
include_once __DIR__ . "/EventDBDAOUtil.php"; //this file will be automatically generated on this module installation
include_once __DIR__ . "/ObjectEventDBDAOUtil.php"; //this file will be automatically generated on this module installation

class EventUtil {
	
	const EVENT_PHOTO_GROUP_ID = 0;
	const EVENT_ATTACHMENTS_GROUP_ID = 1;
	const EVENT_DESCRIPTION_HTML_IMAGE_GROUP_ID = 3;
	
	public static function prepareEventsPhotos($EVC, &$events, $no_cache = false, $brokers = array()) {
		if ($events) {
			$attachment_ids = array();
			$indexes = array();
			
			foreach ($events as $idx => $event) {
				$photo_id = $event["photo_id"];
				
				if ($photo_id) {
					$attachment_ids[] = $photo_id;
					$indexes[$photo_id] = $idx;
				}
			}
			
			$attachments = AttachmentUtil::getAttachmentsByIds($brokers, $attachment_ids, $no_cache);
			
			if ($attachments) {
				$folder_path = AttachmentUtil::getAttachmentsFolderPath($EVC);
				$url = AttachmentUtil::getAttachmentsFolderUrl($EVC);
				
				foreach ($attachments as $attachment) {
					$path = $attachment["path"];
					
					if ($path) {
						$idx = $indexes[ $attachment["attachment_id"] ];
						
						if ($events[$idx]) {
							$events[$idx]["photo_path"] = $folder_path . $path;
							$events[$idx]["photo_url"] = $url . $path;
						}
					}
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function getEventsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "EventService.getEventsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/event", "get_events_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Event = $broker->callObject("module/event", "Event");
					return $Event->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("me_event", null, $conditions, $options);
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function countEventsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "EventService.countEventsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/event", "count_events_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Event = $broker->callObject("module/event", "Event");
					return $Event->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("me_event", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getEventsByIds($brokers, $event_ids, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$event_ids_str = is_array($event_ids) ? implode(', ', $event_ids) : $event_ids;
			$event_ids_str = str_replace(array("'", "\\"), "", $event_ids_str);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "EventService.getEventsByIds", array("event_ids" => $event_ids, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/event", "get_events_by_ids", array("event_ids" => $event_ids_str), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Event = $broker->callObject("module/event", "Event");
					$conditions = array("event_id" => array("operator" => "in", "value" => $event_ids));
					return $Event->find(array("conditions" => $conditions), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("me_event", null, array("event_id" => array("operator" => "in", "value" => $event_ids)), $options);
				}
			}
		}
	}
	
	//$tags is a string containing multiple event tags
	//This method will return the events that contains at least one tag in $tags
	public static function getEventsByTags($brokers, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "EventService.getEventsByTags", array("tags" => $tags, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					return $broker->callSelect("module/event", "get_events_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$Event = $broker->callObject("module/event", "Event");
					return $Event->callSelect("get_events_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					$sql = EventDBDAOUtil::get_events_by_tags(array("tags" => $tags_str, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	//$tags is a string containing multiple event tags
	//This method will return the events that contains at least one tag in $tags
	public static function countEventsByTags($brokers, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "EventService.countEventsByTags", array("tags" => $tags, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$result = $broker->callSelect("module/event", "count_events_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$Event = $broker->callObject("module/event", "Event");
					$result = $Event->callSelect("count_events_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					$sql = EventDBDAOUtil::count_events_by_tags(array("tags" => $tags_str, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	//$tags is a string containing multiple event tags
	//This method will return the events that contains at least one tag in $tags
	public static function getEventsByObjectAndTags($brokers, $object_type_id, $object_id, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "EventService.getEventsByObjectAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					return $broker->callSelect("module/event", "get_events_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$Event = $broker->callObject("module/event", "Event");
					return $Event->callSelect("get_events_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					$sql = EventDBDAOUtil::get_events_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	//$tags is a string containing multiple event tags
	//This method will return the events that contains at least one tag in $tags
	public static function countEventsByObjectAndTags($brokers, $object_type_id, $object_id, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "EventService.countEventsByObjectAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$result = $broker->callSelect("module/event", "count_events_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$Event = $broker->callObject("module/event", "Event");
					$result = $Event->callSelect("count_events_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					$sql = EventDBDAOUtil::count_events_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	//$tags is a string containing multiple event tags
	//This method will return the events that contains at least one tag in $tags
	public static function getEventsByObjectGroupAndTags($brokers, $object_type_id, $object_id, $group = null, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group = is_numeric($group) ? $group : null;
					
					return $broker->callBusinessLogic("module/event", "EventService.getEventsByObjectGroupAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					return $broker->callSelect("module/event", "get_events_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$Event = $broker->callObject("module/event", "Event");
					return $Event->callSelect("get_events_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					$sql = EventDBDAOUtil::get_events_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	//$tags is a string containing multiple event tags
	//This method will return the events that contains at least one tag in $tags
	public static function countEventsByObjectGroupAndTags($brokers, $object_type_id, $object_id, $group = null, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group = is_numeric($group) ? $group : null;
					
					return $broker->callBusinessLogic("module/event", "EventService.countEventsByObjectGroupAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$result = $broker->callSelect("module/event", "count_events_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$Event = $broker->callObject("module/event", "Event");
					$result = $Event->callSelect("count_events_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					$sql = EventDBDAOUtil::count_events_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	//$tags is a string containing multiple event tags
	//This method will return the events that contains all $tags
	public static function getEventsWithAllTags($brokers, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/event", "EventService.getEventsWithAllTags", array("tags" => $tags, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
						return $broker->callSelect("module/event", "get_events_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						
						$Event = $broker->callObject("module/event", "Event");
						return $Event->callSelect("get_events_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						$sql = EventDBDAOUtil::get_events_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	}
	
	public static function countEventsWithAllTags($brokers, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/event", "EventService.countEventsWithAllTags", array("tags" => $tags, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						
						$result = $broker->callSelect("module/event", "count_events_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						
						$Event = $broker->callObject("module/event", "Event");
						$result = $Event->callSelect("count_events_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						$sql = EventDBDAOUtil::count_events_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
	}
	
	//$tags is a string containing multiple event tags
	//This method will return the events that contains all $tags
	public static function getEventsByObjectWithAllTags($brokers, $object_type_id, $object_id, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/event", "EventService.getEventsByObjectWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						
						return $broker->callSelect("module/event", "get_events_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						
						$Event = $broker->callObject("module/event", "Event");
						return $Event->callSelect("get_events_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						$sql = EventDBDAOUtil::get_events_by_object_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	}
	
	public static function countEventsByObjectWithAllTags($brokers, $object_type_id, $object_id, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/event", "EventService.countEventsByObjectWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						
						$result = $broker->callSelect("module/event", "count_events_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						
						$Event = $broker->callObject("module/event", "Event");
						$result = $Event->callSelect("count_events_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						$sql = EventDBDAOUtil::count_events_by_object_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
	}
	
	//$tags is a string containing multiple event tags
	//This method will return the events that contains all $tags
	public static function getEventsByObjectGroupWithAllTags($brokers, $object_type_id, $object_id, $group = null, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$group = is_numeric($group) ? $group : null;
					
						return $broker->callBusinessLogic("module/event", "EventService.getEventsByObjectGroupWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						
						return $broker->callSelect("module/event", "get_events_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						
						$Event = $broker->callObject("module/event", "Event");
						return $Event->callSelect("get_events_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						$sql = EventDBDAOUtil::get_events_by_object_group_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	}
	
	public static function countEventsByObjectGroupWithAllTags($brokers, $object_type_id, $object_id, $group = null, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$group = is_numeric($group) ? $group : null;
					
						return $broker->callBusinessLogic("module/event", "EventService.countEventsByObjectGroupWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						
						$result = $broker->callSelect("module/event", "count_events_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						
						$Event = $broker->callObject("module/event", "Event");
						$result = $Event->callSelect("count_events_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "e");
						$sql = EventDBDAOUtil::count_events_by_object_group_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "event_object_type_id" => ObjectUtil::EVENT_OBJECT_TYPE_ID, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
	}
	
	public static function getAllEvents($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/event", "EventService.getAllEvents", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/event", "get_all_events", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Event = $broker->callObject("module/event", "Event");
					return $Event->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("me_event", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllEvents($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/event", "EventService.countAllEvents", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/event", "count_all_events", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Event = $broker->callObject("module/event", "Event");
					return $Event->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("me_event", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getEventProperties($EVC, $event_id, $no_cache = false, $brokers = array()) {
		$data = null;
		
		$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
		if (is_array($brokers) && is_numeric($event_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = $broker->callBusinessLogic("module/event", "EventService.getEvent", array("event_id" => $event_id), array("no_cache" => $no_cache));
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data = $broker->callSelect("module/event", "get_event", array("event_id" => $event_id), array("no_cache" => $no_cache));
					$data = $data[0];
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Event = $broker->callObject("module/event", "Event", array("no_cache" => $no_cache));
					$data = $Event->findById($event_id, null, array("no_cache" => $no_cache));
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data = $broker->findObjects("me_event", null, array("event_id" => $event_id), array("no_cache" => $no_cache));
					$data = $data[0];
					break;
				}
			}
		
			if ($data) {
				$data["tags"] = TagUtil::getObjectTagsString($broker, ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id);
				
				if ($data["photo_id"]) {
					$attachment_data = AttachmentUtil::getAttachmentsByConditions($brokers, array("attachment_id" => $data["photo_id"]), null, null, $no_cache);
					$data["photo_path"] = AttachmentUtil::getAttachmentsFolderPath($EVC) . $attachment_data[0]["path"];
					$data["photo_url"] = AttachmentUtil::getAttachmentsFolderUrl($EVC) . $attachment_data[0]["path"];
				}
				
				$data["begin_date"] = $data["begin_date"] ? substr($data["begin_date"], 0, strrpos($data["begin_date"], ":")) : $data["begin_date"];
				$data["begin_date"] = $data["begin_date"] == '0000-00-00 00:00' ? '' : $data["begin_date"];
				
				$data["end_date"] = $data["end_date"] ? substr($data["end_date"], 0, strrpos($data["end_date"], ":")) : $data["end_date"];
				$data["end_date"] = $data["end_date"] == '0000-00-00 00:00' ? '' : $data["end_date"];
			}
		}
		
		return $data;
	}
	
	public static function setEventProperties($EVC, $event_id, $data, $file = null, $brokers = array(), $is_local_file = false) {
		$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
		if (is_array($brokers)) {
			$is_update = false;
			if ($event_id) {
				$is_update = true;
				$data["event_id"] = $event_id;
			}
			
			$event_id = self::insertOrUpdateEvent($brokers, $data);
			
			if ($event_id) {
				$status = true;
				
				//delete photo
				if ($is_update) {
					$db_data = self::getEventProperties($EVC, $event_id, $no_cache);
					
					$delete_photo = !$data["photo_id"] || $data["photo_id"] != $db_data["photo_id"]; //bc of the default_value that could be set
					
					if ($delete_photo)
						AttachmentUtil::deleteFileByObject($EVC, ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id, self::EVENT_PHOTO_GROUP_ID, $brokers);
				}
				
				if ($file["tmp_name"]) {
					//insert or update photo
					$photo_id = AttachmentUtil::replaceObjectFile($EVC, $file, $data["photo_id"], ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id, self::EVENT_PHOTO_GROUP_ID, 0, $brokers, $is_local_file);
					
					if (!$photo_id) {
						$status = false;
					}
					else if ($photo_id != $data["photo_id"]) {//update photo_id in event if different
						$data["event_id"] = $event_id;//in case be an insert. Otherwise it inserts 2 records into DB.
						$data["photo_id"] = $photo_id;
						$event_id = self::insertOrUpdateEvent($brokers, $data);
					}
				}
				
				return $status ? $event_id : false;
			}
		}
	}
	
	public static function insertOrUpdateEvent($brokers, $data) {
		if (is_array($brokers)) {
			$status = false;
					
			$data["published"] = empty($data["published"]) ? 0 : 1;
			$data["photo_id"] = empty($data["photo_id"]) ? 0 : $data["photo_id"];
			$data["country_id"] = empty($data["country_id"]) ? 0 : $data["country_id"];
			$data["latitude"] = empty($data["latitude"]) ? 0 : $data["latitude"];
			$data["longitude"] = empty($data["longitude"]) ? 0 : $data["longitude"];
			$data["allow_comments"] = empty($data["allow_comments"]) ? 0 : 1;
			
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			
			$event_id = $data["event_id"];
			$is_insert = !$event_id;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					if ($is_insert) {
						$event_id = $broker->callBusinessLogic("module/event", "EventService.insertEvent", $data);
						$status = $event_id ? true : false;
					}
					else {
						$status = $broker->callBusinessLogic("module/event", "EventService.updateEvent", $data);
					}
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["title"] = addcslashes($data["title"], "\\'");
					$data["sub_title"] = addcslashes($data["sub_title"], "\\'");
					$data["description"] = addcslashes($data["description"], "\\'");
					$data["published"] = is_numeric($data["published"]) ? $data["published"] : 0;
					$data["photo_id"] = is_numeric($data["photo_id"]) ? $data["photo_id"] : 0;
					$data["allow_comments"] = is_numeric($data["allow_comments"]) ? $data["allow_comments"] : 1;
					$data["address"] = addcslashes($data["address"], "\\'");
					$data["zip_id"] = addcslashes($data["zip_id"], "\\'");
					$data["locality"] = addcslashes($data["locality"], "\\'");
					$data["country_id"] = is_numeric($data["country_id"]) ? $data["country_id"] : 0;
					$data["latitude"] = is_numeric($data["latitude"]) ? $data["latitude"] : 0;
					$data["longitude"] = is_numeric($data["longitude"]) ? $data["longitude"] : 0;
					$data["end_date"] = addcslashes($data["end_date"], "\\'");
					$data["begin_date"] = addcslashes($data["begin_date"], "\\'");
					
					$data["end_date"] = empty($data["end_date"]) ? '0000-00-00 00:00:00' : $data["end_date"];
			
					if ($is_insert) {
						$status = $broker->callInsert("module/event", "insert_event", $data);
						$event_id = $broker->getInsertedId();
					}
					else if(is_numeric($event_id)) {
						$status = $broker->callUpdate("module/event", "update_event", $data);
					}
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["end_date"] = empty($data["end_date"]) ? '0000-00-00 00:00:00' : $data["end_date"];
			
					$Event = $broker->callObject("module/event", "Event", array("no_cache" => true));
					$status = $Event->insertOrUpdate($data, $ids);
				
					if ($status && !$event_id && $ids["event_id"]) {
						$event_id = $ids["event_id"];
					}
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$event_data = array(
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
					);
					
					if ($is_insert) {
						$status = $broker->insertObject("me_event", $event_data);
						$event_id = $broker->getInsertedId();
					}
					else if(is_numeric($event_id)) {
						$status = $broker->updateObject("me_event", $event_data, array("event_id" => $event_id));
					}
					break;
				}
			}
			
			if ($status && $event_id) {
				$status = TagUtil::updateObjectTags($broker, $data["tags"], ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id) && self::updateObjectEventsByEventId(array($broker), $event_id, $data);
			
				return $status ? $event_id : false;
			}
		}	
	}
	
	public static function deleteEvent($EVC, $event_id, $brokers = array()) {
		$status = false;
			
		$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
		if (is_array($brokers) && is_numeric($event_id)) {
			$status = AttachmentUtil::deleteFileByObject($EVC, ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id, null, $brokers);
			
			if ($status) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$status = $broker->callBusinessLogic("module/event", "EventService.deleteEvent", array("event_id" => $event_id));
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$status = $broker->callDelete("module/event", "delete_event", array("event_id" => $event_id));
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Event = $broker->callObject("module/event", "Event");
						$status = $Event->delete($event_id);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("me_event", array("event_id" => $event_id));
					}
				}
			
				if ($status) {
					//Related attachments are already deleted above through the AttachmentUtil::deleteFileByObject method
					$status = self::deleteObjectEventsByEventId(array($broker), $event_id) 
					  && TagUtil::deleteObjectTagsByObject(array($broker), ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id) 
					  && CommentUtil::deleteCommentsByObject(array($broker), ObjectUtil::EVENT_OBJECT_TYPE_ID, $event_id);
				}
			}
		}
		
		return $status;
	}
	
	public static function getEventsByObject($brokers, $object_type_id, $object_id, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "EventService.getEventsByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					return $broker->callSelect("module/event", "get_events_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$Event = $broker->callObject("module/event", "Event");
					return $Event->callSelect("get_events_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = EventDBDAOUtil::get_events_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
				
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function countEventsByObject($brokers, $object_type_id, $object_id, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "EventService.countEventsByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$result = $broker->callSelect("module/event", "count_events_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$Event = $broker->callObject("module/event", "Event");
					$result = $Event->callSelect("count_events_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = EventDBDAOUtil::count_events_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
				
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}

	public static function getEventsByObjectGroup($brokers, $object_type_id, $object_id, $group = null, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group = is_numeric($group) ? $group : null;
					
					return $broker->callBusinessLogic("module/event", "EventService.getEventsByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					return $broker->callSelect("module/event", "get_events_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$Event = $broker->callObject("module/event", "Event");
					return $Event->callSelect("get_events_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = EventDBDAOUtil::get_events_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
				
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function countEventsByObjectGroup($brokers, $object_type_id, $object_id, $group = null, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group = is_numeric($group) ? $group : null;
					
					return $broker->callBusinessLogic("module/event", "EventService.countEventsByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$result = $broker->callSelect("module/event", "count_events_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "e");
					
					$Event = $broker->callObject("module/event", "Event");
					$result = $Event->callSelect("count_events_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = EventDBDAOUtil::count_events_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
				
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	private static function getSQLConditions($conditions, $conditions_join, $key_prefix) {
		$cond = DB::getSQLConditions($conditions, $conditions_join, $key_prefix);
		return $cond ? $cond : '1=1';
	}
	
	/* OBJECT EVENT FUNCTIONS */

	public static function insertObjectEvent($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["event_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					
					return $broker->callBusinessLogic("module/event", "ObjectEventService.insertObjectEvent", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					return $broker->callInsert("module/event", "insert_object_event", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->insert($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					return $broker->insertObject("me_object_event", array(
							"event_id" => $data["event_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"], 
							"group" => $data["group"], 
							"order" => $data["order"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
				}
			}
		}
	}

	public static function updateObjectEvent($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["new_event_id"]) && is_numeric($data["new_object_type_id"]) && is_numeric($data["new_object_id"]) && is_numeric($data["old_event_id"]) && is_numeric($data["old_object_type_id"]) && is_numeric($data["old_object_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					
					return $broker->callBusinessLogic("module/event", "ObjectEventService.updateObjectEvent", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					return $broker->callUpdate("module/event", "update_object_event", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->updatePrimaryKeys($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					return $broker->updateObject("me_object_event", array(
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
						));
				}
			}
		}
	}
	
	private static function updateObjectEventsByEventId($brokers, $event_id, $data) {
		if (is_array($brokers) && is_numeric($event_id)) {
			if (self::deleteObjectEventsByEventId($brokers, $event_id)) {
				$status = true;
				$object_events = is_array($data["object_events"]) ? $data["object_events"] : array();
				
				foreach ($object_events as $object_event) {
					if (is_numeric($object_event["object_type_id"]) && is_numeric($object_event["object_id"])) {
						$object_event["event_id"] = $event_id;
					
						if (!self::insertObjectEvent($brokers, $object_event)) {
							$status = false;
						}
					}
				}
				
				return $status;
			}
		}
	}
	
	public static function updateObjectEventOrder($brokers, $event_id, $object_type_id, $object_id, $order) {
		if (is_array($brokers) && is_numeric($event_id) && is_numeric($object_type_id) && is_numeric($object_id) && is_numeric($order)) {
			$data = array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "order" => $order, "modified_date" => date("Y-m-d H:i:s"));
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "ObjectEventService.updateObjectEventOrder", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/event", "update_object_event_id_order", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("me_object_event", array(
							"order" => $data["order"], 
							"modified_date" => $data["modified_date"]
						), array(
							"event_id" => $data["event_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"], 
						));
				}
			}
		}
	}

	public static function deleteObjectEvent($brokers, $event_id, $object_type_id, $object_id) {
		if (is_array($brokers) && is_numeric($event_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$data = array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "ObjectEventService.deleteObjectEvent", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/event", "delete_object_event", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("me_object_event", $data);
				}
			}
		}
	}

	public static function deleteObjectEventsByEventId($brokers, $event_id) {
		if (is_array($brokers) && is_numeric($event_id)) {
			$data = array("event_id" => $event_id);
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "ObjectEventService.deleteObjectEventsByEventId", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/event", "delete_object_events_by_event_id", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("me_object_event", $data);
				}
			}
		}
	}

	public static function deleteObjectEventsByConditions($brokers, $conditions, $conditions_join) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "ObjectEventService.deleteObjectEventsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callDelete("module/event", "delete_object_events_by_conditions", array("conditions" => $cond));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("me_object_event", $conditions, array("conditions_join" => $conditions_join));
				}
			}
		}
	}

	public static function getObjectEvent($brokers, $event_id, $object_type_id, $object_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($event_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "ObjectEventService.getObjectEvent", array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/event", "get_object_event", array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->findById(array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$result = $broker->findObjects("me_object_event", null, array("event_id" => $event_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0];
				}
			}
		}
	}

	//$conditions must be an array containing multiple conditions
	public static function getObjectEventsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "ObjectEventService.getObjectEventsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/event", "get_object_events_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options = $options ? $options : array();
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("me_object_event", null, $conditions, $options);
				}
			}
		}
	}

	//$conditions must be an array containing multiple conditions
	public static function countObjectEventsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/event", "ObjectEventService.countObjectEventsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/event", "count_object_events_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("me_object_event", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}

	public static function getAllObjectEvents($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/event", "ObjectEventService.getAllObjectEvents", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/event", "get_all_object_events", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("me_object_event", null, null, $options);
				}
			}
		}
	}

	public static function countAllObjectEvents($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/event", "ObjectEventService.countAllObjectEvents", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/event", "count_all_object_events", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("me_object_event", null, array("no_cache" => $no_cache));
				}
			}
		}
	}

	public static function getObjectEventsByEventId($brokers, $event_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($event_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("event_id" => $event_id, "options" => $options);
					return $broker->callBusinessLogic("module/event", "ObjectEventService.getObjectEventsByEventId", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/event", "get_object_events_by_event_id", array("event_id" => $event_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->find(array("conditions" => array("event_id" => $event_id)), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("me_object_event", null, array("event_id" => $event_id), $options);
				}
			}
		}
	}

	public static function countObjectEventsByEventId($brokers, $event_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($event_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("event_id" => $event_id, "options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/event", "ObjectEventService.countObjectEventsByEventId", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/event", "count_object_events_by_event_id", array("event_id" => $event_id), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectEvent = $broker->callObject("module/event", "ObjectEvent");
					return $ObjectEvent->count(array("conditions" => array("event_id" => $event_id)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("me_object_event", array("event_id" => $event_id), array("no_cache" => $no_cache));
				}
			}
		}
	}
}
?>
