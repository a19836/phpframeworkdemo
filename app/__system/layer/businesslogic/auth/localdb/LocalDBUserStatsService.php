<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace __system\businesslogic; include_once $vars["current_business_logic_module_path"] . "LocalDBAuthService.php"; class LocalDBUserStatsService extends LocalDBAuthService { /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[new_encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function changeTableEncryptionKey($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->changeDBTableEncryptionKey("user_stats", $data["new_encryption_key"]); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function dropAndCreateTable($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->writeTableItems("", "user_stats"); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=100)
	 */ public function insert($data) { $this->initLocalDBTableHandler($data); $pfdf1ef23 = $this->get($data); $data["created_date"] = !empty($data["created_date"]) ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = !empty($data["modified_date"]) ? $data["modified_date"] : $data["created_date"]; if ($pfdf1ef23) { $data["created_date"] = $pfdf1ef23["created_date"]; $v5c1c342594 = $this->LocalDBTableHandler->updateItem("user_stats", $data, array("name")); } else $v5c1c342594 = $this->LocalDBTableHandler->insertItem("user_stats", $data, array("name")); return $v5c1c342594 ? $data["name"] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=100)
	 */ public function get($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->LocalDBTableHandler->getItems("user_stats"); $v2f228af834 = $this->LocalDBTableHandler->filterItems($pf72c1d58, array("name" => $data["name"]), false, 1); return isset($v2f228af834[0]) ? $v2f228af834[0] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function getAll($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->getItems("user_stats"); } } ?>
