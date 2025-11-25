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
 include_once get_lib("org.phpframework.broker.Broker"); include_once get_lib("org.phpframework.PHPFrameWorkHandler"); abstract class BrokerClient extends Broker { protected $PHPFrameWorkHandler; public function __construct() { parent::__construct(); $this->PHPFrameWorkHandler = new PHPFrameWorkHandler(); } public function setPHPFrameWorkObjName($v077f7f1e6b) {$this->PHPFrameWorkHandler->setPHPFrameWorkObjName($v077f7f1e6b);} public function addBeansFilePath($v76c01f08ea) {$this->PHPFrameWorkHandler->addBeansFilePath($v76c01f08ea);} public function getBeansFilesPath() {return $this->PHPFrameWorkHandler->getBeansFilesPath();} public function setBeanName($v8ffce2a791) {$this->PHPFrameWorkHandler->addBeanName($v8ffce2a791);} public function getBeanName() {return $this->PHPFrameWorkHandler->getBeanName();} } ?>
