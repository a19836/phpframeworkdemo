<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 interface IServiceCacheHandler { public function create($pdcf670f6, $pbfa01ed1, $v9ad1385268, $v3fb9f41470 = false); public function addServiceToRelatedKeysToDelete($pdcf670f6, $pbfa01ed1, $pe7235a8d, $v3fb9f41470 = false); public function checkServiceToRelatedKeysToDelete($pdcf670f6, $pbfa01ed1, $pe7235a8d, $v3fb9f41470 = false); public function deleteAll($pdcf670f6, $v3fb9f41470 = false); public function delete($pdcf670f6, $pbfa01ed1, $v30857f7eca = array()); public function get($pdcf670f6, $pbfa01ed1, $v3fb9f41470 = false); public function isValid($pdcf670f6, $pbfa01ed1, $v492fce9a5d = false, $v3fb9f41470 = false); } ?>
