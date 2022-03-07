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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); include_once $EVC->getUtilPath("WorkFlowDataAccessHandler"); $common_project_name = $EVC->getCommonProjectName(); $modules_path = $EVC->getModulesPath($common_project_name); $object_module_path = $modules_path . "object/"; $user_module_path = $modules_path . "user/"; if (!file_exists($object_module_path) || !file_exists($user_module_path)) die("You must install the 'object' and 'user' module in order to proceed!"); include_once $EVC->getModulePath("object/ObjectUtil", $common_project_name); include_once $EVC->getModulePath("user/UserUtil", $common_project_name); class CMSPresentationUIAutomaticFilesHandler { public static function getTableGroupHtml($v8c5df8072b, $pba9184cd, $v830c74e006, $v64e98269be, $v06d89caf4f, $v09bdc7fd3a, $pec1f6eeb, $v96e120d9d5, $v610214e838, $v3b6d7c67a8 = false) { $v566db8f5a9 = ""; $v31199c28eb = ""; $v6cea3cefa2 = $v3b6d7c67a8 ? WorkFlowDataAccessHandler::getTableFromTables($v3b6d7c67a8, $v8c5df8072b) : null; if ($v3b6d7c67a8 && $v6cea3cefa2) { $v31199c28eb = " with alias: '" . $v6cea3cefa2 . "'"; $v566db8f5a9 = ' table_alias="' . $v6cea3cefa2 . '"'; } $pf8ed4912 = '<div class="table_group" table_name="' . $v8c5df8072b . '"' . $v566db8f5a9 . '>
			<div class="table_header">
				<label>' . ucfirst($v8c5df8072b) . '\'s Table' . $v31199c28eb . '</label>
				<span class="icon maximize" onClick="toggleTablePanel(this)" title="Toggle Properties">Toggle</i></span>
				<span class="icon delete" onClick="removeTablePanel(this)" title="Remove">Remove</span>
			</div>
			<div class="table_panel">'; $pfc2c3a6c = $v64e98269be; $v61fd0490ce = $v64e98269be; $pd69fb7d0 = array_search("setquerydata", $pfc2c3a6c); if ($pd69fb7d0 !== false) unset($pfc2c3a6c[$pd69fb7d0]); $pd69fb7d0 = array_search("getquerydata", $v61fd0490ce); if ($pd69fb7d0 !== false) unset($v61fd0490ce[$pd69fb7d0]); if ($v06d89caf4f) { $pf8ed4912 .= self::getTableUIHtml("Search/Get all table's rows", $v830c74e006, $pfc2c3a6c, "get_all"); $pf8ed4912 .= self::getTableUIHtml("Count all table's items", $v830c74e006, $pfc2c3a6c, "count"); } if ($v06d89caf4f || $v09bdc7fd3a || $v96e120d9d5) $pf8ed4912 .= self::getTableUIHtml("Get a specific table's row", $v830c74e006, $pfc2c3a6c, "get"); if ($pec1f6eeb) $pf8ed4912 .= self::getTableUIHtml("Insert a specific table's row", $v830c74e006, $v61fd0490ce, "insert"); if ($v96e120d9d5) { $pf8ed4912 .= self::getTableUIHtml("Update a specific table's row", $v830c74e006, $v61fd0490ce, "update"); $pf8ed4912 .= self::getTableUIHtml("Update a specific table's row primary key", $v830c74e006, $v61fd0490ce, "update_pks"); $pf8ed4912 .= self::getTableUIHtml("Delete a specific table's row", $v830c74e006, $v61fd0490ce, "delete"); } else if ($v06d89caf4f) { $pf8ed4912 .= self::getTableUIHtml("Update a specific table's row", $v830c74e006, $v61fd0490ce, "update"); $pf8ed4912 .= self::getTableUIHtml("Delete a specific table's row", $v830c74e006, $v61fd0490ce, "delete"); } if ($v610214e838) { $pf8ed4912 .= '
			<div class="table_ui">
				<div class="table_header">
					<label>Foreign Tables</label>
					<span class="icon maximize" onClick="toggleTableUIPanel(this)" title="Toggle Properties">Toggle</span>
					<span class="icon delete" onClick="removeTableUIPanel(this)" title="Remove">Remove</span>
					<span class="icon add" onClick="addForeignTable(this)" title="Add">Add</span>
				</div>
				<div class="table_ui_panel">'; $v571a648e93 = $pba9184cd ? WorkFlowDataAccessHandler::getTableFromTables($pba9184cd, $v8c5df8072b) : null; $v6be2309ce7 = array(); if ($v571a648e93) { $pc37695cb = count($v571a648e93); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pa7c14731 = $v571a648e93[$v43dd7d0051]; $v78108fb2bd = $pa7c14731["child_table"] == $v8c5df8072b ? $pa7c14731["parent_table"] : $pa7c14731["child_table"]; if (!$v6be2309ce7[$v78108fb2bd]) $pf8ed4912 .= self::getForeignTableRowHtml($v8c5df8072b, $v78108fb2bd, $v830c74e006, $v64e98269be, $v3b6d7c67a8); $v6be2309ce7[$v78108fb2bd] = 1; } } $pf8ed4912 .= '</div>
			</div>'; } $pf8ed4912 .= '</div>
		</div>'; return $pf8ed4912; } public static function getForeignTableRowHtml($v8c5df8072b, $v78108fb2bd, $v830c74e006, $v64e98269be, $v3b6d7c67a8 = false) { if ($v3b6d7c67a8 && $v3b6d7c67a8[$v78108fb2bd]) $v31199c28eb = " (with table alias: '" . $v3b6d7c67a8[$v78108fb2bd] . "')"; $pd69fb7d0 = array_search("setquerydata", $v64e98269be); if ($pd69fb7d0 !== false) unset($v64e98269be[$pd69fb7d0]); $pf8ed4912 = self::getTableUIHtml("Get correspondent " . ucfirst($v78108fb2bd) . " items$v31199c28eb", $v830c74e006, $v64e98269be, "relationships", $v78108fb2bd); $pf8ed4912 .= self::getTableUIHtml("Get correspondent " . ucfirst($v78108fb2bd) . " count$v31199c28eb", $v830c74e006, $v64e98269be, "relationships_count", $v78108fb2bd); return $pf8ed4912; } public static function getTableUIHtml($pb8c0935b, $v830c74e006, $v64e98269be, $v3fb9f41470, $pa512b698 = false) { $pf8ed4912 = '
		<div class="table_ui ' . $v3fb9f41470 . '">
			<div class="table_header">
				<label>' . $pb8c0935b . '</label>
				<span class="icon maximize" onClick="toggleTableUIPanel(this)" title="Toggle Properties">Toggle</span>
				<span class="icon delete" onClick="removeTableUIPanel(this)" title="Remove">Remove</span>
			</div>
			<div class="selected_task_properties table_ui_panel" type="' . $v3fb9f41470 . '" relationship_table="' . $pa512b698 . '">
				<div class="brokers_layer_type">
					<label>Brokers Layer Type:</label>
					<select	name="brokers_layer_type" onChange="onChangeBrokersLayerType(this)">'; if (in_array("callbusinesslogic", $v64e98269be)) $pf8ed4912 .= '
					<option value="callbusinesslogic">Business Logic Brokers</option>'; if (in_array("callibatisquery", $v64e98269be)) $pf8ed4912 .= '
						<option value="callibatisquery">Ibatis Brokers</option>'; if (in_array("callhibernatemethod", $v64e98269be)) $pf8ed4912 .= '
						<option value="callhibernatemethod">Hibernate Brokers</option>'; if (in_array("getquerydata", $v64e98269be)) $pf8ed4912 .= '
						<option value="getquerydata">Get SQL Brokers</option>'; if (in_array("setquerydata", $v64e98269be)) $pf8ed4912 .= '
						<option value="setquerydata">Set SQL Brokers</option>'; $pf8ed4912 .= '
					</select>
				</div>
				' . $v830c74e006 . '
			</div>
		</div>'; return $pf8ed4912; } public static function getFormSettingsBlockCode($v30d38c7973, $v5d3813882f = null) { $v067674f4e4 = '<?php
	$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

	$block_settings[$block_id] = array('; if ($v30d38c7973) { $pc3756cad = $v5d3813882f ? $v5d3813882f["form_settings_php_codes_list"] : null; if ($v30d38c7973["actions"]) $v067674f4e4 .= '
		"actions" => ' . self::mb3e475c52684($v30d38c7973["actions"], "\t\t\t") . ','; if ($v30d38c7973["css"]) $v067674f4e4 .= '
		"css" => ' . self::getCodeAttributeValueConfigured($v30d38c7973["css"]) . ','; if ($v30d38c7973["js"]) { $v40b70e70c3 = self::getCodeAttributeValueConfigured($v30d38c7973["js"]); $v40b70e70c3 = self::mca0a23095e56($v40b70e70c3, $pc3756cad); $v067674f4e4 .= '
		"js" => ' . $v40b70e70c3 . ','; } } $v067674f4e4 .= '
	);

	$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("form", $block_id, $block_settings[$block_id]);
	?>'; return $v067674f4e4; } private static function mca0a23095e56($v67db1bd535, $pc3756cad) { if ($pc3756cad && $v67db1bd535[0] == '"' && substr($v67db1bd535, -1) == '"') { foreach ($pc3756cad as $pda53b20a) if ($pda53b20a) { $v391cc249fc = addcslashes($pda53b20a, "\\'\""); $pac65f06f = 0; while (preg_match("/" . preg_quote($v391cc249fc) . "/", $v67db1bd535, $pbae7526c, PREG_OFFSET_CAPTURE, $pac65f06f)) { $pac65f06f = $pbae7526c[0][1]; $v7e4b517c18 = $pac65f06f - 100 > 0 ? $pac65f06f - 100 : 0; $v0911c6122e = $pac65f06f - $v7e4b517c18; $v08d1f6acf0 = substr($v67db1bd535, $v7e4b517c18, $v0911c6122e); $v4cae07428a = $pac65f06f + strlen($v391cc249fc); if (!preg_match("/<ptl:\w\s+$/", $v08d1f6acf0)) $v67db1bd535 = substr($v67db1bd535, 0, $pac65f06f) . '" . (' . $pda53b20a . ') . "' . substr($v67db1bd535, $v4cae07428a); $pac65f06f = $v4cae07428a; } } } return $v67db1bd535; } private static function mb3e475c52684($v55bd236ac1, $pdcf670f6 = "") { $v067674f4e4 = "array("; foreach ($v55bd236ac1 as $v1b5ae9c139) { switch ($v1b5ae9c139["action_type"]) { case "loop": case "group": if ($v1b5ae9c139["action_value"] && $v1b5ae9c139["action_value"]["actions"]) { $pcf8113cc = 'array('; foreach ($v1b5ae9c139["action_value"] as $pe5c5e2fe => $v956913c90f) { $pcf8113cc .= "\n$pdcf670f6\t\t" . (is_numeric($pe5c5e2fe) ? $pe5c5e2fe : '"' . $pe5c5e2fe . '"') . " => "; if (is_array($v956913c90f) && $v956913c90f && $pe5c5e2fe == "actions") $pcf8113cc .= self::mb3e475c52684($v956913c90f, "$pdcf670f6\t\t\t"); else $pcf8113cc .= self::getCodeAttributeValueConfigured($v956913c90f, "$pdcf670f6\t\t\t"); $pcf8113cc .= ","; } $pcf8113cc .= "\n$pdcf670f6\t)"; } else $pcf8113cc = self::getCodeAttributeValueConfigured($v1b5ae9c139["action_value"], $pdcf670f6); break; case "callbusinesslogic": case "callibatisquery": case "callhibernatemethod": case "getquerydata": case "setquerydata": if (is_array($v1b5ae9c139["action_value"])) { $pcf8113cc = 'array('; $v4d036a1415 = array("brokers_layer_type", "result_var_type", "parameters_type", "options_type", "sma_options_type"); foreach ($v1b5ae9c139["action_value"] as $pe5c5e2fe => $v956913c90f) if (!in_array($pe5c5e2fe, $v4d036a1415)) { $pcf8113cc .= "\n$pdcf670f6\t\t" . (is_numeric($pe5c5e2fe) ? $pe5c5e2fe : '"' . $pe5c5e2fe . '"') . " => "; if ((is_array($v956913c90f) || empty($v956913c90f)) && ($pe5c5e2fe == "options" || $pe5c5e2fe == "sma_options" || $pe5c5e2fe == "parameters")) { if (isset($v956913c90f["key"])) $v956913c90f = array($v956913c90f); $pcf8113cc .= trim(WorkFlowTask::getArrayString($v956913c90f, "$pdcf670f6\t\t")); } else if (isset($v1b5ae9c139["action_value"][$pe5c5e2fe . "_type"])) { $v47b2861c43 = $v1b5ae9c139["action_value"][$pe5c5e2fe . "_type"] == "array"; if ($v47b2861c43 && is_array($v956913c90f)) { if (isset($v956913c90f["key"])) $v956913c90f = array($v956913c90f); $pcf8113cc .= trim(WorkFlowTask::getArrayString($v956913c90f, "$pdcf670f6\t\t")); } else $pcf8113cc .= strlen($v956913c90f) ? CMSPresentationLayerHandler::getArgumentCode($v956913c90f, $v1b5ae9c139["action_value"][$pe5c5e2fe . "_type"]) : '""'; $v4d036a1415[] = $pe5c5e2fe . "_type"; } else $pcf8113cc .= self::getCodeAttributeValueConfigured($v956913c90f, "$pdcf670f6\t\t"); $pcf8113cc .= ","; } $pcf8113cc .= "\n$pdcf670f6\t)"; } else $pcf8113cc = self::getCodeAttributeValueConfigured($v1b5ae9c139["action_value"], $pdcf670f6); break; default: $pcf8113cc = self::getCodeAttributeValueConfigured($v1b5ae9c139["action_value"], $pdcf670f6); } $v067674f4e4 .= '
' . $pdcf670f6 . 'array(
' . "$pdcf670f6\t" . '"result_var_name" => ' . self::getCodeAttributeValueConfigured($v1b5ae9c139["result_var_name"], $pdcf670f6) . ',
' . "$pdcf670f6\t" . '"action_type" => ' . self::getCodeAttributeValueConfigured($v1b5ae9c139["action_type"], $pdcf670f6) . ',
' . "$pdcf670f6\t" . '"condition_type" => ' . self::getCodeAttributeValueConfigured($v1b5ae9c139["condition_type"], $pdcf670f6) . ',
' . "$pdcf670f6\t" . '"condition_value" => ' . self::getCodeAttributeValueConfigured($v1b5ae9c139["condition_value"], $pdcf670f6) . ',
' . "$pdcf670f6\t" . '"action_value" => ' . $pcf8113cc . '
' . "$pdcf670f6" . '),'; } $v067674f4e4 .= ($v067674f4e4 == "array(" ? "" : "\n" . substr($pdcf670f6, 0, -1)) . ")"; return $v067674f4e4; } public static function getCodeAttributeValueConfigured($v956913c90f, $v1ee199febb = "") { if (is_array($v956913c90f)) { $v067674f4e4 = 'array('; foreach ($v956913c90f as $pe5c5e2fe => $v2a500a7080) $v067674f4e4 .= '
	' . $v1ee199febb . "\t" . (is_numeric($pe5c5e2fe) ? $pe5c5e2fe : '"' . $pe5c5e2fe . '"') . ' => ' . self::getCodeAttributeValueConfigured($v2a500a7080, $v1ee199febb . "\t") . ','; $v067674f4e4 .= '
	' . $v1ee199febb . ')'; return $v067674f4e4; } $v3fb9f41470 = CMSPresentationLayerHandler::getValueType($v956913c90f, array("non_set_type" => "string", "empty_string_type" => "string")); if (($v3fb9f41470 == "" || $v3fb9f41470 == "string") && substr($v956913c90f, 0, 4) == '"" .' && substr($v956913c90f, -4) == '. ""') return trim(substr($v956913c90f, 4, -4)); return CMSPresentationLayerHandler::getArgumentCode($v956913c90f, $v3fb9f41470); } public static function saveBlockCode($v188b4f5fa6, &$v29fec2ceaa, $v6d17a5248e, $pc4aa460d, &$v806a006822) { if (!$v29fec2ceaa) return false; if (!$pc4aa460d) CMSPresentationLayerHandler::configureUniqueFileId($v29fec2ceaa, $v188b4f5fa6->getBlocksPath(), "." . $v188b4f5fa6->getPresentationLayer()->getPresentationFileExtension()); return self::saveFileCode($v188b4f5fa6->getBlockPath($v29fec2ceaa), $v6d17a5248e, $pc4aa460d, $v806a006822); } public static function getEntityCode($v57a4d641c3) { $pf2e4821f = $v57a4d641c3["regions_blocks"]; if ($pf2e4821f) { $pc06f1034 = $v57a4d641c3["includes"]; $v9e3513bc0e = $v57a4d641c3["template_params"]; $pe7333513 = $v57a4d641c3["template"]; $pfefc55de = '<?php '; if ($pc06f1034) { $pfefc55de .= '
//Includes'; foreach ($pc06f1034 as $pc24afc88) { $pbdb96933 = $pc24afc88; $pee6ea13c = false; if (is_array($pc24afc88)) { $pbdb96933 = $pc24afc88["path"]; $pee6ea13c = $pc24afc88["once"]; } if (trim($pbdb96933)) $pfefc55de .= '
include' . ($pee6ea13c ? '_once' : '') . ' ' . $pbdb96933 . ';'; } $pfefc55de .= '
'; } if ($pe7333513) $pfefc55de .= '
//Template
$EVC->setTemplate("' . $pe7333513 . '");
'; if ($v9e3513bc0e) { $pfefc55de .= '
//Template params:'; foreach ($v9e3513bc0e as $v0506beb79c => $v01644b5abd) { $v5e813b295b = $v67db1bd535 = ""; if (is_array($v01644b5abd)) { $v5e813b295b = $v01644b5abd["name"]; $v67db1bd535 = $v01644b5abd["value"]; } else if (!is_numeric($v0506beb79c)) { $v5e813b295b = $v0506beb79c; $v67db1bd535 = $v01644b5abd; } if ($v5e813b295b && strlen($v67db1bd535)) $pfefc55de .= '
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("' . $v5e813b295b . '", "' . $v67db1bd535 . '");
'; } } $pfefc55de .= '
//Regions-Blocks:'; foreach ($pf2e4821f as $v1758c645b6) { $v9b9b8653bc = "Content"; $peebaaf55 = $v1758c645b6; $v93756c94b3 = null; $v6a9bacaee4 = ""; if (is_array($v1758c645b6)) { $v9b9b8653bc = $v1758c645b6["region"]; $peebaaf55 = $v1758c645b6["block"]; $v93756c94b3 = $v1758c645b6["project"]; $v6a9bacaee4 = $v93756c94b3 ? ', "' . $v93756c94b3 . '"' : ''; } if ($v9b9b8653bc && $peebaaf55) $pfefc55de .= '
$block_local_variables = array();
$EVC->getCMSLayer()->getCMSJoinPointLayer()->resetRegionBlockJoinPoints("' . $v9b9b8653bc . '", "' . $peebaaf55 . '");
$EVC->getCMSLayer()->getCMSTemplateLayer()->addRegionBlock("' . $v9b9b8653bc . '", "' . $peebaaf55 . '"' . $v6a9bacaee4 . ');
include $EVC->getBlockPath("' . $peebaaf55 . '"' . $v6a9bacaee4 . ');
'; } $pfefc55de = trim($pfefc55de) . '
?>'; } return $pfefc55de; } public static function saveEntityCode($v188b4f5fa6, &$v657ab10c9c, $pfefc55de, $pc4aa460d, &$v806a006822) { if (!$v657ab10c9c) return false; if (!$pc4aa460d) CMSPresentationLayerHandler::configureUniqueFileId($v657ab10c9c, $v188b4f5fa6->getEntitiesPath(), "." . $v188b4f5fa6->getPresentationLayer()->getPresentationFileExtension()); return self::saveFileCode($v188b4f5fa6->getEntityPath($v657ab10c9c), $pfefc55de, $pc4aa460d, $v806a006822); } public static function createAndSaveEntityCode($v188b4f5fa6, &$v657ab10c9c, $v57a4d641c3, $pc4aa460d, &$v806a006822) { $pfefc55de = self::getEntityCode($v57a4d641c3); return self::saveEntityCode($v188b4f5fa6, $v657ab10c9c, $pfefc55de, $pc4aa460d, $v806a006822); } public static function saveFileCode($pf3dc0762, $v067674f4e4, $pc4aa460d, &$v806a006822) { $v9a84a79e2e = dirname($pf3dc0762); if (!is_dir($v9a84a79e2e)) @mkdir($v9a84a79e2e, 0755, true); if (!$pc4aa460d) CMSPresentationLayerHandler::configureUniqueFileId($pf3dc0762); $v5c1c342594 = file_put_contents($pf3dc0762, $v067674f4e4) !== false && !empty($v067674f4e4); $v806a006822[$pf3dc0762] = $v5c1c342594; return $v5c1c342594; } public static function getAvailableUserTypes($v188b4f5fa6) { $pc4223ce1 = $v188b4f5fa6->getPresentationLayer()->getBrokers(); $pf72c1d58 = UserUtil::getAllUserTypes($pc4223ce1); $v8653b2d314 = array(); $pc37695cb = $pf72c1d58 ? count($pf72c1d58) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v8653b2d314[ $pf72c1d58[$v43dd7d0051]["user_type_id"] ] = $pf72c1d58[$v43dd7d0051]["name"]; return $v8653b2d314; } public static function getAvailableActivities($v188b4f5fa6) { $pc4223ce1 = $v188b4f5fa6->getPresentationLayer()->getBrokers(); $pf72c1d58 = UserUtil::getAllActivities($pc4223ce1); $v8653b2d314 = array(); $pc37695cb = $pf72c1d58 ? count($pf72c1d58) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v8653b2d314[ $pf72c1d58[$v43dd7d0051]["activity_id"] ] = $pf72c1d58[$v43dd7d0051]["name"]; return $v8653b2d314; } public static function reinsertReservedActivities($v188b4f5fa6) { $pc4223ce1 = $v188b4f5fa6->getPresentationLayer()->getBrokers(); return UserUtil::reinsertReservedActivities($pc4223ce1); } public static function getActivityIdByName($v188b4f5fa6, $v5e813b295b, $v543a4abeae = true) { $pc4223ce1 = $v188b4f5fa6->getPresentationLayer()->getBrokers(); $v5e813b295b = strtolower($v5e813b295b); $ped0a6251 = array("name" => $v5e813b295b); $v97835052d7 = UserUtil::getActivitiesByConditions($pc4223ce1, $ped0a6251, null); $v2a62bd1b82 = $v97835052d7 ? $v97835052d7[0]["activity_id"] : null; if (!$v2a62bd1b82 && $v543a4abeae) { $pb5db5a52 = UserUtil::getReservedActivities(); if (in_array($v5e813b295b, $pb5db5a52)) $v2a62bd1b82 = UserUtil::reinsertReservedActivities($pc4223ce1) ? array_search($v5e813b295b, $pb5db5a52) : null; else $v2a62bd1b82 = UserUtil::insertActivity($pc4223ce1, $ped0a6251); } return $v2a62bd1b82; } public static function getObjectTypeIdByName($v188b4f5fa6, $v5e813b295b, $v543a4abeae = true) { $pc4223ce1 = $v188b4f5fa6->getPresentationLayer()->getBrokers(); $v5e813b295b = strtolower($v5e813b295b); $ped0a6251 = array("name" => $v5e813b295b); $v7953a8daf6 = ObjectUtil::getObjectTypesByConditions($pc4223ce1, $ped0a6251, null); $v0a035c60aa = $v7953a8daf6 ? $v7953a8daf6[0]["object_type_id"] : null; if (!$v0a035c60aa && $v543a4abeae) { $v79b1b7af96 = ObjectUtil::getReservedObjectTypes(); if (in_array($v5e813b295b, $v79b1b7af96)) $v0a035c60aa = ObjectUtil::reinsertReservedObjectTypes($pc4223ce1) ? array_search($v5e813b295b, $v79b1b7af96) : null; else $v0a035c60aa = ObjectUtil::insertObjectType($pc4223ce1, $ped0a6251); } return $v0a035c60aa; } public static function deleteUserTypeActivityObjects($v188b4f5fa6, $v2a62bd1b82, $v0a035c60aa, $v3fab52f440) { $pc4223ce1 = $v188b4f5fa6->getPresentationLayer()->getBrokers(); return UserUtil::deleteUserTypeActivityObjectsByActivityIdAndObjectId($pc4223ce1, $v2a62bd1b82, $v0a035c60aa, $v3fab52f440); } public static function insertUserTypeActivityObject($v188b4f5fa6, $v6bbd1726b0, $v2a62bd1b82, $v0a035c60aa, $v3fab52f440) { $pc4223ce1 = $v188b4f5fa6->getPresentationLayer()->getBrokers(); $v539082ff30 = array("user_type_id" => $v6bbd1726b0, "activity_id" => $v2a62bd1b82, "object_type_id" => $v0a035c60aa, "object_id" => $v3fab52f440); return UserUtil::insertUserTypeActivityObject($pc4223ce1, $v539082ff30); } public static function getUserTypeActivityObjectsByObject($v188b4f5fa6, $v0a035c60aa, $v3fab52f440) { $pc4223ce1 = $v188b4f5fa6->getPresentationLayer()->getBrokers(); $paf1bc6f6 = array("object_type_id" => $v0a035c60aa, "object_id" => $v3fab52f440); return UserUtil::getUserTypeActivityObjectsByConditions($pc4223ce1, $paf1bc6f6, null); } public static function removeAllUserTypeActivitySessionsCache($v188b4f5fa6) { $EVC = $v188b4f5fa6; include_once $v188b4f5fa6->getModulePath("user/UserSessionActivitiesHandler", $v188b4f5fa6->getCommonProjectName()); $v5a7fa070ca = new \UserSessionActivitiesHandler($v188b4f5fa6); return $v5a7fa070ca->removeAllCache(); } } ?>
