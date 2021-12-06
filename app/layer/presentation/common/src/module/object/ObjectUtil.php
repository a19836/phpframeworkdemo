<?php
include_once get_lib("org.phpframework.encryption.CryptoKeyHandler");

if (!class_exists("ObjectUtil")) {
	class ObjectUtil {
	
		const PAGE_OBJECT_TYPE_ID = 1;
		const MODULE_OBJECT_TYPE_ID = 2;
		const ARTICLE_OBJECT_TYPE_ID = 3;
		const OBJECTS_GROUP_OBJECT_TYPE_ID = 4;
		const ACTION_OBJECT_TYPE_ID = 5;
		const ATTACHMENT_OBJECT_TYPE_ID = 6;
		const COMMENT_OBJECT_TYPE_ID = 7;
		const MENU_OBJECT_TYPE_ID = 8;
		const TAG_OBJECT_TYPE_ID = 9;
		const USER_OBJECT_TYPE_ID = 10;
		const EVENT_OBJECT_TYPE_ID = 11;
		const QUIZ_OBJECT_TYPE_ID = 12;
		const QUIZ_QUESTION_OBJECT_TYPE_ID = 13;
		const QUIZ_ANSWER_OBJECT_TYPE_ID = 14;
	
		/* GENERIC FUNCTIONS */
		public static function getReservedObjectTypeIds() {
			return array_keys( self::getReservedObjectTypes() );
		}
		
		public static function getReservedObjectTypes() {
			return array(
				self::PAGE_OBJECT_TYPE_ID => "page", 
				self::MODULE_OBJECT_TYPE_ID => "module", 
				self::ARTICLE_OBJECT_TYPE_ID => "article", 
				self::OBJECTS_GROUP_OBJECT_TYPE_ID => "objects_group", 
				self::ACTION_OBJECT_TYPE_ID => "action", 
				self::ATTACHMENT_OBJECT_TYPE_ID => "attachment", 
				self::COMMENT_OBJECT_TYPE_ID => "comment", 
				self::MENU_OBJECT_TYPE_ID => "menu", 
				self::TAG_OBJECT_TYPE_ID => "tag", 
				self::USER_OBJECT_TYPE_ID => "user", 
				self::EVENT_OBJECT_TYPE_ID => "event", 
				self::QUIZ_OBJECT_TYPE_ID => "quiz", 
				self::QUIZ_QUESTION_OBJECT_TYPE_ID => "quiz_question", 
				self::QUIZ_ANSWER_OBJECT_TYPE_ID => "quiz_answer",
			);
		}
	
		/* OBJECT TYPE FUNCTIONS */
		
		public static function reinsertReservedObjectTypes($brokers) {
			$status = true;
			$object_types = self::getReservedObjectTypes();
			
			if ($object_types)
				foreach ($object_types as $object_type_id => $name) {
					self::deleteActivity($brokers, $object_type_id);
					
					$data = array("object_type_id" => $object_type_id, "name" => $name);
					if (!self::insertObjectType($brokers, $data))
						$status = false;
				}
			
			return $status;
		}
	
		public static function insertObjectType($brokers, $data) {
			if (is_array($brokers)) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
				$options = array();
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient"))
						return $broker->callBusinessLogic("module/object", "ObjectTypeService.insertObjectType", $data);
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["name"] = addcslashes($data["name"], "\\'");
						
						if ($data["object_type_id"]) {
							$options = array("hard_coded_ai_pk" => true);
							$status = $broker->callInsert("module/object", "insert_object_type_with_ai_pk", $data, $options);
							return $status ? $data["object_type_id"] : $status;
						}
						
						$status = $broker->callInsert("module/object", "insert_object_type", $data);
						return $status ? $broker->getInsertedId($options) : $status;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						if (!$data["object_type_id"])
							unset($data["object_type_id"]);
						
						$ObjectType = $broker->callObject("module/object", "ObjectType");
						$status = $ObjectType->insert($data, $ids);
						return $status ? $ids["object_type_id"] : $status;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$attributes = array(
							"name" => $data["name"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						);
						
						if ($data["object_type_id"]) {
							$options["hard_coded_ai_pk"] = true;
							$attributes["object_type_id"] = $data["object_type_id"];
						}
						
						$status = $broker->insertObject("mo_object_type", $attributes, $options);
						return $status ? ($data["object_type_id"] ? $data["object_type_id"] : $broker->getInsertedId($options)) : $status;
					}
				}
			}
		}
	
		public static function updateObjectType($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["object_type_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/object", "ObjectTypeService.updateObjectType", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["name"] = addcslashes($data["name"], "\\'");
					
						return $broker->callUpdate("module/object", "update_object_type", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectType = $broker->callObject("module/object", "ObjectType");
						return $ObjectType->update($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->updateObject("mo_object_type", array(
								"name" => $data["name"],
								"modified_date" => $data["modified_date"]
							), array(
								"object_type_id" => $data["object_type_id"], 
							));
					}
				}
			}
		}
	
		public static function deleteObjectType($brokers, $object_type_id) {
			if (is_array($brokers) && is_numeric($object_type_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/object", "ObjectTypeService.deleteObjectType", array("object_type_id" => $object_type_id));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/object", "delete_object_type", array("object_type_id" => $object_type_id));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectType = $broker->callObject("module/object", "ObjectType");
						return $ObjectType->delete($object_type_id);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mo_object_type", array("object_type_id" => $object_type_id));
					}
				}
			}
		}
	
		public static function getAllObjectTypes($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/object", "ObjectTypeService.getAllObjectTypes", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/object", "get_all_object_types", null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectType = $broker->callObject("module/object", "ObjectType");
						return $ObjectType->find(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mo_object_type", null, null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getObjectTypesByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/object", "ObjectTypeService.getObjectTypesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/object", "get_object_types_by_conditions", array("conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectType = $broker->callObject("module/object", "ObjectType");
						return $ObjectType->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mo_object_type", null, $conditions, $options);
					}
				}
			}
		}
	
		public static function countObjectTypesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/object", "ObjectTypeService.countObjectTypesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/object", "count_object_types_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectType = $broker->callObject("module/object", "ObjectType");
						return $ObjectType->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mo_object_type", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}
	}
}
?>
