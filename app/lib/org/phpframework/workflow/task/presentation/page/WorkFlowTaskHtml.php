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
?><div class="page_task_html">
	<ul>
		<li class="settings_tab"><a href="#page_task_html_settings">Settings</a></li>
		<li class="authentication_tab"><a href="#page_task_html_authentication">Authentication</a></li>
		<li class="advanced_settings_tab"><a href="#page_task_html_advanced_settings">Advanced Settings</a></li>
	</ul>
	
	<div class="settings" id="page_task_html_settings">
		<div class="file_name">
			<label>File Name:</label>
			<input class="task_property_field" type="text" name="file_name" value="" />
		</div>
		
		<div class="template">
			<label>Template:</label>
			<select class="task_property_field" type="text" name="template"></select>
		</div>
		
		<div class="join_type">
			<label>Chldren joined by:</label>
			<select class="task_property_field" type="text" name="join_type">
				<option value="tabs">Tabs</option>
				<option value="list">One after Another</option>
			</select>
		</div>
		
		<?php include dirname(dirname($file_path)) . "/common/LinksHtml.php"; ?>
	</div>
	
	<div class="authentication" id="page_task_html_authentication">
		<div class="authentication_type">
			<label>Authentication Type:</label>
			<select class="task_property_field" type="text" name="authentication_type" onChange="PageTaskPropertyObj.onChangeUserAuthentication(this)">
				<option value="">All Users can acess</option>
				<option value="authenticated">Only authenticated users</option>
			</select>
		</div>
		
		<div class="authentication_users">
			<table>
				<thead>
					<tr>
						<th class="user_type_id"><label>Users With Access:</label></th>
						<th class="actions">
							<i class="icon add" onClick="PageTaskPropertyObj.addAuthenticationUser(this)"></i>
						</th>
					</tr>
				</thead>
				<tbody index_prefix="authentication_users">
					<tr class="no_users"><td colspan="2">There are no configured users...</td></tr>
				</tbody>
			</table>
		</div>
		
		<div class="users_management_admin_panel">
			<a href="javascript:void(0)" onClick="PresentationTaskUtil.openUsersManagementAdminPanelPopup(this)">Users Management Admin Panel</a>
			
			<div class="users_management_admin_panel_popup myfancypopup with_iframe_title">
				<iframe></iframe>
			</div>
		</div>
	</div>
	
	<div class="advanced_settings" id="page_task_html_advanced_settings">
		<a onclick="PageTaskPropertyObj.updateSettingsFromPageFile(this);" title="Update page settings bellow from file"><span class="icon refresh"></span>Update page settings bellow from file</a>
		
		<div class="regions_blocks">
			<label>Regions Blocks: <i class="icon add" onClick="PageTaskPropertyObj.addUserPageRegionBlock(this)"></i></label>
			<ul index_prefix="page_settings[regions_blocks]">
				<li class="no_page_regions_blocks">There are no region-blocks...</li>
			</ul>
		</div>
		
		<div class="includes">
			<label>Includes: <i class="icon add" onClick="PageTaskPropertyObj.addPageInclude(this)"></i></label>
			<ul index_prefix="page_settings[includes]">
				<li class="no_page_includes">There are no includes...</li>
			</ul>
		</div>
		
		<div class="template_params">
			<label>Template Params: <i class="icon add" onClick="PageTaskPropertyObj.addUserPageTemplateParam(this)"></i></label>
			<ul index_prefix="page_settings[template_params]">
				<li class="no_page_template_params">There are no template params...</li>
			</ul>
		</div>
		
		<a class="edit_page" onclick="PageTaskPropertyObj.openEditPageAdminPanelPopup(this);" title="Edit page code"><span class="icon update"></span>Edit page code</a>
	</div>
	
	<div class="edit_page_admin_panel_popup myfancypopup">
		<iframe></iframe>
	</div>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
