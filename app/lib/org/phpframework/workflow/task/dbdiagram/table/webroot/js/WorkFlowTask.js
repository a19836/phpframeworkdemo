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

var DBTableTaskPropertyObj = {
	table_charsets : null, //This will be set by the db driver in the page where this task is called
	table_storage_engines : null, //This will be set by the db driver in the page where this task is called
	table_collations : null, //This will be set by the db driver in the page where this task is called
	column_charsets : null, //This will be set by the db driver in the page where this task is called
	column_collations : null, //This will be set by the db driver in the page where this task is called
	column_types : null,
	column_simple_types : null,
	column_numeric_types : null,
	column_types_ignored_props : null,
	column_types_hidden_props : null,
	show_properties_on_connection_drop : false,
	
	//private variables
	selected_connection_properties_data : null,
	column_simple_custom_types : null, 
	task_property_values_table_attr_prop_names : ["primary_key", "name", "type", "length", "null", "unsigned", "unique", "auto_increment", "has_default", "default", "extra", "charset", "collation", "comment"],
	
	/* Do not uncomment this bc we want to be able to choose the serial types in the diagrams. Postgres uses the serial types.
	column_serial_types : {
		"smallserial" : {type: "smallint", "null": false, unique: true, unsigned: true, auto_increment: true, extra: "auto_increment"},
		"serial" : {type: "int", "null": false, unique: true, unsigned: true, auto_increment: true, extra: "auto_increment"},
		"bigserial" : {type: "bigint", "null": false, unique: true, unsigned: true, auto_increment: true, extra: "auto_increment"},
	},*/
	
	/** START: TASK METHODS **/
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		//console.debug(properties_html_elm);
		//console.debug(task_id);
		//console.debug(task_property_values);
		
		var task_html_elm = properties_html_elm.find('.db_table_task_html');
		
		task_html_elm.tabs();
		
		task_html_elm.find(" > ul > li > a").click(function(ev) {
			var tab_panel_id = $(this).attr("href").replace("#", "");
			task_html_elm.removeClass("simple_ui_shown advanced_ui_shown").addClass(tab_panel_id + "_shown");
		});
		
		var charsets = $.isPlainObject(DBTableTaskPropertyObj.table_charsets) ? DBTableTaskPropertyObj.table_charsets : {};
		var collations = $.isPlainObject(DBTableTaskPropertyObj.table_collations) ? DBTableTaskPropertyObj.table_collations : {};
		var storage_engines = $.isPlainObject(DBTableTaskPropertyObj.table_storage_engines) ? DBTableTaskPropertyObj.table_storage_engines : {};
		
		//PREPARING TABLE NAME
		var task_label = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getTaskLabelByTaskId(task_id);
		
		//PREPARING CHARSETS
		var charset_options = '<option value="">-- Default --</option>';
		var charset_exists = false;
		var charset_lower = task_property_values.table_charset ? ("" + task_property_values.table_charset).toLowerCase() : "";
		var table_charset_elm = task_html_elm.find('.table_charset');
		
		if ($.isEmptyObject(charsets))
			table_charset_elm.hide();
		else {
			table_charset_elm.show();
			
			for(var charset_id in charsets) {
				var selected = ("" + charset_id).toLowerCase() == charset_lower;
				charset_options += '<option value="' + charset_id + '"' + (selected ? ' selected' : '') + '>' + charsets[charset_id] + '</option>';
				
				if (selected)
					charset_exists = true;
			}
		}
		
		if (task_property_values.table_charset && !charset_exists)
			charset_options += '<option value="' + task_property_values.table_charset + '" selected>' + task_property_values.table_charset + ' - NON DEFAULT</option>';
		
		table_charset_elm.find('select').html(charset_options);
		
		//PREPARING COLLATIONS
		var collation_options = '<option value="">-- Default --</option>';
		var collation_exists = false;
		var collation_lower = task_property_values.table_collation ? ("" + task_property_values.table_collation).toLowerCase() : "";
		var table_collation_elm = task_html_elm.find('.table_collation');
		
		if ($.isEmptyObject(collations))
			table_collation_elm.hide();
		else {
			table_collation_elm.show();
			
			for(var collation_id in collations) {
				var selected = ("" + collation_id).toLowerCase() == collation_lower;
				collation_options += '<option value="' + collation_id + '"' + (selected ? ' selected' : '') + '>' + collations[collation_id] + '</option>';
				
				if (selected)
					collation_exists = true;
			}
		}
		
		if (task_property_values.table_collation && !collation_exists)
			collation_options += '<option value="' + task_property_values.table_collation + '" selected>' + task_property_values.table_collation + ' - NON DEFAULT</option>';
		
		table_collation_elm.find('select').html(collation_options);
		
		//PREPARING STORAGE ENGINES
		var storage_engine_options = '<option value="">-- Default --</option>';
		var storage_engine_exists = false;
		var storage_engine_lower = task_property_values.table_storage_engine ? ("" + task_property_values.table_storage_engine).toLowerCase() : "";
		var table_storage_engine_elm = task_html_elm.find('.table_storage_engine');
		
		if ($.isEmptyObject(storage_engines))
			table_storage_engine_elm.hide();
		else {
			table_storage_engine_elm.show();
			
			for(var storage_engine_id in storage_engines) {
				var selected = ("" + storage_engine_id).toLowerCase() == storage_engine_lower;
				storage_engine_options += '<option value="' + storage_engine_id + '"' + (selected ? ' selected' : '') + '>' + storage_engines[storage_engine_id] + '</option>';
				
				if (selected)
					storage_engine_exists = true;
			}
		}
		
		if (task_property_values.table_storage_engine && !storage_engine_exists)
			storage_engine_options += '<option value="' + task_property_values.table_storage_engine + '" selected>' + task_property_values.table_storage_engine + ' - NON DEFAULT</option>';
		
		table_storage_engine_elm.find('select').html(storage_engine_options);
		
		//PREPARING TABLE NAME AND ATTRIBUTES
		task_html_elm.find('.table_name input').val(task_label);
		task_html_elm.find('.table_attrs').html("");
		
		//reset column_simple_custom_types so they don't get saved between tables
		DBTableTaskPropertyObj.column_simple_custom_types = {};
		
		//hide some columns
		if ($.isArray(DBTableTaskPropertyObj.column_types_hidden_props))
			for (var i = 0; i < DBTableTaskPropertyObj.column_types_hidden_props.length; i++) {
				var prop_name = DBTableTaskPropertyObj.column_types_hidden_props[i];
				
				if (prop_name)
					task_html_elm.find('table thead .table_attr_' + prop_name).hide();
			}
		
		var simple_attributes_html = "";
		var advanced_attributes_html = "";
		
		if (!task_property_values || !task_property_values.table_attr_names || task_property_values.table_attr_names.length == 0)
			advanced_attributes_html = DBTableTaskPropertyObj.getTableAttributeHtml();
		else {
			DBTableTaskPropertyObj.regularizeTaskPropertyValues(task_property_values);
			
			$.each(task_property_values.table_attr_names, function(i, table_attr_name) {
				var data = {};
				
				for (var j = 0; j < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; j++) {
					var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[j];
					data[prop_name] = task_property_values["table_attr_" + prop_name + "s"][i];
				}
				
				advanced_attributes_html += DBTableTaskPropertyObj.getTableAttributeHtml(data);
				simple_attributes_html += DBTableTaskPropertyObj.getSimpleAttributeHtml(data);
			});
		}
		
		task_html_elm.find(".table_attrs").html(advanced_attributes_html);
		task_html_elm.find(".simple_attributes > ul").append(simple_attributes_html);
		
		if (simple_attributes_html)
			task_html_elm.find(".simple_attributes > ul > .no_simple_attributes").hide();
		
		//converts table to list by default if taskflowchart is fixed_side_properties
		var is_fixed_properties_panel = $("#" + myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.main_tasks_flow_obj_id).parent().closest(".taskflowchart").hasClass("fixed_side_properties");
		
		if (is_fixed_properties_panel)
			DBTableTaskPropertyObj.convertTableToList( task_html_elm.find(".attributes .view")[0] );
	},
	
	onSubmitTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		var task_html_elm = properties_html_elm.find('.db_table_task_html');
		var task_label = task_html_elm.find(".table_name input").val();
		var label_obj = {label: task_label};
		var is_attributes_list_shown = task_html_elm.hasClass("attributes_list_shown");
		
		//check which tab is selected and if is Simple UI tab, convert the attributes to advanced UI
		var active_tab = task_html_elm.tabs('option', 'active');
		var auto_save = myWFObj.getJsPlumbWorkFlow().jsPlumbProperty.auto_save;
		
		if (active_tab == 0) {
			if (auto_save || auto_convert || confirm("in order to save, the system will now convert the Simple UI's attributes into the Advanced UI. Do you wish to proceed?"))
				DBTableTaskPropertyObj.updateTableAttributesHtmlWithSimpleAttributes(task_html_elm.find(" > ul > li > a")[0], true);
			else
				return false;
		}
		
		//converts list to table first, if apply
		if (is_attributes_list_shown)
			DBTableTaskPropertyObj.convertListToTable( task_html_elm.find(".attributes .view")[0] );
		
		//PREPARE has_default checkboxes according with the default values:
		var has_defaults = task_html_elm.find(".table_attr_has_default .task_property_field");
		var defaults = task_html_elm.find(".table_attr_default .task_property_field");
		
		for (var i = 0; i < has_defaults.length; i++) {
			if (defaults[i].value) {
				has_defaults[i].setAttribute("checked", "checked");
				
				$(defaults[i]).val( defaults[i].value.replace(/\"/g, '\'') );
			}
		}
		
		var names = task_html_elm.find(".table_attr_name .task_property_field");
		for (var i = 0; i < names.length; i++) {
			var v = $(names[i]).val();
			$(names[i]).val( normalizeTaskTableName(v) );
		}
		
		//PREPARE Task label and show the table attributes in the workflow task:
		if (DBTableTaskPropertyObj.onCheckLabel(label_obj, task_id)) {
			var primary_keys_fields = task_html_elm.find(".table_attr_primary_key .task_property_field");
			var names_fields = task_html_elm.find(".table_attr_name .task_property_field");
			var types_fields = task_html_elm.find(".table_attr_type .task_property_field");
			var lengths_fields = task_html_elm.find(".table_attr_length .task_property_field");
			var uks_fields = task_html_elm.find(".table_attr_unique .task_property_field");
			
			var column_types_ignored_props = $.isPlainObject(DBTableTaskPropertyObj.column_types_ignored_props) ? DBTableTaskPropertyObj.column_types_ignored_props : {};
			
			//prepare disabled lengths fields
			$.each(lengths_fields, function(index, length_field) {
				var type = $(types_fields[index]).val();
				var column_type_ignored_props = type && column_types_ignored_props.hasOwnProperty(type) && $.isArray(column_types_ignored_props[type]) ? column_types_ignored_props[type] : [];
				var is_length_disabled = !type || $.inArray("length", column_type_ignored_props) != -1;
				var length = $(length_field).val(); //Do not ad parseInt or parseFloat bc the length can be 2 values splited by comma, like it happens with the decimal type.
				
				lengths_fields[index] = !is_length_disabled && (length || parseInt(length) === 0) ? length_field : null; //Do not ad parseInt or parseFloat bc the length can be 2 values splited by comma, like it happens with the decimal type.
			});
			
			//prepare serial fields
			$.each(types_fields, function(index, type_field) {
				type_field = $(type_field);
				var type = type_field.val();
				var tr = type_field.parent().closest("tr");
				
				DBTableTaskPropertyObj.prepareAttributeSerialType(tr, type);
			});
			
			DBTableTaskPropertyObj.prepareTableAttributes(task_id, {
				table_name: label_obj.label,
				table_attr_names: names_fields,
				table_attr_primary_keys: primary_keys_fields,
				table_attr_types: types_fields,
				table_attr_lengths: lengths_fields,
				table_attr_uniques: uks_fields,
			});
			
			return true;
		}
		
		//converts back table to list
		if (is_attributes_list_shown)
			DBTableTaskPropertyObj.convertTableToList( task_html_elm.find(".attributes .view")[0] );
		
		return false;
	},
	
	onCheckLabel : function(label_obj, task_id) {
		if (isTaskTableLabelValid(label_obj, task_id))
			return true;
		
		label_obj.label = normalizeTaskTableName(label_obj.label);
		
		if (isTaskTableLabelValid(label_obj, task_id, true))
			return true;
		
		myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.removeLastShownMessage("error");
		return false;
	},
	
	onCancelLabel : function(task_id) {
		return prepareLabelIfUserLabelIsInvalid(task_id);
	},
	
	onCompleteLabel : function(task_id) {
		onEditLabel(task_id);
		
		updateTaskLabelInShownTaskProperties(task_id, ".db_table_task_html .table_name input");
		
		myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.repaintTaskByTaskId(task_id);
		
		return true;
	},
	
	onTaskCreation : function(task_id) {
		var task_property_values = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id];
		
		if (task_property_values && task_property_values.table_attr_names && task_property_values.table_attr_names.length > 0) {
			DBTableTaskPropertyObj.regularizeTaskPropertyValues(task_property_values);
			DBTableTaskPropertyObj.prepareTableAttributes(task_id, task_property_values);
		}
	},
	
	prepareTableAttributes : function(task_id, data) {
		if (data) {
			var label = data.table_name;
			var WF = myWFObj.getJsPlumbWorkFlow();
			var task = WF.jsPlumbTaskFlow.getTaskById(task_id);
			
			WF.jsPlumbTaskFlow.getTaskLabelElement(task).html(label);
			onEditLabel(task_id);
			
			var primary_keys = data.table_attr_primary_keys;
			var names = data.table_attr_names;
			var types = data.table_attr_types;
			var lengths = data.table_attr_lengths;
			var uks = data.table_attr_uniques;
			
			if (names) {
				var column_types_ignored_props = $.isPlainObject(DBTableTaskPropertyObj.column_types_ignored_props) ? DBTableTaskPropertyObj.column_types_ignored_props : {};
				
				//PREPARE ATTRIBUTES
				var html = '<table class="table_attrs">';
				
				for (var i = 0; i < names.length; i++) {
					var name = names[i] && names[i].nodeName && names[i].nodeName.toLowerCase() == "input" ? $(names[i]).val() : names[i];
					var primary_key = primary_keys[i] && primary_keys[i].nodeName && primary_keys[i].nodeName.toLowerCase() == "input" ? $(primary_keys[i]).is(":checked") : primary_keys[i];
					var type = types[i] && types[i].nodeName && types[i].nodeName.toLowerCase() == "select" ? $(types[i]).val() : types[i];
					var length = lengths[i] && lengths[i].nodeName && lengths[i].nodeName.toLowerCase() == "input" ? $(lengths[i]).val() : lengths[i];
					var uk = uks[i] && uks[i].nodeName && uks[i].nodeName.toLowerCase() == "input" ? $(uks[i]).is(":checked") : uks[i];
					
					var column_type_ignored_props = type && column_types_ignored_props.hasOwnProperty(type) && $.isArray(column_types_ignored_props[type]) ? column_types_ignored_props[type] : [];
					var is_length_disabled = !type || $.inArray("length", column_type_ignored_props) != -1;
					
					name = name ? name : "";
					primary_key = checkIfValueIsTrue(primary_key) ? "PK" : (checkIfValueIsTrue(uk) ? "UK" : "");
					type = type ? type : "";
					length = !is_length_disabled && (length || parseInt(length) === 0) ? length : ""; //Do not ad parseInt or parseFloat bc the length can be 2 values splited by comma, like it happens with the decimal type.
					
					var primary_key_title = primary_key == "PK" ? "Primary Key" : (primary_key == "UK" ? "Unique Key" : "");
					var name_title = "Attribute Name: " + name.replace(/"/g, "&quot;");
					var type_title = "Attribute Type: " + type + "\nAttribute Length: " + length;
					
					html += '<tr class="table_attr"><td class="primary_key" title="' + primary_key_title + '">' + primary_key + '</td><td class="name" title="' + name_title + '">' + name + '</td><td class="type" title="' + type_title + '">' + type + (length ? " (" + length + ")" : "") + '</td></tr>';
				}
				
				html += "</table>";
				
				var eps = task.children("." + WF.jsPlumbTaskFlow.task_eps_class_name);
				eps.children(".table_attrs").remove();
				eps.append(html);
	
				this.prepareTableForeignKeys(task_id);
				
				var label_height = parseInt( task.children("." + WF.jsPlumbTaskFlow.task_label_class_name).height() );
				var min_height = parseInt( task.css("min-height") );
	
				var height = names.length * 18 + label_height;
				height = height < min_height ? min_height : height;
	
				task.css("height", height);
				
				this.checkingTaskConnectionsPropertiesFromTaskProperties(task_id);
				
				resizeTableTaskBasedOnAttributes(task_id);
			}
		}
	},
	
	prepareTableForeignKeys : function(task_id) {
		if (task_id) {
			var fks = this.getTaskForeignKeys(task_id);
			var task = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getTaskById(task_id);
			
			var primary_keys = task.find(" > ." + myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.task_eps_class_name + " .table_attrs .table_attr .primary_key");
			var names = task.find(" > ." + myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.task_eps_class_name + " .table_attrs .table_attr .name");
		
			if (names) {
				for (var i = 0; i < names.length; i++) {
					var j_primary_key = $(primary_keys[i]);
					var primary_key = j_primary_key.text();
					var name = $(names[i]).text();
					var fk = $.inArray(name, fks) != -1;
					//console.log(name+":"+primary_key+":"+fk);
					
					var str = "";
					
					if (fk)
						str = primary_key[0] == "P" ? "PFK" : (primary_key[0] == "U" ? "FUK" : "FK");
					else if (primary_key && primary_key[0] == "P")
						str = "PK";
					else if (primary_key && primary_key[0] == "U")
						str = "UK";
					
					var title = "";
					switch (str) {
						case "PK": title = "Primary Key"; break;
						case "UK": title = "Unique Key"; break;
						case "FK": title = "Foreign Key"; break;
						case "PFK": title = "Primary and Foreign Key"; break;
						case "FUK": title = "Foreign and Unique Key"; break;
					}
					
					j_primary_key.attr("title", title);
					j_primary_key.html(str);
				}
			}
		}
	},
	
	updateTaskPropertiesFromTableAttributes : function(task_id, table_attributes) {
		var task_property_values = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id];
		task_property_values = task_property_values ? task_property_values : {};
		
		for (var i = 0; i < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; i++) {
			var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[i];
			task_property_values["table_attr_" + prop_name + "s"] = [];
		}
		
		for (var attr_name in table_attributes) {
			if (attr_name != "properties") {
				var prop = table_attributes[attr_name]["properties"];
		
				if (prop) {
					var type = prop["type"];
					
					//prepare serial props
					/*Do not uncomment this bc we want to be able to choose the serial types in the diagrams. Postgres uses the serial types.
					if (DBTableTaskPropertyObj.column_serial_types && DBTableTaskPropertyObj.column_serial_types.hasOwnProperty(type) && $.isPlainObject(DBTableTaskPropertyObj.column_serial_types[type]))
						for (var k in DBTableTaskPropertyObj.column_serial_types[type]) {
							var v = DBTableTaskPropertyObj.column_serial_types[type][k];
							
							if (k == "extra" && prop["extra"]) {
								prop["extra"] = "" + prop["extra"];
								var parts = ("" + v).split(" ");
								
								for (var i = 0; i < parts.length; i++)
									if (prop["extra"].toLowerCase().indexOf(parts[i].toLowerCase()) == -1)
										prop["extra"] += " " + parts[i];
							}
							else
								prop[k] = v;
						}*/
					
					for (var i = 0; i < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; i++) {
						var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[i];
						var prop_value = prop[prop_name];
						
						if (prop_name == "type")
							prop_value = type;
						else if (prop_name == "has_default")
							prop_value = prop["default"] ? true : false;
						else if (prop_name == "default" || prop_name == "charset" || prop_name == "collation")
							prop_value = prop[prop_name] ? prop[prop_name] : "";
						
						task_property_values["table_attr_" + prop_name + "s"].push(prop_value);
					}
				}
			}
		}

		myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id] = task_property_values;

		this.prepareTableAttributes(task_id, task_property_values);
	},
	
	getTableAttributeHtml : function(data) {
		//console.debug(data);
		
		var column_types_hidden_props = $.isArray(DBTableTaskPropertyObj.column_types_hidden_props) ? DBTableTaskPropertyObj.column_types_hidden_props : [];
		var charsets = $.isPlainObject(DBTableTaskPropertyObj.column_charsets) ? DBTableTaskPropertyObj.column_charsets : {};
		var collations = $.isPlainObject(DBTableTaskPropertyObj.column_collations) ? DBTableTaskPropertyObj.column_collations : {};
		var types = $.isPlainObject(DBTableTaskPropertyObj.column_types) ? DBTableTaskPropertyObj.column_types : {};
		
		var primary_key = false, name = "", type, length = "", is_null = false, unsigned = false, unique = false, auto_increment = false, has_default = false, default_value = "", extra = "", charset = "", collation = "", comment = "";
		
		if (data) {
			primary_key = DBTableTaskPropertyObj.checkIfTrue(data.primary_key);
			name = data.name ? data.name : "";
			type = data.type;
			length = data["length"] || parseInt(data["length"]) === 0 ? data["length"] : ""; //Do not ad parseInt or parseFloat bc the length can be 2 values splited by comma, like it happens with the decimal type.
			is_null = DBTableTaskPropertyObj.checkIfTrue(data["null"]);
			unsigned = DBTableTaskPropertyObj.checkIfTrue(data.unsigned);
			unique = DBTableTaskPropertyObj.checkIfTrue(data.unique);
			auto_increment = DBTableTaskPropertyObj.checkIfTrue(data.auto_increment);
			has_default = DBTableTaskPropertyObj.checkIfTrue(data.has_default);
			default_value = data["default"] && data["default"] != null ? data["default"] : "";
			extra = data.extra ? data.extra : "";
			charset = data.charset ? data.charset : "";
			collation = data.collation ? data.collation : "";
			comment = data.comment ? data.comment : "";
			
			if (default_value)
				has_default = true;
		}
		
		is_null = primary_key ? false : is_null;
		unique = primary_key ? true : unique;
		
		var column_types_ignored_props = $.isPlainObject(DBTableTaskPropertyObj.column_types_ignored_props) ? DBTableTaskPropertyObj.column_types_ignored_props : {};
		var column_type_ignored_props = type && column_types_ignored_props.hasOwnProperty(type) && $.isArray(column_types_ignored_props[type]) ? column_types_ignored_props[type] : [];
		
		var is_length_disabled = !type || $.inArray("length", column_type_ignored_props) != -1;
		var is_unsigned_disabled = !type || $.inArray("unsigned", column_type_ignored_props) != -1;
		var is_null_disabled = type && $.inArray("null", column_type_ignored_props) != -1;
		var is_auto_increment_disabled = type && $.inArray("auto_increment", column_type_ignored_props) != -1;
		var is_default_disabled = type && $.inArray("default", column_type_ignored_props) != -1;
		var is_extra_disabled = type && $.inArray("extra", column_type_ignored_props) != -1;
		var is_charset_disabled = type && $.inArray("charset", column_type_ignored_props) != -1;
		var is_collation_disabled = type && $.inArray("collation", column_type_ignored_props) != -1;
		var is_comment_disabled = type && $.inArray("comment", column_type_ignored_props) != -1;
		
		var html = '<tr>'
					+ '<td class="table_attr_primary_key"><input type="checkbox" class="task_property_field" name="table_attr_primary_keys[]" ' + (primary_key ? 'checked="checked"' : '') + ' value="1" onClick="DBTableTaskPropertyObj.onClickCheckBox(this)" /></td>'
					+ '<td class="table_attr_name"><input type="text" class="task_property_field" name="table_attr_names[]" value="' + name + '" onBlur="DBTableTaskPropertyObj.onBlurTableAttributeInputBox(this)" /></td>'
					+ '<td class="table_attr_type"><select class="task_property_field" name="table_attr_types[]" onChange="DBTableTaskPropertyObj.onChangeSelectBox(this)"><option></option>';
		
		for (var key in types) 
			html += '<option value="' + key + '" ' + (key == type ? "selected" : "") + '>' + types[key] + '</option>';
		
		if (type && !types.hasOwnProperty(type))
			html += '<option value="' + type + '">' + type + ' - NON DEFAULT</option>';
		
		html +=			 '</select></td>'
					+ '<td class="table_attr_length"' + ($.inArray("length", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '><input type="text" class="task_property_field" name="table_attr_lengths[]" value="' + length + '" ' + (is_length_disabled ? 'disabled="disabled"' : '') + ' /></td>'
					+ '<td class="table_attr_null"' + ($.inArray("null", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '><input type="checkbox" class="task_property_field" name="table_attr_nulls[]" ' + (is_null ? 'checked="checked"' : '') + ' value="1" ' + (is_null_disabled ? 'disabled="disabled"' : '') + ' /></td>'
					+ '<td class="table_attr_unsigned"' + ($.inArray("unsigned", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '><input type="checkbox" class="task_property_field" name="table_attr_unsigneds[]" ' + (unsigned ? 'checked="checked"' : '') + ' value="1" ' + (is_unsigned_disabled ? 'disabled="disabled"' : '') + ' /></td>'
					+ '<td class="table_attr_unique"' + ($.inArray("unique", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '><input type="checkbox" class="task_property_field" name="table_attr_uniques[]" ' + (unique ? 'checked="checked"' : '') + ' value="1" /></td>'
					+ '<td class="table_attr_auto_increment"' + ($.inArray("auto_increment", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '><input type="checkbox" class="task_property_field" name="table_attr_auto_increments[]" ' + (auto_increment ? 'checked="checked"' : '') + ' value="1" ' + (is_auto_increment_disabled ? 'disabled="disabled"' : '') + ' /></td>'
					+ '<td class="table_attr_has_default"' + ($.inArray("default", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '><input type="checkbox" class="task_property_field" name="table_attr_has_defaults[]" ' + (has_default ? 'checked="checked"' : '') + ' value="1" ' + (is_default_disabled ? 'disabled="disabled"' : '') + ' onClick="DBTableTaskPropertyObj.onClickCheckBox(this)" title="Enable/Disable Default value" /></td>'
					+ '<td class="table_attr_default"' + ($.inArray("default", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '><input type="text" class="task_property_field" name="table_attr_defaults[]" value="' + default_value + '" ' + (has_default && !is_default_disabled ? '' : 'disabled="disabled"') + ' /></td>'
					+ '<td class="table_attr_extra"' + ($.inArray("extra", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '><input type="text" class="task_property_field" name="table_attr_extras[]" value="' + extra + '" ' + (is_extra_disabled ? 'disabled="disabled"' : '') + ' /></td>'
					+ '<td class="table_attr_charset"' + ($.inArray("charset", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '><select class="task_property_field" name="table_attr_charsets[]" ' + (is_charset_disabled ? 'disabled="disabled"' : '') + '><option value="">-- Default --</option>';
		
		var charset_exists = false;
		var charset_lower = charset ? ("" + charset).toLowerCase() : "";
		
		$.each(charsets, function(charset_id, charset_label) {
			var selected = ("" + charset_id).toLowerCase() == charset_lower;
			html += '<option value="' + charset_id + '" ' + (selected ? "selected" : "") + '>' + charset_label + '</option>';
			
			if (selected)
				charset_exists = true;
		});
		
		if (charset && !charset_exists)
			html += '<option value="' + charset + '" selected>' + charset + ' - NON DEFAULT</option>';
		
		html +=			 '</select></td>'
					+ '<td class="table_attr_collation"' + ($.inArray("collation", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '><select class="task_property_field" name="table_attr_collations[]" ' + (is_collation_disabled ? 'disabled="disabled"' : '') + '><option value="">-- Default --</option>';
		
		var collation_exists = false;
		var collation_lower = collation ? ("" + collation).toLowerCase() : "";
		
		$.each(collations, function(collation_id, collation_label) {
			var selected = ("" + collation_id).toLowerCase() == collation_lower;
			html += '<option value="' + collation_id + '" ' + (selected ? "selected" : "") + '>' + collation_label + '</option>';
			
			if (selected)
				collation_exists = true;
		});
		
		if (collation && !collation_exists)
			html += '<option value="' + collation + '" selected>' + collation + ' - NON DEFAULT</option>';
		
		html +=			 '</select></td>'
					+ '<td class="table_attr_comment"' + ($.inArray("comment", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '><input type="text" class="task_property_field" name="table_attr_comments[]" value="' + comment + '" ' + (is_comment_disabled ? 'disabled="disabled"' : '') + ' /></td>'
					+ '<td class="table_attr_icons">'
					+ '	<a class="icon move_up" onClick="DBTableTaskPropertyObj.moveUpTableAttribute(this)">move up</a>'
					+ '	<a class="icon move_down" onClick="DBTableTaskPropertyObj.moveDownTableAttribute(this)">move down</a>'
					+ '	<a class="icon delete" onClick="DBTableTaskPropertyObj.removeTableAttribute(this)">remove</a>'
					+ '</td>'
			+ '</tr>';
		
		return html;
	},
	
	onClickCheckBox : function(elm) {
		var j_elm = $(elm);
		var j_parent = j_elm.parent();
		
		if (j_parent.hasClass("table_attr_has_default")) {
			var default_field = j_parent.parent().find('.table_attr_default input');
		
			if(elm.checked)
				default_field.removeAttr('disabled');
			else
				default_field.attr('disabled', 'disabled').val('');
		}
		else if (j_elm.is(":checked") && j_parent.hasClass("table_attr_primary_key")) {
			var column_numeric_types = $.isArray(DBTableTaskPropertyObj.column_numeric_types) ? DBTableTaskPropertyObj.column_numeric_types : [];
			var j_grand_parent = j_parent.parent();
			var type = j_grand_parent.find('.table_attr_type select').val();
			var primary_keys_count = j_grand_parent.parent().find(".table_attr_primary_key input:checked").length;
			j_grand_parent.find('.table_attr_null input').removeAttr("checked").prop("checked", false);
			j_grand_parent.find('.table_attr_unique input').attr("checked", "checked").prop("checked", true);
			
			//if there is only 1 primary key and type is numeric or blank, then add auto_increment text and check unsigned. Note that postgres will recognize the "auto_increment" text and remove it directly in the db-driver, so don't worry.
			if (!type || ($.isArray(column_numeric_types) && $.inArray(type, column_numeric_types) != -1)) { 
				//prepare unsigned
				var unsigned_input = j_grand_parent.find('.table_attr_unsigned input');
				
				if (!unsigned_input.is(":disabled"))
					unsigned_input.attr("checked", "checked").prop("checked", true);
				
				if (primary_keys_count == 1) {
					//prepare extra
					var extra = j_grand_parent.find('.table_attr_extra input');
					var text = extra.val();
					
					if (!text || ("" + text).toLowerCase().indexOf("auto_increment") == -1) {
						j_grand_parent.find('.table_attr_auto_increment input').attr("checked", "checked").prop("checked", true);
						
						extra.val(text + (text ? " " : "") + "auto_increment"); //TODO: Maybe in the future remove this bc it shouldn't be needed, since we already have the .table_attr_auto_increment field.
					}
				}
			}
		}
	},
	
	onChangeSelectBox : function(elm) {
		var j_elm = $(elm);
		var j_parent = j_elm.parent();
		
		if (j_parent.hasClass("table_attr_type")) {
			var value = j_elm.val();
			var tr = j_parent.parent();
			var length_field = tr.find('.table_attr_length input');
			var unsigned_field = tr.find(".table_attr_unsigned input");
			
			DBTableTaskPropertyObj.prepareAttributeSerialType(tr, value);
			value = j_elm.val(); //update the current value
			
			var column_types_ignored_props = $.isPlainObject(DBTableTaskPropertyObj.column_types_ignored_props) ? DBTableTaskPropertyObj.column_types_ignored_props : {};
			var column_type_ignored_props = value && column_types_ignored_props.hasOwnProperty(value) && $.isArray(column_types_ignored_props[value]) ? column_types_ignored_props[value] : [];
			
			tr.find("input, select").removeAttr('disabled');
			
			if (!value) {
				column_type_ignored_props.push("length");
				column_type_ignored_props.push("unsigned");
				column_type_ignored_props.push("auto_increment");
			}
			
			for (var i = 0; i < column_type_ignored_props.length; i++) {
				var field_name = column_type_ignored_props[i];
				var td = tr.find(".table_attr_" + field_name);
				td.find("input, select").attr('disabled', 'disabled');  
				td.find("input[type=text]").val('');
				td.find("input[type=checkbox]").removeAttr("checked").prop("checked", false); 
			}
			
			//disable or enable default field if has_default is or not selected
			DBTableTaskPropertyObj.onClickCheckBox( tr.find(".table_attr_has_default input")[0] );
		}
	},
	
	prepareAttributeSerialType : function(tr, type) {
		/*Do not uncomment this bc we want to be able to choose the serial types in the diagrams. Postgres uses the serial types.
		if (DBTableTaskPropertyObj.column_serial_types && DBTableTaskPropertyObj.column_serial_types.hasOwnProperty(type) && $.isPlainObject(DBTableTaskPropertyObj.column_serial_types[type])) {
			var props = DBTableTaskPropertyObj.column_serial_types[type];
			
			for (var k in props) {
				var v = props[k];
				var input = tr.find('.table_attr_' + k).find('input, select');
				
				if (input[0]) {
					var input_type = input.attr("type");
					
					if (input_type == "checkbox") {
						if (v)
							input.attr("checked", "checked").prop("checked", true); 
						else
							input.removeAttr("checked").prop("checked", false); 
					}
					else if (input.is("select"))
						input.val(v);
					else if (v) {
						var text = input.val();
						text = typeof text != "undefined" ? "" + text : "";
						var parts = ("" + v).split(" ");
						
						for (var i = 0; i < parts.length; i++)
							if (text.toLowerCase().indexOf(parts[i].toLowerCase()) == -1) 
								text += (text ? " " : "") + parts[i];
						
						input.val(text);
					}
				}
			}
		}*/
	},
	
	onBlurTableAttributeInputBox : function(elm) {
		elm = $(elm);
		var name = elm.val();
		
		if (name) {
			name = normalizeTaskTableName(name);
			
			//don't allow . for attribute name
			if (name.indexOf(".") != -1)
				name = name.replace(/\.+/, "");
			
			elm.val(name);
			
			isTaskTableNameAdvisable(name);
		}
	},
	
	addTableAttribute : function(elm) {
		var html = DBTableTaskPropertyObj.getTableAttributeHtml();
		var task_html_elm = $(elm).parent().closest('.db_table_task_html');
		//var task_html_elm = $("#" + myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.main_tasks_flow_obj_id + " .db_table_task_html");
		
		if (task_html_elm.hasClass("attributes_list_shown")) {
			var column_names = DBTableTaskPropertyObj.getTableColumnNames( task_html_elm.find("table") );
			DBTableTaskPropertyObj.convertTableRowToListItem(task_html_elm.find(".list_attrs"), $(html), column_names);
		}
		else
			task_html_elm.find(".table_attrs").append(html);
	},
	
	removeTableAttribute : function(elm) {
		$(elm).parent().parent().remove();
	},
	
	moveUpTableAttribute : function(elm) {
		var item = $(elm).parent().parent();
	
		if (item.prev()[0])
			item.parent()[0].insertBefore(item[0], item.prev()[0]);
	},
	
	moveDownTableAttribute : function(elm) {
		var item = $(elm).parent().parent();
	
		if (item.next()[0])
			item.parent()[0].insertBefore(item.next()[0], item[0]);
	},
	
	getTaskForeignKeys : function(task_id) {
		var fks = [];
		
		var source_connections = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getSourceConnections(task_id);
		var target_connections = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getTargetConnections(task_id);
		var connections = source_connections.concat(target_connections);
	
		for (var i = 0; i < connections.length; i++) {
			var connection = connections[i];
			var overlay = connection.getParameter("connection_exit_overlay");
			var props = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.connections_properties[connection.id];
			
			if (overlay && props) {
				if (connection.sourceId == task_id && overlay != "One To Many") {
					arr = props.source_columns;
				
					if(arr) {
						arr = !$.isArray(arr) && !$.isPlainObject(arr) ? [arr] : arr;
						$.each(arr, function(k, item) {
							fks.push(item);
						});
					}
				}
			
				if (connection.targetId == task_id && overlay != "Many To One" ) {
					arr = props.target_columns;
				
					if(arr) {
						arr = !$.isArray(arr) && !$.isPlainObject(arr) ? [arr] : arr;
						$.each(arr, function(k, item) {
							fks.push(item);
						});
					}
				}
			}
		}
		
		return fks;
	},
	
	updateSimpleAttributesHtmlWithTableAttributes : function(elm, do_not_confirm) {
		if (do_not_confirm || auto_convert || confirm("Do you wish to convert the Advanced UI's attributes into the Simple UI?")) {
			var task_html_elm = $(elm).closest(".db_table_task_html");
			
			//prepare task_property_values
			var task_property_values = {};
			var WF = myWFObj.getJsPlumbWorkFlow();
			var query_string = WF.jsPlumbProperty.getPropertiesQueryStringFromHtmlElm(task_html_elm.find(".table_attrs"), "task_property_field");
			
			try {
				parse_str(query_string, task_property_values);
			}
			catch(e) {
				//alert(e);
				if (console && console.log) {
					console.log(e);
					console.log("Error in updateSimpleAttributesHtmlWithTableAttributes method, trying to execute the parse_str function with query_string: " + query_string);
				}
			}
			
			//prepare html
			var html = "";
			
			//I added the collation after, so there are some .xml files that don't contain this. So we need to add this, otherwise we get a js error.
			$.each(task_property_values.table_attr_names, function(i, table_attr_name) {
				var data = {};
				
				for (var j = 0; j < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; j++) {
					var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[j];
					var prop_value = task_property_values["table_attr_" + prop_name + 's'][i];
					
					data[prop_name] = prop_value;
				}
				
				html += DBTableTaskPropertyObj.getSimpleAttributeHtml(data);
			});
			
			var ul = task_html_elm.find(".simple_attributes > ul");
			ul.children("li:not(.no_simple_attributes)").remove();
			ul.append(html);
			
			if (html)
				ul.children(".no_simple_attributes").hide();
			else
				ul.children(".no_simple_attributes").show();
		}
		
		//remove width and height style so the popup get updated automatically
		myWFObj.getJsPlumbWorkFlow().getMyFancyPopupObj().getPopup().css({width: "", height: ""});
	},
	
	/** START: TASK METHODS - SIMPLE UI **/
	
	updateTableAttributesHtmlWithSimpleAttributes : function(elm, do_not_confirm) {
		if (do_not_confirm || auto_convert || confirm("Do you wish to convert the Simple UI's attributes into the Advanced UI?")) {
			var task_html_elm = $(elm).closest(".db_table_task_html");
			var lis = task_html_elm.find(".simple_attributes > ul > li:not(.no_simple_attributes)");
			var html = '';
			
			for (var i = 0; i < lis.length; i++) {
				var data = DBTableTaskPropertyObj.convertSimpleAttributeIntoTableAttributeData($(lis[i]));
				html += DBTableTaskPropertyObj.getTableAttributeHtml(data);
			}
			
			task_html_elm.find(".table_attrs").html(html);
		}
		
		//remove width and height style so the popup get updated automatically
		myWFObj.getJsPlumbWorkFlow().getMyFancyPopupObj().getPopup().css({width: "", height: ""});
	},
	
	convertSimpleAttributesIntoTableAttributesData : function(elm) {
		var task_html_elm = $(elm).closest(".db_table_task_html");
		var lis = task_html_elm.find(".simple_attributes > ul > li:not(.no_simple_attributes)");
		
		//set task_property_values props with empty array
		var task_property_values = {};
		
		for (var i = 0; i < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; i++) {
			var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[i];
			task_property_values["table_attr_" + prop_name + "s"] = [];
		}
		
		//add values to task_property_values props arrays
		for (var i = 0; i < lis.length; i++) {
			var prop = DBTableTaskPropertyObj.convertSimpleAttributeIntoTableAttributeData($(lis[i]));
			
			for (var j = 0; j < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; j++) {
				var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[j];
				
				task_property_values["table_attr_" + prop_name + "s"].push( prop[prop_name] );
			}
		}
		
		return task_property_values;
	},
	
	convertSimpleAttributeIntoTableAttributeData : function(li) {
		var type_select = li.find("select.simple_attr_type");
		var type = type_select.val();
		
		var column_simple_types = $.isPlainObject(DBTableTaskPropertyObj.column_simple_types) ? DBTableTaskPropertyObj.column_simple_types : {};
		var column_simple_custom_types = $.isPlainObject(DBTableTaskPropertyObj.column_simple_custom_types) ? DBTableTaskPropertyObj.column_simple_custom_types : {};
		
		
		//update the type with the real DB value.
		if (type) {
			if (column_simple_types.hasOwnProperty(type))
				type = column_simple_types[type]["type"];
			else if (column_simple_custom_types.hasOwnProperty(type))
				type = column_simple_custom_types[type]["type"];
		}
		
		var data = {};
		
		for (var i = 0; i < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; i++) {
			var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[i];
			var input_prop_name = prop_name == "null" ? "not_null" : prop_name;
			var field_elm = li.find("input.simple_attr_" + input_prop_name);
			
			if (prop_name == "type")
				data[prop_name] = type;
			else if (field_elm.attr("type") == "checkbox") {
				data[prop_name] = field_elm.is(":checked");
				
				if (prop_name == "null") //the field is not_null, so we must revert the value
					data[prop_name] = data[prop_name] ? false : true;
				
			}
			else if (!field_elm[0] || field_elm[0].hasAttribute("disabled")) //used for the default field and other ignored fields
				data[prop_name] = "";
			//else if (prop_name == "default") //no need anymore bc the above condition already covers this case.
			//	data[prop_name] = li.find("input.simple_attr_has_default").is(":checked") ? field_elm.val() : "";
			else
				data[prop_name] = field_elm.val();
		}
		//console.log(data);
		
		return data;
	},
	
	getSimpleAttributeHtml : function(data) {
		//console.debug(data);
		
		//prepare attributes
		var column_types_hidden_props = $.isArray(DBTableTaskPropertyObj.column_types_hidden_props) ? DBTableTaskPropertyObj.column_types_hidden_props : [];
		var types = $.isPlainObject(DBTableTaskPropertyObj.column_types) ? DBTableTaskPropertyObj.column_types : {};
		var column_simple_types = $.isPlainObject(DBTableTaskPropertyObj.column_simple_types) ? DBTableTaskPropertyObj.column_simple_types : {};
		var column_simple_custom_types = $.isPlainObject(DBTableTaskPropertyObj.column_simple_custom_types) ? DBTableTaskPropertyObj.column_simple_custom_types : {};
		
		var column_simple_types_exists = !$.isEmptyObject(column_simple_types);
		
		var primary_key = false, name = "", type, length = "", is_null = false, unsigned = false, unique = false, auto_increment = false, has_default = false, default_value = "", extra = "", charset = "", collation = "", comment = "";
		
		if (data) {
			primary_key = DBTableTaskPropertyObj.checkIfTrue(data.primary_key);
			name = data.name ? data.name : "";
			type = data.type;
			length = data["length"] || parseInt(data["length"]) === 0 ? data["length"] : ""; //Do not ad parseInt or parseFloat bc the length can be 2 values splited by comma, like it happens with the decimal type.
			is_null = DBTableTaskPropertyObj.checkIfTrue(data["null"]);
			unsigned = DBTableTaskPropertyObj.checkIfTrue(data.unsigned);
			unique = DBTableTaskPropertyObj.checkIfTrue(data.unique);
			auto_increment = DBTableTaskPropertyObj.checkIfTrue(data.auto_increment);
			has_default = DBTableTaskPropertyObj.checkIfTrue(data.has_default);
			default_value = data["default"] && data["default"] != null ? data["default"] : "";
			extra = data.extra ? data.extra : "";
			charset = data.charset ? data.charset : "";
			collation = data.collation ? data.collation : "";
			comment = data.comment ? data.comment : "";
			
			if (default_value)
				has_default = true;
		}
		
		//convert native types to simple types
		if (column_simple_types_exists && type) {
			//prepare current field data
			var is_auto_increment = auto_increment || ("" + extra).toLowerCase().indexOf("auto_increment") != -1;
			var current_simple_props = {
				primary_key: primary_key,
				type: type,
				length: length,
				"null": is_null,
				unsigned: unsigned,
				unique: unique,
				auto_increment: is_auto_increment,
				"default": default_value,
				extra: typeof extra == "string" ? extra.replace(/(^|\s)auto_increment(\s|$)/gi, " ") : extra,
				charset: charset,
				collation: collation,
				comment: comment
			};
			
			//find current field matches with any simple type
			for (var key in column_simple_types) {
				var props = column_simple_types[key];
				
				//prepare auto_increment props
				if (props.hasOwnProperty("auto_increment"))
					props["auto_increment"] = props["auto_increment"] || (props.hasOwnProperty("extra") && typeof props["extra"] == "string" && props["extra"].toLowerCase().indexOf("auto_increment") != -1);
				
				if (props.hasOwnProperty("extra") && typeof props["extra"] == "string")
					props["extra"] = props["extra"].replace(/(^|\s)auto_increment(\s|$)/gi, " ");
				
				//check if all props matches with current field props
				var exists = true;
				
				for (var i = 0; i < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; i++) {
					var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[i];
					
					if (props.hasOwnProperty(prop_name) && props[prop_name] != current_simple_props[prop_name]) {
						//console.log(name+":"+key+":"+prop_name+":"+props[prop_name]+"=="+current_simple_props[prop_name]);
						exists = false;
						break;
					}
				}
				
				if (exists) {
					type = key;
					auto_increment = props["auto_increment"]; //update auto_increment, bc it might be hard-coded in the "extra" prop
					break;
				}
			}
			
			//if current field is not a simple type, but is a primary key, then we need to create a new simple type according with the field props.
			if (primary_key && !column_simple_types.hasOwnProperty(type)) {
				var column_numeric_types = $.isArray(DBTableTaskPropertyObj.column_numeric_types) ? DBTableTaskPropertyObj.column_numeric_types : [];
				var simple_type = "simple_manual_primary_key";
				
				if (is_auto_increment)
					simple_type = "simple_auto_primary_key";
				else if ($.isArray(column_numeric_types) && $.inArray(type, column_numeric_types) != -1)
					simple_type = "simple_fk_auto_primary_key";
				
				var key = simple_type + "_" + type;
				column_simple_custom_types[key] = current_simple_props;
				
				if (column_simple_types.hasOwnProperty(simple_type))
					column_simple_custom_types[key]["label"] = column_simple_types[simple_type]["label"] + " - " + type;
				else //if simple_type doesn't exist in column_simple_types, ucwords the simple_type as label
					column_simple_custom_types[key]["label"] = simple_type.replace(/_/g, " ").replace(/\b[a-z]/g, function(letter) {
						return letter.toUpperCase();
					}) + " - " + type;
				
				type = key; //set the new type as a simple type
			}
		}
		
		//continue preparing attributes
		is_null = primary_key ? false : is_null;
		unique = primary_key ? true : unique;
		
		var column_types_ignored_props = $.isPlainObject(DBTableTaskPropertyObj.column_types_ignored_props) ? DBTableTaskPropertyObj.column_types_ignored_props : {};
		var column_type_ignored_props = type && column_types_ignored_props.hasOwnProperty(type) && $.isArray(column_types_ignored_props[type]) ? column_types_ignored_props[type] : [];
		
		var is_length_disabled = !type || $.inArray("length", column_type_ignored_props) != -1;
		var is_null_disabled = type && $.inArray("null", column_type_ignored_props) != -1;
		var is_default_disabled = type && $.inArray("default", column_type_ignored_props) != -1;
		
		var html = '<li>'
					+ '<div class="header">'
						+ '<input type="text" class="simple_attr_name" value="' + name + '" placeHolder="attribute name" onBlur="DBTableTaskPropertyObj.onBlurSimpleAttributeInputBox(this)" />'
						+ '<select class="simple_attr_type" onChange="DBTableTaskPropertyObj.onChangeSimpleAttributeTypeSelectBox(this)">'
						   + '<option value="">Please choose a type</option>'
						   + '<option disabled></option>';
		
		if (column_simple_types_exists) {
			html += 			'<optgroup label="Simple Types">';
			
			for (var key in column_simple_types) {
				var label = column_simple_types[key]["label"] ? column_simple_types[key]["label"] : key;
				html += 			'<option value="' + key + '" ' + (key == type ? "selected" : "") + ' title="' + label + '">' + label + '</option>';
			}
			
			html += 			'</optgroup>'
						   + '<option disabled></option>';
		}
		
		var column_simple_custom_types_exists = !$.isEmptyObject(column_simple_custom_types);
		
		if (column_simple_types_exists || column_simple_custom_types_exists) 
			html += 			'<optgroup label="Native Types">';
		
		for (var key in types) 
			html += 				'<option value="' + key + '" ' + (key == type ? "selected" : "") + ' title="' + types[key] + '">' + types[key] + '</option>';
		
		if (column_simple_types_exists || column_simple_custom_types_exists) 
			html +=  			'</optgroup>';
		
		if (column_simple_custom_types_exists) {
			html += 			'<option disabled></option>'
						   + '<optgroup label="Other Types - created dynamically">';
			
			for (var key in column_simple_custom_types) {
				var label = column_simple_custom_types[key]["label"] ? column_simple_custom_types[key]["label"] : key;
				html += 			'<option value="' + key + '" ' + (key == type ? "selected" : "") + ' title="' + label + '">' + label + '</option>';
			}
			
			html += 			'</optgroup>';
		}
		
		if (type && !types.hasOwnProperty(type) && !column_simple_types.hasOwnProperty(type) && !column_simple_custom_types.hasOwnProperty(type))
			html += 			'<option value="' + type + '" title="' + type + '">' + type + ' - NON DEFAULT</option>';
		
		html +=			  '</select>'
						+ '<a class="icon maximize" onClick="DBTableTaskPropertyObj.toggleSimpleAttributeProps(this)" title="Toggle other Properties">Toggle</a>'
						+ '<a class="icon move_up" onClick="DBTableTaskPropertyObj.moveUpSimpleAttribute(this)">move up</a>'
						+ '<a class="icon move_down" onClick="DBTableTaskPropertyObj.moveDownSimpleAttribute(this)">move down</a>'
						+ '<a class="icon delete" onClick="DBTableTaskPropertyObj.removeSimpleAttributeProps(this)" title="Remove this attribute">Remove</a>'
					+ '</div>'
					+ '<ul>'
						+ '<li' + ($.inArray("length", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '>'
							+ '<label>Length:</label>'
							+ '<input type="text" class="simple_attr_length" value="' + length + '" ' + (is_length_disabled ? 'disabled="disabled"' : '') + ' />'
						+ '</li>'
						+ '<li' + ($.inArray("null", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '>'
							+ '<label>Is Mandatory:</label>'
							+ '<input type="checkbox" class="simple_attr_not_null" ' + (!is_null ? 'checked="checked"' : '') + ' value="1" ' + (is_null_disabled ? 'disabled="disabled"' : '') + ' />'
						+ '</li>'
						+ '<li' + ($.inArray("default", column_types_hidden_props) != -1 ? ' style="display:none;"' : '') + '>'
							+ '<label>Default Value:</label>'
							+ '<input type="checkbox" class="simple_attr_has_default" ' + (has_default ? 'checked="checked"' : '') + ' value="1" ' + (is_default_disabled ? 'disabled="disabled"' : '') + ' onClick="DBTableTaskPropertyObj.onClickSimpleAttributeHasDefaultCheckBox(this)" title="Enable/Disable Default value" />'
							+ '<input type="text" class="simple_attr_default" value="' + default_value + '" placeHolder="write default value" title="write default value" ' + (has_default && !is_default_disabled ? '' : 'disabled="disabled"') + ' />'
						+ '</li>'
					+ '</ul>'
					+ '<input type="hidden" class="simple_attr_primary_key" value="' + (primary_key ? '1' : '') + '" />'
					+ '<input type="hidden" class="simple_attr_unsigned" value="' + (unsigned ? '1' : '') + '" />'
					+ '<input type="hidden" class="simple_attr_unique" value="' + (unique ? '1' : '') + '" />'
					+ '<input type="hidden" class="simple_attr_auto_increment" value="' + (auto_increment ? '1' : '') + '" />'
					+ '<input type="hidden" class="simple_attr_extra" value="' + extra + '" />'
					+ '<input type="hidden" class="simple_attr_charset" value="' + charset + '" />'
					+ '<input type="hidden" class="simple_attr_collation" value="' + collation + '">'
					+ '<input type="hidden" class="simple_attr_comment" value="' + comment + '" />'
			+ '</li>';
		
		return html;
	},
	
	onBlurSimpleAttributeInputBox : function(elm) {
		this.onBlurTableAttributeInputBox(elm);
	},
	
	onClickSimpleAttributeHasDefaultCheckBox : function(elm) {
		var default_field = $(elm).parent().children('input.simple_attr_default');
		
		if(elm.checked)
			default_field.removeAttr('disabled');
		else
			default_field.attr('disabled', 'disabled').val('');
	},
	
	onChangeSimpleAttributeTypeSelectBox : function(elm) {
		var j_elm = $(elm);
		var value = j_elm.val();
		var li = j_elm.parent().closest("li");
		var has_default_input = li.find("input.simple_attr_has_default");
		
		var column_simple_types = $.isPlainObject(DBTableTaskPropertyObj.column_simple_types) ? DBTableTaskPropertyObj.column_simple_types : {};
		var column_simple_custom_types = $.isPlainObject(DBTableTaskPropertyObj.column_simple_custom_types) ? DBTableTaskPropertyObj.column_simple_custom_types : {};
		
		var simple_props = value && column_simple_types.hasOwnProperty(value) ? column_simple_types[value] : (
			value && column_simple_custom_types.hasOwnProperty(value) ? column_simple_custom_types[value] : null
		);
		
		//If type is simple type and an auto_increment, check if it is the only field with the auto_increment property
		if (simple_props) { 
			var is_auto_increment = (simple_props.hasOwnProperty("auto_increment") && simple_props["auto_increment"]) || (simple_props.hasOwnProperty("extra") && typeof simple_props["extra"] == "string" && simple_props["extra"].toLowerCase().indexOf("auto_increment") != -1);
			
			if (is_auto_increment) {
				//check if exists more than 1 auto_increment field
				var selects = li.parent().find("select.simple_attr_auto_increment");
				var auto_increments_count = 0;
				
				for (var i = 0; i < selects.length; i++)
					if ($(selects[i]).val() == 1)
						auto_increments_count++;
				
				//if there is more than 1 auto_increment fields, than reset this field, bc there can only be 1 auto_increment field!
				if (auto_increments_count > 1) {
					j_elm.val("");
					value = "";
					
					myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("You cannot have more than one auto increment field! Please choose another type...");
				}
			}
		}
		
		//reset all hidden props - This must happens before we load the simple type props, if apply, bc if we change from simple_auto_primary_key to manual_primary_key or to simple_fk_auto_primary_key, the auto_increment field will still be 1. So we must reset it before we load the simple type props!
		li.find("input.simple_attr_primary_key").val(0);
		li.find("input.simple_attr_unique").val(0);
		li.find("input.simple_attr_unsigned").val(0);
		li.find("input.simple_attr_auto_increment").val(0);
		
		var extra_elm = li.find("input.simple_attr_extra");
		var extra = extra_elm.val();
		extra = typeof extra == "string" ? extra.replace(/(^|\s)auto_increment(\s|$)/gi, " ") : extra;
		extra_elm.val(extra);
		
		//If type is a simple type, update fields accordingly
		if (simple_props) {
			value = simple_props["type"]; //change the value with the real DB value, so we can use the value to prepare the ignored fields below.
			
			//reset shown props
			li.find("input.simple_attr_length").val("");
			li.find("input.simple_attr_not_null").removeAttr("checked").prop("checked", false);
			has_default_input.removeAttr("checked").prop("checked", false);
			li.find("input.simple_attr_default").val("");
			
			//set the new values for the shown and hidden props
			for (var prop_name in simple_props) {
				var prop_value = simple_props[prop_name];
				var input_prop_name = prop_name;
				
				if (prop_name == "null") {
					prop_value = !prop_value;
					input_prop_name = "not_null";
				}
				
				var input = li.find("input.simple_attr_" + input_prop_name).first();
				
				if (input.attr("type") == "checkbox") {
					if (prop_value)
						input.attr("checked", "checked").prop("checked", true);
					else
						input.removeAttr("checked").prop("checked", false);
				}
				else {
					input.val(prop_value);
					
					if (prop_name == "default" && prop_value != null && typeof prop_value != "undefined" && !has_default_input.is(":checked"))
						has_default_input.attr("checked", "checked").prop("checked", true);
				}
			}
			
			if (simple_props.hasOwnProperty("auto_increment") && simple_props["auto_increment"])
				extra_elm.val(extra + " auto_increment"); //TODO: Maybe in the future remove this bc it shouldn't be needed, since we already have the .table_attr_auto_increment field.
		}
		
		//update the attribute type with the correct value if is serial
		DBTableTaskPropertyObj.prepareSimpleAttributeSerialType(li, value);
		value = j_elm.val(); //update the current value
		
		//prepare ignored fields according with attribute type
		var column_types_ignored_props = $.isPlainObject(DBTableTaskPropertyObj.column_types_ignored_props) ? DBTableTaskPropertyObj.column_types_ignored_props : {};
		var column_type_ignored_props = value && column_types_ignored_props.hasOwnProperty(value) && $.isArray(column_types_ignored_props[value]) ? column_types_ignored_props[value] : [];
		
		li.find("input, select").removeAttr('disabled');
		
		if (!value) {
			column_type_ignored_props.push("length");
		}
		
		for (var i = 0; i < column_type_ignored_props.length; i++) {
			var field_name = column_type_ignored_props[i];
			
			if (field_name == "null")
				field_name = "not_null";
			
			var field = li.find(".simple_attr_" + field_name);
			field.attr('disabled', 'disabled');
			
			if (field.is("input[type=text]"))
				field.val('');
			else if (field.is("input[type=checkbox]"))
				field.removeAttr("checked").prop("checked", false); 
		}
		
		//disable or enable default field if has_default is or not selected
		DBTableTaskPropertyObj.onClickSimpleAttributeHasDefaultCheckBox(has_default_input[0]);
	},
	
	prepareSimpleAttributeSerialType : function(li, type) {
		/*Do not uncomment this bc we want to be able to choose the serial types in the diagrams. Postgres uses the serial types.
		if (DBTableTaskPropertyObj.column_serial_types && DBTableTaskPropertyObj.column_serial_types.hasOwnProperty(type) && $.isPlainObject(DBTableTaskPropertyObj.column_serial_types[type])) {
			var props = DBTableTaskPropertyObj.column_serial_types[type];
			
			for (var k in props) {
				var v = props[k];
				var input = li.find('.simple_attr_' + k);
				
				if (input[0]) {
					var input_type = input.attr("type");
					
					if (input_type == "checkbox") {
						if (v)
							input.attr("checked", "checked").prop("checked", true); 
						else
							input.removeAttr("checked").prop("checked", false); 
					}
					else if (input.is("select"))
						input.val(v);
					else if (v) {
						var text = input.val();
						text = typeof text != "undefined" ? "" + text : "";
						var parts = ("" + v).split(" ");
						
						for (var i = 0; i < parts.length; i++)
							if (text.toLowerCase().indexOf(parts[i].toLowerCase()) == -1) 
								text += (text ? " " : "") + parts[i];
						
						input.val(text);
					}
				}
			}
		}*/
	},
	
	addSimpleAttribute : function(elm) {
		var html = DBTableTaskPropertyObj.getSimpleAttributeHtml();
		
		var ul = $(elm).parent().closest(".simple_attributes").children("ul");
		ul.append(html);
		
		ul.children(".no_simple_attributes").hide();
	},
	
	toggleSimpleAttributeProps : function(elm) {
		elm = $(elm);
		var ul = elm.parent().closest("li").children("ul");
		
		if (ul.css("display") != "none")
			elm.removeClass("minimize").addClass("maximize");
		else
			elm.removeClass("maximize").addClass("minimize");
		
		ul.toggle("slow");
	},
	
	removeSimpleAttributeProps : function(elm) {
		elm = $(elm);
		var li = elm.parent().closest("li");
		var ul = li.parent();
		
		li.remove();
		
		if (ul.children(":not(.no_simple_attributes)").length > 0)
			ul.children(".no_simple_attributes").show();
		
	},
	
	moveUpSimpleAttribute : function(elm) {
		this.moveUpTableAttribute(elm);
	},
	
	moveDownSimpleAttribute : function(elm) {
		this.moveDownTableAttribute(elm);
	},
	/** END: TASK METHODS - SIMPLE UI **/
	/** END: TASK METHODS **/
	
	/** START: CONNECTION METHODS **/
	initSelectedConnectionPropertiesData : function(connection) {
		//console.debug(myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[connection.sourceId]);
		//console.debug(myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[connection.targetId]);
		
		var relationship = connection.getParameter("connection_exit_overlay");
		
		var source_table = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getTaskLabelByTaskId(connection.sourceId);
		var target_table = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getTaskLabelByTaskId(connection.targetId);
		
		var source_attributes = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[connection.sourceId] && myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[connection.sourceId].table_attr_names ? myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[connection.sourceId].table_attr_names : [];
		
		var target_attributes = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[connection.targetId] && myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[connection.targetId].table_attr_names ? myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[connection.targetId].table_attr_names : [];
		
		//the first time we load the task proeprties from a file, the source_attributes is an array, but when we save new properties from the selected_task_properties panel, the source_attributes becomes an object. So we need always to do the following:
		var new_source_attributes = new Array();
		if (source_attributes)
			$.each(source_attributes, function(i, source_attribute) {
				new_source_attributes.push(source_attribute);
			});
		
		var new_target_attributes = new Array();
		if (target_attributes)
			$.each(target_attributes, function(i, target_attribute) {
				new_target_attributes.push(target_attribute);
			});
		
		DBTableTaskPropertyObj.selected_connection_properties_data = {
			source_table: source_table ? source_table : "",
			source_attributes: new_source_attributes,
			target_table: target_table ? target_table : "",
			target_attributes: new_target_attributes,
			relationship: relationship
		};
	},
	
	onLoadConnectionProperties : function(properties_html_elm, connection, connection_property_values) {
		//console.debug(properties_html_elm);
		//console.debug(connection);
		//console.debug(connection_property_values);
		
		//PREPARE CONNECTION PROPERTIES DATA
		DBTableTaskPropertyObj.initSelectedConnectionPropertiesData(connection);
		
		properties_html_elm.find('.db_table_connection_html').hide();
		
		var overlays = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.available_connection_overlays_type;
		if (overlays) {
			//PREPARE HTML
			properties_html_elm.find('.db_table_connection_html .table_attrs').html("");
			properties_html_elm.find('.db_table_connection_html').show();
			
			var properties_data = DBTableTaskPropertyObj.selected_connection_properties_data;
			
			var options = '';
			for (var i = 0; i < overlays.length; i++) {
				options += '<option>' + overlays[i] + '</option>';
			}
			properties_html_elm.find('.db_table_connection_html .relationship_props .relationship select').html(options);
			properties_html_elm.find('.db_table_connection_html .relationship_props .relationship select').val(properties_data.relationship);
			
			properties_html_elm.find('.db_table_connection_html .relationship_props .source').html(properties_data.source_table);
			properties_html_elm.find('.db_table_connection_html .relationship_props .target').html(properties_data.target_table);
			
			properties_html_elm.find('.db_table_connection_html th.source_column').html(properties_data.source_table);
			properties_html_elm.find('.db_table_connection_html th.target_column').html(properties_data.target_table);
			
			var html;
		
			if (!connection_property_values || !connection_property_values.source_columns || connection_property_values.source_columns.length == 0) {
				html = DBTableTaskPropertyObj.getTableForeignKeyHtml();
			}
			else {
				if (!$.isArray(connection_property_values.source_columns) && !$.isPlainObject(connection_property_values.source_columns)) {
					connection_property_values.source_columns = [ connection_property_values.source_columns ];
					connection_property_values.target_columns = [ connection_property_values.target_columns ];
				}
			
				html = "";
			
				$.each(connection_property_values.source_columns, function(i, connection_property_value) {
					var data = {
						source_column: connection_property_values.source_columns[i],
						target_column: connection_property_values.target_columns[i]
					};
			
					html += DBTableTaskPropertyObj.getTableForeignKeyHtml(data);
				});
			}
		
			if (!html) {
				myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("Error: Couldn't detect this connection's properties. Please remove this connection, create a new one and try again...");
			}
			else {
				properties_html_elm.find(".db_table_connection_html .table_attrs").html(html);
			}
		}
	},
	
	onSubmitConnectionProperties : function(properties_html_elm, connection, connection_property_values) {
		//console.log(properties_html_elm);
		//console.log(connection);
		//console.log(connection_property_values);
		
		var properties_data = DBTableTaskPropertyObj.selected_connection_properties_data;
		var is_inner_connection = properties_data && properties_data.source_table && properties_data.source_table == properties_data.target_table;
		
		var overlay = properties_html_elm.find('.db_table_connection_html .relationship_props .relationship select').val();
		var source_columns = properties_html_elm.find(".db_table_connection_html .source_column .connection_property_field");
		var target_columns = properties_html_elm.find(".db_table_connection_html .target_column .connection_property_field");
		//console.log(overlay);
		
		var status = true;
		var error_message = "";
		
		for (var i = 0; i < source_columns.length; i++) {
			var source_column = $(source_columns[i]).val();
			var target_column = $(target_columns[i]).val();
		
			if (!source_column) {
				status = false;
				error_message = "Error: Child attribute name cannot be empty!";
				break;
			}
			else if (!target_column) {
				status = false;
				error_message = "Error: Parent attribute name cannot be empty!";
				break;
			}
			else if (is_inner_connection && source_column == target_column) {
				status = false;
				error_message = "Error: Child and Parent attributes cannot be the same!";
				break;
			}
		}
		
		if (status)
			myWFObj.getJsPlumbWorkFlow().jsPlumbContextMenu.setSelectedConnectionOverlay(overlay, {do_not_call_hide_properties : true});
		else 
			myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError(error_message);
		
		return status;
	},
	
	onCompleteConnectionProperties : function(properties_html_elm, connection, connection_property_values, status) {
		//console.log(properties_html_elm);
		//console.log(connection);
		//console.log(connection_property_values);
		//console.log(status);
		
		if (status) {
			DBTableTaskPropertyObj.selected_connection_properties_data = null;
			
			var new_connection = getConfiguredTaskTableConnection(connection);
			
			if (new_connection && connection.id != new_connection.id) {
				myWFObj.getJsPlumbWorkFlow().jsPlumbContextMenu.setContextMenuConnectionId(new_connection.id);
				connection = new_connection;
			}
			
			DBTableTaskPropertyObj.prepareTableForeignKeys(connection.sourceId);
			DBTableTaskPropertyObj.prepareTableForeignKeys(connection.targetId);
		}
	},
	
	onCancelConnectionProperties : function(properties_html_elm, connection, connection_property_values) {
		DBTableTaskPropertyObj.selected_connection_properties_data = null;
		
		return true;
	},
	
	onTableConnectionDrop : function(conn) {
		var status = onTableConnectionDrop(conn);
		
		if (status) {
			var source_task_property_values = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[conn.sourceId];
			var target_task_property_values = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[conn.targetId];
			
			//prepare source_task_property_values in case the task_property_values_table_attr_prop_names be a string instead of an array/object.
			if (source_task_property_values && source_task_property_values.table_attr_names && source_task_property_values.table_attr_names.length > 0)
				DBTableTaskPropertyObj.regularizeTaskPropertyValues(source_task_property_values);
			
			//prepare target_task_property_values in case the task_property_values_table_attr_prop_names be a string instead of an array/object.
			if (conn.sourceId != conn.targetId && target_task_property_values && target_task_property_values.table_attr_names && target_task_property_values.table_attr_names.length > 0) {
				DBTableTaskPropertyObj.regularizeTaskPropertyValues(target_task_property_values);
			}
			
			//finds the primary key for target table
			var target_pks = {};
			
			if (target_task_property_values && target_task_property_values.table_attr_primary_keys && source_task_property_values.table_attr_primary_keys.length > 0)
				$.each(target_task_property_values.table_attr_primary_keys, function(i, table_attr_primary_key) {
					if (DBTableTaskPropertyObj.checkIfTrue(table_attr_primary_key)) {
						var pk_name = target_task_property_values.table_attr_names[i];
						target_pks[pk_name] = i;
					}
				});
			
			if (!$.isEmptyObject(target_pks)) {
				var conn_attrs = {};
				var attrs_to_add = {};
				
				if (conn.sourceId == conn.targetId) { //find PKs for table and if attributes "parent_" + xxx don't exist, add them
					for (var pk_name in target_pks) {
						var exists = false;
						
						$.each(source_task_property_values.table_attr_names, function(i, table_attr_name) {
							if (table_attr_name == "parent_" + pk_name) {
								conn_attrs[table_attr_name] = pk_name;
								exists = true;
								return false;
							}
						});
						
						if (!exists) {
							conn_attrs["parent_" + pk_name] = pk_name;
							attrs_to_add["parent_" + pk_name] = pk_name;
						}
					}
				}
				else { //finds if PKs from one table exist in another, and if not, add them
					//check if pk attr exists in source_task_property_values.table_attr_names
					if (source_task_property_values && source_task_property_values.table_attr_names && source_task_property_values.table_attr_names.length > 0) {
						var target_table_name = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getTaskLabelByTaskId(conn.targetId);
						
						for (var pk_name in target_pks) {
							var exists = false;
							
							$.each(source_task_property_values.table_attr_names, function(i, table_attr_name) {
								if (table_attr_name == target_table_name + "_" + pk_name) {
									conn_attrs[table_attr_name] = pk_name;
									exists = true;
									return false;
								}
							});
							
							if (!exists)
								$.each(source_task_property_values.table_attr_names, function(i, table_attr_name) {
									if (table_attr_name == pk_name) {
										conn_attrs[table_attr_name] = pk_name;
										exists = true;
										return false;
									}
								});
							
							if (!exists) {
								conn_attrs[pk_name] = pk_name;
								attrs_to_add[pk_name] = pk_name;
							}
						}
					}
					else
						for (var pk_name in target_pks) {
							conn_attrs[pk_name] = pk_name;
							attrs_to_add[pk_name] = pk_name;
						}
				}
				
				if (!$.isEmptyObject(attrs_to_add)) {
					//if source_task_property_values is null, sets it an empty object 
					if (!source_task_property_values)
						myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[conn.sourceId] = source_task_property_values = {};
					
					//if source_task_property_values.table_attr_names is null, sets to it an empty array
					if (!$.isArray(source_task_property_values.table_attr_names) && !$.isPlainObject(source_task_property_values.table_attr_names))
						for (var i = 0; i < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; i++) {
							var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[i];
							source_task_property_values["table_attr_" + prop_name + "s"] = [];
						}
					
					//if source_task_property_values is a plain object, gets the maximum index
					var max_index = -1;
					
					if ($.isPlainObject(source_task_property_values.table_attr_names))
						for (var i in source_task_property_values.table_attr_names)
							if (i > max_index)
								max_index = i;
					
					max_index++;
					
					//add attributes to source_task_property_values
					for (var src_attr_name in attrs_to_add) {
						var trg_attr_name = attrs_to_add[src_attr_name];
						var index = target_pks[trg_attr_name];
						
						for (var i = 0; i < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; i++) {
							var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[i];
							var prop_value = target_task_property_values["table_attr_" + prop_name + "s"][index];
							
							if (prop_name == "name")
								prop_value = src_attr_name;
							else if (prop_name == "primary_key" || prop_name == "auto_increment" || prop_name == "unique")
								prop_value = 0;
							else if (prop_name == "extra" && prop_value && ("" + prop_value).toLowerCase().indexOf("auto_increment") != -1)
								prop_value = ("" + prop_value).replace(/auto_increment/g, "");
							
							if ($.isArray(source_task_property_values["table_attr_" + prop_name + "s"]))
								source_task_property_values["table_attr_" + prop_name + "s"].push(prop_value);
							else
								source_task_property_values["table_attr_" + prop_name + "s"][max_index] = prop_value;
						}
					}
				}
				
				//add connection properties with correspondent attributes
				var props = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.connections_properties[conn.connection.id];
				
				if (!props)
					myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.connections_properties[conn.connection.id] = props = {};
				
				if (!props.source_columns) {
					props.source_columns = [];
					props.target_columns = [];
				}
				else	if (!$.isArray(props.source_columns) && !$.isPlainObject(props.source_columns)) {
					props.source_columns = [ props.source_columns ];
					props.target_columns = [ props.target_columns ];
				}
				
				//if props.source_columns is a plain object, gets the maximum index
				var max_index = -1;
				
				if ($.isPlainObject(props.source_columns))
					for (var i in props.source_columns)
						if (i > max_index)
							max_index = i;
				
				max_index++;
				
				//sets the pk_name to connection: source and target tables
				for (var src_attr_name in conn_attrs) {
					var trg_attr_name = conn_attrs[src_attr_name];
					
					if ($.isArray(props.source_columns)) {
						//only adds if not exists yet
						if ($.inArray(src_attr_name, props.source_columns) == -1 || $.inArray(trg_attr_name, props.target_columns) == -1) {
							props.source_columns.push(src_attr_name);
							props.target_columns.push(trg_attr_name);
						}
					}
					else {
						//only adds if not exists yet
						for (var idx in props.source_columns)
							if (props.source_columns[idx] == src_attr_name && props.target_columns[idx] == trg_attr_name) {
								props.source_columns[max_index] = src_attr_name;
								props.target_columns[max_index] = trg_attr_name;
								max_index++;
								break;
							}
					}
				}
				
				//refresh tasks and connection with new configurations
				myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.changeConnectionOverlayType(conn.connection, "Many To One");
				DBTableTaskPropertyObj.prepareTableAttributes(conn.sourceId, source_task_property_values);
				DBTableTaskPropertyObj.prepareTableForeignKeys(conn.sourceId);
				DBTableTaskPropertyObj.prepareTableForeignKeys(conn.targetId);
			}
		}
		
		if (DBTableTaskPropertyObj.show_properties_on_connection_drop)
			myWFObj.getJsPlumbWorkFlow().jsPlumbProperty.showConnectionProperties(conn.connection.id);
		
		return status;
	},
	
	onSuccessConnectionDeletion : function(connection) {
		DBTableTaskPropertyObj.prepareTableForeignKeys(connection.sourceId);
		DBTableTaskPropertyObj.prepareTableForeignKeys(connection.targetId);
	},
	
	getTableForeignKeyHtml : function(data) {
		var properties_source_attributes = [], properties_target_attributes = [];
		
		var properties_data = DBTableTaskPropertyObj.selected_connection_properties_data;
		
		if (properties_data) {
			properties_source_attributes = properties_data.source_attributes ? properties_data.source_attributes : [];
			properties_target_attributes = properties_data.target_attributes ? properties_data.target_attributes : [];
		}
		
		if (properties_source_attributes.length > 0 && properties_target_attributes.length > 0) {
			var source_column = "", target_column = "";
		
			if (data) {
				source_column = data.source_column ? data.source_column : "";
				target_column = data.target_column ? data.target_column : "";
			}
			
			var html = '<tr>'
				+ '<td class="source_column"><select class="connection_property_field" name="source_columns[]"><option></option>';
			for (var i = 0; i < properties_source_attributes.length; i++)
				html += '<option ' + (properties_source_attributes[i] == source_column ? "selected" : "") + '>' + properties_source_attributes[i] + '</option>';
			
			html +=	'</select></td>'
				+ '<td class="target_column"><select class="connection_property_field" name="target_columns[]"><option></option>';
			for (var i = 0; i < properties_target_attributes.length; i++)
				html += '<option ' + (properties_target_attributes[i] == target_column ? "selected" : "") + '>' + properties_target_attributes[i] + '</option>';
			
			html += '</select></td>'
				+ '<td class="table_attr_icons">'
					+ '	<a class="icon move_up" onClick="DBTableTaskPropertyObj.moveUpTableForeignKey(this)">move up</a>'
					+ '	<a class="icon move_down" onClick="DBTableTaskPropertyObj.moveDownTableForeignKey(this)">move down</a>'
					+ '	<a class="icon delete" onClick="DBTableTaskPropertyObj.removeTableForeignKey(this)">remove</a>'
				+ '</td>'
			+ '</tr>';
				
		
			return html;
		}
	},
	
	addTableForeignKey : function() {
		var html = DBTableTaskPropertyObj.getTableForeignKeyHtml();
		
		if (!html)
			myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("Error: Couldn't detect this connection's properties. Please remove this connection, create a new one and try again...");
		else
			$("#" + myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.main_tasks_flow_obj_id + " .db_table_connection_html .table_attrs").append(html);
	},
	
	removeTableForeignKey : function(elm) {
		this.removeTableAttribute(elm);
	},
	
	moveUpTableForeignKey : function(elm) {
		this.moveUpTableAttribute(elm);
	},
	
	moveDownTableForeignKey : function(elm) {
		this.moveDownTableAttribute(elm);
	},
	
	checkingTaskConnectionsPropertiesFromTaskProperties : function(task_id) {
		var task_property_values = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id];
		var table_attr_names = task_property_values && task_property_values["table_attr_names"] ? task_property_values["table_attr_names"] : [];
		
		//PREPARING SOURCE CONNECTIONS
		var connections = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getSourceConnections(task_id);
		var source_inconsistencies = this.checkingTaskConnectionsPropertiesFromTaskPropertiesAux(connections, table_attr_names, "source");
		
		//PREPARING TARGET CONNECTIONS
		var connections = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getTargetConnections(task_id);
		var target_inconsistencies = this.checkingTaskConnectionsPropertiesFromTaskPropertiesAux(connections, table_attr_names, "target");
		
		if (source_inconsistencies || target_inconsistencies)
			myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("The system detected some inconsistencies in some connections' properties for this table, but they were fixed and removed successfully.");
	},
	
	checkingTaskConnectionsPropertiesFromTaskPropertiesAux : function(connections, table_attr_names, type) {
		var inconsistencies = false;
		
		if ($.isPlainObject(table_attr_names)) {
			var arr = new Array();
			for (var i in table_attr_names)
				arr.push(table_attr_names[i]);
			table_attr_names = arr;
		}
		
		for (var i = 0; i < connections.length; i++) {
			var c = connections[i];
			
			var props = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.connections_properties[c.id];
			
			if (props) {
				if (!$.isArray(props.source_columns) && !$.isPlainObject(props.source_columns)) {
					props.source_columns = [ props.source_columns ];
					props.target_columns = [ props.target_columns ];
				}
				
				var new_props = {
					source_columns: [],
					target_columns: [],
				};
				
				$.each(props.source_columns, function(j, source_column) {
					var sc = props.source_columns[j];
					var tc = props.target_columns[j];
				
					var exists = (type == "source" && $.inArray(sc, table_attr_names) != -1) || (type == "target" && $.inArray(tc, table_attr_names) != -1);
				
					if (exists) {
						new_props["source_columns"].push(sc);
						new_props["target_columns"].push(tc);
					}
					else {
						inconsistencies = true;
					}
				});
				
				myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.connections_properties[c.id] = new_props.source_columns.length > 0 ? new_props : null;
			}
		}
		
		return inconsistencies;
	},
	/** END: CONNECTION METHODS **/
	
	checkIfTrue : function(value) {
		var v = typeof value == "string" ? value.toLowerCase() : "";
		
		return value && value != null && typeof value != "undefined" && value != 0 && v != "null" && v != "false" && v != "0" ? true : false;
	},
	
	regularizeTaskPropertyValues : function(task_property_values) {
		if (!$.isArray(task_property_values.table_attr_names) && !$.isPlainObject(task_property_values.table_attr_names)) {
			for (var i = 0; i < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; i++) {
				var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[i];
				task_property_values["table_attr_" + prop_name + "s"] = [ task_property_values["table_attr_" + prop_name + "s"] ];
			}
		}
		
		//I added the collation after, so there are some .xml files that don't contain this. So we need to add this, otherwise we get a js error.
		$.each(task_property_values.table_attr_names, function(i, table_attr_name) {
			for (var j = 0; j < DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.length; j++) {
				var prop_name = DBTableTaskPropertyObj.task_property_values_table_attr_prop_names[j];
				
				if (!task_property_values.hasOwnProperty("table_attr_" + prop_name + "s") || 
					(!$.isPlainObject(task_property_values["table_attr_" + prop_name + "s"]) && !$.isArray(task_property_values["table_attr_" + prop_name + "s"]))
				) 
					task_property_values["table_attr_" + prop_name + "s"] = {};
				
				if (typeof task_property_values["table_attr_" + prop_name + "s"][i] == "undefined") 
					task_property_values["table_attr_" + prop_name + "s"][i] = null;
			}
		});
	},
	
	toggleTableAndListView : function(elm) {
		var task_html_elm = $(elm).parent().closest('.db_table_task_html');
		
		if (task_html_elm.hasClass("attributes_list_shown"))
			this.convertListToTable(elm);
		else
			this.convertTableToList(elm);
	},
	
	convertTableToList : function(elm) {
		elm = $(elm);
		var task_html_elm = elm.parent().closest('.db_table_task_html');
		var table = task_html_elm.find("table");
		var ul = task_html_elm.find(".list_attributes > ul.list_attrs");
		var rows = table.find("tbody.table_attrs tr");
		var column_names = this.getTableColumnNames(table);
		
		ul.html("");
		
		for (var i = 0 ; i < rows.length; i++)
			this.convertTableRowToListItem(ul, $(rows[i]), column_names);
		
		task_html_elm.removeClass("attributes_table_shown").addClass("attributes_list_shown");
	},
	
	getTableColumnNames : function(table) {
		var column_names = [];
		var ths = table.find("thead tr th");
		
		for (var i = 0 ; i < ths.length; i++) {
			th = $(ths[i]);
			column_names.push( th.html() );
			
			if (th[0].hasAttribute("colspan")) {
				var length = parseInt( th.attr("colspan") );
				
				if ($.isNumeric(length) && length > 0)
					for (var j = 0; j < length - 1; j++)
						column_names.push( th.html() );
			}
		}
		
		return column_names;
	},
	
	convertTableRowToListItem : function(ul, row, column_names) {
		var columns = row.children("td");
		var li = document.createElement("LI");
		li = $(li);
		li.attr("class", row.attr("class") );
		ul.append(li);
		
		for (var i = 0 ; i < columns.length; i++) {
			column = $(columns[i]);
			var column_name = column_names[i];
			var div = document.createElement("DIV");
			div = $(div);
			
			div.attr("class", column.attr("class"));
			div.attr("style", column.attr("style"));
			
			if (!column.hasClass("table_attr_icons"))
				div.append('<label>' + column_name + ':</label>');
			
			div.append( column.children() );
			
			li.append(div);
		}
	},
	
	convertListToTable : function(elm) {
		elm = $(elm);
		var task_html_elm = elm.parent().closest('.db_table_task_html');
		var tbody = task_html_elm.find("table tbody.table_attrs");
		var lis = task_html_elm.find(".list_attributes > ul.list_attrs > li");
		
		tbody.html("");
		
		for (var i = 0 ; i < lis.length; i++) {
			li = $(lis[i]);
			var columns = li.children("div");
			var tr = document.createElement("TR");
			tr = $(tr);
			tr.attr("class", li.attr("class") );
			tbody.append(tr);
			
			for (var j = 0 ; j < columns.length; j++) {
				column = $(columns[j]);
				var td = document.createElement("TD");
				td = $(td);
				
				td.attr("class", column.attr("class"));
				td.attr("style", column.attr("style"));
				
				if (!column.hasClass("table_attr_icons"))
					column.children("label").first().remove();
				
				td.append( column.children() );
				
				tr.append(td);
			}
		}
		
		task_html_elm.removeClass("attributes_list_shown").addClass("attributes_table_shown");
	},
};
