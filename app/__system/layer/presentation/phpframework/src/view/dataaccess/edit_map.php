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

include $EVC->getUtilPath("WorkFlowUIHandler"); $filter_by_layout_url_query = LayoutTypeProjectUIHandler::getFilterByLayoutURLQuery($filter_by_layout); $get_layer_sub_files_url = $project_url_prefix . "admin/get_sub_files?bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=#path#"; $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowQueryHandler = new WorkFlowQueryHandler($WorkFlowUIHandler, $project_url_prefix, $project_common_url_prefix, $db_drivers, $selected_db_broker, $selected_db_driver, $selected_type, $selected_table, $selected_tables_name, $selected_table_attrs, $map_php_types, $map_db_types); $head = $WorkFlowUIHandler->getHeader(); $head .= LayoutTypeProjectUIHandler::getHeader(); $head .= '
<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>

<!-- Edit Code JS file -->
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_code.js"></script>

<!-- Edit QUERY JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/dataaccess/edit_query.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/dataaccess/edit_query.js"></script>

<!-- Top-Bar CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/top_bar.css" type="text/css" charset="utf-8" />

<!-- Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/dataaccess/edit_map.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/dataaccess/edit_map.js"></script>'; $head .= $WorkFlowQueryHandler->getHeader(); $head .= $WorkFlowQueryHandler->getDataAccessJavascript($bean_name, $bean_file_name, $path, $item_type, $hbn_obj_id, $get_layer_sub_files_url); $head .= '<script>
var save_data_access_object_url = \'' . $project_url_prefix . 'phpframework/dataaccess/save_map?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '&obj=' . $hbn_obj_id . '&relationship_type=' . $relationship_type . '\';
var remove_data_access_object_url = \'' . $project_url_prefix . 'phpframework/dataaccess/remove_map?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '&obj=' . $hbn_obj_id . '&relationship_type=' . $relationship_type . '&map=#obj_id#&query_type=' . $query_type . '\';
var old_obj_id = \'' . $map_id . '\';
</script>'; $main_content = $WorkFlowQueryHandler->getGlobalTaskFlowChar(); $main_content .= '<div class="edit_map">
	<div class="top_bar">
		<header>
			<div class="title">
				' . ($map_id ? "Edit" : "Add") . ' ' . ($query_type == "parameter_map" ? "Parameter" : "Result") . ' Map: <span class="map_name"></span>
			</div>
			
			<ul>
				<li class="full_screen" title="Toggle Full Screen"><a onClick="toggleFullScreen(this)"><i class="icon full_screen"></i> Full Screen</a></li>
				<li class="save" title="Save Map"><a onClick="saveMapObject()"><i class="icon save"></i> Save</a></li>
				<li class="sub_menu">
					<i class="icon sub_menu"></i>
					<ul>
						<li class="update_automatically" title="Create Map Automatically"><a onClick="createSingleMapParameterOrResultMapAutomatically()"><i class="icon update_automatically"></i> Update Automatically</a></li>
						<li class="toggle_ids" title="Toggle Map Class"><a class="toggle_icon" onClick="toggleMapClass(this, \'.edit_map\')"><i class="icon toggle_ids"></i> Toggle Map Class</a></li>
						<li class="dummy_elm_to_add_auto_save_options"></li>
					</ul>
				</li>
			</ul>
		</header>
	</div>'; if ($obj_data || !$map_id) { $main_content .= $WorkFlowQueryHandler->getChooseQueryTableOrAttributeHtml("choose_db_table_or_attribute"); $main_content .= $WorkFlowQueryHandler->getChooseDAOObjectFromFileManagerHtml("choose_dao_object_from_file_manager"); $obj_html = $query_type == "parameter_map" ? $WorkFlowQueryHandler->getParameterMapHTML("map", $obj_data, $map_php_types, $map_db_types) : $WorkFlowQueryHandler->getResultMapHTML("map", $obj_data, $map_php_types, $map_db_types); $main_content .= '
<div class="data_access_obj">	
	<div class="relationships">
		<div class="' . ($query_type == "parameter_map" ? 'parameters_maps' : 'results_maps') .' map">
			<div class="' . ($query_type == "parameter_map" ? 'parameters' : 'results') .' map">
				' . $obj_html . '
			</div>
		</div>
	</div>
</div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the selected object. Please refresh and try again...</div>'; ?>
