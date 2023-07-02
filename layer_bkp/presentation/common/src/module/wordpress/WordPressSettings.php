<?php
include_once get_lib("org.phpframework.cms.wordpress.WordPressCMSBlockSettings");
include_once dirname(__DIR__) . "/common/CommonSettings.php";

class WordPressSettings extends CommonSettings {
	const WORDPRESS_REQUEST_CONTENT_ENCRYPTION_KEY_HEX = WordPressCMSBlockSettings::WORDPRESS_REQUEST_CONTENT_ENCRYPTION_KEY_HEX; //must be the same name, bc if the user adds this as a global variable, this needs to affects here and in the WordPressCMSBlockSettings
	const WORDPRESS_REQUEST_CONTENT_CONNECTION_TIMEOUT = WordPressCMSBlockSettings::WORDPRESS_REQUEST_CONTENT_CONNECTION_TIMEOUT; //must be the same name, bc if the user adds this as a global variable, this needs to affects here and in the WordPressCMSBlockSettings
	
	const WORDPRESS_REQUEST_CONTENT_EXPIRATION_TIMEOUT = 30; //in seconds
}
?>
