<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $path = isset($_GET["path"]) ? $_GET["path"] : null; $popup = isset($_GET["popup"]) ? $_GET["popup"] : null; $path = str_replace("../", "", $path); $file_path = $path ? APP_PATH . $path : null; $file_exists = $file_path ? file_exists($file_path) : null; $readonly = true; if ($file_exists) { $is_contents_allowed = strpos($file_path, LIB_PATH) === 0; if ($is_contents_allowed) { $available_extensions = array("xml" => "xml", "php" => "php", "js" => "javascript", "css" => "css", "" => "text", "txt" => "text", "html" => "html", "htm" => "html"); $fpel = strtolower(pathinfo($file_path, PATHINFO_EXTENSION)); $editor_code_type = isset($available_extensions[$fpel]) ? $available_extensions[$fpel] : null; $code = file_get_contents($file_path); } } ?>
