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

class WorkFlowTaskException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { switch($v6de691233b) { case 1: $this->problem = "Error parsing xml for task with ID: '$v67db1bd535'!"; break; case 2: $this->problem = "Task with ID: '$v67db1bd535' does NOT exist!"; break; case 3: $this->problem = "Invalid Task Class for class: $v67db1bd535! All the Task classes must extend the WorkFlowTask class, which is not this case."; break; case 4: $this->problem = "Error trying to create class object for class: $v67db1bd535."; break; case 5: $this->problem = "Error \$task[OBJ] variable is not an object from the class: " . $v67db1bd535[0] . ". Object " . get_class($v67db1bd535[1]) . " is incorrect! Probably this task type does NOT exist!"; break; case 6: $this->problem = "Error Could Not clone " . $v67db1bd535[0] . " obj class. Error \$task[OBJ] variable is not an object from the class: " . $v67db1bd535[0] . ". Object " . get_class($v67db1bd535[1]) . " is incorrect!"; break; case 7: $this->problem = "Workflow webroot folder path cannot be empty!"; break; case 8: $this->problem = "Error creating folder: '$v67db1bd535'!"; break; case 9: $this->problem = "Error copying file from: '" . $v67db1bd535[0] . "' to '" . $v67db1bd535[1] . "'!"; break; case 10: $this->problem = "Wrong namespace in file: '$v67db1bd535'!"; break; case 11: $this->problem = ""; break; case 12: $this->problem = "Class don't exist: '$v67db1bd535'!"; break; case 13: $this->problem = "Workflow webroot folder domain cannot be empty!"; break; case 14: $this->problem = "Error trying to get the URL prefix for the webroot of the task: '$v67db1bd535'!"; break; } } } ?>
