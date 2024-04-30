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
 include_once get_lib("org.phpframework.db.DB"); include_once get_lib("org.phpframework.db.statement.MSSqlDBStatement"); include_once get_lib("org.phpframework.db.property.MSSqlDBProperty"); include_once get_lib("org.phpframework.db.static.MSSqlDBStatic"); class MSSqlDB extends DB { use MSSqlDBStatement; use MSSqlDBProperty; use MSSqlDBStatic; const DEFAULT_DB_NAME = 'msdb'; public function __construct() { if (!$this->default_php_extension_type) { $v3d648f7ed6 = self::getAvailablePHPExtensionTypes(); $this->default_php_extension_type = $v3d648f7ed6[0]; } } public function parseDSN($v56c929bbd8) { return self::convertDSNToOptions($v56c929bbd8); } public function getDSN($v5d3813882f = null) { $v5d3813882f = $v5d3813882f ? $v5d3813882f : $this->options; $v56c929bbd8 = in_array("pdo", self::$available_php_extension_types) ? 'odbc:' : ''; $v5b5ec93636 = !empty($v5d3813882f["odbc_driver"]) ? $v5d3813882f["odbc_driver"] : null; $v7baa5c4751 = !empty($v5d3813882f["odbc_data_source"]) ? $v5d3813882f["odbc_data_source"] : null; $v27ebcf3049 = $v87b039ee1a = false; $v4470ec9925 = in_array("pdo", self::$available_php_extension_types); if ($v7baa5c4751) $v56c929bbd8 .= $v7baa5c4751 . (substr($v7baa5c4751, -1) == ";" ? "" : ";"); else if (empty($v5d3813882f["host"]) && self::$default_odbc_data_source) $v56c929bbd8 .= self::$default_odbc_data_source . (substr(self::$default_odbc_data_source, -1) == ";" ? "" : ";"); else if ($v5b5ec93636) $v56c929bbd8 .= 'Driver={' . $v5b5ec93636 . '};'; else if (self::$default_odbc_driver) $v56c929bbd8 .= 'Driver={' . self::$default_odbc_driver . '};'; else if ($v4470ec9925) $v56c929bbd8 = 'sqlsvr:'; if (!empty($v5d3813882f["host"])) $v56c929bbd8 .= ($v27ebcf3049 ? 'host' : 'Server') . "=" . $v5d3813882f["host"] . (!empty($v5d3813882f["port"]) ? ':' . $v5d3813882f["port"] : '') . ';'; if (!empty($v5d3813882f["db_name"])) $v56c929bbd8 .= ($v87b039ee1a ? 'dbname' : 'Database') . '=' . $v5d3813882f["db_name"] . ';'; if (!empty($v5d3813882f["encoding"])) $v56c929bbd8 .= 'charset=' . $v5d3813882f["encoding"] . ';'; if (!empty($v5d3813882f["extra_dsn"])) $v56c929bbd8 .= $v5d3813882f["extra_dsn"]; return $v56c929bbd8; } public function getVersion() { if ($this->link) switch ($this->default_php_extension_type) { case "sqlsrv": $pba23d78c = sqlsrv_server_info($this->link); return isset($pba23d78c["SQLServerVersion"]) ? $pba23d78c["SQLServerVersion"] : null; case "pdo": return $this->link->getAttribute(PDO::ATTR_SERVER_VERSION); case "odbc": return null; } return null; } public function connect() { $this->db_selected = false; try{ if ($this->link) $this->close(); $this->default_php_extension_type = !empty($this->options["extension"]) ? $this->options["extension"] : $this->default_php_extension_type; switch ($this->default_php_extension_type) { case "sqlsrv": $v8a4ed461b2 = isset($this->options["host"]) ? $this->options["host"] . (!empty($this->options["port"]) ? ', ' . $this->options["port"] : '') : null; $v249a31d701 = array(); if (!empty($this->options["db_name"])) $v249a31d701["Database"] = $this->options["db_name"]; if (!empty($this->options["username"])) { $v249a31d701["UID"] = $this->options["username"]; if (!empty($this->options["password"])) $v249a31d701["PWD"] = $this->options["password"]; } if (!empty($this->options["encoding"])) $v249a31d701["CharacterSet"] = $this->options["encoding"]; $v5eaebbd806 = self::parseExtraSettings($this->options["extra_settings"]); if ($v5eaebbd806) foreach ($v5eaebbd806 as $pa7d67d68 => $pe5bfaa31) if ($pa7d67d68) $v249a31d701[$pa7d67d68] = $pe5bfaa31; $this->link = sqlsrv_connect($v8a4ed461b2, $v249a31d701); break; case "pdo": $pc1d077f0 = !empty($this->options["pdo_settings"]) ? $this->options["pdo_settings"] : array(); if (!array_key_exists(PDO::ATTR_ERRMODE, $pc1d077f0)) $pc1d077f0[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $pc1d077f0[PDO::ATTR_PERSISTENT] = true; $v27ce1b7a06 = self::parseExtraSettingsAsPDOSettings($this->options["extra_settings"]); if ($v27ce1b7a06) foreach ($v27ce1b7a06 as $v8b5ce22ebd => $v216fc3414a) if ($v8b5ce22ebd) $pc1d077f0[$v8b5ce22ebd] = $v216fc3414a; $v56c929bbd8 = self::getDSN($this->options); $this->link = new PDO( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null, $pc1d077f0 ); break; case "odbc": $v56c929bbd8 = self::getDSN($this->options); if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $this->link = odbc_pconnect( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null ); else $this->link = odbc_connect( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null ); break; } if ($this->link && !empty($this->options["db_name"])) $this->db_selected = true; if (!$this->link || !$this->db_selected) { $paec2c009 = null; $v0f9512fda4 = $this->default_php_extension_type == "sqlsrv" && sqlsrv_errors() ? print_r(sqlsrv_errors(), true) : ($this->default_php_extension_type == "odbc" ? odbc_errormsg() : null); if ($v0f9512fda4) $paec2c009 = new Exception("Failed to connect to MSSQL: " . $v0f9512fda4); launch_exception(new SQLException(1, $paec2c009, $this->options)); } } catch(Exception $paec2c009) { launch_exception(new SQLException(1, $paec2c009, $this->options)); } return $this->db_selected; } public function connectWithoutDB() { try{ if ($this->link) $this->close(); $this->default_php_extension_type = !empty($this->options["extension"]) ? $this->options["extension"] : $this->default_php_extension_type; switch ($this->default_php_extension_type) { case "sqlsrv": $v8a4ed461b2 = isset($this->options["host"]) ? $this->options["host"] . (!empty($this->options["port"]) ? ', ' . $this->options["port"] : '') : null; $v249a31d701 = array(); $v249a31d701["Database"] = self::DEFAULT_DB_NAME; if (!empty($this->options["username"])) { $v249a31d701["UID"] = $this->options["username"]; if (!empty($this->options["password"])) $v249a31d701["PWD"] = $this->options["password"]; } if (!empty($this->options["encoding"])) $v249a31d701["CharacterSet"] = $this->options["encoding"]; $this->link = sqlsrv_connect($pa33bcf7c, $v249a31d701); break; case "pdo": $pc1d077f0 = !empty($this->options["pdo_settings"]) ? $this->options["pdo_settings"] : array(); if (!array_key_exists(PDO::ATTR_ERRMODE, $pc1d077f0)) $pc1d077f0[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $pc1d077f0[PDO::ATTR_PERSISTENT] = true; $v5d3813882f = $this->options; $v5d3813882f["db_name"] = self::DEFAULT_DB_NAME; $v56c929bbd8 = self::getDSN($v5d3813882f); $this->link = new PDO( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null, $pc1d077f0 ); break; case "odbc": $v5d3813882f = $this->options; $v5d3813882f["db_name"] = self::DEFAULT_DB_NAME; $v56c929bbd8 = self::getDSN($v5d3813882f); if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $this->link = odbc_pconnect( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null ); else $this->link = odbc_connect( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null ); break; } if (!$this->link) { $paec2c009 = null; $v0f9512fda4 = $this->default_php_extension_type == "sqlsrv" && sqlsrv_errors() ? print_r(sqlsrv_errors(), true) : ($this->default_php_extension_type == "odbc" ? odbc_errormsg() : null); if ($v0f9512fda4) $paec2c009 = new Exception("Failed to connect to MSSQL: " . $v0f9512fda4); launch_exception(new SQLException(1, $paec2c009, $this->options)); } } catch(Exception $paec2c009) { launch_exception(new SQLException(1, $paec2c009, $this->options)); } return $this->link ? true : false; } public function close() { try { if ($this->link) { $v5b6376bac9 = true; switch ($this->default_php_extension_type) { case "sqlsrv": $v5b6376bac9 = sqlsrv_close($this->link); break; case "odbc": $v5b6376bac9 = odbc_close($this->link); break; } if ($v5b6376bac9) { $this->db_selected = false; $this->link = null; return true; } } return false; }catch(Exception $paec2c009) { return launch_exception(new SQLException(3, $paec2c009)); } } public function setCharset($pbebc8cef = "utf8") { $this->init(); try { return ini_set('mssql.charset', $pbebc8cef) !== false; }catch(Exception $paec2c009) { return launch_exception(new SQLException(20, $paec2c009, $pbebc8cef)); } } public function selectDB($pb67a2609) { $this->init(); try { return $pb67a2609 && $this->setData("use $pb67a2609"); }catch(Exception $paec2c009) { return launch_exception(new SQLException(2, $paec2c009, array($pb67a2609))); } } public function errno() { try { if ($this->link) { switch ($this->default_php_extension_type) { case "sqlsrv": $v8a29987473 = sqlsrv_errors(); if ($v8a29987473) foreach($v8a29987473 as $v0f9512fda4) return isset($v0f9512fda4["code"]) ? $v0f9512fda4["code"] : null; break; case "pdo": return $this->link->errorCode(); case "odbc": return odbc_error($this->link); } } return -1; }catch(Exception $paec2c009) { return launch_exception(new SQLException(4, $paec2c009)); } } public function error() { try { if ($this->link) { switch ($this->default_php_extension_type) { case "sqlsrv": $v8a29987473 = sqlsrv_errors(); $v1db8fcc7cd = ""; if ($v8a29987473) $v1db8fcc7cd = "SQLSTATE error code: " . (isset($v0f9512fda4["SQLSTATE"]) ? $v0f9512fda4["SQLSTATE"] : "") . "\nDriver-specific error code: " . (isset($v0f9512fda4["code"]) ? $v0f9512fda4["code"] : "") . "\nDriver-specific error message: " . (isset($v0f9512fda4["message"]) ? $v0f9512fda4["message"] : ""); return $v1db8fcc7cd; case "pdo": $v0f9512fda4 = $this->link->errorInfo(); $v1db8fcc7cd = ""; if ($v0f9512fda4) $v1db8fcc7cd = "SQLSTATE error code: " . (isset($v0f9512fda4[0]) ? $v0f9512fda4[0] : "") . "\nDriver-specific error code: " . (isset($v0f9512fda4[1]) ? $v0f9512fda4[1] : "") . "\nDriver-specific error message: " . (isset($v0f9512fda4[2]) ? $v0f9512fda4[2] : ""); return $v1db8fcc7cd; case "odbc": return odbc_errormsg($this->link); } } return "This db driver was not initialized yet! Please call the connect method first!"; }catch(Exception $paec2c009) { return launch_exception(new SQLException(5, $paec2c009)); } } public function execute(&$v3c76382d93, $v5d3813882f = false) { $this->init(); try { $v3c76382d93 = self::replaceSQLEnclosingDelimiter(trim($v3c76382d93), "`", self::getEnclosingDelimiters()); if ($v5d3813882f && !empty($v5d3813882f["hard_coded_ai_pk"]) && preg_match("/^insert\s+into\s+/i", trim($v3c76382d93))) { $v539082ff30 = SQLQueryHandler::parse($v3c76382d93); if (!empty($v539082ff30["table"])) { $pc661dc6b = strpos($v539082ff30["table"], "[") !== false ? $v539082ff30["table"] : "[" . $v539082ff30["table"] . "]"; $v3c76382d93 = "SET IDENTITY_INSERT $pc661dc6b ON; " . $v3c76382d93 . "; SET IDENTITY_INSERT $pc661dc6b OFF;"; } } preg_match_all("/(\s|;|)(GO\s*;?|(CREATE\s(VIEW|FUNCTION|PROCEDURE|TRIGGER|EVENT)))(\s|$)/i", $v3c76382d93, $pbae7526c, PREG_OFFSET_CAPTURE); $v02c7c6c783 = array(); if ($pbae7526c && $pbae7526c[2]) { $v4430104888 = 0; foreach ($pbae7526c[2] as $v87ae7286da) { $pbaeb17fb = $v87ae7286da[1]; $v582c762520 = strtoupper(substr(trim($v87ae7286da[0]), 0, 2)) == "GO"; $v9d1744e29c = substr($v3c76382d93, $v4430104888, $pbaeb17fb - $v4430104888); if (trim($v9d1744e29c)) $v02c7c6c783[] = $v9d1744e29c; $v4430104888 = $pbaeb17fb; if ($v582c762520) $v4430104888 += strlen($v87ae7286da[0]); } $v9d1744e29c = substr($v3c76382d93, $v4430104888); if (trim($v9d1744e29c)) $v02c7c6c783[] = $v9d1744e29c; } else $v02c7c6c783[] = $v3c76382d93; $v5c1c342594 = true; foreach ($v02c7c6c783 as $pa5d7127e) { $v9ad1385268 = null; switch ($this->default_php_extension_type) { case "sqlsrv": $v9ad1385268 = sqlsrv_query($this->link, $pa5d7127e); break; case "pdo": $v9ad1385268 = $this->link->exec($pa5d7127e); break; case "odbc": $v9ad1385268 = odbc_exec($this->link, $pa5d7127e); break; } if ($v9ad1385268 === false) $v5c1c342594 = false; else if ($v9ad1385268 && $this->isResultValid($v9ad1385268)) $this->freeResult($v9ad1385268); } return $v5c1c342594; } catch(Exception $paec2c009) { return launch_exception(new SQLException(6, $paec2c009, array($v3c76382d93))); } } public function query(&$v3c76382d93, $v5d3813882f = false) { $this->init(); try { if (is_array($v5d3813882f) && preg_match("/^select\s+/i", trim($v3c76382d93))) { if (substr($v3c76382d93, -1) == ";") $v3c76382d93 = substr($v3c76382d93, 0, -1); if (!empty($v5d3813882f["sort"])) { $pdab26aff = self::addSortOptionsToSQL($v5d3813882f["sort"]); if ($pdab26aff) { if (stripos($v3c76382d93, " limit ") !== false) $v3c76382d93 = "SELECT * FROM (" . $v3c76382d93 . ") AS QUERY_WITH_SORTING ORDER BY " . $pdab26aff; else $v3c76382d93 .= " ORDER BY " . $pdab26aff; } } if (stripos($v3c76382d93, "limit") && preg_match("/\s+limit\s*([0-9]+)(|\s*,\s*([0-9]+))\s*$/i", $v3c76382d93, $v87ae7286da)) { if (empty($v5d3813882f["start"])) $v5d3813882f["start"] = $v87ae7286da[1]; if (empty($v5d3813882f["limit"])) $v5d3813882f["limit"] = $v87ae7286da[3]; $v3c76382d93 = preg_replace("/\s+limit\s*([0-9]+)(|\s*,\s*([0-9]+))\s*$/i", "", $v3c76382d93); } if(isset($v5d3813882f["limit"]) && is_numeric($v5d3813882f["limit"])) { if (stripos($v3c76382d93, " order by ") === false) $v3c76382d93 .= " ORDER BY (SELECT NULL)"; $v3c76382d93 .= " OFFSET " . (!empty($v5d3813882f["start"]) ? $v5d3813882f["start"] : 0) . " ROWS FETCH NEXT " . $v5d3813882f["limit"] . " ROWS ONLY;"; } } $v3c76382d93 = self::replaceSQLEnclosingDelimiter(trim($v3c76382d93), "`", self::getEnclosingDelimiters()); switch ($this->default_php_extension_type) { case "sqlsrv": return sqlsrv_query($this->link, $v3c76382d93); case "pdo": return $this->link->query($v3c76382d93); case "odbc": return odbc_exec($this->link, $v3c76382d93); } } catch(Exception $paec2c009) { return launch_exception(new SQLException(6, $paec2c009, array($v3c76382d93))); } } public function freeResult($v9ad1385268) { try { switch ($this->default_php_extension_type) { case "sqlsrv": return sqlsrv_free_stmt($v9ad1385268); case "pdo": return $v9ad1385268->closeCursor(); case "odbc": return odbc_free_result($v9ad1385268); } }catch(Exception $paec2c009) { return launch_exception(new SQLException(7, $paec2c009, array($v9ad1385268))); } } public function numRows($v9ad1385268) { try { switch ($this->default_php_extension_type) { case "sqlsrv": return sqlsrv_num_rows($v9ad1385268); case "pdo": return $v9ad1385268->rowCount(); case "odbc": return odbc_num_rows($v9ad1385268); } }catch(Exception $paec2c009) { return launch_exception(new SQLException(13, $paec2c009, array($v9ad1385268))); } } public function numFields($v9ad1385268) { try { switch ($this->default_php_extension_type) { case "sqlsrv": return sqlsrv_num_fields($v9ad1385268); case "pdo": return $v9ad1385268->columnCount(); case "odbc": return odbc_num_fields($v9ad1385268); } }catch(Exception $paec2c009) { return launch_exception(new SQLException(14, $paec2c009, array($v9ad1385268))); } } public function fetchArray($v9ad1385268, $v8966764c3b = false) { try { switch ($this->default_php_extension_type) { case "sqlsrv": if ($v8966764c3b == DB::FETCH_OBJECT) return sqlsrv_fetch_object($v9ad1385268); $v8966764c3b = $this->med4121014952($v8966764c3b ? $v8966764c3b : DB::FETCH_BOTH); return sqlsrv_fetch_array($v9ad1385268, $v8966764c3b); case "pdo": if ($v8966764c3b == DB::FETCH_OBJECT) return $v9ad1385268->fetch(PDO::FETCH_OBJ); $v8966764c3b = $this->med4121014952($v8966764c3b ? $v8966764c3b : DB::FETCH_BOTH); return $v9ad1385268->fetch($v8966764c3b); case "odbc": if ($v8966764c3b == DB::FETCH_OBJECT) return odbc_fetch_object($v9ad1385268); $v651d593e1f = false; $v62e186f8df = false; if ($v8966764c3b == DB::FETCH_ASSOC || $v8966764c3b == DB::FETCH_BOTH || !$v8966764c3b) { $v62e186f8df = odbc_fetch_array($v9ad1385268); $v651d593e1f = true; } if ($v8966764c3b == DB::FETCH_NUM || $v8966764c3b == DB::FETCH_BOTH || !$v8966764c3b) $v62e186f8df = is_array($v62e186f8df) ? array_merge($v62e186f8df, array_values($v62e186f8df)) : ($v651d593e1f ? $v62e186f8df : odbc_fetch_row($v9ad1385268)); return $v62e186f8df; } }catch(Exception $paec2c009) { return launch_exception(new SQLException(8, $paec2c009, array($v9ad1385268, $v8966764c3b))); } } public function fetchField($v9ad1385268, $pac65f06f) { try { try { switch ($this->default_php_extension_type) { case "sqlsrv": $v86e5d83e26 = sqlsrv_field_metadata($v9ad1385268); $v5d170b1de6 = $v86e5d83e26 ? $v86e5d83e26[$pac65f06f] : null; if ($v5d170b1de6) $this->mec9a1cb70da5($v5d170b1de6); break; case "pdo": $v5d170b1de6 = $v9ad1385268->getColumnMeta($pac65f06f); if ($v5d170b1de6) self::preparePDOField($v5d170b1de6); break; case "odbc": $v5d170b1de6 = new stdClass(); $v5d170b1de6->name = odbc_field_name($v9ad1385268, $pac65f06f); $v5d170b1de6->type = strtolower( odbc_field_type($v9ad1385268, $pac65f06f) ); $v5d170b1de6->length = odbc_field_len($v9ad1385268, $pac65f06f); $v5d170b1de6->precision = odbc_field_precision($v9ad1385268, $pac65f06f); $v5d170b1de6->scale = odbc_field_scale($v9ad1385268, $pac65f06f); $v5d170b1de6->not_null = null; break; } } catch (PDOException $paec2c009) { } if ($v5d170b1de6) { $v5d170b1de6->type = self::convertColumnTypeFromDB($v5d170b1de6->type, $pe1390784); if ($pe1390784) foreach ($pe1390784 as $pe5c5e2fe => $v956913c90f) $v5d170b1de6->$pe5c5e2fe = $v956913c90f; } return $v5d170b1de6; }catch(Exception $paec2c009) { return launch_exception(new SQLException(12, $paec2c009, array($v9ad1385268, $pac65f06f))); } } private function mec9a1cb70da5(&$v5d170b1de6) { if (is_array($v5d170b1de6)) $v5d170b1de6 = (object) $v5d170b1de6; foreach ($v5d170b1de6 as $pe5c5e2fe => $v956913c90f) { unset($v5d170b1de6->$pe5c5e2fe); $pe5c5e2fe = strtolower($pe5c5e2fe); $v5d170b1de6->$pe5c5e2fe = $v956913c90f; } $v5d170b1de6->length = $v5d170b1de6->size; $v5d170b1de6->not_null = empty($v5d170b1de6->nullable); unset($v5d170b1de6->size); unset($v5d170b1de6->nullable); $pc4b62752 = self::$mssqlserver_data_types[$v5d170b1de6->type]; $v5d170b1de6->type = $pc4b62752[0]; } public function isResultValid($v9ad1385268) { switch ($this->default_php_extension_type) { case "pdo": return is_a($v9ad1385268, "PDOStatement"); } return is_resource($v9ad1385268); } public function listTables($pb67a2609 = false, $v5d3813882f = false) { $pac4bc40a = array(); $pb67a2609 = $pb67a2609 ? $pb67a2609 : (!$this->isDBSelected() && !empty($this->options["db_name"]) ? $this->options["db_name"] : null); $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $v5d3813882f["return_type"] = "result"; $v3c76382d93 = self::getTablesStatement($pb67a2609, $this->options); $v9ad1385268 = $this->getData($v3c76382d93, $v5d3813882f); if($v9ad1385268) foreach ($v9ad1385268 as $pc661dc6b) $pac4bc40a[] = array( "name" => (!empty($pc661dc6b["table_schema"]) && empty($this->options["schema"]) ? $pc661dc6b["table_schema"] . "." : "") . $pc661dc6b["table_name"], "table_name" => isset($pc661dc6b["table_name"]) ? $pc661dc6b["table_name"] : null, "schema" => isset($pc661dc6b["table_schema"]) ? $pc661dc6b["table_schema"] : null, "type" => isset($pc661dc6b["table_type"]) ? strtolower($pc661dc6b["table_type"]) : null ); return $pac4bc40a; } public function listTableFields($pc661dc6b, $v5d3813882f = false) { $v86e5d83e26 = array(); $pb67a2609 = !$this->isDBSelected() && !empty($this->options["db_name"]) ? $this->options["db_name"] : null; $v3c76382d93 = self::getTableFieldsStatement($pc661dc6b, $pb67a2609, $this->options); if (empty($this->options["db_name"])) return launch_exception(new SQLException(19, null, $v3c76382d93)); $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $v5d3813882f["return_type"] = "result"; $v9ad1385268 = $this->getData($v3c76382d93, $v5d3813882f); if($v9ad1385268) foreach ($v9ad1385268 as $v5d170b1de6) if (isset($v5d170b1de6["column_name"])) { $v8ccbc1125e = isset($v5d170b1de6["column_default"]) ? $v5d170b1de6["column_default"] : null; $pe1390784 = null; $v0911c6122e = !empty($v5d170b1de6["character_maximum_length"]) ? $v5d170b1de6["character_maximum_length"] : (isset($v5d170b1de6["numeric_precision"]) ? $v5d170b1de6["numeric_precision"] : null); if (is_numeric($v0911c6122e) && isset($v5d170b1de6["numeric_scale"]) && is_numeric($v5d170b1de6["numeric_scale"])) $v0911c6122e += $v5d170b1de6["numeric_scale"]; $v8ccbc1125e = $v8ccbc1125e == "''" ? "" : $v8ccbc1125e; $v9073377656 = array( "name" => $v5d170b1de6["column_name"], "type" => isset($v5d170b1de6["data_type"]) ? self::convertColumnTypeFromDB($v5d170b1de6["data_type"], $pe1390784) : null, "length" => $v0911c6122e, "null" => isset($v5d170b1de6["is_nullable"]) && strtolower($v5d170b1de6["is_nullable"]) == "no" ? false : true, "primary_key" => !empty($v5d170b1de6["is_primary_key"]) ? true : false, "unique" => !empty($v5d170b1de6["is_primary_key"]) || !empty($v5d170b1de6["is_unique_key"]) ? true : false, "unsigned" => false, "default" => $v8ccbc1125e, "charset" => isset($v5d170b1de6["character_set_name"]) ? $v5d170b1de6["character_set_name"] : null, "collation" => isset($v5d170b1de6["collation_name"]) ? $v5d170b1de6["collation_name"] : null, "extra" => "", "comment" => isset($v5d170b1de6["column_comment"]) ? $v5d170b1de6["column_comment"] : null, ); $v0b2c926cd8 = "IDENTITY (" . (isset($v5d170b1de6["seed_value"]) && is_numeric($v5d170b1de6["seed_value"]) ? $v5d170b1de6["seed_value"] : 1) . ", " . (isset($v5d170b1de6["increment_value"]) && is_numeric($v5d170b1de6["increment_value"]) ? $v5d170b1de6["increment_value"] : 1) . ")"; $v6c33401cc3 = isset($v5d170b1de6["data_type"]) ? in_array($v5d170b1de6["data_type"], array("serial", "smallserial", "bigserial")) : false; if (!empty($v5d170b1de6["is_identity"])) $v9073377656["extra"] .= $v0b2c926cd8; if ($pe1390784) foreach ($pe1390784 as $pe5c5e2fe => $v956913c90f) { if ($v956913c90f && ($pe5c5e2fe == "auto_increment" || $pe5c5e2fe == "identity")) $v6c33401cc3 = true; else $v9073377656[$pe5c5e2fe] = $v956913c90f; } if ($v6c33401cc3 && stripos($v9073377656["extra"], "IDENTITY") === false) $v9073377656["extra"] .= ($v9073377656["extra"] ? " " : "") . $v0b2c926cd8; $v9073377656["auto_increment"] = $v6c33401cc3 || stripos($v9073377656["extra"], "IDENTITY") !== false; if ($v9073377656["default"] && preg_match("/^\(+/", $v9073377656["default"]) && preg_match("/\)+$/", $v9073377656["default"])) { $v9073377656["default"] = preg_replace("/^\(+/", "", $v9073377656["default"]); $v9073377656["default"] = preg_replace("/\)+$/", "", $v9073377656["default"]); if (!is_numeric($v9073377656["default"]) && preg_match("/^'+/", $v9073377656["default"]) && preg_match("/'+$/", $v9073377656["default"])) { $v9073377656["default"] = preg_replace("/^'+/", "", $v9073377656["default"]); $v9073377656["default"] = preg_replace("/'+$/", "", $v9073377656["default"]); } } $v86e5d83e26[ $v5d170b1de6["column_name"] ] = $v9073377656; } return $v86e5d83e26; } public function getInsertedId($v5d3813882f = false) { if ($this->init()) switch ($this->default_php_extension_type) { case "pdo": try { return $this->link->lastInsertId(); } catch (Exception $paec2c009) { } case "sqlsrv": case "odbc": $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $v5d3813882f["return_type"] = "result"; $v9ad1385268 = $this->getData("SELECT @@IDENTITY AS id", $v5d3813882f); if ($v9ad1385268) return isset($v9ad1385268[0]["id"]) ? $v9ad1385268[0]["id"] : null; } return 0; } private function med4121014952($pea108151) { if ($this->default_php_extension_type == "sqlsrv") switch ($pea108151) { case DB::FETCH_ASSOC: return SQLSRV_FETCH_ASSOC; case DB::FETCH_NUM: return SQLSRV_FETCH_NUMERIC; case DB::FETCH_BOTH: return SQLSRV_FETCH_BOTH; } return self::convertFetchTypeToPDOAndODBCExtensions($this->default_php_extension_type, $pea108151); } } ?>
