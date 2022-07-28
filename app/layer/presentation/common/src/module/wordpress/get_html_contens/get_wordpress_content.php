<?php
include_once get_lib("org.phpframework.encryption.CryptoKeyHandler");
include_once get_lib("org.phpframework.cms.wordpress.WordPressCMSBlockHandler");
include_once $EVC->getModulePath("wordpress/WordPressSettings", $EVC->getCommonProjectName());

//get authentication header passwed from request, decrypted and get the $time and $md5
//then compare with $md5 == md5(serialize($POST)) and if time() < $time + 30; //+ 30 seconds only. If request is bigger than this, refuse connection. Only allow 30 seconds maximum.
//if not validaded!, echo null
$encryption_key = WordPressSettings::getConstantVariable("WORDPRESS_REQUEST_CONTENT_ENCRYPTION_KEY_HEX");
$is_user_authenticated = false;

$post_data = $_POST["phpframework_wordpress_data"];
unset($_POST["phpframework_wordpress_data"]);
unset($_REQUEST["phpframework_wordpress_data"]);

if ($encryption_key) {
	$is_user_authenticated = true;
	
	$key = CryptoKeyHandler::hexToBin($encryption_key);
	$cipher_text = $post_data["data"];
	$cipher_bin = CryptoKeyHandler::hexToBin($cipher_text);
	$str = CryptoKeyHandler::decryptText($cipher_bin, $key);
	
	$pos_1 = strpos($str, "_");
	$pos_2 = strpos($str, "_", $pos_1 + 1);
	$time = substr($str, 0, $pos_1);
	$md5 = substr($str, $pos_1 + 1, $pos_2 - ($pos_1 + 1));
	$serialized = substr($str, $pos_2 + 1);
	$expiration_time = WordPressSettings::getConstantVariable("WORDPRESS_REQUEST_CONTENT_EXPIRATION_TIMEOUT");
	$expiration_time = $expiration_time ? $expiration_time : 30; //default is 30 seconds
	
	if ($md5 == md5($serialized) && time() < $time + $expiration_time) {
		$post_data = unserialize($serialized);
		
		if (!is_array($post_data))
			$post_data = null;
	}
}

if ($post_data) {
	$settings = $post_data["settings"];
	$block_id = $post_data["block_id"];
	$url_query = $post_data["url_query"];
	$options = $post_data["options"];
	
	if ($options["request_method"]) {
		$orig_request_method = $_SERVER['REQUEST_METHOD'];
		$_SERVER['REQUEST_METHOD'] = $options["request_method"];
		
		if ($options["request_method"] == "GET") {
			$orig_post = $_POST;
			$_POST = array();
		}
	}
	
	$WordPressCMSBlockHandler = new \WordPressCMSBlockHandler($EVC, $settings, $is_user_authenticated); //$is_user_authenticated must be false, bc this page is public accessable
	$content = $WordPressCMSBlockHandler->getBlockContentDirectly($block_id, $url_query, $options);
	
	if ($options["request_method"]) {
		$_SERVER['REQUEST_METHOD'] = $orig_request_method;
		$_POST = $orig_post;
	}
	
	echo serialize($content);
}
?>
