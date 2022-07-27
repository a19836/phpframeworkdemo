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

<!-- Filemanager CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />

<!-- Icons CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS and JS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/layout.js"></script>

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/choose_available_tutorial.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/choose_available_tutorial.js"></script>

<script>
var is_popup = ' . ($popup ? 1 : 0) . ';
</script>'; $main_content = '<div class="choose_available_tutorial ' . ($popup ? " in_popup" : "") . '">
	<div class="title' . ($popup ? " inside_popup_title" : "") . '">Video Tutorials</div>
	<ul>'; foreach ($tutorials as $tutorial) $main_content .= getTutorialHtml($tutorial); function getTutorialHtml($v20f9a15b0d) { if ($v20f9a15b0d["video"] || $v20f9a15b0d["items"]) { $ped0a6251 = ''; $pf9ed8697 = ''; if ($v20f9a15b0d["items"]) { $ped0a6251 = 'onClick="toggleSubTutorials(this)"'; $pf9ed8697 = '<span class="icon dropdown_arrow"></span>'; } else $ped0a6251 = 'onClick="openVideoPopup(this)" video_url="' . $v20f9a15b0d["video"] . '" image_url="' . $v20f9a15b0d["image"] . '"'; $pf8ed4912 = '<li' . ($v20f9a15b0d["items"] ? ' class="with_sub_tutorials"' : '') . '>
					<div class="tutorial_header" ' . $ped0a6251 . '>
						<div class="tutorial_title"><span class="icon video"></span>' . $v20f9a15b0d["title"] . $pf9ed8697 . '</div>
						' . ($v20f9a15b0d["description"] ? '<div class="tutorial_description">' . $v20f9a15b0d["description"] . '</div>' : '') . '
					</div>'; if ($v20f9a15b0d["items"]) { $pf8ed4912 .= '<ul class="sub_tutorials">'; foreach ($v20f9a15b0d["items"] as $v83cf8e0027) $pf8ed4912 .= getTutorialHtml($v83cf8e0027); $pf8ed4912 .= '</ul>'; } $pf8ed4912 .= '</li>'; } return $pf8ed4912; } $main_content .= '
	</ul>
	
	<div class="myfancypopup with_title show_video_popup">
		<div class="title"></div>
		<div class="content">
			<div class="video">
				<iframe width="560" height="315" title="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
			
			<div class="details">
				<img class="image" alt="Card image cap" onError="$(this).hide()">
				<div class="description"></div>
			</div>
		</div>
	</div>
</div>'; ?>
