<?php
if (!class_exists("ObjectsGroupDBDAOUtil")) {
	class ObjectsGroupDBDAOUtil {
		
		public static function get_objects_groups_by_tags($data = array()) {
			return "select og.*, ot.`group` tag_group, ot.`order` tag_order
					from mog_objects_group og
					inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=og.objects_group_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")";
		}
	
		public static function count_objects_groups_by_tags($data = array()) {
			return "select count(og.objects_group_id) total
					from mog_objects_group og
					inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=og.objects_group_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")";
		}
	
		public static function get_objects_groups_by_object_and_tags($data = array()) {
			return "select og.*, oog.`group` `group`, oog.`order` `order`, ot.`group` tag_group, ot.`order` tag_order
					from mog_objects_group og
					inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["objects_group_object_type_id"] . " and ot.object_id=og.objects_group_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")";
		}
	
		public static function count_objects_groups_by_object_and_tags($data = array()) {
			return "select count(og.objects_group_id) total
					from mog_objects_group og
					inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["objects_group_object_type_id"] . " and ot.object_id=og.objects_group_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")";
		}
	
		public static function get_objects_groups_by_object_group_and_tags($data = array()) {
			return "select og.*, oog.`group` `group`, oog.`order` `order`, ot.`group` tag_group, ot.`order` tag_order
					from mog_objects_group og
					inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . " and oog.`group`=" . $data["group"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["objects_group_object_type_id"] . " and ot.object_id=og.objects_group_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")";
		}
	
		public static function count_objects_groups_by_object_group_and_tags($data = array()) {
			return "select count(og.objects_group_id) total
					from mog_objects_group og
					inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . " and oog.`group`=" . $data["group"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["objects_group_object_type_id"] . " and ot.object_id=og.objects_group_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")";
		}
	
		public static function get_objects_groups_with_all_tags($data = array()) {
			return "select og.*, z.tag_group, z.tag_order, z.tags_count 
					from mog_objects_group og 
					inner join (
						select og.objects_group_id, ot.`group` tag_group, ot.`order` tag_order, count(t.tag) tags_count
						from mog_objects_group og
						inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=og.objects_group_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						group by og.objects_group_id, ot.`group`, ot.`order` having count(t.tag) >= " . $data["tags_count"] . "
					) z on z.objects_group_id=og.objects_group_id";
		}
	
		public static function count_objects_groups_with_all_tags($data = array()) {
			return "select count(objects_group_id) total
					from (
						select og.objects_group_id, count(t.tag) tags_count
						from mog_objects_group og
						inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=og.objects_group_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						group by og.objects_group_id having count(t.tag) >= " . $data["tags_count"] . "
					) Z";
		}
	
		public static function get_objects_groups_by_object_with_all_tags($data = array()) {
			return "select og.*, z.`group`, z.`order`, z.tag_group, z.tag_order, z.tags_count 
					from mog_objects_group og 
					inner join (
						select og.objects_group_id, oog.`group` `group`, oog.`order` `order`, ot.`group` tag_group, ot.`order` tag_order, count(t.tag) tags_count
						from mog_objects_group og
						inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["objects_group_object_type_id"] . " and ot.object_id=og.objects_group_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						group by og.objects_group_id, oog.`group`, oog.`order`, ot.`group`, ot.`order` having count(t.tag) >= " . $data["tags_count"] . "
					) z on z.objects_group_id=og.objects_group_id";
		}
	
		public static function count_objects_groups_by_object_with_all_tags($data = array()) {
			return "select count(objects_group_id) total
					from (
						select og.objects_group_id, count(t.tag) tags_count
						from mog_objects_group og
						inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["objects_group_object_type_id"] . " and ot.object_id=og.objects_group_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						group by og.objects_group_id having count(t.tag) >= " . $data["tags_count"] . "
					) Z";
		}
	
		public static function get_objects_groups_by_object_group_with_all_tags($data = array()) {
			return "select og.*, z.`group`, z.`order`, z.tag_group, z.tag_order, z.tags_count 
					from mog_objects_group og 
					inner join (
						select og.objects_group_id, oog.`group` `group`, oog.`order` `order`, ot.`group` tag_group, ot.`order` tag_order, count(t.tag) tags_count
						from mog_objects_group og
						inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . " and oog.`group`=" . $data["group"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["objects_group_object_type_id"] . " and ot.object_id=og.objects_group_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						group by og.objects_group_id, oog.`group`, oog.`order`, ot.`group`, ot.`order` having count(t.tag) >= " . $data["tags_count"] . "
					) z on z.objects_group_id=og.objects_group_id";
		}
	
		public static function count_objects_groups_by_object_group_with_all_tags($data = array()) {
			return "select count(objects_group_id) total
					from (
						select og.objects_group_id, count(t.tag) tags_count
						from mog_objects_group og
						inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . " and oog.`group`=" . $data["group"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["objects_group_object_type_id"] . " and ot.object_id=og.objects_group_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						group by og.objects_group_id having count(t.tag) >= " . $data["tags_count"] . "
					) Z";
		}
	
		public static function get_objects_groups_by_object_and_conditions($data = array()) {
			return "select og.*, oog.`group` `group`, oog.`order` `order`
					from mog_objects_group og
					inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . "
					where " . $data["conditions"];
		}
	
		public static function count_objects_groups_by_object_and_conditions($data = array()) {
			return "select count(og.objects_group_id) total 
					from mog_objects_group og
					inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . "
					where " . $data["conditions"];
		}
	
		public static function get_objects_groups_by_object($data = array()) {
			return "select og.*, oog.`group` `group`, oog.`order` `order`
					from mog_objects_group og 
					inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"];
		}
	
		public static function count_objects_groups_by_object($data = array()) {
			return "select count(og.objects_group_id) total
					from mog_objects_group og 
					inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"];
		}
	
		public static function get_objects_groups_by_object_group($data = array()) {
			return "select og.*, oog.`group` `group`, oog.`order` `order`
					from mog_objects_group og 
					inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . " and oog.`group`=" . $data["group"];
		}
	
		public static function count_objects_groups_by_object_group($data = array()) {
			return "select count(og.objects_group_id) total
					from mog_objects_group og 
					inner join mog_object_objects_group oog on oog.objects_group_id=og.objects_group_id and oog.object_type_id=" . $data["object_type_id"] . " and oog.object_id=" . $data["object_id"] . " and oog.`group`=" . $data["group"];
		}
	
	}
}
?>