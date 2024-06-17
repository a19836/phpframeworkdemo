<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class CacheLayerException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535) { switch($v6de691233b) { case 1: $this->problem = "Cache service '{$v67db1bd535}' needs to have the CACHE_HANDLER defined!"; break; case 2: $this->problem = "Cache service constructor doesn't exists: '{$v67db1bd535}'!"; break; case 3: $this->problem = "'$v67db1bd535' variable must be an array"; break; case 4: $this->problem = "'$v67db1bd535' variable cannot be empty"; break; } } } ?>
