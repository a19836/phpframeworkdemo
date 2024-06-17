<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.util.text.TextSanitizer"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $last_file_created_time = $_GET["file_created_time"]; $last_file_pointer = $_GET["file_pointer"]; $number_of_lines = $_GET["number_of_lines"]; $popup = $_GET["popup"]; $ajax = $_GET["ajax"]; $file_path = $GLOBALS["GlobalLogHandler"]->getFilePath(); if (file_exists($file_path)) { $file_created_time = filectime($file_path); $file_pointer = filesize($file_path); if ($last_file_pointer) { $output = $GLOBALS["GlobalLogHandler"]->tail(0, $last_file_pointer); } else { $number_of_lines = $number_of_lines > 0 ? $number_of_lines : 100; $output = $GLOBALS["GlobalLogHandler"]->tail($number_of_lines); } $output = TextSanitizer::convertBinaryCodeInTextToBase64($output); } if ($ajax) { $obj = array( "output" => $output, "file_created_time" => $file_created_time, "file_pointer" => $file_pointer ); echo json_encode($obj); die(); } ?>
