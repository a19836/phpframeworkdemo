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

namespace Module\Tag;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class ObjectTagService extends \soa\CommonService {
	private $ObjectTag;
	
	private function getObjectTagHbnObj($b, $options) {
		if (!$this->ObjectTag) 
			$this->ObjectTag = $b->callObject("module/tag", "ObjectTag", $options);
		
		return $this->ObjectTag;
	}
	
	/**
	 * @param (name=data[tag_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function insertObjectTag($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callInsert("module/tag", "insert_object_tag", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectTag = $this->getObjectTagHbnObj($b, $options);
			return $ObjectTag->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("mt_object_tag", array(
					"tag_id" => $data["tag_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"group" => isset($data["group"]) ? $data["group"] : null, 
					"order" => isset($data["order"]) ? $data["order"] : null, 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/tag", "ObjectTagService.insertObjectTag", $data, $options);
	}
	
	/**
	 * @param (name=data[tag_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function updateObjectTag($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/tag", "update_object_tag", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectTag = $this->getObjectTagHbnObj($b, $options);
			return $ObjectTag->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mt_object_tag", array(
					"group" => isset($data["group"]) ? $data["group"] : null, 
					"order" => isset($data["order"]) ? $data["order"] : null, 
					"modified_date" => $data["modified_date"]
				), array(
					"tag_id" => $data["tag_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/tag", "ObjectTagService.updateObjectTag", $data, $options);
	}
	
	/**
	 * @param (name=data[tag_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function updateObjectTagOrder($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/tag", "update_object_tag_order", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectTag = $this->getObjectTagHbnObj($b, $options);
			return $ObjectTag->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mt_object_tag", array(
					"order" => isset($data["order"]) ? $data["order"] : null, 
					"modified_date" => $data["modified_date"]
				), array(
					"tag_id" => $data["tag_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/tag", "ObjectTagService.updateObjectTagOrder", $data, $options);
	}
	
	/**
	 * @param (name=data[tag_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19) 
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteObjectTag($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/tag", "delete_object_tag", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectTag = $this->getObjectTagHbnObj($b, $options);
			return $ObjectTag->delete($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mt_object_tag", array("tag_id" => $data["tag_id"], "object_type_id" => $data["object_type_id"], "object_id" => $data["object_id"]), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/tag", "ObjectTagService.deleteObjectTag", $data, $options);
	}
	
	/** 
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19) 
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteObjectTagsByObject($data) {
		$object_id = $data["object_id"];
		$object_type_id = $data["object_type_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/tag", "delete_object_tags_by_object", array("object_id" => $object_id, "object_type_id" => $object_type_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectTag = $this->getObjectTagHbnObj($b, $options);
			return $ObjectTag->delete(array("object_id" => $object_id, "object_type_id" => $object_type_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mt_object_tag", array("object_id" => $object_id, "object_type_id" => $object_type_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/tag", "ObjectTagService.deleteObjectTagsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[tag_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteObjectTagsByTagId($data) {
		$tag_id = $data["tag_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/tag", "delete_object_tags_by_tag_id", array("tag_id" => $tag_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectTag = $this->getObjectTagHbnObj($b, $options);
			return $ObjectTag->delete(array("tag_id" => $tag_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mt_object_tag", array("tag_id" => $tag_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/tag", "ObjectTagService.deleteObjectTagsByTagId", $data, $options);
	}
	
	/**
	 * @param (name=data[tag_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectTagsByTagId($data) {
		$tag_id = $data["tag_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/tag", "get_object_tags_by_tag_id", array("tag_id" => $tag_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectTag = $this->getObjectTagHbnObj($b, $options);
			$conditions = array("tag_id" => $tag_id);
			return $ObjectTag->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mt_object_tag", null, array("tag_id" => $tag_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/tag", "ObjectTagService.getObjectTagsByTagId", $data, $options);
	}
	
	/**
	 * @param (name=data[tag_id], type=bigint, not_null=1, length=19)
	 */
	public function countObjectTagsByTagId($data) {
		$tag_id = $data["tag_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/tag", "count_object_tags_by_tag_id", array("tag_id" => $tag_id), $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectTag = $this->getObjectTagHbnObj($b, $options);
			return $ObjectTag->count(array("conditions" => array("tag_id" => $tag_id)));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mt_object_tag", array("tag_id" => $tag_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/tag", "ObjectTagService.countObjectTagsByTagId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectTagsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/tag", "get_object_tags_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectTag = $this->getObjectTagHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $ObjectTag->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mt_object_tag", null, array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/tag", "ObjectTagService.getObjectTagsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][tag_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function getObjectTagsByConditions($data) {
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/tag", "get_object_tags_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectTag = $this->getObjectTagHbnObj($b, $options);
				return $ObjectTag->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				return $b->findObjects("mt_object_tag", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/tag", "ObjectTagService.getObjectTagsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][tag_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function countObjectTagsByConditions($data) {
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/tag", "count_object_tags_by_conditions", array("conditions" => $cond), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectTag = $this->getObjectTagHbnObj($b, $options);
				return $ObjectTag->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				return $b->countObjects("mt_object_tag", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/tag", "ObjectTagService.countObjectTagsByConditions", $data, $options);
		}
	}
	
	public function getAllObjectTags($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/tag", "get_all_object_tags", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectTag = $this->getObjectTagHbnObj($b, $options);
			return $ObjectTag->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mt_object_tag", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/tag", "ObjectTagService.getAllObjectTags", null, $options);
	}
	
	public function countAllObjectTags($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/tag", "count_all_object_tags", null, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectTag = $this->getObjectTagHbnObj($b, $options);
			return $ObjectTag->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mt_object_tag", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/tag", "ObjectTagService.countAllObjectTags", null, $options);
	}
}
?>
