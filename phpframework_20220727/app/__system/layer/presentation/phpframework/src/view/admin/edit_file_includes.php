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

include_once $EVC->getUtilPath("WorkFlowPresentationHandler"); include $EVC->getUtilPath("BreadCrumbsUIHandler"); $filter_by_layout_url_query = LayoutTypeProjectUIHandler::getFilterByLayoutURLQuery($filter_by_layout); $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#$filter_by_layout_url_query&path=#path#"; $choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#"; $choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#"; $choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#"; $head = '
<!-- Add MD5 JS File -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquery/js/jquery.md5.js"></script>

<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Icons CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS and JS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/layout.js"></script>

<!-- Edit Code JS file -->
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_php_code.js"></script>

<!-- Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/edit_file_includes.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/edit_file_includes.js"></script>

<script>
var layer_type = "' . ($item_type == "presentation" ? "pres" : ($item_type == "businesslogic" ? "bl" : $item_type)) . '";
var selected_project_id = "' . $selected_project_id . '";

var save_object_url = \'' . $project_url_prefix . 'phpframework/admin/save_file_includes?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '\';

var new_use_html = \'' . str_replace("'", "\\'", str_replace("\n", "", WorkFlowPHPFileHandler::getUseHTML())) .'\';
var new_include_html = \'' . str_replace("'", "\\'", str_replace("\n", "", WorkFlowPHPFileHandler::getInludeHTML())) .'\';
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getBusinessLogicBrokersHtml($business_logic_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDataAccessBrokersHtml($data_access_brokers, $choose_bean_layer_files_from_file_manager_url); $head .= '</script>'; $head .= LayoutTypeProjectUIHandler::getHeader(); $main_content = '
	<div class="top_bar">
		<header>
			<div class="title">Manage Includes in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($file_path, $obj, true) . '</div>
			<ul>
				<li class="save" data-title="Save"><a onClick="saveIncludes()"><i class="icon save"></i> Save</a></li>
				<li class="sub_menu" onClick="openSubmenu(this)">
					<i class="icon sub_menu"></i>
					<ul>
						<li class="full_screen" title="Maximize/Minimize Editor Screen"><a onclick="toggleFullScreen(this)"><i class="icon full_screen"></i> Maximize Editor Screen</a></li>
						<li class="separator"></li>
						<li class="save" title="Save"><a onClick="saveIncludes()"><i class="icon save"></i> Save</a></li>
					</ul>
				</li>
			</ul>
		</header>
	</div>'; $main_content .= WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $db_brokers, $data_access_brokers, $ibatis_brokers, $hibernate_brokers, $business_logic_brokers, $presentation_brokers); $main_content .= '
<div class="includes_obj with_top_bar_section">
	<div class="namespace">
		<label>Namespace:</label>
		<input type="text" value="' . ($obj_data && $obj_data["namespaces"] ? $obj_data["namespaces"][0] : '') . '" placeHolder="Some\Namespace\Here\If\Apply" />
	</div>
	<div class="uses">
		<label>Uses:</label>
		<span class="icon add" onClick="addNewUse(this)" title="Add Use">Add</span>
		<div class="fields">'; $uses = $obj_data ? $obj_data["uses"] : null; if ($uses) foreach ($uses as $use => $alias) $main_content .= WorkFlowPHPFileHandler::getUseHTML($use, $alias); $main_content .= '
		</div>
	</div>
	<div class="includes">
		<label>Includes:</label>
		<span class="icon add" onClick="addNewInclude(this)" title="Add Include">Add</span>
		<div class="fields">'; $includes = $obj_data ? $obj_data["includes"] : null; if ($includes) { $t = count($includes); for ($i = 0; $i < $t; $i++) { $include = $includes[$i]; if ($include && $include[0]) $main_content .= WorkFlowPHPFileHandler::getInludeHTML($include); } } $main_content .= '
		</div>
	</div>
</div>
'; ?>
