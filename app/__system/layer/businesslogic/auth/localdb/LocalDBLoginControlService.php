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
namespace __system\businesslogic; include_once $vars["current_business_logic_module_path"] . "LocalDBAuthService.php"; class LocalDBLoginControlService extends LocalDBAuthService { /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[new_encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function changeTableEncryptionKey($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->changeDBTableEncryptionKey("login_control", $data["new_encryption_key"]); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function dropAndCreateTable($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->writeTableItems("", "login_control"); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function insert($data) { $this->initLocalDBTableHandler($data); $pfdf1ef23 = $this->get($data); if (empty($data["login_expired_time"])) { $data["login_expired_time"] = time() + 3600; } if (empty($data["session_id"])) { $data["session_id"] = \CryptoKeyHandler::getHexKey(); } $data["created_date"] = !empty($data["created_date"]) ? $data["created_date"] : date("Y-m-d H:i:s"); $data["modified_date"] = !empty($data["modified_date"]) ? $data["modified_date"] : $data["created_date"]; if ($pfdf1ef23) $v5c1c342594 = $this->LocalDBTableHandler->updateItem("login_control", $data, array("username")); else $v5c1c342594 = $this->LocalDBTableHandler->insertItem("login_control", $data, array("username")); return $v5c1c342594 ? $data["session_id"] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function insertFailedLoginAttempt($data) { $v36b8841602 = $this->getFailedLoginAttempts($data); $pfdf1ef23 = $this->get($data); $v8f602836b9 = date("Y-m-d H:i:s"); $v342a134247 = array( "username" => $data["username"], "failed_login_attempts" => $v36b8841602 + 1, "failed_login_time" => time(), "created_date" => !empty($pfdf1ef23["created_date"]) ? $pfdf1ef23["created_date"] : $v8f602836b9, "modified_date" => !empty($pfdf1ef23["modified_date"]) ? $pfdf1ef23["modified_date"] : $v8f602836b9, ); if ($pfdf1ef23) return $this->LocalDBTableHandler->updateItem("login_control", $v342a134247, array("username")); return $this->LocalDBTableHandler->insertItem("login_control", $v342a134247, array("username")); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[session_id], type=varchar, not_null=1, min_length=1, max_length=200)
	 */ public function expireSession($data) { $v342a134247 = $this->getBySessionId($data); if ($v342a134247 && !empty($v342a134247["session_id"]) && !empty($v342a134247["username"])) { $v342a134247["login_expired_time"] = time() - 1; $v342a134247["modified_date"] = date("Y-m-d H:i:s"); return $this->LocalDBTableHandler->updateItem("login_control", $v342a134247, array("username")); } return false; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function resetFailedLoginAttempts($data) { $this->initLocalDBTableHandler($data); $v342a134247 = $this->get($data); if ($v342a134247) { $v342a134247["failed_login_attempts"] = null; $v342a134247["failed_login_time"] = null; $v342a134247["modified_date"] = date("Y-m-d H:i:s"); return $this->LocalDBTableHandler->updateItem("login_control", $v342a134247, array("username")); } return true; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function delete($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->deleteItem("login_control", array("username" => $data["username"])); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function getFailedLoginAttempts($data) { $v342a134247 = $this->get($data); return $v342a134247 && !empty($v342a134247["username"]) && isset($v342a134247["failed_login_attempts"]) ? $v342a134247["failed_login_attempts"] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function get($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->LocalDBTableHandler->getItems("login_control"); $v2f228af834 = $this->LocalDBTableHandler->filterItems($pf72c1d58, array("username" => $data["username"]), false, 1); return isset($v2f228af834[0]) ? $v2f228af834[0] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[session_id], type=varchar, not_null=1, min_length=1, max_length=200)
	 */ public function getBySessionId($data) { $this->initLocalDBTableHandler($data); $pf72c1d58 = $this->LocalDBTableHandler->getItems("login_control"); $v2f228af834 = $this->LocalDBTableHandler->filterItems($pf72c1d58, array("session_id" => $data["session_id"]), false, 1); return isset($v2f228af834[0]) ? $v2f228af834[0] : null; } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 */ public function isUserBlocked($data) { $v342a134247 = $this->get($data); if (empty($data["maximum_failed_attempts"])) $data["maximum_failed_attempts"] = 3; if (empty($data["expired_time"])) $data["expired_time"] = 3600; $v310a246730 = isset($v342a134247["failed_login_attempts"]) ? $v342a134247["failed_login_attempts"] : null; $v238f974121 = isset($v342a134247["failed_login_time"]) ? $v342a134247["failed_login_time"] : null; return $v310a246730 > $data["maximum_failed_attempts"] && $v238f974121 + $data["expired_time"] >= time(); } /**
	 * @param (name=data[root_path], type=varchar, not_null=1, min_length=1)
	 * @param (name=data[encryption_key], type=varchar, not_null=1, min_length=1)
	 */ public function getAll($data) { $this->initLocalDBTableHandler($data); return $this->LocalDBTableHandler->getItems("login_control"); } } ?>
