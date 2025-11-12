<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.util.xml.MyXML"); class MyJSON { public static function arrayToJSON($pfb662071) { return json_encode($pfb662071); } public static function jSONToArray($v6dff65edce) { return json_decode($v6dff65edce); } public static function xmlToJSON($v241205aec6) { $v6dcd71ad57 = new MyXML($v241205aec6); $pfb662071 = $v6dcd71ad57->toArray(); return self::arrayToJSON($pfb662071); } } ?>
