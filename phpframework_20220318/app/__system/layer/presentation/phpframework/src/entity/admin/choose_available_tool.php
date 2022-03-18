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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $selected_db_driver = $_GET["selected_db_driver"]; $is_popup = $_GET["is_popup"]; $filter_by_layout = $_GET["filter_by_layout"]; $layers_beans = AdminMenuHandler::getLayers($user_global_variables_file_path); if ($layers_beans && $layers_beans["presentation_layers"]) { if ($bean_name && $bean_file_name && $path && $layers_beans["presentation_layers"][$bean_name] == $bean_file_name) { $layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $bean_file_name, $bean_name, $user_global_variables_file_path); $filter_by_layout = "$layer_bean_folder_name/" . preg_replace("/\/+$/", "", $path); $filter_by_layout_permission = UserAuthenticationHandler::$PERMISSION_BELONG_NAME; } else if ($filter_by_layout) { foreach ($layers_beans["presentation_layers"] as $bn => $bfn) { $layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $bfn, $bn, $user_global_variables_file_path); $layer_bean_folder_name .= "/"; if (substr($filter_by_layout, 0, strlen($layer_bean_folder_name)) == $layer_bean_folder_name) { $bean_name = $bn; $bean_file_name = $bfn; $path = substr($filter_by_layout, strlen($layer_bean_folder_name)); $filter_by_layout_permission = UserAuthenticationHandler::$PERMISSION_BELONG_NAME; break; } } } } $do_not_filter_by_layout = array( "bean_name" => $bean_name, "bean_file_name" => $bean_file_name, ); include $EVC->getUtilPath("admin_uis_layers_and_permissions"); ?>
