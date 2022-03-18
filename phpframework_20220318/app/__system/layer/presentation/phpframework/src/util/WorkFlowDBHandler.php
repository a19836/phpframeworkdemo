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

include_once $EVC->getUtilPath("WorkFlowTasksFileHandler"); include_once $EVC->getUtilPath("WorkFlowBeansConverter"); include_once $EVC->getUtilPath("PHPVariablesFileHandler"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once get_lib("org.phpframework.db.DB"); class WorkFlowDBHandler { private $v3d55458bcd; private $v5039a77f9d; private $v0f9512fda4; const TASK_TABLE_TYPE = "02466a6d"; const TASK_TABLE_TAG = "table"; public function __construct($v5039a77f9d, $v3d55458bcd) { $this->v5039a77f9d = $v5039a77f9d; $this->v3d55458bcd = $v3d55458bcd; } public function getError() { return $this->v0f9512fda4; } public function areTasksDBDriverValid($v5e053dece2, $v99ccd215e5, $v08be7fc6fe = true, &$pb5834e2b = null) { $v5c1c342594 = true; $v8cd3e3837d = new WorkFlowTasksFileHandler($v5e053dece2); $v8cd3e3837d->init(); $v1d696dbd12 = $v8cd3e3837d->getTasksByLayerTag("dbdriver"); foreach ($v1d696dbd12 as $v7f5911d32d) if (!$v08be7fc6fe || $v7f5911d32d["properties"]["active"]) { $v5c1c342594 = false; $v6491a7e70b = WorkFlowBeansConverter::getFileNameFromRawLabel($v7f5911d32d["label"]) . "_dbdriver.xml"; $v4948cc5869 = $this->getBeanObject($v6491a7e70b, $v7f5911d32d["label"]); if ($v4948cc5869) { try { $v5c1c342594 = $v99ccd215e5 ? @$v4948cc5869->connect() : @$v4948cc5869->connect(); } catch (Exception $paec2c009) {} if (!$v5c1c342594 && $v99ccd215e5) { try { $v5d3813882f = $v4948cc5869->getOptions(); $v5c1c342594 = $v4948cc5869->createDB($v5d3813882f["db_name"]) && @$v4948cc5869->connect(); } catch (Exception $paec2c009) {} } } if (!$v5c1c342594) { $pb5834e2b = $v7f5911d32d["label"]; break; } } return $v5c1c342594; } public function getFirstTaskDBDriverCredentials($v5e053dece2, $pd7b11284 = "") { $pef349725 = array(); $v7f5911d32d = $this->getFirstTaskDBDriver($v5e053dece2); if ($v7f5911d32d) if (is_array($v7f5911d32d["properties"])) foreach ($v7f5911d32d["properties"] as $pe5c5e2fe => $v956913c90f) if ($pe5c5e2fe != "exits") $pef349725[$pd7b11284 . $pe5c5e2fe] = $v956913c90f; return $pef349725; } public function getFirstTaskDBDriver($v5e053dece2) { $v8cd3e3837d = new WorkFlowTasksFileHandler($v5e053dece2); $v8cd3e3837d->init(); $v1d696dbd12 = $v8cd3e3837d->getTasksByLayerTag("dbdriver", 1); return $v1d696dbd12[0]; } public function getBeanObject($v6491a7e70b, $v8ffce2a791) { $pb512d021 = new WorkFlowBeansFileHandler($this->v5039a77f9d . $v6491a7e70b, $this->v3d55458bcd); $v8ffce2a791 = WorkFlowBeansConverter::getObjectNameFromRawLabel($v8ffce2a791); $v972f1a5c2b = $pb512d021->getBeanObject($v8ffce2a791); $this->v0f9512fda4 = $pb512d021->getError(); return $v972f1a5c2b; } public function getDBTables($pa0462a8e, $v8ffce2a791) { $v87e4fe1181 = $this->getBeanObject($pa0462a8e, $v8ffce2a791); if ($v87e4fe1181) { return $v87e4fe1181->listTables(); } return false; } public function getDBTableAttributes($pa0462a8e, $v8ffce2a791, $pc661dc6b) { $v87e4fe1181 = $this->getBeanObject($pa0462a8e, $v8ffce2a791); if ($v87e4fe1181) { return $v87e4fe1181->listTableFields($pc661dc6b); } return false; } public function getTaskDBDiagramSql($pa0462a8e, $v8ffce2a791, $v5e053dece2) { $v3c76382d93 = ""; $v87e4fe1181 = $this->getBeanObject($pa0462a8e, $v8ffce2a791); if ($v87e4fe1181) { $v8cd3e3837d = new WorkFlowTasksFileHandler($v5e053dece2); $v8cd3e3837d->init(); $v1d696dbd12 = $v8cd3e3837d->getWorkflowData(); $v1d696dbd12 = $v1d696dbd12["tasks"]; if (is_array($v1d696dbd12)) { foreach ($v1d696dbd12 as $v8282c7dd58 => $v7f5911d32d) { $pef349725 = $v7f5911d32d["properties"]; $v87a92bb1ad = array( "table_name" => $v7f5911d32d["label"], "charset" => $pef349725["table_charset"], "collation" => $pef349725["table_collation"], "table_storage_engine" => $pef349725["table_storage_engine"], "attributes" =>array(), ); $ped0a6251 = $pef349725["table_attr_names"]; if ($ped0a6251) { if (is_array($ped0a6251)) { $pc37695cb = count($ped0a6251); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v87a92bb1ad["attributes"][] = array( "name" => $ped0a6251[$v43dd7d0051], "primary_key" => $pef349725["table_attr_primary_keys"][$v43dd7d0051], "type" => $pef349725["table_attr_types"][$v43dd7d0051], "length" => $pef349725["table_attr_lengths"][$v43dd7d0051], "null" => $pef349725["table_attr_nulls"][$v43dd7d0051], "unsigned" => $pef349725["table_attr_unsigneds"][$v43dd7d0051], "unique" => $pef349725["table_attr_uniques"][$v43dd7d0051], "auto_increment" => $pef349725["table_attr_auto_increments"][$v43dd7d0051], "default" => $pef349725["table_attr_has_defaults"][$v43dd7d0051] ? $pef349725["table_attr_defaults"][$v43dd7d0051] : null, "extra" => $pef349725["table_attr_extras"][$v43dd7d0051], "charset" => $pef349725["table_attr_charsets"][$v43dd7d0051], "collation" => $pef349725["table_attr_collations"][$v43dd7d0051], "comment" => $pef349725["table_attr_comments"][$v43dd7d0051], ); } } else { $v87a92bb1ad["attributes"][] = array( "name" => $ped0a6251, "primary_key" => $pef349725["table_attr_primary_keys"], "type" => $pef349725["table_attr_types"], "length" => $pef349725["table_attr_lengths"], "null" => $pef349725["table_attr_nulls"], "unsigned" => $pef349725["table_attr_unsigneds"], "unique" => $pef349725["table_attr_uniques"], "auto_increment" => $pef349725["table_attr_auto_increments"], "default" => $pef349725["table_attr_has_defaults"] ? $pef349725["table_attr_defaults"] : null, "extra" => $pef349725["table_attr_extras"], "charset" => $pef349725["table_attr_charsets"], "collation" => $pef349725["table_attr_collations"], "comment" => $pef349725["table_attr_comments"], ); } } $v3c76382d93 .= ($v3c76382d93 ? "\n\n" : "") . $v87e4fe1181->getDropTableStatement($v87a92bb1ad["table_name"], $v87e4fe1181->getOptions()) . ";\n" . $v87e4fe1181->getCreateTableStatement($v87a92bb1ad, $v87e4fe1181->getOptions()) . ";"; } } } return $v3c76382d93; } public function getUpdateTaskDBDiagram($pa0462a8e, $v8ffce2a791, $v5e053dece2 = false) { $v8cd3e3837d = new WorkFlowTasksFileHandler($v5e053dece2); $v8cd3e3837d->init(); $v1d696dbd12 = $v8cd3e3837d->getWorkflowData(); $v87e4fe1181 = $this->getBeanObject($pa0462a8e, $v8ffce2a791); $pac4bc40a = $v87e4fe1181 ? $v87e4fe1181->listTables() : array(); $pd7dcf6a3 = array(); $v16ac35fd79 = count($pac4bc40a); for ($v43dd7d0051 = 0; $v43dd7d0051 < $v16ac35fd79; $v43dd7d0051++) { $pc661dc6b = $pac4bc40a[$v43dd7d0051]; if (!empty($pc661dc6b)) { $ped0a6251 = $v87e4fe1181 ? $v87e4fe1181->listTableFields($pc661dc6b["name"]) : array(); $v571a648e93 = $v87e4fe1181 ? $v87e4fe1181->listForeignKeys($pc661dc6b["name"]) : array(); $pd7dcf6a3[ $pc661dc6b["name"] ] = array($ped0a6251, $v571a648e93, $pc661dc6b); } } return self::getUpdateTaskDBDiagramFromTablesData($pd7dcf6a3, $v1d696dbd12); } public static function getTableTaskRealNameFromTasks($v1d696dbd12, $v8c5df8072b) { if (!array_key_exists($v8c5df8072b, $v1d696dbd12)) foreach ($v1d696dbd12 as $v08ba0e224b => $v7f5911d32d) if (DB::isTheSameStaticTableName($v8c5df8072b, $v08ba0e224b, array("simple_comparison" => true))) return $v08ba0e224b; return $v8c5df8072b; } public static function getUpdateTaskDBDiagramFromTablesData($pd7dcf6a3, $v1d696dbd12 = false) { $pa8a98224 = 10; $v9ee95f7393 = 10; $v29dfbeade3 = array(); $pe65fff24 = array(); if ($v1d696dbd12 && $v1d696dbd12["tasks"]) foreach ($v1d696dbd12["tasks"] as $v8282c7dd58 => $v7f5911d32d) if ($v7f5911d32d["label"]) $pe65fff24[ $v7f5911d32d["label"] ] = $v8282c7dd58; if (isset($v1d696dbd12["containers"])) { $v29dfbeade3["containers"] = $v1d696dbd12["containers"]; } foreach ($pd7dcf6a3 as $pc661dc6b => $v87a92bb1ad) { $ped0a6251 = $v87a92bb1ad[0]; $v571a648e93 = $v87a92bb1ad[1]; $v94c2944e8d = is_array($v87a92bb1ad[2]) ? $v87a92bb1ad[2] : array(); $v08ba0e224b = self::getTableTaskRealNameFromTasks($pe65fff24, $pc661dc6b); $v8282c7dd58 = $pe65fff24[$v08ba0e224b]; if (!empty($v8282c7dd58)) { $pa3858a3f = $v1d696dbd12["tasks"][$v8282c7dd58]["offset_left"]; $pacea7686 = $v1d696dbd12["tasks"][$v8282c7dd58]["offset_top"]; } else { $v9ee95f7393 += 250; if ($v9ee95f7393 > 1200) { $pa8a98224 += 300; $v9ee95f7393 = 10; } $pa3858a3f = $v9ee95f7393; $pacea7686 = $pa8a98224; } $pbb3f6f30 = !empty($v8282c7dd58) ? $v8282c7dd58 : $pc661dc6b; $v29dfbeade3["tasks"][$pbb3f6f30] = array( "label" => $pc661dc6b, "id" => $pbb3f6f30, "type" => self::TASK_TABLE_TYPE, "tag" => self::TASK_TABLE_TAG, "offset_left" => $pa3858a3f, "offset_top" => $pacea7686, "properties" => array( "exits" => array( "layer_exit" => array( "color" => "rgb(0,0,0)", "type" => "Flowchart", "overlay" => "No Arrows", ) ), "table_charset" => $v94c2944e8d["charset"], "table_collation" => $v94c2944e8d["collation"], "table_storage_engine" => $v94c2944e8d["engine"], "table_attr_primary_keys" => array(), "table_attr_names" => array(), "table_attr_types" => array(), "table_attr_lengths" => array(), "table_attr_nulls" => array(), "table_attr_unsigneds" => array(), "table_attr_uniques" => array(), "table_attr_auto_increments" => array(), "table_attr_has_defaults" => array(), "table_attr_defaults" => array(), "table_attr_extras" => array(), "table_attr_charsets" => array(), "table_attr_comments" => array(), ) ); if (is_array($ped0a6251)) { foreach ($ped0a6251 as $v5e45ec9bb9 => $v1b0cfa478b) { if (!empty($v5e45ec9bb9)) { $pd69fb7d0 = $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_names"] ? count($v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_names"]) : 0; $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_primary_keys"][$pd69fb7d0] = !empty($v1b0cfa478b["primary_key"]); $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_names"][$pd69fb7d0] = $v5e45ec9bb9; $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_types"][$pd69fb7d0] = $v1b0cfa478b["type"]; $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_lengths"][$pd69fb7d0] = $v1b0cfa478b["length"]; $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_nulls"][$pd69fb7d0] = !empty($v1b0cfa478b["null"]); $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_unsigneds"][$pd69fb7d0] = !empty($v1b0cfa478b["unsigned"]); $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_uniques"][$pd69fb7d0] = !empty($v1b0cfa478b["unique"]); $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_auto_increments"][$pd69fb7d0] = !empty($v1b0cfa478b["auto_increment"]); $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_has_defaults"][$pd69fb7d0] = isset($v1b0cfa478b["default"]) && empty($v1b0cfa478b["primary_key"]); $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_defaults"][$pd69fb7d0] = $v1b0cfa478b["default"]; $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_extras"][$pd69fb7d0] = $v1b0cfa478b["extra"]; $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_charsets"][$pd69fb7d0] = $v1b0cfa478b["charset"]; $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_collations"][$pd69fb7d0] = $v1b0cfa478b["collation"]; $v29dfbeade3["tasks"][$pbb3f6f30]["properties"]["table_attr_comments"][$pd69fb7d0] = $v1b0cfa478b["comment"]; } } } if (is_array($v571a648e93)) { $peb3c0114 = array(); foreach ($v571a648e93 as $pa7c14731) if (!empty($pa7c14731)) $peb3c0114[ $pa7c14731["parent_table"] ][] = $pa7c14731; foreach ($peb3c0114 as $pfcfb6727 => $v4203214533) { $pef349725 = array(); $v76b16266c9 = "Many To One"; if ($v4203214533) { $v8dc2e79009 = $pd7dcf6a3[$pfcfb6727][0]; $pebfdf883 = 0; $pc37695cb = count($v4203214533); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pb62b9fbc = $v4203214533[$v43dd7d0051]; $pef349725["source_columns"][] = $pb62b9fbc["child_column"]; $pef349725["target_columns"][] = $pb62b9fbc["parent_column"]; $pb692bad2 = $ped0a6251[ $pb62b9fbc["child_column"] ]; if ($pb692bad2 && !empty($pb692bad2["primary_key"])) $pebfdf883++; } if ($pc37695cb == $pebfdf883) $v76b16266c9 = "One To One"; } $v29dfbeade3["tasks"][$pbb3f6f30]["exits"]["layer_exit"][] = array( "task_id" => !empty($pe65fff24[$pfcfb6727]) ? $pe65fff24[$pfcfb6727] : $pfcfb6727, "label" => null, "type" => $pfcfb6727 == $pc661dc6b ? "StateMachine" : "Flowchart", "overlay" => $v76b16266c9, "properties" => $pef349725 ); } } } return $v29dfbeade3; } } ?>
