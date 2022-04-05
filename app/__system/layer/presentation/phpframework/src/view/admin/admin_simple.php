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

include_once $EVC->getUtilPath("AdminMenuUIHandler"); if (!$is_admin_ui_simple_allowed) { echo '<script>
		alert("You don\'t have permission to access this Workspace!");
		document.location="' . $project_url_prefix . 'auth/logout";
	</script>'; die(); } $filter_by_layout_url_query = $filter_by_layout ? "&filter_by_layout=$filter_by_layout&filter_by_layout_permission=$filter_by_layout_permission" : ""; $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_simple.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_simple.js"></script>
'; $main_content = '
<div id="main_menu">
	<ul class="dropdown">
		' . ($layers["presentation_layers"] ? '
			<li class="pages link" onClick="goTo(this, \'url\', event)" url="' . "{$project_url_prefix}phpframework/presentation/list?element_type=entity&bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=$project" . '">
				<label>Pages</label>
			</li>
			<!--li class="templates">
				<a href="javascript:void(0)" onClick="goTo(this, \'url\', event)" url="' . "{$project_url_prefix}phpframework/presentation/list?element_type=template&bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=$project" . '">Templates</a>
				<ul>
					<li><a href="javascript:void(0)" onClick="goTo(this, \'url\', event)" url="' . "{$project_url_prefix}phpframework/presentation/list?element_type=template&bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=$project" . '">List Templates</a></li>
					<li><a href="javascript:void(0)" onClick="goTo(this, \'url\', event)" url="' . "{$project_url_prefix}phpframework/presentation/install_template?bean_name=$bean_name&bean_file_name=$bean_file_name&filter_by_layout=$filter_by_layout&path=$project/src/template/" . '">Install New Template</a></li>
				</ul>
			</li>
			<li class="blocks link" onClick="goTo(this, \'url\', event)" url="' . "{$project_url_prefix}phpframework/presentation/list?element_type=block&bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=$project" . '">
				<label>Blocks</label>
			</li>
			<li class="utils link" onClick="goTo(this, \'url\', event)" url="' . "{$project_url_prefix}phpframework/presentation/list?element_type=util&bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=$project" . '">
				<label>Actions</label>
			</li-->
			<li class="webroot link" onClick="goTo(this, \'url\', event)" url="' . "{$project_url_prefix}phpframework/presentation/list?element_type=webroot&bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=$project" . '">
				<label>Webroot</label>
			</li>
		' : '') . '
		<li class="management">
			<label>Management</label>
			<ul>
				' . ($layers["presentation_layers"] ? '
					<li><a href="javascript:void(0)" onClick="goTo(this, \'url\', event)" url="' . "{$project_url_prefix}phpframework/presentation/install_template?bean_name=$bean_name&bean_file_name=$bean_file_name&filter_by_layout=$filter_by_layout&path=$project/src/template/" . '">Install New Template</a></li>
					<li><a href="javascript:void(0)" onClick="goTo(this, \'url\', event)" url="' . "{$project_url_prefix}phpframework/presentation/list?element_type=template&bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=$project" . '">List Templates</a></li>
					<li><a href="javascript:void(0)" onClick="goTo(this, \'url\', event)" url="' . "{$project_url_prefix}phpframework/presentation/list?element_type=block&bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=$project" . '">List Blocks</a></li>
					<li><a href="javascript:void(0)" onClick="goTo(this, \'url\', event)" url="' . "{$project_url_prefix}phpframework/presentation/list?element_type=util&bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=$project" . '">List Actions</a></li>
					<li class="delimiter"></li>
				' : '') . '
				' . ($is_switch_admin_ui_allowed ? '<li><a href="' . $project_url_prefix . 'admin/admin_uis">Switch Workspace</a></li>' : '') . '
				<li><a href="javascript:void(0)" onClick="chooseAvailableProject(\'' . $project_url_prefix . 'admin/choose_available_project?redirect_path=admin&is_popup=1\')">Switch Project</a></li>
				<li class="delimiter"></li>
				<li><a href="javascript:void(0)" onClick="chooseAvailableTool(\'' . "{$project_url_prefix}admin/choose_available_tool?element_type=util&bean_name=$bean_name&bean_file_name=$bean_file_name&filter_by_layout=$filter_by_layout&path=$project&is_popup=1" . '\')">Other Tools</a></li>
				' . ($is_flush_cache_allowed ? '<li><a href="javascript:void(0)" onClick="flushCacheFromAdmin(\'' . $project_url_prefix . 'admin/flush_cache\');">Flush Cache</a></li>' : '') . '
				<li><a href="' . $project_url_prefix . 'auth/logout">Logout</a></li>
				<li><a href="javascript:void(0)" onClick="goTo(this, \'url\', event)" url="' . $project_url_prefix . 'admin/about">About</a></li>
				'; $main_content .= '
			</ul>
		</li>
		<li class="buttons">
			<span class="project">Selected project: "' . $project . '"</span>
			<span class="icon home" onClick="goTo(this, \'home_url\', event)" home_url="' . "{$project_url_prefix}admin/admin_home" . '" title="Go Home"></span>
			<span class="icon refresh" onClick="refreshIframe()" title="Refresh"></span>
			<span class="icon go_back" onClick="goBack()" title="Go Back"></span>
		</li>
	</ul>
</div>
<div id="content">
	<div class="iframe_overlay">
		<div class="iframe_loading">Loading...</div>
	</div>
	<iframe src="' . "{$project_url_prefix}admin/admin_home" . '"></iframe>
</div>'; if ($default_page) { $main_content .= '<script>$("iframe")[0].src = \'' . $default_page . '\';</script>'; } ?>
