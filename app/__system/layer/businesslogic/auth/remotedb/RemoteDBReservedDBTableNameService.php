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

namespace __system\businesslogic; include_once $vars["business_logic_modules_service_common_file_path"]; include_once get_lib("org.phpframework.db.DB"); class RemoteDBReservedDBTableNameService extends CommonService { public function dropAndCreateTable($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v87a92bb1ad = array( "table_name" => "sysauth_reserved_db_table_name", "attributes" => array( array( "name" => "reserved_db_table_name_id", "type" => "bigint", "primary_key" => 1, "auto_increment" => 1, ), array( "name" => "name", "type" => "varchar", "length" => 255, ), array( "name" => "created_date", "type" => "timestamp", "null" => 1, ), array( "name" => "modified_date", "type" => "timestamp", "null" => 1, ), ) ); if (!isset($v5d3813882f["schema"])) $v5d3813882f["schema"] = $this->getBroker()->getFunction("getOption", "schema"); $pcc7f917f = $this->getBroker()->getFunction("getDropTableStatement", $v87a92bb1ad["table_name"], $v5d3813882f); $pd6148c78 = $this->getBroker()->getFunction("getCreateTableStatement", array($v87a92bb1ad), $v5d3813882f); $v5c1c342594 = $this->getBroker()->setData($pcc7f917f, $v5d3813882f) && $this->getBroker()->setData($pd6148c78, $v5d3813882f); $this->insertIfNotExistsYet(array("name" => $v87a92bb1ad["table_name"])); return $v5c1c342594; } /**
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function insert($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $data["name"] = addcslashes($data["name"], "\\'"); $data["created_date"] = !empty($data["created_date"]) ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = !empty($data["modified_date"]) ? $data["modified_date"] : $data["created_date"]; if (empty($data["reserved_db_table_name_id"])) $data["reserved_db_table_name_id"] = "DEFAULT"; $v5c1c342594 = $this->getBroker()->callInsert("auth", "insert_reserved_db_table_name", $data, $v5d3813882f); return $v5c1c342594 ? ($data["reserved_db_table_name_id"] == "DEFAULT" ? $this->getBroker()->getInsertedId($v5d3813882f) : $data["reserved_db_table_name_id"]) : $v5c1c342594; } /**
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function insertIfNotExistsYet($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $pee4c7870 = $this->search(array("conditions" => array("name" => $data["name"]), "options" => $v5d3813882f)); if (!empty($pee4c7870[0])) return true; return $this->insert($data); } /**
	 * @param (name=data[reserved_db_table_name_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function update($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $data["name"] = addcslashes($data["name"], "\\'"); $data["modified_date"] = date("Y-m-d H:i:s"); return $this->getBroker()->callUpdate("auth", "update_reserved_db_table_name", $data, $v5d3813882f); } /**
	 * @param (name=data[reserved_db_table_name_id], type=bigint, not_null=1, length=19)
	 */ public function delete($data) { $v5e4be93887 = $data["reserved_db_table_name_id"]; $v5d3813882f = isset($data["options"]) ? $data["options"] : null; return $this->getBroker()->callDelete("auth", "delete_reserved_db_table_name", array("reserved_db_table_name_id" => $v5e4be93887), $v5d3813882f); } /**
	 * @param (name=data[reserved_db_table_name_id], type=bigint, not_null=1, length=19)
	 */ public function get($data) { $v5e4be93887 = $data["reserved_db_table_name_id"]; $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v9ad1385268 = $this->getBroker()->callSelect("auth", "get_reserved_db_table_name", array("reserved_db_table_name_id" => $v5e4be93887), $v5d3813882f); return isset($v9ad1385268[0]) ? $v9ad1385268[0] : null; } public function getAll($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; return $this->getBroker()->callSelect("auth", "get_all_reserved_db_table_names", null, $v5d3813882f); } /**
	 * @param (name=data[conditions][reserved_db_table_name_id], type=bigint, length=19)
	 * @param (name=data[conditions][name], type=varchar, length=255)
	 */ public function search($data) { $paf1bc6f6 = isset($data["conditions"]) ? $data["conditions"] : null; $v7fd392376f = isset($data["conditions_join"]) ? $data["conditions_join"] : null; $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v9e394b2939 = \DB::getSQLConditions($paf1bc6f6, $v7fd392376f); return $v9e394b2939 ? $this->getBroker()->callSelect("auth", "get_reserved_db_table_names_by_conditions", array("conditions" => $v9e394b2939), $v5d3813882f) : null; } } ?>
