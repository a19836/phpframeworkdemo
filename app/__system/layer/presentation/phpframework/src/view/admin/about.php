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
$logged_name = $UserAuthenticationHandler->auth["user_data"]["name"] ? $UserAuthenticationHandler->auth["user_data"]["name"] : $UserAuthenticationHandler->auth["user_data"]["username"]; $head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icon CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/about.css" type="text/css" charset="utf-8" />
'; $main_content = '
<div class="about">
	<div class="top_bar">
		<header>
			<div class="title">About</div>
		</header>
	</div>

	<div class="author">
		<label>Author:</label>
		<span><a href="https://www.jplpinto.com" target="jplpinto">João Pinto - www.jplpinto.com</a></span>
	</div>
	<div class="company">
		<label>Company:</label>
		<span><a href="https://www.onlineit.pt" target="onlineit">www.onlineit.pt</a></span>
	</div>
	<div class="framework">
		<label>Framework:</label>
		<span><a href="https://www.bloxtor.com" target="bloxtor">www.bloxtor.com</a></span>
	</div>
	<div class="version">
		<label>Version:</label>
		<span>' . $version . '</span>
	</div>
	<div class="projects_expiration_date">
		<label>Projects Expiration Date:</label>
		<span>' . ($li_data["dep"] == -1 ? "Unlimited" : $li_data["dep"]) . '</span>
	</div>
	<div class="sysadmin_expiration_date">
		<label>SysAdmin Expiration Date:</label>
		<span>' . $li_data["des"] . '</span>
	</div>
	<div class="projects_maximum_number">
		<label>Projects Maximum Number:</label>
		<span>' . ($li_data["nmp"] == -1 ? "Unlimited" : $li_data["nmp"] - 1) . '</span>
	</div>
	<div class="users_maximum_number">
		<label>Developer-Users Maximum Number:</label>
		<span>' . ($li_data["nmu"] == -1 ? "Unlimited" : $li_data["nmu"]) . '</span>
	</div>
	<div class="end_users_maximum_number">
		<label>End-Users Maximum Number:</label>
		<span>' . ($li_data["nmue"] == -1 ? "Unlimited" : $li_data["nmue"]) . '</span>
	</div>
	<div class="actions_maximum_number">
		<label>Actions Maximum Number:</label>
		<span>' . ($li_data["nma"] == -1 ? "Unlimited" : $li_data["nma"]) . '</span>
	</div>
	<div class="used_actions_total">
		<label>Used Actions Total:</label>
		<span>' . ($user_actions_count ? $user_actions_count : 0) . '</span>
	</div>
	<div class="logged_as">
		<label>Logged as:</label>
		<span>' . $logged_name . ' (<a href="javascript:void(0)" onClick="logout()">logout</a>)</span>
	</div>
	<div class="license">
		<label>License:</label>
		<span>&lt;root path&gt;/' . basename($license_path) . '</span>
		<iframe src="' . $project_url_prefix . 'license"></iframe>
	</div>
</div>

<script>
function logout() {
	var url = "' . $project_url_prefix . 'auth/logout";
	
	if (window.parent && window.parent.document && window.parent.document.location)
		window.parent.document.location = url;
	else
		window.document.location = url;
}
</script>'; ?>
