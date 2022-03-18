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

trait MSSqlDBStatement { public static function getCreateDBStatement($pb67a2609, $v5d3813882f = false) { if ($v5d3813882f["encoding"]) { $pf6d213c3 = self::$db_charsets_to_collations[ $v5d3813882f["encoding"] ]; $pf6d213c3 = $pf6d213c3 ? " COLLATE " . $pf6d213c3 : ""; } return "IF DB_ID ('" . $pb67a2609 . "') IS NULL CREATE DATABASE " . $pb67a2609 . $pf6d213c3; } public static function getDropDatabaseStatement($pb67a2609, $v5d3813882f = false) { return "/* DROP DATABASE IF EXISTS [$pb67a2609] */;"; } public static function getSelectedDBStatement($v5d3813882f = false) { return "SELECT DB_NAME() AS db"; } public static function getDBsStatement($v5d3813882f = false) { return "SELECT name, database_id FROM sys.databases"; } public static function getTablesStatement($pb67a2609 = false, $v5d3813882f = false) { $pa51282b5 = $v5d3813882f && $v5d3813882f["schema"] ? $v5d3813882f["schema"] : null; $v3c76382d93 ="SELECT 
				t.TABLE_NAME AS 'table_name', 
				t.TABLE_TYPE AS 'table_type',
				t.TABLE_SCHEMA AS 'table_schema'
			FROM information_schema.TABLES t 
			INNER JOIN sys.tables st ON SCHEMA_NAME(st.schema_id)=t.TABLE_SCHEMA AND st.name=t.TABLE_NAME AND st.is_ms_shipped=0 
			WHERE t.TABLE_TYPE='BASE TABLE' AND t.TABLE_CATALOG=" . ($pb67a2609 ? "'$pb67a2609'" : "DB_NAME()") . ($pa51282b5 ? " AND t.TABLE_SCHEMA='$pa51282b5'" : "") . "
			ORDER BY t.TABLE_NAME ASC"; return $v3c76382d93; } public static function getTableFieldsStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; $pd5ae1c40 = $pbec62cc6["database"]; $pb67a2609 = $pb67a2609 ? $pb67a2609 : $pd5ae1c40; $v3c76382d93 = "SELECT 
				isc.COLUMN_NAME AS 'column_name',  
				isc.DATA_TYPE AS 'data_type', 
				isc.COLUMN_DEFAULT AS 'column_default',  
				isc.IS_NULLABLE AS 'is_nullable', 
				isc.CHARACTER_MAXIMUM_LENGTH AS 'character_maximum_length', 
				isc.NUMERIC_PRECISION AS 'numeric_precision', 
				isc.NUMERIC_SCALE AS 'numeric_scale', 
				isc.CHARACTER_SET_NAME AS 'character_set_name', 
				isc.COLLATION_NAME AS 'collation_name', 
				sep.value AS 'column_comment',
				col.is_identity,
				ic.seed_value,
				ic.increment_value,
				isccu_pk.COLUMN_NAME AS 'is_primary_key',
				isccu_uk.COLUMN_NAME AS 'is_unique_key'
			FROM information_schema.COLUMNS AS isc 
			INNER JOIN sys.columns AS col ON col.name = isc.COLUMN_NAME
			INNER JOIN sys.tables AS tab ON tab.object_id = col.object_id AND tab.name = isc.TABLE_NAME
			LEFT JOIN sys.identity_columns AS ic ON ic.object_id = col.object_id AND ic.name = col.name
			LEFT JOIN sys.extended_properties sep ON tab.object_id = sep.major_id AND col.column_id = sep.minor_id AND sep.name = 'MS_Description'
			LEFT JOIN information_schema.TABLE_CONSTRAINTS istc_pk ON istc_pk.TABLE_CATALOG = isc.TABLE_CATALOG AND istc_pk.TABLE_NAME = isc.TABLE_NAME AND istc_pk.CONSTRAINT_TYPE = 'PRIMARY KEY'
			LEFT JOIN information_schema.CONSTRAINT_COLUMN_USAGE isccu_pk ON isccu_pk.TABLE_CATALOG = isc.TABLE_CATALOG AND isccu_pk.TABLE_NAME = isc.TABLE_NAME AND isccu_pk.CONSTRAINT_NAME = istc_pk.CONSTRAINT_NAME AND isccu_pk.COLUMN_NAME = isc.COLUMN_NAME
			LEFT JOIN information_schema.TABLE_CONSTRAINTS istc_uk ON istc_uk.TABLE_CATALOG = isc.TABLE_CATALOG AND istc_uk.TABLE_NAME = isc.TABLE_NAME AND istc_uk.CONSTRAINT_TYPE = 'UNIQUE'
			LEFT JOIN information_schema.CONSTRAINT_COLUMN_USAGE isccu_uk ON isccu_uk.TABLE_CATALOG = isc.TABLE_CATALOG AND isccu_uk.TABLE_NAME = isc.TABLE_NAME AND isccu_uk.CONSTRAINT_NAME = istc_uk.CONSTRAINT_NAME AND isccu_uk.COLUMN_NAME = isc.COLUMN_NAME
			WHERE isc.TABLE_CATALOG=" . ($pb67a2609 ? "'$pb67a2609'" : "DB_NAME()") . ($pa51282b5 ? " AND isc.TABLE_SCHEMA='$pa51282b5'" : "") . " AND isc.TABLE_NAME='$pc661dc6b'
			ORDER BY isc.ORDINAL_POSITION ASC;"; return $v3c76382d93; } public static function getForeignKeysStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; $pd5ae1c40 = $pbec62cc6["database"]; $pb67a2609 = $pb67a2609 ? $pb67a2609 : $pd5ae1c40; $v3c76382d93 = "SELECT   
			    OBJECT_NAME(f.parent_object_id) AS 'child_table',
			    COL_NAME(fc.parent_object_id, fc.parent_column_id) AS 'child_column',
			    OBJECT_NAME (f.referenced_object_id) AS 'parent_table',
			    COL_NAME(fc.referenced_object_id, fc.referenced_column_id) AS 'parent_column',
			    f.name AS 'constraint_name',
			    f.delete_referential_action,
			    f.delete_referential_action_desc,
                   CASE f.delete_referential_action
                      WHEN 0 THEN 'NO ACTION '
                      WHEN 1 THEN 'CASCADE '
                      WHEN 2 THEN 'SET NULL '
                      ELSE 'SET DEFAULT '
                     END AS on_delete,
			    f.update_referential_action,
			    f.update_referential_action_desc,
                   CASE f.update_referential_action
                      WHEN 0 THEN 'NO ACTION '
                      WHEN 1 THEN 'CASCADE '
                      WHEN 2 THEN 'SET NULL '
                      ELSE 'SET DEFAULT '
                     END AS on_update,
			    f.is_disabled,
			    CASE f.is_disabled
                      WHEN 0 THEN ' WITH CHECK '
                      ELSE ' WITH NOCHECK '
                     END AS disabled_code,
			    f.is_not_trusted,
			    CASE f.is_not_trusted
                      WHEN 0 THEN ' WITH CHECK '
                      ELSE ' WITH NOCHECK '
                     END AS not_trusted_code,
			    f.is_not_for_replication, 
                   CASE f.is_not_for_replication
	                 WHEN 1 THEN ' NOT FOR REPLICATION '
	                 ELSE ''
                     END AS replication_code
			FROM sys.foreign_keys AS f  
			INNER JOIN sys.foreign_key_columns AS fc ON f.object_id = fc.constraint_object_id
			INNER JOIN information_schema.COLUMNS AS isc ON isc.TABLE_CATALOG=" . ($pb67a2609 ? "'$pb67a2609'" : "DB_NAME()") . " AND isc.TABLE_SCHEMA=SCHEMA_NAME(f.schema_id) AND OBJECT_ID(isc.TABLE_NAME)=f.parent_object_id AND isc.COLUMN_NAME=COL_NAME(fc.parent_object_id, fc.parent_column_id)
			WHERE " . ($pa51282b5 ? " isc.TABLE_SCHEMA='$pa51282b5' AND " : "") . "isc.TABLE_NAME='$pc661dc6b'"; return $v3c76382d93; } public static function getCreateTableStatement($v87a92bb1ad, $v5d3813882f = false) { $v8c5df8072b = $v87a92bb1ad["table_name"] ? $v87a92bb1ad["table_name"] : $v87a92bb1ad["name"]; $pe8765fe8 = $v87a92bb1ad["table_collation"] ? $v87a92bb1ad["table_collation"] : $v87a92bb1ad["collation"]; $pfdbbc383 = $v87a92bb1ad["attributes"]; $pe8765fe8 = !empty($pe8765fe8) ? " COLLATE=$pe8765fe8" : ""; $pe83acfb1 = self::getParsedTableEscapedSQL($v8c5df8072b, $v5d3813882f); $v3c76382d93 = "CREATE TABLE $pe83acfb1 (\n"; if (is_array($pfdbbc383)) { $v890f3625e5 = array(); foreach ($pfdbbc383 as $v97915b9670) { $v5e813b295b = $v97915b9670["name"]; $v597dd8d456 = $v97915b9670["primary_key"] == "1" || strtolower($v97915b9670["primary_key"]) == "true"; if ($v597dd8d456) $v890f3625e5[] = $v5e813b295b; $peda0af84 = self::getCreateTableAttributeStatement($v97915b9670); $v3c76382d93 .= "  " . $peda0af84 . ",\n"; } if ($v890f3625e5) $v3c76382d93 .= "  PRIMARY KEY ([" . implode("], [", $v890f3625e5) . "])"; else $v3c76382d93 = substr($v3c76382d93, 0, strlen($v3c76382d93) - 2); } if (is_array($v87a92bb1ad["unique_keys"])) foreach ($v87a92bb1ad["unique_keys"] as $pbfa01ed1) if ($pbfa01ed1["attribute"]) { $v3fb9f41470 = isset($pbfa01ed1["type"]) && $pbfa01ed1["type"] ? "WITH " . $pbfa01ed1["type"] : ""; $ped0a6251 = is_array($pbfa01ed1["attribute"]) ? $pbfa01ed1["attribute"] : array($pbfa01ed1["attribute"]); $v3c76382d93 .= ",   " . ($pbfa01ed1["name"] ? "CONSTRAINT " . $pbfa01ed1["name"] . " " : "") . "UNIQUE ([" . implode('", "', $ped0a6251) . "]) $v3fb9f41470"; } if (is_array($v87a92bb1ad["foreign_keys"])) foreach ($v87a92bb1ad["foreign_keys"] as $pbfa01ed1) if ($pbfa01ed1["attribute"] || $pbfa01ed1["child_column"]) { $v6912eee73f = isset($pbfa01ed1["on_delete"]) && $pbfa01ed1["on_delete"] ? "ON DELETE " . $pbfa01ed1["on_delete"] : ""; $pd7c78775 = isset($pbfa01ed1["on_update"]) && $pbfa01ed1["on_update"] ? "ON UPDATE " . $pbfa01ed1["on_update"] : ""; $v5e45ec9bb9 = $pbfa01ed1["attribute"] ? $pbfa01ed1["attribute"] : $pbfa01ed1["child_column"]; $pe3b2d7ad = $pbfa01ed1["reference_attribute"] ? $pbfa01ed1["reference_attribute"] : $pbfa01ed1["parent_column"]; $pf8a2ba68 = $pbfa01ed1["reference_table"] ? $pbfa01ed1["reference_table"] : $pbfa01ed1["parent_table"]; $pa28639ac = $pbfa01ed1["name"] ? $pbfa01ed1["name"] : $pbfa01ed1["constraint_name"]; $ped0a6251 = is_array($v5e45ec9bb9) ? $v5e45ec9bb9 : array($v5e45ec9bb9); $v9256fc8d51 = is_array($pe3b2d7ad) ? $pe3b2d7ad : array($pe3b2d7ad); $v45be4caf5b = self::getParsedTableEscapedSQL($pf8a2ba68, $v5d3813882f); $v3c76382d93 .= ",\n   " . ($pa28639ac ? "CONSTRAINT " . $pa28639ac . " " : "") . " FOREIGN KEY ([" . implode('", "', $ped0a6251) . "]) REFERENCES " . $v45be4caf5b . " ([" . implode('", "', $v9256fc8d51) . "]) $v6912eee73f $pd7c78775"; } if (is_array($v87a92bb1ad["index_keys"])) foreach ($v87a92bb1ad["index_keys"] as $pbfa01ed1) if ($pbfa01ed1["attribute"]) { $v3fb9f41470 = isset($pbfa01ed1["type"]) && $pbfa01ed1["type"] ? "WITH " . $pbfa01ed1["type"] : ""; $ped0a6251 = is_array($pbfa01ed1["attribute"]) ? $pbfa01ed1["attribute"] : array($pbfa01ed1["attribute"]); $v3c76382d93 .= ",\n   INDEX " . $pbfa01ed1["name"] . " ([" . implode('", "', $ped0a6251) . "]) $v3fb9f41470"; } $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v3c76382d93 .= "\n) $pe8765fe8 $v77cb07b555"; return trim($v3c76382d93); } public static function getCreateTableAttributeStatement($v261e7b366d, $v5d3813882f = false, &$v808a08b1f9 = array()) { $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $name = $v261e7b366d["name"]; $type = self::convertColumnTypeToDB($v261e7b366d["type"], $pe1390784); $length = $v261e7b366d["length"]; $pk = $v261e7b366d["primary_key"] == "1" || strtolower($v261e7b366d["primary_key"]) == "true"; $auto_increment = $v261e7b366d["auto_increment"] == "1" || strtolower($v261e7b366d["auto_increment"]) == "true" || stripos($v261e7b366d["extra"], "auto_increment") !== false; $unique = $v261e7b366d["unique"] == "1" || strtolower($v261e7b366d["unique"]) == "true"; $null = $v261e7b366d["null"] == "1" || strtolower($v261e7b366d["null"]) == "true"; $default = $v261e7b366d["default"]; $default_type = $v261e7b366d["default_type"]; $extra = $v261e7b366d["extra"]; $collation = $v261e7b366d["collation"]; if ($pe1390784) foreach ($pe1390784 as $pe5c5e2fe => $v956913c90f) if ($pe5c5e2fe != "unsigned" && $pe5c5e2fe != "charset" && $pe5c5e2fe != "comment") eval("\$$pe5c5e2fe = \$v956913c90f;"); if ($auto_increment || stripos($extra, "auto_increment") !== false || stripos($extra, "identity") !== false) { $extra = str_ireplace("auto_increment", "", $extra); $auto_increment = true; } $length = !self::ignoreColumnTypeDBProp($type, "length") && is_numeric($length) ? $length : null; $auto_increment = !self::ignoreColumnTypeDBProp($type, "auto_increment") ? $auto_increment : null; $unique = !self::ignoreColumnTypeDBProp($type, "unique") && !$pk ? $unique : false; $null = !self::ignoreColumnTypeDBProp($type, "null") ? $null : null; $default = !self::ignoreColumnTypeDBProp($type, "default") ? $default : null; $collation = !self::ignoreColumnTypeDBProp($type, "collation") ? $collation : null; $pdbbe5385 = self::isReservedWord($default); $v3d15cdb3d4 = self::isReservedWordFunction($default); $v23ed8083a0 = in_array($type, self::getDBColumnNumericTypes()); $default = isset($default) && $v23ed8083a0 && !is_numeric($default) && !$pdbbe5385 && !$v3d15cdb3d4 ? null : $default; if (!isset($default) && isset($null) && !$null && !$pk) { $default = self::getDefaultValueForColumnType($type); $pdbbe5385 = self::isReservedWord($default); $v3d15cdb3d4 = self::isReservedWordFunction($default); } $default_type = $default_type ? $default_type : (isset($default) && (is_numeric($default) || $pdbbe5385 || $v3d15cdb3d4) ? "numeric" : "string"); $v808a08b1f9["type"] = $type; $v808a08b1f9["primary_key"] = $pk; $v808a08b1f9["length"] = $length; $v808a08b1f9["auto_increment"] = $auto_increment; $v808a08b1f9["unique"] = $unique; $v808a08b1f9["null"] = $null; $v808a08b1f9["default"] = $default; $v808a08b1f9["collation"] = $collation; $length = $length && !$v5d3813882f["ignore_length"] ? "($length)" : ""; $auto_increment = $auto_increment && !$v5d3813882f["ignore_auto_increment"] && stripos($extra, "identity") === false ? "IDENTITY (1,1)" : ""; $unique = $unique && !$v5d3813882f["ignore_unique"] ? "UNIQUE" : ""; $null = isset($null) && !$v5d3813882f["ignore_null"] ? ($null ? "NULL" : "NOT NULL") : ""; $default = isset($default) && !$v5d3813882f["ignore_default"] && !$auto_increment ? "DEFAULT " . ($default_type == "string" ? "'$default'" : $default) : ""; $collation = !empty($collation) && !$v5d3813882f["ignore_collation"] ? "COLLATE $collation" : ""; $extra = !$v5d3813882f["ignore_extra"] ? $extra : ""; $v77cb07b555 = $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v3c76382d93 = trim(preg_replace("/[ ]+/", " ", "[$name] $type{$length} $unsigned $charset $collation $null $auto_increment $unique $default $extra $comment $v77cb07b555")); return $v3c76382d93; } public static function getRenameTableStatement($pe8f357f7, $v38abe7147f, $v5d3813882f = false) { $v93973610e9 = self::getParsedTableEscapedSQL($pe8f357f7, $v5d3813882f); $pf87d7c43 = self::getParsedTableEscapedSQL($v38abe7147f, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "EXEC sp_rename $v93973610e9, $pf87d7c43 $v77cb07b555"; } public static function getDropTableStatement($pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "DROP TABLE IF EXISTS $v769bf5da97 $v77cb07b555"; } public static function getDropTableCascadeStatement($pc661dc6b, $v5d3813882f = false) { return self::getDropTableStatement($pc661dc6b, $v5d3813882f); } public static function getAddTableAttributeStatement($pc661dc6b, $v261e7b366d, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v3c76382d93 = self::getCreateTableAttributeStatement($v261e7b366d); return "ALTER TABLE $v769bf5da97 ADD $v3c76382d93 $v77cb07b555"; } public static function getModifyTableAttributeStatement($pc661dc6b, $v261e7b366d, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; $v5eaddbc862 = self::getCreateTableAttributeStatement($v261e7b366d, array("ignore_default" => true, "ignore_unique" => true, "ignore_auto_increment" => true), $v808a08b1f9); if ($v808a08b1f9["primary_key"] && $v808a08b1f9["extra"]) $v5eaddbc862 = self::getCreateTableAttributeStatement($v261e7b366d, array("ignore_default" => true, "ignore_unique" => true, "ignore_auto_increment" => true, "ignore_extra" => true), $v808a08b1f9); $v5e813b295b = $v261e7b366d["name"]; $v4bfe0500a2 = null; $v6a54e01756 = isset($v808a08b1f9["default"]) && $v261e7b366d["has_default"]; $v2be87570d1 = null; $v93ff269092 = rand(); if ($v6a54e01756) { $pdbbe5385 = self::isReservedWord($v808a08b1f9["default"]); $v3d15cdb3d4 = self::isReservedWordFunction($v808a08b1f9["default"]); $v4bfe0500a2 = is_numeric($v808a08b1f9["default"]) || $pdbbe5385 || $v3d15cdb3d4 ? $v808a08b1f9["default"] : "''" . $v808a08b1f9["default"] . "''"; $v2be87570d1 = is_numeric($v808a08b1f9["default"]) ? "((" . $v808a08b1f9["default"] . "))" : "(''" . $v808a08b1f9["default"] . "'')"; } $v3c76382d93 = "
			--update default constraint with new default value, but only if different
			DECLARE @drop_sql NVARCHAR(MAX) = '';
			DECLARE @add_sql NVARCHAR(MAX) = '';
			DECLARE @add_sql_active TINYINT = " . (isset($v4bfe0500a2) ? 1 : 0) . ";
			DECLARE @is_default_different TINYINT = 1;
			
			SELECT TOP 1 
				@drop_sql = 'ALTER TABLE $v769bf5da97 DROP CONSTRAINT ' + dc.name + ';', 
				@add_sql = 'ALTER TABLE $v769bf5da97 ADD CONSTRAINT ' + dc.name + ' DEFAULT $v4bfe0500a2 FOR [$v5e813b295b];',
				@is_default_different = CAST(
				   CASE
				        WHEN dc.definition != '$v2be87570d1'
				           THEN 1
				        ELSE 0
				   END AS bit)
			FROM sys.default_constraints dc 
			INNER JOIN sys.columns c ON c.default_object_id = dc.object_id
			INNER JOIN sys.objects o ON o.object_id = dc.parent_object_id
			WHERE dc.parent_object_id = OBJECT_ID('$pc661dc6b')" . ($pa51282b5 ? " AND SCHEMA_NAME(o.schema_id)='$pa51282b5'" : "") . " AND c.name = '$v5e813b295b';
			
			IF @add_sql = ''
				SELECT @add_sql = 'ALTER TABLE $v769bf5da97 ADD CONSTRAINT df__{$pc661dc6b}__{$v5e813b295b}__pf$v93ff269092 DEFAULT $v4bfe0500a2 FOR [$v5e813b295b];';
			
			IF (@drop_sql != '' AND @is_default_different = 1)
				EXEC sp_executeSQL @drop_sql;
			"; $v3c76382d93 .= "
			--drop unique key if exists
			WHILE 1=1
			BEGIN
				SELECT 
					@drop_sql = 'ALTER TABLE $v769bf5da97 DROP CONSTRAINT ' + c.name + ';'
				FROM sys.objects t
				INNER JOIN sys.indexes i ON t.object_id = i.object_id
				INNER JOIN sys.key_constraints c ON i.object_id = c.parent_object_id AND i.index_id = c.unique_index_id
				INNER JOIN sys.index_columns ic ON ic.object_id = t.object_id AND ic.index_id = i.index_id
				INNER JOIN sys.columns col ON ic.object_id = col.object_id AND ic.column_id = col.column_id AND col.name = '$v5e813b295b'
				WHERE i.is_unique = 1 AND t.type = 'U' AND c.type = 'UQ' AND t.name='$pc661dc6b'" . ($pa51282b5 ? " AND SCHEMA_NAME(t.schema_id)='$pa51282b5'" : "") . " AND t.is_ms_shipped <> 1;
				
				IF @@ROWCOUNT = 0 BREAK
				
				EXEC (@drop_sql);
			END
			"; if (!$v808a08b1f9["primary_key"] && isset($v808a08b1f9["null"]) && !$v808a08b1f9["null"] && isset($v808a08b1f9["default"])) { $pdbbe5385 = self::isReservedWord($v808a08b1f9["default"]); $v3d15cdb3d4 = self::isReservedWordFunction($v808a08b1f9["default"]); $v4bfe0500a2 = is_numeric($v808a08b1f9["default"]) || $pdbbe5385 || $v3d15cdb3d4 ? $v808a08b1f9["default"] : "'" . $v808a08b1f9["default"] . "'"; $v3c76382d93 .= "
			--if attribute is NOT NULL, update all null values with default values, before it change it to NOT NULL
			UPDATE $v769bf5da97 SET [$v5e813b295b] = $v4bfe0500a2 WHERE [$v5e813b295b] IS NULL;
			"; } $v3c76382d93 .= "
			--update column
			ALTER TABLE $v769bf5da97 ALTER COLUMN $v5eaddbc862 $v77cb07b555;
			"; $v3c76382d93 .= "
			IF (@add_sql_active = 1 AND @is_default_different = 1)
				EXEC sp_executeSQL @add_sql;
			"; if (!$v808a08b1f9["primary_key"] && $v808a08b1f9["unique"]) $v3c76382d93 .= "
			--add unique key
				ALTER TABLE $v769bf5da97 ADD CONSTRAINT uk__{$pc661dc6b}__{$v5e813b295b}__pf$v93ff269092 UNIQUE ($v5e813b295b);
			"; if ($v808a08b1f9["auto_increment"]) { } return $v3c76382d93; } public static function getRenameTableAttributeStatement($pc661dc6b, $pfc66218f, $paa23699f, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "EXEC sp_rename '$v769bf5da97.$pfc66218f', '$paa23699f', 'COLUMN' $v77cb07b555"; } public static function getDropTableAttributeStatement($pc661dc6b, $v97915b9670, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; $v3c76382d93 = "
			DECLARE @drop_sql NVARCHAR(MAX) = '';
			
			--drop default constraint if exists
			SELECT TOP 1 
				@drop_sql = 'ALTER TABLE $v769bf5da97 DROP CONSTRAINT ' + dc.name + ';'
			FROM sys.default_constraints dc 
			INNER JOIN sys.columns c ON c.default_object_id = dc.object_id
			INNER JOIN sys.objects o ON o.object_id = dc.parent_object_id
			WHERE dc.parent_object_id = OBJECT_ID('$pc661dc6b')" . ($pa51282b5 ? " AND SCHEMA_NAME(o.schema_id)='$pa51282b5'" : "") . " AND c.name = '$v97915b9670';
			
			IF (@drop_sql != '')
				EXEC sp_executeSQL @drop_sql;
			
			--drop unique key if exists
			DECLARE @drop_sql2 NVARCHAR(MAX) = '';
			
			WHILE 1=1
			BEGIN
				SELECT 
					@drop_sql2 = 'ALTER TABLE $v769bf5da97 DROP CONSTRAINT ' + c.name + ';'
				FROM sys.objects t
				INNER JOIN sys.indexes i ON t.object_id = i.object_id
				INNER JOIN sys.key_constraints c ON i.object_id = c.parent_object_id AND i.index_id = c.unique_index_id
				INNER JOIN sys.index_columns ic ON ic.object_id = t.object_id AND ic.index_id = i.index_id
				INNER JOIN sys.columns col ON ic.object_id = col.object_id AND ic.column_id = col.column_id AND col.name = '$v97915b9670'
				WHERE i.is_unique = 1 AND t.type = 'U' AND (c.type = 'UQ' OR i.type = 1 OR i.type = 2)" . ($pa51282b5 ? " AND SCHEMA_NAME(t.schema_id)='$pa51282b5'" : "") . " AND t.name='$pc661dc6b' AND t.is_ms_shipped <> 1;
				
				IF @@ROWCOUNT = 0 BREAK
				
				EXEC (@drop_sql2);
			END
			
			ALTER TABLE $v769bf5da97 DROP COLUMN [$v97915b9670] $v77cb07b555;"; return $v3c76382d93; } public static function getAddTablePrimaryKeysStatement($pc661dc6b, $pfdbbc383, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pa28639ac = str_replace(" ", "_", strtolower($pc661dc6b)) . "_pk"; $pfdbbc383 = is_array($pfdbbc383) ? $pfdbbc383 : array($pfdbbc383); $v325ffa1d87 = array(); foreach ($pfdbbc383 as $v1b0cfa478b) { if (is_array($v1b0cfa478b)) $v325ffa1d87[] = $v1b0cfa478b["name"]; else $v325ffa1d87[] = $v1b0cfa478b; } return "ALTER TABLE $v769bf5da97 ADD CONSTRAINT $pa28639ac PRIMARY KEY ([" . implode("], [", $v325ffa1d87) . "]) $v77cb07b555"; } public static function getDropTablePrimaryKeysStatement($pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; return "DECLARE @sql NVARCHAR(MAX);
			   SELECT @sql = 'ALTER TABLE $v769bf5da97 DROP CONSTRAINT ' + name + ' $v77cb07b555;'
			    FROM sys.key_constraints
			    WHERE [type] = 'PK'
			    AND [parent_object_id] = OBJECT_ID('$pc661dc6b')" . ($pa51282b5 ? " AND SCHEMA_NAME(t.schema_id)='$pa51282b5'" : "") . ";
			   EXEC sp_executeSQL @sql;"; } public static function getAddTableForeignKeyStatement($pc661dc6b, $pa7c14731, $v5d3813882f = false) { if ($pa7c14731 && ($pa7c14731["attribute"] || $pa7c14731["child_column"])) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v6912eee73f = isset($pa7c14731["on_delete"]) && $pa7c14731["on_delete"] ? "ON DELETE " . $pa7c14731["on_delete"] : ""; $pd7c78775 = isset($pa7c14731["on_update"]) && $pa7c14731["on_update"] ? "ON UPDATE " . $pa7c14731["on_update"] : ""; $v5e45ec9bb9 = $pa7c14731["attribute"] ? $pa7c14731["attribute"] : $pa7c14731["child_column"]; $pe3b2d7ad = $pa7c14731["reference_attribute"] ? $pa7c14731["reference_attribute"] : $pa7c14731["parent_column"]; $pf8a2ba68 = $pa7c14731["reference_table"] ? $pa7c14731["reference_table"] : $pa7c14731["parent_table"]; $pa28639ac = $pa7c14731["name"] ? $pa7c14731["name"] : $pa7c14731["constraint_name"]; $v13aa5fbc76 = $pa7c14731["replication_code"] ? $pa7c14731["replication_code"] : ""; $ped0a6251 = is_array($v5e45ec9bb9) ? $v5e45ec9bb9 : array($v5e45ec9bb9); $v9256fc8d51 = is_array($pe3b2d7ad) ? $pe3b2d7ad : array($pe3b2d7ad); $v45be4caf5b = self::getParsedTableEscapedSQL($pf8a2ba68, $v5d3813882f); return "ALTER TABLE $v769bf5da97 ADD " . ($pa28639ac ? "CONSTRAINT [" . $pa28639ac . "] " : "") . " FOREIGN KEY ([" . implode('], [', $ped0a6251) . "]) REFERENCES " . $v45be4caf5b . " ([" . implode('], [', $v9256fc8d51) . "]) $v6912eee73f $pd7c78775 $v13aa5fbc76 $pf64b5abc $v77cb07b555"; } } public static function getDropTableForeignKeysStatement($pc661dc6b, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "WHILE 1=1
BEGIN
  SELECT 
     @drop_sql = 'ALTER TABLE ' + isc.TABLE_NAME + ' DROP CONSTRAINT IF EXISTS ' + f.name + '$v77cb07b555;'
  FROM sys.foreign_keys AS f  
  INNER JOIN sys.foreign_key_columns AS fc ON f.object_id = fc.constraint_object_id
  INNER JOIN information_schema.COLUMNS AS isc ON isc.TABLE_CATALOG=DB_NAME() AND isc.TABLE_SCHEMA=SCHEMA_NAME(f.schema_id) AND OBJECT_ID(isc.TABLE_NAME)=f.parent_object_id AND isc.COLUMN_NAME=COL_NAME(fc.parent_object_id, fc.parent_column_id)
  WHERE " . ($pa51282b5 ? "isc.TABLE_SCHEMA='$pa51282b5' AND " : "") . "isc.TABLE_NAME='$pc661dc6b'
  
  IF @@ROWCOUNT = 0 BREAK

  EXEC (@drop_sql);
END"; } public static function getDropTableForeignConstraintStatement($pc661dc6b, $pa28639ac, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; return "IF (EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_CATALOG=DB_NAME()" . ($pa51282b5 ? " AND TABLE_SCHEMA = '$pa51282b5'" : "") . " AND TABLE_NAME='$pc661dc6b' AND TABLE_TYPE='BASE TABLE'))
BEGIN
  ALTER TABLE $v769bf5da97 DROP CONSTRAINT IF EXISTS [$pa28639ac];
END;"; } public static function getAddTableIndexStatement($pc661dc6b, $pfdbbc383, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pfdbbc383 = is_array($pfdbbc383) ? $pfdbbc383 : array($pfdbbc383); $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $v9f683c2c95 = "idx__{$pc661dc6b}__{$v97915b9670}__pf" . rand(); return "CREATE INDEX $v9f683c2c95 ON $v769bf5da97 ([" . implode("], [", $pfdbbc383) . "]) $v77cb07b555"; } public static function getLoadTableDataFromFileStatement($pf3dc0762, $pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v52fe4649ca = $v5d3813882f["fields_delimiter"] ? $v5d3813882f["fields_delimiter"] : "\t"; $v78ac4d6619 = $v5d3813882f["lines_delimiter"] ? $v5d3813882f["lines_delimiter"] : "\r\n"; return "BULK INSERT $v769bf5da97 FROM '$pf3dc0762' WITH (FIELDTERMINATOR = '$v52fe4649ca', ROWTERMINATOR = '$v78ac4d6619' $v77cb07b555)"; } public static function getShowCreateTableStatement($pc661dc6b, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; return "DECLARE  
	@object_name SYSNAME, 
	@object_id INT, 
	@SQL NVARCHAR(MAX);

SELECT 
	@object_name = '[' + OBJECT_SCHEMA_NAME(o.[object_id]) + '].[' + OBJECT_NAME([object_id]) + ']', 
	@object_id = [object_id]
FROM (SELECT [object_id] = OBJECT_ID('" . ($pa51282b5 ? "$pa51282b5." : "") . "$pc661dc6b', 'U')) o;

SELECT @SQL = 'CREATE TABLE ' + @object_name + ' (' + '\n' + STUFF((  
    SELECT '\n' + '    , [' + c.name + '] ' +   
        CASE WHEN c.is_computed = 1  
            THEN 'AS ' + OBJECT_DEFINITION(c.[object_id], c.column_id)  
            ELSE   
                CASE WHEN c.system_type_id != c.user_type_id   
                    THEN '[' + SCHEMA_NAME(tp.[schema_id]) + '].[' + tp.name + ']'   
                    ELSE '[' + UPPER(tp.name) + ']'   
                END  +   
                CASE   
                    WHEN tp.name IN ('varchar', 'char', 'varbinary', 'binary')  
                        THEN '(' + CASE WHEN c.max_length = -1   
                                        THEN 'MAX'   
                                        ELSE CAST(c.max_length AS VARCHAR(5))   
                                    END + ')'  
                    WHEN tp.name IN ('nvarchar', 'nchar')  
                        THEN '(' + CASE WHEN c.max_length = -1   
                                        THEN 'MAX'   
                                        ELSE CAST(c.max_length / 2 AS VARCHAR(5))   
                                    END + ')'  
                    WHEN tp.name IN ('datetime2', 'time2', 'datetimeoffset')   
                        THEN '(' + CAST(c.scale AS VARCHAR(5)) + ')'  
                    WHEN tp.name = 'decimal'  
                        THEN '(' + CAST(c.[precision] AS VARCHAR(5)) + ',' + CAST(c.scale AS VARCHAR(5)) + ')'  
                    ELSE ''  
                END +  
                CASE WHEN c.collation_name IS NOT NULL AND c.system_type_id = c.user_type_id   
                    THEN ' COLLATE ' + c.collation_name  
                    ELSE ''  
                END +  
                CASE WHEN c.is_nullable = 1   
                    THEN ' NULL'  
                    ELSE ' NOT NULL'  
                END +  
                CASE WHEN c.default_object_id != 0   
                    THEN ' CONSTRAINT [' + OBJECT_NAME(c.default_object_id) + ']' +   
                         ' DEFAULT ' + OBJECT_DEFINITION(c.default_object_id)  
                    ELSE ''  
                END +   
                CASE WHEN cc.[object_id] IS NOT NULL   
                    THEN ' CONSTRAINT [' + cc.name + '] CHECK ' + cc.[definition]  
                    ELSE ''  
                END +  
                CASE WHEN c.is_identity = 1   
                    THEN ' IDENTITY(' + CAST(IDENTITYPROPERTY(c.[object_id], 'SeedValue') AS VARCHAR(5)) + ',' +   
                                    CAST(IDENTITYPROPERTY(c.[object_id], 'IncrementValue') AS VARCHAR(5)) + ')'   
                    ELSE ''   
                END   
        END  
    FROM sys.columns c WITH(NOLOCK)  
    JOIN sys.types tp WITH(NOLOCK) ON c.user_type_id = tp.user_type_id  
    LEFT JOIN sys.check_constraints cc WITH(NOLOCK)   
         ON c.[object_id] = cc.parent_object_id   
        AND cc.parent_column_id = c.column_id  
    WHERE c.[object_id] = @object_id  
    ORDER BY c.column_id  
    FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 7, '      ') +   
    ISNULL((SELECT '  
    , CONSTRAINT [' + i.name + '] PRIMARY KEY ' +   
    CASE WHEN i.index_id = 1   
        THEN 'CLUSTERED'   
        ELSE 'NONCLUSTERED'   
    END +' (' + (  
    SELECT STUFF(CAST((  
        SELECT ', [' + COL_NAME(ic.[object_id], ic.column_id) + ']' +  
                CASE WHEN ic.is_descending_key = 1  
                    THEN ' DESC'  
                    ELSE ''  
                END  
        FROM sys.index_columns ic WITH(NOLOCK)  
        WHERE i.[object_id] = ic.[object_id]  
            AND i.index_id = ic.index_id  
        FOR XML PATH(N''), TYPE) AS NVARCHAR(MAX)), 1, 2, '')) + ')'  
    FROM sys.indexes i WITH(NOLOCK)  
    WHERE i.[object_id] = @object_id  
        AND i.is_primary_key = 1), '') + '\n' + ')';  
  
SELECT '$pc661dc6b' as 'Table', REPLACE(@SQL, '\n    , ', ',\n    ') as 'Create Table'"; } public static function getShowCreateViewStatement($pa36e00ea, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pa36e00ea, $v5d3813882f); $pa36e00ea = $pbec62cc6["name"]; return "SELECT '$pa36e00ea' as 'View', OBJECT_DEFINITION(OBJECT_ID('$pa36e00ea')) as 'Create View'"; } public static function getShowCreateTriggerStatement($v5ed3bce1d1, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($v5ed3bce1d1, $v5d3813882f); $v5ed3bce1d1 = $pbec62cc6["name"]; return "SELECT '$v5ed3bce1d1' as 'Trigger', OBJECT_DEFINITION(OBJECT_ID('$v5ed3bce1d1')) as 'SQL Original Statement'"; } public static function getShowCreateProcedureStatement($pbda8f16d, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pbda8f16d, $v5d3813882f); $pbda8f16d = $pbec62cc6["name"]; return "SELECT ROUTINE_NAME as 'Procedure', ROUTINE_DEFINITION as 'Create Procedure' FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE = 'PROCEDURE' and ROUTINE_NAME = '$pbda8f16d'"; } public static function getShowCreateFunctionStatement($v2f4e66e00a, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($v2f4e66e00a, $v5d3813882f); $v2f4e66e00a = $pbec62cc6["name"]; return "SELECT ROUTINE_NAME as 'Function', ROUTINE_DEFINITION as 'Create Function' FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE = 'FUNCTION' and ROUTINE_NAME = '$v2f4e66e00a'"; } public static function getShowCreateEventStatement($v76392c9cad, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($v76392c9cad, $v5d3813882f); $v76392c9cad = $pbec62cc6["name"]; return "SELECT '$v76392c9cad' as 'Event', OBJECT_DEFINITION(OBJECT_ID('$v76392c9cad')) as 'Create Event'"; } public static function getShowTablesStatement($pb67a2609, $v5d3813882f = false) { return str_replace("\n", " ", self::getTablesStatement($pb67a2609, $v5d3813882f)); } public static function getShowViewsStatement($pb67a2609, $v5d3813882f = false) { return "SELECT v.TABLE_NAME AS 'view_name' ". "FROM INFORMATION_SCHEMA.VIEWS v ". "INNER JOIN sys.objects o ON SCHEMA_NAME(o.schema_id)=v.TABLE_SCHEMA AND o.name=v.TABLE_NAME AND o.type='V' AND o.is_ms_shipped=0 ". "WHERE v.TABLE_CATALOG='$pb67a2609'"; } public static function getShowTriggersStatement($pb67a2609, $v5d3813882f = false) { return "SELECT trg.name AS 'Trigger', tab.TABLE_NAME AS 'table_name' ". "FROM sys.triggers trg ". "INNER JOIN INFORMATION_SCHEMA.TABLES tab ON tab.TABLE_NAME=OBJECT_NAME(trg.parent_id) AND tab.TABLE_SCHEMA=OBJECT_SCHEMA_NAME(trg.parent_id) ". "WHERE tab.TABLE_CATALOG='$pb67a2609' and trg.is_ms_shipped=0"; } public static function getShowTableColumnsStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { return str_replace("\n", " ", self::getTableFieldsStatement($pc661dc6b, $pb67a2609, $v5d3813882f)); } public static function getShowForeignKeysStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { return str_replace("\t", "", self::getForeignKeysStatement($pc661dc6b, $pb67a2609, $v5d3813882f)); } public static function getShowProceduresStatement($pb67a2609, $v5d3813882f = false) { return "SELECT r.ROUTINE_NAME as 'procedure_name' ". "FROM INFORMATION_SCHEMA.ROUTINES r ". "INNER JOIN sys.objects o ON  SCHEMA_NAME(o.schema_id)=r.ROUTINE_SCHEMA AND o.name=r.ROUTINE_NAME AND o.type='P' AND o.is_ms_shipped=0 ". "WHERE r.ROUTINE_TYPE='PROCEDURE' and r.ROUTINE_CATALOG='$pb67a2609'"; } public static function getShowFunctionsStatement($pb67a2609, $v5d3813882f = false) { return "SELECT r.ROUTINE_NAME as 'function_name' ". "FROM INFORMATION_SCHEMA.ROUTINES r ". "INNER JOIN sys.objects o ON  SCHEMA_NAME(o.schema_id)=r.ROUTINE_SCHEMA AND o.name=r.ROUTINE_NAME AND o.type in ('AF', 'FN', 'FS', 'FT', 'IF', 'TF') AND o.is_ms_shipped=0 ". "WHERE r.ROUTINE_TYPE='FUNCTION' and r.ROUTINE_CATALOG='$pb67a2609'"; } public static function getShowEventsStatement($pb67a2609, $v5d3813882f = false) { return "SELECT o.name AS 'event_name' ". "FROM sys.events e ". "INNER JOIN sys.objects o ON o.object_id=e.object_id AND o.is_ms_shipped=0 ". "INNER JOIN INFORMATION_SCHEMA.TABLES tab ON tab.TABLE_NAME=OBJECT_NAME(o.parent_object_id) AND tab.TABLE_SCHEMA=OBJECT_SCHEMA_NAME(o.parent_object_id)". "WHERE tab.TABLE_CATALOG='$pb67a2609'"; } public static function getSetupTransactionStatement($v5d3813882f = false) { return "SET TRANSACTION ISOLATION LEVEL REPEATABLE READ"; } public static function getStartTransactionStatement($v5d3813882f = false) { return "BEGIN TRANSACTION ". "/* [transaction_name] WITH MARK [description] */"; } public static function getCommitTransactionStatement($v5d3813882f = false) { return "COMMIT TRANSACTION"; } public static function getStartDisableAutocommitStatement($v5d3813882f = false) { return "SET IMPLICIT_TRANSACTIONS ON;"; } public static function getEndDisableAutocommitStatement($v5d3813882f = false) { return "SET IMPLICIT_TRANSACTIONS OFF;"; } public static function getStartLockTableWriteStatement($pc661dc6b, $v5d3813882f = false) { return null; } public static function getStartLockTableReadStatement($pc661dc6b, $v5d3813882f = false) { return null; } public static function getEndLockTableStatement($v5d3813882f = false) { return null; } public static function getStartDisableKeysStatement($pc661dc6b, $v5d3813882f = false) { return null; } public static function getEndDisableKeysStatement($pc661dc6b, $v5d3813882f = false) { return null; } public static function getDropTriggerStatement($v5ed3bce1d1, $v5d3813882f = false) { $v6caad9d962 = self::getParsedTableEscapedSQL($v5ed3bce1d1, $v5d3813882f); return "DROP TRIGGER IF EXISTS $v6caad9d962;"; } public static function getDropProcedureStatement($pbda8f16d, $v5d3813882f = false) { $v7c7c261ebc = self::getParsedTableEscapedSQL($pbda8f16d, $v5d3813882f); return "DROP PROCEDURE IF EXISTS $v7c7c261ebc;"; } public static function getDropFunctionStatement($v2f4e66e00a, $v5d3813882f = false) { $v7992b8f618 = self::getParsedTableEscapedSQL($v2f4e66e00a, $v5d3813882f); return "DROP FUNCTION IF EXISTS $v7992b8f618;"; } public static function getDropEventStatement($v76392c9cad, $v5d3813882f = false) { $pdea5ed12 = self::getParsedTableEscapedSQL($v76392c9cad, $v5d3813882f); return "DROP EVENT IF EXISTS $pdea5ed12;"; } public static function getDropViewStatement($pa36e00ea, $v5d3813882f = false) { $pe8a320e9 = self::getParsedTableEscapedSQL($pa36e00ea, $v5d3813882f); return "DROP VIEW IF EXISTS $pe8a320e9;"; } } ?>
