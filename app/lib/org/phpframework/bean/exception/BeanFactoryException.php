<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class BeanFactoryException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535) { switch($v6de691233b) { case 1: $this->problem = "Bean number '{$v67db1bd535}' is invalid. The beans only can be IMPORT, BEAN or VAR type!"; break; case 2: $this->problem = "Bean '{$v67db1bd535}' does not exist!"; break; case 3: $this->problem = "Infinitive cicle creating bean '{$v67db1bd535}'!"; break; } } } ?>
