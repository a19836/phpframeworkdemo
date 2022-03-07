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

include $EVC->getUtilPath("CMSPresentationLayerUIHandler"); $filter_by_layout_url_query = LayoutTypeProjectUIHandler::getFilterByLayoutURLQuery($filter_by_layout); $save_url = $project_url_prefix . "phpframework/presentation/save_entity_simple?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $page_preview_url = $project_url_prefix . "phpframework/presentation/test_project?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $view_project_url = $project_url_prefix . "phpframework/presentation/view_project?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $get_block_handler_source_code_url = $project_url_prefix . "phpframework/presentation/get_module_handler_source_code?bean_name=$bean_name&bean_file_name=$bean_file_name&project=$path&block=#block#"; $get_page_block_join_points_html_url = $project_url_prefix . "phpframework/presentation/get_page_block_join_points_html?bean_name=$bean_name&bean_file_name=$bean_file_name&project=$path&block=#block#"; $get_available_templates_props_url = $project_url_prefix . "phpframework/presentation/get_available_templates_props?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#"; $get_available_blocks_list_url = $project_url_prefix . "phpframework/presentation/get_available_blocks_list?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $get_block_params_url = $project_url_prefix . "phpframework/presentation/get_block_params?bean_name=$bean_name&bean_file_name=$bean_file_name&project=#project#&block=#block#"; $get_template_regions_and_params_url = $project_url_prefix . "phpframework/presentation/get_template_regions_and_params?bean_name=$bean_name&bean_file_name=$bean_file_name&project=$selected_project_id&template=#template#"; $edit_simple_template_layout_url = $project_url_prefix . "phpframework/presentation/edit_simple_template_layout?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $template_region_info_url = $project_url_prefix . "phpframework/presentation/template_region_info?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$selected_project_id/" . $P->settings["presentation_templates_path"] . "#template#.php&region=#region#"; $template_samples_url = $project_url_prefix . "phpframework/presentation/template_samples?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$selected_project_id/" . $P->settings["presentation_templates_path"] . "#template#.php"; $templates_regions_html_url = $project_url_prefix . "phpframework/presentation/templates_regions_html?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$selected_project_id"; $install_template_url = $project_url_prefix . "phpframework/presentation/install_template?bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=$selected_project_id/src/template/"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=#path#"; $choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#"; $choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#"; $choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#"; $create_page_module_block_url = $project_url_prefix . "phpframework/presentation/create_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $add_block_url = $project_url_prefix . "phpframework/presentation/edit_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&module_id=#module_id#&edit_block_type=simple"; $edit_block_url = $project_url_prefix . "phpframework/presentation/edit_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#&edit_block_type=simple"; $get_module_info_url = $project_url_prefix . "phpframework/presentation/get_module_info?module_id=#module_id#"; $get_db_data_url = $project_url_prefix . "db/get_db_data?bean_name=#bean_name#&bean_file_name=#bean_file_name#&type=#type#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $create_entity_code_url = $project_url_prefix . "phpframework/presentation/create_entity_code?project=$selected_project_id&default_extension=" . $P->getPresentationFileExtension(); $create_page_presentation_uis_diagram_block_url = $project_url_prefix . "phpframework/presentation/create_page_presentation_uis_diagram_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $confirm_save = $obj_data["code"] && $cached_modified_date != $file_modified_time; $head = '
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

<!-- Top-Bar CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/top_bar.css" type="text/css" charset="utf-8" />

<!-- Add PHP CODE CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/edit_php_code.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_code.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_php_code.js"></script>

<!-- Add CodeHighLight CSS and JS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/codehighlight/styles/default.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/codehighlight/highlight.pack.js"></script>

<!-- Add local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_page_and_template.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_page_and_template.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/responsive_iframe.js"></script>

<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_entity_simple.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_entity_simple.js"></script>

<!-- Add Join Point CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/module_join_points.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/module_join_points.js"></script>

<!-- Add Choose AvailableTemplate CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/choose_available_template.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/choose_available_template.js"></script>

<script>
var layer_type = "pres";
var selected_project_id = "' . $selected_project_id . '";
var file_modified_time = ' . $file_modified_time . '; //for version control

var save_object_url = \'' . $save_url . '\';
var page_preview_url = \'' . $page_preview_url . '\';
var get_block_handler_source_code_url = \'' . $get_block_handler_source_code_url . '\';
var get_page_block_join_points_html_url = \'' . $get_page_block_join_points_html_url . '\';
var get_template_regions_and_params_url = \'' . $get_template_regions_and_params_url . '\';
var template_region_info_url = \'' . $template_region_info_url . '\';
var template_samples_url = \'' . $template_samples_url . '\';
var templates_regions_html_url = \'' . $templates_regions_html_url . '\'; //used in widget: src/view/presentation/common_editor_widget/template_region/import_region_html.xml
var install_template_url = \'' . $install_template_url . '\';
var get_available_templates_props_url = \'' . $get_available_templates_props_url . '\';

var create_page_module_block_url = \'' . $create_page_module_block_url . '\';
var add_block_url = \'' . $add_block_url . '\';
var edit_block_url = \'' . $edit_block_url . '\';
var get_module_info_url = \'' . $get_module_info_url . '\';

var layer_default_template = \'' . $layer_default_template . '\';
var available_templates_props = ' . json_encode($available_templates_props) . ';
var available_projects_props = ' . json_encode($available_projects_props) . ';
var show_templates_only = ' . ($_GET["show_templates_only"] ? 1 : 0) . '; //This is set when we switch the entity advanced ui to the simple ui.
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url); $head .= '</script>'; $head .= CMSPresentationLayerUIHandler::getHeader($project_url_prefix, $project_common_url_prefix, $get_available_blocks_list_url, $get_block_params_url, $create_entity_code_url, $available_blocks_list, $regions_blocks_list, $block_params_values_list, $blocks_join_points, $template_params_values_list, $selected_project_id, true); $head .= LayoutTypeProjectUIHandler::getHeader(); $query_string = str_replace(array("&edit_entity_type=advanced", "&edit_entity_type=simple"), "", $_SERVER["QUERY_STRING"]); $main_content = '
	<div class="top_bar">
		<header>
			<div class="title">Edit Page "<span class="file_name" title="' . $entity_view_code . '">' . $entity_view_code . '</span>"</div>
			<ul>
				<li class="advanced_editor" title="Switch to Free Html Editor"><a href="?' . $query_string . '&edit_entity_type=advanced"><i class="icon show_advanced_ui"></i> Switch to Free Html Editor</a></li>
				<li class="view_project_page" title="View Project Page"><a href="' . $view_project_url . '" target="project"><i class="icon view"></i></a></li>
				<li class="preview" title="Preview & Test Page"><a onClick="preview()"><i class="icon preview_file"></i> Preview & Test Page</a></li>
				<li class="full_screen" title="Toggle Full Screen"><a onClick="toggleFullScreen(this)"><i class="icon full_screen"></i> Full Screen</a></li>
				<li class="save" title="Save Page"><a onClick="' . ($confirm_save ? 'confirmSave' : 'save') . '()"><i class="icon save"></i> Save</a></li>
				<li class="save_preview" title="Save & Preview Page"><a onClick="saveAndPreview(' . ($confirm_save ? 'true' : 'false') . ');"><i class="icon save_preview_file"></i> Save & Preview Page</a></li>
			</ul>
		</header>
	</div>'; if ($obj_data) { $code_exists = !empty(trim($obj_data["code"])); $main_content .= '
	<script>	
	var code_exists = ' . ($code_exists ? 1 : 0) . ';
	</script>'; if ($hard_coded) $main_content .='<div class="invalid">This file was probably changed manually. If you continue editing this file through this editor, it may loose some data.<br/>We recommend you to edit this file through the "<a href="?' . $query_string . '&edit_entity_type=advanced">Advanced Editor</a>" instead.<span class="icon close" onClick="$(this).parent().hide();"></span></div>'; $common_webroot_path = $EVC->getWebrootPath($EVC->getCommonProjectName()); $ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($common_webroot_path, $project_common_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/common_editor_widget/", $project_url_prefix . "widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($common_webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $main_content .= WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, null, null, null, null, null, $presentation_brokers); $main_content .= CMSPresentationLayerUIHandler::getChoosePresentationIncludeFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $presentation_brokers); $main_content .= CMSPresentationLayerUIHandler::getTemplateRegionBlockHtmlEditorPopupHtml($ui_menu_widgets_html); $main_content .= '<div id="choose_project_template_url_from_file_manager" class="myfancypopup choose_from_file_manager">
		<ul class="mytree">
			<li>
				<label>' . $presentation_brokers[0][0] . '</label>
				<ul url="' . $choose_bean_layer_files_from_file_manager_url . '"></ul>
			</li>
		</ul>
		<div class="button">
			<input type="button" value="UPDATE" onClick="MyFancyPopup.settings.updateFunction(this)" />
		</div>
	</div>'; $main_content .= '<div class="entity_obj inactive">
		' . getTemplatesHtml($set_template, $selected_template, $available_templates, $installed_wordpress_folders_name) . '
		
		<div class="entity_obj_tabs">
			<!--ul class="tabs">
				<li><a class="inactive" href="#entity_template_layout" onClick="updateLayoutFromSettings($(\'.entity_obj\'))">Layout</a></li>
				<li class="' . ($code_exists ? "" : "ui-tabs-active") . '"><a class="inactive" href="#entity_template_settings" onClick="updateSettingsFromLayout($(\'.entity_obj\'))">Settings</a></li>
			</ul-->
			
			<div class="regions_blocks_includes_settings_overlay"></div>
			<div class="entity_template_settings regions_blocks_includes_settings collapsed" id="entity_template_settings">
				<div class="settings_header">
					Settings
					<div class="icon maximize" onClick="toggleSettingsPanel(this)" title="Toggle Settings">Toggle</i></div>
				</div>
				
				' . CMSPresentationLayerUIHandler::getRegionsBlocksAndIncludesHtml($selected_or_default_template, $available_regions_list, $regions_blocks_list, $available_blocks_list, $available_block_params_list, $block_params_values_list, $includes, $available_params_list, $template_params_values_list) . '
			</div>'; $template_region_blocks = array_map(function($n) { return ""; }, array_flip($available_regions_list)); if ($regions_blocks_list) foreach ($regions_blocks_list as $rbl) { if (!is_array($template_region_blocks[ $rbl[0] ])) $template_region_blocks[ $rbl[0] ] = array(); $template_region_blocks[ $rbl[0] ][] = $rbl; } $template_includes = array_map(function($include) { $inc_path = CMSPresentationLayerHandler::getArgumentCode($include["path"], $include["path_type"]); return array("path" => $inc_path, "once" => $include["once"]); }, $includes); $qs = array( "template" => $selected_or_default_template, "template_regions" => $template_region_blocks, "template_params" => $template_params_values_list, "template_includes" => $template_includes, "is_external_template" => $is_external_template, "external_template_params" => $set_template["template_params"], ); $iframe_url = $edit_simple_template_layout_url . "&data=" . urlencode(json_encode($qs)); $main_content .= '
			<div class="entity_template_layout tab_content_template_layout" id="entity_template_layout">
				' . CMSPresentationLayerUIHandler::getTabContentTemplateLayoutHtml($user_global_variables_file_path, $user_beans_folder_path, $PEVC, $UserAuthenticationHandler, $bean_name, $bean_file_name, $iframe_url, $edit_simple_template_layout_url, $choose_bean_layer_files_from_file_manager_url, $get_db_data_url, $create_page_presentation_uis_diagram_block_url, "iframeModulesBlocksToolbarTree") . '
			</div>
		</div>
	</div>
	
	<div class="current_entity_code hidden">' . str_replace(">", "&gt;", str_replace("<", "&lt;", $obj_data["code"])) . '</div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the selected file. Please refresh and try again...</div>'; function getTemplatesHtml($pb46d1829, $v7b2ad4afbf, $paf93610a, $v32c1e58e0c) { if (is_array($pb46d1829["template_params"])) { $pa1cddb9c = is_array($pb46d1829["template_params"]["type"]) ? $pb46d1829["template_params"]["type"]["value"] : null; if ($pa1cddb9c == 'project') { $v76b80da673 = is_array($pb46d1829["template_params"]["template_id"]) ? $pb46d1829["template_params"]["template_id"]["value"] : null; $v1a936daaed = is_array($pb46d1829["template_params"]["external_project_id"]) ? $pb46d1829["template_params"]["external_project_id"]["value"] : null; $pa2ed4c82 = is_array($pb46d1829["template_params"]["keep_original_project_url_prefix"]) ? $pb46d1829["template_params"]["keep_original_project_url_prefix"]["value"] : null; } else if ($pa1cddb9c == 'block') { $v76b80da673 = is_array($pb46d1829["template_params"]["block_id"]) ? $pb46d1829["template_params"]["block_id"]["value"] : null; $v1a936daaed = is_array($pb46d1829["template_params"]["external_project_id"]) ? $pb46d1829["template_params"]["external_project_id"]["value"] : null; } else if ($pa1cddb9c == 'wordpress_template') { $v76b80da673 = is_array($pb46d1829["template_params"]["url_query"]) ? $pb46d1829["template_params"]["url_query"]["value"] : null; $v820cf4f4d9 = is_array($pb46d1829["template_params"]["wordpress_installation_name"]) ? $pb46d1829["template_params"]["wordpress_installation_name"]["value"] : null; } else if ($pa1cddb9c == 'url') { $v74823199e7 = is_array($pb46d1829["template_params"]["url"]) ? $pb46d1829["template_params"]["url"]["value"] : null; } $v467a8922be = is_array($pb46d1829["template_params"]["cache_ttl"]) ? $pb46d1829["template_params"]["cache_ttl"]["value"] : null; } $pf8ed4912 = '
	<div class="template">
		<label>with</label>
		<select name="template_genre" onChange="onChangeTemplateGenre(this)">
			<option value="">Internal Template</option>
			<option value="external_template"' . ($pa1cddb9c ? ' selected' : '') . '>External Template</option>
		</select>
		
		<select name="template" onChange="onChangeTemplate(this)" ' . ($pa1cddb9c ? ' style="display:none"' : ' title="' . str_replace('"', '&quot;', $v7b2ad4afbf) . '"') . '>
			<option value="">-- DEFAULT --</option>'; foreach ($paf93610a as $v9a84a79e2e) $pf8ed4912 .= '<option value="' . $v9a84a79e2e . '"' . ($v9a84a79e2e == $v7b2ad4afbf ? ' selected' : '') . '>' . $v9a84a79e2e . '</option>'; $pf8ed4912 .= '
		</select>
		
		<span class="icon search" onClick="onChooseAvailableTemplate(this, true)" Title="Choose a template">Search</span>
	</div>
	
	<div class="external_template_params"' . (!$pa1cddb9c ? ' style="display:none"' : '') . '>
		<div class="external_template_type">
			<label>External Template Type:</label>
			<select name="type" onChange="onChangeExternalTemplateType(this)">
				<option value="">-- Please choose a type --</option>
				<option value="project"' . ($pa1cddb9c == 'project' ? ' selected' : '') . '>Template from another project</option>
				<option value="block"' . ($pa1cddb9c == 'block' ? ' selected' : '') . '>Template from a Block</option>
				<option value="url"' . ($pa1cddb9c == 'url' ? ' selected' : '') . '>Template from an URL</option>
				<option value="wordpress_template"' . ($pa1cddb9c == 'wordpress_template' ? ' selected' : '') . '>WordPress Template</option>
			</select>
		</div>
		
		<div class="template_id project_param"' . ($pa1cddb9c == 'project' ? '' : ' style="display:none"') . '>
			<label>Template:</label>
			<input name="template_id" value="' . ($pa1cddb9c == 'project' ? $v76b80da673 : '') . '" onBlur="onBlurExternalTemplate(this)" />
			<span class="icon search" onClick="onChooseProjectTemplate(this)" Title="Choose a template">Search</span>
		</div>
		
		<div class="block_id block_param"' . ($pa1cddb9c == 'block' ? '' : ' style="display:none"') . '>
			<label>Block:</label>
			<input name="block_id" value="' . ($pa1cddb9c == 'block' ? $v76b80da673 : '') . '" onBlur="onBlurExternalTemplate(this)" />
			<span class="icon search" onClick="onChooseBlockTemplate(this)" Title="Choose a block">Search</span>
		</div>
		
		<div class="external_project_id project_param block_param"' . ($pa1cddb9c == 'project' || $pa1cddb9c == 'block' ? '' : ' style="display:none"') . '>
			<label>Project:</label>
			<input name="external_project_id" value="' . ($pa1cddb9c == 'project' || $pa1cddb9c == 'block' ? $v1a936daaed : '') . '" onBlur="onBlurExternalTemplate(this)" />
		</div>
		
		<div class="keep_original_project_url_prefix project_param"' . ($pa1cddb9c == 'project' ? '' : ' style="display:none"') . '>
			<label>Keep Original Project URL Prefix:</label>
			<input type="checkbox" name="keep_original_project_url_prefix" value="1" ' . ($pa1cddb9c == 'project' && $pa2ed4c82 ? ' checked' : '') . ' onChange="onBlurExternalTemplate(this)" />
			<div class="info">Select this checkbox if you wish to load the template with the files (css, js, images and links) from the selected project.</div>
		</div>
		
		<div class="url_query wordpress_template_param"' . ($pa1cddb9c == 'wordpress_template' ? '' : ' style="display:none"') . '>
			<label>Url Query:</label>
			<input name="url_query" value="' . ($pa1cddb9c == 'wordpress_template' ? $v76b80da673 : '') . '" onBlur="onBlurExternalTemplate(this)" />
			<div class="info">Please write the URL Query of the WordPress page you wish to fetch.<br/>Note that the URL Query must be the relative URI from the WordPress page url.</div>
		</div>
		
		<div class="wordpress_installation_name wordpress_template_param"' . ($pa1cddb9c == 'wordpress_template' ? '' : ' style="display:none"') . '>
			<label>WordPress Installation:</label>
			<select name="wordpress_installation_name" onChange="onBlurExternalTemplate(this)">
				<option value="">-- Default DB Driver folder name if exists --</option>'; foreach ($v32c1e58e0c as $v5e813b295b) $pf8ed4912 .= '<option value="' . $v5e813b295b . '"' . ($v820cf4f4d9 == $v5e813b295b ? ' selected' : '') . '>' . ucwords(str_replace("_", " ", $v5e813b295b)) . '</option>'; if ($v820cf4f4d9 && !in_array($v820cf4f4d9, $v32c1e58e0c)) $pf8ed4912 .= '<option value="' . $v820cf4f4d9 . '" selected>' . $v820cf4f4d9 . ' - INVALID VALUE</option>'; $pf8ed4912 .= '
			</select>
		</div>
		
		<div class="url url_param"' . ($pa1cddb9c == 'url' ? '' : ' style="display:none"') . '>
			<label>URL:</label>
			<input type="text" name="url" value="' . ($pa1cddb9c == 'url' ? $v74823199e7 : '') . '" onBlur="onBlurExternalTemplate(this)" />
			<span class="icon search" onClick="onPresentationIncludePageUrlTaskChooseFile(this)" Title="Choose a page url">Search</span>
		</div>
		
		<div class="cache_ttl project_param block_param wordpress_template_param url_param"' . ($pa1cddb9c ? '' : ' style="display:none"') . '>
			<label>Cache TTL (in minutes):</label>
			<input name="cache_ttl" value="' . $v467a8922be . '" />
			<div class="info">Please write the minutes that this template should be cached, this is, everytime that the end-user calls this pages, the system will request and build this external template and this process may take some time. If you cache it, the next time the end-user requests this same page, it will be much faster. <br/>
			Note that this "External Template Cache" is not related with this "Page\'s Cache". The "Pages\' Cache" are a different type of cache!</div>
		</div>
	</div>'; return $pf8ed4912; } ?>
