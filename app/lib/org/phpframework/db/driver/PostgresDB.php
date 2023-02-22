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

 include_once get_lib("org.phpframework.db.DB"); include_once get_lib("org.phpframework.db.statement.PostgresDBStatement"); include_once get_lib("org.phpframework.db.property.PostgresDBProperty"); include_once get_lib("org.phpframework.db.static.PostgresDBStatic"); class PostgresDB extends DB { use PostgresDBStatement; use PostgresDBProperty; use PostgresDBStatic; const DEFAULT_DB_NAME = 'postgres'; public function __construct() { if (!$this->default_php_extension_type) { $v3d648f7ed6 = self::getAvailablePHPExtensionTypes(); $this->default_php_extension_type = $v3d648f7ed6[0]; } } public function parseDSN($v56c929bbd8) { return self::convertDSNToOptions($v56c929bbd8); } public function getDSN($v5d3813882f = null) { $v5d3813882f = $v5d3813882f ? $v5d3813882f : $this->options; $v56c929bbd8 = in_array("pdo", self::$available_php_extension_types) ? 'odbc:' : ''; $v5b5ec93636 = $v5d3813882f["odbc_driver"] ? $v5d3813882f["odbc_driver"] : null; $v7baa5c4751 = $v5d3813882f["odbc_data_source"] ? $v5d3813882f["odbc_data_source"] : null; $v27ebcf3049 = $v87b039ee1a = false; $v4470ec9925 = in_array("pdo", self::$available_php_extension_types); if ($v7baa5c4751) $v56c929bbd8 .= $v7baa5c4751; else if (!$v5d3813882f["host"] && self::$default_odbc_data_source) $v56c929bbd8 .= self::$default_odbc_data_source; else if ($v5b5ec93636) $v56c929bbd8 .= 'Driver={' . $v5b5ec93636 . '};'; else if (self::$default_odbc_driver) $v56c929bbd8 .= 'Driver={' . self::$default_odbc_driver . '};'; else if ($v4470ec9925) $v56c929bbd8 = 'pgsql:'; if ($v4470ec9925) $v27ebcf3049 = $v87b039ee1a = true; if ($v5d3813882f["host"]) $v56c929bbd8 .= ($v27ebcf3049 ? 'host' : 'Server') . "=" . $v5d3813882f["host"] . ($v5d3813882f["port"] ? ':' . $v5d3813882f["port"] : '') . ';'; if ($v5d3813882f["db_name"]) $v56c929bbd8 .= ($v87b039ee1a ? 'dbname' : 'Database') . '=' . $v5d3813882f["db_name"] . ';'; if ($v5d3813882f["encoding"]) $v56c929bbd8 .= 'client_encoding=' . $v5d3813882f["encoding"] . ';'; if ($v5d3813882f["extra_dsn"]) $v56c929bbd8 .= $v5d3813882f["extra_dsn"]; return $v56c929bbd8; } public function getVersion() { if ($this->link) switch ($this->default_php_extension_type) { case "pg": $v872c4849e0 = pg_version($this->link); return $v872c4849e0 ? $v872c4849e0["client"] : null; case "pdo": return $this->link->getAttribute(PDO::ATTR_SERVER_VERSION); case "odbc": return null; } return null; } public function connect() { $this->db_selected = false; try{ if ($this->link) $this->close(); $this->default_php_extension_type = $this->options["extension"] ? $this->options["extension"] : $this->default_php_extension_type; switch ($this->default_php_extension_type) { case "pg": $pba3eadf8 = "host=" . $this->options["host"] . " ".(is_numeric($this->options["port"]) ? " port=" . $this->options["port"] : "") . " dbname=" . $this->options["db_name"] . " user=" . $this->options["username"] . " " . ($this->options["password"] ? "password=" . $this->options["password"] : ""); if($this->options["persistent"]) $this->link = $this->options["new_link"] ? pg_pconnect($pba3eadf8, PGSQL_CONNECT_FORCE_NEW) : pg_pconnect($pba3eadf8); else $this->link = $this->options["new_link"] ? pg_connect($pba3eadf8, PGSQL_CONNECT_FORCE_NEW) : pg_connect($pba3eadf8); break; case "pdo": $pc1d077f0 = $this->options["pdo_settings"] ? $this->options["pdo_settings"] : array(); if (!array_key_exists(PDO::ATTR_ERRMODE, $pc1d077f0)) $pc1d077f0[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; if($this->options["persistent"] && !$this->options["new_link"]) $pc1d077f0[PDO::ATTR_PERSISTENT] = true; $v56c929bbd8 = self::getDSN($this->options); $this->link = new PDO($v56c929bbd8, $this->options["username"], $this->options["password"], $pc1d077f0); break; case "odbc": $v56c929bbd8 = self::getDSN($this->options); if($this->options["persistent"] && !$this->options["new_link"]) $this->link = odbc_pconnect($v56c929bbd8, $this->options["username"], $this->options["password"]); else $this->link = odbc_connect($v56c929bbd8, $this->options["username"], $this->options["password"]); break; } if ($this->link) { if ($this->options["encoding"] && !$this->setCharset($this->options["encoding"])) $this->close(); else if ($this->options["db_name"]) $this->db_selected = true; } if (!$this->link || !$this->db_selected) { $paec2c009 = null; $v0f9512fda4 = $this->default_php_extension_type == "pg" ? pg_last_error() : ($this->default_php_extension_type == "odbc" ? odbc_errormsg() : null); if ($v0f9512fda4) $paec2c009 = new Exception("Failed to connect to PGSQL: " . $v0f9512fda4); launch_exception(new SQLException(1, $paec2c009, $this->options)); } } catch(Exception $paec2c009) { launch_exception(new SQLException(1, $paec2c009, $this->options)); } return $this->db_selected; } public function connectWithoutDB() { try{ if ($this->link) $this->close(); $this->default_php_extension_type = $this->options["extension"] ? $this->options["extension"] : $this->default_php_extension_type; switch ($this->default_php_extension_type) { case "pg": $pba3eadf8 = "host=" . $this->options["host"] . " ".(is_numeric($this->options["port"]) ? " port=" . $this->options["port"] : "") . " dbname=" . self::DEFAULT_DB_NAME . " user=" . $this->options["username"] . " ". ($this->options["password"] ? "password=" . $this->options["password"] : ""); if($this->options["persistent"]) $this->link = $this->options["new_link"] ? pg_pconnect($pba3eadf8, PGSQL_CONNECT_FORCE_NEW) : pg_pconnect($pba3eadf8); else $this->link = $this->options["new_link"] ? pg_connect($pba3eadf8, PGSQL_CONNECT_FORCE_NEW) : pg_connect($pba3eadf8); break; case "pdo": $pc1d077f0 = $this->options["pdo_settings"] ? $this->options["pdo_settings"] : array(); if (!array_key_exists(PDO::ATTR_ERRMODE, $pc1d077f0)) $pc1d077f0[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; if($this->options["persistent"] && !$this->options["new_link"]) $pc1d077f0[PDO::ATTR_PERSISTENT] = true; $v5d3813882f = $this->options; $v5d3813882f["db_name"] = self::DEFAULT_DB_NAME; $v56c929bbd8 = self::getDSN($v5d3813882f); $this->link = new PDO($v56c929bbd8, $this->options["username"], $this->options["password"], $pc1d077f0); break; case "odbc": $v5d3813882f = $this->options; $v5d3813882f["db_name"] = self::DEFAULT_DB_NAME; $v56c929bbd8 = self::getDSN($v5d3813882f); if($this->options["persistent"] && !$this->options["new_link"]) $this->link = odbc_pconnect($v56c929bbd8, $this->options["username"], $this->options["password"]); else $this->link = odbc_connect($v56c929bbd8, $this->options["username"], $this->options["password"]); break; } if ($this->link) { if ($this->options["encoding"] && !$this->setCharset($this->options["encoding"])) $this->close(); } if (!$this->link) { $paec2c009 = null; $v0f9512fda4 = $this->default_php_extension_type == "pg" ? pg_last_error() : ($this->default_php_extension_type == "odbc" ? odbc_errormsg() : null); if ($v0f9512fda4) $paec2c009 = new Exception("Failed to connect to PGSQL: " . $v0f9512fda4); launch_exception(new SQLException(1, $paec2c009, $this->options)); } } catch(Exception $paec2c009) { launch_exception(new SQLException(1, $paec2c009, $this->options)); } return $this->link ? true : false; } public function close() { try { if ($this->link) { $v5b6376bac9 = true; switch ($this->default_php_extension_type) { case "pg": $v5b6376bac9 = pg_close($this->link); break; case "odbc": $v5b6376bac9 = odbc_close($this->link); break; } if ($v5b6376bac9) { $this->db_selected = false; $this->link = null; return true; } } return false; }catch(Exception $paec2c009) { return launch_exception(new SQLException(3, $paec2c009)); } } public function setCharset($pbebc8cef = "unicode") { $this->init(); try { switch ($this->default_php_extension_type) { case "pg": return pg_set_client_encoding($this->link, strtoupper($pbebc8cef)) != -1; case "pdo": return $this->link->query("SET NAMES '$pbebc8cef'"); case "odbc": return odbc_exec($this->link, "SET NAMES '$pbebc8cef'"); } return false; }catch(Exception $paec2c009) { return launch_exception(new SQLException(20, $paec2c009, $pbebc8cef)); } } public function selectDB($pb67a2609) { $this->init(); try { if ($pb67a2609) { $pe78026e8 = $this->options["db_name"]; $this->options["db_name"] = $pb67a2609; $v5c1c342594 = $this->connect(); $this->options["db_name"] = $pe78026e8; return $v5c1c342594; } }catch(Exception $paec2c009) { return launch_exception(new SQLException(2, $paec2c009, array($pb67a2609))); } } public function errno() { try { if ($this->link) { switch ($this->default_php_extension_type) { case "pg": return 0; case "pdo": return $this->link->errorCode(); case "odbc": return odbc_error($this->link); } } return -1; }catch(Exception $paec2c009) { return launch_exception(new SQLException(4, $paec2c009)); } } public function error() { try { if ($this->link) { switch ($this->default_php_extension_type) { case "pg": return pg_last_error($this->link); case "pdo": return $this->link->errorInfo(); case "odbc": return odbc_errormsg($this->link); } } return "This db driver was not initialized yet! Please call the connect method first!"; }catch(Exception $paec2c009) { return launch_exception(new SQLException(5, $paec2c009)); } } public function execute(&$v3c76382d93, $v5d3813882f = false) { $this->init(); try { $v3c76382d93 = self::replaceSQLEnclosingDelimiter(trim($v3c76382d93), "`", self::getEnclosingDelimiters()); switch ($this->default_php_extension_type) { case "pg": return pg_query($this->link, $v3c76382d93); case "pdo": return $this->link->exec($v3c76382d93); case "odbc": return odbc_exec($this->link, $v3c76382d93); } } catch(Exception $paec2c009) { return launch_exception(new SQLException(6, $paec2c009, array($v3c76382d93))); } } public function query(&$v3c76382d93, $v5d3813882f = false) { $this->init(); try { if (is_array($v5d3813882f) && preg_match("/^select\s+/i", trim($v3c76382d93))) { if (substr($v3c76382d93, -1) == ";") $v3c76382d93 = substr($v3c76382d93, 0, -1); if ($v5d3813882f["sort"]) { $pdab26aff = self::addSortOptionsToSQL($v5d3813882f["sort"]); if ($pdab26aff) { if (stripos($v3c76382d93, " limit ") !== false) $v3c76382d93 = "SELECT * FROM (" . $v3c76382d93 . ") AS QUERY_WITH_SORTING ORDER BY " . $pdab26aff; else $v3c76382d93 .= " ORDER BY " . $pdab26aff; } } if (stripos($v3c76382d93, "limit") && preg_match("/\s+limit\s*([0-9]+)(|\s*,\s*([0-9]+))\s*$/i", $v3c76382d93, $v87ae7286da)) { if (!$v5d3813882f["start"]) $v5d3813882f["start"] = $v87ae7286da[1]; if (!$v5d3813882f["limit"]) $v5d3813882f["limit"] = $v87ae7286da[3]; $v3c76382d93 = preg_replace("/\s+limit\s*([0-9]+)(|\s*,\s*([0-9]+))\s*$/i", "", $v3c76382d93); } if(isset($v5d3813882f["limit"]) && is_numeric($v5d3813882f["limit"])) { if (stripos($v3c76382d93, " order by ") !== false) $v3c76382d93 = "SELECT * FROM (" . $v3c76382d93 . ") AS QUERY_WITH_PAGINATION LIMIT " . $v5d3813882f["limit"] . " OFFSET " . ($v5d3813882f["start"] ? $v5d3813882f["start"] : 0); else $v3c76382d93 .= " LIMIT " . $v5d3813882f["limit"] . " OFFSET " . ($v5d3813882f["start"] ? $v5d3813882f["start"] : 0); } } $v3c76382d93 = self::replaceSQLEnclosingDelimiter(trim($v3c76382d93), "`", self::getEnclosingDelimiters()); switch ($this->default_php_extension_type) { case "pg": return pg_query($this->link, $v3c76382d93); case "pdo": return $this->link->query($v3c76382d93); case "odbc": return odbc_exec($this->link, $v3c76382d93); } }catch(Exception $paec2c009) { return launch_exception(new SQLException(6, $paec2c009, array($v3c76382d93))); } } public function freeResult($v9ad1385268) { try { switch ($this->default_php_extension_type) { case "pg": return pg_free_result($v9ad1385268); case "pdo": return $v9ad1385268->closeCursor(); case "odbc": return odbc_free_result($v9ad1385268); } }catch(Exception $paec2c009) { return launch_exception(new SQLException(7, $paec2c009, array($v9ad1385268))); } } public function numRows($v9ad1385268) { try { switch ($this->default_php_extension_type) { case "pg": return pg_num_rows($v9ad1385268); case "pdo": return $v9ad1385268->rowCount(); case "odbc": return odbc_num_rows($v9ad1385268); } }catch(Exception $paec2c009) { return launch_exception(new SQLException(13, $paec2c009, array($v9ad1385268))); } } public function numFields($v9ad1385268) { try { switch ($this->default_php_extension_type) { case "pg": return pg_num_fields($v9ad1385268); case "pdo": return $v9ad1385268->columnCount(); case "odbc": return odbc_num_fields($v9ad1385268); } }catch(Exception $paec2c009) { return launch_exception(new SQLException(14, $paec2c009, array($v9ad1385268))); } } public function fetchArray($v9ad1385268, $v8966764c3b = false) { try { switch ($this->default_php_extension_type) { case "pg": if ($v8966764c3b == DB::FETCH_OBJECT) return pg_fetch_object($v9ad1385268); $v8966764c3b = $this->med4121014952($v8966764c3b ? $v8966764c3b : DB::FETCH_BOTH); return pg_fetch_array($v9ad1385268, null, $v8966764c3b); case "pdo": if ($v8966764c3b == DB::FETCH_OBJECT) return $v9ad1385268->fetch(PDO::FETCH_OBJ); $v8966764c3b = $this->med4121014952($v8966764c3b ? $v8966764c3b : DB::FETCH_BOTH); return $v9ad1385268->fetch($v8966764c3b); case "odbc": if ($v8966764c3b == DB::FETCH_OBJECT) return odbc_fetch_object($v9ad1385268); $v651d593e1f = false; $v62e186f8df = false; if ($v8966764c3b == DB::FETCH_ASSOC || $v8966764c3b == DB::FETCH_BOTH || !$v8966764c3b) { $v62e186f8df = odbc_fetch_array($v9ad1385268); $v651d593e1f = true; } if ($v8966764c3b == DB::FETCH_NUM || $v8966764c3b == DB::FETCH_BOTH || !$v8966764c3b) $v62e186f8df = is_array($v62e186f8df) ? array_merge($v62e186f8df, array_values($v62e186f8df)) : ($v651d593e1f ? $v62e186f8df : odbc_fetch_row($v9ad1385268)); return $v62e186f8df; } } catch(Exception $paec2c009) { return launch_exception(new SQLException(8, $paec2c009, array($v9ad1385268, $v8966764c3b))); } } public function fetchField($v9ad1385268, $pac65f06f) { try { try { switch ($this->default_php_extension_type) { case "pg": $v5d170b1de6 = new stdClass(); $v5d170b1de6->name = pg_field_name($v9ad1385268, $pac65f06f); $v5d170b1de6->type = pg_field_type($v9ad1385268, $pac65f06f); $v5d170b1de6->length = pg_field_prtlen($v9ad1385268, $v5d170b1de6->name); $v5d170b1de6->max_length = pg_field_size($v9ad1385268, $pac65f06f); $v5d170b1de6->not_null = empty(pg_field_is_null($v9ad1385268, $pac65f06f)); break; case "pdo": $v5d170b1de6 = $v9ad1385268->getColumnMeta($pac65f06f); if ($v5d170b1de6) self::preparePDOField($v5d170b1de6); break; case "odbc": $v5d170b1de6 = new stdClass(); $v5d170b1de6->name = odbc_field_name($v9ad1385268, $pac65f06f); $v5d170b1de6->type = strtolower( odbc_field_type($v9ad1385268, $pac65f06f) ); $v5d170b1de6->length = odbc_field_len($v9ad1385268, $pac65f06f); $v5d170b1de6->precision = odbc_field_precision($v9ad1385268, $pac65f06f); $v5d170b1de6->scale = odbc_field_scale($v9ad1385268, $pac65f06f); $v5d170b1de6->not_null = null; break; } } catch (PDOException $paec2c009) { } if ($v5d170b1de6) { $v5d170b1de6->type = self::convertColumnTypeFromDB($v5d170b1de6->type, $pe1390784); if ($pe1390784) foreach ($pe1390784 as $pe5c5e2fe => $v956913c90f) $v5d170b1de6->$pe5c5e2fe = $v956913c90f; } return $v5d170b1de6; }catch(Exception $paec2c009) { return launch_exception(new SQLException(12, $paec2c009, array($v9ad1385268, $pac65f06f))); } } public function isResultValid($v9ad1385268) { switch ($this->default_php_extension_type) { case "pdo": return is_a($v9ad1385268, "PDOStatement"); } return is_resource($v9ad1385268); } public function listTables($pb67a2609 = false, $v5d3813882f = false) { $pac4bc40a = array(); $pb67a2609 = $pb67a2609 ? $pb67a2609 : (!$this->isDBSelected() && $this->options["db_name"] ? $this->options["db_name"] : null); $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $v5d3813882f["return_type"] = "result"; $v3c76382d93 = self::getTablesStatement($pb67a2609, $this->options); $v9ad1385268 = $this->getData($v3c76382d93, $v5d3813882f); if($v9ad1385268) foreach ($v9ad1385268 as $pc661dc6b) $pac4bc40a[] = array( "name" => ($pc661dc6b["table_schema"] && !$this->options["schema"] ? $pc661dc6b["table_schema"] . "." : "") . $pc661dc6b["table_name"], "table_name" => $pc661dc6b["table_name"], "schema" => $pc661dc6b["table_schema"], ); return $pac4bc40a; } public function listTableFields($pc661dc6b, $v5d3813882f = false) { $v86e5d83e26 = array(); $pb67a2609 = !$this->isDBSelected() && $this->options["db_name"] ? $this->options["db_name"] : null; $v3c76382d93 = self::getTableFieldsStatement($pc661dc6b, $pb67a2609, $this->options); $pbec62cc6 = self::parseTableName($pc661dc6b, $v5d3813882f); $v8c5df8072b = $pbec62cc6["name"]; if (!$this->options["db_name"]) return launch_exception(new SQLException(19, null, $v3c76382d93)); $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $v5d3813882f["return_type"] = "result"; $v9ad1385268 = $this->getData($v3c76382d93, $v5d3813882f); if($v9ad1385268) foreach ($v9ad1385268 as $v5d170b1de6) { $pfd5c64b3 = self::mbc5857c3293f($v5d170b1de6["column_name"], $v5d170b1de6["check_constraint_value"]); $v3c261bfc96 = isset($pfd5c64b3) && is_numeric($pfd5c64b3) && $pfd5c64b3 >= 0; $pe1390784 = null; $v0911c6122e = $v5d170b1de6["character_maximum_length"] ? $v5d170b1de6["character_maximum_length"] : $v5d170b1de6["numeric_precision"]; $v5d170b1de6["is_primary"] = explode(",", str_replace(array("{", "}"), "", strtolower(trim($v5d170b1de6["is_primary"])))); $v5d170b1de6["is_unique"] = explode(",", str_replace(array("{", "}"), "", strtolower(trim($v5d170b1de6["is_unique"])))); $v926576f1c1 = in_array("t", $v5d170b1de6["is_primary"]) || in_array(true, $v5d170b1de6["is_primary"], true); $v9073377656 = array( "name" => $v5d170b1de6["column_name"], "type" => self::convertColumnTypeFromDB($v5d170b1de6["data_type"], $pe1390784), "length" => $v0911c6122e, "null" => strtolower(trim($v5d170b1de6["is_nullable"])) == "no" ? false : true, "primary_key" => $v926576f1c1, "unique" => $v926576f1c1 || in_array("t", $v5d170b1de6["is_unique"]) || in_array(true, $v5d170b1de6["is_unique"], true) || $v5d170b1de6["unique_constraint_name"] ? true : false, "unsigned" => $v3c261bfc96, "default" => isset($v5d170b1de6["column_default"]) ? str_replace(array("::character varying", "'"), "", $v5d170b1de6["column_default"]) : null, "charset" => $v5d170b1de6["character_set_name"], "collation" => $v5d170b1de6["collation_name"], "extra" => $v5d170b1de6["extra"], "comment" => $v5d170b1de6["column_comment"], ); if ($v5d170b1de6["check_constraint_value"]) $v9073377656["extra"] .= ($v9073377656["extra"] ? " " : "") . "CHECK " . $v5d170b1de6["check_constraint_value"]; $v9dc18650df = $v8c5df8072b . "_" . $v5d170b1de6["column_name"] . "_seq"; $v6c33401cc3 = in_array($v5d170b1de6["data_type"], array("serial", "smallserial", "bigserial")) || stripos($v5d170b1de6["column_default"], "nextval('$v9dc18650df") !== false; if ($pe1390784) foreach ($pe1390784 as $pe5c5e2fe => $v956913c90f) { if ($v956913c90f && $pe5c5e2fe == "auto_increment") $v6c33401cc3 = true; else $v9073377656[$pe5c5e2fe] = $v956913c90f; } if ($v6c33401cc3) $v9073377656["extra"] .= ($v9073377656["extra"] ? " " : "") . "auto_increment"; $v9073377656["auto_increment"] = $v6c33401cc3; $v86e5d83e26[ $v5d170b1de6["column_name"] ] = $v9073377656; } return $v86e5d83e26; } private static function mbc5857c3293f($pf49225a7, $v485386d9ba) { $v50493f8454 = "($pf49225a7 > ("; $v4430104888 = strpos($v485386d9ba, $v50493f8454); if ($v4430104888 === false) { $v50493f8454 = "($pf49225a7 > "; $v4430104888 = strpos($v485386d9ba, $v50493f8454); } if ($v4430104888 !== false) { $pbaeb17fb = strpos($v485386d9ba, ")", $v4430104888 + strlen($v50493f8454)); if ($pbaeb17fb !== false) { $v4430104888 = $v4430104888 + strlen($v50493f8454); $v05c929c77a = substr($v485386d9ba, $v4430104888, $pbaeb17fb - $v4430104888); return (int) $v05c929c77a; } } return null; } public function getInsertedId($v5d3813882f = false) { if ($this->init()) switch ($this->default_php_extension_type) { case "pdo": try { return $this->link->lastInsertId(); } catch (Exception $paec2c009) { } case "pg": case "odbc": $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $v5d3813882f["return_type"] = "result"; $v9ad1385268 = $this->getData("SELECT lastval() AS id", $v5d3813882f); if ($v9ad1385268) return $v9ad1385268[0]["id"]; } return 0; } private function med4121014952($pea108151) { if ($this->default_php_extension_type == "pg") switch ($pea108151) { case DB::FETCH_ASSOC: return PGSQL_ASSOC; case DB::FETCH_NUM: return PGSQL_NUM; case DB::FETCH_BOTH: return PGSQL_BOTH; } return self::convertFetchTypeToPDOAndODBCExtensions($this->default_php_extension_type, $pea108151); } } ?>
