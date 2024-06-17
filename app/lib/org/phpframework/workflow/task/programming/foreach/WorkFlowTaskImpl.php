<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace WorkFlowTask\programming\_foreach; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { protected $is_loop_task = true; public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "stmt_foreach") { $v52ff2b3770 = $v5faa4b8a01->expr; $pd109c924 = $v5faa4b8a01->keyVar; $v5f63a73e92 = $v5faa4b8a01->valueVar; $v75c00de05b = $v5faa4b8a01->stmts; $v972f1a5c2b = $pb16df866->printCodeExpr($v52ff2b3770); $pbfa01ed1 = $pd109c924 ? $pb16df866->printCodeExpr($pd109c924) : ""; $v67db1bd535 = $pb16df866->printCodeExpr($v5f63a73e92); $pb735b03c = self::createTasksPropertiesFromCodeStmts($v75c00de05b, $pb16df866); $v9073377656 = array( "obj" => $v972f1a5c2b, "key" => $pbfa01ed1, "value" => $v67db1bd535, "label" => "loop " . self::prepareTaskPropertyValueLabelFromCodeStmt($v972f1a5c2b), "exits" => array( "start_exit" => array( "color" => "#31498f", "label" => "Start loop", ), self::DEFAULT_EXIT_ID => array( "color" => "#2C2D34", "label" => "End loop", ), ), ); $v6939304e91 = array(); $v6939304e91[self::DEFAULT_EXIT_ID][] = array("task_id" => "#next_task#"); if ($pb735b03c) { $v6939304e91["start_exit"][] = array("task_id" => $pb735b03c[0]["id"]); $pb735b03c = $pb16df866->cleanInvalidExitsFromTasks($pb735b03c); $pb735b03c = $pb16df866->stopLoopInnerTasksToBeConnectedToOtherOutsideTasks($pb735b03c); $v1f377b389c = array($pb735b03c); } return $v9073377656; } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "obj" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["obj"][0]["value"], "key" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["key"][0]["value"], "value" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["value"][0]["value"], ); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $pde6f8fba = array(); if ($v56dcda6d50) $pde6f8fba = is_array($v56dcda6d50) ? $v56dcda6d50 : array($v56dcda6d50); if ($v539082ff30["exits"][self::DEFAULT_EXIT_ID][0]) $pde6f8fba = array_merge($pde6f8fba, $v539082ff30["exits"][self::DEFAULT_EXIT_ID]); $pafaf19a2 = self::printTask($v1d696dbd12, $v539082ff30["exits"]["start_exit"], $pde6f8fba, $v54bb17785b . "\t", $v5d3813882f); $v972f1a5c2b = $pef349725["obj"] ? self::getVariableValueCode($pef349725["obj"], "variable") : null; $pbfa01ed1 = $pef349725["key"] ? self::getVariableValueCode($pef349725["key"], "variable") : null; $v67db1bd535 = $pef349725["value"] ? self::getVariableValueCode($pef349725["value"], "variable") : null; if ($v972f1a5c2b && $v67db1bd535) { $pbfa01ed1 = $pbfa01ed1 ? "$pbfa01ed1 => " : ""; $v067674f4e4 = $v54bb17785b . "foreach ($v972f1a5c2b as $pbfa01ed1$v67db1bd535) {"; $v067674f4e4 .= $pafaf19a2 ? $pafaf19a2 : "\n\n"; $v067674f4e4 .= !$v54bb17785b && !preg_match("/\s/", substr($v067674f4e4, -1)) ? " " : ""; $v067674f4e4 .= $v54bb17785b . "}\n"; } else { $v067674f4e4 = ""; } return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID][0], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
