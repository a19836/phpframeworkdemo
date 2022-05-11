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
?><div class="loop_task_html">
	<div class="init_counters">
		<label>Init Counters:</label>
		<a class="icon variable_add" onclick="LoopTaskPropertyObj.addInitCounterVariable(this)" title="Add Variable">Add Var</a>
		<a class="icon code_add" onclick="LoopTaskPropertyObj.addInitCounterCode(this)" title="Add Code">Add Code</a>
		
		<div class="fields">
			<ul></ul>
		</div>
	</div>
	
	<div class="test_counters">
		<label>Test Counters:</label>
		
		<div class="conditions"></div>
	</div>
	
	<div class="increment_counters">
		<label>Increment Counters:</label>
		<a class="icon variable_add" onclick="LoopTaskPropertyObj.addIncrementCounterVariable(this)" title="Add Variable">Add Var</a>
		<a class="icon code_add" onclick="LoopTaskPropertyObj.addIncrementCounterCode(this)" title="Add Code">Add Code</a>
		
		<div class="fields">
			<ul></ul>
		</div>
	</div>
	
	<div class="other_settings">
		<label>Other Settings:</label>
	
		<div class="execute_first_iteration">
			<label>Always execute the first iteration:</label>
			<input class="task_property_field" type="checkbox" name="execute_first_iteration" value="1" />
		</div>
	</div>
	
	<div class="task_property_exit" exit_id="start_exit" exit_color="#335ACC" exit_label="Start loop"></div>
	<div class="task_property_exit" exit_id="default_exit" exit_color="#2C2D34" exit_label="End loop"></div>
</div>
