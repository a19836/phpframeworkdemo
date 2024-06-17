<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.object.ObjTypeHandler"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once $EVC->getUtilPath("WorkFlowDataAccessHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $db_driver = $_GET["db_driver"]; $db_type = $_GET["type"]; $path = $_GET["path"]; $hbn_obj_id = $_GET["obj"]; $query_id = $_GET["query"]; $query_type = $_GET["query_type"]; $relationship_type = $_GET["relationship_type"]; $path = str_replace("../", "", $path); $PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_path); $PHPVariablesFileHandler->startUserGlobalVariables(); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); if ($obj && is_a($obj, "DataAccessLayer")) { $layer_path = $obj->getLayerPathSetting(); $file_path = $layer_path . $path; if ($path && file_exists($file_path)) { $parameters = array(); $obj->getSQLClient()->loadXML($file_path); $nodes = $obj->getSQLClient()->getNodesData(); if ($query_id) { $db_broker = WorkFlowBeansFileHandler::getLayerLocalDBBrokerNameForChildBrokerDBDriver($user_global_variables_file_path, $user_beans_folder_path, $obj, $db_driver); $tasks_file_path = $db_type == "diagram" ? WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($workflow_paths_id, "db_diagram", $db_driver) : null; if ($obj->getType() == "ibatis") { $rels = $nodes; $query_type = $query_type ? $query_type : getNodeType($rels, $query_id); $node = $nodes[$query_type][$query_id]; WorkFlowDataAccessHandler::prepareSQLStatementParameters($node, $rels, $obj, $db_broker, $db_driver, $tasks_file_path, $tables_props, null, $parameters); } else if ($hbn_obj_id) { if ($relationship_type == "queries") { $hbn_obj_data = $nodes["class"][$hbn_obj_id]; $rels = $hbn_obj_data["childs"][$relationship_type]; $query_type = $query_type ? $query_type : getNodeType($rels, $query_id); $node = $rels[$query_type][$query_id]; WorkFlowDataAccessHandler::prepareSQLStatementParameters($node, $rels, $obj, $db_broker, $db_driver, $tasks_file_path, $tables_props, null, $parameters); } else if ($relationship_type == "relationships") { $hbn_obj_data = $nodes["class"][$hbn_obj_id]; $rels = $hbn_obj_data["childs"][$relationship_type]; $query_type = $query_type ? $query_type : getNodeType($rels, $query_id); $node = $rels[$query_type][$query_id]; WorkFlowDataAccessHandler::prepareRelationshipParameters($node, $rels, $obj, $db_broker, $db_driver, $tasks_file_path, $tables_props, $hbn_obj_data, $parameters); if (empty($node["@"]["parameter_class"])) { $hbn_obj_parameters = WorkFlowDataAccessHandler::getHbnObjParameters($obj, $db_broker, $db_driver, $tasks_file_path, $hbn_obj_data, $tables_props); WorkFlowDataAccessHandler::addPrimaryKeysToParameters($hbn_obj_parameters, $parameters); } } else if ($relationship_type == "native") { $hbn_obj_data = $nodes["class"][$hbn_obj_id]; $hbn_obj_parameters = WorkFlowDataAccessHandler::getHbnObjParameters($obj, $db_broker, $db_driver, $tasks_file_path, $hbn_obj_data, $tables_props); $parameters = array(); $no_pks = true; if ($tables_props) { $table_name = key($tables_props); $attrs = $tables_props[$table_name]; if ($attrs) foreach ($attrs as $attr_name => $attr_props) if ($attr_props["primary_key"]) { $no_pks = false; break; } } switch (strtolower($query_id)) { case "insert": case "insertall": $parameters = $hbn_obj_parameters; WorkFlowDataAccessHandler::removePrimaryKeysFromParameters($hbn_obj_parameters, $parameters); break; case "update": $parameters = $hbn_obj_parameters; foreach ($parameters as $param_name => $param_props) if (ObjTypeHandler::isDBAttributeNameACreatedDate($param_name)) unset($parameters[$param_name]); if ($no_pks) { foreach ($parameters as $param_name => $param_props) if (!ObjTypeHandler::isDBAttributeNameACreatedDate($param_name) && !ObjTypeHandler::isDBAttributeNameACreatedUserId($param_name) && !ObjTypeHandler::isDBAttributeNameAModifiedDate($param_name) && !ObjTypeHandler::isDBAttributeNameAModifiedUserId($param_name)) { $pn = $param_props["name"] ? $param_props["name"] : $param_name; $parameters["new_$param_name"] = $param_props; $parameters["new_$param_name"]["name"] = "new_$pn"; $parameters["old_$param_name"] = $param_props; $parameters["old_$param_name"]["name"] = "old_$pn"; unset($parameters[$param_name]); } } break; case "updateall": case "insertorupdate": case "insertorupdateall": case "deleteall": case "find": case "count": $parameters = $hbn_obj_parameters; break; case "updateprimarykeys": $parameters = array(); if (is_array($hbn_obj_parameters)) { foreach ($hbn_obj_parameters as $param_name => $param_props) { $add = false; if ($no_pks && !ObjTypeHandler::isDBAttributeNameACreatedDate($param_name) && !ObjTypeHandler::isDBAttributeNameACreatedUserId($param_name) && !ObjTypeHandler::isDBAttributeNameAModifiedDate($param_name) && !ObjTypeHandler::isDBAttributeNameAModifiedUserId($param_name)) $add = true; else if ($param_props["primary_key"]) $add = true; if ($add) { $pn = $param_props["name"] ? $param_props["name"] : $param_name; $parameters["new_$param_name"] = $param_props; $parameters["new_$param_name"]["name"] = "new_$pn"; $parameters["old_$param_name"] = $param_props; $parameters["old_$param_name"]["name"] = "old_$pn"; } } } break; case "delete": case "findbyid": case "findrelationships": case "findrelationship": case "countrelationships": case "countrelationship": $parameters = $hbn_obj_parameters; if ($no_pks) { foreach ($parameters as $param_name => $param_props) if (ObjTypeHandler::isDBAttributeNameACreatedDate($param_name) || ObjTypeHandler::isDBAttributeNameACreatedUserId($param_name) || ObjTypeHandler::isDBAttributeNameAModifiedDate($param_name) || ObjTypeHandler::isDBAttributeNameAModifiedUserId($param_name)) unset($parameters[$param_name]); } else $parameters = WorkFlowDataAccessHandler::getPrimaryKeysFromParameters($hbn_obj_parameters, $parameters); break; } } else { } } } if ($parameters) { $props = array(); foreach ($parameters as $attr_name => $param) { $name = $param["name"] ? $param["name"] : $attr_name; $type = ObjTypeHandler::convertCompositeTypeIntoSimpleType($param["type"]); $props[$name] = $type && !ObjTypeHandler::isPHPTypeNumeric($type) && !ObjTypeHandler::isDBTypeNumeric($type) ? "string" : ""; } } } } $PHPVariablesFileHandler->endUserGlobalVariables(); function getNodeType($v987a981e39, $pff56eb51) { if (is_array($v987a981e39)) { foreach ($v987a981e39 as $v9c4bf49720 => $v16eb00c1d7) { if (!empty($v16eb00c1d7[$pff56eb51])) { return $v9c4bf49720; } } } return null; } ?>
