<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.server.local.LocalBrokerServer"); include_once get_lib("org.phpframework.broker.server.IBusinessLogicBrokerServer"); class LocalBusinessLogicBrokerServer extends LocalBrokerServer implements IBusinessLogicBrokerServer { public function callBusinessLogic($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callBusinessLogic($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function getBrokersDBDriversName() { return $this->Layer->getBrokersDBDriversName(); } } ?>
