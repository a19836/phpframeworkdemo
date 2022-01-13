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

include $EVC->getUtilPath("WorkFlowUIHandler"); $filter_by_layout_url_query = LayoutTypeProjectUIHandler::getFilterByLayoutURLQuery($filter_by_layout); $get_layer_sub_files_url = $project_url_prefix . "admin/get_sub_files?bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=#path#"; $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowQueryHandler = new WorkFlowQueryHandler($WorkFlowUIHandler, $project_url_prefix, $project_common_url_prefix, $db_drivers, $selected_db_broker, $selected_db_driver, $selected_type, $selected_table, $selected_tables_name, $selected_table_attrs, $map_php_types, $map_db_types); $head = $WorkFlowUIHandler->getHeader(); $head .= LayoutTypeProjectUIHandler::getHeader(); $head .= '
<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>

<!-- Edit Code JS file -->
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_code.js"></script>

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/dataaccess/edit_hbn_obj.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/dataaccess/edit_hbn_obj.js"></script>

<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $project_url_prefix . 'css/dataaccess/edit_query.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/dataaccess/edit_query.js"></script>'; $head .= $WorkFlowQueryHandler->getHeader(); $head .= $WorkFlowQueryHandler->getDataAccessJavascript($bean_name, $bean_file_name, $path, $item_type, $hbn_obj_id, $get_layer_sub_files_url); $head .= '<script>
var save_data_access_object_url = \'' . $project_url_prefix . 'phpframework/dataaccess/save_hbn_obj?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '&obj=' . $hbn_obj_id . '\';
var remove_data_access_object_url = \'' . $project_url_prefix . 'phpframework/dataaccess/remove_hbn_obj?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '&obj=#obj_id#\';
var old_obj_id = \'' . $hbn_obj_id . '\';

var new_id_html = \'' . str_replace("'", "\\'", str_replace("\n", "", getIdHTML())) .'\';
var auto_increment_db_attributes_types = ' . json_encode(DB::getAllColumnAutoIncrementTypes()) . ';
</script>'; $main_content = $WorkFlowQueryHandler->getGlobalTaskFlowChar(); $title = $hbn_obj_id ? 'Edit "' . $hbn_obj_id . '" Hibernate Object' : 'Add Hibernate Object'; $main_content .= '<div class="title">' . $title . '</div>
	<div class="sub_title simple_settings" onClick="$(\'.advanced_settings\').show(); $(\'.simple_settings\').hide()">(Click here to see advanced settings)</div>
	<div class="sub_title advanced_settings" onClick="$(\'.advanced_settings\').hide(); $(\'.simple_settings\').show()">(Click here to see simple settings)</div>'; if ($obj_data || !$hbn_obj_id) { $name = WorkFlowDataAccessHandler::getNodeValue($obj_data, "name"); $table = WorkFlowDataAccessHandler::getNodeValue($obj_data, "table"); $extends = WorkFlowDataAccessHandler::getNodeValue($obj_data, "extends"); $main_content .= $WorkFlowQueryHandler->getChooseQueryTableOrAttributeHtml("choose_db_table_or_attribute"); $main_content .= $WorkFlowQueryHandler->getChooseIncludeFromFileManagerHtml($get_layer_sub_files_url, "choose_include_from_file_manager"); $main_content .= $WorkFlowQueryHandler->getChooseDAOObjectFromFileManagerHtml("choose_dao_object_from_file_manager"); $main_content .= $WorkFlowQueryHandler->getChooseAvailableMapIdHtml("choose_map_id"); $main_content .= '
<div class="data_access_obj">
	<div class="create_automatically">
		<span class="icon update" onClick="createHibernateObjectAutomatically(this);" title="Update Automatically">Update Automatically</span>
		<label onClick="createHibernateObjectAutomatically(this);" title="Update Automatically">Update Automatically</label>
	</div>
	<div class="name">
		<label>Name: </label>
		<input type="text" name="name" value="' . $name . '" />
	</div>
	<div class="table">
		<label>Table: </label>
		<input type="text" name="table" value="' . $table . '" />
		<span class="icon search" onClick="getHbnObjTableFromDB(this)" title="Get table from DB">Get from DB</span>
	</div>
	
	<div class="extends">
		<label>Extends: </label>
		<select name="extends">
			<option></option>'; $t = count($hbn_class_objs); for ($i = 0; $i < $t; $i++) $main_content .= '<option ' . ($hbn_class_objs[$i] == $extends ? 'selected' : '') . '>' . $hbn_class_objs[$i] . '</option>'; if ($extends && !in_array($extends, $hbn_class_objs)) $main_content .= '<option selected>' . $extends . '</option>'; $main_content .= '
		</select>
		<span class="icon search" onClick="getExtendedClassFromFileManager(this)" title="Get from File Manager">Get from Fie Manager</span>
	</div>'; $main_content .= '
	<div class="ids">
		<label>Primary Keys Settings:</label>
		<span class="icon add" onClick="addNewId(this)">add</span>
		<span class="icon update" onClick="createHibernateObjectIdsAutomatically(this)" title="Create Ids Automatically">add</span>
		<div class="fields">'; $ids = array(); if ($obj_data["childs"]["id"]) { $t = count($obj_data["childs"]["id"]); for ($i = 0; $i < $t; $i++) { $attr_name = $obj_data["childs"]["id"][$i]["@"]["column"]; $generator = WorkFlowDataAccessHandler::getNodeValue($obj_data["childs"]["id"][$i], "generator", "type"); $main_content .= getIdHTML($attr_name, $generator); } } $main_content .= '
		</div>
	</div>'; $main_content .= '
	<div id="tabs" class="advanced_settings">
		 <ul>
			<li><a href="#tabs-2">Parameter Map</a></li>
			<li><a href="#tabs-3">Result Map</a></li>
			<li><a id="relationship_tab" href="#tabs-4">Relationships</a></li>
			<li><a id="query_tab" href="#tabs-5" onClick="initQueriesTab(this)">Queries</a></li>
			<li><a href="#tabs-1">Includes</a></li>
		</ul>
	'; $imports = $obj_data["childs"]["import"]; $main_content .= '<div id="tabs-1">' . $WorkFlowQueryHandler->getInludeHTMLBlock($imports) . '</div>'; $parameter_type = isset($obj_data["childs"]["parameter_map"]) ? "map" : "class"; $parameter_map = $obj_data["childs"]["parameter_map"][0]; $parameter_class = WorkFlowDataAccessHandler::getNodeValue($obj_data, "parameter_class"); $main_content .= '
		<div id="tabs-2">
			<div class="parameters map">
				<div class="description">
					The purpose of a "Parameters Map/Class" is to convert and validate an input data object. This is:<br/>
					- let\'s say that a specific method receives an argument, which is an object with a "name", "age" and "country" attributes. Something like: {"name" => "...", "age" => "...", "country" => "..."}. <br/>
					- but the real input object passed to this method only contains the attributes "n", "a" and "c". Something like: {"n" => "David", "a" => "35", "c" => "Portugal"}. <br/>
					<br/>
					So we can create a "Parameters Map/Class" to convert this input object to the right one, transforming the attribute "n" to "name", "a" to "age" and "c" to "country". This is, to something like: {"name" => "David", "age" => "35", "country" => "Portugal"}<br/>
					Additionally we can refer that the "age" attribute is a numeric field, and the system will check and convert the correspondent value to that type.
				</div>
				<div class="type">
					<label>Parameters type:</label>
					<select name="parameter_type" onChange="onChangeParameterType(this)">
						<option value="class" ' . ($parameter_type == "class" ? 'selected' : '') . '>Class</option>
						<option value="map" ' . ($parameter_type == "map" ? 'selected' : '') . '>Map</option>
					</select>
				</div>
				' . getParameterClassHTML($parameter_type, $parameter_class) . '
				' . $WorkFlowQueryHandler->getParameterMapHTML($parameter_type, $parameter_map, $map_php_types, $map_db_types) . '
			</div>
		</div>'; $result_type = isset($obj_data["childs"]["result_map"]) ? "map" : "class"; $result_map = $obj_data["childs"]["result_map"][0]; $result_class = WorkFlowDataAccessHandler::getNodeValue($obj_data, "result_class"); $main_content .= '
		<div id="tabs-3">
			<div class="results map">
				<div class="description">
					The purpose of a "Result Map/Class" is to convert and validate an output data object. This is:<br/>
					- let\'s say that a specific method returns a result, which is an object with a "name", "age" and "country" attributes. Something like: {"name" => "David", "age" => "35", "country" => "Portugal"}. <br/>
					- but the real output object that we would like to return should contain the attributes "n", "a" and "c". Something like: {"n" => "...", "a" => "...", "c" => "..."}. <br/>
					<br/>
					So we can create a "Result Map/Class" to convert this result to the right output object, transforming the attribute "name" to "n", "age" to "a" and "country" to "c". This is, to something like: {"n" => "David", "a" => "35", "c" => "Portugal"}<br/>
					Additionally we can refer that the "a" attribute is a numeric field, and the system will check and convert the correspondent value to that type.
				</div>
				<div class="type">
					<label>Result type:</label>
					<select name="result_type" onChange="onChangeResultType(this)">
						<option value="class" ' . ($result_type == "class" ? 'selected' : '') . '>Class</option>
						<option value="map" ' . ($result_type == "map" ? 'selected' : '') . '>Map</option>
					</select>
				</div>
				' . getResultClassHTML($result_type, $result_class) . '
				' . $WorkFlowQueryHandler->getResultMapHTML($result_type, $result_map, $map_php_types, $map_db_types) . '
			</div>
		</div>'; $relationships = $obj_data["childs"]["relationships"][0]["childs"]; $main_content .= '
		<div id="tabs-4" class="hbn_obj_relationships">
			' . $WorkFlowQueryHandler->getDataAccessObjHtml($relationships, true) . '
		</div>
		<script>
		var relationships_elm = $(".hbn_obj_relationships").children(".relationships");
		relationships_elm.find(".advanced_query_settings").html("Show More Settings");
		relationships_elm.children(".update").first().attr("onClick", "updateHibernateObjectRelationshipsAutomatically(this)");
		</script>'; $queries = $obj_data["childs"]["queries"][0]["childs"]; $main_content .= '
		<div id="tabs-5" class="hbn_obj_queries">
			' . $WorkFlowQueryHandler->getDataAccessObjHtml($queries) . '
		</div>'; $main_content .= '
	</div>
	
	<div class="save_button">
		<input type="button" name="value" value="SAVE" onClick="saveHibernateObject();" />
	</div>
</div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the selected object. Please refresh and try again...</div>'; function getParameterClassHTML($paeae9fca, $v217e7cf3c0, $pb6233a29 = false) { return '
	<div class="class" ' . ($paeae9fca == "class" ? 'style="display:block;"' : 'style="display:none;"') . '>
		<label>Parameter Class:</label>
		<input type="text" name="parameter_class" value="' . $v217e7cf3c0 . '" />
		<span class="icon search" onClick="getParameterClassFromFileManager(this)" title="Get class from File Manager">Get from File Manager</span>
		<span class="icon delete" onClick="$(this).parent().remove();" ' . ($pb6233a29 ? '' : 'style="display:none;"') . '>delete</span>
	</div>'; } function getResultClassHTML($pbd9f98de, $v21ff8db28c, $pb6233a29 = false) { return '
	<div class="class" ' . ($pbd9f98de == "class" ? 'style="display:block;"' : 'style="display:none;"') . '>
		<label>Result Class:</label>
		<input type="text" name="result_class" value="' . $v21ff8db28c . '" />
		<span class="icon search" onClick="getResultClassFromFileManager(this)" title="Get class from File Manager">Get from File Manager</span>
		<span class="icon delete" onClick="$(this).parent().remove();" ' . ($pb6233a29 ? '' : 'style="display:none;"') . '>delete</span>
	</div>'; } function getIdHTML($v5e45ec9bb9 = false, $v3a031888ca = false) { return '<div class="id">
		<label class="attr_name">Attribute Name:</label>
		<input class="attr_name" type="text" name="id_columns[]" value="' . $v5e45ec9bb9 . '" />
		<span class="icon search" onClick="getTableAttributeFromDB(this, \'input.attr_name\')" title="Get attribute from DB">Get from DB</span>
		<label class="generator">Generator Type:</label>
		<select class="generator">
			<option value="">-- Default --</option>
			<option ' . (strtolower($v3a031888ca) == "increment" ? 'selected' : '') . '>increment</option>
		</select>
		<span class="icon delete" onClick="$(this).parent().remove();">delete</span>
	</div>'; } ?>
