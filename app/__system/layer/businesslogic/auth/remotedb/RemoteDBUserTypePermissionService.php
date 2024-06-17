<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace __system\businesslogic; include_once $vars["business_logic_modules_service_common_file_path"]; include_once get_lib("org.phpframework.db.DB"); class RemoteDBUserTypePermissionService extends CommonService { public function dropAndCreateTable($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v87a92bb1ad = array( "table_name" => "sysauth_user_type_permission", "attributes" => array( array( "name" => "user_type_id", "type" => "bigint", "primary_key" => 1, ), array( "name" => "permission_id", "type" => "bigint", "primary_key" => 1, ), array( "name" => "object_type_id", "type" => "bigint", "primary_key" => 1, ), array( "name" => "object_id", "type" => "varchar", "length" => 255, "primary_key" => 1, ), array( "name" => "created_date", "type" => "timestamp", "null" => 1, ), array( "name" => "modified_date", "type" => "timestamp", "null" => 1, ), ) ); if (!isset($v5d3813882f["schema"])) $v5d3813882f["schema"] = $this->getBroker()->getFunction("getOption", "schema"); $pcc7f917f = $this->getBroker()->getFunction("getDropTableStatement", $v87a92bb1ad["table_name"], $v5d3813882f); $pd6148c78 = $this->getBroker()->getFunction("getCreateTableStatement", array($v87a92bb1ad), $v5d3813882f); $v5c1c342594 = $this->getBroker()->setData($pcc7f917f, $v5d3813882f) && $this->getBroker()->setData($pd6148c78, $v5d3813882f); $this->getBusinessLogicLayer()->callBusinessLogic("auth.remotedb", "RemoteDBReservedDBTableNameService.insertIfNotExistsYet", array("name" => $v87a92bb1ad["table_name"])); return $v5c1c342594; } /**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function insert($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $data["created_date"] = !empty($data["created_date"]) ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = !empty($data["modified_date"]) ? $data["modified_date"] : $data["created_date"]; return $this->getBroker()->callInsert("auth", "insert_user_type_permission", $data, $v5d3813882f); } /**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 */ public function updateByObjectsPermissions($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; if ($data["user_type_id"]) { $pca049f76 = array(); $pca049f76["conditions"] = array("user_type_id" => $data["user_type_id"]); $pca049f76["options"] = $v5d3813882f; $this->deleteByConditions($pca049f76); $v5c1c342594 = true; $v8f602836b9 = date("Y-m-d H:i:s"); if (isset($data["permissions_by_objects"]) && is_array($data["permissions_by_objects"])) foreach ($data["permissions_by_objects"] as $v0a035c60aa => $pdf191095) foreach ($pdf191095 as $v3fab52f440 => $pab9132c1) if ($pab9132c1) { $pc37695cb = count($pab9132c1); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pb76ee81a = $pab9132c1[$v43dd7d0051]; if ($pb76ee81a) { $v700315c4d3 = array( "user_type_id" => $data["user_type_id"], "permission_id" => $pb76ee81a, "object_type_id" => $v0a035c60aa, "object_id" => $v3fab52f440, "created_date" => $v8f602836b9, "modified_date" => $v8f602836b9, "options" => $v5d3813882f, ); if (!$this->insert($v700315c4d3)) { $v5c1c342594 = false; } } } } return $v5c1c342594; } return false; } /**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function delete($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; return $this->getBroker()->callDelete("auth", "delete_user_type_permission", array("user_type_id" => $data["user_type_id"], "permission_id" => $data["permission_id"], "object_type_id" => $data["object_type_id"], "object_id" => $data["object_id"]), $v5d3813882f); } /**
	 * @param (name=data[conditions][user_type_id], type=bigint, length=19)
	 * @param (name=data[conditions][permission_id], type=bigint, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint, length=19)
	 * @param (name=data[conditions][object_id], type=varchar, length=255)
	 */ public function deleteByConditions($data) { $paf1bc6f6 = isset($data["conditions"]) ? $data["conditions"] : null; $v7fd392376f = isset($data["conditions_join"]) ? $data["conditions_join"] : null; $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v9e394b2939 = \DB::getSQLConditions($paf1bc6f6, $v7fd392376f); return $this->getBroker()->callDelete("auth", "delete_user_type_permissions_by_conditions", array("conditions" => $v9e394b2939), $v5d3813882f); } /**
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function get($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v9ad1385268 = $this->getBroker()->callSelect("auth", "get_user_type_permission", array("user_type_id" => $data["user_type_id"], "permission_id" => $data["permission_id"], "object_type_id" => $data["object_type_id"], "object_id" => $data["object_id"]), $v5d3813882f); return $v9ad1385268[0]; } public function getAll($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; return $this->getBroker()->callSelect("auth", "get_all_user_type_permissions", null, $v5d3813882f); } /**
	 * @param (name=data[conditions][user_type_id], type=bigint, length=19)
	 * @param (name=data[conditions][permission_id], type=bigint, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint, length=19)
	 * @param (name=data[conditions][object_id], type=varchar, length=255)
	 */ public function search($data) { $paf1bc6f6 = isset($data["conditions"]) ? $data["conditions"] : null; $v7fd392376f = isset($data["conditions_join"]) ? $data["conditions_join"] : null; $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v9e394b2939 = \DB::getSQLConditions($paf1bc6f6, $v7fd392376f); return $v9e394b2939 ? $this->getBroker()->callSelect("auth", "get_user_type_permissions_by_conditions", array("conditions" => $v9e394b2939), $v5d3813882f) : null; } } ?>
