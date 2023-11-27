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

class AdminMenuUIHandler { public static function getHeader($peb014cfd, $v37d269c4fa) { return '
<!-- Add Jquery Tap-Hold Event JS file -->
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerytaphold/taphold.js"></script>

<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $v37d269c4fa . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add ContextMenu main JS and CSS files -->
<link rel="stylesheet" href="' . $v37d269c4fa . 'vendor/jquerymycontextmenu/css/style.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerymycontextmenu/js/jquery.mycontextmenu.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $peb014cfd . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $peb014cfd . 'js/file_manager.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $v37d269c4fa . 'vendor/fontawesome/css/all.min.css">

<!-- Icons CSS file -->
<link rel="stylesheet" href="' . $peb014cfd . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS and JS files -->
<link rel="stylesheet" href="' . $peb014cfd . 'css/layout.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $peb014cfd . 'js/layout.js"></script>

<!-- Add Admin Menu JS and CSS files -->
<link rel="stylesheet" href="' . $peb014cfd . 'css/admin/admin_menu.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $peb014cfd . 'js/admin/admin_menu.js"></script>
'; } public static function getContextMenus($pe847b157) { return '
<div id="selected_menu_properties" class="myfancypopup with_title">
	<div class="title">Properties</div>
	<p class="content"></p>
</div>

<ul id="item_context_menu" class="mycontextmenu">
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return removeItem(this, \'remove_url\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="undefined_file_context_menu" class="mycontextmenu">
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit File</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')" allow_upper_case="1">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\')" allow_upper_case="1">Rename Name</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="zip_file_context_menu" class="mycontextmenu">
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\')">Rename Name</a></li>
	<li class="unzip"><a onClick="return manageFile(this, \'unzip_url\', \'unzip\')">Unzip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="lib_group_context_menu" class="mycontextmenu">
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="main_lib_group_context_menu" class="mycontextmenu">
	<li class="manage_docbook"><a onClick="return goTo(this, \'manage_docbook_url\', event)">Manage Docbook</a></li>
</ul>

<ul id="lib_file_context_menu" class="mycontextmenu">
	<li class="view_docbook"><a onClick="return goTo(this, \'view_docbook_url\', event)">View Docbook</a></li>
	<li class="view_code"><a onClick="return goTo(this, \'view_code_url\', event)">View Code</a></li>
	<li class="line_break"></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="main_dao_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Group</a></li>
	<li class="hbnt_obj"><a onClick="return manageFile(this, \'create_dao_hibernate_model_url\', \'create_file\', triggerFileNodeAfterCreateFile)" allow_upper_case="1">Add Hibernate DAO Object</a></li>
	<li class="objt_obj"><a onClick="return manageFile(this, \'create_dao_obj_type_url\', \'create_file\', triggerFileNodeAfterCreateFile)" allow_upper_case="1">Add ObjectType DAO Object</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="dao_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Sub-Group</a></li>
	<li class="hbnt_obj"><a onClick="return manageFile(this, \'create_dao_hibernate_model_url\', \'create_file\', triggerFileNodeAfterCreateFile)" allow_upper_case="1">Add Hibernate DAO Object</a></li>
	<li class="objt_obj"><a onClick="return manageFile(this, \'create_dao_obj_type_url\', \'create_file\', triggerFileNodeAfterCreateFile)" allow_upper_case="1">Add ObjectType DAO Object</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="dao_file_context_menu" class="mycontextmenu">
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit File</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')" allow_upper_case="1">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\')" allow_upper_case="1">Rename Name</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="main_db_group_context_menu" class="mycontextmenu">
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="db_driver_context_menu" class="mycontextmenu">
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit</a></li>
	<li class="line_break"></li>
	<li class="execute_sql"><a onClick="return goTo(this, \'execute_sql_url\', event)">Execute SQL</a></li>
	<li class="db_dump"><a onClick="return goTo(this, \'db_dump_url\', event)">DB Dump</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="db_driver_tables_context_menu" class="mycontextmenu">
	<li class="add_auto_table"><a onClick="return manageDBTableAction(this, \'add_auto_table_url\', \'add_table\')">Add Table Automatically</a></li>
	<li class="add_manual_table"><a onClick="return goTo(this, \'add_manual_table_url\', event)">Add Table Manually</a></li>
	<li class="line_break"></li>
	<li class="edit_diagram"><a onClick="return goTo(this, \'edit_diagram_url\', event)">Edit Tables Diagram</a></li>
	<li class="line_break"></li>
	<li class="create_diagram_sql"><a onClick="return goTo(this, \'create_diagram_sql_url\', event)">Create Diagram\'s SQL</a></li>
	<li class="execute_sql"><a onClick="return goTo(this, \'execute_sql_url\', event)">Execute SQL</a></li>
	<li class="db_dump"><a onClick="return goTo(this, \'db_dump_url\', event)">DB Dump</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="db_driver_table_context_menu" class="mycontextmenu">
	<li class="add_attribute"><a onClick="return manageDBTableAction(this, \'add_attribute_url\', \'add_attribute\')">Add Attribute</a></li>
	<li class="line_break"></li>
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit</a></li>
	<li class="line_break"></li>
	<li class="manage_records"><a onClick="return goTo(this, \'manage_records_url\', event)">Manage Records</a></li>
	<li class="line_break"></li>
	<li class="execute_sql"><a onClick="return goTo(this, \'execute_sql_url\', event)">Execute SQL</a></li>
	<li class="import_data"><a onClick="return goTo(this, \'import_data_url\', event)">Import Data</a></li>
	<li class="export_data"><a onClick="return goTo(this, \'export_data_url\', event)">Export Data</a></li>
	<li class="db_dump"><a onClick="return goTo(this, \'db_dump_url\', event)">DB Dump</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageDBTableAction(this, \'remove_url\', \'remove_table\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageDBTableAction(this, \'rename_url\', \'rename_table\')">Rename</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="db_driver_table_attribute_context_menu" class="mycontextmenu">
	<li class="primary_key"><a onClick="return manageDBTableAction(this, \'set_property_url\', \'set_primary_key\')">Primary Key</a></li>
	<li class="null"><a onClick="return manageDBTableAction(this, \'set_property_url\', \'set_null\')">Null</a></li>
	<li class="type"><a href="javascript:void(0)">
		<select>
			<option disabled>-- Choose Type --</option>
		</select>
		<input placeHolder="length" />
		<span class="hidden" onClick="return manageDBTableAction(this.parentNode, \'set_property_url\', \'set_type\')"></span>
	</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageDBTableAction(this, \'remove_url\', \'remove_attribute\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageDBTableAction(this, \'rename_url\', \'rename_attribute\')">Rename</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="db_diagram_context_menu" class="mycontextmenu">
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit</a></li>
	<li class="line_break"></li>
	<li class="create_sql"><a onClick="return goTo(this, \'create_sql_url\', event)">Create Diagram\'s SQL</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="main_ibatis_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Group</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="ibatis_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\')">Add File</a></li>
	<li class="line_break"></li>
	<li class="create_automatically"><a onClick="return goTo(this, \'create_automatically_url\', event)">Create Queries Automatically</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="ibatis_group_common_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\')">Add File</a></li>
	<li class="line_break"></li>
	<li class="create_automatically"><a onClick="return goTo(this, \'create_automatically_url\', event)">Create Queries Automatically</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="ibatis_file_context_menu" class="mycontextmenu">
	<li class="query"><a onClick="return goTo(this, \'add_query_url\', event)">Add Query</a></li>
	<li class="parameter_map"><a onClick="return goTo(this, \'add_parameter_map_url\', event)">Add Parameter Map</a></li>
	<li class="result_map"><a onClick="return goTo(this, \'add_result_map_url\', event)">Add Result Map</a></li>
	<li class="line_break"></li>
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit File</a></li>
	<li class="line_break"></li>
	<li class="manage_includes"><a onClick="return goTo(this, \'manage_includes_url\', event)">Manage Includes</a></li>
	<li class="line_break"></li>
	<li class="create_automatically"><a onClick="return goTo(this, \'create_automatically_url\', event)">Create Queries Automatically</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\')">Rename Name</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="main_hibernate_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Group</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="hibernate_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\')">Add File</a></li>
	<li class="line_break"></li>
	<li class="obj"><a onClick="return goTo(this, \'add_obj_url\', event)">Add Object Manually</a></li>
	<li class="create_automatically"><a onClick="return goTo(this, \'create_automatically_url\', event)">Add Objects Automatically</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="hibernate_group_common_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\')">Add File</a></li>
	<li class="line_break"></li>
	<li class="obj"><a onClick="return goTo(this, \'add_obj_url\', event)">Add Object Manually</a></li>
	<li class="create_automatically"><a onClick="return goTo(this, \'create_automatically_url\', event)">Add Objects Automatically</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="hibernate_file_context_menu" class="mycontextmenu">
	<li class="obj"><a onClick="return goTo(this, \'add_obj_url\', event)">Add Object</a></li>
	<li class="line_break"></li>
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit File</a></li>
	<li class="line_break"></li>
	<li class="manage_includes"><a onClick="return goTo(this, \'manage_includes_url\', event)">Manage Includes</a></li>
	<li class="line_break"></li>
	<li class="create_automatically"><a onClick="return goTo(this, \'create_automatically_url\', event)">Add Objects Automatically</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\')">Rename Name</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="hibernate_import_context_menu" class="mycontextmenu">
	<li class="query"><a onClick="return goTo(this, \'add_query_url\', event)">Add Query</a></li>
	<li class="relationship"><a onClick="return goTo(this, \'add_relationship_url\', event)">Add Relationship</a></li>
	<li class="parameter_map"><a onClick="return goTo(this, \'add_parameter_map_url\', event)">Add Parameter Map</a></li>
	<li class="result_map"><a onClick="return goTo(this, \'add_result_map_url\', event)">Add Result Map</a></li>
	<li class="line_break"></li>
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit File</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="hibernate_object_context_menu" class="mycontextmenu">
	<li class="query"><a onClick="return goTo(this, \'add_query_url\', event)">Add Query</a></li>
	<li class="relationship"><a onClick="return goTo(this, \'add_relationship_url\', event)">Add Relationship</a></li>
	<li class="parameter_map"><a onClick="return goTo(this, \'add_parameter_map_url\', event)">Add Parameter Map</a></li>
	<li class="result_map"><a onClick="return goTo(this, \'add_result_map_url\', event)">Add Result Map</a></li>
	<li class="line_break"></li>
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return removeItem(this, \'remove_url\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="main_business_logic_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Group</a></li>
	<li class="line_break"></li>
	<li class="create_automatically"><a onClick="return goTo(this, \'create_automatically_url\', event)">Add Services Automatically</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="business_logic_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\')" allow_upper_case="1">Add File</a></li>
	<li class="line_break"></li>
	<!--li class="service_obj"><a onClick="return goTo(this, \'add_service_obj_url\', event)">Add Service Object Manually</a></li-->
	<li class="service_obj"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_service_obj_url\', \'edit_service_obj_url\', \'service_object\', null, event)">Add Service Object Manually</a></li>
	<!--li class="service_function"><a onClick="return goTo(this, \'add_service_func_url\', event)">Add Service Function Manually</a></li-->
	<li class="service_function"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_service_func_url\', \'edit_service_func_url\', \'function\', null, event)">Add Service Function Manually</a></li>
	<li class="create_automatically"><a onClick="return goTo(this, \'create_automatically_url\', event)">Add Services Automatically</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="business_logic_group_common_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\')" allow_upper_case="1">Add File</a></li>
	<li class="line_break"></li>
	<!--li class="service_obj"><a onClick="return goTo(this, \'add_service_obj_url\', event)">Add Service Object Manually</a></li-->
	<li class="service_obj"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_service_obj_url\', \'edit_service_obj_url\', \'service_object\', null, event)">Add Service Object Manually</a></li>
	<!--li class="service_function"><a onClick="return goTo(this, \'add_service_func_url\', event)">Add Service Function Manually</a></li-->
	<li class="service_function"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_service_func_url\', \'edit_service_func_url\', \'function\', null, event)">Add Service Function Manually</a></li>
	<li class="create_automatically"><a onClick="return goTo(this, \'create_automatically_url\', event)">Add Services Automatically</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="business_logic_file_context_menu" class="mycontextmenu">
	<!--li class="service_obj"><a onClick="return goTo(this, \'add_service_obj_url\', event)">Add Service Object Manually</a></li-->
	<li class="service_obj"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_service_obj_url\', \'edit_service_obj_url\', \'service_object\', null, event)">Add Service Object Manually</a></li>
	<!--li class="service_function"><a onClick="return goTo(this, \'add_service_func_url\', event)">Add Service Function Manually</a></li-->
	<li class="service_function"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_service_func_url\', \'edit_service_func_url\', \'function\', null, event)">Add Service Function Manually</a></li>
	<li class="toggle_all_children"><a onClick="return toggleAllChildren(this)">Toggle Private Services</a></li>
	<li class="line_break"></li>
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit File</a></li>
	<li class="line_break"></li>
	<li class="manage_includes"><a onClick="return goTo(this, \'manage_includes_url\', event)">Manage Includes</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')" allow_upper_case="1">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\')" allow_upper_case="1">Rename Name</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="business_logic_object_context_menu" class="mycontextmenu">
	<!--li class="service_method"><a onClick="return goTo(this, \'add_service_method_url\', event)">Add Service Method</a></li-->
	<li class="service_method"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_service_method_url\', \'edit_service_method_url\', \'service_method\', null, event)">Add Service Method</a></li>
	<li class="toggle_all_children"><a onClick="return toggleAllChildren(this)">Toggle Private Methods</a></li>
	<li class="line_break"></li>
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit Visually</a></li>
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit Code</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return removeBusinessLogicObject(this, \'remove_url\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageBusinessLogicObject(this, \'rename_url\', \'rename\')" allow_upper_case="1">Rename File</a></li>
	<!--li class="rename"><a onClick="return manageBusinessLogicObject(this, \'rename_url\', \'rename_name\')" allow_upper_case="1">Rename Name</a></li-->
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="main_presentation_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Sub-Group</a></li>
	<li class="add_project"><a onClick="return goToPopup(this, \'create_project_url\', event, \'with_iframe_title edit_project_details_popup\', refreshLastNodeParentChildsIfNotTreeLayoutAndMainTreeNode)">Add New Project</a></li>
	<li class="line_break"></li>
	<li class="manage_wordpress"><a onClick="return goTo(this, \'manage_wordpress_url\', event)">Manage WordPress</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Zipped Project</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="presentation_project_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', triggerFileNodeAfterCreateFile)">Add File</a></li>
	<li class="add_project"><a onClick="return goToPopup(this, \'create_project_url\', event, \'with_iframe_title edit_project_details_popup\', refreshLastNodeParentChildsIfNotTreeLayoutAndMainTreeNode)">Add New Project</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\', renameProject)">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Zipped Project</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_project_common_context_menu" class="mycontextmenu">
	<li class="edit_config"><a onClick="return goTo(this, \'edit_config_url\', event)">Edit Config</a></li>
	<li class="line_break"></li>
	<li class="manage_wordpress"><a onClick="return goTo(this, \'manage_wordpress_url\', event)">Manage WordPress</a></li>
	<li class="line_break"></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="presentation_project_common_wordpress_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\', managePresentationFile)">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', [managePresentationFile, triggerFileNodeAfterCreateFile])">Add File</a></li>
	<li class="line_break"></li>
	<li class="manage_wordpress"><a onClick="return goTo(this, \'manage_wordpress_url\', event)">Manage WordPress</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\', managePresentationFile)">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\', managePresentationFile)">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_project_context_menu" class="mycontextmenu">
	<li class="edit"><a onClick="return goToPopup(this, \'edit_url\', event, \'with_iframe_title edit_project_details_popup\', onSuccessfullEditProject)">Edit Project Details</a></li>
	<li class="edit_project_global_variables"><a onClick="return goTo(this, \'edit_project_global_variables_url\', event)">Edit Project Global Variables</a></li>
	<li class="edit_config"><a onClick="return goTo(this, \'edit_config_url\', event)">Edit Config</a></li>
	<li class="edit_init"><a onClick="return goTo(this, \'edit_init_url\', event)">Edit Init - Advanced</a></li>
	<li class="line_break"></li>
	<li class="manage_users"><a onClick="return goToPopup(this, \'manage_users_url\', event, \'with_iframe_title\')">Manage Users</a></li>
	<li class="manage_references"><a onClick="return goToPopup(this, \'manage_references_url\', event, \'with_iframe_title\', refreshLastNodeParentChilds)">Manage References</a></li>
	<li class="manage_wordpress"><a onClick="return goTo(this, \'manage_wordpress_url\', event)">Manage WordPress</a></li>
	<li class="line_break"></li>
	<li class="install_program"><a onClick="return goTo(this, \'install_program_url\', event)">Install Program</a></li>
	<li class="line_break"></li>
	<li class="view_project"><a onClick="return openWindow(this, \'view_project_url\', \'project\')">Preview Project</a></li>
	<li class="test_project"><a onClick="return openWindow(this, \'test_project_url\', \'project\')">Test Project</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\', onSuccessfullRemoveProject)">Remove Project</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\', renameProject)">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="presentation_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\', managePresentationFile)">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', [managePresentationFile, triggerFileNodeAfterCreateFile])">Add File</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\', managePresentationFile)">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\', managePresentationFile)">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_evc_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\', managePresentationFile)">Add Folder</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', [managePresentationFile, triggerFileNodeAfterCreateFile])">Add File</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="presentation_main_templates_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\', managePresentationFile)">Add Folder</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', [managePresentationFile, triggerFileNodeAfterCreateFile])">Add Template</a></li>
	<li class="line_break"></li>
	<li class="install_template"><a onClick="return goTo(this, \'install_template_url\', event)">Install New Template</a></li>
	<li class="convert_template"><a onClick="return goTo(this, \'convert_template_url\', event)">Convert Url to Template</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="presentation_main_pages_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\', managePresentationFile)">Add Folder</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', [managePresentationFile, triggerFileNodeAfterCreateFile])">Add Page</a></li>
	' . ($pe847b157 ? '<li class="line_break"></li>
	<li class="create_automatically"><a onClick="return goTo(this, \'create_automatically_url\', event)">Create UIs Automatically</a></li>
	<li class="create_uis_diagram"><a onClick="return goTo(this, \'create_uis_diagram_url\', event)">Folder UIs Diagram</a></li>' : '') . '
	<li class="line_break"></li>
	<li class="view_project"><a onClick="return openWindow(this, \'view_project_url\', \'project\')">Preview Project</a></li>
	<li class="test_project"><a onClick="return openWindow(this, \'test_project_url\', \'project\')">Test Project</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="presentation_pages_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\', managePresentationFile)">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', [managePresentationFile, triggerFileNodeAfterCreateFile])">Add Page</a></li>
	' . ($pe847b157 ? '<li class="line_break"></li>
	<li class="create_automatically"><a onClick="return goTo(this, \'create_automatically_url\', event)">Create UIs Automatically</a></li>
	<li class="create_uis_diagram"><a onClick="return goTo(this, \'create_uis_diagram_url\', event)">Folder UIs Diagram</a></li>' : '') . '
	<li class="line_break"></li>
	<li class="view_project"><a onClick="return openWindow(this, \'view_project_url\', \'project\')">Preview Folder</a></li>
	<li class="test_project"><a onClick="return openWindow(this, \'test_project_url\', \'project\')">Test Folder</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\', managePresentationFile)">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\', managePresentationFile)">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_main_utils_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\', managePresentationFile)">Add Folder</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', [managePresentationFile, triggerFileNodeAfterCreateFile])" allow_upper_case="1">Add File</a></li>
	<li class="line_break"></li>
	<!--li class="class_obj"><a onClick="return goTo(this, \'add_class_obj_url\', event)">Add Class</a></li-->
	<li class="class_obj"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_class_obj_url\', \'edit_class_obj_url\', \'class_object\', null, event)">Add Class</a></li>
	<!--li class="class_function"><a onClick="return goTo(this, \'add_class_func_url\', event)">Add Function</a></li-->
	<li class="class_function"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_class_func_url\', \'edit_class_func_url\', \'function\', null, event)">Add Function</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="presentation_utils_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\', managePresentationFile)">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', [managePresentationFile, triggerFileNodeAfterCreateFile])" allow_upper_case="1">Add File</a></li>
	<li class="line_break"></li>
	<!--li class="class_obj"><a onClick="return goTo(this, \'add_class_obj_url\', event)">Add Class</a></li-->
	<li class="class_obj"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_class_obj_url\', \'edit_class_obj_url\', \'class_object\', null, event)">Add Class</a></li>
	<!--li class="class_function"><a onClick="return goTo(this, \'add_class_func_url\', event)">Add Function</a></li-->
	<li class="class_function"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_class_func_url\', \'edit_class_func_url\', \'function\', null, event)">Add Function</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\', managePresentationFile)">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\', managePresentationFile)">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_file_context_menu" class="mycontextmenu">
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\', managePresentationFile)">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\', managePresentationFile)">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\', managePresentationFile)">Rename Name</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_page_file_context_menu" class="mycontextmenu">
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit Visually</a></li>
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit Code</a></li>
	<li class="line_break"></li>
	<li class="view_project"><a onClick="return openWindow(this, \'view_project_url\', \'project\')">Preview Page</a></li>
	<li class="test_project"><a onClick="return openWindow(this, \'test_project_url\', \'project\')">Test Page</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\', managePresentationFile)">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\', managePresentationFile)">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\', managePresentationFile)">Rename Name</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_template_file_context_menu" class="mycontextmenu">
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit Visually</a></li>
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit Code</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\', managePresentationFile)">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\', managePresentationFile)">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\', managePresentationFile)">Rename Name</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_block_file_context_menu" class="mycontextmenu">
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit Visually</a></li>
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit Code</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\', managePresentationFile)">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\', managePresentationFile)">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\', managePresentationFile)">Rename Name</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_util_file_context_menu" class="mycontextmenu">
	<!--li class="class_obj"><a onClick="return goTo(this, \'add_class_obj_url\', event)">Add Class</a></li-->
	<li class="class_obj"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_class_obj_url\', \'edit_class_obj_url\', \'class_object\', null, event)">Add Class</a></li>
	<!--li class="class_function"><a onClick="return goTo(this, \'add_class_func_url\', event)">Add Function</a></li-->
	<li class="class_function"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_class_func_url\', \'edit_class_func_url\', \'function\', null, event)">Add Function</a></li>
	<li class="line_break"></li>
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit</a></li>
	<li class="line_break"></li>
	<li class="manage_includes"><a onClick="return goTo(this, \'manage_includes_url\', event)">Manage Includes</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\', managePresentationFile)">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\', managePresentationFile)" allow_upper_case="1">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\', managePresentationFile)" allow_upper_case="1">Rename Name</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_util_object_context_menu" class="mycontextmenu">
	<!--li class="class_method"><a onClick="return goTo(this, \'add_class_method_url\', event)">Add Class Method</a></li-->
	<li class="class_method"><a onClick="return createClassObjectOrMethodOrFunction(this, \'save_class_method_url\', \'edit_class_method_url\', \'class_method\', null, event)" static="1">Add Class Method</a></li>
	<li class="toggle_all_children"><a onClick="return toggleAllChildren(this)">Toggle Private Methods</a></li>
	<li class="line_break"></li>
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit Visually</a></li>
	<!--li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit Code</a></li-->
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_cache_file_context_menu" class="mycontextmenu">
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit</a></li>
	<li class="line_break"></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="presentation_cache_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', triggerFileNodeAfterCreateFile)">Add File</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="cms_module_context_menu" class="mycontextmenu">
	<li class="manage_modules"><a onClick="return goTo(this, \'manage_modules_url\', event)">Manage Modules</a></li>
	<li class="line_break"></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="main_vendor_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', triggerFileNodeAfterCreateFile)">Add File</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="vendor_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Sub-Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', triggerFileNodeAfterCreateFile)">Add File</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="vendor_file_context_menu" class="mycontextmenu">
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit File</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\')">Rename Name</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="main_test_unit_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Group</a></li>
	<li class="test_unit_obj"><a onClick="return manageFile(this, \'create_test_unit_obj_url\', \'create_file\', triggerFileNodeAfterCreateFile)">Add Test-Unit</a></li>
	<li class="line_break"></li>
	<li class="manage_test_units"><a onClick="return goTo(this, \'manage_test_units_url\', event)">Manage Test-Units</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>

<ul id="test_unit_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Sub-Group</a></li>
	<li class="test_unit_obj"><a onClick="return manageFile(this, \'create_test_unit_obj_url\', \'create_file\', triggerFileNodeAfterCreateFile)">Add Test-Unit</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')">Rename</a></li>
	<li class="zip"><a onClick="return manageFile(this, \'zip_url\', \'zip\')">Zip</a></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="test_unit_obj_context_menu" class="mycontextmenu">
	<li class="edit"><a onClick="return goTo(this, \'edit_url\', event)">Edit Visually</a></li>
	<li class="edit_raw_file"><a onClick="return goTo(this, \'edit_raw_file_url\', event)">Edit Code</a></li>
	<li class="line_break"></li>
	<li class="cut"><a onClick="return cutFile(this)">Cut</a></li>
	<li class="copy"><a onClick="return copyFile(this)">Copy</a></li>
	<li class="line_break"></li>
	<li class="remove"><a onClick="return manageFile(this, \'remove_url\', \'remove\')">Remove</a></li>
	<li class="line_break"></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename\')" allow_upper_case="1">Rename File</a></li>
	<li class="rename"><a onClick="return manageFile(this, \'rename_url\', \'rename_name\')" allow_upper_case="1">Rename Name</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download File</a></li>
	<li class="diff_file"><a onClick="return goTo(this, \'diff_file_url\', event)">Diff File</a></li>
	<li class="line_break"></li>
	<li class="properties"><a onClick="return showProperties(this)">Properties</a></li>
</ul>

<ul id="main_other_group_context_menu" class="mycontextmenu">
	<li class="create_folder"><a onClick="return manageFile(this, \'create_url\', \'create_folder\')">Add Group</a></li>
	<li class="create_file"><a onClick="return manageFile(this, \'create_url\', \'create_file\', triggerFileNodeAfterCreateFile)">Add File</a></li>
	<li class="line_break"></li>
	<li class="paste"><a onClick="return manageFile(this, \'paste_url\', \'paste\')">Paste</a></li>
	<li class="line_break"></li>
	<li class="upload"><a onClick="return goTo(this, \'upload_url\', event)">Upload Files</a></li>
	<li class="download"><a onClick="return goToNew(this, \'download_url\', event)">Download Folder</a></li>
	<li class="line_break"></li>
	<li class="refresh"><a onClick="return refresh(this)">Refresh</a></li>
</ul>'; } public static function getInlineIconsByContextMenus() { return array( "item_context_menu" => array("edit", "remove"), "undefined_file_context_menu" => array("edit_raw_file", "remove"), "zip_file_context_menu" => array("unzip", "remove"), "main_dao_group_context_menu" => array("create_folder"), "dao_group_context_menu" => array("create_folder", "remove"), "dao_file_context_menu" => array("edit_raw_file", "remove"), "main_db_group_context_menu" => array(), "db_driver_context_menu" => array("edit"), "db_driver_tables_context_menu" => array("add_table", "edit_diagram"), "db_driver_table_context_menu" => array("edit", "manage_records"), "db_diagram_context_menu" => array("edit"), "main_ibatis_group_context_menu" => array("create_folder"), "ibatis_group_context_menu" => array("create_folder", "create_file", "remove"), "ibatis_group_common_context_menu" => array("create_folder", "create_file", "remove"), "ibatis_file_context_menu" => array("edit_raw_file", "remove"), "main_hibernate_group_context_menu" => array("create_folder"), "hibernate_group_context_menu" => array("create_folder", "create_file", "remove"), "hibernate_group_common_context_menu" => array("create_folder", "create_file", "remove"), "hibernate_file_context_menu" => array("edit_raw_file", "remove"), "hibernate_import_context_menu" => array("edit_raw_file", "remove"), "hibernate_object_context_menu" => array("edit", "remove"), "main_business_logic_group_context_menu" => array("create_folder"), "business_logic_group_context_menu" => array("create_folder", "create_file", "remove"), "business_logic_group_common_context_menu" => array("create_folder", "create_file", "remove"), "business_logic_file_context_menu" => array("edit_raw_file", "remove"), "business_logic_object_context_menu" => array("edit", "service_method", "remove"), "main_presentation_group_context_menu" => array("create_folder"), "presentation_project_group_context_menu" => array("create_folder", "create_file", "remove"), "presentation_project_common_context_menu" => array(), "presentation_project_common_wordpress_group_context_menu" => array("create_folder", "create_file", "remove"), "presentation_project_context_menu" => array("remove"), "presentation_group_context_menu" => array("create_folder", "create_file", "remove"), "presentation_evc_group_context_menu" => array("create_folder", "create_file", "paste"), "presentation_main_templates_group_context_menu" => array("create_folder", "create_file", "install_template", "convert_template", "paste"), "presentation_main_pages_group_context_menu" => array("create_folder", "create_file", "create_automatically", "create_uis_diagram", "view_project", "paste"), "presentation_pages_group_context_menu" => array("view_project", "create_folder", "create_file", "remove"), "presentation_file_context_menu" => array("edit", "remove"), "presentation_page_file_context_menu" => array("view_project", "edit", "remove"), "presentation_block_file_context_menu" => array("edit", "remove"), "presentation_main_utils_group_context_menu" => array("create_folder", "create_file", "class_obj", "class_function", "paste"), "presentation_utils_group_context_menu" => array("create_folder", "create_file", "class_obj", "class_function", "paste"), "presentation_util_file_context_menu" => array("edit_raw_file", "remove"), "presentation_util_object_context_menu" => array("edit", "remove"), "presentation_cache_file_context_menu" => array("edit_raw_file"), "presentation_cache_group_context_menu" => array("create_folder", "create_file"), "cms_module_context_menu" => array(), "main_vendor_group_context_menu" => array("create_folder", "create_file"), "vendor_group_context_menu" => array("create_folder", "create_file", "remove"), "vendor_file_context_menu" => array("edit_raw_file", "remove"), "main_test_unit_group_context_menu" => array("create_folder", "test_unit_obj"), "test_unit_group_context_menu" => array("create_folder", "test_unit_obj", "remove"), "test_unit_obj_context_menu" => array("edit", "remove"), "main_other_group_context_menu" => array("create_folder", "create_file"), ); } public static function getLayersGroup($pfd248cca, $pca34cb23, &$pdbc0498b, $peb014cfd, $pb154d332 = false, $v75b595c772 = false) { $pf8ed4912 = ""; if (!empty($pca34cb23)) { $pf8ed4912 .= '
			<li class="' . strtolower($pfd248cca) . ' jstree-open" data-jstree=\'{"icon":"main_node main_node_' . strtolower($pfd248cca) . '"}\'>
				<label>' . self::getLayerLabel($pfd248cca) . '</label>
				<ul>'; foreach ($pca34cb23 as $v0a5deb92d8 => $v4a24304713) $pf8ed4912 .= self::getLayer($v0a5deb92d8, $v4a24304713, $pdbc0498b, $peb014cfd, $pb154d332, $v75b595c772); $pf8ed4912 .= '
				</ul>
			</li>'; } return $pf8ed4912; } public static function getLayer($v0a5deb92d8, $v4a24304713, &$pdbc0498b, $peb014cfd, $pb154d332 = false, $v75b595c772 = false, $pc66a0204 = false) { $pf8ed4912 = ""; if ($v0a5deb92d8 != "properties") { self::updateMainLayersProperties($v0a5deb92d8, $v4a24304713, $pdbc0498b, $peb014cfd, $pb154d332, $v75b595c772, $pc66a0204); $pef349725 = isset($pdbc0498b[$v0a5deb92d8]) ? $pdbc0498b[$v0a5deb92d8] : null; $pfb472c50 = isset($pef349725["path"]) ? $pef349725["path"] : null; $v8773b3a63a = isset($pef349725["item_type"]) ? $pef349725["item_type"] : null; $pfd248cca = strtolower($v8773b3a63a); $v3ae55a9a2e = ($v8773b3a63a == "db_driver" ? "" : "main_node_") . $pfd248cca; $pf8ed4912 .= self::getNode($v0a5deb92d8, $v4a24304713, $pef349725, $pfb472c50, $v3ae55a9a2e); } return $pf8ed4912; } public static function updateMainLayersProperties($v0a5deb92d8, $v4a24304713, &$pdbc0498b, $peb014cfd, $pb154d332 = false, $v75b595c772 = false, $pc66a0204 = false) { if ($v0a5deb92d8 != "properties" && $v0a5deb92d8 != "aliases") { $pef349725 = isset($v4a24304713["properties"]) ? $v4a24304713["properties"] : null; $v8773b3a63a = isset($pef349725["item_type"]) ? $pef349725["item_type"] : null; $v8ffce2a791 = isset($pef349725["bean_name"]) ? $pef349725["bean_name"] : null; $pa0462a8e = isset($pef349725["bean_file_name"]) ? $pef349725["bean_file_name"] : null; $v5e4d5e7cf8 = isset($pef349725["automatic_ui"]) ? $pef349725["automatic_ui"] : null; $v8ffce2a791 = $v8773b3a63a == "dao" ? "dao" : ($v8773b3a63a == "vendor" ? "vendor" : ($v8773b3a63a == "other" ? "other" : ($v8773b3a63a == "lib" ? "lib" : $v8ffce2a791))); $v75b595c772 = $v75b595c772 ? $v75b595c772 : "belong"; $v0de500a75d = $pb154d332 ? "&filter_by_layout=$pb154d332&filter_by_layout_permission=$v75b595c772" : ""; $pea5fed5d = $pb154d332 ? "&filter_by_layout=$pb154d332" : ""; $pea5fed5d .= $pc66a0204 ? "&selected_db_driver=$pc66a0204" : ""; $v6de8723b40 = array(); $v6de8723b40["folder"]["get_sub_files_url"] = $peb014cfd . "admin/get_sub_files?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$v0de500a75d&path=#path#&item_type=$v8773b3a63a"; $v6de8723b40["folder"]["attributes"]["rename_url"] = $peb014cfd . "admin/manage_file?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$v0de500a75d&path=#path#&action=#action#&item_type=$v8773b3a63a&extra=#extra#"; $v6de8723b40["folder"]["attributes"]["remove_url"] = $peb014cfd . "admin/manage_file?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$v0de500a75d&path=#path#&action=#action#&item_type=$v8773b3a63a"; $v6de8723b40["folder"]["attributes"]["create_url"] = $v6de8723b40["folder"]["attributes"]["rename_url"]; $v6de8723b40["folder"]["attributes"]["upload_url"] = $peb014cfd . "admin/upload_file?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$v0de500a75d&path=#path#&item_type=$v8773b3a63a"; $v6de8723b40["folder"]["attributes"]["download_url"] = $peb014cfd . "admin/download_file?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#&item_type=$v8773b3a63a"; $v6de8723b40["folder"]["attributes"]["zip_url"] = $v6de8723b40["folder"]["attributes"]["rename_url"]; $v6de8723b40["folder"]["attributes"]["copy_url"] = "[$v8ffce2a791,$pa0462a8e,#path#,$v8773b3a63a]"; $v6de8723b40["folder"]["attributes"]["cut_url"] = $v6de8723b40["folder"]["attributes"]["copy_url"]; $v6de8723b40["folder"]["attributes"]["paste_url"] = $v6de8723b40["folder"]["attributes"]["rename_url"]; $v6de8723b40["file"]["attributes"]["onClick"] = 'return goTo(this, \'edit_raw_file_url\', event)'; $v6de8723b40["file"]["attributes"]["rename_url"] = $v6de8723b40["folder"]["attributes"]["rename_url"]; $v6de8723b40["file"]["attributes"]["remove_url"] = $v6de8723b40["folder"]["attributes"]["remove_url"]; $v6de8723b40["file"]["attributes"]["create_url"] = $v6de8723b40["folder"]["attributes"]["create_url"]; $v6de8723b40["file"]["attributes"]["edit_raw_file_url"] = $peb014cfd . "phpframework/admin/edit_raw_file?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&item_type=$v8773b3a63a&path=#path#"; $v6de8723b40["file"]["attributes"]["copy_url"] = $v6de8723b40["folder"]["attributes"]["copy_url"]; $v6de8723b40["file"]["attributes"]["cut_url"] = $v6de8723b40["file"]["attributes"]["copy_url"]; $v6de8723b40["file"]["attributes"]["download_url"] = $v6de8723b40["folder"]["attributes"]["download_url"]; $v6de8723b40["file"]["attributes"]["zip_url"] = $v6de8723b40["folder"]["attributes"]["zip_url"]; $v6de8723b40["file"]["attributes"]["diff_file_url"] = $peb014cfd . "diff/?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=$v8773b3a63a"; $v6de8723b40["undefined_file"] = $v6de8723b40["file"]; $v6de8723b40["zip_file"] = $v6de8723b40["file"]; $v6de8723b40["zip_file"]["attributes"]["unzip_url"] = $v6de8723b40["file"]["attributes"]["rename_url"]; $v6de8723b40["import"]["attributes"]["onClick"] = 'return goTo(this, \'edit_raw_file_url\', event)'; $v6de8723b40["import"]["attributes"]["rename_url"] = $v6de8723b40["file"]["attributes"]["rename_url"]; $v6de8723b40["import"]["attributes"]["remove_url"] = $v6de8723b40["file"]["attributes"]["remove_url"]; $v6de8723b40["import"]["attributes"]["edit_raw_file_url"] = $v6de8723b40["file"]["attributes"]["edit_raw_file_url"]; $v6de8723b40["import"]["attributes"]["copy_url"] = $v6de8723b40["file"]["attributes"]["copy_url"]; $v6de8723b40["import"]["attributes"]["cut_url"] = $v6de8723b40["import"]["attributes"]["copy_url"]; $v6de8723b40["import"]["attributes"]["download_url"] = $v6de8723b40["folder"]["attributes"]["download_url"]; $v6de8723b40["import"]["attributes"]["zip_url"] = $v6de8723b40["folder"]["attributes"]["zip_url"]; $v6de8723b40[$v8773b3a63a]["attributes"]["create_url"] = $v6de8723b40["folder"]["attributes"]["create_url"]; $v6de8723b40[$v8773b3a63a]["attributes"]["upload_url"] = $v6de8723b40["folder"]["attributes"]["upload_url"]; $v6de8723b40[$v8773b3a63a]["attributes"]["paste_url"] = $v6de8723b40["folder"]["attributes"]["paste_url"]; $v6de8723b40[$v8773b3a63a]["attributes"]["download_url"] = $v6de8723b40["folder"]["attributes"]["download_url"]; $v6de8723b40[$v8773b3a63a]["attributes"]["zip_url"] = $v6de8723b40["folder"]["attributes"]["zip_url"]; $v6de8723b40[$v8773b3a63a]["get_sub_files_url"] = $v6de8723b40["folder"]["get_sub_files_url"]; $v6de8723b40["cms_module"] = $v6de8723b40["folder"]; $v6de8723b40["cms_module"]["get_sub_files_url"] .= "&folder_type=module"; $v6de8723b40["cms_module"]["attributes"]["manage_modules_url"] = $peb014cfd . "phpframework/admin/manage_modules?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e"; $v6de8723b40["reserved_file"]["attributes"]["onClick"] = 'return goTo(this, \'view_url\', event)'; $v6de8723b40["reserved_file"]["attributes"]["view_url"] = $peb014cfd . "phpframework/admin/view_file?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&item_type=$v8773b3a63a&path=#path#"; $v6de8723b40["reserved_file"]["attributes"]["download_url"] = $v6de8723b40["folder"]["attributes"]["download_url"]; $v6de8723b40["reserved_file"]["attributes"]["zip_url"] = $v6de8723b40["folder"]["attributes"]["zip_url"]; $v6de8723b40["reserved_file"]["attributes"]["diff_file_url"] = $v6de8723b40["file"]["attributes"]["diff_file_url"]; if ($v8773b3a63a == "db" || $v8773b3a63a == "db_driver") { $pc38bce14 = isset($v4a24304713["properties"]["layer_bean_folder_name"]) ? $v4a24304713["properties"]["layer_bean_folder_name"] : null; $v6de8723b40["table"]["get_sub_files_url"] = $peb014cfd . "db/get_db_data?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&table=#table#"; $v6de8723b40["table"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["table"]["attributes"]["edit_url"] = $peb014cfd . "db/edit_table?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&table=#table#"; $v6de8723b40["table"]["attributes"]["rename_url"] = $peb014cfd . "db/manage_table_action?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=#action#&table=#table#&extra=#extra#"; $v6de8723b40["table"]["attributes"]["remove_url"] = $peb014cfd . "db/manage_table_action?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=#action#&table=#table#"; $v6de8723b40["table"]["attributes"]["add_attribute_url"] = $peb014cfd . "db/manage_table_action?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=#action#&table=#table#&extra=#extra#"; $v6de8723b40["table"]["attributes"]["add_fk_attribute_url"] = $peb014cfd . "db/manage_table_action?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=#action#&table=#table#&fk_table=#fk_table#&fk_attribute=#fk_attribute#&previous_attribute=#previous_attribute#&next_attribute=#next_attribute#&attribute_index=#attribute_index#"; $v6de8723b40["table"]["attributes"]["import_data_url"] = $peb014cfd . "db/import_table_data?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&table=#table#"; $v6de8723b40["table"]["attributes"]["export_data_url"] = $peb014cfd . "db/export_table_data?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&table=#table#"; $v6de8723b40["table"]["attributes"]["db_dump_url"] = $peb014cfd . "db/db_dump?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&table=#table#"; $v6de8723b40["table"]["attributes"]["execute_sql_url"] = $peb014cfd . "db/execute_sql?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&table=#table#"; $v6de8723b40["table"]["attributes"]["manage_records_url"] = $peb014cfd . "db/manage_records?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&table=#table#"; $v6de8723b40["table"]["attributes"]["bean_name"] = "#bean_name#"; $v6de8723b40["table"]["attributes"]["bean_file_name"] = "#bean_file_name#"; $v6de8723b40["table"]["attributes"]["table_name"] = "#table#"; $v6de8723b40["attribute"]["attributes"]["onClick"] = 'return manageDBTableAction(this, \'rename_url\', \'rename_attribute\')'; $v6de8723b40["attribute"]["attributes"]["remove_url"] = $peb014cfd . "db/manage_table_action?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=#action#&table=#table#&attribute=#attribute#"; $v6de8723b40["attribute"]["attributes"]["rename_url"] = $peb014cfd . "db/manage_table_action?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=#action#&table=#table#&attribute=#attribute#&extra=#extra#"; $v6de8723b40["attribute"]["attributes"]["sort_url"] = $peb014cfd . "db/manage_table_action?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=#action#&table=#table#&attribute=#attribute#&previous_attribute=#previous_attribute#&next_attribute=#next_attribute#&attribute_index=#attribute_index#"; $v6de8723b40["attribute"]["attributes"]["set_property_url"] = $peb014cfd . "db/manage_table_action?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=#action#&table=#table#&attribute=#attribute#&properties=#properties#"; $v6de8723b40["attribute"]["attributes"]["execute_sql_url"] = $peb014cfd . "db/execute_sql?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#"; $v6de8723b40["attribute"]["attributes"]["table_name"] = "#table#"; $v6de8723b40["attribute"]["attributes"]["attribute_name"] = "#attribute#"; $v6de8723b40["db_management"]["get_sub_files_url"] = $peb014cfd . "db/get_db_data?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#"; ; $v6de8723b40["db_management"]["attributes"] = array( "edit_url" => $peb014cfd . "db/set_db_settings?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#", "add_auto_table_url" => $peb014cfd . "db/manage_table_action?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=#action#&extra=#extra#", "add_manual_table_url" => $peb014cfd . "db/edit_table?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#", "db_dump_url" => $peb014cfd . "db/db_dump?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#", "execute_sql_url" => $peb014cfd . "db/execute_sql?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#", "edit_diagram_url" => $peb014cfd . "db/diagram?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#", "create_diagram_sql_url" => $peb014cfd . "db/create_diagram_sql?layer_bean_folder_name=$pc38bce14&bean_name=#bean_name#&bean_file_name=#bean_file_name#", ); $v6de8723b40["db_management"]["attributes"]["onClick"] = 'return goTo(this, \'edit_diagram_url\', event)'; $v6de8723b40["db_driver"] = $v6de8723b40["db_management"]; $v6de8723b40["db_driver"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; } else if ($v8773b3a63a == "lib") { $v6de8723b40[$v8773b3a63a]["attributes"]["manage_docbook_url"] = $peb014cfd . "docbook/"; $v6de8723b40["file"]["attributes"]["onClick"] = 'return goTo(this, \'view_docbook_url\', event)'; $v6de8723b40["file"]["attributes"]["view_docbook_url"] = $peb014cfd . "docbook/file_docbook?path=lib/#path#"; $v6de8723b40["file"]["attributes"]["view_code_url"] = $peb014cfd . "docbook/file_code?path=lib/#path#"; } else if ($v8773b3a63a == "vendor") { $v6de8723b40["dao"] = array(); $v6de8723b40["dao"]["get_sub_files_url"] = $peb014cfd . "admin/get_sub_files?bean_name=dao&bean_file_name=&path=#path#&item_type=dao"; $v6de8723b40["dao"]["attributes"]["rename_url"] = $peb014cfd . "admin/manage_file?bean_name=dao&bean_file_name=&path=#path#&action=#action#&item_type=dao&extra=#extra#"; $v6de8723b40["dao"]["attributes"]["remove_url"] = $peb014cfd . "admin/manage_file?bean_name=dao&bean_file_name=&path=#path#&action=#action#&item_type=dao"; $v6de8723b40["dao"]["attributes"]["create_url"] = $v6de8723b40["dao"]["attributes"]["rename_url"]; $v6de8723b40["dao"]["attributes"]["upload_url"] = $peb014cfd . "admin/upload_file?bean_name=dao&bean_file_name=&path=#path#&item_type=dao"; $v6de8723b40["dao"]["attributes"]["download_url"] = $peb014cfd . "admin/download_file?bean_name=dao&bean_file_name=&path=#path#&item_type=dao"; $v6de8723b40["dao"]["attributes"]["zip_url"] = $v6de8723b40["dao"]["attributes"]["rename_url"]; $v6de8723b40["dao"]["attributes"]["copy_url"] = "[dao,,#path#,dao]"; $v6de8723b40["dao"]["attributes"]["cut_url"] = $v6de8723b40["dao"]["attributes"]["copy_url"]; $v6de8723b40["dao"]["attributes"]["paste_url"] = $v6de8723b40["dao"]["attributes"]["rename_url"]; $v6de8723b40["dao"]["attributes"]["create_dao_hibernate_model_url"] = $peb014cfd . "phpframework/dao/create_file?type=hibernatemodel&path=#path#&file_name=#extra#"; $v6de8723b40["dao"]["attributes"]["create_dao_obj_type_url"] = $peb014cfd . "phpframework/dao/create_file?type=objtype&path=#path#&file_name=#extra#"; $v6de8723b40["code_workflow_editor"] = $v6de8723b40["folder"]; $v6de8723b40["code_workflow_editor_task"] = $v6de8723b40["folder"]; $v6de8723b40["layout_ui_editor"] = $v6de8723b40["folder"]; $v6de8723b40["layout_ui_editor_widget"] = $v6de8723b40["folder"]; $v6de8723b40["test_unit"] = array(); $v6de8723b40["test_unit"]["get_sub_files_url"] = $peb014cfd . "admin/get_sub_files?bean_name=test_unit&bean_file_name=&path=#path#&item_type=test_unit"; $v6de8723b40["test_unit"]["attributes"]["rename_url"] = $peb014cfd . "admin/manage_file?bean_name=test_unit&bean_file_name=&path=#path#&action=#action#&item_type=test_unit&extra=#extra#"; $v6de8723b40["test_unit"]["attributes"]["remove_url"] = $peb014cfd . "admin/manage_file?bean_name=test_unit&bean_file_name=&path=#path#&action=#action#&item_type=test_unit"; $v6de8723b40["test_unit"]["attributes"]["create_url"] = $v6de8723b40["test_unit"]["attributes"]["rename_url"]; $v6de8723b40["test_unit"]["attributes"]["upload_url"] = $peb014cfd . "admin/upload_file?bean_name=test_unit&bean_file_name=&path=#path#&item_type=test_unit"; $v6de8723b40["test_unit"]["attributes"]["download_url"] = $peb014cfd . "admin/download_file?bean_name=test_unit&bean_file_name=&path=#path#&item_type=test_unit"; $v6de8723b40["test_unit"]["attributes"]["zip_url"] = $v6de8723b40["test_unit"]["attributes"]["rename_url"]; $v6de8723b40["test_unit"]["attributes"]["copy_url"] = "[test_unit,,#path#,test_unit]"; $v6de8723b40["test_unit"]["attributes"]["cut_url"] = $v6de8723b40["test_unit"]["attributes"]["copy_url"]; $v6de8723b40["test_unit"]["attributes"]["paste_url"] = $v6de8723b40["test_unit"]["attributes"]["rename_url"]; $v6de8723b40["test_unit"]["attributes"]["create_test_unit_obj_url"] = $peb014cfd . "phpframework/testunit/create_test?path=#path#&file_name=#extra#"; $v6de8723b40["test_unit"]["attributes"]["manage_test_units_url"] = $peb014cfd . "phpframework/testunit/"; } else if ($v8773b3a63a == "dao") { $v6de8723b40["folder"]["attributes"]["create_dao_hibernate_model_url"] = $peb014cfd . "phpframework/dao/create_file?type=hibernatemodel&path=#path#&file_name=#extra#"; $v6de8723b40["folder"]["attributes"]["create_dao_obj_type_url"] = $peb014cfd . "phpframework/dao/create_file?type=objtype&path=#path#&file_name=#extra#"; $v6de8723b40[$v8773b3a63a]["attributes"]["create_dao_hibernate_model_url"] = $v6de8723b40["folder"]["attributes"]["create_dao_hibernate_model_url"]; $v6de8723b40[$v8773b3a63a]["attributes"]["create_dao_obj_type_url"] = $v6de8723b40["folder"]["attributes"]["create_dao_obj_type_url"]; $v6de8723b40["objtype"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["hibernatemodel"]["attributes"] = $v6de8723b40["file"]["attributes"]; } else if ($v8773b3a63a == "test_unit") { $v6de8723b40["folder"]["attributes"]["create_test_unit_obj_url"] = $peb014cfd . "phpframework/testunit/create_test?path=#path#&file_name=#extra#"; $v6de8723b40[$v8773b3a63a]["attributes"]["create_test_unit_obj_url"] = $v6de8723b40["folder"]["attributes"]["create_test_unit_obj_url"]; $v6de8723b40[$v8773b3a63a]["attributes"]["manage_test_units_url"] = $peb014cfd . "phpframework/testunit/"; $v6de8723b40["test_unit_obj"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["test_unit_obj"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["test_unit_obj"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/testunit/edit_test?path=#path#"; } else if ($v8773b3a63a == "ibatis" || $v8773b3a63a == "hibernate") { $v6de8723b40[$v8773b3a63a]["attributes"]["create_automatically_url"] = $peb014cfd . "phpframework/dataaccess/create_data_access_objs_automatically?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#"; $v6de8723b40["file"]["attributes"]["create_automatically_url"] = $v6de8723b40[$v8773b3a63a]["attributes"]["create_automatically_url"]; $v6de8723b40["file"]["attributes"]["edit_raw_file_url"] = $peb014cfd . "phpframework/dataaccess/edit_file?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&item_type=$v8773b3a63a&path=#path#"; $v6de8723b40["file"]["attributes"]["add_obj_url"] = $peb014cfd . "phpframework/dataaccess/edit_hbn_obj?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#"; $v6de8723b40["file"]["attributes"]["add_query_url"] = $peb014cfd . "phpframework/dataaccess/edit_query?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#"; $v6de8723b40["file"]["attributes"]["add_result_map_url"] = $peb014cfd . "phpframework/dataaccess/edit_map?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#&query_type=result_map"; $v6de8723b40["file"]["attributes"]["add_parameter_map_url"] = $peb014cfd . "phpframework/dataaccess/edit_map?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#&query_type=parameter_map"; $v6de8723b40["file"]["attributes"]["manage_includes_url"] = $peb014cfd . "phpframework/dataaccess/edit_includes?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#"; $v6de8723b40["folder"]["attributes"]["create_automatically_url"] = $v6de8723b40[$v8773b3a63a]["attributes"]["create_automatically_url"]; $v6de8723b40["folder"]["attributes"]["add_obj_url"] = $v6de8723b40["file"]["attributes"]["add_obj_url"]; $v6de8723b40["obj"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["obj"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/dataaccess/edit_hbn_obj?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#&obj=#node_id#"; $v6de8723b40["obj"]["attributes"]["remove_url"] = $peb014cfd . "phpframework/dataaccess/remove_hbn_obj?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#&item_type=$v8773b3a63a&obj=#node_id#"; $v6de8723b40["obj"]["attributes"]["add_query_url"] = $v6de8723b40["file"]["attributes"]["add_query_url"] . "&obj=#hbn_obj_id#"; $v6de8723b40["obj"]["attributes"]["add_relationship_url"] = $peb014cfd . "phpframework/dataaccess/edit_relationship?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#&obj=#hbn_obj_id#"; $v6de8723b40["obj"]["attributes"]["add_result_map_url"] = $v6de8723b40["file"]["attributes"]["add_result_map_url"] . "&obj=#hbn_obj_id#"; $v6de8723b40["obj"]["attributes"]["add_parameter_map_url"] = $v6de8723b40["file"]["attributes"]["add_parameter_map_url"] . "&obj=#hbn_obj_id#"; $v6de8723b40["obj"]["attributes"]["diff_file_url"] = $v6de8723b40["file"]["attributes"]["diff_file_url"]; $v6de8723b40["import"]["attributes"]["add_query_url"] = $v6de8723b40["file"]["attributes"]["add_query_url"] . "&relationship_type=import"; $v6de8723b40["import"]["attributes"]["add_relationship_url"] = $peb014cfd . "phpframework/dataaccess/edit_relationship?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#&relationship_type=import"; $v6de8723b40["import"]["attributes"]["add_result_map_url"] = $v6de8723b40["file"]["attributes"]["add_result_map_url"] . "&relationship_type=import"; $v6de8723b40["import"]["attributes"]["add_parameter_map_url"] = $v6de8723b40["file"]["attributes"]["add_parameter_map_url"] . "&relationship_type=import"; $v6de8723b40["import"]["attributes"]["diff_file_url"] = $v6de8723b40["file"]["attributes"]["diff_file_url"]; $v6de8723b40["query"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["query"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/dataaccess/edit_query?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#&obj=#hbn_obj_id#&query_id=#node_id#&query_type=#query_type#&relationship_type=#relationship_type#"; $v6de8723b40["query"]["attributes"]["remove_url"] = $peb014cfd . "phpframework/dataaccess/remove_query?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&item_type=$v8773b3a63a&path=#path#&obj=#hbn_obj_id#&query_id=#node_id#&query_type=#query_type#&relationship_type=#relationship_type#"; $v6de8723b40["relationship"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["relationship"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/dataaccess/edit_relationship?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#&obj=#hbn_obj_id#&query_id=#node_id#&query_type=#query_type#&relationship_type=#relationship_type#"; $v6de8723b40["relationship"]["attributes"]["remove_url"] = $peb014cfd . "phpframework/dataaccess/remove_relationship?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&item_type=$v8773b3a63a&path=#path#&obj=#hbn_obj_id#&query_id=#node_id#&query_type=#query_type#&relationship_type=#relationship_type#"; $v6de8723b40["map"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["map"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/dataaccess/edit_map?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#&obj=#hbn_obj_id#&map=#node_id#&query_type=#query_type#&relationship_type=#relationship_type#"; $v6de8723b40["map"]["attributes"]["remove_url"] = $peb014cfd . "phpframework/dataaccess/remove_map?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&item_type=$v8773b3a63a&path=#path#&obj=#hbn_obj_id#&map=#node_id#&query_type=#query_type#&relationship_type=#relationship_type#"; } else if ($v8773b3a63a == "businesslogic") { $v6de8723b40[$v8773b3a63a]["attributes"]["create_automatically_url"] = $v5e4d5e7cf8 ? $peb014cfd . "phpframework/businesslogic/create_business_logic_objs_automatically?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#" : ""; $v6de8723b40["folder"]["attributes"]["add_service_obj_url"] = $peb014cfd . "phpframework/businesslogic/edit_service?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["folder"]["attributes"]["save_service_obj_url"] = $peb014cfd . "phpframework/businesslogic/save_service?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["folder"]["attributes"]["edit_service_obj_url"] = $peb014cfd . "phpframework/businesslogic/edit_service?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path##extra#.php&service=#extra#"; $v6de8723b40["folder"]["attributes"]["add_service_func_url"] = $peb014cfd . "phpframework/businesslogic/edit_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["folder"]["attributes"]["save_service_func_url"] = $peb014cfd . "phpframework/businesslogic/save_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["folder"]["attributes"]["edit_service_func_url"] = $peb014cfd . "phpframework/businesslogic/edit_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#functions.php&function=#extra#"; $v6de8723b40["folder"]["attributes"]["create_automatically_url"] = $v6de8723b40[$v8773b3a63a]["attributes"]["create_automatically_url"]; $v6de8723b40["file"]["attributes"]["edit_raw_file_url"] = $peb014cfd . "phpframework/businesslogic/edit_file?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&item_type=$v8773b3a63a&path=#path#"; $v6de8723b40["file"]["attributes"]["add_service_obj_url"] = $v6de8723b40["folder"]["attributes"]["add_service_obj_url"]; $v6de8723b40["file"]["attributes"]["save_service_obj_url"] = $v6de8723b40["folder"]["attributes"]["save_service_obj_url"]; $v6de8723b40["file"]["attributes"]["edit_service_obj_url"] = $peb014cfd . "phpframework/businesslogic/edit_service?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&service=#extra#"; $v6de8723b40["file"]["attributes"]["add_service_func_url"] = $v6de8723b40["folder"]["attributes"]["add_service_func_url"]; $v6de8723b40["file"]["attributes"]["save_service_func_url"] = $v6de8723b40["folder"]["attributes"]["save_service_func_url"]; $v6de8723b40["file"]["attributes"]["edit_service_func_url"] = $peb014cfd . "phpframework/businesslogic/edit_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&function=#extra#"; $v6de8723b40["file"]["attributes"]["manage_includes_url"] = $peb014cfd . "phpframework/businesslogic/edit_includes?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["service"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["service"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/businesslogic/edit_service?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&service=#service#"; $v6de8723b40["service"]["attributes"]["rename_url"] = $v6de8723b40["file"]["attributes"]["rename_url"]; $v6de8723b40["service"]["attributes"]["remove_url"] = $peb014cfd . "phpframework/businesslogic/remove_service?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#&service=#service#"; $v6de8723b40["service"]["attributes"]["add_service_method_url"] = $peb014cfd . "phpframework/businesslogic/edit_method?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&service=#service#"; $v6de8723b40["service"]["attributes"]["save_service_method_url"] = $peb014cfd . "phpframework/businesslogic/save_method?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&class=#service#"; $v6de8723b40["service"]["attributes"]["edit_service_method_url"] = $peb014cfd . "phpframework/businesslogic/edit_method?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&service=#service#&method=#extra#"; $v6de8723b40["service"]["attributes"]["edit_raw_file_url"] = $v6de8723b40["file"]["attributes"]["edit_raw_file_url"]; $v6de8723b40["service"]["attributes"]["copy_url"] = $v6de8723b40["file"]["attributes"]["copy_url"]; $v6de8723b40["service"]["attributes"]["cut_url"] = $v6de8723b40["service"]["attributes"]["copy_url"]; $v6de8723b40["service"]["attributes"]["diff_file_url"] = $v6de8723b40["file"]["attributes"]["diff_file_url"]; $v6de8723b40["method"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["method"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/businesslogic/edit_method?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&service=#service#&method=#method#"; $v6de8723b40["method"]["attributes"]["remove_url"] = $peb014cfd . "phpframework/businesslogic/remove_method?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#&service=#service#&method=#method#"; $v6de8723b40["function"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["function"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/businesslogic/edit_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&function=#method#"; $v6de8723b40["function"]["attributes"]["remove_url"] = $peb014cfd . "phpframework/businesslogic/remove_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#&function=#method#"; } else if ($v8773b3a63a == "presentation") { $v6de8723b40[$v8773b3a63a]["attributes"]["create_url"] = str_replace("/admin/manage_file?", "/phpframework/presentation/manage_file?", $v6de8723b40["folder"]["attributes"]["create_url"]) . "&folder_type=project_folder"; $v6de8723b40[$v8773b3a63a]["attributes"]["create_project_url"] = $peb014cfd . "phpframework/presentation/edit_project_details?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#&popup=1&on_success_js_func=onSuccessfullPopupAction"; $v6de8723b40[$v8773b3a63a]["attributes"]["manage_wordpress_url"] = $peb014cfd . "phpframework/cms/wordpress/manage?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["cms_folder"] = $v6de8723b40["folder"]; $v6de8723b40["wordpress_folder"] = $v6de8723b40["folder"]; $v6de8723b40["wordpress_folder"]["attributes"]["manage_wordpress_url"] = $v6de8723b40[$v8773b3a63a]["attributes"]["manage_wordpress_url"]; $v6de8723b40["wordpress_installation_folder"] = $v6de8723b40["folder"]; $v6de8723b40["wordpress_installation_folder"]["attributes"]["manage_wordpress_url"] = $v6de8723b40[$v8773b3a63a]["attributes"]["manage_wordpress_url"]; $v6de8723b40["project_folder"] = $v6de8723b40["folder"]; $v6de8723b40["project_folder"]["attributes"]["rename_url"] = str_replace("/admin/manage_file?", "/phpframework/presentation/manage_file?", $v6de8723b40["folder"]["attributes"]["rename_url"]); $v6de8723b40["project_folder"]["attributes"]["remove_url"] = str_replace("/admin/manage_file?", "/phpframework/presentation/manage_file?", $v6de8723b40["folder"]["attributes"]["remove_url"]); $v6de8723b40["project_folder"]["attributes"]["create_url"] = $v6de8723b40[$v8773b3a63a]["attributes"]["create_url"]; $v6de8723b40["project_folder"]["attributes"]["create_project_url"] = $v6de8723b40[$v8773b3a63a]["attributes"]["create_project_url"]; $v6de8723b40["project_folder"]["attributes"]["upload_url"] = str_replace("/admin/manage_file?", "/phpframework/presentation/manage_file?", $v6de8723b40["folder"]["attributes"]["upload_url"]); $v6de8723b40["project_folder"]["attributes"]["copy_url"] = str_replace("/admin/manage_file?", "/phpframework/presentation/manage_file?", $v6de8723b40["folder"]["attributes"]["copy_url"]); $v6de8723b40["project_folder"]["attributes"]["cut_url"] = str_replace("/admin/manage_file?", "/phpframework/presentation/manage_file?", $v6de8723b40["folder"]["attributes"]["cut_url"]); $v6de8723b40["project_folder"]["attributes"]["paste_url"] = str_replace("/admin/manage_file?", "/phpframework/presentation/manage_file?", $v6de8723b40["folder"]["attributes"]["paste_url"]); $v6de8723b40["project_folder"]["get_sub_files_url"] .= "&folder_type=project_folder"; $v6de8723b40["project"]["attributes"]["edit_url"] = $v6de8723b40[$v8773b3a63a]["attributes"]["create_project_url"]; $v6de8723b40["project"]["attributes"]["rename_url"] = str_replace("/admin/manage_file?", "/phpframework/presentation/manage_file?", $v6de8723b40["folder"]["attributes"]["rename_url"]); $v6de8723b40["project"]["attributes"]["remove_url"] = str_replace("/admin/manage_file?", "/phpframework/presentation/manage_file?", $v6de8723b40["folder"]["attributes"]["remove_url"]); $v6de8723b40["project"]["attributes"]["download_url"] = $v6de8723b40["folder"]["attributes"]["download_url"]; $v6de8723b40["project"]["attributes"]["zip_url"] = $v6de8723b40["project"]["attributes"]["rename_url"]; $v6de8723b40["project"]["attributes"]["edit_project_global_variables_url"] = $peb014cfd . "phpframework/presentation/edit_project_global_variables?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#src/config/pre_init_config.php"; $v6de8723b40["project"]["attributes"]["edit_config_url"] = $peb014cfd . "phpframework/presentation/edit_config?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#src/config/config.php"; $v6de8723b40["project"]["attributes"]["edit_init_url"] = $peb014cfd . "phpframework/presentation/edit_init?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&item_type=$v8773b3a63a&path=#path#src/config/init.php"; $v6de8723b40["project"]["attributes"]["manage_users_url"] = $peb014cfd . "phpframework/module/user/admin/index?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&popup=1"; $v6de8723b40["project"]["attributes"]["manage_references_url"] = $peb014cfd . "phpframework/presentation/manage_references?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#&popup=1&on_success_js_func=onSuccessfullPopupAction"; $v6de8723b40["project"]["attributes"]["manage_wordpress_url"] = $v6de8723b40[$v8773b3a63a]["attributes"]["manage_wordpress_url"]; $v6de8723b40["project"]["attributes"]["install_program_url"] = $peb014cfd . "phpframework/admin/install_program?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["project"]["attributes"]["view_project_url"] = $peb014cfd . "phpframework/presentation/view_project?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#"; $v6de8723b40["project"]["attributes"]["test_project_url"] = $peb014cfd . "phpframework/presentation/test_project?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#"; $v6de8723b40["project"]["attributes"]["project_path"] = "#path#"; $v6de8723b40["project"]["get_sub_files_url"] = $v6de8723b40["folder"]["get_sub_files_url"] . "&folder_type=project"; $v6de8723b40["project_common"] = $v6de8723b40["project"]; $v6de8723b40["folder"]["get_sub_files_url"] .= "&folder_type=#folder_type#"; $v6de8723b40["file"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["file"]["attributes"]["edit_url"] = $v6de8723b40["file"]["attributes"]["edit_raw_file_url"] . "&folder_type=#folder_type#"; $v6de8723b40["entity_file"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["entity_file"]["attributes"]["onClick"] = 'return goTo(this, \'click_url\', event)'; $v6de8723b40["entity_file"]["attributes"]["click_url"] = $peb014cfd . "phpframework/presentation/edit_entity?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["entity_file"]["attributes"]["edit_url"] = $v6de8723b40["entity_file"]["attributes"]["click_url"] . "&edit_entity_type=simple&dont_save_cookie=1"; $v6de8723b40["entity_file"]["attributes"]["edit_raw_file_url"] = $v6de8723b40["entity_file"]["attributes"]["edit_url"] . "&edit_entity_type=advanced&dont_save_cookie=1"; $v6de8723b40["entity_file"]["attributes"]["view_project_url"] = $v6de8723b40["project"]["attributes"]["view_project_url"]; $v6de8723b40["entity_file"]["attributes"]["test_project_url"] = $v6de8723b40["project"]["attributes"]["test_project_url"]; $v6de8723b40["view_file"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["view_file"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/presentation/edit_view?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["template_file"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["template_file"]["attributes"]["onClick"] = 'return goTo(this, \'click_url\', event)'; $v6de8723b40["template_file"]["attributes"]["click_url"] = $peb014cfd . "phpframework/presentation/edit_template?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["template_file"]["attributes"]["edit_url"] = $v6de8723b40["template_file"]["attributes"]["click_url"] . "&edit_template_type=simple&dont_save_cookie=1"; $v6de8723b40["template_file"]["attributes"]["edit_raw_file_url"] = $v6de8723b40["template_file"]["attributes"]["edit_url"] . "&edit_template_type=advanced&dont_save_cookie=1"; $v6de8723b40["util_file"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["util_file"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/presentation/edit_util?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["util_file"]["attributes"]["edit_raw_file_url"] = $v6de8723b40["util_file"]["attributes"]["edit_url"]; $v6de8723b40["util_file"]["attributes"]["add_class_obj_url"] = $peb014cfd . "phpframework/admin/edit_file_class?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation"; $v6de8723b40["util_file"]["attributes"]["save_class_obj_url"] = $peb014cfd . "phpframework/admin/save_file_class?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation"; $v6de8723b40["util_file"]["attributes"]["edit_class_obj_url"] = $peb014cfd . "phpframework/admin/edit_file_class?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&class=#extra#&item_type=presentation"; $v6de8723b40["util_file"]["attributes"]["add_class_func_url"] = $peb014cfd . "phpframework/admin/edit_file_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation"; $v6de8723b40["util_file"]["attributes"]["save_class_func_url"] = $peb014cfd . "phpframework/admin/save_file_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation"; $v6de8723b40["util_file"]["attributes"]["edit_class_func_url"] = $peb014cfd . "phpframework/admin/edit_file_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&function=#extra#&item_type=presentation"; $v6de8723b40["util_file"]["attributes"]["manage_includes_url"] = $peb014cfd . "phpframework/admin/edit_file_includes?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&item_type=$v8773b3a63a&path=#path#&item_type=presentation"; $v6de8723b40["config_file"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["config_file"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/presentation/edit_config?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["controller_file"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["css_file"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["js_file"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["img_file"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["block_file"]["attributes"] = $v6de8723b40["file"]["attributes"]; $v6de8723b40["block_file"]["attributes"]["onClick"] = 'return goTo(this, \'click_url\', event)'; $v6de8723b40["block_file"]["attributes"]["click_url"] = $peb014cfd . "phpframework/presentation/edit_block?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["block_file"]["attributes"]["edit_url"] = $v6de8723b40["block_file"]["attributes"]["click_url"] . "&edit_block_type=simple&dont_save_cookie=1"; $v6de8723b40["block_file"]["attributes"]["edit_raw_file_url"] = $v6de8723b40["block_file"]["attributes"]["edit_url"] . "&edit_block_type=advanced&dont_save_cookie=1"; $v6de8723b40["module_file"] = $v6de8723b40["undefined_file"]; $v6de8723b40["module_folder"] = $v6de8723b40["folder"]; $v6de8723b40["module_folder"]["get_sub_files_url"] .= "&folder_type=module"; $v6de8723b40["entities_folder"] = $v6de8723b40["views_folder"] = $v6de8723b40["templates_folder"] = $v6de8723b40["utils_folder"] = $v6de8723b40["configs_folder"] = $v6de8723b40["webroot_folder"] = $v6de8723b40["controllers_folder"] = $v6de8723b40["blocks_folder"] = $v6de8723b40["folder"]; $v6de8723b40["templates_folder"]["attributes"]["install_template_url"] = $peb014cfd . "phpframework/presentation/install_template?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#"; $v6de8723b40["templates_folder"]["attributes"]["convert_template_url"] = $peb014cfd . "phpframework/presentation/convert_url_to_template?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#"; $v6de8723b40["template_folder"] = $v6de8723b40["folder"]; $v6de8723b40["template_folder"]["attributes"]["download_url"] .= "&folder_type=template_folder"; $v6de8723b40["entities_folder"]["attributes"]["create_automatically_url"] = $v5e4d5e7cf8 ? $peb014cfd . "phpframework/presentation/create_presentation_uis_automatically?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#" : ""; $v6de8723b40["entities_folder"]["attributes"]["create_uis_diagram_url"] = $v5e4d5e7cf8 ? $peb014cfd . "phpframework/presentation/create_presentation_uis_diagram?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#" : ""; $v6de8723b40["entities_folder"]["attributes"]["view_project_url"] = $v6de8723b40["project"]["attributes"]["view_project_url"]; $v6de8723b40["entities_folder"]["attributes"]["test_project_url"] = $v6de8723b40["project"]["attributes"]["test_project_url"]; $v6de8723b40["entities_folder"]["attributes"]["project_with_auto_view"] = "0"; $v6de8723b40["folder"]["attributes"]["create_automatically_url"] = $v6de8723b40["entities_folder"]["attributes"]["create_automatically_url"]; $v6de8723b40["folder"]["attributes"]["create_uis_diagram_url"] = $v6de8723b40["entities_folder"]["attributes"]["create_uis_diagram_url"]; $v6de8723b40["folder"]["attributes"]["view_project_url"] = $v6de8723b40["project"]["attributes"]["view_project_url"]; $v6de8723b40["folder"]["attributes"]["test_project_url"] = $v6de8723b40["project"]["attributes"]["test_project_url"]; $v6de8723b40["utils_folder"]["attributes"]["add_class_obj_url"] = $peb014cfd . "phpframework/admin/edit_file_class?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation"; $v6de8723b40["utils_folder"]["attributes"]["save_class_obj_url"] = $peb014cfd . "phpframework/admin/save_file_class?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation"; $v6de8723b40["utils_folder"]["attributes"]["add_class_func_url"] = $peb014cfd . "phpframework/admin/edit_file_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation"; $v6de8723b40["utils_folder"]["attributes"]["save_class_func_url"] = $peb014cfd . "phpframework/admin/save_file_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation"; $v6de8723b40["folder"]["attributes"]["add_class_obj_url"] = $v6de8723b40["utils_folder"]["attributes"]["add_class_obj_url"]; $v6de8723b40["folder"]["attributes"]["save_class_obj_url"] = $v6de8723b40["utils_folder"]["attributes"]["save_class_obj_url"]; $v6de8723b40["folder"]["attributes"]["add_class_func_url"] = $v6de8723b40["utils_folder"]["attributes"]["add_class_func_url"]; $v6de8723b40["folder"]["attributes"]["save_class_func_url"] = $v6de8723b40["utils_folder"]["attributes"]["save_class_func_url"]; $v6de8723b40["cache_file"]["attributes"]["onClick"] = 'return goTo(this, \'edit_raw_file_url\', event)'; $v6de8723b40["cache_file"]["attributes"]["edit_raw_file_url"] = $v6de8723b40["file"]["attributes"]["edit_raw_file_url"] . "&create_dependencies=1"; $v6de8723b40["cache_file"]["attributes"]["download_url"] = $v6de8723b40["file"]["attributes"]["download_url"]; $v6de8723b40["cache_file"]["attributes"]["zip_url"] = $v6de8723b40["file"]["attributes"]["zip_url"]; $v6de8723b40["cache_file"]["attributes"]["diff_file_url"] = $v6de8723b40["file"]["attributes"]["diff_file_url"]; $v6de8723b40["caches_folder"]["get_sub_files_url"] = $v6de8723b40["folder"]["get_sub_files_url"]; $v6de8723b40["caches_folder"]["attributes"]["create_url"] = $v6de8723b40["folder"]["attributes"]["create_url"]; $v6de8723b40["caches_folder"]["attributes"]["upload_url"] = $v6de8723b40["folder"]["attributes"]["upload_url"]; $v6de8723b40["caches_folder"]["attributes"]["download_url"] = $v6de8723b40["folder"]["attributes"]["download_url"]; $v6de8723b40["caches_folder"]["attributes"]["paste_url"] = $v6de8723b40["folder"]["attributes"]["paste_url"]; $v6de8723b40["class"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["class"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/admin/edit_file_class?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation&class=#class#"; $v6de8723b40["class"]["attributes"]["edit_raw_file_url"] = $v6de8723b40["util_file"]["attributes"]["edit_raw_file_url"]; $v6de8723b40["class"]["attributes"]["remove_url"] = $peb014cfd . "phpframework/admin/remove_file_class?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#&item_type=presentation&class=#class#"; $v6de8723b40["class"]["attributes"]["add_class_method_url"] = $peb014cfd . "phpframework/admin/edit_file_class_method?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation&class=#class#&static=1"; $v6de8723b40["class"]["attributes"]["save_class_method_url"] = $peb014cfd . "phpframework/admin/save_file_class_method?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation&class=#class#"; $v6de8723b40["class"]["attributes"]["edit_class_method_url"] = $peb014cfd . "phpframework/admin/edit_file_class_method?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation&class=#class#&method=#extra#"; $v6de8723b40["class"]["attributes"]["diff_file_url"] = $v6de8723b40["file"]["attributes"]["diff_file_url"]; $v6de8723b40["method"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["method"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/admin/edit_file_class_method?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation&class=#class#&method=#method#"; $v6de8723b40["method"]["attributes"]["remove_url"] = $peb014cfd . "phpframework/admin/remove_file_class_method?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#&item_type=presentation&class=#class#&method=#method#"; $v6de8723b40["function"]["attributes"]["onClick"] = 'return goTo(this, \'edit_url\', event)'; $v6de8723b40["function"]["attributes"]["edit_url"] = $peb014cfd . "phpframework/admin/edit_file_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=#path#&item_type=presentation&function=#method#"; $v6de8723b40["function"]["attributes"]["remove_url"] = $peb014cfd . "phpframework/admin/remove_file_function?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=#path#&item_type=presentation&function=#method#"; } $v6de8723b40["cms_common"] = $v6de8723b40["folder"]; $v6de8723b40["cms_program"] = $v6de8723b40["folder"]; $v6de8723b40["cms_resource"] = $v6de8723b40["folder"]; $pef349725["ui"] = $v6de8723b40; $v591b4fae09 = $v0a5deb92d8; $pdbc0498b[$v591b4fae09] = $pef349725; if ($v8773b3a63a == "db") { if (is_array($v4a24304713)) foreach ($v4a24304713 as $pff56eb51 => $v6694236c2c) $pdbc0498b[$pff56eb51] = $pef349725; } else if ($v8773b3a63a == "vendor") { $pfa26c375 = array( "properties" => array( "item_type" => "dao", "bean_name" => "dao", ), ); self::updateMainLayersProperties("dao", $pfa26c375, $pdbc0498b, $peb014cfd); $pe92fe86e = array( "properties" => array( "item_type" => "test_unit", "bean_name" => "test_unit", ), ); self::updateMainLayersProperties("test_unit", $pe92fe86e, $pdbc0498b, $peb014cfd); } } } public static function getLayerLabel($v5e813b295b, $pef349725 = false) { if (!empty($pef349725["item_label"])) $v5e813b295b = $pef349725["item_label"]; else if (!isset($pef349725["item_type"])) $v5e813b295b = ucfirst(strtolower(str_replace("_", " ", $v5e813b295b))); $pb8c0935b = isset($pef349725["item_title"]) ? $pef349725["item_title"] : null; return '<label' . ($pb8c0935b ? ' title="' . str_replace('"', "&quot;", $pb8c0935b) . '"' : '') . '>' . $v5e813b295b . '</label>'; } public static function getSubNodes($v50d32a6fc4, $pd64c62e3 = false, $pc7de54d5 = false) { $pf8ed4912 = ''; if (is_array($v50d32a6fc4)) foreach ($v50d32a6fc4 as $pff56eb51 => $v6694236c2c) if ($pff56eb51 != "properties" && $pff56eb51 != "aliases") $pf8ed4912 .= self::getNode($pff56eb51, $v6694236c2c, $pd64c62e3, $pc7de54d5); return $pf8ed4912; } public static function getNode($pff56eb51, $v6694236c2c, $pd64c62e3 = false, $pc7de54d5 = false, $v3ae55a9a2e = false) { $pf8ed4912 = ""; $pff56eb51 = trim($pff56eb51); $pef349725 = isset($v6694236c2c["properties"]) ? $v6694236c2c["properties"] : null; $v8773b3a63a = isset($pef349725["item_type"]) ? $pef349725["item_type"] : null; $v8f40ad5766 = isset($pef349725["item_id"]) ? $pef349725["item_id"] : null; $v12cbe3df36 = isset($pef349725["item_menu"]) ? $pef349725["item_menu"] : null; $pf3dc0762 = isset($pef349725["path"]) ? $pef349725["path"] : null; if (empty($pf3dc0762) && ($v8773b3a63a == "folder" || $v8773b3a63a == "file" || $v8773b3a63a == "import")) $pf3dc0762 .= $pc7de54d5 . $pff56eb51 . ($v8773b3a63a == "folder" ? "/" : ""); $pf8ed4912 .= '<li ' . (!empty($v3ae55a9a2e) ? 'class="' . $v3ae55a9a2e . '"' : '') . ' data-jstree=\'{"icon":"' . (!empty($v3ae55a9a2e) ? $v3ae55a9a2e : self::getIcon($pef349725)) . '"}\'><a'; if (!empty($v8f40ad5766) && !empty($v12cbe3df36)) $pf8ed4912 .= ' properties_id="' . $v8f40ad5766 . '"'; if (!empty($pd64c62e3["ui"][$v8773b3a63a]["attributes"])) { foreach ($pd64c62e3["ui"][$v8773b3a63a]["attributes"] as $v5e45ec9bb9 => $pd6321fb0) { $pd6321fb0 = str_replace("#path#", $pf3dc0762, $pd6321fb0); if ($v8773b3a63a == "db_driver" || $v8773b3a63a == "db_management" || $v8773b3a63a == "db_diagram") { $v8ffce2a791 = !empty($pef349725["bean_name"]) ? $pef349725["bean_name"] : ""; $pa0462a8e = !empty($pef349725["bean_file_name"]) ? $pef349725["bean_file_name"] : ""; $pd6321fb0 = str_replace("#bean_name#", $v8ffce2a791, $pd6321fb0); $pd6321fb0 = str_replace("#bean_file_name#", $pa0462a8e, $pd6321fb0); } else if ($v8773b3a63a == "obj" || $v8773b3a63a == "query" || $v8773b3a63a == "relationship" || $v8773b3a63a == "hbn_native" || $v8773b3a63a == "map" || $v8773b3a63a == "import") { $pa9694aaa = !empty($pef349725["hbn_obj_id"]) ? $pef349725["hbn_obj_id"] : ""; $pa9694aaa = $v8773b3a63a == "query" || $v8773b3a63a == "relationship" || $v8773b3a63a == "hbn_native" || $v8773b3a63a == "map" || $v8773b3a63a == "import" ? $pa9694aaa : $pff56eb51; $pc221318a = !empty($pef349725["query_type"]) ? $pef349725["query_type"] : ""; $v05e3431fe2 = !empty($pef349725["relationship_type"]) ? $pef349725["relationship_type"] : ""; $pd6321fb0 = str_replace("#hbn_obj_id#", $pa9694aaa, $pd6321fb0); $pd6321fb0 = str_replace("#query_type#", $pc221318a, $pd6321fb0); $pd6321fb0 = str_replace("#relationship_type#", $v05e3431fe2, $pd6321fb0); $pd6321fb0 = str_replace("#node_id#", $pff56eb51, $pd6321fb0); } else if ($v8773b3a63a == "service" || $v8773b3a63a == "class" || $v8773b3a63a == "method" || $v8773b3a63a == "function") { $v20b8676a9f = $v8773b3a63a == "method" || !empty($pef349725["service"]) ? $pef349725["service"] : $pff56eb51; $v20b8676a9f = $v8773b3a63a == "method" || $v20b8676a9f != "" ? $v20b8676a9f : $pff56eb51; $pfef14f0b = !empty($pef349725["class"]) ? $pef349725["class"] : ""; $pfef14f0b = $v8773b3a63a == "method" || $pfef14f0b != "" ? $pfef14f0b : $pff56eb51; $pd6321fb0 = str_replace("#service#", $v20b8676a9f, $pd6321fb0); $pd6321fb0 = str_replace("#class#", $pff56eb51, $pd6321fb0); $pd6321fb0 = str_replace("#method#", $pff56eb51, $pd6321fb0); } else if ($v8773b3a63a == "table" || $v8773b3a63a == "attribute") { $v8ffce2a791 = !empty($pef349725["bean_name"]) ? $pef349725["bean_name"] : ""; $pa0462a8e = !empty($pef349725["bean_file_name"]) ? $pef349725["bean_file_name"] : ""; $v5e813b295b = !empty($pef349725["name"]) ? $pef349725["name"] : ""; $pd6321fb0 = str_replace("#bean_name#", $v8ffce2a791, $pd6321fb0); $pd6321fb0 = str_replace("#bean_file_name#", $pa0462a8e, $pd6321fb0); $pd6321fb0 = str_replace("#name#", $v5e813b295b, $pd6321fb0); $pd6321fb0 = str_replace("#table#", $v5e813b295b, $pd6321fb0); } else if ($v8773b3a63a == "entities_folder" && $v5e45ec9bb9 == "project_with_auto_view") $pd6321fb0 = !empty($pef349725["project_with_auto_view"]) ? $pef349725["project_with_auto_view"] : "0"; $pf8ed4912 .= " $v5e45ec9bb9=\"$pd6321fb0\""; } } $v6f3a2700dd = false; if (isset($pd64c62e3["ui"][$v8773b3a63a]["get_sub_files_url"])) { $v6f3a2700dd = $pd64c62e3["ui"][$v8773b3a63a]["get_sub_files_url"]; $v6f3a2700dd = str_replace("#path#", $pf3dc0762, $v6f3a2700dd); $v6f3a2700dd = str_replace("#folder_type#", $pef349725["folder_type"], $v6f3a2700dd); if ($v8773b3a63a == "db_driver" || $v8773b3a63a == "db_management" || $v8773b3a63a == "db_diagram" || $v8773b3a63a == "table") { $v8ffce2a791 = !empty($pef349725["bean_name"]) ? $pef349725["bean_name"] : ""; $pa0462a8e = !empty($pef349725["bean_file_name"]) ? $pef349725["bean_file_name"] : ""; $v6f3a2700dd = str_replace("#bean_name#", $v8ffce2a791, $v6f3a2700dd); $v6f3a2700dd = str_replace("#bean_file_name#", $pa0462a8e, $v6f3a2700dd); $v6f3a2700dd = str_replace("#table#", $pff56eb51, $v6f3a2700dd); } } $pf8ed4912 .= '>' . self::getLayerLabel($pff56eb51, $pef349725) . "</a>\n"; $v29680e12e6 = self::getSubNodes($v6694236c2c, $pd64c62e3, $pf3dc0762); $pf8ed4912 .= '<ul ' . (!empty($v6f3a2700dd) ? 'url="' . $v6f3a2700dd . '"' : '') . '>' . $v29680e12e6 . '</ul>' . "\n"; $pf8ed4912 .= '</li>' . self::getMenu($pef349725) . "\n"; return $pf8ed4912; } public static function getIcon($pef349725) { return !empty($pef349725["item_type"]) ? strtolower($pef349725["item_type"]) : ""; } public static function getMenu($pef349725) { if (!empty($pef349725["item_id"]) && !empty($pef349725["item_menu"])) return '<script>
				menu_item_properties.' . $pef349725["item_id"] . ' = ' . json_encode($pef349725["item_menu"]) . ';
			</script>'; } } ?>
