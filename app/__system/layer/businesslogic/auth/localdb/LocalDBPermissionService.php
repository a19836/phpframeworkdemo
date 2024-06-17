<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace __system\businesslogic; include_once $vars["current_business_logic_module_path"] . "LocalDBAuthService.php"; class LocalDBPermissionService extends LocalDBAuthService { /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[new_encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function changeTableEncryptionKey($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->changeDBTableEncryptionKey("permission", $data["new_encryption_key"]); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function dropAndCreateTable($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->writeTableItems("", "permission"); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function insert($data) { $this->initLocalDBTableHandler($data); $data["created_date"] = !empty($data["created_date"]) ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = !empty($data["modified_date"]) ? $data["modified_date"] : $data["created_date"]; if (empty($data["permission_id"])) { $data["permission_id"] = $this->LocalDBTableHandler->getPKMaxValue("permission", "permission_id") + 1; } return $this->LocalDBTableHandler->insertItem("permission", $data, array("permission_id")) ? $data["permission_id"] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function update($data) { $this->initLocalDBTableHandler($data); $data["modified_date"] = date("Y-m-d H:i:s"); return $this->LocalDBTableHandler->updateItem("permission", $data, array("permission_id")); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 */ public function delete($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->deleteItem("permission", array("permission_id" => $data["permission_id"])); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 */ public function get($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->LocalDBTableHandler->getItems("permission"); $v2f228af834 = $this->LocalDBTableHandler->filterItems($pf72c1d58, array("permission_id" => $data["permission_id"]), false, 1); return isset($v2f228af834[0]) ? $v2f228af834[0] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function getAll($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->getItems("permission"); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[conditions][permission_id], type=bigint, length=19)
	 * @param (name=data[conditions][name], type=varchar, length=50)
	 */ public function search($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->LocalDBTableHandler->getItems("permission"); $paf1bc6f6 = isset($data["conditions"]) ? $data["conditions"] : null; return $this->LocalDBTableHandler->filterItems($pf72c1d58, $paf1bc6f6, false); } } ?>
