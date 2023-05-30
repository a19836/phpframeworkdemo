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

include $EVC->getUtilPath("CMSPresentationLayerUIHandler"); include $EVC->getUtilPath("BreadCrumbsUIHandler"); $filter_by_layout_url_query = LayoutTypeProjectUIHandler::getFilterByLayoutURLQuery($filter_by_layout); $save_url = $project_url_prefix . "phpframework/presentation/save_template?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $get_block_handler_source_code_url = $project_url_prefix . "phpframework/presentation/get_module_handler_source_code?bean_name=$bean_name&bean_file_name=$bean_file_name&project=$path&block=#block#"; $get_page_block_join_points_html_url = $project_url_prefix . "phpframework/presentation/get_page_block_join_points_html?bean_name=$bean_name&bean_file_name=$bean_file_name&project=$path&block=#block#"; $get_available_blocks_list_url = $project_url_prefix . "phpframework/presentation/get_available_blocks_list?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $get_block_params_url = $project_url_prefix . "phpframework/presentation/get_block_params?bean_name=$bean_name&bean_file_name=$bean_file_name&project=#project#&block=#block#"; $edit_simple_template_layout_url = $project_url_prefix . "phpframework/presentation/edit_simple_template_layout?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&template=$selected_template"; $template_region_info_url = $project_url_prefix . "phpframework/presentation/template_region_info?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&region=#region#"; $template_samples_url = $project_url_prefix . "phpframework/presentation/template_samples?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $templates_regions_html_url = $project_url_prefix . "phpframework/presentation/templates_regions_html?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#$filter_by_layout_url_query&path=#path#"; $upload_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/upload_file?bean_name=#bean_name#&bean_file_name=#bean_file_name#$filter_by_layout_url_query&path=#path#"; $choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#"; $choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#"; $choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#"; $get_db_data_url = $project_url_prefix . "db/get_db_data?bean_name=#bean_name#&bean_file_name=#bean_file_name#&type=#type#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $create_page_module_block_url = $project_url_prefix . "phpframework/presentation/create_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $add_block_url = $project_url_prefix . "phpframework/presentation/edit_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&module_id=#module_id#&edit_block_type=simple"; $edit_block_url = $project_url_prefix . "phpframework/presentation/edit_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#&edit_block_type=simple"; $get_module_info_url = $project_url_prefix . "phpframework/presentation/get_module_info?module_id=#module_id#"; $create_page_presentation_uis_diagram_block_url = $project_url_prefix . "phpframework/presentation/create_page_presentation_uis_diagram_block?bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=" . str_replace("/src/", "/src/entity/", $path); $create_entity_code_url = $project_url_prefix . "phpframework/presentation/create_entity_code?project=$selected_project_id&default_extension=" . $P->getPresentationFileExtension(); $template_preview_html_url = $project_url_prefix . "phpframework/presentation/template_preview?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $head = '
<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS and JS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/layout.js"></script>

<!-- Add PHP CODE CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/edit_php_code.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_php_code.js"></script>

<!-- Add CodeHighLight CSS and JS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/codehighlight/styles/default.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/codehighlight/highlight.pack.js"></script>

<!-- Add local Responsive Iframe CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/responsive_iframe.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/responsive_iframe.js"></script>

<!-- Add local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_page_and_template.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_page_and_template.js"></script>

<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_template_simple.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_template_simple.js"></script>

<!-- Add Join Point CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/module_join_points.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/module_join_points.js"></script>

<script>
var layer_type = "pres";
var file_modified_time = ' . $file_modified_time . '; //for version control

var save_object_url = \'' . $save_url . '\';
var get_block_handler_source_code_url = \'' . $get_block_handler_source_code_url . '\';
var get_page_block_join_points_html_url = \'' . $get_page_block_join_points_html_url . '\';
var template_region_info_url = \'' . $template_region_info_url . '\';
var template_samples_url = \'' . $template_samples_url . '\';
var templates_regions_html_url = \'' . $templates_regions_html_url . '\'; //used in widget: src/view/presentation/common_editor_widget/template_region/import_region_html.xml

var create_page_module_block_url = \'' . $create_page_module_block_url . '\';
var add_block_url = \'' . $add_block_url . '\';
var edit_block_url = \'' . $edit_block_url . '\';
var get_module_info_url = \'' . $get_module_info_url . '\';

var template_preview_html_url = \'' . $template_preview_html_url . '\';

var layer_default_template = \'' . $selected_template . '\';
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url, $upload_bean_layer_files_from_file_manager_url); $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url); $head .= '</script>'; $head .= CMSPresentationLayerUIHandler::getHeader($project_url_prefix, $project_common_url_prefix, $get_available_blocks_list_url, $get_block_params_url, $create_entity_code_url, $available_blocks_list, $regions_blocks_list, $block_params_values_list, $includes, $blocks_join_points, $template_params_values_list, $selected_project_id, true); $head .= LayoutTypeProjectUIHandler::getHeader(); $query_string = str_replace(array("&edit_template_type=advanced", "&edit_template_type=simple"), "", $_SERVER["QUERY_STRING"]); $main_content = '
	<div class="top_bar advanced_items_shown">
		<header>
			<div class="title">Edit Template (Visual Workspace): ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($file_path, $P, true) . '</div>
			<ul>
				<li class="preview" data-title="Preview Template"><a onClick="preview()"><i class="icon view"></i> Preview Template</a></li>
				<li class="save" data-title="Save Template"><a onClick="saveTemplate()"><i class="icon save"></i> Save</a></li>
				<li class="sub_menu">
					<i class="icon sub_menu"></i>
					<ul>
						<li class="show_advanced_ui" title="Switch to Code Workspace"><a href="?' . $query_string . '&edit_template_type=advanced"><i class="icon show_advanced_ui"></i> Switch to Code Workspace</a></li>
						<li class="separator"></li>
						<li class="toggle_advanced_items" title="Toggle Advanced Items"><a onClick="toggleAdvancedItems(this)"><i class="icon toggle_advanced_items"></i> <span>Hide Advanced Items</span> <input type="checkbox" checked/></a></li>
						<li class="toggle_main_settings" title="Toggle Main Settings"><a onClick="toggleSettingsPanel(this)"><i class="icon toggle_main_settings"></i> <span>Show Main Settings</span> <input type="checkbox"/></a></li>
						<!--li class="toggle_widget_settings" title="Toggle Widget Settings"><a onClick="toggleWidgetSettings(this)"><i class="icon toggle_widget_settings"></i> <span>Show Widget Settings</span> <input type="checkbox"/></a></li-->
						<li class="separator"></li>
						<li class="flip_layout_ui_panels" title="Flip Layout UI Panels"><a onClick="flipCodeLayoutUIEditorPanelsSide(this)"><i class="icon flip_layout_ui_panels"></i> Flip Layout UI Panels</a></li>
						<li class="separator"></li>
						<li class="update_layout_from_settings" title="Update Settings to Layout UI"><a onClick="updateCodeEditorLayoutFromSettings( $(\'.template_obj\') )"><i class="icon update_layout_from_settings"></i> Update Settings to Layout UI</a></li>
						<li class="separator"></li>
						<li class="view_template_samples" title="View Template Samples"><a onClick="openTemplateSamples()"><i class="icon view_template_samples"></i> View Template Samples</a></li>
						<li class="preview" title="Preview"><a onClick="preview()"><i class="icon view"></i> Preview Template</a></li>
						<li class="separator"></li>
						<li class="full_screen" title="Maximize/Minimize Editor Screen"><a onClick="toggleFullScreen(this)"><i class="icon full_screen"></i> Maximize Editor Screen</a></li>
						<li class="separator"></li>
						<li class="save" title="Save Template"><a onClick="saveTemplate()"><i class="icon save"></i> Save</a></li>
					</ul>
				</li>
			</ul>
		</header>
	</div>'; if ($obj_data) { $code = $obj_data["code"]; $doc_type_props = WorkFlowPresentationHandler::getHtmlTagProps($code, "!DOCTYPE"); $html_props = WorkFlowPresentationHandler::getHtmlTagProps($code, "html"); $head_props = WorkFlowPresentationHandler::getHtmlTagProps($code, "head", array("get_inline_code" => true)); $body_props = WorkFlowPresentationHandler::getHtmlTagProps($code, "body", array("get_inline_code" => true)); $code_exists = !empty(trim($code)); if ($code_exists && !$html_props["inline_code"] && !$head_props["inline_code"] && !$body_props["inline_code"]) $body_props["inline_code"] = $code; $is_code_valid = !$code_exists || $html_props["inline_code"] || $html_props["html_attributes"] || $head_props["inline_code"] || $head_props["html_attributes"] || $body_props["inline_code"] || $body_props["html_attributes"]; $main_content .= WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, null, null, null, null, null, $presentation_brokers); $main_content .= CMSPresentationLayerUIHandler::getChoosePresentationIncludeFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $presentation_brokers); $common_webroot_path = $EVC->getWebrootPath($EVC->getCommonProjectName()); $ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($common_webroot_path, $project_common_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/common_editor_widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($common_webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $template_region_block_html_editor_ui_menu_widgets_html = $ui_menu_widgets_html; $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/view_editor_widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/template_editor_widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $main_content .= CMSPresentationLayerUIHandler::getTemplateRegionBlockHtmlEditorPopupHtml($template_region_block_html_editor_ui_menu_widgets_html); $main_content .= '
	<div class="template_obj with_top_bar_tab inactive advanced_items_shown">
		<ul class="tabs tabs_transparent tabs_right tabs_icons">
			<li id="code_editor_layout_tab" title="Content Editor"><a href="#code_editor_layout" onclick="updateCodeEditorLayoutFromMainTab(this);"><i class="icon code_editor_layout_tab"></i> Content Editor</a></li>
			<li id="code_editor_body_tab" title="Design Editor"><a href="#code_editor_body"><i class="icon code_editor_body_tab"></i> Design Editor</a></li>
		</ul>
		
		<script>
			var doc_type_tag_attributes_str = \'' . str_replace("\n", '\n', addcslashes($doc_type_props["html_attributes"], "\\'")) . '\';
			var html_tag_attributes_str = \'' . str_replace("\n", '\n', addcslashes($html_props["html_attributes"], "\\'")) . '\';
			var head_tag_attributes_str = \'' . str_replace("\n", '\n', addcslashes($head_props["html_attributes"], "\\'")) . '\';
			var body_tag_attributes_str = \'' . str_replace("\n", '\n', addcslashes($body_props["html_attributes"], "\\'")) . '\';
			
			var is_code_valid = ' . ($is_code_valid ? 1 : 0) . ';
		</script>
		
		<div id="code_editor_body" class="code_editor_body code_layout_ui_editor">
			' . (!$is_code_valid ? '<div class="invalid">Note that some of the main tags (html, head or body) have attributes or dynamic code inside. If you continue editing this file through this editor, it may loose some data.<br/>We recommend you to edit this file through the "<a href="?' . $query_string . '&edit_template_type=advanced">Advanced Editor</a>" instead.<span class="icon close" onClick="$(this).parent().hide();"></span></div>' : '') . '
			
			' . WorkFlowPresentationHandler::getCodeEditorHtml(trim($body_props["inline_code"]), array("save_func" => "saveTemplate", "show_pretty_print" => false), $ui_menu_widgets_html, $user_global_variables_file_path, $user_beans_folder_path, $PEVC, $UserAuthenticationHandler, $bean_name, $bean_file_name, $brokers_db_drivers, $choose_bean_layer_files_from_file_manager_url, $get_db_data_url, $create_page_presentation_uis_diagram_block_url, "chooseCodeLayoutUIEditorModuleBlockFromFileManagerTreeRightContainer") . '
		</div>'; $template_region_blocks = array_map(function($n) { return ""; }, array_flip($available_regions_list)); if ($regions_blocks_list) foreach ($regions_blocks_list as $rbl) { if (!is_array($template_region_blocks[ $rbl[0] ])) $template_region_blocks[ $rbl[0] ] = array(); $template_region_blocks[ $rbl[0] ][] = $rbl; } $template_includes = array_map(function($include) { $inc_path = PHPUICodeExpressionHandler::getArgumentCode($include["path"], $include["path_type"]); return array("path" => $inc_path, "once" => $include["once"]); }, $includes); $qs = array( "template_regions" => $template_region_blocks, "template_params" => $template_params_values_list, "template_includes" => $template_includes, ); $iframe_url = $edit_simple_template_layout_url . "&data=" . urlencode(json_encode($qs)); $reverse_class = $_COOKIE["main_navigator_side"] == "main_navigator_reverse" ? "" : "reverse"; $main_content .= '		
		<div id="code_editor_layout" class="code_editor_layout tab_content_template_layout ' . $reverse_class . ' toolbar_open">
			' . CMSPresentationLayerUIHandler::getTabContentTemplateLayoutHtml($user_global_variables_file_path, $user_beans_folder_path, $PEVC, $UserAuthenticationHandler, $bean_name, $bean_file_name, $iframe_url, $edit_simple_template_layout_url, $choose_bean_layer_files_from_file_manager_url, $get_db_data_url, $create_page_presentation_uis_diagram_block_url, "iframeModulesBlocksToolbarTree") . '
		</div>
		
		<div id="preview" class="myfancypopup"><iframe orig_src="' . $template_preview_html_url . '"></iframe></div>
		
		<div class="regions_blocks_includes_settings_overlay"></div>
		<div class="code_editor_settings regions_blocks_includes_settings collapsed" id="code_editor_settings">
			<div class="settings_header">
				Main Settings
				<div class="icon maximize" onClick="toggleSettingsPanel(this)">Toggle</div>
			</div>
			
			<a class="update_automatically" href="javascript:void(0)" onClick="updateRegionsFromBodyEditor();" title="Update regions from the Body-Code-Editor above">
				<i class="icon update_automatically"></i>
				Update settings from Body-Code-Editor
			</a>
			
		' . CMSPresentationLayerUIHandler::getRegionsBlocksAndIncludesHtml($selected_template, $available_regions_list, $regions_blocks_list, $available_blocks_list, $available_block_params_list, $block_params_values_list, $includes, $available_params_list, $template_params_values_list) . '
		
			<div class="head">
				<label>Head Code:</label>
				<textarea>' . htmlspecialchars(trim($head_props["inline_code"]), ENT_NOQUOTES) . '</textarea>
			</div>
		</div>
	</div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the selected file. Please refresh and try again...</div>'; ?>
