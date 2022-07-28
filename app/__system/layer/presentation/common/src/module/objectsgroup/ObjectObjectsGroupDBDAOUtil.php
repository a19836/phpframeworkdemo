<?php
if (!class_exists("ObjectObjectsGroupDBDAOUtil")) {
	class ObjectObjectsGroupDBDAOUtil {
		
		public static function change_object_objects_groups_object_ids_of_parent_object($data = array()) {
			return "update mog_object_objects_group oog
					inner join mog_object_objects_group oog2 on oog.objects_group_id=oog2.objects_group_id and oog2.object_type_id=" . $data["parent_object_type_id"] . " and oog2.object_id=" . $data["parent_object_id"] . "
					set oog.object_type_id=" . $data["new_object_type_id"] . ", oog.object_id=" . $data["new_object_id"] . ", oog.modified_date='" . $data["modified_date"] . "' 
					where oog.object_type_id=" . $data["old_object_type_id"] . " and oog.object_id=" . $data["old_object_id"];
		}
	
	}
}
?>