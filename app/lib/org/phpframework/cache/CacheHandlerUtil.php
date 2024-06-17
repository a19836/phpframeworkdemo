<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.util.HashCode"); class CacheHandlerUtil { const CACHE_FILE_EXTENSION = "cache"; public static function getCacheFilePath($pf3dc0762) { if ($pf3dc0762) $pf3dc0762 = $pf3dc0762 . "." . self::CACHE_FILE_EXTENSION; return $pf3dc0762; } public static function getFilePathKey($v250a1176c9) { return HashCode::getHashCodePositive($v250a1176c9); } public static function getConfigureRegexp($v7be595dc2a) { $pbd1bc7b0 = strrpos($v7be595dc2a, "/"); if($pbd1bc7b0 > 0) return $v7be595dc2a; $v7be595dc2a = substr($v7be595dc2a, 0, 1) != "/" ? "/" . $v7be595dc2a : $v7be595dc2a; $v7be595dc2a .= substr($v7be595dc2a, strlen($v7be595dc2a) - 1) != "/" ? "/" : ""; return $v7be595dc2a; } public static function checkIfKeyTypeMatchValue($v67db1bd535, $pbfa01ed1, $v3fb9f41470) { $v5c1c342594 = false; if (empty($v3fb9f41470)) $v5c1c342594 = $v67db1bd535 === $pbfa01ed1; else { $v3fb9f41470 = strtolower($v3fb9f41470); switch ($v3fb9f41470) { case "regexp": case "regex": $v7be595dc2a = self::getConfigureRegexp($pbfa01ed1); $v5c1c342594 = preg_match($v7be595dc2a, $v67db1bd535); break; case "start": case "begin": case "prefix": $v5c1c342594 = substr($v67db1bd535, 0, strlen($pbfa01ed1)) == $pbfa01ed1; break; case "middle": $pbd1bc7b0 = strpos($v67db1bd535, $pbfa01ed1); $v5c1c342594 = is_numeric($pbd1bc7b0) && $pbd1bc7b0 >= 0; break; case "end": case "finish": case "suffix": $v5c1c342594 = substr($v67db1bd535, strlen($v67db1bd535) - strlen($pbfa01ed1)) == $pbfa01ed1; break; } } return $v5c1c342594; } public static function dF($v89d33f4133, $v64373479bb = true, $pae9f0543 = array()) { $v5c1c342594 = self::deleteFolder($v89d33f4133, $v64373479bb, $pae9f0543); if ($v89d33f4133 && function_exists('exec')) { $v2bfa5ee318 = $v89d33f4133 . (!$v64373479bb ? '/*' : ''); if (!$pae9f0543) @exec("rm -rf '$v2bfa5ee318'"); } return $v5c1c342594; } public static function getCorrectKeyType($v3fb9f41470) { switch(strtolower($v3fb9f41470)) { case "regex": case "regexp": return "regexp"; case "start": case "begin": case "prefix": return "prefix"; case "middle": return "middle"; case "end": case "finish": case "suffix": return "suffix"; } return ""; } public static function getRegexFromKeyType($pbfa01ed1, $v3fb9f41470) { $v3fb9f41470 = self::getCorrectKeyType($v3fb9f41470); $v5f7147fb39 = false; switch(strtolower($v3fb9f41470)) { case "regexp": $v5f7147fb39 = $pbfa01ed1; break; case "prefix": $v5f7147fb39 = $pbfa01ed1 . "(.*)"; break; case "middle": $v5f7147fb39 = "(.*)" . $pbfa01ed1 . "(.*)"; break; case "suffix": $v5f7147fb39 = "(.*)" . $pbfa01ed1; break; } $v5f7147fb39 = self::getConfigureRegexp($v5f7147fb39); return $v5f7147fb39; } public static function configureFolderPath(&$v17be587282) { $v17be587282 .= $v17be587282 && substr($v17be587282, -1) != "/" ? "/" : ""; } public static function preparePath($v17be587282) { return !empty($v17be587282) && !file_exists($v17be587282) ? @mkdir($v17be587282, 0755, true) : true; } public static function deleteFolder($v89d33f4133, $v64373479bb = true, $pae9f0543 = array()) { if ($v89d33f4133) { if (is_dir($v89d33f4133)) { $v5c1c342594 = true; $v7959970a41 = false; $v6ee393d9fb = array_diff(scandir($v89d33f4133), array('.', '..')); foreach ($v6ee393d9fb as $v7dffdb5a5b) { $v9a84a79e2e = $v89d33f4133 . "/" . $v7dffdb5a5b; if (in_array(realpath($v9a84a79e2e), $pae9f0543)) $v7959970a41 = true; else if (is_dir($v9a84a79e2e)) { if (!self::deleteFolder($v9a84a79e2e, true)) $v5c1c342594 = false; } else if (!unlink($v9a84a79e2e)) $v5c1c342594 = false; } return !$v7959970a41 && $v64373479bb && $v5c1c342594 ? rmdir($v89d33f4133) : $v5c1c342594; } } return true; } } ?>
