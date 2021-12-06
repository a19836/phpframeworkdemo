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

include_once $EVC->getUtilPath("AdminMenuHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $item_type = $_GET["item_type"]; $folder_type = $_GET["folder_type"]; $path = str_replace("../", "", $path); $AdminMenuHandler = new AdminMenuHandler(); if ($item_type == "dao") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("vendor/dao/$path", "layer", "access"); $sub_files = AdminMenuHandler::getDaoObjs($path, 1); $sub_files["properties"]["bean_name"] = "dao"; $sub_files["properties"]["bean_file_name"] = ""; } else if ($item_type == "lib") { $sub_files = AdminMenuHandler::getLibObjs($path, 1); $sub_files["properties"]["bean_name"] = "lib"; $sub_files["properties"]["bean_file_name"] = ""; } else if ($item_type == "vendor") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("vendor/$path", "layer", "access"); $sub_files = AdminMenuHandler::getVendorObjs($path, 1); $sub_files["properties"]["bean_name"] = "vendor"; $sub_files["properties"]["bean_file_name"] = ""; } else if ($item_type == "other") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("other/$path", "layer", "access"); $sub_files = AdminMenuHandler::getOtherObjs($path, 1); $sub_files["properties"]["bean_name"] = "other"; $sub_files["properties"]["bean_file_name"] = ""; } else if ($item_type == "test_unit") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("vendor/testunit/$path", "layer", "access"); $sub_files = AdminMenuHandler::getTestUnitObjs($path, 1); $sub_files["properties"]["bean_name"] = "test_unit"; $sub_files["properties"]["bean_file_name"] = ""; } else { $layer_object_id = LAYER_PATH . WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $bean_file_name, $bean_name, $user_global_variables_file_path) . "/" . $path; $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($layer_object_id, "layer", "access"); if ($item_type == "presentation" && $path && $folder_type != "project_folder") { if ($folder_type == "project") $sub_files = AdminMenuHandler::getBeanObjs($bean_file_name, $bean_name, $user_global_variables_file_path, $path, 1); else $sub_files = AdminMenuHandler::getPresentationFolderFiles($bean_file_name, $bean_name, $user_global_variables_file_path, $path, 1, $folder_type); } else $sub_files = AdminMenuHandler::getBeanObjs($bean_file_name, $bean_name, $user_global_variables_file_path, $path, 1); if ($sub_files) foreach ($sub_files as $sub_file_name => $sub_file) if ($sub_file_name != "aliases" && $sub_file_name != "properties") { $object_id = "$layer_object_id/$sub_file_name"; if (!$UserAuthenticationHandler->isInnerFilePermissionAllowed($object_id, "layer", "access")) unset($sub_files[$sub_file_name]); } } ?>
