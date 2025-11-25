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
 class SQLMapIncludesHandler { public static function getLibsOfResultClassAndMap($v21ff8db28c, $pce128343) { $pc06f1034 = array(); if ($v21ff8db28c) $pc06f1034[] = self::md9652182c280($v21ff8db28c); if (!empty($pce128343["attrib"]["class"])) $pc06f1034[] = self::md9652182c280($pce128343["attrib"]["class"]); $pc37695cb = !empty($pce128343["result"]) ? count($pce128343["result"]) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v9ad1385268 = $pce128343["result"][$v43dd7d0051]; if(is_array($v9ad1385268)) foreach($v9ad1385268 as $pbfa01ed1 => $v67db1bd535) if($v67db1bd535 && ($pbfa01ed1 == "output_type" || $pbfa01ed1 == "input_type")) $pc06f1034[] = self::md9652182c280($v67db1bd535); } return $pc06f1034; } private static function md9652182c280($pc24afc88) { $pbd1bc7b0 = strpos($pc24afc88, "("); $pc24afc88 = $pbd1bc7b0 !== false ? substr($pc24afc88, 0, $pbd1bc7b0) : $pc24afc88; return $pc24afc88; } public static function getRelationshipsLibsOfResultClassAndMap($pe33d544d) { $pc06f1034 = array(); if (is_array($pe33d544d)) { foreach($pe33d544d as $v016220e8f0 => $v10c59e20bd) { $v21ff8db28c = isset($v10c59e20bd["result_class"]) ? $v10c59e20bd["result_class"] : null; $pce128343 = isset($v10c59e20bd["result_map"]) ? $v10c59e20bd["result_map"] : null; $v1b590c61a6 = self::getLibsOfResultClassAndMap($v21ff8db28c, $pce128343); $pc06f1034 = array_merge($pc06f1034, $v1b590c61a6); } } return $pc06f1034; } public static function includeLibsOfResultClassAndMap($pc06f1034) { if (is_array($pc06f1034)) { $pc06f1034 = array_flip($pc06f1034); $pc06f1034 = array_flip($pc06f1034); reset($pc06f1034); foreach($pc06f1034 as $pc24afc88) { $pc24afc88 = get_lib($pc24afc88); if(file_exists($pc24afc88)) { include_once $pc24afc88; } } } } } ?>
