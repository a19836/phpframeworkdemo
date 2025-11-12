<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 interface IIbatisDataAccessBrokerServer { public function callQuerySQL($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false); public function callQuery($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callSelectSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callSelect($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callInsertSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callInsert($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callUpdateSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callUpdate($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callDeleteSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callDelete($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callProcedureSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callProcedure($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); } ?>
