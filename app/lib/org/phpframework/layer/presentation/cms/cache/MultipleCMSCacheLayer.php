<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.layer.presentation.cms.cache.CMSModuleCacheLayer"); include_once get_lib("org.phpframework.layer.presentation.cms.cache.CMSBlockCacheLayer"); class MultipleCMSCacheLayer { private $pdd3510b1; private $v9857cd3214; public function __construct($v874d5d2d79, $v30857f7eca) { $this->pdd3510b1 = new CMSModuleCacheLayer($v874d5d2d79, $v30857f7eca); $this->v9857cd3214 = new CMSBlockCacheLayer($v874d5d2d79, $v30857f7eca); } public function setCMSModuleCacheLayer($pdd3510b1) {$this->pdd3510b1 = $pdd3510b1;} public function getCMSModuleCacheLayer() {return $this->pdd3510b1;} public function setCMSBlockCacheLayer($v9857cd3214) {$this->v9857cd3214 = $v9857cd3214;} public function getCMSBlockCacheLayer() {return $this->v9857cd3214;} } ?>
