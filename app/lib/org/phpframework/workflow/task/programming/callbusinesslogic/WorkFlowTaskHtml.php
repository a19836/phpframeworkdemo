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
?><div class="call_business_logic_task_html">
	<div class="get_automatically">
		<label>Get business logic from File Manager: </label>
		<span class="icon update" onClick="CallBusinessLogicTaskPropertyObj.onChooseBusinessLogic(this)" title="Get business logic from File Manager">Update</span>
	</div>
	<div class="broker_method_obj" title="Write here the business logic Broker variable">
		<label>Ctx Broker Obj:</label>
		<select onChange="BrokerOptionsUtilObj.onBrokerChange(this)"></select>
		<input type="text" class="task_property_field" name="method_obj" />
		<span class="icon add_variable inline" onClick="BrokerOptionsUtilObj.chooseCreatedBrokerVariable(this)">Search</span>
	</div>
	<div class="module_id">
		<label>Module Id:</label>
		<input type="text" class="task_property_field" name="module_id" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field" name="module_id_type">
			<option></option>
			<option>string</option>
			<option>variable</option>
		</select>
	</div>
	<div class="service_id">
		<label>Service Id:</label>
		<input type="text" class="task_property_field" name="service_id" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field" name="service_id_type">
			<option></option>
			<option>string</option>
			<option>variable</option>
		</select>
	</div>
	
	<div class="params">
		<label class="main_label">Parameters:</label>
		<input type="text" class="task_property_field parameters_code" name="parameters" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field parameters_type" name="parameters_type" onChange="CallBusinessLogicTaskPropertyObj.onChangeParametersType(this)">
			<option></option>
			<option>string</option>
			<option>variable</option>
			<option>array</option>
		</select>
		<div class="parameters array_items"></div>
	</div>
	<div class="opts">
		<label class="main_label">Options:</label>
		<input type="text" class="task_property_field options_code" name="options" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field options_type" name="options_type" onChange="LayerOptionsUtilObj.onChangeOptionsType(this)">
			<option></option>
			<option>string</option>
			<option>variable</option>
			<option>array</option>
		</select>
		<div class="options array_items"></div>
	</div>
	
	<?php include dirname(dirname($file_path)) . "/common/ResultVariableHtml.php"; ?>
		
	<div class="task_property_exit" exit_id="default_exit" exit_color="#fcfc01"></div>
</div>
