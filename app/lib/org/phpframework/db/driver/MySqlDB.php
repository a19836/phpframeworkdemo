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
 include_once get_lib("org.phpframework.db.DB"); include_once get_lib("org.phpframework.db.statement.MySqlDBStatement"); include_once get_lib("org.phpframework.db.property.MySqlDBProperty"); include_once get_lib("org.phpframework.db.static.MySqlDBStatic"); class MySqlDB extends DB { use MySqlDBStatement; use MySqlDBProperty; use MySqlDBStatic; public function __construct() { if (!$this->default_php_extension_type) { $v3d648f7ed6 = self::getAvailablePHPExtensionTypes(); $this->default_php_extension_type = $v3d648f7ed6[0]; } } public function parseDSN($v56c929bbd8) { return self::convertDSNToOptions($v56c929bbd8); } public function getDSN($v5d3813882f = null) { $v5d3813882f = $v5d3813882f ? $v5d3813882f : $this->options; $v56c929bbd8 = in_array("pdo", self::$available_php_extension_types) ? 'odbc:' : ''; $v5b5ec93636 = !empty($v5d3813882f["odbc_driver"]) ? $v5d3813882f["odbc_driver"] : null; $v7baa5c4751 = !empty($v5d3813882f["odbc_data_source"]) ? $v5d3813882f["odbc_data_source"] : null; $v27ebcf3049 = $v87b039ee1a = false; $v4470ec9925 = in_array("pdo", self::$available_php_extension_types); if ($v7baa5c4751) $v56c929bbd8 .= $v7baa5c4751 . (substr($v7baa5c4751, -1) == ";" ? "" : ";"); else if (empty($v5d3813882f["host"]) && self::$default_odbc_data_source) $v56c929bbd8 .= self::$default_odbc_data_source . (substr(self::$default_odbc_data_source, -1) == ";" ? "" : ";"); else if ($v5b5ec93636) $v56c929bbd8 .= 'Driver={' . $v5b5ec93636 . '};'; else if (self::$default_odbc_driver) $v56c929bbd8 .= 'Driver={' . self::$default_odbc_driver . '};'; else if ($v4470ec9925) { $v56c929bbd8 = 'mysql:'; $v87b039ee1a = true; } if (!empty($v5d3813882f["host"])) $v56c929bbd8 .= ($v27ebcf3049 ? 'host' : 'Server') . "=" . $v5d3813882f["host"] . (!empty($v5d3813882f["port"]) ? ':' . $v5d3813882f["port"] : '') . ';'; if (!empty($v5d3813882f["db_name"])) $v56c929bbd8 .= ($v87b039ee1a ? 'dbname' : 'Database') . '=' . $v5d3813882f["db_name"] . ';'; if (!empty($v5d3813882f["encoding"])) $v56c929bbd8 .= 'charset=' . $v5d3813882f["encoding"] . ';'; if (!empty($v5d3813882f["extra_dsn"])) $v56c929bbd8 .= $v5d3813882f["extra_dsn"]; return $v56c929bbd8; } public function getVersion() { if ($this->link) switch ($this->default_php_extension_type) { case "mysqli": return mysqli_get_server_version($this->link); case "mysql": return mysql_get_server_info($this->link); case "pdo": return $this->link->getAttribute(PDO::ATTR_SERVER_VERSION); case "odbc": return null; } return null; } public function connect() { $this->db_selected = false; try{ if ($this->link) $this->close(); $this->default_php_extension_type = !empty($this->options["extension"]) ? $this->options["extension"] : $this->default_php_extension_type; switch ($this->default_php_extension_type) { case "mysqli": $v244067a7fe = isset($this->options["host"]) ? $this->options["host"] : null; if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $v244067a7fe = 'p:' . $v244067a7fe; $this->link = mysqli_connect( $v244067a7fe, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null, isset($this->options["db_name"]) ? $this->options["db_name"] : null, isset($this->options["port"]) ? $this->options["port"] : null ); break; case "mysql": $v244067a7fe = isset($this->options["host"]) ? $this->options["host"] . ($this->options["port"] ? ":" . $this->options["port"] : "") : null; if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $this->link = mysql_pconnect( $v244067a7fe, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null ); else $this->link = mysql_connect( $v244067a7fe, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null, isset($this->options["new_link"]) ? $this->options["new_link"] : null ); if ($this->link && !empty($this->options["db_name"]) && !mysql_select_db($this->options["db_name"], $this->link)) $this->close(); break; case "pdo": $pc1d077f0 = !empty($this->options["pdo_settings"]) ? $this->options["pdo_settings"] : array(); if (!array_key_exists(PDO::ATTR_ERRMODE, $pc1d077f0)) $pc1d077f0[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $pc1d077f0[PDO::ATTR_PERSISTENT] = true; $v27ce1b7a06 = self::parseExtraSettingsAsPDOSettings($this->options["extra_settings"]); if ($v27ce1b7a06) foreach ($v27ce1b7a06 as $v8b5ce22ebd => $v216fc3414a) if ($v8b5ce22ebd) $pc1d077f0[$v8b5ce22ebd] = $v216fc3414a; $v56c929bbd8 = self::getDSN($this->options); $this->link = new PDO( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null, $pc1d077f0 ); break; case "odbc": $v56c929bbd8 = self::getDSN($this->options); if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $this->link = odbc_pconnect( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null ); else $this->link = odbc_connect( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null ); break; } if ($this->link) { if (!empty($this->options["encoding"]) && !$this->setCharset($this->options["encoding"])) $this->close(); else if (!empty($this->options["db_name"])) $this->db_selected = true; } if (!$this->link || !$this->db_selected) { $paec2c009 = null; $v0f9512fda4 = $this->default_php_extension_type == "mysqli" && mysqli_connect_errno() ? mysqli_connect_error() : ($this->default_php_extension_type == "mysql" && mysql_errno() ? mysql_error() : ($this->default_php_extension_type == "odbc" ? odbc_errormsg() : null)); if ($v0f9512fda4) $paec2c009 = new Exception("Failed to connect to MySQL: " . $v0f9512fda4); launch_exception(new SQLException(1, $paec2c009, $this->options)); } } catch(Exception $paec2c009) { launch_exception(new SQLException(1, $paec2c009, $this->options)); } return $this->db_selected; } public function connectWithoutDB() { try{ if ($this->link) $this->close(); $this->default_php_extension_type = !empty($this->options["extension"]) ? $this->options["extension"] : $this->default_php_extension_type; switch ($this->default_php_extension_type) { case "mysqli": $v244067a7fe = isset($this->options["host"]) ? $this->options["host"] : null; if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $v244067a7fe = 'p:' . $v244067a7fe; $this->link = mysqli_connect( $v244067a7fe, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null, null, isset($this->options["port"]) ? $this->options["port"] : null ); break; case "mysql": $v244067a7fe = !empty($this->options["host"]) ? $this->options["host"] . ($this->options["port"] ? ":" . $this->options["port"] : "") : null; if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $this->link = mysql_pconnect( $v244067a7fe, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null ); else $this->link = mysql_connect( $v244067a7fe, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null, isset($this->options["new_link"]) ? $this->options["new_link"] : null ); break; case "pdo": $pc1d077f0 = !empty($this->options["pdo_settings"]) ? $this->options["pdo_settings"] : array(); if (!array_key_exists(PDO::ATTR_ERRMODE, $pc1d077f0)) $pc1d077f0[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $pc1d077f0[PDO::ATTR_PERSISTENT] = true; $v5d3813882f = $this->options; unset($v5d3813882f["db_name"]); $v56c929bbd8 = self::getDSN($v5d3813882f); $this->link = new PDO( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null, $pc1d077f0 ); break; case "odbc": $v5d3813882f = $this->options; unset($v5d3813882f["db_name"]); $v56c929bbd8 = self::getDSN($v5d3813882f); if(!empty($this->options["persistent"]) && empty($this->options["new_link"])) $this->link = odbc_pconnect( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null ); else $this->link = odbc_connect( $v56c929bbd8, isset($this->options["username"]) ? $this->options["username"] : null, isset($this->options["password"]) ? $this->options["password"] : null ); break; } if ($this->link) { if (!empty($this->options["encoding"]) && !$this->setCharset($this->options["encoding"])) $this->close(); } if (!$this->link) { $paec2c009 = null; $v0f9512fda4 = $this->default_php_extension_type == "mysqli" && mysqli_connect_errno() ? mysqli_connect_error() : ($this->default_php_extension_type == "mysql" && mysql_errno() ? mysql_error() : ($this->default_php_extension_type == "odbc" ? odbc_errormsg() : null)); if ($v0f9512fda4) $paec2c009 = new Exception("Failed to connect to MySQL: " . $v0f9512fda4); launch_exception(new SQLException(1, $paec2c009, $this->options)); } } catch(Exception $paec2c009) { launch_exception(new SQLException(1, $paec2c009, $this->options)); } return $this->link ? true : false; } public function close() { try { if ($this->link) { $v5b6376bac9 = true; if ($this->ping()) switch ($this->default_php_extension_type) { case "mysqli": $v5b6376bac9 = mysqli_close($this->link); break; case "mysql": $v5b6376bac9 = mysql_close($this->link); break; case "odbc": $v5b6376bac9 = odbc_close($this->link); break; } if ($v5b6376bac9) { $this->db_selected = false; $this->link = null; return true; } } return false; }catch(Exception $paec2c009) { return launch_exception(new SQLException(3, $paec2c009)); } } public function ping() { try { if ($this->link) { switch ($this->default_php_extension_type) { case "mysqli": return @mysqli_ping($this->link); case "mysql": return @mysql_ping($this->link); case "pdo": return @$this->query("select 1"); case "odbc": return @$this->query("select 1"); } } return false; }catch(Exception $paec2c009) { return launch_exception(new SQLException(4, $paec2c009)); } } public function setCharset($pbebc8cef = "utf8") { $this->init(); try { switch ($this->default_php_extension_type) { case "mysqli": return mysqli_set_charset($this->link, $pbebc8cef); case "mysql": return mysql_set_charset($pbebc8cef, $this->link); case "pdo": return $this->link->query("SET NAMES $pbebc8cef"); case "odbc": return odbc_exec($this->link, "SET NAMES $pbebc8cef"); } return false; }catch(Exception $paec2c009) { return launch_exception(new SQLException(20, $paec2c009, $pbebc8cef)); } } public function selectDB($pb67a2609) { $this->init(); try { if ($pb67a2609) switch ($this->default_php_extension_type) { case "mysqli": return mysqli_select_db($this->link, $pb67a2609); case "mysql": return mysql_select_db($pb67a2609, $this->link); case "pdo": return $this->link->query("use $pb67a2609"); case "odbc": return odbc_exec($this->link, "use $pb67a2609"); } return false; }catch(Exception $paec2c009) { return launch_exception(new SQLException(2, $paec2c009, array($pb67a2609))); } } public function errno() { try { if ($this->link) { switch ($this->default_php_extension_type) { case "mysqli": return mysqli_errno($this->link); case "mysql": return mysql_errno($this->link); case "pdo": return $this->link->errorCode(); case "odbc": return odbc_error($this->link); } } return -1; }catch(Exception $paec2c009) { return launch_exception(new SQLException(4, $paec2c009)); } } public function error() { try { if ($this->link) { switch ($this->default_php_extension_type) { case "mysqli": return mysqli_error($this->link); case "mysql": return mysql_error($this->link); case "pdo": return $this->link->errorInfo(); case "odbc": return odbc_errormsg($this->link); } } return "This db driver was not initialized yet! Please call the connect method first!"; }catch(Exception $paec2c009) { return launch_exception(new SQLException(5, $paec2c009)); } } private function mb5bc24119f0f(&$v3c76382d93, $v5d3813882f = false) { $v3c76382d93 = trim($v3c76382d93); if ($v3c76382d93 && stripos($v3c76382d93, "DELIMITER") !== false && preg_match("/\s*DELIMITER(\s*|'|\")/i", $v3c76382d93)) { $v19cbfb3aee = $v5d3813882f && !empty($v5d3813882f["delimiter"]) ? $v5d3813882f["delimiter"] : ";"; $v1612a5ddce = self::splitSQL($v3c76382d93); $v7eab668a0b = ""; foreach ($v1612a5ddce as $v9d1744e29c) { if (preg_match("/^DELIMITER(\s*|'|\")/i", $v9d1744e29c)) { preg_match("/^DELIMITER\s*('|\")?(.*)('|\")?$/i", $v9d1744e29c, $pbae7526c, PREG_OFFSET_CAPTURE); $v19cbfb3aee = $pbae7526c[2][0]; continue 1; } else if (substr($v9d1744e29c, - strlen($v19cbfb3aee)) == $v19cbfb3aee) $v9d1744e29c = substr($v9d1744e29c, 0, - strlen($v19cbfb3aee)) . ";"; $v7eab668a0b .= $v9d1744e29c . "\n"; } $v3c76382d93 = $v7eab668a0b; } return $v3c76382d93; } public function execute(&$v3c76382d93, $v5d3813882f = false) { $this->init(); try { $this->mb5bc24119f0f($v3c76382d93, $v5d3813882f); switch ($this->default_php_extension_type) { case "mysqli": $v19cbfb3aee = $v5d3813882f && !empty($v5d3813882f["delimiter"]) ? $v5d3813882f["delimiter"] : ";"; $v1669c07b63 = strpos($v3c76382d93, $v19cbfb3aee) !== false; if ($v1669c07b63) { $v5c1c342594 = mysqli_multi_query($this->link, $v3c76382d93); if ($v5c1c342594) { do { if ($v9ad1385268 = mysqli_store_result($this->link)) { if ($v9ad1385268 === false) $v5c1c342594 = $v9ad1385268; else if ($v9ad1385268 && $this->isResultValid($v9ad1385268)) $this->freeResult($v9ad1385268); } mysqli_more_results($this->link); } while (mysqli_next_result($this->link)); } return $v5c1c342594; } return @mysqli_query($this->link, $v3c76382d93); case "mysql": return @mysql_query($v3c76382d93, $this->link); case "pdo": return $this->link->exec($v3c76382d93); case "odbc": return odbc_exec($this->link, $v3c76382d93); } } catch(Exception $paec2c009) { return launch_exception(new SQLException(6, $paec2c009, array($v3c76382d93))); } } public function query(&$v3c76382d93, $v5d3813882f = false) { $this->init(); try { $this->mb5bc24119f0f($v3c76382d93, $v5d3813882f); if (is_array($v5d3813882f) && stripos($v3c76382d93, "select ") === 0) { if (substr($v3c76382d93, -1) == ";") $v3c76382d93 = substr($v3c76382d93, 0, -1); if (!empty($v5d3813882f["sort"])) { $pdab26aff = self::addSortOptionsToSQL($v5d3813882f["sort"]); if ($pdab26aff) { if (stripos($v3c76382d93, " limit ") !== false) $v3c76382d93 = "SELECT * FROM (" . $v3c76382d93 . ") AS QUERY_WITH_SORTING ORDER BY " . $pdab26aff; else $v3c76382d93 .= " ORDER BY " . $pdab26aff; } } if(isset($v5d3813882f["limit"]) && is_numeric($v5d3813882f["limit"])) { if (stripos($v3c76382d93, " order by ") !== false) $v3c76382d93 = "SELECT * FROM (" . $v3c76382d93 . ") AS QUERY_WITH_PAGINATION LIMIT " . (!empty($v5d3813882f["start"]) ? $v5d3813882f["start"] : 0) . ", " . $v5d3813882f["limit"]; else $v3c76382d93 .= " LIMIT " . (!empty($v5d3813882f["start"]) ? $v5d3813882f["start"] : 0) . ", " . $v5d3813882f["limit"]; } } switch ($this->default_php_extension_type) { case "mysqli": return @mysqli_query($this->link, $v3c76382d93); case "mysql": return @mysql_query($v3c76382d93, $this->link); case "pdo": return $this->link->query($v3c76382d93); case "odbc": return odbc_exec($this->link, $v3c76382d93); } } catch(Exception $paec2c009) { return launch_exception(new SQLException(6, $paec2c009, array($v3c76382d93))); } } public function freeResult($v9ad1385268) { try { switch ($this->default_php_extension_type) { case "mysqli": return mysqli_free_result($v9ad1385268); case "mysql": return mysql_free_result($v9ad1385268); case "pdo": return $v9ad1385268->closeCursor(); case "odbc": return odbc_free_result($v9ad1385268); } }catch(Exception $paec2c009) { return launch_exception(new SQLException(7, $paec2c009, array($v9ad1385268))); } } public function numRows($v9ad1385268) { try { switch ($this->default_php_extension_type) { case "mysqli": return mysqli_num_rows($v9ad1385268); case "mysql": return mysql_num_rows($v9ad1385268); case "pdo": return $v9ad1385268->rowCount(); case "odbc": return odbc_num_rows($v9ad1385268); } }catch(Exception $paec2c009) { return launch_exception(new SQLException(13, $paec2c009, array($v9ad1385268))); } } public function numFields($v9ad1385268) { try { switch ($this->default_php_extension_type) { case "mysqli": return mysqli_num_fields($v9ad1385268); case "mysql": return mysql_num_fields($v9ad1385268); case "pdo": return $v9ad1385268->columnCount(); case "odbc": return odbc_num_fields($v9ad1385268); } }catch(Exception $paec2c009) { return launch_exception(new SQLException(14, $paec2c009, array($v9ad1385268))); } } public function fetchArray($v9ad1385268, $v8966764c3b = false) { try { switch ($this->default_php_extension_type) { case "mysqli": if ($v8966764c3b == DB::FETCH_OBJECT) return mysqli_fetch_object($v9ad1385268); $v8966764c3b = $this->med4121014952($v8966764c3b ? $v8966764c3b : DB::FETCH_BOTH); return mysqli_fetch_array($v9ad1385268, $v8966764c3b); case "mysql": if ($v8966764c3b == DB::FETCH_OBJECT) return mysql_fetch_object($v9ad1385268); $v8966764c3b = $this->med4121014952($v8966764c3b ? $v8966764c3b : DB::FETCH_BOTH); return mysql_fetch_array($v9ad1385268, $v8966764c3b); case "pdo": if ($v8966764c3b == DB::FETCH_OBJECT) return $v9ad1385268->fetch(PDO::FETCH_OBJ); $v8966764c3b = $this->med4121014952($v8966764c3b ? $v8966764c3b : DB::FETCH_BOTH); return $v9ad1385268->fetch($v8966764c3b); case "odbc": if ($v8966764c3b == DB::FETCH_OBJECT) return odbc_fetch_object($v9ad1385268); $v651d593e1f = false; $v62e186f8df = false; if ($v8966764c3b == DB::FETCH_ASSOC || $v8966764c3b == DB::FETCH_BOTH || !$v8966764c3b) { $v62e186f8df = odbc_fetch_array($v9ad1385268); $v651d593e1f = true; } if ($v8966764c3b == DB::FETCH_NUM || $v8966764c3b == DB::FETCH_BOTH || !$v8966764c3b) $v62e186f8df = is_array($v62e186f8df) ? array_merge($v62e186f8df, array_values($v62e186f8df)) : ($v651d593e1f ? $v62e186f8df : odbc_fetch_row($v9ad1385268)); return $v62e186f8df; } }catch(Exception $paec2c009) { return launch_exception(new SQLException(8, $paec2c009, array($v9ad1385268, $v8966764c3b))); } } public function fetchField($v9ad1385268, $pac65f06f) { try { try { switch ($this->default_php_extension_type) { case "mysqli": $v5d170b1de6 = mysqli_fetch_field_direct($v9ad1385268, $pac65f06f); if ($v5d170b1de6) $this->f84b4624876($v5d170b1de6); break; case "mysql": $v5d170b1de6 = mysql_fetch_field($v9ad1385268, $pac65f06f); if ($v5d170b1de6) $this->mc134ca2c5074($v5d170b1de6); break; case "pdo": $v5d170b1de6 = $v9ad1385268->getColumnMeta($pac65f06f); if ($v5d170b1de6) self::preparePDOField($v5d170b1de6, self::$mysqli_flags); break; case "odbc": $v5d170b1de6 = new stdClass(); $v5d170b1de6->name = odbc_field_name($v9ad1385268, $pac65f06f); $v5d170b1de6->type = strtolower( odbc_field_type($v9ad1385268, $pac65f06f) ); $v5d170b1de6->length = odbc_field_len($v9ad1385268, $pac65f06f); $v5d170b1de6->precision = odbc_field_precision($v9ad1385268, $pac65f06f); $v5d170b1de6->scale = odbc_field_scale($v9ad1385268, $pac65f06f); $v5d170b1de6->not_null = null; break; } } catch (PDOException $paec2c009) { } if ($v5d170b1de6) { $v5d170b1de6->type = self::convertColumnTypeFromDB($v5d170b1de6->type, $pe1390784); if ($pe1390784) foreach ($pe1390784 as $pe5c5e2fe => $v956913c90f) $v5d170b1de6->$pe5c5e2fe = $v956913c90f; } return $v5d170b1de6; }catch(Exception $paec2c009) { return launch_exception(new SQLException(12, $paec2c009, array($v9ad1385268, $pac65f06f))); } } private function f84b4624876(&$v5d170b1de6) { if (is_array($v5d170b1de6)) $v5d170b1de6 = (object) $v5d170b1de6; $pc4b62752 = self::$mysqli_data_types[$v5d170b1de6->type]; if ($v5d170b1de6->flags) { foreach (self::$mysqli_flags as $pe96e65ba => $pc37695cb) if ($v5d170b1de6->flags & $pe96e65ba) $v5d170b1de6->$pc37695cb = true; $v5886ce2382 = isset($pc4b62752[0]) ? $pc4b62752[0] : null; $v0d86bd92df = isset($pc4b62752[1]) ? $pc4b62752[1] : null; switch($v5d170b1de6->type) { case MYSQLI_TYPE_NEWDECIMAL: $v5d170b1de6->type = empty($v5d170b1de6->decimal) && !empty($v5d170b1de6->numeric) ? $v0d86bd92df : $v5886ce2382; break; case MYSQLI_TYPE_TINY_BLOB: case MYSQLI_TYPE_MEDIUM_BLOB: case MYSQLI_TYPE_LONG_BLOB: case MYSQLI_TYPE_BLOB: $v5d170b1de6->type = empty($v5d170b1de6->blob) ? $v0d86bd92df : $v5886ce2382; break; default: $v5d170b1de6->type = $v5886ce2382; } } else $v5d170b1de6->type = isset($pc4b62752[0]) ? $pc4b62752[0] : null; } private function mc134ca2c5074(&$v5d170b1de6) { if (is_array($v5d170b1de6)) $v5d170b1de6 = (object) $v5d170b1de6; $v5d170b1de6->length = $v5d170b1de6->max_length; } public function isResultValid($v9ad1385268) { switch ($this->default_php_extension_type) { case "mysqli": return is_a($v9ad1385268, "mysqli_result"); case "pdo": return is_a($v9ad1385268, "PDOStatement"); } return is_resource($v9ad1385268); } public function listDBs($v5d3813882f = false, $pf49225a7 = "name") { return parent::listDBs($v5d3813882f, "Database"); } public function listTables($pb67a2609 = false, $v5d3813882f = false) { $pac4bc40a = array(); $pb67a2609 = $pb67a2609 ? $pb67a2609 : (!$this->isDBSelected() && !empty($this->options["db_name"]) ? $this->options["db_name"] : null); $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $v5d3813882f["return_type"] = "result"; $v3c76382d93 = self::getTablesStatement($pb67a2609, $this->options); $v9ad1385268 = $this->getData($v3c76382d93, $v5d3813882f); if($v9ad1385268) foreach ($v9ad1385268 as $pc661dc6b) $pac4bc40a[] = array( "name" => (isset($pc661dc6b["table_name"]) ? $pc661dc6b["table_name"] : null), "table_name" => isset($pc661dc6b["table_name"]) ? $pc661dc6b["table_name"] : null, "schema" => isset($pc661dc6b["table_schema"]) ? $pc661dc6b["table_schema"] : null, "type" => isset($pc661dc6b["table_type"]) ? strtolower($pc661dc6b["table_type"]) : null, "engine" => isset($pc661dc6b["table_storage_engine"]) ? $pc661dc6b["table_storage_engine"] : null, "charset" => isset($pc661dc6b["table_charset"]) ? $pc661dc6b["table_charset"] : null, "collation" => isset($pc661dc6b["table_collation"]) ? $pc661dc6b["table_collation"] : null, "comment" => isset($pc661dc6b["table_comment"]) ? $pc661dc6b["table_comment"] : null ); return $pac4bc40a; } public function listTableFields($pc661dc6b, $v5d3813882f = false) { $v86e5d83e26 = array(); $pb67a2609 = !$this->isDBSelected() && !empty($this->options["db_name"]) ? $this->options["db_name"] : null; $v3c76382d93 = self::getTableFieldsStatement($pc661dc6b, $pb67a2609, $this->options); if (empty($this->options["db_name"])) return launch_exception(new SQLException(19, null, $v3c76382d93)); $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $v5d3813882f["return_type"] = "result"; $v9ad1385268 = $this->getData($v3c76382d93, $v5d3813882f); if($v9ad1385268) foreach ($v9ad1385268 as $v5d170b1de6) if (isset($v5d170b1de6["column_name"])) { $v4716626df2 = isset($v5d170b1de6["column_key"]) ? $v5d170b1de6["column_key"] : null; $v324a7eadbc = isset($v5d170b1de6["column_type"]) ? $v5d170b1de6["column_type"] : null; $pa26646ce = isset($v5d170b1de6["data_type"]) ? $v5d170b1de6["data_type"] : null; $v8ccbc1125e = isset($v5d170b1de6["column_default"]) ? $v5d170b1de6["column_default"] : null; $pf69fac25 = strtolower($v4716626df2); $v1e4058522f = strtolower($v324a7eadbc); $v3c261bfc96 = strpos($v1e4058522f, "unsigned") > 0; $pe1390784 = null; $v0911c6122e = !empty($v5d170b1de6["character_maximum_length"]) ? $v5d170b1de6["character_maximum_length"] : (isset($v5d170b1de6["numeric_precision"]) ? $v5d170b1de6["numeric_precision"] : null); preg_match("/" . $pa26646ce . "\(([0-9]+)\)/", $v324a7eadbc, $pbae7526c); $pe2ae3be9 = !empty($pbae7526c[0]) ? $pbae7526c[1] : null; if (is_numeric($pe2ae3be9)) $v0911c6122e = $pe2ae3be9; else if (is_numeric($v0911c6122e) && preg_match("/" . $pa26646ce . "\(([0-9]+),([0-9]+)\)/", $v324a7eadbc, $pbae7526c) && !empty($pbae7526c[0])) $v0911c6122e = $pbae7526c[1] . "," . $pbae7526c[2]; $v8ccbc1125e = $v8ccbc1125e == "''" ? "" : $v8ccbc1125e; $v9073377656 = array( "name" => $v5d170b1de6["column_name"], "type" => self::convertColumnTypeFromDB($pa26646ce, $pe1390784), "length" => $v0911c6122e, "null" => isset($v5d170b1de6["is_nullable"]) && strtolower($v5d170b1de6["is_nullable"]) == "no" ? false : true, "primary_key" => !empty($v5d170b1de6["is_primary"]) || $pf69fac25 == "pri" ? true : false, "unique" => !empty($v5d170b1de6["is_primary"]) || !empty($v5d170b1de6["is_unique"]) || $pf69fac25 == "pri" || $pf69fac25 == "uni" ? true : false, "unsigned" => $v3c261bfc96, "default" => $v8ccbc1125e, "charset" => isset($v5d170b1de6["character_set_name"]) ? $v5d170b1de6["character_set_name"] : null, "collation" => isset($v5d170b1de6["collation_name"]) ? $v5d170b1de6["collation_name"] : null, "extra" => isset($v5d170b1de6["extra"]) ? $v5d170b1de6["extra"] : null, "comment" => isset($v5d170b1de6["column_comment"]) ? $v5d170b1de6["column_comment"] : null, ); $v6c33401cc3 = in_array($pa26646ce, array("serial", "smallserial", "bigserial")); if ($pe1390784) foreach ($pe1390784 as $pe5c5e2fe => $v956913c90f) { if ($v956913c90f && $pe5c5e2fe == "auto_increment") $v6c33401cc3 = true; else $v9073377656[$pe5c5e2fe] = $v956913c90f; } if ($v6c33401cc3 && stripos($v9073377656["extra"], "auto_increment") === false) $v9073377656["extra"] .= ($v9073377656["extra"] ? " " : "") . "auto_increment"; $v9073377656["auto_increment"] = $v6c33401cc3 || stripos($v9073377656["extra"], "auto_increment") !== false; $v86e5d83e26[ $v5d170b1de6["column_name"] ] = $v9073377656; } return $v86e5d83e26; } public function getInsertedId($v5d3813882f = false) { if ($this->init()) switch ($this->default_php_extension_type) { case "mysqli": return mysqli_insert_id($this->link); case "mysql": return mysql_insert_id($this->link); case "pdo": try { return $this->link->lastInsertId(); } catch (Exception $paec2c009) { } case "odbc": $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $v5d3813882f["return_type"] = "result"; $v9ad1385268 = $this->getData("SELECT LAST_INSERT_ID() as id", $v5d3813882f); if ($v9ad1385268) return isset($v9ad1385268[0]["id"]) ? $v9ad1385268[0]["id"] : null; } return 0; } private function med4121014952($pea108151) { switch ($this->default_php_extension_type) { case "mysqli": switch ($pea108151) { case DB::FETCH_ASSOC: return MYSQLI_ASSOC; case DB::FETCH_NUM: return MYSQLI_NUM; case DB::FETCH_BOTH: return MYSQLI_BOTH; } break; case "mysql": switch ($pea108151) { case DB::FETCH_ASSOC: return MYSQL_ASSOC; case DB::FETCH_NUM: return MYSQL_NUM; case DB::FETCH_BOTH: return MYSQL_BOTH; } break; } return self::convertFetchTypeToPDOAndODBCExtensions($this->default_php_extension_type, $pea108151); } } ?>
