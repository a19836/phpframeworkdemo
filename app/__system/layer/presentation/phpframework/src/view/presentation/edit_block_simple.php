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

include_once $EVC->getUtilPath("WorkFlowPresentationHandler"); $query_string = str_replace(array("&edit_block_type=advanced", "&edit_block_type=simple"), "", $_SERVER["QUERY_STRING"]); $title = isset($title) ? $title : 'Edit Block "' . $block_id . '"'; $sub_title = isset($sub_title) ? $sub_title : '(<a href="?' . $query_string . '&edit_block_type=advanced">Show Advanced UI</a>)'; $save_url = $save_url ? $save_url : $project_url_prefix . "phpframework/presentation/save_block_simple?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $call_module_file_prefix_url = $project_url_prefix . "phpframework/module/" . $module_id . "/#module_file_path#?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $call_common_module_file_prefix_url = $project_common_url_prefix . "module/common/#module_file_path#?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $get_block_handler_source_code_url = $project_url_prefix . "phpframework/presentation/get_module_handler_source_code?bean_name=$bean_name&bean_file_name=$bean_file_name&project=$path&block=#block#"; $module_admin_panel_url = $module_group_id ? $project_url_prefix . "/module/" . $module_group_id . "/admin/?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path" : ""; $presentation_project_webroot_url = getPresentationProjectWebrootUrl($PEVC, $user_global_variables_file_path); $presentation_project_common_webroot_url = getPresentationProjectCommonWebrootUrl($PEVC, $user_global_variables_file_path); $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#"; $choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#"; $choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $templates_regions_html_url = $project_url_prefix . "phpframework/presentation/templates_regions_html?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $block_settings = isset($block_params[0]["block_settings"]["key"]) ? array($block_params[0]["block_settings"]) : $block_params[0]["block_settings"]; $block_settings_obj = CMSPresentationLayerJoinPointsUIHandler::convertBlockSettingsArrayToObj($block_settings); $head = '
<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add PHP CODE CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/edit_php_code.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_code.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_php_code.js"></script>

<!-- Add PHPJS Functions -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/phpjs/functions/strings/parse_str.js"></script>
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/phpjs/functions/strings/stripslashes.js"></script>
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/phpjs/functions/strings/addcslashes.js"></script>

<!-- Add local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_block_simple.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_block_simple.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/responsive_iframe.js"></script>

<!-- Add Join Point CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/module_join_points.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/module_join_points.js"></script>

<script>
var layer_type = "pres";
var selected_project_id = "' . $selected_project_id . '";
var file_modified_time = ' . $file_modified_time . '; //for version control

var save_object_url = \'' . $save_url . '\';
var call_module_file_prefix_url = \'' . $call_module_file_prefix_url . '\';
var call_common_module_file_prefix_url = \'' . $call_common_module_file_prefix_url . '\';
var get_block_handler_source_code_url = \'' . $get_block_handler_source_code_url . '\';
var module_admin_panel_url = \'' . $module_admin_panel_url . '\';
var presentation_project_webroot_url = \'' . $presentation_project_webroot_url . '\';
var presentation_project_common_webroot_url = \'' . $presentation_project_common_webroot_url . '\';
var system_project_webroot_url = \'' . $project_url_prefix . '\';
var system_project_common_webroot_url = \'' . $project_common_url_prefix . '\';

var templates_regions_html_url = \'' . $templates_regions_html_url . '\'; //used in widget: src/view/presentation/common_editor_widget/template_region/import_region_html.xml

var block_settings_obj = ' . json_encode($block_settings_obj) . ';
var load_module_settings_function = null;
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url); $head .= '</script>'; $head .= CMSPresentationLayerJoinPointsUIHandler::getHeader(); $main_content = '<div class="title">' . $title . '</div>
' . ($sub_title ? '<div class="sub_title">' . $sub_title . '</div>' : ''); if ($module) { if (!$module["enabled"]) $main_content .='<div class="invalid">Warning: This module is currently DISABLED!</div>'; if ($hard_coded) $main_content .='<div class="invalid">Alert: The system detected that the block id is different than the current file name. We advise you to edit this file with the Advanced UI, otherwise you may overwrite other people\'s changes...</div>'; $main_content .= WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, null, null, null, null, null, $presentation_brokers); $main_content .= '
	<div class="block_obj">
		<div class="module_data">
			<input type="hidden" name="module_id" value="' . $module_id . '" />
			<div class="module_img">' . ($module["images"][0]["url"] ? '<img src="' . $module["images"][0]["url"] . '" />' : '<span class="no_photo">No Photo</span>') . '</div>
			<div class="module_label">' . $module["label"] . '</div>
			<div class="module_description">
				' . str_replace("\n", "<br>", $module["description"]) . '
				' . ($exists_admin_panel ? '<a class="open_module_admin_panel_popup" href="javascript:void(0)" onClick="openModuleAdminPanelPopup()">Open ' . $module["group_id"] . ' admin panel</a>' : '') . '
			</div>
		</div>'; if ($module["settings_html"]) $main_content .= '
			<div class="module_settings">
				<label>Module\'s Settings:</label>
				<div class="settings">
					' . $module["settings_html"] . '
				</div>
			</div>'; $main_content .= CMSPresentationLayerJoinPointsUIHandler::getBlockJoinPointsJavascriptObjs($block_join_points, $block_local_join_points); $main_content .= CMSPresentationLayerJoinPointsUIHandler::getBlockJoinPointsHtml($module["join_points"], $block_id, !$obj_data["code"], $module["module_handler_impl_file_path"]); $main_content .= '
		<script>
			load_module_settings_function = ' . ($module["load_module_settings_js_function"] ? $module["load_module_settings_js_function"] : 'null') . ';
		</script>
		
		<div class="buttons">
			<input type="button" name="save" value="SAVE" onClick="saveBlock();" />
		</div>
	</div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the correspondent block\'s module. Please fix this on the advacend UI</div>'; function getPresentationProjectWebrootUrl($EVC, $user_global_variables_file_path) { $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $EVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); include $EVC->getConfigPath("config"); $url = $project_url_prefix; $PHPVariablesFileHandler->endUserGlobalVariables(); return $url; } function getPresentationProjectCommonWebrootUrl($EVC, $user_global_variables_file_path) { $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $EVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); include $EVC->getConfigPath("config"); $url = $project_common_url_prefix; $PHPVariablesFileHandler->endUserGlobalVariables(); return $url; } ?>
