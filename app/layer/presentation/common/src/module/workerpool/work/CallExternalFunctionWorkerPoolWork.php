<?php
// Do not include the WorkerPoolWork or WorkerPoolUtil files here, because they are already included in the WorkerPoolHandler.php

class CallExternalFunctionWorkerPoolWork extends WorkerPoolWork {
	
	protected function run() {
		if ($this->args) {
			$args = array_keys($this->args);
			
			$function_file = $args["function_file"];
			$function_name = $args["function_name"];
			$function_args = $args["function_args"];
			
			if ($function_file && $function_name) {
				$file_path = $function_file;
				
				if (substr($file_path, 0, 1) != "/")
					$file_path = get_lib($file_path);
				
				if (file_exists($file_path)) {
					include_once $file_path;
					
					if (function_exists($function_name)) {
						debug_log("[CallExternalFunctionWorkerPoolWork::run][" . $this->worker['thread_id'] . "] Executing function '$function_name' in file '$function_file'.", "info");
						
						if ($function_args)
							$res = call_user_func_array($function_name, $function_args);
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
