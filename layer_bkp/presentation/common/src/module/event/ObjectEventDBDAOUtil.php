<?php
if (!class_exists("ObjectEventDBDAOUtil")) {
	class ObjectEventDBDAOUtil {
		
		public static function change_object_events_object_ids_of_parent_object($data = array()) {
			return "update me_object_event oe
					inner join me_object_event oe2 on oe.event_id=oe2.event_id and oe2.object_type_id=" . $data["parent_object_type_id"] . " and oe2.object_id=" . $data["parent_object_id"] . "
					set oe.object_type_id=" . $data["new_object_type_id"] . ", oe.object_id=" . $data["new_object_id"] . ", oe.modified_date='" . $data["modified_date"] . "' 
					where oe.object_type_id=" . $data["old_object_type_id"] . " and oe.object_id=" . $data["old_object_id"];
		}
	
	}
}
?>