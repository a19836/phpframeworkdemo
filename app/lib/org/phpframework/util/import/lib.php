<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 function get_lib($v327f72fb62, $v3fb9f41470 = "php") { $v30857f7eca = get_lib_settings($v327f72fb62); return $v30857f7eca[0] . str_replace(".", "/", $v30857f7eca[1] ) . "." . $v3fb9f41470; } function get_lib_settings($v327f72fb62) { $v8a4df75785 = strpos($v327f72fb62, "."); $v375507cc94 = substr($v327f72fb62, 0, $v8a4df75785); $v9643d970d1 = ""; switch(strtolower($v375507cc94)) { case "lib": $v9643d970d1 = LIB_PATH; break; case "app": $v9643d970d1 = APP_PATH; break; case "vendor": $v9643d970d1 = VENDOR_PATH; break; case "root": $v9643d970d1 = CMS_PATH; break; default: $v9643d970d1 = LIB_PATH; $v8a4df75785 = -1; } return array($v9643d970d1, substr($v327f72fb62, $v8a4df75785 + 1)); } ?>
