<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $filter_by_layout = $_GET["filter_by_layout"]; $popup = $_GET["popup"]; $creation_step = $_GET["creation_step"]; $on_success_js_func = $_GET["on_success_js_func"]; $path = str_replace("../", "", $path);$filter_by_layout = str_replace("../", "", $filter_by_layout); if (!$get_store_pages_url) $creation_step = 2; if (!$creation_step) { $creation_step = 0; } else if ($creation_step == 1) { if ($_POST) $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); include_once $EVC->getEntityPath("presentation/install_page"); if ($_POST && $status) { $status_message = 'Pre-built page successfully installed!'; $creation_step = 2; $from_step_1 = true; } } else if ($creation_step == 2) { } ?>
