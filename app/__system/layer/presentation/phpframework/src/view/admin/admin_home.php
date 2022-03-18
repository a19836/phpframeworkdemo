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
<!-- Jquery core JavaScript-->
<script src="' . $project_url_prefix . 'vendor/jquery/jquery.min.js"></script>

<!-- Add Jquery UI JS and CSS files -->
<!--link rel="stylesheet" href="' . $project_url_prefix . 'vendor/jquery-ui/jquery-ui.min.css" type="text/css" /-->
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'vendor/jquery-ui/jquery-ui.min.js"></script>

<!-- Bootstrap core -->
<link href="' . $project_url_prefix . 'vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="' . $project_url_prefix . 'vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="' . $project_url_prefix . 'vendor/jquery-easing/jquery.easing.min.js"></script>

<!--[if lt IE 7 ]>
  <script src="' . $project_url_prefix . 'vendor/drewdiller/dd_belatedpng.js"></script>
  <script>DD_belatedPNG.fix("img, .png_bg"); // Fix any <img> or .png_bg bg-images. Also, please read goo.gl/mZiyb </script>
<![endif]-->

<!-- for-mobile-apps -->
<script type="application/x-javascript"> 
	addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
	function hideURLbar(){ window.scrollTo(0,1); } 
</script>
<!-- //for-mobile-apps -->

<!-- Custom fonts for this template-->
<link href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
<!--script src="' . $project_common_url_prefix . 'vendor/fontawesome/js/all.min.js"></script-->

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_home.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_home.js"></script>
'; $main_content = '<h4 class="text-center mt-3"><small>Admin Panel</small></h4>'; if ($project) $main_content .= '<small class="text-center text-muted d-block">Selected project: "' . $project . '"</small>'; $main_content .= '
<div class="admin_panel mt-3 border-0">
	<ul>
		<li><a href="#tutorials">Tutorials</a></li>
		<li><a href="#how_it_works">How it works?</a></li>
		<li><a href="#projects">Projects</a></li>
	</ul>
	
	<div id="projects" class="projects">
		TODO: show list of projects...
	</div>
	
	<div id="how_it_works" class="how_it_works">
		' . $presentation . '
	</div>
	
	<div id="tutorials" class="tutorials card-columns">'; foreach ($tutorials as $tutorial) { $main_content .= '
	<div class="card shadow ' . ($tutorial["items"] && !$tutorial["video"] ? "border_bottom" : "") . '">'; if ($tutorial["image"]) $main_content .= '
		<div class="card-header">
			<img class="card-img-top" src="' . $tutorial["image"] . '" alt="Card image cap" onError="$(this).parent().remove()">
		</div>'; $main_content .= '
		<div class="card-body">
			<p class="card-title mb-0">' . $tutorial["title"] . '</p>
			' . ($tutorial["description"] ? '<p class="card-text mt-2 text-muted">' . $tutorial["description"] . '</p>' : '') . '
		</div>'; if ($tutorial["items"]) { $main_content .= '<ul class="list-group list-group-flush mb-1">'; foreach ($tutorial["items"] as $sub_tutorial) { $id = md5($sub_tutorial["title"]); $main_content .= '<li class="list-group-item p-0">
				<button class="btn btn-link w-100 text-left pr-4 collapsed" type="button" data-toggle="collapse" data-target="#sub_' . $id . '" aria-expanded="false" aria-controls="sub_' . $id . '">

					' . $sub_tutorial["title"] . '
					<span class="dropdown-toggle"></span>
				</button>
				
				<div id="sub_' . $id . '" class="collapse pl-3 pr-3 pb-2 text-muted">'; if ($sub_tutorial["image"]) $main_content .= '<img class="mr-2 mb-2 border border-muted col-6 align-top" src="' . $sub_tutorial["image"] . '" alt="Card image cap" onError="$(this).remove()">'; if ($sub_tutorial["description"]) $main_content .= '<span class="description">' . $sub_tutorial["description"] . '</span>'; if ($sub_tutorial["video"]) $main_content .= '<a class="video_link mt-2 d-block text-secondary text-right" href="javascript:void(0)" onClick="openVideoPopup(this)" video_url="' . $sub_tutorial["video"] . '"><small>Watch video</small></a>'; $main_content .= '
				</div>
			</li>'; } $main_content .= '</ul>'; } if ($tutorial["video"]) $main_content .= '
		<div class="card-footer text-right">
			<a class="video_link text-secondary" href="javascript:void(0)" onClick="openVideoPopup(this)" video_url="' . $tutorial["video"] . '"><small>Watch video</small></a>
		</div>'; $main_content .= '
	</div>'; } $main_content .= '
	</div>
</div>

<div class="modal fade modal_video text-center" id="modal-video-01" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog d-inline-block" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<small class="modal-title">We look for investment</small>
				<div class="close-mo-icon trans-0-4 c-black" data-dismiss="modal" aria-label="Close">&times;</div>
			</div>
			<div class="modal-body">
				<iframe width="560" height="315" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
			<div class="modal-footer justify-content-start text-muted">
				<small class="modal-description"></small>
			</div>
		</div>
	</div>
</div>'; ?>
