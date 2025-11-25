<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */
 include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = isset($_GET["bean_name"]) ? $_GET["bean_name"] : null; $bean_file_name = isset($_GET["bean_file_name"]) ? $_GET["bean_file_name"] : null; $path = isset($_GET["path"]) ? $_GET["path"] : null; $filter_by_layout = isset($_GET["filter_by_layout"]) ? $_GET["filter_by_layout"] : null; $popup = isset($_GET["popup"]) ? $_GET["popup"] : null; $creation_step = isset($_GET["creation_step"]) ? $_GET["creation_step"] : null; $on_success_js_func = isset($_GET["on_success_js_func"]) ? $_GET["on_success_js_func"] : null; $path = str_replace("../", "", $path); $filter_by_layout = str_replace("../", "", $filter_by_layout); if (!$get_store_pages_url) $creation_step = 2; if (!$creation_step) { $creation_step = 0; } else if ($creation_step == 1) { if (!empty($_POST)) $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); include_once $EVC->getEntityPath("presentation/install_page"); if (!empty($_POST) && !empty($status)) { $status_message = 'Pre-built page successfully installed!'; $creation_step = 2; $from_step_1 = true; } } else if ($creation_step == 2) { } ?>
