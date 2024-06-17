<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
interface IIbatisDataAccessBrokerServer { public function callQuerySQL($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false); public function callQuery($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callSelectSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callSelect($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callInsertSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callInsert($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callUpdateSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callUpdate($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callDeleteSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callDelete($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callProcedureSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callProcedure($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); } ?>
