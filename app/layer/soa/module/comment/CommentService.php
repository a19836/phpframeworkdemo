<?php
namespace Module\Comment;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/CommentDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class CommentService extends \soa\CommonService {
	private $Comment;
	
	private function getCommentHbnObj($b, $options) {
		if (!$this->Comment) 
			$this->Comment = $b->callObject("module/comment", "Comment", $options);
		
		return $this->Comment;
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[comment], type=longblob, not_null=1)
	 */
	public function insertComment($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["comment"] = addcslashes($data["comment"], "\\'");
			
			$status = $b->callInsert("module/comment", "insert_comment", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			$status = $Comment->insert($data, $ids);
			return $status ? $ids["comment_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("mc_comment", array(
					"user_id" => $data["user_id"], 
					"comment" => $data["comment"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.insertComment", $data, $options);
	}
	
	/**
	 * @param (name=data[comment_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[comment], type=longblob, not_null=1)
	 */
	public function updateComment($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["comment"] = addcslashes($data["comment"], "\\'");
			
			return $b->callUpdate("module/comment", "update_comment", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mc_comment", array(
					"user_id" => $data["user_id"], 
					"comment" => $data["comment"], 
					"modified_date" => $data["modified_date"]
				), array(
					"comment_id" => $data["comment_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.updateComment", $data, $options);
	}
	
	/**
	 * @param (name=data[comment_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteComment($data) {
		$comment_id = $data["comment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/comment", "delete_comment", array("comment_id" => $comment_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->delete($comment_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mc_comment", array("comment_id" => $comment_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.deleteComment", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteCommentsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/comment", "delete_comments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->callDelete("delete_comments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = CommentDBDAOServiceUtil::delete_comments_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
			
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.deleteCommentsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[comment_id], type=bigint, not_null=1, length=19)  
	 */
	public function getComment($data) {
		$comment_id = $data["comment_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/comment", "get_comment", array("comment_id" => $comment_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->findById($comment_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mc_comment", array("comment_id" => $comment_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.getComment", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][comment_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][comment], type=longblob|array)
	 */
	public function getCommentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/comment", "get_comments_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Comment = $this->getCommentHbnObj($b, $options);
				return $Comment->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mc_comment", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/comment", "CommentService.getCommentsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][comment_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][comment], type=longblob|array)
	 */
	public function countCommentsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/comment", "count_comments_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Comment = $this->getCommentHbnObj($b, $options);
				return $Comment->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mc_comment", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/comment", "CommentService.countCommentsByConditions", $data, $options);
		}
	}
	
	public function getAllComments($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/comment", "get_all_comments", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mc_comment", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.getAllComments", null, $options);
	}
	
	public function countAllComments($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/comment", "count_all_comments", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mc_comment", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.countAllComments", null, $options);
	}
	
	/**
	 * @param (name=data[comment_ids], type=mixed, not_null=1)  
	 */
	public function getCommentsByIds($data) {
		$comment_ids = $data["comment_ids"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($comment_ids) {
			$comment_ids_str = "";//just in case the user tries to hack the sql query. By default all comment_id should be numeric.
			$comment_ids = is_array($comment_ids) ? $comment_ids : array($comment_ids);
			foreach ($comment_ids as $comment_id) 
				$comment_ids_str .= ($comment_ids_str ? ", " : "") . "'" . addcslashes($comment_id, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) 
				return $b->callSelect("module/comment", "get_comments_by_ids", array("comment_ids" => $comment_ids_str), $options);
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Comment = $this->getCommentHbnObj($b, $options);
				$conditions = array("comment_id" => array("operator" => "in", "value" => $comment_ids));
				return $Comment->find(array("conditions" => $conditions), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				return $b->findObjects("mc_comment", null, array("comment_id" => array("operator" => "in", "value" => $comment_ids)), $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/comment", "CommentService.getCommentsByIds", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 */
	public function getCommentsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/comment", "get_comments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->callSelect("get_comments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = CommentDBDAOServiceUtil::get_comments_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
				
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.getCommentsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 */
	public function getCommentsByObjectGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/comment", "get_comments_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->callSelect("get_comments_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = CommentDBDAOServiceUtil::get_comments_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
				
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.getCommentsByObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[parent_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[parent_object_id], type=bigint, not_null=1, length=19) 
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 */
	public function getParentObjectCommentsByObject($data) {
		$parent_object_type_id = $data["parent_object_type_id"];
		$parent_object_id = $data["parent_object_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/comment", "get_parent_object_comments_by_object", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->callSelect("get_parent_object_comments_by_object", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = CommentDBDAOServiceUtil::get_parent_object_comments_by_object(array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
				
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.getParentObjectCommentsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[parent_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[parent_object_id], type=bigint, not_null=1, length=19) 
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 */
	public function getParentObjectCommentsByObjectGroup($data) {
		$parent_object_type_id = $data["parent_object_type_id"];
		$parent_object_id = $data["parent_object_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/comment", "get_parent_object_comments_by_object_group", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->callSelect("get_parent_object_comments_by_object_group", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = CommentDBDAOServiceUtil::get_parent_object_comments_by_object_group(array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
				
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.getParentObjectCommentsByObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[parent_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[parent_object_id], type=bigint, not_null=1, length=19)    
	 * @param (name=data[parent_group], type=bigint, default=0, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 */
	public function getParentObjectGroupCommentsByObject($data) {
		$parent_object_type_id = $data["parent_object_type_id"];
		$parent_object_id = $data["parent_object_id"];
		$parent_group = $data["parent_group"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/comment", "get_parent_object_group_comments_by_object", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->callSelect("get_parent_object_group_comments_by_object", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = CommentDBDAOServiceUtil::get_parent_object_group_comments_by_object(array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id));
				
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.getParentObjectGroupCommentsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[parent_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[parent_object_id], type=bigint, not_null=1, length=19)    
	 * @param (name=data[parent_group], type=bigint, default=0, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 */
	public function getParentObjectGroupCommentsByObjectGroup($data) {
		$parent_object_type_id = $data["parent_object_type_id"];
		$parent_object_id = $data["parent_object_id"];
		$parent_group = $data["parent_group"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/comment", "get_parent_object_group_comments_by_object_group", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Comment = $this->getCommentHbnObj($b, $options);
			return $Comment->callSelect("get_parent_object_group_comments_by_object_group", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = CommentDBDAOServiceUtil::get_parent_object_group_comments_by_object_group(array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
				
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/comment", "CommentService.getParentObjectGroupCommentsByObjectGroup", $data, $options);
	}
}
?>
