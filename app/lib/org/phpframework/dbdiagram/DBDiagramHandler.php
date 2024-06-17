<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.xmlfile.XMLFileParser"); include_once get_lib("org.phpframework.dbdiagram.TableDiagram"); class DBDiagramHandler { public static function parseFile($pd404993b) { $pfb662071 = XMLFileParser::parseXMLFileToArray($pd404993b); $pac4bc40a = array(); if (!empty($pfb662071["tables"][0]["childs"]["table"]) && is_array($pfb662071["tables"][0]["childs"]["table"])) { foreach ($pfb662071["tables"][0]["childs"]["table"] as $v87a92bb1ad) { $pcadd9b50 = new TableDiagram(); $pcadd9b50->parse($v87a92bb1ad); if ($pcadd9b50->isValid()) { $pac4bc40a[] = $pcadd9b50; } else { launch_exception(new TableDiagramException(11, $pcadd9b50)); } } } $v3c76382d93 = ""; $v16ac35fd79 = count($pac4bc40a); for ($v43dd7d0051 = 0; $v43dd7d0051 < $v16ac35fd79; $v43dd7d0051++) { $pcadd9b50 = $pac4bc40a[$v43dd7d0051]; $v3c76382d93 .= $pcadd9b50->printSQL(); } return $v3c76382d93; } } ?>
