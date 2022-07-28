<?php
namespace Module\Zip;

if (!class_exists("ZipDBDAOServiceUtil")) {
	class ZipDBDAOServiceUtil {
		
		public static function delete_zips_by_city_id($data = array()) {
			return "delete zi.*
					from join mz_zone zo on zo.city_id=" . $data["city_id"] . "
					inner join mz_zip zi on zi.zone_id=zo.zone_id";
		}
	
		public static function delete_zips_by_state_id($data = array()) {
			return "delete zi.*
					from mz_city ci on ci.state_id=" . $data["state_id"] . "
					inner join mz_zone zo on zo.city_id=ci.city_id
					inner join mz_zip zi on zi.zone_id=zo.zone_id";
		}
	
		public static function delete_zips_by_country_id($data = array()) {
			return "delete zi.*
					from mz_state st on st.country_id=" . $data["country_id"] . "
					inner join mz_city ci on ci.state_id=st.state_id
					inner join mz_zone zo on zo.city_id=ci.city_id
					inner join mz_zip zi on zi.zone_id=zo.zone_id";
		}
	
		public static function get_zip($data = array()) {
			return "select zi.*, zo.name as 'zone', ci.city_id, ci.name as 'city', st.state_id, st.name as 'state', co.name as 'country'
					from mz_zip zi
					inner join mz_zone zo on zo.zone_id=zi.zone_id
					inner join mz_city ci on ci.city_id=zo.city_id
					inner join mz_state st on st.state_id=ci.state_id
					inner join mz_country co on co.country_id=st.country_id
					where zi.zip_id='" . $data["zip_id"] . "' and zi.country_id=" . $data["country_id"];
		}
	
	}
}
?>