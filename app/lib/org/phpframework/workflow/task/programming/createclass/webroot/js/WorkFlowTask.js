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

var CreateClassTaskPropertyObj = {
	
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.createTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".create_class_task_html");
		
		if (task_property_values["properties"]) {
			var add_icon = task_html_elm.find(" > .properties table thead th .icon.add");
			
			if ($.isPlainObject(task_property_values["properties"]) && task_property_values["properties"].hasOwnProperty("name"))
				task_property_values["properties"] = [ task_property_values["properties"] ];
			
			$.each(task_property_values["properties"], function(idx, prop) {
				var new_item = CreateClassTaskPropertyObj.addNewProperty(add_icon[0]);
				
				new_item.find(".name input").val(prop["name"]);
				new_item.find(".value input").val(prop["value"]);
				new_item.find(".type select").val(prop["type"]);
				new_item.find(".var_type select").val(prop["var_type"]);
				new_item.find(".comments input").val(prop["comments"]);
				
				if (prop["static"] == "1")
					new_item.find(".static input").attr("checked", "checked").prop("checked", true);
			});
		}
		
		if (task_property_values["methods"]) {
			var add_icon = task_html_elm.find(" > .methods > label .icon.add");
			
			if ($.isPlainObject(task_property_values["methods"]) && task_property_values["methods"].hasOwnProperty("name"))
				task_property_values["methods"] = [ task_property_values["methods"] ];
			
			$.each(task_property_values["methods"], function(idx, method) {
				var new_item = CreateClassTaskPropertyObj.addNewMethod(add_icon[0]);
				
				new_item.find("input.name").val(method["name"]);
				new_item.find("select.type").val(method["type"]);
				new_item.find("textarea.function_code").val(method["code"]);
				new_item.find(".comments textarea").val(method["comments"]);
				
				if (method["abstract"] == "1")
					new_item.find("select.abstract").val(1);
				
				if (method["static"] == "1")
					new_item.find("select.static").val(1);
				
				FunctionUtilObj.loadMethodArgs(new_item, method["arguments"]);
			});
		}
	},
	
	onSubmitTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.saveTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".create_class_task_html");
		
		return true;
	},
	
	onCompleteTaskProperties : function(properties_html_elm, task_id, task_property_values, status) {
		if (status) {
			var label = CreateClassTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
		}
	},
	
	onCancelTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		return true;	
	},
	
	onCompleteLabel : function(task_id) {
		onEditLabel(task_id);
		myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.repaintTaskByTaskId(task_id);
		
		return true;
	},
	
	onTaskCreation : function(task_id) {
		setTimeout(function() {
			var task_property_values = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id];
			var label = CreateClassTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
		
			onEditLabel(task_id);
		}, 30);
	},
	
	getDefaultExitLabel : function(task_property_values) {
		return (task_property_values["abstract"] ? "abstract " : "") + (task_property_values["interface"] ? "interface" : "class") + " " + task_property_values["name"] + (task_property_values["extends"] ? " extends " + task_property_values["extends"] : "") + (task_property_values["implements"] ? " implements " + task_property_values["implements"] : "");
	},
	
	addNewProperty : function(elm) {
		var types = ["public", "private", "protected", "const"];
		var var_types = {"string": "string", "": "default"};
		var tbody = $(elm).parent().closest("table").children("tbody");
		var idx = getListNewIndex(tbody);
		
		var html = '<tr class="property">'
				+ '	<td class="name">'
				+ '		<input class="task_property_field" name="properties[' + idx + '][name]" type="text" value="" />'
				+ '	</td>'
				+ '	<td class="value">'
				+ '		<input class="task_property_field" name="properties[' + idx + '][value]" type="text" value="" />'
				+ '	</td>'
				+ '	<td class="type">'
				+ '		<select class="task_property_field" name="properties[' + idx + '][type]">';
		
		for (var i = 0, l = types.length; i < l; i++) 
			html += '<option>' + types[i] + '</option>';
				
		html += '			</select>'
				+ '	</td>'
				+ '	<td class="static">'
				+ '		<input class="task_property_field" name="properties[' + idx + '][static]" type="checkbox" value="1" />'
				+ '	</td>'
				+ '	<td class="var_type">'
				+ '		<select class="task_property_field" name="properties[' + idx + '][var_type]">';
		
		for (var k in var_types) 
			html += '<option value="' + k + '">' + var_types[k] + '</option>';
				
		html += '			</select>'
				+ '	</td>'
				+ '	<td class="comments">'
				+ '		<input class="task_property_field" name="properties[' + idx + '][comments]" type="text" value="" />'
				+ '	</td>'
				+ '	<td class="icon_cell table_header"><span class="icon delete" onClick="$(this).parent().parent().remove();">Remove</span></td>'
				+ '</tr>';
		
		var new_item = $(html);
		
		tbody.append(new_item);
		
		return new_item;
	},
	
	addNewMethod : function(elm) {
		var types = ["public", "private", "protected"];
		var ul = $(elm).parent().closest(".methods").children("ul");
		var idx = getListNewIndex(ul);
		
		var html = '<li>'
			+ '	<input class="task_property_field name" name="methods[' + idx + '][name]" type="text" value="" placeHolder="method name" />'
			+ '	<select class="task_property_field type" name="methods[' + idx + '][type]">';
		
		for (var k in types) 
			html += '<option>' + types[k] + '</option>';
				
		html += '	</select>'
			+ '	<select class="task_property_field abstract" name="methods[' + idx + '][abstract]">'
			+ '		<option value="0"></option>'
			+ '		<option value="1">Abstract</option>'
			+ '	</select>'
			+ '	<select class="task_property_field static" name="methods[' + idx + '][static]">'
			+ '		<option value="0"></option>'
			+ '		<option value="1">Static</option>'
			+ '	</select>'
			+ '	<span class="icon update" onClick="FunctionUtilObj.editMethodCode(this)">Edit Code</span>'
			+ '	<table class="function_args">'
			+ '		<thead>'
			+ '			<tr>'
			+ '				<th class="name table_header">Var Name</th>'
			+ '				<th class="value table_header">Var Value</th>'
			+ '				<th class="var_type table_header">Var Type</th>'
			+ '				<th class="icon_cell table_header"><span class="icon add" onClick="FunctionUtilObj.addNewMethodArg(this)">Add Method Arg</span></th>'
			+ '			</tr>'
			+ '		</thead>'
			+ '		<tbody index_prefix="methods[' + idx + '][arguments]"></tbody>'
			+ '	</table>'
			+ '	<label class="function_code_label">Edit code:</label>'
			+ '	<textarea class="task_property_field function_code" name="methods[' + idx + '][code]" /></textarea>'
			+ '	<div class="comments">'
			+ '		<label>Comments:</label>'
			+ '		<textarea class="task_property_field" name="methods[' + idx + '][comments]"></textarea>'
			+ '	</div>'
			+ '</li>';
		
		var new_item = $(html);
		
		ul.append(new_item);
		
		return new_item;
	},
};
