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
 $head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/db/import_table_data.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/db/import_table_data.js"></script>

<script>
var column_head_html = \'' . str_replace("'", "\\'", str_replace("\n", "", getColumnHeadHtml("#column_index#"))) . '\';
var column_attributes_html = \'' . str_replace("'", "\\'", str_replace("\n", "", getColumnAttributeHtml($table_attrs))) . '\';
</script>'; $main_content = '
<div class="top_bar">
	<header>
		<div class="title">Import Data into Table \'' . $table . '\' in DB: \'' . $bean_name . '\'</div>
		<ul>
			<li class="import" data-title="Import"><a onClick="submitForm(this)"><i class="icon continue"></i> Import</a></li>
		</ul>
	</header>
</div>

<div class="import_table_data with_top_bar_section">'; if ($error_message) $main_content .= '<div class="error">' . $error_message . ($errors ? '<br/>Please see errors bellow...' : '') . '</div>'; $main_content .= '
	<form method="post" enctype="multipart/form-data">
		<div class="file_type">
			<label>File Type: </label>
			<select name="file_type" onChange="onChangeFileType(this)">
				<option value="txt">Text File</option>
				<option value="csv"' . ($file_type == "csv" ? " selected" : "") . '>CSV File</option>
				<!--option value="xls"' . ($file_type == "xls" ? " selected" : "") . '>Excel File</option-->
			</select>
		</div>
		
		<div class="file">
			<label>File: </label>
			<input type="file" name="file" />
		</div>
		
		<div class="rows_delimiter"' . ($file_type == "csv" || $file_type == "xls" ? ' style="display:none;"' : "") . '>
			<label>Rows Delimiter: </label>
			<input type="text" name="rows_delimiter" value="' . $rows_delimiter . '" placeHolder="Default is: end-line or \\n" />
		</div>
		
		<div class="columns_delimiter"' . ($file_type == "csv" || $file_type == "xls" ? ' style="display:none;"' : "") . '>
			<label>Columns Delimiter: </label>
			<input type="text" name="columns_delimiter" value="' . $columns_delimiter . '" placeHolder="Default is: Tab or \\t" />
		</div>
		
		<div class="enclosed_by"' . ($file_type == "csv" || $file_type == "xls" ? ' style="display:none;"' : "") . '>
			<label>Columns Enclosed By: </label>
			<input type="text" name="enclosed_by" value="' . $enclosed_by . '" placeHolder="Default is: &quot;" />
		</div>
		
		<div class="ignore_rows_number">
			<label>Number of first rows to ignore: </label>
			<input type="text" name="ignore_rows_number" value="' . $ignore_rows_number . '" />
		</div>
		
		<div class="insert_ignore">
			<input type="checkbox" name="insert_ignore" value="1" onClick="activateCheckBox(this)"' . ($insert_ignore ? " checked" : "") . ($update_existent ? " disabled" : "") . ' />
			<label>Only inserts new records</label>
			<div class="info">If this is checked, the system will not insert the repteaded records.</div>
		</div>
		
		<div class="update_existent">
			<input type="checkbox" name="update_existent" value="1" onClick="activateCheckBox(this)"' . ($update_existent ? " checked" : "") . ($insert_ignore ? " disabled" : "") . ' />
			<label>Update existent records</label>
			<div class="info">If this is checked, the system will update the repteaded records.</div>
		</div>
		
		<div class="force">
			<input type="checkbox" name="force" value="1"' . ($force ? " checked" : "") . ' />
			<label>Forces rows until the end.</label>
			<div class="info">If this is checked, the system will not stop on the first error and try to insert into the DB all rows until the end of the file.</div>
		</div>
		
		<div class="columns_attributes">
			<label>Columns to Attributes: <span class="icon add" onClick="addNewColumn(this)" title="Add Column">Add</span></label>
		</div>
		
		<table class="columns_attributes_table">
			<thead>
				<tr>'; if (is_array($columns_attributes)) foreach ($columns_attributes as $idx => $attr_name) $main_content .= getColumnHeadHtml($idx + 1); $main_content .= '
				</tr>
			</thead>
			<tbody class="fields">
				<tr>'; if (is_array($columns_attributes)) foreach ($columns_attributes as $idx => $attr_name) $main_content .= getColumnAttributeHtml($table_attrs, $attr_name); $main_content .= '
				</tr>
			</tbody>
		</table>
	</form>'; if ($errors) $main_content .= '<div class="errors">
		<label>Errors:</label>
		<ul>
			<li>' . implode('</li><li>', $errors) . '</li>
		</ul>
	</div>'; $main_content .= '
</div>'; function getColumnHeadHtml($v39d7f37cb9) { return '<th class="column table_header"><span class="label">Column ' . $v39d7f37cb9 . '</span><span class="icon delete" onClick="removeColumn(this)" title="Remove Column">Remove</span></th>'; } function getColumnAttributeHtml($pc3502754, $v342541f3c7 = null) { $pf8ed4912 = '
	<td class="column">
		<select name="columns_attributes[]">
			<option value="">-- none --</option>'; if ($pc3502754) foreach ($pc3502754 as $v5e45ec9bb9) $pf8ed4912 .= '
			<option' . ($v5e45ec9bb9 == $v342541f3c7 ? ' selected' : '') . '>' . $v5e45ec9bb9 . '</option>'; $pf8ed4912 .= '
		</select>
	</td>'; return $pf8ed4912; } ?>
