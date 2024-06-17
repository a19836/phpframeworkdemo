<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.client.rest.RESTBrokerClient"); include_once get_lib("org.phpframework.broker.client.IDBBrokerClient"); class RESTDBBrokerClient extends RESTBrokerClient implements IDBBrokerClient { public function getDBDriversName() { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca); } public function getFunction($v9d33ecaf56, $v9367d5be85 = false, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__ . "/$v9d33ecaf56"; return $this->requestResponse($v30857f7eca, array("parameters" => $v9367d5be85, "options" => $v5d3813882f)); } public function getData($v3c76382d93, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => $v3c76382d93, "options" => $v5d3813882f)); } public function setData($v3c76382d93, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => $v3c76382d93, "options" => $v5d3813882f)); } public function getSQL($v3c76382d93, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => $v3c76382d93, "options" => $v5d3813882f)); } public function setSQL($v3c76382d93, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => $v3c76382d93, "options" => $v5d3813882f)); } public function getInsertedId($v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("options" => $v5d3813882f)); } public function insertObject($v8c5df8072b, $pfdbbc383, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => array( "table_name" => $v8c5df8072b, "attributes" => $pfdbbc383, ), "options" => $v5d3813882f)); } public function updateObject($v8c5df8072b, $pfdbbc383, $paf1bc6f6 = false, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => array( "table_name" => $v8c5df8072b, "attributes" => $pfdbbc383, "conditions" => $paf1bc6f6, ), "options" => $v5d3813882f)); } public function deleteObject($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => array( "table_name" => $v8c5df8072b, "conditions" => $paf1bc6f6, ), "options" => $v5d3813882f)); } public function findObjects($v8c5df8072b, $pfdbbc383 = false, $paf1bc6f6 = false, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => array( "table_name" => $v8c5df8072b, "attributes" => $pfdbbc383, "conditions" => $paf1bc6f6, ), "options" => $v5d3813882f)); } public function countObjects($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => array( "table_name" => $v8c5df8072b, "conditions" => $paf1bc6f6, ), "options" => $v5d3813882f)); } public function findRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => array( "table_name" => $v8c5df8072b, "rel_elm" => $v10c59e20bd, "parent_conditions" => $v4ec0135323, ), "options" => $v5d3813882f)); } public function countRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => array( "table_name" => $v8c5df8072b, "rel_elm" => $v10c59e20bd, "parent_conditions" => $v4ec0135323, ), "options" => $v5d3813882f)); } public function findObjectsColumnMax($v8c5df8072b, $v7162e23723, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/" . __FUNCTION__; return $this->requestResponse($v30857f7eca, array("parameters" => array( "table_name" => $v8c5df8072b, "attribute_name" => $v7162e23723, ), "options" => $v5d3813882f)); } } ?>
