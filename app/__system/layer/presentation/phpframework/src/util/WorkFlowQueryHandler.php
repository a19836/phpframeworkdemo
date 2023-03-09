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

include_once $EVC->getUtilPath("WorkFlowDataAccessHandler"); include_once $EVC->getUtilPath("WorkFlowBrokersSelectedDBVarsHandler"); class WorkFlowQueryHandler { const TASK_QUERY_TYPE = "d0d250c0"; const TASK_QUERY_TAG = "query"; private $pcfdeae4e; private $pcd2aca48; private $v00161f0c07; private $v9b98e0e818; private $v5e788adf08; private $pc66a0204; private $v5a331eab7e; private $pd76831fc; private $v5e4089f2c3; private $v9d043dd3df; private $v197630b0cc; private $v1167f1d261; public function __construct($pcfdeae4e, $pcd2aca48, $v00161f0c07, $v9b98e0e818, $v5e788adf08, $pc66a0204, $v5a331eab7e, $pd76831fc, $v5e4089f2c3, $v9d043dd3df, $v197630b0cc, $v1167f1d261) { $this->pcfdeae4e = $pcfdeae4e; $this->pcd2aca48 = $pcd2aca48; $this->v00161f0c07 = $v00161f0c07; $this->v9b98e0e818 = $v9b98e0e818; $this->v5e788adf08 = $v5e788adf08; $this->pc66a0204 = $pc66a0204; $this->v5a331eab7e = $v5a331eab7e; $this->pd76831fc = $pd76831fc; $this->v5e4089f2c3 = $v5e4089f2c3; $this->v9d043dd3df = $v9d043dd3df; $this->v197630b0cc = $v197630b0cc; $this->v1167f1d261 = $v1167f1d261; } public static function getSelectedDBBrokersDriversTablesAndAttributes($pe54bee2d, $v5e053dece2, $pdb9e96e6, $v875c215016 = false, $v1bdf90f0ab = false, $pb154d332 = false, $v1eb9193558 = null) { $pc4223ce1 = $pe54bee2d->getBrokers(); $v9b98e0e818 = array(); $v5e788adf08 = $pc66a0204 = $pf0f5722a = $v4fda6fa047 = $v5a331eab7e = $pd76831fc = $v9d043dd3df = null; if ($pc4223ce1) { foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) { $v9b98e0e818[$v2b2cf4c0eb] = $pd922c2f7->getDBDriversName(); if ($pb154d332 && $v1eb9193558) $v1eb9193558->filterLayerBrokersDBDriversNamesFromLayoutName($pe54bee2d, $v9b98e0e818[$v2b2cf4c0eb], $pb154d332); if ($v1bdf90f0ab && empty($v5e788adf08) && $v9b98e0e818[$v1bdf90f0ab]) { $v5e788adf08 = $v2b2cf4c0eb; $pc66a0204 = $v9b98e0e818[$v1bdf90f0ab][0]; } else if (empty($pf0f5722a) && $v9b98e0e818[$v2b2cf4c0eb]) { $pf0f5722a = $v2b2cf4c0eb; $v4fda6fa047 = $v9b98e0e818[$v2b2cf4c0eb][0]; } } if (empty($v5e788adf08) && $pf0f5722a) { $v5e788adf08 = $pf0f5722a; $pc66a0204 = $v4fda6fa047; } } if ($v5e788adf08 && $pc66a0204) { $v5e053dece2 = WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($pdb9e96e6, "db_diagram", $pc66a0204); $v5a331eab7e = file_exists($v5e053dece2) ? "diagram" : "db"; if ($v5a331eab7e == "diagram") { $v094d1a2778 = new WorkFlowDataAccessHandler(); $v094d1a2778->setTasksFilePath($v5e053dece2); $v1d696dbd12 = $v094d1a2778->getTasks(); $v5e4089f2c3 = array_keys($v1d696dbd12["tasks"]); $pd76831fc = !empty($v875c215016) ? $v875c215016 : $v5e4089f2c3[0]; $v9d043dd3df = $v1d696dbd12["tasks"][$pd76831fc]; $v9d043dd3df = $v9d043dd3df["properties"]["table_attr_names"]; } else { $v1502dfe376 = $pe54bee2d->getBroker($v5e788adf08)->getFunction("listTables", null, array("db_driver" => $pc66a0204)); $v5e4089f2c3 = array(); if ($v1502dfe376) foreach ($v1502dfe376 as $pa9848378) $v5e4089f2c3[] = $pa9848378["name"]; if ($v875c215016) { $v9a4d5f4ed3 = $pe54bee2d->getBroker($v5e788adf08)->getFunction("isTableInNamesList", array($v5e4089f2c3, $v875c215016), array("db_driver" => $pc66a0204)); $pd76831fc = $v9a4d5f4ed3 ? $v875c215016 : $v5e4089f2c3[0]; } else $pd76831fc = $v5e4089f2c3[0]; $v9d043dd3df = $pe54bee2d->getBroker($v5e788adf08)->getFunction("listTableFields", $pd76831fc, array("db_driver" => $pc66a0204)); $v9d043dd3df = array_keys($v9d043dd3df); } } return array( "brokers" => $pc4223ce1, "db_drivers" => $v9b98e0e818, "selected_db_broker" => $v5e788adf08, "selected_db_driver" => $pc66a0204, "selected_type" => $v5a331eab7e, "selected_table" => $pd76831fc, "selected_tables_name" => $v5e4089f2c3, "selected_table_attrs" => $v9d043dd3df, ); } public function getDataAccessObjHtml($v69f1629ff2, $pab85d90e = false, $v30857f7eca = null) { $pf8ed4912 = '
			<div class="relationships">
				<div class="description">
					"Relationships" are links or dependecies between objects or tables.
				</div>
				<span class="icon update_automatically" onClick="updateDataAccessObjectRelationshipsAutomatically(this)" title="Create Relationships Automatically">Update Automatically</span>
				
				<div class="relationships_tabs">
					 <ul class="tabs tabs_transparent">
						<li><a href="#relationships_tabs-1">' . ($pab85d90e ? 'Foreign Tables' : 'Rules') . '</a></li>
						' . ($pab85d90e ? '' : '<li><a href="#relationships_tabs-2">Parameters Maps</a></li>') . '
						<li><a href="#relationships_tabs-3">Results Maps</a></li>
						<li><a href="#relationships_tabs-4">Includes</a></li>
					</ul>'; $pf8ed4912 .= '
					<div id="relationships_tabs-1">
						<div class="description">
							' . ($pab85d90e ? 'The purpose for the "Foreign Tables" is to create relationships between objects, so we can call specific methods according with these relationships. Here is an example:<br/>
							- if the current object is the CAR object<br/>
							- and cars have doors<br/>
							...You should create the relationship between CAR and DOOR, in order to get the doors for specific CAR\'s objects, or the correspondent doors\' details or other information related this relationship...' : 'The purpose for the "Rules" is to create specific queries, not included by the native methods...') . '
						</div>
						<span class="icon add add_relationship" onClick="addRelationshipBlock(this, ' . ($pab85d90e ? '1' : '0') . ')">Add</span>
						<div class="rels">'; if ($v69f1629ff2) { foreach ($v69f1629ff2 as $pa1c701b0 => $v987a981e39) { if ($pa1c701b0 == "one_to_one" || $pa1c701b0 == "one_to_many" || $pa1c701b0 == "many_to_one" || $pa1c701b0 == "many_to_many" || $pa1c701b0 == "insert" || $pa1c701b0 == "update" || $pa1c701b0 == "delete" || $pa1c701b0 == "select" || $pa1c701b0 == "procedure") { $pf8ed4912 .= $this->getQueriesBlockHtml($v987a981e39, $pab85d90e, $pa1c701b0, true, $v30857f7eca); } } } $pf8ed4912 .= '	
						</div>
					</div>'; if (!$pab85d90e) { $pf8ed4912 .= '
						<div id="relationships_tabs-2">
							<div class="parameters_maps">
								<div class="description">
									The purpose of a "Parameters Map/Class" is to convert and validate an input data object. This is:<br/>
									- let\'s say that a specific method receives an argument, which is an object with a "name", "age" and "country" attributes. Something like: {"name" => "...", "age" => "...", "country" => "..."}. <br/>
									- but the real input object passed to this method only contains the attributes "n", "a" and "c". Something like: {"n" => "David", "a" => "35", "c" => "Portugal"}. <br/>
									<br/>
									So we can create a "Parameters Map/Class" to convert this input object to the right one, transforming the attribute "n" to "name", "a" to "age" and "c" to "country". This is, to something like: {"name" => "David", "age" => "35", "country" => "Portugal"}<br/>
									Additionally we can refer that the "age" attribute is a numeric field, and the system will check and convert the correspondent value to that type.
								</div>
								<span class="icon add add_parameter" onClick="addParameterMap(this)" title="Add new Map">Add</span>
								<div class="parameters">'; if ($v69f1629ff2["parameter_map"]) { $pc37695cb = count($v69f1629ff2["parameter_map"]); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $pf8ed4912 .= $this->getParameterMapHTML("map", $v69f1629ff2["parameter_map"][$v43dd7d0051], $this->v197630b0cc, $this->v1167f1d261, true); } $pf8ed4912 .= '
								</div>
							</div>
						</div>'; } $pf8ed4912 .= '
					<div id="relationships_tabs-3">
						<div class="results_maps">
							<div class="description">
								The purpose of a "Result Map/Class" is to convert and validate an output data object. This is:<br/>
								- let\'s say that a specific method returns a result, which is an object with a "name", "age" and "country" attributes. Something like: {"name" => "David", "age" => "35", "country" => "Portugal"}. <br/>
								- but the real output object that we would like to return should contain the attributes "n", "a" and "c". Something like: {"n" => "...", "a" => "...", "c" => "..."}. <br/>
								<br/>
								So we can create a "Result Map/Class" to convert this result to the right output object, transforming the attribute "name" to "n", "age" to "a" and "country" to "c". This is, to something like: {"n" => "David", "a" => "35", "c" => "Portugal"}<br/>
								Additionally we can refer that the "a" attribute is a numeric field, and the system will check and convert the correspondent value to that type.
							</div>
							<span class="icon add add_result" onClick="addResultMap(this)" title="Add new Map">Add</span>
							<div class="results">'; if ($v69f1629ff2["result_map"]) { $pc37695cb = count($v69f1629ff2["result_map"]); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $pf8ed4912 .= $this->getResultMapHTML("map", $v69f1629ff2["result_map"][$v43dd7d0051], $this->v197630b0cc, $this->v1167f1d261, true); } $pf8ed4912 .= '
							</div>
						</div>
					</div>'; $pf8ed4912 .= '
					<div id="relationships_tabs-4">' . $this->getInludeHTMLBlock($v69f1629ff2["import"]) . '</div>'; $pf8ed4912 .= '	
				</div>
			</div>'; return $pf8ed4912; } public function getHeader() { return '
			<script src="' . $this->v00161f0c07 . 'vendor/jquery/js/jquery.md5.js"></script>
			<script src="' . $this->v00161f0c07 . 'vendor/acecodeeditor/src/ace.js"></script>
			<script src="' . $this->v00161f0c07 . 'vendor/acecodeeditor/src/ext-language_tools.js"></script>
		'; } public function getDataAccessJavascript($v8ffce2a791, $pa0462a8e, $pa32be502, $v8773b3a63a, $pa9694aaa, $pec7b777d) { $pf8ed4912 = '<script>
			var create_hbn_object_relationships_automatically_url = "' . $this->pcd2aca48 . 'phpframework/dataaccess/create_hbn_obj_relationships_automatically?bean_name=' . $v8ffce2a791 . '&bean_file_name=' . $pa0462a8e . '";
			var get_map_fields_url = "' . $this->pcd2aca48 . 'phpframework/dataaccess/get_broker_table_hbn_map?bean_name=' . $v8ffce2a791 . '&bean_file_name=' . $pa0462a8e . '&item_type=' . $v8773b3a63a . '";
			var get_available_map_ids_url = "' . $this->pcd2aca48 . 'phpframework/dataaccess/get_available_hbn_maps?bean_name=' . $v8ffce2a791 . '&bean_file_name=' . $pa0462a8e . '&path=' . $pa32be502 . '&obj=' . $pa9694aaa . '&query_type=#query_type#&map_type=#map_type#";
			var get_sql_from_query_obj = "' . $this->pcd2aca48 . 'phpframework/dataaccess/get_sql_from_query_obj?bean_name=' . $v8ffce2a791 . '&bean_file_name=' . $pa0462a8e . '&path=' . $pa32be502 . '&item_type=' . $v8773b3a63a . '&db_broker=#db_broker#&db_driver=#db_driver#";
			var get_query_obj_from_sql = "' . $this->pcd2aca48 . 'phpframework/dataaccess/get_query_obj_from_sql?bean_name=' . $v8ffce2a791 . '&bean_file_name=' . $pa0462a8e . '&path=' . $pa32be502 . '&item_type=' . $v8773b3a63a . '&db_broker=#db_broker#&db_driver=#db_driver#";
			
			var relative_file_path = "' . $pa32be502 . '";
			relative_file_path = relative_file_path.substr(0, 1) == "/" ? relative_file_path.substr(1, relative_file_path.length - 1) : relative_file_path;
			relative_file_path = relative_file_path.substr(relative_file_path.length - 1, 1) == "/" ? relative_file_path.substr(0, relative_file_path.length - 1) : relative_file_path;
			'; if ($v8ffce2a791) $pf8ed4912 .= '
			main_layers_properties.' . $v8ffce2a791 . ' = {ui: {
				folder: {
					get_sub_files_url: "' . $pec7b777d . '",
				},
				cms_common: {
					get_sub_files_url: "' . $pec7b777d . '",
				},
				cms_module: {
					get_sub_files_url: "' . $pec7b777d . '",
				},
				cms_program: {
					get_sub_files_url: "' . $pec7b777d . '",
				},
				cms_resource: {
					get_sub_files_url: "' . $pec7b777d . '",
				},
				file: {
					attributes: {
						file_path: "#path#"
					}
				},
				import: {
					attributes: {
						file_path: "#path#"
					}
				},
				referenced_folder: {
					get_sub_files_url: "' . $pec7b777d . '",
				},
			}};
			'; $v912c18a5b6 = $this->pcd2aca48 . 'admin/get_sub_files?item_type=dao&path=#path#'; $pf8ed4912 .= '
			main_layers_properties.dao = {ui: {
				folder: {
					get_sub_files_url: "' . $v912c18a5b6 . '",
				},
				cms_common: {
					get_sub_files_url: "' . $v912c18a5b6 . '",
				},
				cms_module: {
					get_sub_files_url: "' . $v912c18a5b6 . '",
				},
				cms_program: {
					get_sub_files_url: "' . $v912c18a5b6 . '",
				},
				cms_resource: {
					get_sub_files_url: "' . $v912c18a5b6 . '",
				},
				hibernatemodel: {
					attributes: {
						file_path: "#path#"
					}
				},
				objtype: {
					attributes: {
						file_path: "#path#"
					}
				},
			}};


			var new_include_html = \'' . str_replace("'", "\\'", str_replace("\n", "", $this->getInludeHTML())) .'\';
			var new_parameter_html = \'' . str_replace("'", "\\'", str_replace("\n", "", $this->getParameterHTML($this->v197630b0cc, $this->v1167f1d261))) .'\';
			var new_result_html = \'' . str_replace("'", "\\'", str_replace("\n", "", $this->getResultHTML($this->v197630b0cc, $this->v1167f1d261))) .'\';
			var new_parameter_map_html = \'' . str_replace("'", "\\'", str_replace("\n", "", $this->getParameterMapHTML("map", false, $this->v197630b0cc, $this->v1167f1d261, true))) . '\';
			var new_result_map_html = \'' . str_replace("'", "\\'", str_replace("\n", "", $this->getResultMapHTML("map", false, $this->v197630b0cc, $this->v1167f1d261, true))) . '\';

			var new_relationship_block_html = \'' . str_replace("'", "\\'", str_replace("\n", "", $this->getQueryBlockHtml(true))) . '\';
			var new_relationship_query_block_html = \'' . str_replace("'", "\\'", str_replace("\n", "", $this->getQueryBlockHtml())) . '\';
			var new_relationship_attribute1_html = \'' . str_replace("'", "\\'", str_replace("\n", "", self::getQueryAttributeHtml1())) . '\';
			var new_relationship_attribute2_html = \'' . str_replace("'", "\\'", str_replace("\n", "", self::getQueryAttributeHtml2())) . '\';
			var new_relationship_key_html = \'' . str_replace("'", "\\'", str_replace("\n", "", self::getQueryKeyHtml())) . '\';
			var new_relationship_condition1_html = \'' . str_replace("'", "\\'", str_replace("\n", "", self::getQueryConditionHtml1())) . '\';
			var new_relationship_condition2_html = \'' . str_replace("'", "\\'", str_replace("\n", "", self::getQueryConditionHtml2())) . '\';
			var new_relationship_group_by_html = \'' . str_replace("'", "\\'", str_replace("\n", "", self::getQueryGroupByHtml())) . '\';
			var new_relationship_sort_html = \'' . str_replace("'", "\\'", str_replace("\n", "", self::getQuerySortHtml())) . '\';
					
			var task_table_type_id = "' . self::TASK_QUERY_TYPE . '";
			var tasks_settings = ' . $this->pcfdeae4e->getTasksSettingsObj() . ';
			
			DBQueryTaskPropertyObj.on_click_checkbox = onClickQueryAtributeCheckBox;
			DBQueryTaskPropertyObj.on_delete_table = onDeleteQueryTable;
			DBQueryTaskPropertyObj.on_complete_table_label = prepareTableLabelSettings;
			DBQueryTaskPropertyObj.on_complete_connection_properties = prepareTablesRelationshipKeys;
			DBQueryTaskPropertyObj.on_complete_select_start_task = prepareTableStartTask;
			
			' . WorkFlowBrokersSelectedDBVarsHandler::printSelectedDBVarsJavascriptCode($this->pcd2aca48, $v8ffce2a791, $pa0462a8e, array( "dal_broker" => $this->v5e788adf08, "db_driver" => $this->pc66a0204, "type" => $this->v5a331eab7e, "db_table" => $this->pd76831fc, "db_brokers_drivers" => $this->v9b98e0e818 )); if ($this->v5e788adf08 && $this->pc66a0204 && $this->v5a331eab7e && $this->v5e4089f2c3) { $pc37695cb = count($this->v5e4089f2c3); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { if ($this->v5e4089f2c3[$v43dd7d0051] == $this->pd76831fc) { $pf8ed4912 .= 'db_brokers_drivers_tables_attributes["' . $this->v5e788adf08 . '"]["' . $this->pc66a0204 . '"]["' . $this->v5a331eab7e . '"]["' . $this->pd76831fc . '"] = ' . json_encode($this->v9d043dd3df) . ';'; } else { $pf8ed4912 .= 'db_brokers_drivers_tables_attributes["' . $this->v5e788adf08 . '"]["' . $this->pc66a0204 . '"]["' . $this->v5a331eab7e . '"]["' . $this->v5e4089f2c3[$v43dd7d0051] . '"] = [];'; } } } $pf8ed4912 .= '</script>'; return $pf8ed4912; } public function getGlobalTaskFlowChar() { $pf8ed4912 = '
		<div id="taskflowchart_global">
			<div class="tasks_menu scroll">
				' . $this->pcfdeae4e->printTasksList() . '
			</div>
			<div class="tasks_properties hidden">
				' . $this->pcfdeae4e->printTasksProperties() . '
			</div>

			<div class="connections_properties hidden">
				' . $this->pcfdeae4e->printConnectionsProperties() . '
			</div>
		</div>'; return $pf8ed4912; } public function getChooseQueryTableOrAttributeHtml($v1cbfbb49c5 = false, $v8aefdcedb9 = "MyFancyPopup") { $pf8ed4912 = '<div id="' . $v1cbfbb49c5 . '" class="myfancypopup choose_table_or_attribute">
				<div class="title">DB Table/Attribute Selection</div>
				<div class="contents">
					<div class="db_broker' . (count($this->v9b98e0e818) == 1 ? " single_broker" : "") . '">
						<label>DB Broker:</label>
						<select onChange="updateDBDrivers(this)">
							<option></option>'; foreach ($this->v9b98e0e818 as $pab752e34 => $v84bde5f80a) { $pf8ed4912 .= '			<option ' . ($this->v5e788adf08 == $pab752e34 ? 'selected' : '') . '>' . $pab752e34 . '</option>'; } $pf8ed4912 .= '			</select>
					</div>
					<div class="db_driver" ' . ($this->v5e788adf08 ? '' : 'style="display:none"') . '>
						<label>DB Driver:</label>
						<select onChange="updateDBTables(this)">
							<option></option>'; if ($this->v9b98e0e818[$this->v5e788adf08]) { $pc37695cb = count($this->v9b98e0e818[$this->v5e788adf08]); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $pf8ed4912 .= '			<option ' . ($this->pc66a0204 == $this->v9b98e0e818[$this->v5e788adf08][$v43dd7d0051] ? 'selected' : '') . '>' . $this->v9b98e0e818[$this->v5e788adf08][$v43dd7d0051] . '</option>'; } $pf8ed4912 .= '			</select>
					</div>
					<div class="type" ' . ($this->v5e788adf08 ? '' : 'style="display:none"') . '>
						<label>Type:</label>
						<select onChange="updateDBTables(this)">
							<option value="db" ' . ($this->v5a331eab7e == "db" ? 'selected' : '') . '>From DB Server</option>
							<option value="diagram" ' . ($this->v5a331eab7e == "diagram" ? 'selected' : '') . '>From DB Diagram</option>
						</select>
					</div>
					<div class="db_table" ' . ($this->pc66a0204 ? '' : 'style="display:none"') . '>
						<label>DB Table:</label>
						<select onChange="updateDBAttributes(this)">'; if ($this->v5e4089f2c3) { $pc37695cb = count($this->v5e4089f2c3); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $pf8ed4912 .= '			<option ' . ($this->v5e4089f2c3[$v43dd7d0051] == $this->pd76831fc ? 'selected' : '') . '>' . $this->v5e4089f2c3[$v43dd7d0051] . '</option>'; } $pf8ed4912 .= '			</select>
					</div>
					<div class="db_attribute" ' . ($this->pd76831fc ? '' : 'style="display:none"') . '>
						<label>DB Attribute:</label>
						<select onChange="syncChooseTableOrAttributePopups(this)">'; if ($this->v9d043dd3df) { $pc37695cb = count($this->v9d043dd3df); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $pf8ed4912 .= '			<option>' . $this->v9d043dd3df[$v43dd7d0051] . '</option>'; } $pf8ed4912 .= '			</select>
					</div>
				</div>
				<div class="button">
					<input type="button" value="update" onClick="' . $v8aefdcedb9 . '.settings.updateFunction(this)" />
				</div>
			</div>'; return $pf8ed4912; } public function getChooseIncludeFromFileManagerHtml($v94a9c171e3, $v1cbfbb49c5 = false) { $pf8ed4912 = '<div id="' . $v1cbfbb49c5 . '" class="myfancypopup">
			<ul class="mytree">
				<li>
					<label>Root</label>
					<ul url="' . str_replace("#path#", "", $v94a9c171e3) . '"></ul>
				</li>
			</ul>
			<div class="button">
				<input type="button" value="update" onClick="MyFancyPopup.settings.updateFunction(this)" />
			</div>
		</div>'; return $pf8ed4912; } public function getChooseDAOObjectFromFileManagerHtml($v1cbfbb49c5 = false) { $pf8ed4912 = '<div id="' . $v1cbfbb49c5 . '" class="myfancypopup">
			<ul class="mytree">
				<li>
					<label>External Lib - "dao" Folder</label>
					<ul url="' . $this->pcd2aca48 . 'admin/get_sub_files?item_type=dao&path="></ul>
				</li>
			</ul>
			<div class="button">
				<input type="button" value="update" onClick="MyFancyPopup.settings.updateFunction(this)" />
			</div>
		</div>'; return $pf8ed4912; } public function getChooseAvailableMapIdHtml($v1cbfbb49c5 = false) { $pf8ed4912 = '<div id="' . $v1cbfbb49c5 . '" class="myfancypopup">
			<div class="title">Map Selection</div>
			<div class="contents">
				<div class="map">
					<label>Available Maps:</label>
					<select></select>
					<span class="icon refresh" onClick="updateAvailableMapsOptions()">Refresh</span>
				</div>
			</div>
			<div class="button">
				<input type="button" value="update" onClick="MyFancyPopup.settings.updateFunction(this)" />
			</div>
		</div>'; return $pf8ed4912; } public function getInludeHTML($pc24afc88 = false, $v8b6fd90a28 = false) { return '
			<div class="include">
				<label class="include_path">Path:</label>
				<input class="include_path" type="text" value="' . $pc24afc88 . '" />
				<label class="is_include_relative">Relative:</label>
				<input class="is_include_relative" type="checkbox" value="1" ' . ($v8b6fd90a28 ? 'checked="checked"' : '') . ' />
				<span class="icon search" onClick="getIncludePathFromFileManager(this, \'input.include_path\')" title="Get file from File Manager">Search</span>
				<span class="icon delete" onClick="removeInclude(this);" title="Delete">Remove</span>
			</div>'; } public function getInludeHTMLBlock($v280898d986) { $pf8ed4912 .= '
		<div class="includes">
			<div class="description">
				"includes" are files that you can add and that contains specific configurations, this is, an include file can have parameteres or result maps, queries, relationships, etc...
			</div>
			<span class="icon add" onClick="addNewInclude(this)" title="Add new include">Add</span>
			<div class="fields">'; if ($v280898d986) { $pc37695cb = count($v280898d986); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pc24afc88 = $v280898d986[$v43dd7d0051]["value"]; $v8b6fd90a28 = $v280898d986[$v43dd7d0051]["@"]["relative"]; $pf8ed4912 .= $this->getInludeHTML($pc24afc88, $v8b6fd90a28); } } else $pf8ed4912 .= '<div class="no_includes">There are includes...</div>'; $pf8ed4912 .= '
			</div>
		</div>'; return $pf8ed4912; } public function getMapSelectOptions($pe3573e1b, $pb92c3c9b, $v4b559a4220 = "org.phpframework.object.php.Primitive", $v0a938c6790 = true, $v4cad1832a9 = true) { $pb92c3c9b = ObjTypeHandler::convertSimpleTypeIntoCompositeType($pb92c3c9b, $v4b559a4220); $pf8ed4912 = $v0a938c6790 ? '<option></option>' : ''; $pf8ed4912 .= '<optgroup label="Primitive Types">'; if ($pe3573e1b) foreach ($pe3573e1b as $pb13127fc => $v945fda93f2) { if (strpos($pb13127fc, $v4b559a4220) === 0) { $pf8ed4912 .= '<option value="' . $pb13127fc . '" ' . ($pb92c3c9b == $pb13127fc ? 'selected' : '') . '>' . $v945fda93f2 . '</option>'; } } $pf8ed4912 .= '</optgroup>
			<optgroup label="Composite Types">'; if ($pe3573e1b) foreach ($pe3573e1b as $pb13127fc => $v945fda93f2) { if (strpos($pb13127fc, $v4b559a4220) === false) { $pf8ed4912 .= '<option value="' . $pb13127fc . '" ' . ($pb92c3c9b == $pb13127fc ? 'selected' : '') . '>' . $v945fda93f2 . '</option>'; } } $pf8ed4912 .= '</optgroup>
			<optgroup label="Other Types">'; if ($v4cad1832a9 && $pb92c3c9b && !isset($pe3573e1b[$pb92c3c9b])) { $pbd1bc7b0 = strrpos($pb92c3c9b, "."); $v02a69d4e0f = $pbd1bc7b0 !== false ? substr($pb92c3c9b, $pbd1bc7b0 + 1) : $pb92c3c9b; $pf8ed4912 .= '<option value="' . $pb92c3c9b . '" selected>' . $v02a69d4e0f . '</option>'; } $pf8ed4912 .= '</optgroup>'; return $pf8ed4912; } public function getParameterHTML($v197630b0cc, $v1167f1d261, $v8dca298c48 = false, $v5a911d8233 = false, $v1c301b29c5 = false, $v10353796a8 = false, $v18d8ec0406 = false) { $pf8ed4912 = '
		<tr class="parameter field">
			<td class="input_name">
				<input type="text" value="' . $v8dca298c48 . '" />
			</td>
			<td class="input_type">
				<select>
		 			' . $this->getMapSelectOptions($v197630b0cc, $v5a911d8233) . '
				</select>
				<span class="icon search" onClick="geMapPHPTypeFromFileManager(this, \'select\')" title="Get type from File Manager">Search</span>
			</td>
			<td class="output_name">
				<input type="text" value="' . $v1c301b29c5 . '" />
				<span class="icon search" onClick="getTableAttributeFromDB(this, \'input\')" title="Get attribute from DB">Search</span>
			</td>
			<td class="output_type">
				<select>
					' . $this->getMapSelectOptions($v1167f1d261, $v10353796a8, "org.phpframework.object.db.DBPrimitive") . '
				</select>
			</td>
			<td class="mandatory">
				<input type="checkbox" value="1" ' . ($v18d8ec0406 ? 'checked="checked"' : '') . ' />
			</td>
			<td class="icon_cell"><span class="icon delete" onClick="$(this).parent().parent().remove();" title="Delete">Remove</span></td>
		</tr>'; return $pf8ed4912; } public function getParameterMapHTML($paeae9fca, $v2967293505, $v197630b0cc, $v1167f1d261, $pb6233a29 = false) { $pf8ed4912 = '
		<div class="map" ' . ($paeae9fca == "map" ? 'style="display:block;"' : 'style="display:none;"') . '>
			<label>Parameter Map:</label>
			<span class="icon delete" onClick="$(this).parent().remove();" title="Delete" ' . ($pb6233a29 ? '' : 'style="display:none;"') . '>Remove</span>
			<span class="icon update_automatically" onClick="createParameterOrResultMapAutomatically(this, \'parameter\')" title="Create Map Automatically">Update Automatically</span>
			<div class="map_id">
				<label>ID:</label>
				<input type="text" value="' . $v2967293505["@"]["id"] . '" placeHolder="Id/Name" onBlur="validateMapId(this, \'parameter\');" />
			</div>
			<div class="map_class">
				<label>Class:</label>
				<input type="text" value="' . $v2967293505["@"]["class"] . '" />
				<span class="icon search" onClick="getParameterClassFromFileManager(this)">Search</span>
			</div>
			<table>
				<thead class="fields_title">
					<tr>
						<th class="input_name table_header">PHP Attribute Name</th>
						<th class="input_type table_header">PHP Attribute Type</th>
						<th class="output_name table_header">DB Attribute Name</th>
						<th class="output_type table_header">DB Attribute Type</th>
						<th class="mandatory table_header">Mandatory</th>
						<th class="icon_cell"><span class="icon add" onClick="addNewParameter(this)" title="Add">Add</span></th>
					</tr>
				</thead>
				<tbody class="fields">'; $v9367d5be85 = $v2967293505["childs"]["parameter"]; if ($v9367d5be85) { $pc37695cb = count($v9367d5be85); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pc5faab2f = $v9367d5be85[$v43dd7d0051]["@"]; $pf8ed4912 .= $this->getParameterHTML($v197630b0cc, $v1167f1d261, $pc5faab2f["input_name"], $pc5faab2f["input_type"], $pc5faab2f["output_name"], $pc5faab2f["output_type"], $pc5faab2f["mandatory"]); } } else $pf8ed4912 .= $this->getParameterHTML($v197630b0cc, $v1167f1d261); $pf8ed4912 .= '	</tbody>
			</table>
		</div>'; return $pf8ed4912; } public function getResultHTML($v197630b0cc, $v1167f1d261, $v8dca298c48 = false, $v5a911d8233 = false, $v1c301b29c5 = false, $v10353796a8 = false, $v18d8ec0406 = false) { return '
		<tr class="result field">
			<td class="input_name">
				<input type="text" value="' . $v8dca298c48 . '" />
				<span class="icon search" onClick="getTableAttributeFromDB(this, \'input\')" title="Get attribute from DB">Search</span>
			</td>
			<td class="input_type">
				<select>
					' . $this->getMapSelectOptions($v1167f1d261, $v5a911d8233, "org.phpframework.object.db.DBPrimitive") . '
				</select>
			</td>
			<td class="output_name">
				<input type="text" value="' . $v1c301b29c5 . '" />
			</td>
			<td class="output_type">
				<select>
					' . $this->getMapSelectOptions($v197630b0cc, $v10353796a8) . '
				</select>
				<span class="icon search" onClick="geMapPHPTypeFromFileManager(this, \'select\')" title="Get type from File Manager">Search</span>
			</td>
			<td class="mandatory">
				<input type="checkbox" value="1" ' . ($v18d8ec0406 ? 'checked="checked"' : '') . ' />
			</td>
			<td class="icon_cell"><span class="icon delete" onClick="$(this).parent().parent().remove();" title="Delete">Remove</span></td>
		</tr>'; } public function getResultMapHTML($pbd9f98de, $pce128343, $v197630b0cc, $v1167f1d261, $pb6233a29 = false) { $pf8ed4912 = '
		<div class="map" ' . ($pbd9f98de == "map" ? 'style="display:block;"' : 'style="display:none;"') . '>
			<label>Result Map:</label>
			<span class="icon delete" onClick="$(this).parent().remove();" title="Delete" ' . ($pb6233a29 ? '' : 'style="display:none;"') . '>Remove</span>
			<span class="icon update_automatically" onClick="createParameterOrResultMapAutomatically(this, \'result\')" title="Create Map Automatically">Update Automatically</span>
			<div class="map_id">
				<label>ID:</label>
				<input type="text" value="' . $pce128343["@"]["id"] . '" placeHolder="Id/Name" onBlur="validateMapId(this, \'result\');" />
			</div>
			<div class="map_class">
				<label>Class:</label>
				<input type="text" value="' . $pce128343["@"]["class"] . '" />
				<span class="icon search" onClick="getParameterClassFromFileManager(this)">Search</span>
			</div>
			<table>
				<thead class="fields_title">
					<tr>
						<th class="input_name table_header">DB Attribute Name</th>
						<th class="input_type table_header">DB Attribute Type</th>
						<th class="output_name table_header">PHP Attribute Name</th>
						<th class="output_type table_header">PHP Attribute Type</th>
						<th class="mandatory table_header">Mandatory</th>
						<th class="icon_cell"><span class="icon add" onClick="addNewResult(this)" title="Add">Add</span></th>
					</tr>
				</thead>
				<tbody class="fields">'; $pee4c7870 = $pce128343["childs"]["result"]; if ($pee4c7870) { $pc37695cb = count($pee4c7870); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v9ad1385268 = $pee4c7870[$v43dd7d0051]["@"]; $pf8ed4912 .= $this->getResultHTML($v197630b0cc, $v1167f1d261, $v9ad1385268["input_name"], $v9ad1385268["input_type"], $v9ad1385268["output_name"], $v9ad1385268["output_type"], $v9ad1385268["mandatory"]); } } else $pf8ed4912 .= $this->getResultHTML($v197630b0cc, $v1167f1d261); $pf8ed4912 .= '	</tbody>
			</table>
		</div>'; return $pf8ed4912; } public function getQueriesBlockHtml($v987a981e39, $pab85d90e = false, $pa1c701b0 = false, $v483eef20da = true, $v30857f7eca = null) { $pf8ed4912 = ""; if ($v987a981e39) { $pc37695cb = count($v987a981e39); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v16eb00c1d7 = $v987a981e39[$v43dd7d0051]; $v1cbfbb49c5 = WorkFlowDataAccessHandler::getNodeValue($v16eb00c1d7, "id"); $v5e813b295b = WorkFlowDataAccessHandler::getNodeValue($v16eb00c1d7, "name"); $v217e7cf3c0 = WorkFlowDataAccessHandler::getNodeValue($v16eb00c1d7, "parameter_class"); $v2967293505 = WorkFlowDataAccessHandler::getNodeValue($v16eb00c1d7, "parameter_map"); $v21ff8db28c = WorkFlowDataAccessHandler::getNodeValue($v16eb00c1d7, "result_class"); $pce128343 = WorkFlowDataAccessHandler::getNodeValue($v16eb00c1d7, "result_map"); $pfdbbc383 = $v16eb00c1d7["childs"]["attribute"]; $v9994512d98 = $v16eb00c1d7["childs"]["key"]; $paf1bc6f6 = $v16eb00c1d7["childs"]["condition"]; $pe4b1434e = $v16eb00c1d7["childs"]["group_by"]; $v04003a4f53 = $v16eb00c1d7["childs"]["sort"]; $v552b831ecd = WorkFlowDataAccessHandler::getNodeValue($v16eb00c1d7, "limit"); $v7e4b517c18 = WorkFlowDataAccessHandler::getNodeValue($v16eb00c1d7, "start"); $v3c76382d93 = $v16eb00c1d7["value"]; $v93ff269092 = rand(0, 1000); $v016220e8f0 = $pab85d90e ? $v5e813b295b : $v1cbfbb49c5; $v539082ff30 = array( "type" => $pa1c701b0, "name" => $v016220e8f0, "parameter_class" => $v217e7cf3c0, "parameter_map" => $v2967293505, "result_class" => $v21ff8db28c, "result_map" => $pce128343, "attributes" => $pfdbbc383, "keys" => $v9994512d98, "conditions" => $paf1bc6f6, "groups_by" => $pe4b1434e, "sorts" => $v04003a4f53, "limit" => $v552b831ecd, "start" => $v7e4b517c18, "sql" => $v3c76382d93 ); $v90746d4d9c = array( "init_ui" => true, "init_workflow" => true, "minimize" => $v483eef20da, ); $v30857f7eca = $v30857f7eca ? array_merge($v90746d4d9c, $v30857f7eca) : $v90746d4d9c; $pbe1051a0 = $this->getQueryBlockHtml($pab85d90e, $v30857f7eca, $v539082ff30); $pbe1051a0 = str_replace("#rand#", $v93ff269092, $pbe1051a0); $pf8ed4912 .= $pbe1051a0; } } return $pf8ed4912; } public function getQueryBlockHtml($pab85d90e = false, $v30857f7eca = false, $v539082ff30 = false) { $v6cf0577ade = $v30857f7eca["init_ui"]; $v483eef20da = $v30857f7eca["minimize"]; $v6d756b21eb = $v30857f7eca["encapsulate_parameter_and_result_settings"]; $pa1c701b0 = $v539082ff30["type"]; $v5e813b295b = $v539082ff30["name"]; $v217e7cf3c0 = $v539082ff30["parameter_class"]; $v2967293505 = $v539082ff30["parameter_map"]; $v21ff8db28c = $v539082ff30["result_class"]; $pce128343 = $v539082ff30["result_map"]; $pfdbbc383 = $v539082ff30["attributes"]; $v9994512d98 = $v539082ff30["keys"]; $paf1bc6f6 = $v539082ff30["conditions"]; $pe4b1434e = $v539082ff30["groups_by"]; $v04003a4f53 = $v539082ff30["sorts"]; $v552b831ecd = $v539082ff30["limit"]; $v7e4b517c18 = $v539082ff30["start"]; $v3c76382d93 = $v539082ff30["sql"]; if ($pab85d90e) { $pba9aec33 = array("one_to_one", "one_to_many", "many_to_one", "many_to_many"); $pcdd6ebdf = true; } else { $pba9aec33 = array("insert", "update", "delete", "select", "procedure"); $pcdd6ebdf = $pa1c701b0 == "select"; } $pf8ed4912 = '
		<div class="relationship">
			<div class="header_buttons">
				<span class="icon delete" onClick="$(this).parent().parent().remove();" title="Remove Query">Remove</span>
				<span class="icon toggle" onClick="toggleQuery(this);" title="Toggle Query">Toggle</span>
			</div>
			<div style="float:none; clear:both;"></div>
			<div class="rel_type">
				<label>Relationship Type:</label>
				<select onChange="updateRelationshipType(this, #rand#);">'; $pc37695cb = count($pba9aec33); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pf8ed4912 .= '	<option ' . ($pba9aec33[$v43dd7d0051] == $pa1c701b0 ? 'selected' : '') . '>' . $pba9aec33[$v43dd7d0051] . '</option>'; } $pf8ed4912 .= '	</select>
			</div>
			<div class="rel_name">
				<label>Name:</label>
				<input type="text" value="' . $v5e813b295b . '" placeHolder="Name" onBlur="validateRelationshipName(this);" />
			</div>
			<div style="float:none; clear:both;"></div>'; if ($v6d756b21eb) $pf8ed4912 .= '
			<div class="settings collapsed">
				<div class="settings_header">
					Main Settings
					<div class="icon maximize" onClick="toggleMainSettingsPanel(this)">Toggle</div>
				</div>'; if (!$pab85d90e) { $pf8ed4912 .= '
			<div class="parameter_class_id">
				<label>Parameters Class Id:</label>
				<input type="text" value="' . $v217e7cf3c0 . '" />
				<span class="icon search" onClick="getParameterClassFromFileManager(this)">Search</span>
			</div>
			<div class="parameter_map_id">
				<label>Parameter Map Id:</label>
				<input type="text" value="' . $v2967293505 . '" />
				<span class="icon search" onClick="getAvailableParameterMap(this, \'' . ($pab85d90e ? 'relationships' : 'queries') . '\')">Search</span>
			</div>'; } $pf8ed4912 .= '
			<div class="result_class_id"' . '>
				<label>Result Class Id:</label>
				<input type="text" value="' . $v21ff8db28c . '" />
				<span class="icon search" onClick="getResultClassFromFileManager(this)">Search</span>
			</div>
			<div class="result_map_id"' . '>
				<label>Result Map Id:</label>
				<input type="text" value="' . $pce128343 . '" />
				<span class="icon search" onClick="getAvailableResultMap(this, \'' . ($pab85d90e ? 'relationships' : 'queries') . '\')">Search</span>
			</div>
			<div style="float:none; clear:both;"></div>
		'; if ($v6d756b21eb) $pf8ed4912 .= '</div>'; $pf8ed4912 .= $this->getQueryHtml($pab85d90e, $pa1c701b0, $pcdd6ebdf, $pfdbbc383, $v9994512d98, $paf1bc6f6, $pe4b1434e, $v04003a4f53, $v552b831ecd, $v7e4b517c18, $v3c76382d93, $v30857f7eca); $pf8ed4912 .= '</div>'; if ($v6cf0577ade && $v483eef20da) { $pf8ed4912 .= '<script>
			$(function () {
				$("#" + jsPlumbWorkFlow_#rand#.jsPlumbTaskFlow.main_tasks_flow_obj_id).parent().parent().parent().parent().parent().parent().children(".header_buttons").children(".minimize").first().click();
			});
			</script>'; } return $pf8ed4912; } public function getQueryHtml($pab85d90e = false, $pa1c701b0 = false, $pcdd6ebdf = true, $pfdbbc383 = false, $v9994512d98 = false, $paf1bc6f6 = false, $pe4b1434e = false, $v04003a4f53 = false, $v552b831ecd = false, $v7e4b517c18 = false, $v3c76382d93 = false, $v30857f7eca = null) { $pf8ed4912 = '
		<div rand_number="#rand#" class="query">
			<ul class="tabs tabs_transparent query_tabs">
				<li class="query_design_tab"><a href="#query_obj_tabs_#rand#-1" onClick="initQueryDesign(this, #rand#)">UI</a></li>
				<li class="query_sql_tab"><a href="#query_obj_tabs_#rand#-2" onClick="initQuerySql(this, #rand#)">SQL</a></li>
			</ul>
					
			<div id="query_obj_tabs_#rand#-1">
				<div class="query_insert_update_delete"' . ($pcdd6ebdf ? ' style="display:none"' : '') . '>
					' . self::getQueryInsertUpdateDeleteHtml($pfdbbc383, $paf1bc6f6) . '
				</div>
				
				<div class="query_select"' . (!$pcdd6ebdf ? ' style="display:none"' : '') . '>
					' . self::getQuerySelectHtml($pab85d90e, $pfdbbc383, $v9994512d98, $paf1bc6f6, $pe4b1434e, $v04003a4f53, $v552b831ecd, $v7e4b517c18, $v30857f7eca) . '
				</div>
			</div>
			
			<div id="query_obj_tabs_#rand#-2" class="sql_text_area">
				<textarea>' . trim(str_replace("\t", "", htmlspecialchars($v3c76382d93, ENT_NOQUOTES))) . '</textarea>
			</div>
		</div>'; return $pf8ed4912; } public function getQueryInsertUpdateDeleteHtml($pfdbbc383 = false, $paf1bc6f6 = false) { $pc661dc6b = $pfdbbc383 ? WorkFlowDataAccessHandler::getNodeValue($pfdbbc383[0], "table") : ""; $pc661dc6b = $pc661dc6b ? $pc661dc6b : ($paf1bc6f6 ? WorkFlowDataAccessHandler::getNodeValue($paf1bc6f6[0], "table") : ""); $pf8ed4912 = '
		<div class="query_table">
			<label>Table:</label>
			<input type="text" value="' . $pc661dc6b .'" onBlur="onBlurQueryInputField(this, #rand#)" />
			<span class="icon search" onClick="getTableFromDB(this, #rand#)">Search</span>	
		</div>
		
		<ul class="tabs tabs_transparent query_insert_update_delete_tabs">
			<li class="query_insert_update_delete_tabs_attributes"><a href="#query_insert_update_delete_tabs_#rand#-1">Attributes</a></li>
			<li class="query_insert_update_delete_tabs_conditions"><a href="#query_insert_update_delete_tabs_#rand#-2">Conditions</a></li>
		</ul> 
			
		<div id="query_insert_update_delete_tabs_#rand#-1" class="attributes query_insert_update_delete_tab">
			<table>
				<thead class="fields_title">
					<tr>
						<th class="column table_header">Column</th>
						<th class="value table_header">Value</th>
						<th class="icon_cell table_header"><span class="icon add" onClick="addQueryAttribute2(this, #rand#)">Add</span></span></th>
					</tr>
				</thead>
				<tbody class="fields">'; if ($pfdbbc383) { $pc37695cb = count($pfdbbc383); for ($v9d27441e80 = 0; $v9d27441e80 < $pc37695cb; $v9d27441e80++) { $v97915b9670 = $pfdbbc383[$v9d27441e80]; if ($v97915b9670["@"] || $v97915b9670["childs"]) { $v9ea12a829c = WorkFlowDataAccessHandler::getNodeValue($v97915b9670, "column"); $v67db1bd535 = WorkFlowDataAccessHandler::getNodeValue($v97915b9670, "value"); $pf8ed4912 .= self::getQueryAttributeHtml2($v9ea12a829c, $v67db1bd535); } } } $pf8ed4912 .= '
				</tbody>
			</table>
		</div>
		
		<div id="query_insert_update_delete_tabs_#rand#-2" class="conditions query_insert_update_delete_tab">
			<table>
				<thead class="fields_title">
					<tr>
						<th class="column table_header">Column</th>
						<th class="operator table_header">Operator</th>
						<th class="value table_header">Value</th>
						<th class="icon_cell table_header"><span class="icon add" onClick="addQueryCondition2(this, #rand#)">Add</span></span></th>
					</tr>
				</thead>
				<tbody class="fields">'; if ($paf1bc6f6) { $pc37695cb = count($paf1bc6f6); for ($v9d27441e80 = 0; $v9d27441e80 < $pc37695cb; $v9d27441e80++) { $v32dd06ab9b = $paf1bc6f6[$v9d27441e80]; if ($v32dd06ab9b["@"] || $v32dd06ab9b["childs"]) { $v9ea12a829c = WorkFlowDataAccessHandler::getNodeValue($v32dd06ab9b, "column"); $v19a7745bc6 = WorkFlowDataAccessHandler::getNodeValue($v32dd06ab9b, "operator"); $v67db1bd535 = WorkFlowDataAccessHandler::getNodeValue($v32dd06ab9b, "value"); $pf8ed4912 .= self::getQueryConditionHtml2($v9ea12a829c, $v19a7745bc6, $v67db1bd535); } } } $pf8ed4912 .= '
				</tbody>
			</table>
		</div>'; return $pf8ed4912; } public function getQuerySelectHtml($pab85d90e = false, $pfdbbc383 = false, $v9994512d98 = false, $paf1bc6f6 = false, $pe4b1434e = false, $v04003a4f53 = false, $v552b831ecd = false, $v7e4b517c18 = false, $v30857f7eca = null) { $pf8ed4912 = '
			<div class="query_ui">
			' . $this->getQueryWorkFlow($pab85d90e, $pfdbbc383, $v9994512d98, $paf1bc6f6, $v30857f7eca) . '
			</div>
			<div class="query_settings">
				<ul class="tabs tabs_transparent query_settings_tabs">
					<li class="query_settings_tabs_attributes"><a href="#query_settings_tabs_#rand#-1">Attributes</a></li>
					<li class="query_settings_tabs_keys"><a href="#query_settings_tabs_#rand#-2">Keys</a></li>
					<li class="query_settings_tabs_conditions"><a href="#query_settings_tabs_#rand#-3">Conditions</a></li>
					<li class="query_settings_tabs_group_by"><a href="#query_settings_tabs_#rand#-4">Group By</a></li>
					<li class="query_settings_tabs_sorting"><a href="#query_settings_tabs_#rand#-5">Sorting</a></li>
					<li class="query_settings_tabs_limit"><a href="#query_settings_tabs_#rand#-6">Limit/Start</a></li>
				</ul> 
			
				' . $this->getChooseQueryTableOrAttributeHtml(false, "jsPlumbWorkFlow_#rand#.getMyFancyPopupObj()") . '
			
				<span class="icon view advanced_query_settings" onClick="showOrHideExtraQuerySettings(this, #rand#)">Toggle Advanced Settings</span>
				<div id="query_settings_tabs_#rand#-1" class="attributes query_settings_tab">
					<table>
						<thead class="fields_title">
							<tr>
								<th class="table table_header">Table</th>
								<th class="column table_header">Column</th>
								<th class="name table_header">Name</th>
								<th class="icon_cell table_header"><span class="icon add" onClick="addQueryAttribute1(this, #rand#)">Add</span></th>
							</tr>
						</thead>
						<tbody class="fields">'; if ($pfdbbc383) { $pc37695cb = count($pfdbbc383); for ($v9d27441e80 = 0; $v9d27441e80 < $pc37695cb; $v9d27441e80++) { $v97915b9670 = $pfdbbc383[$v9d27441e80]; if ($v97915b9670["@"] || $v97915b9670["childs"]) { $pc661dc6b = WorkFlowDataAccessHandler::getNodeValue($v97915b9670, "table"); $v9ea12a829c = WorkFlowDataAccessHandler::getNodeValue($v97915b9670, "column"); $v5e813b295b = WorkFlowDataAccessHandler::getNodeValue($v97915b9670, "name"); $pc661dc6b = empty($pc661dc6b) && !empty($v9ea12a829c) ? $this->pd76831fc : $pc661dc6b; $pf8ed4912 .= self::getQueryAttributeHtml1($pc661dc6b, $v9ea12a829c, $v5e813b295b); } } } $pf8ed4912 .= '
						</tbody>
					</table>
				</div>

				<div id="query_settings_tabs_#rand#-2" class="keys query_settings_tab">
					<table>
						<thead class="fields_title">
							<tr>
								<th class="ptable table_header">Primary Table</th>
								<th class="pcolumn table_header">Primary Column</th>
								<th class="operator table_header">Operator</th>
								<th class="ftable table_header">Foreign Table</th>
								<th class="fcolumn table_header">Foreign Column</th>
								<th class="value table_header">Value</th>
								<th class="join table_header">Join</th>
								<th class="icon_cell table_header"><span class="icon add" onClick="addQueryKey(this, #rand#)">Add</span></th>
							</tr>
						</thead>
						<tbody class="fields">'; if ($v9994512d98) { $pc37695cb = count($v9994512d98); for ($v9d27441e80 = 0; $v9d27441e80 < $pc37695cb; $v9d27441e80++) { $pbfa01ed1 = $v9994512d98[$v9d27441e80]; if ($pbfa01ed1["@"] || $pbfa01ed1["childs"]) { $v3b625ac0a5 = WorkFlowDataAccessHandler::getNodeValue($pbfa01ed1, "ptable"); $pa3c827a4 = WorkFlowDataAccessHandler::getNodeValue($pbfa01ed1, "pcolumn"); $v294f67133d = WorkFlowDataAccessHandler::getNodeValue($pbfa01ed1, "ftable"); $v8903e4e7c4 = WorkFlowDataAccessHandler::getNodeValue($pbfa01ed1, "fcolumn"); $v67db1bd535 = WorkFlowDataAccessHandler::getNodeValue($pbfa01ed1, "value"); $v3c581c912b = WorkFlowDataAccessHandler::getNodeValue($pbfa01ed1, "join"); $v19a7745bc6 = WorkFlowDataAccessHandler::getNodeValue($pbfa01ed1, "operator"); $v3b625ac0a5 = empty($v3b625ac0a5) && !empty($pa3c827a4) ? $this->pd76831fc : $v3b625ac0a5; $v294f67133d = empty($v294f67133d) && !empty($v8903e4e7c4) ? $this->pd76831fc : $v294f67133d; $pf8ed4912 .= self::getQueryKeyHtml($v3b625ac0a5, $pa3c827a4, $v294f67133d, $v8903e4e7c4, $v67db1bd535, $v3c581c912b, $v19a7745bc6); } } } $pf8ed4912 .= '
						</tbody>
					</table>
				</div>

				<div id="query_settings_tabs_#rand#-3" class="conditions query_settings_tab">
					<table>
						<thead class="fields_title">
							<tr>
								<th class="table table_header">Table</th>
								<th class="column table_header">Column</th>
								<th class="operator table_header">Operator</th>
								<th class="value table_header">Value</th>
								<th class="icon_cell table_header"><span class="icon add" onClick="addQueryCondition1(this, #rand#)">Add</span></th>
							</tr>
						</thead>
						<tbody class="fields">'; if ($paf1bc6f6) { $pc37695cb = count($paf1bc6f6); for ($v9d27441e80 = 0; $v9d27441e80 < $pc37695cb; $v9d27441e80++) { $v32dd06ab9b = $paf1bc6f6[$v9d27441e80]; if ($v32dd06ab9b["@"] || $v32dd06ab9b["childs"]) { $pc661dc6b = WorkFlowDataAccessHandler::getNodeValue($v32dd06ab9b, "table"); $v9ea12a829c = WorkFlowDataAccessHandler::getNodeValue($v32dd06ab9b, "column"); $v19a7745bc6 = WorkFlowDataAccessHandler::getNodeValue($v32dd06ab9b, "operator"); $v67db1bd535 = WorkFlowDataAccessHandler::getNodeValue($v32dd06ab9b, "value"); $pc661dc6b = empty($pc661dc6b) && !empty($v9ea12a829c) ? $this->pd76831fc : $pc661dc6b; $pf8ed4912 .= self::getQueryConditionHtml1($pc661dc6b, $v9ea12a829c, $v19a7745bc6, $v67db1bd535); } } } $pf8ed4912 .= '
						</tbody>
					</table>
				</div>

				<div id="query_settings_tabs_#rand#-4" class="groups_by query_settings_tab">
					<table>
						<thead class="fields_title">
							<tr>
								<th class="table table_header">Table</th>
								<th class="column table_header">Column</th>
								<th class="icon_cell table_header"><span class="icon add" onClick="addQueryGroupBy(this, #rand#)">Add</span></th>
							</tr>
						</thead>
						<tbody class="fields">'; if ($pe4b1434e) { $pc37695cb = count($pe4b1434e); for ($v9d27441e80 = 0; $v9d27441e80 < $pc37695cb; $v9d27441e80++) { $v1c5ac544d0 = $pe4b1434e[$v9d27441e80]; if ($v1c5ac544d0["@"] || $v1c5ac544d0["childs"]) { $pc661dc6b = WorkFlowDataAccessHandler::getNodeValue($v1c5ac544d0, "table"); $v9ea12a829c = WorkFlowDataAccessHandler::getNodeValue($v1c5ac544d0, "column"); $pc661dc6b = empty($pc661dc6b) && !empty($v9ea12a829c) ? $this->pd76831fc : $pc661dc6b; $pf8ed4912 .= self::getQueryGroupByHtml($pc661dc6b, $v9ea12a829c); } } } $pf8ed4912 .= '
						</tbody>
					</table>
				</div>

				<div id="query_settings_tabs_#rand#-5" class="sorts query_settings_tab">
					<table>
						<thead class="fields_title">
							<tr>
								<th class="table table_header">Table</th>
								<th class="column table_header">Column</th>
								<th class="order table_header">Order</th>
								<th class="icon_cell table_header"><span class="icon add" onClick="addQuerySort(this, #rand#)">Add</span></th>
							</tr>
						</thead>
						<tbody class="fields">'; if ($v04003a4f53) { $pc37695cb = count($v04003a4f53); for ($v9d27441e80 = 0; $v9d27441e80 < $pc37695cb; $v9d27441e80++) { $pdab26aff = $v04003a4f53[$v9d27441e80]; if ($pdab26aff["@"] || $pdab26aff["childs"]) { $pc661dc6b = WorkFlowDataAccessHandler::getNodeValue($pdab26aff, "table"); $v9ea12a829c = WorkFlowDataAccessHandler::getNodeValue($pdab26aff, "column"); $pc06af91c = strtolower( WorkFlowDataAccessHandler::getNodeValue($pdab26aff, "order") ); $pc661dc6b = empty($pc661dc6b) && !empty($v9ea12a829c) ? $this->pd76831fc : $pc661dc6b; $pf8ed4912 .= self::getQuerySortHtml($pc661dc6b, $v9ea12a829c, $pc06af91c); } } } $pf8ed4912 .= '
						</tbody>
					</table>
				</div>

				<div id="query_settings_tabs_#rand#-6" class="limit_start query_settings_tab">
					<div class="sub_limit_start">
						<div class="start">
							<label>Start:</label>
							<input type="text" value="' . $v7e4b517c18 . '" onBlur="onBlurQueryInputField(this, #rand#)" />
						</div>
						<div class="limit">
							<label>Limit:</label>
							<input type="text" value="' . $v552b831ecd . '" onBlur="onBlurQueryInputField(this, #rand#)" />
						</div>
					</div>
				</div>
			</div>'; return $pf8ed4912; } public function getQueryWorkFlow($pab85d90e = false, $pfdbbc383 = false, $v9994512d98 = false, $paf1bc6f6 = false, $v30857f7eca = null) { $v6cf0577ade = $v30857f7eca["init_ui"]; $v1e8fddc00c = $v30857f7eca["init_workflow"]; $v243e50bc1d = array( "Add new Table" => array( "class" => "add_new_table", "html" => '<a class="icon" onClick="return addNewTask(#rand#);">Add new Table</a>' ), "Update Tables' Attributes" => array( "class" => "update_tables_attributes", "html" => '<a class="icon" onClick="return updateQueryDBBroker(#rand#, false);">Update Tables\' Attributes</a>' ), "Toggle UI" => array( "class" => "toggle_ui", "html" => '<a class="icon" onClick="return showOrHideQueryUI(this, #rand#);">Toggle UI</a>' ), "Toggle Settings" => array( "class" => "toggle_settings", "html" => '<a class="icon" onClick="return showOrHideQuerySettings(this, #rand#);">Toggle Settings</a>' ), ); $this->pcfdeae4e->setMenus($v243e50bc1d); $pf8ed4912 = '<div id="taskflowchart_#rand#" class="taskflowchart">
			' . $this->pcfdeae4e->getMenusContent() . '
			<div class="tasks_flow" sync_ui_and_settings="' . ($pab85d90e ? 0 : 1) . '">
				' . $this->getChooseQueryTableOrAttributeHtml(false, "jsPlumbWorkFlow_#rand#.getMyFancyPopupObj()") . '
			</div>
		</div>'; if ($v6cf0577ade) { $pf8ed4912 .= '
			<script>
				;(function() {
					addTaskFlowChart(#rand#, ' . ($v1e8fddc00c ? "true" : "false") . ');
					
					' . ($v1e8fddc00c ? '
					setTimeout(function() {//wait until jsPlumbWorkFlow is initialized.
						updateQueryUITableFromQuerySettings(#rand#);
					}, 1000);' : '') . '
				})();
			</script>'; } return $pf8ed4912; } public static function getQueryAttributeHtml1($pc661dc6b = false, $v9ea12a829c = false, $v5e813b295b = false) { return '
		<tr class="field">
			<td class="table">
				<input type="text" value="' . $pc661dc6b .'" onFocus="onFocusTableField(this)" onBlur="onBlurQueryTableField(this, #rand#)" />
				<span class="icon search" onClick="getQueryTableFromDB(this, #rand#)">Search</span>
			</td>
			<td class="column">
				<input type="text" value="' . $v9ea12a829c .'" onFocus="onFocusAttributeField(this)" onBlur="onBlurQueryAttributeField(this, #rand#)" />
				<span class="icon search" onClick="getQueryTableAttributeFromDB(this, \'input\', #rand#)">Search</span>
			</td>
			<td class="name">
				<input type="text" value="' . $v5e813b295b .'" onBlur="onBlurQueryInputField(this, #rand#)" />
			</td>
			<td class="icon_cell table_header"><span class="icon delete" onClick="deleteQueryAttribute(this, #rand#);">Remove</span></td>
		</tr>'; } public static function getQueryAttributeHtml2($v9ea12a829c = false, $v67db1bd535 = false) { return '
		<tr class="field">
			<td class="column">
				<input type="text" value="' . $v9ea12a829c .'" onBlur="onBlurQueryInputField(this, #rand#)" />
				<span class="icon search" onClick="getTableAttributeFromDB(this, \'input\', #rand#)">Search</span>
			</td>
			<td class="value">
				<input type="text" value="' . $v67db1bd535 .'" onBlur="onBlurQueryInputField(this, #rand#)" />
			</td>
			<td class="icon_cell table_header"><span class="icon delete" onClick="deleteQueryField(this, #rand#);">Remove</span></td>
		</tr>'; } public static function getQueryKeyHtml($v3b625ac0a5 = false, $pa3c827a4 = false, $v294f67133d = false, $v8903e4e7c4 = false, $v67db1bd535 = false, $v3c581c912b = false, $v19a7745bc6 = false) { $v3cbbdf9379 = array("inner", "left", "right"); $pa75e95f5 = array("=", "!=", ">", ">=", "<=", "like", "not like", "is", "not is", "in"); $v19a7745bc6 = strtolower($v19a7745bc6); $v3c581c912b = strtolower($v3c581c912b); if ($v19a7745bc6 == "in") { $v67db1bd535 = trim($v67db1bd535); $v67db1bd535 = substr($v67db1bd535, 0, 1) == "(" && substr($v67db1bd535, strlen($v67db1bd535) - 1) == ")" ? substr($v67db1bd535, 1, strlen($v67db1bd535) - 2) : $v67db1bd535; } else if (!$v19a7745bc6 && ($pa3c827a4 || $v8903e4e7c4)) { $v19a7745bc6 = "="; } $pf8ed4912 = '
			<tr class="field">
				<td class="ptable">
					<input type="text" value="' . $v3b625ac0a5 .'" onFocus="onFocusQueryKey(this);" onBlur="onBlurQueryKey(this, #rand#);" />
					<span class="icon search" onClick="getQueryTableFromDB(this, #rand#)">Search</span>
				</td>
				<td class="pcolumn">
					<input type="text" value="' . $pa3c827a4 .'" onFocus="onFocusQueryKey(this);" onBlur="onBlurQueryKey(this, #rand#);" />
					<span class="icon search" onClick="getQueryTableAttributeFromDB(this, \'input\', #rand#)">Search</span>
				</td>
				<td class="operator">
					<select onFocus="onFocusQueryKey(this);" onChange="onBlurQueryKey(this, #rand#);">
						<option></option>'; if ($pa75e95f5) { $pc37695cb = count($pa75e95f5); for ($pc5166886 = 0; $pc5166886 < $pc37695cb; $pc5166886++) $pf8ed4912 .= '	<option ' . ($v19a7745bc6 == $pa75e95f5[$pc5166886] ? 'selected' : '') . '>' . $pa75e95f5[$pc5166886] . '</option>'; } $pf8ed4912 .= '		</select>
				</td>
				<td class="ftable">
					<input type="text" value="' . $v294f67133d .'" onFocus="onFocusQueryKey(this);" onBlur="onBlurQueryKey(this, #rand#);" />
					<span class="icon search" onClick="getQueryTableFromDB(this, #rand#)">Search</span>
				</td>
				<td class="fcolumn">
					<input type="text" value="' . $v8903e4e7c4 .'" onFocus="onFocusQueryKey(this);" onBlur="onBlurQueryKey(this, #rand#);" />
					<span class="icon search" onClick="getQueryTableAttributeFromDB(this, \'input\', #rand#)">Search</span>
				</td>
				<td class="value">
					<input type="text" value="' . $v67db1bd535 .'" onFocus="onFocusQueryKey(this);" onBlur="onBlurQueryKey(this, #rand#);" />
				</td>
				<td class="join">
					<select onFocus="onFocusQueryKey(this);" onChange="onBlurQueryKey(this, #rand#);">'; if ($v3cbbdf9379) { $pc37695cb = count($v3cbbdf9379); for ($pc5166886 = 0; $pc5166886 < $pc37695cb; $pc5166886++) $pf8ed4912 .= '	<option ' . ($v3c581c912b == $v3cbbdf9379[$pc5166886] ? 'selected' : '') . '>' . $v3cbbdf9379[$pc5166886] . '</option>'; } $pf8ed4912 .= '	</select>
				</td>
				<td class="icon_cell table_header"><span class="icon delete" onClick="deleteQueryKey(this, #rand#);">Remove</span></td>
			</tr>'; return $pf8ed4912; } public static function getQueryConditionHtml1($pc661dc6b = false, $v9ea12a829c = false, $v19a7745bc6 = false, $v67db1bd535 = false) { $pa75e95f5 = array("=", "!=", ">", ">=", "<=", "like", "not like", "is", "not is", "in"); $v19a7745bc6 = strtolower($v19a7745bc6); if ($v19a7745bc6 == "in") { $v67db1bd535 = trim($v67db1bd535); $v67db1bd535 = substr($v67db1bd535, 0, 1) == "(" && substr($v67db1bd535, strlen($v67db1bd535) - 1) == ")" ? substr($v67db1bd535, 1, strlen($v67db1bd535) - 2) : $v67db1bd535; } else if (!$v19a7745bc6 && $v9ea12a829c) { $v19a7745bc6 = "="; } $pf8ed4912 = '
			<tr class="field">
				<td class="table">
					<input type="text" value="' . $pc661dc6b .'" onBlur="onBlurQueryInputField(this, #rand#)" />
					<span class="icon search" onClick="getQueryTableFromDB(this, #rand#)">Search</span>
				</td>
				<td class="column">
					<input type="text" value="' . $v9ea12a829c .'" onBlur="onBlurQueryInputField(this, #rand#)" />
					<span class="icon search" onClick="getQueryTableAttributeFromDB(this, \'input\', #rand#)">Search</span>
				</td>
				<td class="operator">
					<select onChange="onBlurQueryInputField(this, #rand#)">
						<option></option>'; if ($pa75e95f5) { $pc37695cb = count($pa75e95f5); for ($pc5166886 = 0; $pc5166886 < $pc37695cb; $pc5166886++) $pf8ed4912 .= '	<option ' . ($v19a7745bc6 == $pa75e95f5[$pc5166886] ? 'selected' : '') . '>' . $pa75e95f5[$pc5166886] . '</option>'; } $pf8ed4912 .= '	</select>
				</td>
				<td class="value">
					<input type="text" value="' . $v67db1bd535 .'" onBlur="onBlurQueryInputField(this, #rand#)" />
				</td>
				<td class="icon_cell table_header"><span class="icon delete" onClick="deleteQueryField(this, #rand#);">Remove</span></td>
			</tr>'; return $pf8ed4912; } public static function getQueryConditionHtml2($v9ea12a829c = false, $v19a7745bc6 = false, $v67db1bd535 = false) { $pa75e95f5 = array("=", "!=", ">", ">=", "<=", "like", "not like", "is", "not is", "in"); $v19a7745bc6 = strtolower($v19a7745bc6); if ($v19a7745bc6 == "in") { $v67db1bd535 = trim($v67db1bd535); $v67db1bd535 = substr($v67db1bd535, 0, 1) == "(" && substr($v67db1bd535, strlen($v67db1bd535) - 1) == ")" ? substr($v67db1bd535, 1, strlen($v67db1bd535) - 2) : $v67db1bd535; } else if (!$v19a7745bc6 && $v9ea12a829c) $v19a7745bc6 = "="; $pf8ed4912 = '
			<tr class="field">
				<td class="column">
					<input type="text" value="' . $v9ea12a829c .'" onBlur="onBlurQueryInputField(this, #rand#)" />
					<span class="icon search" onClick="getTableAttributeFromDB(this, \'input\', #rand#)">Search</span>
				</td>
				<td class="operator">
					<select onChange="onBlurQueryInputField(this, #rand#)">
						<option></option>'; if ($pa75e95f5) { $pc37695cb = count($pa75e95f5); for ($pc5166886 = 0; $pc5166886 < $pc37695cb; $pc5166886++) $pf8ed4912 .= '	<option ' . ($v19a7745bc6 == $pa75e95f5[$pc5166886] ? 'selected' : '') . '>' . $pa75e95f5[$pc5166886] . '</option>'; } $pf8ed4912 .= '	</select>
				</td>
				<td class="value">
					<input type="text" value="' . $v67db1bd535 .'" onBlur="onBlurQueryInputField(this, #rand#)" />
				</td>
				<td class="icon_cell table_header"><span class="icon delete" onClick="deleteQueryField(this, #rand#);">Remove</span></td>
			</tr>'; return $pf8ed4912; } public static function getQueryGroupByHtml($pc661dc6b = false, $v9ea12a829c = false) { return '
		<tr class="field">
			<td class="table">
				<input type="text" value="' . $pc661dc6b .'" onBlur="onBlurQueryInputField(this, #rand#)" />
				<span class="icon search" onClick="getQueryTableFromDB(this, #rand#)">Search</span>
			</td>
			<td class="column">
				<input type="text" value="' . $v9ea12a829c .'" onBlur="onBlurQueryInputField(this, #rand#)" />
				<span class="icon search" onClick="getQueryTableAttributeFromDB(this, \'input\', #rand#)">Search</span>
			</td>
			<td class="icon_cell table_header"><span class="icon delete" onClick="deleteQueryField(this, #rand#);">Remove</span></td>
		</tr>'; } public static function getQuerySortHtml($pc661dc6b = false, $v9ea12a829c = false, $pc06af91c = false) { $pbe68ffeb = array("ASC", "DESC"); $pf8ed4912 = '
			<tr class="field">
				<td class="table">
					<input type="text" value="' . $pc661dc6b .'" onBlur="onBlurQueryInputField(this, #rand#)" />
					<span class="icon search" onClick="getQueryTableFromDB(this, #rand#)">Search</span>
				</td>
				<td class="column">
					<input type="text" value="' . $v9ea12a829c .'" onBlur="onBlurQueryInputField(this, #rand#)" />
					<span class="icon search" onClick="getQueryTableAttributeFromDB(this, \'input\', #rand#)">Search</span>
				</td>
				<td class="order">
					<select onChange="onBlurQueryInputField(this, #rand#)">'; if ($pbe68ffeb) { $pc37695cb = count($pbe68ffeb); for ($pc5166886 = 0; $pc5166886 < $pc37695cb; $pc5166886++) $pf8ed4912 .= '	<option ' . ($pc06af91c == $pbe68ffeb[$pc5166886] ? 'selected' : '') . '>' . $pbe68ffeb[$pc5166886] . '</option>'; } $pf8ed4912 .= '	</select>
				</td>
				<td class="icon_cell table_header"><span class="icon delete" onClick="deleteQueryField(this, #rand#);">Remove</span></td>
			</tr>'; return $pf8ed4912; } } ?>
