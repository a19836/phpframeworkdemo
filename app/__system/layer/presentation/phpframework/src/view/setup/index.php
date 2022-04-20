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

$head = ""; include $EVC->getViewPath($page); $head .= '<link rel="stylesheet" href="' . $project_url_prefix . 'css/setup.css" type="text/css" charset="utf-8" />

<script>
function cancelSetup() {'; if ($is_inside_of_iframe) { $head .= '	
		if (confirm("Are you sure you wish to cancel?")) {
			var url = window.top.location;
			window.top.location = url;
			//window.parent.location = url;
		}'; } else { $head .= '	
		if (confirm("Are you sure you wish to cancel and exit from this application?")) {
			window.open("", "_self", ""); //bug fix
			window.close();
		
			//If not closed:
			window.location = "error";
		}'; } $head .= '
	return false;
}
</script>'; $main_content_aux = '<div id="setup">'; if (!empty($continue_function) || !empty($back_function)) { $main_content_aux .= '<div class="buttons">'; if (!empty($continue_function)) { $main_content_aux .= '<input class="ok" type="button" name="continue" value="CONTINUE" onClick="return ' . $continue_function . '" />'; } if (!empty($back_function)) { $back_label = empty($back_label) ? "BACK" : $back_label; $main_content_aux .= '<input class="back" type="button" name="back" value="' . $back_label . '" onClick="return ' . $back_function . '" />'; } $main_content_aux .= '<input class="cancel" type="button" name="cancel" value="Cancel" onClick="return cancelSetup();" />
	</div>'; } $main_content_aux .= $main_content . '
</div>'; $main_content = $main_content_aux; ?>
