<?php
/*
 * Copyright (c) 2007 PHPMyFrameWork - Joao Pinto
 * AUTHOR: Joao Paulo Lopes Pinto -- http://jplpinto.com
 * 
 * The use of this code must be allowed first by the creator Joao Pinto, since this is a private and proprietary code.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS 
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY 
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR 
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL 
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER 
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT 
 * OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. IN NO EVENT SHALL 
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN 
 * AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE 
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

include_once get_lib("org.phpframework.workflow.IWorkFlowTask"); include_once get_lib("org.phpframework.workflow.exception.WorkFlowTaskException"); include_once get_lib("org.phpframework.util.text.TextSanitizer"); abstract class WorkFlowTask implements IWorkFlowTask { public $data; const DEFAULT_EXIT_ID = "default_exit"; const CONNECTOR_TASK_TYPE = "__connector__"; private $pd3147405; protected $is_loop_task = false; protected $is_return_task = false; protected $is_break_task = false; protected $priority = false; public function cloneTask() { eval ('$pa1327e3e = new ' . get_class($this) . '();'); if (!empty($pa1327e3e)) { $pa1327e3e->setTaskClassInfo( $this->pd3147405 ); $pa1327e3e->data = $this->data; } return $pa1327e3e; } public function parse($v2c1228f003) { $v7f5911d32d = array( "id" => self::getTaskId($v2c1228f003), "type" => self::getTaskType($v2c1228f003), "label" => $v2c1228f003["childs"]["label"][0]["value"], "tag" => $v2c1228f003["childs"]["tag"][0]["value"], "raw_data" => $v2c1228f003, ); $v7e4b517c18 = $v7f5911d32d["raw_data"]["@"]["start"]; if (strlen($v7e4b517c18)) $v7f5911d32d["start"] = $v7e4b517c18 == "true" ? 1 : (is_numeric($v7e4b517c18) && $v7e4b517c18 > 0 ? $v7e4b517c18 : false); $v6939304e91 = self::getTaskExists($v2c1228f003); if ($v6939304e91) $v7f5911d32d["exits"] = $v6939304e91; $pef349725 = $this->parseProperties($v7f5911d32d); if (!empty($pef349725)) { $v7f5911d32d["properties"] = $pef349725; } unset($v7f5911d32d["raw_data"]); $this->data = $v7f5911d32d; } protected static function parseGroup($pf0fe1cbf) { $pf5d4184d = array( "type" => "group", "join" => $pf0fe1cbf["@"]["join"], ); $pfe52f392 = array(); if (is_array($pf0fe1cbf["childs"])) { foreach ($pf0fe1cbf["childs"] as $v3fb9f41470 => $pd6f79d73) { if ($v3fb9f41470 == "item") { foreach ($pd6f79d73 as $v342a134247) { $pfae508b5 = array("value" => $v342a134247["childs"]["first"][0]["value"], "type" => $v342a134247["childs"]["first"][0]["@"]["type"]); $v1bb84cf6f6 = array("value" => $v342a134247["childs"]["second"][0]["value"], "type" => $v342a134247["childs"]["second"][0]["@"]["type"]); if (isset($v342a134247["childs"]["first"][0]["childs"]["value"][0]["value"])) { $pfae508b5["value"] = $v342a134247["childs"]["first"][0]["childs"]["value"][0]["value"]; } if (isset($v342a134247["childs"]["first"][0]["childs"]["type"][0]["value"])) { $pfae508b5["type"] = $v342a134247["childs"]["first"][0]["childs"]["type"][0]["value"]; } if (isset($v342a134247["childs"]["second"][0]["childs"]["value"][0]["value"])) { $v1bb84cf6f6["value"] = $v342a134247["childs"]["second"][0]["childs"]["value"][0]["value"]; } if (isset($v342a134247["childs"]["second"][0]["childs"]["type"][0]["value"])) { $v1bb84cf6f6["type"] = $v342a134247["childs"]["second"][0]["childs"]["type"][0]["value"]; } $pfe52f392[ $v342a134247["xml_order_id"] ] = array( "type" => $v3fb9f41470, "first" => $pfae508b5, "operator" => $v342a134247["childs"]["operator"][0]["value"], "second" => $v1bb84cf6f6, ); } } else if ($v3fb9f41470 == "group") { foreach ($pd6f79d73 as $peb6501c6) { $pee1f4d1a = self::parseGroup($peb6501c6); $pfe52f392[ $peb6501c6["xml_order_id"] ] = $pee1f4d1a; } } else if ($v3fb9f41470 == "join") { $pf5d4184d["join"] = $pd6f79d73[0]["value"]; } } } $pcbcc2769 = array(); $v9994512d98 = array_keys($pfe52f392); sort($v9994512d98); foreach($v9994512d98 as $pbfa01ed1) { $pcbcc2769[] = $pfe52f392[$pbfa01ed1]; } $pf5d4184d["item"] = $pcbcc2769; return $pf5d4184d; } protected static function getConfiguredParsedType($v3fb9f41470, $pc28d161b = array("", "string", "variable")) { return $v3fb9f41470 && is_array($pc28d161b) ? (in_array($v3fb9f41470, $pc28d161b) ? $v3fb9f41470 : "") : $v3fb9f41470; } public static function printTask($v1d696dbd12, $v55230b8b5f, $v6d0b1fb507, $v54bb17785b = "", $v5d3813882f = null) { $pb35f0325 = $v5d3813882f ? $v5d3813882f["return_obj"] : false; $v5a8476b36f = $v5d3813882f ? $v5d3813882f["with_comments"] : false; $pba23d78c = $pb35f0325 ? array() : ""; if (isset($v55230b8b5f)) { if (!is_array($v55230b8b5f)) $v55230b8b5f = array($v55230b8b5f); $v6d0b1fb507 = is_array($v6d0b1fb507) ? $v6d0b1fb507 : array($v6d0b1fb507); $v16ac35fd79 = count($v55230b8b5f); for ($v43dd7d0051 = 0; $v43dd7d0051 < $v16ac35fd79; $v43dd7d0051++) { $v8282c7dd58 = $v55230b8b5f[$v43dd7d0051]; if ($v8282c7dd58 && !in_array($v8282c7dd58, $v6d0b1fb507)) { $v7f5911d32d = $v1d696dbd12[ $v8282c7dd58 ]; if (isset($v7f5911d32d)) { $v575c44b71f = $v7f5911d32d->printCode($v1d696dbd12, $v6d0b1fb507, $v54bb17785b, $v5d3813882f); if ($v5a8476b36f) { $v846ba4c022 = "task[" . $v7f5911d32d->data["tag"] . "][" . html_entity_decode($v7f5911d32d->data["label"]) . "]"; $v846ba4c022 = "\n$v54bb17785b/* START: $v846ba4c022 */\n"; } else $v846ba4c022 = "\n"; if ($pb35f0325) $pba23d78c[] = array("comment" => $v846ba4c022, "code" => $v575c44b71f); else if (isset($v575c44b71f)) $pba23d78c .= $v846ba4c022 . $v575c44b71f; } else launch_exception(new WorkFlowTaskException(2, $v8282c7dd58)); } } } return $pba23d78c; } protected static function printGroup($peb6501c6) { $v067674f4e4 = ""; if (is_array($peb6501c6["item"])) { $v3c581c912b = strtolower($peb6501c6["join"]) == "and" ? "&&" : "||"; $pa56d9a5b = false; foreach($peb6501c6["item"] as $v342a134247) { $v067674f4e4 .= ($pa56d9a5b ? " " . $v3c581c912b . " " : ""); if ($v342a134247["type"] == "group") { $v067674f4e4 .= "(" . self::printGroup($v342a134247) . ")"; } else if ($v342a134247["type"] == "item") { if (is_array($v342a134247["first"])) { $v2adde01bd3 = self::getVariableValueCode($v342a134247["first"]["value"], $v342a134247["first"]["type"]); } else { $v2adde01bd3 = $v342a134247["first"]; } if (is_array($v342a134247["second"])) { $pd5efa3ff = self::getVariableValueCode($v342a134247["second"]["value"], $v342a134247["second"]["type"]); } else { $pd5efa3ff = $v342a134247["second"]; } $v19a7745bc6 = $v342a134247["operator"] ? $v342a134247["operator"] : "=="; $v38cee9c6bb = strtolower($v2adde01bd3); $v92f0947ee9 = strtolower($pd5efa3ff); if ($v38cee9c6bb == "true" || $v92f0947ee9 == "true") { if ($v38cee9c6bb == $v92f0947ee9) $v067674f4e4 .= $v2adde01bd3; else if ($v19a7745bc6 == "==") $v067674f4e4 .= $v38cee9c6bb == "true" ? $pd5efa3ff : $v2adde01bd3; else if ($v19a7745bc6 == "!=") $v067674f4e4 .= $v38cee9c6bb == "true" ? "!$pd5efa3ff" : "!$v2adde01bd3"; else $v067674f4e4 .= $v2adde01bd3 . " " . $v19a7745bc6 . " " . $pd5efa3ff; } else if ($v38cee9c6bb == "false" || $v92f0947ee9 == "false") { if ($v38cee9c6bb == $v92f0947ee9) $v067674f4e4 .= $v2adde01bd3; else if ($v19a7745bc6 == "==") $v067674f4e4 .= $v38cee9c6bb == "false" ? "!$pd5efa3ff" : "!$v2adde01bd3"; else if ($v19a7745bc6 == "!=") $v067674f4e4 .= $v38cee9c6bb == "false" ? $pd5efa3ff : $v2adde01bd3; else $v067674f4e4 .= $v2adde01bd3 . " " . $v19a7745bc6 . " " . $pd5efa3ff; } else $v067674f4e4 .= $v2adde01bd3 . " " . $v19a7745bc6 . " " . $pd5efa3ff; } $pa56d9a5b = true; } } return $v067674f4e4; } public static function getTaskPaths($v1d696dbd12, $v8282c7dd58, $pe293334b = false) { $v57a9807e67 = array(); if ($v8282c7dd58) { $v7f5911d32d = $v1d696dbd12[ $v8282c7dd58 ]; if (isset($v7f5911d32d)) { $v57a9807e67[] = array($v8282c7dd58); $v6939304e91 = $v7f5911d32d->data["exits"]; if (is_array($v6939304e91)) { $pfe947a0a = array(); if ($pe293334b && $v7f5911d32d->isLoopTask()) $v6939304e91 = array(self::DEFAULT_EXIT_ID => $v6939304e91[self::DEFAULT_EXIT_ID]); foreach ($v6939304e91 as $v7822e6467d => $pdcbd5e39) { if (!is_array($pdcbd5e39)) $pdcbd5e39 = array($pdcbd5e39); $pc37695cb = count($pdcbd5e39); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v0b70a1f3bc = $pdcbd5e39[$v43dd7d0051]; $pde54e5d6 = self::getTaskPaths($v1d696dbd12, $v0b70a1f3bc, $pe293334b); $pd28479e5 = count($pde54e5d6); for ($v9d27441e80 = 0; $v9d27441e80 < $pd28479e5; $v9d27441e80++) $pfe947a0a[] = array_merge($v57a9807e67[0], $pde54e5d6[$v9d27441e80]); } } if ($pfe947a0a) $v57a9807e67 = $pfe947a0a; } } else launch_exception(new WorkFlowTaskException(2, $v8282c7dd58)); } return $v57a9807e67; } public static function getCommonTaskExitIdFromTaskPaths($v1d696dbd12, $v8282c7dd58) { $v57a9807e67 = self::getTaskPaths($v1d696dbd12, $v8282c7dd58, true); $v665ef581e1 = $v57a9807e67 ? count($v57a9807e67) : 0; $v878142e8d4 = self::mae1bf5ced463($v57a9807e67); if (!$v878142e8d4) { $pa32be502 = $v57a9807e67[0]; $pc37695cb = $pa32be502 ? count($pa32be502) : 0; for ($v43dd7d0051 = 1; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v0b70a1f3bc = $pa32be502[$v43dd7d0051]; $v5c1c342594 = true; for ($v9d27441e80 = 1; $v9d27441e80 < $v665ef581e1; $v9d27441e80++) if (!in_array($v0b70a1f3bc, $v57a9807e67[$v9d27441e80])) $v5c1c342594 = false; if ($v5c1c342594) return $v0b70a1f3bc; } } $v6b5db2c7c0 = array(); for ($v9d27441e80 = 0; $v9d27441e80 < $v665ef581e1; $v9d27441e80++) { $pa32be502 = $v57a9807e67[$v9d27441e80]; if (is_array($pa32be502)) { $pe003572c = $pa32be502[ count($pa32be502) - 1 ]; $pa1feb8d8 = $v1d696dbd12[$pe003572c]; if (!$pa1feb8d8->isReturnTask()) $v6b5db2c7c0[] = $v9d27441e80; } } $pa22e93a6 = count($v6b5db2c7c0); if ($pa22e93a6 >= 2 && $pa22e93a6 != $v665ef581e1) { $paa60a679 = array(); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pa22e93a6; $v43dd7d0051++) $paa60a679[] = $v57a9807e67[ $v6b5db2c7c0[$v43dd7d0051] ]; $v878142e8d4 = self::mae1bf5ced463($paa60a679); if (!$v878142e8d4) { $pd69fb7d0 = $v6b5db2c7c0[0]; $pa32be502 = $v57a9807e67[$pd69fb7d0]; $pc37695cb = $pa32be502 ? count($pa32be502) : 0; for ($v43dd7d0051 = 1; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v0b70a1f3bc = $pa32be502[$v43dd7d0051]; $v5c1c342594 = true; for ($v9d27441e80 = 1; $v9d27441e80 < $pa22e93a6; $v9d27441e80++) { $pd69fb7d0 = $v6b5db2c7c0[$v9d27441e80]; if (!in_array($v0b70a1f3bc, $v57a9807e67[$pd69fb7d0])) $v5c1c342594 = false; } if ($v5c1c342594) return $v0b70a1f3bc; } } } else if ($pa22e93a6 == 0) { $v87937e23a4 = array(); for ($v43dd7d0051 = 0; $v43dd7d0051 < $v665ef581e1; $v43dd7d0051++) { $pa32be502 = $v57a9807e67[$v43dd7d0051]; do { $pabdb0169 = implode(",", $pa32be502); $v15f3268002 = 0; for ($pc5166886 = 0; $pc5166886 < $v665ef581e1; $pc5166886++) if ($pc5166886 != $v43dd7d0051) { $v4dab67729b = $v57a9807e67[$pc5166886]; $v075ee51a04 = implode(",", $v4dab67729b); if (strpos($v075ee51a04, $pabdb0169) !== false) $v15f3268002++; } if ($v15f3268002 > 0) $v87937e23a4[$v15f3268002][] = $pabdb0169; array_shift($pa32be502); } while(!empty($pa32be502)); } if ($v87937e23a4) { krsort($v87937e23a4); $v9994512d98 = array_keys($v87937e23a4); $pc430fa79 = $v87937e23a4[ $v9994512d98[0] ]; $v15f3268002 = 0; $v1365dc2bc7 = null; $pc37695cb = $pc430fa79 ? count($pc430fa79) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v617affee92 = explode(",", $pc430fa79[$v43dd7d0051]); $v516d65c8d0 = count($v617affee92); if ($v516d65c8d0 > $v15f3268002) { $v15f3268002 = $v516d65c8d0; $v1365dc2bc7 = $v617affee92[0]; } } return $v1365dc2bc7; } } return null; } private static function mae1bf5ced463($v57a9807e67) { if ($v57a9807e67) { $pc37695cb = count($v57a9807e67); for ($v43dd7d0051 = 1; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) if ($v57a9807e67[$v43dd7d0051][1] != $v57a9807e67[0][1]) return false; } return true; } public static function createTasksPropertiesFromCodeStmts($v793f92423d, $pb16df866) { if ($v793f92423d) { $v6f3463dea9 = array(); $v71ff493177 = array(); $pb59a7c0b = $pb16df866->getAvailableStatements(); $v75c604f977 = array(); foreach ($v793f92423d as $v5faa4b8a01) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); $v1d696dbd12 = $pb59a7c0b[$pe83cda0c]; $v7959970a41 = false; $pc37695cb = $v1d696dbd12 ? count($v1d696dbd12) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7f5911d32d = $v1d696dbd12[$v43dd7d0051]; if ($v7f5911d32d) { $v6939304e91 = $v1f377b389c = array(); $v9073377656 = $v7f5911d32d["obj"]->createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, $v6939304e91, $v1f377b389c, $v6f3463dea9); $v4a2817e138 = null; if (is_array($v9073377656) && !empty($v9073377656)) $v4a2817e138 = $pb16df866->createXMLTask($v7f5911d32d, $v9073377656, $v6939304e91); else if ($v1f377b389c) $v4a2817e138 = $pb16df866->createConnectorXMLTask($v6939304e91); if ($v4a2817e138) { if (!empty($v75c604f977)) { $v6f3463dea9[] = $pb16df866->createUndefinedXMLTask($v75c604f977); $v75c604f977 = array(); } $v6f3463dea9[] = $v4a2817e138; if ($v1f377b389c) $v71ff493177[ count($v6f3463dea9) - 1 ] = $v1f377b389c; $v7959970a41 = true; break; } } } if (!$v7959970a41) $v75c604f977[] = $v5faa4b8a01; } if ($v75c604f977) $v6f3463dea9[] = $pb16df866->createUndefinedXMLTask($v75c604f977); $v4e15605731 = array(); $pc37695cb = count($v6f3463dea9); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7ef59e63bd = $v6f3463dea9[$v43dd7d0051]; $v1f377b389c = $v71ff493177[$v43dd7d0051]; do { $v43dd7d0051++; $v7e12e3e1a3 = $v6f3463dea9[$v43dd7d0051]; if ($v7e12e3e1a3["type"] == self::CONNECTOR_TASK_TYPE) { $v63a66853a9 = $v71ff493177[$v43dd7d0051]; $v7e12e3e1a3 = $v63a66853a9[0]["type"] ? $v63a66853a9[0] : $v63a66853a9[0][0]; } } while (!$v7e12e3e1a3 && $v43dd7d0051 < $pc37695cb - 1); $v43dd7d0051--; if ($v7ef59e63bd["type"] != self::CONNECTOR_TASK_TYPE) { if ($v7ef59e63bd["exits"] && $v7e12e3e1a3["id"]) $pb16df866->replaceNextTaskInTaskExits($v7ef59e63bd, $v7e12e3e1a3); else if (!$v7ef59e63bd["exits"] && !$v7ef59e63bd["is_return"] && !$v7ef59e63bd["is_break"]) $pb16df866->addNextTaskToAllTaskExits($v7ef59e63bd, $v7e12e3e1a3); $v4e15605731[] = $v7ef59e63bd; } if ($v1f377b389c) { $pa2678e94 = $v1f377b389c[0]["type"] ? array($v1f377b389c) : $v1f377b389c; $pd28479e5 = $pa2678e94 ? count($pa2678e94) : 0; for ($v9d27441e80 = 0; $v9d27441e80 < $pd28479e5; $v9d27441e80++) { $v1f377b389c = $pa2678e94[$v9d27441e80]; $paf3a3908 = $v1f377b389c ? count($v1f377b389c) : 0; if ($v7e12e3e1a3["id"]) for ($pc5166886 = 0; $pc5166886 < $paf3a3908; $pc5166886++) if ($v1f377b389c[$pc5166886]["exits"] && !$v1f377b389c[$pc5166886]["is_break"]) $pb16df866->replaceNextTaskInTaskExits($v1f377b389c[$pc5166886], $v7e12e3e1a3); $v4e15605731 = array_merge($v4e15605731, $v1f377b389c); } } } $v6f3463dea9 = $v4e15605731; return $v6f3463dea9; } return null; } public static function joinTaskPropertiesWithIncludeFileTaskPropertiesSibling(&$pe927264a, &$v6f3463dea9) { if ($v6f3463dea9) { $pbabc36ae = $v6f3463dea9[ count($v6f3463dea9) - 1 ]; if ($pbabc36ae && $pbabc36ae["tag"] == "includefile") { if (!is_array($pe927264a)) $pe927264a = array(); $pe927264a["include_file_path"] = $pbabc36ae["properties"]["file_path"]; $pe927264a["include_file_path_type"] = $pbabc36ae["properties"]["type"]; $pe927264a["include_once"] = $pbabc36ae["properties"]["once"]; array_pop($v6f3463dea9); } } } protected static function prepareTaskPropertyValueLabelFromCodeStmt($v67db1bd535) { return substr($v67db1bd535, 0, 1) == '$' ? substr($v67db1bd535, 1) : $v67db1bd535; } public static function getTaskType($v2c1228f003) { return isset($v2c1228f003["childs"]["type"][0]["value"]) ? strtolower($v2c1228f003["childs"]["type"][0]["value"]) : false; } public static function getTaskId($v2c1228f003) { return isset($v2c1228f003["childs"]["id"][0]["value"]) ? $v2c1228f003["childs"]["id"][0]["value"] : false; } public static function getTaskExists($v2c1228f003) { $v6939304e91 = array(); if (is_array($v2c1228f003["childs"]["exits"][0]["childs"])) foreach ($v2c1228f003["childs"]["exits"][0]["childs"] as $v7aba950727 => $v40b113f5d9) if ($v40b113f5d9) { $v16ac35fd79 = count($v40b113f5d9); for ($v43dd7d0051 = 0; $v43dd7d0051 < $v16ac35fd79; $v43dd7d0051++) if (isset($v40b113f5d9[$v43dd7d0051]["childs"]["task_id"][0]["value"]) && !empty($v40b113f5d9[$v43dd7d0051]["childs"]["task_id"][0]["value"])) $v6939304e91[ $v7aba950727 ][] = $v40b113f5d9[$v43dd7d0051]["childs"]["task_id"][0]["value"]; } return $v6939304e91; } protected static function parseParameters($v9367d5be85) { } protected static function parseIncludes($pc06f1034) { $v6824ec2eb2 = array(); if (is_array($pc06f1034)) { foreach ($pc06f1034 as $pc24afc88) { $v6824ec2eb2[] = array( "type" => (isset($pc24afc88["@"]["type"]) ? $pc24afc88["@"]["type"] : ""), "include" => $pc24afc88["value"], ); } } return $v6824ec2eb2; } protected static function parseArrayItems($pf72c1d58) { $v2f228af834 = array(); $pc37695cb = $pf72c1d58 ? count($pf72c1d58) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v342a134247 = $pf72c1d58[$v43dd7d0051]["childs"]; if ($v342a134247) { $pbfa01ed1 = $v342a134247["key"][0]["value"]; $v1491940c54 = $v342a134247["key_type"][0]["value"]; $pb0e4fbbf = $v342a134247["items"]; if ($pb0e4fbbf) { $v2f228af834[] = array( "key" => $pbfa01ed1, "key_type" => $v1491940c54, "items" => self::parseArrayItems($pb0e4fbbf), ); } else { $v67db1bd535 = $v342a134247["value"][0]["value"]; $pc3e857ed = $v342a134247["value_type"][0]["value"]; $v2f228af834[] = array( "key" => $pbfa01ed1, "key_type" => $v1491940c54, "value" => $v67db1bd535, "value_type" => $pc3e857ed, ); } } } return $v2f228af834; } public static function getArrayString($pf72c1d58, $v54bb17785b = "") { if (empty($pf72c1d58) || !is_array($pf72c1d58)) return "array()"; $v067674f4e4 = $v54bb17785b . "array(\n"; $v866b2a3622 = true; foreach ($pf72c1d58 as $v342a134247) { $pbfa01ed1 = $v342a134247["key"]; $v1491940c54 = $v342a134247["key_type"]; $pb0e4fbbf = $v342a134247["items"]; if ($pb0e4fbbf) { $v67db1bd535 = self::getArrayString($pb0e4fbbf, $v54bb17785b . "\t"); $v67db1bd535 = ltrim($v67db1bd535); } else { $v67db1bd535 = $v342a134247["value"]; $pc3e857ed = $v342a134247["value_type"]; $v67db1bd535 = self::getVariableValueCode($v67db1bd535, $pc3e857ed); } if (!$v866b2a3622) { $v067674f4e4 .= ",\n"; } if ($v1491940c54 != "null" && isset($pbfa01ed1) && strlen($pbfa01ed1) > 0) { $pbfa01ed1 = self::getVariableValueCode($pbfa01ed1, $v1491940c54); $v067674f4e4 .= "$v54bb17785b\t$pbfa01ed1 => " . $v67db1bd535; } else { $v067674f4e4 .= "$v54bb17785b\t$v67db1bd535"; } $v866b2a3622 = false; } $v067674f4e4 .= "\n$v54bb17785b)"; return $v067674f4e4; } protected static function getParametersString($v9367d5be85) { $v327f72fb62 = ""; if (is_array($v9367d5be85)) { $pc37695cb = count($v9367d5be85); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pc5faab2f = $v9367d5be85[$v43dd7d0051]; $v67db1bd535 = $pc5faab2f["childs"]["value"][0]["value"]; $v3fb9f41470 = $pc5faab2f["childs"]["type"][0]["value"]; if (isset($v67db1bd535)) { $pc5faab2f = self::getVariableValueCode($v67db1bd535, $v3fb9f41470); $pc5faab2f = strlen($pc5faab2f) ? $pc5faab2f : "null"; $v327f72fb62 .= ($v43dd7d0051 > 0 ? ", " : "") . $pc5faab2f; } } } return $v327f72fb62; } protected static function getVariableAssignmentOperator($v23de710cb7 = false) { return $v23de710cb7 == "concat" || $v23de710cb7 == "concatenate" ? ".=" : ($v23de710cb7 == "increment" ? "+=" : ($v23de710cb7 == "decrement" ? "-=" : "=")); } public static function getVariableValueCode($v498f441296, $v3fb9f41470 = null) { if (!isset($v498f441296)) return $v3fb9f41470 == "string" || $v3fb9f41470 == "date" ? "''" : (!$v3fb9f41470 ? "null" : ""); $v956913c90f = trim($v498f441296); if ($v3fb9f41470 == "variable" && $v956913c90f) return (substr($v956913c90f, 0, 1) != '$' ? '$' : '') . $v956913c90f; else if ($v3fb9f41470 == "string" || $v3fb9f41470 == "date") { return '"' . TextSanitizer::addCSlashes($v498f441296, '"', true) . '"'; } else if (!$v3fb9f41470 && strlen($v956913c90f) == 0) return "null"; else if (!$v3fb9f41470 && substr($v956913c90f, 0, 5) == '<?php' && substr($v956913c90f, -2) == '?>') return trim(substr($v956913c90f, 5, -2)); else if (!$v3fb9f41470 && substr($v956913c90f, 0, 3) == '<?=' && substr($v956913c90f, -2) == '?>') return trim(substr($v956913c90f, 3, -2)); else if (!$v3fb9f41470 && substr($v956913c90f, 0, 2) == '<?' && substr($v956913c90f, -2) == '?>') return trim(substr($v956913c90f, 2, -2)); else return $v956913c90f; } protected static function getPropertiesResultVariableCode($pef349725, $pb534504c = true) { $v1cfba8c105 = $pef349725["result_var_name"]; $v84627e5925 = $pef349725["result_var_assignment"]; $v55c0c0e582 = $pef349725["result_obj_name"]; $pe9716498 = $pef349725["result_prop_name"]; $static = $pef349725["result_static"]; $pe475b3ff = $pef349725["result_prop_assignment"]; $v2a4b335fe3 = $pef349725["result_echo"]; $v05b1c40a78 = $pef349725["result_return"]; $v3fb9f41470 = $pef349725["result_var_type"]; return self::getResultVariableCode($v1cfba8c105, $v84627e5925, $v55c0c0e582, $pe9716498, $static, $pe475b3ff, $v2a4b335fe3, $v05b1c40a78, $v3fb9f41470, $pb534504c); } protected static function getResultVariableCode($v1cfba8c105, $v84627e5925, $v55c0c0e582, $pe9716498, $static, $pe475b3ff, $v2a4b335fe3 = null, $v05b1c40a78 = null, $v3fb9f41470 = null, $pb534504c = true) { $v1cfba8c105 = isset($v1cfba8c105) ? trim($v1cfba8c105) : $v1cfba8c105; $v55c0c0e582 = isset($v55c0c0e582) ? trim($v55c0c0e582) : $v55c0c0e582; $pe9716498 = isset($pe9716498) ? trim($pe9716498) : $pe9716498; if ( (isset($v3fb9f41470) && $v3fb9f41470 == "variable") || (!isset($v3fb9f41470) && $v1cfba8c105) ) { if (empty($v1cfba8c105)) { return null; } $v19a7745bc6 = self::getVariableAssignmentOperator($v84627e5925); return (substr($v1cfba8c105, 0, 1) != '$' ? '$' : '') . $v1cfba8c105 . ($pb534504c ? " $v19a7745bc6 " : ""); } else if ( (isset($v3fb9f41470) && $v3fb9f41470 == "obj_prop") || (!isset($v3fb9f41470) && $v55c0c0e582 && $pe9716498) ) { if (empty($v55c0c0e582) || empty($pe9716498)) { return null; } $v19a7745bc6 = self::getVariableAssignmentOperator($pe475b3ff); if ($static) { return $v55c0c0e582 . '::' . (substr($pe9716498, 0, 1) != '$' ? '$' : '') . $pe9716498 . ($pb534504c ? " $v19a7745bc6 " : ""); } else { return (substr($v55c0c0e582, 0, 1) != '$' ? '$' : '') . $v55c0c0e582 . '->' . $pe9716498 . ($pb534504c ? " $v19a7745bc6 " : ""); } } else if (!empty($v2a4b335fe3)) { return "echo "; } else if (!empty($v05b1c40a78)) { return "return "; } return null; } protected static function getPropertiesIncludeFileCode($pef349725) { if ($pef349725["include_file_path"]) { $v1cfba8c105 = self::getVariableValueCode($pef349725["include_file_path"], $pef349725["include_file_path_type"]); if ($v1cfba8c105) return "include" . ($pef349725["include_once"] ? "_once" : "") . " $v1cfba8c105;"; } return null; } protected static function parseResultVariableProperties($v3c3af72a1c, $pef349725 = array()) { $v9073377656 = array( "result_var_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["result_var_type"][0]["value"], "result_var_name" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["result_var_name"][0]["value"], "result_var_assignment" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["result_var_assignment"][0]["value"], "result_obj_name" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["result_obj_name"][0]["value"], "result_prop_name" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["result_prop_name"][0]["value"], "result_static" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["result_static"][0]["value"], "result_prop_assignment" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["result_prop_assignment"][0]["value"], "result_echo" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["result_echo"][0]["value"], "result_return" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["result_return"][0]["value"], ); return array_merge($v9073377656, $pef349725); } protected static function parseIncludeFileProperties($v3c3af72a1c, $pef349725 = array()) { $v9073377656 = array( "include_file_path" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["include_file_path"][0]["value"], "include_file_path_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["include_file_path_type"][0]["value"], "include_once" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["include_once"][0]["value"], ); return array_merge($v9073377656, $pef349725); } public function setTaskClassInfo($pd3147405) { $this->pd3147405 = $pd3147405;} public function getTaskClassInfo() { return $this->pd3147405;} public function isLoopTask() { return $this->is_loop_task; } public function isReturnTask() { return $this->is_return_task; } public function isBreakTask() { return $this->is_break_task; } public function setPriority($v22a616cf75) { $this->priority = $v22a616cf75; } public function getPriority() { return $this->priority; } } ?>
