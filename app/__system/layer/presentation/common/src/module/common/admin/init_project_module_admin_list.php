<?php
$current_page = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
$rows_per_page = 50;

$options = array(
	"start" => \PaginationHandler::getStartValue($current_page, $rows_per_page), 
	"limit" => $rows_per_page, 
	"sort" => null
);
?>
