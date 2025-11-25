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
 include get_lib("org.phpframework.bean.exception.BeanArgumentException"); class BeanArgument { const AK = "x1qMj9Bx3BxeefcvT4/FtHadhrNOM/J31akctINwSmsLWcn6YJ7g7fJrPjkwZtRO"; public $index; public $value = false; public $reference = false; public function __construct($v8a4df75785, $v67db1bd535 = false, $v6da63250f5 = false) { $this->index = $v8a4df75785; $this->value = $v67db1bd535; $this->reference = $v6da63250f5; $this->f085037e150(); } private function f085037e150() { if(!is_numeric($this->index)) { launch_exception(new BeanArgumentException(1, $this->index)); return false; } elseif($this->index <= 0) { launch_exception(new BeanArgumentException(2, $this->index)); return false; } elseif($this->value && $this->reference) { launch_exception(new BeanArgumentException(3, array($this->value, $this->reference))); return false; } return true; } } ?>
