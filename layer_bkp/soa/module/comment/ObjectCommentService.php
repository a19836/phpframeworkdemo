<?php
namespace Module\Comment;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class ObjectCommentService extends \soa\CommonService {
	private $ObjectComment;
	
	private function getObjectCommentHbnObj($b, $options) {
		if (!$this->ObjectComment)
			$this->ObjectComment = $b->callObject("module/comment", "ObjectComment", $options);
		
		return $this->ObjectComment;
	}
	
	/**
	 * @param (name=data[comment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function insertObjectComment($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callInsert("module/comment", "insert_object_comment", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			return $ObjectComment->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("mc_object_comment", array(
					"comment_id" => $data["comment_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"group" => $data["group"], 
					"order" => $data["order"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.insertObjectComment", $data, $options);
	}
	
	/**
	 * @param (name=data[new_comment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_comment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function updateObjectComment($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/comment", "update_object_comment", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			return $ObjectComment->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mc_object_comment", array(
					"comment_id" => $data["new_comment_id"], 
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"group" => $data["group"], 
					"order" => $data["order"], 
					"modified_date" => $data["modified_date"]
				), array(
					"comment_id" => $data["old_comment_id"], 
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.updateObjectComment", $data, $options);
	}
	
	/**
	 * @param (name=data[new_comment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_comment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function updateObjectCommentIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/comment", "update_object_comment_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			return $ObjectComment->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mc_object_comment", array(
					"comment_id" => $data["new_comment_id"], 
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"comment_id" => $data["old_comment_id"], 
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.updateObjectCommentIds", $data, $options);
	}
	
	/**
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function changeObjectCommentsObjectIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/comment", "change_object_comments_object_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			return $ObjectComment->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mc_object_comment", array(
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.changeObjectCommentsObjectIds", $data, $options);
	}
	
	/**
	 * @param (name=data[comment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectComment($data) {
		$comment_id = $data["comment_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/comment", "delete_object_comment", array("comment_id" => $comment_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			return $ObjectComment->delete(array("comment_id" => $comment_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mc_object_comment", array("comment_id" => $comment_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.deleteObjectComment", $data, $options);
	}
	
	/**
	 * @param (name=data[comment_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectCommentsByCommentId($data) {
		$comment_id = $data["comment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/comment", "delete_object_comments_by_comment_id", array("comment_id" => $comment_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			$conditions = array("comment_id" => $comment_id);
			return $ObjectComment->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mc_object_comment", array("comment_id" => $comment_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.deleteObjectCommentsByCommentId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectCommentsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/comment", "delete_object_comments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $ObjectComment->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mc_object_comment", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.deleteObjectCommentsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[comment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectComment($data) {
		$comment_id = $data["comment_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/comment", "get_object_comment", array("comment_id" => $comment_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			return $ObjectComment->findById(array("comment_id" => $comment_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mc_object_comment", null, array("comment_id" => $comment_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.getObjectComment", $data, $options);
	}
	
	/**
	 * @param (name=data[comment_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectCommentsByCommentId($data) {
		$comment_id = $data["comment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/comment", "get_object_comments_by_comment_id", array("comment_id" => $comment_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			$conditions = array("comment_id" => $comment_id);
			return $ObjectComment->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mc_object_comment", null, array("comment_id" => $comment_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.getObjectCommentsByCommentId", $data, $options);
	}
	
	/**
	 * @param (name=data[comment_id], type=bigint, not_null=1, length=19)
	 */
	public function countObjectCommentsByCommentId($data) {
		$comment_id = $data["comment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/comment", "count_object_comments_by_comment_id", array("comment_id" => $comment_id), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			return $ObjectComment->count(array("conditions" => array("comment_id" => $comment_id)));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mc_object_comment", array("comment_id" => $comment_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.countObjectCommentsByCommentId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectCommentsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/comment", "get_object_comments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $ObjectComment->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mc_object_comment", null, array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.getObjectCommentsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][comment_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function getObjectCommentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/comment", "get_object_comments_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
				return $ObjectComment->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mc_object_comment", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/comment", "ObjectCommentService.getObjectCommentsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][comment_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function countObjectCommentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/comment", "count_object_comments_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
				return $ObjectComment->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mc_object_comment", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/comment", "ObjectCommentService.countObjectCommentsByConditions", $data, $options);
		}
	}
	
	public function getAllObjectComments($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/comment", "get_all_object_comments", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			return $ObjectComment->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mc_object_comment", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.getAllObjectComments", null, $options);
	}
	
	public function countAllObjectComments($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/comment", "count_all_object_comments", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectComment = $this->getObjectCommentHbnObj($b, $options);
			return $ObjectComment->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mc_object_comment", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "ObjectCommentService.countAllObjectComments", null, $options);
	}
}
?>
