<?php
include_once $EVC->getUtilPath("CMSPresentationUIAutomaticFilesHandler");

$layout_ui_editor_widget_resource_options_js = "";

//prepare available activities and user types
$available_user_types = CMSPresentationUIAutomaticFilesHandler::getAvailableUserTypes($PEVC);
$available_activities = CMSPresentationUIAutomaticFilesHandler::getAvailableActivities($PEVC);
$layout_ui_editor_widget_resource_options_js .= '
var available_user_types = ' . json_encode($available_user_types) . ';
var available_activities = ' . json_encode($available_activities) . ';';

//prepare numeric types
$layout_ui_editor_widget_resource_options_js .= '
var php_numeric_types = ' . json_encode(ObjTypeHandler::getPHPNumericTypes()) . ';
var db_numeric_types = ' . json_encode(ObjTypeHandler::getDBNumericTypes()) . ';';

//prepare internal attributes
$internal_attribute_names = array_values( array_map('strtolower', array_unique( array_merge( ObjTypeHandler::getDBAttributeNameCreatedDateAvailableValues(), ObjTypeHandler::getDBAttributeNameModifiedDateAvailableValues(), ObjTypeHandler::getDBAttributeNameCreatedUserIdAvailableValues(), ObjTypeHandler::getDBAttributeNameModifiedUserIdAvailableValues() ) ) ) );

$layout_ui_editor_widget_resource_options_js .= '
var internal_attribute_names = ' . json_encode($internal_attribute_names) . ';';	
?>
