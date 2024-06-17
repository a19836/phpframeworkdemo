<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $path = $_GET["path"]; $popup = $_GET["popup"]; $path = str_replace("../", "", $path); $file_path = $path ? APP_PATH . $path : null; $file_exists = $file_path ? file_exists($file_path) : null; $readonly = true; if ($file_exists) { $is_contents_allowed = strpos($file_path, LIB_PATH) === 0; if ($is_contents_allowed) { $available_extensions = array("xml" => "xml", "php" => "php", "js" => "javascript", "css" => "css", "" => "text", "txt" => "text", "html" => "html", "htm" => "html"); $editor_code_type = $available_extensions[ strtolower(pathinfo($file_path, PATHINFO_EXTENSION)) ]; $code = file_get_contents($file_path); } } ?>
