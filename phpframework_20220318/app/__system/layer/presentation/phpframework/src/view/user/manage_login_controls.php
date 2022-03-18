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

include $EVC->getUtilPath("UserAuthenticationUIHandler"); $head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Top-Bar CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/top_bar.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/user/user.css" type="text/css" charset="utf-8" />
'; $main_content = '
<div id="menu">' . UserAuthenticationUIHandler::getMenu($UserAuthenticationHandler, $project_url_prefix) . '</div>
<div id="content">
	<div class="top_bar">
		<header>
			<div class="title">Manage Login Controls</div>
		</header>
	</div>
	
	<div class="login_controls_list">
	<table>
		<tr>
			<th class="table_header username">Username</th>
			<th class="table_header session_id">Session Id</th>
			<th class="table_header failed_login_attempts">Failed Login Attempts</th>
			<th class="table_header failed_login_time">Failed Login Time</th>
			<th class="table_header login_expired_time">Login Expired Time</th>
			<th class="table_header created_date">Created Date</th>
			<th class="table_header modified_date">Modified Date</th>
		</tr>'; $t = count($login_controls); for ($i = 0; $i < $t; $i++) { $login_control = $login_controls[$i]; $main_content .= '<tr>
		<td class="username">' . $login_control["username"] . '</td>
		<td class="session_id">' . $login_control["session_id"] . '</td>
		<td class="failed_login_attempts">' . $login_control["failed_login_attempts"] . '</td>
		<td class="failed_login_time">' . $login_control["failed_login_time"] . '</td>
		<td class="login_expired_time">' . date("Y-m-d H:i:s", $login_control["login_expired_time"]) . '</td>
		<td class="created_date">' . $login_control["created_date"] . '</td>
		<td class="modified_date">' . $login_control["modified_date"] . '</td>
	</tr>'; } $main_content .= '</table>
	</div>
</div>'; ?>
