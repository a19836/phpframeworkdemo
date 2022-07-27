<?php
/*
 * Copyright (c) 2007 PHPMyFrameWork - Joao Pinto
 * AUTHOR: Joao Paulo Lopes Pinto -- http://jplpinto.com
 * 
 * The use of this code must be allowed first by the creator Joao Pinto, since this is a private and proprietary code.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS 
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY 
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR 
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL 
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER 
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT 
 * OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. IN NO EVENT SHALL 
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN 
 * AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE 
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

trait MySqlDBStatement { public static function getCreateDBStatement($pb67a2609, $v5d3813882f = false) { return "CREATE DATABASE IF NOT EXISTS `" . $pb67a2609 . "`" . ($v5d3813882f["encoding"] ? " DEFAULT CHARACTER SET " . $v5d3813882f["encoding"] : ""); } public static function getDropDatabaseStatement($pb67a2609, $v5d3813882f = false) { return "/*!40000 DROP DATABASE IF EXISTS `$pb67a2609`*/;"; } public static function getSelectedDBStatement($v5d3813882f = false) { return "SELECT DATABASE() AS db"; } public static function getDBsStatement($v5d3813882f = false) { return "SHOW DATABASES"; } public static function getTablesStatement($pb67a2609 = false, $v5d3813882f = false) { $pa51282b5 = $v5d3813882f && $v5d3813882f["schema"] ? $v5d3813882f["schema"] : null; $pb67a2609 = $pb67a2609 ? $pb67a2609 : $pa51282b5; $v3c76382d93 = "SELECT 
				TABLE_NAME AS 'table_name', 
				TABLE_TYPE AS 'table_type',
				TABLE_SCHEMA AS 'table_schema', 
				ENGINE AS 'table_storage_engine', 
				'' AS 'table_charset',
				TABLE_COLLATION AS 'table_collation', 
				TABLE_COMMENT AS 'table_comment'
			FROM information_schema.TABLES 
			WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA=" . ($pb67a2609 ? "'$pb67a2609'" : "DATABASE()") . "
			ORDER BY TABLE_NAME ASC"; return $v3c76382d93; } public static function getTableFieldsStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; $pb67a2609 = $pb67a2609 ? $pb67a2609 : $pa51282b5; $v3c76382d93 = "SELECT 
				COLUMN_NAME AS 'column_name', 
				DATA_TYPE AS 'data_type', 
				COLUMN_TYPE AS 'column_type', 
				COLUMN_DEFAULT AS 'column_default', 
				IS_NULLABLE AS 'is_nullable', 
				CHARACTER_MAXIMUM_LENGTH AS 'character_maximum_length', 
				NUMERIC_PRECISION AS 'numeric_precision', 
				CHARACTER_SET_NAME AS 'character_set_name', 
				COLLATION_NAME AS 'collation_name', 
				COLUMN_KEY AS 'column_key', 
				EXTRA AS 'extra', 
				COLUMN_COMMENT AS 'column_comment', 
				IF(LOWER(COLUMN_KEY) = 'pri', 1, 0) AS is_primary,
				IF(LOWER(COLUMN_KEY) = 'uni', 1, 0) AS is_unique
			FROM information_schema.COLUMNS 
			WHERE TABLE_SCHEMA=" . ($pb67a2609 ? "'$pb67a2609'" : "DATABASE()") . " AND TABLE_NAME='$pc661dc6b'
			ORDER BY ORDINAL_POSITION ASC"; return $v3c76382d93; } public static function getForeignKeysStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; $pb67a2609 = $pb67a2609 ? $pb67a2609 : $pa51282b5; $v3c76382d93 = "select 
				kcu.table_catalog AS 'catalog', 
				kcu.table_schema AS 'schema', 
				kcu.column_name AS 'child_column', 
				kcu.referenced_table_name AS 'parent_table', 
				kcu.referenced_column_name AS 'parent_column',
				kcu.constraint_name,
				rc.UPDATE_RULE AS 'on_update',
				rc.DELETE_RULE AS 'on_delete'
		from information_schema.key_column_usage kcu
		left join information_schema.referential_constraints rc ON rc.constraint_name=kcu.constraint_name and rc.CONSTRAINT_CATALOG=kcu.CONSTRAINT_CATALOG and rc.CONSTRAINT_SCHEMA=kcu.CONSTRAINT_SCHEMA and rc.TABLE_NAME=kcu.TABLE_NAME and rc.REFERENCED_TABLE_NAME=kcu.REFERENCED_TABLE_NAME
		where kcu.referenced_table_name is not null and kcu.table_schema = " . ($pb67a2609 ? "'$pb67a2609'" : "DATABASE()") . " and kcu.table_name = '$pc661dc6b'"; return $v3c76382d93; } public static function getCreateTableStatement($v87a92bb1ad, $v5d3813882f = false) { $v8c5df8072b = $v87a92bb1ad["table_name"] ? $v87a92bb1ad["table_name"] : $v87a92bb1ad["name"]; $v0aaaac50d5 = $v87a92bb1ad["table_charset"] ? $v87a92bb1ad["table_charset"] : $v87a92bb1ad["charset"]; $pe8765fe8 = $v87a92bb1ad["table_collation"] ? $v87a92bb1ad["table_collation"] : $v87a92bb1ad["collation"]; $v70378781f0 = $v87a92bb1ad["table_storage_engine"] ? $v87a92bb1ad["table_storage_engine"] : $v87a92bb1ad["engine"]; $pfdbbc383 = $v87a92bb1ad["attributes"]; $v70378781f0 = !empty($v70378781f0) ? "ENGINE=$v70378781f0" : ""; $v0aaaac50d5 = !empty($v0aaaac50d5) ? "DEFAULT CHARACTER SET=$v0aaaac50d5" : ""; $pe8765fe8 = !empty($pe8765fe8) ? (empty($v0aaaac50d5) ? "DEFAULT" : "") . " COLLATE=$pe8765fe8" : ""; $pe83acfb1 = self::getParsedTableEscapedSQL($v8c5df8072b, $v5d3813882f); $v3c76382d93 = "CREATE TABLE $pe83acfb1 (\n"; if (is_array($pfdbbc383)) { $v890f3625e5 = array(); foreach ($pfdbbc383 as $v97915b9670) { $v5e813b295b = $v97915b9670["name"]; $v597dd8d456 = $v97915b9670["primary_key"] == "1" || strtolower($v97915b9670["primary_key"]) == "true"; if ($v597dd8d456) $v890f3625e5[] = $v5e813b295b; $peda0af84 = self::getCreateTableAttributeStatement($v97915b9670); $v3c76382d93 .= "  " . $peda0af84 . ",\n"; } if ($v890f3625e5) $v3c76382d93 .= "  PRIMARY KEY (`" . implode("`, `", $v890f3625e5) . "`)"; else $v3c76382d93 = substr($v3c76382d93, 0, strlen($v3c76382d93) - 2); } if (is_array($v87a92bb1ad["unique_keys"])) foreach ($v87a92bb1ad["unique_keys"] as $pbfa01ed1) if ($pbfa01ed1["attribute"]) { $v3fb9f41470 = isset($pbfa01ed1["type"]) && $pbfa01ed1["type"] ? "USING " . $pbfa01ed1["type"] : ""; $ped0a6251 = is_array($pbfa01ed1["attribute"]) ? $pbfa01ed1["attribute"] : array($pbfa01ed1["attribute"]); $v3c76382d93 .= ",   UNIQUE " . $pbfa01ed1["name"] . " $v3fb9f41470 (`" . implode('`, `', $ped0a6251) . "`)"; } if (is_array($v87a92bb1ad["foreign_keys"])) foreach ($v87a92bb1ad["foreign_keys"] as $pbfa01ed1) if ($pbfa01ed1["attribute"] || $pbfa01ed1["child_column"]) { $v6912eee73f = isset($pbfa01ed1["on_delete"]) && $pbfa01ed1["on_delete"] ? "ON DELETE " . $pbfa01ed1["on_delete"] : ""; $pd7c78775 = isset($pbfa01ed1["on_update"]) && $pbfa01ed1["on_update"] ? "ON UPDATE " . $pbfa01ed1["on_update"] : ""; $v5e45ec9bb9 = $pbfa01ed1["attribute"] ? $pbfa01ed1["attribute"] : $pbfa01ed1["child_column"]; $pe3b2d7ad = $pbfa01ed1["reference_attribute"] ? $pbfa01ed1["reference_attribute"] : $pbfa01ed1["parent_column"]; $pf8a2ba68 = $pbfa01ed1["reference_table"] ? $pbfa01ed1["reference_table"] : $pbfa01ed1["parent_table"]; $pa28639ac = $pbfa01ed1["name"] ? $pbfa01ed1["name"] : $pbfa01ed1["constraint_name"]; $ped0a6251 = is_array($v5e45ec9bb9) ? $v5e45ec9bb9 : array($v5e45ec9bb9); $v9256fc8d51 = is_array($pe3b2d7ad) ? $pe3b2d7ad : array($pe3b2d7ad); $v45be4caf5b = self::getParsedTableEscapedSQL($pf8a2ba68, $v5d3813882f); $v3c76382d93 .= ",\n   FOREIGN KEY " . $pa28639ac . " (`" . implode('`, `', $ped0a6251) . "`) REFERENCES " . $v45be4caf5b . " (`" . implode('`, `', $v9256fc8d51) . "`) $v6912eee73f $pd7c78775"; } if (is_array($v87a92bb1ad["index_keys"])) foreach ($v87a92bb1ad["index_keys"] as $pbfa01ed1) if ($pbfa01ed1["attribute"]) { $v3fb9f41470 = isset($pbfa01ed1["type"]) && $pbfa01ed1["type"] ? "USING " . $pbfa01ed1["type"] : ""; $ped0a6251 = is_array($pbfa01ed1["attribute"]) ? $pbfa01ed1["attribute"] : array($pbfa01ed1["attribute"]); $v3c76382d93 .= ",\n   INDEX " . $pbfa01ed1["name"] . " $v3fb9f41470 (`" . implode('`, `', $ped0a6251) . "`),\n"; } $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v3c76382d93 .= "\n) $v70378781f0 $v0aaaac50d5 $pe8765fe8 $v77cb07b555"; return trim($v3c76382d93); } public static function getCreateTableAttributeStatement($v261e7b366d, $v5d3813882f = false, &$v808a08b1f9 = array()) { $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $name = $v261e7b366d["name"]; $type = self::convertColumnTypeToDB($v261e7b366d["type"], $pe1390784); $length = $v261e7b366d["length"]; $pk = $v261e7b366d["primary_key"] == "1" || strtolower($v261e7b366d["primary_key"]) == "true"; $auto_increment = $v261e7b366d["auto_increment"] == "1" || strtolower($v261e7b366d["auto_increment"]) == "true"; $unsigned = $v261e7b366d["unsigned"] == "1" || strtolower($v261e7b366d["unsigned"]) == "true"; $unique = $v261e7b366d["unique"] == "1" || strtolower($v261e7b366d["unique"]) == "true"; $null = $v261e7b366d["null"] == "1" || strtolower($v261e7b366d["null"]) == "true"; $default = $v261e7b366d["default"]; $default_type = $v261e7b366d["default_type"]; $extra = $v261e7b366d["extra"]; $collation = $v261e7b366d["collation"]; $comment = $v261e7b366d["comment"]; if ($pe1390784) foreach ($pe1390784 as $pe5c5e2fe => $v956913c90f) eval("\$$pe5c5e2fe = \$v956913c90f;"); if ($auto_increment || stripos($extra, "auto_increment") !== false) { $extra = str_ireplace("auto_increment", "", $extra); $auto_increment = true; } $length = !self::ignoreColumnTypeDBProp($type, "length") && is_numeric($length) ? $length : null; $auto_increment = !self::ignoreColumnTypeDBProp($type, "auto_increment") ? $auto_increment : null; $unsigned = !self::ignoreColumnTypeDBProp($type, "unsigned") ? $unsigned : false; $unique = !self::ignoreColumnTypeDBProp($type, "unique") ? $unique : false; $null = !self::ignoreColumnTypeDBProp($type, "null") ? $null : null; $default = !self::ignoreColumnTypeDBProp($type, "default") ? $default : null; $collation = !self::ignoreColumnTypeDBProp($type, "collation") ? $collation : null; $comment = !self::ignoreColumnTypeDBProp($type, "comment") ? $comment : null; $pdbbe5385 = self::isReservedWord($default); $v3d15cdb3d4 = self::isReservedWordFunction($default); $v23ed8083a0 = in_array($type, self::getDBColumnNumericTypes()); $default = isset($default) && $v23ed8083a0 && !is_numeric($default) && !$pdbbe5385 && !$v3d15cdb3d4 ? null : $default; $default_type = $default_type ? $default_type : (isset($default) && (is_numeric($default) || $pdbbe5385 || $v3d15cdb3d4) ? "numeric" : "string"); $v808a08b1f9["type"] = $type; $v808a08b1f9["primary_key"] = $pk; $v808a08b1f9["length"] = $length; $v808a08b1f9["auto_increment"] = $auto_increment; $v808a08b1f9["unsigned"] = $unsigned; $v808a08b1f9["unique"] = $unique; $v808a08b1f9["null"] = $null; $v808a08b1f9["default"] = $default; $v808a08b1f9["collation"] = $collation; $v808a08b1f9["comment"] = $comment; $length = $length && !$v5d3813882f["ignore_length"] ? "($length)" : ""; $auto_increment = $auto_increment && !$v5d3813882f["ignore_auto_increment"] ? "AUTO_INCREMENT" : ""; $unsigned = $unsigned && !$v5d3813882f["ignore_unsigned"] ? "unsigned" : ""; $unique = $unique && !$v5d3813882f["ignore_unique"] && !$pk ? "UNIQUE" : ""; $null = isset($null) && !$v5d3813882f["ignore_null"] ? ($null ? "NULL" : "NOT NULL") : ""; $default = isset($default) && !$v5d3813882f["ignore_default"] && !$auto_increment ? "DEFAULT " . ($default_type == "string" ? "'$default'" : $default) : ""; $collation = !empty($collation) && !$v5d3813882f["ignore_collation"] ? "COLLATE $collation" : ""; $comment = !empty($comment) && !$v5d3813882f["ignore_comment"] ? "COMMENT '$comment'" : ""; $extra = !$v5d3813882f["ignore_extra"] ? $extra : ""; $v77cb07b555 = $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return trim(preg_replace("/[ ]+/", " ", "`$name` $type{$length} $unsigned $null $auto_increment $unique $charset $collation $default $extra $comment $v77cb07b555")); } public static function getRenameTableStatement($pe8f357f7, $v38abe7147f, $v5d3813882f = false) { $v93973610e9 = self::getParsedTableEscapedSQL($pe8f357f7, $v5d3813882f); $pf87d7c43 = self::getParsedTableEscapedSQL($v38abe7147f, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "RENAME TABLE $v93973610e9 TO $pf87d7c43 $v77cb07b555"; } public static function getDropTableStatement($pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "DROP TABLE IF EXISTS $v769bf5da97 $v77cb07b555"; } public static function getDropTableCascadeStatement($pc661dc6b, $v5d3813882f = false) { $v5d3813882f["suffix"] = "CASCADE " . $v5d3813882f["suffix"]; return self::getDropTableStatement($pc661dc6b, $v5d3813882f); } public static function getAddTableAttributeStatement($pc661dc6b, $v261e7b366d, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v3c76382d93 = self::getCreateTableAttributeStatement($v261e7b366d); return "ALTER TABLE $v769bf5da97 ADD COLUMN $v3c76382d93 $v77cb07b555"; } public static function getModifyTableAttributeStatement($pc661dc6b, $v261e7b366d, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v3c76382d93 = self::getCreateTableAttributeStatement($v261e7b366d); return "ALTER TABLE $v769bf5da97 MODIFY COLUMN $v3c76382d93 $v77cb07b555"; } public static function getRenameTableAttributeStatement($pc661dc6b, $pfc66218f, $paa23699f, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "ALTER TABLE $v769bf5da97 RENAME COLUMN `$pfc66218f` TO `$paa23699f` $v77cb07b555"; } public static function getDropTableAttributeStatement($pc661dc6b, $v97915b9670, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "ALTER TABLE $v769bf5da97 DROP COLUMN `$v97915b9670` $v77cb07b555"; } public static function getAddTablePrimaryKeysStatement($pc661dc6b, $pfdbbc383, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pa28639ac = str_replace(" ", "_", strtolower($pc661dc6b)) . "_pk"; $pfdbbc383 = is_array($pfdbbc383) ? $pfdbbc383 : array($pfdbbc383); $v325ffa1d87 = array(); foreach ($pfdbbc383 as $v1b0cfa478b) { if (is_array($v1b0cfa478b)) $v325ffa1d87[] = $v1b0cfa478b["name"]; else $v325ffa1d87[] = $v1b0cfa478b; } return "ALTER TABLE $v769bf5da97 ADD CONSTRAINT $pa28639ac PRIMARY KEY (`" . implode("`, `", $v325ffa1d87) . "`) $v77cb07b555"; } public static function getDropTablePrimaryKeysStatement($pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "ALTER TABLE $v769bf5da97 DROP PRIMARY KEY $v77cb07b555"; } public static function getAddTableForeignKeyStatement($pc661dc6b, $pa7c14731, $v5d3813882f = false) { if ($pa7c14731 && ($pa7c14731["attribute"] || $pa7c14731["child_column"])) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v6912eee73f = isset($pa7c14731["on_delete"]) && $pa7c14731["on_delete"] ? "ON DELETE " . $pa7c14731["on_delete"] : ""; $pd7c78775 = isset($pa7c14731["on_update"]) && $pa7c14731["on_update"] ? "ON UPDATE " . $pa7c14731["on_update"] : ""; $v5e45ec9bb9 = $pa7c14731["attribute"] ? $pa7c14731["attribute"] : $pa7c14731["child_column"]; $pe3b2d7ad = $pa7c14731["reference_attribute"] ? $pa7c14731["reference_attribute"] : $pa7c14731["parent_column"]; $pf8a2ba68 = $pa7c14731["reference_table"] ? $pa7c14731["reference_table"] : $pa7c14731["parent_table"]; $pa28639ac = $pa7c14731["name"] ? $pa7c14731["name"] : $pa7c14731["constraint_name"]; $ped0a6251 = is_array($v5e45ec9bb9) ? $v5e45ec9bb9 : array($v5e45ec9bb9); $v9256fc8d51 = is_array($pe3b2d7ad) ? $pe3b2d7ad : array($pe3b2d7ad); $v45be4caf5b = self::getParsedTableEscapedSQL($pf8a2ba68, $v5d3813882f); return "ALTER TABLE $v769bf5da97 ADD FOREIGN KEY " . $pa28639ac . " (`" . implode('`, `', $ped0a6251) . "`) REFERENCES " . $v45be4caf5b . " (`" . implode('`, `', $v9256fc8d51) . "`) $v6912eee73f $pd7c78775 $v77cb07b555"; } } public static function getDropTableForeignKeysStatement($pc661dc6b, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "DROP PROCEDURE IF EXISTS dropAllDBForeignKeys;
DELIMITER ;;
CREATE PROCEDURE dropAllDBForeignKeys()
BEGIN
  DECLARE bDone INT;
  DECLARE sql_str VARCHAR(1000);

  DECLARE curs CURSOR FOR SELECT 
	  CONCAT('ALTER TABLE ', TABLE_NAME, ' DROP FOREIGN KEY ', CONSTRAINT_NAME, '$v77cb07b555;') AS 'drop_sql'
	FROM information_schema.key_column_usage 
	WHERE CONSTRAINT_SCHEMA = DATABASE() AND referenced_table_name IS NOT NULL AND TABLE_NAME='$pc661dc6b';
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;

  OPEN curs;

  SET bDone = 0;
  REPEAT
    FETCH curs INTO sql_str;

    IF sql_str != '' THEN
	  SET @sql = sql_str;
	  PREPARE stmt FROM @sql;
	  EXECUTE stmt;
	  DEALLOCATE PREPARE stmt;
    END IF;
  UNTIL bDone END REPEAT;

  CLOSE curs;
END;;
DELIMITER ;

CALL dropAllDBForeignKeys();
DROP PROCEDURE IF EXISTS dropAllDBForeignKeys;"; } public static function getDropTableForeignConstraintStatement($pc661dc6b, $pa28639ac, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; return "DROP PROCEDURE IF EXISTS dropTableForeignKey;
DELIMITER ;;
CREATE PROCEDURE dropTableForeignKey()
BEGIN
  IF (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='$pc661dc6b') = 1
  THEN 
  	IF (SELECT COUNT(*) FROM information_schema.key_column_usage WHERE referenced_table_name is not null AND table_schema = DATABASE() AND table_name='table' AND constraint_name='$pa28639ac') = 1
  	THEN 
	  	ALTER TABLE `$v769bf5da97` DROP FOREIGN KEY `$pa28639ac`;
  	END IF;
  END IF;
END;;
DELIMITER ;

CALL dropTableForeignKey();
DROP PROCEDURE IF EXISTS dropTableForeignKey;"; } public static function getAddTableIndexStatement($pc661dc6b, $pfdbbc383, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pfdbbc383 = is_array($pfdbbc383) ? $pfdbbc383 : array($pfdbbc383); return "ALTER TABLE $v769bf5da97 ADD INDEX (`" . implode("`, `", $pfdbbc383) . "`) $v77cb07b555"; } public static function getLoadTableDataFromFileStatement($pf3dc0762, $pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pfdbbc383 = $v5d3813882f && $v5d3813882f["attributes"] ? "(" . (is_array($v5d3813882f["attributes"]) ? implode(", ", $v5d3813882f["attributes"]) : $v5d3813882f["attributes"]) . ")" : ""; $v52fe4649ca = $v5d3813882f["fields_delimiter"] ? $v5d3813882f["fields_delimiter"] : "\t"; $v78ac4d6619 = $v5d3813882f["lines_delimiter"] ? $v5d3813882f["lines_delimiter"] : "\r\n"; return "LOAD DATA LOCAL INFILE '$pf3dc0762' INTO TABLE $v769bf5da97 FIELDS TERMINATED BY '$v52fe4649ca' LINES TERMINATED BY '$v78ac4d6619' $pfdbbc383 $v77cb07b555"; } public static function getShowCreateTableStatement($pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); return "SHOW CREATE TABLE $v769bf5da97"; } public static function getShowCreateViewStatement($pa36e00ea, $v5d3813882f = false) { $pe8a320e9 = self::getParsedTableEscapedSQL($pa36e00ea, $v5d3813882f); return "SHOW CREATE VIEW $pe8a320e9"; } public static function getShowCreateTriggerStatement($v5ed3bce1d1, $v5d3813882f = false) { $v6caad9d962 = self::getParsedTableEscapedSQL($v5ed3bce1d1, $v5d3813882f); return "SHOW CREATE TRIGGER $v6caad9d962"; } public static function getShowCreateProcedureStatement($pbda8f16d, $v5d3813882f = false) { $v7c7c261ebc = self::getParsedTableEscapedSQL($pbda8f16d, $v5d3813882f); return "SHOW CREATE PROCEDURE $v7c7c261ebc"; } public static function getShowCreateFunctionStatement($v2f4e66e00a, $v5d3813882f = false) { $v7992b8f618 = self::getParsedTableEscapedSQL($v2f4e66e00a, $v5d3813882f); return "SHOW CREATE FUNCTION $v7992b8f618"; } public static function getShowCreateEventStatement($v76392c9cad, $v5d3813882f = false) { $pdea5ed12 = self::getParsedTableEscapedSQL($v76392c9cad, $v5d3813882f); return "SHOW CREATE EVENT $pdea5ed12"; } public static function getShowTablesStatement($pb67a2609, $v5d3813882f = false) { return str_replace("\t", "", self::getTablesStatement($pb67a2609, $v5d3813882f)); } public static function getShowViewsStatement($pb67a2609, $v5d3813882f = false) { return "SELECT TABLE_NAME AS table_name ". "FROM INFORMATION_SCHEMA.TABLES ". "WHERE TABLE_TYPE='VIEW' AND TABLE_SCHEMA='$pb67a2609'"; } public static function getShowTriggersStatement($pb67a2609, $v5d3813882f = false) { return "SHOW TRIGGERS FROM `$pb67a2609`;"; } public static function getShowTableColumnsStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { return str_replace("\t", "", self::getTableFieldsStatement($pc661dc6b, $pb67a2609, $v5d3813882f)); } public static function getShowForeignKeysStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { return str_replace("\t", "", self::getForeignKeysStatement($pc661dc6b, $pb67a2609, $v5d3813882f)); } public static function getShowProceduresStatement($pb67a2609, $v5d3813882f = false) { return "SELECT SPECIFIC_NAME AS procedure_name ". "FROM INFORMATION_SCHEMA.ROUTINES ". "WHERE ROUTINE_TYPE='PROCEDURE' AND ROUTINE_SCHEMA='$pb67a2609'"; } public static function getShowFunctionsStatement($pb67a2609, $v5d3813882f = false) { return "SELECT SPECIFIC_NAME AS function_name ". "FROM INFORMATION_SCHEMA.ROUTINES ". "WHERE ROUTINE_TYPE='FUNCTION' AND ROUTINE_SCHEMA='$pb67a2609'"; } public static function getShowEventsStatement($pb67a2609, $v5d3813882f = false) { return "SELECT EVENT_NAME AS event_name ". "FROM INFORMATION_SCHEMA.EVENTS ". "WHERE EVENT_SCHEMA='$pb67a2609'"; } public static function getSetupTransactionStatement($v5d3813882f = false) { return "SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ"; } public static function getStartTransactionStatement($v5d3813882f = false) { return "START TRANSACTION ". "/*!40100 WITH CONSISTENT SNAPSHOT */"; } public static function getCommitTransactionStatement($v5d3813882f = false) { return "COMMIT"; } public static function getStartDisableAutocommitStatement($v5d3813882f = false) { return "SET autocommit=0;"; } public static function getEndDisableAutocommitStatement($v5d3813882f = false) { return "COMMIT;"; } public static function getStartLockTableWriteStatement($pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); return "LOCK TABLES $v769bf5da97 WRITE;"; } public static function getStartLockTableReadStatement($pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); return "LOCK TABLES $v769bf5da97 READ LOCAL;"; } public static function getEndLockTableStatement($v5d3813882f = false) { return "UNLOCK TABLES;"; } public static function getStartDisableKeysStatement($pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); return "/*!40000 ALTER TABLE $v769bf5da97 DISABLE KEYS */;"; } public static function getEndDisableKeysStatement($pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); return "/*!40000 ALTER TABLE $v769bf5da97 ENABLE KEYS */;"; } public static function getDropTriggerStatement($v5ed3bce1d1, $v5d3813882f = false) { $v6caad9d962 = self::getParsedTableEscapedSQL($v5ed3bce1d1, $v5d3813882f); return "DROP TRIGGER IF EXISTS $v6caad9d962;"; } public static function getDropProcedureStatement($pbda8f16d, $v5d3813882f = false) { $v7c7c261ebc = self::getParsedTableEscapedSQL($pbda8f16d, $v5d3813882f); return "DROP PROCEDURE IF EXISTS $v7c7c261ebc;"; } public static function getDropFunctionStatement($v2f4e66e00a, $v5d3813882f = false) { $v7992b8f618 = self::getParsedTableEscapedSQL($v2f4e66e00a, $v5d3813882f); return "DROP FUNCTION IF EXISTS $v7992b8f618;"; } public static function getDropEventStatement($v76392c9cad, $v5d3813882f = false) { $pdea5ed12 = self::getParsedTableEscapedSQL($v76392c9cad, $v5d3813882f); return "DROP EVENT IF EXISTS $pdea5ed12;"; } public static function getDropViewStatement($pa36e00ea, $v5d3813882f = false) { $pe8a320e9 = self::getParsedTableEscapedSQL($pa36e00ea, $v5d3813882f); return "DROP VIEW IF EXISTS $pe8a320e9;"; } } ?>
