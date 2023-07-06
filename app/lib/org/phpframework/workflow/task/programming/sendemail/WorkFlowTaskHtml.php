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
?><div class="send_email_task_html">
	
	<div class="info">This task needs the file 'lib/org/phpframework/util/web/SendEmailHandler' to be included before! If is not included yet, please add it by clicking <a class="include_file_before" href="javascript:void(0)" onClick="ProgrammingTaskUtil.addIncludeFileTaskBeforeTaskFromSelectedTaskProperties(SendEmailTaskPropertyObj.dependent_file_path_to_include, '', 1)">here</a>. 
	</div>
	
	<div class="method">
		<label>Method:</label>
		<select class="task_property_field" name="method" onChange="SendEmailTaskPropertyObj.onChangeMethod(this)">
			<option value="SendEmailHandler::sendSMTPEmail">send email through smtp server (recommended)</option>
			<option value="SendEmailHandler::sendEmail">send email through internal system</option>
		</select>
		<div class="info"></div>
		<div class="reload">To reload these attributes into the settings please click <a href="javascript:void(0)" onClick="SendEmailTaskPropertyObj.reloadMethodAttributes(this)">here</a>.</div>
	</div>
	<div class="settngs">
		<label class="main_label">Settings:</label>
		<input type="text" class="task_property_field settings_code" name="settings" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field settings_type" name="settings_type" onChange="SendEmailTaskPropertyObj.onChangeSettingsType(this)">
			<option>string</option>
			<option selected>variable</option>
			<option value="">code</option>
			<option>array</option>
		</select>
		<div class="settings array_items"></div>
	</div>
	
	<?php include dirname(dirname($file_path)) . "/common/ResultVariableHtml.php"; ?>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
