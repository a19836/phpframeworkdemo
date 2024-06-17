<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
interface IDBBrokerServer { public function getDBDriversName(); public function getFunction($v9d33ecaf56, $v9367d5be85 = false, $v5d3813882f = false); public function getData($v3c76382d93, $v5d3813882f = false); public function setData($v3c76382d93, $v5d3813882f = false); public function getSQL($v3c76382d93, $v5d3813882f = false); public function setSQL($v3c76382d93, $v5d3813882f = false); public function getInsertedId($v5d3813882f = false); public function insertObject($v8c5df8072b, $pfdbbc383, $v5d3813882f = false); public function updateObject($v8c5df8072b, $pfdbbc383, $paf1bc6f6 = false, $v5d3813882f = false); public function deleteObject($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false); public function findObjects($v8c5df8072b, $pfdbbc383 = false, $paf1bc6f6 = false, $v5d3813882f = false); public function countObjects($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false); public function findRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false); public function countRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false); public function findObjectsColumnMax($v8c5df8072b, $v7162e23723, $v5d3813882f = false); } ?>
