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
?><div class="inner_task_settings">
	<ul>
		<li><a href="#inner_task_parent_link_settings">Parent Link Settings</a></li>
		<li><a href="#inner_task_interface_settings">Interface Settings</a></li>
	</ul>
	
	<div id="inner_task_parent_link_settings">
		<div class="parent_link_class">
			<label>Parent Link Class:</label>
			<input class="task_property_field" type="text" name="parent_link_class" />
		</div>
		
		<div class="parent_link_value">
			<label>Parent Link Value:</label>
			<input class="task_property_field" type="text" name="parent_link_value" />
		</div>
		
		<div class="parent_link_title">
			<label>Parent Link Title:</label>
			<input class="task_property_field" type="text" name="parent_link_title" />
		</div>
		
		<div class="parent_link_previous_html">
			<label>Parent Link Previous Html:</label>
			<textarea class="task_property_field" name="parent_link_previous_html"></textarea>
		</div>
		
		<div class="parent_link_next_html">
			<label>Parent Link Next Html:</label>
			<textarea class="task_property_field" name="parent_link_next_html"></textarea>
		</div>
	</div>
	
	<div id="inner_task_interface_settings">
		<div class="interface_type">
			<label>Interface Type:</label>
			<select class="task_property_field" name="interface_type">
				<option value="embeded">Embeded</option>
				<option value="popup">Popup</option>
			</select>
		</div>

		<div class="interface_class">
			<label>Interface Class:</label>
			<input class="task_property_field" type="text" name="interface_class" />
		</div>

		<div class="interface_previous_html">
			<label>Interface Previous Html:</label>
			<textarea class="task_property_field" name="interface_previous_html"></textarea>
		</div>

		<div class="interface_next_html">
			<label>Interface Next Html:</label>
			<textarea class="task_property_field" name="interface_next_html"></textarea>
		</div>
	</div>
</div>
