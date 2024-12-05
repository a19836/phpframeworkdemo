<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class PHPParserNodeTraverser extends PhpParser\NodeTraverser { public function addNodeTraverserVisitor(PhpParser\NodeVisitor $v9dd4bc205e) { parent::addVisitor($v9dd4bc205e); } public function nodesTraverse(array $v50d32a6fc4) { return parent::traverse($v50d32a6fc4); } } ?>
