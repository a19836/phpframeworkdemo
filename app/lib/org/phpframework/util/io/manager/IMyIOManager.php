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
interface IMyIOManager { public function add($v3fb9f41470, $v17be587282, $v5e813b295b, $v30857f7eca = array()); public function edit($v17be587282, $v5e813b295b, $v30857f7eca = array()); public function delete($v3fb9f41470, $v17be587282, $v5e813b295b); public function copy($v3fb9f41470, $pc941b4ab, $v23d7f19208, $v525288e856, $v30857f7eca = array()); public function move($v3fb9f41470, $pc941b4ab, $v23d7f19208, $v525288e856, $v30857f7eca = array()); public function rename($v17be587282, $v0c4b06ddf7, $pe6871e84, $v30857f7eca = array()); public function getFile($v17be587282, $v5e813b295b); public function getFileInfo($v17be587282, $v5e813b295b); public function getFileNameExtension($v5e813b295b); public function getFiles($v17be587282); public function getFilesCount($v17be587282); public function upload($v6eee6903b3, $v17be587282, $pe6871e84, $v30857f7eca = array()); public function exists($v17be587282, $v5e813b295b); public function setOptions($v5d3813882f); public function setOption($pe238ca78, $v67db1bd535); } ?>
