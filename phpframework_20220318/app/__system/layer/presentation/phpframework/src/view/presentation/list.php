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

include_once $EVC->getUtilPath("AdminMenuUIHandler"); $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/list.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/list.js"></script>'; $main_content = AdminMenuUIHandler::getContextMenus($exists_db_drivers); if ($item_type == "presentation") { $et = $element_type == "entity" ? "Pages" : ( $element_type == "webroot" ? "Webroot Files" : ( $element_type == "util" ? "Actions" : ( substr($element_type, -1) == "y" ? ucfirst(substr($element_type, 0, -1)) . "ies" : ucfirst($element_type) . "s" ) ) ); } else $et = ucwords($item_type) . " Files"; $main_content .= '
<div class="title">' . $et . ' List:'; if ($element_type == "template") $main_content .= '<a class="sub_title" href="' . $project_url_prefix . 'phpframework/presentation/install_template?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '/src/template/">(Install new Template)</a>'; $main_content .= '</div>'; $main_content .= '
<div id="file_tree" class="mytree hidden ' . ($element_type ? "list_$element_type" : "") . ($path ? ' mytree_filtered' : '') . '">
	<ul>'; $main_layers_properties = array(); if ($layers) foreach ($layers as $layer_name => $layer) { $main_content .= AdminMenuUIHandler::getLayer($layer_name, $layer, $main_layers_properties, $project_url_prefix, $filter_by_layout, $filter_by_layout_permission, $selected_db_driver); if ($item_type == "presentation" && $element_type) { $properties = $main_layers_properties[$layer_name]; $bean_file_name = $properties["bean_file_name"]; $bean_name = $properties["bean_name"]; $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); $main_layers_properties[$layer_name]["prefix_path"] = CMSPresentationLayerHandler::getPresentationLayerPrefixPath($obj, $element_type); } } $main_content .= '
	</ul>
</div>'; if (!$layers) $main_content .= '<div class="error">There are no files!</div>'; $main_content .= '
<script>
	var element_type = "' . $element_type . '";
	var item_type = "' . $item_type . '";
	var path_to_filter = "' . $path . '";
	var inline_icons_by_context_menus = ' . json_encode(AdminMenuUIHandler::getInlineIconsByContextMenus()) . ';
	
	main_layers_properties = ' . json_encode($main_layers_properties) . '; //this var is already created in the filemanage.js
</script>

<div class="myfancypopup auxiliar_popup">
	<iframe></iframe>
</div>'; ?>
