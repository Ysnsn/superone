<?php view::layout('install/layout')?>

<?php view::begin('content');?>
	
<div class="mdui-container-fluid">
	<div class="mdui-col-md-6 mdui-col-offset-md-3">
	  <center><h4 class="mdui-typo-display-2-opacity" style="color:#Fff">系统管理</h4></center>
	  <form action="" method="post">
		  <div class="mdui-textfield mdui-textfield-floating-label">
		    <i class="mdui-icon material-icons">https</i>
		    <label class="mdui-textfield-label">密码</label>
		    <input name="password" class="mdui-textfield-input" type="password"/>
		  </div>
		  <br>
		  <button type="submit" class="mdui-btn mdui-btn-dense mdui-color-theme-red mdui-ripple mdui-btn-block" style="background-color:red;">
		  	<i class="mdui-icon material-icons">fingerprint</i>
		  	登录
		  </button>
		  <br>
		  <a  href="/login.php" class="mdui-btn mdui-btn-dense mdui-color-theme-red mdui-ripple mdui-btn-block" style="background-color:red";>qq登陆</a>
	  </form>
	</div>
	
</div>

<?php view::end('content');?>