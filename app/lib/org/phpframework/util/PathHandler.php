<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 class PathHandler { public static function getAbsolutePath($pa32be502) { $pa32be502 = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $pa32be502); $v9cd205cadb = array_filter(explode(DIRECTORY_SEPARATOR, $pa32be502), 'strlen'); $v6b24f7fe5a = array(); foreach ($v9cd205cadb as $v1d2d80ed32) { if ('.' == $v1d2d80ed32) continue; if ('..' == $v1d2d80ed32) array_pop($v6b24f7fe5a); else $v6b24f7fe5a[] = $v1d2d80ed32; } return (substr($pa32be502, 0, 1) == DIRECTORY_SEPARATOR ? DIRECTORY_SEPARATOR : "") . implode(DIRECTORY_SEPARATOR, $v6b24f7fe5a) . (substr($pa32be502, -1) == DIRECTORY_SEPARATOR ? DIRECTORY_SEPARATOR : ""); } } ?>
