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

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/cms/wordpress/install.css" type="text/css" charset="utf-8" />
'; $msg = $is_installed ? 'already has the wordpress installed!<br/>If you wish to reinstalled it please click in the button bellow, but all wordpress\'s previous data will be lost...' : 'doesn\'t have the wordpress installed.<br/>To proceed with it installation, please click in the button bellow.<br/>Note that the Wordpress framework has a GPL licence.'; $main_content = '
<div class="top_bar">
	<header>
		<div class="title" title="' . $path . '">Install WordPress in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($selected_project_id, $P) . '</div>
	</header>
</div>
<div class="install_wordpress with_top_bar_section">
	<label>The DB Driver "' . $db_driver . '" ' . $msg . '</label>
	
	<form method="post">
		<input class="button" type="submit" name="install" value="' . ($is_installed ? 'Reinstall' : 'Install') . ' WordPress in \'' . $db_driver . '\' DB Driver" name="submit" onClick="$(this).parent().prepend(\'<div>Installing...</div>\').find(\'input, p\').hide()">
		
		' . ($is_installed ? '<input class="button" type="submit" name="hack" value="Re-Hacking WordPress in \'' . $db_driver . '\' DB Driver" name="submit" onClick="$(this).parent().prepend(\'<div>Hacking...</div>\').find(\'input, p\').hide()">' : '') . '
		
		' . ($is_installed ? '<p>Note that Reinstalling or Re-Hacking WordPress is extremelly inadvisable and imprudent.<br/>Are you really sure, you wish to continue?</p>' : '') . '
	</form>
</div>'; if ($error_message) $main_content .= '<script>alert("' . addcslashes($error_message, '"') . '");</script>'; ?>
