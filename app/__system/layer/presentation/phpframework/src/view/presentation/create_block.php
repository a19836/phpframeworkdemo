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

$query_string = str_replace(array("&edit_block_type=advanced", "&edit_block_type=simple"), "", $_SERVER["QUERY_STRING"]); $title = isset($title) ? $title : "Create Block"; $sub_title = isset($sub_title) ? $sub_title : '(<a href="' . $project_url_prefix . 'phpframework/presentation/edit_block?' . $query_string . '&edit_block_type=advanced">Show Advanced UI</a>)'; $add_block_url = $add_block_url ? $add_block_url : $project_url_prefix . "phpframework/presentation/edit_block?bean_name=$bean_name&bean_file_name=$bean_file_name&filter_by_layout=$filter_by_layout&path=$path&module_id=#module_id#"; $head = '
<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/create_block.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/create_block.js"></script>

<script>
var add_block_url = "' . $add_block_url . '";
</script>'; $main_content = '<div class="title">' . $title . '</div>
' . ($sub_title ? '<div class="sub_title">' . $sub_title . '</div>' : '') . '

<div class="modules_list">
	<table>
		<tr>
			<th class="table_header group"></th>
			<th class="table_header photo">Photo</th>
			<th class="table_header label">Label</th>
			<th class="table_header module_id">Module ID</th>
			<th class="table_header description">Description</th>
			<th class="table_header buttons"></th>
		</tr>'; foreach ($loaded_modules as $group_module_id => $loaded_modules_by_group) { $main_content .= '<tr>
		<td class="group" colspan="6">
			<label>' . $group_module_id . '</label>
			<span class="icon maximize" onClick="toggleGroupOfMopdules(this, \'' . $group_module_id . '\')" title="Toggle Group of Modules">Toggle Group of Modules</span>
		</td>
	</tr>'; foreach ($loaded_modules_by_group as $module_id => $loaded_module) { $main_content .= '<tr class="group_module_item" group_module_id="' . $group_module_id . '">
			<td class="group"></td>
			<td class="photo">' . ($loaded_module["images"][0]["url"] ? '<img src="' . $loaded_module["images"][0]["url"] . '" />' : 'No Photo') . '</td>
			<td class="label">' . $loaded_module["label"] . '</td>
			<td class="module_id">' . $loaded_module["id"] . '</td>
			<td class="description">' . $loaded_module["description"] . '</td>
			<td class="buttons">
				<span class="icon add" onClick="addBlock(this, \'' . $loaded_module["id"] . '\')" title="Click here to add a new block based in this module: \'' . $loaded_module["label"] . '\'">Add New Block</span>
			</td>
		</tr>'; } } $main_content .= '</table>
</div>'; ?>
