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
?><div class="create_class_task_html">
	<div class="name">
		<label>Name:</label>
		<input class="task_property_field" name="name" type="text" value="" />
	</div>
	<div class="extends">
		<label>Extends:</label>
		<input class="task_property_field" name="extends" type="text" value="" />
		<span class="icon search" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseClassName(this)" do_not_update_args="1">Search</span>
	</div>
	<div class="implements">
		<label>Implements:</label>
		<input class="task_property_field" name="implements" type="text" value="" />
		<span class="icon search" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseClassName(this)" do_not_update_args="1">Search</span>
	</div>
	<div class="abstract">
		<label>Is Abstract:</label>
		<input class="task_property_field" name="abstract" type="checkbox" value="1" />
	</div>
	<div class="interface">
		<label>Is Interface:</label>
		<input class="task_property_field" name="interface" type="checkbox" value="1" />
	</div>
	<div class="comments">
		<label>Comments:</label>
		<textarea class="task_property_field" name="comments"></textarea>
	</div>
	<div class="properties">
		<label>Properties:</label>
		<table>
			<thead>
				<tr>
					<th class="name table_header">Name</th>
					<th class="value table_header">Value</th>
					<th class="type table_header">Type</th>
					<th class="static table_header">Static</th>
					<th class="var_type table_header">Var Type</th>
					<th class="comments table_header">Comments</th>
					<th class="icon_cell table_header"><span class="icon add" onClick="CreateClassTaskPropertyObj.addNewProperty(this)">Add Property</span></th>
				</tr>
			</thead>
			<tbody index_prefix="properties"></tbody>
		</table>
	</div>
	<div class="methods">
		<label>Methods: <span class="icon add" onClick="CreateClassTaskPropertyObj.addNewMethod(this)">Add Method</span></label>
		<ul index_prefix="methods"></ul>
	</div>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#004480"></div>
</div>
