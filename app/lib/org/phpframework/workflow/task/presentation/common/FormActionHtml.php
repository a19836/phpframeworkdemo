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
?><div class="actions">
	<label>Actions:</label>
	
	<ul>
		<li class="single_insert">
			<i class="icon maximize" onClick="PresentationTaskUtil.toggleAdvancedActionSettings(this)"></i>
			<label>Add Action:</label>
			<input class="task_property_field action_active" type="checkbox" name="action[single_insert]" value="1" onClick="PresentationTaskUtil.onChangeFormAction(this)" />
			
			<ul class="advanced_action_settings">
				<li class="ok_msg_message"><label>Ok Message: </label><input class="task_property_field" type="text" name="action[single_insert_ok_msg_message]" /></li>
				<li class="ok_msg_redirect_url"><label>OK Redirect Url: </label><input class="task_property_field" type="text" name="action[single_insert_ok_msg_redirect_url]" /></li>
				<li class="error_msg_message"><label>Error Message: </label><input class="task_property_field" type="text" name="action[single_insert_error_msg_message]" /></li>
				<li class="error_msg_redirect_url"><label>Error Redirect Url: </label><input class="task_property_field" type="text" name="action[single_insert_error_msg_redirect_url]" /></li>
			</ul>
		</li>
		<li class="single_update">
			<i class="icon maximize" onClick="PresentationTaskUtil.toggleAdvancedActionSettings(this)"></i>
			<label>Update Action:</label>
			<input class="task_property_field action_active" type="checkbox" name="action[single_update]" value="1" onClick="PresentationTaskUtil.onChangeFormAction(this)" />
			
			<ul class="advanced_action_settings">
				<li class="ok_msg_message"><label>Ok Message: </label><input class="task_property_field" type="text" name="action[single_update_ok_msg_message]" /></li>
				<li class="ok_msg_redirect_url"><label>OK Redirect Url: </label><input class="task_property_field" type="text" name="action[single_update_ok_msg_redirect_url]" /></li>
				<li class="error_msg_message"><label>Error Message: </label><input class="task_property_field" type="text" name="action[single_update_error_msg_message]" /></li>
				<li class="error_msg_redirect_url"><label>Error Redirect Url: </label><input class="task_property_field" type="text" name="action[single_update_error_msg_redirect_url]" /></li>
			</ul>
		</li>
		<li class="single_delete">
			<i class="icon maximize" onClick="PresentationTaskUtil.toggleAdvancedActionSettings(this)"></i>
			<label>Delete Action:</label>
			<input class="task_property_field action_active" type="checkbox" name="action[single_delete]" value="1" />
			<input class="task_property_field confirmation_message" type="text" name="action[single_delete_confirmation_message]" placeHolder="Write here a confirmation message..." />
			
			<ul class="advanced_action_settings">
				<li class="ok_msg_message"><label>Ok Message: </label><input class="task_property_field" type="text" name="action[single_delete_ok_msg_message]" /></li>
				<li class="ok_msg_redirect_url"><label>OK Redirect Url: </label><input class="task_property_field" type="text" name="action[single_delete_ok_msg_redirect_url]" /></li>
				<li class="error_msg_message"><label>Error Message: </label><input class="task_property_field" type="text" name="action[single_delete_error_msg_message]" /></li>
				<li class="error_msg_redirect_url"><label>Error Redirect Url: </label><input class="task_property_field" type="text" name="action[single_delete_error_msg_redirect_url]" /></li>
			</ul>
		</li>
	</ul>
</div>
