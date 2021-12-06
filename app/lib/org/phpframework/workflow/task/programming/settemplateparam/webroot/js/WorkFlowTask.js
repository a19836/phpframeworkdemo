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

var SetTemplateParamTaskPropertyObj = {
	
	brokers_options : null,
	on_choose_page_url_callback: null,
	
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.createTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".set_template_param_task_html");
		
		if (typeof SetTemplateParamTaskPropertyObj.on_choose_page_url_callback == "function")
			task_html_elm.find(".value .search").css("display", "inline-block");
		
		BrokerOptionsUtilObj.initFields(task_html_elm.find(".broker_method_obj"), SetTemplateParamTaskPropertyObj.brokers_options, task_property_values["method_obj"]);
		
		var name = task_property_values["name"] ? "" + task_property_values["name"] + "" : "";
		name = task_property_values["name_type"] == "variable" && name.trim().substr(0, 1) == '$' ? name.trim().substr(1) : name;
		task_html_elm.find(".name input").val(name);
		
		var value = task_property_values["value"] ? "" + task_property_values["value"] + "" : "";
		value = task_property_values["value_type"] == "variable" && value.trim().substr(0, 1) == '$' ? value.trim().substr(1) : value;
		task_html_elm.find(".value input").val(value);
	},
	
	onSubmitTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.saveTaskLabelField(properties_html_elm, task_id);
		
		return true;
	},
	
	onCompleteTaskProperties : function(properties_html_elm, task_id, task_property_values, status) {
		if (status) {
			var label = SetTemplateParamTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
			
			var default_method_obj_str = BrokerOptionsUtilObj.getDefaultBroker(SetTemplateParamTaskPropertyObj.brokers_options);
			if (!task_property_values["method_obj"] && default_method_obj_str) {
				task_property_values["method_obj"] = default_method_obj_str;
			}
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
			var label = SetTemplateParamTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
		
			onEditLabel(task_id);
			
			var default_method_obj_str = BrokerOptionsUtilObj.getDefaultBroker(SetTemplateParamTaskPropertyObj.brokers_options);
			if (!task_property_values["method_obj"] && default_method_obj_str) {
				myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id]["method_obj"] = default_method_obj_str;
			}
		}, 100);
	},
	
	getDefaultExitLabel : function(task_property_values) {
		var method_obj = (task_property_values["method_obj"].trim().substr(0, 1) != "$" ? "$" : "") + task_property_values["method_obj"];
		
		return method_obj + "->setParam(" + ProgrammingTaskUtil.getValueString(task_property_values["name"], task_property_values["name_type"]) + ", " + ProgrammingTaskUtil.getValueString(task_property_values["value"], task_property_values["value_type"]) + ")";
	},
};
