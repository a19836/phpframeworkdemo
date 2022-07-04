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

include $EVC->getUtilPath("BreadCrumbsUIHandler"); $head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/cms/wordpress/manage.css" type="text/css" charset="utf-8" />
'; $main_content = '
<div class="top_bar">
	<header>
		<div class="title">Manage WordPress in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($selected_project_id, $P) . '</div>
	</header>
</div>
<div class="manage_wordpress with_top_bar_section">
	<form method="post">'; if (is_array($projects)) { $main_content .= '
		<div class="project">
			<label>Please choose a Project:</label>
			<select name="project">'; foreach ($projects as $project) $main_content .= '<option>' . $project . '</option>'; $main_content .= '
			</select>
		</div>'; } $main_content .= '
		<div class="db_driver">
			<label>Please choose a DB Driver:</label>
			<select name="db_driver">'; if ($layer_db_drivers) { $installed_options = $non_installed_options = ""; foreach ($layer_db_drivers as $db_driver_name => $db_driver_props) { $is_installed = in_array($db_driver_name, $installed_wordpress_folders_name); $option = '<option value="' . $db_driver_name . '"' . ($selected_db_driver == $db_driver_name ? ' selected' : '') . '>' . $db_driver_name . ($db_driver_props ? '' : ' (Rest)') . '</option>'; if ($is_installed) $installed_options .= $option; else $non_installed_options .= $option; } foreach ($installed_wordpress_folders_name as $folder_name) if (!array_key_exists($folder_name, $layer_db_drivers)) $installed_options .= '<option value="' . $folder_name . '"' . ($selected_db_driver == $db_driver_name ? ' selected' : '') . '>' . ucwords(str_replace("_", " ", $folder_name)) . ' - INACCESSIBLE DB DRIVER</option>'; if ($installed_options) $main_content .= "<optgroup label=\"Installed\">$installed_options</optgroup>"; if ($non_installed_options) $main_content .= "<optgroup label=\"Not Installed yet\">$non_installed_options</optgroup>"; } $main_content .= '
			</select>
		</div>
		
		<div class="buttons">
			<input type="submit" name="go_to_wordpress" value="Go to WordPress" />
			<input type="submit" name="install_wordpress" value="Install WordPress" />
		</div>
	</form>
</div>'; ?>
