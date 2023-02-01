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
?><div class="clear"></div>
<div class="result">
	<div class="result_var_type" title="Assign to a variable the return value of method.">
		<label>Please choose the result variable type:</label>
		<select onChange="ProgrammingTaskUtil.onChangeResultVariableType(this);" class="task_property_field" name="result_var_type">
			<option value="">no variable</option>
			<option value="variable">simple variable</option>
			<option value="obj_prop">object property</option>
			<option value="echo">echo/print</option>
			<option value="return">return</option>
		</select>
	</div>
	<div class="type_variable">
		<div class="result_var_name">
			<label>Variable Name:</label>
			<input type="text" class="task_property_field" name="result_var_name" />
			<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Search</span>
		</div>
		<div class="result_var_assignment">
			<label>Assignment:</label>
			<select class="task_property_field" name="result_var_assignment">
				<option>default</option>
				<option>concatenate</option>
				<option>increment</option>
				<option>decrement</option>
			</select>
		</div>
	</div>
	<div class="type_obj_prop">
		<div class="result_obj_name" title="The object should be a variable; or a class name in case of a static property.">
			<label>Object:</label>
			<input type="text" class="task_property_field" name="result_obj_name" />
			<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Search</span>
		</div>
		<div class="result_prop_name">
			<label>Property Name:</label>
			<input type="text" class="task_property_field" name="result_prop_name" />
			<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			<span class="icon search" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseObjectProperty(this)">Search</span>
		</div>
		<div class="result_static">
			<label>Is Property Static:</label>
			<input type="checkbox" class="task_property_field" name="result_static" value="1" />
		</div>
		<div class="result_prop_assignment">
			<label>Assignment:</label>
			<select class="task_property_field" name="result_prop_assignment">
				<option>default</option>
				<option>concatenate</option>
				<option>increment</option>
				<option>decrement</option>
			</select>
		</div>
	</div>
	<div class="type_echo">
		<input type="hidden" class="task_property_field" name="result_echo" />
	</div>
	<div class="type_return">
		<input type="hidden" class="task_property_field" name="result_return" />
	</div>
</div>
