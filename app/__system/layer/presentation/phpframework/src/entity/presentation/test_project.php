<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $popup = $_GET["popup"]; $path = str_replace("../", "", $path); $view_project_url = $project_url_prefix . "phpframework/presentation/view_project?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&"; if ($_POST) { $post_vars = $_POST["post_vars"]; $get_vars = $_POST["get_vars"]; $vars = array("post_vars" => array(), "get_vars" => array()); if ($post_vars) foreach ($post_vars as $var) $vars["post_vars"][ $var["name"] ] = $var["value"]; if ($get_vars) foreach ($get_vars as $var) $vars["get_vars"][ $var["name"] ] = $var["value"]; $view_project_url .= http_build_query($vars); } ?>
