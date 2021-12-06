<?php
namespace Module\Attachment;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/AttachmentDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class AttachmentService extends \soa\CommonService {
	private $Attachment;
	
	private function getAttachmentHbnObj($b, $options) {
		if (!$this->Attachment) 
			$this->Attachment = $b->callObject("module/attachment", "Attachment", $options);
		
		return $this->Attachment;
	}
	
	/**
	 * @param (name=data[name], type=varchar, not_null=1, length=50, @mstrcut)
	 * @param (name=data[type], type=varchar, not_null=1, length=100)
	 * @param (name=data[size], type=bigint, not_null=1, length=19)
	 * @param (name=data[path], type=varchar, not_null=1, length=255)
	 */
	public function insertAttachment($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			$data["type"] = addcslashes($data["type"], "\\'");
			$data["path"] = addcslashes($data["path"], "\\'");
			
			$status = $b->callInsert("module/attachment", "insert_attachment", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Attachment = $this->getAttachmentHbnObj($b, $options);
			$status = $Attachment->insert($data, $ids);
			return $status ? $ids["attachment_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("mat_attachment", array(
					"name" => $data["name"], 
					"type" => $data["type"], 
					"size" => $data["size"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/attachment", "AttachmentService.insertAttachment", $data, $options);
	}
	
	/**
	 * @param (name=data[attachment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, length=50, @mstrcut)
	 * @param (name=data[type], type=varchar, not_null=1, length=100)
	 * @param (name=data[size], type=bigint, not_null=1, length=19)
	 * @param (name=data[path], type=varchar, not_null=1, length=255)
	 */
	public function updateAttachment($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			$data["type"] = addcslashes($data["type"], "\\'");
			$data["path"] = addcslashes($data["path"], "\\'");
			
			return $b->callUpdate("module/attachment", "update_attachment", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Attachment = $this->getAttachmentHbnObj($b, $options);
			return $Attachment->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mat_attachment", array(
					"name" => $data["name"], 
					"type" => $data["type"], 
					"size" => $data["size"], 
					"modified_date" => $data["modified_date"]
				), array(
					"attachment_id" => $data["attachment_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/attachment", "AttachmentService.updateAttachment", $data, $options);
	}
	
	/**
	 * @param (name=data[attachment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, length=50)
	 */
	public function updateAttachmentName($data) {
		$attachment_id = $data["attachment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			return $b->callUpdate("module/attachment", "update_attachment_name", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Attachment = $this->getAttachmentHbnObj($b, $options);
			return $Attachment->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mat_attachment", array(
					"name" => $data["name"], 
					"modified_date" => $data["modified_date"]
				), array(
					"attachment_id" => $data["attachment_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "AttachmentService.updateAttachmentName", $data, $options);
	}
	
	/**
	 * @param (name=data[attachment_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteAttachment($data) {
		$attachment_id = $data["attachment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/attachment", "delete_attachment", array("attachment_id" => $attachment_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Attachment = $this->getAttachmentHbnObj($b, $options);
			return $Attachment->delete($attachment_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mat_attachment", array("attachment_id" => $attachment_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "AttachmentService.deleteAttachment", $data, $options);
	}
	
	/**
	 * @param (name=data[attachment_id], type=bigint, not_null=1, length=19)  
	 */
	public function getAttachment($data) {
		$attachment_id = $data["attachment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/attachment", "get_attachment", array("attachment_id" => $attachment_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Attachment = $this->getAttachmentHbnObj($b, $options);
			return $Attachment->findById($attachment_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mat_attachment", null, array("attachment_id" => $attachment_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "AttachmentService.getAttachment", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][attachment_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][type], type=varchar|array, length=100)
	 * @param (name=data[conditions][size], type=bigint|array, length=19)
	 * @param (name=data[conditions][path], type=varchar|array, length=255)
	 */
	public function getAttachmentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/attachment", "get_attachments_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Attachment = $this->getAttachmentHbnObj($b, $options);
				return $Attachment->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mat_attachment", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][attachment_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][type], type=varchar|array, length=100)
	 * @param (name=data[conditions][size], type=bigint|array, length=19)
	 * @param (name=data[conditions][path], type=varchar|array, length=255)
	 */
	public function countAttachmentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/attachment", "count_attachments_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Attachment = $this->getAttachmentHbnObj($b, $options);
				return $Attachment->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mat_attachment", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/attachment", "AttachmentService.countAttachmentsByConditions", $data, $options);
		}
	}
	
	public function getAllAttachments($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/attachment", "get_all_attachments", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Attachment = $this->getAttachmentHbnObj($b, $options);
			return $Attachment->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mat_attachment", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "AttachmentService.getAllAttachments", null, $options);
	}
	
	public function countAllAttachments($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/attachment", "count_all_attachments", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Attachment = $this->getAttachmentHbnObj($b, $options);
			return $Attachment->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mat_attachment", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "AttachmentService.countAllAttachments", null, $options);
	}
	
	/**
	 * @param (name=data[attachment_ids], type=mixed, not_null=1)  
	 */
	public function getAttachmentsByIds($data) {
		$attachment_ids = $data["attachment_ids"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($attachment_ids) {
			$attachment_ids_str = "";//just in case the user tries to hack the sql query. By default all attachment_id should be numeric.
			$attachment_ids = is_array($attachment_ids) ? $attachment_ids : array($attachment_ids);
			foreach ($attachment_ids as $attachment_id) 
				$attachment_ids_str .= ($attachment_ids_str ? ", " : "") . "'" . addcslashes($attachment_id, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) 
				return $b->callSelect("module/attachment", "get_attachments_by_ids", array("attachment_ids" => $attachment_ids_str), $options);
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Attachment = $this->getAttachmentHbnObj($b, $options);
				$conditions = array("attachment_id" => array("operator" => "in", "value" => $attachment_ids));
				return $Attachment->find(array("conditions" => $conditions), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				return $b->findObjects("mat_attachment", null, array("attachment_id" => array("operator" => "in", "value" => $attachment_ids)), $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByIds", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 */
	public function getAttachmentsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/attachment", "get_attachments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Attachment = $this->getAttachmentHbnObj($b, $options);
			return $Attachment->callSelect("get_attachments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = AttachmentDBDAOServiceUtil::get_attachments_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
				
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)   
	 * @param (name=data[object_ids], type=mixed, not_null=1)  
	 */
	public function getAttachmentsByObjects($data) {
		$object_type_id = $data["object_type_id"];
		$object_ids = $data["object_ids"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($object_ids) {
			$object_ids_str = "";//just in case the user tries to hack the sql query. By default all object_id should be numeric.
			$object_ids = is_array($object_ids) ? $object_ids : array($object_ids);
			foreach ($object_ids as $object_id)
				$object_ids_str .= ($object_ids_str ? ", " : "") . "'" . addcslashes($object_id, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) 
				return $b->callSelect("module/attachment", "get_attachments_by_objects", array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str), $options);
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Attachment = $this->getAttachmentHbnObj($b, $options);
				return $Attachment->callSelect("get_attachments_by_objects", array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$sql = AttachmentDBDAOServiceUtil::get_attachments_by_objects(array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByObjects", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 */
	public function getAttachmentsByObjectGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/attachment", "get_attachments_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Attachment = $this->getAttachmentHbnObj($b, $options);
			return $Attachment->callSelect("get_attachments_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = AttachmentDBDAOServiceUtil::get_attachments_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19) 
	 * @param (name=data[object_ids], type=mixed, not_null=1)   
	 * @param (name=data[groups], type=mixed, default=0)  
	 */
	public function getAttachmentsByObjectsGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_ids = $data["object_ids"];
		$groups = $data["groups"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($object_ids) {
			$object_ids_str = "";//just in case the user tries to hack the sql query. By default all object_id should be numeric.
			$object_ids = is_array($object_ids) ? $object_ids : array($object_ids);
			foreach ($object_ids as $object_id)
				$object_ids_str .= ($object_ids_str ? ", " : "") . "'" . addcslashes($object_id, "\\'") . "'";
			
			$groups_str = "";//just in case the user tries to hack the sql query. By default all groups should be numeric.
			$groups = is_array($groups) ? $groups : array($groups);
			foreach ($groups as $group)
				$groups_str .= ($groups_str ? ", " : "") . "'" . addcslashes($group, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) 
				return $b->callSelect("module/attachment", "get_attachments_by_objects_group", array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str, "groups" => $groups_str), $options);
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Attachment = $this->getAttachmentHbnObj($b, $options);
				return $Attachment->callSelect("get_attachments_by_objects_group", array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str, "groups" => $groups_str), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$sql = AttachmentDBDAOServiceUtil::get_attachments_by_objects_group(array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str, "groups" => $groups_str));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByObjectsGroup", $data, $options);
		}
	}
}
?>
