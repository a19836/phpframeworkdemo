<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
if (!function_exists("normalize_windows_path_to_linux")) { function normalize_windows_path_to_linux($pa32be502) { return DIRECTORY_SEPARATOR != "/" ? str_replace(DIRECTORY_SEPARATOR, "/", $pa32be502) : $pa32be502; } } if (!function_exists("get_lib")) { function get_lib($pa32be502) { $pa32be502 = strpos($pa32be502, "lib.") === 0 ? substr($pa32be502, strlen("lib.")) : $pa32be502; return dirname(dirname(dirname(dirname(normalize_windows_path_to_linux(__DIR__))))) . "/" . str_replace(".", "/", $pa32be502) . ".php"; } } include_once get_lib("org.phpframework.phpscript.PHPCodePrintingHandler"); class WordPressInstallationHandler { public static function hackWordPress($v08d9602741, $v872f5b4dbb, $v5c5dfdb754, $v3b8d285d6e, $v4d92d44fa9, $v317efb26b3, &$pef612b9d) { $v4d92d44fa9 = preg_replace("/\/+$/", "", $v4d92d44fa9); $v9a84a79e2e = $v3b8d285d6e . "wp-config.php"; if (file_exists($v9a84a79e2e) || copy($v3b8d285d6e . "wp-config-sample.php", $v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); $v6490ea3a15 = str_replace("\r", "", $v6490ea3a15); $v68cb9a35a2 = isset($v5c5dfdb754["host"]) ? $v5c5dfdb754["host"] : null; $pd8f15bc2 = isset($v5c5dfdb754["port"]) ? $v5c5dfdb754["port"] : null; $v68af10efbe = isset($v5c5dfdb754["db_name"]) ? $v5c5dfdb754["db_name"] : null; $padc0af92 = isset($v5c5dfdb754["username"]) ? $v5c5dfdb754["username"] : null; $v499dcd0e11 = isset($v5c5dfdb754["password"]) ? $v5c5dfdb754["password"] : null; $v99a3d13f74 = isset($v5c5dfdb754["encoding"]) ? $v5c5dfdb754["encoding"] : null; $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")DB_HOST('|\")\s*,[^\)]*\)\s*;/", "define('DB_HOST', '" . $v68cb9a35a2 . ($pd8f15bc2 ? ":" . $pd8f15bc2 : "") . "');", $v6490ea3a15); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")DB_NAME('|\")\s*,[^\)]*\)\s*;/", "define('DB_NAME', '" . $v68af10efbe . "');", $v6490ea3a15); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")DB_USER('|\")\s*,[^\)]*\)\s*;/", "define('DB_USER', '" . $padc0af92 . "');", $v6490ea3a15); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")DB_PASSWORD('|\")\s*,[^\)]*\)\s*;/", "define('DB_PASSWORD', '" . $v499dcd0e11 . "');", $v6490ea3a15); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")DB_CHARSET('|\")\s*,[^\)]*\)\s*;/", "define('DB_CHARSET', '" . $v99a3d13f74 . "');", $v6490ea3a15); $v6bbfbd6421 = "auth key " . CryptoKeyHandler::binToHex( CryptoKeyHandler::getKey() ); $v34324f17be = "secure auth key " . CryptoKeyHandler::binToHex( CryptoKeyHandler::getKey() ); $v20c88c0e36 = "logged in key " . CryptoKeyHandler::binToHex( CryptoKeyHandler::getKey() ); $v53b86f614a = "monce key " . CryptoKeyHandler::binToHex( CryptoKeyHandler::getKey() ); $v44e84c55ae = "auth salt " . CryptoKeyHandler::binToHex( CryptoKeyHandler::getKey() ); $pdffa1d77 = "secure auth salt " . CryptoKeyHandler::binToHex( CryptoKeyHandler::getKey() ); $pe4edb7f9 = "logged in salt " . CryptoKeyHandler::binToHex( CryptoKeyHandler::getKey() ); $v3d5af809db = "monce salt " . CryptoKeyHandler::binToHex( CryptoKeyHandler::getKey() ); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")AUTH_KEY('|\")\s*,[^\)]*\)\s*;/", "define('AUTH_KEY', '" . $v6bbfbd6421 . "');", $v6490ea3a15); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")SECURE_AUTH_KEY('|\")\s*,[^\)]*\)\s*;/", "define('SECURE_AUTH_KEY', '" . $v34324f17be . "');", $v6490ea3a15); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")LOGGED_IN_KEY('|\")\s*,[^\)]*\)\s*;/", "define('LOGGED_IN_KEY', '" . $v20c88c0e36 . "');", $v6490ea3a15); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")NONCE_KEY('|\")\s*,[^\)]*\)\s*;/", "define('NONCE_KEY', '" . $v53b86f614a . "');", $v6490ea3a15); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")AUTH_SALT('|\")\s*,[^\)]*\)\s*;/", "define('AUTH_SALT', '" . $v44e84c55ae . "');", $v6490ea3a15); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")SECURE_AUTH_SALT('|\")\s*,[^\)]*\)\s*;/", "define('SECURE_AUTH_SALT', '" . $pdffa1d77 . "');", $v6490ea3a15); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")LOGGED_IN_SALT('|\")\s*,[^\)]*\)\s*;/", "define('LOGGED_IN_SALT', '" . $pe4edb7f9 . "');", $v6490ea3a15); $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")NONCE_SALT('|\")\s*,[^\)]*\)\s*;/", "define('NONCE_SALT', '" . $v3d5af809db . "');", $v6490ea3a15); $v91a962d917 = 'define( \'WP_DEBUG\',${3});

//Define URL based in the phpframework_wp_request_uri, which is the relative request uri dynamically
global $phpframework_wp_request_uri;

if ($phpframework_wp_request_uri) {
	$protocol = !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" ? "https" : "http";
	$url = $protocol . "://" . $_SERVER["HTTP_HOST"] . $phpframework_wp_request_uri;
	define("WP_HOME", $url);
	define("WP_SITEURL", $url);
}

//very important to define the cookies paths as root, otherwise when the wordpress access the cookies through the WordPressHacker.php or WordPressCMSBlockHandler.php, the url will be different and it cannot get the right cookies. If I set the paths to root "/", I fix this issue.
define("COOKIEPATH", "/");
define("SITECOOKIEPATH", "/");
//define("ADMIN_COOKIE_PATH", "/"); //this is only for the wp-admin panel ui. Leave the default
define("PLUGINS_COOKIE_PATH", "/");

//disable automatic updates
define("WP_AUTO_UPDATE_CORE", false);
'; $v6490ea3a15 = preg_replace("/define\(\s*(\"|')WP_DEBUG(\"|')\s*,([^\)]+)\);/", $v91a962d917, $v6490ea3a15); if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-config.php with DB credentials and security keys. Please try again..."; } else $pef612b9d = "Error trying to create wp-config.php. Please try again..."; $v15493e4c60 = getallheaders(); $v2639654a50 = null; if (!empty($v15493e4c60["Authorization"])) { $v9a84a79e2e = $v3b8d285d6e . "wp-admin/includes/upgrade.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, 'array("Authorization"') === false) { $v2639654a50 = $v6490ea3a15; $v391cc249fc = '/\$response\s*=\s*wp_remote_get\s*\(\s*\$test_url\s*,\s*array\s*\(\s*("|\')timeout("|\')\s*=>\s*([0-9]+)\s*\)\s*\);/i'; $v91a962d917 = '$response = wp_remote_get( $test_url, array( ${1}timeout${2} => ${3}, "headers" => array("Authorization" => \'' . $v15493e4c60["Authorization"] . '\') ) );'; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15); if (strpos($v6490ea3a15, 'array("Authorization"') === false) $pef612b9d = "Could not add Authorization header to wp_remote_get function in wp-admin/includes/upgrade.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-admin/includes/upgrade.php with Authorization header code. Please try again..."; } } else $pef612b9d = "File 'wp-admin/includes/upgrade.php' not found. Please try again..."; } define("WP_HOME", $v4d92d44fa9); define("WP_SITEURL", $v4d92d44fa9); define('WP_INSTALLING', true); require_once $v3b8d285d6e . 'wp-load.php'; require_once $v3b8d285d6e . 'wp-admin/includes/upgrade.php'; require_once $v3b8d285d6e . 'wp-admin/includes/translation-install.php'; require_once $v3b8d285d6e . WPINC . '/wp-db.php'; global $wp_version, $required_php_version, $required_mysql_version; if (!is_blog_installed()) { $v44deca3193 = 'en_US'; $v019cd98f0f = isset($v317efb26b3["username"]) ? $v317efb26b3["username"] : null; $pa5a7bf31 = isset($v317efb26b3["password"]) ? $v317efb26b3["password"] : null; $v929befc495 = isset($v317efb26b3["email"]) ? $v317efb26b3["email"] : null; $v9f4f241f4c = wp_unslash("PHPFramework " . $v872f5b4dbb); $v7ef7482f16 = wp_unslash($v019cd98f0f); $pa871ec4b = wp_unslash($pa5a7bf31); $v3a61dc2bec = wp_unslash($v929befc495 ? $v317efb26b3["email"] : "dummy@gmail.com"); $v1074acb702 = 1; $wpdb->show_errors(); $v9ad1385268 = wp_install($v9f4f241f4c, $v7ef7482f16, $v3a61dc2bec, $v1074acb702, '', wp_slash($pa871ec4b), $v44deca3193); if ((isset($pa871ec4b) && !isset($v9ad1385268["password"])) || $v9ad1385268["password"] != $pa871ec4b) $pef612b9d = "Something went wrong with the wordpress installation. Please try again..."; $v067674f4e4 = '<?php
//added by phpframework at ' . date("Y-m-d H:i") . '
$admin_user = "' . $v7ef7482f16 . '";
$admin_pass = "' . $pa871ec4b . '";
?>'; if (file_put_contents($v3b8d285d6e . "default_admin_credentials.php", $v067674f4e4) === false) $pef612b9d = "Could not create file with default admin credentials. Please try again..."; update_option("home", $v4d92d44fa9); update_option("siteurl", $v4d92d44fa9); } if ($v2639654a50) { if (file_put_contents($v3b8d285d6e . "wp-admin/includes/upgrade.php", $v2639654a50) === false) $pef612b9d = "Could not re-write the original code of wp-admin/includes/upgrade.php. Please try again..."; } $v39d1337f82 = $v08d9602741->getWebrootPath() . "assets/wordpress_phpframework_plugin.zip"; if (ZipHandler::unzip($v39d1337f82, $v3b8d285d6e . "wp-content/plugins/")) { activate_plugin("phpframework/phpframework.php"); } else $pef612b9d = "Error unzip 'assets/wordpress_phpframework_plugin.zip'. Please try again..."; $v39d1337f82 = $v08d9602741->getWebrootPath() . "assets/wordpress_phpframework_template.zip"; if (!ZipHandler::unzip($v39d1337f82, $v3b8d285d6e . "wp-content/themes/")) $pef612b9d = "Error unzip 'assets/wordpress_phpframework_plugin.zip'. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . "wp-admin/includes/user.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, 'file_put_contents($default_admin_credentials_path, $contents)') === false) { $v391cc249fc = '/\$user_id\s*=\s*wp_update_user\s*\(\s*\$user\s*\)\s*;/'; $v91a962d917 = '${0}
		
		//changed by phpframework at ' . date("Y-m-d H:i") . '
		//updates the default_admin_credentials.php with new password, if user changed it
		$default_admin_credentials_path = ABSPATH . "default_admin_credentials.php";
		
		if (!is_wp_error($user_id) && file_exists($default_admin_credentials_path)) {
			include $default_admin_credentials_path;
			
			if ($user->user_login == $admin_user && $user->user_pass) { //it means the user changed his password
				$contents = file_get_contents($default_admin_credentials_path);
				
				if (preg_match(\'/\\\$admin_pass\s*=\s*/\', $contents, $matches, PREG_OFFSET_CAPTURE)) {
					$start_pos = $matches[0][1];
					$end_pos = stripos($contents, "\n", $start_pos);
					$end_pos = $end_pos === false ? strlen($contents) : $end_pos;
					
					$to_search = trim( substr($contents, $start_pos, $end_pos - $start_pos) );
					
					$contents = str_replace($to_search, \'$admin_pass = "\' . addcslashes(wp_unslash($user->user_pass), \'\\\\"\') . \'";\', $contents);
					
					file_put_contents($default_admin_credentials_path, $contents);
				}
			}
		}'; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15); if (strpos($v6490ea3a15, 'file_put_contents($default_admin_credentials_path, $contents)') === false) $pef612b9d = "Could not find text to replace in wp-admin/includes/user.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-admin/includes/user.php with phpframework redirect function. Please try again..."; } } else $pef612b9d = "File 'wp-admin/includes/user.php' not found. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . "wp-includes/pluggable.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, 'WordPressCMSBlockHandler') === false) { $v391cc249fc = '/header\s*\(\s*("|\')\s*Location:\s*\$location("|\')[^\)]*\)\s*;/'; $v91a962d917 = '//changed by phpframework at ' . date("Y-m-d H:i") . '
		//change the location value according with the phpframework current page
		if (class_exists("WordPressCMSBlockHandler") && method_exists("WordPressCMSBlockHandler", "prepareRedirectUrl"))
			WordPressCMSBlockHandler::prepareRedirectUrl($location, basename(dirname(__DIR__)));
		
		${0}'; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15); if (strpos($v6490ea3a15, 'WordPressCMSBlockHandler') === false) $pef612b9d = "Could not find text to replace in wp-includes/pluggable.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-includes/pluggable.php with phpframework redirect function. Please try again..."; } } else $pef612b9d = "File 'wp-includes/pluggable.php' not found. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . "wp-blog-header.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); $v391cc249fc = '/require_once\s*ABSPATH\s*.\s*WPINC\s*.\s*("|\')\/template-loader.php("|\')\s*;/'; $v91a962d917 = "//changed by phpframework at " . date("Y-m-d H:i") . "
	//changed require_once to require so we can call multiple times the wordpress template
	require ABSPATH . WPINC . '/template-loader.php';"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15); if (preg_match($v391cc249fc, $v6490ea3a15)) $pef612b9d = "Could not find text to replace in wp-blog-header.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-blog-header.php to include a template multiple times. Please try again..."; } else $pef612b9d = "File 'wp-blog-header.php' not found. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . "wp-includes/general-template.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, "\$current_phpframework_result_key = 'header';") === false) { $v391cc249fc = '/function\s+get_header\s*\([^{]+\{/'; $v91a962d917 = "\${0}
	//changed by phpframework at " . date("Y-m-d H:i") . "
	global \$phpframework_options, \$phpframework_results, \$current_phpframework_result_key;
	
	//start fetching header output
	if (\$phpframework_options) {
		\$obgc = ob_get_contents();
		\$phpframework_results['before_header'] .= \$obgc;
		\$phpframework_results['full_page_html'] .= \$obgc;
		ob_end_clean();
		
		ob_start(null, 0);
		\$current_phpframework_result_key = 'header';
	}
	"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); } if (strpos($v6490ea3a15, "\$current_phpframework_result_key = 'theme_content';") === false) { $v391cc249fc = '/if\s*\(\s*!\s*(locate_template\([^)]*\))\s*\)\s*\{/'; $v91a962d917 = "\$status = \${1};
	
	//changed by phpframework at " . date("Y-m-d H:i") . "
	//stop fetching header output and save it
	if (\$phpframework_options) {
		\$obgc = ob_get_contents();
		\$obgc = '<!-- phpframework:template:region: \"Before Header\" -->' . \$obgc . '<!-- phpframework:template:region: \"After Header\" -->';
		\$phpframework_results[\$current_phpframework_result_key] .= \$obgc;
		\$phpframework_results['full_page_html'] .= \$obgc;
		ob_end_clean();
		
		ob_start(null, 0); //start a new ob_start that will be closed in the get_footer in order to get the theme_content
		\$current_phpframework_result_key = 'theme_content';
	}
	
	if ( ! \$status) {"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); } if (strpos($v6490ea3a15, "\$current_phpframework_result_key = 'header';") === false || strpos($v6490ea3a15, "\$current_phpframework_result_key = 'theme_content';") === false) $pef612b9d = "Could not find text to replace in wp-includes/general-template.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-includes/general-template.php to include the get_header hacking. Please try again..."; } else $pef612b9d = "File 'wp-includes/general-template.php' not found. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . "wp-includes/general-template.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, "\$current_phpframework_result_key = 'footer';") === false) { $v391cc249fc = '/function\s+get_footer\s*\([^{]+\{/'; $v91a962d917 = "\${0}
	//changed by phpframework at " . date("Y-m-d H:i") . "
	global \$phpframework_options, \$phpframework_results, \$current_phpframework_result_key;

	//start fetching footer output
	if (\$phpframework_options) {
		\$obgc = ob_get_contents();
		\$current_phpframework_result_key_label = ucwords(str_replace('_', ' ', \$current_phpframework_result_key));
		\$obgc = '<!-- phpframework:template:region: \"Before ' . \$current_phpframework_result_key_label . '\" -->' . \$obgc . '<!-- phpframework:template:region: \"After ' . \$current_phpframework_result_key_label . '\" -->';
		
		\$phpframework_results[\$current_phpframework_result_key] .= \$obgc;
		\$phpframework_results['full_page_html'] .= \$obgc;
		ob_end_clean();
		
		ob_start(null, 0);
		\$current_phpframework_result_key = 'footer';
	}
	"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); } if (strpos($v6490ea3a15, "\$current_phpframework_result_key = 'after_footer';") === false) { $v391cc249fc = '/if\s*\(\s*!\s*(locate_template\([^)]*\))\s*\)\s*\{/'; $v91a962d917 = "\$status = \${1};
	
	//changed by phpframework at " . date("Y-m-d H:i") . "
	//stop fetching footer output and save it
	if (\$phpframework_options) {
		\$obgc = ob_get_contents();
		\$obgc = '<!-- phpframework:template:region: \"Before Footer\" -->' . \$obgc . '<!-- phpframework:template:region: \"After Footer\" -->';
		\$phpframework_results[\$current_phpframework_result_key] .= \$obgc;
		\$phpframework_results['full_page_html'] .= \$obgc;
		ob_end_clean();
		
		ob_start(null, 0);
		\$current_phpframework_result_key = 'after_footer';
	}
	
	if ( ! \$status) {"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); } if (strpos($v6490ea3a15, "\$current_phpframework_result_key = 'footer';") === false || strpos($v6490ea3a15, "\$current_phpframework_result_key = 'after_footer';") === false) $pef612b9d = "Could not find text to replace in wp-includes/general-template.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-includes/general-template.php to include the get_footer hacking. Please try again..."; } else $pef612b9d = "File 'wp-includes/general-template.php' not found. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . "wp-includes/general-template.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, "phpframework_results['theme_side_bars']") === false) { $v391cc249fc = '/function\s+get_sidebar\s*\([^{]+\{/'; $v91a962d917 = "\${0}
	//changed by phpframework at " . date("Y-m-d H:i") . "
	global \$phpframework_options, \$phpframework_results;

	//start fetching sidebar output
	if (\$phpframework_options)
		ob_start(null, 0);
	"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); $v391cc249fc = '/if\s*\(\s*!\s*(locate_template\([^)]*\))\s*\)\s*\{/'; $v91a962d917 = "\$status = \${1};
	
	//changed by phpframework at " . date("Y-m-d H:i") . "
	//stop fetching sidebar output and save it
	if (\$phpframework_options) {
		//get sidebar
		\$sidebar_id = \$name ? \$name : 0;
		\$sidebar = ob_get_contents();
		\$sidebar = '<!-- phpframework:template:region: \"Before Side Bar: ' . \$sidebar_id . '\" -->' . \$sidebar . '<!-- phpframework:template:region: \"After Side Bar: ' . \$sidebar_id . '\" -->';
		\$phpframework_results['theme_side_bars'][\$sidebar_id][] = \$sidebar;
		ob_end_clean();
		
		//print sidebar html
		echo \$sidebar;
	}
	
	if ( ! \$status) {"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); } if (strpos($v6490ea3a15, "//start fetching sidebar output") === false || strpos($v6490ea3a15, "phpframework_results['theme_side_bars']") === false) $pef612b9d = "Could not find text to replace in wp-includes/general-template.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-includes/general-template.php to include the get_sidebar hacking. Please try again..."; } else $pef612b9d = "File 'wp-includes/general-template.php' not found. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . "wp-includes/widgets.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, "phpframework_results['theme_side_bars']") === false) { $v391cc249fc = '/function\s+dynamic_sidebar\s*\([^{]+\{/'; $v91a962d917 = "\${0}
	//changed by phpframework at " . date("Y-m-d H:i") . "
	global \$phpframework_options, \$phpframework_results;
	
	//start fetching sidebar output
	if (\$phpframework_options) 
		ob_start(null, 0);
	"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); $v391cc249fc = '/return\s+(apply_filters\s*\(\s*\'dynamic_sidebar_has_widgets\'\s*,\s*false,\s*\$index\s*\)\s*;)/'; $v91a962d917 = "
		//changed by phpframework at " . date("Y-m-d H:i") . "
		//stop fetching sidebar output and save it
		if (\$phpframework_options) {
			//get sidebar
			\$returned = \${1}
			\$sidebar_id = \$index ? \$index : 0;
			\$sidebar = ob_get_contents();
			\$sidebar = '<!-- phpframework:template:region: \"Before Side Bar: ' . \$sidebar_id . '\" -->' . \$sidebar . '<!-- phpframework:template:region: \"After Side Bar: ' . \$sidebar_id . '\" -->';
			\$phpframework_results['theme_side_bars'][\$sidebar_id][] = \$sidebar;
			ob_end_clean();
			
			//print sidebar html
			echo \$sidebar;
			
			return \$returned;
		}
		else
			return \${1}"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); $v391cc249fc = '/return\s+(apply_filters\s*\(\s*\'dynamic_sidebar_has_widgets\'\s*,\s*\$did_one\s*,\s*\$index\s*\)\s*;)/'; $v91a962d917 = "
	//changed by phpframework at " . date("Y-m-d H:i") . "
	//stop fetching sidebar output and save it
	if (\$phpframework_options) {
		//get sidebar
		\$returned = \${1}
		\$sidebar_id = \$index ? \$index : 0;
		\$sidebar = ob_get_contents();
		\$sidebar = '<!-- phpframework:template:region: \"Before Side Bar: ' . \$sidebar_id . '\" -->' . \$sidebar . '<!-- phpframework:template:region: \"After Side Bar: ' . \$sidebar_id . '\" -->';
		\$phpframework_results['theme_side_bars'][\$sidebar_id][] = \$sidebar;
		ob_end_clean();
		
		//print sidebar html
		echo \$sidebar;
		
		return \$returned;
	}
	else
		return \${1}"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); } if (strpos($v6490ea3a15, "phpframework_results['theme_side_bars']") === false) $pef612b9d = "Could not find text to replace in wp-includes/widgets.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-includes/widgets.php to include the get_sidebar hacking. Please try again..."; } else $pef612b9d = "File 'wp-includes/widgets.php' not found. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . "wp-includes/nav-menu-template.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, "phpframework_results['theme_menus']") === false) { $v391cc249fc = '/function\s+wp_nav_menu\s*\([^{]+\{/'; $v91a962d917 = "\${0}
	//changed by phpframework at " . date("Y-m-d H:i") . "
	global \$phpframework_options, \$phpframework_results;
	"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); $v391cc249fc = '/\$args\s*=\s*apply_filters\s*\(\s*\'wp_nav_menu_args\'\s*,\s*\$args\s*\)\s*;/'; $v91a962d917 = "\$phpframework_menu_id = !empty(\$args['menu']) ? \$args['menu'] : \$args['menu_id'];
	
	\${0}"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); $v391cc249fc = '/\$args\s*=\s*\(\s*object\s*\)\s*\$args\s*;/'; $v91a962d917 = "\${0}
	
	//changed by phpframework at " . date("Y-m-d H:i") . "
	//preparing menu id
	if (!\$phpframework_menu_id) {
		\$phpframework_menu_id = \$args->menu ? (is_object(\$args->menu) ? (\$args->menu->slug ? \$args->menu->slug : \$args->menu->term_id) : \$args->menu) : \$args->menu_id;
		
		if (!\$phpframework_menu_id)
			\$phpframework_menu_id = \$args->theme_location ? \$args->theme_location : 0;
	}"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); $v391cc249fc = '/\$args\s*\->\s*menu\s*=\s*\$menu\s*;/'; $v91a962d917 = "\${0}
		
		//changed by phpframework at " . date("Y-m-d H:i") . "
		//preparing menu id
		if (is_object(\$menu)) {
			if (\$args->theme_location && \$menu_maybe && \$menu_maybe == \$menu) {
				\$phpframework_menu_id = \$args->theme_location;
			}
			else {
				\$old_phpframework_menu_id = \$phpframework_menu_id;
				\$phpframework_menu_id = \$menu->slug ? \$menu->slug : \$menu->term_id;
				
				if (!\$phpframework_menu_id)
					\$phpframework_menu_id = \$old_phpframework_menu_id;
			}
		}"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); $v391cc249fc = '/\$nav_menu\s*=\s*apply_filters\(\s*\'pre_wp_nav_menu\'\s*,\s*null\s*,\s*\$args\s*\)\s*;\s*if\s*\(\s*null\s*!==\s*\$nav_menu\s*\)\s*\{/'; $v91a962d917 = "\${0}
		//changed by phpframework at " . date("Y-m-d H:i") . "
		//saving menu
		if (\$phpframework_options) {
			\$nav_menu = '<!-- phpframework:template:region: \"Before Nav Menu: ' . \$phpframework_menu_id . '\" -->' . \$nav_menu . '<!-- phpframework:template:region: \"After Nav Menu: ' . \$phpframework_menu_id . '\" -->';
			\$phpframework_results['theme_menus'][\$phpframework_menu_id][] = \$nav_menu;
		}
		"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); $v391cc249fc = '/return\s*(call_user_func\s*\(\s*\$args\->\s*fallback_cb\s*,\s*\(\s*array\s*\)\s*\$args\s*\)\s*;)/'; $v91a962d917 = "
		//changed by phpframework at " . date("Y-m-d H:i") . "
		//saving menu
		if (\$phpframework_options) {
			//get nav_menu
			ob_start(null, 0);
			\$returned = \${1}
			\$nav_menu = ob_get_contents();
			\$nav_menu = '<!-- phpframework:template:region: \"Before Nav Menu: ' . \$phpframework_menu_id . '\" -->' . \$nav_menu . '<!-- phpframework:template:region: \"After Nav Menu: ' . \$phpframework_menu_id . '\" -->';
			\$phpframework_results['theme_menus'][\$phpframework_menu_id][] = \$nav_menu . (\$args->echo ? '' : \$returned);
			ob_end_clean();
			
			//print nav_menu html
			echo \$nav_menu;
			
			return \$returned;
		}
		else
			return \${1}"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); $v391cc249fc = '/if\s*\(\s*\$args->echo\s*\)\s*\{\s*echo\s*\$nav_menu\s*;\s*\}\s*else\s*\{\s*return\s*\$nav_menu\s*;\s*}\s*\}/'; $v91a962d917 = "//changed by phpframework at " . date("Y-m-d H:i") . "
	//saving menu
	if (\$phpframework_options) {
		\$nav_menu = '<!-- phpframework:template:region: \"Before Nav Menu: ' . \$phpframework_menu_id . '\" -->' . \$nav_menu . '<!-- phpframework:template:region: \"After Nav Menu: ' . \$phpframework_menu_id . '\" -->';
		\$phpframework_results['theme_menus'][\$phpframework_menu_id][] = \$nav_menu;
	}
	
	\${0}"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); } if (strpos($v6490ea3a15, "phpframework_results['theme_menus']") === false) $pef612b9d = "Could not find text to replace in wp-includes/nav-menu-template.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-includes/nav-menu-template.php to include the wp_nav_menu hacking. Please try again..."; } else $pef612b9d = "File 'wp-includes/nav-menu-template.php' not found. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . "wp-includes/comment-template.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, "phpframework_results['theme_comments']") === false) { $v391cc249fc = '/if\s*\(\s*empty\(\s*\$file\s*\)\s*\)\s*\{\s*\$file\s*=\s*\'\/comments\.php\'\s*;\s*\}/'; $v91a962d917 = "//changed by phpframework at " . date("Y-m-d H:i") . "
	global \$phpframework_options, \$phpframework_results;
	
	//start fetching comments output
	if (\$phpframework_options)
		ob_start(null, 0);
	
	\${0}"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); $v391cc249fc = '/require\s+ABSPATH\s*\.\s*WPINC\s*\.\s*\'\/theme-compat\/comments\.php\'\s*;\s*\}/'; $v91a962d917 = "\${0}
		
	//changed by phpframework at " . date("Y-m-d H:i") . "
	//stop fetching comments output and save it
	if (\$phpframework_options) {
		//get comments html
		\$post_id = isset(\$comment_args['post_id']) ? \$comment_args['post_id'] : null;
		\$comments_html_id = \$post_id ? \$post_id : 0;
		\$comments_html = ob_get_contents();
		\$comments_html = '<!-- phpframework:template:region: \"Before Comments from post: ' . \$comments_html_id . '\" -->' . \$comments_html . '<!-- phpframework:template:region: \"After Comments from post: ' . \$comments_html_id . '\" -->';
		\$phpframework_results['theme_comments'][\$comments_html_id][] = \$comments_html;
		ob_end_clean();
		
		//print comments html
		echo \$comments_html;
	}"; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15, 1); } if (strpos($v6490ea3a15, "phpframework_results['theme_comments']") === false) $pef612b9d = "Could not find text to replace in wp-includes/comment-template.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-includes/comment-template.php to include the comments_template hacking. Please try again..."; } else $pef612b9d = "File 'wp-includes/comment-template.php' not found. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . "wp-admin/themes.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, '$theme["id"] == "phpframework"') === false) { $v391cc249fc = "/wp_reset_vars\s*\(\s*array\s*\(\s*'theme'\s*,\s*'search'\s*\)\s*\)\s*;/"; $v91a962d917 = '
//changed by phpframework at ' . date("Y-m-d H:i") . '
//hide the phpframework theme
if ($themes) {
	foreach ($themes as $idx => $theme)
		if (isset($theme["id"]) && $theme["id"] == "phpframework") {
			unset($themes[$idx]);
			break;
		}
	
	$themes = array_values($themes);
}

wp_reset_vars( array( \'theme\', \'search\' ) );'; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15); } if (strpos($v6490ea3a15, 'WordPressInstallationHandler::prepareFolderFilesWithDirectRequests(') === false) { $v391cc249fc = '/switch_theme\s*\(\s*\$theme\s*->\s*get_stylesheet\s*\(\s*\)\s*\)\s*;/'; $v91a962d917 = '${0}
		
		//changed by phpframework at ' . date("Y-m-d H:i") . '
		//when user activates a plugins this checks if there is any plugin\'s sub-file that can be call directly from ajax or the browser, and if so, adds the WordPressRequestHandler controls...
		$wpih_class_exists = class_exists("WordPressInstallationHandler");

		if (!$wpih_class_exists) {
			@include_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(ABSPATH))))))) . "/lib/org/phpframework/cms/wordpress/WordPressInstallationHandler.php";
			$wpih_class_exists = class_exists("WordPressInstallationHandler");
		}
		
		if ($wpih_class_exists) 
			WordPressInstallationHandler::prepareFolderFilesWithDirectRequests(ABSPATH, $theme->get_stylesheet_directory());
		'; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15); } if (strpos($v6490ea3a15, '$theme["id"] == "phpframework"') === false || strpos($v6490ea3a15, 'WordPressInstallationHandler::prepareFolderFilesWithDirectRequests(') === false) $pef612b9d = "Could not find text to replace in wp-admin/themes.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-admin/themes.php to exclude phpframework theme. Please try again..."; } else $pef612b9d = "File 'wp-admin/themes.php' not found. Please try again..."; $v6ee393d9fb = array("index.php", "wp-links-opml.php", "xmlrpc.php", "wp-admin/admin-ajax.php", "wp-admin/admin-post.php"); self::ma7ab49aa302c($v3b8d285d6e, $v6ee393d9fb, $pef612b9d); $v9a84a79e2e = $v3b8d285d6e . "wp-admin/plugins.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, 'WordPressInstallationHandler::prepareFolderFilesWithDirectRequests(') === false) { $v391cc249fc = '/\$result\s*=\s*activate_plugin\s*\([^;]+;/'; $v91a962d917 = '//changed by phpframework at ' . date("Y-m-d H:i") . '
			//when user activates a plugins this checks if there is any plugin\'s sub-file that can be call directly from ajax or the browser, and if so, adds the WordPressRequestHandler controls...
			if (dirname($plugin) != ".") { //be sure that the plugin is not a simple file and has a folder.
				$wpih_class_exists = class_exists("WordPressInstallationHandler");

				if (!$wpih_class_exists) {
					@include_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(ABSPATH))))))) . "/lib/org/phpframework/cms/wordpress/WordPressInstallationHandler.php";
					$wpih_class_exists = class_exists("WordPressInstallationHandler");
				}
				
				if ($wpih_class_exists) 
					WordPressInstallationHandler::prepareFolderFilesWithDirectRequests(ABSPATH, WP_PLUGIN_DIR . "/" . dirname($plugin));
			}
			
			${0}'; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15); } if (strpos($v6490ea3a15, 'WordPressInstallationHandler::prepareFolderFilesWithDirectRequests(') === false) $pef612b9d = "Could not find text to replace in wp-admin/plugins.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-admin/plugins.php to exclude phpframework theme. Please try again..."; } else $pef612b9d = "File 'wp-admin/plugins.php' not found. Please try again..."; $v049a3dab04 = 'echo \'<script>alert("If you execute any update action, you must then re-hack this WordPress installation manually.\\n\\nHere are the steps to manually re-hack it:\\n1- Open your PHPFramework admin panel (Advanced view);\\n2- in the left side bar, right click in a project and choose the \\\'Manage WordPress\\\' menu item;\\n3- The Manage WordPress Page will open in the right side bar;\\n4- In the new opened page, choose the correspondent WordPress installation;\\n5- Click in the \\\'Install WordPress\\\' button;\\n6- Click in the \\\'Re-Hacking WordPress ...\\\' button and voila...\\n\\nIf no errors are shown, WordPress was successfully re-hacked!");</script>\';'; $v9a84a79e2e = $v3b8d285d6e . "wp-admin/update-core.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, '<script>alert(') === false) { $v391cc249fc = '/\$action\s*=\s*isset\s*\(/'; $v91a962d917 = '//changed by phpframework at ' . date("Y-m-d H:i") . '
' . $v049a3dab04 . '

${0}'; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15); } if (strpos($v6490ea3a15, '<script>alert(') === false) $pef612b9d = "Could not find text to replace in wp-admin/update-core.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-admin/update-core.php to exclude phpframework theme. Please try again..."; } else $pef612b9d = "File 'wp-admin/update-core.php' not found. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . "wp-admin/update.php"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, '<script>alert(') === false) { $v391cc249fc = '/if\s*\(\s*isset\s*\(\s*\$_GET\s*\[\s*\'action\'\s*\]\s*\)\s*\)\s*\{/'; $v91a962d917 = '//changed by phpframework at ' . date("Y-m-d H:i") . '
' . $v049a3dab04 . '

${0}'; $v6490ea3a15 = preg_replace($v391cc249fc, $v91a962d917, $v6490ea3a15); } if (strpos($v6490ea3a15, '<script>alert(') === false) $pef612b9d = "Could not find text to replace in wp-admin/update.php. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update wp-admin/update.php to exclude phpframework theme. Please try again..."; } else $pef612b9d = "File 'wp-admin/update.php' not found. Please try again..."; $v9a84a79e2e = $v3b8d285d6e . ".htaccess"; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if (strpos($v6490ea3a15, 'RewriteEngine Off') === false) { $v6490ea3a15 = '
#added by phpframework at ' . date("Y-m-d H:i") . '
<IfModule mod_rewrite.c>
RewriteEngine Off
</IfModule>
' . $v6490ea3a15; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update .htaccess to include mod_rewrite. Please try again..."; } } else { $v307547b9ce = parse_url($v4d92d44fa9, PHP_URL_PATH); $v307547b9ce .= substr($v307547b9ce, -1) != "/" ? "/" : ""; $v6490ea3a15 = '
#added by phpframework at ' . date("Y-m-d H:i") . '
<IfModule mod_rewrite.c>
RewriteEngine Off
</IfModule>

# BEGIN WordPress
# The directives (lines) between "BEGIN WordPress" and "END WordPress" are
# dynamically generated, and should only be modified via WordPress filters.
# Any changes to the directives between these markers will be overwritten.

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase ' . $v307547b9ce . '
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . ' . $v307547b9ce . 'index.php [L]
</IfModule>

# END WordPress'; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not create .htaccess with mod_rewrite. Please try again..."; } return empty($pef612b9d); } private static function ma7ab49aa302c($v3b8d285d6e, $v6ee393d9fb, &$pef612b9d = null) { if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) { $v9a84a79e2e = $v3b8d285d6e . $v7dffdb5a5b; if (file_exists($v9a84a79e2e)) { $v6490ea3a15 = file_get_contents($v9a84a79e2e); if ($v6490ea3a15 && strpos($v6490ea3a15, '$WordPressRequestHandler->startCatchingOutput();') === false) { $v89d33f4133 = $v7dffdb5a5b; $v93069bcb20 = "__DIR__"; while (dirname($v89d33f4133) != ".") { $v93069bcb20 = "dirname($v93069bcb20)"; $v89d33f4133 = dirname($v89d33f4133); } $pec5115d2 = '
$wprh_class_exists = class_exists("WordPressRequestHandler");

if (!$wprh_class_exists) {
	@include_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(' . $v93069bcb20 . '))))))) . "/lib/org/phpframework/cms/wordpress/WordPressRequestHandler.php";
	$wprh_class_exists = class_exists("WordPressRequestHandler");
}

if ($wprh_class_exists) {
	$wordpress_folder_name = basename(' . $v93069bcb20 . '); //correspondent to the phpframework db driver name
	$WordPressRequestHandler = new WordPressRequestHandler($wordpress_folder_name, $wordpress_folder_name); //2nd argument is the cookies_prefix
	$WordPressRequestHandler->startCatchingOutput();
}
'; $pb9dab0ed = '

if ($wprh_class_exists)
	$WordPressRequestHandler->endCatchingOutput();
'; $pd8ad1780 = preg_match("/^<\?php\s*\/*/", $v6490ea3a15); $pbd1bc7b0 = strpos($v6490ea3a15, "*/"); if ($pd8ad1780 && $pbd1bc7b0 !== false) $v6490ea3a15 = substr($v6490ea3a15, 0, $pbd1bc7b0 + 2) . "\n" . $pec5115d2 . "\n" . trim(substr($v6490ea3a15, $pbd1bc7b0 + 2)); else $v6490ea3a15 = "<?php$pec5115d2?>" . trim($v6490ea3a15); $pb9cc54e7 = PHPCodePrintingHandler::getCodeWithoutComments($v6490ea3a15); $pd1bfee69 = strrpos($pb9cc54e7, "?>"); $pcb4c32fa = strrpos($pb9cc54e7, "<?"); $v8fe59b352e = $pcb4c32fa !== false && ($pd1bfee69 === false || $pcb4c32fa > $pd1bfee69); if ($v8fe59b352e) $v6490ea3a15 .= $pb9dab0ed; else $v6490ea3a15 .= "<?php$pb9dab0ed?>"; $v6490ea3a15 = preg_replace("/\?><\?(php|)/", "", $v6490ea3a15); if (strpos($v6490ea3a15, '$WordPressRequestHandler->startCatchingOutput();') === false) $pef612b9d = "Could not add WordPressRequestHandler controls to '$v7dffdb5a5b' file. Please try again..."; if (file_put_contents($v9a84a79e2e, $v6490ea3a15) === false) $pef612b9d = "Could not update '$v7dffdb5a5b' to include WordPressRequestHandler class. Please try again..."; } } else $pef612b9d = "WordPress File '$v7dffdb5a5b' not found. Please try again..."; } } public static function prepareFolderFilesWithDirectRequests($v3b8d285d6e, $pdd397f0a) { if (file_exists($pdd397f0a)) { $v6ee393d9fb = array_diff(scandir($pdd397f0a), array('..', '.')); foreach ($v6ee393d9fb as $v7dffdb5a5b) { $v9a84a79e2e = "$pdd397f0a/$v7dffdb5a5b"; if (is_dir($v9a84a79e2e)) self::prepareFolderFilesWithDirectRequests($v3b8d285d6e, $v9a84a79e2e); else if (strtolower(pathinfo($v9a84a79e2e, PATHINFO_EXTENSION)) == "php") { $pae77d38c = file_get_contents($v9a84a79e2e); if (preg_match("/(\/|\"|')load\.php/", $pae77d38c)) { $v9a84a79e2e = substr($v9a84a79e2e, strlen($v3b8d285d6e)); self::ma7ab49aa302c($v3b8d285d6e, array($v9a84a79e2e)); } } } } } } ?>
