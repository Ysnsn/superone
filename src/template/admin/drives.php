<?php view::layout('layout')?>

<?php view::begin('content');?>
	<div class="mdui-typo">
	  <h1> 磁盘管理 <small>更多配置请编辑配置文件,添加新盘需要注销当前登陆账户或者隐私模式</small></h1>
	</div>
<a  href="/install.php" class="mdui-btn  mdui-color-theme-accent mdui-ripple"><i class="mdui-icon material-icons">add</i></a>
	<div class="mdui-divider-inset"></div>
	<div class="mdui-table-fluid">
  <table class="mdui-table">
    <thead>
      <tr>
        <th>#</th>
        <th>公开</th>
        <th>类型</th>
        <th>信息</th>
        <th>游客上传</th>
         <th>初始目录</th> 
         <th>授权</th>
        <th>管理</th>
      </tr>
    </thead>
    <tbody>
    
     
     
     <?php 
  
  if($_GET["action"]=="del")
  {
      
     unlink(ROOT."config/".$_GET["name"].".php") ;
     header('Location: /?/admin/drives');
      
  }
  if($_GET["action"]=="share"){
      $data= config('@'.$_GET["name"]);
      if(config('@'.$_GET["name"])["share"]=="on")
      {
          $data["share"]="off";
          config('@'.$_GET["name"],$data);
       
      }else{
           $data["share"]="on";
           config('@'.$_GET["name"],$data);
      }
      
       header('Location: /?/admin/drives');
  }
  
   if($_GET["action"]=="guestupload"){
      $data= config('@'.$_GET["name"]);
      if(config('@'.$_GET["name"])["guestupload"]=="on")
      {
          $data["guestupload"]="off";
          config('@'.$_GET["name"],$data);
       
      }else{
           $data["guestupload"]="on";
           config('@'.$_GET["name"],$data);
      }
      
       header('Location: /?/admin/drives');
  }
  
  
  
  
  $filess = scandir(ROOT."config/");
    foreach ($filess as $part) {
        if ('base.php' == $part||'.' == $part ||'..' == $part||'uploads.php' == $part||'uploaded.php' == $part||".DS_Store"==$part) continue;
        else {
             $v=str_replace(".php","",$part);
             echo'  
      <tr>
        <td>'.$v.'</td>
        <td> <a href="/admin/drives?action=share&name='.$v.'">'.config('@'.$v)["share"].'</a></td>
        <td>'.config('@'.$v)["drivestype"].'</td>
        
        
         <td>';?>
        <?php     
    
       $user=(onedrive::getuserinfo(config('@'.$v)["access_token"],config('@'.$v)["api_url"]."/me/drive")) ; 
        
       echo $user["owner"]["user"]["email"].'已用'.onedrive::human_filesize($user["quota"]["used"]).'/'.onedrive::human_filesize($user["quota"]["total"]);
       
       ?>
         
         
         <?php echo'</td>
         <td> <a href="/?/admin/drives&action=guestupload&name='.$v.'">'.config('@'.$v)["guestupload"].'</a></td>
         <td> <a href="/?/admin/drives&action=onedrive_root&name='.$v.'">'.config('@'.$v)["onedrive_root"].'</a></td>';?>
         <td> <?php
        if(config('@'.$v)["refresh_token"]){echo "已经授权";} else{
            $配置文件=config('@'.$v);
            
            $oauthurl = $配置文件['oauth_url'];
            $client_id = $配置文件['client_id'];
            if ($_SERVER['REQUEST_URI'] == '/') {
                $_SERVER['REQUEST_URI'] = '/default';
            }
            $redirect_uri = urlencode('http://'.$_SERVER['HTTP_HOST']."/?/admin/drives&action=code&name=".$v."&cc=");
            $授权地址 = $oauthurl.'/authorize?client_id='.$client_id.'&scope=offline_access+files.readwrite.all+Sites.ReadWrite.All&response_type=code&redirect_uri=https://coding.mxin.ltd&state='.$redirect_uri;
            echo '<a href="'.$授权地址.'">授权应用</a>';
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
        
        }  ?>
         </td>
       
       <?php echo ' <td><a href="/?/admin/drives&action=del&name='.$v.'">删除</a></td>
      </tr>
     ';
     
	    	
             }
        
        }
  
  ?>
  
     
     
     
     
     
     
    </tbody>
  </table>
</div>

 
  
  
  
  
  
  
  
  
  
  
  
  
  
  


<?php view::end('content');?>