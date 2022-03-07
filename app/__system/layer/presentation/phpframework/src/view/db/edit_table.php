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
 include_once get_lib("org.phpframework.db.DB"); $charsets = $obj ? $obj->getTableCharsets() : array(); $collations = $obj ? $obj->getTableCollations() : array(); $storage_engines = $obj ? $obj->getStorageEngines() : array(); $column_numeric_types = $obj ? $obj->getDBColumnNumericTypes() : DB::getAllSharedColumnNumericTypes(); $column_types_ignored_props = $obj ? $obj->getDBColumnTypesIgnoredProps() : DB::getAllSharedColumnTypesIgnoredProps(); $column_types_hidden_props = $obj ? $obj->getDBColumnTypesHiddenProps() : DB::getAllSharedColumnTypesHiddenProps(); $charsets = is_array($charsets) ? $charsets : array(); $collations = is_array($collations) ? $collations : array(); $storage_engines = is_array($storage_engines) ? $storage_engines : array(); $column_numeric_types = is_array($column_numeric_types) ? $column_numeric_types : array(); $column_types_ignored_props = is_array($column_types_ignored_props) ? $column_types_ignored_props : array(); $head = '
<!-- Add ACE Editor JS files -->
<script src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
<script src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Icons CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Top-Bar CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/top_bar.css" type="text/css" charset="utf-8" />

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/db/edit_table.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/db/edit_table.js"></script>

<script>
var column_numeric_types = ' . json_encode($column_numeric_types) . ';
var column_types_ignored_props = ' . json_encode($column_types_ignored_props) . ';

var attribute_html = \'' . str_replace("'", "\\'", str_replace("\n", "", getTableAttributeHtml($obj, "#idx#"))) . '\';
var step = ' . ($step ? $step : 0) . ';
</script>'; $main_content = '
<div class="top_bar">
	<header>
		<div class="title">' . ($table ? 'Edit Table \'' . $table . '\'' : 'Add Table') . '</div>
	</header>
</div>

<div class="edit_table">'; if ($table && !$table_exists) $main_content .= '<div class="error">Table does not exists!</div>'; else if ($action == "delete" && $e === true) $main_content .= '<div>Table deleted successfully!</div>'; else { $main_content .= '
	<h3>Table Settings <a class="icon refresh" href="javascript:void(0);" onClick="document.location=document.location+\'\';" title="Refresh">Refresh</a></h3>
	<div class="table_settings">
		<form method="post">
			<input type="hidden" name="step" value="1"/>
			
			<div class="table_name">
				<label>Table Name:</label>
				<input type="text" name="table_name" value="' . $data["table_name"] . '" onBlur="onBlurTableInputBox(this)" />
			</div>'; if (!$table) { $main_content .= '
		<div class="table_charset"' . ($charsets ? '' : ' style="display:none;"') . '>
			<label>Table Charset:</label>
			<select name="table_charset">
				<option value="">-- Default --</option>'; foreach ($charsets as $k => $v) $main_content .= '<option value="' . $k . '">' . $label . '</option>'; $main_content .= '
			</select>
		</div>
		
		<div class="table_collation"' . ($collations ? '' : ' style="display:none;"') . '>
			<label>Table Collation:</label>
			<select name="table_collation">
				<option value="">-- Default --</option>'; foreach ($collations as $k => $v) $main_content .= '<option value="' . $k . '">' . $v . '</option>'; $main_content .= '
			</select>
		</div>
		
		<div class="table_storage_engine"' . ($storage_engines ? '' : ' style="display:none;"') . '>
			<label>Table Storage Engine:</label>
			<select name="table_storage_engine">
				<option value="">-- Default --</option>'; foreach ($storage_engines as $k => $v) $main_content .= '<option value="' . $k . '">' . $v . '</option>'; $main_content .= '
			</select>
		</div>'; } $main_content .= '
			<div class="attributes">
				<label>Table Attributes: <a class="icon add" onClick="addTableAttribute(this)" title="Add">Add</a></label>
			</div>
			
			<table>
				<thead>
					<tr>
						<th class="table_attr_primary_key table_header">PK</th>
						<th class="table_attr_name table_header">Name</th>
						<th class="table_attr_type table_header">Type</th>
						<th class="table_attr_length table_header"' . (in_array("length", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Length</th>
						<th class="table_attr_null table_header"' . (in_array("null", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Null</th>
						<th class="table_attr_unsigned table_header"' . (in_array("unsigned", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Unsigned</th>
						<th class="table_attr_unique table_header"' . (in_array("unique", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Unique</th>
						<th class="table_attr_auto_increment table_header"' . (in_array("auto_increment", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Auto Increment</th>
						<th colspan="2" class="table_attr_default table_header"' . (in_array("default", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Default</th>
						<th class="table_attr_extra table_header"' . (in_array("extra", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Extra</th>
						<th class="table_attr_charset table_header"' . (in_array("charset", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Charset</th>
						<th class="table_attr_collation table_header"' . (in_array("collation", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Collation</th>
						<th class="table_attr_comment table_header"' . (in_array("comment", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Comments</th>
						<th class="table_attr_icons">
							<a class="icon add" onClick="addTableAttribute(this)" title="Add">Add</a>
						</th>
					</tr>
				</thead>
				<tbody index_prefix="attributes">'; if ($data["attributes"]) foreach ($data["attributes"] as $idx => $attr) $main_content .= getTableAttributeHtml($obj, $idx, $attr); $colspan = 14 - count($column_types_hidden_props); $main_content .= '	<tr class="no_attributes"' . ($data["attributes"] ? ' style="display:none"' : '') . '><td colspan="' . $colspan . '">No attributes added yet...</td></tr>
				</tbody>
			</table>
			
			<div class="save_button">
				' . ($table ? '<input type="submit" name="update" value="update" />
				<input class="delete" type="submit" name="delete" value="delete" onClick="return onDeleteButton(this);" />' : '<input type="submit" name="add" value="add" />') . '
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
				<input class="back" type="button" name="back" value="back" onClick="return onBackButton(this, 0);" />
				<input class="execute" type="submit" name="execute" value="execute" onClick="return onExecuteButton(this);" />
			</div>'; } else $main_content .= '<div>' . $status_message . '</div>		
			<div class="save_button">
				<input class="back" type="button" name="back" value="back" onClick="return onBackButton(this, 0);" />
			</div>'; $main_content .= '
		</form>
	</div>
	
	<h3>Execution Errors</h3>
	<div class="table_errors">'; if ($error_message) $main_content .= '<div class="error">' . $error_message . ($errors ? '<br/>Please see errors bellow...' : '') . '</div>'; else $main_content .= '<div>SQL executed successfully!</div>'; if ($errors) $main_content .= '<div class="errors">
			<label>Errors:</label>
			<ul>
				<li>' . implode('</li><li>', $errors) . '</li>
			</ul>
		</div>'; $main_content .= '
		<div class="save_button">
			<input class="back" type="button" name="back" value="back" onClick="return onBackButton(this, 1);" />
		</div>
	</div>'; } $main_content .= '
</div>'; function getTableAttributeHtml($v972f1a5c2b, $pd69fb7d0, $v539082ff30 = null) { $pe2c07578 = $v972f1a5c2b ? $v972f1a5c2b->getColumnCharsets() : array(); $v06b0f1c9be = $v972f1a5c2b ? $v972f1a5c2b->getColumnCollations() : array(); $v4159504aa3 = $v972f1a5c2b ? $v972f1a5c2b->getDBColumnTypes() : DB::getAllSharedColumnTypes(); $v8b7819f513 = $v972f1a5c2b ? $v972f1a5c2b->getDBColumnTypesIgnoredProps() : DB::getAllSharedColumnTypesIgnoredProps(); $pdc1215e3 = $v972f1a5c2b ? $v972f1a5c2b->getDBColumnTypesHiddenProps() : DB::getAllSharedColumnTypesHiddenProps(); $pe2c07578 = is_array($pe2c07578) ? $pe2c07578 : array(); $v06b0f1c9be = is_array($v06b0f1c9be) ? $v06b0f1c9be : array(); $v4159504aa3 = is_array($v4159504aa3) ? $v4159504aa3 : array(); $v8b7819f513 = is_array($v8b7819f513) ? $v8b7819f513 : array(); $v4a32ab2adf = is_array($v8b7819f513[ $v539082ff30["type"] ]) ? $v8b7819f513[ $v539082ff30["type"] ] : array(); $pd8c9353b = $v539082ff30["primary_key"] ? false : $v539082ff30["null"]; $v9707fb73e0 = $v539082ff30["primary_key"] ? true : $v539082ff30["unique"]; $v6c33401cc3 = $v539082ff30["auto_increment"]; $v6a54e01756 = strlen($v539082ff30["default"]) ? true : $v539082ff30["has_default"]; $v78c1f573dd = !$v539082ff30["type"] || in_array("length", $v4a32ab2adf); $pd68d0564 = !$v539082ff30["type"] || in_array("unsigned", $v4a32ab2adf); $v6876f9e654 = in_array("null", $v4a32ab2adf); $pebcbeb3e = in_array("auto_increment", $v4a32ab2adf); $pd0d5cab2 = in_array("default", $v4a32ab2adf); $pa3102e3f = in_array("extra", $v4a32ab2adf); $v5ef44a2b0a = in_array("charset", $v4a32ab2adf); $pfba02f79 = in_array("collation", $v4a32ab2adf); $pd5de3879 = in_array("comment", $v4a32ab2adf); $pf8ed4912 = '
	<tr>
		<td class="table_attr_primary_key">
			<input type="checkbox" name="attributes[' . $pd69fb7d0 . '][primary_key]" ' . ($v539082ff30["primary_key"] ? 'checked="checked"' : '') . ' value="1" onClick="onClickCheckBox(this)" />
		</td>
		<td class="table_attr_name">
			<input type="hidden" name="attributes[' . $pd69fb7d0 . '][old_name]" value="' . $v539082ff30["name"] . '" />
			<input type="text" name="attributes[' . $pd69fb7d0 . '][name]" value="' . $v539082ff30["name"] . '" onBlur="onBlurTableAttributeInputBox(this)" />
		</td>
		<td class="table_attr_type">
			<select name="attributes[' . $pd69fb7d0 . '][type]" onChange="onChangeSelectBox(this)"><option></option>'; foreach ($v4159504aa3 as $pb13127fc => $v597da3fc4e) $pf8ed4912 .= '<option value="' . $pb13127fc . '" ' . ($pb13127fc == $v539082ff30["type"] ? "selected" : "") . '>' . $v597da3fc4e . '</option>'; if ($v539082ff30["type"] && !array_key_exists($v539082ff30["type"], $v4159504aa3)) $pf8ed4912 .= '<option value="' . $v539082ff30["type"] . '">' . $v539082ff30["type"] . ' - NON DEFAULT</option>'; $pf8ed4912 .= '
			</select>
		</td>
		<td class="table_attr_length"' . (in_array("length", $pdc1215e3) ? ' style="display:none;"' : '') . '>
			<input type="text" name="attributes[' . $pd69fb7d0 . '][length]" value="' . $v539082ff30["length"] . '" ' . ($v78c1f573dd ? 'disabled="disabled"' : '') . ' />
		</td>
		<td class="table_attr_null"' . (in_array("null", $pdc1215e3) ? ' style="display:none;"' : '') . '>
			<input type="checkbox" name="attributes[' . $pd69fb7d0 . '][null]" ' . ($pd8c9353b ? 'checked="checked"' : '') . ' value="1" ' . ($v6876f9e654 ? 'disabled="disabled"' : '') . ' />
		</td>
		<td class="table_attr_unsigned"' . (in_array("unsigned", $pdc1215e3) ? ' style="display:none;"' : '') . '>
			<input type="checkbox" name="attributes[' . $pd69fb7d0 . '][unsigned]" ' . ($v539082ff30["unsigned"] ? 'checked="checked"' : '') . ' value="1" ' . ($pd68d0564 ? 'disabled="disabled"' : '') . ' />
		</td>
		<td class="table_attr_unique"' . (in_array("unique", $pdc1215e3) ? ' style="display:none;"' : '') . '>
			<input type="checkbox" name="attributes[' . $pd69fb7d0 . '][unique]" ' . ($v9707fb73e0 ? 'checked="checked"' : '') . ' value="1" />
		</td>
		<td class="table_attr_auto_increment"' . (in_array("auto_increment", $pdc1215e3) ? ' style="display:none;"' : '') . '>
			<input type="checkbox" name="attributes[' . $pd69fb7d0 . '][auto_increment]" ' . ($v6c33401cc3 ? 'checked="checked"' : '') . ' value="1" ' . ($pebcbeb3e ? 'disabled="disabled"' : '') . ' />
		</td>
		<td class="table_attr_has_default"' . (in_array("default", $pdc1215e3) ? ' style="display:none;"' : '') . '>
			<input type="checkbox" name="attributes[' . $pd69fb7d0 . '][has_default]" ' . ($v6a54e01756 ? 'checked="checked"' : '') . ' value="1" ' . ($pd0d5cab2 ? 'disabled="disabled"' : '') . ' onClick="onClickCheckBox(this)" title="Enable/Disable Default value" />
		</td>
		<td class="table_attr_default"' . (in_array("default", $pdc1215e3) ? ' style="display:none;"' : '') . '>
			<input type="text" name="attributes[' . $pd69fb7d0 . '][default]" value="' . $v539082ff30["default"] . '" ' . ($v6a54e01756 && !$pd0d5cab2 ? '' : 'disabled="disabled"') . ' />
		</td>
		<td class="table_attr_extra"' . (in_array("extra", $pdc1215e3) ? ' style="display:none;"' : '') . '>
			<input type="text" name="attributes[' . $pd69fb7d0 . '][extra]" value="' . $v539082ff30["extra"] . '" ' . ($pa3102e3f ? 'disabled="disabled"' : '') . ' />
		</td>
		<td class="table_attr_charset"' . (in_array("charset", $pdc1215e3) ? ' style="display:none;"' : '') . '>
			<select name="attributes[' . $pd69fb7d0 . '][charset]" ' . ($v5ef44a2b0a ? 'disabled="disabled"' : '') . '>
				<option value="">-- Default --</option>'; $v7df711f565 = false; $v1c4e41faf7 = $v539082ff30["charset"] ? strtolower($v539082ff30["charset"]) : ""; foreach ($pe2c07578 as $v61b7283f7b => $v0467b2fac1) { $pb69caaf8 = strtolower($v61b7283f7b) == $v1c4e41faf7; $pf8ed4912 .= '<option value="' . $v61b7283f7b . '" ' . ($pb69caaf8 ? "selected" : "") . '>' . $v0467b2fac1 . '</option>'; if ($pb69caaf8) $v7df711f565 = true; } if ($v539082ff30["charset"] && !$v7df711f565) $pf8ed4912 .= '<option value="' . $v539082ff30["charset"] . '" selected>' . $v539082ff30["charset"] . ' - NON DEFAULT</option>'; $pf8ed4912 .= '
			</select>
		</td>
		<td class="table_attr_collation"' . (in_array("collation", $pdc1215e3) ? ' style="display:none;"' : '') . '>
			<select name="attributes[' . $pd69fb7d0 . '][collation]" ' . ($pfba02f79 ? 'disabled="disabled"' : '') . '>
				<option value="">-- Default --</option>'; $pe2cf4fc3 = false; $v15887de58e = $v539082ff30["collation"] ? strtolower($v539082ff30["collation"]) : ""; foreach ($v06b0f1c9be as $v7c30a554a1 => $pc7ef7941) { $pb69caaf8 = strtolower($v7c30a554a1) == $v15887de58e; $pf8ed4912 .= '<option value="' . $v7c30a554a1 . '" ' . ($pb69caaf8 ? "selected" : "") . '>' . $pc7ef7941 . '</option>'; if ($pb69caaf8) $pe2cf4fc3 = true; } if ($v539082ff30["collation"] && !$pe2cf4fc3) $pf8ed4912 .= '<option value="' . $v539082ff30["collation"] . '" selected>' . $v539082ff30["collation"] . ' - NON DEFAULT</option>'; $pf8ed4912 .= '
			</select>
		</td>
		<td class="table_attr_comment"' . (in_array("comment", $pdc1215e3) ? ' style="display:none;"' : '') . '>
			<input type="text" name="attributes[' . $pd69fb7d0 . '][comment]" value="' . $v539082ff30["comment"] . '" ' . ($pd5de3879 ? 'disabled="disabled"' : '') . ' />
		</td>
		<td class="table_attr_icons">
			<a class="icon delete" onClick="removeTableAttribute(this)" ' . ($v539082ff30 ? 'confirm="1"' : "") . ' title="Remove">Remove</a>
		</td>
	</tr>'; return $pf8ed4912; } ?>
