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

include $EVC->getConfigPath("config"); mcbe6bb6d6830($EVC, $user_global_variables_file_path, $user_beans_folder_path); include $EVC->getUtilPath("sanitize_html_in_post_request", $EVC->getCommonProjectName()); include $EVC->getConfigPath("authentication"); function mcbe6bb6d6830($EVC, $user_global_variables_file_path, $user_beans_folder_path) { $pb9be6168 = substr(LA_REGEX, strpos(LA_REGEX, "]") + 1); if ($pb9be6168 == -1) $v5c1c342594 = true; else if (is_numeric($pb9be6168)) { include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $v6ee393d9fb = CMSPresentationLayerHandler::getPresentationLayersProjectsFiles($user_global_variables_file_path, $user_beans_folder_path, "webroot", false, 0); $v03f4b4ed53 = 0; if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if ($v7dffdb5a5b["projects"]) $v03f4b4ed53 += count($v7dffdb5a5b["projects"]); if ($v03f4b4ed53 <= $pb9be6168) $v5c1c342594 = true; } $v258de04f2e = $v5c1c342594 ? "646566696e65282250524f4a454354535f434845434b4544222c20313233293b" : "596f752065786365656420746865206d6178696d756d206e756d626572206f662070726f6a65637473207468617420796f7572206c6963656e636520616c6c6f772e"; $v1db8fcc7cd = ""; for ($v43dd7d0051 = 0; $v43dd7d0051 < strlen($v258de04f2e) - 1; $v43dd7d0051 += 2) $v1db8fcc7cd .= chr( hexdec($v258de04f2e[$v43dd7d0051] . $v258de04f2e[$v43dd7d0051+1]) ); if ($v5c1c342594) eval($v1db8fcc7cd); else { echo $v1db8fcc7cd; die(1); } } include $EVC->getControllerPath("index", $EVC->getCommonProjectName()); ?>
