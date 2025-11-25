<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */
 class VendorFrameworkHandler { public static function getVendorFrameworkFolder($pa32be502) { if (self::isLaravelProjectFolder($pa32be502)) return "laravel"; return null; } public static function isLaravelProjectFolder($pa32be502) { return file_exists("$pa32be502/artisan") && is_dir("$pa32be502/app") && is_dir("$pa32be502/bootstrap") && is_dir("$pa32be502/config") && file_exists("$pa32be502/composer.json") && strpos(file_get_contents("$pa32be502/composer.json"), '"laravel/framework"'); } } ?>
