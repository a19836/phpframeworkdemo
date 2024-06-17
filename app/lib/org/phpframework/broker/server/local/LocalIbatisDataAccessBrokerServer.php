<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.server.local.LocalDataAccessBrokerServer"); include_once get_lib("org.phpframework.broker.server.IIbatisDataAccessBrokerServer"); class LocalIbatisDataAccessBrokerServer extends LocalDataAccessBrokerServer implements IIbatisDataAccessBrokerServer { public function callQuerySQL($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callQuerySQL($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callQuery($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callQuery($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callSelectSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callSelectSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callSelect($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callSelect($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callInsertSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callInsertSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callInsert($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callInsert($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callUpdateSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callUpdateSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callUpdate($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callUpdate($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callDeleteSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callDeleteSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callDelete($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callDelete($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callProcedureSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callProcedureSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callProcedure($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->Layer->callProcedure($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } } ?>
