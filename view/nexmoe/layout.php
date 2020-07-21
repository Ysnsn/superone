<?php if($_REQUEST["type"]!="json"): ?>
<!DOCTYPE html>
<html>

    <head>
    <meta charset="utf-8">
    <meta name="google" content="notranslate" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="//cdn.jsdelivr.net">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
     <title><?php e(config('site_name').$title);?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@0.4.3/dist/css/mdui.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aplayer/dist/APlayer.min.css">
    
    
  
  
    <style>
    
    body{background-color:#f2f5fa;padding-bottom:60px;background-position:center bottom;background-repeat:no-repeat;background-attachment:fixed}
    
    
    
    
  .mc-drawer {
            background-color: rgba(255, 255, 255, 0.5);

        }
 
#menu {/* 右键菜单*/
background-position: center  0px;
background-size: cover;
        }
 html,body{height:100%;
	background-color: #fff;
	background-image:url(https://cdn.jsdelivr.net/gh/742481030/cdnimg@master/E2JNM8.jpg) !important;
	padding-bottom: 60px;
	background-position:auto!important;
	background-size: cover !important;
	background-attachment: fixed !important;
	background-repeat: no-repeat !important;
}    

        .mdui-container {/*内容区域*/
            max-width: 1024px;
        }

        .mdui-list-item {
            -webkit-transition: none;
            transition: none;
        }
        .mdui-list-item>a {
            width: 100%;
            line-height: 45px
        }
        .mdui-list>.th {
            background-color: initial;
        }
        .mdui-row>.mdui-list>.mdui-list-item {
            margin: 0px 0px 0px 0px;
            padding: 0;
           
        }
	
     
        #instantclick-bar {
            background: 

        }

        .mdui-video-fluid {
            height: -webkit-fill-available;
        }

        .dplayer-video-wrap .dplayer-video {
            height: -webkit-fill-available !important;
        }

        .gslide iframe,
        .gslide video {
            height: -webkit-fill-available;
        }

        @media screen and (max-width:800px) {/*小屏幕*/
            .mdui-list-item .mdui-text-right {
               display: none;
            }

            .mdui-container {
                width: 100% !important;
                margin: 0px;
            }
           
        }
        
        

        .spec-col {
            padding: .9em;
            display: flex;
            align-items: center;
            white-space: nowrap;
            flex: 1 50%;
            min-width: 225px
        }

        .spec-type {
            font-size: 1.35em
        }

        .spec-value {
            font-size: 1.25em
        }

        .spec-text {
            float: left
        }

        .device-section {
            padding-top: 30px
        }

        .spec-device-img {
            height: auto;
            height: 340px;
            padding-bottom: 30px
        }

        #dl-header {
            margin: 0
        }

        #dl-section {
            padding-top: 10px
        }

        #dl-latest {
            position: relative;
            top: 50%;
            transform: translateY(-50%)
        }

        .mdui-typo.mdui-shadow-3 {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .nexmoe-item {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .mdui-row {
            margin-right: 1px;
            margin-left: 1px;
        }

      
        
         .thumb .th {
            display: none;
        }

        .thumb .mdui-text-right {
            display: none;
        }

        .thumb .mdui-list-item a,
        .thumb .mdui-list-item {
            width: 213px;
            height: 230px;
            float: left;
            margin: 10px 10px !important;
        }

        .thumb .mdui-col-xs-12,
        .thumb .mdui-col-sm-7 {
            width: 100% !important;
            height: 230px;
        }

        .thumb .mdui-list-item .mdui-icon {
            font-size: 100px;
            display: block;
            margin-top: 40px;
            color: #7ab5ef;
        }

        .thumb .mdui-list-item span {
            float: left;
            display: block;
            text-align: center;
            width: 100%;
            position: absolute;
            top: 180px;
        }

        .thumb .forcedownload {
            display: none;
        }


 
        .mdui-checkbox-icon::after {/*复选框透明*/
            border-color: transparent;
        }
        .mdui-fab-fixed, .mdui-fab-wrapper {
   
    bottom: 64px;
}

 #toolbar{
   
    background-image:url() !important;
}
        <?php echo config("cssstyle");//自定义css?>
        
    </style>
</head>

    <body class="  mdui-loaded mdui-appbar-with-toolbar">
       
      
            
            
        <div class="mdui-appbar mdui-appbar-fixed">
            <div class="mdui-toolbar mdui-color-orange"id="toolbar"><button mdui-drawer="{target: '.mc-drawer', swipe: true}"
                    class="mdui-btn mdui-btn-icon mdui-ripple "><i
                        class="mdui-icon material-icons">menu</i></button>
                    
                        	<div class="mdui-toolbar-spacer"></div>


                           
                 
            
            </div>
      









        <div class="mc-drawer mdui-drawer <?php if(!config("appbar")):?>mdui-drawer-close<?endif?>">
            <div class="mdui-list">
                <a class="mdui-list-item mdui-ripple " href="/"><i
                        class="mdui-list-item-icon mdui-icon material-icons">home</i>
                    <div class="mdui-list-item-content">首页</div>
                </a>
 <?php 
		
	$filess = scandir(ROOT."config/");
    foreach ($filess as $part) {
        if ('.' == $part ||'..' == $part||'default.php' == $part||'default.php' == $part||'uploads.php' == $part||'uploaded.php' == $part||'base.php' == $part||".DS_Store"==$part) continue;
        else {
             $v=str_replace(".php","",$part);
        echo '<a href="/'.$v.'/" class="mdui-list-item mdui-ripple">
		    	<i class="mdui-list-item-icon mdui-icon material-icons">cloud</i>
			    <div class="mdui-list-item-content">'.$v.'</div>
	    	</a>';
             }
        
        }

	if($_COOKIE["admin"]==config("password@base"))	
	echo'<a href="/install.php" class="mdui-list-item mdui-ripple">
			<i class="mdui-list-item-icon mdui-icon material-icons">home</i>
			<div class="mdui-list-item-content">添加新盘</div>
		</a>';
?>
<?php e(config('drawer'));?><a href="https://github.com/742481030/oneindex" class="mdui-list-item mdui-ripple ">
                    <i class="mdui-list-item-icon mdui-icon material-icons">code</i>
                    <div class="mdui-list-item-content">Github</div>
                </a>
            </div>
            <div class="copyright"></div>
        </div>

    </div>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
       
        


                    
                   


   
        <div class="mdui-container">
 


<navs>
            <div class="mdui-container-fluid">
                
                 <div class=" mdui-toolbar nexmoe-item mdui-shadow-3 folder">
                     <a href="javascript:;" class="mdui-btn mdui-btn-icon getlink-btn"><i class="mdui-icon material-icons">link</i></a>
                     <a class="<?php if(is_login()):?>admin<?php endif?>" href="/"><?php e(config('site_name'));?></a>
			<?php foreach((array)$navs as $n=>$l):?>
			<a  class="<?php if(is_login()):?>admin<?php endif?>" href="<?php e($l);?>"><?php e($n);?></a>
						<i class="mdui-icon material-icons mdui-icon-dark" style="margin:0;">chevron_right</i>
			<?php endforeach;?>
					</div>
             </div>
       
           
            </navs>
            
            <list id="viewlist">
                <?php endif?><?php view::section('content');?>
            <?php if($_REQUEST["type"]!="json"): ?>
            </list>
    </div>

 <ul class="mdui-menu" id="menu">
     <?php  if($_COOKIE["moveitem"]): ?>
      <li class="mdui-menu-item">
            <a href="javascript:;" onclick="pastitem()" ; class="mdui-ripple">粘贴</a>
        </li>
        <?php endif;?>
        <li class="mdui-menu-item">
            <a href="javascript:;" onclick="share()" ; class="mdui-ripple">分享链接</a>
        </li>
        <li class="mdui-menu-item">
            <a href="javascript:;" onclick="deldel()" ; class="mdui-ripple">刷新</a>
        </li>
        <?php if(is_login()): ?>
        <li class="mdui-menu-item">
            <a href="javascript:;" class="mdui-ripple" onclick="renamebox()" ;>重命名</a>
        </li>
        <li class="mdui-menu-item">
            <a href="javascript:;" onclick="moveoneitem()" ; class="mdui-ripple">移动</a>
        </li>
        <li class="mdui-menu-item">
            <a href="javascript:;" class="mdui-ripple" onclick="delitem()" ;>删除</a>
        </li>
        <li class="mdui-menu-item">
            <a href="/admin" class="mdui-ripple" onclick="changebg()">更换背景</a>
        </li>
        <?php endif;?>
        <li class="mdui-menu-item">
            <a href="/admin" class="mdui-ripple">系统设置</a>
        </li>
    </ul>
        <upload>

        <div class="mdui-dialog mdui-dialog-open" id="exampleDialog" style="top: 89.703px; display: none; height:auto;">
            <div class="mdui-dialog-content" style="height: 665.594px;">
                <div class="mdui-dialog-title">文件上传</div>

                <div id="upload_div" style="margin:0 0 16px 0;">
                    <div id="upload_btns" align="center" style="display:none" ;>
                        <select onchange="document.getElementById('upload_file').webkitdirectory=this.value;">
                            <option value="">上传文件</option>
                            <option value="1">上传文件夹</option>
                        </select>
                        <input id="upload_file" type="file" name="upload_filename" multiple="multiple"
                            class=" layui-btn" onchange="preup();">
                        <input id="upload_submit" onclick="preup();" value="上传" type="button">
                    </div>
                </div>
                <br><br><br><br><br><br><br><br>
            </div>
            <div class="mdui-dialog-actions">

                <button class="mdui-btn mdui-ripple" mdui-dialog-confirm="" onclick="uploadkill()">完成上传</button>
            </div>
        </div>




    </upload>
    
    </body>

    <footer>


      
 
         <script src="https://cdn.jsdelivr.net/combine/npm/mdui@0.4.3/dist/js/mdui.min.js,gh/mcstudios/glightbox/dist/js/glightbox.min.js,npm/aplayer/dist/APlayer.min.js,npm/js-cookie@2/src/js.cookie.min.js,gh/axios/axios@0.19.2/dist/axios.min.js"></script>
        
          <script src="https://cdn.jsdelivr.net/combine/gh/newcdn/ui/js/dianjiyuanquan.js"></script>
          <script src="/view/nexmoe/manger-11.js"></script>
      

       
    </footer>
<canvas id="Snow"></canvas>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/cdngod/texiao@1.0/css/snow.css">
<script src="https://cdn.jsdelivr.net/gh/cdngod/texiao@1.0/js/snow.js"></script>
</html>
<?php endif?>