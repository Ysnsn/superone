<?php view::layout('layout')?>

<?php view::begin('content');


$ss= check_version();


echo "当前版本".SOFTVERSION;
    echo " 最新版本".$ss;


?>

<div class="mdui-container-fluid">

	<div class="mdui-typo">
	  <h1> 更新设置<small></small></h1>
	</div>
	<form action="/admin/update" method="get">
		<?php if (config("update")=="on"):?>
 <label class="mdui-radio">
    <input type="radio" name="update" value="on"checked/>
    <i class="mdui-radio-icon"></i>
     开启更新
  </label>
  
  <label class="mdui-radio">
    <input type="radio" name="update"  value="off" />
    <i class="mdui-radio-icon"></i>
    关闭更新
  </label>
  <?php else:?>
  <label class="mdui-radio">
    <input type="radio" name="update" value="on"/>
    <i class="mdui-radio-icon"></i>
     开启更新
  </label>
  
  <label class="mdui-radio">
    <input type="radio" name="update"  value="off" checked/>
    <i class="mdui-radio-icon"></i>
    关闭更新
  </label>
  <?php endif;?>
	   <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right">
	   	<i class="mdui-icon material-icons">&#xe161;</i> 保存
	   </button>
	</form>
</div>
<?php view::end('content');?>