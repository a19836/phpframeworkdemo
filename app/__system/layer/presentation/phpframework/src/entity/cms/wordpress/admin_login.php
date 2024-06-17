<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.cms.wordpress.WordPressUrlsParser"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $db_driver = $_GET["db_driver"]; $path = str_replace("../", "", $path); if ($bean_name && $bean_file_name) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $P = $PEVC->getPresentationLayer(); $selected_project_id = $P->getSelectedPresentationId(); $common_project_name = $PEVC->getCommonProjectName(); if (!$db_driver && $path && dirname($path) == "$common_project_name/webroot/" . WordPressUrlsParser::WORDPRESS_FOLDER_PREFIX) $db_driver = basename($path); if ($db_driver) { $wordpress_folder_suffix = WordPressUrlsParser::WORDPRESS_FOLDER_PREFIX . "/$db_driver/"; $wordpress_folder_path = $PEVC->getWebrootPath($common_project_name) . $wordpress_folder_suffix; $is_installed = file_exists($wordpress_folder_path . "index.php"); $default_admin_credentials_path = $wordpress_folder_path . "default_admin_credentials.php"; if ($is_installed) { if (!file_exists($default_admin_credentials_path)) { launch_exception(new Exception("File '{$wordpress_folder_suffix}default_admin_credentials.php' does not exists! Someone deleted this file... Please reinstall this cms...")); die(); } include $default_admin_credentials_path; $user_data = array( "username" => $admin_user, "password" => $admin_pass, ); $PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_path); $PHPVariablesFileHandler->startUserGlobalVariables(); $wordpress_url = getProjectCommonUrlPrefix($PEVC, $selected_project_id ? $selected_project_id : $common_project_name) . $wordpress_folder_suffix; $wordpress_url = preg_replace("/\/+$/", "", $wordpress_url); $PHPVariablesFileHandler->endUserGlobalVariables(); require $wordpress_folder_path . 'wp-load.php'; $wp_home_url = get_option('home'); $wp_site_url = get_option('siteurl'); if ($wordpress_url != $wp_home_url || $wordpress_url != $wp_site_url) { update_option("siteurl", $wordpress_url); update_option("home", $wordpress_url); $updated = $wordpress_url == get_option('home') && $wordpress_url == get_option('siteurl'); $htaccess_fp = $wordpress_folder_path . ".htaccess"; if (file_exists($htaccess_fp)) { $htaccess_contents = file_get_contents($htaccess_fp); $new_url_parts = parse_url($wordpress_url); $old_url_parts = parse_url($wordpress_url != $wp_home_url ? $wp_home_url : $wp_site_url); if (substr($new_url_parts["path"], -1) == "/") $new_url_parts["path"] = substr($new_url_parts["path"], 0, -1); if (substr($old_url_parts["path"], -1) == "/") $old_url_parts["path"] = substr($old_url_parts["path"], 0, -1); if ($new_url_parts["host"] != $old_url_parts["host"] && strpos($htaccess_contents, $old_url_parts["host"]) !== false) $htaccess_contents = str_replace($old_url_parts["host"], $new_url_parts["host"], $htaccess_contents); if ($new_url_parts["path"] != $old_url_parts["path"] && strpos($htaccess_contents, $old_url_parts["path"]) !== false) $htaccess_contents = str_replace($old_url_parts["path"], $new_url_parts["path"], $htaccess_contents); if (file_put_contents($htaccess_fp, $htaccess_contents) === false) $updated = false; } wp_clean_update_cache(); wp_cache_flush(); if (!$updated) { launch_exception(new Exception("Could not automatically login to the WordPress installation '$db_driver' bc it was previously moved to another folder or has a new root domain, and the system could NOT update it with the new changes! Please try again or contact the system administrator...")); die(); } else { $url = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; header("Location: $url"); die(); } } define("WP_HOME", $wordpress_url); define("WP_SITEURL", $wordpress_url); if (force_ssl_admin() && !is_ssl()) { $url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; header("Location: $url"); die(); } $secure_cookie = is_ssl(); if (!$secure_cookie && !force_ssl_admin()) { $user = get_user_by('login', $user_data["username"]); if ($user && get_user_option('use_ssl', $user->ID)) { $secure_cookie = true; force_ssl_admin(true); } } $user = wp_signon(array( "user_login" => $user_data["username"], "user_password" => $user_data["password"], "remember" => "forever", ), $secure_cookie); $url = $wordpress_url . "/wp-admin/" . $_GET["wordpress_admin_file_to_open"]; } else $url = $project_url_prefix . "phpframework/cms/wordpress/install?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&db_driver=$db_driver"; header("Location: $url"); echo "<script>document.location='$url';</script>"; } else { launch_exception(new Exception("Undefined db driver!")); die(); } } else { launch_exception(new Exception("PEVC doesn't exists!")); die(); } } else { launch_exception(new Exception("Undefined bean!")); die(); } function getProjectCommonUrlPrefix($EVC, $selected_project_id) { include $EVC->getConfigPath("config", $selected_project_id); return $project_common_url_prefix; } ?>
