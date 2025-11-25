<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

namespace Module\ObjectsGroup;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/ObjectsGroupDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class ObjectsGroupService extends \soa\CommonService {
	private $ObjectsGroup;
	
	private function getObjectsGroupHbnObj($b, $options) {
		if (!$this->ObjectsGroup)
			$this->ObjectsGroup = $b->callObject("module/objectsgroup", "ObjectsGroup", $options);
		
		return $this->ObjectsGroup;
	}
	
	/**
	 * @param (name=data[object], type=mixed, not_null=1, min_length=1)
	 */
	public function insertObjectsGroup($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["object"] = addcslashes(json_encode($data["object"]), "\\'");
			
			$status = $b->callInsert("module/objectsgroup", "insert_objects_group", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
			$ids = null;
			$status = $ObjectsGroup->insert($data, $ids);
			return $status ? (isset($ids["objects_group_id"]) ? $ids["objects_group_id"] : null) : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("mog_objects_group", array(
					"object" => $data["object"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.insertObjectsGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[objects_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object], type=mixed, not_null=1, min_length=1)
	 */
	public function updateObjectsGroup($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["object"] = addcslashes(json_encode($data["object"]), "\\'");
			
			return $b->callUpdate("module/objectsgroup", "update_objects_group", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
			return $ObjectsGroup->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mog_objects_group", array(
					"object" => $data["object"],
					"modified_date" => $data["modified_date"]
				), array(
					"objects_group_id" => $data["objects_group_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.updateObjectsGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[objects_group_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteObjectsGroup($data) {
		$objects_group_id = $data["objects_group_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/objectsgroup", "delete_objects_group", array("objects_group_id" => $objects_group_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
			return $ObjectsGroup->delete($objects_group_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mog_objects_group", array("objects_group_id" => $objects_group_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.deleteObjectsGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[objects_group_id], type=bigint, not_null=1, length=19)  
	 */
	public function getObjectsGroup($data) {
		$objects_group_id = $data["objects_group_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/objectsgroup", "get_objects_group", array("objects_group_id" => $objects_group_id), $options);
			$result = isset($result[0]) ? $result[0] : null;
			$result["object"] = isset($result["object"]) ? json_decode($result["object"], true) : null;
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
			$result = $ObjectsGroup->findById($objects_group_id);
			$result["object"] = isset($result["object"]) ? json_decode($result["object"], true) : null;
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mog_objects_group", null, array("objects_group_id" => $objects_group_id), $options);
			return isset($result[0]) ? $result[0] : null;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][objects_group_id], type=bigint|array, length=19)
	 */
	public function getObjectsGroupsByConditions($data) {
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/objectsgroup", "get_objects_groups_by_conditions", array("conditions" => $cond), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
				$result = $ObjectsGroup->find($data, $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				return $b->findObjects("mog_objects_group", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][objects_group_id], type=bigint|array, length=19)
	 */
	public function countObjectsGroupsByConditions($data) {
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/objectsgroup", "count_objects_groups_by_conditions", array("conditions" => $cond), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
				return $ObjectsGroup->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				return $b->countObjects("mog_objects_group", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint|array, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint|array, not_null=1, length=19)  
	 * @param (name=data[conditions][objects_group_id], type=bigint|array, length=19)
	 */
	public function getObjectsGroupsByObjectAndConditions($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/objectsgroup", "get_objects_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				
				$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
				$result = $ObjectsGroup->callSelect("get_objects_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$sql = ObjectsGroupDBDAOServiceUtil::get_objects_groups_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
				
				$result = $b->getSQL($sql, $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectAndConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint|array, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint|array, not_null=1, length=19) 
	 * @param (name=data[conditions][objects_group_id], type=bigint|array, length=19)
	 */
	public function countObjectsGroupsByObjectAndConditions($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/objectsgroup", "count_objects_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				
				$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
				$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$sql = ObjectsGroupDBDAOServiceUtil::count_objects_groups_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
				
				$result = $b->getSQL($sql, $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectAndConditions", $data, $options);
		}
	}
	
	public function getAllObjectsGroups($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/objectsgroup", "get_all_objects_groups", null, $options);
			self::prepareObjectsGroupsListData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
			$result = $ObjectsGroup->find();
			self::prepareObjectsGroupsListData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mog_objects_group", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getAllObjectsGroups", $data, $options);
	}
	
	public function countAllObjectsGroups($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/objectsgroup", "count_all_objects_groups", null, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
			return $ObjectsGroup->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mog_objects_group", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countAllObjectsGroups", $data, $options);
	}
	
	/**
	 * @param (name=data[objects_group_ids], type=mixed, not_null=1)  
	 */
	public function getObjectsGroupsByIds($data) {
		$objects_group_ids = $data["objects_group_ids"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($objects_group_ids) {
			$objects_group_ids_str = "";//just in case the user tries to hack the sql query. By default all objects_group_id should be numeric.
			$objects_group_ids = is_array($objects_group_ids) ? $objects_group_ids : array($objects_group_ids);
			foreach ($objects_group_ids as $objects_group_id)
				$objects_group_ids_str .= ($objects_group_ids_str ? ", " : "") . "'" . addcslashes($objects_group_id, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$result = $b->callSelect("module/objectsgroup", "get_objects_groups_by_ids", array("objects_group_ids" => $objects_group_ids_str), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
				$conditions = array("objects_group_id" => array("operator" => "in", "value" => $objects_group_ids));
				$result = $ObjectsGroup->find(array("conditions" => $conditions), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$result = $b->findObjects("mog_objects_group", null, array("objects_group_id" => array("operator" => "in", "value" => $objects_group_ids)), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByIds", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function getObjectsGroupsByTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$result = $b->callSelect("module/objectsgroup", "get_objects_groups_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
				$result = $ObjectsGroup->callSelect("get_objects_groups_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$sql = ObjectsGroupDBDAOServiceUtil::get_objects_groups_by_tags(array("tags" => $tags_str, "object_type_id" => $object_type_id));
				
				$result = $b->getSQL($sql, $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function countObjectsGroupsByTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$result = $b->callSelect("module/objectsgroup", "count_objects_groups_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
				$result = $ObjectsGroup->callSelect("count_objects_groups_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$sql = ObjectsGroupDBDAOServiceUtil::count_objects_groups_by_tags(array("tags" => $tags_str, "object_type_id" => $object_type_id));
				
				$result = $b->getSQL($sql, $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[objects_group_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function getObjectsGroupsByObjectAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$objects_group_object_type_id = $data["objects_group_object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$result = $b->callSelect("module/objectsgroup", "get_objects_groups_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
				$result = $ObjectsGroup->callSelect("get_objects_groups_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$sql = ObjectsGroupDBDAOServiceUtil::get_objects_groups_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id));
				
				$result = $b->getSQL($sql, $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[objects_group_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function countObjectsGroupsByObjectAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$objects_group_object_type_id = $data["objects_group_object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$result = $b->callSelect("module/objectsgroup", "count_objects_groups_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
				$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$sql = ObjectsGroupDBDAOServiceUtil::count_objects_groups_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id));
				
				$result = $b->getSQL($sql, $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)   
	 * @param (name=data[group], type=bigint, default=0, length=19)  
	 * @param (name=data[objects_group_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function getObjectsGroupsByObjectGroupAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = isset($data["group"]) ? $data["group"] : null;
		$tags = $data["tags"];
		$objects_group_object_type_id = $data["objects_group_object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$result = $b->callSelect("module/objectsgroup", "get_objects_groups_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
				$result = $ObjectsGroup->callSelect("get_objects_groups_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$sql = ObjectsGroupDBDAOServiceUtil::get_objects_groups_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id));
				
				$result = $b->getSQL($sql, $options);
				self::prepareObjectsGroupsListData($result);
				return $result;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectGroupAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)    
	 * @param (name=data[group], type=bigint, default=0, length=19)   
	 * @param (name=data[objects_group_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function countObjectsGroupsByObjectGroupAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = isset($data["group"]) ? $data["group"] : null;
		$tags = $data["tags"];
		$objects_group_object_type_id = $data["objects_group_object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$result = $b->callSelect("module/objectsgroup", "count_objects_groups_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
				$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$sql = ObjectsGroupDBDAOServiceUtil::count_objects_groups_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "objects_group_object_type_id" => $objects_group_object_type_id));
				
				$result = $b->getSQL($sql, $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectGroupAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function getObjectsGroupsWithAllTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
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
					$result = $b->callSelect("module/objectsgroup", "get_objects_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id), $options);
					self::prepareObjectsGroupsListData($result);
					return $result;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
					$result = $ObjectsGroup->callSelect("get_objects_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id), $options);
					self::prepareObjectsGroupsListData($result);
					return $result;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = ObjectsGroupDBDAOServiceUtil::get_objects_groups_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id));
					
					$result = $b->getSQL($sql, $options);
					self::prepareObjectsGroupsListData($result);
					return $result;
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function countObjectsGroupsWithAllTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
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
					$result = $b->callSelect("module/objectsgroup", "count_objects_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
					$result = $ObjectsGroup->callSelect("count_objects_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = ObjectsGroupDBDAOServiceUtil::count_objects_groups_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id));
					
					$result = $b->getSQL($sql, $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[objects_group_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function getObjectsGroupsByObjectWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$objects_group_object_type_id = $data["objects_group_object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
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
					$result = $b->callSelect("module/objectsgroup", "get_objects_groups_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
					self::prepareObjectsGroupsListData($result);
					return $result;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
					$result = $ObjectsGroup->callSelect("get_objects_groups_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
					self::prepareObjectsGroupsListData($result);
					return $result;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = ObjectsGroupDBDAOServiceUtil::get_objects_groups_by_object_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id));
					
					$result = $b->getSQL($sql, $options);
					self::prepareObjectsGroupsListData($result);
					return $result;
				}
				else if (is_a($b, "IBusinessLogicBrokerClient"))
					return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[objects_group_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function countObjectsGroupsByObjectWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$objects_group_object_type_id = $data["objects_group_object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
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
					$result = $b->callSelect("module/objectsgroup", "count_objects_groups_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
					$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = ObjectsGroupDBDAOServiceUtil::count_objects_groups_by_object_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id));
					
					$result = $b->getSQL($sql, $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IBusinessLogicBrokerClient"))
					return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)   
	 * @param (name=data[group], type=bigint, default=0, length=19)  
	 * @param (name=data[objects_group_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function getObjectsGroupsByObjectGroupWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = isset($data["group"]) ? $data["group"] : null;
		$tags = $data["tags"];
		$objects_group_object_type_id = $data["objects_group_object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
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
					$result = $b->callSelect("module/objectsgroup", "get_objects_groups_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
					self::prepareObjectsGroupsListData($result);
					return $result;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
					$result = $ObjectsGroup->callSelect("get_objects_groups_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
					self::prepareObjectsGroupsListData($result);
					return $result;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = ObjectsGroupDBDAOServiceUtil::get_objects_groups_by_object_group_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id));
					
					$result = $b->getSQL($sql, $options);
					self::prepareObjectsGroupsListData($result);
					return $result;
				}
				else if (is_a($b, "IBusinessLogicBrokerClient"))
					return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectGroupWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)     
	 * @param (name=data[group], type=bigint, default=0, length=19)  
	 * @param (name=data[objects_group_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 */
	public function countObjectsGroupsByObjectGroupWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = isset($data["group"]) ? $data["group"] : null;
		$tags = $data["tags"];
		$objects_group_object_type_id = $data["objects_group_object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
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
					$result = $b->callSelect("module/objectsgroup", "count_objects_groups_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
					$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = ObjectsGroupDBDAOServiceUtil::count_objects_groups_by_object_group_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "objects_group_object_type_id" => $objects_group_object_type_id));
					
					$result = $b->getSQL($sql, $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectGroupWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 */
	public function getObjectsGroupsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/objectsgroup", "get_objects_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			self::prepareObjectsGroupsListData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
			$result = $ObjectsGroup->callSelect("get_objects_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			self::prepareObjectsGroupsListData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ObjectsGroupDBDAOServiceUtil::get_objects_groups_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
			
			$result = $b->getSQL($sql, $options);
			self::prepareObjectsGroupsListData($result);
			return $result;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 */
	public function countObjectsGroupsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/objectsgroup", "count_objects_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
			$result = $ObjectsGroup->callSelect("count_objects_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ObjectsGroupDBDAOServiceUtil::count_objects_groups_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
			
			$result = $b->getSQL($sql, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 */
	public function getObjectsGroupsByObjectGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = isset($data["group"]) ? $data["group"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/objectsgroup", "get_objects_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
			self::prepareObjectsGroupsListData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
			$result = $ObjectsGroup->callSelect("get_objects_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
			self::prepareObjectsGroupsListData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ObjectsGroupDBDAOServiceUtil::get_objects_groups_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
			
			$result = $b->getSQL($sql, $options);
			self::prepareObjectsGroupsListData($result);
			return $result;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.getObjectsGroupsByObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 */
	public function countObjectsGroupsByObjectGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = isset($data["group"]) ? $data["group"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/objectsgroup", "count_objects_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectsGroup = $this->getObjectsGroupHbnObj($b, $options);
			$result = $ObjectsGroup->callSelect("count_objects_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ObjectsGroupDBDAOServiceUtil::count_objects_groups_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
			
			$result = $b->getSQL($sql, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/objectsgroup", "ObjectsGroupService.countObjectsGroupsByObjectGroup", $data, $options);
	}
	
	private static function prepareObjectsGroupsListData(&$data) {
		if ($data) {
			$t = count($data);
			for ($i = 0; $i < $t; $i++)
				$data[$i]["object"] = isset($data[$i]["object"]) ? json_decode($data[$i]["object"], true) : null;
		}
	}
}
?>
