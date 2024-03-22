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
?><div class="db_table_connection_html">
	<div class="header">
		<label>Tables Join:</label>
		<select class="connection_property_field tables_join" name="tables_join">
			<option>inner</option>
			<option>left</option>
			<option>righ</option>
		</select>
		<input class="connection_property_field source_table" type="text" name="source_table" value="" />
		<input class="connection_property_field target_table" type="text" name="target_table" value="" />
	</div>
	
	<table>
		<thead>
			<tr>
				<th class="source_column table_header"></th>
				<th class="operator table_header">operator</th>
				<th class="target_column table_header"></th>
				<th class="column_value table_header">value</th>
				<th class="table_attr_icons">
					<a class="icon add" onClick="DBQueryTaskPropertyObj.addTableJoinKey()">ADD</a>
				</th>
			</tr>
		</thead>
		<tbody class="table_attrs">
			
		</tbody>
	</table>
	
	<div class="delete_connection_button">
		<button onClick="removeTableConnectionFromConnectionProperties(this)">Delete Connection Between Tables</button>
	</div>
</div>
