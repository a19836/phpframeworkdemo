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

$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $object_type_id = $_GET["object_type_id"]; if ($object_type_id) { $object_type_data = $UserAuthenticationHandler->getObjectType($object_type_id); } if ($_POST["object_type_data"]) { $new_object_type_data = $_POST["object_type_data"]; if ($_POST["delete"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); if ($object_type_id && in_array($object_type_id, $UserAuthenticationHandler->getReservedObjectTypes())) { $object_type_data = $new_object_type_data; $error_message = "This is a reserved object type and is not editable!"; } else if ($object_type_id && $UserAuthenticationHandler->deleteObjectType($object_type_id)) { die("<script>alert('Object Type deleted successfully'); document.location = '$project_url_prefix/user/manage_object_types';</script>"); } else { $object_type_data = $new_object_type_data; $error_message = "There was an error trying to delete this object type. Please try again..."; } } else if (empty($new_object_type_data["name"])) { $object_type_data = $new_object_type_data; $error_message = "Error: Name cannot be undefined"; } else { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $new_object_type_data["name"] = strtolower($new_object_type_data["name"]); if ($object_type_data["name"] != $new_object_type_data["name"]) { $results = $UserAuthenticationHandler->searchObjectTypes(array("name" => $new_object_type_data["name"])); if ($results[0]) { $object_type_data = $new_object_type_data; $error_message = "Error: Repeated Name"; } } if (!$error_message) { if ($object_type_data) { $object_type_data = array_merge($object_type_data, $new_object_type_data); if (in_array($object_type_id, $UserAuthenticationHandler->getReservedObjectTypes())) { $error_message = "This is a reserved object type and is not editable!"; } else if ($UserAuthenticationHandler->updateObjectType($object_type_data)) { $status_message = "Object Type updated successfully..."; } else { $error_message = "There was an error trying to update this object type. Please try again..."; } } else { $object_type_data = $new_object_type_data; $status = $UserAuthenticationHandler->insertObjectType($object_type_data); if ($status) { die("<script>alert('Object Type inserted successfully'); document.location = '?object_type_id=" . $status . "';</script>"); } else { $error_message = "There was an error trying to insert this object type. Please try again..."; } } } } } if (empty($object_type_data)) { $object_type_data = array( "object_type_id" => $object_type_id, "name" => "", ); } ?>
