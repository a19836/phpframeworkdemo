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

trait PostgresDBStatement { public static function getCreateDBStatement($pb67a2609, $v5d3813882f = false) { $v3c76382d93 = "CREATE DATABASE \"" . $pb67a2609 . "\""; if ($v5d3813882f["encoding"] || $v5d3813882f["collation"]) { $v3c76382d93 .= " WITH"; if ($v5d3813882f["encoding"]) $v3c76382d93 .= " ENCODING '" . strtoupper($v5d3813882f["encoding"]) . "'"; if ($v5d3813882f["collation"]) $v3c76382d93 .= " LC_COLLATE '" . strtoupper($v5d3813882f["collation"]) . "'"; } return $v3c76382d93; } public static function getDropDatabaseStatement($pb67a2609, $v5d3813882f = false) { return "/*!40000 DROP DATABASE IF EXISTS \"$pb67a2609\" */;"; } public static function getSelectedDBStatement($v5d3813882f = false) { return "SELECT current_database() AS db"; } public static function getDBsStatement($v5d3813882f = false) { return "SELECT datname AS name FROM pg_database"; } public static function getTablesStatement($pb67a2609 = false, $v5d3813882f = false) { $pa51282b5 = $v5d3813882f && $v5d3813882f["schema"] ? $v5d3813882f["schema"] : null; $v3c76382d93 = "SELECT 
				t.table_name AS \"table_name\",
				t.table_schema AS \"table_schema\"
			FROM information_schema.tables t
			WHERE t.table_catalog=" . ($pb67a2609 ? "'$pb67a2609'" : "current_database()") . " AND " . ($pa51282b5 ? "t.table_schema='$pa51282b5'" : "t.table_schema!='pg_catalog' AND t.table_schema!='information_schema'") . " AND t.table_type='BASE TABLE'
			ORDER BY t.table_name ASC"; return $v3c76382d93; } public static function getTableFieldsStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; $pd5ae1c40 = $pbec62cc6["database"]; $pb67a2609 = $pb67a2609 ? $pb67a2609 : $pd5ae1c40; $v3c76382d93 = "
		SELECT 
			isc.column_name, 
			isc.column_default, 
			isc.is_nullable, 
			isc.data_type, 
			isc.character_maximum_length, 
			isc.numeric_precision, 
			isc.character_set_name, 
			isc.character_set_schema, 
			isc.collation_name, 
			isc.collation_schema, 
			ARRAY_AGG(pkt.indisprimary) AS is_primary,
			ARRAY_AGG(pkt.indisunique) AS is_unique,
			checkconstraint.check_constraint_name,
			checkconstraint.check_constraint_value,
			uk.constraint_name AS unique_constraint_name,
			dt.column_comment
		FROM information_schema.columns isc 
		LEFT JOIN (
			SELECT               
			    pg_attribute.attname AS attname, 
			    format_type(pg_attribute.atttypid, pg_attribute.atttypmod) AS type,
	    		    pg_index.indisunique, 
	    		    pg_index.indisprimary, 
	    		    pg_index.indisexclusion, 
	    		    pg_index.indimmediate, 
	    		    pg_index.indisclustered, 
	    		    pg_index.indisvalid, 
	    		    pg_index.indcheckxmin, 
	    		    pg_index.indisready, 
	    		    pg_index.indislive
			 FROM pg_index, pg_class, pg_attribute, pg_namespace 
			 WHERE 
			     pg_class.oid = '$pc661dc6b'::regclass AND 
			     indrelid = pg_class.oid AND 
			     " . ($pa51282b5 ? "nspname = '$pa51282b5'" : "nspname NOT LIKE 'pg%' AND nspname <> 'information_schema'") . " AND 
			     pg_class.relnamespace = pg_namespace.oid AND 
			     pg_attribute.attrelid = pg_class.oid AND 
			     pg_attribute.attnum = any(pg_index.indkey)
		) pkt ON pkt.attname = isc.column_name and pkt.type = isc.data_type
		LEFT JOIN (
			SELECT 
			  column_name AS cn, 
			  consrc AS check_constraint_value,
			  constraint_name AS check_constraint_name
			FROM information_schema.constraint_column_usage
			INNER JOIN pg_constraint on conname = constraint_name and contype='c'
			WHERE table_catalog = " . ($pb67a2609 ? "'$pb67a2609'" : "current_database()") . " and table_name = '$pc661dc6b'
		) checkconstraint ON checkconstraint.cn = isc.column_name
		LEFT JOIN (
			SELECT 
			  c.column_name cn, 
			  pgd.description column_comment
			FROM pg_catalog.pg_statio_all_tables AS st
			  INNER JOIN pg_catalog.pg_description pgd on (pgd.objoid=st.relid)
			  INNER JOIN information_schema.columns c on (pgd.objsubid=c.ordinal_position and c.table_schema=st.schemaname and c.table_name=st.relname)
			WHERE " . ($pa51282b5 ? "st.schemaname='$pa51282b5'": "st.schemaname NOT LIKE 'pg%' AND st.schemaname <> 'information_schema'") . " and st.relname='$pc661dc6b'
		) dt ON dt.cn = isc.column_name
		LEFT JOIN information_schema.table_constraints tc ON tc.table_schema=isc.table_schema AND tc.table_name=isc.table_name AND constraint_type = 'UNIQUE'
		LEFT JOIN (
			SELECT c.conname AS constraint_name, a.attname AS column, '$pc661dc6b' AS table
			FROM pg_constraint c
			INNER JOIN  (
				SELECT attname, array_agg(attnum::int) AS attkey
				FROM pg_attribute
				WHERE attrelid = '$pc661dc6b'::regclass
				GROUP BY attname
			) a ON c.conkey::int[] <@ a.attkey AND c.conkey::int[] @> a.attkey
			WHERE c.contype='u' AND c.conrelid='$pc661dc6b'::regclass
		) uk ON uk.table=isc.table_name AND uk.column=isc.column_name
		WHERE isc.table_catalog=" . ($pb67a2609 ? "'$pb67a2609'" : "current_database()") . " and " . ($pa51282b5 ? "isc.table_schema = '$pa51282b5'" : "isc.table_schema NOT LIKE 'pg%' AND isc.table_schema <> 'information_schema'") . " and isc.table_name='$pc661dc6b'
		GROUP BY 
			isc.column_name, 
			isc.column_default, 
			isc.is_nullable, 
			isc.data_type, 
			isc.character_maximum_length, 
			isc.numeric_precision, 
			isc.character_set_name, 
			isc.character_set_schema, 
			isc.collation_name, 
			isc.collation_schema, 
			checkconstraint.check_constraint_name,
			checkconstraint.check_constraint_value,
			uk.constraint_name,
			dt.column_comment"; return $v3c76382d93; } public static function getForeignKeysStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; $pd5ae1c40 = $pbec62cc6["database"]; $pb67a2609 = $pb67a2609 ? $pb67a2609 : $pd5ae1c40; $v3c76382d93 = "SELECT 
			  col.table_catalog AS catalog, 
			  col.table_schema AS schema, 
			  col.table_name AS child_table, 
			  --col.column_name AS child_column,
			  att2.attname AS child_column, 
			  cl.relname AS parent_table, 
			  att.attname AS parent_column,
			  tc.constraint_name,
			  con.confupdtype,
			  CASE con.confupdtype
		            WHEN 'a' THEN 'NO ACTION '
		            WHEN 'r' THEN 'RESTRICT '
		            WHEN 'c' THEN 'CASCADE '
		            WHEN 'n' THEN 'SET NULL '
		            ELSE 'SET DEFAULT '
		           END AS on_update,
			  con.confdeltype,
			  CASE con.confdeltype
		            WHEN 'a' THEN 'NO ACTION '
		            WHEN 'r' THEN 'RESTRICT '
		            WHEN 'c' THEN 'CASCADE '
		            WHEN 'n' THEN 'SET NULL '
		            ELSE 'SET DEFAULT '
		           END AS on_delete,
			  pg_get_constraintdef(pgc.oid, true) AS constraint_def
			FROM (
				SELECT 
				  unnest(con1.conkey) AS parent, 
				  unnest(con1.confkey) AS child, 
				  con1.confrelid, 
				  con1.conrelid,
				  ns.nspname,
				  cl.relname,
				  con1.confupdtype,
				  con1.confdeltype
				FROM pg_class cl
				INNER JOIN pg_namespace ns ON cl.relnamespace = ns.oid" . ($pa51282b5 ? " AND ns.nspname='$pa51282b5'" : "") . "
				INNER JOIN pg_constraint con1 ON con1.conrelid = cl.oid AND con1.contype = 'f'
				WHERE cl.relname = '$pc661dc6b'
			) con
			INNER JOIN pg_attribute att ON att.attrelid = con.confrelid AND att.attnum = con.child
			INNER JOIN pg_class cl ON cl.oid = con.confrelid
			INNER JOIN pg_attribute att2 ON att2.attrelid = con.conrelid AND att2.attnum = con.parent
			INNER JOIN pg_namespace ns ON cl.relnamespace = ns.oid AND ns.nspname=con.nspname
			INNER JOIN information_schema.table_constraints tc ON ns.nspname = tc.constraint_schema AND tc.constraint_schema=con.nspname AND tc.table_name=con.relname AND tc.constraint_type = 'FOREIGN KEY'
			INNER JOIN pg_constraint pgc ON pgc.conname = tc.constraint_name AND pgc.connamespace = ns.oid AND pgc.conrelid = con.conrelid AND pgc.contype = 'f'
			INNER JOIN information_schema.columns col ON col.table_schema = tc.table_schema AND col.table_name = tc.table_name AND col.ordinal_position=ANY(pgc.conkey);"; return $v3c76382d93; } public static function getCreateTableStatement($v87a92bb1ad, $v5d3813882f = false) { $v8c5df8072b = $v87a92bb1ad["table_name"] ? $v87a92bb1ad["table_name"] : $v87a92bb1ad["name"]; $v70378781f0 = $v87a92bb1ad["table_storage_engine"] ? $v87a92bb1ad["table_storage_engine"] : $v87a92bb1ad["engine"]; $pfdbbc383 = $v87a92bb1ad["attributes"]; $pf601a685 = ""; if (!empty($v0aaaac50d5) || !empty($pe8765fe8) || !empty($v70378781f0)) { $pf601a685 = "WITH "; $pf601a685 .= !empty($v70378781f0) ? "($v70378781f0) " : ""; } $pe83acfb1 = self::getParsedTableEscapedSQL($v8c5df8072b, $v5d3813882f); $v3c76382d93 = "CREATE TABLE $pe83acfb1 (\n"; if (is_array($pfdbbc383)) { $v890f3625e5 = array(); foreach ($pfdbbc383 as $v97915b9670) { $v5e813b295b = $v97915b9670["name"]; $v597dd8d456 = $v97915b9670["primary_key"] == "1" || strtolower($v97915b9670["primary_key"]) == "true"; if ($v597dd8d456) $v890f3625e5[] = $v5e813b295b; $peda0af84 = self::getCreateTableAttributeStatement($v97915b9670); $v3c76382d93 .= "  " . $peda0af84 . ",\n"; } if ($v890f3625e5) { $v3c76382d93 .= "  PRIMARY KEY (\"" . implode("\", \"", $v890f3625e5) . "\")"; } else { $v3c76382d93 = substr($v3c76382d93, 0, strlen($v3c76382d93) - 2); } } if (is_array($v87a92bb1ad["unique_keys"])) foreach ($v87a92bb1ad["unique_keys"] as $pbfa01ed1) if ($pbfa01ed1["attribute"]) { $ped0a6251 = is_array($pbfa01ed1["attribute"]) ? $pbfa01ed1["attribute"] : array($pbfa01ed1["attribute"]); $v3c76382d93 .= ",   " . ($pbfa01ed1["name"] ? "CONSTRAINT " . $pbfa01ed1["name"] . " " : "") . " UNIQUE (\"" . implode('", "', $ped0a6251) . "\")"; } if (is_array($v87a92bb1ad["foreign_keys"])) foreach ($v87a92bb1ad["foreign_keys"] as $pbfa01ed1) if ($pbfa01ed1["attribute"] || $pbfa01ed1["child_column"]) { $v6912eee73f = isset($pbfa01ed1["on_delete"]) && $pbfa01ed1["on_delete"] ? "ON DELETE " . $pbfa01ed1["on_delete"] : ""; $pd7c78775 = isset($pbfa01ed1["on_update"]) && $pbfa01ed1["on_update"] ? "ON UPDATE " . $pbfa01ed1["on_update"] : ""; $v5e45ec9bb9 = $pbfa01ed1["attribute"] ? $pbfa01ed1["attribute"] : $pbfa01ed1["child_column"]; $pe3b2d7ad = $pbfa01ed1["reference_attribute"] ? $pbfa01ed1["reference_attribute"] : $pbfa01ed1["parent_column"]; $pf8a2ba68 = $pbfa01ed1["reference_table"] ? $pbfa01ed1["reference_table"] : $pbfa01ed1["parent_table"]; $pa28639ac = $pbfa01ed1["name"] ? $pbfa01ed1["name"] : $pbfa01ed1["constraint_name"]; $ped0a6251 = is_array($v5e45ec9bb9) ? $v5e45ec9bb9 : array($v5e45ec9bb9); $v9256fc8d51 = is_array($pe3b2d7ad) ? $pe3b2d7ad : array($pe3b2d7ad); $v45be4caf5b = self::getParsedTableEscapedSQL($pf8a2ba68, $v5d3813882f); $v3c76382d93 .= ",\n   " . ($pa28639ac ? "CONSTRAINT " . $pa28639ac . " " : "") . " FOREIGN KEY (\"" . implode('", "', $ped0a6251) . "\") REFERENCES " . $v45be4caf5b . " (\"" . implode('", "', $v9256fc8d51) . "\") $v6912eee73f $pd7c78775"; } $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v3c76382d93 .= "\n) $pf601a685 $v77cb07b555"; if (is_array($v87a92bb1ad["index_keys"])) foreach ($v87a92bb1ad["index_keys"] as $pbfa01ed1) if ($pbfa01ed1["attribute"]) { $v3fb9f41470 = isset($pbfa01ed1["type"]) && $pbfa01ed1["type"] ? "USING " . $pbfa01ed1["type"] : ""; $ped0a6251 = is_array($pbfa01ed1["attribute"]) ? $pbfa01ed1["attribute"] : array($pbfa01ed1["attribute"]); $v3c76382d93 .= "\nCREATE INDEX " . $pbfa01ed1["name"] . " ON $pe83acfb1 $v3fb9f41470 (\"" . implode('", "', $ped0a6251) . "\")"; } return trim($v3c76382d93); } public static function getCreateTableAttributeStatement($v261e7b366d, $v5d3813882f = false, &$v808a08b1f9 = array()) { $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $name = $v261e7b366d["name"]; $type = self::convertColumnTypeToDB($v261e7b366d["type"], $pe1390784); $length = $v261e7b366d["length"]; $pk = $v261e7b366d["primary_key"] == "1" || strtolower($v261e7b366d["primary_key"]) == "true"; $auto_increment = $v261e7b366d["auto_increment"] == "1" || strtolower($v261e7b366d["auto_increment"]) == "true"; $unsigned = $v261e7b366d["unsigned"] == "1" || strtolower($v261e7b366d["unsigned"]) == "true"; $unique = $v261e7b366d["unique"] == "1" || strtolower($v261e7b366d["unique"]) == "true"; $null = $v261e7b366d["null"] == "1" || strtolower($v261e7b366d["null"]) == "true"; $default = $v261e7b366d["default"]; $default_type = $v261e7b366d["default_type"]; $extra = $v261e7b366d["extra"]; $collation = $v261e7b366d["collation"]; if ($pe1390784) foreach ($pe1390784 as $pe5c5e2fe => $v956913c90f) if ($pe5c5e2fe != "charset") eval("\$$pe5c5e2fe = \$v956913c90f;"); if ($auto_increment || stripos($extra, "auto_increment") !== false) { $extra = str_ireplace("auto_increment", "", $extra); $auto_increment = true; if (!$v5d3813882f["do_not_convert_to_serial"]) $type = $type == "bigint" ? "bigserial" : ($type == "smallint" ? "smallserial" : "serial"); } $length = !self::ignoreColumnTypeDBProp($type, "length") && is_numeric($length) ? $length : null; $unsigned = !self::ignoreColumnTypeDBProp($type, "unsigned") ? $unsigned : false; $unique = !self::ignoreColumnTypeDBProp($type, "unique") ? $unique : false; $null = !self::ignoreColumnTypeDBProp($type, "null") ? $null : null; $default = !self::ignoreColumnTypeDBProp($type, "default") ? $default : null; $collation = !self::ignoreColumnTypeDBProp($type, "collation") ? $collation : null; $pdbbe5385 = self::isReservedWord($default); $v3d15cdb3d4 = self::isReservedWordFunction($default); $v23ed8083a0 = in_array($type, self::getDBColumnNumericTypes()); $default = isset($default) && $v23ed8083a0 && !is_numeric($default) && !$pdbbe5385 && !$v3d15cdb3d4 ? null : $default; if (!isset($default) && isset($null) && !$null && !$pk) { $default = self::getDefaultValueForColumnType($type); $pdbbe5385 = self::isReservedWord($default); $v3d15cdb3d4 = self::isReservedWordFunction($default); } $default_type = $default_type ? $default_type : (isset($default) && (is_numeric($default) || $pdbbe5385 || $v3d15cdb3d4) ? "numeric" : "string"); $v808a08b1f9["type"] = $type; $v808a08b1f9["primary_key"] = $pk; $v808a08b1f9["length"] = $length; $v808a08b1f9["auto_increment"] = $auto_increment; $v808a08b1f9["unsigned"] = $unsigned; $v808a08b1f9["unique"] = $unique; $v808a08b1f9["null"] = $null; $v808a08b1f9["default"] = $default; $v808a08b1f9["collation"] = $collation; $length = $length && !$v5d3813882f["ignore_length"] ? "($length)" : ""; $unsigned = $unsigned && !$v5d3813882f["ignore_unsigned"] ? "CHECK (\"$name\" > 0)" : ""; $unique = $unique && !$v5d3813882f["ignore_unique"] && !$pk ? "UNIQUE" : ""; $null = isset($null) && !$v5d3813882f["ignore_null"] ? ($null ? "NULL" : "NOT NULL") : ""; $default = isset($default) && !$v5d3813882f["ignore_default"] && !$auto_increment ? "DEFAULT " . ($default_type == "string" ? "'$default'" : $default) : ""; $collation = !empty($collation) && !$v5d3813882f["ignore_collation"] ? "COLLATE '$collation'" : ""; $extra = !$v5d3813882f["ignore_extra"] ? $extra : ""; $v77cb07b555 = $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; if ($unsigned && preg_match("/CHECK\s*\(+\s*\"?$name\"?\s*>/i", $extra)) $unsigned = ""; return trim(preg_replace("/[ ]+/", " ", "\"$name\" $type{$length} $unique $null $charset $collation $default $comment $extra $unsigned $v77cb07b555")); } public static function getRenameTableStatement($pe8f357f7, $v38abe7147f, $v5d3813882f = false) { $v93973610e9 = self::getParsedTableEscapedSQL($pe8f357f7, $v5d3813882f); $pf87d7c43 = self::getParsedTableEscapedSQL($v38abe7147f, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "RENAME TABLE $v93973610e9 RENAME TO $pf87d7c43 $v77cb07b555"; } public static function getDropTableStatement($pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "DROP TABLE IF EXISTS $v769bf5da97 $v77cb07b555"; } public static function getDropTableCascadeStatement($pc661dc6b, $v5d3813882f = false) { $v5d3813882f["suffix"] = "CASCADE " . $v5d3813882f["suffix"]; return self::getDropTableStatement($pc661dc6b, $v5d3813882f); } public static function getAddTableAttributeStatement($pc661dc6b, $v261e7b366d, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v3c76382d93 = self::getCreateTableAttributeStatement($v261e7b366d); return "ALTER TABLE $v769bf5da97 ADD COLUMN $v3c76382d93 $v77cb07b555"; } public static function getModifyTableAttributeStatement($pc661dc6b, $v261e7b366d, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; $v5eaddbc862 = self::getCreateTableAttributeStatement($v261e7b366d, array("do_not_convert_to_serial" => true, "ignore_null" => true, "ignore_default" => true, "ignore_unique" => true, "ignore_unsigned" => true, "ignore_auto_increment" => true), $v808a08b1f9); if ($v808a08b1f9["primary_key"]) $v5eaddbc862 = self::getCreateTableAttributeStatement($v261e7b366d, array("do_not_convert_to_serial" => true, "ignore_null" => true, "ignore_default" => true, "ignore_unique" => true, "ignore_unsigned" => true, "ignore_auto_increment" => true, "ignore_extra" => true), $v808a08b1f9); $pbd1bc7b0 = strpos(trim($v5eaddbc862), '"', 1); $v5eaddbc862 = substr($v5eaddbc862, 0, $pbd1bc7b0 + 1) . " TYPE" . substr($v5eaddbc862, $pbd1bc7b0 + 1); $v5e813b295b = $v261e7b366d["name"]; $v3c76382d93 = "--remove default value
			ALTER TABLE $v769bf5da97 ALTER COLUMN \"$v5e813b295b\" DROP DEFAULT;
			"; $v3c76382d93 .= "
			--remove unique constraint
			DO $$
			DECLARE myvar varchar = '';
			BEGIN
			    SELECT concat('ALTER TABLE $v769bf5da97 DROP CONSTRAINT ', tc.constraint_name) INTO myvar
				FROM information_schema.table_constraints tc
				INNER JOIN pg_namespace nsp on nsp.nspname = tc.constraint_schema
				INNER JOIN pg_constraint pgc on pgc.conname = tc.constraint_name and pgc.connamespace = nsp.oid and pgc.contype = 'u'
				INNER JOIN information_schema.columns col on col.table_schema = tc.table_schema and col.table_name = tc.table_name and col.ordinal_position=ANY(pgc.conkey)
				WHERE " . ($pa51282b5 ? "tc.constraint_schema='$pa51282b5' AND " : "") . "tc.table_name='$pc661dc6b' AND col.column_name='$v5e813b295b' AND tc.constraint_type = 'UNIQUE';
			    
			    IF myvar != '' THEN
			    	 --raise notice 'drop constraint: %', myvar;
		   	 	 EXECUTE myvar;
		   	    END IF;
			END $$;
			"; $v3c76382d93 .= "
			--modify attribute with new type
			ALTER TABLE $v769bf5da97 ALTER COLUMN $v5eaddbc862 $v77cb07b555;
			"; if (isset($v808a08b1f9["null"])) { if (!$v808a08b1f9["null"]) { if (!$v808a08b1f9["primary_key"] && isset($v808a08b1f9["default"])) { $pdbbe5385 = self::isReservedWord($v808a08b1f9["default"]); $v3d15cdb3d4 = self::isReservedWordFunction($v808a08b1f9["default"]); $v4bfe0500a2 = is_numeric($v808a08b1f9["default"]) || $pdbbe5385 || $v3d15cdb3d4 ? $v808a08b1f9["default"] : "'" . $v808a08b1f9["default"] . "'"; $v3c76382d93 .= "
			--set attribute values with default value for NULL values
			UPDATE $v769bf5da97 SET \"$v5e813b295b\" = $v4bfe0500a2 WHERE \"$v5e813b295b\" IS NULL;
			"; } $v3c76382d93 .= "
			--set attribute to NOT NULL
			ALTER TABLE $v769bf5da97 ALTER COLUMN \"$v5e813b295b\" SET NOT NULL;
			"; } else $v3c76382d93 .= "
			--set attribute to NULL
			ALTER TABLE $v769bf5da97 ALTER COLUMN \"$v5e813b295b\" DROP NOT NULL;
			"; } $v6a54e01756 = isset($v808a08b1f9["default"]) && $v261e7b366d["has_default"]; if ($v808a08b1f9["auto_increment"]) { $v3c76382d93 .= "
			--set auto increment constraint for PK: $v5e813b295b
			DO $$
			DECLARE myvar varchar = '';
			DECLARE sequence_name varchar = '';
			DECLARE max_value bigint = 0;
			BEGIN
				SELECT pg_get_serial_sequence('$v769bf5da97', '$v5e813b295b') INTO sequence_name;
				
				IF sequence_name = '' OR sequence_name IS NULL THEN
			   	 	SELECT max(\"$v5e813b295b\") INTO max_value FROM $v769bf5da97;
			   	 	
			   	 	IF max_value > 0 THEN
			   	 		max_value = max_value + 1;
			   	 	END IF;
			   	 	
			   	 	sequence_name = '{$pc661dc6b}_{$v5e813b295b}_seq';
					myvar = concat('CREATE SEQUENCE ', sequence_name, ' START ', max_value, ' OWNED BY $v769bf5da97.\"$v5e813b295b\";');
		   	     ELSE
		   	     	myvar = concat('ALTER SEQUENCE ', sequence_name, ' OWNED BY $v769bf5da97.\"$v5e813b295b\";');
		   	     END IF;
		   	     
		   	     --raise notice 'create or modify sequence: %', myvar;
		   	     EXECUTE myvar;
		   	     
		   	     myvar = CONCAT('ALTER TABLE $v769bf5da97 ALTER COLUMN \"$v5e813b295b\" SET DEFAULT nextval(''', sequence_name, ''');');
				--raise notice 'set sequence to table: %', myvar;
		   	     EXECUTE myvar;
			END $$;
			"; } else if ($v6a54e01756) { $pdbbe5385 = self::isReservedWord($v808a08b1f9["default"]); $v3d15cdb3d4 = self::isReservedWordFunction($v808a08b1f9["default"]); $v4bfe0500a2 = is_numeric($v808a08b1f9["default"]) || $pdbbe5385 || $v3d15cdb3d4 ? $v808a08b1f9["default"] : "'" . $v808a08b1f9["default"] . "'"; $v3c76382d93 .= "
			--set default value
			ALTER TABLE $v769bf5da97 ALTER COLUMN \"$v5e813b295b\" SET DEFAULT $v4bfe0500a2;
			"; if (!$v808a08b1f9["auto_increment"]) { $v3c76382d93 .= "
				--set auto increment constraint for PK: $v5e813b295b
				DO $$
				DECLARE myvar varchar = '';
				DECLARE sequence_name varchar = '';
				DECLARE max_value bigint = 0;
				BEGIN
					SELECT pg_get_serial_sequence('$v769bf5da97', '$v5e813b295b') INTO sequence_name;
					
					IF sequence_name != '' AND sequence_name IS NOT NULL THEN
			   	     	myvar = concat('DROP SEQUENCE IF EXISTS ', sequence_name, ' CASCADE;');
				   	     --raise notice 'drop sequence: %', myvar;
				   	     EXECUTE myvar;
			   	     END IF;
				END $$;
				"; } } $v3c76382d93 .= "
			--add and remove unsigned constraint
			DO $$
			DECLARE myvar varchar = '';
			DECLARE constraint_name varchar = '';
			DECLARE add_constraint smallint = " . ($v808a08b1f9["unsigned"] ? 1 : 0) . ";
			BEGIN
				SELECT 
					--tc.table_schema,
					--tc.table_name,
					--string_agg(col.column_name, ', ') AS columns,
					--cc.check_clause,
					tc.constraint_name INTO constraint_name
				FROM information_schema.table_constraints tc
				INNER JOIN information_schema.check_constraints cc on tc.constraint_schema = cc.constraint_schema and tc.constraint_name = cc.constraint_name
				INNER JOIN pg_namespace nsp on nsp.nspname = cc.constraint_schema
				INNER JOIN pg_constraint pgc on pgc.conname = cc.constraint_name and pgc.connamespace = nsp.oid and pgc.contype = 'c'
				INNER JOIN information_schema.columns col on col.table_schema = tc.table_schema and col.table_name = tc.table_name and col.ordinal_position=ANY(pgc.conkey)
				WHERE " . ($pa51282b5 ? "tc.constraint_schema='$pa51282b5' AND " : "") . "tc.table_name='$pc661dc6b' AND col.column_name='$v5e813b295b' AND cc.check_clause = CONCAT('((', col.column_name, ' > 0))')
				GROUP BY tc.table_schema, tc.table_name, tc.constraint_name, cc.check_clause
				ORDER BY tc.table_schema, tc.table_name
				LIMIT 1;
				
				--raise notice 'constraint_name: %', constraint_name;
				
				IF add_constraint <> 1 AND constraint_name != '' AND constraint_name IS NOT NULL THEN
					myvar = CONCAT('ALTER TABLE $v769bf5da97 DROP CONSTRAINT ' , constraint_name, ';');
					--raise notice 'drop check constraint: %', myvar;
					EXECUTE myvar;
				END IF;
				
				IF add_constraint = 1 AND (constraint_name = '' OR constraint_name IS NULL) THEN
					myvar = CONCAT('ALTER TABLE $v769bf5da97 ADD CONSTRAINT {$pc661dc6b}_{$v5e813b295b}_check CHECK ($v5e813b295b > 0);');
					--raise notice 'add check constraint: %', myvar;
					EXECUTE myvar;
				END IF;
			END $$;
			"; if (!$v808a08b1f9["primary_key"] && $v808a08b1f9["unique"]) { $v93ff269092 = rand(); $v3c76382d93 .= "
			--add unique constraint
			ALTER TABLE $v769bf5da97 ADD CONSTRAINT uk__{$pc661dc6b}__{$v5e813b295b}__pf$v93ff269092 UNIQUE ($v5e813b295b);
			"; } return $v3c76382d93; } public static function getRenameTableAttributeStatement($pc661dc6b, $pfc66218f, $paa23699f, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "ALTER TABLE $v769bf5da97 RENAME COLUMN \"$pfc66218f\" TO \"$paa23699f\" $v77cb07b555"; } public static function getDropTableAttributeStatement($pc661dc6b, $v97915b9670, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "ALTER TABLE $v769bf5da97 DROP COLUMN \"$v97915b9670\" $v77cb07b555"; } public static function getAddTablePrimaryKeysStatement($pc661dc6b, $pfdbbc383, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pa28639ac = str_replace(" ", "_", strtolower($pc661dc6b)) . "_pk"; $pfdbbc383 = is_array($pfdbbc383) ? $pfdbbc383 : array($pfdbbc383); $v325ffa1d87 = array(); foreach ($pfdbbc383 as $v1b0cfa478b) { if (is_array($v1b0cfa478b)) $v325ffa1d87[] = $v1b0cfa478b["name"]; else $v325ffa1d87[] = $v1b0cfa478b; } $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; $v3c76382d93 = "ALTER TABLE $v769bf5da97 ADD PRIMARY KEY (\"" . implode("\", \"", $v325ffa1d87) . "\") $v77cb07b555;
		"; foreach ($pfdbbc383 as $v1b0cfa478b) if (is_array($v1b0cfa478b)) { self::getCreateTableAttributeStatement($v1b0cfa478b, null, $v808a08b1f9); if ($v808a08b1f9["auto_increment"]) { $v5e813b295b = $v1b0cfa478b["name"]; $v3c76382d93 .= "
					--set auto increment constraint for PK: $v5e813b295b
					DO $$
					DECLARE myvar varchar = '';
					DECLARE sequence_name varchar = '';
					DECLARE max_value bigint = 0;
					BEGIN
						SELECT pg_get_serial_sequence('$v769bf5da97', '$v5e813b295b') INTO sequence_name;
						
						IF sequence_name = '' OR sequence_name IS NULL THEN
					   	 	SELECT max(\"$v5e813b295b\") INTO max_value FROM $v769bf5da97;
					   	 	
					   	 	IF max_value > 0 THEN
					   	 		max_value = max_value + 1;
					   	 	END IF;
					   	 	
					   	 	sequence_name = '{$pc661dc6b}_{$v5e813b295b}_seq';
							myvar = concat('CREATE SEQUENCE ', sequence_name, ' START ', max_value, ' OWNED BY $v769bf5da97.\"$v5e813b295b\";');
				   	     ELSE
				   	     	myvar = concat('ALTER SEQUENCE ', sequence_name, ' OWNED BY $v769bf5da97.\"$v5e813b295b\";');
				   	     END IF;
				   	     
				   	     --raise notice 'create or modify sequence: %', myvar;
				   	     EXECUTE myvar;
				   	     
				   	     myvar = CONCAT('ALTER TABLE $v769bf5da97 ALTER COLUMN \"$v5e813b295b\" SET DEFAULT nextval(''', sequence_name, ''');');
						--raise notice 'set sequence to table: %', myvar;
				   	     EXECUTE myvar;
					END $$;
					"; } } return $v3c76382d93; } public static function getDropTablePrimaryKeysStatement($pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $pa51282b5 = $pbec62cc6["schema"]; return "DO $$
				DECLARE myvar varchar = '';
				DECLARE sequence_name varchar = '';
				DECLARE attr_name varchar = '';
				DECLARE t_row record;
				BEGIN
			   		-- get pk attr name if exists
			   		FOR t_row in 
			   			SELECT a.attname, format_type(a.atttypid, a.atttypmod) AS data_type
						 FROM pg_index i
						 INNER JOIN pg_attribute a ON a.attrelid = i.indrelid AND a.attnum = ANY(i.indkey)
						 " . ($pa51282b5 ? "INNER JOIN pg_class c ON c.oid = i.indrelid AND a.attrelid = c.oid
						 INNER JOIN pg_namespace ns ON ns.nspname = '$pa51282b5' AND c.relnamespace = ns.oid" : "") . "
						 WHERE i.indrelid = '$pc661dc6b'::regclass AND i.indisprimary 
					LOOP
						attr_name = '';
						sequence_name = '';
						
						SELECT t_row.attname INTO attr_name;
				   		--raise notice 'attr_name: %', attr_name;
				   		
				   		IF attr_name != '' AND attr_name IS NOT NULL THEN
					   		-- get sequence name if exists
							myvar = concat('SELECT pg_get_serial_sequence(''$v769bf5da97'', ''', attr_name, ''');');
							--raise notice 'pg_get_serial_sequence: %', myvar;
							EXECUTE myvar INTO sequence_name;
							--raise notice 'sequence_name: %', sequence_name;
							
							--drop sequence
							IF sequence_name != '' AND sequence_name IS NOT NULL THEN
					   	     	myvar = concat('DROP SEQUENCE IF EXISTS ', sequence_name, ' CASCADE;');
					   	     	--raise notice 'drop sequence: %', myvar;
					   	     	EXECUTE myvar;
					   	     END IF;
					   	END IF;
					END LOOP;
				   	
					--drop pk constraint
					SELECT concat('ALTER TABLE $v769bf5da97 DROP CONSTRAINT ', constraint_name, ';') INTO myvar
					 FROM information_schema.table_constraints
					 WHERE " . ($pa51282b5 ? "table_schema = '$pa51282b5' AND " : "") . "table_name = '$pc661dc6b' AND constraint_type = 'PRIMARY KEY';

					IF myvar != '' THEN
						--raise notice 'drop pk contraint: %', myvar;
						EXECUTE myvar;
					END IF;
				END $$;"; } public static function getAddTableForeignKeyStatement($pc661dc6b, $pa7c14731, $v5d3813882f = false) { if ($pa7c14731 && ($pa7c14731["attribute"] || $pa7c14731["child_column"])) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $v6912eee73f = isset($pa7c14731["on_delete"]) && $pa7c14731["on_delete"] ? "ON DELETE " . $pa7c14731["on_delete"] : ""; $pd7c78775 = isset($pa7c14731["on_update"]) && $pa7c14731["on_update"] ? "ON UPDATE " . $pa7c14731["on_update"] : ""; $v5e45ec9bb9 = $pa7c14731["attribute"] ? $pa7c14731["attribute"] : $pa7c14731["child_column"]; $pe3b2d7ad = $pa7c14731["reference_attribute"] ? $pa7c14731["reference_attribute"] : $pa7c14731["parent_column"]; $pf8a2ba68 = $pa7c14731["reference_table"] ? $pa7c14731["reference_table"] : $pa7c14731["parent_table"]; $pa28639ac = $pa7c14731["name"] ? $pa7c14731["name"] : $pa7c14731["constraint_name"]; $ped0a6251 = is_array($v5e45ec9bb9) ? $v5e45ec9bb9 : array($v5e45ec9bb9); $v9256fc8d51 = is_array($pe3b2d7ad) ? $pe3b2d7ad : array($pe3b2d7ad); $v45be4caf5b = self::getParsedTableEscapedSQL($pf8a2ba68, $v5d3813882f); return "ALTER TABLE $v769bf5da97 ADD CONSTRAINT " . $pa28639ac . " FOREIGN KEY (\"" . implode('", "', $ped0a6251) . "\") REFERENCES " . $v45be4caf5b . " (\"" . implode('", "', $v9256fc8d51) . "\") $v6912eee73f $pd7c78775 $v77cb07b555"; } } public static function getDropTableForeignKeysStatement($pc661dc6b, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $pc661dc6b = $pbec62cc6["name"]; $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; return "DO $$
DECLARE myvar varchar = '';
DECLARE t_row record;
BEGIN
	FOR t_row in 
		SELECT tc.table_name, tc.constraint_name 
		FROM information_schema.table_constraints tc
		INNER JOIN pg_namespace nsp on nsp.nspname = tc.constraint_schema
		INNER JOIN pg_constraint pgc on pgc.conname = tc.constraint_name and pgc.connamespace = nsp.oid and pgc.contype = 'f'
		INNER JOIN information_schema.columns col on col.table_schema = tc.table_schema and col.table_name = tc.table_name and col.ordinal_position=ANY(pgc.conkey)
		WHERE tc.constraint_schema NOT LIKE 'pg%' AND tc.constraint_schema <> 'information_schema' AND tc.table_name='$pc661dc6b' AND tc.constraint_type = 'FOREIGN KEY'
	LOOP
		myvar = '';
		
		SELECT concat('ALTER TABLE IF EXISTS \"', t_row.table_name, '\" DROP CONSTRAINT \"', t_row.constraint_name, '\"$v77cb07b555;') INTO myvar;
		
		IF myvar != '' AND myvar IS NOT NULL THEN
			EXECUTE myvar;
	   	END IF;
	END LOOP;
END $$;"; } public static function getDropTableForeignConstraintStatement($pc661dc6b, $pa28639ac, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); return "ALTER TABLE IF EXISTS $v769bf5da97 DROP CONSTRAINT \"$pa28639ac\";"; } public static function getAddTableIndexStatement($pc661dc6b, $pfdbbc383, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pfdbbc383 = is_array($pfdbbc383) ? $pfdbbc383 : array($pfdbbc383); return "CREATE INDEX ON $v769bf5da97 (\"" . implode("\", \"", $pfdbbc383) . "\") $v77cb07b555"; } public static function getLoadTableDataFromFileStatement($pf3dc0762, $pc661dc6b, $v5d3813882f = false) { $v769bf5da97 = self::getParsedTableEscapedSQL($pc661dc6b, $v5d3813882f); $v77cb07b555 = $v5d3813882f && $v5d3813882f["suffix"] ? $v5d3813882f["suffix"] : ""; $pfdbbc383 = $v5d3813882f && $v5d3813882f["attributes"] ? "(" . (is_array($v5d3813882f["attributes"]) ? implode(", ", $v5d3813882f["attributes"]) : $v5d3813882f["attributes"]) . ")" : ""; $v52fe4649ca = $v5d3813882f["fields_delimiter"] ? $v5d3813882f["fields_delimiter"] : "\t"; return "COPY $v769bf5da97 $pfdbbc383 FROM '$pf3dc0762' WITH DELIMITER '$v52fe4649ca' $v77cb07b555"; } public static function getShowCreateTableStatement($pc661dc6b, $v5d3813882f = false) { return ""; } public static function getShowCreateViewStatement($pa36e00ea, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pa36e00ea, $v5d3813882f); $pa36e00ea = $pbec62cc6["name"]; return "select '$pa36e00ea' as \"View\", pg_get_viewdef('$pa36e00ea') as \"Create View\""; } public static function getShowCreateTriggerStatement($v5ed3bce1d1, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($v5ed3bce1d1, $v5d3813882f); $v5ed3bce1d1 = $pbec62cc6["name"]; return "SELECT tgname as \"Trigger\", pg_get_triggerdef(oid) as \"SQL Original Statement\" ". "FROM pg_trigger WHERE tgname='$v5ed3bce1d1'"; } public static function getShowCreateProcedureStatement($pbda8f16d, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($pbda8f16d, $v5d3813882f); $pbda8f16d = $pbec62cc6["name"]; return "SELECT proname as \"Procedure\", pg_get_functiondef(f.oid) as \"Create Procedure\"
			FROM pg_catalog.pg_proc f
			INNER JOIN pg_catalog.pg_namespace n ON (f.pronamespace = n.oid)
			WHERE proname='$pbda8f16d'"; } public static function getShowCreateFunctionStatement($v2f4e66e00a, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($v2f4e66e00a, $v5d3813882f); $v2f4e66e00a = $pbec62cc6["name"]; return "SELECT proname as \"Function\", pg_get_functiondef(f.oid) as \"Create Function\"
			FROM pg_catalog.pg_proc f
			INNER JOIN pg_catalog.pg_namespace n ON (f.pronamespace = n.oid)
			WHERE proname='$v2f4e66e00a'"; } public static function getShowCreateEventStatement($v76392c9cad, $v5d3813882f = false) { $pbec62cc6 = self::parseTableName($v76392c9cad, $v5d3813882f); $v76392c9cad = $pbec62cc6["name"]; return "SELECT tgname as \"Event\", pg_get_triggerdef(oid) as \"Create Event\" ". "FROM pg_trigger WHERE tgname='$v76392c9cad'"; } public static function getShowTablesStatement($pb67a2609, $v5d3813882f = false) { return str_replace("\t", "", self::getTablesStatement($pb67a2609, $v5d3813882f)); } public static function getShowViewsStatement($pb67a2609, $v5d3813882f = false) { return "SELECT TABLE_NAME AS \"view_name\" ". "FROM INFORMATION_SCHEMA.views ". "WHERE TABLE_CATALOG = '$pb67a2609' AND TABLE_SCHEMA NOT LIKE 'pg%' AND TABLE_SCHEMA <> 'information_schema'"; } public static function getShowTriggersStatement($pb67a2609, $v5d3813882f = false) { return "SELECT TRIGGER_NAME AS \"Trigger\", event_object_table AS \"table_name\" ". "FROM INFORMATION_SCHEMA.TRIGGERS ". "WHERE TRIGGER_CATALOG='$pb67a2609' AND TRIGGER_SCHEMA NOT LIKE 'pg%' AND TRIGGER_SCHEMA <> 'information_schema'"; } public static function getShowTableColumnsStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { return str_replace("\n", " ", self::getTableFieldsStatement($pc661dc6b, $pb67a2609, $v5d3813882f)); } public static function getShowForeignKeysStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false) { return str_replace("\t", "", self::getForeignKeysStatement($pc661dc6b, $pb67a2609, $v5d3813882f)); } public static function getShowProceduresStatement($pb67a2609, $v5d3813882f = false) { return "SELECT p.oid, n.nspname as \"schema\", p.proname as \"procedure_name\" ". "FROM pg_proc p ". "INNER JOIN pg_namespace n ON n.oid = p.pronamespace ". "INNER JOIN information_schema.routines rt ON rt.routine_catalog='$pb67a2609' AND rt.routine_type='PROCEDURE' AND routine_schema=n.nspname AND rt.routine_name=p.proname ". "WHERE n.nspname NOT LIKE 'pg%' AND n.nspname <> 'information_schema' ". "GROUP BY p.oid, n.nspname, p.proname"; } public static function getShowFunctionsStatement($pb67a2609, $v5d3813882f = false) { return "SELECT p.oid, n.nspname as \"schema\", p.proname as \"function_name\" ". "FROM pg_proc p ". "INNER JOIN pg_namespace n ON n.oid = p.pronamespace ". "INNER JOIN information_schema.routines rt ON rt.routine_catalog='$pb67a2609' AND rt.routine_type='FUNCTION' AND routine_schema=n.nspname AND rt.routine_name=p.proname ". "WHERE n.nspname NOT LIKE 'pg%' AND n.nspname <> 'information_schema' ". "GROUP BY p.oid, n.nspname, p.proname"; } public static function getShowEventsStatement($pb67a2609, $v5d3813882f = false) { return "SELECT TRIGGER_NAME AS \"event_name\" ". "FROM INFORMATION_SCHEMA.TRIGGERS ". "WHERE TRIGGER_CATALOG='$pb67a2609' AND TRIGGER_SCHEMA NOT LIKE 'pg%' AND TRIGGER_SCHEMA <> 'information_schema'"; } public static function getSetupTransactionStatement($v5d3813882f = false) { return "SET TRANSACTION ISOLATION LEVEL REPEATABLE READ"; } public static function getStartTransactionStatement($v5d3813882f = false) { return "START TRANSACTION ". "/* [transaction_name] WITH MARK [description] */"; } public static function getCommitTransactionStatement($v5d3813882f = false) { return "COMMIT TRANSACTION"; } public static function getStartDisableAutocommitStatement($v5d3813882f = false) { return "SET AUTOCOMMIT TO OFF;"; } public static function getEndDisableAutocommitStatement($v5d3813882f = false) { return "SET AUTOCOMMIT TO ON;"; } public static function getStartLockTableWriteStatement($pc661dc6b, $v5d3813882f = false) { return null; } public static function getStartLockTableReadStatement($pc661dc6b, $v5d3813882f = false) { return null; } public static function getEndLockTableStatement($v5d3813882f = false) { return null; } public static function getStartDisableKeysStatement($pc661dc6b, $v5d3813882f = false) { return null; } public static function getEndDisableKeysStatement($pc661dc6b, $v5d3813882f = false) { return null; } public static function getDropTriggerStatement($v5ed3bce1d1, $v5d3813882f = false) { $v6caad9d962 = self::getParsedTableEscapedSQL($v5ed3bce1d1, $v5d3813882f); return "DROP TRIGGER IF EXISTS $v6caad9d962;"; } public static function getDropProcedureStatement($pbda8f16d, $v5d3813882f = false) { $v7c7c261ebc = self::getParsedTableEscapedSQL($pbda8f16d, $v5d3813882f); return "DROP PROCEDURE IF EXISTS $v7c7c261ebc;"; } public static function getDropFunctionStatement($v2f4e66e00a, $v5d3813882f = false) { $v7992b8f618 = self::getParsedTableEscapedSQL($v2f4e66e00a, $v5d3813882f); return "DROP FUNCTION IF EXISTS $v7992b8f618;"; } public static function getDropEventStatement($v76392c9cad, $v5d3813882f = false) { $pdea5ed12 = self::getParsedTableEscapedSQL($v76392c9cad, $v5d3813882f); return "DROP EVENT TRIGGER IF EXISTS $pdea5ed12;"; } public static function getDropViewStatement($pa36e00ea, $v5d3813882f = false) { $pe8a320e9 = self::getParsedTableEscapedSQL($pa36e00ea, $v5d3813882f); return "DROP VIEW IF EXISTS $pe8a320e9;"; } } ?>
