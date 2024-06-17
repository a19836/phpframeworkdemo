<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class MyXMLArrayException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535) { switch($v6de691233b) { case 1: $this->problem = "ERROR: Node conditions can only contain attribute or numeric indexes. Sub-nodes conditions are not supported! Please remove the '{$v67db1bd535}' sub-node condition please."; break; case 2: $this->problem = "ERROR: Node conditions contains a unknown character. Please check the '{$v67db1bd535}' character, please."; break; } } } ?>
