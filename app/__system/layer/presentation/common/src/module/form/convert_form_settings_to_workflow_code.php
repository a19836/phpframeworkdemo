<?php
$defined_vars = array_keys(get_defined_vars());

include_once $EVC->getUtilPath("SequentialLogicalActivityCodeConverter");

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$settings = $_POST["settings"];

if (is_array($settings)) {
	MyArray::arrKeysToLowerCase($settings, true);
	
	$code = SequentialLogicalActivityCodeConverter::convertActionsSettingsToCode($EVC, $webroot_cache_folder_path, $webroot_cache_folder_url, $settings["actions"]);
	
	if ($settings["css"] || $settings["js"]) {
		$code = preg_replace("/\?>$/", "", $code);
		
		if ($settings["css"])
			$code .= "\n/*** STYLE ***/\n" . 'echo "<style>" . ' . SequentialLogicalActivityCodeConverter::prepareStringValue($settings["css"]) . ' . "</style>";' . "\n";
		
		if ($settings["js"]) 
			$code .= "\n/*** SCRIPT ***/\n" . 'echo "<script>" . ' . SequentialLogicalActivityCodeConverter::prepareStringValue($settings["js"]) . ' . "</script>";' . "\n";
		
		$code .= "\n?>";
	}
	
	preg_match_all("/\\$(\w+)/u", $code, $matches_1, PREG_PATTERN_ORDER); //'\w' means all words with '_' and '/u' means with accents and ç too. '/u' converts unicode to accents chars. 
	preg_match_all("/\\$\{(\w+)/u", $code, $matches_2, PREG_PATTERN_ORDER); //'\w' means all words with '_' and '/u' means with accents and ç too. '/u' converts unicode to accents chars. 
	$matches = $matches_1[1] && $matches_2[1] ? array_merge($matches_1[1], $matches_2[1]) : ($matches_1[1] ? $matches_1[1] : $matches_2[1]);
	$external_vars = $matches && $matches[1] ? array_intersect($defined_vars, $matches) : null;
	unset($external_vars["results"]);
	
	if (strpos($code, '$entity_path') !== false && !in_array('entity_path', $external_vars))
		$external_vars[] = 'entity_path';
	
	$external_vars_code = "";
	if ($external_vars)
		foreach ($external_vars as $external_var)
			if ($external_var)
				$external_vars_code .= "\n\t\t\t" . '"' . $external_var . '" => $' . $external_var . ',';
	
	if ($external_vars_code)
		$external_vars_code = "array($external_vars_code\n\t\t)";
	else
		$external_vars_code = "null";
	
	//"\\'" => double back slash is very important otherwise the code conversion won't work if there is javascript already with "\'" inside...
	$code = 'array(
		"code" => \'' . addcslashes($code, "\\'") . '\',
		"external_vars" => ' . $external_vars_code . ',
	)';
	
	$obj_code = array("code" => $code);
	header("Content-type: application/json");
	echo json_encode($obj_code);
}
?>
