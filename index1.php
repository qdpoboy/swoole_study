<?php 
if(isset($_COOKIE['nick'])){
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="./static/css/app.css" rel="stylesheet">
        <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <meta name="description" content="icugame聊天室，基于swoole的websocket聊天室">
        <meta name="keywords" content="icugame聊天室">
        <title>icugame聊天室</title>
    </head>
    <script>
        $(function () {
            //调浏览器的消息通知弹窗
            Notification.requestPermission(function(status){
                if(Notification.permission !== status){
                    Notification.permission = status;
                }
            });
            
            var wsServer = 'ws://23.105.213.196:9502';
            var websocket = new WebSocket(wsServer);
            websocket.onopen = function (evt) {
                console.log("已连接WebSocket服务");
            };

            websocket.onclose = function (evt) {
                console.log("链接已断开");
            };

            websocket.onmessage = function (evt) {
                if (!evt.data.match("^\{(.+:.+,*){1,}\}$")) {
                    //普通字符串处理
                    var html_append = '<p class="c-p c-center"><span class="cp-span">' + evt.data + '</span></p>';
                    $('.content').append(html_append);
                } else {
                    //通过这种方法可将字符串转换为对象
                    var retrieve_data = $.parseJSON(evt.data);
                    var html_append = '<p class="c-p c-left"><img src="./static/images/2.jpg" class="avator"><span class="cp-span">' + retrieve_data.data + '</span></p>'
                    $('.content').append(html_append);
                    $(window).scrollTop($(window).height());
                    
                    //visible (表明页面为浏览器当前激活tab，而且窗口不是最小化状态)，hidden (页面不是当前激活tab页面，或者窗口最小化了。)，prerender (页面在重新生成，对用户不可见。)
                    if(document.visibilityState == 'hidden'){
                        var t = new Date().toLocaleString();
                        var options={
                            dir: "ltr",
                            lang: "utf-8",
                            icon: "./static/images/2.jpg",
                            body: retrieve_data.data
                        };
                        if(Notification && Notification.permission === "granted"){
                            var n = new Notification("某某某: "+ t, options);    
                            n.onshow = function(){
                                console.log("You got me!");
                            };
                            n.onclick = function() {
                                console.log("You clicked me!");
                            };
                            n.onclose = function(){
                                console.log("notification closed!");
                            };
                            n.onerror = function() {
                                console.log("An error accured");
                            };
                        }else if(Notification && Notification.permission !== "denied") {
                            Notification.requestPermission(function(status){
                                if(Notification.permission !== status){
                                    Notification.permission = status;
                                }
//                                if(status === "granted"){
//                                    for(var i = 0; i < 3; i++){
//                                        var n = new Notification("Hi! " + i, {
//                                            tag: "Beyoung",
//                                            icon: "http://ihuster.com/static/avatar/b_default.png",
//                                            body: "你好呀，我是第" + i +"条消息啦！"
//                                        });
//                                    }
//                                }
                            });
                        }else{
                            alert("Hi!");
                        }
                    }
                }
            };

            websocket.onerror = function (evt, e) {
                console.log('报错: ' + evt.data);
            };
            $("#send").click(function () {
                send_to_server();
            });
            $("#con").keydown(function (event) {
                if (event.keyCode == 13) {
                    send_to_server();
                }
            });
            function send_to_server(){
                var con = $("#con").val();
                if (con) {
                    websocket.send(con);
                    $("#con").val("");
                    var html_append = '<p class="c-p c-right"><span class="cp-span">' + con + '</span><img src="./static/images/2.png" class="avator"></p>'
                    $(".content").append(html_append);
                    $(window).scrollTop($(window).height()); 
                } else {
                    $('#con').popover('show');
                    setTimeout("$('#con').popover('destroy')", 2000);
                }
            }
        });

    </script>
    <body>
        <div class="container">
            <div class="webim">
                <div class="content">
                </div>
                <div class="chatting navbar-fixed-bottom">
                    <input type="text" name="nickname" id="nickname" class="nickname" placeholder="请输入昵称">
                    <form class="form-inline" onsubmit="return false;">
                        <div class="form-group">
                            <label for="con">输入框：</label>
                            <input type="text" class="form-control chat-con" id="con" placeholder="请在此输入聊天内容，回车即可发送。" data-placement="top" data-toggle="popover" data-trigger="focus" title="提示" data-content="聊天的内容竟然为空，简直不敢相信~">
                        </div>
                        <button type="button" class="btn btn-primary" id="send">发送</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
}else{
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="./static/css/app.css" rel="stylesheet">
        <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <meta name="description" content="icugame聊天室，基于swoole的websocket聊天室">
        <meta name="keywords" content="icugame聊天室">
        <title>icugame聊天室</title>
        <script>
            $(function () {
                $("#nickname").keydown(function (event) {
                    if (event.keyCode == 13) {
                        var nickname = $(this).val();
                        if(nickname){
                            
                        }else{
                            alert("昵称不能为空!");
                        }
                    }
                });
            });
        </script>
    </head>
    <body>
        <div class="container">
            <div class="login">
                <input type="text" name="nickname" id="nickname" class="nickname" placeholder="请输入昵称">
            </div>
        </div>
    </body>
</html>
<?php
}
?>