<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 class PHP7Parser extends PhpParser\Parser\Php7 { public function __construct(PhpParser\Lexer $pbc3f0fa5, $v02a69d4e0f = null) { if (!$v02a69d4e0f && !is_array($v02a69d4e0f) && (PHPPARSER_EMULATIVE_VERSION == "52_71" || PHPPARSER_EMULATIVE_VERSION == "52_82")) $v02a69d4e0f = array(); parent::__construct($pbc3f0fa5, $v02a69d4e0f); } } ?>
