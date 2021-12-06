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
?><div class="layer_connection_html">
	<div class="connection_type">
		<label>Connection Type: </label>
		<select class="connection_property_field" name="connection_type" onChange="onChangeLayerConnectionPropertiesType(this)">
			<option value="">Local</option>
			<option value="rest">REST</option>
			<option value="soap">SOAP</option>
		</select>
	</div>
	
	<div class="connection_response_type">
		<label>Connection Response Type: </label>
		<select class="connection_property_field" name="connection_response_type">
			<option value="">PHP Serialize</option>
			<option value="xml">XML</option>
			<option value="json">JSON</option>
		</select>
	</div>
	
	<div class="connection_settings">
		<label>Other Connection Settings: </label>
		<table>
			<thead>
				<tr>
					<th class="setting_name table_header">Setting Name</th>
					<th class="setting_value table_header">Setting Value</th>
					<th class="table_attr_icons">
						<a class="add" onClick="addLayerConnectionPropertiesSetting(this)" title="Add">Add</a>
					</th>
				</tr>
			</thead>
			<tbody class="table_attrs"></tbody>
		</table>
	</div>
	
	<div class="connection_global_variables_name">
		<label>Connection Global Variables Name: </label>
		<div class="info">"Connection Global Variables Name" are global variables name that were changed dynamically in the Rest Client side and you wish to set as Global Variables in the REST Server side. So we must pass them from the REST Client side to the REST Server side.</div>
		<table>
			<thead>
				<tr>
					<th class="var_name table_header">Global Variable Name</th>
					<th class="table_attr_icons">
						<a class="add" onClick="addLayerConnectionGLobalVarName(this)" title="Add Rest Global Variable Name">Add</a>
					</th>
				</tr>
			</thead>
			<tbody class="table_attrs"></tbody>
		</table>
	</div>
</div>
