<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class LayoutTypeProjectUIHandler { public static function getHeader() { return '
<script>
function prepareGetSubFilesUrlToFilterOnlyByBelongingFiles(url) {
	if (url.indexOf("?filter_by_layout=") != -1 || url.indexOf("&filter_by_layout=") != -1) {
		//remove all filter_by_layout_permission
		url = url.replace(/&?filter_by_layout_permission=([^&]*)/g, "");
		url = url.replace(/&?filter_by_layout_permission\\[\\]=([^&]*)/g, "");
		
		url += "&filter_by_layout_permission=' . UserAuthenticationHandler::$PERMISSION_BELONG_NAME . '"; //add filter_by_layout_permission=referenced
	}
	
	return url;
}

function prepareGetSubFilesUrlToFilterOnlyByReferencedFiles(url) {
	if (url.indexOf("?filter_by_layout=") != -1 || url.indexOf("&filter_by_layout=") != -1) {
		//remove all filter_by_layout_permission
		url = url.replace(/&?filter_by_layout_permission=([^&]*)/g, "");
		url = url.replace(/&?filter_by_layout_permission\\[\\]=([^&]*)/g, "");
		
		url += "&filter_by_layout_permission=' . UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME . '"; //add filter_by_layout_permission=referenced
	}
	
	return url;
}
</script>
		'; } public static function getJavascriptHandlerToParseGetSubFilesUrlWithOnlyBelongingFiles() { return "prepareGetSubFilesUrlToFilterOnlyByBelongingFiles"; } public static function getJavascriptHandlerToParseGetSubFilesUrlWithOnlyReferencedFiles() { return "prepareGetSubFilesUrlToFilterOnlyByReferencedFiles"; } public static function getFilterByLayoutURLQuery($pb154d332) { return $pb154d332 ? "&filter_by_layout=$pb154d332&filter_by_layout_permission[]=" . UserAuthenticationHandler::$PERMISSION_BELONG_NAME . "&filter_by_layout_permission[]=" . UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME : ""; } } ?>
