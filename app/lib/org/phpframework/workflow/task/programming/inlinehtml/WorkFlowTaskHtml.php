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
?><div class="inlinehtml_task_html">
	<ul>
		<li id="inlinehtml_code_editor_tab"><a href="#inlinehtml_code" onClick="InlineHTMLTaskPropertyObj.updateHtmlFromWysiwygEditor(this)">Code</a></li>
		<li id="inlinehtml_wysiwyg_editor_tab"><a href="#inlinehtml_wysiwyg" onClick="InlineHTMLTaskPropertyObj.updateHtmlFromCodeEditor(this)">WYSIWYG</a></li>
	</ul>
	
	<div id="inlinehtml_code">
		<textarea></textarea>
	</div>
	
	<div id="inlinehtml_wysiwyg">
		<textarea></textarea>
	</div>
	
	<!-- MY LAYOUT UI EDITOR -->
	<div class="layout_ui_editor">
		<ul class="menu-widgets hidden"></ul><!--  Menu widgets will be added later -->
		<div class="template-source"><textarea></textarea></div>
	</div>
	
	<textarea class="task_property_field" name="code" style="display:none"></textarea>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#6ea935"></div>
</div>
