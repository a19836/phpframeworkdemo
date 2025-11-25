<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */
 namespace WorkFlowTask\programming\slaitemgroup; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { return null; } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = isset($v7f5911d32d["raw_data"]) ? $v7f5911d32d["raw_data"] : null; $pef349725 = isset($v3c3af72a1c["childs"]["properties"][0]["childs"]) ? $v3c3af72a1c["childs"]["properties"][0]["childs"] : null; $pef349725 = \MyXML::complexArrayToBasicArray($pef349725, array("lower_case_keys" => true)); return isset($pef349725["properties"]) ? $pef349725["properties"] : null; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = isset($this->data) ? $this->data : null; $pef349725 = isset($v539082ff30["properties"]) ? $v539082ff30["properties"] : null; $pde6f8fba = array(); if ($v56dcda6d50) $pde6f8fba = is_array($v56dcda6d50) ? $v56dcda6d50 : array($v56dcda6d50); if (!empty($v539082ff30["exits"]["outside_group_exit"][0])) $pde6f8fba = array_merge($pde6f8fba, $v539082ff30["exits"]["outside_group_exit"]); $v5cfb8e5eb1 = isset($v539082ff30["exits"]["inside_group_exit"]) ? $v539082ff30["exits"]["inside_group_exit"] : null; $pdaaa120c = isset($v539082ff30["exits"]["outside_group_exit"][0]) ? $v539082ff30["exits"]["outside_group_exit"][0] : null; $v1f377b389c = self::printTask($v1d696dbd12, $v5cfb8e5eb1, $pde6f8fba, $v54bb17785b . "\t", $v5d3813882f); $v7e12e3e1a3 = self::printTask($v1d696dbd12, $pdaaa120c, $v56dcda6d50, $v54bb17785b, $v5d3813882f); return array( "properties" => $pef349725, "inner" => $v1f377b389c, "next" => $v7e12e3e1a3, ); } } ?>
