<?php include '../../../../init.php';?>
<?php

  



 if (!file_exists('../../../../config/base.php')) {
        $default_config = array(
            'qqlogin' => '',

            'cssstyle' => '',
            'guestpreload' => '1',
            'adminpreload' => '1',

            'bgimg' => 'https://cdn.jsdelivr.net/gh/742481030/cdnimg@master/E2JNM8.jpg',
            'mobileimg' => 'https://cdn.jsdelivr.net/gh/SeireiA/Seirei@latest/usr/uploads/2020/07/1366416900.png',
            'update' => 'on',
            'guestupload' => '',
            'site_name' => 'OneIndex',
            'title_name' => '',
            'appbar' => '',
            'password' => 'oneindex',
            'drawer' => '',
            'style' => 'nexmoe',
            'onedrive_root' => '',
            'cache_type' => 'filecache',
            'cache_expire_time' => 3600,
            'cache_refresh_time' => 600,
            'page_item' => 50,
            'root_path' => '',
            'onedrive_hide' => 'uploads',
            'show' => array(
            'stream' => ['txt', 'html'],
            'image' => ['bmp', 'jpg', 'jpeg', 'png', 'gif', 'webp'],
            'video5' => [],
            'video' => ['mpg', 'mpeg', 'mov', 'flv', 'mp4', 'webm', 'mkv', 'm3u8'],
            'video2' => ['avi', 'rm', 'rmvb', 'wmv', 'asf', 'ts'],
            'audio' => ['ogg', 'mp3', 'wav', 'flac', 'aac', 'm4a', 'ape'],
            'code' => ['php', 'css', 'go', 'java', 'js', 'json', 'txt', 'sh', 'md'],
            'doc' => ['csv', 'doc', 'docx', 'odp', 'ods', 'odt', 'pot', 'potm', 'potx', 'pps', 'ppsx', 'ppsxm', 'ppt', 'pptm', 'pptx', 'rtf', 'xls', 'xlsx'],
             ),
            'images' => ['home' => false, 'public' => false, 'exts' => ['jpg', 'png', 'gif', 'bmp']],
    );

        config('@base', $default_config);}


if($_REQUEST["site_name"])
{$base=config("@base");
  $base["site_name"]=$_REQUEST["site_name"];
 $base["password"]=$_REQUEST["title_name"];

    config("@base",$base);
   
    setcookie('admin',config('password'),time() + 86400,"/");
      header("refresh:2;url=/admin/drives");print('正在加载配置，请稍等...<br>2秒后自动跳转。');;
  exit;
    
}




?>
<!DOCTYPE html>
<!-- saved from url=(0025)http://x.mxin.ltd/?step=3 -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title> 系统安装 - Powered by oneindex</title>
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <link rel="stylesheet" href="https://www.layuicdn.com/layui-v2.1.7/css/layui.css">
    <link rel="stylesheet" href="style.css">
    
    <script src="https://www.layuicdn.com/layui-v2.1.7/layui.js"></script>
 
   
   </head>
<body>
<div class="header">
    <h1></h1>
</div>
<style type="text/css">
.layui-table td, .layui-table th{text-align:left;}
.layui-table tbody tr.no{background-color:#f00;color:#fff;}
</style>
<div class="header">
    <h1>感谢您选择superone软件</h1>
</div>
<div class="install-box">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>数据库配置</legend>
    </fieldset>
    <form class="layui-form layui-form-pane" action="install3.php" method="get">
        <div class="layui-form-item">
            <label class="layui-form-label">网站名称</label>
            <div class="layui-input-inline w300">
                <input type="text" class="layui-input" name="site_name"  value="superone">
            </div>
            <div class="layui-form-mid layui-word-aux">设置站点名称</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">管理员密码</label>
            <div class="layui-input-inline w300">
                <input type="text" class="layui-input" name="title_name"  value="share">
            </div>
            <div class="layui-form-mid layui-word-aux">管理员密码</div>
        </div>        
        
       
	
        <div class="step-btns">
            <a href="install2.php" class="layui-btn layui-btn-primary layui-btn-big fl">返回上一步</a>
            <button type="submit" class="layui-btn layui-btn-big layui-btn-normal fr"  style="">立即执行安装</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="./install3_files/jquery.min.js"></script>



</body></html>