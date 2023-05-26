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

var SetTemplateRegionBlockParamTaskPropertyObj = {
	
	main_variable_name : "region_block_local_variables",
	
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.createTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".set_template_region_block_param_task_html");
		
		if (typeof ProgrammingTaskUtil.on_programming_task_choose_page_url_callback == "function")
			task_html_elm.addClass("with_search");
		
		if (!task_html_elm.children("input.main_variable_name").val()) {
			task_html_elm.children("input.main_variable_name").val( SetTemplateRegionBlockParamTaskPropertyObj.main_variable_name );
		}
		
		var region = task_property_values["region"] ? "" + task_property_values["region"] + "" : "";
		region = task_property_values["region_type"] == "variable" && region.trim().substr(0, 1) == '$' ? region.trim().substr(1) : region;
		task_html_elm.find(".region input").val(region);
		
		var block = task_property_values["block"] ? "" + task_property_values["block"] + "" : "";
		block = task_property_values["block_type"] == "variable" && block.trim().substr(0, 1) == '$' ? block.trim().substr(1) : block;
		task_html_elm.find(".block input").val(block);
		
		var param_name = task_property_values["param_name"] ? "" + task_property_values["param_name"] + "" : "";
		param_name = task_property_values["param_name_type"] == "variable" && param_name.trim().substr(0, 1) == '$' ? param_name.trim().substr(1) : param_name;
		task_html_elm.find(".param_name input").val(param_name);
		
		var param_value = task_property_values["param_value"] ? "" + task_property_values["param_value"] + "" : "";
		param_value = task_property_values["param_value_type"] == "variable" && param_value.trim().substr(0, 1) == '$' ? param_value.trim().substr(1) : param_value;
		task_html_elm.find(".param_value input").val(param_value);
	},
	
	onSubmitTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.saveTaskLabelField(properties_html_elm, task_id);
		
		return true;
	},
	
	onCompleteTaskProperties : function(properties_html_elm, task_id, task_property_values, status) {
		if (status) {
			var label = SetTemplateRegionBlockParamTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
			
			SetTemplateRegionBlockParamTaskPropertyObj.saveNewVariable(task_property_values);
		}
	},
	
	onCancelTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		return true;	
	},
	
	onCompleteLabel : function(task_id) {
		return ProgrammingTaskUtil.onEditLabel(task_id);
	},
	
	onTaskCreation : function(task_id) {
		setTimeout(function() {
			var task_property_values = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id];
			SetTemplateRegionBlockParamTaskPropertyObj.saveNewVariable(task_property_values);
		
			var label = SetTemplateRegionBlockParamTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
		
			onEditLabel(task_id);
			
			ProgrammingTaskUtil.onTaskCreation(task_id);
		}, 100);
	},
	
	getDefaultExitLabel : function(task_property_values) {
		if (task_property_values["region"] && task_property_values["block"] && task_property_values["param_name"]) {
			var mvn = task_property_values["main_variable_name"] ? task_property_values["main_variable_name"] : this.main_variable_name;
			
			return '$' + mvn + '[' + ProgrammingTaskUtil.getValueString(task_property_values["region"], task_property_values["region_type"]) + '][' + ProgrammingTaskUtil.getValueString(task_property_values["block"], task_property_values["block_type"]) + '][' + ProgrammingTaskUtil.getValueString(task_property_values["param_name"], task_property_values["param_name_type"]) + '] = ' + ProgrammingTaskUtil.getValueString(task_property_values["param_value"], task_property_values["param_value_type"]);
		}
		return "";
	},
	
	saveNewVariable : function(task_property_values) {
		if (task_property_values["region"] && task_property_values["block"] && task_property_values["param_name"]) {
			var mvn = task_property_values["main_variable_name"] ? task_property_values["main_variable_name"] : this.main_variable_name;
			
			var var_name = mvn + '[' + ProgrammingTaskUtil.getValueString(task_property_values["region"], task_property_values["region_type"]) + '][' + ProgrammingTaskUtil.getValueString(task_property_values["block"], task_property_values["block_type"]) + '][' + ProgrammingTaskUtil.getValueString(task_property_values["param_name"], task_property_values["param_name_type"]) + ']';
		
			ProgrammingTaskUtil.variables_in_workflow[var_name] = {};
		}
	},
};
