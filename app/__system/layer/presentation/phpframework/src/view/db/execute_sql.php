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
$head = '
<!-- Add ACE editor -->
<script src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
<script src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>

<!-- Add DataTables Plugin -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerydatatables/media/css/jquery.dataTables.min.css" charset="utf-8" />
<script src="' . $project_common_url_prefix . 'vendor/jquerydatatables/media/js/jquery.dataTables.min.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Icons CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/db/execute_sql.css" charset="utf-8" />
<script src="' . $project_url_prefix . 'js/db/execute_sql.js"></script>'; $main_content .= '
	<div class="top_bar' . ($popup ? ' in_popup' : '') . '">
		<header>
			<div class="title">Execute SQL in DB: \'' . $bean_name . '\'</div>
			<ul>
				<li class="execute" data-title="Execute"><a onClick="execute()"><i class="icon continue"></i> Execute</a></li>
			</ul>
		</header>
	</div>

	<div class="sql_text_area' . ($popup ? ' in_popup' : '') . '">
		<textarea>' . "\n" . htmlspecialchars($sql, ENT_NOQUOTES) . '</textarea>
	</div>
	<div class="sql_results' . ($popup ? ' in_popup' : '') . '">
		<table class="display compact">'; $fields = $results["fields"]; $rows = $results["result"]; if ($fields) { $main_content .= '<thead><tr>'; $t = count($fields); for ($i = 0; $i < $t; $i++) { $name = $fields[$i]->name; $main_content .= '<th class="table_header">' . $name . '</th>'; } $main_content .= '</tr></thead>'; } $main_content .= '<tbody>'; if ($_POST) { if ($exception_message) { $main_content .= '<tr>
			<td class="message error">
				' . $exception_message . '
			</td>
		</tr>'; } else if (!$is_select_sql) { $message = $results ? "SQL executed successfully." : "SQL executed unsuccessfully."; $main_content .= '<tr>
			<td class="message ' . ($results ? 'success' : 'error') . '" colspan="' . ($fields ? count($fields) : 0) . '">
				' . $message . '
				<script>
					if (typeof window.parent.refreshAndShowLastNodeChilds == "function")
						window.parent.refreshAndShowLastNodeChilds();
				</script>
			</td>
		</tr>'; } else if (!$results || !is_array($rows) || empty($rows)) $main_content .= '<tr><td class="empty" colspan="' . ($fields ? count($fields) : 0) . '">Empty results...</tr>'; else { $t = count($rows); for ($i = 0; $i < $t; $i++) { $row = $rows[$i]; $main_content .= '<tr>'; foreach ($row as $column_name => $column_value) $main_content .= '<td>' . $column_value . '</td>'; $main_content .= '</tr>'; } } } else $main_content .= '<tr><td class="empty">Click in the button above to execute the query and show its results...</tr>'; $main_content .= '</tbody>
		</table>
	</div>
	
	<script>
		createSQLEditor();
	</script>
'; ?>
