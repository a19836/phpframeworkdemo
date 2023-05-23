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

namespace __system\businesslogic; include_once $vars["business_logic_modules_service_common_file_path"]; class RemoteDBUserStatsService extends CommonService { public function dropAndCreateTable($data) { $v5d3813882f = $data["options"]; $v87a92bb1ad = array( "table_name" => "sysauth_user_stats", "attributes" => array( array( "name" => "name", "type" => "varchar", "length" => 100, "primary_key" => 1, ), array( "name" => "value", "type" => "varchar", "length" => 200, ), array( "name" => "created_date", "type" => "timestamp", "null" => 1, ), array( "name" => "modified_date", "type" => "timestamp", "null" => 1, ), ) ); if (!isset($v5d3813882f["schema"])) $v5d3813882f["schema"] = $this->getBroker()->getFunction("getOption", "schema"); $pcc7f917f = $this->getBroker()->getFunction("getDropTableStatement", $v87a92bb1ad["table_name"], $v5d3813882f); $pd6148c78 = $this->getBroker()->getFunction("getCreateTableStatement", array($v87a92bb1ad), $v5d3813882f); return $this->getBroker()->setData($pcc7f917f, $v5d3813882f) && $this->getBroker()->setData($pd6148c78, $v5d3813882f); } /**
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=100)
	 */ public function insert($data) { $v5d3813882f = $data["options"]; $pfdf1ef23 = $this->get($data); $data["name"] = addcslashes($data["name"], "\\'"); $data["value"] = addcslashes($data["value"], "\\'"); $data["created_date"] = $data["created_date"] ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = $data["modified_date"] ? $data["modified_date"] : $data["created_date"]; if ($pfdf1ef23) { $data["created_date"] = $pfdf1ef23["created_date"]; $v5c1c342594 = $this->getBroker()->callInsert("auth", "update_user_stats", $data, $v5d3813882f); } else $v5c1c342594 = $this->getBroker()->callInsert("auth", "insert_user_stats", $data, $v5d3813882f); return $v5c1c342594 ? $data["name"] : null; } /**
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=100)
	 */ public function get($data) { $v5e813b295b = addcslashes($data["name"], "\\'"); $v5d3813882f = $data["options"]; $pf72c1d58 = $this->getBroker()->callSelect("auth", "get_user_stats", array("name" => $v5e813b295b), $v5d3813882f); return $pf72c1d58 ? $pf72c1d58[0] : null; } public function getAll($data) { $v5d3813882f = $data["options"]; return $this->getBroker()->callSelect("auth", "get_all_user_statss", null, $v5d3813882f); } } ?>
