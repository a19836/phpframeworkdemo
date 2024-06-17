<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $path = $_GET["path"]; $path = str_replace("../", "", $path); $file_path = $path ? APP_PATH . $path : null; $file_exists = $file_path ? file_exists($file_path) : null; if ($file_exists) { $is_docbook_allowed = strpos($file_path, LIB_PATH . "org/") === 0; $is_contents_allowed = strpos($file_path, LIB_PATH) === 0; if ($is_docbook_allowed) { $relative_file_path = substr($file_path, strlen(APP_PATH)); $cached_path = $EVC->getEntitiesPath() . "docbook/files/$relative_file_path.ser"; $classes_properties = file_exists($cached_path) ? unserialize(file_get_contents($cached_path)) : null; } else if ($is_contents_allowed) $contents = file_get_contents($file_path); } ?>
