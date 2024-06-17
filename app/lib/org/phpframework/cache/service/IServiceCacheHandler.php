<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
interface IServiceCacheHandler { public function create($pdcf670f6, $pbfa01ed1, $v9ad1385268, $v3fb9f41470 = false); public function addServiceToRelatedKeysToDelete($pdcf670f6, $pbfa01ed1, $pe7235a8d, $v3fb9f41470 = false); public function checkServiceToRelatedKeysToDelete($pdcf670f6, $pbfa01ed1, $pe7235a8d, $v3fb9f41470 = false); public function deleteAll($pdcf670f6, $v3fb9f41470 = false); public function delete($pdcf670f6, $pbfa01ed1, $v30857f7eca = array()); public function get($pdcf670f6, $pbfa01ed1, $v3fb9f41470 = false); public function isValid($pdcf670f6, $pbfa01ed1, $v492fce9a5d = false, $v3fb9f41470 = false); } ?>
