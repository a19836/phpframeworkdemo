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

$manage_record_action_url = $project_url_prefix . "phpframework/db/manage_record_action?layer_bean_folder_name=$layer_bean_folder_name&bean_name=$bean_name&bean_file_name=$bean_file_name&table=$table"; $head = '
<!-- TimePicker -->
<link rel="stylesheet" media="all" type="text/css" href="' . $project_common_url_prefix . 'vendor/jquerytimepickeraddon/dist/jquery-ui-timepicker-addon.min.css" />
<script src="' . $project_common_url_prefix . 'vendor/jquerytimepickeraddon/dist/jquery-ui-timepicker-addon.min.js" type="text/javascript"></script>
<script src="' . $project_common_url_prefix . 'vendor/jquerytimepickeraddon/dist/i18n/jquery-ui-timepicker-addon-i18n.min.js" type="text/javascript"></script>
<script src="' . $project_common_url_prefix . 'vendor/jquerytimepickeraddon/dist/jquery-ui-sliderAccess.js" type="text/javascript"></script>
<script src="' . $project_common_url_prefix . 'vendor/jquerytimepickeraddon/dist/i18n/jquery-ui-timepicker-pt.js" type="text/javascript"></script>

<!-- Tinymce -->
<script src="' . $project_common_url_prefix . 'vendor/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script src="' . $project_common_url_prefix . 'vendor/tinymce/js/tinymce/jquery.tinymce.min.js" type="text/javascript"></script>

<!-- Modernizr  -->
<script src="' . $project_common_url_prefix . 'vendor/modernizr/modernizr.min.js"></script>

<!-- Local CSS and JS  -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/db/manage_record.css" charset="utf-8" />
<script src="' . $project_url_prefix . 'js/db/manage_record.js"></script>

<script>
var manage_record_action_url = \'' . $manage_record_action_url . '\';
</script>'; $main_content .= '
<div class="manage_record">
	<div class="title">Manage Record for table: "' . $table . '"</div>'; if (!$table_exists) $main_content .= '<div class="error">Table does not exist!</div>'; else if (!$table_fields) $main_content .= '<div class="error">Table fields do not exist!</tr>'; else if (!$results && $action != "insert") $main_content .= '<div class="error">Record does not exists!</div>'; else { foreach ($pks as $field_name) $main_content .= '<input type="hidden" name="' . $field_name . '" value="' . $results[$field_name] . '" />'; $main_content .= '<table>'; foreach ($table_fields as $field_name => $field) { $field_value = $results[$field_name]; $label = ucwords(str_replace(array("_", "-"), " ", strtolower($field_name))); $main_content .= '
				<tr>
					<th>' . $label . ': </th>
					<td>'; $field_html_type = $table_fields_types[$field_name]; $field_html_type = $field_html_type ? $field_html_type : "text"; $options = null; if (is_array($field_html_type)) { $options = $field_html_type["options"]; $field_html_type = $options ? $field_html_type["type"] : "text"; } if ($field_html_type == "textarea") $main_content .= '<textarea name="' . $field_name . '">' . $field_value . '</textarea>'; else if ($field_html_type == "select") { $main_content .= '<select name="' . $field_name . '">'; $value_exists = false; if (is_array($options)) foreach($options as $v => $l) { $main_content .= '<option value="' . $v . '"' . ($v == $field_value ? ' selected' : '') . '>' . $l . '</option>'; if ($v == $field_value) $value_exists = true; } if (!$value_exists) $main_content .= '<option value="' . $field_value . '" selected>' . $field_value . '</option>'; $main_content .= '</select>'; } else if ($field_html_type == "checkbox" || $field_html_type == "radio") $main_content .= '<input type="' . $field_html_type . '" name="' . $field_name . '" value="1"' . ($field_value == 1 ? ' checked' : '') . '/>'; else $main_content .= '<input type="' . $field_html_type . '" name="' . $field_name . '" value="' . $field_value . '"/>'; $main_content .= '</td>
		</tr>'; } $main_content .= '</table>
					<div class="buttons">'; if ($action == "insert") $main_content .= '<input class="insert" type="button" value="Add" onClick="addRecord(this)">'; else $main_content .= '<input class="save" type="button" value="Save" onClick="saveRecord(this)">
					<input class="delete" type="button" value="Delete" onClick="deleteRecord(this)">'; $main_content .= '</div>'; } $main_content .= '</div>'; ?>
