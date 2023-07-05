<?php
class SchoolResourceUtil {
	
	
	/**
	 * Get records from table: school.
	 */
	public static function getAll ($EVC, $limit = false, $start = false, $conditions = false, $conditions_type = false, $conditions_join = false, $sort = false, $no_cache = false) {
		$options = array(
			"no_cache" => $no_cache,
			"limit" => $limit,
			"start" => $start,
			"sort" => $sort
		);
		$data = array(
			"conditions" => $conditions,
			"conditions_type" => $conditions_type,
			"conditions_join" => $conditions_join,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "SchoolResourceService.getAll", $data, $options);
		
		return $result;
	}
	
	/**
	 * Delete record from table: school.
	 */
	public static function delete ($EVC, $pks, $no_cache = true) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "SchoolResourceService.delete", $data, $options);
		
		return $result;
	}
	
	/**
	 * Delete multiple records at once from table: school.
	 */
	public static function multipleDelete ($EVC, $pks, $no_cache = true) {
		$data = array(
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "SchoolResourceService.multipleDelete", $data, $options);
		return $result;
	}
	
	/**
	 * Count records from table: school.
	 */
	public static function count ($EVC, $conditions = false, $conditions_type = false, $conditions_join = false, $no_cache = false) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"conditions" => $conditions,
			"conditions_type" => $conditions_type,
			"conditions_join" => $conditions_join,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "SchoolResourceService.count", $data, $options);
		
		return $result;
	}
	
	/**
	 * Insert data into table: school.
	 */
	public static function insert ($EVC, $attributes, $no_cache = true) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"attributes" => $attributes,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "SchoolResourceService.insert", $data, $options);
		
		return $result;
	}
	
	/**
	 * Get a record from table: school.
	 */
	public static function get ($EVC, $pks, $no_cache = false) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "SchoolResourceService.get", $data, $options);
		
		return $result;
	}
	
	/**
	 * Update data into table: school.
	 */
	public static function update ($EVC, $attributes, $pks, $no_cache = true) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"attributes" => $attributes,
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "SchoolResourceService.update", $data, $options);
		
		return $result;
	}
	
	/**
	 * Get key-value pair list from table: school, where the key is the table primary key and the value is the table attribute label.
	 */
	public static function getAllOptions ($EVC, $limit = false, $start = false, $conditions = false, $conditions_type = false, $conditions_join = false, $sort = false, $no_cache = false) {
		$options = array(
			"no_cache" => $no_cache,
			"limit" => $limit,
			"start" => $start,
			"sort" => $sort
		);
		$data = array(
			"conditions" => $conditions,
			"conditions_type" => $conditions_type,
			"conditions_join" => $conditions_join,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "SchoolResourceService.getAllOptions", $data, $options);
		
		return $result;
	}
}
?>