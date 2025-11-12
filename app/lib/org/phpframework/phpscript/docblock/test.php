<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include get_lib("org.phpframework.phpscript.docblock.DocBlockParser"); foo(); $DocBlockParser = new DocBlockParser(); $DocBlockParser->ofFunction("bar"); $input = array( "id" => 1, "age" => "as", "full_name" => null, "options" => array( "no_cache" => null ), ); $output = "asd"; $status1 = $DocBlockParser->checkInputMethodAnnotations($input); $status2 = $DocBlockParser->checkOutputMethodAnnotations($output); echo "Status:$status1:$status2"; print_r($DocBlockParser->getObjects()); print_r($input); print_r($DocBlockParser->getTagParamsErrors()); print_r($DocBlockParser->getTagReturnErrors()); function foo() { function ma0144dcc62d2 ($v1cbfbb49c5, $v7591e93685, $v0c48c64def, $v5d3813882f = false) {} function mbde52cd6fb24 () {} function f7aeaf992f5 () {} function f9a8b7dc209 () {} function f5d3f7b52bb () {} } function get_lib($pa32be502) { $v333a329170 = dirname(dirname(dirname(dirname(__DIR__)))) . "/"; if (preg_match("/^(vendor|lib)\./i", $pa32be502)) $v333a329170 = dirname($v333a329170) . "/"; return $v333a329170 . str_replace(".", "/", $pa32be502) . ".php"; } function launch_exception($paec2c009) { throw $paec2c009; } function normalize_windows_path_to_linux($pa32be502) { return DIRECTORY_SEPARATOR != "/" ? str_replace(DIRECTORY_SEPARATOR, "/", $pa32be502) : $pa32be502; } ?>
