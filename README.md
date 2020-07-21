## 预览地址  
[pan.mxin.ltd](https://pan.mxin.ltd/)
##毫秒级响应,纵然他前后端没有分离.  
##服务器客户端双重缓存策略.  
##缓存策略精细到文件夹,避免频繁调用api.  
##特色文件管理,如同我的电脑一样轻松复制粘贴.  
##特色网络防火墙功能,避免房子丢了.
##内置api无需填写申请.  
##开源免费想怎么改怎么改不触犯法律(某翼触犯法律作者疑跑路)  
##无需什么scf hreko 又卡又慢,一不小心被攻击了房子都没了.
##低成本 拒绝高昂oss cos 等天价存储.
##无需cdn 前端资源使用强大的微软jsdevli 每次访问只需要几kb流量,无聊刷新也有客户端缓存  
##壁纸随心换.  
##支持主题扩展.
##支持命令行模式
##支持webdav扩展   



其他说明

1.nginx伪静态
```
if (!-f $request_filename){
set $rule_0 1$rule_0;
}
if (!-d $request_filename){
set $rule_0 2$rule_0;
}
if ($rule_0 = "21"){
rewrite ^/(.*)$ /index.php/$1 last;
}
```
基于oneindex项目  重构。  
