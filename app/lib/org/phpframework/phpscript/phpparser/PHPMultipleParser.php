<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class PHPMultipleParser { private $v76c73f588e; public function __construct() { if (!defined("PHPPARSER_EMULATIVE_VERSION")) $this->v76c73f588e = null; else if (PHPPARSER_EMULATIVE_VERSION == "52_84") { $this->v76c73f588e = (new PhpParser\ParserFactory())->createForNewestSupportedVersion(); } else if (PHPPARSER_EMULATIVE_VERSION == "52_82" || PHPPARSER_EMULATIVE_VERSION == "52_71") { $pf1d2e1d9 = new PHP5Parser(new PHPParserLexerEmulative); $v3c085bda5f = new PHP7Parser(new PHPParserLexerEmulative); $this->v76c73f588e = new PhpParser\Parser\Multiple(array($pf1d2e1d9, $v3c085bda5f)); } elseif (PHPPARSER_EMULATIVE_VERSION == "52_56") { $this->v76c73f588e = new PHP4Parser(new PHPParserLexerEmulative); } else $this->v76c73f588e = null; } public function parse($v067674f4e4) { return $this->v76c73f588e ? $this->v76c73f588e->parse($v067674f4e4) : null; } } ?>
