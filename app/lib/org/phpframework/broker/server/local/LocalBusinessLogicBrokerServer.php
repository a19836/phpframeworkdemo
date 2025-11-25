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
 include_once get_lib("org.phpframework.broker.server.local.LocalBrokerServer"); include_once get_lib("org.phpframework.broker.server.IBusinessLogicBrokerServer"); class LocalBusinessLogicBrokerServer extends LocalBrokerServer implements IBusinessLogicBrokerServer { public function callBusinessLogic($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callBusinessLogic($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function getBrokersDBDriversName() { return $this->Layer->getBrokersDBDriversName(); } } ?>
