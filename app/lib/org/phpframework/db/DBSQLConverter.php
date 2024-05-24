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
trait DBSQLConverter { public static function convertObjectToDefaultSQL($v539082ff30, $v5d3813882f = false) { return SQLQueryHandler::create($v539082ff30); } public static function convertDefaultSQLToObject($v3c76382d93, $v5d3813882f = false) { return SQLQueryHandler::parse($v3c76382d93); } public static function buildDefaultTableInsertSQL($v8c5df8072b, $pfdbbc383, $v5d3813882f = false) { $v3c76382d93 = null; if ($v8c5df8072b && $pfdbbc383) { $v2632f4eb62 = ""; $v2a0f8b446e = ""; foreach($pfdbbc383 as $pbfa01ed1 => $v67db1bd535) { $v2632f4eb62 .= (strlen($v2632f4eb62) ? ", " : "") . SQLQueryHandler::getParsedSqlColumnName($pbfa01ed1); $v2a0f8b446e .= (strlen($v2a0f8b446e) ? ", " : "") . self::createBaseExprValue($v67db1bd535); } if ($v2632f4eb62) $v3c76382d93 = "INSERT INTO " . SQLQueryHandler::getParsedSqlTableName($v8c5df8072b) . " ($v2632f4eb62) VALUES ($v2a0f8b446e)"; } return $v3c76382d93; } public static function buildDefaultTableUpdateSQL($v8c5df8072b, $pfdbbc383, $paf1bc6f6 = false, $v5d3813882f = false) { $v3c76382d93 = null; if ($v8c5df8072b && $pfdbbc383) { $v5d3813882f = is_array($v5d3813882f) ? $v5d3813882f : array(); $v7fd392376f = isset($v5d3813882f["conditions_join"]) ? $v5d3813882f["conditions_join"] : null; $pefeda58c = isset($v5d3813882f["all"]) ? $v5d3813882f["all"] : null; $pfd2c850a = isset($v5d3813882f["sql_conditions"]) ? $v5d3813882f["sql_conditions"] : null; $v0e015dc901 = self::getSQLConditions($paf1bc6f6, $v7fd392376f); $v0e015dc901 .= $pfd2c850a ? ($v0e015dc901 ? " AND " : "") . $pfd2c850a : ""; if ($v0e015dc901 || $pefeda58c) { $v2632f4eb62 = ""; foreach($pfdbbc383 as $pbfa01ed1 => $v67db1bd535) $v2632f4eb62 .= ($v2632f4eb62 ? ", " : "") . SQLQueryHandler::getParsedSqlColumnName($pbfa01ed1) . "=" . self::createBaseExprValue($v67db1bd535); $v51828a2055 = $v0e015dc901 ? " WHERE {$v0e015dc901}" : ""; $v3c76382d93 = "UPDATE " . SQLQueryHandler::getParsedSqlTableName($v8c5df8072b) . " SET {$v2632f4eb62}{$v51828a2055}"; } } return $v3c76382d93; } public static function buildDefaultTableDeleteSQL($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false) { $v3c76382d93 = null; if ($v8c5df8072b) { $v5d3813882f = is_array($v5d3813882f) ? $v5d3813882f : array(); $v7fd392376f = isset($v5d3813882f["conditions_join"]) ? $v5d3813882f["conditions_join"] : null; $pefeda58c = isset($v5d3813882f["all"]) ? $v5d3813882f["all"] : null; $pfd2c850a = isset($v5d3813882f["sql_conditions"]) ? $v5d3813882f["sql_conditions"] : null; $v0e015dc901 = self::getSQLConditions($paf1bc6f6, $v7fd392376f); $v0e015dc901 .= $pfd2c850a ? ($v0e015dc901 ? " AND " : "") . $pfd2c850a : ""; if($v0e015dc901 || $pefeda58c) { $v51828a2055 = $v0e015dc901 ? " WHERE {$v0e015dc901}" : ""; $v3c76382d93 = "DELETE FROM " . SQLQueryHandler::getParsedSqlTableName($v8c5df8072b) . $v51828a2055; } } return $v3c76382d93; } public static function buildDefaultTableFindSQL($v8c5df8072b, $pfdbbc383 = false, $paf1bc6f6 = false, $v5d3813882f = false) { $v3c76382d93 = null; if ($v8c5df8072b) { $v5d3813882f = is_array($v5d3813882f) ? $v5d3813882f : array(); $v7fd392376f = isset($v5d3813882f["conditions_join"]) ? $v5d3813882f["conditions_join"] : null; $v04003a4f53 = isset($v5d3813882f["sorts"]) ? $v5d3813882f["sorts"] : null; $pfd2c850a = isset($v5d3813882f["sql_conditions"]) ? $v5d3813882f["sql_conditions"] : null; $v0e015dc901 = self::getSQLConditions($paf1bc6f6, $v7fd392376f); $v0e015dc901 .= $pfd2c850a ? ($v0e015dc901 ? " AND " : "") . $pfd2c850a : ""; $v1caae2fe5c = self::getSQLSort($v04003a4f53); $v2632f4eb62 = self::getSQLAttributes($pfdbbc383); $v3c76382d93 = "SELECT {$v2632f4eb62} FROM " . SQLQueryHandler::getParsedSqlTableName($v8c5df8072b); $v3c76382d93 .= $v0e015dc901 ? " WHERE {$v0e015dc901}" : ""; $v3c76382d93 .= $v1caae2fe5c ? " ORDER BY {$v1caae2fe5c}" : ""; } return $v3c76382d93; } public static function buildDefaultTableCountSQL($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false) { $v3c76382d93 = null; if ($v8c5df8072b) { $v5d3813882f = is_array($v5d3813882f) ? $v5d3813882f : array(); $v7fd392376f = isset($v539082ff30["conditions_join"]) ? $v539082ff30["conditions_join"] : null; $pfd2c850a = isset($v5d3813882f["sql_conditions"]) ? $v5d3813882f["sql_conditions"] : null; $v0e015dc901 = self::getSQLConditions($paf1bc6f6, $v7fd392376f); $v0e015dc901 .= $pfd2c850a ? ($v0e015dc901 ? " AND " : "") . $pfd2c850a : ""; $v3c76382d93 = "SELECT count(*) AS total FROM " . SQLQueryHandler::getParsedSqlTableName($v8c5df8072b); $v3c76382d93 .= $v0e015dc901 ? " WHERE {$v0e015dc901}" : ""; } return $v3c76382d93; } public static function buildDefaultTableFindRelationshipSQL($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false) { $v3c76382d93 = null; if ($v8c5df8072b && $v10c59e20bd) { $v5d3813882f = is_array($v5d3813882f) ? $v5d3813882f : array(); $v9994512d98 = isset($v10c59e20bd["keys"]) ? $v10c59e20bd["keys"] : null; $pfdbbc383 = isset($v10c59e20bd["attributes"]) ? $v10c59e20bd["attributes"] : null; $paf1bc6f6 = isset($v10c59e20bd["conditions"]) ? $v10c59e20bd["conditions"] : null; $pe4b1434e = isset($v10c59e20bd["groups_by"]) ? $v10c59e20bd["groups_by"] : null; $v04003a4f53 = !empty($v5d3813882f["sorts"]) && empty($v10c59e20bd["sorts"]) ? $v5d3813882f["sorts"] : (isset($v10c59e20bd["sorts"]) ? $v10c59e20bd["sorts"] : null); $pfd2c850a = isset($v5d3813882f["sql_conditions"]) ? $v5d3813882f["sql_conditions"] : null; $v0e015dc901 = self::getSQLRelationshipConditions($paf1bc6f6, $v8c5df8072b, $v4ec0135323); $v0e015dc901 .= $pfd2c850a ? ($v0e015dc901 ? " AND " : "") . $pfd2c850a : ""; $pfa7a7af0 = self::getSQLRelationshipGroupBy($pe4b1434e, $v8c5df8072b); $v1caae2fe5c = self::getSQLRelationshipSort($v04003a4f53, $v8c5df8072b, ($pfa7a7af0 ? true : false)); $v3c76382d93 = "SELECT "; $v3c76382d93 .= self::getSQLRelationshipAttributes($pfdbbc383, $v8c5df8072b, $v9994512d98); $v3c76382d93 .= " FROM " . SQLQueryHandler::getParsedSqlTableName($v8c5df8072b) . " " . self::getSQLRelationshipJoins($v9994512d98, $v8c5df8072b); $v3c76382d93 .= $v0e015dc901 || $pfd2c850a ? " WHERE $v0e015dc901" : ""; $v3c76382d93 .= $pfa7a7af0 ? " " . $pfa7a7af0 : ""; if($pfa7a7af0 && $v1caae2fe5c) $v3c76382d93 = "SELECT * FROM ({$v3c76382d93}) Z ORDER BY {$v1caae2fe5c}"; elseif($v1caae2fe5c) $v3c76382d93 .= " ORDER BY {$v1caae2fe5c}"; } return $v3c76382d93; } public static function buildDefaultTableCountRelationshipSQL($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false) { $v3c76382d93 = null; if ($v8c5df8072b && $v10c59e20bd) { $v9994512d98 = isset($v10c59e20bd["keys"]) ? $v10c59e20bd["keys"] : null; $pfdbbc383 = isset($v10c59e20bd["attributes"]) ? $v10c59e20bd["attributes"] : null; $paf1bc6f6 = isset($v10c59e20bd["conditions"]) ? $v10c59e20bd["conditions"] : null; $pe4b1434e = isset($v10c59e20bd["groups_by"]) ? $v10c59e20bd["groups_by"] : null; $pfd2c850a = isset($v5d3813882f["sql_conditions"]) ? $v5d3813882f["sql_conditions"] : null; $v0e015dc901 = self::getSQLRelationshipConditions($paf1bc6f6, $v8c5df8072b, $v4ec0135323); $v0e015dc901 .= $pfd2c850a ? ($v0e015dc901 ? " AND " : "") . $pfd2c850a : ""; $v783f2dc478 = self::getSQLRelationshipGroupBy($pe4b1434e, $v8c5df8072b); $v3c76382d93 = " FROM " . SQLQueryHandler::getParsedSqlTableName($v8c5df8072b) . " " . self::getSQLRelationshipJoins($v9994512d98, $v8c5df8072b); $v3c76382d93 .= $v0e015dc901 ? " WHERE {$v0e015dc901}" : ""; $v3c76382d93 .= $v783f2dc478 ? " " . $v783f2dc478 : ""; if($v783f2dc478) $v3c76382d93 = "SELECT count(*) AS total FROM (
					SELECT " . self::getSQLRelationshipAttributes($pfdbbc383, $v8c5df8072b, $v9994512d98) . "
					$v3c76382d93
				) Z"; else $v3c76382d93 = "SELECT count(*) AS total " . $v3c76382d93; } return $v3c76382d93; } public static function buildDefaultTableFindColumnMaxSQL($v8c5df8072b, $v7162e23723, $v5d3813882f = false) { $v3c76382d93 = "SELECT MAX(" . SQLQueryHandler::getParsedSqlColumnName($v7162e23723) . ") AS max FROM " . SQLQueryHandler::getParsedSqlTableName($v8c5df8072b); return $v3c76382d93; } public static function getSQLRelationshipConditions($paf1bc6f6, $v8c5df8072b = false, $v4ec0135323 = false) { $v3c76382d93 = ""; if(is_array($v4ec0135323)) $v3c76382d93 .= ($v3c76382d93 ? " AND " : "") . self::getSQLConditions($v4ec0135323, null, $v8c5df8072b); $pc37695cb = $paf1bc6f6 ? count($paf1bc6f6) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v32dd06ab9b = $paf1bc6f6[$v43dd7d0051]; if (is_array($v32dd06ab9b)) { $v9ea12a829c = isset($v32dd06ab9b["column"]) ? $v32dd06ab9b["column"] : null; $pc661dc6b = !empty($v32dd06ab9b["table"]) ? $v32dd06ab9b["table"] : null; $v19a7745bc6 = !empty($v32dd06ab9b["operator"]) ? $v32dd06ab9b["operator"] : "="; $v67db1bd535 = isset($v32dd06ab9b["value"]) ? $v32dd06ab9b["value"] : null; $v5edc956f7b = isset($v32dd06ab9b["refcolumn"]) ? $v32dd06ab9b["refcolumn"] : null; $v590500b763 = isset($v32dd06ab9b["reftable"]) ? $v32dd06ab9b["reftable"] : null; if ($v9ea12a829c) { if (!$pc661dc6b) $pc661dc6b = SQLQueryHandler::getTableFromColumn($v9ea12a829c); if (!$pc661dc6b) $pc661dc6b = $v8c5df8072b; if ($v5edc956f7b) { $v590500b763 = $v590500b763 ? $v590500b763 : $v8c5df8072b; $v3c76382d93 .= ($v3c76382d93 ? " AND " : "") . self::prepareTableAttributeWithFunction($v9ea12a829c, $pc661dc6b) . " {$v19a7745bc6} " . self::prepareTableAttributeWithFunction($v5edc956f7b, $v590500b763); } if (isset($v32dd06ab9b["value"])) { $v3060512b8c = strtolower($v19a7745bc6); if ($v3060512b8c == "in" || $v3060512b8c == "not in") $v67db1bd535 = self::createBaseExprValueForOperatorIn($v67db1bd535); else if ($v3060512b8c == "is" || $v3060512b8c == "is not") $v67db1bd535 = self::createBaseExprValueForOperatorIs($v67db1bd535); else $v67db1bd535 = self::createBaseExprValue($v67db1bd535); $v9e394b2939 = array( $v9ea12a829c => array( "operator" => $v19a7745bc6, "value" => $v67db1bd535 ) ); $v3c76382d93 .= ($v3c76382d93 ? " AND " : "") . self::getSQLConditions($v9e394b2939, null, $pc661dc6b); } } } else $v3c76382d93 .= ($v3c76382d93 ? " AND " : "") . $v32dd06ab9b; } return $v3c76382d93; } protected static function getSQLRelationshipSort($v04003a4f53, $v8c5df8072b, $v1c5ac544d0 = false) { $v3c76382d93 = ""; if ($v04003a4f53) foreach ($v04003a4f53 as $pd69fb7d0 => $pdab26aff) { if (is_array($pdab26aff)) { $v9ea12a829c = isset($pdab26aff["column"]) ? $pdab26aff["column"] : null; $pc06af91c = isset($pdab26aff["order"]) ? $pdab26aff["order"] : null; $pc661dc6b = !empty($pdab26aff["table"]) ? $pdab26aff["table"] : null; if ($v9ea12a829c && !$pc661dc6b) $pc661dc6b = SQLQueryHandler::getTableFromColumn($v9ea12a829c); } else { $v9ea12a829c = is_numeric($pd69fb7d0) ? $pdab26aff : $pd69fb7d0; $pc06af91c = is_numeric($pd69fb7d0) ? "" : $pdab26aff; $pc661dc6b = null; if ($v9ea12a829c) $pc661dc6b = SQLQueryHandler::getTableFromColumn($v9ea12a829c); } if($v9ea12a829c) { if (!$pc661dc6b) $pc661dc6b = $v8c5df8072b; $v5d170b1de6 = $v1c5ac544d0 ? $v9ea12a829c : self::prepareTableAttributeWithFunction($v9ea12a829c, $pc661dc6b); $v3c76382d93 .= ($v3c76382d93 ? ", " : "") . "{$v5d170b1de6} {$pc06af91c}"; } } return $v3c76382d93; } protected static function getSQLRelationshipAttributes($ped0a6251, $v8c5df8072b, $v9994512d98) { $v3c76382d93 = ""; if ($ped0a6251) foreach ($ped0a6251 as $pd69fb7d0 => $v1b0cfa478b) { if (is_array($v1b0cfa478b)) { $v5e813b295b = !empty($v1b0cfa478b["name"]) ? $v1b0cfa478b["name"] : (isset($v1b0cfa478b["column"]) ? $v1b0cfa478b["column"] : null); $v9ea12a829c = isset($v1b0cfa478b["column"]) ? $v1b0cfa478b["column"] : null; $pc661dc6b = !empty($v1b0cfa478b["table"]) ? $v1b0cfa478b["table"] : null; if ($v9ea12a829c && !$pc661dc6b) $pc661dc6b = SQLQueryHandler::getTableFromColumn($v9ea12a829c); } else { $v5e813b295b = $v1b0cfa478b; $v9ea12a829c = is_numeric($pd69fb7d0) ? $v1b0cfa478b : $pd69fb7d0; $pc661dc6b = null; if ($v9ea12a829c) $pc661dc6b = SQLQueryHandler::getTableFromColumn($v9ea12a829c); } if($v9ea12a829c) { if (!$pc661dc6b) $pc661dc6b = $v8c5df8072b; $v3c76382d93 .= (strlen($v3c76382d93) ? ", " : "") . self::prepareTableAttributeWithFunction($v9ea12a829c, $pc661dc6b) . ($v9ea12a829c != "*" && $v9ea12a829c != $v5e813b295b && $v5e813b295b ? " AS \"{$v5e813b295b}\"" : ""); } } if (!$v3c76382d93 && $v9994512d98) { $pc37695cb = count($v9994512d98); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pbfa01ed1 = $v9994512d98[$v43dd7d0051]; if (!empty($pbfa01ed1["ftable"])) $v3c76382d93 .= ($v3c76382d93 ? ", " : "") . SQLQueryHandler::getParsedSqlTableName($pbfa01ed1["ftable"]) . ".*"; } } return $v3c76382d93 ? $v3c76382d93 : "*"; } protected static function getSQLRelationshipJoins($v9994512d98, $v8c5df8072b) { $v3cbbdf9379 = array(); $pc37695cb = $v9994512d98 ? count($v9994512d98) : 0; for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pbfa01ed1 = $v9994512d98[$v43dd7d0051]; $pd14ab296 = !empty($pbfa01ed1["ptable"]) ? $pbfa01ed1["ptable"] : $v8c5df8072b; $v68b622a4d2 = isset($pbfa01ed1["pcolumn"]) ? $pbfa01ed1["pcolumn"] : null; $v546b996ea7 = isset($pbfa01ed1["ftable"]) ? $pbfa01ed1["ftable"] : null; $v0d1d84a0c9 = isset($pbfa01ed1["fcolumn"]) ? $pbfa01ed1["fcolumn"] : null; $v3c581c912b = !empty($pbfa01ed1["join"]) ? strtoupper($pbfa01ed1["join"]) : "inner"; $v19a7745bc6 = isset($pbfa01ed1["operator"]) ? $pbfa01ed1["operator"] : null; $v67db1bd535 = isset($pbfa01ed1["value"]) ? $pbfa01ed1["value"] : null; $v9a6537a74a = isset($pbfa01ed1["value"]) && strlen($pbfa01ed1["value"]); $v19a7745bc6 = $v19a7745bc6 ? $v19a7745bc6 : "="; $v3060512b8c = strtolower($v19a7745bc6); if ($v68b622a4d2 && !$pd14ab296) $pd14ab296 = SQLQueryHandler::getTableFromColumn($v68b622a4d2); if ($v0d1d84a0c9 && !$v546b996ea7) $v546b996ea7 = SQLQueryHandler::getTableFromColumn($v0d1d84a0c9); if ($v9a6537a74a) { if ($v3060512b8c == "in" || $v3060512b8c == "not in") $v67db1bd535 = self::createBaseExprValueForOperatorIn($v67db1bd535); else if ($v3060512b8c == "is" || $v3060512b8c == "is not") $v67db1bd535 = self::createBaseExprValueForOperatorIs($v67db1bd535); else $v67db1bd535 = self::createBaseExprValue($v67db1bd535); } $pb5b44f83 = array(); if ($v0d1d84a0c9 && $v546b996ea7) { $v566db8f5a9 = $v546b996ea7 == $v8c5df8072b ? $v546b996ea7 . "_aux" : SQLQueryHandler::getAlias($v546b996ea7); $v120c2184c9 = " {$v3c581c912b} JOIN " . SQLQueryHandler::getParsedSqlTableName($v546b996ea7) . ($v566db8f5a9 != $v546b996ea7 ? " {$v566db8f5a9}" : "") . " ON "; $pcb8bd2f5 = " {$v3c581c912b} JOIN " . SQLQueryHandler::getParsedSqlTableName($pd14ab296) . " ON "; if ($v3cbbdf9379[ $v120c2184c9 ] && $pd14ab296 && $pd14ab296 != $v8c5df8072b && !$v3cbbdf9379[ $pcb8bd2f5 ]) { $v120c2184c9 = $pcb8bd2f5; } $pb5b44f83 = is_array($v3cbbdf9379[ $v120c2184c9 ]) ? $v3cbbdf9379[ $v120c2184c9 ] : array(); if($v68b622a4d2) { $pc8c664f0 = " " . self::prepareTableAttributeWithFunction($v0d1d84a0c9, $v566db8f5a9) . " $v19a7745bc6 " . self::prepareTableAttributeWithFunction($v68b622a4d2, $pd14ab296); if(!in_array($pc8c664f0, $pb5b44f83)) { $pb5b44f83[] = $pc8c664f0; } } if ($v9a6537a74a) { $pc8c664f0 = " " . self::prepareTableAttributeWithFunction($v0d1d84a0c9, $v566db8f5a9) . " $v19a7745bc6 {$v67db1bd535}"; if (!in_array($pc8c664f0, $pb5b44f83)) { $pb5b44f83[] = $pc8c664f0; } if ($v68b622a4d2) { $pc8c664f0 = " " . self::prepareTableAttributeWithFunction($v68b622a4d2, $pd14ab296) . " $v19a7745bc6 {$v67db1bd535}"; if(!in_array($pc8c664f0, $pb5b44f83)) { $pb5b44f83[] = $pc8c664f0; } } } } else if ($v68b622a4d2 && $v9a6537a74a) { $v566db8f5a9 = $pd14ab296 == $v8c5df8072b ? $pd14ab296 . "_aux" : SQLQueryHandler::getAlias($pd14ab296); $v120c2184c9 = " {$v3c581c912b} JOIN " . SQLQueryHandler::getParsedSqlTableName($pd14ab296) . ($v566db8f5a9 != $pd14ab296 ? " {$v566db8f5a9}" : "") . " ON "; $pb5b44f83 = is_array($v3cbbdf9379[ $v120c2184c9 ]) ? $v3cbbdf9379[ $v120c2184c9 ] : array(); $pc8c664f0 = " " . self::prepareTableAttributeWithFunction($v68b622a4d2, $v566db8f5a9) . " $v19a7745bc6 {$v67db1bd535}"; if(!in_array($pc8c664f0, $pb5b44f83)) { $pb5b44f83[] = $pc8c664f0; } } else if ($v0d1d84a0c9 && $v9a6537a74a) { $v566db8f5a9 = $v546b996ea7 == $v8c5df8072b ? $v546b996ea7 . "_aux" : SQLQueryHandler::getAlias($v546b996ea7); $v120c2184c9 = " {$v3c581c912b} JOIN " . SQLQueryHandler::getParsedSqlTableName($v546b996ea7) . ($v566db8f5a9 != $v546b996ea7 ? " {$v566db8f5a9}" : "") . " ON "; $pb5b44f83 = is_array($v3cbbdf9379[ $v120c2184c9 ]) ? $v3cbbdf9379[ $v120c2184c9 ] : array(); $pc8c664f0 = " " . self::prepareTableAttributeWithFunction($v0d1d84a0c9, $v566db8f5a9) . " $v19a7745bc6 {$v67db1bd535}"; if(!in_array($pc8c664f0, $pb5b44f83)) { $pb5b44f83[] = $pc8c664f0; } } if(count($pb5b44f83)) $v3cbbdf9379[ $v120c2184c9 ] = $pb5b44f83; } $v3c76382d93 = ""; foreach($v3cbbdf9379 as $v087d4ea2cb => $pb5b44f83) $v3c76382d93 .= $v087d4ea2cb . implode(" AND ", $pb5b44f83); return $v3c76382d93; } protected static function getSQLRelationshipGroupBy($v1c5ac544d0, $v8c5df8072b) { $v783f2dc478 = ""; $v2bba73adc8 = ""; $pd264077c = array(); if ($v1c5ac544d0) foreach ($v1c5ac544d0 as $v3264112bb5) { if (is_array($v3264112bb5)) { $v9ea12a829c = isset($v3264112bb5["column"]) ? $v3264112bb5["column"] : null; $v3e419d0061 = isset($v3264112bb5["having"]) ? $v3264112bb5["having"] : null; $pc661dc6b = !empty($v3264112bb5["table"]) ? $v3264112bb5["table"] : null; if ($v9ea12a829c && !$pc661dc6b) $pc661dc6b = SQLQueryHandler::getTableFromColumn($v9ea12a829c); } else { $v9ea12a829c = $v3264112bb5; $v3e419d0061 = ""; $pc661dc6b = null; if ($v9ea12a829c) $pc661dc6b = SQLQueryHandler::getTableFromColumn($v9ea12a829c); } if($v9ea12a829c) { if (!$pc661dc6b) $pc661dc6b = $v8c5df8072b; $pc6e4a31b = self::prepareTableAttributeWithFunction($v9ea12a829c, $pc661dc6b); if(!in_array($pc6e4a31b, $pd264077c)) { $pd264077c[] = $pc6e4a31b; $v783f2dc478 .= ($v783f2dc478 ? ", " : "") . $pc6e4a31b; } $v2bba73adc8 .= ($v2bba73adc8 ? " AND " : "") . $v3e419d0061; } } $v3c76382d93 = ""; if($v783f2dc478) { $v3c76382d93 .= " GROUP BY " . $v783f2dc478; if($v2bba73adc8) { $v3c76382d93 .= " HAVING " . $v2bba73adc8; } } return $v3c76382d93; } protected static function getSQLAttributes($pfdbbc383) { $v3c76382d93 = ""; if (is_array($pfdbbc383) && count($pfdbbc383)) { $v9de5864376 = array_keys($pfdbbc383) === range(0, count($pfdbbc383) - 1); foreach($pfdbbc383 as $v5e45ec9bb9 => $v23482c1cb9) { if ($v9de5864376) $v5e45ec9bb9 = $v23482c1cb9; if($v5e45ec9bb9) { $v1b0cfa478b = $v5e45ec9bb9 == "*" ? "*" : SQLQueryHandler::getParsedSqlColumnName($v5e45ec9bb9); $v3c76382d93 .= (strlen($v3c76382d93) ? ", " : "") . $v1b0cfa478b . ($v23482c1cb9 && $v23482c1cb9 != $v5e45ec9bb9 ? " AS \"" . $v23482c1cb9 . "\"" : ""); } } } else $v3c76382d93 = "*"; return $v3c76382d93; } public static function getSQLConditions($paf1bc6f6, $v3c581c912b = false, $v988022b1bb = "") { $v3c76382d93 = ""; if (is_array($paf1bc6f6)) { $v3c581c912b = $v3c581c912b ? strtoupper($v3c581c912b) : null; $v3c581c912b = $v3c581c912b == "AND" || $v3c581c912b == "OR" ? $v3c581c912b : "AND"; foreach ($paf1bc6f6 as $pbfa01ed1 => $v67db1bd535) { $v8a5955cc77 = strtoupper($pbfa01ed1); if ($v8a5955cc77 == "AND" || $v8a5955cc77 == "OR" || (is_numeric($pbfa01ed1) && is_array($v67db1bd535))) { $v47cf45a27e = is_array($v67db1bd535) ? self::getSQLConditions($v67db1bd535, $v8a5955cc77, $v988022b1bb) : (is_string($v67db1bd535) && $v67db1bd535 ? $v67db1bd535 : ""); $v3c76382d93 .= $v47cf45a27e ? ($v3c76382d93 ? " $v3c581c912b " : "") . "(" . $v47cf45a27e . ")" : ""; } else { $v3c76382d93 .= $v3c76382d93 ? " $v3c581c912b " : ""; $v35ba6156cd = self::prepareTableAttributeWithFunction($pbfa01ed1, $v988022b1bb); if (is_array($v67db1bd535)) { $v651d593e1f = array_keys($v67db1bd535) !== range(0, count($v67db1bd535) - 1); if ($v651d593e1f) $v67db1bd535 = array($v67db1bd535); $v9a8b7dc209 = ''; foreach ($v67db1bd535 as $v956913c90f) { $v9a8b7dc209 .= ($v9a8b7dc209 ? " $v3c581c912b " : ""); if (is_array($v956913c90f)) { $v19a7745bc6 = "="; $v2b3a130180 = ""; foreach ($v956913c90f as $pe5c5e2fe => $pbde5fb24) { $pe5c5e2fe = strtolower($pe5c5e2fe); if ($pe5c5e2fe == "operator") $v19a7745bc6 = strtolower($pbde5fb24); else if ($pe5c5e2fe == "value") $v2b3a130180 = $pbde5fb24; } if ($v19a7745bc6 == "in" || $v19a7745bc6 == "not in") $v9a8b7dc209 .= "$v35ba6156cd $v19a7745bc6 " . self::createBaseExprValueForOperatorIn($v2b3a130180); else if ($v19a7745bc6 == "is" || $v19a7745bc6 == "is not") $v9a8b7dc209 .= "$v35ba6156cd $v19a7745bc6 " . self::createBaseExprValueForOperatorIs($v2b3a130180); else $v9a8b7dc209 .= "$v35ba6156cd $v19a7745bc6 " . self::createBaseExprValue($v2b3a130180); } else $v9a8b7dc209 .= "$v35ba6156cd = " . self::createBaseExprValue($v956913c90f); } $v3c76382d93 .= $v9a8b7dc209; } else $v3c76382d93 .= "$v35ba6156cd = " . self::createBaseExprValue($v67db1bd535); } } } return $v3c76382d93; } protected static function getSQLSort($pdab26aff) { $v3c76382d93 = ""; if(is_array($pdab26aff) && count($pdab26aff)) { foreach($pdab26aff as $v629e3f04ae) { if(is_array($v629e3f04ae)) { $pf1c499cb = ""; $pca9d089e = ""; foreach($v629e3f04ae as $pbfa01ed1 => $v67db1bd535) { switch(strtolower($pbfa01ed1)) { case "column": $pf1c499cb = $v67db1bd535; break; case "order": $pca9d089e = $v67db1bd535; break; } } if($pf1c499cb) $v3c76382d93 .= ($v3c76382d93 ? ", " : "") . SQLQueryHandler::getParsedSqlColumnName($pf1c499cb) . " {$pca9d089e}"; } } } return $v3c76382d93; } protected static function prepareTableAttributeWithFunction($v5e45ec9bb9, $v8c5df8072b = false) { if (strpos($v5e45ec9bb9, "(") !== false) { $v4430104888 = strrpos($v5e45ec9bb9, "(") + 1; $pbaeb17fb = strpos($v5e45ec9bb9, ")", $v4430104888); $pbaeb17fb = $pbaeb17fb >= $v4430104888 ? $pbaeb17fb : strlen($v5e45ec9bb9); $pbd63d088 = substr($v5e45ec9bb9, 0, $v4430104888); $pdaf015e8 = substr($v5e45ec9bb9, $v4430104888, $pbaeb17fb - $v4430104888); $v3e6a104d4d = substr($v5e45ec9bb9, $pbaeb17fb); $pdaf015e8 = self::prepareTableAttributeName($pdaf015e8, $v8c5df8072b); return $pbd63d088 . $pdaf015e8 . $v3e6a104d4d; } return self::prepareTableAttributeName($v5e45ec9bb9, $v8c5df8072b); } protected static function prepareTableAttributeName($v5e45ec9bb9, $v8c5df8072b = false) { $v5e45ec9bb9 = trim($v5e45ec9bb9); $pbd1bc7b0 = strrpos($v5e45ec9bb9, "."); if ($pbd1bc7b0 !== false) { $pd98df87c = SQLQueryHandler::removeInvalidCharsFromName(substr($v5e45ec9bb9, 0, $pbd1bc7b0)); $v8c5df8072b = $pd98df87c ? $pd98df87c : $v8c5df8072b; $v5e45ec9bb9 = SQLQueryHandler::removeInvalidCharsFromName(substr($v5e45ec9bb9, $pbd1bc7b0 + 1)); } $pd98df87c = $v8c5df8072b ? SQLQueryHandler::getParsedSqlTableName($v8c5df8072b) . "." : ""; $v5e45ec9bb9 = $v5e45ec9bb9 == "*" ? "*" : SQLQueryHandler::getParsedSqlColumnName($v5e45ec9bb9); return $pd98df87c . $v5e45ec9bb9; } public static function createBaseExprValue($v67db1bd535) { $v0b80020f7e = get_class(); $v3ae55a9a2e = new ReflectionClass($v0b80020f7e); $pa0d4d0cd = $v3ae55a9a2e->isAbstract(); if (!$pa0d4d0cd) { $pdbbe5385 = self::isReservedWord($v67db1bd535); $v3d15cdb3d4 = self::isReservedWordFunction($v67db1bd535); } return $v67db1bd535 == "DEFAULT" || $pdbbe5385 || $v3d15cdb3d4 ? $v67db1bd535 : SQLQueryHandler::createBaseExprValue($v67db1bd535); } public static function createBaseExprValueForOperatorIn($v67db1bd535) { $pccce8e2a = function($v956913c90f) { return self::createBaseExprValue($v956913c90f); }; return SQLQueryHandler::createBaseExprValueForOperatorIn($v67db1bd535, $pccce8e2a); } public static function createBaseExprValueForOperatorIs($v67db1bd535) { $pccce8e2a = function($v956913c90f) { return self::createBaseExprValue($v956913c90f); }; return SQLQueryHandler::createBaseExprValueForOperatorIs($v67db1bd535, $pccce8e2a); } } ?>
