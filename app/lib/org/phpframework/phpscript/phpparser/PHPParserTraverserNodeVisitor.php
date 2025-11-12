<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 use PhpParser\Node; class PHPParserTraverserNodeVisitor extends PHPParserNodeVisitorAbstract { public function leaveNode(Node $v6694236c2c) { $pcc2fe66c = $v6694236c2c->getAttribute("comments"); if ($pcc2fe66c) { $v6694236c2c->setAttribute("comments", array()); $v6694236c2c->setAttribute("my_comments", $pcc2fe66c); } return null; } } ?>
