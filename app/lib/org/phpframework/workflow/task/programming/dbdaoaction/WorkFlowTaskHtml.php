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
?><div class="db_dao_action_task_html">
	<div class="broker_method_obj" title="Write here the DB Object variable">
		<label>Broker Obj:</label>
		<select onChange="BrokerOptionsUtilObj.onBrokerChange(this)"></select>
		<input type="text" class="task_property_field" name="method_obj" />
		<span class="icon add_variable inline" onClick="BrokerOptionsUtilObj.chooseCreatedBrokerVariable(this)">Search</span>
	</div>
	<div class="method_name">
		<label>Method Name:</label>
		<select class="task_property_field" name="method_name" onChange="DBDAOActionTaskPropertyObj.onChangeMethodName(this)">
			<option></option>
			<option>insertObject</option>
			<option>updateObject</option>
			<option>deleteObject</option>
			<option>findObjects</option>
			<option>countObjects</option>
			<option>findObjectsColumnMax</option>
			<option>findRelationshipObjects</option> 
			<option>countRelationshipObjects</option>
		</select>
	</div>
	
	<div class="get_automatically">
		<label>Get table from File Manager: </label>
		<span class="icon update" onClick="DBDAOActionTaskPropertyObj.onChooseTable(this)" title="Get table from list">Update</span>
	</div>
	
	<div class="table_name">
		<label>Table Name:</label>
		<input type="text" class="task_property_field" name="table_name" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field" name="table_name_type">
			<option></option>
			<option>string</option>
			<option>variable</option>
		</select>
	</div>
	
	<div class="attrs">
		<label class="main_label">Attributes:</label>
		<input type="text" class="task_property_field attributes_code" name="attributes" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field attributes_type" name="attributes_type" onChange="DBDAOActionTaskPropertyObj.onChangeAttributesType(this)">
			<option></option>
			<option>string</option>
			<option>variable</option>
			<option>array</option>
			<option>options</option>
		</select>
		<div class="attributes array_items"></div>
		<ul class="attributes_options">
			<a class="icon add" onclick="DBDAOActionTaskPropertyObj.onAddTableAttributeOption(this)" title="Add">add</a>
			<li class="no_items">There are no loaded attributes!</li>
		</ul>
	</div>
	
	<div class="conds">
		<label class="main_label">Conditions:</label>
		<input type="text" class="task_property_field conditions_code" name="conditions" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field conditions_type" name="conditions_type" onChange="DBDAOActionTaskPropertyObj.onChangeConditionsType(this)">
			<option></option>
			<option>string</option>
			<option>variable</option>
			<option>array</option>
			<option>options</option>
		</select>
		<div class="conditions array_items"></div>
		<ul class="conditions_options">
			<a class="icon add" onclick="DBDAOActionTaskPropertyObj.onAddTableAttributeOption(this)" title="Add">add</a>
			<li class="no_items">There are no loaded conditions!</li>
		</ul>
	</div>
	
	<div class="kys">
		<label class="main_label">Keys:</label>
		<input type="text" class="task_property_field keys_code" name="keys" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field keys_type" name="keys_type" onChange="DBDAOActionTaskPropertyObj.onChangeKeysType(this)">
			<option></option>
			<option>string</option>
			<option>variable</option>
			<option>array</option>
			<option>options</option>
		</select>
		<div class="keys array_items"></div>
		<ul class="keys_options">
			<a class="icon add" onclick="DBDAOActionTaskPropertyObj.onAddTableAttributeOption(this)" title="Add">add</a>
			<li class="no_items">There are no loaded keys!</li>
		</ul>
	</div>
	
	<div class="rels">
		<label class="main_label">Extra Rel Elms:</label>
		<input type="text" class="task_property_field relations_code" name="relations" />
		<select class="task_property_field relations_type" name="relations_type" onChange="DBDAOActionTaskPropertyObj.onChangeRelElmType(this)">
			<option></option>
			<option>string</option>
			<option>variable</option>
			<option>array</option>
		</select>
		<div class="relations array_items"></div>
	</div>
	
	<div class="parent_conds">
		<label class="main_label">Parent Conditions:</label>
		<input type="text" class="task_property_field parent_conditions_code" name="parent_conditions" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field parent_conditions_type" name="parent_conditions_type" onChange="DBDAOActionTaskPropertyObj.onChangeParentConditionsType(this)">
			<option></option>
			<option>string</option>
			<option>variable</option>
			<option>array</option>
			<option>options</option>
		</select>
		<div class="parent_conditions array_items"></div>
		<ul class="parent_conditions_options">
			<a class="icon add" onclick="DBDAOActionTaskPropertyObj.onAddTableAttributeOption(this)" title="Add">add</a>
			<li class="no_items">There are no loaded conditions!</li>
		</ul>
	</div>
	
	<div class="opts">
		<label class="main_label">Options:</label>
		<input type="text" class="task_property_field options_code" name="options" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field options_type" name="options_type" onChange="LayerOptionsUtilObj.onChangeOptionsType(this)">
			<option value="">code</option>
			<option>string</option>
			<option>variable</option>
			<option>array</option>
		</select>
		<div class="options array_items"></div>
	</div>
	
	<?php include dirname(dirname($file_path)) . "/common/ResultVariableHtml.php"; ?>
		
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
