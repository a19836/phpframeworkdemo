<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

include_once get_lib("org.phpframework.cms.wordpress.WordPressCMSBlockSettings");
include_once dirname(__DIR__) . "/common/CommonSettings.php";

class WordPressSettings extends CommonSettings {
	const WORDPRESS_REQUEST_CONTENT_ENCRYPTION_KEY_HEX = WordPressCMSBlockSettings::WORDPRESS_REQUEST_CONTENT_ENCRYPTION_KEY_HEX; //must be the same name, bc if the user adds this as a global variable, this needs to affects here and in the WordPressCMSBlockSettings
	const WORDPRESS_REQUEST_CONTENT_CONNECTION_TIMEOUT = WordPressCMSBlockSettings::WORDPRESS_REQUEST_CONTENT_CONNECTION_TIMEOUT; //must be the same name, bc if the user adds this as a global variable, this needs to affects here and in the WordPressCMSBlockSettings
	
	const WORDPRESS_REQUEST_CONTENT_EXPIRATION_TIMEOUT = 30; //in seconds
}
?>
