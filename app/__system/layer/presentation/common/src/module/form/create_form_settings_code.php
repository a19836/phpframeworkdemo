<?php
include_once $EVC->getUtilPath("SequentialLogicalActivitySettingsCodeCreator");

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$settings = isset($_POST["settings"]) ? $_POST["settings"] : null;
$code = "";

if (is_array($settings)) {
	if ($settings)
		foreach ($settings as $type => $value) {
			$type = strtolower($type);
			
			switch ($type) {
				case "actions":
					if (is_array($value))
						$code .= ($code ? ",\n" : "") . "\t" . '"actions" => ' . SequentialLogicalActivitySettingsCodeCreator::getActionsCode($webroot_cache_folder_path, $webroot_cache_folder_url, $value, "\t\t");
					break;
				
				case "css":
				case "js":
					$code .= ($code ? ",\n" : "") . "\t" . '"' . $type . '" => ' . SequentialLogicalActivitySettingsCodeCreator::prepareStringValue($value);
					break;
			}
		}
	
	$code = "array(\n" . $code . "\n)";
}

$obj_code = array("code" => $code);
header("Content-type: application/json");
echo json_encode($obj_code);
?>
