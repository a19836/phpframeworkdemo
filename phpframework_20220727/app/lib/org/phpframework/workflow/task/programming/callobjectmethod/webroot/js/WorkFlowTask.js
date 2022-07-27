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

var CallObjectMethodTaskPropertyObj = {
	
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.createTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".call_object_method_task_html");
		ProgrammingTaskUtil.setResultVariableType(task_property_values, task_html_elm);
		
		var method_args = task_property_values["method_args"];
		
		ProgrammingTaskUtil.setArgs(method_args, task_html_elm.find(".method_args .args").first());
	},
	
	onSubmitTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.saveTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".call_object_method_task_html");
		ProgrammingTaskUtil.saveNewVariableInWorkflowAccordingWithType(task_html_elm);
		ProgrammingTaskUtil.onSubmitResultVariableType(task_html_elm);
		
		return true;
	},
	
	onCompleteTaskProperties : function(properties_html_elm, task_id, task_property_values, status) {
		if (status) {
			var label = CallObjectMethodTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
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
			ProgrammingTaskUtil.saveNewVariableInWorkflowAccordingWithTaskPropertiesValues(task_property_values);
		
			var label = CallObjectMethodTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
		
			onEditLabel(task_id);
			
			ProgrammingTaskUtil.onTaskCreation(task_id);
		}, 100);
	},
	
	getDefaultExitLabel : function(task_property_values) {
		if (task_property_values["method_name"]) {
			var method_obj = (task_property_values["method_static"] != 1 && task_property_values["method_obj"].trim().substr(0, 1) != "$" ? "$" : "") + task_property_values["method_obj"];
		
			return ProgrammingTaskUtil.getResultVariableString(task_property_values) + method_obj + (task_property_values["method_static"] == 1 ? '::' : '->') + task_property_values["method_name"] + "(" + ProgrammingTaskUtil.getArgsString(task_property_values["method_args"]) + ")";
		}
		return "";
	},
};
