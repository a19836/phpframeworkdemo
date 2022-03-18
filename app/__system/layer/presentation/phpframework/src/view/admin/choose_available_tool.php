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

include_once $EVC->getUtilPath("AdminMenuUIHandler"); $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/choose_available_tool.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/choose_available_tool.js"></script>

<script>
var is_popup = ' . ($is_popup ? 1 : 0) . ';
</script>'; $main_content = '<div class="choose_available_tool">
	<div class="title">Tools</div>
	<ul>
		' . ($is_switch_admin_ui_allowed ? '<li class="switch_admin_ui" onClick="return goTo(\'' . $project_url_prefix . 'admin/admin_uis\', event, 1)">
			<label>Switch Workspace</label>
			<div class="photo"></div>
			<div class="description">Switch to other Workspace more fitted to your technical skills.</div>
		</li>' : '') . '
		<li class="switch_project" onClick="return goTo(\'' . $project_url_prefix . 'admin/choose_available_project?redirect_path=admin\', event, 1)">
			<label>Switch Project</label>
			<div class="photo"></div>
			<div class="description">Switch to another project...</div>
		</li>
		<li class="delimiter"></li>
		
		' . ($is_manage_projects_allowed ? '<li class="manage_projects" onClick="return goTo(\'' . $project_url_prefix . 'phpframework/presentation/manage_projects\', event)">
			<label>Manage Projects</label>
			<div class="photo"></div>
			<div class="description">Create, edit and remove Projects. Set default project...</div>
		</li>' : '') . '
		' . ($is_manage_layers_allowed ? '<li class="manage_layers" onClick="return goTo(\'' . $project_url_prefix . 'setup?step=3.1&iframe=1&hide_setup=1\', event)">
			<label>Manage Layers</label>
			<div class="photo"></div>
			<div class="description">Edit the framework structure by managing its\' layers</div>
		</li>' : '') . '
		' . ($is_manage_modules_allowed ? '<li class="manage_modules" onClick="return goTo(\'' . $project_url_prefix . 'phpframework/admin/manage_modules\', event)">
			<label>Manage Modules</label>
			<div class="photo"></div>
			<div class="description">Install, enable, disable and edit modules...</div>
		</li>' : '') . '
		' . ($is_manage_users_allowed ? '<li class="manage_users" onClick="return goTo(\'' . $project_url_prefix . 'user/manage_users\', event)">
			<label>Manage Users</label>
			<div class="photo"></div>
			<div class="description">Manage the framework\'s users</div>
		</li>' : '') . '
		' . ($is_testunits_allowed ? '<li class="manage_test_units" onClick="return goTo(\'' . $project_url_prefix . 'phpframework/testunit/\', event)">
			<label>Manage Test-Units</label>
			<div class="photo"></div>
			<div class="description">Create and execute your test-units in a batch...</div>
		</li>' : '') . '
		' . ($is_deployment_allowed ? '<li class="manage_deployments" onClick="return goTo(\'' . $project_url_prefix . 'phpframework/deployment/\', event)">
			<label>Manage Deployments</label>
			<div class="photo"></div>
			<div class="description">Deploy your projects to multiple servers with a single click...</div>
		</li>' : '') . '
		' . ($is_program_installation_allowed ? '<li class="install_program" onClick="return goTo(\'' . "{$project_url_prefix}phpframework/admin/install_program?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path" . '\', event)">
			<label>Install a Program</label>
			<div class="photo"></div>
			<div class="description">Install a Program and start using it in a few minutes...</div>
		</li>' : '') . '
		' . ($is_diff_files_allowed ? '<li class="diff_files" onClick="return goTo(\'' . $project_url_prefix . 'phpframework/diff/\', event)">
			<label>Diff Files</label>
			<div class="photo"></div>
			<div class="description">Compare 2 files and check their differences...</div>
		</li>' : '') . '
		<li class="flush_cache" onClick="flushCacheFromAdmin(\'' . $project_url_prefix . 'admin/flush_cache\');">
			<label>Flush Cache</label>
			<div class="photo"></div>
			<div class="description">Delete all saved cache.</div>
		</li>
		<li class="about" onClick="return goTo(\'' . $project_url_prefix . 'phpframework/admin/about\', event)">
			<label>About</label>
			<div class="photo"></div>
			<div class="description">Show framework info.</div>
		</li>
		<li class="delimiter"></li>
		
		<li class="doc_book" onClick="return goTo(\'' . $project_url_prefix . 'docbook/\', event)">
			<label>Doc Book</label>
			<div class="photo"></div>
			<div class="description">Go to our Library Doc-Book</div>
		</li>'; if ($layers) { $filter_by_layout_url_query = $filter_by_layout ? "&filter_by_layout=$filter_by_layout&filter_by_layout_permission=$filter_by_layout_permission" : ""; if ($layers["others"]["other"]) $main_content .= '
		<li class="other_files" onClick="return goTo(\'' . $project_url_prefix . 'phpframework/presentation/list?item_type=other\', event)">
			<label>Other Files</label>
			<div class="photo"></div>
			<div class="description">View, Edit and Upload other files that you may wish to include here...</div>
		</li>'; if (isset($layers["vendors"]["vendor"])) $main_content .= '
			<li class="vendor_files" onClick="return goTo(\'' . $project_url_prefix . 'phpframework/presentation/list?item_type=vendor\', event)">
				<label>Vendor Files</label>
				<div class="photo"></div>
				<div class="description">Extend the framework with the upload of external libraries, new workflow tasks, new ui widgets and much more...</div>
			</li>
			<li class="dao_files" onClick="return goTo(\'' . $project_url_prefix . 'phpframework/presentation/list?item_type=dao\', event)">
				<label>DAO Files</label>
				<div class="photo"></div>
				<div class="description">Create, edit and manage your DAO objects here...</div>
			</li>'; $main_content .= '<li class="delimiter"></li>'; if ($layers["db_layers"]) foreach ($layers["db_layers"] as $layer_name => $layer) { $bn = $layer["properties"]["bean_name"]; $bfn = $layer["properties"]["bean_file_name"]; $main_content .= '
			<li class="db_layers" onClick="return goTo(\'' . "{$project_url_prefix}phpframework/presentation/list?bean_name=$bn&bean_file_name=$bfn$filter_by_layout_url_query" . '\', event)">
				<label>DBs</label>
				<div class="photo"></div>
				<div class="description">Manage Data-Bases.</div>
			</li>'; } if ($layers["data_access_layers"]) foreach ($layers["data_access_layers"] as $layer_name => $layer) { $bn = $layer["properties"]["bean_name"]; $bfn = $layer["properties"]["bean_file_name"]; $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bfn, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bn); $obj_type = $obj->getType(); $label = WorkFlowBeansFileHandler::getLayerObjFolderName($obj); $main_content .= '
			<li class="data_access_layers data_access_layers_' . $obj_type . '" onClick="return goTo(\'' . "{$project_url_prefix}phpframework/presentation/list?bean_name=$bn&bean_file_name=$bfn$filter_by_layout_url_query&selected_db_driver=$selected_db_driver" . '\', event)">
				<label>' . ucwords($label) . ' Data Access</label>
				<div class="photo"></div>
				<div class="description">Manage the ' . $obj_type . ' rules.</div>
			</li>'; } if ($layers["business_logic_layers"]) foreach ($layers["business_logic_layers"] as $layer_name => $layer) { $bn = $layer["properties"]["bean_name"]; $bfn = $layer["properties"]["bean_file_name"]; $label = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $bfn, $bn, $user_global_variables_file_path); $main_content .= '
			<li class="business_logic_layers" onClick="return goTo(\'' . "{$project_url_prefix}phpframework/presentation/list?bean_name=$bn&bean_file_name=$bfn$filter_by_layout_url_query&selected_db_driver=$selected_db_driver" . '\', event)">
				<label>' . ucwords($label) . ' Business Logic</label>
				<div class="photo"></div>
				<div class="description">Manage the business logic services.</div>
			</li>'; } } $main_content .= '
	</ul>
</div>'; ?>
