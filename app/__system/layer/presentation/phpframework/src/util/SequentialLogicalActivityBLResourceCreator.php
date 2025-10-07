<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.phpscript.PHPCodePrintingHandler"); include_once $EVC->getUtilPath("WorkFlowDataAccessHandler"); include_once $EVC->getUtilPath("CMSPresentationFormSettingsUIHandler"); class SequentialLogicalActivityBLResourceCreator { private $pf3dc0762; private $v1335217393; private $pac4bc40a; private $v9d7547e4d6; private $pe13b2783; public function __construct($pf3dc0762, $v1335217393, $pac4bc40a, $v9d7547e4d6) { $this->pf3dc0762 = $pf3dc0762; $this->v1335217393 = $v1335217393; $this->v9d7547e4d6 = $v9d7547e4d6; $this->pac4bc40a = $pac4bc40a; $this->pe13b2783 = WorkFlowDBHandler::getTableFromTables($pac4bc40a, $v9d7547e4d6); } public function createBLResourceServiceFile($v47cef7ac50, $v8ab32450b0, &$pef612b9d = null) { $v7e5f67574c = file_exists($this->pf3dc0762) && PHPCodePrintingHandler::getClassFromFile($this->pf3dc0762, $this->v1335217393); if (!$v7e5f67574c) { $pf232dd5a = PHPCodePrintingHandler::getClassFromFile($v47cef7ac50, $v8ab32450b0); if ($pf232dd5a) { $pf232dd5a["includes"] = PHPCodePrintingHandler::getIncludesFromFile($v47cef7ac50); $v7e5f67574c = PHPCodePrintingHandler::addClassToFile($this->pf3dc0762, array( "name" => $this->v1335217393, "extends" => isset($pf232dd5a["extends"]) ? $pf232dd5a["extends"] : null, "includes" => isset($pf232dd5a["includes"]) ? $pf232dd5a["includes"] : null )); } } return $v7e5f67574c; } public function createInsertMethod($pcd8c70bc, $v547b6994da, &$pef612b9d = null) { if ($this->pe13b2783) { $v325ffa1d87 = array_keys($this->pe13b2783); $pa7c11132 = self::getInsertActionPreviousCode($this->pac4bc40a, $this->v9d7547e4d6, $v325ffa1d87, '$attributes'); $pa7c11132 = str_replace("\n", "\n\t", $pa7c11132); $v067674f4e4 = '$options = isset($data["options"]) ? $data["options"] : null;
$this->mergeOptionsWithBusinessLogicLayer($options);

$attributes = isset($data["attributes"]) ? $data["attributes"] : null;

if ($attributes) {
	' . $pa7c11132 . '
	
	$result = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $v547b6994da . '", $attributes, $options);
	
	return $result;
}'; $pd85a728f = "Insert parsed resource data into table: " . $this->v9d7547e4d6 . "."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "insert", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } } public function createUpdateMethod($pcd8c70bc, $v5bfa31fb0f, $v28aa060c7b, $v64cc85efd7, &$pef612b9d = null) { if ($this->pe13b2783) { $v325ffa1d87 = array_keys($this->pe13b2783); $pd253e918 = array(); foreach ($this->pe13b2783 as $v5e45ec9bb9 => $v1b0cfa478b) if (!empty($v1b0cfa478b["primary_key"])) $pd253e918[] = $v5e45ec9bb9; $v6bb8b59263 = empty($pd253e918); $pa7c11132 = self::getUpdateActionPreviousCode($this->pac4bc40a, $this->v9d7547e4d6, $v325ffa1d87, '$attributes'); $pa7c11132 = str_replace("\n", "\n\t", $pa7c11132); $v0d13f6b70b = self::getUpdateActionPreviousCode($this->pac4bc40a, $this->v9d7547e4d6, $pd253e918, '$pks'); $v0d13f6b70b = str_replace("\n", "\n\t", $v0d13f6b70b); $v067674f4e4 = '$options = isset($data["options"]) ? $data["options"] : null;
$this->mergeOptionsWithBusinessLogicLayer($options);

$attributes = isset($data["attributes"]) ? $data["attributes"] : null;
$pks = isset($data["pks"]) ? $data["pks"] : null;

'; if ($v6bb8b59263) { $v067674f4e4 .= 'if ($attributes && $pks) {
	$status = true;

	' . $pa7c11132 . '
	' . $v0d13f6b70b . '

	$filtered_attributes = $pks;

	if ($status) {
		//get the record from DB bc the $attributes may only have a few attributes, so we need to populate the other ones in order to call the broker->update method.
		'; if ($v5bfa31fb0f) $v067674f4e4 .= '$data = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $v5bfa31fb0f . '", $filtered_attributes, $options);'; else $v067674f4e4 .= '//because there is no get service, we join the attributes and pks variables.
				$data = array_merge($attributes, $pks);
				//$data = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "REPLACE WITH THE CORRECT SERVICE THAT GETS THE ITEM DATA", $filtered_attributes, $options);'; $v067674f4e4 .= '
		
		if (!$data || !is_array($data))
			return false;
		
		foreach ($attributes as $attr_name => $attr_value)
			$data[$attr_name] = $attr_value;
		
		foreach ($pks as $pk_name => $pk_value) {
			$data["old_" . $pk_name] = $pk_value;
			$data["new_" . $pk_name] = $attributes[$pk_name];
			unset($data[$pk_name]);
		}
		
		$status = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $v28aa060c7b . '", $data, $options);
	}

	return $status;
}'; } else { $v263f49bc22 = ''; foreach ($this->pe13b2783 as $v5e45ec9bb9 => $v1b0cfa478b) if (empty($v1b0cfa478b["primary_key"]) && isset($v1b0cfa478b["type"]) && ObjTypeHandler::isDBTypeBlob($v1b0cfa478b["type"])) $v263f49bc22 .= '
			if (isset($filtered_attributes["' . $v5e45ec9bb9 . '"]) && isset($data["' . $v5e45ec9bb9 . '"]) && empty($_FILES["' . $v5e45ec9bb9 . '"]["tmp_name"])) $filtered_attributes["' . $v5e45ec9bb9 . '"] = $data["' . $v5e45ec9bb9 . '"];'; $v067674f4e4 .= 'if ($attributes && $pks) {
	$status = true;

	' . $pa7c11132 . '
	' . $v0d13f6b70b . '

	if ($status) {
		//get new pks from $attributes and get $attributes without pks
		$update_pks = false;
		$filtered_pks = array();
		$filtered_attributes = array();
		
		foreach ($attributes as $attribute_name => $attribute_value) {
			if (array_key_exists($attribute_name, $pks)) {
				$filtered_pks["new_" . $attribute_name] = $attribute_value;
				
				if ($attribute_value != $pks[$attribute_name])
					$update_pks = true;
			}
			else
				$filtered_attributes[$attribute_name] = $attribute_value;
		}
		
		$status = $update_pks || $filtered_attributes;
		
		if ($status) {
			foreach ($pks as $pk_name => $pk_value) {
				if ($update_pks)
					$filtered_pks["old_" . $pk_name] = $pk_value;
				
				if ($filtered_attributes)
					$filtered_attributes[$pk_name] = $pk_value;
			}
			
			if ($filtered_attributes) {
				//get the record from DB bc the $attributes may only have a few attributes, so we need to populate the other ones in order to call the broker->update method.
				'; if ($v5bfa31fb0f) $v067674f4e4 .= '$data = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $v5bfa31fb0f . '", $filtered_attributes, $options);'; else $v067674f4e4 .= '//because there is no get service, we join the attributes and pks variables.
				$data = array_merge($attributes, $pks);
				//$data = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "REPLACE WITH THE CORRECT SERVICE THAT GETS THE ITEM DATA", $filtered_attributes, $options);'; $v067674f4e4 .= '
				
				if (!$data || !is_array($data))
					return false;
				' . $v263f49bc22 . '
				
				foreach ($filtered_attributes as $attr_name => $attr_value)
					$data[$attr_name] = $attr_value;
				
				$status = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $v28aa060c7b . '", $data, $options);
			}
			
			if ($status && $update_pks) {
				'; if ($v64cc85efd7) $v067674f4e4 .= '$status = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $v64cc85efd7 . '", $filtered_pks, $options);'; else $v067674f4e4 .= '//$status = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "REPLACE WITH THE CORRECT SERVICE THAT UPDATES PKS", $filtered_pks, $options);'; $v067674f4e4 .= '
			}
		}
	}

	return $status;
}'; } $pd85a728f = "Update data into table: " . $this->v9d7547e4d6 . "."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "update", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } } public function createUpdateAttributeMethod($pcd8c70bc, $v5bfa31fb0f, $v28aa060c7b, &$pef612b9d = null) { if ($this->pe13b2783) { $v325ffa1d87 = array_keys($this->pe13b2783); $pd253e918 = array(); foreach ($this->pe13b2783 as $v5e45ec9bb9 => $v1b0cfa478b) if (!empty($v1b0cfa478b["primary_key"])) $pd253e918[] = $v5e45ec9bb9; $v6bb8b59263 = empty($pd253e918); $pa7c11132 = self::getUpdateActionPreviousCode($this->pac4bc40a, $this->v9d7547e4d6, $v325ffa1d87, '$attributes', false); $pa7c11132 = str_replace("\n", "\n\t", $pa7c11132); $v0d13f6b70b = self::getUpdateActionPreviousCode($this->pac4bc40a, $this->v9d7547e4d6, $pd253e918, '$pks', false); $v0d13f6b70b = str_replace("\n", "\n\t", $v0d13f6b70b); $v263f49bc22 = ''; foreach ($this->pe13b2783 as $v5e45ec9bb9 => $v1b0cfa478b) if (empty($v1b0cfa478b["primary_key"]) && isset($v1b0cfa478b["type"]) && ObjTypeHandler::isDBTypeBlob($v1b0cfa478b["type"])) $v263f49bc22 .= '
	if (isset($attributes["' . $v5e45ec9bb9 . '"]) && isset($data["' . $v5e45ec9bb9 . '"]) && empty($_FILES["' . $v5e45ec9bb9 . '"]["tmp_name"])) $attributes["' . $v5e45ec9bb9 . '"] = $data["' . $v5e45ec9bb9 . '"];'; $v067674f4e4 = '$options = isset($data["options"]) ? $data["options"] : null;
$this->mergeOptionsWithBusinessLogicLayer($options);

$attributes = isset($data["attributes"]) ? $data["attributes"] : null;
$pks = isset($data["pks"]) ? $data["pks"] : null;

if ($attributes && $pks) {
	$status = true;
	
	' . $pa7c11132 . '
	' . $v0d13f6b70b . '
	
	if ($status) {
		$data = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $v5bfa31fb0f . '", $pks, $options);
		
		if (!$data || !is_array($data))
			return false;
		' . $v263f49bc22 . '
		
		foreach ($attributes as $attribute_name => $attribute_value)
			$data[$attribute_name] = $attribute_value;
	
		if ($status) {
			'; if ($v6bb8b59263) $v067674f4e4 .= 'foreach ($pks as $pk_name => $pk_value) {
				$data["old_" . $pk_name] = $pk_value;
				$data["new_" . $pk_name] = isset($attributes[$pk_name]) ? $attributes[$pk_name] : null;
				unset($data[$pk_name]);
			}
			
			'; $v067674f4e4 .= '$status = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $v28aa060c7b . '", $data, $options);
		}
	}
	
	return $status;
}'; $pd85a728f = "Update an attribute from table: " . $this->v9d7547e4d6 . "."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "updateAttribute", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } } public function createMultipleSaveMethod($pcd8c70bc, $v547b6994da, $v28aa060c7b, &$pef612b9d = null) { $v55dffd6ac4 = PHPCodePrintingHandler::getFunctionFromFile($this->pf3dc0762, "insert", $this->v1335217393); $pb2504a38 = PHPCodePrintingHandler::getFunctionFromFile($this->pf3dc0762, "update", $this->v1335217393); if (!$v55dffd6ac4) $v55dffd6ac4 = self::createInsertMethod($pcd8c70bc, $v547b6994da, $pef612b9d); if (!$pb2504a38) $pb2504a38 = self::createUpdateMethod($pcd8c70bc, $v28aa060c7b, $pef612b9d); if (!$v55dffd6ac4) $pef612b9d = "Error: Couldn't find any resource service for insert action."; else if (!$pb2504a38) $pef612b9d = "Error: Couldn't find any resource service for update action."; else { $v067674f4e4 = '$status = true;

$pks = isset($data["pks"]) ? $data["pks"] : null;
$attributes = isset($data["attributes"]) ? $data["attributes"] : null;

if ($attributes)
	for ($i = 0, $t = count($attributes); $i < $t; $i++) {
		$data["attributes"] = $attributes[$i];
		$data["pks"] = isset($pks[$i]) ? $pks[$i] : null;
		$is_insert = empty($pks[$i]);
		
		if ($is_insert && !$this->insert($data))
			$status = false;
		else if (!$is_insert && !$this->update($data))
			$status = false;
	}

return $status;'; $pd85a728f = "Update multiple records at once parsed resource record into table: " . $this->v9d7547e4d6 . "."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "multipleSave", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } } public function createInsertUpdateAttributeMethod($pcd8c70bc, $v5bfa31fb0f, $v547b6994da, $v3f465296e2, &$pef612b9d = null) { $v7d868b087a = PHPCodePrintingHandler::getFunctionFromFile($this->pf3dc0762, "get", $this->v1335217393); $v55dffd6ac4 = PHPCodePrintingHandler::getFunctionFromFile($this->pf3dc0762, "insert", $this->v1335217393); $v0c0dd50ae2 = PHPCodePrintingHandler::getFunctionFromFile($this->pf3dc0762, "updateAttribute", $this->v1335217393); if (!$v7d868b087a) $v7d868b087a = self::createGetMethod($pcd8c70bc, $v5bfa31fb0f, $pef612b9d); if (!$v55dffd6ac4) $v55dffd6ac4 = self::createInsertMethod($pcd8c70bc, $v547b6994da, $pef612b9d); if (!$v0c0dd50ae2) $v0c0dd50ae2 = self::createUpdateAttributeMethod($pcd8c70bc, $v3f465296e2, $pef612b9d); if (!$v7d868b087a) $pef612b9d = "Error: Couldn't find any resource service for get action."; else if (!$v55dffd6ac4) $pef612b9d = "Error: Couldn't find any resource service for insert action."; else if (!$v0c0dd50ae2) $pef612b9d = "Error: Couldn't find any resource service for update_attribute action."; else { $v067674f4e4 = '$item_data = $this->get($data);

if (!empty($item_data))
	return $this->updateAttribute($data);

if (isset($data["pks"]) && is_array($data["pks"]))
	$data["attributes"] = isset($data["attributes"]) && is_array($data["attributes"]) ? array_merge($data["attributes"], $data["pks"]) : $data["pks"];

return $this->insert($data);'; $pd85a728f = "Insert or update an attribute from table: " . $this->v9d7547e4d6 . "."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "insertUpdateAttribute", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } } public function createInsertDeleteAttributeMethod($pcd8c70bc, $v5bfa31fb0f, $v547b6994da, $pb4309add, &$pef612b9d = null) { $v7d868b087a = PHPCodePrintingHandler::getFunctionFromFile($this->pf3dc0762, "get", $this->v1335217393); $v55dffd6ac4 = PHPCodePrintingHandler::getFunctionFromFile($this->pf3dc0762, "insert", $this->v1335217393); $v213792fb2e = PHPCodePrintingHandler::getFunctionFromFile($this->pf3dc0762, "delete", $this->v1335217393); if (!$v7d868b087a) $v7d868b087a = self::createGetMethod($pcd8c70bc, $v5bfa31fb0f, $pef612b9d); if (!$v55dffd6ac4) $v55dffd6ac4 = self::createInsertMethod($pcd8c70bc, $v547b6994da, $pef612b9d); if (!$v213792fb2e) $v213792fb2e = self::createUpdateAttributeMethod($pcd8c70bc, $pb4309add, $pef612b9d); if (!$v7d868b087a) $pef612b9d = "Error: Couldn't find any resource service for get action."; else if (!$v55dffd6ac4) $pef612b9d = "Error: Couldn't find any resource service for insert action."; else if (!$v213792fb2e) $pef612b9d = "Error: Couldn't find any resource service for delete action."; else { $v067674f4e4 = '$exists = false;

//note that the $data["attributes"] should only have 1 attribute name based in the html element that this action was called.
if (isset($data["attributes"]) && is_array($data["attributes"]))
	foreach ($data["attributes"] as $attr_name => $attr_value)
		if ($attr_value) {
			$exists = true;
			break;
		}
	
$item_data = $this->get($data);

if (!empty($item_data))
	return $exists || $this->delete($data);

if (isset($data["pks"]) && is_array($data["pks"]))
	$data["attributes"] = isset($data["attributes"]) && is_array($data["attributes"]) ? array_merge($data["attributes"], $data["pks"]) : $data["pks"];

return !$exists || $this->insert($data);'; $pd85a728f = "Insert or delete a record based if a value from an attribute, from table: " . $this->v9d7547e4d6 . ", exists or not."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "insertDeleteAttribute", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } } public function createDeleteMethod($pcd8c70bc, $pb4309add, &$pef612b9d = null) { if ($this->pe13b2783) { $pd253e918 = array(); foreach ($this->pe13b2783 as $v5e45ec9bb9 => $v1b0cfa478b) if (!empty($v1b0cfa478b["primary_key"])) $pd253e918[] = $v5e45ec9bb9; $v0d13f6b70b = self::getUpdateActionPreviousCode($this->pac4bc40a, $this->v9d7547e4d6, $pd253e918, '$pks', false); $v0d13f6b70b = str_replace("\n", "\n\t", $v0d13f6b70b); $v067674f4e4 = '$options = isset($data["options"]) ? $data["options"] : null;
$this->mergeOptionsWithBusinessLogicLayer($options);

$status = false;
$pks = isset($data["pks"]) ? $data["pks"] : null;

if ($pks) {
	$status = true;
	
	' . $v0d13f6b70b . '
	
	if ($status)
		$status = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $pb4309add . '", $pks, $options);
}

return $status;'; $pd85a728f = "Delete record from table: " . $this->v9d7547e4d6 . "."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "delete", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } } public function createMultipleDeleteMethod($pcd8c70bc, $pb4309add, &$pef612b9d = null) { $v213792fb2e = PHPCodePrintingHandler::getFunctionFromFile($this->pf3dc0762, "delete", $this->v1335217393); if (!$v213792fb2e) $v213792fb2e = self::createDeleteMethod($pcd8c70bc, $pb4309add, $pef612b9d); if (!$v213792fb2e) $pef612b9d = "Error: Couldn't find any resource service for delete action."; else { $v067674f4e4 = '$status = true;
$pks = isset($data["pks"]) ? $data["pks"] : null;

if ($pks)
for ($i = 0, $t = count($pks); $i < $t; $i++) {
	$data["pks"] = $pks[$i];
	
	if (!$this->delete($data))
		$status = false;
}

return $status;'; $pd85a728f = "Delete multiple records at once parsed resource record from table: " . $this->v9d7547e4d6 . "."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "multipleDelete", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } } public function createMultipleInsertDeleteAttributeMethod($pcd8c70bc, $v04b91bb6cb, $v547b6994da = null, &$pef612b9d = null) { $v55dffd6ac4 = PHPCodePrintingHandler::getFunctionFromFile($this->pf3dc0762, "insert", $this->v1335217393); if (!$v55dffd6ac4 && $v547b6994da) $v55dffd6ac4 = self::createInsertMethod($pcd8c70bc, $v547b6994da, $pef612b9d); if (!$v55dffd6ac4) $pef612b9d = "Error: Couldn't find any resource service for insert action."; else { $v067674f4e4 = '$options = isset($data["options"]) ? $data["options"] : null;
$this->mergeOptionsWithBusinessLogicLayer($options);

$attributes = isset($data["attributes"]) ? $data["attributes"] : null;
$pks = isset($data["pks"]) ? $data["pks"] : null;

$delete_data = array(
	"conditions" => $pks
);
$this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $v04b91bb6cb . '", $delete_data, $options);
//print_r($delete_data);die();

$items = array();

//note that the $attributes should only have 1 attribute name based in the html element that this action was called.
if (is_array($attributes))
	foreach ($attributes as $attr_name => $attr_value)
		if ($attr_value) {
			$item = $pks;
			
			if (is_array($attr_value)) {
				for ($i = 0, $t = count($attr_value); $i < $t; $i++) 
				    if ($attr_value[$i] || is_numeric($attr_value[$i])) {
						$item[$attr_name] = $attr_value[$i];
						$items[] = $item;
					}
			}
			else if ($attr_value || is_numeric($attr_value)) {
				$item[$attr_name] = $attr_value;
				$items[] = $item;
			}
		}
//print_r($items);var_dump($items);die();

$status = true;

if (!empty($items))
	for ($i = 0, $t = count($items); $i < $t; $i++)
		if (!$this->insert(array("attributes" => $items[$i], "options" => $options)))
			$status = false;

return $status;'; $pd85a728f = "Delete all records and insert new ones, based in an attribute, from table: " . $this->v9d7547e4d6 . ", exists or not."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "multipleInsertDeleteAttribute", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } } public function createGetMethod($pcd8c70bc, $v5bfa31fb0f, &$pef612b9d = null) { $v067674f4e4 = '$options = isset($data["options"]) ? $data["options"] : null;
$this->mergeOptionsWithBusinessLogicLayer($options);

$result = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $v5bfa31fb0f . '", $data["pks"], $options);

' . self::getSelectItemActionNextCode($this->pe13b2783, '$result') . '

return $result;'; $pd85a728f = "Get a parsed resource record from table: " . $this->v9d7547e4d6 . "."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "get", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } public function createGetAllMethod($pcd8c70bc, $pe5d3d60f, &$pef612b9d = null) { $v067674f4e4 = self::getCodeForGetAllOrCountOrGetAllOptionsMethod($pcd8c70bc, $pe5d3d60f); $v067674f4e4 .= '
' . self::getSelectItemsActionNextCode($this->pe13b2783, '$result'); $v067674f4e4 .= '
return $result;'; $pd85a728f = "Get records from table: " . $this->v9d7547e4d6 . "."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "getAll", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } public function createCountMethod($pcd8c70bc, $v75e0c46976, &$pef612b9d = null) { $v067674f4e4 = self::getCodeForGetAllOrCountOrGetAllOptionsMethod($pcd8c70bc, $v75e0c46976); $v067674f4e4 .= '
return $result;'; $pd85a728f = "Count records from table: " . $this->v9d7547e4d6 . "."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "count", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } public function createGetAllOptionsMethod($pcd8c70bc, $pe5d3d60f, &$pef612b9d = null) { $v9352c56342 = PHPCodePrintingHandler::getFunctionFromFile($this->pf3dc0762, "getAll", $this->v1335217393); if (!$v9352c56342) $v9352c56342 = self::createGetAllMethod($pcd8c70bc, $pe5d3d60f, $pef612b9d); if (!$v9352c56342) $pef612b9d = "Error: Couldn't find any resource service for get_all action."; else { $pfa973d42 = self::getTableOptionsSettings($this->v9d7547e4d6, $this->pac4bc40a); if (!$pfa973d42) $pef612b9d = "Error: No primary attribute or no valid attributes when trying to create the getAllOptions method."; else { $v9994512d98 = isset($pfa973d42["keys"]) ? $pfa973d42["keys"] : null; $v76b0aa2076 = isset($pfa973d42["values"]) ? $pfa973d42["values"] : null; $v067674f4e4 = '$result = $this->getAll($data);

$options = array();

if ($result) 
	for ($i = 0, $t = count($result); $i < $t; $i++) {
		$item = $result[$i];
		$key = '; for ($v43dd7d0051 = 0, $pc37695cb = count($v9994512d98); $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v067674f4e4 .= ($v43dd7d0051 > 0 ? ' . "_" . ' : "") . '$item["' . $v9994512d98[$v43dd7d0051] . '"]'; $v067674f4e4 .= ';
		$value = '; for ($v43dd7d0051 = 0, $pc37695cb = count($v76b0aa2076); $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v067674f4e4 .= ($v43dd7d0051 > 0 ? ' . "_" . ' : "") . '$item["' . $v76b0aa2076[$v43dd7d0051] . '"]'; $v067674f4e4 .= ';
		$options[$key] = $value;
	}

return $options;'; $pd85a728f = "Get key-value pair list from table: " . $this->v9d7547e4d6 . ", where the key is the table primary key and the value is the table attribute label."; return self::f78c42374a8($this->pf3dc0762, array( "name" => "getAllOptions", "arguments" => array("data" => null), "code" => $v067674f4e4, "comments" => $pd85a728f ), $this->v1335217393); } } } public static function getSelectItemActionNextCode($ped0a6251, $v99242dea8e, $v8fb3d02187 = null) { $v067674f4e4 = ""; $v99242dea8e = substr($v99242dea8e, 0, 1) == '$' || substr($v99242dea8e, 0, 2) == '@$' ? $v99242dea8e : '$' . $v99242dea8e; if ($ped0a6251) foreach ($ped0a6251 as $v5e45ec9bb9 => $v1b0cfa478b) if (!is_array($v8fb3d02187) || in_array($v5e45ec9bb9, $v8fb3d02187)) if (isset($v1b0cfa478b["type"]) && ObjTypeHandler::isDBTypeDate($v1b0cfa478b["type"])) $v067674f4e4 .= 'if (' . $v99242dea8e . '["' . $v5e45ec9bb9 . '"] == "0000-00-00 00:00:00" || ' . $v99242dea8e . '["' . $v5e45ec9bb9 . '"] == "0000-00-00") ' . $v99242dea8e . '["' . $v5e45ec9bb9 . '"] = "";' . "\n"; return $v067674f4e4; } public static function getSelectItemsActionNextCode($ped0a6251, $v99242dea8e) { $v0f707090f0 = array(); $v99242dea8e = substr($v99242dea8e, 0, 1) == '$' || substr($v99242dea8e, 0, 2) == '@$' ? $v99242dea8e : '$' . $v99242dea8e; if ($ped0a6251) foreach ($ped0a6251 as $v5e45ec9bb9 => $v1b0cfa478b) if (isset($v1b0cfa478b["type"]) && ObjTypeHandler::isDBTypeDate($v1b0cfa478b["type"])) $v0f707090f0[] = $v5e45ec9bb9; if ($v0f707090f0) { $v067674f4e4 = 'if (is_array(' . $v99242dea8e . '))
	foreach (' . $v99242dea8e . ' as $k => &$v) {' . "\n"; foreach ($v0f707090f0 as $v5e45ec9bb9) $v067674f4e4 .= "\t\t" . 'if (isset($v["' . $v5e45ec9bb9 . '"]) && ($v["' . $v5e45ec9bb9 . '"] == "0000-00-00 00:00:00" || $v["' . $v5e45ec9bb9 . '"] == "0000-00-00")) $v["' . $v5e45ec9bb9 . '"] = "";' . "\n"; $v067674f4e4 .= "\t}\n"; return $v067674f4e4; } return null; } public static function getCodeForGetAllOrCountOrGetAllOptionsMethod($pcd8c70bc, $v20b8676a9f) { $v067674f4e4 = '$options = isset($data["options"]) ? $data["options"] : null;
$this->mergeOptionsWithBusinessLogicLayer($options);

$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
$conditions_type = isset($data["conditions_type"]) ? $data["conditions_type"] : null;
$conditions_case = isset($data["conditions_case"]) ? $data["conditions_case"] : null;
$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;

'; $v067674f4e4 .= self::getDefaultConditionsCode(); $v067674f4e4 .= '
$data = array(
	"conditions" => $conditions,
	"conditions_join" => $conditions_join
);

$result = $this->getBusinessLogicLayer()->callBusinessLogic("' . $pcd8c70bc . '", "' . $v20b8676a9f . '", $data, $options);
'; return $v067674f4e4; } public static function getDefaultConditionsCode() { $v067674f4e4 = '//prepare $conditions based in $conditions_type: starts_with or ends_with
if ($conditions)
	foreach ($conditions as $attribute_name => $attribute_value) {
		$attribute_condition_type = is_array($conditions_type) ? (isset($conditions_type[$attribute_name]) ? $conditions_type[$attribute_name] : null) : $conditions_type;
		$attribute_operator = $attribute_condition_type == "starts_with" || $attribute_condition_type == "ends_with" || $attribute_condition_type == "contains" ? "like" : $attribute_condition_type;
		$attribute_case = is_array($conditions_case) ? (isset($conditions_case[$attribute_name]) ? $conditions_case[$attribute_name] : null) : $conditions_case;
		$attribute_join = is_array($conditions_join) ? (isset($conditions_join[$attribute_name]) ? $conditions_join[$attribute_name] : null) : $conditions_join;
		
		if ($attribute_operator && $attribute_operator != "=" && $attribute_operator != "equal") {
			if (is_array($attribute_value) && $attribute_operator != "in" && $attribute_operator != "not in") {
				$conditions[$attribute_name] = array();
				
				foreach ($attribute_value as $v)
					$conditions[$attribute_name][] = array(
						"operator" => $attribute_operator,
						"value" => ($attribute_condition_type == "starts_with" || $attribute_condition_type == "contains" ? "%" : "") . ($attribute_case == "insensitive" ? strtolower($v) : $v) . ($attribute_condition_type == "ends_with" || $attribute_condition_type == "contains" ? "%" : ""),
					);
			}
			else {
				if (($attribute_operator == "in" || $attribute_operator == "not in") && $attribute_case == "insensitive" && is_array($attribute_value))
					foreach ($attribute_value as $k => $v)
						if (is_string($v))
							$attribute_value[$k] = strtolower($v);
				
	    			$conditions[$attribute_name] = array(
					"operator" => $attribute_operator,
					"value" => $attribute_operator == "in" || $attribute_operator == "not in" ? $attribute_value : (
						($attribute_condition_type == "starts_with" || $attribute_condition_type == "contains" ? "%" : "") . ($attribute_case == "insensitive" ? strtolower($attribute_value) : $attribute_value) . ($attribute_condition_type == "ends_with" || $attribute_condition_type == "contains" ? "%" : "")
					),
				);
			}
			
			if ($attribute_case == "insensitive") {
				$conditions["lower($attribute_name)"] = $conditions[$attribute_name];
				unset($conditions[$attribute_name]);
				$attribute_name = "lower($attribute_name)";
			}
		}
		
		if (strtolower($attribute_join) == "or") {
			$conditions[$attribute_join][$attribute_name] = $conditions[$attribute_name];
			unset($conditions[$attribute_name]);
	    	}
	}
	
$conditions_join = "and";
'; return $v067674f4e4; } public static function getTableOptionsSettings($v9d7547e4d6, $pac4bc40a, $v78108bc88f = null) { if (!is_array($v78108bc88f)) $v78108bc88f = array(array("table" => $v9d7547e4d6)); else if (array_key_exists("table", $v78108bc88f)) $v78108bc88f = array($v78108bc88f); $v1239d19a31 = WorkFlowDataAccessHandler::getTableAttributeFKTable($v78108bc88f, $pac4bc40a); $pf71bb87b = isset($v1239d19a31["table"]) ? $v1239d19a31["table"] : null; $v50bb61a728 = isset($v1239d19a31["attribute"]) ? $v1239d19a31["attribute"] : null; $v65f81ba9af = $pf71bb87b ? WorkFlowDBHandler::getTableFromTables($pac4bc40a, $pf71bb87b) : null; if (!$v50bb61a728 && $v65f81ba9af) { $v50bb61a728 = array(); $v6bb8b59263 = true; $v9948ea8876 = null; foreach ($v65f81ba9af as $v5e45ec9bb9 => $v1b0cfa478b) { if (!empty($v1b0cfa478b["primary_key"])) { $v50bb61a728[] = $v5e45ec9bb9; $v6bb8b59263 = false; } if (!$v9948ea8876) $v9948ea8876 = $v5e45ec9bb9; } if ($v6bb8b59263 && !$v50bb61a728) { $v83030c1a8d = WorkFlowDataAccessHandler::getTableAttrTitle($v65f81ba9af, $pf71bb87b); $v50bb61a728[] = $v83030c1a8d ? $v83030c1a8d : $v9948ea8876; } } if ($v50bb61a728) { $v83030c1a8d = $v65f81ba9af && is_array($v78108bc88f) && array_key_exists("attribute", $v78108bc88f) && is_string($v78108bc88f["attribute"]) && !empty($v65f81ba9af[ $v78108bc88f["attribute"] ]) ? $v65f81ba9af[ $v78108bc88f["attribute"] ] : null; $v83030c1a8d = !$v83030c1a8d && $v65f81ba9af ? WorkFlowDataAccessHandler::getTableAttrTitle($v65f81ba9af, $pf71bb87b) : null; $v83030c1a8d = $v83030c1a8d ? $v83030c1a8d : $v50bb61a728; $v9994512d98 = is_array($v50bb61a728) ? $v50bb61a728 : array($v50bb61a728); $v76b0aa2076 = is_array($v83030c1a8d) ? $v83030c1a8d : array($v83030c1a8d); return array( "keys" => $v9994512d98, "values" => $v76b0aa2076, ); } return null; } public static function getInsertActionPreviousCode($pac4bc40a, $v8c5df8072b, $pfdbbc383, $v99242dea8e, $v29a1267fee = true, $v085645662c = false) { $v067674f4e4 = ""; $v99242dea8e = substr($v99242dea8e, 0, 1) == '$' || substr($v99242dea8e, 0, 2) == '@$' ? $v99242dea8e : '$' . $v99242dea8e; $ped0a6251 = WorkFlowDBHandler::getTableFromTables($pac4bc40a, $v8c5df8072b); $pb0f83f27 = null; foreach ($pfdbbc383 as $v5e45ec9bb9) { $v1b0cfa478b = isset($ped0a6251[$v5e45ec9bb9]) ? $ped0a6251[$v5e45ec9bb9] : null; $paf45a3a1 = ObjTypeHandler::isDBAttributeNameACreatedDate($v5e45ec9bb9) || ObjTypeHandler::isDBAttributeNameACreatedUserId($v5e45ec9bb9); if ($v085645662c && empty($v1b0cfa478b["primary_key"]) && $paf45a3a1) continue; $v3fb9f41470 = isset($v1b0cfa478b["type"]) ? $v1b0cfa478b["type"] : null; $v9abfb1e31c = !isset($v1b0cfa478b["null"]) || $v1b0cfa478b["null"]; $v23ed8083a0 = ObjTypeHandler::isDBTypeNumeric($v3fb9f41470) || ObjTypeHandler::isPHPTypeNumeric($v3fb9f41470); $v7b5b136445 = ObjTypeHandler::isDBTypeBlob($v3fb9f41470); $pf17ebc72 = (ObjTypeHandler::isDBAttributeNameACreatedUserId($v5e45ec9bb9) || ObjTypeHandler::isDBAttributeNameAModifiedUserId($v5e45ec9bb9)) && $v23ed8083a0; $v1a025758bb = 'array_key_exists("' . $v5e45ec9bb9 . '", ' . $v99242dea8e . ') && '; $v5a911d8233 = null; CMSPresentationFormSettingsUIHandler::prepareFormInputParameters($v1b0cfa478b, $v5a911d8233); $v487af869a3 = isset($v1b0cfa478b["default"]) ? $v1b0cfa478b["default"] : null; $pa3516e89 = (strlen($v487af869a3) || !$v9abfb1e31c) && ($v5a911d8233 == "checkbox" || $v5a911d8233 == "radio") && $v23ed8083a0; if ($pa3516e89) $v1b0cfa478b["default"] = 0; if ($v29a1267fee && !empty($v1b0cfa478b["primary_key"]) && WorkFlowDataAccessHandler::isAutoIncrementedAttribute($v1b0cfa478b)) { $v067674f4e4 .= ""; } else if ($v9abfb1e31c && ($v23ed8083a0 || ObjTypeHandler::isDBTypeDate($v3fb9f41470))) { $v4bfe0500a2 = 'null'; if ($pf17ebc72) $pb0f83f27 = $v99242dea8e . '["logged_user_id"] = isset($data["logged_user_id"]) ? $data["logged_user_id"] : null;' . "\n"; else if ($pa3516e89) $v4bfe0500a2 = isset($v1b0cfa478b["default"]) ? $v1b0cfa478b["default"] : null; $v067674f4e4 .= 'if (' . $v1a025758bb . '!strlen(trim(' . $v99242dea8e . '["' . $v5e45ec9bb9 . '"]))) ' . $v99242dea8e . '["' . $v5e45ec9bb9 . '"] = ' . $v4bfe0500a2 . ';' . "\n"; } else if ($v23ed8083a0 || ObjTypeHandler::isDBTypeBoolean($v3fb9f41470)) { $v4bfe0500a2 = 'null'; if ($pf17ebc72) { $pb0f83f27 = $v99242dea8e . '["logged_user_id"] = isset($data["logged_user_id"]) ? $data["logged_user_id"] : null;' . "\n"; if (empty($v1b0cfa478b["default"]) && !strlen($v1b0cfa478b["default"]) && !$v9abfb1e31c) $v4bfe0500a2 = $v99242dea8e . '["logged_user_id"] > 0 ? ' . $v99242dea8e . '["logged_user_id"] : ' . $v4bfe0500a2; } else if ($pa3516e89) $v4bfe0500a2 = isset($v1b0cfa478b["default"]) ? $v1b0cfa478b["default"] : null; $v067674f4e4 .= 'if (' . $v1a025758bb . '!is_numeric(' . $v99242dea8e . '["' . $v5e45ec9bb9 . '"])) ' . $v99242dea8e . '["' . $v5e45ec9bb9 . '"] = ' . $v4bfe0500a2 . ';' . "\n"; } if ($v7b5b136445) $v067674f4e4 .= 'if (!empty($_FILES["' . $v5e45ec9bb9 . '"]["tmp_name"]) && file_exists($_FILES["' . $v5e45ec9bb9 . '"]["tmp_name"])) ' . $v99242dea8e . '["' . $v5e45ec9bb9 . '"] = file_get_contents($_FILES["' . $v5e45ec9bb9 . '"]["tmp_name"]);' . "\n"; } if ($pb0f83f27) $v067674f4e4 = $pb0f83f27 . "\n" . $v067674f4e4; return $v067674f4e4; } public static function getUpdateActionPreviousCode($pac4bc40a, $v8c5df8072b, $pfdbbc383, $v99242dea8e, $v085645662c = true) { $v067674f4e4 = self::getInsertActionPreviousCode($pac4bc40a, $v8c5df8072b, $pfdbbc383, $v99242dea8e, false, $v085645662c); $ped0a6251 = WorkFlowDBHandler::getTableFromTables($pac4bc40a, $v8c5df8072b); foreach ($pfdbbc383 as $v5e45ec9bb9) { $v1b0cfa478b = isset($ped0a6251[$v5e45ec9bb9]) ? $ped0a6251[$v5e45ec9bb9] : null; if (!empty($v1b0cfa478b["primary_key"])) { $v670a5790dd = isset($v1b0cfa478b["type"]) ? $v1b0cfa478b["type"] : null; if (ObjTypeHandler::isDBTypeNumeric($v670a5790dd) || ObjTypeHandler::isPHPTypeNumeric($v670a5790dd)) $v067674f4e4 .= 'if (array_key_exists("' . $v5e45ec9bb9 . '", ' . $v99242dea8e . ') && !is_numeric(' . $v99242dea8e . '["' . $v5e45ec9bb9 . '"])) $status = false;' . "\n"; else $v067674f4e4 .= 'if (array_key_exists("' . $v5e45ec9bb9 . '", ' . $v99242dea8e . ') && !strlen(trim(' . $v99242dea8e . '["' . $v5e45ec9bb9 . '"]))) $status = false;' . "\n"; } } return $v067674f4e4; } private static function f78c42374a8($pf3dc0762, $v036170882a, $v1335217393) { $pbb803615 = PHPCodePrintingHandler::getFunctionFromFile($pf3dc0762, $v036170882a["name"], $v1335217393); if (!$pbb803615) return PHPCodePrintingHandler::addFunctionToFile($pf3dc0762, $v036170882a, $v1335217393); return true; } } ?>
