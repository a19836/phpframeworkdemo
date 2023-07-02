<?php
/******* SET DEFAULT EVC *******/
$default_template = $GLOBALS["project_default_template"] ? $GLOBALS["project_default_template"] : "pages";

/******* PREPARE EVC *******/
$EVC->setController( basename(__FILE__, ".php") );
$EVC->setEntity($parameters[0]);
$EVC->setView($parameters[1] ? $parameters[1] : $parameters[0]);
$EVC->setTemplate($default_template);

$CMSHtmlParserLayer = $EVC->getCMSLayer()->getCMSHtmlParserLayer();
$CMSHtmlParserLayer->init($parameters[0], $project_url_prefix, $project_common_url_prefix);

/******* SHOW EVC *******/
$entity_path = $EVC->getEntityPath( $EVC->getEntity() );
if(file_exists($entity_path)) {
	include $entity_path;
}
else {
	header("HTTP/1.0 404 Not Found");
	launch_exception(new EVCException(2, $entity_path));
}

$view_params = $EVC->getViewParams();
$view_path = $EVC->getViewPath( $EVC->getView(), $view_params ? $view_params["project_id"] : false );
if(file_exists($view_path)) {
	include $view_path;
}
else if ($EVC->getView() != $EVC->getEntity()) {
	header("HTTP/1.0 404 Not Found");
	launch_exception(new EVCException(3, $view_path));
}

$template_params = $EVC->getTemplateParams();
$template_path = $EVC->getTemplatePath( $EVC->getTemplate(), $template_params ? $template_params["project_id"] : false );
if(file_exists($template_path)) {
	$CMSHtmlParserLayer->beforeIncludeTemplate($template_path);
	
	include $template_path;
	
	$CMSHtmlParserLayer->afterIncludeTemplate($template_path);
}
else {
	header("HTTP/1.0 404 Not Found");
	launch_exception(new EVCException(4, $template_path));
}
?>
