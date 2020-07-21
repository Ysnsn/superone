<?php

//use cahce;
//use onedrive;
//use view;
//use fetch
class IndexController
{
    private $url_path;
    private $name;
    private $path;
    private $items;
    private $time;
    public static $驱动器;
    public static $请求路径;

    public function __construct()
    {
          $_GET["path"]=str_replace("?/","",$_GET["path"]);
        $requesturi = explode('/', $_GET['path']);

        $驱动器 = $requesturi['0'];
        if ($驱动器 == '') {
            $驱动器 = 'default';
        }

       

        (load_config($驱动器));
$请求路径=visit_path();
        ///初始化配置文件完成

        //分页页数
        $this->z_page = config('page_item') ?? 200;
        $paths = explode('/', rawurldecode($请求路径));

        if (substr($_SERVER['REQUEST_URI'], -1) != '/') {
            $this->name = array_pop($paths);
        }
      

        preg_match_all("(\.page\-([0-9]*)/$)", get_absolute_path(join('/', $paths)), $mat);
        if (empty($mat[1][0])) {
            $this->page = 1;
        } else {
            $this->page = $mat[1][0];
        }
        $this->page = $_REQUEST['page'] ?? '1';
        $this->url_path = preg_replace("(\.page\-[0-9]*/$)", '', get_absolute_path(join('/', $paths)));

        $this->path = get_absolute_path(config('onedrive_root@'.drives()).$this->url_path);
        //获取文件夹下所有元素
        $this->items = $this->items($this->path);
      
       
    }

    public function index()
    {
         header("X-Powered-By:  Shanghai Mingxin Technology Co., Ltd.");
         header("Access-Control-Allow-Origin: *");
         header("Access-Control-Allow-Methods: GET, POST, OPTIONS,VIEW");

         $this->is404();
        $this->checkcache();
        //验证缓存是否异常

        $this->is_password();
        //客户端缓存
        $this->clientcache();

        //输出目录或者文件
        if (!empty($this->name)) {//file
            return $this->file();
        } else {//dir
            return $this->dir();
        }
    }

    //客户端缓存验证管理员不启用
    public function clientcache()
    {
        $docomenttime = cache::gettime(drives().$this->path);

        if (is_login()) {
            return;
        }
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            // 如果文件没有修改并且当大于当前时间时, 表示还在缓存中... 释放304
            if ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == gmdate('D, d M Y H:i:s', $docomenttime).' GMT' && $docomenttime + 3500 > time()) {
                header('HTTP/1.1 304 Not Modified');

                exit();
            }
        }

            header('Cache-Control:max-age=0');

                header('Cache-Control:max-age=600');

            header('Expires: '.gmdate('D, d M Y H:i:s', $docomenttime+3500).' GMT');
            header('Last-Modified: '.gmdate('D, d M Y H:i:s',$docomenttime).' GMT');
    }

    //缓存验证防止首页空白需要优化
    public function checkcache()
    {
        if (file_exists(ROOT.'config/'.self::$驱动器.'.php')) {
            if ($this->path == '/') {
                if ($this->items == null) {
                    $result = onedrive::upload(config('onedrive_root').'缓存错误日志.txt', '首页不能为空');
                    if (function_exists('opcache_reset')) {
                        opcache_reset();
                    }
                    unset($this->items);
                    $this->items = onedrive::dir($this->path);
                    cache::set(drives().$this->path, $this->items, 600);

                    header('refresh: 0');
                }
            }
        }
    }

    //判断是否加密需要优化跨目录验证
    public function is_password()
    {
        if (empty($this->items['.password'])) {
            return false;
        } else {
            $this->items['.password']['path'] = get_absolute_path($this->path).'.password';
        }

        $password = $this->get_content2($this->items['.password']);
        list($password) = explode("\n", $password);
        $password = trim($password);
        unset($this->items['.password']);
        if (!empty($password) && strcmp($password, $_COOKIE[md5($this->path)]) === 0) {
            return true;
        }

        $this->password($password);
    }

    public function password($password)
    {
        if (!empty($_REQUEST['password']) && strcmp($password, $_REQUEST['password']) === 0) {
            setcookie(md5($this->path), $_POST['password']);

            return true;
        }
        $navs = $this->navs();
        echo view::load('password')->with('navs', $navs);
        exit();
    }

    //返回下载链接
    public function file()
    {
        $item = $this->items[$this->name];
        if ($item['folder']) {//是文件
            $url = $_SERVER['REQUEST_URI'].'/';
        } elseif (!is_null($_GET['t'])) {//缩略图
            $url = $this->thumbnail($item);
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' || !is_null($_GET['s'])) {
            return $this->show($item);
        } else {//返回下载链接
            if (config('proxy_domain') != '') {
                $url = str_replace(config('main_domain'), config('proxy_domain'), $item['downloadUrl']);
            } else {
                $url = $item['downloadUrl'];
            }
        }
         header('Location: '.$url);
    }

    //列目录
    public function dir()
    { //$root = get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).(config('root_path') ? '/?' : '');
        $root = get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).(config('root_path').drives());
        $navs = $this->navs();

        if ($this->items['index.html']) {
            $this->items['index.html']['path'] = get_absolute_path($this->path).'index.html';
            $index = $this->get_content($this->items['index.html']);
            header('Content-type: text/html');
            echo $index;
            exit();
        }

        if ($this->items['README.md']) {
            $this->items['README.md']['path'] = get_absolute_path($this->path).'README.md';
            $readme = $this->get_content2($this->items['README.md']);
            $Parsedown = new Parsedown();
            $readme = $Parsedown->text($readme);
            //不在列表中展示
        //	unset($this->items['README.md']);
        }

        if ($this->items['HEAD.md']) {
            $this->items['HEAD.md']['path'] = get_absolute_path($this->path).'HEAD.md';
            $head = $this->get_content2($this->items['HEAD.md']);
            $Parsedown = new Parsedown();
            $head = $Parsedown->text($head);
            //不在列表中展示
            unset($this->items['HEAD.md']);
        }

        $this->totalpage = ceil(count($this->items) / $this->z_page);

        if ($this->page * $this->z_page >= count($this->items)) {
            $this->page = $this->totalpage;
        }

        return view::load('list')->with('title', config('title_name'))
                    ->with('navs', $navs)
                    ->with('path', join('/', array_map('rawurlencode', explode('/', $this->url_path))))
                    ->with('root', $root)
                    ->with('items', array_slice($this->items, $this->z_page * ($this->page - 1), $this->z_page))
                    ->with('head', $head)
                    ->with('readme', $readme)
                    ->with('page', $this->page)
                    ->with('totalpage', $this->totalpage)
                    ->with('url', $url);
    }

    public function show($item)
    {
        $root = get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).(config('root_path') ? '?/' : '');
        $ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
        $data['title'] = $item['name'];
        $data['navs'] = $this->navs();
        $data['item'] = $item;
        $data['ext'] = $ext;
        $data['item']['path'] = get_absolute_path($this->path).$this->name;
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $uri = onedrive::urlencode(get_absolute_path($this->url_path.'/'.$this->name));
        $data['url'] = $http_type.$_SERVER['HTTP_HOST'].'/'.drives().$root.$uri;

        $show = config('show');
        foreach ($show as $n => $exts) {
            if ($ext == 'pdf') {
                return view::load('show/pdf')->with($data);
            } elseif (in_array($ext, $exts)) {
                return view::load('show/'.$n)->with($data);
            }
        }

        header('Location: '.$item['downloadUrl']);
    }

    //缩略图
    public function thumbnail($item)
    {
        if (!empty($_GET['t'])) {
            list($width, $height) = explode('|', $_GET['t']);
        } else {
            //800 176 96
            $width = $height = 800;
        }
        $item['thumb'] = onedrive::thumbnail($this->path.$this->name);
        list($item['thumb'], $tmp) = explode('&width=', $item['thumb']);
        $item['thumb'] .= strpos($item['thumb'], '?') ? '&' : '?';

        return $item['thumb']."width={$width}&height={$height}";
    }

    //文件夹下元素
    public function items($path, $fetch = false)
    {
        $items = cache::get(drives().$this->path, function () {
            return onedrive::dir($this->path);
        }, config('cache_expire_time'));

        return $items;
    }

    //导航栏目
    public function drivelist()
    {
        $list = [];
        $i;
        $filess = scandir(ROOT.'config/');
        foreach ($filess as $part) {
            if ('.' == $part || '..' == $part || 'default1.php' == $part || 'default.php' == $part || 'uploads.php' == $part || 'uploaded.php' == $part || 'base.php' == $part || '.DS_Store' == $part) {
                continue;
            } else {
                $v = str_replace('.php', '', $part);
                echo $v;
                $list[$i] = $v;
                ++$i;
            }
        }

        return  $list;
    }

    //导航栏目
    public function navs()
    {
        $root = get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).drives().config('root_path');
        $navs['/'] = get_absolute_path($root.'/');
        foreach (explode('/', $this->url_path) as $v) {
            if (empty($v)) {
                continue;
            }
            $navs[rawurldecode($v)] = end($navs).$v.'/';
        }
        if (!empty($this->name)) {
            $navs[$this->name] = end($navs).urlencode($this->name);
        }

        return $navs;
    }

    // 文件直接输出
    public static function get_content2($item)
    {
        $content = cache::get('content_'.$item['path'], function () use ($item) {
            $resp = fetch::get($item['downloadUrl']);
            if ($resp->http_code == 200) {
                return $resp->content;
            }
        }, config('cache_expire_time'));

        return $content;
    }

    //文件axios加载
    public static function get_content($item)
    {
        $content = cache::get('content_'.$item['path'], function () use ($item) {
            $resp = fetch::get($item['downloadUrl']);
            if ($resp->http_code == 200) {
                return '<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
axios.get("'.$item['downloadUrl'].'")
  .then(function (response) {
    console.log(response);
    document.write(response.data)
  })
  .catch(function (error) {
    console.log(error);
  });
</script>';

                return $resp->content;
            }
        }, config('cache_expire_time'));

        return $content;
    }

    //404
    public function is404()
    {
        if (!empty($this->items[$this->name]) || (empty($this->name) && is_array($this->items))) {
            return false;
        }
        //如果是文件，且上一级文件夹存在这个文件，则不是404
        //如果是文件夹，且可以获取到文件夹内容，则不是404
        //判断时如果缓存了上一级文件夹内容，则从上一级文件夹内容读，否则自动缓存
        http_response_code(404);
        view::load('404')->show();
        die();
    }

    public function __destruct()
    {
        if (!function_exists('fastcgi_finish_request')) {
            return;
        }
    }
}
