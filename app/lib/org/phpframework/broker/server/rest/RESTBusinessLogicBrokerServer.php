<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.server.rest.RESTBrokerServer"); include_once get_lib("org.phpframework.broker.server.local.LocalBusinessLogicBrokerServer"); class RESTBusinessLogicBrokerServer extends RESTBrokerServer { protected function setLocalBrokerServer() { $this->LocalBrokerServer = new LocalBusinessLogicBrokerServer($this->Layer); } protected function executeWebServiceResponse() { $v9cd205cadb = explode("/", $this->url); if (strtolower($v9cd205cadb[0]) == "getbrokersdbdriversname") { $v9ad1385268 = $this->LocalBrokerServer->getBrokersDBdriversName(); return $this->getWebServiceResponse("getBrokersDBdriversName", null, $v9ad1385268, $this->response_type); } else { $v95eeadc9e9 = array_pop($v9cd205cadb); $pc8b88eb4 = implode("/", $v9cd205cadb); $v9ad1385268 = $this->LocalBrokerServer->callBusinessLogic($pc8b88eb4, $v95eeadc9e9, $this->parameters, $this->options); $pc0481df4 = array("module" => $pc8b88eb4, "service" => $v95eeadc9e9, "parameters" => $this->parameters, "options" => $this->options); return $this->getWebServiceResponse("callBusinessLogic", $pc0481df4, $v9ad1385268, $this->response_type); } } } ?>
