<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

include_once get_lib("org.phpframework.phpscript.PHPCodePrintingHandler");
// Do not include the WorkerPoolWork or WorkerPoolUtil files here, because they are already included in the WorkerPoolHandler.php

class CallExternalClassMethodWorkerPoolWork extends WorkerPoolWork {
	
	protected function run() {
		if ($this->args) {
			$args = array_keys($this->args);
			
			$class_method_file = isset($args["class_method_file"]) ? $args["class_method_file"] : null;
			$class_name = isset($args["class_name"]) ? $args["class_name"] : null;
			$method_name = isset($args["method_name"]) ? $args["method_name"] : null;
			$method_args = isset($args["method_args"]) ? $args["method_args"] : null;
			
			if ($class_method_file && $class_name && $method_name) {
				$file_path = $class_method_file;
				
				if (substr($file_path, 0, 1) != "/")
					$file_path = get_lib($file_path);
				
				if (file_exists($file_path)) {
					include_once $file_path;
					
					$cn = \PHPCodePrintingHandler::getClassPathFromClassName($file_path, $class_name);
					
					if ($cn && class_exists($cn)) {
						$reflect = new \ReflectionClass($cn);
						$publics = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
						$mn = null;
						$is_static = false;
						
						foreach ($publics as $prop)
							if (strtolower($prop->getName()) == strtolower($method_name)) {
								$mn = $prop->getName();
								$is_static = $prop->isStatic();
								break;
							}
						
						if (!$mn)
							throw new Exception("Method '$method_name' does NOT exists for class '$class_name' in file '$class_method_file'!");
						else if (!$is_static)
							throw new Exception("Method '$method_name' does NOT exists for class '$class_name' in file '$class_method_file'!");
						else if (method_exists($cn, $mn)) {
							debug_log("[CallExternalClassMethodWorkerPoolWork::run][" . (isset($this->worker['thread_id']) ? $this->worker['thread_id'] : null) . "] Executing class method '$cn::$mn' in file '$class_method_file'.", "info");
							
							if ($method_args) {
								$method_args = is_array($method_args) ? array_values($method_args) : $method_args;
								$res = @call_user_func_array(array($cn, $mn), $method_args); //Note that the @ is very important here bc in PHP 8 this gives an warning, this is: 'Warning: Array to string conversion in...'
							}
							else
								$res = call_user_func(array($cn, $mn));
							
							return $res;
						}
						else
							throw new Exception("Method '$method_name' does NOT exists for class '$class_name' in file '$class_method_file'!");
					}
					else
						throw new Exception("Class '$class_name' does NOT exists in file '$class_method_file'!");
				}
				else
					throw new Exception("File '$class_method_file' does NOT exists!");
			}
		}
	}
}
?>
