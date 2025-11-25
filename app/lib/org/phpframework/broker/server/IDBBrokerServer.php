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
 interface IDBBrokerServer { public function getDBDriversName(); public function getFunction($v9d33ecaf56, $v9367d5be85 = false, $v5d3813882f = false); public function getData($v3c76382d93, $v5d3813882f = false); public function setData($v3c76382d93, $v5d3813882f = false); public function getSQL($v3c76382d93, $v5d3813882f = false); public function setSQL($v3c76382d93, $v5d3813882f = false); public function getInsertedId($v5d3813882f = false); public function insertObject($v8c5df8072b, $pfdbbc383, $v5d3813882f = false); public function updateObject($v8c5df8072b, $pfdbbc383, $paf1bc6f6 = false, $v5d3813882f = false); public function deleteObject($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false); public function findObjects($v8c5df8072b, $pfdbbc383 = false, $paf1bc6f6 = false, $v5d3813882f = false); public function countObjects($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false); public function findRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false); public function countRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false); public function findObjectsColumnMax($v8c5df8072b, $v7162e23723, $v5d3813882f = false); } ?>
