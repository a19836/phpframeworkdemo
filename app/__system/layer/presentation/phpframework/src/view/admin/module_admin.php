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
<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/module_admin.css" type="text/css" charset="utf-8" />
'; $main_content = '
<div class="top_bar' . ($popup ? ' in_popup' : '') . '">
	<header>
		<div class="title">"' . ucwords($group_module_id) . '" Module Admin Panel</div>
	</header>
</div>
<div class="module_admin">
	<label>Please choose a project:</label>
	
	<form method="post">
		<select name="project">'; if ($projects) { $previous_folder = null; foreach ($projects as $project_name => $project) { if ($project["item_type"] != "project_common") { $project_folder = dirname($project_name); $project_folder = $project_folder == "." ? "" : $project_folder; if ($project_folder && $project_folder != $previous_folder) { $main_content .= '<option disabled>' . str_repeat("&nbsp;&nbsp;&nbsp;", substr_count($project_folder, '/')) . basename($project_folder) . '</option>'; $previous_folder = $project_folder; } $main_content .= '<option value="' . $project_name . '"' . ($selected_project == $project_name ? ' selected' : '') . '>' . str_repeat("&nbsp;&nbsp;&nbsp;", substr_count($project_name, '/')) . $project_name . '</option>'; } } } $main_content .= '</select>
		
		<input class="button" type="submit" value="Go to Module Admin Panel" name="submit">
	</form>
</div>'; ?>
