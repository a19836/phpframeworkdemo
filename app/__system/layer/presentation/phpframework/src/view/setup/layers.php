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

include $EVC->getViewPath("/layer/diagram"); $confirm_msg = $diagram_already_exists ? "If you added new DataBases in this diagram, you can have some issues in the future, because when you did the installation, there were some tables that were created automatically, which will not be created in the new DataBases, this is, tables from installed Modules and maybe from the CMS authentication system...\\n\\nDo you still wish to continue?" : "We will save this workflow automatically. Do you wish to continue?"; $head .= '
<script>
	function continueSetup(do_not_confirm) {
		if (do_not_confirm || confirm("' . $confirm_msg . '")) {
			var popup = jsPlumbWorkFlow.getMyFancyPopupObj();
			MyFancyPopup.init();
			MyFancyPopup.showOverlay();
			MyFancyPopup.showLoading();
			
			$(window).unbind("beforeunload");
			
			var save_options = {
				success: function(data, textStatus, jqXHR) {
					if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
						showAjaxLoginPopup(jquery_native_xhr_object.responseURL, jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url, function() {
							jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
							continueSetup(true);
						});
				},
			};
			
			if (jsPlumbWorkFlow.jsPlumbTaskFile.save(null, save_options))
				$("#layer_form form").submit();
			else
				MyFancyPopup.hidePopup();
		}
	}
</script>'; $main_content .= '<div id="layer_form" style="display:none">
	<form method="post">
		<input type="hidden" name="create_layers_workflow" value="1" />'; if ($tasks_folders) foreach ($tasks_folders as $task_id => $folder) $main_content .= '
		<input type="hidden" name="tasks_folders[' . $task_id . ']" value="' . $folder . '" />'; $main_content .= '
	</form>
</div>'; $continue_function = "continueSetup()"; $back_function = "document.location='?step=3&iframe=$is_inside_of_iframe'"; $back_label = "BEGINNER"; ?>
