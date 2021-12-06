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

include_once get_lib("org.phpframework.db.DB"); $extensions = DB::getAllExtensionsByType(); $encodings = DB::getAllDBCharsetsByType(); $driver_labels = DB::getAllDriverLabelsByType(); $ignore_connection_options = DB::getAllIgnoreConnectionOptionsByType(); $ignore_connection_options_by_extension = DB::getAllIgnoreConnectionOptionsByExtensionAndType(); echo '<script>
DBDriverTaskPropertyObj.encodings = ' . json_encode($encodings) . ';
DBDriverTaskPropertyObj.extensions = ' . json_encode($extensions) . ';
DBDriverTaskPropertyObj.ignore_options = ' . json_encode($ignore_connection_options) . ';
DBDriverTaskPropertyObj.ignore_options_by_extension = ' . json_encode($ignore_connection_options_by_extension) . ';
</script>'; ?>
<div class="db_driver_task_html">
	<div class="type">
		<label>DataBase Type:</label>
		<select class="task_property_field" name="type" onChange="DBDriverTaskPropertyObj.onChangeType(this)">
		<?php
 $first_driver_type = key($driver_labels); foreach ($driver_labels as $driver_type => $driver_label) echo '<option value="' . $driver_type . '">' . $driver_label. '</option>'; ?>
		</select>
	</div>
	
	<div class="extension">
		<label>Connection Type:</label>
		<select class="task_property_field" name="extension" onChange="DBDriverTaskPropertyObj.onChangeExtension(this)">
		<?php
 if ($first_driver_type) { $first_driver_extensions = $extensions[$first_driver_type]; foreach ($first_driver_extensions as $idx => $value) echo '<option value="' . $value . '">' . $value . ($idx == 0 ? " - Default" : "") . '</option>'; } ?>
		</select>
	</div>

	<div class="host">
		<label>Host:</label>
		<input type="text" class="task_property_field" name="host" value="" />
	</div>

	<div class="port">
		<label>Port:</label>
		<input type="text" class="task_property_field" name="port" value="" />
	</div>

	<div class="db_name">
		<label>DB Name:</label>
		<input type="text" class="task_property_field" name="db_name" value="" />
	</div>

	<div class="user">
		<label>User:</label>
		<input type="text" class="task_property_field" name="username" value="" />
	</div>

	<div class="password">
		<label>Password:</label>
		<input type="password" class="task_property_field" name="password" value="" />
		<span class="toggle_password" onClick="DBDriverTaskPropertyObj.togglePasswordField(this)"></span>
	</div>

	<div class="persistent">
		<label>Persistent:</label>
		<select class="task_property_field" name="persistent">
			<option value="1">Yes</option>
			<option value="0">No</option>
		</select>
	</div>

	<div class="new_link">
		<label>New Link:</label>
		<select class="task_property_field" name="new_link">
			<option value="0">No</option>
			<option value="1">Yes</option>
		</select>
	</div>

	<div class="encoding">
		<label>Encoding:</label>
		<select class="task_property_field" name="encoding">
			<option value="">-- Default --</option>
		<?php
 if ($first_driver_type) { $first_driver_encodings = $encodings[$first_driver_type]; foreach ($first_driver_encodings as $enc => $label) echo '<option value="' . $enc . '">' . $label . '</option>'; } ?>
		</select>
	</div>
	
	<div class="schema">
		<label>Schema:</label>
		<input type="text" class="task_property_field" name="schema" value="" />
	</div>

	<div class="odbc_data_source" title="A Data Source Name (DSN) is the logical name that is used by Open Database Connectivity (ODBC) to refer to the driver and other information that is required to access data from a data source. Data sources are usually defined in /etc/odbc.ini">
		<label>ODBC Data Source:</label>
		<input type="text" class="task_property_field" name="odbc_data_source" value="" />
	</div>

	<div class="odbc_driver" title="Is the file path of the installed driver that connects to a data-base from ODBC protocol. Or the name of an ODBC instance that was defined in /etc/odbcinst.ini">
		<label>ODBC Driver:</label>
		<input type="text" class="task_property_field" name="odbc_driver" value="" />
	</div>

	<div class="extra_dsn" title="Other DSN attributes. Each attribute must be splitted by comma.">
		<label>Extra DSN:</label>
		<input type="text" class="task_property_field" name="extra_dsn" value="" />
	</div>
</div>
