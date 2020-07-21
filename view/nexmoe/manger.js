/* Clipboard.js */
!function(e){if("object"==typeof exports&&"undefined"!=typeof module)module.exports=e();else if("function"==typeof define&&define.amd )define([],e);else{("undefined"!=typeof window?window:"undefined"!=typeof global?global:"undefined"!=typeof self?self:this).copyToClipboard=e()}}(function(){return function(){return function e(t,n,o){function r(a,i){if(!n[a]){if(!t[a]){var u="function"==typeof require&&require;if(!i&&u )return u(a,!0);if(c)return c(a,!0);var l=new Error("Cannot find module '"+a+"'");throw l.code="MODULE_NOT_FOUND",l}var s=n[a]={exports:{}};t[a][0].call(s.exports,function(e){return r(t[a][1][e]||e)},s,s.exports,e,t,n,o)}return n[a].exports}for(var c="function"==typeof require&&require ,a=0;a<o.length;a++)r(o[a]);return r}}()({1:[function(e,t,n){"use strict";var o=e("toggle-selection"),r="Copy to clipboard: #{key}, Enter";t.exports=function(e,t){var n,c,a,i,u,l,s=!1;t||(t={}),n=t.debug||!1;try{if(a=o(),i=document.createRange(),u=document.getSelection(),(l=document.createElement("span")).textContent=e,l.style.all="unset",l.style.position="fixed",l.style.top=0,l.style.clip="rect(0, 0, 0, 0)",l.style.whiteSpace="pre",l.style.webkitUserSelect="text",l.style.MozUserSelect="text",l.style.msUserSelect="text",l.style.userSelect="text",document.body.appendChild(l),i.selectNode(l),u.addRange(i),!document.execCommand("copy"))throw new Error("copy command was unsuccessful");s=!0}catch(o){n&&console.error("unable to copy using execCommand: ",o),n&&console.warn("trying IE specific stuff");try{window.clipboardData.setData("text",e),s=!0}catch(o){n&&console.error("unable to copy using clipboardData: ",o),n&&console.error("falling back to prompt"),c=function(e){var t=(/mac os x/i.test(navigator.userAgent)?"鈱�":"Ctrl")+"+C";return e.replace(/#{\s*key\s*}/g,t)}("message"in t?t.message:r),window.prompt(c,e)}}finally{u&&("function"==typeof u.removeRange?u.removeRange(i):u.removeAllRanges()),l&&document.body.removeChild(l),a()}return s}},{"toggle-selection":2}],2:[function(e,t,n){t.exports=function(){var e=document.getSelection();if(!e.rangeCount)return function(){};for(var t=document.activeElement,n=[],o=0;o <e.rangeCount;o++)n.push(e.getRangeAt(o));switch(t.tagName.toUpperCase()){case"INPUT":case"TEXTAREA":t.blur();break;default:t=null}return e.removeAllRanges(),function(){"Caret"===e.type&&e.removeAllRanges (),e.rangeCount||n.forEach(function(t){e.addRange(t)}),t&&t.focus ()}}},{}]},{},[1])(1)});



















var $$ = mdui.JQ;

var _wr = function (type) {
  var orig = history[type];
  return function () {
    var rv = orig.apply(this, arguments);
    var e = new Event(type);
    e.arguments = arguments;
    window.dispatchEvent(e);
    return rv;
  };
};


//监听鼠标右击事件 / 移动端长按事件
$$(document).on("contextmenu", function (e) {


  e.preventDefault(); //阻止冒泡，阻止默认的浏览器菜单

  //鼠标点击位置，相对于浏览器
  var _x = e.pageX,
    _y = e.pageY;

  let $div = $$("<div></div>").css({
    position: "absolute",
    top: _y + "px",
    left: _x + "px",
  });
  $$("body").append($div); //创建临时DOM

  // anchorSelector 表示触发菜单的元素的 CSS 选择器或 DOM 元素
  // menuSelector 表示菜单的 CSS 选择器或 DOM 元素
  // options 表示组件的配置参数，见下面的参数列表
  // 完整文档列表：https://doc.nowtime.cc/mdui/menu.html
  var instq = new mdui.Menu($div, "#menu");
  instq.open(); //打开菜单栏
  $div.remove(); //销毁创建的临时DOM

  console.log(e);
  console.log(e);
  e.target.id;
  console.log(e.path.a);
 // if ((e.target.id == "") | (e.target.id < 999999999999999)) {
  //  instq.close();
  //}

  Cookies.set("flieid", e.target.id, { expires: 0.025 });
  // console.log(e.relatedTarget.tagName);
  console.log(e.target.id);
});

function share() {
  var id = Cookies.get("flieid");
  var url = document.domain;
  // alert ("https://"+url+'/'+驱动器+"/?downid="+id);
  var ss = $$("#" + id).attr("data-sort-name");

  var me = document.URL + ss;
  navigator.clipboard.writeText(me).then(
    function () {
      alert("复制成功" + me);
      /* clipboard successfully set */
    },
    function () {
      /* clipboard write failed */
    }
  );
}

$$(function () {
    
    
    $$(".getlink-btn").on("click",function(){
	var dl_link_list = Array.from($$('a[data-readypreview]'))
        .map(x => x.href) 				  // 鎵€鏈塴ist涓殑閾炬帴
	copyToClipboard(dl_link_list.join("\r\n"));
	mdui.alert("复制成功");
})
  
    
    
    
    
    
    
    
    
    
  $$(".file .iframe").each(function () {
    $$(this).on("click", function () {
      var form = $$("<form  method=post></form>")
        .attr("action", $$(this).attr("href"))
        .get(0);
      $$(document.body).append(form);
      form.submit();
      $$(form).remove();
      //window.location.href=$$(this).attr('href')+"?s"

      return false;
    });
  });
  $$(".file .dl").each(function () {
    $$(this).on("click", function () {
      var form = $$("<form  method=post></form>")
        .attr("action", $$(this).attr("href"))
        .get(0);
      $$(document.body).append(form);
      form.submit();
      $$(form).remove();
      return false;
    });
  });

  $$(".folder .admin").each(function () {
    $$(this).on("click", function () {
      var u = $$(this).attr("href");

      var form = $$("<form  method=post></form>")
        .attr("action", $$(this).attr("href"))
        .get(0);
      $$(document.body).append(form);
      form.submit();
      $$(form).remove();
      return false;
    });
  });
});

window.TC = window.TC || {};

$$(".file .audio").on("click", function (e) {
  e.preventDefault();
  TC.preview_audio(this);
});
TC.preview_audio = function (aud) {
  if (!TC.aplayer) {
    TC.aplayerList = [];
    $$(".file .audio").each(function () {
      var ext = $$(this).data("readypreview");
      var n = $$(this).find("span").text();
      var l = n.replace("." + ext, ".lrc");
      var la = $$('a[data-name="' + l + '"]');
      var lrc = undefined;
      if (la.length > 0) {
        lrc = la[0].href + "?s";
      }
      TC.aplayerList.push({
        name: n,
        url: this.href,
        artist: " ",
        lrc: lrc,
      });
    });
    $$('<div id="aplayer">').appendTo("body");
    TC.aplayer = new APlayer({
      container: document.getElementById("aplayer"),
      fixed: true,
      audio: TC.aplayerList,
      lrcType: 3,
    });
  }
  var k = -1;
  for (var i in TC.aplayerList) {
    if (TC.aplayerList[i].name == $$(aud).data("name")) {
      k = i;
      break;
    }
  }
  if (k >= 0) {
    TC.aplayer.list.switch(k);
    TC.aplayer.play();
    TC.aplayer.setMode("normal");
  }
};

$$.fn.extend({
  sortElements: function (comparator, getSortable) {
    getSortable =
      getSortable ||
      function () {
        return this;
      };

    var placements = this.map(function () {
      var sortElement = getSortable.call(this),
        parentNode = sortElement.parentNode,
        nextSibling = parentNode.insertBefore(
          document.createTextNode(""),
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
  },
});
var lightbox = GLightbox();


function thumb() {
  if ($$("#thumb i").text() == "apps") {
    $$("#thumb i").text("format_list_bulleted");
    $$(".nexmoe-item").removeClass("thumb");
    $$(".nexmoe-item .mdui-list-item").css("background", "");
  } else {
    $$("#thumb i").text("apps");
    $$(".mdui-col-xs-12 i.mdui-icon").each(function () {
      if ($(this).text() == "image" || $$(this).text() == "ondemand_video") {
        var href = $$(this).parent().parent().attr("href");
        var thumb = href.indexOf("?") == -1 ? "?t=220" : "&t=220";
        $$(this).hide();
        $$(this)
          .parent()
          .parent()
          .parent()
          .css("background", "url(" + href + thumb + ")  no-repeat center top");
      }
    });
  }
}

$$(function () {
  $$(".icon-sort").on("click", function () {
    let sort_type = $$(this).attr("data-sort"),
      sort_order = $$(this).attr("data-order");
    let sort_order_to = sort_order === "less" ? "more" : "less";

    $$("li[data-sort]").sortElements(function (a, b) {
      let data_a = $$(a).attr("data-sort-" + sort_type),
        data_b = $$(b).attr("data-sort-" + sort_type);
      let rt = data_a.localeCompare(data_b, undefined, { numeric: true });
      return sort_order === "more" ? 0 - rt : rt;
    });

    $$(this)
      .attr("data-order", sort_order_to)
      .text("expand_" + sort_order_to);
  });
});

var ckname = "image_mode";
function getCookie(name) {
  var arr,
    reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
  if ((arr = document.cookie.match(reg))) return unescape(arr[2]);
  else return null;
}
function setCookie(key, value, day) {
  var exp = new Date();
  exp.setTime(exp.getTime() - 1);
  var cval = getCookie(key);
  if (cval != null)
    document.cookie = key + "=" + cval + ";expires=" + exp.toGMTString();
  var date = new Date();
  var nowDate = date.getDate();
  date.setDate(nowDate + day);
  var cookie = key + "=" + value + "; expires=" + date;
  document.cookie = cookie;
  return cookie;
}
$$("#image_view").on("click", function () {
  if ($(this).prop("checked") == true) {
    setCookie(ckname, 1, 1);
    window.location.href = window.location.href;
  } else {
    setCookie(ckname, 0, 1);
    window.location.href = window.location.href;
  }
});

function checkall() {
  var box = document.getElementById("sellall");
  var loves = document.getElementsByName("itemid");
  if (box.checked == false) {
    for (var i = 0; i < loves.length; i++) {
      loves[i].checked = false;
    }
  } else {
    for (var i = 0; i < loves.length; i++) {
      loves[i].checked = true;
    }
  }
  onClickHander();
}

function onClickHander() {
  obj = document.getElementsByName("itemid");
  check_val = [];
  for (k in obj) {
    if (obj[k].checked) check_val.push(obj[k].value);
  }
  //alert(check_val);
  console.log(check_val);

  if (check_val != "") {
    var div = document.getElementById("mangger");
    var div2= document.getElementById("navess");
    div2.style.display = "none";
    div.style.display = "block";
  } else {
    var div = document.getElementById("mangger");
    var div2= document.getElementById("navess");
    div.style.display = "none";
    div2.style.display = "block";
  }
}

function sellcheckbox() {
  obj = document.getElementsByName("itemid");

  check_val = [];
  for (k in obj) {
    if (obj[k].checked) check_val.push(obj[k].value);
  }
  //alert(check_val);
  console.log(check_val);
  data = JSON.stringify(check_val);

  console.log(data);
}

/////////////重建缓存
function deldel() {
  Cookies.remove("moveitem");
  var cache = window.location.pathname;
  if (cache == "/") {
    cache = "/default/";
  }
      var config = {
        method: "CACHE",
        url: cache,
        headers: {
          "Cache-Control": "no-cache",
        },
      };
      axios(config)
.then(function (response) {
  console.log(JSON.stringify(response.data));
    location.reload();
})
.catch(function (error) {
  console.log(error);
});


}

function logout() {
  document.cookie = "admin=; path=/";
  location.href = location.href;
}

////////////////////////////////////////////////////////
axios.interceptors.request.use(
  function (config) {
     config.headers = {
     "Cache-Control": "no-cache",
   
   }
    // 在发送请求之前做些什么
    return config;
  },
  function (error) {
    // 对请求错误做些什么
    return Promise.reject(error);
  }
);

// 添加响应拦截器
axios.interceptors.response.use(
  function (response) {
    // 对响应数据做点什么
    return response;
  },
  function (error) {
    // 对响应错误做点什么
    if (error.response.status === 401) {
      alert("未登陆");
      location.href = "/login.php";
    }
    return Promise.reject(error);
  }
);

///////文件上传
function uploadfieone() {
  document.getElementById("upload_file").webkitdirectory = "";
  document.getElementById("upload_file").click();
}
//////文件夹上传
function uploadfietwo() {
  document.getElementById("upload_file").webkitdirectory = 1;
  document.getElementById("upload_file").click();
}
//新建文件夹axios-creat
function create_folder() {
  var 完整路径 = window.location.pathname;
  if (完整路径 == "/") {
    完整路径 = "/default/";
  }
  mdui.prompt(
    "新建文件夹",
    function (value) {
      var config = {
        method: "creat",
        url: 完整路径 + "?create_folder=" + value,
      };

      axios(config)
        .then(function (response) {
          deldel(); //更新缓存
          console.log(JSON.stringify(response));
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    function (value) {}
  );
}
//单文件删除fetch-delete
function delitem() {
  var id = Cookies.get("flieid");

  data = JSON.stringify(id);

  var myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");
  var raw = data;
  var requestOptions = {
    method: "DELETE",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  fetch("/" + 驱动器 + "/?action=dellist", requestOptions)
    .then((response) => response.text())
    .then((data) => {
      alert("删除成功");
      deldel(); //清空缓存
    })

    .then((result) => console.log(result))
    .catch((error) => console.log("error", error));
}

////批量删除文件函数fetch-delete
function dellistitem() {
  obj = document.getElementsByName("itemid");
  check_val = [];
  for (k in obj) {
    if (obj[k].checked) check_val.push(obj[k].value);
  }

  console.log(check_val);
  data = JSON.stringify(check_val);
  alert("确认删除" + data);
  var myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");
  var raw = data;
  var requestOptions = {
    method: "DELETE",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  fetch("/" + 驱动器 + "/?action=dellist", requestOptions)
    .then((response) => response.text())
    .then((data) => {
      alert(data);
      deldel(); //清空缓存
    })

    .then((result) => console.log(result))
    .catch((error) => console.log("error", error));
}

/////文件重命名   xhr-get
function renamebox() {
  mdui.prompt(
    "重命名",
    function (value) {
      var id = Cookies.get("flieid");
      var xhr4 = new XMLHttpRequest();
      xhr4.withCredentials = true;
      xhr4.addEventListener("readystatechange", function () {
        if (this.readyState === 4) {
          console.log(this.responseText);
          deldel();
        }
      });
      xhr4.open("RENAME", "/" + 驱动器 + "/?rename=" + id + "&name=" + value);
      xhr4.send();
      console.log(xhr4);
    },
    function (value) {}
  );
}

///剪切文件axios-
function moveitem() {
  obj = document.getElementsByName("itemid");

  check_val = [];
  for (k in obj) {
    if (obj[k].checked) check_val.push(obj[k].value);
  }
  //alert(check_val);
  console.log(check_val);
  data = JSON.stringify(check_val);
  alert(data);
  Cookies.set("moveitem", data, { expires: 0.025 });
}
function moveoneitem() {
     var id = Cookies.get("flieid");
     data=[id];
     alert(data);
   Cookies.set("moveitem", data, { expires: 0.025 });
}

///粘贴文件axios-get
function pastitem() {
  var url = location + "?this=path";
  var conf = {
    method: "path",
    url: url,
  };
  axios(conf)
    .then(function (response) {
      dataid = response.data;

      var move = Cookies.get("moveitem");
      if (typeof move == "undefined") {
        return false;
      }
      alert("粘贴" + move + "到" + dataid);

      var urls =
        "/" + 驱动器 + "/?filemanger=move&id=" + move + "&newid=" + dataid;

      var configs = {
        method: "move",
        url: urls,
      };
      axios(configs)
        .then(function (response) {
          deldel();
          console.log(response);
        })
        .catch(function (error) {
          console.log(error);
        });
    })
    .catch(function (error) {
      console.log(error);
    });
}

////////////////文件上传函数put

function ajaxLoading() {
  layer.msg("文件移动中请等待", {
    icon: 16,
    shade: 0.1,
  });
}

function ajaxLoadEnd() {
  layer.closeAll("loading");
}

function uploadbuttonhide() {
  document.getElementById("exampleDialog").style.display = "block";
}
function uploadkill() {
  document.getElementById("exampleDialog").style.display = "none";
  deldel();
}
function preup() {
  // uploadbuttonhide();
  var files = document.getElementById("upload_file").files;
  if (files.length < 1) {
    uploadbuttonhide();
    return;
  }
  var table1 = document.createElement("table");
  document.getElementById("upload_div").appendChild(table1);
  table1.setAttribute("class", "list-table");
  var timea = new Date().getTime();
  var i = 0;
  getuplink(i);
  function getuplink(i) {
    var file = files[i];
    var tr1 = document.createElement("tr");
    table1.appendChild(tr1);
    tr1.setAttribute("data-to", 1);
    var td1 = document.createElement("td");
    tr1.appendChild(td1);
    td1.setAttribute("style", "width:30%;word-break:break-word;");
    td1.setAttribute("id", "upfile_td1_" + timea + "_" + i);
    td1.innerHTML =
      (file.webkitRelativePath || file.name) + "<br>" + size_format(file.size);
    var td2 = document.createElement("td");
    tr1.appendChild(td2);
    td2.setAttribute("id", "upfile_td2_" + timea + "_" + i);
    if (file.size > 15 * 1024 * 1024 * 1024) {
      td2.innerHTML = '<font color="red">文件过大，终止上传。</font>';
      uploadbuttonshow();
      return;
    }
    upbigfilename = encodeURIComponent(file.webkitRelativePath || file.name);

    td2.innerHTML = "获取上传链接 ...";
    var xhr1 = new XMLHttpRequest();
    var 完整路径 = window.location.pathname;
    if (完整路径 == "/") {
      完整路径 = "/default/";
    }
    xhr1.open(
      "PUT",
      完整路径 +
        "?action=upbigfile&upbigfilename=" +
        upbigfilename +
        "&filesize=" +
        file.size +
        "&lastModified=" +
        file.lastModified
    );
    xhr1.setRequestHeader("x-requested-with", "XMLHttpRequest");
    xhr1.send(null);
    xhr1.onload = function (e) {
      td2.innerHTML = '<font color="red">' + xhr1.responseText + "</font>";
      if (xhr1.status == 200) {
        console.log(xhr1.responseText);
        var html = JSON.parse(xhr1.responseText);
        if (!html["uploadUrl"]) {
          td2.innerHTML =
            '<font color="red">' + xhr1.responseText + "</font><br>";
          uploadbuttonshow();
        } else {
          td2.innerHTML = "开始上传 ...";
          binupfile(file, html["uploadUrl"], timea + "_" + i, upbigfilename);
        }
      }
      if (xhr1.status == 409) {
        td2.innerHTML = "md5: " + filemd5;
        tdnum = timea + "_" + i;
        document.getElementById("upfile_td1_" + tdnum).innerHTML =
          '<div style="color:green"><a href="/' +
          upbigfilename +
          '" id="upfile_a_' +
          tdnum +
          '" target="_blank">' +
          document.getElementById("upfile_td1_" + tdnum).innerHTML +
          "</a>上传完成";
      }
      if (i < files.length - 1) {
        i++;
        getuplink(i);
      }
    };
  }
}
function size_format(num) {
  if (num > 1024) {
    num = num / 1024;
  } else {
    return num.toFixed(2) + " B";
  }
  if (num > 1024) {
    num = num / 1024;
  } else {
    return num.toFixed(2) + " KB";
  }
  if (num > 1024) {
    num = num / 1024;
  } else {
    return num.toFixed(2) + " MB";
  }
  return num.toFixed(2) + " GB";
}
function binupfile(file, url, tdnum, filename) {
  var label = document.getElementById("upfile_td2_" + tdnum);
  var reader = new FileReader();
  var StartStr = "";
  var MiddleStr = "";
  var StartTime;
  var EndTime;
  var newstartsize = 0;
  if (!!file) {
    var asize = 0;
    var totalsize = file.size;
    var xhr2 = new XMLHttpRequest();
    xhr2.open("GET", url);
    //xhr2.setRequestHeader('x-requested-with','XMLHttpRequest');
    xhr2.send(null);
    xhr2.onload = function (e) {
      if (xhr2.status == 200) {
        var html = JSON.parse(xhr2.responseText);
        var a = html["nextExpectedRanges"][0];
        newstartsize = Number(a.slice(0, a.indexOf("-")));
        StartTime = new Date();
        asize = newstartsize;
        if (newstartsize == 0) {
          StartStr = "开始于:" + StartTime.toLocaleString() + "<br>";
        } else {
          StartStr =
            "上次上传" +
            size_format(newstartsize) +
            "<br>本次开始于:" +
            StartTime.toLocaleString() +
            "<br>";
        }
        var chunksize = 5 * 1024 * 1024; // chunk size, max 60M. 每小块上传大小，最大60M，微软建议10M
        if (totalsize > 200 * 1024 * 1024) chunksize = 100 * 1024 * 1024;
        function readblob(start) {
          var end = start + chunksize;
          var blob = file.slice(start, end);
          reader.readAsArrayBuffer(blob);
        }
        readblob(asize);

        reader.onload = function (e) {
          var binary = this.result;
          var xhr = new XMLHttpRequest();
          xhr.open("PUT", url, true);
          //xhr.setRequestHeader('x-requested-with','XMLHttpRequest');
          bsize = asize + e.loaded - 1;
          xhr.setRequestHeader(
            "Content-Range",
            "bytes " + asize + "-" + bsize + "/" + totalsize
          );
          xhr.upload.onprogress = function (e) {
            if (e.lengthComputable) {
              var tmptime = new Date();
              var tmpspeed =
                (e.loaded * 1000) / (tmptime.getTime() - C_starttime.getTime());
              var remaintime = (totalsize - asize - e.loaded) / tmpspeed;
              label.innerHTML =
                StartStr +
                "上传 " +
                size_format(asize + e.loaded) +
                " / " +
                size_format(totalsize) +
                " = " +
                (((asize + e.loaded) * 100) / totalsize).toFixed(2) +
                "% 平均速度:" +
                size_format(
                  ((asize + e.loaded - newstartsize) * 1000) /
                    (tmptime.getTime() - StartTime.getTime())
                ) +
                "/s<br>即时速度 " +
                size_format(tmpspeed) +
                "/s 预计还要 " +
                remaintime.toFixed(1) +
                "s";
            }
          };
          var C_starttime = new Date();
          xhr.onload = function (e) {
            if (xhr.status < 500) {
              var response = JSON.parse(xhr.responseText);
              if (response["size"] > 0) {
                // contain size, upload finish. 有size说明是最终返回，上传结束
                var xhr3 = new XMLHttpRequest();
                xhr3.open(
                  "GET",
                  "?action=del_upload_cache&filelastModified=" +
                    file.lastModified +
                    "&filesize=" +
                    file.size +
                    "&filename=" +
                    filename
                );
                xhr3.setRequestHeader("x-requested-with", "XMLHttpRequest");
                xhr3.send(null);
                xhr3.onload = function (e) {
                  console.log(xhr3.responseText + "," + xhr3.status);
                };
                EndTime = new Date();
                MiddleStr = "结束于:" + EndTime.toLocaleString() + "<br>";
                if (newstartsize == 0) {
                  MiddleStr +=
                    "平均速度:" +
                    size_format(
                      (totalsize * 1000) /
                        (EndTime.getTime() - StartTime.getTime())
                    ) +
                    "/s<br>";
                } else {
                  MiddleStr +=
                    "本次平均速度:" +
                    size_format(
                      ((totalsize - newstartsize) * 1000) /
                        (EndTime.getTime() - StartTime.getTime())
                    ) +
                    "/s<br>";
                }
                document.getElementById("upfile_td1_" + tdnum).innerHTML =
                  '<div style="color:green"><a href="/' +
                  驱动器 +
                  "/" +
                  请求路径 +
                  (file.webkitRelativePath || response.name) +
                  '" id="upfile_a_' +
                  tdnum +
                  '" target="_blank">' +
                  document.getElementById("upfile_td1_" + tdnum).innerHTML +
                  '</a>';
                label.innerHTML = StartStr + MiddleStr;
                uploadbuttonshow();

                response.name = file.webkitRelativePath || response.name;
                addelement(response);
              } else {
                if (!response["nextExpectedRanges"]) {
                  label.innerHTML =
                    '<font color="red">' + xhr.responseText + "</font><br>";
                } else {
                  var a = response["nextExpectedRanges"][0];
                  asize = Number(a.slice(0, a.indexOf("-")));
                  readblob(asize);
                }
              }
            } else readblob(asize);
          };
          xhr.send(binary);
        };
      } else {
        if (
          window.location.pathname.indexOf("%23") > 0 ||
          filename.indexOf("%23") > 0
        ) {
          label.innerHTML =
            '<font color="red">目录或文件名含有#，上传失败。</font>';
        } else {
          label.innerHTML =
            '<font color="red">' + xhr2.responseText + "</font>";
        }

        uploadbuttonshow();
      }
    };
  }
}

function operatediv_close(operate) {
  document.getElementById(operate + "_div").style.display = "none";
  document.getElementById("mask").style.display = "none";
}
