<?php
if (!class_exists("CommentDBDAOUtil")) {
	class CommentDBDAOUtil {
		
		public static function delete_comments_by_object($data = array()) {
			return "delete c 
					from mc_comment c
					inner join mc_object_comment oc on oc.comment_id=c.comment_id and oc.object_type_id=" . $data["object_type_id"] . " and oc.object_id=" . $data["object_id"];
		}
	
		public static function get_comments_by_object($data = array()) {
			return "select c.*, oc.`group` `group`, oc.`order` `order`
					from mc_comment c
					inner join mc_object_comment oc on oc.comment_id=c.comment_id and oc.object_type_id=" . $data["object_type_id"] . " and oc.object_id=" . $data["object_id"];
		}
	
		public static function get_comments_by_object_group($data = array()) {
			return "select c.*, oc.`group` `group`, oc.`order` `order`
					from mc_comment c
					inner join mc_object_comment oc on oc.comment_id=c.comment_id and oc.object_type_id=" . $data["object_type_id"] . " and oc.object_id=" . $data["object_id"] . " and oc.`group`=" . $data["group"];
		}
	
		public static function get_parent_object_comments_by_object($data = array()) {
			return "select c.*, oc.`group` `group`, oc.`order` `order`
					from mc_comment c
					inner join mc_object_comment oc on oc.comment_id=c.comment_id and oc.object_type_id=" . $data["object_type_id"] . " and oc.object_id=" . $data["object_id"] . "
					inner join mc_object_comment poc on poc.comment_id=c.comment_id and poc.object_type_id=" . $data["parent_object_type_id"] . " and poc.object_id=" . $data["parent_object_id"];
		}
	
		public static function get_parent_object_comments_by_object_group($data = array()) {
			return "select c.*, oc.`group` `group`, oc.`order` `order`
					from mc_comment c
					inner join mc_object_comment oc on oc.comment_id=c.comment_id and oc.object_type_id=" . $data["object_type_id"] . " and oc.object_id=" . $data["object_id"] . " and oc.`group`=" . $data["group"] . "
					inner join mc_object_comment poc on poc.comment_id=c.comment_id and poc.object_type_id=" . $data["parent_object_type_id"] . " and poc.object_id=" . $data["parent_object_id"];
		}
	
		public static function get_parent_object_group_comments_by_object($data = array()) {
			return "select c.*, oc.`group` `group`, oc.`order` `order`
					from mc_comment c
					inner join mc_object_comment oc on oc.comment_id=c.comment_id and oc.object_type_id=" . $data["object_type_id"] . " and oc.object_id=" . $data["object_id"] . "
					inner join mc_object_comment poc on poc.comment_id=c.comment_id and poc.object_type_id=" . $data["parent_object_type_id"] . " and poc.object_id=" . $data["parent_object_id"] . " and poc.`group`=" . $data["parent_group"];
		}
	
		public static function get_parent_object_group_comments_by_object_group($data = array()) {
			return "select c.*, oc.`group` `group`, oc.`order` `order`
					from mc_comment c
					inner join mc_object_comment oc on oc.comment_id=c.comment_id and oc.object_type_id=" . $data["object_type_id"] . " and oc.object_id=" . $data["object_id"] . " and oc.`group`=" . $data["group"] . "
					inner join mc_object_comment poc on poc.comment_id=c.comment_id and poc.object_type_id=" . $data["parent_object_type_id"] . " and poc.object_id=" . $data["parent_object_id"] . " and poc.`group`=" . $data["parent_group"];
		}
	
	}
}
?>