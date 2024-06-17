<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace __system\businesslogic; include_once $vars["business_logic_modules_service_common_file_path"]; class RemoteDBLoginControlService extends CommonService { public function dropAndCreateTable($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v87a92bb1ad = array( "table_name" => "sysauth_login_control", "attributes" => array( array( "name" => "username", "type" => "varchar", "length" => 50, "primary_key" => 1, ), array( "name" => "session_id", "type" => "varchar", "length" => 200, ), array( "name" => "failed_login_attempts", "type" => "smallint", "default" => "0", ), array( "name" => "failed_login_time", "type" => "bigint", "default" => "0", ), array( "name" => "login_expired_time", "type" => "bigint", "default" => "0", ), array( "name" => "created_date", "type" => "timestamp", "null" => 1, ), array( "name" => "modified_date", "type" => "timestamp", "null" => 1, ), ) ); if (!isset($v5d3813882f["schema"])) $v5d3813882f["schema"] = $this->getBroker()->getFunction("getOption", "schema"); $pcc7f917f = $this->getBroker()->getFunction("getDropTableStatement", $v87a92bb1ad["table_name"], $v5d3813882f); $pd6148c78 = $this->getBroker()->getFunction("getCreateTableStatement", array($v87a92bb1ad), $v5d3813882f); $v5c1c342594 = $this->getBroker()->setData($pcc7f917f, $v5d3813882f) && $this->getBroker()->setData($pd6148c78, $v5d3813882f); $this->getBusinessLogicLayer()->callBusinessLogic("auth.remotedb", "RemoteDBReservedDBTableNameService.insertIfNotExistsYet", array("name" => $v87a92bb1ad["table_name"])); return $v5c1c342594; } /**
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function insert($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $pfdf1ef23 = $this->get($data); if (empty($data["login_expired_time"])) $data["login_expired_time"] = time() + 3600; if (empty($data["session_id"])) $data["session_id"] = \CryptoKeyHandler::getHexKey(); $data["username"] = addcslashes($data["username"], "\\'"); $data["failed_login_attempts"] = 0; $data["failed_login_time"] = 0; $data["created_date"] = !empty($data["created_date"]) ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = !empty($data["modified_date"]) ? $data["modified_date"] : $data["created_date"]; if ($pfdf1ef23) $v5c1c342594 = $this->getBroker()->callUpdate("auth", "update_login_control", $data, $v5d3813882f); else $v5c1c342594 = $this->getBroker()->callInsert("auth", "insert_login_control", $data, $v5d3813882f); return $v5c1c342594 ? $data["session_id"] : null; } /**
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function insertFailedLoginAttempt($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v36b8841602 = $this->getFailedLoginAttempts($data); $pfdf1ef23 = $this->get($data); $v8f602836b9 = date("Y-m-d H:i:s"); $v342a134247 = array( "username" => addcslashes($data["username"], "\\'"), "session_id" => $pfdf1ef23["session_id"], "failed_login_attempts" => $v36b8841602 + 1, "failed_login_time" => time(), "login_expired_time" => 0, "created_date" => !empty($pfdf1ef23["created_date"]) ? $pfdf1ef23["created_date"] : $v8f602836b9, "modified_date" => !empty($pfdf1ef23["modified_date"]) ? $pfdf1ef23["modified_date"] : $v8f602836b9, ); if ($pfdf1ef23) return $this->getBroker()->callUpdate("auth", "update_login_control", $v342a134247, $v5d3813882f); return $this->getBroker()->callInsert("auth", "insert_login_control", $v342a134247, $v5d3813882f); } /**
	 * @param (name=data[session_id], type=varchar, not_null=1, min_length=1, max_length=200)
	 */ public function expireSession($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v342a134247 = $this->getBySessionId($data); if ($v342a134247 && !empty($v342a134247["session_id"]) && !empty($v342a134247["username"])) { $v342a134247["login_expired_time"] = time() - 1; $v342a134247["modified_date"] = date("Y-m-d H:i:s"); return $this->getBroker()->callUpdate("auth", "update_login_control", $v342a134247, $v5d3813882f); } return false; } /**
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function resetFailedLoginAttempts($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v342a134247 = $this->get($data); if ($v342a134247) { $v342a134247["failed_login_attempts"] = 0; $v342a134247["failed_login_time"] = 0; $v342a134247["modified_date"] = date("Y-m-d H:i:s"); return $this->getBroker()->callUpdate("auth", "update_login_control", $v342a134247, $v5d3813882f); } return true; } /**
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function delete($data) { $pd97bc935 = addcslashes($data["username"], "\\'"); $v5d3813882f = isset($data["options"]) ? $data["options"] : null; return $this->getBroker()->callDelete("auth", "delete_login_control", array("username" => $pd97bc935), $v5d3813882f); } /**
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function getFailedLoginAttempts($data) { $v342a134247 = $this->get($data); return $v342a134247 && !empty($v342a134247["username"]) && isset($v342a134247["failed_login_attempts"]) ? $v342a134247["failed_login_attempts"] : null; } /**
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function get($data) { $pd97bc935 = addcslashes($data["username"], "\\'"); $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $pf72c1d58 = $this->getBroker()->callSelect("auth", "get_login_control", array("username" => $pd97bc935), $v5d3813882f); return isset($pf72c1d58[0]) ? $pf72c1d58[0] : null; } /**
	 * @param (name=data[session_id], type=varchar, not_null=1, min_length=1, max_length=200)
	 */ public function getBySessionId($data) { $pf503710f = addcslashes($data["session_id"], "\\'"); $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $pf72c1d58 = $this->getBroker()->callSelect("auth", "get_login_control_by_session_id", array("session_id" => $pf503710f), $v5d3813882f); return isset($pf72c1d58[0]) ? $pf72c1d58[0] : null; } /**
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function isUserBlocked($data) { $v342a134247 = $this->get($data); if (empty($data["maximum_failed_attempts"])) $data["maximum_failed_attempts"] = 3; if (empty($data["expired_time"])) $data["expired_time"] = 3600; $v310a246730 = isset($v342a134247["failed_login_attempts"]) ? $v342a134247["failed_login_attempts"] : null; $v238f974121 = isset($v342a134247["failed_login_time"]) ? $v342a134247["failed_login_time"] : null; return $v310a246730 > $data["maximum_failed_attempts"] && $v238f974121 + $data["expired_time"] >= time(); } public function getAll($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; return $this->getBroker()->callSelect("auth", "get_all_login_controls", null, $v5d3813882f); } } ?>
