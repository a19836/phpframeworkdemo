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

var DBQueryTaskPropertyObj = {
	selected_connection_properties_data : null,
	old_connection_property_values : null,
	show_properties_on_conection_drop : false,
	
	on_click_checkbox : null,
	on_delete_table : null,
	on_complete_table_label : null,
	on_complete_connection_properties : null,
	on_complete_select_start_task : null,
	
	connection_exit_props : {
		color: "#000",
		id: "layer_exit",
		overlay: "No Arrows",
		type: "Straight"
	},
	
	/** START: TASK METHODS **/
	prepareTableAttributes : function(task_id, data, rand_number) {
		if (data) {
			var WF = myWFObj.getJsPlumbWorkFlow();
			var task = WF.jsPlumbTaskFlow.getTaskById(task_id);
			
			if (task[0]) {
				var table_name = data.table_name;
				
				WF.jsPlumbTaskFlow.getTaskLabelElement(task).html(table_name);//the label has now 2 span elements: 1 for the label and another for the delete icon
				
				onEditLabel(task_id);
				WF.jsPlumbTaskFlow.repaintTaskByTaskId(task_id);
		
				var attributes_elm = task.find(" > ." + WF.jsPlumbTaskFlow.task_eps_class_name + " .attributes");
				if (!attributes_elm[0]) {
					task.children("." + WF.jsPlumbTaskFlow.task_eps_class_name).append('<div class="attributes"></div>');
					attributes_elm = task.find(" > ." + WF.jsPlumbTaskFlow.task_eps_class_name + " .attributes");
				}
			
				var attr_names = data.table_attr_names;
			
				if (attr_names) {
					//PREPARE ATTRIBUTES
					var html_checks = "";
					var html_names = "";
					var count = 0;
					for (var attr_name in attr_names) {
						var checked = checkIfValueIsTrue(attr_names[attr_name]);
						attr_name = attr_name ? attr_name : "";
					
						html_names += '<div class="table_attr"><span class="check"></span><span class="name"><p>' + attr_name + '</p></span></div>';
					
						html_checks += '<div class="table_attr"><span class="check"><input type="checkbox" name="query_attributes[' + table_name +  '][' + attr_name + ']" value="1" ' + (checked ? "checked" : "") + ' attribute="' + attr_name + '" /></span></div>';
					
						count++;
					}
			
					task.find(" > ." + WF.jsPlumbTaskFlow.task_eps_class_name + " ." + WF.jsPlumbTaskFlow.task_ep_class_name).html(html_names);
					
					attributes_elm.html(html_checks);
					
					attributes_elm.find(".check input").click(function(originalEvent) {
						if (originalEvent && originalEvent.stopPropagation) originalEvent.stopPropagation();//bc checkbox is inside of eps and task, we should avoid the click of the eps and task to be trigger
						
						if (typeof DBQueryTaskPropertyObj.on_click_checkbox == "function") {
							DBQueryTaskPropertyObj.on_click_checkbox(this, WF, rand_number);
						}
					});
					
					var label_height = parseInt( task.children("." + WF.jsPlumbTaskFlow.task_label_class_name).height() );
					var min_height = parseInt( task.css("min-height") );
					
					var height = count * 20 + label_height; 
					height = height < min_height ? min_height : height;
					
					task.css("height", height + "px");
					
					resizeTableTaskBasedOnAttributes(task_id);
				}
			}
		}
	},
	
	deleteTable : function(task_id, to_confirm) {
		var task = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getTaskById(task_id);
		
		if (task[0]) {
			var status = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.deleteTask(task_id, {confirm: to_confirm});
			
			if (status) {
				if (typeof DBQueryTaskPropertyObj.on_delete_table == "function") {
					DBQueryTaskPropertyObj.on_delete_table(task, myWFObj.getJsPlumbWorkFlow());
				}
			}
			else {
				myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("Error: Couldn't delete the selected table.\nPlease try again...");
			}
		}
	},
	
	onStartLabel : function(task_id) {
		var WF = myWFObj.getJsPlumbWorkFlow();
	
		var label = WF.jsPlumbTaskFlow.getTaskLabelByTaskId(task_id);
		var parts = label.split(" ");
		var table_name = parts[0];
		var alias = parts[1] ? parts[1] : "";
		
		var span = WF.jsPlumbTaskFlow.getTaskLabelElementByTaskId(task_id);
		span.html(alias);
		span.attr("table_name", table_name);
		span.attr("table_alias", alias);
		
		return true;
	},
	
	onCheckLabel : function(label_obj, task_id) {
		var WF = myWFObj.getJsPlumbWorkFlow();
		var span = WF.jsPlumbTaskFlow.getTaskLabelElementByTaskId(task_id);
		var table_name = span.attr("table_name");
		
		if (label_obj.label.trim() == "")
			return isTaskLabelRepeated({label: table_name}, task_id) == false;
		else if (isTaskTableLabelValid(label_obj, task_id)) {
			if (label_obj.label == "as") {
				WF.jsPlumbStatusMessage.showError("Invalid Label! Please try again...");
				return false;
			}
			
			var new_label = table_name + (label_obj.label.trim() ? " " + label_obj.label.replace(/[ -]+/g, "_").trim() : "");
			
			return isTaskLabelRepeated({label: new_label}, task_id) == false;
		}
		return false;
	},
	
	onCompleteLabel : function(task_id) {
		var WF = myWFObj.getJsPlumbWorkFlow();
		var span = WF.jsPlumbTaskFlow.getTaskLabelElementByTaskId(task_id);
		var table_name = span.attr("table_name");
		
		var new_alias = span.text();
		var old_alias = span.attr("table_alias");
		
		var new_label = table_name + (new_alias.trim() ? " " + new_alias.replace(/[ -]+/g, "_").trim() : "");
		var old_label = table_name + (old_alias ? " " + old_alias : "");
		
		span.html(new_label);
		span.removeAttr("table_name");
		span.removeAttr("table_alias");
		
		onEditLabel(task_id);
		WF.jsPlumbTaskFlow.repaintTaskByTaskId(task_id);
		
		if (old_label != new_label) {
			if (typeof DBQueryTaskPropertyObj.on_complete_table_label == "function") {
				DBQueryTaskPropertyObj.on_complete_table_label(WF, task_id, old_label, new_label);
			}
		}
	},
	
	onTaskCreation : function(task_id) {
		var WF = myWFObj.getJsPlumbWorkFlow();
		var task = WF.jsPlumbTaskFlow.getTaskById(task_id);
		
		if (task[0]) {
			task.children("." + WF.jsPlumbTaskFlow.task_label_class_name).append('<i class="icon delete"></i></i>');//do not use span
			
			task.find(" > ." + WF.jsPlumbTaskFlow.task_label_class_name + " .delete").click(function(){
				myWFObj.setJsPlumbWorkFlow(WF);
				DBQueryTaskPropertyObj.deleteTable(task.attr("id"), true);
			});
		}
	},
	/** END: TASK METHODS **/
	
	/** START: CONNECTION METHODS **/
	initSelectedConnectionPropertiesData : function(connection) {
		var WF = myWFObj.getJsPlumbWorkFlow();
		var source_table = WF.jsPlumbTaskFlow.getTaskLabelByTaskId(connection.sourceId);
		var target_table = WF.jsPlumbTaskFlow.getTaskLabelByTaskId(connection.targetId);
		
		var attrs = $("#" + WF.jsPlumbTaskFlow.main_tasks_flow_obj_id + " #" + connection.sourceId + " > ." + WF.jsPlumbTaskFlow.task_eps_class_name + " .attributes .table_attr .check input");
		
		var source_attributes = [];
		for (var i = 0; i < attrs.length; i++) {
			var attr = $(attrs[i]).attr("attribute");
			
			if (attr != "*") {
				source_attributes.push(attr);
			}
		}
		
		attrs = $("#" + WF.jsPlumbTaskFlow.main_tasks_flow_obj_id + " #" + connection.targetId + " > ." + WF.jsPlumbTaskFlow.task_eps_class_name + " .attributes .table_attr .check input");
		
		var target_attributes = [];
		for (var i = 0; i < attrs.length; i++) {
			var attr = $(attrs[i]).attr("attribute");
			
			if (attr != "*") {
				target_attributes.push(attr);
			}
		}
		
		DBQueryTaskPropertyObj.selected_connection_properties_data = {
			source_table: source_table ? source_table : "",
			source_attributes: source_attributes,
			target_table: target_table ? target_table : "",
			target_attributes: target_attributes
		};
	},
	
	onLoadConnectionProperties : function(properties_html_elm, connection, connection_property_values) {
		//console.debug(properties_html_elm);
		//console.debug(connection);
		//console.debug(connection_property_values);
		
		//PREPARE CONNECTION PROPERTIES DATA
		DBQueryTaskPropertyObj.old_connection_property_values = connection_property_values;
		DBQueryTaskPropertyObj.initSelectedConnectionPropertiesData(connection);
		
		var properties_data = DBQueryTaskPropertyObj.selected_connection_properties_data;
		
		properties_html_elm.find('.db_table_connection_html .header .tables_join').val(connection_property_values.tables_join);
		properties_html_elm.find('.db_table_connection_html .header .source_table').val(properties_data.source_table);
		properties_html_elm.find('.db_table_connection_html .header .target_table').val(properties_data.target_table);
		
		properties_html_elm.find('.db_table_connection_html th.source_column').html(properties_data.source_table);
		properties_html_elm.find('.db_table_connection_html th.target_column').html(properties_data.target_table);
		
		var attributes = connection_property_values.attributes;
		
		var html;
		
		if (!connection_property_values || !connection_property_values.source_columns || connection_property_values.source_columns.length == 0) {
			html = DBQueryTaskPropertyObj.getTableJoinKey();
		}
		else {
			if (!$.isArray(connection_property_values.source_columns) && !$.isPlainObject(connection_property_values.source_columns)) {
				connection_property_values.source_columns = [ connection_property_values.source_columns ];
				connection_property_values.target_columns = [ connection_property_values.target_columns ];
				connection_property_values.column_values = [ connection_property_values.column_values ];
				connection_property_values.operators = [ connection_property_values.operators ];
			}
			
			html = "";
			
			for (var i in connection_property_values.source_columns) {
				if (i >= 0) {
					var data = {
						source_column: connection_property_values.source_columns[i],
						target_column: connection_property_values.target_columns[i],
						column_value: connection_property_values.column_values[i],
						operator: connection_property_values.operators[i]
					};
			
					html += DBQueryTaskPropertyObj.getTableJoinKey(data);
				}
			}
		}
		
		if (!html) {
			myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("Error: Couldn't detect this connection's properties. Please remove this connection, create a new one and try again...");
		}
		else {
			properties_html_elm.find(".db_table_connection_html .table_attrs").html(html);
		}
	},
	
	onSubmitConnectionProperties : function(properties_html_elm, connection, connection_property_values) {
		//console.debug(properties_html_elm);
		//console.debug(connection);
		//console.debug(connection_property_values);
		
		var properties_data = DBQueryTaskPropertyObj.selected_connection_properties_data;
		
		var source_columns = properties_html_elm.find(".db_table_connection_html .source_column .connection_property_field");
		var target_columns = properties_html_elm.find(".db_table_connection_html .target_column .connection_property_field");
		
		var status = true;
		var error_message = "";
		
		for (var i = 0; i < source_columns.length; i++) {
			var source_column = $(source_columns[i]).val();
			var target_column = $(target_columns[i]).val();
		
			if (!source_column && !target_column) {
				status = false;
				error_message = "Error: Parent and child attribute names cannot be empty!";
				break;
			}
		}
		
		if (!status) {
			myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError(error_message);
		}
		
		return status;
	},
	
	onCompleteConnectionProperties : function(properties_html_elm, connection, connection_property_values, status) {
		if (status) {
			if (typeof DBQueryTaskPropertyObj.on_complete_connection_properties == "function") {
				DBQueryTaskPropertyObj.on_complete_connection_properties(myWFObj.getJsPlumbWorkFlow(), connection, DBQueryTaskPropertyObj.old_connection_property_values, connection_property_values);
			}
			
			DBQueryTaskPropertyObj.selected_connection_properties_data = null;
		}
	},
	
	onSuccessConnectionDeletion : function(connection) {
		var props = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.connections_properties[connection.id];
		
		if (typeof DBQueryTaskPropertyObj.on_complete_connection_properties == "function") {
			DBQueryTaskPropertyObj.on_complete_connection_properties(myWFObj.getJsPlumbWorkFlow(), connection, props);
		}
		
		return true;
	},
	
	onSuccessConnectionDrag : function(conn) {
		if (!invalidateTaskConnectionIfItIsToItSelf(conn)) {
			myWFObj.getJsPlumbWorkFlow().jsPlumbProperty.hideSelectedConnectionProperties();
			
			return false;
		}
		
		return true;
	},
	
	onSuccessConnectionDrop : function(conn) {
		if (conn.sourceId == conn.targetId) {
			myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("Invalid connection. You cannot create self-connections.\nIf you wish to create a connection between the same tables, please create another task with the table name but with a different alias.");
			return false;
		}
		
		//checks if already exists the same connection.
		var connections = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getSourceConnections(conn.sourceId);
	
		var exists = false;
		for (var i = 0; i < connections.length; i++) {
			var c = connections[i];
			
			if (c.id != conn.connection.id && c.sourceId == conn.sourceId && c.targetId == conn.targetId) {
				exists = true;
				break;
			}
		}
		
		if (exists) {
			myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("Already exists a connection with these tables.\nYou cannot create repeated connections.\nPlease use the existent connection.");
			return false;
		}
		
		onTableConnectionDrop(conn);
		
		if (DBQueryTaskPropertyObj.show_properties_on_conection_drop) {
			myWFObj.getJsPlumbWorkFlow().jsPlumbProperty.showConnectionProperties(conn.connection.id);
		}
		
		return true;
	},
	
	addTableJoinKey : function() {
		var html = DBQueryTaskPropertyObj.getTableJoinKey();
		
		if (!html) {
			myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("Error: Couldn't detect this connection's properties. Please remove this connection, create a new one and try again...");
		}
		else {
			$("#" + myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.main_tasks_flow_obj_id + " .db_table_connection_html .table_attrs").append(html);
		}
	},
	
	removeTableJoinKey : function(elm) {
		$(elm).parent().parent().remove();
	},
	
	getTableJoinKey : function(data) {
		var properties_source_attributes = [];
		var properties_target_attributes = [];
		
		var properties_data = DBQueryTaskPropertyObj.selected_connection_properties_data;
		
		if (properties_data) {
			properties_source_attributes = properties_data.source_attributes ? properties_data.source_attributes : [];
			properties_target_attributes = properties_data.target_attributes ? properties_data.target_attributes : [];
		}
		
		if (properties_source_attributes.length > 0 && properties_target_attributes.length > 0) {
			var operators = ["=", "!=", ">", ">=", "<=", "like", "not like", "is", "not is"];
			
			var source_column = "", target_column = "", column_value = "", operator = "";
		
			if (data) {
				source_column = data.source_column ? data.source_column : "";
				target_column = data.target_column ? data.target_column : "";
				column_value = data.column_value ? data.column_value : "";
				operator = data.operator ? data.operator : "";
			}
			
			var html = '<tr>'
				+ '<td class="source_column"><select class="connection_property_field" name="source_columns[]"><option></option>';
			for (var j = 0; j < properties_source_attributes.length; j++) {
				html += '<option ' + (properties_source_attributes[j] == source_column ? "selected" : "") + '>' + properties_source_attributes[j] + '</option>';
			}
			html +=	'</select></td>'
				+ '<td class="operator"><select class="connection_property_field" name="operators[]">';
			for (var j = 0; j < operators.length; j++) {
				html += '<option ' + (operators[j] == operator ? "selected" : "") + '>' + operators[j] + '</option>';
			}
			html +=	'</select></td>'
				+ '<td class="target_column"><select class="connection_property_field" name="target_columns[]"><option></option>';
			for (var j = 0; j < properties_target_attributes.length; j++) {
				html += '<option ' + (properties_target_attributes[j] == target_column ? "selected" : "") + '>' + properties_target_attributes[j] + '</option>';
			}
			html += '</select></td>'
				+ '<td class="column_value"><input class="connection_property_field" name="column_values[]" value="' + column_value + '" /></td>'
				+ '<td class="table_attr_icons"><a class="icon delete" onClick="DBQueryTaskPropertyObj.removeTableJoinKey(this)">remove</a></td>'
			+ '</tr>';
			
			return html;
		}
	},
	/** END: CONNECTION METHODS **/
	
	/** END: MENUS METHODS **/
	onShowTaskMenu : function(task_id, j_task, task_context_menu) {
		task_context_menu.find(".start_task").off().attr("onclick", "return DBQueryTaskPropertyObj.setSelectedStartTask();").children("a").html("Is Main Table");
	},
	
	setSelectedStartTask : function() {
		var WF = myWFObj.getJsPlumbWorkFlow();
		WF.jsPlumbContextMenu.hideContextMenus();
		
		var task_id = WF.jsPlumbContextMenu.getContextMenuTaskId();
		this.setStartTaskById(task_id);
		
		return false;
	},
	
	setStartTaskById : function(task_id) {
		var WF = myWFObj.getJsPlumbWorkFlow();
		var tasks = WF.jsPlumbTaskFlow.getAllTasks();
		var j_task = WF.jsPlumbTaskFlow.getTaskById(task_id);
		
		for (var i = 0, l = tasks.length; i < l; i++) {
			var task = $(tasks[i]);
			task.removeAttr("is_start_task");
			task.removeClass(WF.jsPlumbTaskFlow.start_task_class_name);
		}
		
		if (j_task[0]) {
			j_task.attr("is_start_task", 1);
			j_task.addClass(WF.jsPlumbTaskFlow.start_task_class_name);
		}
		
		this.onCompleteSelectStartTask(task_id, j_task);
	},
	
	onCompleteSelectStartTask : function(task_id, j_task) {
		if (typeof DBQueryTaskPropertyObj.on_complete_select_start_task == "function")
			DBQueryTaskPropertyObj.on_complete_select_start_task(myWFObj.getJsPlumbWorkFlow(), task_id, j_task);
	},
	/** END: MENUS METHODS **/
};
