<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.broker.client.rest.RESTBrokerClient"); include_once get_lib("org.phpframework.broker.client.IBusinessLogicBrokerClient"); class RESTBusinessLogicBrokerClient extends RESTBrokerClient implements IBusinessLogicBrokerClient { public function callBusinessLogic($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/$pc8b88eb4/$v95eeadc9e9"; return $this->requestResponse($v30857f7eca, array("parameters" => $v9367d5be85, "options" => $v5d3813882f)); } public function getBrokersDBdriversName() { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/getBrokersDBdriversName"; return $this->requestResponse($v30857f7eca); } } ?>
