<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace WorkFlowTask\programming\code; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $v067674f4e4 = $pb16df866->printCodeStatement($v5faa4b8a01); return array( "code" => $v067674f4e4, "label" => "Code: " . str_replace('"', '', substr($v067674f4e4, 0, 50)), "exits" => array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ), ); } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "code" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["code"][0]["value"], ); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $v067674f4e4 = $v54bb17785b . str_replace("\n", "\n$v54bb17785b", $pef349725["code"]) . "\n"; return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
