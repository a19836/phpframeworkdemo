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
 include_once get_lib("org.phpframework.db.DB"); include $EVC->getUtilPath("WorkFlowUIHandler"); $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $tasks_settings = $WorkFlowTaskHandler->getLoadedTasksSettings(); $task_contents = array(); foreach ($tasks_settings as $group_id => $group_tasks) foreach ($group_tasks as $task_type => $task_settings) if (is_array($task_settings)) $task_contents = $task_settings["task_properties_html"]; $charsets = $obj ? $obj->getTableCharsets() : array(); $collations = $obj ? $obj->getTableCollations() : array(); $storage_engines = $obj ? $obj->getStorageEngines() : array(); $column_charsets = $obj ? $obj->getColumnCharsets() : array(); $column_collations = $obj ? $obj->getColumnCollations() : array(); $column_column_types = $obj ? $obj->getDBColumnTypes() : DB::getAllColumnTypesByType(); $column_column_simple_types = $obj ? $obj->getDBColumnSimpleTypes() : DB::getAllColumnSimpleTypesByType(); $column_numeric_types = $obj ? $obj->getDBColumnNumericTypes() : DB::getAllSharedColumnNumericTypes(); $column_mandatory_length_types = $obj ? $obj->getDBColumnMandatoryLengthTypes() : DB::getAllSharedColumnMandatoryLengthTypes(); $column_types_ignored_props = $obj ? $obj->getDBColumnTypesIgnoredProps() : DB::getAllSharedColumnTypesIgnoredProps(); $column_types_hidden_props = $obj ? $obj->getDBColumnTypesHiddenProps() : DB::getAllSharedColumnTypesHiddenProps(); $charsets = is_array($charsets) ? $charsets : array(); $collations = is_array($collations) ? $collations : array(); $storage_engines = is_array($storage_engines) ? $storage_engines : array(); $column_charsets = is_array($column_charsets) ? $column_charsets : array(); $column_collations = is_array($column_collations) ? $column_collations : array(); $column_column_types = is_array($column_column_types) ? $column_column_types : array(); $column_column_simple_types = is_array($column_column_simple_types) ? $column_column_simple_types : array(); $column_numeric_types = is_array($column_numeric_types) ? $column_numeric_types : array(); $column_mandatory_length_types = is_array($column_mandatory_length_types) ? $column_mandatory_length_types : array(); $column_types_ignored_props = is_array($column_types_ignored_props) ? $column_types_ignored_props : array(); $head = '
<!-- Add ACE Editor JS files -->
<script src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
<script src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>
'; $head .= $WorkFlowUIHandler->getHeader(); $head .= '
<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/db/edit_table.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/db/edit_table.js"></script>

<script>
DBTableTaskPropertyObj.column_types = ' . json_encode($column_column_types) . ';
DBTableTaskPropertyObj.column_simple_types = ' . json_encode($column_column_simple_types) . ';
DBTableTaskPropertyObj.column_numeric_types = ' . json_encode($column_numeric_types) . ';
DBTableTaskPropertyObj.column_mandatory_length_types = ' . json_encode($column_mandatory_length_types) . ';
DBTableTaskPropertyObj.column_types_ignored_props = ' . json_encode($column_types_ignored_props) . ';
DBTableTaskPropertyObj.column_types_hidden_props = ' . json_encode($column_types_hidden_props) . ';
DBTableTaskPropertyObj.table_charsets = ' . json_encode($charsets) . ';
DBTableTaskPropertyObj.table_collations = ' . json_encode($collations) . ';
DBTableTaskPropertyObj.table_storage_engines = ' . json_encode($storage_engines) . ';
DBTableTaskPropertyObj.column_charsets = ' . json_encode($column_charsets) . ';
DBTableTaskPropertyObj.column_collations = ' . json_encode($column_collations) . ';

DBTableTaskPropertyObj.on_update_simple_attributes_html_with_table_attributes_callback = onUpdateSimpleAttributesHtmlWithTableAttributes;
DBTableTaskPropertyObj.on_update_table_attributes_html_with_simple_attributes_callback = onUpdateTableAttributesHtmlWithSimpleAttributes;

var task_property_values = ' . json_encode($data) . ';

var step = ' . ($step ? $step : 0) . ';
</script>'; $main_content = '
<div class="top_bar">
	<header>
		<div class="title">' . ($table ? 'Edit Table \'' . $table . '\'' : 'Add Table') . ' in DB: \'' . $bean_name . '\'</div>
	</header>
</div>

<div class="edit_table">'; if ($table && !$table_exists) $main_content .= '<div class="error">Table does not exists!</div>'; else if ($action == "delete" && $e === true) $main_content .= '<div class="message">Table deleted successfully!</div>'; else if ($obj) { $allow_sort = $obj->allowTableAttributeSorting(); $main_content .= '
	<h3>Table Settings <a class="icon refresh" href="javascript:void(0);" onClick="document.location=document.location+\'\';" title="Refresh">Refresh</a></h3>
	<div class="table_settings' . ($allow_sort ? " allow_sort" : "") . '">
		<div class="selected_task_properties">
		' . $task_contents . '
		</div>
		
		<form method="post">
			<input type="hidden" name="step" value="1"/>
			<textarea class="hidden" name="data"></textarea>
			
			<div class="save_button">
				' . ($table ? '<input class="delete" type="submit" name="delete" value="Delete" onClick="return onDeleteButton(this);" />
				<input type="submit" name="update" value="Update" onClick="return onSaveButton(this);" />' : '<input type="submit" name="add" value="Add" onClick="return onSaveButton(this);" />') . '
			</div>
		</form>
	</div>
	
	<h3>Table SQLs</h3>
	<div class="table_sql_statements">
		<form method="post">
			<input type="hidden" name="step" value="2"/>
			<input type="hidden" name="action" value="' . $action . '"/>
			<textarea class="hidden" name="data">' . json_encode($data) . '</textarea>
			'; if ($sql_statements) { foreach ($sql_statements as $idx => $sql) $main_content .= '<div class="sql_statement">
				<label>' . $sql_statements_labels[$idx] . '</label>
				<textarea class="hidden" name="sql_statements[]">' . htmlspecialchars($sql, ENT_NOQUOTES) . '</textarea>
				<textarea class="editor">' . htmlspecialchars($sql, ENT_NOQUOTES) . '</textarea>
			</div>'; $main_content .= '		
			<div class="save_button">
				<input class="back" type="button" name="back" value="Back" onClick="return onBackButton(this, 0);" />
				<input class="execute" type="submit" name="execute" value="Execute" onClick="return onExecuteButton(this);" />
			</div>'; } else $main_content .= '<div>' . $status_message . '</div>		
			<div class="save_button">
				<input class="back" type="button" name="back" value="Back" onClick="return onBackButton(this, 0);" />
			</div>'; $main_content .= '
		</form>
	</div>
	
	<h3>Execution Status</h3>
	<div class="table_errors">'; if ($error_message) $main_content .= '<div class="error">' . $error_message . ($errors ? '<br/>Please see errors bellow...' : '') . '</div>'; else $main_content .= '<div>SQL executed successfully!</div>'; if ($errors) $main_content .= '<div class="errors">
			<label>Errors:</label>
			<ul>
				<li>' . implode('</li><li>', $errors) . '</li>
			</ul>
		</div>'; $main_content .= '
		<div class="save_button">
			<input class="back" type="button" name="back" value="Back" onClick="return onBackButton(this, 1);" />
		</div>
	</div>'; } $main_content .= '
</div>'; ?>
