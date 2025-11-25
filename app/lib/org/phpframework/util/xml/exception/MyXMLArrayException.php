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
 class MyXMLArrayException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535) { switch($v6de691233b) { case 1: $this->problem = "ERROR: Node conditions can only contain attribute or numeric indexes. Sub-nodes conditions are not supported! Please remove the '{$v67db1bd535}' sub-node condition please."; break; case 2: $this->problem = "ERROR: Node conditions contains a unknown character. Please check the '{$v67db1bd535}' character, please."; break; } } } ?>
