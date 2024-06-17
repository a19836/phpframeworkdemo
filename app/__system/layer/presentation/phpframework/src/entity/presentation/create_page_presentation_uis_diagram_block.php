<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $parent_entity_file_path = $_GET["path"]; $task_tag = $_GET["task_tag"]; $task_tag_action = $_GET["task_tag_action"]; $db_driver = $_GET["db_driver"]; $db_type = $_GET["db_type"]; $db_table = $_GET["db_table"]; $parent_add_block_func = $_GET["parent_add_block_func"]; $popup = $_GET["popup"]; $parent_entity_file_path = str_replace("../", "", $parent_entity_file_path); if (!$parent_add_block_func) die("parent_add_block_func missing"); if ($bean_name && $parent_entity_file_path) { $new_path = dirname($parent_entity_file_path) . "/" . pathinfo($parent_entity_file_path, PATHINFO_FILENAME) . "/"; $_GET["path"] = $new_path; } $do_not_load_or_save_workflow = true; $do_not_save_vars_file = true; $do_not_check_if_path_exists = true; $task_tag_action = str_replace(array(";", "|"), ",", $task_tag_action); $task_tag_action = explode(",", $task_tag_action); include $EVC->getEntityPath("presentation/create_presentation_uis_diagram"); ?>
