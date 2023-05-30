<?php
class StudentResourceUtil {

	
	/**
	 * Get records from table: student.
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
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "StudentResourceService.getAll", $data, $options);
		
		return $result;
	}
	
	/**
	 * Count records from table: student.
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
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "StudentResourceService.count", $data, $options);
		
		return $result;
	}
	
	/**
	 * Delete record from table: student.
	 */
	public static function delete ($EVC, $pks, $no_cache = true) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "StudentResourceService.delete", $data, $options);
		
		return $result;
	}
	
	/**
	 * Insert data into table: student.
	 */
	public static function insert ($EVC, $attributes, $no_cache = true) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"attributes" => $attributes,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "StudentResourceService.insert", $data, $options);
		
		return $result;
	}
	
	/**
	 * Get a record from table: student.
	 */
	public static function get ($EVC, $pks, $no_cache = false) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "StudentResourceService.get", $data, $options);
		
		return $result;
	}
	
	/**
	 * Update data into table: student.
	 */
	public static function update ($EVC, $attributes, $pks, $no_cache = true) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"attributes" => $attributes,
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "StudentResourceService.update", $data, $options);
		
		return $result;
	}
	
	/**
	 * Delete multiple records at once from table: student.
	 */
	public static function multipleDelete ($EVC, $pks, $no_cache = true) {
		$data = array(
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "StudentResourceService.multipleDelete", $data, $options);
		return $result;
	}
}
?>