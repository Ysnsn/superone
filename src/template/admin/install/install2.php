
<?php

if (file_exists(__DIR__.'../../../config/base.php')) {
    header('Location: /admin');
    exit;
}
?><!DOCTYPE html>
<!-- saved from url=(0025)http://x.mxin.ltd/?step=2 -->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title> 系统安装 - Powered by PHP168.COM</title>
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <link rel="stylesheet" href="https://www.layuicdn.com/layui-v2.1.7/css/layui.css">
    <link rel="stylesheet" href="style.css">
   
    <script src="https://www.layuicdn.com/layui-v2.1.7/layui.js"></script>
    <script>
        layui.config({
            base: '/public/static/install/js/',
            version: '1594231171'
        }).use('global');
    </script>
    <script async="" charset="utf-8" src="./istall2_files/global.js"></script>
</head>

<body>
    <div class="header">

    </div>
    <style type="text/css">
        .layui-table td,
        .layui-table th {
            text-align: left;
        }

        .layui-table tbody tr.no {
            background-color: #f00;
            color: #fff;
        }
    </style>
    <div class="install-box">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>运行环境检测</legend>
        </fieldset>
        <table class="layui-table" lay-skin="line">
            <thead>
                <tr>
                    <th>环境名称</th>
                    <th>当前配置</th>
                    <th>所需配置</th>
                </tr>
            </thead>
            <tbody>
                <tr class="ok">
                    <td>操作系统</td>
                    <td>Linux</td>
                    <td>Windows/类Unix</td>
                </tr>
                <tr class="ok">
                    <td>PHP版本</td>
                    <td><?php echo PHP_VERSION; ?></td>
                    <td>7.0及以上</td>
                </tr>
                
            </tbody>
        </table>
        <table class="layui-table" lay-skin="line">
            <thead>
                <tr>
                    <th>目录/文件</th>
                    <th>所需权限</th>
                    <th>当前权限</th>
                </tr>
            </thead>
            <tbody>
                <tr class="ok">
                    <td>./config</td>
                    <td>读写</td>
                    <td>读写</td>
                </tr>
                <tr class="ok">
                    <td>./cache</td>
                    <td>读写</td>
                    <td>读写</td>
                </tr>
                
               
            </tbody>
        </table>
        <table class="layui-table" lay-skin="line">
            <thead>
                <tr>
                    <th>函数/扩展</th>
                    <th>类型</th>
                    <th>结果</th>
                </tr>
            </thead>
            <tbody>
             
                <tr class="yes">
                    <td>zip</td>
                    <td>模块</td>
                    <td>支持</td>
                </tr>
                
                <tr class="yes">
                    <td>curl</td>
                    <td>模块</td>
                    <td><?php  if (!function_exists('curl_init')) {
    echo '不支持';
} else {
    echo '支持';
}    ?></td>
                </tr>
                
                <tr class="yes">
                    <td>file_get_contents</td>
                    <td>函数</td>
                    <td><?php  if (!function_exists('file_get_contents')) {
    echo '不支持';
} else {
    echo '支持';
}    ?></td>
                </tr>
               
            </tbody>
        </table>
        <div class="step-btns">
            <a href="index.php" class="layui-btn layui-btn-primary layui-btn-big fl">返回上一步</a>
            <a href="install3.php" class="layui-btn layui-btn-big layui-btn-normal fr">进行下一步</a>
        </div>
    </div>


</body>

</html>