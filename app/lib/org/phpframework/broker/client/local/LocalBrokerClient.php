<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.BrokerClient"); include_once get_lib("org.phpframework.broker.server.local.LocalBrokerServer"); abstract class LocalBrokerClient extends BrokerClient { private $v50eed269ec; public function setBrokerServer(LocalBrokerServer $v50eed269ec) { $this->v50eed269ec = $v50eed269ec; } public function getBrokerServer() { if(!$this->v50eed269ec || !$this->PHPFrameWorkHandler->objExists()) { $this->PHPFrameWorkHandler->loadBeansFile(); $this->v50eed269ec = $this->PHPFrameWorkHandler->getObject(); } return $this->v50eed269ec; } } ?>
