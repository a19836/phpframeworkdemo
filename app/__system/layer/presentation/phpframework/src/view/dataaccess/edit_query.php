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

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Icons CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS and JS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/layout.js"></script>

<!-- Edit QUERY JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/dataaccess/edit_query.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/dataaccess/edit_query.js"></script>

<!-- Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/dataaccess/edit_single_query.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/dataaccess/edit_single_query.js"></script>'; $head .= $WorkFlowQueryHandler->getHeader(); $head .= $WorkFlowQueryHandler->getDataAccessJavascript($bean_name, $bean_file_name, $path, $item_type, $hbn_obj_id, $get_layer_sub_files_url); $head .= '<script>
var save_data_access_object_url = \'' . $project_url_prefix . 'phpframework/dataaccess/save_query?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '&obj=' . $hbn_obj_id . '&relationship_type=' . $relationship_type . '\';
var remove_data_access_object_url = \'' . $project_url_prefix . 'phpframework/dataaccess/remove_query?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '&obj=' . $hbn_obj_id . '&relationship_type=' . $relationship_type . '&query_id=#obj_id#&query_type=' . $query_type . '\';
var old_obj_id = \'' . $query_id . '\';
var old_obj_type = \'' . $rel_type . '\';
var is_covertable_sql = ' . ($is_covertable_sql ? 1 : 0) . ';
</script>'; $main_content = $WorkFlowQueryHandler->getGlobalTaskFlowChar(); $rand = rand(0, 1000); $main_content .= '<div class="edit_single_query' . ($is_covertable_sql ? " covertable_sql" : "") . '">
	<div class="top_bar">
		<header>
			<div class="title">
				' . ($query_id ? "Edit" : "Add") . ' <span class="query_type"></span> SQL Query: <span class="query_name"></span>
				<input class="is_convertable_sql" type="checkbox" title="Is SQL convertable"' . ($is_covertable_sql ? " checked" : "") . ' onChange="onChangeIsConvertableSQL(this)" previous_auto_convert="1" />
			</div>
			<ul>
				<li class="full_screen" title="Toggle Full Screen"><a onClick="toggleFullScreen(this)"><i class="icon full_screen"></i> Full Screen</a></li>
				<li class="save" title="Save Query"><a onClick="saveQueryObject(onSuccessSingleQuerySave)"><i class="icon save"></i> Save</a></li>
				<li class="sub_menu">
					<i class="icon sub_menu"></i>
					<ul>
						<li class="add_new_table select_query" title="Add new Table"><a onclick="return addNewTask(' . $rand . ');"><i class="icon add"></i> Add Table</a></li>
						<li class="update_tables_attributes select_query" title="Update Tables\' Attributes"><a onclick="return updateQueryDBBroker(' . $rand . ', false);"><i class="icon update_tables_attributes"></i> Update Tables\' Attributes</a></li>
						<li class="toggle_ui select_query" title="Toggle Query Diagram"><a class="toggle_icon active" onclick="return showOrHideSingleQueryUI(this, ' . $rand . ');"><i class="icon toggle_ui"></i> Toggle Query Diagram</a></li>
						<li class="toggle_settings select_query" title="Toggle Query Settings"><a class="toggle_icon active" onclick="return showOrHideSingleQuerySettings(this, ' . $rand . ');"><i class="icon toggle_settings"></i> Toggle Query Settings</a></li>
						
						<li class="create_sql_from_ui" title="Generate SQL From Diagram"><a onClick="autoUpdateSqlFromUI(' . $rand . ')"><i class="icon create_sql_from_ui"></i> Generate SQL From Diagram</a></li>
						<li class="create_ui_from_sql" title="Generate Diagram From Settings"><a onClick="autoUpdateUIFromSql(' . $rand . ')"><i class="icon create_ui_from_sql"></i> Generate Diagram From Settings</a></li>
						<li class="toggle_main_settings" title="Toggle Main Settings"><a class="toggle_icon" onClick="toggleMainSettingsPanel(this, \'.edit_single_query\')"><i class="icon toggle_ids"></i> Toggle Main Settings</a></li>
						<li class="dummy_elm_to_add_auto_save_options"></li>
					</ul>
				</li>
			</ul>
		</header>
	</div>'; if ($obj_data || !$query_id) { $main_content .= $WorkFlowQueryHandler->getChooseQueryTableOrAttributeHtml("choose_db_table_or_attribute"); $main_content .= $WorkFlowQueryHandler->getChooseDAOObjectFromFileManagerHtml("choose_dao_object_from_file_manager"); $main_content .= $WorkFlowQueryHandler->getChooseAvailableMapIdHtml("choose_map_id"); $data = array( "type" => $rel_type, "name" => $name, "parameter_class" => $parameter_class, "parameter_map" => $parameter_map, "result_class" => $result_class, "result_map" => $result_map, "sql" => $sql ); $settings = array( "init_ui" => true, "encapsulate_parameter_and_result_settings" => true, ); $sql_html = $WorkFlowQueryHandler->getQueryBlockHtml(false, $settings, $data); $sql_html = str_replace("#rand#", $rand, $sql_html); $main_content .= '
<div class="data_access_obj">	
	<div class="relationships">
		<div class="rels">
			' . $sql_html . '
		</div>
	</div>
</div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the selected object. Please refresh and try again...</div>'; $main_content .= '</div>'; ?>
