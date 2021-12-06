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
<script src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
<script src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>

<style>
.title {
	width: 100%;
	color: #000;
	font-size: 16px;
	font-weight: bold;
	margin: 10px 0 5px;
	text-align: center;
}
.sql_text_area .ace_editor, .sql_text_area textarea {
	height:400px;
	width:100%;
	border-top:3px inset #CCC;
	border-bottom:3px inset #CCC;
	margin:0;
}
.save_button {
	width:100%;
	height:25px;
	text-align:center;
	margin:10px 0 15px 0;
}
.save_button input {
	border:1px outset;
	border-radius:0.5em;
	height:25px !important;
	line-height:15px !important;
	padding:3px 5px !important;
}

.status_ok, .status_error {
	width:100%;
	height:25px;
	text-align:center;
	margin:10px 0 15px 0;
	font-weight:bold;
}
.status_ok {
	color:#009900;
}
.status_error {
	color:#990000;
}
</style>'; $main_content .= '<div class="title">DB Diagram\'s SQL</div>'; if ($_POST) { $main_content .= '
		<div class="status_' . ($status ? 'ok' : 'error') . '">' . ($status ? 'SQL executed successfully' : 'SQL executed unssuccessfully') . '</div>
	'; } if (!$_POST || !$status) { $main_content .= '
		<div class="save_button">
			<input type="button" name="value" value="EXECUTE SQL" onClick="execute();" />
		</div>
	
		<div class="sql_text_area">
			<textarea>' . "\n" . htmlspecialchars($sql, ENT_NOQUOTES) . '</textarea>
		</div>
		<script>
			var sql_text_area = $(".sql_text_area");
			var textarea = sql_text_area.children("textarea")[0];
			
			ace.require("ace/ext/language_tools");
			var editor = ace.edit(textarea);
			editor.setTheme("ace/theme/chrome");
			editor.session.setMode("ace/mode/sql");
	    		editor.setAutoScrollEditorIntoView(true);
			editor.setOption("maxLines", "Infinity");
			editor.setOption("minLines", 30);
			editor.setOptions({
				enableBasicAutocompletion: true,
				enableSnippets: true,
				enableLiveAutocompletion: false,
			});
			editor.$blockScrolling = "Infinity";
			
			sql_text_area.find("textarea.ace_text-input").removeClass("ace_text-input"); //fixing problem with scroll up, where when focused or pressed key inside editor the page scrolls to top
			
			sql_text_area.data("editor", editor);
			
			$(function () {
				editor.focus();
			});
		
			function execute() {
				var editor = sql_text_area.data("editor");
				var sql = editor.getValue();
			
				$("#main_column").append(\'<form id="form_sql" method="post" style="display:none"><textarea name="sql"></textarea></form>\');
				$("#form_sql textarea").val(sql);
				$("#form_sql")[0].submit();
			}
		</script>
	'; } ?>
