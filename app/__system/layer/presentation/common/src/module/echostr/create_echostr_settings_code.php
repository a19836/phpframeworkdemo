<?php
include_once get_lib("org.phpframework.phpscript.PHPUICodeExpressionHandler");

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$str = trim($_POST["str"]);
$fc = substr($str, 0, 1);
$lc = substr($str, -1);
$str_type = PHPUICodeExpressionHandler::getValueType($str, array("non_set_type" => "string", "empty_string_type" => "string"));

$is_code_type = is_numeric($str) || (substr($str, 0, 1) == '"' && substr($str, -1) == '"' && preg_match('/^"(.*)([^\\\\])"(.*)"$/', str_replace("\n", "", $str)));
$is_code_type = $is_code_type || (substr($str, 0, 1) == "'" && substr($str, -1) == "'" && preg_match("/^'(.*)([^\\\\])'(.*)'$/", str_replace("\n", "", $str)));

if ($str_type == "string" && $is_code_type)
	$str_type = "";

$code = 'array(
	"str" => ' . PHPUICodeExpressionHandler::getArgumentCode($str, $str_type) . ',
)';

header("Content-Type: application/json");
echo json_encode(array("code" => $code));
?>
