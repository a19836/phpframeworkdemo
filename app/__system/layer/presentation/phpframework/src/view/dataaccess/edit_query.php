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

include $EVC->getUtilPath("WorkFlowUIHandler"); $get_layer_sub_files_url = $project_url_prefix . "admin/get_sub_files?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#"; $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowQueryHandler = new WorkFlowQueryHandler($WorkFlowUIHandler, $project_url_prefix, $project_common_url_prefix, $db_drivers, $selected_db_broker, $selected_db_driver, $selected_type, $selected_table, $selected_tables_name, $selected_table_attrs, $map_php_types, $map_db_types); $head = $WorkFlowUIHandler->getHeader(); $head .= '
<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>

<!-- Edit Code JS file -->
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_code.js"></script>

<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $project_url_prefix . 'css/dataaccess/edit_query.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/dataaccess/edit_query.js"></script>'; $head .= $WorkFlowQueryHandler->getHeader(); $head .= $WorkFlowQueryHandler->getDataAccessJavascript($bean_name, $bean_file_name, $path, $item_type, $hbn_obj_id, $get_layer_sub_files_url); $head .= '<script>
var save_data_access_object_url = \'' . $project_url_prefix . 'phpframework/dataaccess/save_query?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '&obj=' . $hbn_obj_id . '&relationship_type=' . $relationship_type . '\';
var remove_data_access_object_url = \'' . $project_url_prefix . 'phpframework/dataaccess/remove_query?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '&obj=' . $hbn_obj_id . '&relationship_type=' . $relationship_type . '&query_id=#obj_id#&query_type=' . $query_type . '\';
var old_obj_id = \'' . $query_id . '\';
</script>
<style>
.relationship {
	border:0;
}
.data_access_obj {
	width:690px;
}
.relationship .header_buttons {
	display:none;
}
.relationship {
	display:none;
}
.save_button {
	padding-top:0;
}
</style>'; $main_content = $WorkFlowQueryHandler->getGlobalTaskFlowChar(); $title = $query_id ? 'Edit "' . $query_id . '" Query SQL' : 'Add Query SQL'; $main_content .= '<div class="title">' . $title . '</div>'; if ($obj_data || !$query_id) { $main_content .= $WorkFlowQueryHandler->getChooseQueryTableOrAttributeHtml("choose_db_table_or_attribute"); $main_content .= $WorkFlowQueryHandler->getChooseDAOObjectFromFileManagerHtml("choose_dao_object_from_file_manager"); $main_content .= $WorkFlowQueryHandler->getChooseAvailableMapIdHtml("choose_map_id"); $data = array( "type" => $rel_type, "name" => $name, "parameter_class" => $parameter_class, "parameter_map" => $parameter_map, "result_class" => $result_class, "result_map" => $result_map, "sql" => $sql ); $settings = array( "init_ui" => true, ); $rand = rand(0, 1000); $sql_html = $WorkFlowQueryHandler->getQueryBlockHtml(false, $settings, $data); $sql_html = str_replace("#rand#", $rand, $sql_html); $main_content .= '
<div class="data_access_obj">	
	<div class="relationships">
		<div class="rels">
			' . $sql_html . '
		</div>
	</div>
	<script>
	$(function () {
		$(".relationship").css("display", "block");
		
		var a = $(".query_tabs .query_sql_tab a").first();
		a.attr("not_create_sql_from_ui", 1);
		a.click();
		a.removeAttr("not_create_sql_from_ui");
	});
	</script>
	
	<div class="save_button">
		<input type="button" name="value" value="SAVE" onClick="saveQueryObject();" />
	</div>
</div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the selected object. Please refresh and try again...</div>'; ?>
