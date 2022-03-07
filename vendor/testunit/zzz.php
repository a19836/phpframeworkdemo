<?php
include_once get_lib("org.phpframework.testunit.TestUnit");

class zzz extends TestUnit {
	
	
	/**
	 * som ecomennets a da aslçcmalç s
	 * asdasd
	 * asd
	 * asas dasdas
	 * 
	 * @enabled
	 * 
	 * @global_variables_files_path /asd/qwe 12/dd
	 * @global_variables_files_path 1/2/3
	 * 
	 * @depends (path=sub_folder/ddff) 
	 */
	public function execute () {
		//START: task[setvar][Set Var]
		$name = "Joao P. Pinto";
		
		//START: task[return][Return]
		return $name;
	}
}
?>
