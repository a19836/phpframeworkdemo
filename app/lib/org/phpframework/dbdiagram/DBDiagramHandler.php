<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.xmlfile.XMLFileParser"); include_once get_lib("org.phpframework.dbdiagram.TableDiagram"); class DBDiagramHandler { public static function parseFile($pd404993b) { $pfb662071 = XMLFileParser::parseXMLFileToArray($pd404993b); $pac4bc40a = array(); if (!empty($pfb662071["tables"][0]["childs"]["table"]) && is_array($pfb662071["tables"][0]["childs"]["table"])) { foreach ($pfb662071["tables"][0]["childs"]["table"] as $v87a92bb1ad) { $pcadd9b50 = new TableDiagram(); $pcadd9b50->parse($v87a92bb1ad); if ($pcadd9b50->isValid()) { $pac4bc40a[] = $pcadd9b50; } else { launch_exception(new TableDiagramException(11, $pcadd9b50)); } } } $v3c76382d93 = ""; $v16ac35fd79 = count($pac4bc40a); for ($v43dd7d0051 = 0; $v43dd7d0051 < $v16ac35fd79; $v43dd7d0051++) { $pcadd9b50 = $pac4bc40a[$v43dd7d0051]; $v3c76382d93 .= $pcadd9b50->printSQL(); } return $v3c76382d93; } } ?>
