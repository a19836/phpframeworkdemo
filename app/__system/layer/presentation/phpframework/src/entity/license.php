<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
if (file_exists($license_path)) { include_once get_lib("lib.vendor.parsedown.Parsedown"); $content = file_get_contents($license_path); $Parsedown = new Parsedown(); $inner_html = $Parsedown->text($content); $html = '<html>
	<head>
		<style>
		blockquote {
			font: 14px/22px normal helvetica, sans-serif;
			margin-top: 10px;
			margin-bottom: 10px;
			margin-left: 0px;
			padding-left: 15px;
			border-left: 3px solid #FC3C44;
		}
		</style>
	</head>
	<body>' . $inner_html . '</body>
</html>'; echo $html; die(); } else { $msg = "No License found in '<root path>/" . (substr($license_path, strlen(CMS_PATH))) . "'!<br/>In order to use this software, you must get your license first! <br/>Otherwise you are not allowed to use this software!"; launch_exception(new Exception($msg)); } die(); ?>
