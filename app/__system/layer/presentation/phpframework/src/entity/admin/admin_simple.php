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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $choose_available_project_url = "{$project_url_prefix}admin/choose_available_project?redirect_path=admin"; $default_page = $_GET["default_page"]; if (!empty($_GET["bean_name"])) { $bean_name = $_GET["bean_name"]; UserAuthenticationHandler::setEternalRootCookie("selected_bean_name", $bean_name); } else if (!empty($_COOKIE["selected_bean_name"])) $bean_name = $_COOKIE["selected_bean_name"]; if (!empty($_GET["bean_file_name"])) { $bean_file_name = $_GET["bean_file_name"]; UserAuthenticationHandler::setEternalRootCookie("selected_bean_file_name", $bean_file_name); } else if (!empty($_COOKIE["selected_bean_file_name"])) $bean_file_name = $_COOKIE["selected_bean_file_name"]; if (!empty($_GET["project"])) { $project = $_GET["project"]; UserAuthenticationHandler::setEternalRootCookie("selected_project", $project); } else if (!empty($_COOKIE["selected_project"])) $project = $_COOKIE["selected_project"]; if (!$bean_name || !$bean_file_name || !$project) { header("Location: $choose_available_project_url"); die("<script>document.location = '$choose_available_project_url';</script>"); } $layers_beans = AdminMenuHandler::getLayers($user_global_variables_file_path); if ($bean_name && $bean_file_name && $project && $layers_beans && $layers_beans["presentation_layers"] && $layers_beans["presentation_layers"][$bean_name] == $bean_file_name) { $layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $bean_file_name, $bean_name, $user_global_variables_file_path); $filter_by_layout = "$layer_bean_folder_name/" . preg_replace("/\/+$/", "", $project); $filter_by_layout_permission = UserAuthenticationHandler::$PERMISSION_BELONG_NAME; } $do_not_filter_by_layout = array( "bean_name" => $bean_name, "bean_file_name" => $bean_file_name, ); include $EVC->getUtilPath("admin_uis_layers_and_permissions"); $projects = null; if ($layers["presentation_layers"]) foreach ($layers["presentation_layers"] as $layer_name => $layer) if ($layer["properties"]["bean_name"] == $bean_name && $layer["properties"]["bean_file_name"] == $bean_file_name) { $projects = $presentation_projects[$layer_name]; break; } if (!$projects || !$projects[$project]) { header("Location: $choose_available_project_url"); echo "<script>document.location='$choose_available_project_url';</script>"; die(); } ?>
