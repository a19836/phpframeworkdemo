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

namespace __system\businesslogic; include_once $vars["business_logic_modules_service_common_file_path"]; include_once get_lib("org.phpframework.db.DB"); class RemoteDBPermissionService extends CommonService { public function dropAndCreateTable($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v87a92bb1ad = array( "table_name" => "sysauth_permission", "attributes" => array( array( "name" => "permission_id", "type" => "bigint", "primary_key" => 1, "auto_increment" => 1, ), array( "name" => "name", "type" => "varchar", "length" => 50, "null" => 1, ), array( "name" => "created_date", "type" => "timestamp", "null" => 1, ), array( "name" => "modified_date", "type" => "timestamp", "null" => 1, ), ) ); if (!isset($v5d3813882f["schema"])) $v5d3813882f["schema"] = $this->getBroker()->getFunction("getOption", "schema"); $pcc7f917f = $this->getBroker()->getFunction("getDropTableStatement", $v87a92bb1ad["table_name"], $v5d3813882f); $pd6148c78 = $this->getBroker()->getFunction("getCreateTableStatement", array($v87a92bb1ad), $v5d3813882f); $v5c1c342594 = $this->getBroker()->setData($pcc7f917f, $v5d3813882f) && $this->getBroker()->setData($pd6148c78, $v5d3813882f); $this->getBusinessLogicLayer()->callBusinessLogic("auth.remotedb", "RemoteDBReservedDBTableNameService.insertIfNotExistsYet", array("name" => $v87a92bb1ad["table_name"])); return $v5c1c342594; } /**
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function insert($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $data["name"] = addcslashes($data["name"], "\\'"); $data["created_date"] = !empty($data["created_date"]) ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = !empty($data["modified_date"]) ? $data["modified_date"] : $data["created_date"]; if (empty($data["permission_id"])) $data["permission_id"] = "DEFAULT"; $v5c1c342594 = $this->getBroker()->callInsert("auth", "insert_permission", $data, $v5d3813882f); return $v5c1c342594 ? ($data["permission_id"] == "DEFAULT" ? $this->getBroker()->getInsertedId($v5d3813882f) : $data["permission_id"]) : $v5c1c342594; } /**
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function update($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $data["name"] = addcslashes($data["name"], "\\'"); $data["modified_date"] = date("Y-m-d H:i:s"); return $this->getBroker()->callUpdate("auth", "update_permission", $data, $v5d3813882f); } /**
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 */ public function delete($data) { $pb76ee81a = $data["permission_id"]; $v5d3813882f = isset($data["options"]) ? $data["options"] : null; return $this->getBroker()->callDelete("auth", "delete_permission", array("permission_id" => $pb76ee81a), $v5d3813882f); } /**
	 * @param (name=data[permission_id], type=bigint, not_null=1, length=19)
	 */ public function get($data) { $pb76ee81a = $data["permission_id"]; $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v9ad1385268 = $this->getBroker()->callSelect("auth", "get_permission", array("permission_id" => $pb76ee81a), $v5d3813882f); return isset($v9ad1385268[0]) ? $v9ad1385268[0] : null; } public function getAll($data) { $v5d3813882f = isset($data["options"]) ? $data["options"] : null; return $this->getBroker()->callSelect("auth", "get_all_permissions", null, $v5d3813882f); } /**
	 * @param (name=data[conditions][permission_id], type=bigint, length=19)
	 * @param (name=data[conditions][name], type=varchar, length=50)
	 */ public function search($data) { $paf1bc6f6 = isset($data["conditions"]) ? $data["conditions"] : null; $v7fd392376f = isset($data["conditions_join"]) ? $data["conditions_join"] : null; $v5d3813882f = isset($data["options"]) ? $data["options"] : null; $v9e394b2939 = \DB::getSQLConditions($paf1bc6f6, $v7fd392376f); return $v9e394b2939 ? $this->getBroker()->callSelect("auth", "get_permissions_by_conditions", array("conditions" => $v9e394b2939), $v5d3813882f) : null; } } ?>
