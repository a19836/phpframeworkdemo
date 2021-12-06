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

include_once $EVC->getUtilPath("AdminMenuHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $layers = AdminMenuHandler::getLayersFiles($user_global_variables_file_path); unset($layers["db_layers"]); $layers["others"]["other"] = AdminMenuHandler::getOtherObjs(false, 1); $layers["vendors"]["vendor"]["properties"]["item_label"] = "Vendors"; $layers["others"]["other"]["properties"]["item_label"] = "Other Files"; $layers["lib"]["lib"]["properties"] = array( "item_type" => "lib", "item_label" => "Lib", ); foreach ($layers as $layer_type_name => $layer_type) foreach ($layer_type as $layer_name => $layer) { if ($layer_type_name == "vendors" || $layer_type_name == "others") $layer_object_id = $layer_name; else $layer_object_id = LAYER_PATH . WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $layer["properties"]["bean_file_name"], $layer["properties"]["bean_name"], $user_global_variables_file_path); if (!$UserAuthenticationHandler->isInnerFilePermissionAllowed($layer_object_id, "layer", "access")) unset($layers[$layer_type_name][$layer_name]); else if ($layer_type_name == "presentation_layers") { foreach ($layer as $fn => $f) if ($fn != "properties" && $fn != "aliases") unset($layers[$layer_type_name][$layer_name][$fn]); } } ?>
