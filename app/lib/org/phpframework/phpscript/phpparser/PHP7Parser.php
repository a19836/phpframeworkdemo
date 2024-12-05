<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class PHP7Parser extends PhpParser\Parser\Php7 { public function __construct(PhpParser\Lexer $pbc3f0fa5, $v02a69d4e0f = null) { if (!$v02a69d4e0f && !is_array($v02a69d4e0f) && (PHPPARSER_EMULATIVE_VERSION == "52_71" || PHPPARSER_EMULATIVE_VERSION == "52_82")) $v02a69d4e0f = array(); parent::__construct($pbc3f0fa5, $v02a69d4e0f); } } ?>
