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
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/template_samples.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/template_samples.js"></script>

<script>
</script>'; $main_content = '<div class="title' . ($popup ? " inside_popup_title" : "") . '">Template Samples</div>
<div class="template_samples_obj">'; if ($sample_files) { $main_content .= '<ul>'; foreach ($sample_files as $sample_file) { $main_content .= '<li>
		<div class="header">' . pathinfo($sample_file, PATHINFO_FILENAME) . ' <span class="icon maximize" onClick="toggleSampleContent(this)">Maximize</span></div>
		
		<div class="sample">
			<div class="iframe_toolbar desktop">
				' . CMSPresentationLayerUIHandler::getTabContentTemplateLayoutIframeToolbarContentsHtml() . '
			</div>
			<iframe id="view_ui" orig_src="' . $sample_file . '"></iframe>
		</div>
	</li>'; } $main_content .= '</ul>'; } else $main_content .= '<div class="no_template_samples">This template does not have any samples!</div>'; $main_content .= '</div>'; ?>
