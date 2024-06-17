<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace WorkFlowTask\programming\slaitemsingle; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { return null; } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = $v3c3af72a1c["childs"]["properties"][0]["childs"]; $pef349725 = \MyXML::complexArrayToBasicArray($pef349725, array("lower_case_keys" => true)); return $pef349725["properties"]; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $v7e12e3e1a3 = self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID][0], $v56dcda6d50, $v54bb17785b, $v5d3813882f); return array( "properties" => $pef349725, "next" => $v7e12e3e1a3, ); } } ?>
