<?php

//20200707 格式化
require __DIR__.'/init.php';
if(!file_exists(ROOT.'config/default.php')){
     header('Location: /admin');
    exit;
}
function Alert($Str, $Typ = 'back', $TopWindow = '', $Tim = 100)
{
    echo '<script>'.chr(10);
    if (!empty($Str)) {
        echo "alert(\"Warning:\\n\\n{$Str}\\n\\n\");".chr(10);
    }

    echo 'function _r_r_(){';
    $WinName = (!empty($TopWindow)) ? 'top' : 'self';
    switch (strtolower($Typ)) {
    case '#':
        break;
    case 'back':
        echo $WinName.'.history.go(-1);'.chr(10);
        break;
    case 'reload':
        echo $WinName.'.window.location.reload();'.chr(10);
        break;
    case 'close':
        echo 'window.opener=null;window.close();'.chr(10);
        break;
    case 'function':
        echo "var _T=new Function('return {$TopWindow}')();_T();".chr(10);
        break;
        //Die();
    default:
        if ($Typ != '') {
            //Echo "window.{$WinName}.location.href='{$Typ}';";
            echo "window.{$WinName}.location=('{$Typ}');";
        }
    }

    echo '}'.chr(10);

    //為防止Firefox不執行setTimeout
    echo 'if(setTimeout("_r_r_()",'.$Tim.')==2){_r_r_();}';
    if ($Tim == 100) {
        echo '_r_r_();'.chr(10);
    } else {
        echo 'setTimeout("_r_r_()",'.$Tim.');'.chr(10);
    }
    echo '</script>'.chr(10);
    exit();
}

if (!config('qqlogin')) {
    Alert($tip = "管理员没有开启qq互联登陆请配置config/base.php中 'QQ互联登陆' => '1',然后退出登陆点qq登陆即可", 'back');
}
 //应用的APPID
 $app_id = '101861794';
 //应用的APPKEY
 $app_secret = 'aca494b87124da2fbd94d31de263a114';
 //成功授权后的回调地址
 $my_url = 'https://coding.mxin.ltd/';
 //Step1：获取Authorization Code
 session_start();
 $code = $_REQUEST['code'];
 if (empty($code)) {
     //state参数用于防止CSRF攻击，成功授权后回调时会原样带回
     $_SESSION['state'] = 'http://'.$_SERVER['HTTP_HOST'].'/login.php';
     //拼接URL
     $dialog_url = 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='
       .$app_id.'&redirect_uri='.urlencode($my_url).'&state='
       .$_SESSION['state'];
     echo "<script> top.location.href='".$dialog_url."'</script>";
 }
 //Step2：通过Authorization Code获取Access Token
 if ($_REQUEST['state'] !== '') {
     //拼接URL
     $token_url = 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&'
    .'client_id='.$app_id.'&redirect_uri='.urlencode($my_url)
    .'&client_secret='.$app_secret.'&code='.$code;
     $response = file_get_contents($token_url);
     if (strpos($response, 'callback') !== false) {
         $lpos = strpos($response, '(');
         $rpos = strrpos($response, ')');
         $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
         $msg = json_decode($response);
         if (isset($msg->error)) {
             echo '<h3>error:</h3>'.$msg->error;
             echo '<h3>msg  :</h3>'.$msg->error_description;
             exit;
         }
     }
     //Step3：使用Access Token来获取用户的OpenID
     $params = array();
     parse_str($response, $params);
     $graph_url = 'https://graph.qq.com/oauth2.0/me?access_token='.$params['access_token'];
     $str = file_get_contents($graph_url);
     if (strpos($str, 'callback') !== false) {
         $lpos = strpos($str, '(');
         $rpos = strrpos($str, ')');
         $str = substr($str, $lpos + 1, $rpos - $lpos - 1);
     }
     $user = json_decode($str);
     if (isset($user->error)) {
         echo '<h3>error:</h3>'.$user->error;
         echo '<h3>msg  :</h3>'.$user->error_description;
         exit;
     }
     //cho("Hello " . $user->openid);
     if (config('openid') == '') {
         config('openid', $user->openid);

         setcookie('admin', config('password'));
         
          echo '
        <script src="https://cdn.jsdelivr.net/combine/npm/mdui@0.4.3/dist/js/mdui.min.js,gh/mcstudios/glightbox/dist/js/glightbox.min.js,npm/aplayer/dist/APlayer.min.js,npm/js-cookie@2/src/js.cookie.min.js,gh/axios/axios@0.19.2/dist/axios.min.js"></script>
        
        <script>
axios.post("/default")
  .then(function (response) {
    console.log(response);
    document.write(response.data)
    window.location.href="/"
  })
  .catch(function (error) {
    console.log(error);
  });
</script>';
     } elseif ($user->openid == config('openid')) {
         setcookie('admin', config('password'));
             
        echo '
        <script src="https://cdn.jsdelivr.net/combine/npm/mdui@0.4.3/dist/js/mdui.min.js,gh/mcstudios/glightbox/dist/js/glightbox.min.js,npm/aplayer/dist/APlayer.min.js,npm/js-cookie@2/src/js.cookie.min.js,gh/axios/axios@0.19.2/dist/axios.min.js"></script>
        
        <script>
axios.post("/default")
  .then(function (response) {
    console.log(response);
    document.write(response.data)
    window.location.href="/"
  })
  .catch(function (error) {
    console.log(error);
  });
</script>';
        
        // header('Location:/');
     } else {
         Alert($tip = "你不是管理员,无法访问隐私内容", 'back');
     }
 } else {
     echo 'The state does not match. You may be a victim of CSRF.';
 }

   //	header('Location: '.$goto);
