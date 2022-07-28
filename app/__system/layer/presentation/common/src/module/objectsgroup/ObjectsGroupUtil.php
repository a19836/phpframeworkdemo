<?php
include_once $EVC->getModulePath("tag/TagUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
include_once __DIR__ . "/ObjectsGroupDBDAOUtil.php"; //this file will be automatically generated on this module installation
include_once __DIR__ . "/ObjectObjectsGroupDBDAOUtil.php"; //this file will be automatically generated on this module installation

if (!class_exists("ObjectsGroupUtil")) {
	class ObjectsGroupUtil {
		
		/* GENERIC FUNCTIONS */
		
		/* INVALID ARRAY FILES:
		1- Wrong key value - Cannot be an array. Must be a string!
			$_FILES = Array(
			    [banner_1] => Array(
				    [name] => array([foo] => article.zip)
				    [type] => array([foo] => application/zip)
				    [tmp_name] => array([foo] => /tmp/phphRyEWH)
				    [error] => array([foo] => 0)
				    [size] => array([foo] => 67138)
				)
			)
		*/
		public static function checkIfSingleFileFieldsAreValid($files) {
			if ($files) {
				foreach ($files as $group => $file) {
					if (is_array($file["name"]))
						return false;
				}
			}
			return true;
		}
		
		/* INVALID ARRAY FILES:
		1- Wrong key value - Cannot be an array. Must be a string!
			$_FILES = Array(
			    [banner] => Array(
				    [name] => Array(
					    [0] => array([foo] => action.zip)
					    [1] => article.zip
					)
				    [type] => Array(
					    [0] => array([foo] => application/zip)
					    [1] => application/zip
					)
				    [tmp_name] => Array(
					    [0] => array([foo] => /tmp/php7Ga91O)
					    [1] => /tmp/php123a91O
					)
				    [error] => Array(
					    [0] => array([foo] => 0)
					    [1] => 0
					)
				    [size] => Array(
					    [0] => array([foo] => 45335)
					    [1] => 85305
					)
				)
			)
		2- Missing array items
			$_FILES = Array(
			    [banner_1] => Array(
				    [name] => article.zip
				    [type] => application/zip
				    [tmp_name] => /tmp/phphRyEWH
				    [error] => 0
				    [size] => 67138
				)
			)
		*/
		public static function checkIfMultipleFileFieldsAreValid($files) {
			if ($files) {
				foreach ($files as $group => $items) {
					if (!is_array($items["name"]))
						return false;
				
					foreach ($items["name"] as $i => $item) {
						if (is_array($item))
							return false;
					}
				}
			}
			return true;
		}
		
		/*
		$_FILES = Array(
		    [banner_1] => Array(
			    [name] => article.zip
			    [type] => application/zip
			    [tmp_name] => /tmp/phphRyEWH
			    [error] => 0
			    [size] => 67138
			)
		)
		*/
		public static function getSingleFiles($files) {
			$new_files = array();
			
			if ($files) {
				foreach ($files as $group => $file) {
					$new_files[$group] = array(
						"name" => $file["name"],
						"type" => $file["type"],
						"tmp_name" => $file["tmp_name"],
						"error" => $file["error"],
						"size" => $file["size"],
					);
				}
			}
			
			return $new_files;
		}
		
		/*
		$_FILES = Array(
		    [banner] => Array(
			    [name] => Array(
				    [0] => action.zip
				    [1] => article.zip
				)
			    [type] => Array(
				    [0] => application/zip
				    [1] => application/zip
				)
			    [tmp_name] => Array(
				    [0] => /tmp/php7Ga91O
				    [1] => /tmp/php123a91O
				)
			    [error] => Array(
				    [0] => 0
				    [1] => 0
				)
			    [size] => Array(
				    [0] => 45335
				    [1] => 85305
				)
			)
		)
		*/
		public static function getMultipleFiles($files) {
			$new_files = array();
			
			if ($files) {
				foreach ($files as $group => $items) {
					foreach ($items["name"] as $i => $item) {
						$new_files[$i][$group] = array(
							"name" => $items["name"][$i],
							"type" => $items["type"][$i],
							"tmp_name" => $items["tmp_name"][$i],
							"error" => $items["error"][$i],
							"size" => $items["size"][$i],
						);
					}
				}
			}
			
			return $new_files;
		}
		
		/* OBJECTS GROUP FUNCTIONS */
	
		public static function insertObjectsGroup($EVC, $data, $files = null, $brokers = array()) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers)) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$objects_group_id = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.insertObjectsGroup", $data);
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["object"] = addcslashes(json_encode($data["object"]), "\\'");
					
						$status = $broker->callInsert("module/objectsgroup", "insert_objects_group", $data);
						$objects_group_id = $status ? $broker->getInsertedId() : $status;
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$status = $ObjectsGroup->insert($data, $ids);
						$objects_group_id = $status ? $ids["objects_group_id"] : $status;
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$status = $broker->insertObject("mog_object_objects_group", array(
								"object" => $data["object"], 
								"created_date" => $data["created_date"], 
								"modified_date" => $data["modified_date"]
							));
						$objects_group_id = $status ? $broker->getInsertedId() : $status;
						break;
					}
				}
				
				if ($objects_group_id) {
					$status = TagUtil::updateObjectTags($broker, $data["tags"], ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, $objects_group_id) && self::uploadObjectsGroupAttachments($EVC, $data, $files, $objects_group_id, $broker) && self::updateObjectObjectsGroupsByObjectsGroupId(array($broker), $objects_group_id, $data);
					
					return $status ? $objects_group_id : false;
				}
			}
		}
	
		public static function updateObjectsGroup($EVC, $data, $files = null, $brokers = array()) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers) && is_numeric($data["objects_group_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$status = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.updateObjectsGroup", $data);
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["object"] = addcslashes(json_encode($data["object"]), "\\'");
					
						$status = $broker->callUpdate("module/objectsgroup", "update_objects_group", $data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$status = $ObjectsGroup->update($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$status = $broker->updateObject("mog_objects_group", array(
								"object" => $data["object"], 
								"modified_date" => $data["modified_date"]
							), array(
								"objects_group_id" => $data["objects_group_id"]
							));
						break;
					}
				}
				
				if ($status) {
					$status = TagUtil::updateObjectTags($broker, $data["tags"], ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, $data["objects_group_id"]) && self::uploadObjectsGroupAttachments($EVC, $data, $files, $data["objects_group_id"], $broker, true) && self::updateObjectObjectsGroupsByObjectsGroupId(array($broker), $data["objects_group_id"], $data);
				}
			}
			
			return $status;
		}
		
		private static function uploadObjectsGroupAttachments($EVC, $data, $files, $objects_group_id, $broker, $is_update = false) {
			$status = true;
			
			if (is_array($files))
				foreach ($files as $group => $file) {
					$group_id = is_numeric($group) ? $group : HashCode::getHashCodePositive($group);
					
					if (!$data[$group] && $is_update)
						AttachmentUtil::deleteFileByObject($EVC, ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, $objects_group_id, $group_id, array($broker));
					
					if ($file["tmp_name"]) {
						//insert or update photo
						$photo_id = AttachmentUtil::replaceObjectFile($EVC, $file, $data[$group], ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, $objects_group_id, $group_id, 0, array($broker));
						
						if (!$photo_id)
							$status = false;
					}
				}
			
			return $status;
		}
	
		public static function deleteObjectsGroup($EVC, $objects_group_id, $brokers = array()) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
			
			if (is_array($brokers) && is_numeric($objects_group_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$status = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.deleteObjectsGroup", array("objects_group_id" => $objects_group_id));
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$status = $broker->callDelete("module/objectsgroup", "delete_objects_group", array("objects_group_id" => $objects_group_id));
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$status = $ObjectsGroup->delete($objects_group_id);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mog_objects_group", array("objects_group_id" => $objects_group_id));
					}
				}
				
				if ($status) {
					$status = TagUtil::deleteObjectTagsByObject(array($broker), ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, $objects_group_id) && AttachmentUtil::deleteFileByObject($EVC, ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, $objects_group_id, null, $brokers) && self::deleteObjectObjectsGroupsByObjectsGroupId(array($broker), $objects_group_id);
				}
			}
			
			return $status;
		}
	
		public static function getObjectsGroupProperties($EVC, $objects_group_id, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers) && is_numeric($objects_group_id)) {
				$data = null;
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroup", array("objects_group_id" => $objects_group_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data = $broker->callSelect("module/objectsgroup", "get_objects_group", array("objects_group_id" => $objects_group_id), array("no_cache" => $no_cache));
						$data["object"] = json_decode($data["object"], true);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$data = $ObjectsGroup->findById($objects_group_id, null, array("no_cache" => $no_cache));
						$data["object"] = json_decode($data["object"], true);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data = $broker->findObjects("mog_objects_group", null, array("objects_group_id" => $objects_group_id), array("no_cache" => $no_cache));
						$data = $data[0];
						$data["object"] = json_decode($data["object"], true);
						break;
					}
				}
				
				if ($data) {
					$data["tags"] = TagUtil::getObjectTagsString($broker, ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, $objects_group_id);
					$attachments = self::getObjectsGroupAttachments($EVC, $objects_group_id, array($broker));
					$data["attachments"] = $attachments[$objects_group_id];
				}
				
				return $data;
			}
		}
	
		public static function getObjectsGroupsByConditions($EVC, $conditions, $conditions_join, $brokers = array(), $options = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$data = $broker->callSelect("module/objectsgroup", "get_objects_groups_by_conditions", array("conditions" => $cond), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$data = $ObjectsGroup->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						$data = $broker->findObjects("mog_objects_group", null, $conditions, $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
				}
				
				self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
				return $data;
			}
		}
	
		public static function countObjectsGroupsByConditions($EVC, $conditions, $conditions_join, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/objectsgroup", "count_objects_groups_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						return $ObjectsGroup->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mog_objects_group", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}
		
		//$conditions must be an array containing multiple conditions
		public static function getObjectsGroupsByObjectAndConditions($EVC, $object_type_id, $object_id, $conditions, $conditions_join, $brokers = array(), $options = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectAndConditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$data = $broker->callSelect("module/objectsgroup", "get_objects_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$data = $ObjectsGroup->callSelect("get_objects_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$sql = ObjectsGroupDBDAOUtil::get_objects_groups_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
						
						$data = $broker->getSQL($sql, $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
				}
				
				self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
				return $data;
			}
		}
		
		//$conditions must be an array containing multiple conditions
		public static function countObjectsGroupsByObjectAndConditions($EVC, $object_type_id, $object_id, $conditions, $conditions_join, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectAndConditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$result = $broker->callSelect("module/objectsgroup", "count_objects_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
					
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$sql = ObjectsGroupDBDAOUtil::count_objects_groups_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
		
		public static function getObjectsGroupsByIds($EVC, $objects_group_ids, $brokers = array(), $options = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				$objects_group_ids = is_array($objects_group_ids) ? implode(', ', $objects_group_ids) : $objects_group_ids;
				$objects_group_ids_str = str_replace(array("'", "\\"), "", $objects_group_ids_str);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByIds", array("objects_group_ids" => $objects_group_ids, "options" => $options), $options);
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data = $broker->callSelect("module/objectsgroup", "get_objects_groups_by_ids", array("objects_group_ids" => $objects_group_ids_str), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$conditions = array("objects_group_id" => array("operator" => "in", "value" => $objects_group_ids));
						$data = $ObjectsGroup->find(array("conditions" => $conditions), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data = $broker->findObjects("mog_objects_group", null, array("objects_group_id" => array("operator" => "in", "value" => $objects_group_ids)), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
				}
				
				self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
				return $data;
			}
		}
	
		//$tags is a string containing multiple objects_group tags
		//This method will return the objects_groups that contains at least one tag in $tags
		public static function getObjectsGroupsByTags($EVC, $tags, $brokers = array(), $options = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
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
						$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByTags", array("tags" => $tags, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => $options), $options);
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data = $broker->callSelect("module/objectsgroup", "get_objects_groups_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$data = $ObjectsGroup->callSelect("get_objects_groups_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = ObjectsGroupDBDAOUtil::get_objects_groups_by_tags(array("tags" => $tags_str, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
						
						$data = $broker->getSQL($sql, $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
				}
				
				self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
				return $data;
			}
		}
	
		//$tags is a string containing multiple objects_group tags
		//This method will return the objects_groups that contains at least one tag in $tags
		public static function countObjectsGroupsByTags($EVC, $tags, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers)) {
				$tags = TagUtil::convertTagsStringToArray($tags);
				$tags = array_values($tags);
		
				$tags_str = "";
				foreach ($tags as $tag) {
					$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
				}
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByTags", array("tags" => $tags, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/objectsgroup", "count_objects_groups_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$result = $ObjectsGroup->callSelect("count_objects_groups_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = ObjectsGroupDBDAOUtil::count_objects_groups_by_tags(array("tags" => $tags_str, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
	
		//$tags is a string containing multiple objects_group tags
		//This method will return the objects_groups that contains at least one tag in $tags
		public static function getObjectsGroupsByObjectAndTags($EVC, $object_type_id, $object_id, $tags, $brokers = array(), $options = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
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
						$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => $options), $options);
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data = $broker->callSelect("module/objectsgroup", "get_objects_groups_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$data = $ObjectsGroup->callSelect("get_objects_groups_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = ObjectsGroupDBDAOUtil::get_objects_groups_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
						
						$data = $broker->getSQL($sql, $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
				}
				
				self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
				return $data;
			}
		}
	
		//$tags is a string containing multiple objects_group tags
		//This method will return the objects_groups that contains at least one tag in $tags
		public static function countObjectsGroupsByObjectAndTags($EVC, $object_type_id, $object_id, $tags, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$tags = TagUtil::convertTagsStringToArray($tags);
				$tags = array_values($tags);
		
				$tags_str = "";
				foreach ($tags as $tag) {
					$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
				}
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/objectsgroup", "count_objects_groups_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = ObjectsGroupDBDAOUtil::count_objects_groups_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
	
		//$tags is a string containing multiple objects_group tags
		//This method will return the objects_groups that contains at least one tag in $tags
		public static function getObjectsGroupsByObjectGroupAndTags($EVC, $object_type_id, $object_id, $group = null, $tags, $brokers = array(), $options = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
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
						
						$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectGroupAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => $options), $options);
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						
						$data = $broker->callSelect("module/objectsgroup", "get_objects_groups_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$data = $ObjectsGroup->callSelect("get_objects_groups_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$sql = ObjectsGroupDBDAOUtil::get_objects_groups_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
						
						$data = $broker->getSQL($sql, $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
				}
				
				self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
				return $data;
			}
		}
	
		//$tags is a string containing multiple objects_group tags
		//This method will return the objects_groups that contains at least one tag in $tags
		public static function countObjectsGroupsByObjectGroupAndTags($EVC, $object_type_id, $object_id, $group = null, $tags, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
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
						
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectGroupAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						
						$result = $broker->callSelect("module/objectsgroup", "count_objects_groups_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$sql = ObjectsGroupDBDAOUtil::count_objects_groups_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
	
		//$tags is a string containing multiple objects_group tags
		//This method will return the objects_groups that contains all $tags
		public static function getObjectsGroupsWithAllTags($EVC, $tags, $brokers = array(), $options = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
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
							$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsWithAllTags", array("tags" => $tags, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => $options), $options);
							break;
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							$data = $broker->callSelect("module/objectsgroup", "get_objects_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
							self::prepareObjectsGroupsListData($data);
							break;
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
							$data = $ObjectsGroup->callSelect("get_objects_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
							self::prepareObjectsGroupsListData($data);
							break;
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							$sql = ObjectsGroupDBDAOUtil::get_objects_groups_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
							
							$data = $broker->getSQL($sql, $options);
							self::prepareObjectsGroupsListData($data);
							break;
						}
					}
				
					self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
					return $data;
				}
			}
		}
	
		public static function countObjectsGroupsWithAllTags($EVC, $tags, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
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
							return $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsWithAllTags", array("tags" => $tags, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							$result = $broker->callSelect("module/objectsgroup", "count_objects_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
							return $result[0]["total"];
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
							$result = $ObjectsGroup->callSelect("count_objects_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
							return $result[0]["total"];
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							$sql = ObjectsGroupDBDAOUtil::count_objects_groups_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
							
							$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
							return $result[0]["total"];
						}
					}
				}
			}
		}
		
		//$tags is a string containing multiple objects_group tags
		//This method will return the objects_groups that contains all $tags
		public static function getObjectsGroupsByObjectWithAllTags($EVC, $object_type_id, $object_id, $tags, $brokers = array(), $options = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
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
							$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => $options), $options);
							break;
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							$data = $broker->callSelect("module/objectsgroup", "get_objects_groups_by_object_and_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
							self::prepareObjectsGroupsListData($data);
							break;
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
							$data = $ObjectsGroup->callSelect("get_objects_groups_by_object_and_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
							self::prepareObjectsGroupsListData($data);
							break;
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							$sql = ObjectsGroupDBDAOUtil::get_objects_groups_by_object_and_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
							
							$data = $broker->getSQL($sql, $options);
							self::prepareObjectsGroupsListData($data);
							break;
						}
					}
				
					self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
					return $data;
				}
			}
		}
	
		public static function countObjectsGroupsByObjectWithAllTags($EVC, $object_type_id, $object_id, $tags, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
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
							return $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							$result = $broker->callSelect("module/objectsgroup", "count_objects_groups_by_object_and_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
							return $result[0]["total"];
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
							$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_and_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
							return $result[0]["total"];
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							$sql = ObjectsGroupDBDAOUtil::count_objects_groups_by_object_and_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
							
							$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
							return $result[0]["total"];
						}
					}
				}
			}
		}
		
		//$tags is a string containing multiple objects_group tags
		//This method will return the objects_groups that contains all $tags
		public static function getObjectsGroupsByObjectGroupWithAllTags($EVC, $object_type_id, $object_id, $group = null, $tags, $brokers = array(), $options = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
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
							
							$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectGroupWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => $options), $options);
							break;
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							$group = is_numeric($group) ? $group : 0;
							
							$data = $broker->callSelect("module/objectsgroup", "get_objects_groups_by_object_group_and_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
							self::prepareObjectsGroupsListData($data);
							break;
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$group = is_numeric($group) ? $group : 0;
							
							$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
							$data = $ObjectsGroup->callSelect("get_objects_groups_by_object_group_and_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), $options);
							self::prepareObjectsGroupsListData($data);
							break;
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							$group = is_numeric($group) ? $group : 0;
							$sql = ObjectsGroupDBDAOUtil::get_objects_groups_by_object_group_and_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
							
							$data = $broker->getSQL($sql, $options);
							self::prepareObjectsGroupsListData($data);
							break;
						}
					}
				
					self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
					return $data;
				}
			}
		}
	
		public static function countObjectsGroupsByObjectGroupWithAllTags($EVC, $object_type_id, $object_id, $group = null, $tags, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
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
							
							return $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectGroupWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							$group = is_numeric($group) ? $group : 0;
							
							$result = $broker->callSelect("module/objectsgroup", "count_objects_groups_by_object_group_and_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
							return $result[0]["total"];
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$group = is_numeric($group) ? $group : 0;
							
							$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
							$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_group_and_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID), array("no_cache" => $no_cache));
							return $result[0]["total"];
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							$group = is_numeric($group) ? $group : 0;
							$sql = ObjectsGroupDBDAOUtil::count_objects_groups_by_object_group_and_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID));
							
							$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
							return $result[0]["total"];
						}
					}
				}
			}
		}
		
		public static function getAllObjectsGroups($EVC, $brokers = array(), $options = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getAllObjectsGroups", array("options" => $options), $options);
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data = $broker->callSelect("module/objectsgroup", "get_all_objects_groups", null, $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$data = $ObjectsGroup->find(null, $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data = $broker->findObjects("mog_objects_group", null, null, $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
				}
				
				self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
				return $data;
			}
		}
		
		public static function countAllObjectsGroups($EVC, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countAllObjectsGroups", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/objectsgroup", "count_all_objects_groups", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						return $ObjectsGroup->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->count("mog_objects_group", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getObjectsGroupsByObject($EVC, $object_type_id, $object_id, $brokers = array(), $options, $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "options" => $options), array("no_cache" => $no_cache));
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data = $broker->callSelect("module/objectsgroup", "get_objects_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$data = $ObjectsGroup->callSelect("get_objects_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = ObjectsGroupDBDAOUtil::get_objects_groups_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
						
						$data = $broker->getSQL($sql, $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
				}
				
				self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
				return $data;
			}
		}
	
		public static function countObjectsGroupsByObject($EVC, $object_type_id, $object_id, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/objectsgroup", "count_objects_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$result = $ObjectsGroup->callSelect("count_objects_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = ObjectsGroupDBDAOUtil::count_objects_groups_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}

		public static function getObjectsGroupsByObjectGroup($EVC, $object_type_id, $object_id, $group = null, $brokers = array(), $options, $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$group = is_numeric($group) ? $group : null;
						
						$data = $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "options" => $options), array("no_cache" => $no_cache));
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
					
						$data = $broker->callSelect("module/objectsgroup", "get_objects_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
					
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$data = $ObjectsGroup->callSelect("get_objects_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$sql = ObjectsGroupDBDAOUtil::get_objects_groups_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
						
						$data = $broker->getSQL($sql, $options);
						self::prepareObjectsGroupsListData($data);
						break;
					}
				}
				
				self::prepareObjectsGroupsWithTags($EVC, $data, $broker);
				return $data;
			}
		}
	
		public static function countObjectsGroupsByObjectGroup($EVC, $object_type_id, $object_id, $group = null, $brokers = array(), $no_cache = false) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$group = is_numeric($group) ? $group : null;
						
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
					
						$result = $broker->callSelect("module/objectsgroup", "count_objects_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
					
						$ObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectsGroup");
						$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$sql = ObjectsGroupDBDAOUtil::count_objects_groups_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
		
		/* OBJECT OBJECTS GROUP FUNCTIONS */

		public static function insertObjectObjectsGroup($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["objects_group_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"])) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.insertObjectObjectsGroup", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
						return $broker->callInsert("module/objectsgroup", "insert_object_objects_group", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->insert($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						return $broker->insertObject("mog_object_objects_group", array(
								"objects_group_id" => $data["objects_group_id"], 
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

		public static function updateObjectObjectsGroup($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["new_objects_group_id"]) && is_numeric($data["new_object_type_id"]) && is_numeric($data["new_object_id"]) && is_numeric($data["old_objects_group_id"]) && is_numeric($data["old_object_type_id"]) && is_numeric($data["old_object_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.updateObjectObjectsGroup", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
						return $broker->callUpdate("module/objectsgroup", "update_object_objects_group", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->updatePrimaryKeys($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						return $broker->updateObject("mog_object_objects_group", array(
								"objects_group_id" => $data["new_objects_group_id"], 
								"object_type_id" => $data["new_object_type_id"], 
								"object_id" => $data["new_object_id"], 
								"group" => $data["group"], 
								"order" => $data["order"], 
								"modified_date" => $data["modified_date"]
							), array(
								"objects_group_id" => $data["old_objects_group_id"], 
								"object_type_id" => $data["old_object_type_id"], 
								"object_id" => $data["old_object_id"], 
							));
					}
				}
			}
		}
		
		private static function updateObjectObjectsGroupsByObjectsGroupId($brokers, $objects_group_id, $data) {
			if (is_array($brokers) && is_numeric($objects_group_id)) {
				if (self::deleteObjectObjectsGroupsByObjectsGroupId($brokers, $objects_group_id)) {
					$status = true;
					$object_objects_groups = is_array($data["object_objects_groups"]) ? $data["object_objects_groups"] : array();
					
					foreach ($object_objects_groups as $object_objects_group) {
						if (is_numeric($object_objects_group["object_type_id"]) && is_numeric($object_objects_group["object_id"])) {
							$object_objects_group["objects_group_id"] = $objects_group_id;
					
							if (!self::insertObjectObjectsGroup($brokers, $object_objects_group)) {
								$status = false;
							}
						}
					}
				
					return $status;
				}
			}
		}

		public static function deleteObjectObjectsGroup($brokers, $objects_group_id, $object_type_id, $object_id) {
			if (is_array($brokers) && is_numeric($objects_group_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$data = array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.deleteObjectObjectsGroup", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/objectsgroup", "delete_object_objects_group", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mog_object_objects_group", $data);
					}
				}
			}
		}

		public static function deleteObjectObjectsGroupsByObjectsGroupId($brokers, $objects_group_id) {
			if (is_array($brokers) && is_numeric($objects_group_id)) {
				$data = array("objects_group_id" => $objects_group_id);
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.deleteObjectObjectsGroupsByObjectsGroupId", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/objectsgroup", "delete_object_objects_groups_by_objects_group_id", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mog_object_objects_group", $data);
					}
				}
			}
		}

		public static function deleteObjectObjectsGroupsByConditions($brokers, $conditions, $conditions_join) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.deleteObjectObjectsGroupsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callDelete("module/objectsgroup", "delete_object_objects_groups_by_conditions", array("conditions" => $cond));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mog_object_objects_group", $conditions, array("conditions_join" => $conditions_join));
					}
				}
			}
		}

		public static function getObjectObjectsGroup($brokers, $objects_group_id, $object_type_id, $object_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($objects_group_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.getObjectObjectsGroup", array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/objectsgroup", "get_object_objects_group", array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
						return $result[0];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->findById(array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$result = $broker->findObjects("mog_object_objects_group", null, array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
						return $result[0];
					}
				}
			}
		}

		//$conditions must be an array containing multiple conditions
		public static function getObjectObjectsGroupsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.getObjectObjectsGroupsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/objectsgroup", "get_object_objects_groups_by_conditions", array("conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mog_object_objects_group", null, $conditions, $options);
					}
				}
			}
		}

		//$conditions must be an array containing multiple conditions
		public static function countObjectObjectsGroupsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.countObjectObjectsGroupsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/objectsgroup", "count_object_objects_groups_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mog_object_objects_group", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}

		public static function getAllObjectObjectsGroups($brokers, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.getAllObjectObjectsGroups", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/objectsgroup", "get_all_object_objects_groups", null, $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->find(null, $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mog_object_objects_group", null, null, $options);
					}
				}
			}
		}

		public static function countAllObjectObjectsGroups($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.countAllObjectObjectsGroups", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/objectsgroup", "count_all_object_objects_groups", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mog_object_objects_group", null, array("no_cache" => $no_cache));
					}
				}
			}
		}

		public static function getObjectObjectsGroupsByObjectsGroupId($brokers, $objects_group_id, $options = array(), $no_cache = false) {
			if (is_array($brokers) && is_numeric($objects_group_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("objects_group_id" => $objects_group_id, "options" => $options);
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.getObjectObjectsGroupsByObjectsGroupId", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/objectsgroup", "get_object_objects_groups_by_objects_group_id", array("objects_group_id" => $objects_group_id), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->find(array("conditions" => array("objects_group_id" => $objects_group_id)), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mog_object_objects_group", null, array("objects_group_id" => $objects_group_id), $options);
					}
				}
			}
		}

		public static function countObjectObjectsGroupsByObjectsGroupId($brokers, $objects_group_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($objects_group_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("objects_group_id" => $objects_group_id, "options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.countObjectObjectsGroupsByObjectsGroupId", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/objectsgroup", "count_object_objects_groups_by_objects_group_id", array("objects_group_id" => $objects_group_id), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectObjectsGroup = $broker->callObject("module/objectsgroup", "ObjectObjectsGroup");
						return $ObjectObjectsGroup->count(array("conditions" => array("objects_group_id" => $objects_group_id)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mog_object_objects_group", array("objects_group_id" => $objects_group_id), array("no_cache" => $no_cache));
					}
				}
			}
		}
		
		/* PRIVATE FUNCTIONS */
		
		private static function prepareObjectsGroupsListData(&$data) {
			if ($data) {
				$t = count($data);
				for ($i = 0; $i < $t; $i++)
					$data[$i]["object"] = json_decode($data[$i]["object"], true);
			}
		}
		
		private static function prepareObjectsGroupsWithTags($EVC, &$data, $broker) {
			if ($broker && is_array($data)) {
				$t = count($data);
				
				$objects_group_ids = array();
				for ($i = 0; $i < $t; $i++)
					$objects_group_ids[$i] = $data[$i]["objects_group_id"];
				
				$indexes = array_flip($objects_group_ids);
				
				$tags = TagUtil::getObjectsTagsString($broker, ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, $objects_group_ids);
				if ($tags)
					foreach ($tags as $objects_group_id => $objects_group_tags) {
						$idx = $indexes[$objects_group_id];
						$data[$idx]["tags"] = $objects_group_tags;
					}
				
				$attachments = self::getObjectsGroupAttachments($EVC, $objects_group_ids, array($broker));
				if ($attachments)
					foreach ($attachments as $objects_group_id => $objects_group_attachments) {
						$idx = $indexes[$objects_group_id];
						$data[$idx]["attachments"] = $objects_group_attachments;
					}
			}
		}
		
		private static function getObjectsGroupAttachments($EVC, $objects_group_ids, $brokers = array()) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
			
			$new_attachments = array();
			$attachments = AttachmentUtil::getAttachmentsByObjects($brokers, ObjectUtil::OBJECTS_GROUP_OBJECT_TYPE_ID, $objects_group_ids);
			
			if ($attachments) {
				$folder_path = AttachmentUtil::getAttachmentsFolderPath($EVC);
				$url = AttachmentUtil::getAttachmentsFolderUrl($EVC);
				
				foreach ($attachments as $idx => $attachment) {
					$path = $attachment["path"];
					
					if ($path) {
						$attachment["absolute_path"] = $folder_path . $path;
						$attachment["url"] = $url . $path;
					}
					
					$group = $attachment["group"];
					if (strlen($group)) {
						$new_attachments[ $attachment["object_id"] ][$group][] = $attachment;
					}
					else {
						$new_attachments[ $attachment["object_id"] ][] = $attachment;
					}
				}
			}
			
			return $new_attachments;
		}
	}
}
?>
