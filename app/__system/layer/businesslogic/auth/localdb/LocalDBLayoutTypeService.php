<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace __system\businesslogic; include_once $vars["current_business_logic_module_path"] . "LocalDBAuthService.php"; class LocalDBLayoutTypeService extends LocalDBAuthService { /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[new_encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function changeTableEncryptionKey($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->changeDBTableEncryptionKey("layout_type", $data["new_encryption_key"]); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function dropAndCreateTable($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->writeTableItems("", "layout_type"); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[type_id], type=tinyint, not_null=1, default=0, length=1)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function insert($data) { $this->initLocalDBTableHandler($data); $data["created_date"] = !empty($data["created_date"]) ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = !empty($data["modified_date"]) ? $data["modified_date"] : $data["created_date"]; if (empty($data["layout_type_id"])) $data["layout_type_id"] = $this->LocalDBTableHandler->getPKMaxValue("layout_type", "layout_type_id") + 1; return $this->LocalDBTableHandler->insertItem("layout_type", $data, array("layout_type_id")) ? $data["layout_type_id"] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[layout_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[type_id], type=tinyint, not_null=1, default=0, length=1)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function update($data) { $this->initLocalDBTableHandler($data); $data["modified_date"] = date("Y-m-d H:i:s"); return $this->LocalDBTableHandler->updateItem("layout_type", $data, array("layout_type_id")); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[old_name_prefix], type=varchar, not_null=1, min_length=1, max_length=255)
	 * @param (name=data[new_name_prefix], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function updateByNamePrefix($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->getAll(array("root_path" => $data["root_path"], "encryption_key" => $data["encryption_key"])); if ($pf72c1d58) { $v86bd693a16 = strlen($data["old_name_prefix"]); $pc37695cb = count($pf72c1d58); $v54817707c7 = false; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v342a134247 = $pf72c1d58[$v43dd7d0051]; $v5e813b295b = $v342a134247["name"]; if (substr($v5e813b295b, 0, $v86bd693a16) == $data["old_name_prefix"]) { $v342a134247["name"] = $data["new_name_prefix"] . substr($v5e813b295b, $v86bd693a16); $v342a134247["modified_date"] = date("Y-m-d H:i:s"); $pf72c1d58[$v43dd7d0051] = $v342a134247; $v54817707c7 = true; } } if ($v54817707c7) { return $this->LocalDBTableHandler->writeTableItems($pf72c1d58, "layout_type"); } } return true; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[layout_type_id], type=bigint, not_null=1, length=19)
	 */ public function delete($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->deleteItem("layout_type", array("layout_type_id" => $data["layout_type_id"])); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[layout_type_id], type=bigint, not_null=1, length=19)
	 */ public function get($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->LocalDBTableHandler->getItems("layout_type"); $v2f228af834 = $this->LocalDBTableHandler->filterItems($pf72c1d58, array("layout_type_id" => $data["layout_type_id"]), false, 1); return isset($v2f228af834[0]) ? $v2f228af834[0] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function getAll($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->getItems("layout_type"); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[conditions][layout_type_id], type=bigint, length=19)
	 * @param (name=data[conditions][type_id], type=tinyint, length=1)
	 * @param (name=data[conditions][name], type=varchar, length=255)
	 */ public function search($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->LocalDBTableHandler->getItems("layout_type"); $paf1bc6f6 = isset($data["conditions"]) ? $data["conditions"] : null; return $this->LocalDBTableHandler->filterItems($pf72c1d58, $paf1bc6f6, false); } } ?>
