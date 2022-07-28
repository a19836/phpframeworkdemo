<?php
namespace Module\ObjectsGroup;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/ObjectObjectsGroupDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class ObjectObjectsGroupService extends \soa\CommonService {
	private $ObjectObjectsGroup;
	
	private function getObjectObjectsGroupHbnObj($b, $options) {
		if (!$this->ObjectObjectsGroup)
			$this->ObjectObjectsGroup = $b->callObject("module/objectsgroup", "ObjectObjectsGroup", $options);
		
		return $this->ObjectObjectsGroup;
	}
	
	/**
	 * @param (name=data[objects_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function insertObjectObjectsGroup($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callInsert("module/objectsgroup", "insert_object_objects_group", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			return $ObjectObjectsGroup->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("mog_object_objects_group", array(
					"objects_group_id" => $data["objects_group_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"group" => $data["group"], 
					"order" => $data["order"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.insertObjectObjectsGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[new_objects_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_objects_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function updateObjectObjectsGroup($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callUpdate("module/objectsgroup", "update_object_objects_group", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			return $ObjectObjectsGroup->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mog_object_objects_group", array(
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
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.updateObjectObjectsGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[new_objects_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_objects_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function updateObjectObjectsGroupIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/objectsgroup", "update_object_objects_group_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			return $ObjectObjectsGroup->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mog_object_objects_group", array(
					"objects_group_id" => $data["new_objects_group_id"], 
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"objects_group_id" => $data["old_objects_group_id"], 
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.updateObjectObjectsGroupIds", $data, $options);
	}
	
	/**
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function changeObjectObjectsGroupsObjectIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/objectsgroup", "change_object_objects_groups_object_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			return $ObjectObjectsGroup->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mog_object_objects_group", array(
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.changeObjectObjectsGroupsObjectIds", $data, $options);
	}
	
	/**
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[parent_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[parent_object_id], type=bigint, not_null=1, length=19)
	 */
	public function changeObjectObjectsGroupsObjectIdsOfParentObject($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/objectsgroup", "change_object_objects_groups_object_ids_of_parent_object", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			return $ObjectObjectsGroup->callUpdate("change_object_objects_groups_object_ids_of_parent_object", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ObjectObjectsGroupDBDAOServiceUtil::change_object_objects_groups_object_ids_of_parent_object($data);
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.changeObjectObjectsGroupsObjectIdsOfParentObject", $data, $options);
	}
	
	/**
	 * @param (name=data[objects_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectObjectsGroup($data) {
		$objects_group_id = $data["objects_group_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/objectsgroup", "delete_object_objects_group", array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			return $ObjectObjectsGroup->delete(array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mog_object_objects_group", array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.deleteObjectObjectsGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[objects_group_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectObjectsGroupsByObjectsGroupId($data) {
		$objects_group_id = $data["objects_group_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/objectsgroup", "delete_object_objects_groups_by_objects_group_id", array("objects_group_id" => $objects_group_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			$conditions = array("objects_group_id" => $objects_group_id);
			return $ObjectObjectsGroup->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mog_object_objects_group", array("objects_group_id" => $objects_group_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.deleteObjectObjectsGroupsByObjectsGroupId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectObjectsGroupsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/objectsgroup", "delete_object_objects_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $ObjectObjectsGroup->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mog_object_objects_group", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.deleteObjectObjectsGroupsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][objects_group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function deleteObjectObjectsGroupsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/objectsgroup", "delete_object_objects_groups_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
				return $ObjectObjectsGroup->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]));
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("mog_object_objects_group", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.deleteObjectObjectsGroupsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[objects_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectObjectsGroup($data) {
		$objects_group_id = $data["objects_group_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/objectsgroup", "get_object_objects_group", array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			return $ObjectObjectsGroup->findById(array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mog_object_objects_group", null, array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.getObjectObjectsGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[objects_group_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectObjectsGroupsByObjectsGroupId($data) {
		$objects_group_id = $data["objects_group_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/objectsgroup", "get_object_objects_groups_by_objects_group_id", array("objects_group_id" => $objects_group_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			$conditions = array("objects_group_id" => $objects_group_id);
			return $ObjectObjectsGroup->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mog_object_objects_group", null, array("objects_group_id" => $objects_group_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.getObjectObjectsGroupsByObjectsGroupId", $data, $options);
	}
	
	/**
	 * @param (name=data[objects_group_id], type=bigint, not_null=1, length=19)
	 */
	public function countObjectObjectsGroupsByObjectsGroupId($data) {
		$objects_group_id = $data["objects_group_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/objectsgroup", "count_object_objects_groups_by_objects_group_id", array("objects_group_id" => $objects_group_id), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			return $ObjectObjectsGroup->count(array("objects_group_id" => $objects_group_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mog_object_objects_group", array("objects_group_id" => $objects_group_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.countObjectObjectsGroupsByObjectsGroupId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectObjectsGroupsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/objectsgroup", "get_object_objects_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $ObjectObjectsGroup->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mog_object_objects_group", null, array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.getObjectObjectsGroupsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][objects_group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function getObjectObjectsGroupsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/objectsgroup", "get_object_objects_groups_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
				return $ObjectObjectsGroup->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mog_object_objects_group", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.getObjectObjectsGroupsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][objects_group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function countObjectObjectsGroupsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/objectsgroup", "count_object_objects_groups_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
				return $ObjectObjectsGroup->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mog_object_objects_group", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.countObjectObjectsGroupsByConditions", $data, $options);
		}
	}
	
	public function getAllObjectObjectsGroups($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/objectsgroup", "get_all_object_objects_groups", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			return $ObjectObjectsGroup->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mog_object_objects_group", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.getAllObjectObjectsGroups", null, $options);
	}
	
	public function countAllObjectObjectsGroups($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/objectsgroup", "count_all_object_objects_groups", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectObjectsGroup = $this->getObjectObjectsGroupHbnObj($b, $options);
			return $ObjectObjectsGroup->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mog_object_objects_group", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/objectsgroup", "ObjectObjectsGroupService.countAllObjectObjectsGroups", null, $options);
	}
}
?>
