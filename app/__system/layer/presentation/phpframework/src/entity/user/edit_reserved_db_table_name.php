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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $reserved_db_table_name_id = $_GET["reserved_db_table_name_id"]; if ($reserved_db_table_name_id) $reserved_db_table_name_data = $UserAuthenticationHandler->getReservedDBTableName($reserved_db_table_name_id); if ($_POST["reserved_db_table_name_data"]) { $new_reserved_db_table_name_data = $_POST["reserved_db_table_name_data"]; if ($_POST["delete"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); if ($reserved_db_table_name_id && $UserAuthenticationHandler->deleteReservedDBTableName($reserved_db_table_name_id)) { die("<script>alert('Reserved db table name deleted successfully'); document.location = '$project_url_prefix/user/manage_reserved_db_table_names';</script>"); } else { $reserved_db_table_name_data = $new_reserved_db_table_name_data; $error_message = "There was an error trying to delete this reserved db table name. Please try again..."; } } else if (empty($new_reserved_db_table_name_data["name"])) { $reserved_db_table_name_data = $new_reserved_db_table_name_data; $error_message = "Error: Name cannot be undefined"; } else { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $new_reserved_db_table_name_data["name"] = strtolower($new_reserved_db_table_name_data["name"]); if ($reserved_db_table_name_data["name"] != $new_reserved_db_table_name_data["name"]) { $results = $UserAuthenticationHandler->searchReservedDBTableNames(array("name" => $new_reserved_db_table_name_data["name"])); if ($results[0]) { $reserved_db_table_name_data = $new_reserved_db_table_name_data; $error_message = "Error: Repeated Name"; } } if (!$error_message) { if ($reserved_db_table_name_data) { $reserved_db_table_name_data = array_merge($reserved_db_table_name_data, $new_reserved_db_table_name_data); if ($UserAuthenticationHandler->updateReservedDBTableName($reserved_db_table_name_data)) { $status_message = "Reserved db table name updated successfully..."; } else { $error_message = "There was an error trying to update this reserved db table name. Please try again..."; } } else { $reserved_db_table_name_data = $new_reserved_db_table_name_data; $status = $UserAuthenticationHandler->insertReservedDBTableName($reserved_db_table_name_data); if ($status) { die("<script>alert('Reserved db table name inserted successfully'); document.location = '?reserved_db_table_name_id=" . $status . "';</script>"); } else { $error_message = "There was an error trying to insert this reserved db table name. Please try again..."; } } } } } if (empty($reserved_db_table_name_data)) { $reserved_db_table_name_data = array( "reserved_db_table_name_id" => $reserved_db_table_name_id, "name" => "", ); } ?>
