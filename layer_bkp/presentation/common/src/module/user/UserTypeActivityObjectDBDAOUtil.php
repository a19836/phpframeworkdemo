<?php
if (!class_exists("UserTypeActivityObjectDBDAOUtil")) {
	class UserTypeActivityObjectDBDAOUtil {
		
		public static function get_user_type_activity_objects_by_user_id_and_conditions($data = array()) {
			return "select utao.* 
					from mu_user_type_activity_object utao
					inner join mu_user_user_type uut on uut.user_type_id=utao.user_type_id and uut.user_id=" . $data["user_id"] . "
					where " . $data["conditions"];
		}
	
		public static function count_user_type_activity_objects_by_user_id_and_conditions($data = array()) {
			return "select count(*) total 
					from mu_user_type_activity_object utao
					inner join mu_user_user_type uut on uut.user_type_id=utao.user_type_id and uut.user_id=" . $data["user_id"] . "
					where " . $data["conditions"];
		}
	
	}
}
?>