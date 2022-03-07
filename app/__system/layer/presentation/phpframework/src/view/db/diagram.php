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

include $EVC->getUtilPath("WorkFlowUIHandler"); if ($bean_name) { $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $head = $WorkFlowUIHandler->getHeader(); $head .= '
	<!-- Top-Bar CSS file -->
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/top_bar.css" type="text/css" charset="utf-8" />
	
	<!-- Add Local JS and CSS files -->
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/db/diagram.css" type="text/css" charset="utf-8" />
	<script>
		var get_updated_db_diagram_url = \'' . $project_url_prefix . 'db/get_updated_db_diagram?layer_bean_folder_name=' . $layer_bean_folder_name . '&bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $workflow_path_id . '\';
		var get_db_data_url = \'' . $project_url_prefix . 'db/get_db_data?layer_bean_folder_name=' . $layer_bean_folder_name . '&bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&table=#table#\';
		var create_diagram_sql_url = \'' . $project_url_prefix . 'db/create_diagram_sql?layer_bean_folder_name=' . $layer_bean_folder_name . '&bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '\';
		
		var task_type_id = "' . WorkFlowDBHandler::TASK_TABLE_TYPE . '";
	</script>
	<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/db/diagram.js"></script>'; $head .= $WorkFlowUIHandler->getJS($workflow_path_id); $head .= '<script>prepareTaskContextMenu();</script>'; $menus = array( "Flush Cache" => array( "class" => "flush_cache", "html" => '<a onClick="return flushCache();"><i class="icon flush_cache"></i> Flush Cache</a>', ), "Empty Diagram" => array( "class" => "empty_diagram", "html" => '<a onClick="emptyDiagam();return false;"><i class="icon empty_diagram"></i> Empty Diagram</a>', ), "Add new Table" => array( "class" => "add_new_table", "html" => '<a onClick="addNewTable();return false;"><i class="icon add"></i> Add new Table</a>', ), "Update DB Diagram Automatically" => array( "class" => "update_db_diagram_automatically", "html" => '<a onClick="return updateDBDiagram();"><i class="icon update_db_diagram_automatically"></i> Update DB Diagram Automatically</a>', ), "Create Diagram's SQL" => array( "class" => "create_diagram_sql", "html" => '<a onClick="createDiagamSQL();return false;"><i class="icon create_diagram_sql"></i> Create Diagram\'s SQL</a>', ), "Save" => array( "class" => "save", "html" => '<a onClick="return saveDBDiagram();"><i class="icon save"></i> Save</a>', ), ); $WorkFlowUIHandler->setMenus($menus); $main_content = '
	<div class="top_bar">
		<header>
			<div class="title">Tables Diagram from DB: \'' . $bean_name . '\'</div>
		</header>
	</div>'; $main_content .= $WorkFlowUIHandler->getContent(); if ($DBDriver) $main_content .= '<script>
			DBTableTaskPropertyObj.column_types = ' . json_encode($DBDriver->getDBColumnTypes()) . ';
			DBTableTaskPropertyObj.column_simple_types = ' . json_encode($DBDriver->getDBColumnSimpleTypes()) . ';
			DBTableTaskPropertyObj.column_numeric_types = ' . json_encode($DBDriver->getDBColumnNumericTypes()) . ';
			DBTableTaskPropertyObj.column_types_ignored_props = ' . json_encode($DBDriver->getDBColumnTypesIgnoredProps()) . ';
			DBTableTaskPropertyObj.column_types_hidden_props = ' . json_encode($DBDriver->getDBColumnTypesHiddenProps()) . ';
			DBTableTaskPropertyObj.table_charsets = ' . json_encode($DBDriver->getTableCharsets()) . ';
			DBTableTaskPropertyObj.table_collations = ' . json_encode($DBDriver->getTableCollations()) . ';
			DBTableTaskPropertyObj.table_storage_engines = ' . json_encode($DBDriver->getStorageEngines()) . ';
			DBTableTaskPropertyObj.column_charsets = ' . json_encode($DBDriver->getColumnCharsets()) . ';
			DBTableTaskPropertyObj.column_collations = ' . json_encode($DBDriver->getColumnCollations()) . ';
		</script>'; $main_content .= '<div class="loading_panel"></div>'; } else { $error_message = "Error: DB Name undefined!"; } ?>
