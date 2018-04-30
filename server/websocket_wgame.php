<?php

/**
 * websocket server
 * @author wxj
 */
$ws = new swoole_websocket_server("0.0.0.0", 9502);

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    //var_dump($request->fd, $request->get, $request->server);
    echo "client-{$request->fd} is open\n";
    $ws->push($request->fd, "欢迎来到ICUGAME聊天室");
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    //echo "Message: {$frame->data}\n";
    echo "Message: " . gettype($frame->data) . "\n";
    $push_data = [
        'data' => urlToLink($frame->data),
        'append' => 'add',
        'type' => 'text',
    ];
    // $ws->connections 遍历所有websocket连接用户的fd，给所有用户推送
    foreach ($ws->connections as $fd) {
        if ($frame->fd != $fd) {
            $ws->push($fd, json_encode($push_data));
        }
    }
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();

