<?php
namespace Module\Menu;

if (!class_exists("MenuGroupDBDAOServiceUtil")) {
	class MenuGroupDBDAOServiceUtil {
		
		public static function get_menu_groups_by_object_and_conditions($data = array()) {
			return "select g.*, og.`group` `group`, og.`order` `order`
					from mmenu_group g
					inner join mmenu_object_group og on og.object_type_id=" . $data["object_type_id"] . " and og.object_id=" . $data["object_id"] . " and og.group_id=g.group_id
					where " . $data["conditions"];
		}
	
		public static function count_menu_groups_by_object_and_conditions($data = array()) {
			return "select count(g.group_id) total 
					from mmenu_group g
					inner join mmenu_object_group og on og.object_type_id=" . $data["object_type_id"] . " and og.object_id=" . $data["object_id"] . " and og.group_id=g.group_id
					where " . $data["conditions"];
		}
	
		public static function get_menu_groups_by_object($data = array()) {
			return "select g.*, og.`group` `group`, og.`order` `order`
					from mmenu_group g 
					inner join mmenu_object_group og on og.object_type_id=" . $data["object_type_id"] . " and og.object_id=" . $data["object_id"] . " and og.group_id=g.group_id";
		}
	
		public static function count_menu_groups_by_object($data = array()) {
			return "select count(g.group_id) total
					from mmenu_group g 
					inner join mmenu_object_group og on og.object_type_id=" . $data["object_type_id"] . " and og.object_id=" . $data["object_id"] . " and og.group_id=g.group_id";
		}
	
		public static function get_menu_groups_by_object_group($data = array()) {
			return "select g.*, og.`group` `group`, og.`order` `order`
					from mmenu_group g 
					inner join mmenu_object_group og on og.object_type_id=" . $data["object_type_id"] . " and og.object_id=" . $data["object_id"] . " and og.`group`=" . $data["group"] . " and og.group_id=g.group_id";
		}
	
		public static function count_menu_groups_by_object_group($data = array()) {
			return "select count(g.group_id) total
					from mmenu_group g 
					inner join mmenu_object_group og on og.object_type_id=" . $data["object_type_id"] . " and og.object_id=" . $data["object_id"] . " and og.`group`=" . $data["group"] . " and og.group_id=g.group_id";
		}
	
		public static function get_menu_groups_with_all_tags($data = array()) {
			return "select g.*, z.tag_group, z.tag_order, z.tags_count 
					from mmenu_group g 
					inner join (
						select g.group_id, group_concat(distinct ot.`group`) tag_group, max(ot.`order`) tag_order, count(t.tag) tags_count
						from mmenu_group g 
						inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=g.group_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by g.group_id having count(t.tag) >= " . $data["tags_count"] . "
					) z on z.group_id=g.group_id";
		}
	
		public static function count_menu_groups_with_all_tags($data = array()) {
			return "select count(group_id) total
					from (
						select g.group_id, count(t.tag) tags_count
						from mmenu_group g 
						inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=g.group_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by g.group_id having count(t.tag) >= " . $data["tags_count"] . "
					) Z";
		}
	
		public static function get_menu_groups_by_tags($data = array()) {
			return "select g.*, ot.`group` tag_group, ot.`order` tag_order
					from mmenu_group g 
					inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=g.group_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function count_menu_groups_by_tags($data = array()) {
			return "select count(g.group_id) total
					from mmenu_group g 
					inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=g.group_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
	}
}
?>