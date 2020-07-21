<?php

//修改时间2020年7.7日5.14
class onedrive
{
    public static $client_id;
    public static $client_secret;
    public static $redirect_uri;
    public static $typeurl;
    public static $oauth_url;
    public static $drivestype;
    public static $api;
    public static $api_url;
    public static $access_token;

    //验证URL，浏览器访问、授权
    public static function authorize_url()
    {
        $client_id = self::$client_id;
        $scope = urlencode('offline_access files.readwrite.all');
        $redirect_uri = self::$redirect_uri;
        $url = self::$oauth_url."/authorize?client_id={$client_id}&scope={$scope}&response_type=code&redirect_uri={$redirect_uri}";

        if ($_SERVER['HTTP_HOST'] != 'localhost') {
            $url .= '&state='.urlencode('http://'.$_SERVER['HTTP_HOST'].get_absolute_path(dirname($_SERVER['PHP_SELF'])));
        }

        return $url;
    }

    //使用 $code, 获取 $refresh_token
    public static function authorize($code = '')
    {
        $client_id = self::$client_id;
        $client_secret = self::$client_secret;
        $redirect_uri = self::$redirect_uri;

        $url = self::$oauth_url.'/token';
        $post_data = "client_id={$client_id}&redirect_uri={$redirect_uri}&client_secret={$client_secret}&code={$code}&grant_type=authorization_code";
        fetch::$headers = 'Content-Type: application/x-www-form-urlencoded';
        $resp = fetch::post($url, $post_data);
        $data = json_decode($resp->content, true);

        return $data;
    }

    //使用 $refresh_token，获取 $access_token
    public static function get_token($refresh_token)
    {
        $client_id = self::$client_id;
        $client_secret = self::$client_secret;
        $redirect_uri = self::$redirect_uri;

        $request['url'] = self::$oauth_url.'/token';
        $request['post_data'] = "client_id={$client_id}&redirect_uri={$redirect_uri}&client_secret={$client_secret}&refresh_token={$refresh_token}&grant_type=refresh_token";
        $request['headers'] = 'Content-Type: application/x-www-form-urlencoded';
        $resp = fetch::post($request);
        $data = json_decode($resp->content, true);

        return $data;
    }

    public static function access_token()
    {
        $varrr = explode('/', $_SERVER['REQUEST_URI']);
        $驱动器 = $varrr['1'];
        $配置文件 = config('@'.$驱动器);
        if ($配置文件['expires_on'] > time() + 600) {
            return $token['access_token'];
        } else {
            $refresh_token = config('refresh_token@'.$驱动器);
            $token = self::get_token($refresh_token);
            if (!empty($token['refresh_token'])) {
                $配置文件['expires_on'] = time() + $token['expires_in'];
                $配置文 = $token;

                config('@'.$驱动器, $配置文件);

                return $token['access_token'];
            }
        }

        return '';
    }

    // 生成一个request，带token
    public static function request($path = '/', $query = '')
    {
        $path = self::urlencode($path);
        $path = empty($path) ? '/' : ":/{$path}:/";
        $token = self::$access_token;
        $request['headers'] = "Authorization: bearer {$token}".PHP_EOL.'Content-Type: application/json'.PHP_EOL;
        $request['url'] = self::$typeurl.$path.$query;

        return $request;
    }

    //返回目录信息
    public static function dir($path = '/')
    {
        $request = self::request($path, 'children?select=name,size,folder,lastModifiedDateTime,id,@microsoft.graph.downloadUrl');

        $items = array();
        self::dir_next_page($request, $items);
        //不在列表显示的文件夹
        $hide_list = explode(PHP_EOL, config('onedrive_hide'));
        if (is_array($hide_list) && count($hide_list) > 0) {
            foreach ($hide_list as $hide_dir) {
                foreach ($items as $key => $_array) {
                    if (!empty(trim($hide_dir)) && stristr($key, trim($hide_dir))) {
                        unset($items[$key]);
                    }
                }
            }
        }
        

        return $items;
    }

    //通过分页获取页面所有item
    public static function dir_next_page($request, &$items, $retry = 0)
    {
        $resp = fetch::get($request);

        $data = json_decode($resp->content, true);
        if (empty($data) && $retry < 3) {
            ++$retry;

            return self::dir_next_page($request, $items, $retry);
        }

        foreach ((array) $data['value'] as $item) {
            //var_dump($item);
            $items[$item['name']] = array(
                'name' => $item['name'],
                'id' => $item['id'],
                'size' => $item['size'],
                'lastModifiedDateTime' => strtotime($item['lastModifiedDateTime']),
                'downloadUrl' => $item['@microsoft.graph.downloadUrl'],
                'folder' => empty($item['folder']) ? false : true,
            );
        }

        if (!empty($data['@odata.nextLink'])) {
            $request = self::request();
            $request['url'] = $data['@odata.nextLink'];

            return self::dir_next_page($request, $items);
        }
    }

    //文件重命名 by github
    public static function rename($itemid, $name)
    {
        $access_token = self::$access_token;
        $api = str_replace('root', 'items/'.$itemid, self::$typeurl);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => "{\n  \"name\": \"".$name."\"\n}",
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        var_dump($response);
    }

    //文件删除 by by bygithub.com/742481030/oneindex
    public static function delete($itemid = array())
    {
        $access_token = self::$access_token;
        $apie = str_replace('root', 'items/', self::$api);

        $apis = array();

        for ($i = 0; $i < count($itemid); ++$i) {
            $apis[$i] = $apie.$itemid[$i];
        }

        $result = $res = $ch = array();
        $nch = 0;
        $mh = curl_multi_init();
        foreach ($apis as $nk => $url) {
            $timeout = 20;
            $ch[$nch] = curl_init();
            curl_setopt_array($ch[$nch], array(
                CURLOPT_URL => $url,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'DELETE',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.$access_token,
                    'Content-Type: application/json',
                ),
            ));

            curl_multi_add_handle($mh, $ch[$nch]);
            ++$nch;
        }

        /* wait for performing request */

        do {
            $mrc = curl_multi_exec($mh, $running);
        } while (CURLM_CALL_MULTI_PERFORM == $mrc);

        while ($running && $mrc == CURLM_OK) {
            // wait for network
            if (curl_multi_select($mh, 0.5) > -1) {
                // pull in new data;
                do {
                    $mrc = curl_multi_exec($mh, $running);
                } while (CURLM_CALL_MULTI_PERFORM == $mrc);
            }
        }

        if ($mrc != CURLM_OK) {
            error_log('CURL Data Error');
        }

        /* get data */

        $nch = 0;

        foreach ($apis as $moudle => $node) {
            if (($err = curl_error($ch[$nch])) == '') {
                $res[$nch] = curl_multi_getcontent($ch[$nch]);
                $result[$moudle] = $res[$nch];
            } else {
                error_log('curl error');
            }

            curl_multi_remove_handle($mh, $ch[$nch]);
            curl_close($ch[$nch]);
            ++$nch;
        }

        curl_multi_close($mh);
        echo '批量处理完成';
    }

    //文件路径转itemsid by bygithub.com/742481030/oneindex
    public static function pathtoid($access_token, $path)
    {
        $request = self::request(urldecode($path));
        $request['headers'] = "Authorization: bearer {$access_token}".PHP_EOL.'Content-Type: application/json'.PHP_EOL;
        $resp = fetch::get($request);
        $data = json_decode($resp->content, true);

        return $data['id'];
    }

    //剪切文件 by  github.com/742481030/oneindex
    public static function movepast($itemid, $newitemid)
    {
        
        
        $api = str_replace('root', 'items/'.$itemid, self::$typeurl);
       
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => "{\n  \"parentReference\": {\n    \"id\": \"".$newitemid."\"\n  }\n  \n}",
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.onedrive::$access_token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        echo $id.'完成';
    }

    //通过id下载文件 by github.com/742481030/oneindex/one index
    public static function downloadbyid($itemid)
    {
        
        $token = self::$access_token;
        $api = str_replace('root', 'items/', self::$api);

        $request['headers'] = "Authorization: bearer {$token}".PHP_EOL.'Content-Type: application/json'.PHP_EOL;
        $request['url'] = $api.$itemid;
        $resp = fetch::get($request);

        $ss = json_decode($resp->content, true)['@microsoft.graph.downloadUrl'];

        header('Location:'.$ss);
    }

    //文件批量移动 by  github.com/742481030/oneindex/oneindex
    public static function 批量移动($itemid = array(), $newitemid)
    {
        
       
        var_dump($itemid);
        $apis = array();
        $api = str_replace('root', 'items/', self::$typeurl);
        for ($i = 0; $i < count($itemid); ++$i) {
            $apis[$i] = $api.$itemid[$i];
        }

       
        $result = $res = $ch = array();
        $nch = 0;
        $mh = curl_multi_init();
        foreach ($apis as $nk => $url) {
            $timeout = 20;
            $ch[$nch] = curl_init();
            curl_setopt_array($ch[$nch], array(
                CURLOPT_URL => $url,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PATCH',
                CURLOPT_POSTFIELDS => "{\n  \"parentReference\": {\n    \"id\": \"".$newitemid."\"\n  }\n  \n}",
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.self::$access_token,
                    'Content-Type: application/json',
                ),
            ));

            curl_multi_add_handle($mh, $ch[$nch]);
            ++$nch;
        }

        /* wait for performing request */

        do {
            $mrc = curl_multi_exec($mh, $running);
        } while (CURLM_CALL_MULTI_PERFORM == $mrc);

        while ($running && $mrc == CURLM_OK) {
            // wait for network
            if (curl_multi_select($mh, 0.5) > -1) {
                // pull in new data;
                do {
                    $mrc = curl_multi_exec($mh, $running);
                } while (CURLM_CALL_MULTI_PERFORM == $mrc);
            }
        }

        if ($mrc != CURLM_OK) {
            error_log('CURL Data Error');
        }

        /* get data */

        $nch = 0;

        foreach ($apis as $moudle => $node) {
            if (($err = curl_error($ch[$nch])) == '') {
                $res[$nch] = curl_multi_getcontent($ch[$nch]);
                $result[$moudle] = $res[$nch];
            } else {
                error_log('curl error');
            }

            curl_multi_remove_handle($mh, $ch[$nch]);
            curl_close($ch[$nch]);
            ++$nch;
        }

        curl_multi_close($mh);
        echo '批量处理完成';
        
    }

    //获取站点id  github.com/742481030/oneindex/oneindex
    public static function get_siteidbyname($sitename, $access_token, $api_url)
    {
        $request['headers'] = "Authorization: bearer {$access_token}".PHP_EOL.'Content-Type: application/json'.PHP_EOL;
        $request['url'] = $api_url.'/sites/root';
        $resp = fetch::get($request);
        $data = json_decode($resp->content, true);
     $hostname = $data['siteCollection']['hostname'];
        $getsiteid = $api_url.'/sites/'.$hostname.':'.$_REQUEST['site'];
        $request['url'] = $getsiteid;
        $respp = fetch::get($request);
        $datass = json_decode($respp->content, true);

        return $siteidurl = $datass['id'];
    }
public  static function getuserinfo($token,$apiurl){
    
     $request['headers'] = "Authorization: bearer {$token}".PHP_EOL.'Content-Type: application/json'.PHP_EOL;
        $request['url'] = $apiurl;
        $resp = fetch::get($request);
        return $data = json_decode($resp->content, true);
}
    //新建文件夹 by  github.com/742481030/oneindex/oneindex
    public static function create_folder($path = '/', $name = '新建文件夹')
    {
        $path = self::urlencode($path);
        $path = empty($path) ? '/' : ":/{$path}:/";
        $api = self::$typeurl.$path.'/children';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "{\n  \"name\": \"".$name."\",\n  \"folder\": { },\n  \"@microsoft.graph.conflictBehavior\": \"rename\"\n}",
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.self::$access_token.'',
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    //文件缩略图链接
    public static function thumbnail($path, $size = 'large')
    {
        $request = self::request($path, "thumbnails/0?select={$size}");
        $resp = fetch::get($request);
        $data = json_decode($resp->content, true);
        $request = self::request($path, "thumbnails/0?select={$size}");

        return @$data[$size]['url'];
    }

    //分享链接
    public static function share($path)
    {
        $request = self::request($path, 'createLink');
        $post_data['type'] = 'view';
        $post_data['scope'] = 'anonymous';
        $resp = fetch::post($request, json_encode($post_data));
        $data = json_decode($resp->content, true);

        return $data;
    }

    //简单文件上传函数
    public static function upload($path, $content)
    {
        $request = self::request($path, 'content');
        $request['post_data'] = $content;
        $resp = fetch::put($request);
        $data = @json_decode($resp->content, true);

        return $data;
    }

    public static function upload_url($path, $url)
    {
        $request = self::request(get_absolute_path(dirname($path)), 'children');
        $request['headers'] .= 'Prefer: respond-async'.PHP_EOL;
        $post_data['@microsoft.graph.sourceUrl'] = $url;
        $post_data['name'] = pathinfo($path, PATHINFO_BASENAME);
        $post_data['file'] = json_decode('{}');
        $request['post_data'] = json_encode($post_data);
        $resp = fetch::post($request);
        list($tmp, $location) = explode('ocation:', $resp->headers);
        list($location, $tmp) = explode(PHP_EOL, $location);

        return trim($location);
    }

    //上传会话
    public static function create_upload_session($path)
    {
        $request = self::request($path, 'createUploadSession');
        $request['post_data'] = '{"item": {"@microsoft.graph.conflictBehavior": "fail"}}';
        $token = self::access_token();
        $resp = fetch::post($request);
        $data = json_decode($resp->content, true);
        if ($resp->http_code == 409) {
            return false;
        }

        return $data;
    }

    //分块上传
    public static function upload_session($url, $file, $offset, $length = 10240)
    {
        $token = self::access_token();
        $file_size = self::_filesize($file);
        $content_length = (($offset + $length) > $file_size) ? ($file_size - $offset) : $length;
        $end = $offset + $content_length - 1;
        $post_data = self::file_content($file, $offset, $length);

        $request['url'] = $url;
        $request['curl_opt'] = [CURLOPT_TIMEOUT => 360];
        $request['headers'] = "Authorization: bearer {$token}".PHP_EOL;
        $request['headers'] .= "Content-Length: {$content_length}".PHP_EOL;
        $request['headers'] .= "Content-Range: bytes {$offset}-{$end}/{$file_size}";
        $request['post_data'] = $post_data;
        $resp = fetch::put($request);
        $data = json_decode($resp->content, true);

        return $data;
    }

    //文件上传进度
    public static function upload_session_status($url)
    {
        $token = self::access_token();
        fetch::$headers = "Authorization: bearer {$token}".PHP_EOL.'Content-Type: application/json'.PHP_EOL;
        $resp = fetch::get($url);
        $data = json_decode($resp->content, true);

        return $data;
    }

    //删除上传会话
    public static function delete_upload_session($url)
    {
        $token = self::access_token();
        fetch::$headers = "Authorization: bearer {$token}".PHP_EOL.'Content-Type: application/json'.PHP_EOL;
        $resp = fetch::delete($url);
        $data = json_decode($resp->content, true);

        return $data;
    }

    //获取文件信息
    public static function file_content($file, $offset, $length)
    {
        $handler = fopen($file, 'rb') or die('获取文件内容失败');
        fseek($handler, $offset);

        return fread($handler, $length);
    }

    //文件大小格式化
    public static function human_filesize($size, $precision = 1)
    {
        for ($i = 0; ($size / 1024) > 1; $i++, $size /= 1024) {
        }

        return round($size, $precision).(['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'][$i]);
    }

    //路径转码
    public static function urlencode($path)
    {
        foreach (explode('/', $path) as $k => $v) {
            if (empty(!$v)) {
                $paths[] = rawurlencode($v);
            }
        }

        return @join('/', $paths);
    }

    //文件大小
    public static function _filesize($path)
    {
        if (!file_exists($path)) {
            return false;
        }
        $size = filesize($path);

        if (!($file = fopen($path, 'rb'))) {
            return false;
        }

        if ($size >= 0) { //Check if it really is a small file (< 2 GB)
            if (fseek($file, 0, SEEK_END) === 0) { //It really is a small file
                fclose($file);

                return $size;
            }
        }

        //Quickly jump the first 2 GB with fseek. After that fseek is not working on 32 bit php (it uses int internally)
        $size = PHP_INT_MAX - 1;
        if (fseek($file, PHP_INT_MAX - 1) !== 0) {
            fclose($file);

            return false;
        }

        $length = 1024 * 1024;
        while (!feof($file)) { //Read the file until end
            $read = fread($file, $length);
            $size = bcadd($size, $length);
        }
        $size = bcsub($size, $length);
        $size = bcadd($size, strlen($read));

        fclose($file);

        return $size;
    }
}
