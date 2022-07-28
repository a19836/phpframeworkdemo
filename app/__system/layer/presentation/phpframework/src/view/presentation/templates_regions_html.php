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

include_once $EVC->getUtilPath("CMSPresentationLayerUIHandler"); $head = '
<!-- Add Ace Editor CSS and JS -->
<script src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
<script src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add local Responsive Iframe CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/responsive_iframe.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/responsive_iframe.js"></script>

<!-- Add local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/templates_regions_html.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/templates_regions_html.js"></script>

<script>
</script>'; $main_content = '<div class="title' . ($popup ? " inside_popup_title" : "") . '">Templates Regions Html</div>
<div class="templates_regions_html_obj' . ($popup ? " in_popup" : "") . '">'; if ($available_templates_regions) { $main_content .= '<ul>'; foreach ($available_templates_regions as $template => $regions) { $template_samples_url = $project_url_prefix . "phpframework/presentation/template_samples?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$selected_project_id/src/template/" . $template . ".php"; $main_content .= '
		<li class="template">
			<div class="header"><span class="icon view" onClick="openTemplateSamples(this)" title="View Template" template_samples_url="' . $template_samples_url . '">Info</span> ' . $template . ' <span class="icon maximize" onClick="toggleContent(this)">Maximize</span></div>
			<ul class="content">'; foreach ($regions as $region_name => $region_samples) { $main_content .= '
			<li class="region">
				<div class="header">' . $region_name . ' <span class="icon maximize" onClick="toggleContent(this)">Maximize</span></div>
				<ul class="content">'; foreach ($region_samples as $sample_name => $sample_data) { $sample_path = $sample_data["sample_path"]; $template_path = $sample_data["template_path"]; $html = $sample_data["html"]; $sample_url = $project_url_prefix . "phpframework/presentation/template_region_sample?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$template_path&region=$region_name&sample_path=$sample_path"; $main_content .= '<li class="sample">
					<div class="header"><input type="radio" name="template_region" value="1" />' . $sample_name . ' <span class="icon maximize" onClick="toggleContent(this)">Maximize</span></div>
					
					<div class="content">
						<ul>
							<li><a href="#view_ui">View UI</a></li>
							<li><a href="#view_source">HTML Source</a></li>
						</ul>
						
						<div id="view_ui" class="view_ui">
							<div class="iframe_toolbar desktop">
								' . CMSPresentationLayerUIHandler::getTabContentTemplateLayoutIframeToolbarContentsHtml() . '
							</div>
							<iframe orig_src="' . $sample_url . '"></iframe>
						</div>
						<div id="view_source" class="view_source">
							<textarea>' . htmlspecialchars($html, ENT_NOQUOTES) . '</textarea>
						</div>
					</div>
				</li>'; } $main_content .= '</ul></li>'; } $main_content .= '</ul></li>'; } $main_content .= '</ul>'; } else $main_content .= '<div class="no_templates_regions_html">There are no templates regions html!</div>'; $main_content .= '</div>'; ?>
