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

include $EVC->getConfigPath("config"); include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); include_once $EVC->getUtilPath("WorkFlowUIHandler"); include_once $EVC->getUtilPath("WorkFlowQueryHandler"); class RegionsBlocksResourcesHandler { public static function getHeader($v188b4f5fa6, $v8ffce2a791, $pa0462a8e, $pa32be502, $peb014cfd, $v37d269c4fa, $v3b2000c17b, $pb5790ec9, $v3d55458bcd, $v4bf8d90f04, $pfce4d1b3, $v77839c9dc3, &$v830c74e006 = null) { $v0a9dad1fe0 = ""; $pa0c6ce9a = $peb014cfd . "phpframework/dataaccess/get_query_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&db_driver=#db_driver#&db_type=#db_type#&path=#path#&query_type=#query_type#&query=#query#&obj=#obj#&relationship_type=#relationship_type#"; $v4ee0f690b1 = $peb014cfd . "phpframework/dataaccess/get_query_result_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&db_driver=" . $GLOBALS["default_db_driver"] . "&module_id=#module_id#&query_type=#query_type#&query=#query#&rel_name=#rel_name#&obj=#obj#&relationship_type=#relationship_type#"; $v9d21f98a2c = $peb014cfd . "phpframework/businesslogic/get_business_logic_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&service=#service#"; $v06d0841ec2 = $peb014cfd . "phpframework/businesslogic/get_business_logic_result_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&module_id=#module_id#&service=#service#"; $v26f1ca5c9c = $peb014cfd . "phpframework/db/get_broker_db_drivers?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&broker=#broker#&item_type=presentation"; $v9ab35f1f0d = $v188b4f5fa6->getPresentationLayer(); $pc4223ce1 = $v9ab35f1f0d->getBrokers(); $v7a0994a134 = WorkFlowBeansFileHandler::getLayerBrokersSettings($v3d55458bcd, $v5039a77f9d, $pc4223ce1, '$EVC->getBroker'); $v8671b32a11 = WorkFlowBeansFileHandler::getLayerNameFromBeanObject($v8ffce2a791, $v9ab35f1f0d) . " (Self)"; $pb0e92e25 = array(); $pb0e92e25[] = array($v8671b32a11, $pa0462a8e, $v8ffce2a791); $v89e7b130c6 = array("default" => '$EVC->getPresentationLayer()'); $v6e9af47944 = $v7a0994a134["business_logic_brokers"]; $v5421227efb = $v7a0994a134["business_logic_brokers_obj"]; $v9fda9fad47 = $v7a0994a134["data_access_brokers"]; $pdeced6cd = $v7a0994a134["data_access_brokers_obj"]; $pf864769c = $v7a0994a134["ibatis_brokers"]; $v6a3a9f9182 = $v7a0994a134["ibatis_brokers_obj"]; $paf75a67c = $v7a0994a134["hibernate_brokers"]; $pbf7e8fcb = $v7a0994a134["hibernate_brokers_obj"]; $v5483bfa973 = $v7a0994a134["db_brokers"]; $v78844cd25d = $v7a0994a134["db_brokers_obj"]; $pb7b19dbe = array( $v8671b32a11 => $pb0e92e25[0] ); $v1f410c3f6b = array( $v89e7b130c6["default"] => $v8671b32a11, ); foreach ($v6e9af47944 as $v7aeaf992f5) { $v2b2cf4c0eb = $v7aeaf992f5[0]; $pb7b19dbe[$v2b2cf4c0eb] = $v7aeaf992f5; $v1f410c3f6b[ $v5421227efb[$v2b2cf4c0eb] ] = $v2b2cf4c0eb; } foreach ($v9fda9fad47 as $v7aeaf992f5) { $v2b2cf4c0eb = $v7aeaf992f5[0]; $pb7b19dbe[$v2b2cf4c0eb] = $v7aeaf992f5; $v1f410c3f6b[ $pdeced6cd[$v2b2cf4c0eb] ] = $v2b2cf4c0eb; } $v125cfae519 = array("callfunction", "callobjectmethod", "restconnector", "soapconnector"); if ($pdeced6cd) { $v125cfae519[] = "query"; $v125cfae519[] = "getquerydata"; $v125cfae519[] = "setquerydata"; if ($v6a3a9f9182) $v125cfae519[] = "callibatisquery"; if ($pbf7e8fcb) $v125cfae519[] = "callhibernatemethod"; } else if ($v78844cd25d) { $v125cfae519[] = "query"; $v125cfae519[] = "getquerydata"; $v125cfae519[] = "setquerydata"; } if ($v5421227efb) $v125cfae519[] = "callbusinesslogic"; $pecad7cca = new WorkFlowTaskHandler($v4bf8d90f04, $pfce4d1b3); $pecad7cca->setCacheRootPath(LAYER_CACHE_PATH); $pecad7cca->setAllowedTaskTags($v125cfae519); $pcfdeae4e = new WorkFlowUIHandler($pecad7cca, $peb014cfd, $v37d269c4fa, $v3b2000c17b, $pb5790ec9, $v3d55458bcd, $v4bf8d90f04, $pfce4d1b3); $pcfdeae4e->setTasksGroupsByTag(array( "disabled" => array("createform", "callfunction", "callobjectmethod", "query", "getquerydata", "setquerydata", "callibatisquery", "callhibernatemethod", "callbusinesslogic", "restconnector", "soapconnector"), )); $v524bbc0f84 = $pecad7cca->getLoadedTasksSettings(); $v830c74e006 = array(); $v0e9b8a0e96 = array(); foreach ($v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) { foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) { if (is_array($v003bc751fd)) { $v1b1c6a10a2 = $v003bc751fd["tag"]; $v830c74e006[$v1b1c6a10a2] = $v003bc751fd["task_properties_html"]; $v0e9b8a0e96[$v1b1c6a10a2] = $v003bc751fd["settings"]["callback"]["on_load_task_properties"]; } } } $pa8dedb03 = '
		var get_form_action_result_properties_url = \'' . $peb014cfd . 'module/form/get_form_action_result_properties\'; //TODO
		
		var get_query_properties_url = \'' . $pa0c6ce9a . '\';
		var get_query_result_properties_url = \'' . $v4ee0f690b1 . '\';
		var get_business_logic_properties_url = \'' . $v9d21f98a2c . '\';
		var get_business_logic_result_properties_url = \'' . $v06d0841ec2 . '\';
		var get_broker_db_drivers_url = \'' . $v26f1ca5c9c . '\';
		
		var js_load_functions = ' . json_encode($v0e9b8a0e96) . ';
		
		ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onProgrammingTaskChooseCreatedVariable;
		ProgrammingTaskUtil.on_programming_task_choose_object_method_callback = onProgrammingTaskChooseObjectMethod;
		ProgrammingTaskUtil.on_programming_task_choose_function_callback = onProgrammingTaskChooseFunction;
		ProgrammingTaskUtil.on_programming_task_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
		ProgrammingTaskUtil.on_programming_task_choose_image_url_callback = onIncludeImageUrlTaskChooseFile;
		
		if (typeof LayerOptionsUtilObj != "undefined" && LayerOptionsUtilObj)
			LayerOptionsUtilObj.on_choose_db_driver_callback = onChooseDBDriver;
		
		if (typeof CallBusinessLogicTaskPropertyObj != "undefined" && CallBusinessLogicTaskPropertyObj) {
			CallBusinessLogicTaskPropertyObj.on_choose_business_logic_callback = onBusinessLogicTaskChooseBusinessLogic;
			CallBusinessLogicTaskPropertyObj.brokers_options = ' . json_encode($v5421227efb) . ';
		}

		if (typeof CallIbatisQueryTaskPropertyObj != "undefined" && CallIbatisQueryTaskPropertyObj) {
			CallIbatisQueryTaskPropertyObj.on_choose_query_callback = onChooseIbatisQuery;
			CallIbatisQueryTaskPropertyObj.brokers_options = ' . json_encode($v6a3a9f9182) . ';
		}

		if (typeof CallHibernateMethodTaskPropertyObj != "undefined" && CallHibernateMethodTaskPropertyObj) {
			CallHibernateMethodTaskPropertyObj.on_choose_hibernate_object_method_callback = onChooseHibernateObjectMethod;
			CallHibernateMethodTaskPropertyObj.brokers_options = ' . json_encode($pbf7e8fcb) . ';
		}

		if (typeof GetQueryDataTaskPropertyObj != "undefined" && GetQueryDataTaskPropertyObj) {
			GetQueryDataTaskPropertyObj.brokers_options = ' . json_encode(array_merge($v78844cd25d, $pdeced6cd)) . ';
		}

		if (typeof SetQueryDataTaskPropertyObj != "undefined" && SetQueryDataTaskPropertyObj) {
			SetQueryDataTaskPropertyObj.brokers_options = ' . json_encode(array_merge($v78844cd25d, $pdeced6cd)) . ';
		}
		
		if (typeof DBQueryTaskPropertyObj != "undefined" && DBQueryTaskPropertyObj) {
			DBQueryTaskPropertyObj.show_properties_on_connection_drop = true;
		}
		
		var brokers_settings = ' . json_encode($pb7b19dbe) . ';
		var brokers_name_by_obj_code = ' . json_encode($v1f410c3f6b) . ';
		'; $v9b98e0e818 = array(); $v5a331eab7e = "db"; $pd7f46171 = $pc66a0204 = null; if ($pc4223ce1) foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) if (is_a($pd922c2f7, "IDataAccessBrokerClient") || is_a($pd922c2f7, "IDBBrokerClient")) { $v9b98e0e818[$v2b2cf4c0eb] = is_a($pd922c2f7, "IDBBrokerClient") ? $pd922c2f7->getDBDriversName() : $pd922c2f7->getBrokersDBDriversName(); if (empty($pd7f46171)) { $pd7f46171 = $v2b2cf4c0eb; if ($GLOBALS["default_db_driver"] && in_array($GLOBALS["default_db_driver"], $v9b98e0e818[$v2b2cf4c0eb])) $pc66a0204 = $GLOBALS["default_db_driver"]; else if (!$pc66a0204) $pc66a0204 = $v9b98e0e818[$v2b2cf4c0eb][0]; } } if ($v9b98e0e818) { $v546cf76c77 = new WorkFlowTaskHandler($v4bf8d90f04, $pfce4d1b3); $v546cf76c77->setCacheRootPath(LAYER_CACHE_PATH); $v546cf76c77->setAllowedTaskTags(array("query")); $pb692084d = new WorkFlowUIHandler($v546cf76c77, $peb014cfd, $v37d269c4fa, $v3b2000c17b, $pb5790ec9, $v3d55458bcd, $v4bf8d90f04, $pfce4d1b3); $v0a1f4a55aa = new WorkFlowQueryHandler($pb692084d, $peb014cfd, $v37d269c4fa, $v9b98e0e818, $pd7f46171, $pc66a0204, $v5a331eab7e, "", array(), array(), array(), array()); $pbb688020 .= $v0a1f4a55aa->getDataAccessJavascript($v8ffce2a791, $pa0462a8e, $pa32be502, "presentation", null, null); $pa8dedb03 .= str_replace('<script>', '', str_replace('</script>', '', $pbb688020)); $pa8dedb03 .= 'get_broker_db_data_url += "&global_default_db_driver_broker=' . $GLOBALS["default_db_broker"] . '";'; $pf8ed4912 = $v0a1f4a55aa->getGlobalTaskFlowChar(); $pf8ed4912 .= $v0a1f4a55aa->getQueryBlockHtml(); $pa8dedb03 .= 'var query_task_html = \'' . addcslashes(str_replace("\n", "", $pf8ed4912), "\\'") . '\';'; $pf8ed4912 = $v0a1f4a55aa->getChooseQueryTableOrAttributeHtml("choose_db_table_or_attribute"); $pa8dedb03 .= '
			var choose_db_table_or_attribute_html = $( \'' . addcslashes(str_replace("\n", "", $pf8ed4912), "\\'") . '\' );
			$("' . $v77839c9dc3 . '").append(choose_db_table_or_attribute_html);
			
			var default_dal_broker = "' . $pd7f46171 . '";
			var default_db_driver = "' . $pc66a0204 . '";
			
			getDBTables("' . $pd7f46171 . '", "' . $pc66a0204 . '", "' . $v5a331eab7e . '");
			
			var db_tables = db_brokers_drivers_tables_attributes["' . $pd7f46171 . '"] && db_brokers_drivers_tables_attributes["' . $pd7f46171 . '"]["' . $pc66a0204 . '"] ? db_brokers_drivers_tables_attributes["' . $pd7f46171 . '"]["' . $pc66a0204 . '"]["' . $v5a331eab7e . '"] : null;
			
			if (db_tables) {
				var html = "<option></option>";
				for (var db_table in db_tables) {
					html += "<option>" + db_table + "</option>";
				}
				choose_db_table_or_attribute_html.find(".db_table select").html(html);
			}
			
			choose_db_table_or_attribute_html.find(".db_broker > select").change(function() {
				onChangePopupDBBrokers(this);
			});
			
			choose_db_table_or_attribute_html.find(".db_driver > select").change(function() {
				onChangePopupDBDrivers(this);
			});
			
			choose_db_table_or_attribute_html.find(".type > select").change(function() {
				onChangePopupDBTypes(this);
			});
			'; $v0a9dad1fe0 .= '
			<!-- DBQUERY TASK - Add Edit-Query JS and CSS files -->
			<link rel="stylesheet" href="' . $peb014cfd . 'css/dataaccess/edit_query.css" type="text/css" charset="utf-8" />
			<script language="javascript" type="text/javascript" src="' . $peb014cfd . 'js/dataaccess/edit_query.js"></script>'; } $v0a9dad1fe0 .= '<script>' . $pa8dedb03 . '</script>

		<link rel="stylesheet" href="' . $v37d269c4fa . 'module/form/brokers_settings.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="' . $v37d269c4fa . 'module/form/brokers_settings.js"></script>'; return $v0a9dad1fe0; } public static function getItemsHtml($v6490ea3a15, $v9b98e0e818) { $pf8ed4912 = '
<nav>
	<a class="add_form_group" onClick="addAndInitNewFormGroup(this)">Add Group <i class="icon add"></i></a>
	<a class="collapse_form_groups" onClick="collapseFormGroups(this)">Collapse Groups <i class="icon collapse_content"></i></a>
	<a class="expand_form_groups" onClick="expandFormGroups(this)">Expand Groups <i class="icon expand_content"></i></a>
</nav>
<ul class="regions_blocks_resources">
	<li class="regions_blocks_resource default">' . self::getItemHtml($v6490ea3a15, $v9b98e0e818) . '</li>
</ul>'; return $pf8ed4912; } public static function getItemHtml($v6490ea3a15, $v9b98e0e818) { $pf8ed4912 = '
	<header class="header">
		<i class="icon expand_content toggle" onClick="toggleGroupBody(this)"></i>
		<input class="result_var_name result_var_name_output" type="text" placeHolder="Result Variable Name or leave it empty for direct output" title="This action will only appear in the output if this field is empty. If this \'Result Variable Name\' cotains a value, the output will be putted to this correspondent variable." />
		
		<i class="icon remove" onClick="removeItem(this)"></i>
		<i class="icon move_down" onClick="moveDownItem(this)"></i>
		<i class="icon move_up" onClick="moveUpItem(this)"></i>
		
		<select class="action_type" onChange="onChangeFormInputType(this)">
			' . ($v9b98e0e818 ? '<optgroup label="From DataBase">
				<option value="insert">Insert object into data-base</option>
				<option value="update">Update object into data-base</option>
				<option value="delete">Delete object into data-base</option>
				<option value="select">Get object from data-base</option>
				<option value="procedure">Call Procedure from data-base</option>
				<option value="getinsertedid">Get inserted object id</option>
			<optgroup>' : '') . '
			
			<optgroup label="From Brokers">
				<option value="callbusinesslogic">Call business logic service</option>
				' . ($v9b98e0e818 ? '
				<option value="callibatisquery">Call ibatis rule</option>
				<option value="callhibernatemethod">Call hibernate rule</option>
				<option value="getquerydata">Get sql query results</option>
				<option value="setquerydata">Set sql query</option>' : '') . '
				<option value="callfunction">Call function</option>
				<option value="callobjectmethod">Call object method</option>
				<option value="restconnector">Call rest connector</option>
				<option value="soapconnector">Call soap connector</option>
			<optgroup>
			
			<optgroup label="Others">
				<option value="code">Execute code</option>
				<option value="array">Result from array</option>
				<option value="string">Result from string/value</option>
				<option value="variable">Result from variable</option>
			<optgroup>
		</select>
		
		<div class="clear"></div>
		
		<div class="sub_header">
			<select class="condition_type" onChange="onGroupConditionTypeChange(this)">
				<option value="execute_always">Always execute</option>
				<option value="execute_if_var">Only execute if variable exists:</option>
				<option value="execute_if_not_var">Only execute if variable doesn\'t exists:</option>
				<option value="execute_if_post_button">Only execute if submit button was clicked via POST:</option>
				<option value="execute_if_not_post_button">Only execute if submit button was not clicked via POST:</option>
				<option value="execute_if_get_button">Only execute if submit button was clicked via GET:</option>
				<option value="execute_if_not_get_button">Only execute if submit button was not clicked via GET:</option>
				<option value="execute_if_previous_action">Only execute if previous action executed correctly</option>
				<option value="execute_if_not_previous_action">Only execute if previous action was not executed correctly</option>
				<option value="execute_if_condition" title="This is relative php code that will execute when the module runs. Aditionally this code will be parsed as a string! This is, quotes will be added to this code! MUST 1 LINE CODE!">Only execute if condition is valid:</option>
				<option value="execute_if_not_condition" title="This is relative php code that will execute when the module runs. Aditionally this code will be parsed as a string! This is, quotes will be added to this code! MUST 1 LINE CODE!">Only execute if condition is invalid:</option>
				<option value="execute_if_code" title="This is absolute php code that will execute directly, before this module runs. Which means when this module runs, this condition can only be true or false. Note that this code will not be parsed as a string! This is, no quotes will be added to this code! MUST 1 LINE CODE!">Only execute if code is valid:</option>
				<option value="execute_if_not_code" title="This is absolute php code that will execute directly, before this module runs. Which means when this module runs, this condition can only be true or false Note that this code will not be parsed as a string! This is, no quotes will be added to this code! MUST 1 LINE CODE!">Only execute if code is invalid:</option>
			</select>
			
			<input class="condition_value" type="text" placeHolder="Variable/Name/Condition here" />
			
			<div class="clear"></div>
			
			<div class="description">
				<label>Description</label>
				<textarea placeHolder="Explain this action here..."></textarea>
			</div>
		</div>
	</header>
	
	<div class="selected_task_properties body">
		<textarea class="undefined-action-value hidden"></textarea> <!-- This will be used everytime a broker-action or database-action does not exists -->
		
		' . ($v9b98e0e818 ? '
		<section class="database_body">
			<header>
				<div class="dal_broker">
					<label>Broker: </label>
					<select class="task_property_field" onChange="updateDALActionBroker(this);"></select>
				</div>
				<div class="db_driver">
					<label>DB Driver: </label>
					<select class="task_property_field" onChange="updateDBActionDriver(this);"></select>
				</div>
				<div class="db_type">
					<label>DB Type: </label>
					<select class="task_property_field" onChange="updateDBActionType(this);">
						<option value="db">From DB Server</option>
						<option value="diagram">From DB Diagram</option>
					</select>
				</div>
			</header>
			<article></article>
			<footer>
				<div class="opts">
					<label class="main_label">Options:</label>
					<input type="text" class="task_property_field options_code" name="options" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
					<select class="task_property_field options_type" name="options_type" onChange="LayerOptionsUtilObj.onChangeOptionsType(this)">
						<option value="">code</option>
						<option>string</option>
						<option>variable</option>
						<option>array</option>
					</select>
					<div class="options array_items"></div>
				</div>
			</footer>
		</section>
		' : '') . '
		
		<section class="broker_body">
			' . $v6490ea3a15["callbusinesslogic"] . '
			' . $v6490ea3a15["callibatisquery"] . '
			' . $v6490ea3a15["callhibernatemethod"] . '
			' . $v6490ea3a15["getquerydata"] . '
			' . $v6490ea3a15["setquerydata"] . '
			' . $v6490ea3a15["callfunction"] . '
			' . $v6490ea3a15["callobjectmethod"] . '
			' . $v6490ea3a15["restconnector"] . '
			' . $v6490ea3a15["soapconnector"] . '
		</section>
		
		<section class="code_body">
			<textarea class="task_property_field">&lt;?

?&gt;</textarea>
		</section>
		
		<section class="array_body array_items"></section>
		
		<section class="string_body">
			<label>String: </label>
			<input class="task_property_field" />
			<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</section>
		
		<section class="variable_body" title="Variable input could be a PHP variable name (like $foo[\'bar\']) or something like #foo[bar]#">
			<label>Variable: </label>
			<input class="task_property_field" />
			<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</section>
	</div>'; return $pf8ed4912; } } ?>
