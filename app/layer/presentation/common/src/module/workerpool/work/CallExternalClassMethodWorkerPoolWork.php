<?php
include_once get_lib("org.phpframework.phpscript.PHPCodePrintingHandler");
// Do not include the WorkerPoolWork or WorkerPoolUtil files here, because they are already included in the WorkerPoolHandler.php

class CallExternalClassMethodWorkerPoolWork extends WorkerPoolWork {
	
	protected function run() {
		if ($this->args) {
			$args = array_keys($this->args);
			
			$class_method_file = $args["class_method_file"];
			$class_name = $args["class_name"];
			$method_name = $args["method_name"];
			$method_args = $args["method_args"];
			
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
							debug_log("[CallExternalClassMethodWorkerPoolWork::run][" . $this->worker['thread_id'] . "] Executing class method '$cn::$mn' in file '$class_method_file'.", "info");
							
							if ($method_args)
								$res = call_user_func_array(array($cn, $mn), $method_args);
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
