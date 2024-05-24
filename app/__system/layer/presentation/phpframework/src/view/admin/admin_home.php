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
include $EVC->getViewPath("admin/choose_available_project"); $projects_head = $head; $projects_main_content = $main_content; include $EVC->getViewPath("admin/choose_available_tutorial"); $tutorials_head = $head; $tutorials_main_content = $main_content; $logged_name = $UserAuthenticationHandler->auth["user_data"]["name"] ? $UserAuthenticationHandler->auth["user_data"]["name"] : $UserAuthenticationHandler->auth["user_data"]["username"]; $head = $projects_head . $tutorials_head . '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_home.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_home.js"></script>

<script>
var is_popup = 1; //must be 1 so when we choose the project, it refresh the main panel.
</script>
'; $main_content = '
<div class="admin_panel">
	<div class="title">Welcome to Bloxtor, ' . $logged_name . '</div>
	
	<ul>
		<li><a href="#projs">All Projects</a></li>
		<li><a href="#tutorials">Video Tutorials</a></li>
		<li><a onClick="openOnlineTutorialsPopup(\'' . $online_tutorials_url_prefix . '\')">How it works?</a></li>
	</ul>
	
	<div id="projs" class="projs">
		' . $projects_main_content . '
	</div>
	
	<div id="tutorials" class="tutorials">
		' . $tutorials_main_content . '
	</div>
</div>'; ?>
