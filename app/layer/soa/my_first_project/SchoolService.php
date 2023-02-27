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
		return array("school_id", "name");
	}
	
	private function getTablePrimaryKeys() {
		return array("school_id");
	}
	
	private function getTableAutoIncrementPrimaryKeys() {
		return array("school_id");
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
		
		$attributes = $this->filterDataByTableAttributes($data, false);
		$ai_pks = $this->getTableAutoIncrementPrimaryKeys();
		$set_ai_pk = null;
		
		//This code supposes that there is only 1 auto increment pk
		foreach ($ai_pks as $pk_name) 
			if ($data[$pk_name]) {
				$set_ai_pk = $pk_name;
				break;
			}
		
		if ($set_ai_pk) {
			$options["hard_coded_ai_pk"] = true;
			$result = $this->getDBBroker()->insertObject($this->getTableName(), $attributes, $options);
			$result = $result ? $data[$set_ai_pk] : false;
		}
		else {
			$attributes = $this->filterDataExcludingTableAutoIncrementPrimaryKeys($attributes);
			$result = $this->getDBBroker()->insertObject($this->getTableName(), $attributes, $options);
			$result = $result ? $this->getDBBroker()->getInsertedId($options) : false;
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
	 * @param (name=data[conditions][name], type=org.phpframework.object.db.DBPrimitive(varchar), length=50, add_sql_slashes=1, sanitize_html=1)  
	 */
	public function getAll($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		self::prepareInputData($data);
		
		if ($data["searching_condition"])
			$options["sql_conditions"] = "1=1" . $data["searching_condition"];
		
		$result = $this->getDBBroker()->findObjects($this->getTableName(), null, null, $options);
		
		return $result;
	}
	
	/**
	 * @param (name=data[conditions][name], type=org.phpframework.object.db.DBPrimitive(varchar), length=50, add_sql_slashes=1, sanitize_html=1)  
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