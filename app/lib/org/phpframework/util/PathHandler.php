<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */ class PathHandler { public static function getAbsolutePath($pa32be502) { $pa32be502 = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $pa32be502); $v9cd205cadb = array_filter(explode(DIRECTORY_SEPARATOR, $pa32be502), 'strlen'); $v6b24f7fe5a = array(); foreach ($v9cd205cadb as $v1d2d80ed32) { if ('.' == $v1d2d80ed32) continue; if ('..' == $v1d2d80ed32) array_pop($v6b24f7fe5a); else $v6b24f7fe5a[] = $v1d2d80ed32; } return (substr($pa32be502, 0, 1) == DIRECTORY_SEPARATOR ? DIRECTORY_SEPARATOR : "") . implode(DIRECTORY_SEPARATOR, $v6b24f7fe5a) . (substr($pa32be502, -1) == DIRECTORY_SEPARATOR ? DIRECTORY_SEPARATOR : ""); } } ?>
