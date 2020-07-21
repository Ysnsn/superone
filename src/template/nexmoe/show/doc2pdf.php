<?php view::layout('layout')?>

<?php
$item['thumb'] = onedrive::thumbnail($item['path']);
?>
<?php view::begin('content');?>
 <script src="https://cdn.jsdelivr.net/combine/npm/mdui@0.4.3/dist/js/mdui.min.js,gh/mcstudios/glightbox/dist/js/glightbox.min.js,npm/aplayer/dist/APlayer.min.js,npm/js-cookie@2/src/js.cookie.min.js,gh/axios/axios@0.19.2/dist/axios.min.js"></script>
	<iframe id="mask" src='<?php echo str_replace('transform/thumbnail?', 'transform/pdf?',$item['thumb']);?>' 
		allowfullscreen="allowfullscreen"
        mozallowfullscreen="mozallowfullscreen"
        msallowfullscreen="msallowfullscreen"
        oallowfullscreen="oallowfullscreen"
        webkitallowfullscreen="webkitallowfullscreen"
        width="100%"
        style="position: absolute;top:0;left:0; z-index:10;"
        ></iframe>
<a href="<?php e($url);?>" class="mdui-fab mdui-fab-fixed mdui-ripple mdui-color-theme-accent" style="z-index:99;"><i class="mdui-icon material-icons">file_download</i></a>
<script>
$ = mdui.JQ;
$(function(){  
      
    var sWidth=document.documentElement.clientWidth;  
    var sHeight=document.documentElement.clientHeight;  
    //获取页面的可视区域高度和宽度  
    var wHeight=document.documentElement.clientHeight;  
    var oMask=document.getElementById("mask");  
    var oMaskIframe=document.getElementById("maskIframe");  
      
    oMask.style.height=(sHeight-1)+"px";  
    oMask.style.width=(sWidth-4)+"px";  
      
});  
</script>
<?php view::end('content');?>