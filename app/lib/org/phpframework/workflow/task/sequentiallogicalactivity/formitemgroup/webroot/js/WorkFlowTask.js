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

var SLAItemGroupTaskPropertyObj = {
	
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		SLAItemTaskPropertyObj.onLoadTaskProperties(properties_html_elm, task_id, task_property_values, true);
	},
	
	onTaskCreation : function(task_id) {
		setTimeout(function() {
			var task_property_values = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id];
			var labels = SLAItemGroupTaskPropertyObj.getExitLabels(task_property_values);
			ProgrammingTaskUtil.updateTaskExitsLabels(task_id, labels);
			
			onEditLabel(task_id);
			
			ProgrammingTaskUtil.onTaskCreation(task_id);
		}, 80);
	},
	
	getExitLabels : function(task_property_values) {
		var labels = {"inside_group_exit": "Inside", "outside_group_exit": "Outside"}; //bc of old diagrams where task_property_values["exits"] don't have the labels.
		
		if (task_property_values && task_property_values["exits"]) {
			var exits = task_property_values["exits"];
			labels["inside_group_exit"] = exits["inside_group_exit"] && exits["inside_group_exit"]["label"] ? exits["inside_group_exit"]["label"] : labels["inside_group_exit"];
			labels["outside_group_exit"] = exits["outside_group_exit"] && exits["outside_group_exit"]["label"] ? exits["outside_group_exit"]["label"] : labels["outside_group_exit"];
		}
		
		return labels;
	},
};
