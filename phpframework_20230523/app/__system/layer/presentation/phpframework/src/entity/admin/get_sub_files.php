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

include_once $EVC->getUtilPath("AdminMenuHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $item_type = $_GET["item_type"]; $folder_type = $_GET["folder_type"]; $filter_by_layout = $_GET["filter_by_layout"]; $filter_by_layout_permission = $_GET["filter_by_layout_permission"]; $path = str_replace("../", "", $path);$filter_by_layout = str_replace("../", "", $filter_by_layout); $AdminMenuHandler = new AdminMenuHandler(); if ($item_type == "dao") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("vendor/dao/$path", "layer", "access"); $sub_files = AdminMenuHandler::getDaoObjs($path, 1); $sub_files["properties"]["bean_name"] = "dao"; $sub_files["properties"]["bean_file_name"] = ""; } else if ($item_type == "lib") { $sub_files = AdminMenuHandler::getLibObjs($path, 1); $sub_files["properties"]["bean_name"] = "lib"; $sub_files["properties"]["bean_file_name"] = ""; } else if ($item_type == "vendor") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("vendor/$path", "layer", "access"); $sub_files = AdminMenuHandler::getVendorObjs($path, 1); $sub_files["properties"]["bean_name"] = "vendor"; $sub_files["properties"]["bean_file_name"] = ""; } else if ($item_type == "other") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("other/$path", "layer", "access"); $sub_files = AdminMenuHandler::getOtherObjs($path, 1); $sub_files["properties"]["bean_name"] = "other"; $sub_files["properties"]["bean_file_name"] = ""; } else if ($item_type == "test_unit") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("vendor/testunit/$path", "layer", "access"); $sub_files = AdminMenuHandler::getTestUnitObjs($path, 1); $sub_files["properties"]["bean_name"] = "test_unit"; $sub_files["properties"]["bean_file_name"] = ""; } else { $layer_object_id = LAYER_PATH . WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $bean_file_name, $bean_name, $user_global_variables_file_path) . "/"; $layer_path_object_id = $layer_object_id . $path . "/"; $options = array( "all" => true, ); $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($layer_path_object_id, "layer", "access"); if ($item_type == "presentation" && $path && $folder_type != "project_folder") { if ($folder_type == "project") $sub_files = AdminMenuHandler::getBeanObjs($bean_file_name, $bean_name, $user_global_variables_file_path, $path, 1, $options); else $sub_files = AdminMenuHandler::getPresentationFolderFiles($bean_file_name, $bean_name, $user_global_variables_file_path, $path, 1, $folder_type, $options); } else $sub_files = AdminMenuHandler::getBeanObjs($bean_file_name, $bean_name, $user_global_variables_file_path, $path, 1, $options); if ($sub_files) { if ($filter_by_layout) { $is_layer_root_path = empty($path); if ($is_layer_root_path && is_array($filter_by_layout_permission) && in_array(UserAuthenticationHandler::$PERMISSION_BELONG_NAME, $filter_by_layout_permission) && in_array(UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME, $filter_by_layout_permission)) { $filter_by_layout_permission = UserAuthenticationHandler::$PERMISSION_BELONG_NAME; $add_referenced_folder = true; } else if (!$filter_by_layout_permission) $filter_by_layout_permission = UserAuthenticationHandler::$PERMISSION_BELONG_NAME; if (!$UserAuthenticationHandler->searchLayoutTypes(array("name" => $filter_by_layout, "type_id" => UserAuthenticationHandler::$LAYOUTS_TYPE_FROM_PROJECT_ID))) $filter_by_layout = $filter_by_layout_permission = null; else $UserAuthenticationHandler->loadLayoutPermissions($filter_by_layout, UserAuthenticationHandler::$LAYOUTS_TYPE_FROM_PROJECT_ID); } prepareSubFiles($sub_files, $UserAuthenticationHandler, $layer_object_id, $layer_path_object_id, $filter_by_layout, $filter_by_layout_permission); if ($filter_by_layout) { if ($filter_by_layout_permission == UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME || (is_array($filter_by_layout_permission) && count($filter_by_layout_permission) == 1 && $filter_by_layout_permission[0] == UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME)) { $js_url_handler = LayoutTypeProjectUIHandler::getJavascriptHandlerToParseGetSubFilesUrlWithOnlyReferencedFiles(); addParseGetSubFilesURLHandlerPropertyToSubFiles($sub_files, $js_url_handler); } if ($add_referenced_folder) { $js_url_handler = LayoutTypeProjectUIHandler::getJavascriptHandlerToParseGetSubFilesUrlWithOnlyBelongingFiles(); addParseGetSubFilesURLHandlerPropertyToSubFiles($sub_files, $js_url_handler); AdminMenuHandler::addReferencedFolderToFilesList($sub_files, $bean_file_name, $bean_name, $path, $item_type); } } } } function prepareSubFiles(&$v2cd5d67337, $pdf77ee66, $pd5679809, $v85cc5e6481, $pb154d332, $v75b595c772) { if (is_array($v2cd5d67337)) foreach ($v2cd5d67337 as $pc0f3a0c5 => $v3a3060fe4b) if ($pc0f3a0c5 != "aliases" && $pc0f3a0c5 != "properties") { if ($v3a3060fe4b["properties"]["item_type"] == "properties") { prepareSubFiles($v2cd5d67337[$pc0f3a0c5], $pdf77ee66, $pd5679809, $v85cc5e6481, $pb154d332, $v75b595c772); $paa2c1de8 = $v2cd5d67337[$pc0f3a0c5]; unset($paa2c1de8["aliases"]); unset($paa2c1de8["properties"]); if (empty($paa2c1de8)) unset($v2cd5d67337[$pc0f3a0c5]); } else { if ($v3a3060fe4b["properties"]["path"]) $v3fab52f440 = $pd5679809 . $v3a3060fe4b["properties"]["path"]; else $v3fab52f440 = $v85cc5e6481 . $pc0f3a0c5; if (!$pdf77ee66->isInnerFilePermissionAllowed($v3fab52f440, "layer", "access")) unset($v2cd5d67337[$pc0f3a0c5]); else if ($pb154d332 && !$pdf77ee66->isLayoutInnerFilePermissionAllowed($v3fab52f440, $pb154d332, "layer", $v75b595c772)) unset($v2cd5d67337[$pc0f3a0c5]); else prepareSubFiles($v2cd5d67337[$pc0f3a0c5], $pdf77ee66, $pd5679809, $v85cc5e6481, $pb154d332, $v75b595c772); } } } function addParseGetSubFilesURLHandlerPropertyToSubFiles(&$v2cd5d67337, $pdeeedd84) { if (is_array($v2cd5d67337)) foreach ($v2cd5d67337 as $pc0f3a0c5 => $v3a3060fe4b) if ($pc0f3a0c5 != "aliases" && $pc0f3a0c5 != "properties") { if ($v3a3060fe4b["properties"]) $v2cd5d67337[$pc0f3a0c5]["properties"]["parse_get_sub_files_url_handler"] = $pdeeedd84; addParseGetSubFilesURLHandlerPropertyToSubFiles($v2cd5d67337[$pc0f3a0c5], $pdeeedd84); } } ?>
