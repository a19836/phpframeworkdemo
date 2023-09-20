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

var DroppableTestTaskPropertyObj = {
	
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		var task_html_elm = $(properties_html_elm).find(".droppable_test_task_html");
		
	},
	
	onSubmitTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		
		return true;
	},
	
	onCompleteTaskProperties : function(properties_html_elm, task_id, task_property_values, status) {
		
	},
	
	onCancelTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		return true;	
	},
	
	onCompleteLabel : function(task_id) {
		return ProgrammingTaskUtil.onEditLabel(task_id);
	},
	
	onTaskCreation : function(task_id) {
		var WF = myWFObj.getTaskFlowChart();
		var j_task = WF.TaskFlow.getTaskById(task_id);
		
		var droppable1 = $('<div class="' + WF.TaskFlow.task_droppable_class_name + '"></div>');
		droppable1.css({
			"height":"100px",
			"position":"absolute",
			"top":"50px",
			"left":0,
			"right":0,
			"box-sizing":"border-box",
			"background":"green",
		});
		
		var droppable2 = $('<div class="' + WF.TaskFlow.task_droppable_class_name + '"></div>');
		droppable2.css({
			"position":"absolute",
			"top":"150px",
			"left":0,
			"right":0,
			"bottom":0,
			"min-height":"100px", 
			"box-sizing":"border-box",
			"background":"orange",
		});
		
		j_task.append(droppable1);
		j_task.append(droppable2);
		
		WF.ContextMenu.prepareTaskDroppables(j_task);
		WF.TaskFlow.resizeTaskParentTask(droppable1, true);
		
		ProgrammingTaskUtil.onTaskCreation(task_id);
	},
};
