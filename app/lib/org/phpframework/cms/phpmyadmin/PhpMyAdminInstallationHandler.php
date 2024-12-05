<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class PhpMyAdminInstallationHandler { const PHPMYADMIN_ENCRYPTION_KEY = "5735dc60a42d263f84c105b986d53445"; public static function hackPhpMyAdminInstallation($pb0d8d323) { $v5c1c342594 = false; if (!$pb0d8d323 || !is_dir($pb0d8d323)) launch_exception(new Exception("PhpMyAdmin installation doesn't exists!")); $v98a8251725 = self::f9bf676f9ab($pb0d8d323); $v13e76aba2d = $pb0d8d323 . "/config.inc.php"; if (file_exists($v13e76aba2d)) { $v6490ea3a15 = file_get_contents($v13e76aba2d); $v64d618328f = preg_match("/include_once ". preg_quote($v98a8251725, "/") . ";/", $v6490ea3a15); if (!$v64d618328f) { $v6490ea3a15 .= "<?php
include_once $v98a8251725;
?>"; $v6490ea3a15 = preg_replace('/\?>\s*<\?php\s*/', "", $v6490ea3a15); $v5c1c342594 = file_put_contents($v13e76aba2d, $v6490ea3a15) !== false; } else $v5c1c342594 = true; } else { $v6490ea3a15 = self::f4d0bc7e944($v98a8251725); $v5c1c342594 = file_put_contents($v13e76aba2d, $v6490ea3a15) !== false; } return $v5c1c342594; } public static function isEnabled($pb0d8d323) { $v5c1c342594 = false; if ($pb0d8d323 && is_dir($pb0d8d323)) { $v13e76aba2d = $pb0d8d323 . "/config.inc.php"; if (file_exists($v13e76aba2d)) { $v6490ea3a15 = file_get_contents($v13e76aba2d); $v98a8251725 = self::f9bf676f9ab($pb0d8d323); $v64d618328f = preg_match("/include_once ". preg_quote($v98a8251725, "/") . ";/", $v6490ea3a15); $v5c1c342594 = $v64d618328f; } } return $v5c1c342594; } private static function f9bf676f9ab($pb0d8d323) { $pb0d8d323 = preg_replace("/\/+/", "/", $pb0d8d323); $v98a8251725 = substr($pb0d8d323, strlen(CMS_PATH)); $v98a8251725 = preg_replace("/(^\/|\/$)/", "", $v98a8251725); $v7354cde23e = substr_count($v98a8251725, '/') + 1; $v98a8251725 = "__DIR__ . '/" . str_repeat("../", $v7354cde23e) . "app/lib/org/phpframework/cms/phpmyadmin/init_db_credentials_for_phpmyadmin.php'"; return $v98a8251725; } private static function f4d0bc7e944($pd5f6432d) { $v40a0bbe826 = bin2hex(random_bytes(32)); return '<?php
/**
 * phpMyAdmin sample configuration, you can use it as base for
 * manual configuration. For easier setup you can use setup/
 *
 * All directives are explained in documentation in the doc/ folder
 * or at <https://docs.phpmyadmin.net/>.
 */

declare(strict_types=1);

/**
 * This is needed for cookie based authentication to encrypt the cookie.
 * Needs to be a 32-bytes long string of random bytes. See FAQ 2.10.
 * Eg:
 * 	php -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"
 */
$cfg["blowfish_secret"] = hex2bin("' . $v40a0bbe826 . '");

/**
 * Servers configuration
 */
$i = 0;

/**
 * First server
 */
$i++;
/* Authentication type */
$cfg["Servers"][$i]["auth_type"] = "cookie";

/* Server parameters */
$cfg["Servers"][$i]["compress"] = false;
$cfg["Servers"][$i]["AllowNoPassword"] = false;

/**
 * Directories for saving/loading files from server
 */
$cfg["UploadDir"] = "";
$cfg["SaveDir"] = "";

include_once ' . $pd5f6432d . ';
?>'; } } ?>
