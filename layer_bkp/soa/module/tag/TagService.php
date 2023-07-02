<?php
namespace Module\Tag;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/TagDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class TagService extends \soa\CommonService {
	private $Tag;
	
	private function getTagHbnObj($b, $options) {
		if (!$this->Tag)
			$this->Tag = $b->callObject("module/tag", "Tag", $options);
		
		return $this->Tag;
	}
	
	/**
	 * @param (name=data[tag_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tag], type=varchar, not_null=1, min_length=1, max_length=200)  
	 */
	public function insertTag($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["tag"] = addcslashes($data["tag"], "\\'");
			
			$status = $b->callInsert("module/tag", "insert_tag", $data, $options);
			return $status ? $data["tag_id"] : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Tag = $this->getTagHbnObj($b, $options);
			$status = $Tag->insert($data, $ids);
			return $status ? $data["tag_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("mt_tag", array(
					"tag_id" => $data["tag_id"], 
					"tag" => $data["tag"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $data["tag_id"] : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/tag", "TagService.insertTag", $data, $options);
	}
	
	/**
	 * @param (name=data[tag_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteTag($data) {
		$tag_id = $data["tag_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/tag", "delete_tag", array("tag_id" => $tag_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Tag = $this->getTagHbnObj($b, $options);
			return $Tag->delete($tag_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mt_tag", array("tag_id" => $tag_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/tag", "TagService.deleteTag", $data, $options);
	}
	
	/**
	 * @param (name=data[tag_id], type=bigint, not_null=1, length=19)  
	 */
	public function getTag($data) {
		$tag_id = $data["tag_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/tag", "get_tag", array("tag_id" => $tag_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Tag = $this->getTagHbnObj($b, $options);
			return $Tag->findById($tag_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mt_tag", null, array("tag_id" => $tag_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/tag", "TagService.getTag", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][tag_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][tag], type=varchar|array)
	 */
	public function getTagsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/tag", "get_tags_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Tag = $this->getTagHbnObj($b, $options);
				return $Tag->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mt_tag", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/tag", "TagService.getTagsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][tag_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][tag], type=varchar|array)
	 */
	public function countTagsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/tag", "count_tags_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Tag = $this->getTagHbnObj($b, $options);
				return $Tag->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mt_tag", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/tag", "TagService.countTagsByConditions", $data, $options);
		}
	}
	
	public function getAllTags($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/tag", "get_all_tags", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Tag = $this->getTagHbnObj($b, $options);
			return $Tag->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mt_tag", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/tag", "TagService.getAllTags", null, $options);
	}
	
	public function countAllTags($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/tag", "count_all_tags", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Tag = $this->getTagHbnObj($b, $options);
			return $Tag->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mt_tag", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/tag", "TagService.countAllTags", null, $options);
	}
	
	/**
	 * @param (name=data[tag_ids], type=mixed, not_null=1)  
	 */
	public function getTagsByIds($data) {
		$tag_ids = $data["tag_ids"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($tag_ids) {
			$tag_ids_str = "";//just in case the user tries to hack the sql query. By default all tag_id should be numeric.
			$tag_ids = is_array($tag_ids) ? $tag_ids : array($tag_ids);
			foreach ($tag_ids as $tag_id) 
				$tag_ids_str .= ($tag_ids_str ? ", " : "") . "'" . addcslashes($tag_id, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) 
				return $b->callSelect("module/tag", "get_tags_by_ids", array("tag_ids" => $tag_ids_str), $options);
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Tag = $this->getTagHbnObj($b, $options);
				$conditions = array("tag_id" => array("operator" => "in", "value" => $tag_ids));
				return $Tag->find(array("conditions" => $conditions), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				return $b->findObjects("mt_tag", null, array("tag_id" => array("operator" => "in", "value" => $tag_ids)), $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/tag", "TagService.getTagsIds", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_ids], type=mixed, not_null=1)
	 */
	public function getTagsByObjects($data) {
		$object_type_id = $data["object_type_id"];
		$object_ids = $data["object_ids"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($object_ids) {
			$object_ids_str = "";
			$object_ids = is_array($object_ids) ? $object_ids : array($object_ids);
			foreach ($object_ids as $object_id) 
				$object_ids_str .= ($object_ids_str ? ", " : "") . "'" . addcslashes($object_id, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) 
				return $b->callSelect("module/tag", "get_tags_by_objects", array("object_ids" => $object_ids_str, "object_type_id" => $object_type_id), $options);
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Tag = $this->getTagHbnObj($b, $options);
				return $Tag->callSelect("get_tags_by_objects", array("object_ids" => $object_ids_str, "object_type_id" => $object_type_id), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$sql = TagDBDAOServiceUtil::get_tags_by_objects(array("object_ids" => $object_ids_str, "object_type_id" => $object_type_id));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/tag", "TagService.getTagsByObjects", $data, $options);
		}
		
		return null;
	}
}
?>
