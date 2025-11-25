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
 class WorkFlowBrokersSelectedDBVarsHandler { public static function getBrokersSelectedDBVars($pc4223ce1) { $pbfd2d1c4 = array(); $pd7f46171 = null; $pc66a0204 = null; $v5a331eab7e = "db"; if ($pc4223ce1) foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) if (is_a($pd922c2f7, "IDataAccessBrokerClient") || is_a($pd922c2f7, "IDBBrokerClient")) { $pbfd2d1c4[$v2b2cf4c0eb] = is_a($pd922c2f7, "IDBBrokerClient") ? $pd922c2f7->getDBDriversName() : $pd922c2f7->getBrokersDBDriversName(); if (empty($pd7f46171)) { $pd7f46171 = $v2b2cf4c0eb; if (!empty($GLOBALS["default_db_driver"]) && in_array($GLOBALS["default_db_driver"], $pbfd2d1c4[$v2b2cf4c0eb])) $pc66a0204 = $GLOBALS["default_db_driver"]; else if (!$pc66a0204) $pc66a0204 = isset($pbfd2d1c4[$v2b2cf4c0eb][0]) ? $pbfd2d1c4[$v2b2cf4c0eb][0] : null; } } return array( "db_brokers_drivers" => $pbfd2d1c4, "dal_broker" => $pd7f46171, "db_driver" => $pc66a0204, "type" => $v5a331eab7e, ); } public static function printSelectedDBVarsJavascriptCode($peb014cfd, $v8ffce2a791, $pa0462a8e, $pe44aa1fe) { $v067674f4e4 = ''; if ($peb014cfd && $v8ffce2a791 && $pa0462a8e) $v067674f4e4 .= 'var get_broker_db_data_url = typeof get_broker_db_data_url != "undefined" && get_broker_db_data_url ? get_broker_db_data_url : "' . $peb014cfd . 'phpframework/dataaccess/get_broker_db_data?bean_name=' . $v8ffce2a791 . '&bean_file_name=' . $pa0462a8e . '";'; if ($pe44aa1fe) { if (array_key_exists("dal_broker", $pe44aa1fe)) $v067674f4e4 .= 'var default_dal_broker = "' . (isset($pe44aa1fe["dal_broker"]) ? $pe44aa1fe["dal_broker"] : "") . '";'; if (array_key_exists("db_driver", $pe44aa1fe)) $v067674f4e4 .= 'var default_db_driver = "' . (isset($pe44aa1fe["db_driver"]) ? $pe44aa1fe["db_driver"] : "") . '";'; if (array_key_exists("type", $pe44aa1fe)) $v067674f4e4 .= 'var default_db_type = "' . (isset($pe44aa1fe["type"]) ? $pe44aa1fe["type"] : "") . '";'; if (array_key_exists("db_table", $pe44aa1fe)) $v067674f4e4 .= 'var default_db_table = "' . (isset($pe44aa1fe["db_table"]) ? $pe44aa1fe["db_table"] : "") . '";'; if (!empty($pe44aa1fe["db_brokers_drivers"])) { $v067674f4e4 .= '
				if (typeof db_brokers_drivers_tables_attributes == "undefined") {
					var db_brokers_drivers_tables_attributes = {};'; foreach ($pe44aa1fe["db_brokers_drivers"] as $pab752e34 => $v84bde5f80a) { $v067674f4e4 .= 'db_brokers_drivers_tables_attributes["' . $pab752e34 . '"] = {};'; if ($v84bde5f80a) { $pc37695cb = count($v84bde5f80a); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v067674f4e4 .= 'db_brokers_drivers_tables_attributes["' . $pab752e34 . '"]["' . $v84bde5f80a[$v43dd7d0051] . '"] = {
								db: {},
								diagram: {}
							};'; } } $v067674f4e4 .= '}'; } return $v067674f4e4; } return ""; } } ?>
