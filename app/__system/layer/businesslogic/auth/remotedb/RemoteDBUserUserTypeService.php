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

namespace __system\businesslogic; include_once $vars["business_logic_modules_service_common_file_path"]; include_once get_lib("org.phpframework.db.DB"); class RemoteDBUserUserTypeService extends CommonService { public function dropAndCreateTable($data) { $v5d3813882f = $data["options"]; $v87a92bb1ad = array( "table_name" => "sysauth_user_user_type", "attributes" => array( array( "name" => "user_id", "type" => "bigint", "primary_key" => 1, ), array( "name" => "user_type_id", "type" => "bigint", "primary_key" => 1, ), array( "name" => "created_date", "type" => "timestamp", "null" => 1, ), array( "name" => "modified_date", "type" => "timestamp", "null" => 1, ), ) ); if (!isset($v5d3813882f["schema"])) $v5d3813882f["schema"] = $this->getBroker()->getFunction("getOption", "schema"); $pcc7f917f = $this->getBroker()->getFunction("getDropTableStatement", $v87a92bb1ad["table_name"], $v5d3813882f); $pd6148c78 = $this->getBroker()->getFunction("getCreateTableStatement", array($v87a92bb1ad), $v5d3813882f); $v5c1c342594 = $this->getBroker()->setData($pcc7f917f, $v5d3813882f) && $this->getBroker()->setData($pd6148c78, $v5d3813882f); $this->getBusinessLogicLayer()->callBusinessLogic("auth.remotedb", "RemoteDBModuleDBTableNameService.insertIfNotExistsYet", array("name" => $v87a92bb1ad["table_name"])); return $v5c1c342594; } /**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 */ public function insert($data) { $v5d3813882f = $data["options"]; $data["created_date"] = $data["created_date"] ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = $data["modified_date"] ? $data["modified_date"] : $data["created_date"]; return $this->getBroker()->callInsert("auth", "insert_user_user_type", $data, $v5d3813882f); } /**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 */ public function delete($data) { $v5d3813882f = $data["options"]; return $this->getBroker()->callDelete("auth", "delete_user_user_type", array("user_id" => $data["user_id"], "user_type_id" => $data["user_type_id"]), $v5d3813882f); } /**
	 * @param (name=data[conditions][user_id], type=bigint, length=19)
	 * @param (name=data[conditions][user_type_id], type=bigint, length=19)
	 */ public function deleteByConditions($data) { $paf1bc6f6 = $data["conditions"]; $v5d3813882f = $data["options"]; $v9e394b2939 = \DB::getSQLConditions($paf1bc6f6, $data["conditions_join"]); return $this->getBroker()->callDelete("auth", "delete_user_user_types_by_conditions", array("conditions" => $v9e394b2939), $v5d3813882f); } /**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[user_type_id], type=bigint, not_null=1, length=19)
	 */ public function get($data) { $v5d3813882f = $data["options"]; $v9ad1385268 = $this->getBroker()->callSelect("auth", "get_user_user_type", array("user_id" => $data["user_id"], "user_type_id" => $data["user_type_id"]), $v5d3813882f); return $v9ad1385268[0]; } public function getAll($data) { $v5d3813882f = $data["options"]; return $this->getBroker()->callSelect("auth", "get_all_user_user_types", null, $v5d3813882f); } /**
	 * @param (name=data[conditions][user_id], type=bigint, length=19)
	 * @param (name=data[conditions][user_type_id], type=bigint, length=19)
	 */ public function search($data) { $paf1bc6f6 = $data["conditions"]; $v5d3813882f = $data["options"]; $v9e394b2939 = \DB::getSQLConditions($paf1bc6f6, $data["conditions_join"]); return $v9e394b2939 ? $this->getBroker()->callSelect("auth", "get_user_user_types_by_conditions", array("conditions" => $v9e394b2939), $v5d3813882f) : null; } } ?>
