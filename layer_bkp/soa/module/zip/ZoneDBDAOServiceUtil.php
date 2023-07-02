<?php
namespace Module\Zip;

if (!class_exists("ZoneDBDAOServiceUtil")) {
	class ZoneDBDAOServiceUtil {
		
		public static function delete_zones_by_state_id($data = array()) {
			return "delete zo.*
					from mz_city ci on ci.state_id=" . $data["state_id"] . "
					inner join mz_zone zo on zo.city_id=ci.city_id";
		}
	
		public static function delete_zones_by_country_id($data = array()) {
			return "delete zo.*
					from mz_state st on st.country_id=" . $data["country_id"] . "
					inner join mz_city ci on ci.state_id=st.state_id
					inner join mz_zone zo on zo.city_id=ci.city_id";
		}
	
		public static function get_zone($data = array()) {
			return "select zo.*, ci.city_id, ci.name as 'city', st.state_id, st.name as 'state', co.country_id, co.name as 'country'
					from mz_zone zo
					inner join mz_city ci on ci.city_id=zo.city_id
					inner join mz_state st on st.state_id=ci.state_id
					inner join mz_country co on co.country_id=st.country_id
					where zo.zone_id=" . $data["zone_id"];
		}
	
		public static function get_full_zones($data = array()) {
			return "select zo.*, ci.city_id, ci.name as 'city', st.state_id, st.name as 'state', co.country_id, co.name as 'country'
					from mz_zone zo
					inner join mz_city ci on ci.city_id=zo.city_id
					inner join mz_state st on st.state_id=ci.state_id
					inner join mz_country co on co.country_id=st.country_id";
		}
	
	}
}
?>