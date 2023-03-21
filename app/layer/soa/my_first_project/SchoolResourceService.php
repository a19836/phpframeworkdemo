<?php
include_once $vars["business_logic_modules_service_common_file_path"];

class SchoolResourceService extends \soa\CommonService {

	
	/**
	 * Get parsed resource records from table: school.
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
		$result = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "SchoolService.getAll", $data, $options);
		return $result;
	}
	
	/**
	 * Delete parsed resource record from table: school.
	 */
	public function delete ($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$pks = $data["pks"];
		
		if ($pks) {
			$status = true;
			
			if (array_key_exists("school_id", $pks) && !is_numeric($pks["school_id"])) $pks["school_id"] = null;
			if (array_key_exists("school_id", $pks) && !is_numeric($pks["school_id"])) $status = false;
			
			
			if ($status) {
				$status = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "SchoolService.delete", array(
					"school_id" => $pks['school_id']
				), $options);
			}
			
			return $status;
		}
	}
	
	/**
	 * Delete multiple records at once parsed resource record from table: school.
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
	 * Count parsed resource records from table: school.
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
		$result = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "SchoolService.countAll", $data, $options);
		
		return $result;
	}
	
	/**
	 * Insert parsed resource data into table: school.
	 */
	public function insert ($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$attributes = $data["attributes"];
		
		if ($attributes) {
			
			$result = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "SchoolService.insert", array(
				"school_id" => $attributes['school_id'],
				"name" => $attributes['name']
			), $options);
			
			
			return $result;
		}
	}
	
	/**
	 * Get a parsed resource record from table: school.
	 */
	public function get ($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$pks = $data["pks"];
		
		$result = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "SchoolService.get", array(
			"school_id" => $pks['school_id']
		), $options);
		
		return $result;
	}
	
	/**
	 * Update parsed resource data into table: school.
	 */
	public function update ($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$attributes = $data["attributes"];
		$pks = $data["pks"];
		
		if ($attributes && $pks) {
			$status = true;
			
			if (array_key_exists("school_id", $attributes) && !is_numeric($attributes["school_id"])) $attributes["school_id"] = null;
			if (array_key_exists("school_id", $attributes) && !is_numeric($attributes["school_id"])) $status = false;
			
			if (array_key_exists("school_id", $pks) && !is_numeric($pks["school_id"])) $pks["school_id"] = null;
			if (array_key_exists("school_id", $pks) && !is_numeric($pks["school_id"])) $status = false;
			
			
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
						$data = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "SchoolService.get", array(
							"school_id" => $filtered_attributes['school_id']
						), $options);
						
						if (!$data || !is_array($data))
							return false;
						
						foreach ($filtered_attributes as $attr_name => $attr_value)
							$data[$attr_name] = $attr_value;
						
						$status = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "SchoolService.update", array(
							"name" => $data['name'],
							"school_id" => $data['school_id']
						), $options);
					}
					
					if ($status && $filtered_pks) {
						$status = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "SchoolService.updatePrimaryKeys", array(
							"new_school_id" => $filtered_pks['new_school_id'],
							"old_school_id" => $filtered_pks['old_school_id']
						), $options);
					}
				}
			}
			
			return $status;
		}
	}
	
	/**
	 * Get parsed resource key-value pair list from table: school, where the key is the table primary key and the value is the table attribute label.
	 */
	public function getAllOptions ($data) {
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
		$result = $this->getBusinessLogicLayer()->callBusinessLogic("my_first_project", "SchoolService.getAll", $data, $options);
		
		$options = array();
		
		if ($result) 
			for ($i = 0, $t = count($result); $i < $t; $i++) {
				$item = $result[$i];
				$key = $item["school_id"];
				$value = $item["name"];
				$options[$key] = $value;
			}
		
		return $options;
	}
}
?>