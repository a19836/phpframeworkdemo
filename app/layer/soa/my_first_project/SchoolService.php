<?php
include_once $vars["business_logic_modules_service_common_file_path"];

class SchoolService extends \soa\CommonService {
	
	private function getDBBroker() {
		$broker = $this->getBusinessLogicLayer()->getBroker("dbdata");
		
		return $broker;
	}
	
	private function getTableName() {
		return "school";
	}
	
	private function getTableAttributes() {
		$attributes = array("school_id", "name");
		return $attributes;
	}
	
	private function getTablePrimaryKeys() {
		$pks = array("school_id");
		return $pks;
	}
	
	private function getTableAutoIncrementPrimaryKeys() {
		$aipks = array("school_id");
		return $aipks;
	}
	
	private function filterDataByTableAttributes($data, $do_not_include_pks = true) {
		if ($data) {
			$attributes = $this->getTableAttributes();
			$pks = $do_not_include_pks ? self::getTablePrimaryKeys() : array();
			
			foreach ($data as $k => $v)
				if (!in_array($k, $attributes) || in_array($k, $pks))
					unset($data[$k]);
		}
		
		return $data;
	}
	
	private function filterDataByTablePrimaryKeys($data) {
		if ($data) {
			$pks = $this->getTablePrimaryKeys();
			
			foreach ($data as $k => $v)
				if (!in_array($k, $pks))
					unset($data[$k]);
		}
		
		return $data;
	}
	
	private function filterDataExcludingTableAutoIncrementPrimaryKeys($data) {
		if ($data) {
			$pks = $this->getTableAutoIncrementPrimaryKeys();
			
			foreach ($data as $k => $v)
				if (in_array($k, $pks))
					unset($data[$k]);
		}
		
		return $data;
	}
	
	
	/**
	 * @param (name=data[school_id], type=org.phpframework.object.db.DBPrimitive(bigint), max_length=20) 
	 * @param (name=data[name], type=org.phpframework.object.db.DBPrimitive(varchar), not_null=1, add_sql_slashes=1, sanitize_html=1, max_length=50) 
	 */
	public function insert ($data) {
		$options = $data["options"];
		
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ((isset($data["school_id"]) && is_string($data["school_id"])) && is_numeric($data["school_id"])) {
			$data["school_id"] += 0;
		 }
		
		$result = false;
		
		$attributes = $this->filterDataByTableAttributes($data, false);
		
		if ($attributes) {
			$ai_pks = $this->getTableAutoIncrementPrimaryKeys();
		
			$set_ai_pk = null;
		
			foreach ($ai_pks as $pk_name) {
				if ($data[$pk_name]) {
					$set_ai_pk = $pk_name;
		
					break;
				 }
			}
		
			if ($set_ai_pk || empty($ai_pks)) {
				$options["hard_coded_ai_pk"] = true;
		
				$result = $this->getDBBroker()->insertObject($this->getTableName(), $attributes, $options);
		
				if ($result) {
					if ($set_ai_pk) {
						$result = $data[$set_ai_pk];
					 }
					else {$pks = $this->getTablePrimaryKeys();
		
						foreach ($pks as $pk_name) {
							if ($data[$pk_name]) {
								$result = $data[$pk_name];
		
								break;
							 }
						}
					}
				 }
			 }
			else {$attributes = $this->filterDataExcludingTableAutoIncrementPrimaryKeys($attributes);
		
				$result = $this->getDBBroker()->insertObject($this->getTableName(), $attributes, $options);
		
				if ($result) {
					if ($ai_pks) {
						$result = $this->getDBBroker()->getInsertedId($options);
					 }
					else {$result = true;
					}
				 }
				else {$result = false;
				}
			}
		 }
		
		return $result;
	}
	
	/**
	 * @param (name=data[name], type=org.phpframework.object.db.DBPrimitive(varchar), not_null=1, default="", length=50, add_sql_slashes=1, sanitize_html=1)  
	 * @param (name=data[school_id], type=org.phpframework.object.db.DBPrimitive(bigint), not_null=1, length=20)  
	 */
	public function update($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if (isset($data["school_id"]) && is_string($data["school_id"]) && is_numeric($data["school_id"])) $data["school_id"] += 0;
			
		$attributes = $this->filterDataByTableAttributes($data);
		$conditions = $this->filterDataByTablePrimaryKeys($data);
		$result = $this->getDBBroker()->updateObject($this->getTableName(), $attributes, $conditions, $options);
		
		return $result;
	}
	
	/**
	 * @param (name=data[new_school_id], type=org.phpframework.object.db.DBPrimitive(bigint), not_null=1, length=20)  
	 * @param (name=data[old_school_id], type=org.phpframework.object.db.DBPrimitive(bigint), not_null=1, length=20)  
	 */
	public function updatePrimaryKeys($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if (isset($data["new_school_id"]) && is_string($data["new_school_id"]) && is_numeric($data["new_school_id"])) $data["new_school_id"] += 0;
			if (isset($data["old_school_id"]) && is_string($data["old_school_id"]) && is_numeric($data["old_school_id"])) $data["old_school_id"] += 0;
			
		$attributes = $conditions = array();
		$pks = self::getTablePrimaryKeys();
		
		foreach ($pks as $pk) {
			$attributes[$pk] = $data["new_" . $pk];
			$conditions[$pk] = $data["old_" . $pk];
		}
		
		$result = $this->getDBBroker()->updateObject($this->getTableName(), $attributes, $conditions, $options);
		
		return $result;
	}
	
	/**
	 * @param (name=data[name], type=org.phpframework.object.db.DBPrimitive(varchar), not_null=1, default="", length=50, add_sql_slashes=1, sanitize_html=1)
	 * @param (name=data[conditions][school_id], type=array|org.phpframework.object.db.DBPrimitive(bigint), length=20)  
	 * @param (name=data[conditions][name], type=array|org.phpframework.object.db.DBPrimitive(varchar), length=50, add_sql_slashes=1, sanitize_html=1)  
	 */
	public function updateAll($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$attributes = $this->filterDataByTableAttributes($data);
		$conditions = $data["conditions"];
		$options["all"] = $data["all"];
		$result = $this->getDBBroker()->updateObject($this->getTableName(), $attributes, $conditions, $options);
		
		return $result;
	}
	
	/**
	 * @param (name=data[school_id], type=org.phpframework.object.db.DBPrimitive(bigint), not_null=1, length=20)  
	 */
	public function delete($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$conditions = $this->filterDataByTablePrimaryKeys($data);
		$result = $this->getDBBroker()->deleteObject($this->getTableName(), $conditions, $options);
		
		return $result;
	}
	
	/**
	 * @param (name=data[conditions][school_id], type=array|org.phpframework.object.db.DBPrimitive(bigint), length=20)  
	 * @param (name=data[conditions][name], type=array|org.phpframework.object.db.DBPrimitive(varchar), length=50, add_sql_slashes=1, sanitize_html=1)  
	 */
	public function deleteAll($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$conditions = $data["conditions"];
		$options["all"] = $data["all"];
		$result = $this->getDBBroker()->deleteObject($this->getTableName(), $conditions, $options);
		
		return $result;
	}
	
	/**
	 * @param (name=data[school_id], type=org.phpframework.object.db.DBPrimitive(bigint), not_null=1, length=20)  
	 */
	public function get($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		self::prepareInputData($data);
		
		if ($data["searching_condition"])
			$options["sql_conditions"] = "1=1" . $data["searching_condition"];
		
		$conditions = $this->filterDataByTablePrimaryKeys($data);
		$result = $this->getDBBroker()->findObjects($this->getTableName(), null, $conditions, $options);
		$result = $result ? $result[0] : null;
		
		return $result;
	}
	
	
	/**
	 * @param (name=data[conditions][school_id], type=array|org.phpframework.object.db.DBPrimitive(bigint), max_length=20) 
	 * @param (name=data[conditions][name], type=array|org.phpframework.object.db.DBPrimitive(varchar), add_sql_slashes=1, sanitize_html=1, max_length=50) 
	 */
	public function getAll ($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		self::prepareInputData($data);
		
		if ($data["searching_condition"])
			$options["sql_conditions"] = "1=1" . $data["searching_condition"];
		
		$result = $this->getDBBroker()->findObjects($this->getTableName(), null, null, $options);
		
		return $result;
	}
	
	/**
	 * @param (name=data[conditions][school_id], type=array|org.phpframework.object.db.DBPrimitive(bigint), length=20)  
	 * @param (name=data[conditions][name], type=array|org.phpframework.object.db.DBPrimitive(varchar), length=50, add_sql_slashes=1, sanitize_html=1)  
	 */
	public function countAll($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		self::prepareInputData($data);
		
		if ($data["searching_condition"])
			$options["sql_conditions"] = "1=1" . $data["searching_condition"];
		
		$result = $this->getDBBroker()->countObjects($this->getTableName(), null, $options);
		
		return $result;
	}
	
	/**
	 * @param (name=data[school_id], type=org.phpframework.object.db.DBPrimitive(bigint), not_null=1, length=20)  
	 */
	public function getSchoolStudentChilds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		self::prepareInputData($data);
		
		if ($data["searching_condition"])
			$options["sql_conditions"] = "1=1" . $data["searching_condition"];
		
		$keys = array (
		  0 => 
		  array (
		    'ptable' => $this->getTableName(),
		    'pcolumn' => 'school_id',
		    'ftable' => 'student',
		    'fcolumn' => 'school_id',
		    'value' => NULL,
		    'join' => 'INNER',
		    'operator' => '=',
		  ),
		);
		
		$rel_elm = array("keys" => $keys);
		$parent_conditions = $this->filterDataByTablePrimaryKeys($data);
		$result = $this->getDBBroker()->findRelationshipObjects($this->getTableName(), $rel_elm, $parent_conditions, $options);
		
		return $result;
	}
	
	/**
	 * @param (name=data[school_id], type=org.phpframework.object.db.DBPrimitive(bigint), not_null=1, length=20)  
	 */
	public function countSchoolStudentChilds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		self::prepareInputData($data);
		
		if ($data["searching_condition"])
			$options["sql_conditions"] = "1=1" . $data["searching_condition"];
		
		$keys = array (
		  0 => 
		  array (
		    'ptable' => $this->getTableName(),
		    'pcolumn' => 'school_id',
		    'ftable' => 'student',
		    'fcolumn' => 'school_id',
		    'value' => NULL,
		    'join' => 'INNER',
		    'operator' => '=',
		  ),
		);
		
		$rel_elm = array("keys" => $keys);
		$parent_conditions = $this->filterDataByTablePrimaryKeys($data);
		$result = $this->getDBBroker()->countRelationshipObjects($this->getTableName(), $rel_elm, $parent_conditions, $options);
		
		return $result;
	}
	
	/**
	 * @param (name=data[school_id], type=org.phpframework.object.db.DBPrimitive(bigint), not_null=1, length=20)  
	 */
	public function getSchoolTeacherChilds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		self::prepareInputData($data);
		
		if ($data["searching_condition"])
			$options["sql_conditions"] = "1=1" . $data["searching_condition"];
		
		$keys = array (
		  0 => 
		  array (
		    'ptable' => $this->getTableName(),
		    'pcolumn' => 'school_id',
		    'ftable' => 'teacher',
		    'fcolumn' => 'school_id',
		    'value' => NULL,
		    'join' => 'INNER',
		    'operator' => '=',
		  ),
		);
		
		$rel_elm = array("keys" => $keys);
		$parent_conditions = $this->filterDataByTablePrimaryKeys($data);
		$result = $this->getDBBroker()->findRelationshipObjects($this->getTableName(), $rel_elm, $parent_conditions, $options);
		
		return $result;
	}
	
	/**
	 * @param (name=data[school_id], type=org.phpframework.object.db.DBPrimitive(bigint), not_null=1, length=20)  
	 */
	public function countSchoolTeacherChilds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		self::prepareInputData($data);
		
		if ($data["searching_condition"])
			$options["sql_conditions"] = "1=1" . $data["searching_condition"];
		
		$keys = array (
		  0 => 
		  array (
		    'ptable' => $this->getTableName(),
		    'pcolumn' => 'school_id',
		    'ftable' => 'teacher',
		    'fcolumn' => 'school_id',
		    'value' => NULL,
		    'join' => 'INNER',
		    'operator' => '=',
		  ),
		);
		
		$rel_elm = array("keys" => $keys);
		$parent_conditions = $this->filterDataByTablePrimaryKeys($data);
		$result = $this->getDBBroker()->countRelationshipObjects($this->getTableName(), $rel_elm, $parent_conditions, $options);
		
		return $result;
	}
	
}
?>