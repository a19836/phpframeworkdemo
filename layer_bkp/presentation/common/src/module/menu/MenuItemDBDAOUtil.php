<?php
if (!class_exists("MenuItemDBDAOUtil")) {
	class MenuItemDBDAOUtil {
		
		public static function get_menu_items_by_first_group_of_object($data = array()) {
			return "select i.* 
					from mmenu_item i
					inner join (
						select group_id from mmenu_object_group where object_type_id=" . $data["object_type_id"] . " and object_id=" . $data["object_id"] . " limit 1
					) og on og.group_id=i.group_id";
		}
	
		public static function get_menu_items_by_first_group_of_object_group($data = array()) {
			return "select i.* 
					from mmenu_item i
					inner join (
						select group_id from mmenu_object_group where object_type_id=" . $data["object_type_id"] . " and object_id=" . $data["object_id"] . " and `group`=" . $data["group"] . " limit 1
					) og on og.group_id=i.group_id";
		}
	
	}
}
?>