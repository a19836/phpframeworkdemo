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
 include_once get_lib("org.phpframework.layer.presentation.cms.module.ICMSModuleHandler"); abstract class CMSModuleHandler implements ICMSModuleHandler { private $v08d9602741; private $pcd8c70bc; private $pfd76dfba; private $v8f96a41927 = false; public function setEVC($v08d9602741) { $this->v08d9602741 = $v08d9602741; } public function getEVC() { return $this->v08d9602741; } public function setModuleId($pcd8c70bc) { $this->pcd8c70bc = $pcd8c70bc; } public function getModuleId() { return $this->pcd8c70bc; } public function setCMSSettings($pfd76dfba) { $this->pfd76dfba = $pfd76dfba; } public function getCMSSettings() { return $this->pfd76dfba; } public function getCMSSetting($v5e813b295b) { return is_array($this->pfd76dfba) && isset($this->pfd76dfba[$v5e813b295b]) ? $this->pfd76dfba[$v5e813b295b] : null; } public function enable() { $this->v8f96a41927 = true; } public function disable() { $this->v8f96a41927 = false; } public function isEnabled() { return $this->v8f96a41927; } public static function getCMSModuleHandlerImplFilePath($v4a650a2b36) { return "$v4a650a2b36/CMSModuleHandlerImpl.php"; } } ?>
