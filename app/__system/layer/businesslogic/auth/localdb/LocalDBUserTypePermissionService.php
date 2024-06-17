<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace __system\businesslogic; include_once $vars["current_business_logic_module_path"] . "LocalDBAuthService.php"; class LocalDBUserTypePermissionService extends LocalDBAuthService { /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[new_encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function changeTableEncryptionKey($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->changeDBTableEncryptionKey("user_type_permission", $data["new_encryption_key"]); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function dropAndCreateTable($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->writeTableItems("", "user_type_permission"); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function insert($data) { $this->initLocalDBTableHandler($data); $data["created_date"] = !empty($data["created_date"]) ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = !empty($data["modified_date"]) ? $data["modified_date"] : $data["created_date"]; return $this->LocalDBTableHandler->insertItem("user_type_permission", $data, array("user_type_id", "permission_id", "object_type_id", "object_id")); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 */ public function updateByObjectsPermissions($data) { if ($data["user_type_id"]) { $pca049f76 = $data; $pca049f76["conditions"] = array("user_type_id" => $data["user_type_id"]); $this->deleteByConditions($pca049f76); $pf72c1d58 = $this->LocalDBTableHandler->getItems("user_type_permission"); $v8f602836b9 = date("Y-m-d H:i:s"); if (isset($data["permissions_by_objects"]) && is_array($data["permissions_by_objects"])) foreach ($data["permissions_by_objects"] as $v0a035c60aa => $pdf191095) foreach ($pdf191095 as $v3fab52f440 => $pab9132c1) if ($pab9132c1) { $pc37695cb = count($pab9132c1); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pb76ee81a = $pab9132c1[$v43dd7d0051]; if (is_numeric($pb76ee81a)) { $pf72c1d58[] = array( "user_type_id" => $data["user_type_id"], "permission_id" => $pb76ee81a, "object_type_id" => $v0a035c60aa, "object_id" => $v3fab52f440, "created_date" => $v8f602836b9, "modified_date" => $v8f602836b9, ); } } } return $this->LocalDBTableHandler->writeTableItems($pf72c1d58, "user_type_permission"); } return false; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function delete($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->deleteItem("user_type_permission", array("user_type_id" => $data["user_type_id"], "permission_id" => $data["permission_id"], "object_type_id" => $data["object_type_id"], "object_id" => $data["object_id"])); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[conditions][user_type_id], type=bigint, length=19)
	 * @param (name=data[conditions][permission_id], type=bigint, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint, length=19)
	 * @param (name=data[conditions][object_id], type=varchar, length=255)
	 */ public function deleteByConditions($data) { $this->initLocalDBTableHandler($data); $paf1bc6f6 = isset($data["conditions"]) ? $data["conditions"] : null; return $this->LocalDBTableHandler->deleteItem("user_type_permission", $paf1bc6f6); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function get($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->LocalDBTableHandler->getItems("user_type_permission"); $v2f228af834 = $this->LocalDBTableHandler->filterItems($pf72c1d58, array("user_type_id" => $data["user_type_id"], "permission_id" => $data["permission_id"], "object_type_id" => $data["object_type_id"], "object_id" => $data["object_id"]), false, 1); return isset($v2f228af834[0]) ? $v2f228af834[0] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function getAll($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->getItems("user_type_permission"); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[conditions][user_type_id], type=bigint, length=19)
	 * @param (name=data[conditions][permission_id], type=bigint, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint, length=19)
	 * @param (name=data[conditions][object_id], type=varchar, length=255)
	 */ public function search($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->LocalDBTableHandler->getItems("user_type_permission"); $paf1bc6f6 = isset($data["conditions"]) ? $data["conditions"] : null; return $this->LocalDBTableHandler->filterItems($pf72c1d58, $paf1bc6f6, false); } } ?>
