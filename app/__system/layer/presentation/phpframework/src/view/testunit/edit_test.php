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

include $EVC->getUtilPath("WorkFlowUIHandler"); include $EVC->getUtilPath("WorkFlowPresentationHandler"); $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowUIHandler->setTasksGroupsByTag(array( "Logic" => array("definevar", "setvar", "setarray", "setdate", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "getbeanobject"), "Connectors" => array("restconnector", "soapconnector"), "Exception" => array("trycatchexception", "throwexception", "printexception"), "DB" => array("getdbdriver", "setquerydata", "getquerydata", "dbdaoaction", "callibatisquery", "callhibernateobject", "callhibernatemethod"), "Layers" => array("callbusinesslogic", "callpresentationlayerwebservice", "setpresentationview", "setpresentationtemplate"), "HTML" => array("inlinehtml", "createform"), "CMS" => array("setblockparams", "settemplateregionblockparam", "includeblock", "addtemplateregionblock", "rendertemplateregion", "settemplateparam", "gettemplateparam"), )); $WorkFlowUIHandler->addFoldersTasksToTasksGroups($code_workflow_editor_user_tasks_folders_path); $save_url = $project_url_prefix . 'phpframework/testunit/save_test?path=' . $path; $path_extra = hash('crc32b', "$path"); $get_workflow_tasks_id = "test_unit_workflow&path_extra=_$path_extra"; $get_tmp_workflow_tasks_id = "test_unit_workflow_tmp&path_extra=_${path_extra}_" . rand(0, 1000); $set_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_workflow_tasks_id}"; $get_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_workflow_tasks_id}"; $create_workflow_file_from_code_url = $project_url_prefix . "workflow/create_workflow_file_from_code?path=${get_tmp_workflow_tasks_id}&loaded_tasks_settings_cache_id=" . $WorkFlowTaskHandler->getLoadedTasksSettingsCacheId(); $get_tmp_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_tmp_workflow_tasks_id}"; $create_code_from_workflow_file_url = $project_url_prefix . "workflow/create_code_from_workflow_file?path=${get_tmp_workflow_tasks_id}"; $set_tmp_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_tmp_workflow_tasks_id}"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#"; $choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#"; $choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $get_query_properties_url = $project_url_prefix . "phpframework/dataaccess/get_query_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&db_driver=#db_driver#&db_type=#db_type#&path=#path#&query_type=#query_type#&query=#query#&obj=#obj#&relationship_type=#relationship_type#"; $get_business_logic_properties_url = $project_url_prefix . "phpframework/businesslogic/get_business_logic_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&service=#service#"; $get_broker_db_drivers_url = $project_url_prefix . "phpframework/db/get_broker_db_drivers?bean_name=#bean_name#&bean_file_name=#bean_file_name#&broker=#broker#&item_type=#item_type#"; $get_broker_db_data_url = $project_url_prefix . "phpframework/dataaccess/get_broker_db_data?bean_name=#bean_name#&bean_file_name=#bean_file_name#"; $head = WorkFlowPresentationHandler::getHeader($project_url_prefix, $project_common_url_prefix, $WorkFlowUIHandler, $set_workflow_file_url); $head .= '
<!-- Top-Bar CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/top_bar.css" type="text/css" charset="utf-8" />

<!-- Add local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/testunit/edit_test.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/testunit/edit_test.js"></script>

<script>
var layer_type = null;
var selected_project_id = null;
var file_modified_time = ' . $file_modified_time . '; //for version control

var layer_brokers_settings = ' . json_encode($layer_brokers_settings) . ';
var layers_projects_urls = ' . json_encode($layers_projects_urls) . ';

var get_workflow_file_url = \'' . $get_workflow_file_url . '\';
var save_object_url = \'' . $save_url . '\';
var create_workflow_file_from_code_url = \'' . $create_workflow_file_from_code_url . '\';
var get_tmp_workflow_file_url = \'' . $get_tmp_workflow_file_url . '\';
var create_code_from_workflow_file_url = \'' . $create_code_from_workflow_file_url . '\';
var set_tmp_workflow_file_url = \'' . $set_tmp_workflow_file_url . '\';
var get_query_properties_url = \'' . $get_query_properties_url . '\';
var get_business_logic_properties_url = \'' . $get_business_logic_properties_url . '\';
var get_broker_db_drivers_url = \'' . $get_broker_db_drivers_url . '\';
var get_broker_db_data_url = \'' . $get_broker_db_data_url . '\';

var new_annotation_html = \'' . str_replace("'", "\\'", str_replace("\n", "", WorkFlowTestUnitHandler::getAnnotationHTML())) .'\';
var new_global_variables_file_path_html = \'' . str_replace("'", "\\'", str_replace("\n", "", WorkFlowTestUnitHandler::getGlobalVariablesFilePathHTML())) .'\';

ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onProgrammingTaskChooseCreatedVariable;
ProgrammingTaskUtil.on_programming_task_choose_object_property_callback = onProgrammingTaskChooseObjectProperty;
ProgrammingTaskUtil.on_programming_task_choose_object_method_callback = onProgrammingTaskChooseObjectMethod;
ProgrammingTaskUtil.on_programming_task_choose_function_callback = onProgrammingTaskChooseFunction;
ProgrammingTaskUtil.on_programming_task_choose_class_name_callback = onProgrammingTaskChooseClassName;
ProgrammingTaskUtil.on_programming_task_choose_file_path_callback = onIncludeFileTaskChooseFile;
ProgrammingTaskUtil.on_programming_task_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
ProgrammingTaskUtil.on_programming_task_choose_image_url_callback = onIncludeImageUrlTaskChooseFile;

GetBeanObjectTaskPropertyObj.phpframeworks_options = ' . json_encode($phpframeworks_options) . ';
GetBeanObjectTaskPropertyObj.bean_names_options = ' . json_encode($bean_names_options) . ';

if (typeof LayerOptionsUtilObj != "undefined" && LayerOptionsUtilObj)
	LayerOptionsUtilObj.on_choose_db_driver_callback = onChooseDBDriver;

if (typeof callPresentationLayerWebServiceTaskPropertyObj != "undefined" && callPresentationLayerWebServiceTaskPropertyObj) {
	callPresentationLayerWebServiceTaskPropertyObj.on_choose_page_callback = onPresentationTaskChoosePage;
	callPresentationLayerWebServiceTaskPropertyObj.brokers_options = ' . json_encode($presentation_brokers_obj) . ';
}

if (typeof SetPresentationTemplateTaskPropertyObj != "undefined" && SetPresentationTemplateTaskPropertyObj)
	SetPresentationTemplateTaskPropertyObj.brokers_options = ' . json_encode($presentation_evc_brokers_obj) . ';

if (typeof SetPresentationViewTaskPropertyObj != "undefined" && SetPresentationViewTaskPropertyObj)
	SetPresentationViewTaskPropertyObj.brokers_options = ' . json_encode($presentation_evc_brokers_obj) . ';

if (typeof GetTemplateParamTaskPropertyObj != "undefined" && GetTemplateParamTaskPropertyObj)
	GetTemplateParamTaskPropertyObj.brokers_options = ' . json_encode($presentation_evc_template_brokers_obj) . ';

if (typeof SetTemplateParamTaskPropertyObj != "undefined" && SetTemplateParamTaskPropertyObj) {
	SetTemplateParamTaskPropertyObj.brokers_options = ' . json_encode($presentation_evc_template_brokers_obj) . ';
}

if (typeof RenderTemplateRegionTaskPropertyObj != "undefined" && RenderTemplateRegionTaskPropertyObj)
	RenderTemplateRegionTaskPropertyObj.brokers_options = ' . json_encode($presentation_evc_template_brokers_obj) . ';

if (typeof AddTemplateRegionBlockTaskPropertyObj != "undefined" && AddTemplateRegionBlockTaskPropertyObj)
	AddTemplateRegionBlockTaskPropertyObj.brokers_options = ' . json_encode($presentation_evc_template_brokers_obj) . ';

if (typeof IncludeBlockTaskPropertyObj != "undefined" && IncludeBlockTaskPropertyObj) {
	IncludeBlockTaskPropertyObj.on_choose_file_callback = onIncludeBlockTaskChooseFile;
	IncludeBlockTaskPropertyObj.brokers_options = ' . json_encode($presentation_evc_brokers_obj) . ';
	IncludeBlockTaskPropertyObj.projects_options = ' . json_encode($available_projects) . ';
}

if (typeof CallBusinessLogicTaskPropertyObj != "undefined" && CallBusinessLogicTaskPropertyObj) {
	CallBusinessLogicTaskPropertyObj.on_choose_business_logic_callback = onBusinessLogicTaskChooseBusinessLogic;
	CallBusinessLogicTaskPropertyObj.brokers_options = ' . json_encode($business_logic_brokers_obj) . ';
}

if (typeof CallIbatisQueryTaskPropertyObj != "undefined" && CallIbatisQueryTaskPropertyObj) {
	CallIbatisQueryTaskPropertyObj.on_choose_query_callback = onChooseIbatisQuery;
	CallIbatisQueryTaskPropertyObj.brokers_options = ' . json_encode($ibatis_brokers_obj) . ';
}

if (typeof CallHibernateObjectTaskPropertyObj != "undefined" && CallHibernateObjectTaskPropertyObj) {
	CallHibernateObjectTaskPropertyObj.on_choose_hibernate_object_callback = onChooseHibernateObject;
	CallHibernateObjectTaskPropertyObj.brokers_options = ' . json_encode($hibernate_brokers_obj) . ';
}

if (typeof CallHibernateMethodTaskPropertyObj != "undefined" && CallHibernateMethodTaskPropertyObj) {
	CallHibernateMethodTaskPropertyObj.on_choose_hibernate_object_method_callback = onChooseHibernateObjectMethod;
	CallHibernateMethodTaskPropertyObj.brokers_options = ' . json_encode($hibernate_brokers_obj) . ';
}

if (typeof GetQueryDataTaskPropertyObj != "undefined" && GetQueryDataTaskPropertyObj) {
	GetQueryDataTaskPropertyObj.brokers_options = ' . json_encode(array_merge($db_brokers_obj, $data_access_brokers_obj)) . ';
}

if (typeof SetQueryDataTaskPropertyObj != "undefined" && SetQueryDataTaskPropertyObj) {
	SetQueryDataTaskPropertyObj.brokers_options = ' . json_encode(array_merge($db_brokers_obj, $data_access_brokers_obj)) . ';
}

if (typeof DBDAOActionTaskPropertyObj != "undefined" && DBDAOActionTaskPropertyObj){
	DBDAOActionTaskPropertyObj.on_choose_table_callback = onChooseDBTableAndAttributes;
	DBDAOActionTaskPropertyObj.brokers_options = ' . json_encode(array_merge($db_brokers_obj, $data_access_brokers_obj)) . ';
}

if (typeof GetDBDriverTaskPropertyObj != "undefined" && GetDBDriverTaskPropertyObj) {
	GetDBDriverTaskPropertyObj.brokers_options = ' . json_encode($db_brokers_obj) . ';
	GetDBDriverTaskPropertyObj.db_drivers_options = ' . json_encode($db_drivers_options) . ';
}
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getBusinessLogicBrokersHtml($business_logic_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDataAccessBrokersHtml($data_access_brokers, $choose_bean_layer_files_from_file_manager_url); $head .= '</script>'; $main_content = '
	<div class="top_bar">
		<header>
			<div class="title">
				Edit Test: \'' . pathinfo($path, PATHINFO_FILENAME) . '\'
			</div>
			<!--ul class="dropdown">
				<li class="save" title="Save"><a class="save" onClick="saveTest()">Save</a></li>
			</ul-->
		</header>
	</div>'; if ($obj_data) { $main_content .= WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml(null, null, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $db_brokers, $data_access_brokers, $ibatis_brokers, $hibernate_brokers, $business_logic_brokers, $presentation_brokers); $main_content .= '
	<div class="edit_test with_top_bar_tab">
		<ul class="tabs tabs_transparent tabs_right tabs_icons">
			<li id="code_editor_tab" title="Code"><a href="#code" onClick="onClickCodeEditorTab(this);return false;"><i class="icon code_editor_tab"></i> Code</a></li>
			<li id="tasks_flow_tab" title="Workflow"><a href="#ui" onClick="onClickTaskWorkflowTab(this);return false;"><i class="icon tasks_flow_tab"></i> Workflow</a></li>
		</ul>
		
		<div id="settings" class="settings collapsed">
			<div class="settings_header">
				Settings
				<div class="icon maximize" onClick="toggleSettingsPanel(this)">Toggle</div>
			</div>
			<div class="enabled">
				<label>Is Enabled:</label>
				<input type="checkbox" value="1" ' . ($enabled ? "checked" : "") . ' />
			</div>
			<div class="global_variables_files_path">
				<label>Global Variables Files Path:</label>
				<span class="icon add" onClick="addNewGlobalVariableFilePath(this)" title="Add Annotation">Add</span>
				<table>
					<thead>
						<tr>
							<th class="path table_header">Path</th>
							<th class="icon_cell table_header"><span class="icon add" onClick="addNewGlobalVariableFilePath(this)" title="Add Annotation">Add</span></th>
						</tr>
					</thead>
					<tbody class="fields">'; if (is_array($global_variables_files_path)) foreach ($global_variables_files_path as $global_variables_file_path) $main_content .= WorkFlowTestUnitHandler::getGlobalVariablesFilePathHTML( $global_variables_file_path->getArgs() ); $main_content .= '
					</tbody>
				</table>
			</div>
			<div class="annotations">
				<label>Annotations:</label>
				<span class="icon add" onClick="addNewAnnotation(this)" title="Add Annotation">Add</span>
				<table>
					<thead>
						<tr>
							<th class="annotation_type table_header">Type</th>
							<th class="path table_header">Test Path</th>
							<th class="description table_header">Description</th>
							<th class="others table_header">Others</th>
							<th class="icon_cell table_header"><span class="icon add" onClick="addNewAnnotation(this)" title="Add Annotation">Add</span></th>
						</tr>
					</thead>
					<tbody class="fields">'; if (is_array($depends)) foreach ($depends as $depend) $main_content .= WorkFlowTestUnitHandler::getAnnotationHTML($depend->getArgs(), "depends"); $main_content .= '
					</tbody>
				</table>
			</div>
			<div class="comments">
				<label>Comments:</label>'; $comments = is_array($obj_data["comments"]) ? implode("\n", $obj_data["comments"]) : ""; $comments .= $method_comments ? trim($method_comments) : ""; $comments = str_replace(array("/*", "*/", "//"), "", $comments); $main_content .= '
				<textarea>' . htmlspecialchars($comments, ENT_NOQUOTES) . '</textarea>
			</div>
		</div>
		
		<div id="code">
			<div class="code_menu top_bar_menu">
				' . WorkFlowPresentationHandler::getCodeEditorMenuHtml(array("save_func" => "saveTest")) . '
			</div>
			<textarea>' . "<?php\n" . htmlspecialchars($obj_data["code"], ENT_NOQUOTES) . "\n?>" . '</textarea>
		</div>
		
		<div id="ui">' . WorkFlowPresentationHandler::getTaskFlowContentHtml($WorkFlowUIHandler, array("save_func" => "saveTest")) . '</div>
	</div>
	<div class="big_white_panel"></div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the selected object. Please refresh and try again...</div>'; ?>
