<?php
include_once $EVC->getUtilPath("CMSPresentationLayerHandler");

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$external_vars_code = "";
if ($_POST["external_vars"])
	foreach ($_POST["external_vars"] as $external_var_name => $external_var_value)
		if ($external_var_name) {
			$type = CMSPresentationLayerHandler::getValueType($external_var_name, array("empty_string_type" => "string"));
			$name = CMSPresentationLayerHandler::getArgumentCode($external_var_name, $type);
			
			$type = CMSPresentationLayerHandler::getValueType($external_var_value, array("empty_string_type" => "string"));
			$value = CMSPresentationLayerHandler::getArgumentCode($external_var_value, $type);
			
			$external_vars_code .= "\n\t\t" . $name . ' => ' . $value . ',';
		}

if ($external_vars_code)
	$external_vars_code = "array($external_vars_code\n\t)";
else
	$external_vars_code = "null";

$code = 'array(
	"code" => \'' . addcslashes($_POST["code"], "'") . '\',
	"external_vars" => ' . $external_vars_code . '
)';

header("Content-Type: application/json");
echo json_encode(array("code" => $code));
?>
