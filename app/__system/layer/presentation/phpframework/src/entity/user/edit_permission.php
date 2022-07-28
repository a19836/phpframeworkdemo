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

$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $permission_id = $_GET["permission_id"]; if ($permission_id) { $permission_data = $UserAuthenticationHandler->getPermission($permission_id); } if ($_POST["permission_data"]) { $new_permission_data = $_POST["permission_data"]; if ($_POST["delete"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); if ($permission_id && !in_array($permission_id, $UserAuthenticationHandler->getReservedPermissions()) && $UserAuthenticationHandler->deletePermission($permission_id)) { die("<script>alert('Permission deleted successfully'); document.location = '$project_url_prefix/user/manage_permissions';</script>"); } else { $permission_data = $new_permission_data; $error_message = "There was an error trying to delete this permission. Please try again..."; } } else if (empty($new_permission_data["name"])) { $permission_data = $new_permission_data; $error_message = "Error: Name cannot be undefined"; } else { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $new_permission_data["name"] = strtolower($new_permission_data["name"]); if ($permission_data["name"] != $new_permission_data["name"]) { $results = $UserAuthenticationHandler->searchPermissions(array("name" => $new_permission_data["name"])); if ($results[0]) { $permission_data = $new_permission_data; $error_message = "Error: Repeated Name"; } } if (!$error_message) { if ($permission_data) { $permission_data = array_merge($permission_data, $new_permission_data); if (in_array($permission_id, $UserAuthenticationHandler->getReservedPermissions())) { $error_message = "This is a reserved permission and you cannot edit it."; } else if ($UserAuthenticationHandler->updatePermission($permission_data)) { $status_message = "Permission updated successfully..."; } else { $error_message = "There was an error trying to update this permission. Please try again..."; } } else { $permission_data = $new_permission_data; $status = $UserAuthenticationHandler->insertPermission($permission_data); if ($status) { die("<script>alert('Permission inserted successfully'); document.location = '?permission_id=" . $status . "';</script>"); } else { $error_message = "There was an error trying to insert this permission. Please try again..."; } } } } } if (empty($permission_data)) { $permission_data = array( "permission_id" => $permission_id, "name" => "", ); } ?>
