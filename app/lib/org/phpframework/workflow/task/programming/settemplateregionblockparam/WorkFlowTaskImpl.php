<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace WorkFlowTask\programming\settemplateregionblockparam; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); include_once get_lib("org.phpframework.layer.presentation.cms.CMSFileHandler"); class WorkFlowTaskImpl extends \WorkFlowTask { const MAIN_VARIABLE_NAME = "region_block_local_variables"; public function __construct() { $this->priority = 2; } public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "expr_assign") { $v9073377656 = $pb16df866->getVariableNameProps($v5faa4b8a01); $v9073377656 = $v9073377656 ? $v9073377656 : array(); preg_match_all('/^' . self::MAIN_VARIABLE_NAME . '([ ]*)\[([^\]]*)\]([ ]*)\[([^\]]*)\]([ ]*)\[([^\]]*)\]([ ]*)$/iu', trim($v9073377656["result_var_name"]), $pbae7526c, PREG_PATTERN_ORDER); if (empty($pbae7526c[0][0])) { return null; } $v9b9b8653bc = $pbae7526c[2][0]; $v3017a9dd59 = \CMSFileHandler::getArgumentType($v9b9b8653bc); $v9b9b8653bc = \CMSFileHandler::prepareArgument($v9b9b8653bc, $v3017a9dd59); $peebaaf55 = $pbae7526c[4][0]; $pa7fd620a = \CMSFileHandler::getArgumentType($peebaaf55); $peebaaf55 = \CMSFileHandler::prepareArgument($peebaaf55, $pa7fd620a); $v67ccb03f4c = $pbae7526c[6][0]; $pfd0f4f8d = \CMSFileHandler::getArgumentType($v67ccb03f4c); $v67ccb03f4c = \CMSFileHandler::prepareArgument($v67ccb03f4c, $pfd0f4f8d); $v52ff2b3770 = $v5faa4b8a01->expr; $v74aa7d558c = strtolower($v52ff2b3770->getType()); $v067674f4e4 = $pb16df866->printCodeExpr($v52ff2b3770); $v067674f4e4 = $pb16df866->getStmtValueAccordingWithType($v067674f4e4, $v74aa7d558c); $v9073377656 = array(); $v9073377656["main_variable_name"] = self::MAIN_VARIABLE_NAME; $v9073377656["region"] = $v9b9b8653bc; $v9073377656["region_type"] = self::getConfiguredParsedType($v3017a9dd59); $v9073377656["block"] = $peebaaf55; $v9073377656["block_type"] = self::getConfiguredParsedType($pa7fd620a); $v9073377656["param_name"] = $v67ccb03f4c; $v9073377656["param_name_type"] = self::getConfiguredParsedType($pfd0f4f8d); $v9073377656["param_value"] = $v067674f4e4; $v9073377656["param_value_type"] = self::getConfiguredParsedType( $pb16df866->getStmtType($v52ff2b3770) ); $v9073377656["label"] = "Add param: " . self::prepareTaskPropertyValueLabelFromCodeStmt($v67ccb03f4c) . " for block " . self::prepareTaskPropertyValueLabelFromCodeStmt($peebaaf55) . " in region " . self::prepareTaskPropertyValueLabelFromCodeStmt($v9b9b8653bc); $v9073377656["exits"] = array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ); return $v9073377656; } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "main_variable_name" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["main_variable_name"][0]["value"], "region" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["region"][0]["value"], "region_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["region_type"][0]["value"], "block" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["block"][0]["value"], "block_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["block_type"][0]["value"], "param_name" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["param_name"][0]["value"], "param_name_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["param_name_type"][0]["value"], "param_value" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["param_value"][0]["value"], "param_value_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["param_value_type"][0]["value"], ); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $pc4de5ee4 = '$' . ($pef349725["main_variable_name"] ? $pef349725["main_variable_name"] : self::MAIN_VARIABLE_NAME); $v067674f4e4 = $v54bb17785b . $pc4de5ee4 . "[" . self::getVariableValueCode($pef349725["region"], $pef349725["region_type"]) . "][" . self::getVariableValueCode($pef349725["block"], $pef349725["block_type"]) . "][" . self::getVariableValueCode($pef349725["param_name"], $pef349725["param_name_type"]) . "] = " . self::getVariableValueCode($pef349725["param_value"], $pef349725["param_value_type"]) . ";\n"; return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
