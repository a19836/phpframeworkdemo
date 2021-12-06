<?php
if (!class_exists("CommonSettings")) {
	abstract class CommonSettings {
	
		public static function getConstantVariable($const_name) {
			#echo("return isset(\$GLOBALS['$const_name']) ? \$GLOBALS['$const_name'] : self::$const_name;\n");
			return eval("return isset(\$GLOBALS['$const_name']) ? \$GLOBALS['$const_name'] : static::$const_name;");
		}
	}
}
?>
