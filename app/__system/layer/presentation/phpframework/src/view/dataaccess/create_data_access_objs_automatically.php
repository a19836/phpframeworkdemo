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

include $EVC->getUtilPath("BreadCrumbsUIHandler"); $head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Icons CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/dataaccess/create_data_access_objs_automatically.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/dataaccess/create_data_access_objs_automatically.js"></script>'; $main_content = ''; if ($_POST["step_2"]) { $exists_any_status_ok = false; $main_content .= '<div class="statuses">
		<div class="top_bar">
			<header>
				<div class="title" title="' . $path . '">Automatic creation in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($folder_path, $obj) . '</div>
			</header>
		</div>
		<div class="title">Please check the statuses of the table objects created</div>
		<table>
			<tr>
				<th class="path">File Path</th>
				<th class="name">Table Name</th>
				<th class="status">Status</th>
			</tr>'; $t = count($selected_tables); for ($i = 0; $i < $t; $i++) { $data = $statuses[$i]; $main_content .= '
			<tr>
				<td class="path">' . $data[0] . '</td>
				<td class="name">' . $selected_tables[$i] . '</td>
				<td class="status status_' . ($data[2] ? "ok" : "error") . '">' . ($data[2] ? "OK" : "ERROR") . '</td>
			</tr>'; if ($data[2]) { $exists_any_status_ok = true; } } if (empty($selected_tables)) { $main_content .= '<tr><td colspan="3" style="text-align:center;">No elements available</td></tr>'; } $main_content .= '
		</table>
		<div class="desc">If any of the statuses is equal to <span class="status_error">ERROR</span>, please try again for the correspondent table...</div>
	</div>'; if ($exists_any_status_ok) $main_content .= '<script>if (window.parent.refreshAndShowLastNodeChilds) window.parent.refreshAndShowLastNodeChilds();</script>'; } else if ($_POST["step_1"]) { $main_content .= '<div class="select_tables">
		<div class="top_bar">
			<header>
				<div class="title" title="' . $path . '">Automatic creation in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($folder_path, $obj) . '</div>
				<ul>
					<li class="continue" data-title="Continue"><a onClick="submitForm(this, checkSelectedTables);"><i class="icon continue"></i> Continue</a></li>
				</ul>
			</header>
		</div>
		<div class="title">Please select the table objects that you wish to create</div>
		<form method="post">
			<input type="hidden" name="db_broker" value="' . $db_broker . '" />
			<input type="hidden" name="db_driver" value="' . $db_driver . '" />
			<input type="hidden" name="type" value="' . $type . '" />'; if (!empty($tables_name)) { $main_content .= '<div class="select_buttons">
			<a onclick="$(\'.select_tables .tables input\').attr(\'checked\', \'checked\')">Select All</a>
			<a onclick="$(\'.select_tables .tables input\').removeAttr(\'checked\')">Deselect All</a>
		</div>
		<div class="tables">'; $t = count($tables_name); for ($i = 0; $i < $t; $i++) { $table_name = $tables_name[$i]; $main_content .= '<div class="table">
						<input type="checkbox" name="st[]" value="' . $table_name . '" />
						<input type="hidden" name="sta[' . $table_name . ']" value="" />
						<label title="Click here to enter a different table alias..." onClick="addTableAlias(this)">' . $table_name . '</label>
					</div>'; } $main_content .= '</div>
			<div class="options">
				<div class="with_maps">
					<input type="checkbox" name="with_maps" value="1" />
					<label>Do you wish to include parameter and result maps in the selected items?</label>
				</div>
				<div class="overwrite">
					<input type="checkbox" name="overwrite" value="1" />
					<label>Do you wish to overwrite the selected items, if they already exists?</label>
				</div>
			</div>
			
			<input type="hidden" name="step_2" value="Continue" />'; } else { if ($type == "diagram") { $main_content .= '<div class="error">There are no tables created in the DB Diagram.<br/>Please go to the DB Layer that you wish, create the correspondent DB Diagram and then execute again this action.</div>'; } else { $main_content .= '<div class="error">We couldn\'t detect any tables in the DB.</div>'; } } $main_content .= '
		</form>
	</div>'; } else { $head .= '<script>
		var db_drivers = ' . json_encode($db_drivers) . ';
	</script>'; $main_content .= '<div class="select_brokers">
		<div class="top_bar">
			<header>
				<div class="title" title="' . $path . '">Automatic creation in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($folder_path, $obj) . '</div>
				<ul>
					<li class="continue" data-title="Continue"><a onClick="submitForm(this);"><i class="icon continue"></i> Continue</a></li>
				</ul>
			</header>
		</div>
		<div class="title">Please select the DB Driver</div>
		<form method="post">'; if (empty($path)) { $main_content .= '<div class="error">You cannot execute this action with an undefined path.</div>'; } else if (empty($obj)) { $main_content .= '<div class="error">Bean name doesn\'t exist. If this problem persists, please talk with the sys-admin.</div>'; } else if (!empty($db_drivers)) { $main_content .= '
			<div class="db_broker' . (count($db_drivers) == 1 ? " single_broker" : "") . '">
				<label>DB Broker:</label>
				<select name="db_broker" onChange="updateDBDrivers(this)">
					<option></option>'; foreach ($db_drivers as $db_broker => $db_driver_names) { $main_content .= '<option ' . ($selected_db_broker == $db_broker ? 'selected' : '') . '>' . $db_broker . '</option>'; } $main_content .= '
				</select>
			</div>
			<div class="db_driver" ' . ($selected_db_broker ? '' : 'style="display:none"') . '>
				<label>DB Driver:</label>
				<select name="db_driver">'; $selected_db_drivers = $db_drivers[$selected_db_broker]; if ($selected_db_drivers) foreach ($selected_db_drivers as $db_driver_name => $db_driver_props) $main_content .= '<option value="' . $db_driver_name . '" ' . ($selected_db_driver == $db_driver_name ? 'selected' : '') . '>' . $db_driver_name . ($db_driver_props ? '' : ' (Rest)') . '</option>'; $main_content .= '	
				</select>
			</div>
			<div class="type">
				<label>Type:</label>
				<select name="type">
					<option value="db">From DB Server</option>
					<option value="diagram">From DB Diagram</option>
				</select>
			</div>
			
			<input type="hidden" name="step_1" value="Continue" />'; } else { $main_content .= '<div class="error">There are no DB brokers.<br/>Apparently you have Data Access Layers without any DB brokers, which means your application is not correctly configured.<br/>Please go to "Layers Management" Menu and configure correclty your DB brokers.</div>'; } $main_content .= '
		</form>
	</div>'; } ?>
