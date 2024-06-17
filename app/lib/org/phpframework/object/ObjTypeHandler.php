<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.object.db.DBPrimitive"); include_once get_lib("org.phpframework.object.php.Primitive"); class ObjTypeHandler { public static $attribute_names_as_created_date = array("created_date", "created_at"); public static $attribute_names_as_modified_date = array("modified_date", "modified_at"); public static $attribute_names_as_created_user_id = array("created_user_id", "created_by"); public static $attribute_names_as_modified_user_id = array("modified_user_id", "modified_by"); public static $attribute_names_as_title = array( "name", "nome", "nombre", "nom", "title", "titulo", "título", "titre", "label", "etiqueta", "etiquette", "étiquette" ); public static function getPHPTypesPaths() { $v4159504aa3 = array(); $pe0185878 = Primitive::getTypes(); foreach ($pe0185878 as $pb13127fc => $v945fda93f2) $v4159504aa3[ "org.phpframework.object.php.Primitive($pb13127fc)" ] = $v945fda93f2; $v6ee393d9fb = array_diff(scandir(__DIR__ . "/php/"), array('.', '..', 'Primitive.php')); foreach ($v6ee393d9fb as $v7dffdb5a5b) { $v250a1176c9 = pathinfo($v7dffdb5a5b, PATHINFO_FILENAME); $v4159504aa3[ "org.phpframework.object.php.$v250a1176c9" ] = $v250a1176c9; } return $v4159504aa3; } public static function getPHPTypes() { $v4159504aa3 = Primitive::getTypes(); $v6ee393d9fb = array_diff(scandir(__DIR__ . "/php/"), array('.', '..', 'Primitive.php')); foreach ($v6ee393d9fb as $v7dffdb5a5b) { $v250a1176c9 = pathinfo($v7dffdb5a5b, PATHINFO_FILENAME); $v4159504aa3[ $v250a1176c9 ] = $v250a1176c9; } return $v4159504aa3; } public static function getPHPNumericTypes() { return Primitive::getNumericTypes(); } public static function getDBTypesPaths() { $v4159504aa3 = array(); $pe76f54d6 = DBPrimitive::getTypes(); foreach ($pe76f54d6 as $pb13127fc => $v945fda93f2) $v4159504aa3[ "org.phpframework.object.db.DBPrimitive($pb13127fc)" ] = $v945fda93f2; return $v4159504aa3; } public static function getDBTypes() { return DBPrimitive::getTypes(); } public static function getDBNumericTypes() { return DBPrimitive::getNumericTypes(); } public static function getDBDateTypes() { return DBPrimitive::getDateTypes(); } public static function getDBTextTypes() { return DBPrimitive::getTextTypes(); } public static function getDBBlobTypes() { return DBPrimitive::getBlobTypes(); } public static function getDBBooleanTypeAvailableValues() { return DBPrimitive::getBooleanTypeAvailableValues(); } public static function getDBAttributeNameTitleAvailableValues() { return self::$attribute_names_as_title; } public static function getDBAttributeNameCreatedDateAvailableValues() { return self::$attribute_names_as_created_date; } public static function getDBAttributeNameModifiedDateAvailableValues() { return array_merge(self::$attribute_names_as_created_date, self::$attribute_names_as_modified_date); } public static function getDBAttributeNameCreatedUserIdAvailableValues() { return self::$attribute_names_as_created_user_id; } public static function getDBAttributeNameModifiedUserIdAvailableValues() { return array_merge(self::$attribute_names_as_created_user_id, self::$attribute_names_as_modified_user_id); } public static function getDBCurrentTimestampAvailableValues() { return DBPrimitive::getCurrentTimestampAvailableValues(); } public static function convertSimpleTypeIntoCompositeType($v3fb9f41470, $v4b559a4220 = "org.phpframework.object.php.Primitive") { $v3fb9f41470 = trim($v3fb9f41470); if ($v3fb9f41470) { if ($v3fb9f41470 == "MyString" || $v3fb9f41470 == "Integer" || $v3fb9f41470 == "Double" || $v3fb9f41470 == "MyFloat" || $v3fb9f41470 == "ArrayList" || $v3fb9f41470 == "HashMap" || $v3fb9f41470 == "MyObj") { return $v3fb9f41470; } else if (preg_match("/^([\w\-\+\ ]+)$/iu", $v3fb9f41470)) { $v3fb9f41470 = "$v4b559a4220($v3fb9f41470)"; } } return $v3fb9f41470; } public static function convertCompositeTypeIntoSimpleType($v3fb9f41470) { $v3fb9f41470 = trim($v3fb9f41470); if ($v3fb9f41470) { if (strpos($v3fb9f41470, "(") !== false) { preg_match_all('/\((.+)\)/u', $v3fb9f41470, $pbae7526c, PREG_PATTERN_ORDER); if (!empty($pbae7526c[0])) $v3fb9f41470 = $pbae7526c[1][0]; } else if (($pbd1bc7b0 = strrpos($v3fb9f41470, ".")) !== false) $v3fb9f41470 = substr($v3fb9f41470, $pbd1bc7b0 + 1); } return $v3fb9f41470; } public static function convertDBToPHPType($v3fb9f41470) { return $v3fb9f41470; } public static function isPHPTypeNumeric($v3fb9f41470) { return $v3fb9f41470 && self::f6a8ac663ed($v3fb9f41470, self::getPHPNumericTypes()); } public static function isDBTypeNumeric($v3fb9f41470) { return $v3fb9f41470 && self::f6a8ac663ed($v3fb9f41470, self::getDBNumericTypes()); } public static function isDBTypeDate($v3fb9f41470) { return $v3fb9f41470 && self::f6a8ac663ed($v3fb9f41470, self::getDBDateTypes()); } public static function isDBTypeText($v3fb9f41470) { return $v3fb9f41470 && self::f6a8ac663ed($v3fb9f41470, self::getDBTextTypes()); } public static function isDBTypeBlob($v3fb9f41470) { return $v3fb9f41470 && self::f6a8ac663ed($v3fb9f41470, self::getDBBlobTypes()); } public static function isDBTypeBoolean($v3fb9f41470) { return $v3fb9f41470 && self::f6a8ac663ed($v3fb9f41470, self::getDBBooleanTypeAvailableValues()); } private static function f6a8ac663ed($v3fb9f41470, $v4159504aa3) { $v3fb9f41470 = self::convertCompositeTypeIntoSimpleType($v3fb9f41470); return in_array($v3fb9f41470, $v4159504aa3); } public static function isDBAttributeNameATitle($v5e813b295b) { return $v5e813b295b && in_array($v5e813b295b, self::getDBAttributeNameTitleAvailableValues()); } public static function isDBAttributeNameACreatedDate($v5e813b295b) { return $v5e813b295b && in_array($v5e813b295b, self::getDBAttributeNameCreatedDateAvailableValues()); } public static function isDBAttributeNameAModifiedDate($v5e813b295b) { return $v5e813b295b && in_array($v5e813b295b, self::getDBAttributeNameModifiedDateAvailableValues()); } public static function isDBAttributeNameACreatedUserId($v5e813b295b) { return $v5e813b295b && in_array($v5e813b295b, self::getDBAttributeNameCreatedUserIdAvailableValues()); } public static function isDBAttributeNameAModifiedUserId($v5e813b295b) { return $v5e813b295b && in_array($v5e813b295b, self::getDBAttributeNameModifiedUserIdAvailableValues()); } public static function isDBAttributeValueACurrentTimestamp($v67db1bd535) { if ($v67db1bd535) { $v76b0aa2076 = self::getDBCurrentTimestampAvailableValues(); if (in_array($v67db1bd535, $v76b0aa2076)) return true; $pf09bd9b7 = strtolower($v67db1bd535); for ($v43dd7d0051 = 0, $pc37695cb = count($v76b0aa2076); $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) if ($pf09bd9b7 == strtolower($v76b0aa2076[$v43dd7d0051])) return true; } return false; } } ?>
