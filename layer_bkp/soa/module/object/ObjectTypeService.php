<?php
namespace Module\Object;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class ObjectTypeService extends \soa\CommonService {
	private $ObjectType;
	
	private function getObjectTypeHbnObj($b, $options) {
		if (!$this->ObjectType)
			$this->ObjectType = $b->callObject("module/object", "ObjectType", $options);
		
		return $this->ObjectType;
	}
	
	/**
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function insertObjectType($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			if ($data["object_type_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$status = $b->callInsert("module/object", "insert_object_type_with_ai_pk", $data, $options);
				return $status ? $data["object_type_id"] : $status;
			}
			
			$status = $b->callInsert("module/object", "insert_object_type", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			if (!$data["object_type_id"])
				unset($data["object_type_id"]);
			
			$ObjectType = $this->getObjectTypeHbnObj($b, $options);
			$status = $ObjectType->insert($data, $ids);
			return $status ? $ids["object_type_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$attributes = array(
				"name" => $data["name"], 
				"created_date" => $data["created_date"], 
				"modified_date" => $data["modified_date"]
			);
			
			if ($data["object_type_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$attributes["object_type_id"] = $data["object_type_id"];
			}
			
			$status = $b->insertObject("mo_object_type", $attributes, $options);
			return $status ? ($data["object_type_id"] ? $data["object_type_id"] : $b->getInsertedId($options)) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/object", "ObjectTypeService.insertObjectType", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function updateObjectType($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			return $b->callUpdate("module/object", "update_object_type", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectType = $this->getObjectTypeHbnObj($b, $options);
			return $ObjectType->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mo_object_type", array(
					"name" => $data["name"],
					"modified_date" => $data["modified_date"]
				), array(
					"object_type_id" => $data["object_type_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/object", "ObjectTypeService.updateObjectType", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteObjectType($data) {
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/object", "delete_object_type", array("object_type_id" => $object_type_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectType = $this->getObjectTypeHbnObj($b, $options);
			return $ObjectType->delete($object_type_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mo_object_type", array("object_type_id" => $object_type_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/object", "ObjectTypeService.deleteObjectType", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 */
	public function getObjectType($data) {
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/object", "get_object_type", array("object_type_id" => $object_type_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectType = $this->getObjectTypeHbnObj($b, $options);
			return $ObjectType->findById($object_type_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mo_object_type", null, array("object_type_id" => $object_type_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/object", "ObjectTypeService.getObjectType", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function getObjectTypesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/object", "get_object_types_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectType = $this->getObjectTypeHbnObj($b, $options);
				return $ObjectType->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mo_object_type", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/object", "ObjectTypeService.getObjectTypesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function countObjectTypesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/object", "count_object_types_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectType = $this->getObjectTypeHbnObj($b, $options);
				return $ObjectType->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mo_object_type", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/object", "ObjectTypeService.countObjectTypesByConditions", $data, $options);
		}
	}
	
	public function getAllObjectTypes($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/object", "get_all_object_types", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectType = $this->getObjectTypeHbnObj($b, $options);
			return $ObjectType->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mo_object_type", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/object", "ObjectTypeService.getAllObjectTypes", $data, $options);
	}
	
	public function countAllObjectTypes($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/object", "count_all_object_types", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectType = $this->getObjectTypeHbnObj($b, $options);
			return $ObjectType->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mo_object_type", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/object", "ObjectTypeService.countAllObjectTypes", $data, $options);
	}
}
?>
