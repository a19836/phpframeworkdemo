<?php
namespace Module\Tag;

if (!class_exists("TagDBDAOServiceUtil")) {
	class TagDBDAOServiceUtil {
		
		public static function get_tags_by_objects($data = array()) {
			return "select t.*, ot.object_type_id, ot.object_id, ot.`group`, ot.`order` 
					from mt_tag t
					inner join mt_object_tag ot on ot.tag_id=t.tag_id and ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id in (" . $data["object_ids"] . ")";
		}
	
	}
}
?>