<?php

//开发中
class ApiController
{
    public $驱动器;
    public $path;
    public $drives;

    // public $配置文件;
    public function __call($method, $ages)
    {
        // 遍历参数$args
        $var = '';
        foreach ($ages as $value) {
            $var .= $value.',';
        }

        return '方法是'.$method.'('.$var.')'.'不存在';
    }

    public function __construct()
    {  
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS,DELETE,MOVE,PATH,COPY,RENAME,CREAT,VIEW,PUT,CACHE');
          load_config(drives());
         
    }

    //功能正常
    public function move()
    {
        if (!is_login()) {
            http_response_code(401);
            exit;
        }
        $id = ($_GET['id']);
        $id = str_replace('"', '', $id);
        $id = str_replace('[', '', $id);
        $id = str_replace(']', '', $id);
        $ids = explode(',', $id);
        var_dump($ids);
        $newid = $_GET['newid'];
        onedrive::批量移动($ids, $newid);
        echo 'api';
    }

    //功能正常
    public function path()
    {
        echo $当前目录id = onedrive::pathtoid(onedrive::$access_token, visit_path());
    }

    //功能正常
    public function put()
    {
        if (!is_login() && config('guestupload') == '') {
            http_response_code(401);
            exit;
        }
        
        $filename = $_GET['upbigfilename'];
        $path = visit_path().$filename;
        $path = onedrive::urlencode($path);
        $path = empty($path) ? '/' : ":/{$path}:/";
        $access_token = onedrive::$access_token;
        $request['headers'] = "Authorization: bearer {$access_token}".PHP_EOL.'Content-Type: application/json'.PHP_EOL;
        $request['url'] = onedrive::$typeurl.$path.'createUploadSession';
        $request['post_data'] = '{"item": {"@microsoft.graph.conflictBehavior": "rename"}}';
        $resp = fetch::post($request);
        $data = json_decode($resp->content, true);
        if ($resp->http_code == 409) {
            return false;
        }

        echo $resp->content;
    }

    //功能正常
    public function delete()
    {
        if (!is_login()) {
            if (!is_login()) {
                http_response_code(401);
                exit;
            }
        }
        echo ' 删除文件';
        if ($_GET['action'] == 'dellist') {
            $bodyData = @file_get_contents('php://input');
            $ss = json_decode($bodyData, true);
            if (!is_array($ss)) {
                $ss = array(
     0 => $ss, );
            }

            var_dump($ss);

            echo  onedrive::delete($ss);
        }
    }

    //功能正常
    public function rename()
    {
        if (!is_login()) {
            if (!is_login()) {
                http_response_code(401);
                exit;
            }
        }
        onedrive::rename($_GET['rename'], $_GET['name']);
    }

    //功能正常
    public function creat()
    {
        if (!is_login()) {
            if (!is_login()) {
                http_response_code(401);
                exit;
            }
        }
        echo '创建文件夹';
       
        var_dump(onedrive::create_folder(visit_path(), $_GET['create_folder']));
    }

    public function cache()
    { 
     !defined('CACHE_PATH') && define('CACHE_PATH', ROOT.'cache/');
       
        echo "删除缓存".visit_path();
     echo drives().visit_path();
      
        var_dump(cache::del(drives().visit_path()));
}
}
