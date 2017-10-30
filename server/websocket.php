<?php

/**
 * websocket server
 * @author wxj
 */
$ws = new swoole_websocket_server("0.0.0.0", 9502);

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    //var_dump($request->fd, $request->get, $request->server);
    $ws->push($request->fd, "hello, welcome\n");
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    //echo "Message: {$frame->data}\n";
    $push_data = [
        'data' => $frame->data,
        'append' => 'add',
    ];
    // $ws->connections 遍历所有websocket连接用户的fd，给所有用户推送
    foreach ($ws->connections as $fd) {
        if ($frame->fd != $fd) {
            $this->server->push($fd, json_encode($push_data));
        }
    }
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();

