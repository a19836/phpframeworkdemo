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

include $EVC->getUtilPath("UserAuthenticationUIHandler"); $head = '
<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/user/user.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/user/user.js"></script>

<script>
var get_user_type_permissions_url = \'' . $project_url_prefix . 'user/get_user_type_permissions?user_type_id=#user_type_id#\';
</script>
'; $main_content = '
<div id="menu">' . UserAuthenticationUIHandler::getMenu($UserAuthenticationHandler, $project_url_prefix) . '</div>
<div id="content">
	<div class="title">Manage User Type Permissions</div>
	<div class="user_type_permissions_list">
	<form method="post" onSubmit="return saveUserTypePermissions();">
		<div class="user_type">
			<label>User Type: </label>
			<select name="user_type_id" onChange="updateUserTypePermissions(this)">'; foreach ($user_types as $name => $id) { $main_content .= '<option value="' . $id . '"' . ($user_type_id == $id ? ' selected' : '') . '>' . $name . '</option>'; } $main_content .= '	</select>
		</div>
		<table object_type_id="' . $page_object_type_id . '">
			<tr>
				<th class="table_header object_id">Pages</th>'; foreach ($permissions as $permission_name => $permission_id) { $main_content .= '<th class="table_header user_type_permission user_type_permission_' . $permission_id . '">' . $permission_name . ' <input type="checkbox" onClick="toggleAllPermissions(this, \'user_type_permission_' . $permission_id . '\')" /></th>'; } $main_content .= '</tr>'; foreach ($pages as $page => $available_page_permissions) { $main_content .= '<tr>
		<td class="object_id">' . $page . '</td>'; foreach ($permissions as $permission_name => $permission_id) { $main_content .= '<td class="user_type_permission user_type_permission_' . $permission_id . '" permission_id="' . $permission_id . '">'; if ($available_page_permissions[$permission_name]) { $main_content .= '<input type="checkbox" name="permissions_by_objects[' . $page_object_type_id . '][' . $page . '][]" value="' . $permission_id . '" />'; } $main_content .= '</td>'; } $main_content .= '</tr>'; } $main_content .= '</table>
		<br/>
		<table object_type_id="' . $layer_object_type_id . '">
			<tr>
				<th class="table_header object_id">Layers</th>
				<th class="table_header user_type_permission user_type_permission_' . $permissions["access"] . '">access<input type="checkbox" onClick="toggleAllPermissions(this, \'user_type_permission_' . $permissions["access"] . '\')" /></th>
			</tr>'; foreach ($layers as $layer_type_name => $layer_type) { $main_content .= '
	<tr>
		<td>' . strtoupper(str_replace("_", " ", $layer_type_name)) . '</td>
		<td></td>
	</tr>'; if ($layer_type) foreach ($layer_type as $layer_name => $layer) { if ($layer_type_name == "vendors" || $layer_type_name == "others") $object_id = $layer_name; else $object_id = "$layer_object_id_prefix/" . $layers_object_id[$layer_type_name][$layer_name]; $main_content .= '
			<tr>
				<td class="object_id" object_id="' . $object_id . '">- ' . $layers_label[$layer_type_name][$layer_name] . '</td>
				<td class="user_type_permission user_type_permission_' . $permissions["access"] . '" permission_id="' . $permissions["access"] . '">
					<input type="checkbox" name="permissions_by_objects[' . $layer_object_type_id . '][' . $object_id . '][]" value="' . $permissions["access"] . '" default_value="0" />
					<span class="icon toggle" onClick="toggleLayerPermissionVisibility(this)" title="Set/Unset Permission"></span>
				</td>
			</tr>'; foreach ($layer as $folder_name => $folder) { $object_id = "$layer_object_id_prefix/" . $layers_object_id[$layer_type_name][$layer_name] . "/$folder_name"; $indentation = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", count(explode("/", $folder_name))); $main_content .= '
				<tr>
					<td class="object_id" object_id="' . $object_id . '">' . $indentation . $folder_name . '</td>
					<td class="user_type_permission user_type_permission_' . $permissions["access"] . '" permission_id="' . $permissions["access"] . '">
						<input type="checkbox" name="permissions_by_objects[' . $layer_object_type_id . '][' . $object_id . '][]" value="' . $permissions["access"] . '" default_value="0" />
						<span class="icon toggle" onClick="toggleLayerPermissionVisibility(this)" title="Set/Unset Permission"></span>
					</td>
				</tr>'; } } } $main_content .= '</table>
		<div class="buttons">
			<div class="submit_button">
				<input type="submit" name="save" value="Save" />
			</div>
		</div>
	</form>
	</div>
</div>
<script>
	updateUserTypePermissions( $(".user_type select")[0] );
</script>'; ?>
