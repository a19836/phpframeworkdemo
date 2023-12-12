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
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $username = $_GET["username"]; if ($username) { $login_control_data = $UserAuthenticationHandler->getLoginControl($username); } if ($_POST) { if ($username && $login_control_data) { if ($_POST["delete"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); if ($username && $UserAuthenticationHandler->deleteLoginControl($username)) { die("<script>alert('Username data deleted successfully'); document.location = '$project_url_prefix/user/manage_login_controls';</script>"); } else { $error_message = "There was an error trying to delete this Username data. Please try again..."; } } else if ($_POST["reset_failed_login_attempts"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); if ($UserAuthenticationHandler->resetFailedLoginAttempts($username)) { die("<script>alert('Username reset successfully'); document.location = '$project_url_prefix/user/manage_login_controls';</script>"); } else { $error_message = "There was an error trying to reset this username. Please try again..."; } } } else { $error_message = "No username data to reset."; } } ?>
