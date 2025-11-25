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
 include_once get_lib("org.phpframework.broker.server.rest.RESTBrokerServer"); include_once get_lib("org.phpframework.broker.server.local.LocalBusinessLogicBrokerServer"); class RESTBusinessLogicBrokerServer extends RESTBrokerServer { protected function setLocalBrokerServer() { $this->LocalBrokerServer = new LocalBusinessLogicBrokerServer($this->Layer); } protected function executeWebServiceResponse() { $v9cd205cadb = explode("/", $this->url); if (strtolower($v9cd205cadb[0]) == "getbrokersdbdriversname") { $v9ad1385268 = $this->LocalBrokerServer->getBrokersDBdriversName(); return $this->getWebServiceResponse("getBrokersDBdriversName", null, $v9ad1385268, $this->response_type); } else { $v95eeadc9e9 = array_pop($v9cd205cadb); $pc8b88eb4 = implode("/", $v9cd205cadb); $v9ad1385268 = $this->LocalBrokerServer->callBusinessLogic($pc8b88eb4, $v95eeadc9e9, $this->parameters, $this->options); $pc0481df4 = array("module" => $pc8b88eb4, "service" => $v95eeadc9e9, "parameters" => $this->parameters, "options" => $this->options); return $this->getWebServiceResponse("callBusinessLogic", $pc0481df4, $v9ad1385268, $this->response_type); } } } ?>
