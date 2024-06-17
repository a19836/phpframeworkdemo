<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class WorkFlowTaskException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "Error parsing xml for task with ID: '$v67db1bd535'!"; break; case 2: $this->problem = "Task with ID: '$v67db1bd535' does NOT exist!"; break; case 3: $this->problem = "Invalid Task Class for class: $v67db1bd535! All the Task classes must extend the WorkFlowTask class, which is not this case."; break; case 4: $this->problem = "Error trying to create class object for class: $v67db1bd535."; break; case 5: $this->problem = "Error \$task[OBJ] variable is not an object from the class: $v9363d877fd. Object " . get_class($pd0c2934c) . " is incorrect! Probably this task type does NOT exist!"; break; case 6: $this->problem = "Error Could Not clone $v9363d877fd obj class. Error \$task[OBJ] variable is not an object from the class: $v9363d877fd. Object " . get_class($pd0c2934c) . " is incorrect!"; break; case 7: $this->problem = "Workflow webroot folder path cannot be empty!"; break; case 8: $this->problem = "Error creating folder: '$v67db1bd535'!"; break; case 9: $this->problem = "Error copying file from: '$v9363d877fd' to '$pd0c2934c'!"; break; case 10: $this->problem = "Wrong namespace in file: '$v67db1bd535'!"; break; case 11: $this->problem = ""; break; case 12: $this->problem = "Class don't exist: '$v67db1bd535'!"; break; case 13: $this->problem = "Workflow webroot folder domain cannot be empty!"; break; case 14: $this->problem = "Error trying to get the URL prefix for the webroot of the task: '$v67db1bd535'!"; break; case 15: $this->problem = "Task path cannot be undefined for task: '$v67db1bd535'!"; break; } } } ?>
