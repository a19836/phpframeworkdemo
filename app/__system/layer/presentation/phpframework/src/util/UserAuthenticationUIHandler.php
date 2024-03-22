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
class UserAuthenticationUIHandler { public static function getMenu($pdf77ee66, $peb014cfd, $v9431023a8c = null) { return '
		<ul>
			<li class="current_user">Current User: "' . $pdf77ee66->auth["user_data"]["username"] . '"</li>
			<li class="manage_menu_item' . ($v9431023a8c == "user/manage_users" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_users">Manage Users</a></li>
			<!--li' . ($v9431023a8c == "user/edit_user" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_user">Add User</a></li-->
			
			<li class="manage_menu_item' . ($v9431023a8c == "user/manage_user_types" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_user_types">Manage User Types</a></li>
			<!--li' . ($v9431023a8c == "user/edit_user_type" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_user_type">Add User Type</a></li-->
			
			<li class="manage_menu_item' . ($v9431023a8c == "user/manage_object_types" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_object_types">Manage Object Types</a></li>
			<!--li' . ($v9431023a8c == "user/edit_object_type" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_object_type">Add Object Type</a></li-->
			
			<li class="manage_menu_item' . ($v9431023a8c == "user/manage_user_user_types" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_user_user_types">Manage User User Types</a></li>
			<!--li' . ($v9431023a8c == "user/edit_user_user_type" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_user_user_type">Add User User Type</a></li-->
			
			<li class="manage_menu_item' . ($v9431023a8c == "user/manage_permissions" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_permissions">Manage Permissions</a></li>
			<!--li' . ($v9431023a8c == "user/edit_permission" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_permission">Add Permission</a></li-->
			
			<li class="manage_menu_item manage_user_type_permissions' . ($v9431023a8c == "user/manage_user_type_permissions" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_user_type_permissions">Manage User Type Permissions</a></li>
			
			<li class="manage_menu_item manage_layout_types' . ($v9431023a8c == "user/manage_layout_types" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_layout_types">Manage Layout Types</a></li>
			<!--li' . ($v9431023a8c == "user/edit_layout_type" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_layout_type">Add Layout Type</a></li-->
			<li class="manage_menu_item manage_layout_type_permissions' . ($v9431023a8c == "user/manage_layout_type_permissions" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_layout_type_permissions">Manage Layout Type Permissions</a></li>
			
			<li class="manage_menu_item manage_reserved_db_table_names' . ($v9431023a8c == "user/manage_reserved_db_table_names" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_reserved_db_table_names">Manage Reserved DB Table Name</a></li>
			<!--li' . ($v9431023a8c == "user/edit_reserved_db_table_name" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_reserved_db_table_name">Add Reserved DB Table Name</a></li-->
			
			<li class="manage_menu_item manage_login_controls' . ($v9431023a8c == "user/manage_login_controls" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_login_controls">Manage Login Controls</a></li>
			
			' . ($pdf77ee66->isLocalDB() ? '<li class="manage_menu_item change_db_keys' . ($v9431023a8c == "user/change_db_keys" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/change_db_keys">Change DB Keys</a></li>' : '') . '
			
			<li class="manage_menu_item change_auth_settings' . ($v9431023a8c == "user/change_auth_settings" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/change_auth_settings">Change Auth Settings</a></li>
		</ul>'; } } ?>
