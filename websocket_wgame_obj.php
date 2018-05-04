<?php

/**
 * websocket server
 * @author wxj
 */
define('DEBUG', 'on');
//开启服务器模式，而非web请求模式
define('SWOOLE_SERVER', true);
define("WEBPATH", str_replace("\\", "/", __DIR__));
require __DIR__ . '/libs/lib_config.php';

class wwebsocket {

    const PORT = 9502;

    private $ws;
    private $frame;
    private $actions;

    public function __construct() {
        $this->init();
    }

    public function init() {
        $this->actions = [
            'fight' => ['run'],
        ];
        $this->ws = new swoole_websocket_server("0.0.0.0", self::PORT);
        $this->ws->set([
            'task_worker_num' => 1,
            'heartbeat_idle_time' => 600,
            'heartbeat_check_interval' => 60,
        ]);
        //check_interval可以不设置，但是idle_time必须设置，假设链接的信息包里有一个属性idle用来判断链接是否在线（0在线/1不在线），在链接连上来的时候idle为0，从链接最后一次开始算起，如果在idle_time时间里没有向服务器发送数据，则idle的状态改为1。
        //check_interval一直在轮循，每次轮循的时候都查看一下idle的值，如果某个链接的值为1则断掉链接。
        //这两个配置为交叉项：如check设为10秒，idle_time设为40s。
        //链接40s没有向服务器发送数据，他的idle属性改为1，这个属性改为1的时候，可能刚刚check完毕，所以不会立即T掉链接，因为在刚刚轮循的时候链接的idle还没有变成0，只能等到下次轮循的时候才会把idle变成1的该链接T掉。
        //以上是真个swoole的心跳机制
        $this->ws->on('open', [$this, 'open']);
        $this->ws->on('message', [$this, 'message']);
        $this->ws->on('close', [$this, 'close']);
        $this->ws->on('task', [$this, 'task']);
        $this->ws->on('finish', [$this, 'finish']);
        $this->ws->start();
    }

    public function open($ws, $request) {
        echo "client-{$request->fd} is open\n";
        //var_dump($request);
    }

    public function message($ws, $frame) {
        $this->frame = $frame;
        //Swoole::$php->router(array($this, 'router'));
        //Swoole::$php->runMVC();
        //$response = Swoole::$php->runMVC();
        //$this->ws->push($frame->fd, $response);
    }

    public function router() {
        $get_data = json_decode($this->frame->data, true);
        $controller = $this->actions[$get_data['c']];
        if ($controller) {
            $mvc['controller'] = $get_data['c'];
            if (in_array($get_data['v'], $controller)) {
                $mvc['view'] = $get_data['v'];
            }
        }
        $mvc['param'] = [
            'ws' => $this->ws,
            'frame' => $this->frame,
        ];
        return $mvc;
    }

    public function close($ws, $fd) {
        echo "client-{$fd} is closed\n";
    }

    public function task() {
        
    }

    public function finish() {
        
    }

}

new wwebsocket();
