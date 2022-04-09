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

include $EVC->getViewPath("admin/choose_available_project"); $projects_html = $main_content; $head .= '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_home.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_home.js"></script>
'; $main_content = '
<div class="admin_panel">
	<div class="title">Dashboard</div>
	
	<ul>
		<li><a href="#projs">Projects</a></li>
		<li><a href="#tutorials">Tutorials</a></li>
		<li><a href="#how_it_works">How it works?</a></li>
	</ul>
	
	<div id="projs" class="projs">
		' . $projects_html . '
	</div>
	
	<div id="how_it_works" class="how_it_works">
		' . $presentation . '
	</div>
	
	<div id="tutorials" class="tutorials">'; foreach ($tutorials as $tutorial) { $main_content .= '
	<div class="card shadow ' . ($tutorial["items"] && !$tutorial["video"] ? "border_bottom" : "") . '">
		<div class="card_header">'; if ($tutorial["image"]) $main_content .= '<img class="card_img_top" src="' . $tutorial["image"] . '" alt="Card image cap" onError="$(this).parent().remove()">'; $main_content .= '</div>
		<div class="card_body">
			<p class="card_title mb-0">' . $tutorial["title"] . '</p>
			' . ($tutorial["description"] ? '<p class="card_description">' . $tutorial["description"] . '</p>' : '') . '
		</div>'; if ($tutorial["items"]) { $main_content .= '<ul class="list_group list_group_flush">'; foreach ($tutorial["items"] as $sub_tutorial) { $id = md5($sub_tutorial["title"]); $main_content .= '<li class="list_group_item collapsed">
				<div class="list_group_item_header" onClick="$(this).parent().toggleClass(\'collapsed\')">

					' . $sub_tutorial["title"] . '
					<span class="dropdown_toggle"></span>
				</div>
				
				<div class="list_group_item_body">'; if ($sub_tutorial["image"]) $main_content .= '<img class="card_img" src="' . $sub_tutorial["image"] . '" alt="Card image cap" onError="$(this).remove()">'; if ($sub_tutorial["description"]) $main_content .= '<span class="description">' . $sub_tutorial["description"] . '</span>'; if ($sub_tutorial["video"]) $main_content .= '<a class="video_link" href="javascript:void(0)" onClick="openVideoPopup(this)" video_url="' . $sub_tutorial["video"] . '"><small>Watch video</small></a>'; $main_content .= '
				</div>
			</li>'; } $main_content .= '</ul>'; } if ($tutorial["video"]) $main_content .= '
		<div class="card_footer">
			<a class="video_link" href="javascript:void(0)" onClick="openVideoPopup(this)" video_url="' . $tutorial["video"] . '"><small>Watch video</small></a>
		</div>'; $main_content .= '
	</div>'; } $main_content .= '
	</div>
</div>

<!--div class="modal fade modal_video text-center" id="modal-video-01" tabindex="-1" role="dialog" aria-hidden="true">
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
</div-->'; ?>
