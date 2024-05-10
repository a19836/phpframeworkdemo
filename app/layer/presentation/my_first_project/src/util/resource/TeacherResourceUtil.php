<?php
class TeacherResourceUtil {

	
	/**
	 * Get records from table: teacher.
	 */
	public static function getAll ($EVC, $limit = false, $start = false, $conditions = false, $conditions_type = false, $conditions_case = false, $conditions_join = false, $sort = false, $no_cache = false) {
		$options = array(
			"no_cache" => $no_cache,
			"limit" => $limit,
			"start" => $start,
			"sort" => $sort
		);
		$data = array(
			"conditions" => $conditions,
			"conditions_type" => $conditions_type,
			"conditions_case" => $conditions_case,
			"conditions_join" => $conditions_join,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "TeacherResourceService.getAll", $data, $options);
		
		return $result;
	}
	
	/**
	 * Count records from table: teacher.
	 */
	public static function count ($EVC, $conditions = false, $conditions_type = false, $conditions_case = false, $conditions_join = false, $no_cache = false) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"conditions" => $conditions,
			"conditions_type" => $conditions_type,
			"conditions_case" => $conditions_case,
			"conditions_join" => $conditions_join,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "TeacherResourceService.count", $data, $options);
		
		return $result;
	}
	
	/**
	 * Delete record from table: teacher.
	 */
	public static function delete ($EVC, $pks, $no_cache = true) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "TeacherResourceService.delete", $data, $options);
		
		return $result;
	}
	
	/**
	 * Delete multiple records at once from table: teacher.
	 */
	public static function multipleDelete ($EVC, $pks, $no_cache = true) {
		$data = array(
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "TeacherResourceService.multipleDelete", $data, $options);
		return $result;
	}
	
	/**
	 * Insert data into table: teacher.
	 */
	public static function insert ($EVC, $attributes, $no_cache = true) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"attributes" => $attributes,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "TeacherResourceService.insert", $data, $options);
		
		return $result;
	}
	
	/**
	 * Get a record from table: teacher.
	 */
	public static function get ($EVC, $pks, $no_cache = false) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "TeacherResourceService.get", $data, $options);
		
		return $result;
	}
	
	/**
	 * Update data into table: teacher.
	 */
	public static function update ($EVC, $attributes, $pks, $no_cache = true) {
		$options = array(
			"no_cache" => $no_cache
		);
		$data = array(
			"attributes" => $attributes,
			"pks" => $pks,
		);
		$result = $EVC->getBroker("soa")->callBusinessLogic("my_first_project", "TeacherResourceService.update", $data, $options);
		
		return $result;
	}
}
?>