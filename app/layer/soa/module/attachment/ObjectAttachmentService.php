<?php
namespace Module\Attachment;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/ObjectAttachmentDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class ObjectAttachmentService extends \soa\CommonService {
	private $ObjectAttachment;
	
	private function getObjectAttachmentHbnObj($b, $options) {
		if (!$this->ObjectAttachment)
			$this->ObjectAttachment = $b->callObject("module/attachment", "ObjectAttachment", $options);
		
		return $this->ObjectAttachment;
	}
	
	/**
	 * @param (name=data[attachment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function insertObjectAttachment($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callInsert("module/attachment", "insert_object_attachment", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			return $ObjectAttachment->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("mat_object_attachment", array(
					"attachment_id" => $data["attachment_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"group" => $data["group"], 
					"order" => $data["order"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.insertObjectAttachment", $data, $options);
	}
	
	/**
	 * @param (name=data[new_attachment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_attachment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function updateObjectAttachment($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/attachment", "update_object_attachment", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			return $ObjectAttachment->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mat_object_attachment", array(
					"attachment_id" => $data["new_attachment_id"], 
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"group" => $data["group"], 
					"order" => $data["order"], 
					"modified_date" => $data["modified_date"]
				), array(
					"attachment_id" => $data["old_attachment_id"], 
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.updateObjectAttachment", $data, $options);
	}
	
	/**
	 * @param (name=data[new_attachment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_attachment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function updateObjectAttachmentIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/attachment", "update_object_attachment_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			return $ObjectAttachment->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mat_object_attachment", array(
					"attachment_id" => $data["new_attachment_id"], 
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"attachment_id" => $data["old_attachment_id"], 
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.updateObjectAttachmentIds", $data, $options);
	}
	
	/**
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function changeObjectAttachmentsObjectIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/attachment", "change_object_attachments_object_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			return $ObjectAttachment->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mat_object_attachment", array(
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.changeObjectAttachmentsObjectIds", $data, $options);
	}
	
	/**
	 * @param (name=data[attachment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectAttachment($data) {
		$attachment_id = $data["attachment_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/attachment", "delete_object_attachment", array("attachment_id" => $attachment_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			return $ObjectAttachment->delete(array("attachment_id" => $attachment_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mat_object_attachment", array("attachment_id" => $attachment_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.deleteObjectAttachment", $data, $options);
	}
	
	/**
	 * @param (name=data[attachment_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectAttachmentsByAttachmentId($data) {
		$attachment_id = $data["attachment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/attachment", "delete_object_attachments_by_attachment_id", array("attachment_id" => $attachment_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			$conditions = array("attachment_id" => $attachment_id);
			return $ObjectAttachment->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mat_object_attachment", array("attachment_id" => $attachment_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.deleteObjectAttachmentsByAttachmentId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectAttachmentsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/attachment", "delete_object_attachments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $ObjectAttachment->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mat_object_attachment", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.deleteObjectAttachmentsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][attachment_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function deleteObjectAttachmentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/attachment", "delete_object_attachments_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
				return $ObjectAttachment->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]));
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("mat_object_attachment", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.deleteObjectAttachmentsByConditions", $data, $options);
		}
	}
	
	public function deleteCorruptedObjectAttachments($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/attachment", "delete_corrupted_object_attachments", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			return $ObjectAttachment->callDelete("delete_corrupted_object_attachments");
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ObjectAttachmentDBDAOServiceUtil::delete_corrupted_object_attachments();
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.deleteCorruptedObjectAttachments", $data, $options);
	}
	
	/**
	 * @param (name=data[attachment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectAttachment($data) {
		$attachment_id = $data["attachment_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/attachment", "get_object_attachment", array("attachment_id" => $attachment_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			return $ObjectAttachment->findById(array("attachment_id" => $attachment_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mat_object_attachment", null, array("attachment_id" => $attachment_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.getObjectAttachment", $data, $options);
	}
	
	/**
	 * @param (name=data[attachment_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectAttachmentsByAttachmentId($data) {
		$attachment_id = $data["attachment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/attachment", "get_object_attachments_by_attachment_id", array("attachment_id" => $attachment_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			$conditions = array("attachment_id" => $attachment_id);
			return $ObjectAttachment->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mat_object_attachment", null, array("attachment_id" => $attachment_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.getObjectAttachmentsByAttachmentId", $data, $options);
	}
	
	/**
	 * @param (name=data[attachment_id], type=bigint, not_null=1, length=19)
	 */
	public function countObjectAttachmentsByAttachmentId($data) {
		$attachment_id = $data["attachment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/attachment", "count_object_attachments_by_attachment_id", array("attachment_id" => $attachment_id), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			return $ObjectAttachment->count(array("attachment_id" => $attachment_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mat_object_attachment", array("attachment_id" => $attachment_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.countObjectAttachmentsByAttachmentId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectAttachmentsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/attachment", "get_object_attachments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $ObjectAttachment->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mat_object_attachment", null, array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.getObjectAttachmentsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][attachment_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function getObjectAttachmentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/attachment", "get_object_attachments_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
				return $ObjectAttachment->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mat_object_attachment", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.getObjectAttachmentsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][attachment_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function countObjectAttachmentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/attachment", "count_object_attachments_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
				return $ObjectAttachment->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mat_object_attachment", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.countObjectAttachmentsByConditions", $data, $options);
		}
	}
	
	public function getAllObjectAttachments($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/attachment", "get_all_object_attachments", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			return $ObjectAttachment->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mat_object_attachment", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.getAllObjectAttachments", null, $options);
	}
	
	public function countAllObjectAttachments($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/attachment", "count_all_object_attachments", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectAttachment = $this->getObjectAttachmentHbnObj($b, $options);
			return $ObjectAttachment->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mat_object_attachment", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/attachment", "ObjectAttachmentService.countAllObjectAttachments", null, $options);
	}
}
?>
