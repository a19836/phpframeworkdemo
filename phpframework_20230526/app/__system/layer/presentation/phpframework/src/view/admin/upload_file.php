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

include $EVC->getUtilPath("BreadCrumbsUIHandler"); $upload_url = $project_url_prefix . "admin/manage_file?bean_name=$bean_name&bean_file_name=$bean_file_name&filter_by_layout=$filter_by_layout&path=$path&action=upload&item_type=$item_type"; $on_success_js_func = $on_success_js_func ? $on_success_js_func : "refreshAndShowLastNodeChilds"; $head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icon CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Adding DropZone plugin -->
<script src="' . $project_common_url_prefix . 'vendor/dropzone/dropzone.js"></script>
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/dropzone/min/dropzone.min.css">

<!-- Adding Local files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/upload_file.css" type="text/css" charset="utf-8" />
<script>
Dropzone.autoDiscover = false; // Disabling autoDiscover, otherwise Dropzone will try to attach twice.

$(function() {
	var default_error_msg = "Error uploading file! Please try again and if the problem persists, contact the sysadmin...";
	
	var myDropzone = new Dropzone(".dropzone", {
		success: function(file, response, progress_event) {
			//console.log(response);
			if (response != 1) {
				var dz_error_message = $(file.previewElement).removeClass("dz-success").addClass("dz-error").children(".dz-error-message");
				dz_error_message.addClass("show");
				dz_error_message.children("[data-dz-errormessage]").html(response ? response : default_error_msg);
				
				//myDropzone.removeFile(file);
			}
			else if (typeof window.parent.' . $on_success_js_func . ' == "function")
				window.parent.' . $on_success_js_func . '();
		},
		error: function(file, response) {
			var dz_error_message = $(file.previewElement).find(".dz-error-message");
			dz_error_message.addClass("show");
			dz_error_message.children("[data-dz-errormessage]").html(response ? response : default_error_msg);
		}
	});
});
</script>'; $main_content .= '
<div class="top_bar">
	<header>
		<div class="title" title="' . $path . '">Upload Files into  in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($file_path, $obj) . '</div>
	</header>
</div>

<div class="upload_files">
	<form action="' . $upload_url . '" class="dropzone"></form>
</div>'; ?>
