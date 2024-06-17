<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include $EVC->getUtilPath("BreadCrumbsUIHandler"); $title = 'Add Block in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($file_path, $P, true); $title_icons = '<li class="go_back" title="Go Back"><a href="javascript:history.back();"><i class="icon go_back"></i> Go Back</a></li>'; $save_url = $project_url_prefix . "phpframework/presentation/save_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; include $EVC->getViewPath("presentation/edit_block_simple"); $main_content .= '<script>
$(function () {
	$(window).unbind("beforeunload");
	
	disableAutoSave(onToggleAutoSave);
	$(".top_bar").find("li.auto_save_activation").remove();
});
</script>'; ?>
