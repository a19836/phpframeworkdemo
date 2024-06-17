<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include $EVC->getUtilPath("BreadCrumbsUIHandler"); $title = "Choose Module in " . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($file_path, $P, true); $title_icons = ''; $add_block_url = $project_url_prefix . "phpframework/presentation/edit_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&filter_by_layout=$filter_by_layout&path=$path&module_id=#module_id#&edit_block_type=simple"; include $EVC->getViewPath("presentation/create_block"); ?>
