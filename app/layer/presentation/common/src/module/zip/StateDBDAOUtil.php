<?php
if (!class_exists("StateDBDAOUtil")) {
	class StateDBDAOUtil {
		
		public static function get_state($data = array()) {
			return "select st.*, co.country_id, co.name as 'country'
					from mz_state st
					inner join mz_country co on co.country_id=st.country_id
					where st.state_id=" . $data["state_id"];
		}
	
		public static function get_full_states($data = array()) {
			return "select st.*, co.country_id, co.name as 'country'
					from mz_state st
					inner join mz_country co on co.country_id=st.country_id";
		}
	
	}
}
?>