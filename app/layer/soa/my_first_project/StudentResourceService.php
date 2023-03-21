<?php
include_once $vars["business_logic_modules_service_common_file_path"];

class StudentResourceService extends \soa\CommonService {

	
	/**
	 * Get parsed resource records from table: student.
	 */
	public function getAll ($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$conditions = $data["conditions"];
		$conditions_type = $data["conditions_type"];
		$conditions_join = $data["conditions_join"];
		
		//prepare $conditions based in $conditions_type: starts_with or ends_with
		if ($conditions && $conditions_type)
			foreach ($conditions as $attribute_name => $attribute_value) {
				$attribute_condition_type = is_array($conditions_type) ? $conditions_type[$attribute_name] : $conditions_type;
				$attribute_operator = $attribute_condition_type == "starts_with" || $attribute_condition_type == "ends_with" || $attribute_condition_type == "contains" ? "like" : $attribute_condition_type;
				$attribute_join = is_array($conditions_join) ? $conditions_join[$attribute_name] : $conditions_join;
				
				if ($attribute_operator && $attribute_operator != "=" && $attribute_operator != "equal")
					$conditions[$attribute_name] = array(
						"operator" => $attribute_operator,
						"value" => ($attribute_condition_type == "starts_with" || $attribute_condition_type == "contains" ? "%" : "") . $attribute_value . ($attribute_condition_type == "ends_with" || $attribute_condition_type == "contains" ? "%" : ""),
					);
				
				if (strtolower($attribute_join) == "or") {
					$conditions[$attribute_join][$attribute_name] = $conditions[$attribute_name];
					unset($conditions[$attribute_name]);
			    	}
			}
			
		$conditions_join = "and";
		
		$data = array(
			"conditions" => $conditions,
			"conditions_join" => $conditions_join
		);
		$result = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "StudentService.getAll", $data, $options);
		return $result;
	}
	
	/**
	 * Delete parsed resource record from table: student.
	 */
	public function delete ($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$pks = $data["pks"];
		
		if ($pks) {
			$status = true;
			
			if (array_key_exists("student_id", $pks) && !is_numeric($pks["student_id"])) $pks["student_id"] = null;
			if (array_key_exists("student_id", $pks) && !is_numeric($pks["student_id"])) $status = false;
			
			
			if ($status) {
				$status = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "StudentService.delete", array(
					"student_id" => $pks['student_id']
				), $options);
			}
			
			return $status;
		}
	}
	
	/**
	 * Delete multiple records at once parsed resource record from table: student.
	 */
	public function multipleDelete ($data) {
		$status = true;
		$pks = $data["pks"];
		
		if ($pks)
		for ($i = 0, $t = count($pks); $i < $t; $i++) {
			$data["pks"] = $pks[$i];
			
			if (!$this->delete($data))
				$status = false;
		}
		
		return $status;
	}
	
	/**
	 * Count parsed resource records from table: student.
	 */
	public function count ($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$conditions = $data["conditions"];
		$conditions_type = $data["conditions_type"];
		$conditions_join = $data["conditions_join"];
		
		//prepare $conditions based in $conditions_type: starts_with or ends_with
		if ($conditions && $conditions_type)
			foreach ($conditions as $attribute_name => $attribute_value) {
				$attribute_condition_type = is_array($conditions_type) ? $conditions_type[$attribute_name] : $conditions_type;
				$attribute_operator = $attribute_condition_type == "starts_with" || $attribute_condition_type == "ends_with" || $attribute_condition_type == "contains" ? "like" : $attribute_condition_type;
				$attribute_join = is_array($conditions_join) ? $conditions_join[$attribute_name] : $conditions_join;
				
				if ($attribute_operator && $attribute_operator != "=" && $attribute_operator != "equal")
					$conditions[$attribute_name] = array(
						"operator" => $attribute_operator,
						"value" => ($attribute_condition_type == "starts_with" || $attribute_condition_type == "contains" ? "%" : "") . $attribute_value . ($attribute_condition_type == "ends_with" || $attribute_condition_type == "contains" ? "%" : ""),
					);
				
				if (strtolower($attribute_join) == "or") {
					$conditions[$attribute_join][$attribute_name] = $conditions[$attribute_name];
					unset($conditions[$attribute_name]);
			    	}
			}
			
		$conditions_join = "and";
		
		$data = array(
			"conditions" => $conditions,
			"conditions_join" => $conditions_join
		);
		$result = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "StudentService.countAll", $data, $options);
		
		return $result;
	}
	
	/**
	 * Insert parsed resource data into table: student.
	 */
	public function insert ($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$attributes = $data["attributes"];
		
		if ($attributes) {
			if (array_key_exists("school_id", $attributes) && !is_numeric($attributes["school_id"])) $attributes["school_id"] = null;
			
			$result = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "StudentService.insert", array(
				"student_id" => $attributes['student_id'],
				"school_id" => $attributes['school_id'],
				"name" => $attributes['name']
			), $options);
			
			
			return $result;
		}
	}
	
	/**
	 * Get a parsed resource record from table: student.
	 */
	public function get ($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$pks = $data["pks"];
		
		$result = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "StudentService.get", array(
			"student_id" => $pks['student_id']
		), $options);
		
		return $result;
	}
	
	/**
	 * Update parsed resource data into table: student.
	 */
	public function update ($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$attributes = $data["attributes"];
		$pks = $data["pks"];
		
		if ($attributes && $pks) {
			$status = true;
			
			if (array_key_exists("student_id", $attributes) && !is_numeric($attributes["student_id"])) $attributes["student_id"] = null;
			if (array_key_exists("school_id", $attributes) && !is_numeric($attributes["school_id"])) $attributes["school_id"] = null;
			if (array_key_exists("student_id", $attributes) && !is_numeric($attributes["student_id"])) $status = false;
			
			if (array_key_exists("student_id", $pks) && !is_numeric($pks["student_id"])) $pks["student_id"] = null;
			if (array_key_exists("student_id", $pks) && !is_numeric($pks["student_id"])) $status = false;
			
			
			if ($status) {
				//get new pks from $attributes and get $attributes without pks
				$filtered_pks = array();
				$filtered_attributes = array();
				
				foreach ($attributes as $attribute_name => $attribute_value) {
					if (array_key_exists($attribute_name, $pks)) {
						if ($attribute_value != $pks[$attribute_name])
							$filtered_pks["new_" . $attribute_name] = $attribute_value;
					}
					else
						$filtered_attributes[$attribute_name] = $attribute_value;
				}
				
				$status = $filtered_pks || $filtered_attributes;
				
				if ($status) {
					foreach ($pks as $pk_name => $pk_value) {
						if ($filtered_pks)
							$filtered_pks["old_" . $pk_name] = $pk_value;
						
						if ($filtered_attributes)
							$filtered_attributes[$pk_name] = $pk_value;
					}
					
					if ($filtered_attributes) {
						//get the record from DB bc the $attributes may only have a few attributes, so we need to populate the other ones in order to call the broker->update method.
						$data = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "StudentService.get", array(
							"student_id" => $filtered_attributes['student_id']
						), $options);
						
						if (!$data || !is_array($data))
							return false;
						
						foreach ($filtered_attributes as $attr_name => $attr_value)
							$data[$attr_name] = $attr_value;
						
						$status = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "StudentService.update", array(
							"school_id" => $data['school_id'],
							"name" => $data['name'],
							"student_id" => $data['student_id']
						), $options);
					}
					
					if ($status && $filtered_pks) {
						$status = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "StudentService.updatePrimaryKeys", array(
							"new_student_id" => $filtered_pks['new_student_id'],
							"old_student_id" => $filtered_pks['old_student_id']
						), $options);
					}
				}
			}
			
			return $status;
		}
	}
}
?>