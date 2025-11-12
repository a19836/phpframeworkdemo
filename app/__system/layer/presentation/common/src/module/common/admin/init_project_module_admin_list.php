<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$current_page = isset($_GET["current_page"]) && is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
$rows_per_page = 50;

$options = array(
	"start" => \PaginationHandler::getStartValue($current_page, $rows_per_page), 
	"limit" => $rows_per_page, 
	"sort" => null
);
?>
