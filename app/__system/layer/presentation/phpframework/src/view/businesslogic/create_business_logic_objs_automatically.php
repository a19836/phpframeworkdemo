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

$filter_by_layout_url_query = LayoutTypeProjectUIHandler::getFilterByLayoutURLQuery($filter_by_layout); $choose_queries_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#$filter_by_layout_url_query&path=#path#"; $head = '
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

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/businesslogic/create_business_logic_objs_automatically.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/businesslogic/create_business_logic_objs_automatically.js"></script>
'; $head .= LayoutTypeProjectUIHandler::getHeader(); if ($_POST["step_1"]) { $exists_any_status_ok = false; $main_content .= '<div class="statuses">
		<div class="top_bar">
			<header>
				<div class="title">Automatic Create Business Logic Files in \'' . $path . '\' - STATUSES:</div>
			</header>
		</div>
		<table>
			<tr>
				<th class="file_path table_header">File Path</th>
				<th class="object_name table_header">Object Name</th>
				<th class="status table_header">Status</th>
			</tr>'; $t = count($statuses); for ($i = 0; $i < $t; $i++) { $s = $statuses[$i]; $status = ($s[2] ? "ok" : "error"); $main_content .= '<tr>
			<td class="file_path">' . $s[0] . '</td>
			<td class="object_name">' . $s[1] . '</td>
			<td class="status status_' . $status . '">' . strtoupper($status) . '</td>
		</tr>'; if ($s[2]) { $exists_any_status_ok = true; } } $main_content .= '
		</table>
	</div>'; if ($exists_any_status_ok) $main_content .= '<script>
		if (window.parent && typeof window.parent.refreshLastNodeChilds == "function")
			window.parent.refreshLastNodeChilds();
		</script>'; } else { $head .= '<script>
	var brokers_db_drivers_name = ' . json_encode($brokers_db_drivers_name) . ';'; if ($related_brokers) foreach ($related_brokers as $b) if ($b[2]) { $get_sub_files_url = str_replace("#bean_file_name#", $b[1], str_replace("#bean_name#", $b[2], $choose_queries_from_file_manager_url)); $head .= 'main_layers_properties.' . $b[2] . ' = {ui: {
					folder: {
						get_sub_files_url: "' . $get_sub_files_url . '",
					},
					cms_common: {
						get_sub_files_url: "' . $get_sub_files_url . '",
					},
					cms_module: {
						get_sub_files_url: "' . $get_sub_files_url . '",
					},
					cms_program: {
						get_sub_files_url: "' . $get_sub_files_url . '",
					},
					file: {
						attributes: {
							file_path: "#path#",
							broker_name: "' . $b[0] . '",
						}
					},
					obj: {
						attributes: {
							file_path: "#path#",
							broker_name: "' . $b[0] . '",
						}
					},
					import: {
						attributes: {
							file_path: "#path#",
							broker_name: "' . $b[0] . '",
						}
					},
					referenced_folder: {
						get_sub_files_url: "' . $get_sub_files_url . '",
					},
				}};'; } $head .= '
	var get_broker_db_data_url = "' . $project_url_prefix . 'phpframework/dataaccess/get_broker_db_data?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '";
	</script>'; $main_content .= '
	<div class="select_options">
		<div class="top_bar">
			<header>
				<div class="title">Automatic Create Business Logic Files in \'' . $path . '\':</div>
				<ul>
					<li class="continue" title="Continue"><a onClick="submitForm(this, checkChooseFiles);"><i class="icon continue"></i> Continue</a></li>
				</ul>
			</header>
		</div>
		
		<form method="post" onSubmit="return checkChooseFiles(this);">
			<div id="choose_queries_from_file_manager" class="choose_from_file_manager">
				<div class="broker">
					<label>Broker:</label>
					<select onChange="onChangeDBBroker(this)">'; if ($related_brokers) foreach ($related_brokers as $b) { $is_db_broker = $db_brokers_bean_file_by_bean_name[ $b[2] ] == $b[1]; $main_content .= '<option bean_file_name="' . $b[1] . '" bean_name="' . $b[2] . '" broker_name="' . $b[0] . '"' . ($is_db_broker ? ' is_db_broker="1"' : '') . '>' . $b[0] . ($b[2] ? '' : ' (Rest)') . '</option>'; } $main_content .= '
					</select>
				</div>
				<div class="db_driver">
					<label>DB Driver:</label>
					<select name="db_driver" onChange="onChangeDBDriver(this)">'; if ($db_drivers) foreach ($db_drivers as $db_driver_name => $db_driver_props) $main_content .= '<option value="' . $db_driver_name . '">' . $db_driver_name . ($db_driver_props ? '' : ' (Rest)') . '</option>'; $main_content .= '			
					</select>
				</div>
				<div class="type">
					<label>Type:</label>
					<select name="type" onChange="onChangeDBType(this)">
						<option value="db">From DB Server</option>
						<option value="diagram">From DB Diagram</option>
					</select>
				</div>
				<div class="include_db_driver">
					<input type="checkbox" name="include_db_driver" value="1" />
					<label>Hard-code db-driver?</label>
				</div>
				<div class="tables"' . ($is_db_layer ? '' : ' style="display:none;"') . '>
					<label>Tables:</label>
					<ul>'; if ($db_driver_tables) foreach ($db_driver_tables as $table) { $service_name = str_replace(" ", "", ucwords(strtolower(str_replace("_", " ", $table["name"])))) . "Service"; $main_content .= '<li class="table">
					<input type="checkbox" name="files[' . $table["name"] . '][all]" value="' . $default_broker_name . '" />
					<input type="hidden" name="aliases[' . $table["name"] . '][all]" value="" />
					<label title="Click here to enter a different table alias..." onClick="addServiceAlias(this, \'' . $service_name . '\')">' . $table["name"] . ' => ' . $service_name . '</label>
				</li>'; } else $main_content .= '<li>No tables available...</li>'; $main_content .= '</ul>
				</div>
				<ul class="mytree"' . ($is_db_layer ? ' style="display:none;"' : '') . '>
					<li>
						<label>Root</label>
						<ul layer_url="' . $choose_queries_from_file_manager_url . '"></ul>
					</li>
				</ul>
				<div class="options">
					<div class="overwrite">
						<input type="checkbox" name="overwrite" value="1" />
						<label>Do you wish to overwrite the selected items, if they already exists?</label>
					</div>
					<div class="namespace">
						<label>Namespace: </label>
						<input type="text" name="namespace" value="" />
					</div>
				</div>
				
				<input type="hidden" name="step_1" value="Continue" />
			</div>
		</form>
		<script>
			updateLayerUrlFileManager( $("#choose_queries_from_file_manager .broker select")[0] );
		</script>
	</div>'; } ?>
