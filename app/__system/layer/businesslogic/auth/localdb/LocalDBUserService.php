<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace __system\businesslogic; include_once $vars["current_business_logic_module_path"] . "LocalDBAuthService.php"; class LocalDBUserService extends LocalDBAuthService { /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[new_encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function changeTableEncryptionKey($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->changeDBTableEncryptionKey("user", $data["new_encryption_key"]); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function dropAndCreateTable($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->writeTableItems("", "user"); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 * @param (name=data[password], type=varchar, not_null=1, min_length=1, max_length=50)
	 * @param (name=data[name], type=varchar, length=50)
	 */ public function insert($data) { $this->initLocalDBTableHandler($data); $this->md6938c867001($data); $data["created_date"] = !empty($data["created_date"]) ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = !empty($data["modified_date"]) ? $data["modified_date"] : $data["created_date"]; if (empty($data["user_id"])) $data["user_id"] = $this->LocalDBTableHandler->getPKMaxValue("user", "user_id") + 1; return $this->LocalDBTableHandler->insertItem("user", $data, array("user_id")) ? $data["user_id"] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 * @param (name=data[password], type=varchar, not_null=1, min_length=1, max_length=50)
	 * @param (name=data[name], type=varchar, length=50)
	 */ public function update($data) { $this->initLocalDBTableHandler($data); $this->md6938c867001($data); $data["modified_date"] = date("Y-m-d H:i:s"); return $this->LocalDBTableHandler->updateItem("user", $data, array("user_id")); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 */ public function delete($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->deleteItem("user", array("user_id" => $data["user_id"])); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 */ public function get($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->LocalDBTableHandler->getItems("user"); $v2f228af834 = $this->LocalDBTableHandler->filterItems($pf72c1d58, array("user_id" => $data["user_id"]), false, 1); return isset($v2f228af834[0]) ? $v2f228af834[0] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 * @param (name=data[password], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function getByUsernameAndPassword($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->LocalDBTableHandler->getItems("user"); $v2f228af834 = $this->LocalDBTableHandler->filterItems($pf72c1d58, array("username" => $data["username"], "password" => md5($data["password"])), false, 1); return isset($v2f228af834[0]) ? $v2f228af834[0] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function getAll($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->getItems("user"); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[conditions][user_id], type=bigint, length=19)
	 * @param (name=data[conditions][username], type=varchar, length=50)
	 * @param (name=data[conditions][password], type=varchar, length=50)
	 * @param (name=data[conditions][name], type=varchar, length=50)
	 */ public function search($data) { $this->initLocalDBTableHandler($data); $paf1bc6f6 = isset($data["conditions"]) ? $data["conditions"] : null; $this->md6938c867001($paf1bc6f6); $pf72c1d58 = $this->LocalDBTableHandler->getItems("user"); return $this->LocalDBTableHandler->filterItems($pf72c1d58, $paf1bc6f6, false); } private function md6938c867001(&$data) { if (isset($data["password"]) && empty($data["options"]["raw_password"])) $data["password"] = md5($data["password"]); unset($data["options"]); } } ?>
