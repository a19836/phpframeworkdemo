<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.sqlmap.exception.SQLMapQueryException"); include_once get_lib("org.phpframework.object.ObjectHandler"); include_once get_lib("org.phpframework.util.HashTagParameter"); class SQLMapQueryHandler extends SQLMap { public function __construct() { parent::__construct(); } public function configureQuery(&$v3c76382d93, $v9367d5be85 = false, $v579230f9a8 = true) { if($v9367d5be85 !== false) { $pbae7526c = self::ma7914ff98efe($v3c76382d93); if(is_array($v9367d5be85)) { $v340fb528fc = array_keys($v9367d5be85) !== range(0, count($v9367d5be85) - 1); if($v340fb528fc) { self::f0eea090ee1($v3c76382d93, $pbae7526c, $v9367d5be85, $v579230f9a8); } else { self::mccaff64cc4b4($v3c76382d93, $pbae7526c, $v9367d5be85, $v579230f9a8); } } else { self::f792f826473($v3c76382d93, $pbae7526c, $v9367d5be85, $v579230f9a8); } } } public function transformData(&$v9367d5be85, $v217e7cf3c0 = false, $v2967293505 = false, $v8ce36c307f = false, $pf2c6b6ca = false) { if($v217e7cf3c0) { $v9367d5be85 = $this->mcd7bb639c039($v217e7cf3c0, $v9367d5be85); } elseif($v2967293505) { if(!is_array($v2967293505)) { if(isset($v8ce36c307f["parameter_map"][$v2967293505])) { $v2967293505 = $v8ce36c307f["parameter_map"][$v2967293505]; } else { launch_exception(new SQLMapQueryException(4)); } } $v9367d5be85 = $this->mc76de0cf9d7f($v2967293505, $v9367d5be85, $pf2c6b6ca); } } private static function ma7914ff98efe($v3c76382d93) { preg_match_all(HashTagParameter::SQL_HASH_TAG_PARAMETER_FULL_REGEX, $v3c76382d93, $v62bf8bcb37); return $v62bf8bcb37[0]; } private static function mccaff64cc4b4(&$v3c76382d93, $pbae7526c, $v9367d5be85, $v579230f9a8 = true) { $pc37695cb = count($pbae7526c); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1cfba8c105 = str_replace("#", "", $pbae7526c[$v43dd7d0051]); $v67db1bd535 = $v9367d5be85[$v43dd7d0051]; if($v579230f9a8) { $v67db1bd535 = (is_numeric($v67db1bd535) || is_bool($v67db1bd535)) && !is_string($v67db1bd535) ? $v67db1bd535 : self::f2c7c76576d($v67db1bd535); } $v3c76382d93 = str_replace($pbae7526c[$v43dd7d0051], $v67db1bd535, $v3c76382d93); } } private static function f0eea090ee1(&$v3c76382d93, $pbae7526c, $v9367d5be85, $v579230f9a8 = true) { $pc37695cb = count($pbae7526c); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1cfba8c105 = str_replace("#", "", $pbae7526c[$v43dd7d0051]); $v67db1bd535 = $v9367d5be85[$v1cfba8c105]; if($v579230f9a8) { $v67db1bd535 = (is_numeric($v67db1bd535) || is_bool($v67db1bd535)) && !is_string($v67db1bd535) ? $v67db1bd535 : self::f2c7c76576d($v67db1bd535); } $v3c76382d93 = str_replace($pbae7526c[$v43dd7d0051], $v67db1bd535, $v3c76382d93); } } private static function f792f826473(&$v3c76382d93, $pbae7526c, $v67db1bd535, $v579230f9a8 = true) { $pc37695cb = count($pbae7526c); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { if($v579230f9a8) { $v67db1bd535 = (is_numeric($v67db1bd535) || is_bool($v67db1bd535)) && !is_string($v67db1bd535) ? $v67db1bd535 : self::f2c7c76576d($v67db1bd535); } $v3c76382d93 = str_replace($pbae7526c[$v43dd7d0051], $v67db1bd535, $v3c76382d93); } } private static function f2c7c76576d($v67db1bd535) { return addcslashes($v67db1bd535, "\\'"); } private function mcd7bb639c039($v217e7cf3c0, $v9367d5be85) { $pa2e93a9c = array(); $v3884851b39 = ObjectHandler::getClassName($v217e7cf3c0); if(ObjectHandler::checkObjClass($v9367d5be85, $v3884851b39) && ObjectHandler::checkIfObjType($v3884851b39) && $this->getErrorHandler()->ok()) { $pa2e93a9c = $v9367d5be85->getData(); } return $pa2e93a9c; } private function mc76de0cf9d7f($v2967293505, $v9367d5be85, $pf2c6b6ca = false) { $v65a396e40d = array(); $pe2bfadf3 = isset($v2967293505["parameter"]) ? $v2967293505["parameter"] : false; $v5c2c9448bd = isset($v2967293505["attrib"]["class"]) ? $v2967293505["attrib"]["class"] : false; $pa2e93a9c = $v9367d5be85; if ($v5c2c9448bd) { $v6c8ff7a378 = ObjectHandler::getClassName($v5c2c9448bd); if(ObjectHandler::checkObjClass($v9367d5be85, $v6c8ff7a378) && ObjectHandler::checkIfObjType($v6c8ff7a378) && $this->getErrorHandler()->ok()) $pa2e93a9c = $v9367d5be85->getData(); } if (!$pe2bfadf3 || !count($pe2bfadf3)) launch_exception(new SQLMapQueryException(3)); elseif (!is_array($pa2e93a9c)) launch_exception(new SQLMapQueryException(7)); else { $pc37695cb = count($pe2bfadf3); for ($v9d27441e80 = 0; $v9d27441e80 < $pc37695cb; $v9d27441e80++) { $v5a38e25d0d = $pe2bfadf3[$v9d27441e80]; $v1c301b29c5 = isset($v5a38e25d0d["output_name"]) ? trim($v5a38e25d0d["output_name"]) : ""; if (strlen($v1c301b29c5) == 0) launch_exception(new SQLMapQueryException(2)); $v8dca298c48 = isset($v5a38e25d0d["input_name"]) ? trim($v5a38e25d0d["input_name"]) : ""; if (strlen($v8dca298c48) == 0) launch_exception(new SQLMapQueryException(1)); if (!isset($pa2e93a9c[$v8dca298c48])) { if (!empty($v5a38e25d0d["mandatory"]) && !$pf2c6b6ca) launch_exception(new SQLMapResultException(8, $v8dca298c48)); } else { if ($pf2c6b6ca && isset($pa2e93a9c[$v8dca298c48]) && is_array($pa2e93a9c[$v8dca298c48])) { $v0a9be8a39a = $pa2e93a9c[$v8dca298c48]; if ($v0a9be8a39a) { $pc37695cb = count($v0a9be8a39a); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v0a9be8a39a[$v43dd7d0051] = $this->f096b4bf4c7(array($v8dca298c48 => $v0a9be8a39a[$v43dd7d0051]), $v5a38e25d0d); } } else $v0a9be8a39a = $this->f096b4bf4c7($pa2e93a9c, $v5a38e25d0d); unset($pa2e93a9c[$v8dca298c48]); $pa2e93a9c[$v1c301b29c5] = $v0a9be8a39a; } } $v65a396e40d = $pa2e93a9c; } return $v65a396e40d; } private function f096b4bf4c7($v9367d5be85, $v5a38e25d0d) { $v8dca298c48 = isset($v5a38e25d0d["input_name"]) ? trim($v5a38e25d0d["input_name"]) : ""; $pb21e5126 = isset($v9367d5be85[$v8dca298c48]) ? $v9367d5be85[$v8dca298c48] : null; $v5a911d8233 = isset($v5a38e25d0d["input_type"]) ? $v5a38e25d0d["input_type"] : false; $v7d93eab82d = false; if ($v5a911d8233) { $v5a911d8233 = ObjTypeHandler::convertSimpleTypeIntoCompositeType($v5a911d8233); $v7d93eab82d = ObjectHandler::createInstance($v5a911d8233); if (ObjectHandler::checkIfObjType($v7d93eab82d) && $this->getErrorHandler()->ok()) { $v28c1cd997a = true; $pe49114c9 = ObjectHandler::getClassName($v5a911d8233); if(!$v7d93eab82d->is_primitive && ( !ObjectHandler::checkObjClass($pb21e5126, $pe49114c9) || !$this->getErrorHandler()->ok() ) ) { $v28c1cd997a = false; } if($v28c1cd997a) { $v7d93eab82d->setField(false); $v7d93eab82d->setInstance($pb21e5126); } } } $pd8947afe = $v7d93eab82d ? $v7d93eab82d->getData() : $pb21e5126; $v10353796a8 = isset($v5a38e25d0d["output_type"]) ? $v5a38e25d0d["output_type"] : false; $v5fbd97449e = false; if ($v10353796a8 && $this->getErrorHandler()->ok()) { $v10353796a8 = ObjTypeHandler::convertSimpleTypeIntoCompositeType($v10353796a8); $v5fbd97449e = ObjectHandler::createInstance($v10353796a8); if (ObjectHandler::checkIfObjType($v5fbd97449e) && $this->getErrorHandler()->ok()) { $v5fbd97449e->setField($v7d93eab82d); $v5fbd97449e->setInstance($pd8947afe); } } $v67db1bd535 = false; if ($this->getErrorHandler()->ok()) { if (!$v5fbd97449e && !is_numeric($v5fbd97449e)) $v67db1bd535 = $pd8947afe; elseif (isset($v5fbd97449e->is_primitive) && $v5fbd97449e->is_primitive) $v67db1bd535 = $v5fbd97449e->getData(); else $v67db1bd535 = $v5fbd97449e; } return $v67db1bd535; } } ?>
