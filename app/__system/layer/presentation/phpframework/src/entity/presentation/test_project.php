<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$bean_name = isset($_GET["bean_name"]) ? $_GET["bean_name"] : null; $bean_file_name = isset($_GET["bean_file_name"]) ? $_GET["bean_file_name"] : null; $path = isset($_GET["path"]) ? $_GET["path"] : null; $popup = isset($_GET["popup"]) ? $_GET["popup"] : null; $path = str_replace("../", "", $path); $view_project_url = $project_url_prefix . "phpframework/presentation/view_project?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&"; if (!empty($_POST)) { $post_vars = isset($_POST["post_vars"]) ? $_POST["post_vars"] : null; $get_vars = isset($_POST["get_vars"]) ? $_POST["get_vars"] : null; $vars = array("post_vars" => array(), "get_vars" => array()); if ($post_vars) foreach ($post_vars as $var) { $var_name = isset($var["name"]) ? $var["name"] : null; $vars["post_vars"][$var_name] = isset($var["value"]) ? $var["value"] : null; } if ($get_vars) foreach ($get_vars as $var) { $var_name = isset($var["name"]) ? $var["name"] : null; $vars["get_vars"][$var_name] = isset($var["value"]) ? $var["value"] : null; } $view_project_url .= http_build_query($vars); } ?>
