<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.Broker"); include_once get_lib("org.phpframework.PHPFrameWorkHandler"); abstract class BrokerClient extends Broker { protected $PHPFrameWorkHandler; public function __construct() { parent::__construct(); $this->PHPFrameWorkHandler = new PHPFrameWorkHandler(); } public function setPHPFrameWorkObjName($v077f7f1e6b) {$this->PHPFrameWorkHandler->setPHPFrameWorkObjName($v077f7f1e6b);} public function addBeansFilePath($v76c01f08ea) {$this->PHPFrameWorkHandler->addBeansFilePath($v76c01f08ea);} public function getBeansFilesPath() {return $this->PHPFrameWorkHandler->getBeansFilesPath();} public function setBeanName($v8ffce2a791) {$this->PHPFrameWorkHandler->addBeanName($v8ffce2a791);} public function getBeanName() {return $this->PHPFrameWorkHandler->getBeanName();} } ?>
