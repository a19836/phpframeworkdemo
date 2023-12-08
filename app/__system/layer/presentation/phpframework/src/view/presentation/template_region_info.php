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
$sample_url = $project_url_prefix . "phpframework/presentation/template_region_sample?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&region=$region&sample_path="; $head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/template_region_info.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/template_region_info.js"></script>

<script>
</script>'; $main_content = '<div class="title' . ($popup ? " inside_popup_title" : "") . '">Template Region Samples for "' . $region . '"</div>
<div class="template_region_obj">'; if ($sample_files) { $main_content .= '<ul>'; foreach ($sample_files as $sample_file) { $main_content .= '<li>
		<div class="header">' . pathinfo($sample_file, PATHINFO_FILENAME) . ' <span class="icon maximize" onClick="toggleSampleContent(this)">Maximize</span></div>
		
		<div class="sample">
			<ul>
				<li><a href="#view_ui">View UI</a></li>
				<li><a href="#view_source">HTML Source</a></li>
			</ul>
			
			<iframe id="view_ui" orig_src="' . $sample_url . $sample_file . '"></iframe>
			<textarea id="view_source">' . htmlspecialchars(file_get_contents($layer_path . $sample_file), ENT_NOQUOTES) . '</textarea>
		</div>
	</li>'; } $main_content .= '</ul>'; } else $main_content .= '<div class="no_template_regions">This template region does not have any samples!</div>'; $main_content .= '</div>'; ?>
