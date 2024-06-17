<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.client.local.LocalBrokerClient"); include_once get_lib("org.phpframework.broker.client.IBusinessLogicBrokerClient"); class LocalBusinessLogicBrokerClient extends LocalBrokerClient implements IBusinessLogicBrokerClient { public function __construct() { parent::__construct(); } public function callBusinessLogic($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callBusinessLogic($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function getBrokersDBdriversName() { return $this->getBrokerServer()->getBrokersDBdriversName(); } } ?>
