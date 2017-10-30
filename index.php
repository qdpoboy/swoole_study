<?php ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet">\
        <link href="./static/css/app.css" rel="stylesheet">
        <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
        <meta name="description" content="icugame聊天室，基于swoole的websocket聊天室">
        <meta name="keywords" content="icugame聊天室">
        <title>icugame聊天室</title>
    </head>
    <script>
        $(function () {
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
                    var retrieve_data = jQuery.parseJSON(evt.data);
                    var html_append = '<p class="c-p c-left"><img src="./static/images/2.jpg" class="avator"><span class="cp-span">' + retrieve_data.data + '</span></p>'
                    $('.content').append(html_append);
                    $(window).scrollTop($(window).height()); 
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
                    alert("输入格式有误");
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
                    <form class="form-inline" onsubmit="return false;">
                        <div class="form-group">
                            <label for="con">输入框：</label>
                            <input type="text" class="form-control chat-con" id="con" placeholder="请在此输入聊天内容，回车即可发送。">
                        </div>
                        <button type="button" class="btn btn-primary" id="send">发送</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
