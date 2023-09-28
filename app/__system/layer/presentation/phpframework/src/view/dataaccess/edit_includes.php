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

include $EVC->getUtilPath("WorkFlowUIHandler"); include $EVC->getUtilPath("BreadCrumbsUIHandler"); $filter_by_layout_url_query = LayoutTypeProjectUIHandler::getFilterByLayoutURLQuery($filter_by_layout); $get_dao_sub_files_url = $project_url_prefix . "admin/get_sub_files?bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=#path#"; $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $external_libs_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowQueryHandler = new WorkFlowQueryHandler($WorkFlowUIHandler, $project_url_prefix, $project_common_url_prefix, $db_drivers, $selected_db_broker, $selected_db_driver, $selected_type, $selected_table, $selected_tables_name, $selected_table_attrs, $map_php_types, $map_db_types); $head = $WorkFlowUIHandler->getHeader(); $head .= LayoutTypeProjectUIHandler::getHeader(); $head .= '
<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>

<!-- Add Layout CSS and JS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/layout.js"></script>

<!-- Edit QUERY JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/dataaccess/edit_query.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/dataaccess/edit_query.js"></script>

<!-- Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/dataaccess/edit_includes.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/dataaccess/edit_includes.js"></script>'; $head .= $WorkFlowQueryHandler->getHeader(); $head .= $WorkFlowQueryHandler->getDataAccessJavascript($bean_name, $bean_file_name, $path, $item_type, $hbn_obj_id, $get_dao_sub_files_url); $head .= '<script>
var save_data_access_object_url = \'' . $project_url_prefix . 'phpframework/dataaccess/save_includes?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '&obj=' . $hbn_obj_id . '&relationship_type=' . $relationship_type . '\';
var old_obj_id = null;
</script>'; $main_content = $WorkFlowQueryHandler->getGlobalTaskFlowChar(); $main_content .= '<div class="edit_includes">
	<div class="top_bar' . ($popup ? " in_popup" : "") . '">
		<header>
			<div class="title" title="' . $path . '">Manage Includes in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($file_path, $obj) . '</div>
			<ul>
				<li class="save" data-title="Save Includes"><a onClick="saveIbatisObject()"><i class="icon save"></i> Save</a></li>
				<li class="sub_menu" onClick="openSubmenu(this)">
					<i class="icon sub_menu"></i>
					<ul>
						<li class="full_screen" title="Maximize/Minimize Editor Screen"><a onClick="toggleFullScreen(this)"><i class="icon full_screen"></i> Maximize Editor Screen</a></li>
						<li class="separator"></li>
						<li class="save" title="Save Includes"><a onClick="saveIbatisObject()"><i class="icon save"></i> Save</a></li>
					</ul>
				</li>
			</ul>
		</header>
	</div>'; $main_content .= $WorkFlowQueryHandler->getChooseIncludeFromFileManagerHtml($get_dao_sub_files_url, "choose_include_from_file_manager"); $main_content .= '
	<div class="data_access_obj with_top_bar_section">	
		<div class="relationships">
			' . $WorkFlowQueryHandler->getInludeHTMLBlock($obj_data) . '
		</div>
	</div>
</div>'; ?>
