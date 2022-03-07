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

<!-- Add Icon CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Top-Bar CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/top_bar.css" type="text/css" charset="utf-8" />

<!-- Layout UI Editor - Add Html/CSS/JS Beautify code -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jsbeautify/js/lib/beautify.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jsbeautify/js/lib/beautify-css.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/myhtmlbeautify/MyHtmlBeautify.js"></script>

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/convert_url_to_template.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_page_and_template.js"></script>
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/convert_url_to_template.js"></script>
'; $main_content = '<div class="convert_url_to_template">
	<div class="top_bar">
		<header>
			<div class="title">
				Convert 
				<input type="text" name="page_url" placeHolder="Page Url" />
				<a class="icon load" onClick="loadUrl(this);" title="Load Url">Load</a>
				To
				<input type="text" name="template_name" placeHolder="Template name" />
			</div>
			<ul>
				<li class="convert_to_region" title="Convert to Region"><a class="update" onClick="convertToRegion()">Convert to Region</a></li>
				<li class="convert_to_param" title="Convert to Param"><a class="update" onClick="convertToParam()">Convert to Param</a></li>
				<li class="save" title="Save as Template"><a class="save" onClick="saveTemplate()">Save as Template</a></li>
			</ul>
		</header>
	</div>'; $main_content .= '
	<div class="page_html">
		<iframe></iframe>
	</div>
</div>'; ?>
