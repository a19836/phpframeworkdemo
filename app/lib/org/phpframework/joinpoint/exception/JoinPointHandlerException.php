<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class JoinPointHandlerException extends Exception { public $problem; public function __construct($v6de691233b, $paec2c009, $v67db1bd535 = array()) { switch($v6de691233b) { case 1: $this->problem = "Error trying to execute code: $v67db1bd535!"; break; case 2: $this->problem = "Error trying to include join point method file: '$v67db1bd535'!"; break; } if (!empty($paec2c009)) { if (is_string($paec2c009)) { parent::__construct($paec2c009, $v6de691233b, null); } else { parent::__construct($paec2c009->problem ? $paec2c009->problem : $paec2c009->getMessage(), $v6de691233b, $paec2c009); } } } } ?>
