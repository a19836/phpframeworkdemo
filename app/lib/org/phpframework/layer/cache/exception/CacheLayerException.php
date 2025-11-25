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
 class CacheLayerException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535) { switch($v6de691233b) { case 1: $this->problem = "Cache service '{$v67db1bd535}' needs to have the CACHE_HANDLER defined!"; break; case 2: $this->problem = "Cache service constructor doesn't exists: '{$v67db1bd535}'!"; break; case 3: $this->problem = "'$v67db1bd535' variable must be an array"; break; case 4: $this->problem = "'$v67db1bd535' variable cannot be empty"; break; } } } ?>
