<?php
namespace Module\User;

if (!class_exists("UserDBDAOServiceUtil")) {
	class UserDBDAOServiceUtil {
		
		public static function get_users_with_user_types_by_conditions($data = array()) {
			return "select u.*, z.user_type_ids
					from mu_user u 
					inner join (
						select u.user_id, group_concat(uut.user_type_id) user_type_ids
						from mu_user u 
						left join mu_user_user_type uut on uut.user_id=u.user_id
						where " . $data["conditions"] . "
						group by u.user_id
					) z on z.user_id=u.user_id";
		}
	
		public static function count_users_with_user_types_by_conditions($data = array()) {
			return "select count(user_id) total 
					from mu_user 
					where " . $data["conditions"];
		}
	
		public static function get_users_with_environments_and_conditions($data = array()) {
			return "select u.*, z.environment_ids, z.user_type_ids
					from mu_user u 
					inner join (
						select u.user_id, group_concat(ue.environment_id) environment_ids, group_concat(uut.user_type_id) user_type_ids
						from mu_user u 
						inner join mu_user_environment ue on ue.user_id=u.user_id and ue.environment_id in (" . $data["environment_ids"] . ")
						left join mu_user_user_type uut on uut.user_id=u.user_id
						where " . $data["conditions"] . "
						group by u.user_id
					) z on z.user_id=u.user_id";
		}
	
		public static function get_users_without_environments_and_with_conditions($data = array()) {
			return "select u.*
					from mu_user u 
					inner join (
						select u.user_id 
						from mu_user u 
						left join mu_user_environment ue on ue.user_id=u.user_id
						where ue.environment_id is NULL and " . $data["conditions"] . "
						group by u.user_id
					) z on z.user_id=u.user_id";
		}
	
		public static function get_users_by_user_types_and_conditions($data = array()) {
			return "select u.*, z.user_type_ids
					from mu_user u 
					inner join (
						select u.user_id, group_concat(uut.user_type_id) user_type_ids
						from mu_user u 
						inner join mu_user_user_type uut on uut.user_id=u.user_id and uut.user_type_id in (" . $data["user_type_ids"] . ")
						where " . $data["conditions"] . "
						group by u.user_id
					) z on z.user_id=u.user_id";
		}
	
		public static function count_users_by_user_types_and_conditions($data = array()) {
			return "select count(distinct(u.user_id)) total
					from mu_user  u
					inner join mu_user_user_type uut on uut.user_id=u.user_id and uut.user_type_id in (" . $data["user_type_ids"] . ")
					where " . $data["conditions"];
		}
	
		public static function get_users_by_object_and_conditions($data = array()) {
			return "select u.*, z.`group`, z.`order`, z.user_type_ids
					from mu_user u 
					inner join (
						select u.user_id, ou.`group` `group`, ou.`order` `order`, group_concat(uut.user_type_id) user_type_ids
						from mu_user u 
						inner join mu_object_user ou on ou.user_id=u.user_id and ou.object_type_id=" . $data["object_type_id"] . " and ou.object_id=" . $data["object_id"] . "
						left join mu_user_user_type uut on uut.user_id=u.user_id
						where " . $data["conditions"] . "
						group by u.user_id, ou.`group`, ou.`order`
					) z on z.user_id=u.user_id";
		}
	
		public static function count_users_by_object_and_conditions($data = array()) {
			return "select count(distinct(u.user_id)) total
					from mu_user  u
					inner join mu_object_user ou on ou.user_id=u.user_id and ou.object_type_id=" . $data["object_type_id"] . " and ou.object_id=" . $data["object_id"] . "
					where " . $data["conditions"];
		}
	
		public static function get_users_by_object_group_and_conditions($data = array()) {
			return "select u.*, z.`group`, z.`order`, z.user_type_ids
					from mu_user u 
					inner join (
						select u.user_id, ou.`group` `group`, ou.`order` `order`, group_concat(uut.user_type_id) user_type_ids
						from mu_user u 
						inner join mu_object_user ou on ou.user_id=u.user_id and ou.object_type_id=" . $data["object_type_id"] . " and ou.object_id=" . $data["object_id"] . " and ou.`group`=" . $data["group"] . "
						left join mu_user_user_type uut on uut.user_id=u.user_id
						where " . $data["conditions"] . "
						group by u.user_id, ou.`group`, ou.`order`
					) z on z.user_id=u.user_id";
		}
	
		public static function count_users_by_object_group_and_conditions($data = array()) {
			return "select count(distinct(u.user_id)) total
					from mu_user  u
					inner join mu_object_user ou on ou.user_id=u.user_id and ou.object_type_id=" . $data["object_type_id"] . " and ou.object_id=" . $data["object_id"] . " and ou.`group`=" . $data["group"] . "
					where " . $data["conditions"];
		}
	
		public static function get_users_by_object_and_user_types_and_conditions($data = array()) {
			return "select u.*, z.`group`, z.`order`, z.user_type_ids
					from mu_user u 
					inner join (
						select u.user_id, ou.`group` `group`, ou.`order` `order`, group_concat(uut.user_type_id) user_type_ids
						from mu_user u 
						inner join mu_object_user ou on ou.user_id=u.user_id and ou.object_type_id=" . $data["object_type_id"] . " and ou.object_id=" . $data["object_id"] . "
						inner join mu_user_user_type uut on uut.user_id=u.user_id and uut.user_type_id in (" . $data["user_type_ids"] . ")
						where " . $data["conditions"] . "
						group by u.user_id, ou.`group`, ou.`order`
					) z on z.user_id=u.user_id";
		}
	
		public static function count_users_by_object_and_user_types_and_conditions($data = array()) {
			return "select count(distinct(u.user_id)) total
					from mu_user  u
					inner join mu_object_user ou on ou.user_id=u.user_id and ou.object_type_id=" . $data["object_type_id"] . " and ou.object_id=" . $data["object_id"] . "
					inner join mu_user_user_type uut on uut.user_id=u.user_id and uut.user_type_id in (" . $data["user_type_ids"] . ")
					where " . $data["conditions"];
		}
	
		public static function get_users_by_object_group_and_user_types_and_conditions($data = array()) {
			return "select u.*, z.`group`, z.`order`, z.user_type_ids
					from mu_user u 
					inner join (
						select u.user_id, ou.`group` `group`, ou.`order` `order`, group_concat(uut.user_type_id) user_type_ids
						from mu_user u 
						inner join mu_object_user ou on ou.user_id=u.user_id and ou.object_type_id=" . $data["object_type_id"] . " and ou.object_id=" . $data["object_id"] . " and ou.`group`=" . $data["group"] . "
						inner join mu_user_user_type uut on uut.user_id=u.user_id and uut.user_type_id in (" . $data["user_type_ids"] . ")
						where " . $data["conditions"] . "
						group by u.user_id, ou.`group`, ou.`order`
					) z on z.user_id=u.user_id";
		}
	
		public static function count_users_by_object_group_and_user_types_and_conditions($data = array()) {
			return "select count(distinct(u.user_id)) total
					from mu_user  u
					inner join mu_object_user ou on ou.user_id=u.user_id and ou.object_type_id=" . $data["object_type_id"] . " and ou.object_id=" . $data["object_id"] . " and ou.`group`=" . $data["group"] . "
					inner join mu_user_user_type uut on uut.user_id=u.user_id and uut.user_type_id in (" . $data["user_type_ids"] . ")
					where " . $data["conditions"];
		}
	
	}
}
?>