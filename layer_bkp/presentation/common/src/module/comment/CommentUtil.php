<?php
include_once get_lib("org.phpframework.encryption.CryptoKeyHandler");
include_once __DIR__ . "/CommentDBDAOUtil.php"; //this file will be automatically generated on this module installation

if (!class_exists("CommentUtil")) {
	class CommentUtil {
	
		/* COMMENT FUNCTIONS */
	
		public static function insertComment($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["user_id"])) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$comment_id = $broker->callBusinessLogic("module/comment", "CommentService.insertComment", $data);
						$status = $comment_id ? true : false;
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["comment"] = addcslashes($data["comment"], "\\'");
					
						$status = $broker->callInsert("module/comment", "insert_comment", $data);
						$comment_id = $status ? $broker->getInsertedId() : $status;
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Comment = $broker->callObject("module/comment", "Comment");
						$status = $Comment->insert($data, $ids);
						$comment_id = $status ? $ids["comment_id"] : $status;
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$status = $broker->insertObject("mc_comment", array(
								"user_id" => $data["user_id"], 
								"comment" => $data["comment"], 
								"created_date" => $data["created_date"], 
								"modified_date" => $data["modified_date"]
							));
						$comment_id = $status ? $broker->getInsertedId() : $status;
						break;
					}
				}
				
				if ($status && $comment_id && !self::updateObjectCommentsByCommentId(array($broker), $comment_id, $data))
					$status = false;
			
				return $status ? $comment_id : false;
			}
		}
	
		public static function updateComment($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["comment_id"]) && is_numeric($data["user_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$status = $broker->callBusinessLogic("module/comment", "CommentService.updateComment", $data);
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["comment"] = addcslashes($data["comment"], "\\'");
					
						$status = $broker->callUpdate("module/comment", "update_comment", $data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Comment = $broker->callObject("module/comment", "Comment");
						$status = $Comment->update($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$status = $broker->updateObject("mc_comment", array(
								"user_id" => $data["user_id"], 
								"comment" => $data["comment"], 
								"modified_date" => $data["modified_date"]
							), array(
								"comment_id" => $data["comment_id"]
							));
						break;
					}
				}
				
				if ($status && $data["comment_id"] && !self::updateObjectCommentsByCommentId(array($broker), $data["comment_id"], $data))
					$status = false;
			
				return $status;
			}
		}
	
		public static function deleteComment($brokers, $comment_id) {
			if (is_array($brokers) && is_numeric($comment_id)) {
				if (self::deleteObjectCommentsByCommentId($brokers, $comment_id))
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							return $broker->callBusinessLogic("module/comment", "CommentService.deleteComment", array("comment_id" => $comment_id));
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							return $broker->callDelete("module/comment", "delete_comment", array("comment_id" => $comment_id));
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$Comment = $broker->callObject("module/comment", "Comment");
							return $Comment->delete($comment_id);
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							return $broker->deleteObject("mc_comment", array("comment_id" => $comment_id));
						}
					}
			}
		}
	
		public static function deleteCommentsByObject($brokers, $object_type_id, $object_id) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
					$data = array("object_type_id" => $object_type_id, "object_id" => $object_id);
					
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							$status = $broker->callBusinessLogic("module/comment", "CommentService.deleteCommentsByObject", $data);
							break;
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							$status = $broker->callDelete("module/comment", "delete_comments_by_object", $data);
							break;
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$Comment = $broker->callObject("module/comment", "Comment");
							$status = $Comment->callDelete("delete_comments_by_object", $data);
							break;
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							$sql = CommentDBDAOUtil::delete_comments_by_object($data);
							
							$status = $broker->setSQL($sql);
							break;
						}
					}
					
					if ($status)
						return self::deleteObjectCommentsByObject(array($broker), $object_type_id, $object_id);
			}
		}
	
		public static function getAllComments($brokers, $options, $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						return $broker->callBusinessLogic("module/comment", "CommentService.getAllComments", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/comment", "get_all_comments", null, $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Comment = $broker->callObject("module/comment", "Comment");
						return $Comment->find(null, $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mc_comment", null, null, $options);
					}
				}
			}
		}
	
		public static function countAllComments($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/comment", "CommentService.countAllComments", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/comment", "count_all_comments", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Comment = $broker->callObject("module/comment", "Comment");
						return $Comment->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mc_comment", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getCommentsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/comment", "CommentService.getCommentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/comment", "get_comments_by_conditions", array("conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Comment = $broker->callObject("module/comment", "Comment");
						return $Comment->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mc_comment", null, $conditions, $options);
					}
				}
			}
		}
	
		public static function countCommentsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/comment", "CommentService.countCommentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/comment", "count_comments_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Comment = $broker->callObject("module/comment", "Comment");
						return $Comment->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mc_comment", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}
	
		public static function getCommentsByIds($brokers, $comment_ids, $no_cache = false) {
			if (is_array($brokers) && $comment_ids) {
				$comment_ids_str = "";//just in case the user tries to hack the sql query. By default all comment_id should be numeric.
				$comment_ids = is_array($comment_ids) ? $comment_ids : array($comment_ids);
				foreach ($comment_ids as $comment_id)
					$comment_ids_str .= ($comment_ids_str ? ", " : "") . "'" . addcslashes($comment_id, "\\'") . "'";
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/comment", "CommentService.getCommentsByIds", array("comment_ids" => $comment_ids, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						return $broker->callSelect("module/comment", "get_comments_by_ids", array("comment_ids" => $comment_ids_str), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Comment = $broker->callObject("module/comment", "Comment");
						$conditions = array("comment_id" => array("operator" => "in", "value" => $comment_ids));
						return $Comment->find(array("conditions" => $conditions), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mc_comment", null, array("comment_id" => array("operator" => "in", "value" => $comment_ids)), array("no_cache" => $no_cache));
					}
				}
			}
		}
		
		public static function getCommentsByObject($brokers, $object_type_id, $object_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/comment", "CommentService.getCommentsByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/comment", "get_comments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Comment = $broker->callObject("module/comment", "Comment");
						return $Comment->callSelect("get_comments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = CommentDBDAOUtil::get_comments_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
							
						return $broker->getSQL($sql, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getCommentsByObjectGroup($brokers, $object_type_id, $object_id, $group = null, $options, $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$group = is_numeric($group) ? $group : null;
						
						return $broker->callBusinessLogic("module/comment", "CommentService.getCommentsByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "options" => $options), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						
						return $broker->callSelect("module/comment", "get_comments_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						
						$Comment = $broker->callObject("module/comment", "Comment");
						return $Comment->callSelect("get_comments_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$sql = CommentDBDAOUtil::get_comments_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
							
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
		
		public static function getParentObjectCommentsByObject($brokers, $parent_object_type_id, $parent_object_id, $object_type_id, $object_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($parent_object_type_id) && is_numeric($parent_object_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/comment", "CommentService.getParentObjectCommentsByObject", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/comment", "get_parent_object_comments_by_object", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Comment = $broker->callObject("module/comment", "Comment");
						return $Comment->callSelect("get_parent_object_comments_by_object", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = CommentDBDAOUtil::get_parent_object_comments_by_object(array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
							
						return $broker->getSQL($sql, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getParentObjectCommentsByObjectGroup($brokers, $parent_object_type_id, $parent_object_id, $object_type_id, $object_id, $group = null, $options, $no_cache = false) {
			if (is_array($brokers) && is_numeric($parent_object_type_id) && is_numeric($parent_object_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$group = is_numeric($group) ? $group : null;
						
						return $broker->callBusinessLogic("module/comment", "CommentService.getParentObjectCommentsByObjectGroup", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "options" => $options), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						
						return $broker->callSelect("module/comment", "get_parent_object_comments_by_object_group", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						
						$Comment = $broker->callObject("module/comment", "Comment");
						return $Comment->callSelect("get_parent_object_comments_by_object_group", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$sql = CommentDBDAOUtil::get_parent_object_comments_by_object_group(array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
							
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	
		public static function getParentObjectGroupCommentsByObject($brokers, $parent_object_type_id, $parent_object_id, $parent_group = null, $object_type_id, $object_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($parent_object_type_id) && is_numeric($parent_object_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$parent_group = is_numeric($parent_group) ? $parent_group : null;
						
						return $broker->callBusinessLogic("module/comment", "CommentService.getParentObjectGroupCommentsByObject", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$parent_group = is_numeric($parent_group) ? $parent_group : 0;
						
						return $broker->callSelect("module/comment", "get_parent_object_group_comments_by_object", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$parent_group = is_numeric($parent_group) ? $parent_group : 0;
						
						$Comment = $broker->callObject("module/comment", "Comment");
						return $Comment->callSelect("get_parent_object_group_comments_by_object", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$parent_group = is_numeric($parent_group) ? $parent_group : 0;
						$sql = CommentDBDAOUtil::get_parent_object_group_comments_by_object(array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id));
							
						return $broker->getSQL($sql, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getParentObjectGroupCommentsByObjectGroup($brokers, $parent_object_type_id, $parent_object_id, $parent_group = null, $object_type_id, $object_id, $group = null, $options, $no_cache = false) {
			if (is_array($brokers) && is_numeric($parent_object_type_id) && is_numeric($parent_object_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$parent_group = is_numeric($parent_group) ? $parent_group : null;
						$group = is_numeric($group) ? $group : null;
						
						return $broker->callBusinessLogic("module/comment", "CommentService.getParentObjectGroupCommentsByObjectGroup", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "options" => $options), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$parent_group = is_numeric($parent_group) ? $parent_group : 0;
						$group = is_numeric($group) ? $group : 0;
						
						return $broker->callSelect("module/comment", "get_parent_object_group_comments_by_object_group", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$parent_group = is_numeric($parent_group) ? $parent_group : 0;
						$group = is_numeric($group) ? $group : 0;
						
						$Comment = $broker->callObject("module/comment", "Comment");
						return $Comment->callSelect("get_parent_object_group_comments_by_object_group", array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$parent_group = is_numeric($parent_group) ? $parent_group : 0;
						$group = is_numeric($group) ? $group : 0;
						$sql = CommentDBDAOUtil::get_parent_object_group_comments_by_object_group(array("parent_object_type_id" => $parent_object_type_id, "parent_object_id" => $parent_object_id, "parent_group" => $parent_group, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
							
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	
		/* OBJECT COMMENT FUNCTIONS */
	
		public static function insertObjectComment($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["comment_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"])) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						
						return $broker->callBusinessLogic("module/comment", "ObjectCommentService.insertObjectComment", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
						
						return $broker->callInsert("module/comment", "insert_object_comment", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						$ObjectComment = $broker->callObject("module/comment", "ObjectComment");
						return $ObjectComment->insert($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						return $broker->insertObject("mc_object_comment", array(
								"comment_id" => $data["comment_id"], 
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
	
		public static function updateObjectComment($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["new_comment_id"]) && is_numeric($data["new_object_type_id"]) && is_numeric($data["new_object_id"]) && is_numeric($data["old_comment_id"]) && is_numeric($data["old_object_type_id"]) && is_numeric($data["old_object_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						
						return $broker->callBusinessLogic("module/comment", "ObjectCommentService.updateObjectComment", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
						return $broker->callUpdate("module/comment", "update_object_comment", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						$ObjectComment = $broker->callObject("module/comment", "ObjectComment");
						return $ObjectComment->updatePrimaryKeys($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						return $broker->updateObject("mc_object_comment", array(
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
							));
					}
				}
			}
		}
	
		private static function updateObjectCommentsByCommentId($brokers, $comment_id, $data) {
			if (is_array($brokers) && is_numeric($comment_id)) {
				if (self::deleteObjectCommentsByCommentId($brokers, $comment_id)) {
					$status = true;
					$object_comments = is_array($data["object_comments"]) ? $data["object_comments"] : array();
				
					foreach ($object_comments as $object_comment) {
						if (is_numeric($object_comment["object_type_id"]) && is_numeric($object_comment["object_id"])) {
							$object_comment["comment_id"] = $comment_id;
					
							if (!self::insertObjectComment($brokers, $object_comment)) {
								$status = false;
							}
						}
					}
				
					return $status;
				}
			}
		}
	
		public static function deleteObjectComment($brokers, $comment_id, $object_type_id, $object_id) {
			if (is_array($brokers) && is_numeric($comment_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$data = array("comment_id" => $comment_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/comment", "ObjectCommentService.deleteObjectComment", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/comment", "delete_object_comment", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectComment = $broker->callObject("module/comment", "ObjectComment");
						return $ObjectComment->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mc_object_comment", $data);
					}
				}
			}
		}
	
		public static function deleteObjectCommentsByCommentId($brokers, $comment_id) {
			if (is_array($brokers) && is_numeric($comment_id)) {
				$data = array("comment_id" => $comment_id);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/comment", "ObjectCommentService.deleteObjectCommentsByCommentId", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/comment", "delete_object_comments_by_comment_id", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectComment = $broker->callObject("module/comment", "ObjectComment");
						return $ObjectComment->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mc_object_comment", $data);
					}
				}
			}
		}
	
		public static function deleteObjectCommentsByObject($brokers, $object_type_id, $object_id) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$data = array("object_type_id" => $object_type_id, "object_id" => $object_id);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/comment", "ObjectCommentService.deleteObjectCommentsByObject", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/comment", "delete_object_comments_by_object", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectComment = $broker->callObject("module/comment", "ObjectComment");
						return $ObjectComment->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mc_object_comment", $data);
					}
				}
			}
		}
	
		//$conditions must be an array containing multiple conditions
		public static function getObjectCommentsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/comment", "ObjectCommentService.getObjectCommentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/comment", "get_object_comment_by_conditions", array("conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectComment = $broker->callObject("module/comment", "ObjectComment");
						return $ObjectComment->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mc_object_comment", null, $conditions, $options);
					}
				}
			}
		}
	
		//$conditions must be an array containing multiple conditions
		public static function countObjectCommentsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/comment", "ObjectCommentService.countObjectCommentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/comment", "count_object_comment_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectComment = $broker->callObject("module/comment", "ObjectComment");
						return $ObjectComment->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mc_object_comment", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}
	
		public static function getObjectCommentsByCommentId($brokers, $comment_id, $options = array(), $no_cache = false) {
			if (is_array($brokers) && is_numeric($comment_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("comment_id" => $comment_id, "options" => $options);
						return $broker->callBusinessLogic("module/comment", "ObjectCommentService.getObjectCommentsByCommentId", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/comment", "get_object_comments_by_comment_id", array("comment_id" => $comment_id), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectComment = $broker->callObject("module/comment", "ObjectComment");
						return $ObjectComment->find(array("conditions" => array("comment_id" => $comment_id)), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mc_object_comment", null, array("comment_id" => $comment_id), $options);
					}
				}
			}
		}
	
		public static function countObjectCommentsByCommentId($brokers, $comment_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($comment_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("comment_id" => $comment_id, "options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/comment", "ObjectCommentService.countObjectCommentsByCommentId", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/comment", "count_object_comments_by_comment_id", array("comment_id" => $comment_id), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectComment = $broker->callObject("module/comment", "ObjectComment");
						return $ObjectComment->count(array("conditions" => array("comment_id" => $comment_id)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mc_object_comment", array("comment_id" => $comment_id), array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getAllObjectComments($brokers, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						return $broker->callBusinessLogic("module/comment", "ObjectCommentService.getAllObjectComments", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/comment", "get_all_object_comments", null, $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectComment = $broker->callObject("module/comment", "ObjectComment");
						return $ObjectComment->find(null, $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mc_object_comment", null, null, $options);
					}
				}
			}
		}
	
		public static function countAllObjectComments($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/comment", "ObjectCommentService.countAllObjectComments", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/comment", "count_all_object_comments", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectComment = $broker->callObject("module/comment", "ObjectComment");
						return $ObjectComment->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mc_object_comment", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	}
}
?>
