<?php
try {
	define('GLOBAL_SETTINGS_PROPERTIES_FILE_PATH', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_settings.php");
	define('GLOBAL_VARIABLES_PROPERTIES_FILE_PATH', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_variables.php");

	include dirname(dirname(__DIR__)) . "/app.php";
	include_once get_lib("org.phpframework.webservice.layer.PresentationLayerWebService");

	define('BEANS_FILE_PATH', BEAN_PATH . 'presentation_pl.xml');
	define('PRESENTATION_DISPATCHER_CACHE_HANDLER_BEAN_NAME', 'PresentationPDispatcherCacheHandler');
	define('PRESENTATION_LAYER_BEAN_NAME', 'PresentationPLayer');
	define('EVC_DISPATCHER_BEAN_NAME', 'PresentationEVCDispatcher');
	define('EVC_BEAN_NAME', 'PresentationEVC');

	echo call_presentation_layer_web_service(array("presentation_id" => $presentation_id, "external_vars" => $external_vars, "includes" => $includes, "includes_once" => $includes_once));
}
catch(Exception $e) {
	$GlobalExceptionLogHandler->log($e);
}
?>