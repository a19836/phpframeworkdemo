<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.client.rest.RESTBrokerClient"); include_once get_lib("org.phpframework.broker.client.IBusinessLogicBrokerClient"); class RESTBusinessLogicBrokerClient extends RESTBrokerClient implements IBusinessLogicBrokerClient { public function callBusinessLogic($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/$pc8b88eb4/$v95eeadc9e9"; return $this->requestResponse($v30857f7eca, array("parameters" => $v9367d5be85, "options" => $v5d3813882f)); } public function getBrokersDBdriversName() { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/getBrokersDBdriversName"; return $this->requestResponse($v30857f7eca); } } ?>
