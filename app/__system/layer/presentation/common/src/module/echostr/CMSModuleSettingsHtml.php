<?php
include $EVC->getConfigPath("config");
include_once $EVC->getUtilPath("WorkFlowPresentationHandler");

$reverse_class = $_COOKIE["main_navigator_side"] == "main_navigator_reverse" ? "" : "reverse";

echo '
<!-- TinyMCE JS Files  -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/tinymce/js/tinymce/tinymce.min.js"></script>	
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/tinymce/js/tinymce/jquery.tinymce.min.js"></script>

<!-- CKEDITOR JS Files  -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/ckeditor/ckeditor.js"></script>

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
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/mycodebeautifier/js/codebeautifier.js"></script>

<!-- Layout UI Editor - Add Html/CSS/JS Beautify code -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jsbeautify/js/lib/beautify.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jsbeautify/js/lib/beautify-css.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/myhtmlbeautify/MyHtmlBeautify.js"></script>

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

<script>
var create_echostr_settings_code_url = \'' . $project_url_prefix . 'module/echostr/create_echostr_settings_code\';
var reverse_class = \'' . $reverse_class . '\';
</script>';

$common_webroot_path = $EVC->getWebrootPath($EVC->getCommonProjectName());
$ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($common_webroot_path, $project_common_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url, array("avoided_widgets" => array("ptl", "php")));
$ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/common_editor_widget/", $webroot_cache_folder_path, $webroot_cache_folder_url);
$ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($common_webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>echostr.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>echostr.js"></script>

<div class="echostr_settings">
	<ul class="tabs">
		<li class="textarea_tab"><a href="#layoutui_content">Ace Editor</a></li>
		<li class="tinymce_tab"><a href="#tinymce_content" onClick="resizeTinyMCEEditor()">TinyMCE Editor</a></li>
		<li class="ckeditor_tab"><a href="#ckeditor_content" onClick="resizeCKEditor()">CK Editor</a></li>
	</ul>
	
	<textarea class="module_settings_property" name="str">Write here what you wish to echo...</textarea>
	
	<div id="layoutui_content" class="editor">
		<textarea>Write here what you wish to echo...</textarea>
		
		<div class="ui-menu-widgets-backup hidden">
			<?= $ui_menu_widgets_html ?>
		</div>
	</div>
	
	<div id="tinymce_content" class="editor">
		<textarea>Write here what you wish to echo...</textarea>
	</div>
	
	<div id="ckeditor_content" class="editor">
		<textarea>Write here what you wish to echo...</textarea>
	</div>
</div>
