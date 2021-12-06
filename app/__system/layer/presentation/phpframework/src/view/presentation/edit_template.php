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

include $EVC->getUtilPath("CMSPresentationLayerUIHandler"); $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowUIHandler->setTasksGroupsByTag(array( "Logic" => array("definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "getbeanobject"), "Connectors" => array("restconnector", "soapconnector"), "Exception" => array("trycatchexception", "throwexception", "printexception"), "Layers" => array("callpresentationlayerwebservice"), "HTML" => array("inlinehtml", "createform"), "CMS" => array("setblockparams", "settemplateregionblockparam", "includeblock", "addtemplateregionblock", "rendertemplateregion", "settemplateparam", "gettemplateparam"), )); $WorkFlowUIHandler->addFoldersTasksToTasksGroups($code_workflow_editor_user_tasks_folders_path); $save_url = $project_url_prefix . 'phpframework/presentation/save_template?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path; $get_block_handler_source_code_url = $project_url_prefix . "phpframework/presentation/get_module_handler_source_code?bean_name=$bean_name&bean_file_name=$bean_file_name&project=$path&block=#block#"; $get_page_block_join_points_html_url = $project_url_prefix . "phpframework/presentation/get_page_block_join_points_html?bean_name=$bean_name&bean_file_name=$bean_file_name&project=$path&block=#block#"; $path_extra = hash('crc32b', "$bean_file_name/$bean_name/$path"); $get_workflow_tasks_id = "presentation_workflow&path_extra=_$path_extra"; $get_tmp_workflow_tasks_id = "presentation_workflow_tmp&path_extra=_${path_extra}_" . rand(0, 1000); $set_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_workflow_tasks_id}"; $get_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_workflow_tasks_id}"; $create_workflow_file_from_code_url = $project_url_prefix . "workflow/create_workflow_file_from_code?path=${get_tmp_workflow_tasks_id}&loaded_tasks_settings_cache_id=" . $WorkFlowTaskHandler->getLoadedTasksSettingsCacheId(); $get_tmp_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_tmp_workflow_tasks_id}"; $create_code_from_workflow_file_url = $project_url_prefix . "workflow/create_code_from_workflow_file?path=${get_tmp_workflow_tasks_id}"; $set_tmp_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_tmp_workflow_tasks_id}"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#"; $choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#"; $choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#"; $get_db_data_url = $project_url_prefix . "db/get_db_data?bean_name=#bean_name#&bean_file_name=#bean_file_name#&type=#type#"; $modules_path = $EVC->getCommonProjectName() . "/" . $EVC->getPresentationLayer()->settings["presentation_modules_path"]; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $create_entity_code_url = $project_url_prefix . "phpframework/presentation/create_entity_code?project=$selected_project_id&default_extension=" . $P->getPresentationFileExtension(); $create_page_presentation_uis_diagram_block_url = $project_url_prefix . "phpframework/presentation/create_page_presentation_uis_diagram_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . str_replace("/src/", "/src/entity/", $path); $get_available_blocks_list_url = $project_url_prefix . "phpframework/presentation/get_available_blocks_list?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $get_block_params_url = $project_url_prefix . "phpframework/presentation/get_block_params?bean_name=$bean_name&bean_file_name=$bean_file_name&project=#project#&block=#block#"; $create_page_module_block_url = $project_url_prefix . "phpframework/presentation/create_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $add_block_url = $project_url_prefix . "phpframework/presentation/edit_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&module_id=#module_id#&edit_block_type=simple"; $edit_block_url = $project_url_prefix . "phpframework/presentation/edit_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#&edit_block_type=simple"; $get_module_info_url = $project_url_prefix . "phpframework/presentation/get_module_info?module_id=#module_id#"; $template_preview_html_url = $project_url_prefix . "phpframework/presentation/template_preview?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $edit_simple_template_layout_url = $project_url_prefix . "phpframework/presentation/edit_simple_template_layout?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&template=$selected_template"; $template_region_info_url = $project_url_prefix . "phpframework/presentation/template_region_info?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&region=#region#"; $template_samples_url = $project_url_prefix . "phpframework/presentation/template_samples?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $templates_regions_html_url = $project_url_prefix . "phpframework/presentation/templates_regions_html?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $head = WorkFlowPresentationHandler::getHeader($project_url_prefix, $project_common_url_prefix, $WorkFlowUIHandler, $set_workflow_file_url, true); $head .= '
<!-- Add local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_page_and_template.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_page_and_template.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/responsive_iframe.js"></script>

<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_template.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_template.js"></script>

<!-- Add Join Point CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/module_join_points.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/module_join_points.js"></script>

<script>
var layer_type = "pres";
var selected_project_id = "' . $selected_project_id . '";
var file_modified_time = ' . $file_modified_time . '; //for version control

var get_workflow_file_url = \'' . $get_workflow_file_url . '\';
var save_object_url = \'' . $save_url . '\';
var get_block_handler_source_code_url = \'' . $get_block_handler_source_code_url . '\';
var get_page_block_join_points_html_url = \'' . $get_page_block_join_points_html_url . '\';
var create_workflow_file_from_code_url = \'' . $create_workflow_file_from_code_url . '\';
var get_tmp_workflow_file_url = \'' . $get_tmp_workflow_file_url . '\';
var create_code_from_workflow_file_url = \'' . $create_code_from_workflow_file_url . '\';
var set_tmp_workflow_file_url = \'' . $set_tmp_workflow_file_url . '\';
var template_preview_html_url = \'' . $template_preview_html_url . '\';

var template_region_info_url = \'' . $template_region_info_url . '\';
var template_samples_url = \'' . $template_samples_url . '\';
var templates_regions_html_url = \'' . $templates_regions_html_url . '\'; //used in widget: src/view/presentation/common_editor_widget/template_region/import_region_html.xml

var create_page_module_block_url = \'' . $create_page_module_block_url . '\';
var add_block_url = \'' . $add_block_url . '\';
var edit_block_url = \'' . $edit_block_url . '\';
var get_module_info_url = \'' . $get_module_info_url . '\';

ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onProgrammingTaskChooseCreatedVariable;
ProgrammingTaskUtil.on_programming_task_choose_object_property_callback = onProgrammingTaskChooseObjectProperty;
ProgrammingTaskUtil.on_programming_task_choose_object_method_callback = onProgrammingTaskChooseObjectMethod;
ProgrammingTaskUtil.on_programming_task_choose_function_callback = onProgrammingTaskChooseFunction;
ProgrammingTaskUtil.on_programming_task_choose_class_name_callback = onProgrammingTaskChooseClassName;

IncludeFileTaskPropertyObj.on_choose_file_callback = onIncludeFileTaskChooseFile;
SetVarTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;

GetUrlContentsTaskPropertyObj.on_choose_page_callback = onIncludePageUrlTaskChooseFile;
SoapConnectorTaskPropertyObj.on_choose_page_callback = onIncludePageUrlTaskChooseFile;

CreateFormTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
CreateFormTaskPropertyObj.on_choose_image_url_callback = onIncludeImageUrlTaskChooseFile;
InlineHTMLTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
InlineHTMLTaskPropertyObj.on_choose_image_url_callback = onIncludeImageUrlTaskChooseFile

FunctionUtilObj.set_tmp_workflow_file_url = set_tmp_workflow_file_url;
FunctionUtilObj.get_tmp_workflow_file_url = get_tmp_workflow_file_url;
FunctionUtilObj.create_code_from_workflow_file_url = create_code_from_workflow_file_url;
FunctionUtilObj.create_workflow_file_from_code_url = create_workflow_file_from_code_url;

callPresentationLayerWebServiceTaskPropertyObj.on_choose_file_callback = onIncludeFileTaskChooseFile;
callPresentationLayerWebServiceTaskPropertyObj.on_choose_page_callback = onPresentationTaskChoosePage;
callPresentationLayerWebServiceTaskPropertyObj.brokers_options = ' . json_encode($presentation_brokers_obj) . ';

GetTemplateParamTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
SetTemplateParamTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
SetTemplateParamTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
SetBlockParamsTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
SetTemplateRegionBlockParamTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
RenderTemplateRegionTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
AddTemplateRegionBlockTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
IncludeBlockTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC')) . ';
IncludeBlockTaskPropertyObj.projects_options = ' . json_encode($available_projects) . ';
GetBeanObjectTaskPropertyObj.phpframeworks_options = ' . json_encode($phpframeworks_options) . ';
GetBeanObjectTaskPropertyObj.bean_names_options = ' . json_encode($bean_names_options) . ';
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url); $head .= '</script>'; $head .= CMSPresentationLayerUIHandler::getHeader($project_url_prefix, $project_common_url_prefix, $get_available_blocks_list_url, $get_block_params_url, $create_entity_code_url, $available_blocks_list, $regions_blocks_list, $block_params_values_list, $blocks_join_points, $template_params_values_list, $selected_project_id, true); $main_content = '<div class="title">Edit Template "' . basename($path) . '"</div>'; if ($obj_data) { $code = $obj_data["code"]; $doc_type_props = WorkFlowPresentationHandler::getHtmlTagProps($code, "!DOCTYPE"); $html_props = WorkFlowPresentationHandler::getHtmlTagProps($code, "html"); $head_props = WorkFlowPresentationHandler::getHtmlTagProps($code, "head", array("get_inline_code" => true)); $body_props = WorkFlowPresentationHandler::getHtmlTagProps($code, "body", array("get_inline_code" => true)); $code_exists = !empty(trim($code)); $is_body_code_valid = !$code_exists || $html_props["inline_code"] || $html_props["html_attributes"] || $head_props["inline_code"] || $head_props["html_attributes"] || $body_props["inline_code"] || $body_props["html_attributes"]; $common_webroot_path = $EVC->getWebrootPath($EVC->getCommonProjectName()); $ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($common_webroot_path, $project_common_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/common_editor_widget/", $project_url_prefix . "widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($common_webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $template_region_block_html_editor_ui_menu_widgets_html = $ui_menu_widgets_html; $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/view_editor_widget/", $project_url_prefix . "widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/template_editor_widget/", $project_url_prefix . "widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $main_content .= WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, null, null, null, null, null, $presentation_brokers); $main_content .= CMSPresentationLayerUIHandler::getChoosePresentationIncludeFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $presentation_brokers); $main_content .= CMSPresentationLayerUIHandler::getTemplateRegionBlockHtmlEditorPopupHtml($template_region_block_html_editor_ui_menu_widgets_html); $main_content .= '
	<div class="template_obj">
		<ul>
			<li id="code_editor_tab"><a href="#code_editor">Simple Editor</a></li>
			<li id="raw_editor_tab"><a href="#code" onClick="onClickCodeRawEditorTab(this);return false;">Raw Editor</a></li>
			<li id="tasks_flow_tab"><a href="#ui" onClick="onClickTaskWorkflowTab(this);return false;">Workflow</a></li>
			<li id="preview_tab"><a href="#preview" onClick="onClickPreviewTab(this);return false;">Preview</a></li>
		</ul>
		
		<div id="code_editor">
			<ul class="tabs">
				<li><a href="#code_editor_contents" onclick="updateCodeEditorLayoutFromMainTab(this);">Regions Settings Editor</a></li>
				<li><a href="#code_editor_body">Body Code Editor</a></li>
				<li><a href="#code_editor_head">Head Code Editor</a></li>
			</ul>
			
			<script>
				var doc_type_tag_attributes_str = \'' . str_replace("\n", '\n', addcslashes($doc_type_props["html_attributes"], "\\'")) . '\';
				var html_tag_attributes_str = \'' . str_replace("\n", '\n', addcslashes($html_props["html_attributes"], "\\'")) . '\';
				var head_tag_attributes_str = \'' . str_replace("\n", '\n', addcslashes($head_props["html_attributes"], "\\'")) . '\';
				var body_tag_attributes_str = \'' . str_replace("\n", '\n', addcslashes($body_props["html_attributes"], "\\'")) . '\';
				
				var is_body_code_valid = ' . ($is_body_code_valid ? 1 : 0) . ';
			</script>
			
			<div id="code_editor_head" class="head">
				<label>Head Code:</label>
				<div class="code_menu">
					' . WorkFlowPresentationHandler::getCodeEditorMenuHtml(array("save_func" => "saveTemplate", "show_pretty_print" => false)) . '
				</div>
				<textarea>' . htmlspecialchars(trim($head_props["inline_code"]), ENT_NOQUOTES) . '</textarea>
			</div>
			<div id="code_editor_body" class="body code_layout_ui_editor">
				<label>Body Code:</label>
				<!--label>Body Code:' . (!$is_body_code_valid ? '<span class="error">The system couldn\'t load correctly this HTML, so we cannot show the wysiwyg editor. Please edit the html in this editor instead:</span>' : '') . '</label-->
				
				' . WorkFlowPresentationHandler::getCodeEditorHtml(trim($body_props["inline_code"]), array("save_func" => "saveTemplate", "show_pretty_print" => false), $ui_menu_widgets_html, $user_global_variables_file_path, $user_beans_folder_path, $PEVC, $bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $get_db_data_url, $create_page_presentation_uis_diagram_block_url, "chooseCodeLayoutUIEditorModuleBlockFromFileManagerTreeRightContainer") . '
			</div>
			
			<div class="clearfix"></div>
			<div id="code_editor_contents" class="code_editor_contents">
				<ul class="tabs">
					<li><a class="inactive" href="#code_editor_layout" onClick="updateCodeEditorLayoutFromSettings(this)">Layout</a></li>
					<li class="' . ($code_exists ? "" : "ui-tabs-active") . '"><a class="inactive" href="#code_editor_settings" onClick="updateCodeEditorSettingsFromLayout(this)">Settings</a></li>
				</ul>
	'; $template_region_blocks = array_map(function($n) { return ""; }, array_flip($available_regions_list)); if ($regions_blocks_list) foreach ($regions_blocks_list as $rbl) { if (!is_array($template_region_blocks[ $rbl[0] ])) $template_region_blocks[ $rbl[0] ] = array(); $template_region_blocks[ $rbl[0] ][] = $rbl; } $template_includes = array_map(function($include) { $inc_path = CMSPresentationLayerHandler::getArgumentCode($include["path"], $include["path_type"]); return array("path" => $inc_path, "once" => $include["once"]); }, $includes); $qs = array( "template_regions" => $template_region_blocks, "template_params" => $template_params_values_list, "template_includes" => $template_includes, ); $iframe_url = $edit_simple_template_layout_url . "&data=" . urlencode(json_encode($qs)); $main_content .= '		
				<div class="code_editor_layout tab_content_template_layout" id="code_editor_layout">
					' . CMSPresentationLayerUIHandler::getTabContentTemplateLayoutHtml($user_global_variables_file_path, $user_beans_folder_path, $PEVC, $bean_name, $bean_file_name, $iframe_url, $edit_simple_template_layout_url, $choose_bean_layer_files_from_file_manager_url, $get_db_data_url, $create_page_presentation_uis_diagram_block_url, "iframeModulesBlocksToolbarTree") . '
				</div>
				
				<div class="code_editor_settings regions_blocks_includes_settings" id="code_editor_settings">
					<label class="advanced_settings_label">Advanced Settings:</label>
					<span class="icon update" onClick="updateRegionsFromBodyEditor();" title="Update regions from the Body-Code-Editor above">Update settings from Body-Code-Editor</span>
				
				' . CMSPresentationLayerUIHandler::getRegionsBlocksAndIncludesHtml($selected_template, $available_regions_list, $regions_blocks_list, $available_blocks_list, $available_block_params_list, $block_params_values_list, $includes, $available_params_list, $template_params_values_list) . '
				</div>
			</div>
			
			<div class="buttons">
				<input type="button" name="save" value="SAVE" onClick="saveTemplate();" />
			</div>
		</div>
		
		<div id="code">
			<div class="code_menu">
				' . WorkFlowPresentationHandler::getCodeEditorMenuHtml(array("save_func" => "saveTemplate", "show_pretty_print" => false)) . '
			</div>
			<textarea>' . htmlspecialchars($obj_data["code"], ENT_NOQUOTES) . '</textarea>
		</div>
		
		<div id="ui">' . WorkFlowPresentationHandler::getTaskFlowContentHtml($WorkFlowUIHandler, array("save_func" => "saveTemplate")) . '</div>
		
		<div id="preview"><iframe orig_src="' . $template_preview_html_url . '"></iframe></div>
		
		<div class="ui-menu-widgets-backup hidden">
			' . $ui_menu_widgets_html . '
		</div>
		<script>
			var mwb = $(".template_obj > .ui-menu-widgets-backup");
			$(".template_obj > #ui > .taskflowchart > .tasks_properties > .task_properties > .create_form_task_html > .ptl_settings > .layout_ui_editor > .menu-widgets").append( mwb.contents().clone() );
			$(".template_obj > #ui > .taskflowchart > .tasks_properties > .task_properties > .inlinehtml_task_html > .layout_ui_editor > .menu-widgets").append( mwb.contents() );
			mwb.remove();
		</script>
	</div>
	<div class="big_white_panel"></div>
	<div class="hide_show_header minimize" onClick="toggleHeader(this)" title="Minimize/Maximize"></div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the selected file. Please refresh and try again...</div>'; ?>
