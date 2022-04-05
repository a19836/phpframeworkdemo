<?php
$common_project_name = $EVC->getCommonProjectName();
include_once $EVC->getModulePath("common/CommonModuleSettingsUI", $common_project_name);
include $EVC->getConfigPath("config");

$tasks = array("createform");
include $EVC->getModulePath("common/init_tasks_flow", $common_project_name);

$head = $tasks_data["head"];
$contents = $tasks_data["contents"]["createform"];
$js_load_function = $tasks_data["js_load_functions"]["createform"];

echo '
<!-- Layout UI Editor - MD5 -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquery/js/jquery.md5.js"></script>

<!-- Layout UI Editor - Add ACE-Editor -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>
<script type="text/javascript" src="' . $proprietary_js_url_prefix . 'jquerytaskflowchart/js/lib/jsPlumbCloneHandler.js"></script>
<script type="text/javascript" src="' . $proprietary_js_url_prefix . 'jquerytaskflowchart/js/task_flow_chart.js"></script>

<!-- Add Code Beautifier -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/mycodebeautifier/js/codebeautifier.js"></script>

<!-- Add Html/CSS/JS Beautify code -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jsbeautify/js/lib/beautify.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jsbeautify/js/lib/beautify-css.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/myhtmlbeautify/MyHtmlBeautify.js"></script>

<!-- Layout UI Editor - Jquery Touch Punch to work on mobile devices with touch -->
<script language="javascript" type="text/javascript" src="http://jplpinto.localhost/__system/common/vendor/jqueryuitouchpunch/jquery.ui.touch-punch.min.js"></script>

<!-- Layout UI Editor - Jquery Tap-Hold Event JS file -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerytaphold/taphold.js"></script>

<!-- Layout UI Editor - Material-design-iconic-font -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/materialdesigniconicfont/css/material-design-iconic-font.min.css">

<!-- Layout UI Editor - JQuery Nestable2 -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/nestable2/jquery.nestable.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/nestable2/jquery.nestable.min.js"></script>

<!-- Layout UI Editor - Html Entities Converter -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/he/he.js"></script>

<!-- Layout UI Editor - HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	 <script src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/html5_ie8/html5shiv.min.js"></script>
	 <script src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/html5_ie8/respond.min.js"></script>
<![endif]-->

<!-- Layout UI Editor - Add Iframe droppable fix -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/jquery-ui-droppable-iframe-fix.js"></script>    

<!-- Layout UI Editor - Add Iframe droppable fix - IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/ie10-viewport-bug-workaround.js"></script>

<!-- Layout UI Editor - Add Layout UI Editor -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/css/some_bootstrap_style.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/css/style.css" type="text/css" charset="utf-8" />

<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/js/TextSelection.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/js/LayoutUIEditor.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/js/CreateWidgetContainerClassObj.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/js/LayoutUIEditorFormFieldUtil.js"></script>

<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>
	
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $project_url_prefix . 'css/edit_php_code.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="' . $project_url_prefix . 'js/edit_php_code.js"></script>

' . $head . '

<!-- Add Common Settings JS -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'module/common/settings.js"></script>

<!-- Add Other Settings CSS and JS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/other_settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="' . $project_common_url_prefix . 'module/common/other_settings.js"></script>

<script>
var js_load_function = ' . ($js_load_function ? $js_load_function : 'null') . ';
var create_form_settings_code_url = \'' . $project_url_prefix . 'module/objectsgroup/show_objects_group/create_form_settings_code\';

ProgrammingTaskUtil.on_programming_task_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
ProgrammingTaskUtil.on_programming_task_choose_image_url_callback = onIncludeImageUrlTaskChooseFile;

CreateFormTaskPropertyObj.layout_ui_editor_menu_widgets_elm_selector = \'.ui-menu-widgets-backup\';
</script>';
?>
<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>
<script type="text/javascript">
	document.write(unescape("%3Cscript src='<?= $project_url_prefix; ?>module/objectsgroup/show_objects_group/init_layer_settings" + location.search + "' type='text/javascript'%3E%3C/script%3E"));
</script>

<div class="selected_task_properties module_show_objects_group_settings">
	<?= $contents; ?>
	
	<div class="ui-menu-widgets-backup hidden">
		<? 
		$common_webroot_path = $EVC->getWebrootPath($common_project_name);
		$ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($common_webroot_path, $project_common_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url, array("avoided_widgets" => array("php")));
		$ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/common_editor_widget/", $project_url_prefix . "widget/", $webroot_cache_folder_path, $webroot_cache_folder_url);
		$ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($common_webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
		
		echo $ui_menu_widgets_html;
		?>
	</div>
	
	<div class="action_settings">
		<label class="input_settings_label">Input Data Settings: </label>
		
		<div class="objects_groups_type">
			<label>Objects Groups Type:</label>
			<select class="module_settings_property" name="objects_groups_type" onChange="updateObjectsGroupsSelectionType(this)">
				<option value="all">List All Objects Groups</option>
				<option value="tags_and">List of Objects Groups with all Tags bellow</option>
				<option value="tags_or">List of Objects Groups with at least one Tag bellow</option>
				<option value="parent">List of Objects Groups by parent</option>
				<option value="parent_group">List of Objects Groups by parent group</option>
				<option value="parent_tags_and">List of Objects Groups by parent and with all Tags bellow</option>
				<option value="parent_tags_or">List of Objects Groups by parent and with at least one Tag bellow</option>
				<option value="parent_group_tags_and">List of Objects Groups by parent group and with all Tags bellow</option>
				<option value="parent_group_tags_or">List of Objects Groups by parent group and with at least one Tag bellow</option>
				<option value="selected">List of Selected Objects Groups</option>
				<option value="specific">Specific Objects Group By Id</option>
			</select>
		</div>
		
		<div class="list_by_parent">
			<div class="list_parent_object_type_id">
				<label>Parent Type:</label>
				<select class="module_settings_property" name="object_type_id">
				</select>
			</div>
			<div class="list_parent_object_id">
				<label>Parent Id:</label>
				<input type="text" class="module_settings_property" name="object_id" value="" />
				<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</div>
			<div class="list_parent_group">
				<label>Group Id:</label>
				<input type="text" class="module_settings_property" name="group" value="" />
			</div>
		</div>
		
		<div class="specific_by_id">
			<label>Objects Group Id:</label>
			<input type="text" class="module_settings_property" name="objects_group_id" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		
		<div class="list_by_tags">
			<div class="list_tags">
				<label>Tags:</label>
				<input type="text" class="module_settings_property" name="tags" value="" />
				<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</div>
		</div>
		
		<div class="list_by_selected_objects_groups">
			<div class="available_objects_groups">
				<label>Add Objects Group:</label>
				<select></select>
				<span class="icon add" onClick="addSelectedObjectsGroup(this)">Add</span>
			</div>
			<div class="selected_objects_groups">
				<table>
					<tr>
						<th class="table_header objects_group_id">ID</th>
						<th class="table_header objects_group_object">Json Object</th>
						<th class="table_header buttons"></th>
					</tr>
					<tr class="no_objects_groups">
						<td colspan="3">No objects groups selected...</td>
					</tr>
				</table>
			</div>
		</div>
		
		<div class="clear"></div>
	
		<?php 
		echo CommonModuleSettingsUI::getListPaginationSettingsHtml(array("label" => "List Pagination Settings"));
		?>
		
		<div class="clear"></div>
		
		<div class="action_buttons_settings">
			<label class="action_buttons_settings_label">Buttons Settings: </label>
			
			<table class="action_buttons">
			<tbody>
				<tr>
					<th class="table_header button_label">Button Label</th>
					<th class="table_header action_type">Action Type</th>
					<th class="table_header action_variable">Based in the Variable</th>
					<th class="table_header buttons">
						<span class="icon add" onClick="addActionButton(this)">Add</span>
					</th>
				</tr>
				<tr class="no_action_buttons">
					<td colspan="4">There are no action buttons...</td>
				</tr>
			</tbody>
			</table>
		</div>
		
		<?php echo CommonModuleSettingsUI::getObjectToObjectFieldsHtml(); ?>
	</div>
</div>
