<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace __system\businesslogic; include_once $vars["business_logic_modules_service_common_file_path"]; include_once get_lib("org.phpframework.localdb.LocalDBTableHandler"); class LocalDBAuthService extends CommonService { protected $LocalDBTableHandler; protected function initLocalDBTableHandler(&$data) { if (!$this->LocalDBTableHandler) { $v4ab372da3a = isset($data["root_path"]) ? $data["root_path"] : null; $pbdcbd484 = isset($data["encryption_key"]) ? $data["encryption_key"] : null; $this->LocalDBTableHandler = new \LocalDBTableHandler($v4ab372da3a, $pbdcbd484); } unset($data["root_path"]); unset($data["encryption_key"]); } } ?>
