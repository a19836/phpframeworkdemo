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
?><div class="reset_region_block_join_points_task_html">
	<div class="broker_method_obj" title="Write here the CMS Block Layer variable">
		<label>CMS Block Layer Obj:</label>
		<select onChange="BrokerOptionsUtilObj.onBrokerChange(this)"></select>
		<input type="text" class="task_property_field" name="method_obj" />
		<span class="icon add_variable inline" onClick="BrokerOptionsUtilObj.chooseCreatedBrokerVariable(this)">Search</span>
	</div>
	<div class="region_id">
		<label>Region Id:</label>
		<input type="text" class="task_property_field" name="region_id" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Search</span>
		<select class="task_property_field" name="region_id_type">
			<option>string</option>
			<option>variable</option>
			<option value="">code</option>
		</select>
	</div>
	<div class="block_id">
		<label>Block Id:</label>
		<input type="text" class="task_property_field" name="block_id" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Search</span>
		<select class="task_property_field" name="block_id_type">
			<option>string</option>
			<option>variable</option>
			<option value="">code</option>
		</select>
	</div>
		
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
