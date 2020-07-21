<?php
//应用入口
require __DIR__.'/init.php';

      
if (!file_exists(ROOT.'config/base.php')  ) {
    header('Location: /src/template/admin/install/index.php');
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
   case 'GET':
        break;
    case 'POST':
      break;
    default:
        route::any('{path:#all}', 'ApiController@'.$_SERVER['REQUEST_METHOD']); exit;

         exit;
}

/*
 *    系统后台

 */
route::group(function () {
    return $_COOKIE['admin'] == config('password');
}, function () {
    route::get('/logout', 'AdminController@logout');
    route::any('/admin/', 'AdminController@settings');
    route::any('/admin/cache', 'AdminController@cache');
    route::any('/admin/update', 'AdminController@update');
    route::any('/admin/show', 'AdminController@show');
    route::any('/admin/setpass', 'AdminController@setpass');
    route::any('/admin/images', 'AdminController@images');
    route::any('/admin/drives', 'AdminController@drives');
    route::any('/admin/sharepoint', 'AdminController@sharepoint');
    // route::any('/admin/upload', 'UploadController@index');
    //守护进程
    route::any('/admin/offline', 'AdminController@offline');
    route::any('/admin/upload/run', 'UploadController@run');
    //上传进程
    route::post('/admin/upload/task', 'UploadController@task');
});
//登陆
route::any('/login', 'AdminController@login');

//跳转到登陆
route::any('/admin/', function () {
    return view::direct(get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).'?/login');
});

define('VIEW_PATH', ROOT.'src/template/'.(config('style') ? config('style') : 'nexmoe').'/');
/**
 *    OneImg.
 */
$images = config('images@base');
if (($_COOKIE['admin'] == config('password') || $images['public'])) {
    route::any('/'.$驱动器.'/images', 'ImagesController@index');
    if ($images['home']) {
        route::any('/', 'ImagesController@index');
    }
}

/*
 *    列目录
 */

    route::any('{path:#all}', 'IndexController@index');


