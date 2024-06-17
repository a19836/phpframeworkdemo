<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include get_lib("org.phpframework.bean.exception.BeanArgumentException"); class BeanArgument { const AK = "x1qMj9Bx3BxeefcvT4/FtHadhrNOM/J31akctINwSmsLWcn6YJ7g7fJrPjkwZtRO"; public $index; public $value = false; public $reference = false; public function __construct($v8a4df75785, $v67db1bd535 = false, $v6da63250f5 = false) { $this->index = $v8a4df75785; $this->value = $v67db1bd535; $this->reference = $v6da63250f5; $this->f085037e150(); } private function f085037e150() { if(!is_numeric($this->index)) { launch_exception(new BeanArgumentException(1, $this->index)); return false; } elseif($this->index <= 0) { launch_exception(new BeanArgumentException(2, $this->index)); return false; } elseif($this->value && $this->reference) { launch_exception(new BeanArgumentException(3, array($this->value, $this->reference))); return false; } return true; } } ?>
