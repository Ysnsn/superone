<?php view::layout('layout')?>
<?php view::begin('content');?>
<?php if ($_GET["page"]==""){
          $_GET["page"]=1;  
            }$next=$_GET["page"]+1;$uppage=$_GET["page"]-1;
           // $root.$path=$请求路径;
            function isImage($filename){
                                     $types = '/(\.jpg$|\.png$|\.jpeg$)/i';
                                     if(preg_match($types, trim($filename))){
                                    return true;
                                     }else{
                                    return false; }}?>
<?php if($_REQUEST["type"]=="json"){
      header('Content-type:text/json'); 
     exit(json_encode( $items,JSON_PRETTY_PRINT));}?>
       
<?php function file_ico($item){
            $ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
            if(in_array($ext,['bmp','jpg','jpeg','png','gif','webp'])){
            return "image";
         }
        if(in_array($ext,['mp4','mkv','webm','avi','mpg', 'mpeg', 'rm', 'rmvb', 'mov', 'wmv', 'mkv', 'asf', 'flv', 'm3u8'])){
        return "ondemand_video";
        }
        if(in_array($ext,['ogg','mp3','wav','flac','aac','m4a','ape'])){
        return "audiotrack";
        }
         return "insert_drive_file";
        }?>
                    <div class="mdui-container-fluid">
    <?php if($head):?>
    <div class="mdui-typo" style="padding: 20px;">
        <?php e($head);?>
    </div>
    <?php endif;?>
   


    <div class="nexmoe-item">
        <div class="mdui-row mdui-shadow-3">
            <ul class="mdui-list">
                <li class="mdui-list-item th" style="padding-right:36px;">
                   
                   <?php if(is_login()):?>
                    <label class="mdui-checkbox"><input type="checkbox" value="" id="sellall" onclick="checkall()"><i
                            class="mdui-checkbox-icon"></i></label>
                            
                           <?endif;?> 
                            
                    <div class="mdui-col-xs-12  mdui-col-sm-7 ">文件 <i class="mdui-icon material-icons icon-sort"
                            data-sort="name" data-order="downward">expand_more</i></div>
                    <div class=" mdui-col-sm-3  mdui-text-right">修改时间 <i class="mdui-icon material-icons icon-sort"
                            data-sort="date" data-order="downward">expand_more</i></div>
                    <div class="  mdui-col-sm-2  mdui-text-right">大小 <i class="mdui-icon material-icons icon-sort"
                            data-sort="size" data-order="downward">expand_more</i></div>

                </li>
             


                <?php foreach((array)$items as $item):?>
                <?php if(!empty($item['folder'])):?>

                <li id="<?php echo$item["id"] ?>" class="mdui-list-item mdui-ripple folder" data-sort data-sort-name="<?php e($item['name']);?>"
                    data-sort-date="<?php echo $item['lastModifiedDateTime'];?>"
                    data-sort-size="<?php echo $item['size'];?>" style="padding-right:36px; ">
                    
                    <?php if(is_login()):?>
                    <label class="mdui-checkbox">
                        <input type="checkbox" value="<?php echo$item["id"] ?>" name="itemid" /
                            onclick="onClickHander()">
                        <i class="mdui-checkbox-icon"></i></label>
                        
                         <?endif;?>           
                    <a class="<?php if(is_login()):?>admin<?php endif;?>" href="<?php echo $root.$path.rawurlencode($item['name'])."/";?>">
                        <div id="<?php echo$item["id"] ?>" class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
                            <i class="mdui-icon material-icons">folder_open</i>
                            <span><?php e($item['name']);?></span>
                        </div>
                        <div class="mdui-col-sm-3 mdui-text-right">
                            <?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
                        <div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?>
                        </div>
                    </a>
                </li>


                <?php else:?>
                <li id="<?php echo$item["id"] ?>" class="mdui-list-item file mdui-ripple" data-sort
                    data-sort-name="<?php e($item['name']);?>"
                    data-sort-date="<?php echo $item['lastModifiedDateTime'];?>"
                    data-sort-size="<?php echo $item['size'];?>" .>
                    <?php if(is_login()):?>
                     <label class="mdui-checkbox">
                        <input type="checkbox" value="<?php echo$item["id"] ?>" name="itemid" /
                            onclick="onClickHander()">
                        <i class="mdui-checkbox-icon"></i></label>
                         <?endif;?> 
                        
                    <a <?php echo file_ico($item)=="image"?'class="glightbox"':"";echo file_ico($item)=="ondemand_video"?'class="iframe"':"";echo file_ico($item)=="audiotrack"?'class="audio"':"";echo file_ico($item)=="insert_drive_file"?'class="dl"':""?>
                        data-name="<?php e($item['name']);?>"
                        data-readypreview="<?php echo strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));?>"
                        href="<?php echo $root.$path.rawurlencode($item['name']);?>" target="_blank">
                        <?php if(isImage($item['name']) and $_COOKIE["image_mode"] == "1"):?>
                        <img class="mdui-img-fluid" src="<?php echo$root.$path.rawurlencode($item['name']);?>">
                        <?php else:?>
                        <div id="<?php echo$item["id"] ?>" class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
                            <i class="mdui-icon material-icons"><?php echo file_ico($item);?></i>
                            <span id="<?php echo$item["id"] ?>"><?php e($item['name']);?></span>
                        </div>
                        <div class="mdui-col-sm-3 mdui-text-right">
                            <?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
                        <div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?>
                        </div>
                        <?php endif;?>
                    </a>

                    <div class="forcedownload ">
                        <a title="直接下载" href="<?php echo $root.$path.rawurlencode($item['name']);?>">
                            <button class="mdui-btn mdui-ripple mdui-btn-icon"><i
                                    class="mdui-icon material-icons">file_download</i></button>
                        </a>
                    </div>
                    
                   
                     
                  
                    
                </li>
                <?php endif;?>
                <?php endforeach;?>

                <?php if($totalpage > 1 ):?>
                <li class="mdui-list-item th">
                    <div class="mdui-col-sm-6 mdui-left mdui-text-left">
                        <?php if(($page-1) >= 1 ):?>
                        <a href="  <?php echo "/".$驱动器."/".$请求路径."?page=".$uppage; ?>/"
                            class="mdui-btn mdui-btn-raised">上一页</a>
                        <?php endif;?>
                        <?php if(($page+1) <= $totalpage ):?>
                        <a href="<?php echo "/".$驱动器."/".$请求路径."?page=".$next ?>/"
                            class="mdui-btn mdui-btn-raised  mdui-right">下一页</a>
                        <?php endif;?>
                    </div>
                    <div class="mdui-col-sm-6 mdui-right mdui-text-right">
                        <div class="mdui-right mdui-text-right"><span class="mdui-chip-title">Page:
                                <?php e($page);?>/<?php e($totalpage);?></span></div>
                    </div>
                </li>
                <?php endif;?>
            </ul>
        </div>
    </div>
    <?php if($readme):?>
    <div class="mdui-typo mdui-shadow-3" style="padding: 20px;margin: 10px; 0px 0px 0px">
        <div class="mdui-chip">
            <span class="mdui-chip-icon"><i class="mdui-icon material-icons">face</i></span>
            <span class="mdui-chip-title">README.md</span>
        </div>
        <?php e($readme);?>
    </div>
    <?php endif;?>

</div>
<?php if(is_login() or config("guestupload")=="on"):?>

                    
                    <div class="mdui-fab-wrapper" id="exampleFab" mdui-fab="options">
    <button class="mdui-fab mdui-ripple mdui-color-theme-accent">
        <!-- 默认显示的图标 -->
        <i class="mdui-icon material-icons">add</i>

        <!-- 在拨号菜单开始打开时，平滑切换到该图标，若不需要切换图标，则可以省略该元素 -->
        <i class="mdui-icon mdui-fab-opened material-icons">touch_app</i>
    </button>
    <div class="mdui-fab-dial">
        <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-pink" onclick="uploadfietwo()"
            mdui-dialog="{target: '#exampleDialog'}"><i class="mdui-icon material-icons">backup</i></button>
        <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-red " onclick="uploadfieone()"
            mdui-dialog="{target: '#exampleDialog'}"><i class="mdui-icon material-icons">file_upload</i></button>
        <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-red " onclick="create_folder()"><i
                class="mdui-icon material-icons">add</i></button>


    </div>
</div>
<?php endif;?>
<?php

if (    ( !is_login() && config("guestpreload") )             ) {
    
    
    $i=0;
    $num=config("preloadnum")?? 10;
    foreach ($items as $item) {

        if(!empty($item['folder'])){
            if ($i==$num){break;}
            echo 
            ' <link rel="prefetch" href=" '.rawurlencode($item['name']).'/">';
                    
                }

$i++;

    }
}
?>
 <script>var 驱动器 = "<?php echo DRIVEID; ?>"
var 请求路径= "<?php echo VIST_PATH; ?>"
var move= "<?php echo $me=str_replace("\"","\\\"",$_COOKIE["moveitem"]);
 ?>";
</script>
<?php view::end('content');?>
