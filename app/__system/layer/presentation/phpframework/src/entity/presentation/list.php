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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $item_type = $_GET["item_type"]; $element_type = $_GET["element_type"]; $filter_by_layout = $_GET["filter_by_layout"]; $filter_by_layout_permission = $_GET["filter_by_layout_permission"]; $filter_by_layout_permission = $filter_by_layout_permission ? $filter_by_layout_permission : UserAuthenticationHandler::$PERMISSION_BELONG_NAME; $exists_db_drivers = false; if ($item_type == "dao") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("vendor/dao/$path", "layer", "access"); $layers = array( $item_type => AdminMenuHandler::getDaoObjs(false, 1) ); } else if ($item_type == "lib") { $layers = array( $item_type => AdminMenuHandler::getLibObjs(false, 1) ); } else if ($item_type == "vendor") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("vendor/$path", "layer", "access"); $layers = array( $item_type => AdminMenuHandler::getVendorObjs(false, 1) ); } else if ($item_type == "other") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("other/$path", "layer", "access"); $layers = array( $item_type => AdminMenuHandler::getOtherObjs(false, 1) ); } else if ($item_type == "test_unit") { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("vendor/testunit/$path", "layer", "access"); $layers = array( $item_type => AdminMenuHandler::getTestUnitObjs(false, 1) ); } else { $filter_layout_by_layers_type = array("presentation_layers", "business_logic_layers", "data_access_layers"); $do_not_filter_by_layout = array( "bean_name" => $bean_name, "bean_file_name" => $bean_file_name, ); include $EVC->getUtilPath("admin_uis_layers_and_permissions"); if ($item_type) $layers = $layers[$item_type . "_layers"]; else if ($bean_name && $bean_file_name) { $new_layers = array(); foreach ($layers as $layer_type_name => $layer_type) foreach ($layer_type as $layer_name => $layer) { $properties = $layer["properties"]; if ($properties["bean_name"] == $bean_name && $properties["bean_file_name"] == $bean_file_name) { $new_layers[$layer_name] = $layer; if (!$item_type) $item_type = substr($layer_type_name, 0, - strlen("_layers")); break; } } $layers = $new_layers; } else $layers = null; } ?>
