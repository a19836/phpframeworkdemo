<?php
//http://jplpinto.localhost/__system/common/vendor/jquerylayoutuieditor/layout_ui_editor.php

define('LIB_PATH', dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))))) . "/app/lib/");
include_once LIB_PATH . "org/phpframework/util/import/lib.php";
include_once get_lib("org.phpframework.xmlfile.XMLFileParser");
include __DIR__ . "/util.php";

$widgets_root_path = __DIR__ . DIRECTORY_SEPARATOR . "widget" . DIRECTORY_SEPARATOR;
$widgets = scanWidgets($widgets_root_path);
//print_r($widgets);die();
$menu_widgets_html = getMenuWidgetsHTML($widgets, $widgets_root_path, "widget/");
//echo $menu_widgets_html;die();
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<!-- JQuery -->
	<script language="javascript" type="text/javascript" src="../jquery/js/jquery-1.8.1.min.js"></script>
	<script language="javascript" type="text/javascript" src="../jqueryui/js/jquery-ui-1.11.4.min.js"></script>
	
	<!-- To work on mobile devices with touch -->
	<script language="javascript" type="text/javascript" src="../jqueryuitouchpunch/jquery.ui.touch-punch.min.js"></script>
	
	<!-- Bootstrap -->
	<!--link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" type="text/css" charset="utf-8" /-->
	
	<!-- Layout UI Editor - Jquery Tap-Hold Event JS file -->
	<script language="javascript" type="text/javascript" src="../jquerytaphold/taphold.js"></script>

	<!-- Material-design-iconic-font -->
	<link rel="stylesheet" href="vendor/materialdesigniconicfont/css/material-design-iconic-font.min.css">
	
	<!-- MyJSLib -->
	<script language="javascript" type="text/javascript" src="../../js/MyJSLib.js"></script>
	
	<!-- Parse_Str -->
	<script type="text/javascript" src="../phpjs/functions/strings/parse_str.js"></script>
	
	<!-- MD5 -->
	<script language="javascript" type="text/javascript" src="../jquery/js/jquery.md5.js"></script>
	
	<!-- JQuery Nestable2 -->
	<link rel="stylesheet" href="vendor/nestable2/jquery.nestable.min.css" type="text/css" charset="utf-8" />
	<script language="javascript" type="text/javascript" src="vendor/nestable2/jquery.nestable.min.js"></script>
	
	<!-- Add Code Editor JS files -->
	<script language="javascript" type="text/javascript" src="../acecodeeditor/src-min-noconflict/ace.js"></script>
	<script language="javascript" type="text/javascript" src="../acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>
	
	<!-- Add Code Beautifier -->
	<script language="javascript" type="text/javascript" src="../mycodebeautifier/js/codebeautifier.js"></script>

	<!-- Add Html/CSS/JS Beautify code -->
	<script language="javascript" type="text/javascript" src="../jsbeautify/js/lib/beautify.js"></script>
	<script language="javascript" type="text/javascript" src="../jsbeautify/js/lib/beautify-css.js"></script>
	<script language="javascript" type="text/javascript" src="../myhtmlbeautify/MyHtmlBeautify.js"></script>
    	
	<!-- Layout UI Editor -->
		<!-- Layout UI Editor - HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			 <script src="vendor/jqueryuidroppableiframe/js/html5_ie8/html5shiv.min.js"></script>
			 <script src="vendor/jqueryuidroppableiframe/js/html5_ie8/respond.min.js"></script>
		<![endif]-->
		
		<!-- Layout UI Editor - Add Iframe droppable fix -->
	    	<script type="text/javascript" src="vendor/jqueryuidroppableiframe/js/jquery-ui-droppable-iframe-fix.js"></script>    
	    
	    	<!-- Layout UI Editor - Add Iframe droppable fix - IE10 viewport hack for Surface/desktop Windows 8 bug -->
	    	<script src="vendor/jqueryuidroppableiframe/js/ie10-viewport-bug-workaround.js"></script>
	    	
		<!-- Layout UI Editor - Add Editor -->
		<link rel="stylesheet" href="css/some_bootstrap_style.css" type="text/css" charset="utf-8" />
		<link rel="stylesheet" href="css/style.css" type="text/css" charset="utf-8" />
		
		<script language="javascript" type="text/javascript" src="js/TextSelection.js"></script>
		<script language="javascript" type="text/javascript" src="js/LayoutUIEditor.js"></script>
		<script language="javascript" type="text/javascript" src="js/CreateWidgetContainerClassObj.js"></script>
		
		<!-- Layout UI Editor - LayoutUIEditorFormFieldUtil.js is optional, bc it depends of task/programming/common/webroot/js/FormFieldsUtilObj.js -->
		<script language="javascript" type="text/javascript" src="http://jplpinto.localhost/__system/phpframework/__system/cache/workflow/tasks/default/programming/common/js/FormFieldsUtilObj.js"></script><!-- Only exists if phpframework cache was not deleted -->
		<script language="javascript" type="text/javascript" src="js/LayoutUIEditorFormFieldUtil.js"></script>
		
	<!-- Others -->
	<script language="javascript" type="text/javascript" src="js/script.js"></script>
	
	<style>
		.layout_ui_editor.layout_ui_editor_1 {
			width:90%;
			margin:0 auto;
		}
		
		.layout_ui_editor.layout_ui_editor_2 {
			width:80%;
			margin:100px auto 0 auto;
			background:#333;
		}
		.layout_ui_editor.layout_ui_editor_2 > .options > .options-center {
			background:#333;
		}
		
		.layout_ui_editor.layout_ui_editor_3 {
			width:80%;
			margin:100px auto 0 auto;
			background:#660000;
		}
		.layout_ui_editor.layout_ui_editor_3 > .options > .options-center {
			background:#660000;
		}
	</style>
</head>
<body>
<?
if ($_SERVER["HTTP_HOST"] != "jplpinto.localhost")
	echo("<script>alert('Please configure your computer to point the host jplpinto.localhost to this IP, otherwise the layout ui editor won't work with the FormFieldsUtilObj!');</script>");
?>
	<div class="layout_ui_editor layout_ui_editor_1">
		<ul class="menu-widgets hidden">
			<? echo $menu_widgets_html; ?>
		</ul>
	</div>
	
	<div class="layout_ui_editor layout_ui_editor_2">
		<ul class="menu-widgets hidden">
			<? echo $menu_widgets_html; ?>
		</ul>
	</div>
	
	<div class="layout_ui_editor layout_ui_editor_3">
		<ul class="menu-widgets hidden">
			<? echo $menu_widgets_html; ?>
		</ul>
	</div>
</body>
</html>
