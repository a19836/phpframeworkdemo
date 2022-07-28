<?php
include get_lib("lib.vendor.captcha.simple-php-captcha");
include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());

$session_id = $_GET["session_id"];

if ($session_id) {
	$captcha = simple_php_captcha();
	/*$captcha = simple_php_captcha( array(
		'min_length' => 5,
		'max_length' => 5,
		'backgrounds' => array(image.png', ...),
		'fonts' => array('font.ttf', ...),
		'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789',
		'min_font_size' => 28,
		'max_font_size' => 28,
		'color' => '#666',
		'angle_min' => 0,
		'angle_max' => 10,
		'shadow' => true,
		'shadow_color' => '#fff',
		'shadow_offset_x' => -1,
		'shadow_offset_y' => 1
	));*/
	
	if ($captcha["code"]) { 
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		UserUtil::updateUserSessionCaptchaBySessionId($brokers, array("session_id" => $session_id, "captcha" => $captcha["code"]));
	}
	
	generate_simple_captcha_image($captcha);
}
?>
