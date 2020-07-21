


var $$ = mdui.JQ;




var _wr = function(type) {
   var orig = history[type];
   return function() {
       var rv = orig.apply(this, arguments);
      var e = new Event(type);
       e.arguments = arguments;
       window.dispatchEvent(e);
       return rv;
   };
};
 history.pushState = _wr('pushState');
 history.replaceState = _wr('replaceState');



window.addEventListener('replaceState', function(e) {
  
});
window.addEventListener('pushState', function(e) {
 
});




    //监听鼠标右击事件 / 移动端长按事件
    $$(document).on('contextmenu', function (e) {
      //   console.log(e);

        //0：移动端长按（iOS 测试未通过）
        //2：电脑端右键
        
            e.preventDefault();//阻止冒泡，阻止默认的浏览器菜单

            //鼠标点击位置，相对于浏览器
            var _x = e.pageX,
                _y = e.pageY;

            let $div = $$("<div></div>").css({
                position: 'absolute',
                top: _y+'px',
                left: _x+'px',
            });
            $$('body').append($div);//创建临时DOM

            // anchorSelector 表示触发菜单的元素的 CSS 选择器或 DOM 元素
            // menuSelector 表示菜单的 CSS 选择器或 DOM 元素
            // options 表示组件的配置参数，见下面的参数列表
            // 完整文档列表：https://doc.nowtime.cc/mdui/menu.html
            var instq = new mdui.Menu($div, '#menu');
            instq.open();//打开菜单栏
            $div.remove();//销毁创建的临时DOM
            
        
        console.log(e);
             console.log(e);(e.target.id);
             console.log(e.path.a)
      if(e.target.id=="" | e.target.id <999999999999999){
           instq.close();
      }
      
   Cookies.set('flieid', e.target.id, { expires: 0.025 });
        // console.log(e.relatedTarget.tagName);
        console.log(e.target.id);
    });
    
    
    
    function share(){
         var id = Cookies.get('flieid')
          var url = document.domain;
       alert ("https://"+url+'/'+驱动器+"/?downid="+id);
   var ss=     $("#"+id).eq(1).attr("href")
   
    }






$$(function() {
    $$('.file .iframe').each(function() {
        $$(this).on('click', function() {
             var form = $$('<form  method=post></form>').attr('action', $$(this).attr('href')).get(0);
            $$(document.body).append(form);
            form.submit();
            $$(form).remove();
            //window.location.href=$$(this).attr('href')+"?s"
           
            return false;
        });
    });
	$$('.file .dl').each(function () {
        $$(this).on('click', function () {
       
            var form = $$('<form target=_blank method=post></form>').attr('action', $$(this).attr('href')).get(0);
            $$(document.body).append(form);
            form.submit();
            $$(form).remove();
            return false;
        });
    });
    
    
    
    
    
    
	$$('.fk .fl').each(function () {
        $$(this).on('click', function () {
            var u=$$(this).attr('href');
      
    
            var form = $$('<form  method=post></form>').attr('action', $$(this).attr('href')).get(0);
            $$(document.body).append(form);
            form.submit();
            $$(form).remove();
            return false;
        });
    });
 
    
    
    
    
    
    
    
    
}); 











window.TC=window.TC||{};




$$(".file .audio").on('click',(function(e){
            e.preventDefault();
           TC.preview_audio(this);
}));
TC.preview_audio = function(aud){
    if(!TC.aplayer){
        TC.aplayerList=[];
       $$(".file .audio").each(function(){
            var ext = $$(this).data("readypreview");
                var n = $$(this).find("span").text();
                var l = n.replace("."+ext,".lrc");
                var la = $$('a[data-name="'+l+'"]');
                var lrc = undefined;
                if(la.length>0){
                    lrc = la[0].href+"?s";
                }
                TC.aplayerList.push({
                    name:n,
                    url:this.href,
                    artist:" ",
                    lrc:lrc
                });
        })
        $$('<div id="aplayer">').appendTo("body");
        TC.aplayer = new APlayer({
            container: document.getElementById('aplayer'),
            fixed: true,
            audio: TC.aplayerList,
            lrcType: 3
        });
    }
    var k=-1;
    for(var i in TC.aplayerList){
        if(TC.aplayerList[i].name==$$(aud).data("name")){
            k=i;
            break;
        }
    }
    if(k>=0){
        TC.aplayer.list.switch(k);
        TC.aplayer.play();
        TC.aplayer.setMode("normal");
    }
}
	

$$.fn.extend({
    sortElements: function (comparator, getSortable) {
        getSortable = getSortable || function () { return this; };

        var placements = this.map(function () {
            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function () {
                parentNode.insertBefore(this, nextSibling);
                parentNode.removeChild(nextSibling);
            };
        });

        return [].sort.call(this, comparator).each(function (i) {
            placements[i].call(getSortable.call(this));
        });
    }
});
var lightbox = GLightbox();
function downall() {
     let dl_link_list = Array.from(document.querySelectorAll("li a"))
         .map(x => x.href) // 所有list中的链接
         .filter(x => x.slice(-1) != "/"); // 筛选出非文件夹的文件下载链接

     let blob = new Blob([dl_link_list.join("\r\n")], {
         type: 'text/plain'
     }); // 构造Blog对象
     let a = document.createElement('a'); // 伪造一个a对象
     a.href = window.URL.createObjectURL(blob); // 构造href属性为Blob对象生成的链接
     a.download = "folder_download_link.txt"; // 文件名称，你可以根据你的需要构造
     a.click() // 模拟点击
     a.remove();
}

function thumb(){
	if($$('#thumb i').text() == "apps"){
		$$('#thumb i').text("format_list_bulleted");
$$('.nexmoe-item').removeClass('thumb');
		$$('.nexmoe-item .mdui-list-item').css("background","");
	}else{
		$$('#thumb i').text("apps");
		$$('.mdui-col-xs-12 i.mdui-icon').each(function(){
			if($(this).text() == "image" || $$(this).text() == "ondemand_video"){
				var href = $$(this).parent().parent().attr('href');
				var thumb =(href.indexOf('?') == -1)?'?t=220':'&t=220';
				$$(this).hide();
				$$(this).parent().parent().parent().css("background","url("+href+thumb+")  no-repeat center top");
			}
		});
	}

}	

$$(function(){


	$$('.icon-sort').on('click', function () {
        let sort_type = $$(this).attr("data-sort"), sort_order = $$(this).attr("data-order");
        let sort_order_to = (sort_order === "less") ? "more" : "less";

        $$('li[data-sort]').sortElements(function (a, b) {
            let data_a = $$(a).attr("data-sort-" + sort_type), data_b = $$(b).attr("data-sort-" + sort_type);
            let rt = data_a.localeCompare(data_b, undefined, {numeric: true});
            return (sort_order === "more") ? 0-rt : rt;
        });

        $$(this).attr("data-order", sort_order_to).text("expand_" + sort_order_to);
    });

  	
  
});
  
var ckname='image_mode';
function getCookie(name) 
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return unescape(arr[2]); 
    else
        return null; 
} 
function setCookie(key,value,day){
	var exp = new Date(); 
	exp.setTime(exp.getTime() - 1); 
	var cval=getCookie(key); 
	if(cval!=null) 
	document.cookie= key + "="+cval+";expires="+exp.toGMTString(); 
	var date = new Date();
	var nowDate = date.getDate();
	date.setDate(nowDate + day);
	var cookie = key+"="+value+"; expires="+date;
	document.cookie = cookie;
	return cookie;
}
$$('#image_view').on('click', function () {
	if($(this).prop('checked') == true){
		setCookie(ckname,1,1);
		window.location.href=window.location.href;
	}else{
		setCookie(ckname,0,1);
		window.location.href=window.location.href;
	}
});
  
  function checkall(){
    var box = document.getElementById("sellall");
            var loves = document.getElementsByName("itemid");
             if(box.checked == false){
                 for (var i = 0; i < loves.length; i++) {
                     loves[i].checked = false;
                 }
             }else{
                 for (var i = 0; i < loves.length; i++) {
                     loves[i].checked = true;
                     }
             }
  onClickHander();
  }
  
 
  function onClickHander(){
            
 obj = document.getElementsByName("itemid");
    check_val = [];
    for(k in obj){
        if(obj[k].checked)
            check_val.push(obj[k].value);
    }
    //alert(check_val);
console.log(check_val);
         
             
             if(check_val!=""){
                 var div=document.getElementById("mangger");
		div.style.display='block';
                 
             }else{
                   var div=document.getElementById("mangger");
		div.style.display='none';
             };
             
             
             
             
        }
 
 
 
 
  
    
     function sellcheckbox()
{ obj = document.getElementsByName("itemid");

    check_val = [];
    for(k in obj){
        if(obj[k].checked)
            check_val.push(obj[k].value);
    }
    //alert(check_val);
console.log(check_val);
 data=JSON.stringify(check_val);


console.log(data);

}
  
  /////////////重建缓存
 function deldel(){
      
      Cookies.remove('moveitem')
    var xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
    xhr.addEventListener("readystatechange", function() {
    if(this.readyState === 4) {
    console.log(this.responseText);
    location.reload();
     }
    });

xhr.open("GET", "/del.php");
 xhr.send();
}
  
  function logout() {
        document.cookie = "admin=; path=/";
        location.href = location.href;
    }