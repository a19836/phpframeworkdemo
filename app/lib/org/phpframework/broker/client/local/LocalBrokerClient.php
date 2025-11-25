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
 include_once get_lib("org.phpframework.broker.BrokerClient"); include_once get_lib("org.phpframework.broker.server.local.LocalBrokerServer"); abstract class LocalBrokerClient extends BrokerClient { private $v50eed269ec; public function setBrokerServer(LocalBrokerServer $v50eed269ec) { $this->v50eed269ec = $v50eed269ec; } public function getBrokerServer() { if(!$this->v50eed269ec || !$this->PHPFrameWorkHandler->objExists()) { $this->PHPFrameWorkHandler->loadBeansFile(); $this->v50eed269ec = $this->PHPFrameWorkHandler->getObject(); } return $this->v50eed269ec; } } ?>
