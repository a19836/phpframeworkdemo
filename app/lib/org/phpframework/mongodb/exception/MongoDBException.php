<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class MongoDBException extends Exception { public $problem; public function __construct($v6de691233b, $paec2c009, $v67db1bd535 = array()) { switch($v6de691233b) { case 1: $this->problem = "Mongo DB connection fail: connect(".implode(", ", $v67db1bd535).")"; break; case 2: $this->problem = "ERROR selecting Mongo DB: " . $v67db1bd535; break; case 3: $this->problem = "ERROR executing command on Mongo DB: " . $v67db1bd535; break; case 4: $this->problem = "ERROR inserting in Mongo DB: " . var_export($v67db1bd535, 1); break; case 5: $this->problem = "ERROR updating in Mongo DB: " . var_export($v67db1bd535, 1); break; case 6: $this->problem = "ERROR deleting in Mongo DB: " . var_export($v67db1bd535, 1); break; case 7: $this->problem = "ERROR executing query on Mongo DB: " . var_export($v67db1bd535, 1); break; } if (!empty($paec2c009)) { if (is_string($paec2c009)) parent::__construct($paec2c009, $v6de691233b, null); else parent::__construct(!empty($paec2c009->problem) ? $paec2c009->problem : $paec2c009->getMessage(), $v6de691233b, $paec2c009); } } } ?>
