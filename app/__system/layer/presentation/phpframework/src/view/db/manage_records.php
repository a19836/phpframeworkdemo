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
include_once get_lib("org.phpframework.util.text.TextValidator"); include_once get_lib("org.phpframework.util.MimeTypeHandler"); $manage_records_url = $project_url_prefix . "phpframework/db/manage_records?layer_bean_folder_name=$layer_bean_folder_name&bean_name=$bean_name&bean_file_name=$bean_file_name&table=#table#&db_type=$db_type"; $manage_record_url = $project_url_prefix . "phpframework/db/manage_record?layer_bean_folder_name=$layer_bean_folder_name&bean_name=$bean_name&bean_file_name=$bean_file_name&table=$table&db_type=$db_type"; $manage_record_action_url = $project_url_prefix . "phpframework/db/manage_record_action?layer_bean_folder_name=$layer_bean_folder_name&bean_name=$bean_name&bean_file_name=$bean_file_name&table=$table"; $head = '
<!-- TimePicker -->
<link rel="stylesheet" media="all" type="text/css" href="' . $project_common_url_prefix . 'vendor/jquerytimepickeraddon/dist/jquery-ui-timepicker-addon.min.css" />
<script src="' . $project_common_url_prefix . 'vendor/jquerytimepickeraddon/dist/jquery-ui-timepicker-addon.min.js" type="text/javascript"></script>
<script src="' . $project_common_url_prefix . 'vendor/jquerytimepickeraddon/dist/i18n/jquery-ui-timepicker-addon-i18n.min.js" type="text/javascript"></script>
<script src="' . $project_common_url_prefix . 'vendor/jquerytimepickeraddon/dist/jquery-ui-sliderAccess.js" type="text/javascript"></script>
<script src="' . $project_common_url_prefix . 'vendor/jquerytimepickeraddon/dist/i18n/jquery-ui-timepicker-pt.js" type="text/javascript"></script>

<!-- Modernizr  -->
<script src="' . $project_common_url_prefix . 'vendor/modernizr/modernizr.min.js"></script>

<!-- Add DataTables Plugin -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerydatatables/media/css/jquery.dataTables.min.css" charset="utf-8" />
<script src="' . $project_common_url_prefix . 'vendor/jquerydatatables/media/js/jquery.dataTables.min.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/db/manage_records.css" charset="utf-8" />
<script src="' . $project_url_prefix . 'js/db/manage_records.js"></script>

<script>
var manage_record_url = \'' . $manage_record_url . '\';
var manage_record_action_url = \'' . $manage_record_action_url . '\';

var table_fields_types = ' . json_encode($table_fields_types) . ';
var table_fks = ' . json_encode($fks) . ';
var table_extra_fks = ' . json_encode($extra_fks) . ';
var new_row_html = \'' . addcslashes(str_replace("\n", "", getRowHtml("#idx#", $table_fields, $table_fields_types, $pks, $fks, $extra_fks, $manage_records_url)), "\\'") . '\';
var new_condition_html = \'' . addcslashes(str_replace("\n", "", getConditionHtml("#field_name#")), "\\'") . '\';
</script>'; $back_icon = strpos($_SERVER["HTTP_REFERER"], "/db/manage_records?") !== false ? '<li class="back" data-title="Back Page"><a class="icon go_back" onClick="goBackPage(this)">Go Back</a></li>' : ''; $main_content .= '
<div class="top_bar' . ($popup ? " in_popup" : "") . '">
	<header>
		<div class="title">
			Manage Records for table: \'' . $table . '\' in DB: \'' . $bean_name . '\'
			<select class="db_type" onChange="onDBTypeChange(this)">
				<option value="db">From DB Server</option>
				<option value="diagram"' . ($db_type == "diagram" ? ' selected' : '') . '>From DB Diagram</option>
			</select>
		</div>
		<ul>
			' . $back_icon . '
			<li class="refresh" data-title="Refresh Page"><a class="icon refresh" onClick="refreshPage(this)">Refresh</a></li>
		</ul>
	</header>
</div>

<div class="manage_records with_top_bar_section' . ($popup ? " in_popup" : "") . '">'; if (!$table_exists) $main_content .= '<div class="error">Table does not exist!</tr>'; else if (!$table_fields) $main_content .= '<div class="error">Table fields do not exist!</tr>'; else { $PaginationLayout->show_x_pages_at_once = 5; $data = $PaginationLayout->data; $data["style"] = "bootstrap1"; $pagination_html = $PaginationLayout->designWithStyle(1, $data); $head .= '<style>' . $PaginationLayout->getBootstrap1Css() . '</style>'; $main_content .= '<div class="conditions">
		<label>
			Conditions: 
			<select>'; foreach ($table_fields as $field_name => $field) { $label = ucwords(str_replace(array("_", "-"), " ", strtolower($field_name))); $main_content .= '<option value="' . $field_name . '">' . $label . '</option>'; } $main_content .= '			
			</select>
			<span class="icon add" title="Add new condition to search" onClick="addCondition(this)">Add</span>
			<input type="button" value="Reset" onClick="resetCondition(this)">
			<input type="button" value="Search" onClick="searchCondition(this)">
		</label>
		<ul>'; if ($conditions) foreach ($conditions as $field_name => $field_value) $main_content .= getConditionHtml($field_name, $field_value, $conditions_operators ? $conditions_operators[$field_name] : null); $main_content .= '	
		</ul>
	</div>'; $main_content .= '
	<div class="top_pagination">' . $pagination_html . '</div>
	
	<form method="post" onSubmit="return MyJSLib.FormHandler.formCheck(this);" enctype="multipart/form-data">
		<div class="responsive_table">
			<table class="display compact">
				<thead>
					<tr>
						<th class="table_header select_item">
							<input type="checkbox" onClick="toggleAll(this)" />
						</th>'; foreach ($table_fields as $field_name => $field) { $label = ucwords(str_replace(array("_", "-"), " ", strtolower($field_name))); $main_content .= '	<th class="table_header" attr_name="' . $field_name . '">
							' . $label . '
							<span class="icon sort' . ($sorts[$field_name] ? " sort_" . $sorts[$field_name] : "") . '" title="' . ($sorts[$field_name] ? "Sorted " . $sorts[$field_name] : "Not sorted") . '" onClick="sortRecords(this)">Sort</span>
							' . ($sorts[$field_name] ? '<span class="icon unsort" title="Unsort" onClick="unsortRecords(this)">Unsort</span>' : '') . '
						</th>'; } $main_content .= '
						<th class="table_header actions">
							<span class="icon add" onClick="insertNewRecord(this)">Add</span>
						</th>'; if ($fks || $extra_fks) $main_content .= '	<th class="table_header fks"></th>'; $main_content .= '	</tr>
				</thead>
				<tbody>'; if (!$results) $main_content .= '<tr><td class="empty" colspan="' . ($table_fields ? count($table_fields) + 2 + ($fks || $extra_fks ? 1 : 0) : 0) . '">Empty results...</tr>'; else { $t = count($results); for ($i = 0; $i < $t; $i++) $main_content .= getRowHtml($i, $table_fields, $table_fields_types, $pks, $fks, $extra_fks, $manage_records_url, $results[$i]); } $main_content .= '</tbody>
			</table>
		</div>
		
		<div class="total">Total of records: ' . $count . '</div>
		
		<div class="buttons">
			<input class="delete" type="submit" name="delete" value="Delete" data-confirmation="1" data-confirmation-message="Do you wish to delete this Item?">
		</div>
	</form>
	<div class="bottom_pagination">' . $pagination_html . '</div>'; } $main_content .= '</div>'; function getConditionHtml($v4ef49e289c, $v90963767ae = null, $v19a7745bc6 = null) { $v9acc88059e = ucwords(str_replace(array("_", "-"), " ", strtolower($v4ef49e289c))); return '
	<li>
		<label>' . $v9acc88059e . '</label>
		<select>
			<option value="="' . ($v19a7745bc6 == '=' ? ' selected' : '') . '>equal</option>
			<option value="!="' . ($v19a7745bc6 == '!=' ? ' selected' : '') . '>different</option>
			<option value="&gt;"' . ($v19a7745bc6 == '>' ? ' selected' : '') . '>bigger</option>
			<option value="&lt;"' . ($v19a7745bc6 == '<' ? ' selected' : '') . '>smaller</option>
			<option value="&gt;="' . ($v19a7745bc6 == '>=' ? ' selected' : '') . '>bigger or equal</option>
			<option value="&lt;="' . ($v19a7745bc6 == '<=' ? ' selected' : '') . '>smaller or equal</option>
			<option value="like"' . ($v19a7745bc6 == 'like' ? ' selected' : '') . '>contains</option>
			<option value="not like"' . ($v19a7745bc6 == 'not like' ? ' selected' : '') . '>not contains</option>
			<option value="in"' . ($v19a7745bc6 == 'in' ? ' selected' : '') . '>in ("," delimiter)</option>
			<option value="not in"' . ($v19a7745bc6 == 'not in' ? ' selected' : '') . '>not in ("," delimiter)</option>
			<option value="is"' . ($v19a7745bc6 == 'is' ? ' selected' : '') . '>is null|true|false</option>
			<option value="not is"' . ($v19a7745bc6 == 'is not' ? ' selected' : '') . '>not is null|true|false</option>
		</select>
		<input type="text" name="' . $v4ef49e289c . '" value="' . $v90963767ae . '" />
		<span class="icon delete" onClick="deleteCondition(this)" title="Delete">Remove</span>
	</li>'; } function getRowHtml($v43dd7d0051, $pca1f5780, $v7ff440f783, $pe2f18119 = null, $v571a648e93 = null, $v8949029d70 = null, $v63572f592a = null, $pff59654a = null) { $pca1f5780 = $pca1f5780 ? $pca1f5780 : array(); $v7ff440f783 = $v7ff440f783 ? $v7ff440f783 : array(); $pe2f18119 = $pe2f18119 ? $pe2f18119 : array(); $pff59654a = $pff59654a ? $pff59654a : array(); $pf8ed4912 = '<tr>
		<td class="select_item">
			<input type="checkbox" name="selected_rows[]" value="' . $v43dd7d0051 . '" title="To remove this record, select this box and then click in the delete button below." />'; foreach ($pe2f18119 as $v4ef49e289c) $pf8ed4912 .= '<input type="hidden" name="selected_pks[' . $v43dd7d0051 . '][' . $v4ef49e289c . ']" value="' . $pff59654a[$v4ef49e289c] . '" />'; $pf8ed4912 .= '
		</td>'; foreach ($pca1f5780 as $v4ef49e289c => $v5d170b1de6) { $v90963767ae = $pff59654a[$v4ef49e289c]; $pe35b25f6 = false; $pb2253540 = $v7ff440f783[$v4ef49e289c]; if (TextValidator::isBinary($v90963767ae)) { $pe35b25f6 = true; $pfd5da3fb = new finfo(FILEINFO_MIME_TYPE); $v71dafe3739 = $pfd5da3fb->buffer($v90963767ae); if (MimeTypeHandler::isImageMimeType($v71dafe3739)) $v90963767ae = "<img src=\"data:$v71dafe3739;base64, " . base64_encode($v90963767ae) . "\" />"; else if (!MimeTypeHandler::isTextMimeType($v71dafe3739)) $v90963767ae = "<a onClick=\"downloadFile(this, '$v4ef49e289c')\">Download File</a>"; } if (!$pe35b25f6 && is_array($pb2253540) && $pb2253540["options"] && array_key_exists($v90963767ae, $pb2253540["options"])) $pf8ed4912 .= '<td attr_name="' . str_replace('"', "&quot;", $v4ef49e289c) . '" attr_value="' . str_replace('"', "&quot;", $v90963767ae) . '">' . $pb2253540["options"][$v90963767ae] . '</td>'; else $pf8ed4912 .= '<td' . ($pe35b25f6 && $pb2253540 != "file" ? ' class="binary"' : '') . ' attr_name="' . str_replace('"', "&quot;", $v4ef49e289c) . '">' . ($pb2253540 == "file" ? '<div class="file_content">' . $v90963767ae . '</div>' : $v90963767ae) . '</td>'; } $pf8ed4912 .= '
		<td class="actions">
			<span class="icon delete" title="Delete" onClick="deleteRow(this)">Remove</span>
			<span class="icon switch" title="Make it editable" onClick="toggleRow(this)">Make it editable</span>
			<span class="icon save" title="Save" onClick="saveRow(this)">Save</span>
			<span class="icon undo undo_toggle" title="Discard changes and make it readonly" onClick="toggleRow(this)">Make it readonly</span>
			<span class="icon edit" title="Edit form" onClick="editRow(this)">Edit</span>
		</td>'; if (($v571a648e93 || $v8949029d70) && $v63572f592a) { $pf8ed4912 .= '<td class="fks">'; if ($v571a648e93) foreach ($v571a648e93 as $pf71bb87b => $pd042c178) { $pc7b98f2b = ucwords(str_replace(array("_", "-"), " ", strtolower($pf71bb87b))); $pc7b98f2b = substr($pc7b98f2b, -1) == "y" ? substr($pc7b98f2b, 0, -1) . "ies" : $pc7b98f2b . "s"; $v1563ad01df = str_replace("#table#", $pf71bb87b, $v63572f592a); foreach ($pd042c178 as $pfe6103e2 => $v97915b9670) $v1563ad01df .= "&conditions[$pfe6103e2]=" . $pff59654a[$v97915b9670]; $pf8ed4912 .= '<a fk_table="' . $pf71bb87b . '" href="' . $v1563ad01df . '">' . $pc7b98f2b . '</a>'; } if ($v8949029d70) foreach ($v8949029d70 as $pf71bb87b => $pd042c178) { $pc7b98f2b = ucwords(str_replace(array("_", "-"), " ", strtolower($pf71bb87b))); $pc7b98f2b = substr($pc7b98f2b, -1) == "y" ? substr($pc7b98f2b, 0, -1) . "ies" : $pc7b98f2b . "s"; $pc7b98f2b .= $v571a648e93 && $v571a648e93[$pf71bb87b] ? " 2" : ""; $v1563ad01df = str_replace("#table#", $pf71bb87b, $v63572f592a); foreach ($pd042c178 as $pfe6103e2 => $v97915b9670) $v1563ad01df .= "&conditions[$pfe6103e2]=" . $pff59654a[$v97915b9670]; $pf8ed4912 .= '<a fk_table="' . $pf71bb87b . '" href="' . $v1563ad01df . '">' . $pc7b98f2b . '</a>'; } $pf8ed4912 .= '</td>'; } $pf8ed4912 .= '
	</tr>'; return $pf8ed4912; } ?>
