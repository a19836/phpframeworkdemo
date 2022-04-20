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

include_once get_lib("org.phpframework.cms.wordpress.WordPressUrlsParser"); include $EVC->getUtilPath("WorkFlowUIHandler"); include $EVC->getUtilPath("WorkFlowPresentationHandler"); $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $SubWorkFlowUIHandler = new WorkFlowUIHandler($SubWorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $template_tasks_types_by_tag = array( "server" => $WorkFlowTaskHandler->getTasksByTag("server")[0]["type"], "presentation" => $WorkFlowTaskHandler->getTasksByTag("presentation")[0]["type"], "businesslogic" => $WorkFlowTaskHandler->getTasksByTag("businesslogic")[0]["type"], "dataaccess" => $WorkFlowTaskHandler->getTasksByTag("dataaccess")[0]["type"], "db" => $WorkFlowTaskHandler->getTasksByTag("db")[0]["type"], "dbdriver" => $WorkFlowTaskHandler->getTasksByTag("dbdriver")[0]["type"], ); $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $choose_test_units_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=test_unit&path=#path#"; $validate_template_properties_url = $project_url_prefix . "deployment/validate_template?server=#server#&template_id=#template_id#"; $deploy_template_to_server_url = $project_url_prefix . "deployment/deploy_template_to_server?server=#server#&template_id=#template_id#&deployment_id=#deployment_id#&action=#action#"; $head = $WorkFlowUIHandler->getHeader(); $head .= '
<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/deployment/index.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/deployment/index.js"></script>'; $head .= $WorkFlowUIHandler->getJS($workflow_path_id); $head .= '<script>
ServerTaskPropertyObj.template_workflow_html += \'' . str_replace("<script>", "<' + 'script>", str_replace("</script>", "<' + '/script>", str_replace("'", "\\'", str_replace(array("\n", "\r"), "", getTemplateWorklowHtml($SubWorkFlowUIHandler, $project_url_prefix))))) . '\';
ServerTaskPropertyObj.get_layers_tasks_file_url = \'' . $project_url_prefix . 'workflow/get_workflow_file?path=layer\';
ServerTaskPropertyObj.validate_template_properties_url = \'' . $validate_template_properties_url . '\';
ServerTaskPropertyObj.deploy_template_to_server_url = \'' . $deploy_template_to_server_url . '\';
ServerTaskPropertyObj.template_tasks_types_by_tag = ' . json_encode($template_tasks_types_by_tag) . ';
ServerTaskPropertyObj.server_time_diff_in_milliseconds = (' . time() . ' * 1000) - (new Date()).getTime();
ServerTaskPropertyObj.on_choose_template_flow_layer_file_callback = onChooseTemplateTaskLayerFile;
ServerTaskPropertyObj.on_get_layer_wordpress_installations_url_callback = onGetLayerWordPressInstallationsUrl;
ServerTaskPropertyObj.on_choose_test_units_callback = onChooseTemplateActionTestUnit;
ServerTaskPropertyObj.on_open_server_properties_popup_callback = onOpenServerPropertiesPopup;
ServerTaskPropertyObj.on_close_server_properties_popup_callback = onCloseServerPropertiesPopup;
ServerTaskPropertyObj.show_php_obfuscation_option = ' . ($show_php_obfuscation_option ? "true" : "false") . ';
ServerTaskPropertyObj.show_js_obfuscation_option = ' . ($show_js_obfuscation_option ? "true" : "false") . ';
ServerTaskPropertyObj.projects_max_expiration_date_allowed = "' . $projects_max_expiration_date_allowed . '";
ServerTaskPropertyObj.sysadmin_max_expiration_date_allowed = "' . $sysadmin_max_expiration_date_allowed . '";
ServerTaskPropertyObj.projects_max_num_allowed = "' . $projects_max_num_allowed . '";
ServerTaskPropertyObj.users_max_num_allowed = "' . $users_max_num_allowed . '";
ServerTaskPropertyObj.end_users_max_num_allowed = "' . $end_users_max_num_allowed . '";
ServerTaskPropertyObj.actions_max_num_allowed = "' . $actions_max_num_allowed . '";
ServerTaskPropertyObj.allowed_paths = "' . $allowed_paths . '";
ServerTaskPropertyObj.allowed_domains = "' . $allowed_domains . '";
ServerTaskPropertyObj.check_allowed_domains_port = ' . ($check_allowed_domains_port ? "true" : "false") . ';

var wordpress_installations_relative_path = "' . $EVC->getCommonProjectName() . '/webroot/' . WordPressUrlsParser::WORDPRESS_FOLDER_PREFIX . '/";
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, ""); $head .= WorkFlowPresentationHandler::getBusinessLogicBrokersHtml($business_logic_brokers, $choose_bean_layer_files_from_file_manager_url, ""); $head .= WorkFlowPresentationHandler::getDataAccessBrokersHtml($data_access_brokers, $choose_bean_layer_files_from_file_manager_url); $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_test_units_files_from_file_manager_url, "", "", ""); foreach ($layer_brokers_settings as $k => $layer_brokers) if ($k == "data_access_brokers" || $k == "business_logic_brokers" || $k == "presentation_brokers") { $t = count($layer_brokers); for ($i = 0; $i < $t; $i++) { $l = $layer_brokers[$i]; $head .= '
				if (main_layers_properties.' . $l[2] . ') {
					if (!main_layers_properties.' . $l[2] . '.ui.folder.hasOwnProperty("attributes"))
						main_layers_properties.' . $l[2] . '.ui.folder.attributes = {};
					
					if (!main_layers_properties.' . $l[2] . '.ui.folder.attributes.hasOwnProperty("file_path"))
						main_layers_properties.' . $l[2] . '.ui.folder.attributes.file_path = "#path#";
					
					if (!main_layers_properties.' . $l[2] . '.ui.cms_common.hasOwnProperty("attributes"))
						main_layers_properties.' . $l[2] . '.ui.cms_common.attributes = {};
					
					if (!main_layers_properties.' . $l[2] . '.ui.cms_common.attributes.hasOwnProperty("file_path"))
						main_layers_properties.' . $l[2] . '.ui.cms_common.attributes.file_path = "#path#";
					
					if (!main_layers_properties.' . $l[2] . '.ui.cms_module.hasOwnProperty("attributes"))
						main_layers_properties.' . $l[2] . '.ui.cms_module.attributes = {};
					
					if (!main_layers_properties.' . $l[2] . '.ui.cms_module.attributes.hasOwnProperty("file_path"))
						main_layers_properties.' . $l[2] . '.ui.cms_module.attributes.file_path = "#path#";
					
					if (!main_layers_properties.' . $l[2] . '.ui.cms_program.hasOwnProperty("attributes"))
						main_layers_properties.' . $l[2] . '.ui.cms_program.attributes = {};
					
					if (!main_layers_properties.' . $l[2] . '.ui.cms_program.attributes.hasOwnProperty("file_path"))
						main_layers_properties.' . $l[2] . '.ui.cms_program.attributes.file_path = "#path#";'; if ($k == "presentation_brokers") $head .= '
					if (!main_layers_properties.' . $l[2] . '.ui.project_folder.hasOwnProperty("attributes"))
						main_layers_properties.' . $l[2] . '.ui.project_folder.attributes = {};
					
					if (!main_layers_properties.' . $l[2] . '.ui.project_folder.attributes.hasOwnProperty("file_path"))
						main_layers_properties.' . $l[2] . '.ui.project_folder.attributes.file_path = "#path#";
					
					if (!main_layers_properties.' . $l[2] . '.ui.project.hasOwnProperty("attributes"))
						main_layers_properties.' . $l[2] . '.ui.project.attributes = {};
					
					if (!main_layers_properties.' . $l[2] . '.ui.project.attributes.hasOwnProperty("file_path"))
						main_layers_properties.' . $l[2] . '.ui.project.attributes.file_path = "#path#";'; $head .= '
				}'; } } $head .= '</script>'; $menus = array( "Flush Cache" => array( "class" => "flush_cache", "html" => '<a onClick="return flushCache();"><i class="icon flush_cache"></i> Flush Cache</a>', ), "Empty Diagram" => array( "class" => "empty_diagram", "html" => '<a onClick="emptyDiagam();return false;"><i class="icon empty_diagram"></i> Empty Diagram</a>', ), 0 => array( "class" => "separator", "title" => " ", "html" => " ", ), "Add new Server" => array( "class" => "add_new_server", "html" => '<a onClick="addNewServer();return false;"><i class="icon add"></i> Add new Server</a>', ), 1 => array( "class" => "separator", "title" => " ", "html" => " ", ), "Maximize/Minimize Editor Screen" => array( "class" => "tasks_flow_full_screen", "html" => '<a onClick="toggleFullScreen(this);return false;"><i class="icon full_screen"></i> Maximize Editor Screen</a>', ), 2 => array( "class" => "separator", "title" => " ", "html" => " ", ), "Save" => array( "class" => "save", "html" => '<a onClick="return saveDeploymentDiagram();"><i class="icon save"></i> Save</a>', ), ); $WorkFlowUIHandler->setMenus($menus); $main_content = '
	<div class="top_bar">
		<header>
			<div class="title">Deployment</div>
			<ul>
				<li class="save" title="Save"><a onClick="saveDeploymentDiagram()"><i class="icon save"></i> Save</a></li>
			</ul>
		</header>
	</div>
	
	<div id="choose_template_task_layer_file_from_file_manager" class="myfancypopup choose_from_file_manager">
		<div class="broker">
			<label>Broker:</label>
			<select onChange="updateTemplateTaskLayerUrlFileManager(this)">'; foreach ($layer_brokers_settings as $k => $layer_brokers) if ($k == "data_access_brokers" || $k == "business_logic_brokers" || $k == "presentation_brokers") { $t = count($layer_brokers); for ($i = 0; $i < $t; $i++) { $l = $layer_brokers[$i]; $layer_name = $l[0]; $bean_file_name = $l[1]; $bean_name = $l[2]; $url = str_replace("#bean_file_name#", $bean_file_name, str_replace("#bean_name#", $bean_name, $choose_bean_layer_files_from_file_manager_url)); $url .= $k == "presentation_brokers" ? "&item_type=presentation" : ""; $main_content .= '<option url="' . $url . '">' . strtolower($layer_name) . '</option>'; } } $main_content .= '
			</select>
		</div>
		<ul class="mytree">
			<li>
				<label>Layer Root</label>
				<ul></ul>
			</li>
		</ul>
		<div class="button">
			<input type="button" value="Update" onClick="MyDeploymentUIFancyPopup.settings.updateFunction(this)" />
		</div>
	</div>'; $main_content .= '
	<div id="choose_test_units_from_file_manager" class="myfancypopup choose_from_file_manager">
		<ul class="mytree">
			<li>
				<label>Test Units</label>
				<ul url="' . $choose_test_units_files_from_file_manager_url . '"></ul>
			</li>
		</ul>
		<div class="button">
			<input type="button" value="Update" onClick="MyDeploymentUIFancyPopup.settings.updateFunction(this)" />
		</div>
	</div>'; $main_content .= $WorkFlowUIHandler->getContent(); $main_content .= '<div class="loading_panel"></div>'; function getTemplateWorklowHtml($v8685d1ca97, $peb014cfd) { $v243e50bc1d = array( "Set Global Vars" => array( "class" => "set_global_vars", "html" => '<a onClick="return ServerTaskPropertyObj.openTemplateGlobalVarsOrSettingsPopup(this, \'' . $peb014cfd . 'phpframework/layer/list_global_vars\');"><i class="icon global_vars"></i> Globar Vars</a>', ), "Set Global Settings" => array( "class" => "set_global_settings", "html" => '<a onClick="return ServerTaskPropertyObj.openTemplateGlobalVarsOrSettingsPopup(this, \'' . $peb014cfd . 'phpframework/layer/list_global_settings\');"><i class="icon global_settings"></i> Global Settings</a>', ), ); $v8685d1ca97->setMenus($v243e50bc1d); $pf8ed4912 = $v8685d1ca97->getContent("taskflowchart_#rand#"); return $pf8ed4912; } ?>
