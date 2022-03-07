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

if (typeof is_global_programming_common_file_already_included == "undefined") {
	var is_global_programming_common_file_already_included = 1;
	
	var ProgrammingTaskUtil = {
		variables_in_workflow : {},
		
		on_programming_task_choose_created_variable_callback : null,
		on_programming_task_choose_object_property_callback : null,
		on_programming_task_choose_object_method_callback : null,
		on_programming_task_choose_function_callback : null,
		on_programming_task_choose_class_name_callback : null,
		on_programming_task_choose_file_path_callback : null,
		on_programming_task_choose_page_url_callback : null,
		on_programming_task_choose_image_url_callback : null,
		
		connections_to_add_after_deletion: null,
		
		createTaskLabelField : function(properties_html_elm, task_id) {
			var label = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getTaskLabelByTaskId(task_id);
			label = label ? label.replace(/"/g, "&quot;") : "";
			
			properties_html_elm.find(".properties_task_id").html('<input type="text" value="' + label + '" old_value="' + label + '" />');
		},
		
		onEditLabel : function(task_id) {
			onEditLabel(task_id);
			
			updateTaskLabelInShownTaskProperties(task_id, ".properties_task_id input");
			
			myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.repaintTaskByTaskId(task_id);
			
			return true;
		},
		
		saveTaskLabelField : function(properties_html_elm, task_id) {
			var old_label = properties_html_elm.find(".properties_task_id input").attr("old_value");
			var new_label = properties_html_elm.find(".properties_task_id input").val();
			
			if (new_label && old_label != new_label) {
				myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getTaskLabelElementByTaskId(task_id).html(new_label);
				
				onEditLabel(task_id);
			}
		},
		
		saveNewVariableInWorkflowAccordingWithType : function(task_html_elm, class_name) {
			var type = task_html_elm.find(".result_var_type select").val();
		
			class_name = class_name ? class_name : null;
		
			if (type == "variable") {
				var var_name = task_html_elm.find(".result_var_name input").val();
				var_name = var_name ? var_name.replace(/^\s+/, "").replace(/\s+$/, "") : "";//trim;
				
				if (var_name) {
					var_name = var_name.charAt(0) == '$' ? var_name : '$' + var_name;
					this.variables_in_workflow[var_name] = {"class_name" : class_name};
				}
			}
			else if (type == "obj_prop") {
				var obj_name = task_html_elm.find(".result_obj_name input").val();
				var prop_name = task_html_elm.find(".result_prop_name input").val();
				var is_static = task_html_elm.find(".result_static input").prop("checked");
				
				obj_name = obj_name ? obj_name.replace(/^\s+/, "").replace(/\s+$/, "") : "";//trim;
				prop_name = prop_name ? prop_name.replace(/^\s+/, "").replace(/\s+$/, "") : "";//trim;
				
				if (obj_name && prop_name) {
					var var_name = obj_name + (is_static ? "::" : "->") + prop_name;
					var_name = var_name.charAt(0) == '$' || is_static ? var_name : '$' + var_name;
					this.variables_in_workflow[var_name] = {"class_name" : class_name};
				}
			}
		},
		
		saveNewVariableInWorkflowAccordingWithTaskPropertiesValues : function(task_property_values, class_name) {
			if (task_property_values) {
				var result_var_name = task_property_values["result_var_name"];
				var result_obj_name = task_property_values["result_obj_name"];
				var result_prop_name = task_property_values["result_prop_name"];
				var result_static = task_property_values["result_static"];
				
				result_var_name = result_var_name ? result_var_name.replace(/^\s+/, "").replace(/\s+$/, "") : "";//trim;
				result_obj_name = result_obj_name ? result_obj_name.replace(/^\s+/, "").replace(/\s+$/, "") : "";//trim;
				result_prop_name = result_prop_name ? result_prop_name.replace(/^\s+/, "").replace(/\s+$/, "") : "";//trim;
				
				if (result_var_name) {
					result_var_name = result_var_name.charAt(0) == '$' ? result_var_name : '$' + result_var_name;
					
					if (class_name) {
						this.variables_in_workflow[result_var_name] = {"class_name" : class_name};
					}
					else {
						this.variables_in_workflow[result_var_name] = {};
					}
				}
				else if (result_obj_name || result_prop_name) {
					var var_name = result_obj_name + (result_static ? "::" : "->") + result_prop_name;
					var_name = var_name.charAt(0) == '$' || result_static ? var_name : '$' + var_name;
					
					if (class_name) {
						this.variables_in_workflow[var_name] = {"class_name" : class_name};
					}
					else {
						this.variables_in_workflow[var_name] = {};
					}
				}
			}
		},
		
		onSubmitResultVariableType : function(task_html_elm) {
			var type = task_html_elm.find(".result .result_var_type select").val();
			
			switch(type) {
				case "variable":
					task_html_elm.find(".type_obj_prop, .type_echo").remove();
					break;
				case "obj_prop": 
					task_html_elm.find(".type_variable, .type_echo").remove();
					break;
				case "echo": 
					task_html_elm.find(".type_echo input").val(1);
					task_html_elm.find(".type_variable, .type_obj_prop").remove();
					break;
				default:
					task_html_elm.find(".type_variable, .type_obj_prop, .type_echo").remove();
			}
		},
	
		onChangeResultVariableType : function(elm) {
			elm = $(elm);
		
			var type = elm.val();
			var task_html_elm = elm.parent().parent().parent();
			var WF = myWFObj.getJsPlumbWorkFlow();
			
			switch(type) {
				case "variable":
					task_html_elm.find(".type_variable").show();
					task_html_elm.find(".type_obj_prop, .type_echo").hide();
					WF.getMyFancyPopupObj().resizeOverlay();
					break;
				case "obj_prop": 
					task_html_elm.find(".type_obj_prop").show();
					task_html_elm.find(".type_variable, .type_echo").hide();
					WF.getMyFancyPopupObj().resizeOverlay();
					break;
				case "echo": 
					task_html_elm.find(".type_echo").show();
					task_html_elm.find(".type_variable, .type_obj_prop").hide();
					WF.getMyFancyPopupObj().resizeOverlay();
					break;
				default:
					task_html_elm.find(".type_variable, .type_obj_prop, .type_echo").hide();
			}
		},
	
		setResultVariableType : function(task_property_values, task_html_elm) {
			var result_var_name = task_property_values["result_var_name"];
			var result_obj_name = task_property_values["result_obj_name"];
			var result_prop_name = task_property_values["result_prop_name"];
			var result_static = task_property_values["result_static"];
			var result_echo = task_property_values["result_echo"];
			
			if (result_var_name) {
				task_html_elm.find(".type_variable").show();
				task_html_elm.find(".type_obj_prop, .type_echo").hide();
				task_html_elm.find(".result_var_type select").val("variable");
			}
			else if (result_obj_name || result_prop_name) {
				task_html_elm.find(".type_obj_prop").show();
				task_html_elm.find(".type_variable, .type_echo").hide();
				task_html_elm.find(".result_var_type select").val("obj_prop");
				
				result_obj_name = result_obj_name ? result_obj_name : "";
				result_obj_name = result_static != 1 && result_obj_name.substr(0, 1) == "$" ? result_obj_name.substr(1) : result_obj_name;
				task_html_elm.find(".result_obj_name input").val(result_obj_name);
			}
			else if (result_echo) {
				task_html_elm.find(".type_echo").show();
				task_html_elm.find(".type_variable, .type_obj_prop").hide();
				task_html_elm.find(".result_var_type select").val("echo");
			}
			else {
				task_html_elm.find(".type_variable, .type_obj_prop, .type_echo").hide();
				task_html_elm.find(".result_var_type select").val("");
			}
		},
		
		getVariableAssignmentOperator : function(assignment) {
			return assignment == "concat" || assignment == "concatenate" ? ".=" : (assignment == "increment" ? "+=" : (assignment == "decrement" ? "-=" : "="));
		},
		
		getValueString : function(value, type) {
			if (typeof value == "undefined" || value == null) {
				return type == "string" || type == "date" ? "''" : (!type ? "null" : "");
			}
			
			value = "" + value + "";
			value = value.trim();
			value = type == "variable" ? (value.substr(0, 1) != '$' ? '$' : '') + value : (type == "string" || type == "date" ? "'" + value.replace(/'/g, "\\'") + "'" : (!type && value.trim().length == 0 ? "null" : value));
			
			return value;
		},
		
		getResultVariableString : function(task_property_values) {
			var result_var_name = task_property_values["result_var_name"];
			var result_var_assignment = task_property_values["result_var_assignment"];
			var result_obj_name = task_property_values["result_obj_name"];
			var result_prop_name = task_property_values["result_prop_name"];
			var result_static = task_property_values["result_static"];
			var result_prop_assignment = task_property_values["result_prop_assignment"];
			var result_echo = task_property_values["result_echo"];
			
			if (result_var_name) {
				return result_var_name ? this.getValueString(result_var_name, "variable") + " " + this.getVariableAssignmentOperator(result_var_assignment) + " " : "";
			}
			else if (result_obj_name && result_prop_name) {
				if (result_static == 1) {
					return result_obj_name.trim() + "::" + this.getValueString(result_prop_name, "variable") + " " + this.getVariableAssignmentOperator(result_prop_assignment) + " ";
				}
				else {
					return this.getValueString(result_obj_name, "variable") + "->" + result_prop_name + " " + this.getVariableAssignmentOperator(result_prop_assignment) + " ";
				}
			}
			else if (result_echo) {
				return "echo ";
			}
			
			return "";
		},
	
		getArgsString : function(args) {
			if (args) {
				if (args["value"] || args["type"] || args["name"]) {
					args = [args];
				}
				
				var str = "";
				var c = 0;
				for (var i in args) {
					var arg = args[i];
					
					var type = arg["type"] ? arg["type"] : "";
					var value = this.getValueString(arg["value"], type);
					
					str += (c > 0 ? ", " : "") + value;
					c++;
				}
				
				return str;
			}
			return "";
		},
	
		setArgs : function(args, args_html_elm) {
			if (args_html_elm[0]) {
				var class_name = args_html_elm.parent()[0].className;
				class_name = class_name ? class_name.split(" ") : ["args"];
				class_name = class_name[0];
				
				if (args && (args.hasOwnProperty("value") || args.hasOwnProperty("type") || args.hasOwnProperty("name"))) {
					args = [args];
				}
				
				var html = '';
				var count = 0;
				
				if (args) {
					for (var i in args) {
						var arg = args[i];
						
						html += this.getTableArg(class_name, arg["name"], arg["value"], arg["type"], count);
						++count;
					}
				}
				else {
					html += '<tr class="table_arg_empty">' + 
							'<td class="table_arg_name"></td>' + 
							'<td colspan="3">There are no arguments defined...</td>' +
						'<tr>';
				}
				
				html = '<table count="' + count + '">' +
						'<tr>' +
							'<th class="table_arg_name"></th>' +
							'<th class="table_arg_value table_header">Value</th>' +
							'<th class="table_arg_type table_header">Type</th>' +
							'<th class="table_arg_remove table_header">' +
								'<a class="icon add" onclick="ProgrammingTaskUtil.addTableArg(this, \'' + class_name + '\')">add</a>' +
							'</th>' +
						'</tr>' +
						html +
						'</table>';
			
				args_html_elm.html(html);
				
				var w = args_html_elm.children("table").width();
				if (w > 0)
					args_html_elm.css("width", w + "px");
			}
		},
		
		addTableArg : function(elm, class_name) {
			var table = $(elm).parent().parent().parent().parent();
			
			var count = table.attr("count");
			count = count ? ++count : 0;
			table.attr("count", count);
			
			var html = this.getTableArg(class_name, "", "", "string", count);
			
			table.append(html);
			table.find(".table_arg_empty").remove();
		},
		
		getTableArg : function(class_name, name, value, type, count) {
			name = name ? name : "";
			value = typeof value != "undefined" || value != null ? "" + value + "" : "";
			type = type ? type : "";
			
			var n = name ? "$" + name + ":" : "";
			
			return '<tr>' +
				'<td class="table_arg_name">' +
					'<input type="hidden" class="task_property_field" name="' + class_name + '[' + count + '][name]" value="' + name.replace(/"/g, "&quot;") + '" />' + 
					'<div>' + n + '</div>' + 
				'</td>' +
				'<td class="table_arg_value">' + 
					'<input type="text" class="task_property_field" name="' + class_name + '[' + count + '][value]" value="' + value.replace(/"/g, "&quot;") + '" />' + 
				'</td>' +
				'<td class="table_arg_type">' +
					'<select class="task_property_field" name="' + class_name + '[' + count + '][type]">' +
						'<option' + (type == "string" ? " selected" : "") + '>string</option>' +
						'<option' + (type == "variable" ? " selected" : "") + '>variable</option>' +
						'<option value=""' + (value && type != "string" && type != "variable" ? " selected" : "") + '>code</option>' +
					'</select>' +
				'</td>' +
				'<td class="table_arg_remove table_header">' +
					'<a class="icon remove" onclick="$(this).parent().parent().remove()">remove</a>' +
				'</td>' +
			'</tr>';
		},
		
		updateTaskDefaultExitLabel : function(task_id, label) {
			var labels = {"default_exit": label};
			this.updateTaskExitsLabels(task_id, labels);
		},
		
		updateTaskExitsLabels : function(task_id, labels) {
			var WF = myWFObj.getJsPlumbWorkFlow();
			var task = WF.jsPlumbTaskFlow.getTaskById(task_id);
			var exits = task.find(" > ." + WF.jsPlumbTaskFlow.task_eps_class_name + " ." + WF.jsPlumbTaskFlow.task_ep_class_name);
			
			var exit, connection_exit_id, span, bg, title;
			
			for (var i = 0; i < exits.length; i++) {
				exit = $(exits[i]);
				
				connection_exit_id = exit.attr("connection_exit_id");
				
				if (connection_exit_id && labels.hasOwnProperty(connection_exit_id)) {
					if (labels[connection_exit_id]) {
						span = $('<span>' + labels[connection_exit_id].replace(/</g, "&lt;") + '</span>');
						
						//setting text color according with background
						bg = exit.css("background-color");
						if (bg) {
							if (bg.indexOf("rgb") != -1) {
								bg = backgroundRgbToHex(bg);
							}
							span.css("color", getContrastYIQ(bg) == "white" ? "#FFF" : "#000");
						}
						
						title = labels[connection_exit_id];
						
						exit.html(span);
						exit.attr("title", title);
					}
					else {
						exit.html("");
						exit.attr("title", "");
					}
				}
			}
			
			var height = 28 + (exits.length * 22);
			var is_resizable_task = task.attr("is_resizable_task");
			var resize_height = is_resizable_task ? height > task.height() : height != task.height();
			
			if (resize_height) {
				task.css("height", height + "px");
			
				WF.jsPlumbTaskFlow.repaintTask(task);
			}
		},
		
		onChangeTaskFieldType : function(elm) {
			elm = $(elm);
			var p = elm.parent();
			
			if (p.children("input[type=text]").css("display") != "none")
				p.children(".add_variable").css("display", "inline"); //do not use show() otherwise the display will be block and UI will be weired.
			else
				p.children(".add_variable").hide();
		},
		
		onBeforeTaskDeletion : function(task_id, task) {
			this.connections_to_add_after_deletion = [];
			this.new_start_task_id = null;
			this.new_start_task_order = null;
			
			var WF = myWFObj.getJsPlumbWorkFlow();
			var child_connections = WF.jsPlumbTaskFlow.getSourceConnections(task_id);
			var cl = child_connections.length;
			var target_id = cl > 0 && child_connections[0] ? child_connections[0].targetId : null;
			
			if (target_id) {
				//prepare new start task
				var start_task_order = task.attr("is_start_task");
				
				if (start_task_order > 0) {
					this.new_start_task_id = target_id;
					this.new_start_task_order = start_task_order;
				}
				
				//prepare parent connections
				var parent_connections = WF.jsPlumbTaskFlow.getTargetConnections(task_id);
				var pl = parent_connections.length;
				
				if (pl > 0)
					for (var i = 0; i < pl; i++) {
						var parent_connection = parent_connections[i];
						var source_id = parent_connection.sourceId;
						
						if (source_id) {
							var parameters = parent_connection.getParameters();
							var connector_type = parameters.connection_exit_type;
							var connection_overlay = parameters.connection_exit_overlay;
							var connection_label = parent_connection.getOverlay("label").getLabel();
							var connection_exit_props = {
								id: parameters.connection_exit_id, 
								color: parameters.connection_exit_color
							};
						
							this.connections_to_add_after_deletion.push([source_id, target_id, connection_label, connector_type, connection_overlay, connection_exit_props]);
						}
					}
			}
			
			return true;
		},
		
		onAfterTaskDeletion : function(task_id, task) {
			var WF = myWFObj.getJsPlumbWorkFlow();
			
			//prepare new start task
			if (this.new_start_task_id) {
				var new_task = WF.jsPlumbTaskFlow.getTaskById(this.new_start_task_id);
				new_task.attr("is_start_task", this.new_start_task_order).addClass("is_start_task");
			}
			
			//prepare new connections
			if ($.isArray(this.connections_to_add_after_deletion) && this.connections_to_add_after_deletion.length > 0) {
				for (var i = 0; i < this.connections_to_add_after_deletion.length; i++) {
					var c = this.connections_to_add_after_deletion[i];
					var source_task_id = c[0];
					var target_task_id = c[1];
					var connection_label = c[2];
					var connector_type = c[3];
					var connection_overlay = c[4];
					var connection_exit_props = c[5];
					
					WF.jsPlumbTaskFlow.connect(source_task_id, target_task_id, connection_label, connector_type, connection_overlay, connection_exit_props);
				}
			}
			
			return true;
		},
		
		onProgrammingTaskChooseCreatedVariable : function(elm) {
			if (typeof this.on_programming_task_choose_created_variable_callback == "function") {
				this.on_programming_task_choose_created_variable_callback(elm);
			}
		},
	
		onProgrammingTaskChooseObjectProperty : function(elm) {
			if (typeof this.on_programming_task_choose_object_property_callback == "function") {
				this.on_programming_task_choose_object_property_callback(elm);
			}
		},
	
		onProgrammingTaskChooseObjectMethod : function(elm) {
			if (typeof this.on_programming_task_choose_object_method_callback == "function") {
				this.on_programming_task_choose_object_method_callback(elm);
			}
		},
	
		onProgrammingTaskChooseFunction : function(elm) {
			if (typeof this.on_programming_task_choose_function_callback == "function") {
				this.on_programming_task_choose_function_callback(elm);
			}
		},
		
		onProgrammingTaskChooseClassName : function(elm) {
			if (typeof this.on_programming_task_choose_class_name_callback == "function") {
				this.on_programming_task_choose_class_name_callback(elm);
			}
		},
		
		onProgrammingTaskChooseFilePath : function(elm) {
			if (typeof this.on_programming_task_choose_file_path_callback == "function") {
				this.on_programming_task_choose_file_path_callback(elm);
			}
		},
		
		onProgrammingTaskChoosePageUrl : function(elm) {
			if (typeof this.on_programming_task_choose_page_url_callback == "function") {
				this.on_programming_task_choose_page_url_callback(elm);
			}
		},
		
		onProgrammingTaskChooseImageUrl : function(elm) {
			if (typeof this.on_programming_task_choose_image_url_callback == "function") {
				this.on_programming_task_choose_image_url_callback(elm);
			}
		},
	};
}
