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
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $user_id = $_GET["user_id"]; if ($user_id) { $user_data = $UserAuthenticationHandler->getUser($user_id); unset($user_data["password"]); } $is_own_user = $UserAuthenticationHandler->auth["user_data"]["username"] == $user_data["username"]; $is_user_editable = $UserAuthenticationHandler->isCurrentPagePermissionAllowed("write"); $is_user_deletable = $UserAuthenticationHandler->isCurrentPagePermissionAllowed("delete"); if ($_POST["user_data"]) { $new_user_data = $_POST["user_data"]; if ($_POST["delete"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); if ($user_id && $UserAuthenticationHandler->deleteUser($user_id) && $UserAuthenticationHandler->deleteUserUserTypesByConditions(array("user_id" => $user_id))) die("<script>alert('User deleted successfully'); document.location = '$project_url_prefix/user/manage_users';</script>"); else { $user_data = $new_user_data; $error_message = "There was an error trying to delete this user. Please try again..."; } } else if (empty($new_user_data["username"])) { $user_data = $new_user_data; $error_message = "Error: Username cannot be undefined"; } else if (empty($new_user_data["password"])) { $user_data = $new_user_data; $error_message = "Error: Password cannot be undefined"; } else if (empty($new_user_data["name"])) { $user_data = $new_user_data; $error_message = "Error: Name cannot be undefined"; } else if ($is_own_user && empty($user_data)) $error_message = "Error: User undefined!"; else { if ($is_own_user) $new_user_data["user_type_id"] = $user_data["user_type_id"]; else if (!$is_user_editable) $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $new_user_data["username"] = strtolower($new_user_data["username"]); if ($user_data["username"] != $new_user_data["username"]) { $results = $UserAuthenticationHandler->searchUsers(array("username" => $new_user_data["username"])); if ($results[0]) { $user_data = $new_user_data; $error_message = "Error: Repeated Username"; } } if (!$error_message) { if ($user_data) { $old_username = $user_data["username"]; $user_data = array_merge($user_data, $new_user_data); if ($UserAuthenticationHandler->updateUser($user_data)) { if ($old_username != $user_data["username"] && $is_own_user) { $UserAuthenticationHandler->login($user_data["username"], $user_data["password"]); die("<script>alert('User updated successfully'); document.location = '?user_id=" . $user_data["user_id"] . "';</script>"); } $status_message = "User updated successfully..."; } else $error_message = "There was an error trying to update this user. Please try again..."; } else if ($UserAuthenticationHandler->isUsersMaximumNumberReached()) $error_message = "You have reached your users maximum number. To add new users please purchase a new licence!"; else { $user_data = $new_user_data; $user_id = $UserAuthenticationHandler->insertUser($user_data); if ($user_id) die("<script>alert('User inserted successfully'); document.location = '?user_id=" . $user_id . "';</script>"); else $error_message = "There was an error trying to insert this user. Please try again..."; } } } } if (empty($user_data)) $user_data = array( "user_id" => $user_id, "username" => $username, "password" => "", "name" => "", ); ?>
