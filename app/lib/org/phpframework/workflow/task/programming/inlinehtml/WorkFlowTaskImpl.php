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
 namespace WorkFlowTask\programming\inlinehtml; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "stmt_inlinehtml") { return array( "code" => isset($v5faa4b8a01->value) ? $v5faa4b8a01->value : null, "label" => "Some HTML", "exits" => array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ), ); } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = isset($v7f5911d32d["raw_data"]) ? $v7f5911d32d["raw_data"] : null; $pef349725 = array( "code" => isset($v3c3af72a1c["childs"]["properties"][0]["childs"]["code"][0]["value"]) ? $v3c3af72a1c["childs"]["properties"][0]["childs"]["code"][0]["value"] : null, ); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = isset($this->data) ? $this->data : null; $pef349725 = isset($v539082ff30["properties"]) ? $v539082ff30["properties"] : null; $v067674f4e4 = isset($pef349725["code"]) ? $pef349725["code"] : null; $v067674f4e4 = substr($v067674f4e4, 0, 1) == "\n" ? "" : "\n" . $v067674f4e4; $v067674f4e4 .= substr($v067674f4e4, strlen($v067674f4e4) - 1) == "\n" ? "" : "\n"; $v067674f4e4 = "?>" . $v067674f4e4 . "<?php"; $v0b70a1f3bc = isset($v539082ff30["exits"][self::DEFAULT_EXIT_ID]) ? $v539082ff30["exits"][self::DEFAULT_EXIT_ID] : null; return $v067674f4e4 . self::printTask($v1d696dbd12, $v0b70a1f3bc, $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
