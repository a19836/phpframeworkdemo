<?php
include_once get_lib("org.phpframework.util.HashCode");
include_once __DIR__ . "/TagDBDAOUtil.php"; //this file will be automatically generated on this module installation

class TagUtil {
	
	/* TAG FUNCTIONS */
	
	public static function insertTag($brokers, $data) {
		if (is_array($brokers) && $data["tag"]) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			
			$data["tag"] = strtolower($data["tag"]);
			$data["tag_id"] = HashCode::getHashCodePositive($data["tag"]);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "TagService.insertTag", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["tag"] = addcslashes($data["tag"], "\\'");
					
					$status = $broker->callInsert("module/tag", "insert_tag", $data);
					return $status ? $data["tag_id"] : $status;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Tag = $broker->callObject("module/tag", "Tag");
					$status = $Tag->insert($data, $ids);
					return $status ? $data["tag_id"] : $status;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$status = $broker->insertObject("mt_tag", array(
							"tag_id" => $data["tag_id"], 
							"tag" => $data["tag"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
					return $status ? $data["tag_id"] : $status;
				}
			}
		}
	}
	
	public static function deleteTag($brokers, $tag_id) {
		if (is_array($brokers) && is_numeric($tag_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "TagService.deleteTag", array("tag_id" => $tag_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/tag", "delete_tag", array("tag_id" => $tag_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Tag = $broker->callObject("module/tag", "Tag");
					return $Tag->delete($tag_id);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mt_tag", array("tag_id" => $tag_id));
				}
			}
		}
	}
	
	public static function getTagsByIds($brokers, $tag_ids, $options = array(), $no_cache = false) {
		if (is_array($brokers) && $tag_ids) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tag_ids_str = "";//just in case the user tries to hack the sql query. By default all tag_id should be numeric.
			$tag_ids = is_array($tag_ids) ? $tag_ids : array($tag_ids);
			foreach ($tag_ids as $tag_id) {
				$tag_ids_str .= ($tag_ids_str ? ", " : "") . "'" . addcslashes($tag_id, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "TagService.getTagsByIds", array("tag_ids" => $tag_ids, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/tag", "get_tags_by_ids", array("tag_ids" => $tag_ids_str), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Tag = $broker->callObject("module/tag", "Tag", $options);
					$conditions = array("tag_id" => array("operator" => "in", "value" => $tag_ids));
					return $Tag->find(array("conditions" => $conditions), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mt_tag", null, array("tag_id" => array("operator" => "in", "value" => $tag_ids)), $options);
				}
			}
		}
	}
	
	public static function getTagsByObjects($brokers, $object_type_id, $object_ids, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && $object_ids) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$object_ids_str = "";//just in case the user tries to hack the sql query. By default all object_id should be numeric.
			$object_ids = is_array($object_ids) ? $object_ids : array($object_ids);
			foreach ($object_ids as $object_id) {
				$object_ids_str .= ($object_ids_str ? ", " : "") . "'" . addcslashes($object_id, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "TagService.getTagsByObjects", array("object_ids" => $object_ids, "object_type_id" => $object_type_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/tag", "get_tags_by_objects", array("object_ids" => $object_ids_str, "object_type_id" => $object_type_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Tag = $broker->callObject("module/tag", "Tag", $options);
					return $Tag->callSelect("get_tags_by_objects", array("object_ids" => $object_ids_str, "object_type_id" => $object_type_id));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = TagDBDAOUtil::get_tags_by_objects(array("object_ids" => $object_ids_str, "object_type_id" => $object_type_id));
					
					return $b->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function getAllTags($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/tag", "TagService.getAllTags", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/tag", "get_all_tags", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Tag = $broker->callObject("module/tag", "Tag");
					return $Tag->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mt_tag", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllTags($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/tag", "TagService.countAllTags", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/tag", "count_all_tags", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Tag = $broker->callObject("module/tag", "Tag");
					return $Tag->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mt_tag", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getTagsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			if (isset($conditions["tag"]))
				$conditions["tag"] = strtolower($conditions["tag"]);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "TagService.getTagsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/tag", "get_tags_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Tag = $broker->callObject("module/tag", "Tag");
					return $Tag->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mt_tag", null, $conditions, $options);
				}
			}
		}
	}
	
	public static function countTagsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			if (isset($conditions["tag"]))
				$conditions["tag"] = strtolower($conditions["tag"]);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "TagService.countTagsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/tag", "count_tags_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Tag = $broker->callObject("module/tag", "Tag");
					return $Tag->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mt_tag", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getObjectsTagsString($brokers, $object_type_id, $object_ids, $options = array(), $no_cache = false) {
		$objects_tags_str = array();
		
		$brokers = is_array($brokers) ? $brokers : array($brokers);
		$tags = self::getTagsByObjects($brokers, $object_type_id, $object_ids, $options, $no_cache);
		
		if ($tags) {
			$t = count($tags);
			for ($i = 0; $i < $t; $i++) {
				$object_id = $tags[$i]["object_id"];
				$objects_tags_str[$object_id] .= ($objects_tags_str[$object_id] ? ", " : "") . $tags[$i]["tag"];
			}
		}
		
		return $objects_tags_str;
	}
	
	public static function getObjectTagsString($brokers, $object_type_id, $object_id, $options = array(), $no_cache = false) {
		$tags = self::getObjectsTagsString($brokers, $object_type_id, $object_id, $options, $no_cache);
		return $tags[$object_id];
	}
	
	/* OBJECT TAG FUNCTIONS */
	
	public static function insertObjectTag($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["tag_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.insertObjectTag", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					return $broker->callInsert("module/tag", "insert_object_tag", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->insert($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					return $broker->insertObject("mt_object_tag", array(
							"tag_id" => $data["tag_id"], 
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
	
	public static function updateObjectTag($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["tag_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.updateObjectTag", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					return $broker->callUpdate("module/tag", "update_object_tag", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					return $broker->updateObject("mt_object_tag", array(
							"group" => $data["group"], 
							"order" => $data["order"], 
							"modified_date" => $data["modified_date"]
						), array(
							"tag_id" => $data["tag_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"]
						));
				}
			}
		}
	}
	
	public static function updateObjectTagOrder($brokers, $tag_id, $object_type_id, $object_id, $order) {
		if (is_array($brokers) && is_numeric($tag_id) && is_numeric($object_type_id) && is_numeric($object_id) && is_numeric($order)) {
			$data = array("tag_id" => $tag_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "order" => $order, "modified_date" => date("Y-m-d H:i:s"));
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.updateObjectTagOrder", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/tag", "update_object_tag_order", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mt_object_tag", array(
							"order" => $order, 
							"modified_date" => $data["modified_date"]
						), array(
							"tag_id" => $data["tag_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"]
						));
				}
			}
		}
	}
	
	public static function convertTagsStringToArray($str) {
		$tags = array();
		
		if (is_array($str)) //it could be already an array bc the tags input field could be a select field with multiple selection.
			$tags_arr = $str;
		else { 
			$str = trim($str);
			$tags_arr = $str ? explode(",", str_replace('"', "", $str)) : array();
		}
		
		if ($tags_arr)
			foreach ($tags_arr as $tag) {
				$tag = trim($tag);
				
				if ($tag) {
					$tag = strtolower($tag);
					$tag_id = HashCode::getHashCodePositive($tag);
					$tags[$tag_id] = $tag;
				}
			}
		
		return $tags;
	}
	
	public static function updateObjectTags($brokers, $str, $object_type_id, $object_id) {
		if ($brokers && is_numeric($object_type_id) && is_numeric($object_id)) {
			$brokers = is_array($brokers) ? $brokers : array($brokers);
			
			$tags = self::convertTagsStringToArray($str);
			$tag_ids = array_keys($tags);
			
			$status = true;
			
			if ($tag_ids) {
				//Insert new tags if apply...
				$inserted_tags = self::getTagsByIds($brokers, $tag_ids);
				$new_tags = self::getNewTags($inserted_tags, $tags);
				
				foreach ($new_tags as $tag_id => $tag)
					if (!self::insertTag($brokers, array("tag" => $tag)))
						$status = false;
				
				//Insert new obj tags and delete old ones...
				if ($status) {
					$obj_tags = self::getObjectTagsByConditions($brokers, array("object_type_id" => $object_type_id, "object_id" => $object_id), null);
					$obj_tags_to_insert = self::getNewTags($obj_tags, $tags);
					
					if ($obj_tags)
						foreach ($obj_tags as $obj_tag)
							if (!isset($tags[ $obj_tag["tag_id"] ]) && !self::deleteObjectTag($brokers, $obj_tag["tag_id"], $object_type_id, $object_id))
								$status = false;
					
					foreach ($obj_tags_to_insert as $tag_id => $tag)
						if (!self::insertObjectTag($brokers, array("tag_id" => $tag_id, "object_type_id" => $object_type_id, "object_id" => $object_id)))
							$status = false;
				}
			}
			//Delete old obj tags 
			else if (!self::deleteObjectTagsByObject($brokers, $object_type_id, $object_id))
				$status = false;
			
			return $status;
		}
	}
	
	public static function getNewTags($inserted_tags, $all_tags) {
		$new_tags = array();
		$inserted_tags = $inserted_tags && is_array($inserted_tags) ? $inserted_tags : array();
		
		foreach ($all_tags as $tag_id => $tag) {
			$exists = false;
		
			foreach ($inserted_tags as $t) {
				if ($t["tag_id"] == $tag_id) {
					$exists = true;
					break;
				}
			}
		
			if (!$exists) {
				$new_tags[$tag_id] = $tag;
			}
		}
	
		return $new_tags;
	}
	
	public static function deleteObjectTag($brokers, $tag_id, $object_type_id, $object_id) {
		if (is_array($brokers) && is_numeric($tag_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.deleteObjectTag", array("tag_id" => $tag_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/tag", "delete_object_tag", array("tag_id" => $tag_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->delete(array("tag_id" => $tag_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mt_object_tag", array("tag_id" => $tag_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
				}
			}
		}
	}
	
	public static function deleteObjectTagsByObject($brokers, $object_type_id, $object_id) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.deleteObjectTagsByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/tag", "delete_object_tags_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->delete(array("object_type_id" => $object_type_id, "object_id" => $object_id));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mt_object_tag", array("object_type_id" => $object_type_id, "object_id" => $object_id));
				}
			}
		}
	}
	
	public static function deleteObjectTagsByTagId($brokers, $tag_id) {
		if (is_array($brokers) && is_numeric($tag_id)) {
			$data = array("tag_id" => $tag_id);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.deleteObjectTagsByTagId", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/tag", "delete_object_tags_by_tag_id", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mt_object_tag", $data);
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function getObjectTagsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.getObjectTagsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/tag", "get_object_tag_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mt_object_tag", null, $conditions, $options);
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function countObjectTagsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.countObjectTagsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/tag", "count_object_tag_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mt_object_tag", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getObjectTagsByTagId($brokers, $tag_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($tag_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("tag_id" => $tag_id, "options" => $options);
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.getObjectTagsByTagId", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/tag", "get_object_tags_by_tag_id", array("tag_id" => $tag_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->find(array("conditions" => array("tag_id" => $tag_id)), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mt_object_tag", null, array("tag_id" => $tag_id), $options);
				}
			}
		}
	}
	
	public static function countObjectTagsByTagId($brokers, $tag_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($tag_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("tag_id" => $tag_id, "options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.countObjectTagsByTagId", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/tag", "count_object_tags_by_tag_id", array("tag_id" => $tag_id), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->count(array("conditions" => array("tag_id" => $tag_id)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mt_object_tag", array("tag_id" => $tag_id), array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getAllObjectTags($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.getAllObjectTags", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/tag", "get_all_object_tags", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mt_object_tag", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllObjectTags($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/tag", "ObjectTagService.countAllObjectTags", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/tag", "count_all_object_tags", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectTag = $broker->callObject("module/tag", "ObjectTag");
					return $ObjectTag->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mt_object_tag", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
}
?>
