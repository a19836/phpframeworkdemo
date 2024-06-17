<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
use PhpParser\Node; class PHPParserTraverserNodeVisitor extends PhpParser\NodeVisitorAbstract { public function leaveNode(Node $v6694236c2c) { $pcc2fe66c = $v6694236c2c->getAttribute("comments"); if ($pcc2fe66c) { $v6694236c2c->setAttribute("comments", array()); $v6694236c2c->setAttribute("my_comments", $pcc2fe66c); } } } ?>
