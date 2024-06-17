<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.layer.presentation.cms.cache.CMSModuleCacheLayer"); include_once get_lib("org.phpframework.layer.presentation.cms.cache.CMSBlockCacheLayer"); class MultipleCMSCacheLayer { private $pdd3510b1; private $v9857cd3214; public function __construct($v874d5d2d79, $v30857f7eca) { $this->pdd3510b1 = new CMSModuleCacheLayer($v874d5d2d79, $v30857f7eca); $this->v9857cd3214 = new CMSBlockCacheLayer($v874d5d2d79, $v30857f7eca); } public function setCMSModuleCacheLayer($pdd3510b1) {$this->pdd3510b1 = $pdd3510b1;} public function getCMSModuleCacheLayer() {return $this->pdd3510b1;} public function setCMSBlockCacheLayer($v9857cd3214) {$this->v9857cd3214 = $v9857cd3214;} public function getCMSBlockCacheLayer() {return $this->v9857cd3214;} } ?>
