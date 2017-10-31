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

function urlToLink($str) {
    $arr = array("www." => "http://www.");
    $str = strtr($str, $arr);
    $arr = array("http://http://" => "http://");
    $str = strtr($str, $arr);
    $str2 = explode("http://", $str);
    $url  = '';
    for ($n = 1; isset($str2[$n]); $n ++) {
        $str3 = explode(".", $str2[$n]);
        if (isset($str3[1])) {
            $str4 = explode("www.", $str2[$n]);
            if ((isset($str4[1]) && isset($str3[2])) || !isset($str4[1])) {
                $length = strlen($str2[$n]);
                for ($i = 0; $i <= $length; $i ++) {
                    //从空格断开 
                    if (($i - 1) == strlen(trim(mb_substr($str2[$n], 0, $i, 'gb2312')))) {
                        $ii = $i - 1;
                        $url1 = mb_substr($str2[$n], 0, $ii, 'gb2312');
                        $url2 = mb_substr($str2[$n], $ii, $length, 'gb2312');
                        $url3 = "<a href=\"http://" . $url1 . "\" target=\"_blank\">http://" . $url1 . "</a>" . $url2;
                        break;
                    }

                    //从出现汉字处断开 
                    if ($i != strlen(mb_substr($str2[$n], 0, $i, 'gb2312'))) {
                        $ii = $i - 1;
                        $url1 = mb_substr($str2[$n], 0, $ii, 'gb2312');
                        $url2 = mb_substr($str2[$n], $ii, $length, 'gb2312');
                        $url3 = "<a href=\"http://" . $url1 . "\" target=\"_blank\">http://" . $url1 . "</a>" . $url2;
                        break;
                    }
                    if ($i == $length) {
                        $url3 = "<a href=\"http://" . $str2[$n] . "\" target=\"_blank\">http://" . $str2[$n] . "</a>";
                    }
                }
            } else {
                $url3 = "http://" . $str2[$n];
            }
        } else {
            $url3 = "http://" . $str2[$n];
        }
        $url .= $url3;
    }
    if (substr($str, 0, 7) == "http://") {
        $url = "<a href=\"http://$str2[0]\" target=\"_blank\">" . $str2[0] . "</a>" . $url;
    } else {
        $url = $str2[0] . $url;
    }
    return $url;
}
