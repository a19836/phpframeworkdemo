<?php
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
$cfg["blowfish_secret"] = hex2bin("db4269b02ef90450d73228b27194ff3c3f420ef7e105e272096003ce3beb46b4");

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

include_once __DIR__ . '/../../../../../../../../app/lib/org/phpframework/cms/phpmyadmin/init_db_credentials_for_phpmyadmin.php';
?>