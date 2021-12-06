<?php
if (!class_exists("CityDBDAOUtil")) {
	class CityDBDAOUtil {
		
		public static function delete_cities_by_country_id($data = array()) {
			return "delete ci.*
					from mz_state st on st.country_id=" . $data["country_id"] . "
					inner join mz_city ci on ci.state_id=st.state_id";
		}
	
		public static function get_city($data = array()) {
			return "select ci.*, st.state_id, st.name as 'state', co.country_id, co.name as 'country'
					from mz_city ci
					inner join mz_state st on st.state_id=ci.state_id
					inner join mz_country co on co.country_id=st.country_id
					where ci.city_id=" . $data["city_id"];
		}
	
		public static function get_full_cities($data = array()) {
			return "select ci.*, st.state_id, st.name as 'state', co.country_id, co.name as 'country'
					from mz_city ci
					inner join mz_state st on st.state_id=ci.state_id
					inner join mz_country co on co.country_id=st.country_id";
		}
	
	}
}
?>