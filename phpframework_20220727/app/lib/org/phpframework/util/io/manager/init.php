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

include_once get_lib("org.phpframework.util.io.exception.MyIOManagerException"); $FileManager = null; if($FILEMANAGER_DATA) { $FILE_MANAGER_TYPE = strtolower($FILEMANAGER_DATA["type"]); $FILE_MANAGER_ARGS = $FILEMANAGER_DATA["args"]; if($FILE_MANAGER_TYPE == "awss3") { include_once get_lib("org.phpframework.util.io.manager.awss3.MyS3Manager"); $FileManager = new MyS3Manager($FILE_MANAGER_ARGS["awsaccesskey"], $FILE_MANAGER_ARGS["awssecretkey"]); $FileManager->setBucket($FILE_MANAGER_ARGS["bucket"]); $FileManager->setRootPath($FILE_MANAGER_ARGS["root_path"]); } else if($FILE_MANAGER_TYPE == "ftp") { include_once get_lib("org.phpframework.util.io.manager.ftp.MyFTPManager"); $FileManager = new MyFTPManager($FILE_MANAGER_ARGS["ftp_host"], $FILE_MANAGER_ARGS["ftp_username"], $FILE_MANAGER_ARGS["ftp_password"], $FILE_MANAGER_ARGS["ftp_port"], array("passive_mode" => $FILE_MANAGER_ARGS["ftp_passive_mode"])); $FileManager->setRootPath($FILE_MANAGER_ARGS["root_path"], $FILE_MANAGER_ARGS["root_path"] ? false : true); } elseif($FILE_MANAGER_TYPE == "file") { include_once get_lib("org.phpframework.util.io.manager.file.MyFileManager"); $FileManager = new MyFileManager(); $FileManager->setRootPath($FILE_MANAGER_ARGS["root_path"], $FILE_MANAGER_ARGS["root_path"] ? false : true); } elseif($FILE_MANAGER_TYPE == "youtube") { } else { launch_exception(new MyIOManagerException(2, $FILE_MANAGER_TYPE)); } if($FILE_MANAGER_ARGS["file_type_allowed"]) $FileManager->setOption("file_type_allowed", $FILE_MANAGER_ARGS["file_type_allowed"]); } else { launch_exception(new MyIOManagerException(1, false)); } ?>
