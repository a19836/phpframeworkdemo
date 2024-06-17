<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class ObjTypeException extends Exception { public $problem; public function __construct($v1335217393, $v67db1bd535) { $v67db1bd535 = is_object($v67db1bd535) ? get_class($v67db1bd535) : $v67db1bd535; $this->problem = "Wrong {$v1335217393} value: '{$v67db1bd535}' "; } } ?>
