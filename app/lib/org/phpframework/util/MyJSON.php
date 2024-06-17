<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.util.xml.MyXML"); class MyJSON { public static function arrayToJSON($pfb662071) { return json_encode($pfb662071); } public static function jSONToArray($v6dff65edce) { return json_decode($v6dff65edce); } public static function xmlToJSON($v241205aec6) { $v6dcd71ad57 = new MyXML($v241205aec6); $pfb662071 = $v6dcd71ad57->toArray(); return self::arrayToJSON($pfb662071); } } ?>
