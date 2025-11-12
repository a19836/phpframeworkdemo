<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$defined_vars = array_keys(get_defined_vars());

include_once $EVC->getUtilPath("SequentialLogicalActivityCodeConverter");

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$settings = isset($_POST["settings"]) ? $_POST["settings"] : null;

if (is_array($settings)) {
	$lower_settings = array();
	
	foreach ($settings as $type => $value) //Do not lower all the settings inner keys. Only lower the main keys. Note that the "draw_graph" action can have uppercase and capitalized keys.
		$lower_settings[ strtolower($type) ] = $value;
	
	$code = SequentialLogicalActivityCodeConverter::convertActionsSettingsToCode($EVC, $webroot_cache_folder_path, $webroot_cache_folder_url, isset($lower_settings["actions"]) ? $lower_settings["actions"] : null);
	
	if (!empty($lower_settings["css"]) || !empty($lower_settings["js"])) {
		$code = preg_replace("/\?>$/", "", $code);
		
		if (!empty($lower_settings["css"]))
			$code .= "\n/*** STYLE ***/\n" . 'echo "<style>" . ' . SequentialLogicalActivityCodeConverter::prepareStringValue($lower_settings["css"]) . ' . "</style>";' . "\n";
		
		if (!empty($lower_settings["js"]))
			$code .= "\n/*** SCRIPT ***/\n" . 'echo "<script>" . ' . SequentialLogicalActivityCodeConverter::prepareStringValue($lower_settings["js"]) . ' . "</script>";' . "\n";
		
		$code .= "\n?>";
	}
	
	preg_match_all("/\\$(\w+)/u", $code, $matches_1, PREG_PATTERN_ORDER); //'\w' means all words with '_' and '/u' means with accents and ç too. '/u' converts unicode to accents chars. 
	preg_match_all("/\\$\{(\w+)/u", $code, $matches_2, PREG_PATTERN_ORDER); //'\w' means all words with '_' and '/u' means with accents and ç too. '/u' converts unicode to accents chars. 
	$matches = !empty($matches_1[1]) && !empty($matches_2[1]) ? array_merge($matches_1[1], $matches_2[1]) : (!empty($matches_1[1]) ? $matches_1[1] : (isset($matches_2[1]) ? $matches_2[1] : null));
	$external_vars = !empty($matches) && !empty($matches[1]) ? array_intersect($defined_vars, $matches) : null;
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
