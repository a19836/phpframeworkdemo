<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.client.local.LocalDataAccessBrokerClient"); include_once get_lib("org.phpframework.broker.client.IIbatisDataAccessBrokerClient"); class LocalIbatisDataAccessBrokerClient extends LocalDataAccessBrokerClient implements IIbatisDataAccessBrokerClient { public function callQuerySQL($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callQuerySQL($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callQuery($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callQuery($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callSelectSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callSelectSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callSelect($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callSelect($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callInsertSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callInsertSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callInsert($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callInsert($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callUpdateSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callUpdateSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callUpdate($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callUpdate($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callDeleteSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callDeleteSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callDelete($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callDelete($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callProcedureSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callProcedureSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callProcedure($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callProcedure($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } } ?>
