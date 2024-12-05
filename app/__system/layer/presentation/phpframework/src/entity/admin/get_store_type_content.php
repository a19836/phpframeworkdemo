<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $type = isset($_GET["type"]) ? $_GET["type"] : null; $url = $type == "modules" ? $get_store_modules_url : ( $type == "templates" ? $get_store_templates_url : ( $type == "programs" ? $get_store_programs_url : ( $type == "pages" ? $get_store_pages_url : null ) ) ); if ($url) { $query_string = isset($_SERVER["QUERY_STRING"]) ? $_SERVER["QUERY_STRING"] : null; $settings = array( "url" => $url, "settings" => array( "connection_timeout" => 60, "follow_location" => 1, ) ); $MyCurl = new MyCurl(); $MyCurl->initSingle($settings); $MyCurl->get_contents(); $data = $MyCurl->getData(); $json = isset($data[0]["content"]) ? $data[0]["content"] : null; echo $json; } die(); ?>
