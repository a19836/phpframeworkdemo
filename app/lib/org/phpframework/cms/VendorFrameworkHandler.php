<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class VendorFrameworkHandler { public static function getVendorFrameworkFolder($pa32be502) { if (self::isLaravelProjectFolder($pa32be502)) return "laravel"; return null; } public static function isLaravelProjectFolder($pa32be502) { return file_exists("$pa32be502/artisan") && is_dir("$pa32be502/app") && is_dir("$pa32be502/bootstrap") && is_dir("$pa32be502/config") && file_exists("$pa32be502/composer.json") && strpos(file_get_contents("$pa32be502/composer.json"), '"laravel/framework"'); } } ?>
