<?php
namespace WorkFlowTask\test\sub_test\usertask3;

include_once get_lib("org.phpframework.workflow.WorkFlowTask");

class WorkFlowTaskImpl extends \WorkFlowTask {
	
	protected $is_break_task = true;
	
	public function __construct() {
		$this->setPriority(1);
	}
	
	public function createTaskPropertiesFromCodeStmt($stmt, $WorkFlowTaskCodeParser, &$exits = null, &$inner_tasks = null) {
		$stmt_type = strtolower($stmt->getType());
		
		if ($stmt_type == "stmt_break") {
			$value = $stmt->num && $stmt->num->value ? $stmt->num->value : "";
			
			$props = array(
				"value" => $value,
				"exits" => array(
					self::DEFAULT_EXIT_ID => array(
						"color" => "#7eacda",
					),
				),
			);
			
			return $props;
		}
	}
	
	public function parseProperties(&$task) {
		$raw_data = $task["raw_data"];
		
		$properties = array(
			"value" => $raw_data["childs"]["properties"][0]["childs"]["value"][0]["value"],
		);
		
		return $properties;
	}
	
	public function printCode($tasks, $stop_task_id, $prefix_tab = "", $options = null) {
		$data = $this->data;
		
		$properties = $data["properties"];
		$value = is_numeric($properties["value"]) ? " " . $properties["value"] : "";
		
		$code .= $prefix_tab . "break$value \"bla\";\n";
		
		return $code; //break does not write the code after it-self. There are no tasks after!
	}
}
?>
