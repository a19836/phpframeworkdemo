<?php
$common_project_name = $EVC->getCommonProjectName();
include_once $EVC->getModulePath("common/CommonModuleSettingsUI", $common_project_name);
include_once $EVC->getModulePath("common/CommonModuleSettingsUtil", $common_project_name);
include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesSettingsUtil", $common_project_name);
include $EVC->getConfigPath("config");

//Set common webroot path for the ui editors in CommonModuleSettingsUI::getLayoutUIEditorMenuWidgetsHtml
CommonModuleSettingsUI::$COMMON_WEBROOT_PATH = $EVC->getWebrootPath($common_project_name);
CommonModuleSettingsUI::$COMMON_WEBROOT_URL = $project_common_url_prefix;
CommonModuleSettingsUI::$LAYOUT_UI_EDITOR_PRESENTATION_COMMON_WIDGETS_URL = $project_url_prefix . "widget/";
CommonModuleSettingsUI::$LAYOUT_UI_EDITOR_PRESENTATION_COMMON_WIDGETS_FOLDER_PATH = $EVC->getViewsPath() . "presentation/common_editor_widget/";
CommonModuleSettingsUI::$LAYOUT_UI_EDITOR_USER_WIDGET_FOLDERS_PATH = $layout_ui_editor_user_widget_folders_path;
CommonModuleSettingsUI::$WEBROOT_CACHE_FOLDER_PATH = $webroot_cache_folder_path;
CommonModuleSettingsUI::$WEBROOT_CACHE_FOLDER_URL = $webroot_cache_folder_url;

//Set PEVC and default db driver
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	$CommonModuleTableExtraAttributesSettingsUtil = new CommonModuleTableExtraAttributesSettingsUtil($EVC, $PEVC, $GLOBALS["default_db_driver"], $module["path"]);
}
include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

//init tasks flow
$tasks = array("createform");
include $EVC->getModulePath("common/init_tasks_flow", $common_project_name);

//echo head
echo '<script>
var jsPlumbWorkFlow = null;
</script>';
echo $tasks_data["head"];
echo '<script>
ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onProgrammingTaskChooseCreatedVariable;
</script>

<!-- Layout UI Editor - MD5 -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquery/js/jquery.md5.js"></script>

<!-- Layout UI Editor - Add ACE-Editor -->
<!-- Add ACE-Editor -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>

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

<!-- Add Form Field CSS and JS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/settings.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/other_settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="' . $project_common_url_prefix . 'module/common/settings.js"></script>
<script type="text/javascript" src="' . $project_common_url_prefix . 'module/common/other_settings.js"></script>
';
?>
