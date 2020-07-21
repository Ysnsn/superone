<?php

ini_set('memory_limit', '512M');

error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('PRC');
define('TIME', time());
define('SOFTVERSION', 10);
!defined('ROOT') && define('ROOT', str_replace('\\', '/', dirname(__FILE__)).'/');

!defined('CONFIG_PATH') && define('CONFIG_PATH', ROOT.'config/');
!defined('CONTROLLER_PATH') && define('CONTROLLER_PATH', ROOT.'src/controller/');
!defined('CACHE_PATH') && define('CACHE_PATH', ROOT.'cache/');
function i_autoload($className)
{
    if (is_int(strripos($className, '..'))) {
        return;
    }
    $file = ROOT.'src/lib/'.$className.'.php';
    if (file_exists($file)) {
        include $file;
    }
}
spl_autoload_register('i_autoload');

!defined('FILE_FLAGS') && define('FILE_FLAGS', LOCK_EX);
/*
 * config('name');
 * config('name@file');
 * config('@file');
 */
if (!function_exists('config')) {
    function config($key)
    {
        if (!file_exists(ROOT.'/config/')) {
            mkdir(ROOT.'/config/');
        }
        static $configs = array();
        list($key, $file) = explode('@', $key, 2);
        $file = empty($file) ? 'base' : $file;

        $file_name = CONFIG_PATH.$file.'.php';
        //读取配置
        if (empty($configs[$file]) and file_exists($file_name)) {
            $configs[$file] = @include $file_name;
        }

        if (func_num_args() === 2) {
            $value = func_get_arg(1);
            //写入配置
            if (!empty($key)) {
                $configs[$file] = (array) $configs[$file];
                if (is_null($value)) {
                    unset($configs[$file][$key]);
                } else {
                    $configs[$file][$key] = $value;
                }
            } else {
                if (is_null($value)) {
                    return unlink($file_name);
                } else {
                    $configs[$file] = $value;
                }
            }
            file_put_contents($file_name, '<?php return '.var_export($configs[$file], true).';', FILE_FLAGS);
        } else {
            //返回结果
            if (!empty($key)) {
                return $configs[$file][$key];
            }

            return $configs[$file];
        }
    }
}

 cache::$type = empty(config('cache_type')) ? 'filecache' : config('cache_type');
///////////////////////////////////////////
if (!function_exists('access_token')) {
    function access_token($配置文件, $驱动器)
    {
        $token = $配置文件;

        if ($_POST['site']) {
            $siteidurl = onedrive::get_siteidbyname($sitename, $配置文件['access_token'], $配置文件['api_url']);

            if ($siteidurl == '') {
                echo   '获取失败重新获取';
                echo '<form action="/'.$驱动器.'/ "  method="get">
 　　<input type="text" name="site" value ="/sites/名称" />
 　　<input type="submit" value="站点id" />
 </form>';
                exit;
            }
            echo $api = $配置文件['api_url'].'/sites/'.$siteidurl.'/drive/root';

            config('api@'.$驱动器, $api);
            echo '配置sharepoint成功<br>';
            echo '<a href="/'.$驱动器.'">授权成功</a>';
            cache::clear();
            cache::clear_opcache();

            exit;
        }

        ///////////////////已经授权////////////////
    if ($token['refresh_token'] !== '') {//已经授权
            if ($token['expires_on'] > time() + 600) {
                return $token['access_token'];
            } else {
                $refresh_token = $token['refresh_token'];
                $newtoken = get_token($配置文件);

                if (!empty($newtoken['refresh_token'])) {
                    $配置文件['expires_on'] = time() + $newtoken['expires_in'];
                    $配置文件['access_token'] = $newtoken['access_token'];
                    $配置文件['refresh_token'] = $newtoken['refresh_token'];
                    config('@'.$驱动器, $配置文件);

                    return $token['access_token'];
                }
            }
    }

        ///////////////////未授权////////////////
   

        return $token['access_token'];

        //endsub
    }
}




//通过配置文件获取access—token
 if (!function_exists('get_token')) {
     function get_token($配置文件 = array())
     {
         $oauth_url = $配置文件['oauth_url'];
         $client_id = $配置文件['client_id'];
         $redirect_uri = $配置文件['redirect_uri'];
         $client_secret = $配置文件['client_secret'];
         $refresh_token = $配置文件['refresh_token'];

         $request['url'] = $oauth_url.'/token';
         $request['post_data'] = "client_id={$client_id}&redirect_uri={$redirect_uri}&client_secret={$client_secret}&refresh_token={$refresh_token}&grant_type=refresh_token";

         $request['headers'] = 'Content-Type: application/x-www-form-urlencoded';
         $resp = fetch::post($request);
         if ($resp->http_code == '200') {
             $data = json_decode($resp->content, true);

             return $data;
         } else {
             //echo $resp->http_code."错误";exit;
         }
     }
 }






if (!function_exists('db')) {
    function db($table)
    {
        return db::table($table);
    }
}

if (!function_exists('view')) {
    function view($file, $set = null)
    {
        return view::load($file, $set = null);
    }
}

if (!function_exists('_')) {
    function _($str)
    {
        return htmlspecialchars($str);
    }
}

if (!function_exists('e')) {
    function e($str)
    {
        echo $str;
    }
}

if (!function_exists('is_login')) {
    function is_login()
    {
        if ($_COOKIE['admin'] == config('password')) {
            return true;
        } else {
            return false;
        }
    }
}
function get_absolute_path($path)
{
    $path = str_replace(array('/', '\\', '//'), '/', $path);
    $parts = array_filter(explode('/', $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) {
            continue;
        }
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }

    return str_replace('//', '/', '/'.implode('/', $absolutes).'/');
}

function splitlast($str, $split)
{
    $len = strlen($split);
    $pos = strrpos($str, $split);
    if ($pos === false) {
        $tmp[0] = $str;
        $tmp[1] = '';
    } elseif ($pos > 0) {
        $tmp[0] = substr($str, 0, $pos);
        $tmp[1] = substr($str, $pos + $len);
    } else {
        $tmp[0] = '';
        $tmp[1] = substr($str, $len);
    }

    return $tmp;
}

function check_version()
{
    return  fetch::get('https://pan.mxin.ltd/version.json')->content;
}

if (!function_exists('str_is')) {
    function str_is($pattern, $value)
    {
        if (is_null($pattern)) {
            $patterns = [];
        }
        $patterns = !is_array($pattern) ? [$pattern] : $pattern;
        if (empty($patterns)) {
            return false;
        }
        foreach ($patterns as $pattern) {
            if ($pattern == $value) {
                return true;
            }
            $pattern = preg_quote($pattern, '#');
            $pattern = str_replace('\*', '.*', $pattern);
            if (preg_match('#^'.$pattern.'\z#u', $value) === 1) {
                return true;
            }
        }

        return false;
    }
}
if (!function_exists('get_domain')) {
    function get_domain($url = null)
    {
        if (is_null($url)) {
            return $_SERVER['HTTP_HOST'];
        }

        return strstr(ltrim(strstr($url, '://'), '://'), '/', true);
    }
}








 function drives()
    {
      
         $requesturi = explode('/', $_GET['path']);

        $驱动器 = $requesturi['0'];
        if ($驱动器 == '') {
            $驱动器 = 'default';
        }

     

        
return $驱动器;
    }

    function visit_path()
    {
          $requesturi = explode('/', $_GET["path"]);
   

    
        
        $驱动器=$requesturi["0"];
        if ($驱动器 == '') {
            $驱动器 = 'default';
        }
$请求路径 =str_replace($驱动器,"",$_GET["path"]);
if($请求路径==""){
    $请求路径="/";
}
 return    $请求路径;
 

     
    }

function URI(){
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
     
     return   $url = $http_type.$_SERVER['HTTP_HOST'].'/'.drives().$root.visit_path();
    
}
function load_config($driveid)
{
    
        if (file_exists(ROOT.'config/'.$driveid.'.php')) {
            $configfile = include ROOT.'config/'.$driveid.'.php';
        }
    
      if ($configfile['drivestype'] == 'cn') {
            onedrive::$api_url = 'https://microsoftgraph.chinacloudapi.cn/v1.0';
            onedrive::$oauth_url = 'https://login.partner.microsoftonline.cn/common/oauth2/v2.0';
        } else {
            onedrive::$api_url = 'https://graph.microsoft.com/v1.0';
            onedrive::$oauth_url = 'https://login.microsoftonline.com/common/oauth2/v2.0';
        }
        onedrive::$api=$configfile['api'];
        onedrive::$client_id = $configfile['client_id'];
        onedrive::$client_secret = $configfile['client_secret'];
        onedrive::$redirect_uri = $configfile['redirect_uri'];
        onedrive::$typeurl = $configfile['api'];
        onedrive::$access_token = access_token($configfile, $driveid);

      
   
    return $configfile;
    
    
}

define('VIST_PATH',visit_path());
define('URI',URI());


if (file_exists(ROOT.'vendor/autoload.php')) {
    require_once ROOT.'vendor/autoload.php';
}







