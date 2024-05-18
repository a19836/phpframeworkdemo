<?php
include $EVC->getConfigPath("config");
include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler");
include_once $EVC->getUtilPath("WorkFlowUIHandler");
include_once $EVC->getUtilPath("WorkFlowPresentationHandler");

$reverse_class = $_COOKIE["main_navigator_side"] == "main_navigator_reverse" ? "" : "reverse";
$common_project_name = $EVC->getCommonProjectName();
$purlp = $project_url_prefix;
$pcurlp = $project_common_url_prefix;

include $EVC->getModulePath("common/start_project_module_file", $common_project_name);
$project_url_prefix = $purlp;
$project_common_url_prefix = $pcurlp;

if ($PEVC) {
	//load the createform task, so we can load the programming/common/js/FormFieldsUtilObj.js file, bc this file is used in the LayoutUIEditor.js
	$allowed_tasks_tag = array("createform");
	$WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url);
	$WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH);
	$WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag);
	
	$WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $external_libs_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
	
	//load createform js files and load all files for the LayoutUIEditor
	echo $WorkFlowUIHandler->getHeader(array("tasks_css_and_js" => true, "icons_and_edit_code_already_included" => true, "ui_editor" => true));
	
	//prepare init_layout_ui_editor_widget_resource_options
	include $EVC->getModulePath("common/init_layout_ui_editor_widget_resource_options", $common_project_name);
	
	echo '<script>
	' . $layout_ui_editor_widget_resource_options_js . '
	</script>';
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

$exists_tinymce = file_exists($EVC->getWebrootPath($common_project_name) . "vendor/tinymce/js/tinymce/tinymce.min.js");
$exists_ckeditor = file_exists($EVC->getWebrootPath($common_project_name) . "vendor/ckeditor/ckeditor.js");

/* The code below is already included by the $WorkFlowUIHandler->getHeader(...) method
echo '
<!-- Layout UI Editor - Color -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'js/color.js"></script>

<!-- Layout UI Editor - Add Jquery Tap-Hold Event JS file -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerytaphold/taphold.js"></script>

<!-- Layout UI Editor - Jquery Touch Punch to work on mobile devices with touch -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jqueryuitouchpunch/jquery.ui.touch-punch.min.js"></script>

<!-- Layout UI Editor - MD5 -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquery/js/jquery.md5.js"></script>

<!-- Layout UI Editor - Add ACE-Editor -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>

<!-- Layout UI Editor - Add Code Beautifier -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/mycodebeautifier/js/MyCodeBeautifier.js"></script>

<!-- Layout UI Editor - Add Html/CSS/JS Beautify code -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jsbeautify/js/lib/beautify.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jsbeautify/js/lib/beautify-css.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'lib/myhtmlbeautify/MyHtmlBeautify.js"></script>

<!-- Add Auto complete -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/myautocomplete/js/MyAutoComplete.js"></script>
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/myautocomplete/css/style.css">

<!-- Layout UI Editor - Html Entities Converter -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/he/he.js"></script>

<!-- Layout UI Editor - Material-design-iconic-font -->
<link rel="stylesheet" href="' . $project_url_prefix . 'lib/jquerylayoutuieditor/vendor/materialdesigniconicfont/css/material-design-iconic-font.min.css">

<!-- Layout UI Editor - JQuery Nestable2 -->
<link rel="stylesheet" href="' . $project_url_prefix . 'lib/jquerylayoutuieditor/vendor/nestable2/jquery.nestable.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'lib/jquerylayoutuieditor/vendor/nestable2/jquery.nestable.min.js"></script>

<!-- Layout UI Editor - HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	 <script src="' . $project_url_prefix . 'lib/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/html5_ie8/html5shiv.min.js"></script>
	 <script src="' . $project_url_prefix . 'lib/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/html5_ie8/respond.min.js"></script>
<![endif]-->

<!-- Layout UI Editor - Add Iframe droppable fix -->
<script type="text/javascript" src="' . $project_url_prefix . 'lib/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/jquery-ui-droppable-iframe-fix.js"></script>    

<!-- Layout UI Editor - Add Iframe droppable fix - IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="' . $project_url_prefix . 'lib/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/ie10-viewport-bug-workaround.js"></script>

<!-- Layout UI Editor - Add Layout UI Editor -->
<link rel="stylesheet" href="' . $project_url_prefix . 'lib/jquerylayoutuieditor/css/some_bootstrap_style.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $project_url_prefix . 'lib/jquerylayoutuieditor/css/style.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $project_url_prefix . 'lib/jquerylayoutuieditor/css/widget_resource.css" type="text/css" charset="utf-8" />

<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'lib/jquerylayoutuieditor/js/TextSelection.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'lib/jquerylayoutuieditor/js/LayoutUIEditor.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'lib/jquerylayoutuieditor/js/CreateWidgetContainerClassObj.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'lib/jquerylayoutuieditor/js/LayoutUIEditorFormField.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'lib/jquerylayoutuieditor/js/LayoutUIEditorWidgetResource.js"></script>

<!-- Layout UI Editor - Add Layout UI Editor Widget Resource Options/Handlers -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout_ui_editor_widget_resource_options.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/layout_ui_editor_widget_resource_options.js"></script>
';*/

if ($exists_tinymce)
	echo '
<!-- TinyMCE JS Files  -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/tinymce/js/tinymce/tinymce.min.js"></script>	
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/tinymce/js/tinymce/jquery.tinymce.min.js"></script>
';

if ($exists_ckeditor)
	echo '
<!-- CKEDITOR JS Files  -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/ckeditor/ckeditor.js"></script>
';

echo '
<script>
var create_echostr_settings_code_url = \'' . $project_url_prefix . 'module/echostr/create_echostr_settings_code\';
var reverse_class = \'' . $reverse_class . '\';
</script>';

$webroot_path = $EVC->getWebrootPath();
$ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($webroot_path, $project_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url, array("avoided_widgets" => array("ptl", "php")));
$ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($webroot_path, $EVC->getViewsPath() . "presentation/common_editor_widget/", $webroot_cache_folder_path, $webroot_cache_folder_url);
$ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>echostr.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>echostr.js"></script>

<div class="echostr_settings">
	<ul class="tabs">
		<li class="textarea_tab"><a href="#layoutui_content">Ace Editor</a></li>
		<?php 
			if ($exists_tinymce)
				echo '
		<li class="tinymce_tab"><a href="#tinymce_content" onClick="resizeTinyMCEEditor()">TinyMCE Editor</a></li>';
			
			if ($exists_ckeditor)
				echo '
		<li class="ckeditor_tab"><a href="#ckeditor_content" onClick="resizeCKEditor()">CK Editor</a></li>';
		?>
	</ul>
	
	<textarea class="module_settings_property" name="str">Write here what you wish to echo...</textarea>
	
	<div id="layoutui_content" class="editor">
		<textarea>Write here what you wish to echo...</textarea>
		
		<div class="ui_menu_widgets_backup hidden">
			<?= $ui_menu_widgets_html ?>
		</div>
	</div>
	
	<?php 
		//echo "exists_tinymce: $exists_tinymce\n<br/>exists_ckeditor:$exists_ckeditor";
		
		if ($exists_tinymce)
			echo '
	<div id="tinymce_content" class="editor">
		<textarea>Write here what you wish to echo...</textarea>
	</div>';
		
		if ($exists_ckeditor)
			echo '
	<div id="ckeditor_content" class="editor">
		<textarea>Write here what you wish to echo...</textarea>
	</div>';
	?>
</div>
