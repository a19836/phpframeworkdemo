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

// Do not include the WorkerPoolWork or WorkerPoolUtil files here, because they are already included in the WorkerPoolHandler.php

class CallExternalFunctionWorkerPoolWork extends WorkerPoolWork {
	
	protected function run() {
		if ($this->args) {
			$args = array_keys($this->args);
			
			$function_file = isset($args["function_file"]) ? $args["function_file"] : null;
			$function_name = isset($args["function_name"]) ? $args["function_name"] : null;
			$function_args = isset($args["function_args"]) ? $args["function_args"] : null;
			
			if ($function_file && $function_name) {
				$file_path = $function_file;
				
				if (substr($file_path, 0, 1) != "/")
					$file_path = get_lib($file_path);
				
				if (file_exists($file_path)) {
					include_once $file_path;
					
					if (function_exists($function_name)) {
						debug_log("[CallExternalFunctionWorkerPoolWork::run][" . (isset($this->worker['thread_id']) ? $this->worker['thread_id'] : null) . "] Executing function '$function_name' in file '$function_file'.", "info");
						
						if ($function_args){
							$function_args = is_array($function_args) ? array_values($function_args) : $function_args;
							$res = @call_user_func_array($function_name, $function_args); //Note that the @ is very important here bc in PHP 8 this gives an warning, this is: 'Warning: Array to string conversion in...'
						}
						else
							$res = call_user_func($function_name);
						
						return $res;
					}
					else
						throw new Exception("Function '$function_name' does NOT exists in file '$function_file'!");
				}
				else
					throw new Exception("File '$function_file' does NOT exists!");
			}
		}
	}
}
?>
