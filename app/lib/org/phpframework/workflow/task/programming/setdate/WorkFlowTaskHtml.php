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
?><div class="set_date_task_html">
	<div class="sample">
		<label>Sample: </label>
		<select onChange="SetDateTaskPropertyObj.onChangeSample(this)">
			<option value="">-- User defined --</option>
			<option value="Y-m-d H:i:s">Current date + time</option>
			<option value="Y-m-d">Current date</option>
			<option value="H:i:s">Current time</option>
			<option value="Y">Current year</option>
			<option value="m">Current month</option>
			<option value="d">Current day</option>
			<option value="H">Current hour</option>
			<option value="i">Current minutes</option>
			<option value="s">Current seconds</option>
		</select>
	</div>
	<div class="format">
		<label>Format: </label>
		<input type="text" class="task_property_field" name="format" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Search</span>
		<div class="info">More formats <a href="https://www.php.net/manual/en/datetime.format.php" target="php_datetime_format">here</a>.</div>
	</div>
	
	<?php include dirname(dirname($file_path)) . "/common/ResultVariableHtml.php"; ?>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
