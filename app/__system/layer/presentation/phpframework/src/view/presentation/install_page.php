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

<!-- Add Icon CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/install_page.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/install_page.js"></script>

<script>
var get_store_pages_url = "' . $project_url_prefix . "phpframework/admin/get_store_type_content?type=pages" . '"; //This is a global var
var is_popup = ' . ($popup ? 1 : 0) . ';
</script>'; $main_content = '
	<div class="top_bar' . ($popup ? " in_popup" : "") . '">
		<header>
			<div class="title" title="' . $path . '">Install New Pre-built Page in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($path, $P) . '</div>
			<ul>
				<li class="continue" data-title="Install Pre-built Page Now"><a onClick="installPage(this)"><i class="icon continue"></i> Install Pre-built Page Now</a></li>
			</ul>
		</header>
	</div>'; if ($_POST) { if (!$status) { $error_message = $error_message ? $error_message : "There was an error trying to install this pre-built page. Please try again..."; if ($messages) { $main_content .= '<ul class="messages">'; foreach ($messages as $msg) $main_content .= '<li class="' . $msg["type"] . '">' . $msg["msg"] . '</li>'; $main_content .= '</ul>'; } } else { $status_message = 'Pre-built page successfully installed!'; $on_success_js_func = $on_success_js_func ? $on_success_js_func : "refreshAndShowLastNodeChilds"; $main_content .= "<script>if (typeof window.parent.$on_success_js_func == 'function') window.parent.$on_success_js_func();</script>"; } } if ($show_install_page) { $main_content .= '
<div class="install_page">
	<ul>
		' . ($get_store_pages_url ? '<li><a href="#store">Store Pages</a></li>' : '') . '
		<li><a href="#local">Upload Local Pre-built Page</a></li>
	</ul>
	<div id="local" class="file_upload">
		<div class="title">Install a local pre-built page from your computer (.zip file)</div>
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="dummy_for_post_var_exists" value="1">
			<input class="upload_file" type="file" name="zip_file">
		</form>'; $main_content .= '
		' . ($pages_download_page_url ? '<div class="go_to_pages_download_page">To download pre-built pages to your local computer, please click <a href="' . $pages_download_page_url . '" target="download_pages">here</a></div>' : '') . '
	
	</div>
	
	' . ($get_store_pages_url ? '
	<div id="store" class="install_store_page">
		<div class="title">Install a pre-built page from our store</div>
		<div class="search_page">
			<i class="icon search active"></i>
			<input placeHolder="Search" onKeyUp="searchPages(this)" />
			<i class="icon close" onClick="resetSearchPages(this)"></i>
		</div>
		<ul>
			<li class="loading">Loading pre-built pages from store...</li>
		</ul>
	</div>' : '') . '
</div>'; } ?>
