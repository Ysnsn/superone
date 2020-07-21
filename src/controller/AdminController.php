<?php

define('VIEW_PATH', ROOT.'src/template/admin/');
class AdminController
{
    

    public function __construct()
    {
    }

    public function login()
    {//echo "login";
        if (!empty($_POST['password']) && $_POST['password'] == config('password')) {
            setcookie('admin',config('password'));

            return view::direct(get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).'?/admin/');
        }
//  header('Location:/login.php');
        return view::load('login')->with('title', '系统管理');
    }

    public function logout()
    {
        setcookie('admin', '');

        return view::direct(get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).'?/login');
    }

    public function settings()
    {
        if ($_POST) {
            config('site_name', $_POST['site_name']);
             config('bgimg', $_POST['bgimg']);
              config('mobileimg', $_POST['mobileimg']);
            config('title_name', $_POST['title_name']);
            config('drawer', $_POST['drawer']);
            config('style', $_POST['style']);
            config('main_domain', $_POST['main_domain']);
            config('proxy_domain', $_POST['proxy_domain']);
            config('onedrive_root', get_absolute_path($_POST['onedrive_root']));

            config('onedrive_hide', $_POST['onedrive_hide']);
              config('appbar', $_POST['appbar']);
            config('cache_type', $_POST['cache_type']);
            config('cache_expire_time', intval($_POST['cache_expire_time']));
            config('page_item', intval($_POST['page_item']));

            $_POST['root_path'] = empty($_POST['root_path']) ? '?/' : '';
            config('root_path', $_POST['root_path']);
        }
        $config = config('@base');

        return view::load('settings')->with('config', $config);
    }

    public function cache()
    {
        require(ROOT."del.php");
        return view::load('cache')->with('message', $message);
    }

    public function images()
    {
        if ($_POST) {
            $config['home'] = empty($_POST['home']) ? false : true;
            $config['public'] = empty($_POST['public']) ? false : true;
            $config['exts'] = explode(' ', $_POST['exts']);
            config('images@base', $config);
        }
        $config = config('images@base');

        return view::load('images')->with('config', $config);
    }

    public function show()
    {
        if (!empty($_POST)) {
            foreach ($_POST as $n => $ext) {
                $show[$n] = explode(' ', $ext);
            }
            config('show', $show);
        }
        $names = [
            'stream' => '直接输出(<5M)，走本服务器流量(stream)',
            'image' => '图片(image)',
            'video' => 'Dplayer 视频(video)',
            'video2' => 'Dplayer DASH 视频(video2)/个人版账户不支持',
            'video5' => 'html5视频(video5)',
            'audio' => '音频播放(audio)',
            'code' => '文本/代码(code)',
            'doc' => '文档(doc)',
        ];
        $show = config('show');

        return view::load('show')->with('names', $names)->with('show', $show);
    }
    
    function offline(){
		
		return view::load('offline');
	}
public function drives(){
     if($_GET["action"]=="code"){
     
       $code=str_replace('?code=','',$_GET["cc"]);
  $配置文件=( load_config($_GET["name"]))  ;
  

        $client_id = $配置文件['client_id'];
        $client_secret = $配置文件['client_secret'];
        $redirect_uri = $配置文件['redirect_uri'];
        $授权url = $配置文件['oauth_url'].'/token';
        $curl = curl_init();
        curl_setopt_array($curl, array(
              CURLOPT_URL => $授权url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'code='.$code.'&grant_type=authorization_code&client_id='.$client_id.'&client_secret='.$client_secret.'&redirect_uri=https%3A//coding.mxin.ltd',
            CURLOPT_HTTPHEADER => array(
            'SdkVersion: postman-graph/v1.0',
            'client_secret:'.$client_secret,
            'code: '.$code,
            'redirect_uri: https://coding.mxin.ltd',
            'Content-Type: application/x-www-form-urlencoded',
            'grant_type: authorization_code', ),
   ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        if($response['refresh_token']){
            
            config('refresh_token@'.$_GET["name"], $response['refresh_token']);
            config('access_token@'.$_GET["name"], $response['access_token']);
             
             
            echo '<a href="'.$地址.'"> onedriv授权授权成功点次完成配置</a><br> <br> <br>';
            echo   ' 如果需要开启sharepoint25T,请去exchage创建组,组的名称填下面,默认使用onedrive<br>';
            echo '<form action=""  method="post">
            
            　<input type="text" name="name" value ="'.$_REQUEST["name"].'" />
 　　<input type="text" name="site" value ="/sites/名称" />
 　　<input type="submit" value="站点id" />
     </form>';
        }
      else{
           header('Location: /?/admin/drives');
          
          
      }
  
  

  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
         exit;
     }
     if ($_REQUEST["site"]!==""&&$_REQUEST["name"]!=""&&$_REQUEST["action"]==""){
         
        $f= load_config($_REQUEST["name"]);
         
      
         
       echo $siteid= onedrive::get_siteidbyname($_REQUEST["site"], $f["access_token"], $f["api_url"]);
       
         if ($siteid =='') {
              echo '<a href="'.$地址.'"> onedriv授权授权成功点次完成配置</a><br> <br> <br>';
            echo   ' 如果需要开启sharepoint25T,请去exchage创建组,组的名称填下面,默认使用onedrive<br>';
            echo '<form action=""  method="post">
            
            　<input type="text" name="name" value ="'.$_REQUEST["name"].'" />
 　　<input type="text" name="site" value ="/sites/名称" />
 　　<input type="submit" value="站点id" />
     </form>';
             
         }else{
                    
                    
                    $api = $f['api_url'].'/sites/'.$siteid.'/drive/root';

            config('api@'.$_REQUEST["name"], $api);
           
            cache::clear();
            cache::clear_opcache();

                     header('Location: /?/admin/drives');
                }
         exit;
     }
    if($_GET["action"]=="add"){
       
       if ($_GET['filename']) {
    if ($_GET['drivestype'] == 'cn') {
        $data = array(
        'drivestype' => 'cn',
        'share' => 'on',
        'onedrive_root' => '/',
          'guestupload' => 'off',
        'client_secret' => 'v4[Nq:4=rmFS78BwYi[@x3sGk-iY.U:S',
        'client_id' => '3447f073-eef3-4c60-bb68-113a86f2c39a',
        'redirect_uri' => 'https://coding.mxin.ltd/',
        'api' => 'https://microsoftgraph.chinacloudapi.cn/v1.0/me/drive/root',
        'api_url' => 'https://microsoftgraph.chinacloudapi.cn/v1.0',
        'oauth_url' => 'https://login.partner.microsoftonline.cn/common/oauth2/v2.0', );
    } else {
        $data = array(
        'drivestype' => 'us',
        'share' => 'on',
        'onedrive_root' => '/',
        'guestupload' => 'off',
        'client_secret' => '~ZkpvnVoMysK36v0_Og1EPp.l3JA_NY-9a',
        'client_id' => '02be423f-f28c-48de-b265-09327e1a04eb',
        'redirect_uri' => 'https://coding.mxin.ltd/',
        'api' => 'https://graph.microsoft.com/v1.0/me/drive/root',
        'api_url' => 'https://graph.microsoft.com/v1.0',
        'oauth_url' => 'https://login.microsoftonline.com/common/oauth2/v2.0',
            );
    }

    if (!file_exists(ROOT.'config/default.php')) {
        $_GET['filename'] = 'default';
    }

    config('@'.$_GET['filename'], $data);

    echo '配置成功点此授权';
    echo '<a href="'.$_GET['filename'].'/" >配置成功点此授权</a>';

    exit;
}
       
       
       
       
       
       
       
       
        echo "add";exit;
    }
    	return view::load('drives');
    	
}
    public function setpass()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['old_pass'] == config('password')) {
                if ($_POST['password'] == $_POST['password2']) {
                    config('password', $_POST['password']);
                    $message = '修改成功';
                } else {
                    $message = '两次密码不一致，修改失败';
                }
            } else {
                $message = '原密码错误，修改失败';
            }
        }

        return view::load('setpass')->with('message', $message);
    }


public function update()
{
    

    
    
   if($_GET["update"]=="on"){
      
       config("update","on");
   }
    elseif($_GET["update"]=="off"){
        
       config("update","off");
        
    }
     return view::load('update');
    
}
   
}
