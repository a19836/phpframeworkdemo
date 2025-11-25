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
 class PHPMultipleParser { private $v76c73f588e; public function __construct() { if (!defined("PHPPARSER_EMULATIVE_VERSION")) $this->v76c73f588e = null; else if (PHPPARSER_EMULATIVE_VERSION == "52_84") { $this->v76c73f588e = (new PhpParser\ParserFactory())->createForNewestSupportedVersion(); } else if (PHPPARSER_EMULATIVE_VERSION == "52_82" || PHPPARSER_EMULATIVE_VERSION == "52_71") { $pf1d2e1d9 = new PHP5Parser(new PHPParserLexerEmulative); $v3c085bda5f = new PHP7Parser(new PHPParserLexerEmulative); $this->v76c73f588e = new PhpParser\Parser\Multiple(array($pf1d2e1d9, $v3c085bda5f)); } elseif (PHPPARSER_EMULATIVE_VERSION == "52_56") { $this->v76c73f588e = new PHP4Parser(new PHPParserLexerEmulative); } else $this->v76c73f588e = null; } public function parse($v067674f4e4) { return $this->v76c73f588e ? $this->v76c73f588e->parse($v067674f4e4) : null; } } ?>
