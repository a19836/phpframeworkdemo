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

namespace __system\businesslogic; include_once $vars["business_logic_modules_service_common_file_path"]; include_once get_lib("org.phpframework.db.DB"); class RemoteDBLayoutTypeService extends CommonService { public function dropAndCreateTable($data) { $v5d3813882f = $data["options"]; $v87a92bb1ad = array( "table_name" => "sysauth_layout_type", "attributes" => array( array( "name" => "layout_type_id", "type" => "bigint", "primary_key" => 1, "auto_increment" => 1, ), array( "name" => "type_id", "type" => "tinyint", "length" => 1, ), array( "name" => "name", "type" => "varchar", "length" => 255, ), array( "name" => "created_date", "type" => "timestamp", "null" => 1, ), array( "name" => "modified_date", "type" => "timestamp", "null" => 1, ), ) ); if (!isset($v5d3813882f["schema"])) $v5d3813882f["schema"] = $this->getBroker()->getFunction("getOption", "schema"); $pcc7f917f = $this->getBroker()->getFunction("getDropTableStatement", $v87a92bb1ad["table_name"], $v5d3813882f); $pd6148c78 = $this->getBroker()->getFunction("getCreateTableStatement", array($v87a92bb1ad), $v5d3813882f); $v5c1c342594 = $this->getBroker()->setData($pcc7f917f, $v5d3813882f) && $this->getBroker()->setData($pd6148c78, $v5d3813882f); $this->getBusinessLogicLayer()->callBusinessLogic("auth.remotedb", "RemoteDBModuleDBTableNameService.insertIfNotExistsYet", array("name" => $v87a92bb1ad["table_name"])); return $v5c1c342594; } /**
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=255)
	 * @param (name=data[type_id], type=tinyint, not_null=1, default=0, length=1)
	 */ public function insert($data) { $v5d3813882f = $data["options"]; $data["name"] = addcslashes($data["name"], "\\'"); $data["created_date"] = $data["created_date"] ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = $data["modified_date"] ? $data["modified_date"] : $data["created_date"]; if (!$data["layout_type_id"]) $data["layout_type_id"] = "DEFAULT"; $v5c1c342594 = $this->getBroker()->callInsert("auth", "insert_layout_type", $data, $v5d3813882f); return $v5c1c342594 ? ($data["layout_type_id"] == "DEFAULT" ? $this->getBroker()->getInsertedId($v5d3813882f) : $data["layout_type_id"]) : $v5c1c342594; } /**
	 * @param (name=data[layout_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[type_id], type=tinyint, not_null=1, default=0, length=1)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=255)
	 */ public function update($data) { $v5d3813882f = $data["options"]; $data["name"] = addcslashes($data["name"], "\\'"); $data["modified_date"] = date("Y-m-d H:i:s"); return $this->getBroker()->callUpdate("auth", "update_layout_type", $data, $v5d3813882f); } /**
	 * @param (name=data[layout_type_id], type=bigint, not_null=1, length=19)
	 */ public function delete($data) { $v1a222c94d4 = $data["layout_type_id"]; $v5d3813882f = $data["options"]; return $this->getBroker()->callDelete("auth", "delete_layout_type", array("layout_type_id" => $v1a222c94d4), $v5d3813882f); } /**
	 * @param (name=data[layout_type_id], type=bigint, not_null=1, length=19)
	 */ public function get($data) { $v1a222c94d4 = $data["layout_type_id"]; $v5d3813882f = $data["options"]; $v9ad1385268 = $this->getBroker()->callSelect("auth", "get_layout_type", array("layout_type_id" => $v1a222c94d4), $v5d3813882f); return $v9ad1385268[0]; } public function getAll($data) { $v5d3813882f = $data["options"]; return $this->getBroker()->callSelect("auth", "get_all_layout_types", null, $v5d3813882f); } /**
	 * @param (name=data[conditions][layout_type_id], type=bigint, length=19)
	 * @param (name=data[conditions][type_id], type=tinyint, length=1)
	 * @param (name=data[conditions][name], type=varchar, length=255)
	 */ public function search($data) { $paf1bc6f6 = $data["conditions"]; $v5d3813882f = $data["options"]; $v9e394b2939 = \DB::getSQLConditions($paf1bc6f6, $data["conditions_join"]); return $v9e394b2939 ? $this->getBroker()->callSelect("auth", "get_layout_types_by_conditions", array("conditions" => $v9e394b2939), $v5d3813882f) : null; } } ?>
