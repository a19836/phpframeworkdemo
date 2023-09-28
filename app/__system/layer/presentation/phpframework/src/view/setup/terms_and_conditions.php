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
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<script>
	function selectDependenciesInstallation(elm) {
		var btn = $("#setup .buttons .ok")[0];
		
		if (elm.checked)
			btn.value = "Continue with dependencies";
		else
			btn.value = "Continue without dependencies";
	}
	
	function continueSetup() {
		if ($("#terms_and_conditions .acceptance input").is(":checked") && $("#terms_and_conditions .dependencies input").is(":checked")) {
			var btn = $("#setup .buttons .ok")[0];
			btn.value = "Downloading and installing dependencies...";
			btn.setAttribute("disabled", "disabled");
		}
		
		$("#terms_and_conditions form").submit();
	}
</script>'; $main_content = '<div id="terms_and_conditions">
	<form method="post" onSubmit="return MyJSLib.FormHandler.formCheck(this);">
		<div class="title">
			<h1>Terms and Conditions</h1>
		</div>
		<div class="info">' . $terms_and_conditions . '</div>
		<div class="acceptance">
			<label>
				<input type="checkbox" name="acceptance" value="1" allownull="false" validationmessage="Please accept the terms and conditions first." required ' . ($_POST["acceptance"] ? "checked" : "") . ' />
				Please accept the terms and conditions.
			</label>
		</div>
		<div class="dependencies">
			<label>
				<input type="checkbox" name="dependencies" value="1" checked onChange="selectDependenciesInstallation(this)" />
				Select this option if you want to install third-party libraries to get full functionality.
			</label>
		</div>
	</form>
</div>
<style> #setup .buttons .cancel {display:none;} </style>
<script>selectDependenciesInstallation( $("#terms_and_conditions .dependencies input")[0] );</script>'; $continue_function = "continueSetup()"; ?>
