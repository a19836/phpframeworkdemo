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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $module_db_table_name_id = $_GET["module_db_table_name_id"]; if ($module_db_table_name_id) $module_db_table_name_data = $UserAuthenticationHandler->getModuleDBTableName($module_db_table_name_id); if ($_POST["module_db_table_name_data"]) { $new_module_db_table_name_data = $_POST["module_db_table_name_data"]; if ($_POST["delete"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); if ($module_db_table_name_id && $UserAuthenticationHandler->deleteModuleDBTableName($module_db_table_name_id)) { die("<script>alert('Module db table name deleted successfully'); document.location = '$project_url_prefix/user/manage_module_db_table_names';</script>"); } else { $module_db_table_name_data = $new_module_db_table_name_data; $error_message = "There was an error trying to delete this module db table name. Please try again..."; } } else if (empty($new_module_db_table_name_data["name"])) { $module_db_table_name_data = $new_module_db_table_name_data; $error_message = "Error: Name cannot be undefined"; } else { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $new_module_db_table_name_data["name"] = strtolower($new_module_db_table_name_data["name"]); if ($module_db_table_name_data["name"] != $new_module_db_table_name_data["name"]) { $results = $UserAuthenticationHandler->searchModuleDBTableNames(array("name" => $new_module_db_table_name_data["name"])); if ($results[0]) { $module_db_table_name_data = $new_module_db_table_name_data; $error_message = "Error: Repeated Name"; } } if (!$error_message) { if ($module_db_table_name_data) { $module_db_table_name_data = array_merge($module_db_table_name_data, $new_module_db_table_name_data); if ($UserAuthenticationHandler->updateModuleDBTableName($module_db_table_name_data)) { $status_message = "Module db table name updated successfully..."; } else { $error_message = "There was an error trying to update this module db table name. Please try again..."; } } else { $module_db_table_name_data = $new_module_db_table_name_data; $status = $UserAuthenticationHandler->insertModuleDBTableName($module_db_table_name_data); if ($status) { die("<script>alert('Module db table name inserted successfully'); document.location = '?module_db_table_name_id=" . $status . "';</script>"); } else { $error_message = "There was an error trying to insert this module db table name. Please try again..."; } } } } } if (empty($module_db_table_name_data)) { $module_db_table_name_data = array( "module_db_table_name_id" => $module_db_table_name_id, "name" => "", ); } ?>
