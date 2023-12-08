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
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $user_type_id = $_GET["user_type_id"]; if ($user_type_id) { $user_type_data = $UserAuthenticationHandler->getUserType($user_type_id); } if ($_POST["user_type_data"]) { $new_user_type_data = $_POST["user_type_data"]; if ($_POST["delete"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); if ($user_type_id && $UserAuthenticationHandler->deleteUserType($user_type_id)) { die("<script>alert('User Type deleted successfully'); document.location = '$project_url_prefix/user/manage_user_types';</script>"); } else { $user_type_data = $new_user_type_data; $error_message = "There was an error trying to delete this user type. Please try again..."; } } else if (empty($new_user_type_data["name"])) { $user_type_data = $new_user_type_data; $error_message = "Error: Name cannot be undefined"; } else { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $new_user_type_data["name"] = strtolower($new_user_type_data["name"]); if ($user_type_data["name"] != $new_user_type_data["name"]) { $results = $UserAuthenticationHandler->searchUserTypes(array("name" => $new_user_type_data["name"])); if ($results[0]) { $user_type_data = $new_user_type_data; $error_message = "Error: Repeated Name"; } } if (!$error_message) { if ($user_type_data) { $user_type_data = array_merge($user_type_data, $new_user_type_data); if ($UserAuthenticationHandler->updateUserType($user_type_data)) { $status_message = "User Type updated successfully..."; } else { $error_message = "There was an error trying to update this user type. Please try again..."; } } else { $user_type_data = $new_user_type_data; $status = $UserAuthenticationHandler->insertUserType($user_type_data); if ($status) { die("<script>alert('User Type inserted successfully'); document.location = '?user_type_id=" . $status . "';</script>"); } else { $error_message = "There was an error trying to insert this user type. Please try again..."; } } } } } if (empty($user_type_data)) { $user_type_data = array( "user_type_id" => $user_type_id, "name" => "", ); } ?>
