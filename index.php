<?php ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet">\
        <link href="./static/css/app.css" rel="stylesheet">
        <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
        <meta name="description" content="">
        <meta name="keywords" content="">
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
                var retrieve_data = evt.data;
                console.log(retrieve_data);
                var html_append =  '<p class="c-p c-left"><img src="./static/images/2.jpg" class="avator"><span class="cp-span">'++'</span></p>'
            };

            websocket.onerror = function (evt, e) {
                console.log('报错: ' + evt.data);
            };
            $("#send").click(function () {
                var con = $("#con").val();
                if (con) {
                    websocket.send(con);
                    $("#con").val("");
                    var html_append = '<p class="c-p c-right"><span class="cp-span">'+con+'</span><img src="./static/images/2.jpg" class="avator"></p>'
                    $(".content").append(html_append);
                } else {
                    alert("输入格式有误");
                }
            });
        });

    </script>
    <body>
        <div class="container">
            <div class="webim">
                <div class="content">
                    <p class="c-p c-left">
                        <img src="./static/images/2.jpg" class="avator">
                        <span class="cp-span">12312123创吧的把失败的</span>
                    </p>
                    <p class="c-p c-right">
                        <span class="cp-span">12312123创吧的把失败的</span>
                        <img src="./static/images/2.jpg" class="avator">
                    </p>
                </div>
                <div class="chatting navbar-fixed-bottom">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="con">输入框：</label>
                            <input type="text" class="form-control chat-con" id="con" placeholder="请在此输入聊天内容">
                        </div>
                        <button type="button" class="btn btn-primary" id="send">发送</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
