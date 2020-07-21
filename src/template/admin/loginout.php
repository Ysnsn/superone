<?php
 setcookie('admin',"",time() -86400,"/");
      header("refresh:2;url=/");print('正在退出...');;
  exit;