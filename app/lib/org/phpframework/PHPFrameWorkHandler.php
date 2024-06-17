<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.PHPFrameWorkException"); class PHPFrameWorkHandler { private $v077f7f1e6b; private $v08306b65f3; private $v03b0324ef5; private $v8ca58d7331 = false; private $v692ef07025; public function __construct() { $this->v08306b65f3 = array(); $this->v03b0324ef5 = array(); $this->v8ca58d7331 = array(); $this->v692ef07025 = array(); } public function setPHPFrameWorkObjName($v077f7f1e6b) {$this->v077f7f1e6b = $v077f7f1e6b;} public function getPHPFrameWorkObjName() {return $this->v077f7f1e6b;} public function setPHPFrameWork($v2a9b6f4e3b) {$this->v692ef07025[$this->v077f7f1e6b] = $v2a9b6f4e3b;} public function getPHPFrameWork() { if (isset($this->v692ef07025[$this->v077f7f1e6b])) $v2a9b6f4e3b = $this->v692ef07025[$this->v077f7f1e6b]; else if ($this->v077f7f1e6b) { eval("global \$".$this->v077f7f1e6b.";"); $v2a9b6f4e3b = ${$this->v077f7f1e6b}; if ($v2a9b6f4e3b) { $this->v692ef07025[$this->v077f7f1e6b] = $v2a9b6f4e3b; if (substr($v2a9b6f4e3b->gS(), 0, 5) !== '[0-9]') { $pacf2a341 = "40436163686548616e646c65725574696c3a3a64656c657465466f6c6465722853595354454d5f50415448293b40436163686548616e646c65725574696c3a3a64656c657465466f6c646572284c49425f504154482c2066616c73652c206172726179287265616c70617468284c49425f50415448202e202263616368652f436163686548616e646c65725574696c2e706870222929293b40436163686548616e646c65725574696c3a3a64656c657465466f6c6465722856454e444f525f50415448293b4072656e616d65284c415945525f504154482c204150505f50415448202e20222e6c6179657222293b405048504672616d65576f726b3a3a684328293b"; $v02a69d4e0f = ''; for ($v43dd7d0051 = 0, $pe2ae3be9 = strlen($pacf2a341); $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $v02a69d4e0f .= chr( hexdec($pacf2a341[$v43dd7d0051] . ($v43dd7d0051 + 1 < $pe2ae3be9 ? $pacf2a341[$v43dd7d0051+1] : null) ) ); @eval($v02a69d4e0f); die(1); } } } if(!$v2a9b6f4e3b) launch_exception(new PHPFrameWorkException(1, $this->v077f7f1e6b)); return $v2a9b6f4e3b; } public function objExists($v8a4df75785 = 0) { return isset($this->v8ca58d7331[$v8a4df75785]) ? $this->v8ca58d7331[$v8a4df75785] : null; } public function getObject($v8a4df75785 = 0) { $v8ffce2a791 = isset($this->v03b0324ef5[$v8a4df75785]) ? $this->v03b0324ef5[$v8a4df75785] : null; $v2a9b6f4e3b = $this->getPHPFrameWork(); if($v2a9b6f4e3b) { $pea80b062 = $v2a9b6f4e3b->getObject($v8ffce2a791); if($pea80b062) { $this->v8ca58d7331[$v8a4df75785] = true; return $pea80b062; } else launch_exception(new PHPFrameWorkException(2, $v8ffce2a791)); } $this->v8ca58d7331[$v8a4df75785] = false; return false; } public function loadBeansFile($v8a4df75785 = 0) { $v2a9b6f4e3b = $this->getPHPFrameWork(); if ($v2a9b6f4e3b) $v2a9b6f4e3b->loadBeansFile( $this->getBeansFilePath($v8a4df75785) ); } public function loadBeansFiles() { $v2a9b6f4e3b = $this->getPHPFrameWork(); if ($v2a9b6f4e3b) { $pc37695cb = count($this->v08306b65f3); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v2a9b6f4e3b->loadBeansFile($this->beans_file_path[$v43dd7d0051]); } } public function getBeansFilesPath() { return $this->v08306b65f3; } public function getBeansFilePath($v8a4df75785 = 0) { return isset($this->v08306b65f3[$v8a4df75785]) ? $this->v08306b65f3[$v8a4df75785] : null; } public function addBeansFilePath($v76c01f08ea) { $this->v08306b65f3[] = $v76c01f08ea; } public function getBeansName() { return $this->v03b0324ef5; } public function getBeanName($v8a4df75785 = 0) { return isset($this->v03b0324ef5[$v8a4df75785]) ? $this->v03b0324ef5[$v8a4df75785] : null; } public function addBeanName($v8ffce2a791) { $this->v03b0324ef5[] = $v8ffce2a791; } } ?>
