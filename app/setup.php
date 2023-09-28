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
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="icon" href="data:;base64,=" />
	<link rel="stylesheet" href="__system/common/css/global.css" type="text/css" charset="utf-8" />
	<style>
		body {width:100%; font-family:verdana,courier,arial; font-size:14px;}
		h1 {text-align:center; width:100%; height:30px; font-size:20px; color:#333;}
		ul {padding-left:0px !important;}
		li {margin-top:10px; margin-left:20px !important;}
		ol {margin-left:20px !important;}
		ol li {list-style:number !important; margin-top:20px;}
		ul {margin-left:20px !important; margin-top:10px;}
		ul li {list-style:square !important; margin-top:0px; font-style:italic;}
		.setup {width:1000px; margin:0 auto;}
		.writeable {color:#009900; font-weight:bold;}
		.non_writeable {color:#CC0000; font-weight:bold;}
		.continue , .continue a {font-weight:bold; font-style:italic;}
		.enjoy {width:100%; text-align:center; margin-top:50px; margin-bottom:20px;}
		.disable, .disable .writeable, .disable .continue {color:#999;}
	</style>
</head>
<body>
	<h1>SETUP</h1>
	<div class="setup">
<?php
$dir_path = str_replace(DIRECTORY_SEPARATOR, "/", __DIR__) . "/"; $installation_dir = dirname($dir_path) . "/"; $main_status = true; $tmp_path = (sys_get_temp_dir() ? sys_get_temp_dir() : "/tmp") . "/phpframework/";@mkdir($tmp_path, 0755, true); $files = array( $tmp_path, $installation_dir . "vendor/", $installation_dir . "vendor/dao/", $installation_dir . "vendor/codeworkfloweditor/", $installation_dir . "vendor/codeworkfloweditor/task/", $installation_dir . "vendor/layoutuieditor/", $installation_dir . "vendor/layoutuieditor/widget/", $installation_dir . "vendor/testunit/", $installation_dir . "other/authdb/", $installation_dir . "other/authdb/permission.tbl", $installation_dir . "other/authdb/user.tbl", $installation_dir . "other/authdb/user_type.tbl", $installation_dir . "other/authdb/user_type_permission.tbl", $installation_dir . "other/authdb/user_stats.tbl", $installation_dir . "other/authdb/user_user_type.tbl", $installation_dir . "other/authdb/login_control.tbl", $installation_dir . "other/authdb/layout_type.tbl", $installation_dir . "other/authdb/layout_type_permission.tbl", $installation_dir . "other/authdb/module_db_table_name.tbl", $installation_dir . "other/authdb/object_type.tbl", $installation_dir . "other/authdb/reserved_db_table_name.tbl", $installation_dir . "other/workflow/", $installation_dir . "app/config/", $installation_dir . "app/layer/", $installation_dir . "app/lib/vendor/", $installation_dir . "app/__system/config/global_settings.php", $installation_dir . "app/__system/config/global_variables.php", $installation_dir . "app/__system/layer/presentation/phpframework/src/config/authentication.php", $installation_dir . "app/__system/layer/presentation/phpframework/webroot/vendor/", $installation_dir . "app/__system/layer/presentation/phpframework/webroot/__system/", $installation_dir . "app/__system/layer/presentation/test/webroot/__system/", $installation_dir . "app/__system/layer/presentation/common/webroot/__system/", $installation_dir . "app/__system/layer/presentation/common/src/module/", $installation_dir . "app/__system/layer/presentation/common/webroot/module/", $installation_dir . "app/__system/layer/presentation/common/webroot/vendor/", ); $optional_files = array( $installation_dir . "other/authdb/layout_type.tbl", $installation_dir . "other/authdb/layout_type_permission.tbl", $installation_dir . "app/__system/layer/presentation/test/webroot/__system/", $installation_dir . "app/__system/layer/presentation/common/webroot/__system/", ); $html = "<ol>
	<li>Please point the Document Root from your WebServer to the $dir_path folder!</li>
	<li>Install the PHP 5.6 or higher.</li>
	<li>Please be sure that your WebServer has the mod_rewrite enable and the php.ini files are well configured, this is, for security and performance reasons, please update your php.ini files with:
		<ul>
			<li>short_open_tag = On</li>
			<li>max_execution_time = 1000</li>
			<li>variables_order = \"EGPCS\"</li>
			<li>upload_max_filesize = 64M</li>
			<li>post_max_size = 64M</li>
			<li>date.timezone = Europe/Lisbon</li>
			
			<li>open_basedir = \"" . $installation_dir . "\"</li>
			<li>sys_temp_dir = \"" . $installation_dir . "tmp\"</li>
			<li>upload_tmp_dir = \"" . $installation_dir . "tmp\"</li>
			<li>session.save_path = \"" . $installation_dir . "tmp\"</li>
			<li>soap.wsdl_cache_dir = \"" . $installation_dir . "tmp\"</li>

			<li>error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT</li>
			<li>display_errors = Off</li>
			<li>display_startup_errors = Off</li>
			<li>log_errors = On</li>

			<li>expose_php = Off</li>
			<li>mail.add_x_header = Off</li>
			<li>session.cookie_httponly = On</li>
			<li>session.cookie_secure = On</li>
			<li>session.use_strict_mode = On</li>
			<li>allow_url_fopen = Off</li>
			<li>allow_url_include = Off</li>

			<li>disable_functions = dl,pcntl_alarm,pcntl_fork,pcntl_waitpid,pcntl_wait,pcntl_wifexited,pcntl_wifstopped,pcntl_wifsignaled,pcntl_wifcontinued,pcntl_wexitstatus,pcntl_wtermsig,pcntl_wstopsig,pcntl_signal,pcntl_signal_dispatch,pcntl_get_last_error,pcntl_strerror,pcntl_sigprocmask,pcntl_sigwaitinfo,pcntl_sigtimedwait,pcntl_exec,pcntl_getpriority,pcntl_setpriority,exec,shell_exec,passthru,system,proc_open,popen,parse_ini_file,show_source</li>	
		</ul>
		<br/>
		And if possible the following ones too (but only if you get request body limit exceed or something similar):
		<ul>
			<li>max_input_time = 360</li>
			<li>memory_limit = 1024M</li>
			<li>max_input_vars = 10000</li>
			<li>suhosin.get.max_vars = 10000 (if apply)</li>
			<li>suhosin.post.max_vars = 10000 (if apply)</li>
			<li>suhosin.request.max_vars = 10000 (if apply)</li>
		</ul>
		<br/>
		In linux, to enable the mod_rewrite in apache, try to execute this command: 
		<ul>
			<li>sudo a2enmod rewrite</li>
		</ul>
		<br/>
		Note that you should make these changes in the php.ini from apache and cli mode. Usually these files are located in:
		<ul>
			<li>/etc/php/apache2/php.ini</li>
			<li>/etc/php/cli/php.ini</li>
		</ul>
	</li>
	<li>Please be sure that you have PHP installed and all the following modules:
		<ul>
			<li>bcmath (is installed by default)</li>
			<li>bz2 (is installed by default)</li>
			<li>ctype (is installed by default)</li>
			<li>curl (is installed by default)</li>
			<li>dom (is installed by default)</li>
			<li>date (is installed by default)</li>
			<li>exif (is installed by default)</li>
			<li>fileinfo (is installed by default)</li>
			<li>filter (is installed by default)</li>
			<li>ftp (is installed by default)</li>
			<li>gd</li>
			<li>hash (is installed by default)</li>
			<li>imap (is installed by default)</li>
			<li>intl (is installed by default)</li>
			<li>json (is installed by default)</li>
			<li>libxml (is installed by default)</li>
			<li>mbstring (is installed by default)</li>
			<li>memcache</li>
			<li>mongodb</li>
			<li>mysqli (is installed by default)</li>
			<li>odbc (is installed by default)</li>
			<li>openssl (is installed by default)</li>
			<li>pcre (is installed by default)</li>
			<li>pdo</li>
			<li>pdo_mysql</li>
			<li>pdo_odbc</li>
			<li>pdo_pgsql</li>
			<li>pdo_sqlite</li>
			<li>pgsql</li>
			<li>posix (is installed by default - optional)</li>
			<li>reflection (is installed by default)</li>
			<li>session (is installed by default)</li>
			<li>simplexml (is installed by default)</li>
			<li>sqlite3</li>
			<li>soap (optional)</li>
			<li>ssh2</li>
			<li>tokenizer (is installed by default)</li>
			<li>xml (is installed by default)</li>
			<li>xmlreader (is installed by default)</li>
			<li>xmlrpc (is installed by default)</li>
			<li>xmlwriter (is installed by default)</li>
			<li>xsl (is installed by default)</li>
			<li>zend opcache (is installed by default)</li>
			<li>zip (is installed by default)</li>
			<li>zlib (is installed by default)</li>
		</ul>
		
		<br>
		If some module is missing you need to execute the command bellow in Linux to install the following packages:
		<ul>
			<li>sudo apt-get/yum install php-common php-cli php-bcmath php-curl php-gd php-mbstring php-mysql/php-mysqlnd php-pgsql php-xml php-ssh2 php-json</li>
			<li>and optionally: sudo apt-get/yum install php-soap php-opcache php-dbg php-process php-odbc php-pdo php-fpm php-dba php-dbg</li>
		</ul>
		
		<br>
		If you wish to connect to mssql-server, please install the 'mssql-server' package. If you are not able to install this package on linux os, please follow the tutorials in order to install the odbc drivers for mssql-server:
		<ul>
			<li><a href='https://docs.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-ver15' target='_blank'>https://docs.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-ver15</a></li>
			<li><a href='https://www.easysoft.com/developer/languages/php/sql_server_unix_tutorial.html' target='_blank'>https://www.easysoft.com/developer/languages/php/sql_server_unix_tutorial.html</a></li>
			<li><a href='https://www.easysoft.com/products/data_access/odbc-sql-server-driver/manual/installation.html#852113' target='_blank'>https://www.easysoft.com/products/data_access/odbc-sql-server-driver/manual/installation.html#852113</a></li>
		</ul>
	</li>
	<li>Go to your /etc/mysql/my.cnf and add the following line:
		<ul>
			<li>
				[mysqld]<br>
				#if mysql version < 8<br>
				sql-mode=\"ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION\"<br>
				<br>
				#if mysql version >= 8<br>
				sql-mode=\"ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION\"<br>
				<br>
				max_allowed_packet=250M<br>
				wait_timeout=28800
				max_allowed_packet=100M
			</li>
			<li>
				[mysqld_safe]<br>
				max_allowed_packet=100M
			</li>
			<li>
				[client]<br>
				max_allowed_packet=100M
			</li>
			<li>
				[mysql]<br>
				max_allowed_packet=100M
			</li>
			<li>
				[mysqldump]<br>
				max_allowed_packet=100M
			</li>
		</ul>
	</li>
	<li>Change the correspondent apache security configurations, if active - (Only if you get request body limit exceed or something similar), by adding of changing the following lines to the file /etc/modsecurity/modsecurity.conf:
		<ul>
			<li>#to 32MB:<br>
			SecRequestBodyLimit 32768000</li>
			
			<li>#to 640KB:<br>
			SecRequestBodyNoFilesLimit 655360</li>
			
			<li>#to 16MB:<br>
			SecRequestBodyInMemoryLimit 16384000</li>
			
			<li>#to 32MB:<br>
			SecResponseBodyLimit 32768000</li>
		</ul>
	</li>
	<li>change the following apache configurations if apply (this is, inside of your virtual-host configuration add the following lines):
		<ul>
			<li>LimitInternalRecursion 100</li>
			<li>LimitRequestBody 0</li>
			<li>LimitRequestFields 10000000</li>
			<li>LimitRequestFieldSize 10000000</li>
			<li>LimitRequestLine 10000000</li>
			<li>LimitXMLRequestBody 10000000</li>
		</ul>
	</li>
	<li>in CentOS is probably that the apache has the external network connections blocked which doesn't allow the mysql connect with the DBs. To check if this is OFF please type the following commands:<br/>
	&nbsp;&nbsp;&nbsp;sudo getsebool -a | grep httpd_can_network<br/>
	<br/>
	If the httpd_can_network_connect is OFF, you should enable them by typing:<br/>
	&nbsp;&nbsp;&nbsp;sudo setsebool -P httpd_can_network_connect 1<br/>
	</li>
	<li>Please be sure that your WebServer has write permissions to the following files:
		<ul>"; foreach ($files as $file) { $optional = in_array($file, $optional_files); $status = false; $exists = file_exists($file); if ($exists || !$optional) { $status = $exists ? is_writable($file) : false; $path = $exists && !empty(realpath($file)) ? realpath($file) : $file; $html .= "<li>" . $path . ": <span class=\"" . ($status ? "writeable" : "non_writeable") . "\">" . ($status ? "OK" : "NON WRITEABLE") . "<span></li>\n"; if(!$status) $main_status = false; } } $html .= "	</ul>
	</li>
	<li>If some of the above files are <span class=\"non_writeable\">NON WRITEABLE</span>, please change their permissions or owner and refresh this page.</li>
	<li class=\"" . ($main_status ? "" : "disable") . "\">If all the files above are <span class=\"writeable\">OK</span>, please click <span class=\"continue\">" . ($main_status ? "<a href=\"__system/setup/\">HERE</a>" : "HERE") . "</span> to login and continue with the setup...<br/>(To login please use the username: \"admin\" and the password: \"admin\".)</li>
	<li>Then after you finish the setup, please go to \"User Management\" panel and change your login password...</li>
	<li>Delete the setup.php file.</li>
	</ol>"; echo $html; ?>
		<div class="enjoy">Enjoy...</div>
	</div>
</body>
</html>
